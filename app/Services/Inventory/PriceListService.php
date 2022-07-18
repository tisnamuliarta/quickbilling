<?php

namespace App\Services\Inventory;

use App\Models\Inventory\PriceList;
use App\Traits\Accounting;
use App\Traits\Categories;
use Illuminate\Support\Arr;

class PriceListService
{
    use Categories;
    use Accounting;

    /**
     * @param $request
     */
    public function index($request)
    {
        $row_data = isset($request->itemsPerPage) ? (int) $request->itemsPerPage : 1000;
        $sorts = isset($request->sortBy[0]) ? (string) $request->sortBy[0] : 'price_list_name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';

        $query = PriceList::select('*')
            ->with(['basePrice'])
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        $result['simple'] = PriceList::pluck('price_list_name');

        $collect = collect($query);
        $result = $collect->merge($result);

        return  $result->all();
    }

    /**
     * @param $request
     * @return array
     */
    public function formData($request, $type): array
    {
        $data = $request->all();
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
