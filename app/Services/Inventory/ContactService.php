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
     * @param $form
     * @return array
     */
    public function formData($form): array
    {
        return [
            'name' => $form['name'],
            'entity_id' => auth()->user()->entity_id,
            'type' => (array_key_exists('type', $form)) ? $form['type'] : null,
            'email' => (array_key_exists('email', $form)) ? $form['email'] : null,
            'company_name' => (array_key_exists('company_name', $form)) ? $form['company_name'] : null,
            'tax_number' => (array_key_exists('tax_number', $form)) ? $form['tax_number'] : null,
            'phone' => (array_key_exists('phone', $form)) ? $form['phone'] : null,
            'fax' => (array_key_exists('fax', $form)) ? $form['fax'] : null,
            'identify_by' => (array_key_exists('identify_by', $form)) ? $form['identify_by'] : null,
            'identify' => (array_key_exists('identify', $form)) ? $form['identify'] : null,
            'first_name' => (array_key_exists('first_name', $form)) ? $form['first_name'] : null,
            'middle_name' => (array_key_exists('middle_name', $form)) ? $form['middle_name'] : null,
            'last_name' => (array_key_exists('last_name', $form)) ? $form['last_name'] : null,
            'identify_number' => (array_key_exists('identify_number', $form)) ? $form['identify_number'] : null,
            'address' => (array_key_exists('address', $form)) ? $form['address'] : null,
            'payable_account_id' => (array_key_exists('payable_account_id', $form))
                ? $form['payable_account_id'] : null,
            'max_payable' => (array_key_exists('max_payable', $form)) ? (float) $form['max_payable'] : 0,
            'can_login' => (array_key_exists('can_login', $form)) ? (($form['can_login']) ?: false) : false,
            'active_max_payable' => (array_key_exists('active_max_payable', $form))
                ? (($form['active_max_payable']) ?: false) : false,
            'payment_term_id' => (array_key_exists('payment_term_id', $form)) ? $form['payment_term_id'] : null,
            'shipping_address' => (array_key_exists('shipping_address', $form)) ? $form['shipping_address'] : null,
            'receivable_account_id' => (array_key_exists('receivable_account_id', $form))
                ? $form['receivable_account_id'] : '',
            'currency_code' => 'IDR',
        ];
    }
}
