<?php

namespace App\Services\Financial;

use App\Models\Financial\Category;
use App\Traits\Accounting;
use App\Traits\Categories;
use Illuminate\Support\Arr;

class AccountCategoryService
{
    use Categories;
    use Accounting;

    /**
     * @param $request
     * @return array
     */
    public function index($request)
    {
        $pages = isset($request->page) ? (int) $request->page : 1;
        $row_data = isset($request->itemsPerPage) ? (int) $request->itemsPerPage : 1000;
        $search = $request->search;
        $query = Category::select('*')
            ->where('name', 'LIKE', '%'.$search.'%')
            ->orWhere('category_type', 'LIKE', '%'.$search.'%')
            ->paginate($row_data);

        return $query;
    }

    /**
     * @param $request
     * @return array
     */
    public function formData($request): array
    {
        $data = $request->all();

        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'destroyed_at');
        Arr::forget($data, 'id');

        return $data;
    }
}
