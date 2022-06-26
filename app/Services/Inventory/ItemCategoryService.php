<?php

namespace App\Services\Inventory;

use App\Models\Inventory\ItemCategory;
use Illuminate\Support\Facades\DB;

class ItemCategoryService
{

    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $options = $request->options;
        $pages = isset($options->page) ? (int) $options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int) $options->itemsPerPage : 1000;
        $sorts = isset($options->sortBy[0]) ? (string) $options->sortBy[0] : 'name';
        $order = isset($options->sortDesc[0]) ? (string) $options->sortDesc[0] : 'asc';
        $search = $request->search;
        $offset = ($pages - 1) * $row_data;

        $result = [];
        $query = ItemCategory::select('*')
            ->where('name', 'LIKE', '%'.$search.'%');

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
     * @param $request
     * @return array
     */
    public function formData($request, $type): array
    {
        if ($type === 'store') {
            $request->merge([
                'code' => $this->generateDocNum(date('Y-m-d H:i:s'))
            ]);
        }
        $request->request->remove('updated_at');
        $request->request->remove('created_at');
        $request->request->remove('deleted_at');
        $request->request->remove('destroyed_at');
        $request->request->remove('default_currency_code');
        $request->request->remove('default_currency_symbol');
        $request->request->remove('id');

        return $request->all();
    }

    /**
     * @param $sysDate
     * @param $alias
     * @return string
     */
    protected function generateDocNum($sysDate): string
    {
        $doc_num = ItemCategory::selectRaw('code')
            ->orderBy('code', 'DESC')
            ->first();

        $number = empty($doc_num) ? '00000' : $doc_num->code;
        $clear_doc_num = (int) substr($number, 0, 5);
        $number = $clear_doc_num + 1;

        return sprintf('%05s', $number);
    }
}
