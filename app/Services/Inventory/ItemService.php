<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Item;
use App\Models\Inventory\ItemCategory;
use App\Traits\Categories;
use App\Traits\FileUpload;
use App\Traits\Financial;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemService
{
    use FileUpload;
    use Categories;
    use Financial;

    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $pagination = (object)$request->options;
        $pages = isset($pagination->page) ? (int)$pagination->page : 1;
        $row_data = isset($pagination->itemsPerPage) ? (int)$pagination->itemsPerPage : 20;
        $sorts = isset($pagination->sortBy[0]) ? (string)$pagination->sortBy[0] : 'name';
        $order = isset($pagination->sortDesc[0]) ? 'DESC' : 'asc';
        $data_status = isset($request->dataStatus) ? (string)$request->dataStatus : 'open';

        $search = isset($request->q) ? (string)$request->q : '';
        $select_data = isset($request->selectData) ? (string)$request->selectData : 'name';
        $offset = ($pages - 1) * $row_data;

        $result = [];
        $query = Item::selectRaw("
                items.*,
                sell_tax.name as sell_tax_name,
                buy_tax.name as buy_tax_name,
                sale_account.name as sales_account,
                buy_account.name as purchase_account,
                inventory_accounts.name as inventory_account_name,
                'actions' as ACTIONS,
                CASE
                    WHEN items.item_group_id = 1 THEN 'Inventory'
                    WHEN items.item_group_id = 2 THEN 'Non inventory'
                    WHEN items.item_group_id = 3 THEN 'Service'
                    WHEN items.item_group_id = 4 THEN 'Bundle'
                END as group_name
            ")
            ->with(['category'])
            ->leftJoin('accounts as sale_account', 'sale_account.id', 'items.sell_account_id')
            ->leftJoin('accounts as buy_account', 'buy_account.id', 'items.buy_account_id')
            ->leftJoin('accounts as inventory_accounts', 'inventory_accounts.id', 'items.inventory_account')
            ->leftJoin('taxes as sell_tax', 'sell_tax.id', 'items.sell_tax_id')
            ->leftJoin('taxes as buy_tax', 'buy_tax.id', 'items.buy_tax_id');

        $result['total'] = $query->count();

        $all_data = $query->offset($offset)
            ->orderBy($sorts, $order)
            ->limit($row_data)
            ->get();

        return array_merge($result, [
            'rows' => $all_data,
        ]);
    }

    /**
     * @param $request
     * @param $type
     * @param null $id
     * @return array
     */
    public function formData($request, $type, $id = null): array
    {
        $request->mergeIfMissing([
            'entity_id' => auth()->user()->entity_id,
        ]);
        $data = $request->all();

        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');
        Arr::forget($data, 'category');
        Arr::forget($data, 'categories');
        Arr::forget($data, 'sales_account');
        Arr::forget($data, 'purchase_account');
        Arr::forget($data, 'ACTIONS');
        Arr::forget($data, 'average_price');
        Arr::forget($data, 'last_buy_price');
        Arr::forget($data, 'sell_tax_name');
        Arr::forget($data, 'buy_tax_name');
        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'group_name');
        Arr::forget($data, 'inventory_account_name');

        $data['image'] = '';
        $data['buy_tax_id'] = (isset($request->buy_tax_id)) ? $request->buy_tax_id : 0;
        $data['quantity'] = (isset($request->quantity)) ? $request->quantity : 0;
        $data['minimum_stock'] = (isset($request->minimum_stock)) ? $request->minimum_stock : 0;
        $data['tract_stock'] = (isset($request->tract_stock)) ? $request->tract_stock : 0;
        $data['enabled'] = (isset($request->enabled)) ? $request->enabled : true;
        $data['sell_tax_id'] = (isset($request->sell_tax_id)) ? $request->sell_tax_id : 0;

        if ($type == 'store') {
            $data['created_by'] = $request->user()->id;
            $data['code'] = (isset($request->code)) ? $request->code :
                $this->generateDocNum(date('Y-m-d H:i:s'), 'ITM');
        }

        return $data;
    }

    /**
     * @param $sysDate
     * @param $alias
     * @return string
     */
    protected function generateDocNum($sysDate, $alias): string
    {
        $data_date = strtotime($sysDate);

        $day_val = date('j', $data_date);

        if ((int)$day_val === 1) {
            $document = Str::upper($alias) . '-' . sprintf('%05s', '1');
            $check_document = Item::where('code', '=', $document)->first();
            if (!$check_document) {
                return Str::upper($alias) . '-' . sprintf('%05s', '1');
            } else {
                //ITM-xxxxx
                return $this->itemCode($data_date, $alias);
            }
        }

        return $this->itemCode($data_date, $alias);
    }

    /**
     * @param $data_date
     * @param $alias
     * @return string
     */
    protected function itemCode($data_date, $alias): string
    {
        $year_val = date('y', $data_date);
        $full_year = date('Y', $data_date);
        $month = date('m', $data_date);
        $end_date = date('t', $data_date);

        $first_date = "${full_year}-${month}-01";
        $second_date = "${full_year}-${month}-${end_date}";

        $doc_num = Item::selectRaw('code')
            ->whereBetween(DB::raw('CONVERT(created_at, date)'), [$first_date, $second_date])
            ->orderBy('code', 'DESC')
            ->first();

        $number = empty($doc_num) ? '0000000000' : $doc_num->code;
        $clear_doc_num = (int)substr($number, 4, 9);
        $number = $clear_doc_num + 1;

        return Str::upper($alias) . '-' . sprintf('%05s', $number);
    }

    /**
     * @param $item
     * @param $request
     * @param $int
     * @return int|mixed|null
     */
    public function checkItem($item, $request, $int): mixed
    {
        $default_int = ($int) ? 0 : null;

        return (isset($request->$item)) ? $request->$item : $default_int;
    }

    /**
     * @param $category
     * @param $item_id
     * @return void
     */
    public function processItemCategory($category, $item_id)
    {
        if ($category) {
            foreach ($category as $item) {
                ItemCategory::updateOrCreate([
                    'category_id' => $this->categoryIdByName($item),
                    'item_id' => $item_id,
                ]);
            }
        }
    }
}
