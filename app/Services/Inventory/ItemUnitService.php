<?php

namespace App\Services\Inventory;

use App\Models\Inventory\ItemUnit;
use Illuminate\Support\Arr;

class ItemUnitService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request)
    {
        $pages = isset($request->page) ? (int) $request->page : 1;
        $row_data = isset($request->itemsPerPage) ? (int) $request->itemsPerPage : 1000;
        $sorts = isset($request->sortBy[0]) ? (string) $request->sortBy[0] : 'name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $search = $request->search;
        $offset = ($pages - 1) * $row_data;

        $query = ItemUnit::select('*')
            ->where('name', 'LIKE', '%'.$search.'%')
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        return $query;
    }

    /**
     * @param $request
     * @param $type
     * @return array
     */
    public function formData($request, $type): array
    {
        $data = $request->all();

        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'destroyed_at');
        Arr::forget($data, 'id');

        if ($type == 'store') {
            $data['price_list_id'] = 0;
        }

        return $data;
    }

    /**
     * @param $sysDate
     * @param $alias
     * @return string
     */
    protected function generateDocNum($sysDate): string
    {
        $doc_num = ItemUnit::selectRaw('code')
            ->orderBy('code', 'DESC')
            ->first();

        $number = empty($doc_num) ? '00000' : $doc_num->code;
        $clear_doc_num = (int) substr($number, 0, 5);
        $number = $clear_doc_num + 1;

        return sprintf('%05s', $number);
    }
}
