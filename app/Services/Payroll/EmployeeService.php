<?php

namespace App\Services\Payroll;

use App\Models\Payroll\Employee;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request)
    {
        $type = (isset($request->type)) ? $request->type : '';
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 10;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'id';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $search = (isset($request->search)) ? $request->search : '';

        $query = Employee::select(
            'employees.*',
            'employees.id as user_id',
            DB::raw("'actions' as actions")
        )
            ->with(['workLocation', 'bank', 'entity'])
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        return collect($query);
    }

    /**
     * @param $request
     * @param $type
     * @return array
     */
    public function formData($request, $type): array
    {
        $data = $request->all();

        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');
        Arr::forget($data, 'actions');
        Arr::forget($data, 'work_location');
        Arr::forget($data, 'bank');
        Arr::forget($data, 'entity');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'id');
        Arr::forget($data, 'user_id');
        Arr::forget($data, 'full_name');

        $data['pay_schedule_name'] = (empty($data['pay_schedule_name'])) ? '-' : $data['pay_schedule_name'];
        $data['employee_id'] = (empty($data['employee_id'])) ? '-' : $data['employee_id'];
        $data['per_hour_rate'] = (empty($data['per_hour_rate'])) ? 0 : $data['per_hour_rate'];
        $data['salary'] = (empty($data['salary'])) ? 0 : $data['salary'];
        $data['hour_per_day'] = (empty($data['hour_per_day'])) ? 0 : $data['hour_per_day'];
        $data['day_per_week'] = (empty($data['day_per_week'])) ? 0 : $data['day_per_week'];

        return $data;
    }
}
