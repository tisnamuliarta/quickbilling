<?php

namespace App\Services\Financial;

use App\Traits\Accounting;
use App\Traits\Categories;
use IFRS\Models\Account;

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
        $pages = isset($options->page) ? (int) $options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int) $options->itemsPerPage : 1000;
        $sorts = isset($options->sortBy[0]) ? (string) $options->sortBy[0] : 'code';
        $order = isset($options->sortDesc[0]) ? (string) $options->sortDesc[0] : 'asc';
        $offset = ($pages - 1) * $row_data;

        $result = [];
        $query = Account::with(['currency', 'entity', 'category', 'balances']);

        $result['total'] = $query->count();

        $all_data = $query->orderBy($sorts, $order)
            //->offset($offset)
            //->limit($row_data)
            ->get();

        return array_merge($result, [
            'rows' => $all_data,
        ]);
    }

    /**
     * @param $type
     * @return array
     */
    public function dataByType($type): array
    {
        $query = Account::selectRaw(
            " CONCAT('(', code, ') ', name, ' (', account_type, ')') as name, id "
        )
            ->where('account_type', 'LIKE', '%'.$type.'%')
            ->orderBy('code')
            ->get();

        return [
            'rows' => $query,
        ];
    }

    /**
     * @param $request
     * @return array
     */
    public function formData($request): array
    {
        $request->request->remove('updated_at');
        $request->request->remove('created_at');
        $request->request->remove('deleted_at');
        $request->request->remove('destroyed_at');
        $request->request->remove('account_type_list');
        $request->request->remove('default_currency_code');
        $request->request->remove('default_currency_symbol');
        $request->request->remove('id');
        $request->request->remove('code');
        $request->request->remove('currency');
        $request->request->remove('entity');
        $request->request->remove('category');
        $request->request->remove('balances');

        return $request->all();
    }
}
