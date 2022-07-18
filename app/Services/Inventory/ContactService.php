<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Contact;
use Illuminate\Support\Arr;

class ContactService
{
    /**
     * @param $request
     * @param $type
     * @return array
     */
    public function index($request, $type)
    {
        $pages = isset($request->page) ? (int) $request->page : 1;
        $row_data = isset($request->itemsPerPage) ? (int) $request->itemsPerPage : 1000;
        $sorts = isset($request->sortBy[0]) ? (string) $request->sortBy[0] : 'name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $type = $request->contactType;
        $offset = ($pages - 1) * $row_data;

        $result = [];
        $query = Contact::where('contacts.type', 'LIKE', '%'.$type.'%')
            ->with(['banks', 'emails', 'sellAccount.balances', 'purchaseAccount.balances'])
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
        Arr::forget($data, 'banks');
        Arr::forget($data, 'emails');
        Arr::forget($data, 'sell_account');
        Arr::forget($data, 'purchase_account');

        return $data;
    }
}
