<?php

namespace App\Services\Payroll;

use App\Models\Financial\Category;
use App\Traits\Accounting;
use App\Traits\Categories;

class PayrollService
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
        $sorts = isset($options->sortBy[0]) ? (string) $options->sortBy[0] : 'category_type';
        $order = isset($options->sortDesc[0]) ? (string) $options->sortDesc[0] : 'asc';
        $search = $request->search;
        $offset = ($pages - 1) * $row_data;

        $result = [];
        $query = Category::select('*')
            ->where('name', 'LIKE', '%'.$search.'%')
            ->orWhere('category_type', 'LIKE', '%'.$search.'%');

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
    public function formData($request): array
    {
        $request->request->remove('updated_at');
        $request->request->remove('created_at');
        $request->request->remove('deleted_at');
        $request->request->remove('destroyed_at');
        $request->request->remove('id');

        return $request->all();
    }
}
