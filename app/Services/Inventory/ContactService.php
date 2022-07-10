<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Contact;

class ContactService
{
    /**
     * @param $request
     * @param $type
     * @return array
     */
    public function index($request, $type)
    {
        $pages = isset($request->page) ? (int)$request->page : 1;
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 1000;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $type = $request->contactType;
        $offset = ($pages - 1) * $row_data;

        $result = [];
        $query = Contact::where('contacts.type', 'LIKE', '%' . $type . '%')
            ->with(['banks', 'emails', 'sellAccount', 'purchaseAccount'])
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
        $request->request->remove('id');
        $request->request->remove('created_at');
        $request->request->remove('updated_at');
        $request->request->remove('deleted_at');

        $request->request->remove('default_currency_code');
        $request->request->remove('default_currency_symbol');
        $data = $request->all();

        return $data;
    }
}
