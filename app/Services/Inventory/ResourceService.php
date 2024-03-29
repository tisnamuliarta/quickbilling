<?php

namespace App\Services\Inventory;

use App\Models\Inventory\ItemCategory;
use App\Models\Inventory\Resource;
use App\Traits\Categories;
use App\Traits\FileUpload;
use App\Traits\Financial;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResourceService
{
    use FileUpload;
    use Categories;
    use Financial;

    /**
     * @param $request
     * @return array
     */
    public function index($request)
    {
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 20;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $data_status = isset($request->dataStatus) ? (string)$request->dataStatus : 'open';

        $search = isset($request->q) ? (string)$request->q : '';
        $select_data = isset($request->selectData) ? (string)$request->selectData : 'name';

        $query = Resource::with([
            'warehouse', 'account',
        ])
            ->where(DB::raw("CONCAT(name, ' ', code)"), 'LIKE', '%'.$search.'%')
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        return $query;
    }

    /**
     * @param $request
     * @param $type
     * @param null $id
     * @return array
     */
    public function formData($request, $type, $id = null): array
    {
        $data = $request->all();

        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');
        Arr::forget($data, 'id');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'warehouse');
        Arr::forget($data, 'account');
        Arr::forget($data, 'whs_code');

        $data['account_id'] = $data['std_cost_account_1'];

        if ($type == 'store') {
            $data['issue_method'] = 'backflush';
            $data['code'] = (isset($request->code)) ? $request->code :
                $this->generateDocNum(date('Y-m-d H:i:s'), 'RS');
        }

        return $data;
    }

    /**
     * @param $sysDate
     * @param $alias
     * @return string
     */
    protected function generateDocNum($sysDate, $alias): string
    {
        $data_date = strtotime($sysDate);

        $day_val = date('j', $data_date);

        $doc_num = Resource::count() + 1;

        return Str::upper($alias) . '-' . str_pad((string)$doc_num, 5, '0', STR_PAD_LEFT);
    }

    /**
     * @param $item
     * @param $request
     * @param $int
     * @return int|mixed|null
     */
    public function checkItem($item, $request, $int): mixed
    {
        $default_int = ($int) ? 0 : null;

        return (isset($request->$item)) ? $request->$item : $default_int;
    }

    /**
     * @param $category
     * @param $item_id
     * @return void
     */
    public function processItemCategory($category, $item_id)
    {
        if ($category) {
            foreach ($category as $item) {
                ItemCategory::updateOrCreate([
                    'category_id' => $this->categoryIdByName($item),
                    'item_id' => $item_id,
                ]);
            }
        }
    }
}
