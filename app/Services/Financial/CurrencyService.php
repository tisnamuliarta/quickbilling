<?php

namespace App\Services\Financial;

use App\Models\Financial\Currency;
use Carbon\Carbon;

class CurrencyService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $options = $request->options;
        $pages = isset($options->page) ? (int)$options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 1000;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "name";
        $order = isset($options->sortDesc[0]) ? (string)$options->sortDesc[0] : "asc";
        $offset = ($pages - 1) * $row_data;

        $result = array();
        $query = Currency::select('*');

        $result["total"] = $query->count();

        $all_data = $query->orderBy($sorts, $order)
            ->offset($offset)
            ->limit($row_data)
            ->get();

        $arr_rows = Currency::select('name', 'code')->get();

        return array_merge($result, [
            "rows" => $all_data,
            "simple" => $arr_rows
        ]);
    }

    /**
     * @param $request
     * @param $type
     * @return array
     */
    public function formData($request, $type): array
    {
        $data = $request->all();

        if ($type == 'store') {
            $data['created_by'] = $request->user()->id;
            $data['created_at'] = Carbon::now();
        } else {
            $data['updated_at'] = Carbon::now();
        }

        return $data;
    }
}
