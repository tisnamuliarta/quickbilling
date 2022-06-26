<?php

namespace App\Services\Financial;

use App\Models\Financial\PaymentTerm;

class PaymentTermService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $options = $request->options;
        $pages = isset($options->page) ? (int) $options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int) $options->itemsPerPage : 1000;
        $sorts = isset($options->sortBy[0]) ? (string) $options->sortBy[0] : 'name';
        $order = isset($options->sortDesc[0]) ? (string) $options->sortDesc[0] : 'asc';
        $offset = ($pages - 1) * $row_data;

        $result = [];
        $query = PaymentTerm::select('*');

        $result['total'] = $query->count();

        $all_data = $query->orderBy($sorts, $order)
            ->offset($offset)
            ->limit($row_data)
            ->get();

        $arr_rows = PaymentTerm::pluck('name');
        $arr_auto_complete = PaymentTerm::select('id', 'name')->get();

        return array_merge($result, [
            'rows' => $all_data,
            'simple' => $arr_rows,
            'auto_complete' => $arr_auto_complete,
        ]);
    }

    /**
     * @param $request
     * @return array
     */
    public function formData($request): array
    {
        $request->request->remove('updated_at');
        $request->request->remove('created_at');
        $request->request->remove('deleted_at');
        $request->request->remove('destroyed_at');

        return $request->all();
    }
}
