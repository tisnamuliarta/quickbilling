<?php

namespace App\Services\Payroll;

use App\Models\Payroll\Employee;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\PayType;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use IFRS\Models\ReportingPeriod;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class PayrollService
{
    use ApiResponse;

    /**
     * @param $request
     *
     * @return array
     */
    public function index($request)
    {
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 1000;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';

        $query = Payroll::select('*')
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        return collect($query);
    }

    /**
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public function getForm(): array
    {
        $form = $this->form('payrolls');

        $form['line_items'] = $this->lineItems();
        $form['id'] = 0;
        $form['transaction_no'] = $this->generateDocNum(Carbon::now());
        $form['transaction_date'] = date('Y-m-d');

        return $form;
    }

    public function lineItems(): array
    {
        $employees = Employee::select('*')->get();
        $columns = [];
        foreach ($employees as $employee) {
            $column = collect([
                'id' => null,
                'employee_name' => $employee->full_name,
                'payment_method' => ($employee->payMethod) ? $employee->payMethod->name : null,
                'salary' => $employee->salary
            ]);

            $pay_types = PayType::all();
            foreach ($pay_types as $pay_type) {
                $column->merge([
                    $pay_type->name => null,
                ]);
            }
            $merge = $column->merge([
                    'sub_total' => $employee->salary
                ]);
            $columns[] = $merge->all();
        }
        return $columns;
    }

    /**
     * @param $sysDate
     *
     * @return string
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    protected function generateDocNum($sysDate): string
    {
        $data_date = strtotime($sysDate);
        $month = date('m', $data_date);

        $entity = Auth::user()->entity;
        $periodCount = ReportingPeriod::getPeriod($sysDate, $entity)->period_count;
        $periodStart = ReportingPeriod::periodStart($sysDate, $entity);
        $periodStart = date('Y-m-d', strtotime($periodStart));

        $nextId = Payroll::where('transaction_date', '>=', $periodStart)
            ->count();

        $nextId = $nextId + 1;

        return str_pad((string)$periodCount, 2, '0', STR_PAD_LEFT)
            . $month .
            str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * @return array|\TValue[]
     */
    public function colHeaders(): array
    {
        App::setLocale(auth()->user()->locale);

        $pay_type = PayType::pluck('name');

        $header = collect([
            'Id',
            __('Employees'),
            __('Payment Method'),
            __('Salary'),
        ]);
        $mergePay = $header->merge($pay_type);
        $merge = $mergePay->merge([__('Total Pay')]);
        return $merge->all();
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        $columns = [
            [
                "data" => 'id',
                "wordWrap" => false,
            ],
            [
                "data" => 'employee_name',
                "width" => 120,
                "readOnly" => true,
                "wordWrap" => false,
            ],
            [
                "data" => 'payment_method',
                "width" => 80,
                "wordWrap" => false,
            ],
            [
                "data" => 'salary',
                "readOnly" => true,
                "width" => 80,
                "wordWrap" => false,
                "type" => 'numeric',
                "numericFormat" => [
                    "pattern" => '0,0.00',
                ],
            ],
        ];
        $pay_types = PayType::all();

        $mergePay = collect($columns);

        foreach ($pay_types as $pay_type) {
            $mergePay = $mergePay->merge([
                [
                    "data" => $pay_type->name,
                    "width" => 80,
                    "wordWrap" => false,
                    "type" => 'numeric',
                    "numericFormat" => [
                        "pattern" => '0,0.00',
                    ],
                ],
            ]);
        }

        $merge = $mergePay->merge([
            [
                "data" => 'sub_total',
                "width" => 100,
                "wordWrap" => false,
                "type" => 'numeric',
                "readOnly" => true,
                "numericFormat" => [
                    "pattern" => '0,0.00',
                ],
            ],
        ]);
        return $merge->all();
    }

    /**
     * @param $request
     *
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
        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');

        return $data;
    }
}
