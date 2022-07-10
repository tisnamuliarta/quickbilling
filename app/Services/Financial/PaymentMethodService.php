<?php

namespace App\Services\Financial;

use App\Models\Financial\PaymentMethod;
use Illuminate\Support\Arr;

class PaymentMethodService
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
        $query = PaymentMethod::select('*')
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        $arr_rows = PaymentMethod::pluck('name');

        $result = array_merge($result, [
            'simple' => $arr_rows,
        ]);

        $collect = collect($query);
        $result = $collect->merge($result);

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
        Arr::forget($data, 'id');

        return $data;
    }
}
