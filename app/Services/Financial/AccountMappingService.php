<?php

namespace App\Services\Financial;

use IFRS\Models\Currency;
use Illuminate\Support\Arr;

class AccountMappingService
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

        $query = Currency::select('*')
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        return $query;
    }

    public function colHeaders()
    {
        return ['ID', 'ACCOUNT ID', 'TYPE', 'NAME', '', 'ACCOUNT', 'ACCOUNT NAME'];
    }

    public function columns()
    {
        return [
            [
                'data' => 'id',
                'wordWrap' => false,
            ],
            [
                'width' => '150',
                'wordWrap' => false,
                'data' => 'account_id',
            ],
            [
                'width' => '100',
                'wordWrap' => false,
                'data' => 'type',
            ],
            [
                'width' => '120',
                'data' => 'name',
                'wordWrap' => false,
            ],
            [
                'data' => 'renderer',
                'wordWrap' => false,
                'width' => '20',
                'renderer' => 'ButtonAddRenderer',
            ],
            [
                'data'=> 'account',
                'width' => '100',
                'wordWrap' => false,
                // 'width' => '150',
            ],
            [
                'width' => '250',
                'data' => 'account_name',
            ],
        ];
    }

    /**
     * @param $request
     * @param $type
     * @return array
     */
    public function formData($request): array
    {
        $data = $request->all();

        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'destroyed_at');
        Arr::forget($data, 'account_type_list');
        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');
        Arr::forget($data, 'id');
        Arr::forget($data, 'code');
        Arr::forget($data, 'currency');
        Arr::forget($data, 'entity');

        return $data;
    }
}
