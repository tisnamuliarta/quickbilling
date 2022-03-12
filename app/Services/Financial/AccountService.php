<?php

namespace App\Services\Financial;

use App\Models\Financial\Account;
use App\Traits\Accounting;
use App\Traits\Categories;

class AccountService
{
    use Categories;
    use Accounting;
    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $options = $request->options;
        $pages = isset($options->page) ? (int)$options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 1000;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "number";
        $order = isset($options->sortDesc[0]) ? (string)$options->sortDesc[0] : "asc";
        $offset = ($pages - 1) * $row_data;

        $result = array();
        $query = Account::selectRaw(
            " accounts.*,
             categories.name as category,
             banks.name as bank,
             taxes.name as tax,
             'actions' as ACTIONS "
        )
            ->leftJoin('categories', 'categories.id', 'accounts.category_id')
            ->leftJoin('taxes', 'taxes.id', 'accounts.tax_id')
            ->leftJoin('banks', 'banks.id', 'accounts.bank_id')
            ->where('categories.type', 'Account Category');

        $result["total"] = $query->count();

        $all_data = $query->orderBy($sorts, $order)
            //->offset($offset)
            //->limit($row_data)
            ->get();

        $arr_rows = Account::pluck('name');

        return array_merge($result, [
            "rows" => $all_data,
            "simple" => $arr_rows
        ]);
    }

    /**
     * @param $type
     * @return array
     */
    public function dataByType($type): array
    {
        $query = Account::selectRaw(
            " CONCAT('(', accounts.number, ') ', accounts.name, ' (', categories.name, ')') as name, accounts.id "
        )
            ->leftJoin('categories', 'categories.id', 'accounts.category_id')
            ->leftJoin('taxes', 'taxes.id', 'accounts.tax_id')
            ->leftJoin('banks', 'banks.id', 'accounts.bank_id')
            ->where('categories.type', 'Account Category')
            ->where('categories.name', 'LIKE', '%' . $type . '%')
            ->orderBy('accounts.number')
            ->get();

        return [
            "rows" => $query
        ];
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
            'category_id' => $this->categoryIdByName($form['category']),
            'bank_id' => (array_key_exists('bank', $form)) ? $this->bankIdByName($form['bank']) : 0,
            'details' => (array_key_exists('details', $form)) ? $form['details'] : '',
            'opening_balance' => (array_key_exists('opening_balance', $form)) ? $form['opening_balance'] : 0,
            'descriptions' => (array_key_exists('descriptions', $form)) ? $form['descriptions'] : '',
            'number' => (array_key_exists('number', $form)) ? $form['number'] : '',
            'currency_code' => 'IDR',
        ];
    }
}
