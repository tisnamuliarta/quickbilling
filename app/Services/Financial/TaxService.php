<?php

namespace App\Services\Financial;

use IFRS\Models\Vat;
use Illuminate\Support\Arr;

class TaxService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 1000;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';

        $result = [];
        $query = Vat::with(['account', 'entity'])
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        $arr_rows = Vat::pluck('name');
        $result = array_merge($result, [
            'simple' => $arr_rows,
        ]);

        $collection = collect($query);
        $result = $collection->merge($result);
        return $result->all();
    }

    /**
     * @param $request
     * @return array
     */
    public function formData($request): array
    {
        $data = $request->all();
        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'destroyed_at');
        Arr::forget($data, 'account');
        Arr::forget($data, 'entity');
        Arr::forget($data, 'id');

        return $data;
    }
}
