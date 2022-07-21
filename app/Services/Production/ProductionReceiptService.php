<?php

namespace App\Services\Production;

use App\Models\Inventory\ItemUnit;
use App\Models\Inventory\ReceiptProduction;
use App\Models\Productions\Production;
use Illuminate\Support\Arr;

class ProductionReceiptService
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

        $query = ReceiptProduction::select('*')
            ->where('name', 'LIKE', '%'.$search.'%')
            ->where('transaction_type', 'receipt')
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

        return $data;
    }

    /**
     * @param $sysDate
     * @return string
     */
    protected function generateDocNum($sysDate): string
    {
        $doc_num = ReceiptProduction::selectRaw('code')
            ->orderBy('code', 'DESC')
            ->first();

        $number = empty($doc_num) ? '00000' : $doc_num->code;
        $clear_doc_num = (int) substr($number, 0, 5);
        $number = $clear_doc_num + 1;

        return sprintf('%05s', $number);
    }
}
