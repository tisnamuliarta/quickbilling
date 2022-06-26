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
    public function index($request, $type): array
    {
        $all_data = [];
        $options = $request->options;
        $pages = isset($options->page) ? (int) $options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int) $options->itemsPerPage : 0;
        $sorts = isset($options->sortBy[0]) ? (string) $options->sortBy[0] : 'name';
        $order = isset($options->sortDesc[0]) ? (string) $options->sortDesc[0] : 'asc';
        $type = $request->contactType;
        $offset = ($pages - 1) * $row_data;

        $result = [];
        $query = Contact::selectRaw(
            " contacts.*,
                    0 as balance,
                    CONCAT('(', sell.code, ') ', sell.name) as sell_account_name,
                    CONCAT('(', buy.code, ') ', buy.name) as buy_account_name,
                    'actions' as ACTIONS "
        )
            ->leftJoin('accounts as sell', 'sell.id', 'contacts.receivable_account_id')
            ->leftJoin('accounts as buy', 'buy.id', 'contacts.payable_account_id')
            ->where('contacts.type', 'LIKE', '%'.$type.'%')
            ->with(['banks', 'emails']);

        $result['total'] = $query->count();

        $query->orderBy($sorts, $order);
        if ($row_data != 0) {
            $query->offset($offset)
                ->limit($row_data);
        }
        $all_data = $query->get();

        $all_data = array_merge($result, [
            'rows' => $all_data,
        ]);

        return $all_data;
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
