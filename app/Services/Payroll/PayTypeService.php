<?php

namespace App\Services\Payroll;

use App\Models\Payroll\PayType;
use App\Models\Payroll\WorkLocation;
use App\Traits\Accounting;
use App\Traits\Categories;
use Illuminate\Support\Arr;

class PayTypeService
{
    use Categories;
    use Accounting;

    /**
     * @param $request
     * @return array
     */
    public function index($request)
    {
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 1000;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';

        $query = PayType::select('*')
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        return collect($query);
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
        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');

        return $data;
    }
}
