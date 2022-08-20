<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Item;
use App\Models\Inventory\ItemCategory;
use App\Traits\Categories;
use App\Traits\FileUpload;
use App\Traits\Financial;
use IFRS\Models\ReportingPeriod;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
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
    public function index($request)
    {
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 20;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $data_status = isset($request->dataStatus) ? (string)$request->dataStatus : 'open';

        $search = isset($request->q) ? (string)$request->q : '';
        $select_data = isset($request->selectData) ? (string)$request->selectData : 'name';

        $query = Item::selectRaw("
                items.*,
                CASE
                    WHEN items.item_group_id = 1 THEN 'Inventory'
                    WHEN items.item_group_id = 2 THEN 'Non inventory'
                    WHEN items.item_group_id = 3 THEN 'Service'
                    WHEN items.item_group_id = 4 THEN 'Bundle'
                END as group_name
            ")
            ->with([
                'category',
                'salesAccount',
                'purchaseAccount',
                'inventoryAccounts',
                'salesTax',
                'purchaseTax',
                'itemWarehouse',
            ])
            ->where(DB::raw("CONCAT(name, ' ', code)"), 'LIKE', '%' . $search . '%')
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        return $query;
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
        Arr::forget($data, 'sales_tax');
        Arr::forget($data, 'purchase_tax');
        Arr::forget($data, 'contact');
        Arr::forget($data, 'inventory_accounts');
        Arr::forget($data, 'whs_name');
        Arr::forget($data, 'item_group');
        Arr::forget($data, 'available_qty');
        Arr::forget($data, 'item_warehouse');

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
                $this->generateDocNum(date('Y-m-d H:i:s'), 'IT');
        }

        return $data;
    }

    /**
     * @param $sysDate
     * @param $alias
     *
     * @return string
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    protected function generateDocNum($sysDate, $alias): string
    {
        $data_date = strtotime($sysDate);

        $day_val = date('j', $data_date);

        $alias = Str::limit($alias, 2);

        $data_date = strtotime($sysDate);
        $month = date('m', $data_date);

        $entity = Auth::user()->entity;
        $periodCount = ReportingPeriod::getPeriod($sysDate, $entity)->period_count;
        $periodStart = ReportingPeriod::periodStart($sysDate, $entity);
        $periodStart = date('Y-m-d', strtotime($periodStart));

        $nextId = Item::where('created_at', '>=', $periodStart)
            ->count();

        $nextId = $nextId + 1;

        return $alias . '-' . str_pad((string)$periodCount, 2, '0', STR_PAD_LEFT)
            . $month .
            str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);
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
            //->whereBetween(DB::raw('CONVERT(created_at, date)'), [$first_date, $second_date])
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
     * @return mixed
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
