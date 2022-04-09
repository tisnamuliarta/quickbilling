<?php

namespace App\Services\Financial;

use App\Models\Financial\Tax;

class TaxService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $options = $request->options;
        $pages = isset($options->page) ? (int)$options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 1000;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "name";
        $order = isset($options->sortDesc[0]) ? (string)$options->sortDesc[0] : "asc";
        $offset = ($pages - 1) * $row_data;

        $result = array();
        $query = Tax::selectRaw(
            " taxes.*,
             CONCAT('(', sell.number, ') ', sell.name) as sell_account_name,
             CONCAT('(', buy.number, ') ', buy.name) as buy_account_name,
             'actions' as ACTIONS "
        )
            ->leftJoin('accounts as sell', 'sell.id', 'taxes.sell_account')
            ->leftJoin('accounts as buy', 'buy.id', 'taxes.buy_account');

        $result["total"] = $query->count();

        $all_data = $query->orderBy($sorts, $order)
            ->offset($offset)
            ->limit($row_data)
            ->get();

        $arr_rows = Tax::pluck('name');
        $arr_row_simple = Tax::select('id', 'name')->get();

        return array_merge($result, [
            "rows" => $all_data,
            "row_simple" => $arr_row_simple,
            "simple" => $arr_rows
        ]);
    }

    /**
     * @param $form
     * @param $request
     * @param $type
     * @return array
     */
    public function formData($form, $request, $type): array
    {
        $data = [
            'name' => $form['name'],
            'entity_id' => auth()->user()->entity_id,
            'rate' => (array_key_exists('rate', $form)) ? $form['rate'] : 0,
            'type' => (array_key_exists('type', $form)) ? $form['type'] : 'normal',
            'enabled' => (array_key_exists('enabled', $form)) ? $form['enabled'] : true,
            'withholding' => (array_key_exists('withholding', $form)) ? $form['withholding'] : false,
            'sell_account' => (array_key_exists('sell_account', $form)) ? $form['sell_account'] : null,
            'buy_account' => (array_key_exists('buy_account', $form)) ? $form['buy_account'] : null,
        ];

        $merge = [];

        if ($type == 'store') {
            $merge['created_by'] = $request->user()->id;
            $data = array_merge($data, $merge);
        }

        return $data;
    }
}
