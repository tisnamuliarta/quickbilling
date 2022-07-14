<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Warehouse;
use App\Traits\Accounting;
use App\Traits\Categories;
use Illuminate\Support\Arr;

class WarehouseService
{
    use Categories;
    use Accounting;

    /**
     * @param $request
     */
    public function index($request)
    {
        $row_data = isset($request->itemsPerPage) ? (int) $request->itemsPerPage : 1000;
        $sorts = isset($request->sortBy[0]) ? (string) $request->sortBy[0] : 'name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';

        $query = Warehouse::select('*')
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        return collect($query);
    }

    /**
     * @param $request
     * @return array
     */
    public function formData($request, $type): array
    {
        $data = $request->all();

        if ($type == 'store') {
            $merge = [
                'created_by' => auth()->user()->id,
            ];
            $data = array_merge($data, $merge);
        }

        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'destroyed_at');
        Arr::forget($data, 'entity_id');
        Arr::forget($data, 'id');
        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');

        return $data;
    }
}
