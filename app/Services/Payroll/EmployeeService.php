<?php

namespace App\Services\Payroll;

use App\Models\Payroll\Employee;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $type = (isset($request->type)) ? $request->type : '';
        $options = $request->options;
        $pages = isset($options->page) ? (int) $options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int) $options->itemsPerPage : 10;
        $sorts = isset($options->sortBy[0]) ? (string) $options->sortBy[0] : 'id';
        $order = isset($options->sortDesc[0]) ? (string) $options->sortDesc[0] : 'desc';
        $offset = ($pages - 1) * $row_data;

        $result = [];
        $query = Employee::select(
            'employees.*',
            DB::raw("'actions' as actions")
        )
            ->with(['workLocation', 'bank', 'entity']);

        $result['total'] = $query->count();

        $all_data = $query->orderBy($sorts, $order)
            ->offset($offset)
            ->limit($row_data)
            ->get();

        return array_merge($result, [
            'rows' => $all_data,
        ]);
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
        $request->request->remove('deleted_at');

        $data = $request->all();

        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');

        return $data;
    }
}
