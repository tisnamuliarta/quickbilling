<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Contact;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContactService
{
    /**
     * @param $request
     * @param $type
     * @return array
     */
    public function index($request, $type)
    {
        $sales = ['SQ', 'SO', 'SD', 'IN', 'RC', 'CN', 'SR'];
        $contact_type = (Str::contains($type, $sales)) ? 'Customer' : 'Vendor';
        if ($type == 'index') {
            $contact_type = '';
        }

        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 1000;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $search = isset($request->search) ? $request->search : '';
        $type = $request->contactType;

        $result = [];
        $query = Contact::where('contacts.type', 'LIKE', '%' . $contact_type . '%')
            ->with(['banks', 'emails', 'sellAccount.balances', 'purchaseAccount.balances'])
            ->where(DB::raw("CONCAT(name, ' ', type)"), 'LIKE', '%' . $search . '%')
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

        $data['enabled'] = (empty($data['enabled'])) ? true : $data['enabled'];
        $data['currency_code'] = (empty($data['currency_code']))
            ? auth()->user()->entity->currency->currency_code : $data['currency_code'];
        $data['can_login'] = (empty($data['can_login'])) ? false : $data['can_login'];
        $data['max_payable'] = (empty($data['max_payable'])) ? 0 : $data['max_payable'];
        $data['active_max_payable'] = (empty($data['active_max_payable'])) ? false : $data['active_max_payable'];

        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'destroyed_at');
        Arr::forget($data, 'id');
        Arr::forget($data, 'banks');
        Arr::forget($data, 'emails');
        Arr::forget($data, 'sell_account');
        Arr::forget($data, 'purchase_account');
        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');
        Arr::forget($data, 'password');

        return $data;
    }
}
