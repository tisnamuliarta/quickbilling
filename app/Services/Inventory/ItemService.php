<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Item;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemService
{
    use FileUpload;

    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $pagination = (object)$request->pagination;
        $pages = isset($pagination->page) ? (int)$pagination->page : 1;
        $row_data = isset($pagination->itemsPerPage) ? (int)$pagination->itemsPerPage : 20;
        $sorts = isset($pagination->sortBy[0]) ? (string)$pagination->sortBy[0] : 'name';
        $order = isset($pagination->sortDesc[0]) ? 'ASC' : 'DESC';
        $data_status = isset($request->dataStatus) ? (string)$request->dataStatus : 'open';

        $search = isset($request->q) ? (string)$request->q : '';
        $select_data = isset($request->selectData) ? (string)$request->selectData : 'name';
        $offset = ($pages - 1) * $row_data;

        $result = array();
        $query = Item::selectRaw("*, 'actions' as ACTIONS");

        $result["total"] = $query->count();

        $all_data = $query->offset($offset)
            ->orderBy($sorts, $order)
            ->limit($row_data)
            ->get();

        $result = array_merge($result, [
            "rows" => $all_data,
        ]);

        return $result;
    }

    /**
     * @param $request
     * @param $type
     * @return array
     */
    public function formData($request, $type): array
    {
        $fileName = null;
        if ($request->hasFile('image_temp')) {
            $file = $request->file('image_temp');

            $fileName = $this->fileName($file);
        }
        $data = [
            'name' => $request->name,
            'image' => isset($fileName) ? $fileName : '',
            'company_id' => session('company_id'),
            'item_group_id' => $this->checkItem('item_group_id', $request, true),
            'sale_price' => $this->checkItem('sale_price', $request, true),
            'purchase_price' => $this->checkItem('purchase_price', $request, true),
            'quantity' => $this->checkItem('quantity', $request, true),
            'minimum_stock' => $this->checkItem('minimum_stock', $request, true),
            'tract_stock' => $this->checkItem('tract_stock', $request, true),
            'buy_tax_id' => $this->checkItem('buy_tax_id', $request, false),
            'sell_tax_id' => $this->checkItem('sell_tax_id', $request, false),
            'buy_account_id' => $this->checkItem('buy_account_id', $request, false),
            'sell_account_id' => $this->checkItem('sell_account_id', $request, false),
            'inventory_account' => $this->checkItem('inventory_account', $request, false),
            'code' => (isset($request->code)) ? $request->code : $this->generateDocNum(date('Y-m-d H:i:s'), 'ITM'),
            'unit' => $this->checkItem('unit', $request, false),
            'description' => $this->checkItem('description', $request, false),
        ];

        $merge = [];

        if ($type == 'store') {
            $merge['created_by'] = $request->user()->id;
            $data = array_merge($data, $merge);
        }

        return $data;
    }

    /**
     * @param $sysDate
     * @param $alias
     *
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
    public function checkItem($item, $request, $int)
    {
        $default_int = ($int) ? 0 : null;

        return (isset($request->$item)) ? $request->$item : $default_int;
    }
}
