<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Item;
use App\Traits\FileUpload;

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
            'image' => $fileName,
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
            'code' => $request->code,
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
