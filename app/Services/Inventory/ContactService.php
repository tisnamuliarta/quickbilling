<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Contact;

class ContactService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $options = $request->options;
        $pages = isset($options->page) ? (int)$options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 20;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "name";
        $order = isset($options->sortDesc[0]) ? (string)$options->sortDesc[0] : "asc";
        $offset = ($pages - 1) * $row_data;

        $result = array();
        $query = Contact::selectRaw(
            " contacts.*,
             CONCAT('(', sell.number, ') ', sell.name) as sell_account_name,
             CONCAT('(', buy.number, ') ', buy.name) as buy_account_name,
             'actions' as ACTIONS "
        )
            ->leftJoin('accounts as sell', 'sell.id', 'contacts.receivable_account_id')
            ->leftJoin('accounts as buy', 'buy.id', 'contacts.payable_account_id');

        $result["total"] = $query->count();

        $all_data = $query->orderBy($sorts, $order)
            ->offset($offset)
            ->limit($row_data)
            ->get();

        $arr_rows = Contact::pluck('name');

        return array_merge($result, [
            "rows" => $all_data,
            "simple" => $arr_rows
        ]);
    }

    /**
     * @param $form
     * @return array
     */
    public function formData($form): array
    {
        return [
            'name' => $form['name'],
            'company_id' => session('company_id'),
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
            'max_payable' => (array_key_exists('max_payable', $form)) ? $form['max_payable'] : null,
            'can_login' => (array_key_exists('can_login', $form)) ? $form['can_login'] : false,
            'active_max_payable' => (array_key_exists('active_max_payable', $form))
                ? $form['active_max_payable'] : false,
            'payment_term_id' => (array_key_exists('payment_term_id', $form)) ? $form['payment_term_id'] : null,
            'shipping_address' => (array_key_exists('shipping_address', $form)) ? $form['shipping_address'] : null,
            'receivable_account_id' => (array_key_exists('receivable_account_id', $form))
                ? $form['receivable_account_id'] : '',
            'currency_code' => 'IDR',
        ];
    }
}
