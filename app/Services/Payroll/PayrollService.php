<?php

namespace App\Services\Payroll;

use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeCommission;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\PayrollDetail;
use App\Models\Payroll\PayType;
use App\Services\Financial\AccountMappingService;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use IFRS\Models\LineItem;
use IFRS\Models\ReportingPeriod;
use IFRS\Transactions\JournalEntry;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'transaction_no';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $search = (isset($request->search)) ? $request->search : '';

        $query = Payroll::select('*')
            ->with(['user'])
            ->where(DB::raw("CONCAT(transaction_no, ' ', transaction_date)"), 'LIKE', '%' . $search . '%')
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        return collect($query);
    }

    /**
     * @param $document
     * @param $items
     *
     * @return void
     */
    public function processItems($document, $items)
    {
        $line_num = 0;
        foreach ($items as $item) {
            [$keys, $values] = Arr::divide($item);
            foreach ($keys as $value) {
                if (!Str::contains($value, [
                    'payroll_id', 'employee_name', 'payment_method', 'salary', 'sub_total', 'employee_id'
                ])) {
                    $pay_type = PayType::where('name', $value)->first();
                    $details = PayrollDetail::where('payroll_id', $document->id)
                        ->where('employee_id', $item['employee_id'])
                        ->where('pay_type_id', $pay_type->id)
                        ->first();

                    if ($details) {
                        $details->amount = $item[$value];
                    } else {
                        $details = new PayrollDetail();
                        $details->entity_id = auth()->user()->entity_id;
                        $details->payroll_id = $document->id;
                        $details->employee_id = $item['employee_id'];
                        $details->salary = $item['salary'];
                        $details->pay_type_id = $pay_type->id;
                        $details->amount = $item[$value];
                        $details->created_by = auth()->user()->id;
                        $details->line_num = $line_num;
                    }
                    $details->save();
                }
            }
            $line_num++;
        }
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
        $form['status'] = 'draft';

        return $form;
    }

    /**
     * @return array
     */
    public function lineItems(): array
    {
        $employees = Employee::orderBy('id')
            ->get();
        $columns = [];
        foreach ($employees as $employee) {
            $column = collect([
                'payroll_id' => null,
                'employee_id' => $employee->id,
                'employee_name' => $employee->full_name,
                'payment_method' => ($employee->payMethod) ? $employee->payMethod->name : '',
                'salary' => $employee->salary
            ]);

            $pay_types = PayType::all();
            foreach ($pay_types as $pay_type) {
                $column = $column->merge([
                    $pay_type->name => 0,
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
            'employee_id',
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
                "data" => 'payroll_id',
                "wordWrap" => false,
            ],
            [
                "data" => 'employee_id',
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
                "readOnly" => true,
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
     * @param $type
     *
     * @return array
     */
    public function formData($request, $type): array
    {
        $pay_period = explode(' to ', $request->pay_period);
        $data = $request->all();
        $data['from_date'] = $pay_period[0];
        $data['to_date'] = $pay_period[1];
        $data['pay_date'] = $request->transaction_date;

        if ($type == 'store') {
            $data['created_by'] = $request->user()->id;
        }

        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'destroyed_at');
        Arr::forget($data, 'id');
        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');
        Arr::forget($data, 'line_items');
        Arr::forget($data, 'line_item');
        Arr::forget($data, 'entity_id');
        Arr::forget($data, 'pay_period');

        return $data;
    }

    /**
     * @throws \Exception
     */
    public function getSingleDocument($id): array
    {
        if ($id == 0) {
            return  [];
        }
        $data = Payroll::where('id', '=', $id)
            ->first();

        if ($data) {
            $item_employee = [];
            foreach ($data->lineItem as $lineItem) {
                $item_employee[$lineItem->line_num]['sub_total'] = 0;
                $sub_total = $item_employee[$lineItem->line_num]['sub_total'];

                $item_employee[$lineItem->line_num] = [
                    'payroll_id' => $data->id,
                    'employee_id' => $lineItem->employee_id,
                    'employee_name' => $lineItem->employee->full_name,
                    'payment_method' => ($lineItem->employee->payMethod) ? $lineItem->employee->payMethod->name : '',
                    'salary' => $lineItem->salary,
                ];
                foreach ($data->lineItem as $lineItm) {
                    $pay_type = PayType::find($lineItm->pay_type_id);
                    $item_employee[$lineItm->line_num][$pay_type->name] = $lineItm->amount;
                }
            }

            foreach ($item_employee as $index => $item) {
                [$keys, $values] = Arr::divide($item);
                // throw new \Exception(json_encode($keys));
                $sub_total = 0;
                foreach ($keys as $value) {
                    if (!Str::contains($value, [
                        'payroll_id', 'employee_name', 'payment_method', 'sub_total', 'employee_id'
                    ])) {
                        $sub_total = $sub_total + $item[$value];
                    }
                }
                $item_employee[$index]['sub_total'] = $sub_total;
            }

            // $lineItems = $collect_pay->merge($item_employee)->all();
            return $item_employee;
        } else {
            throw new \Exception('Data not found!', 1);
        }
    }

    /**
     * @param $document
     * @param $pay_period
     *
     * @return void
     */
    public function processPayroll($document, $pay_period)
    {
        $accountMapping = new AccountMappingService();
        $payroll_clearing = $accountMapping->getAccountByName('Payroll Clearing')->account_id;

        if ($document->status == 'closed') {
            EmployeeCommission::whereBetween('transaction_date', $pay_period)
                ->update([
                    'status' => 'closed'
                ]);

            // create journal entry for debit payroll clearing
            $journalEntry = JournalEntry::create([
                'account_id' => $payroll_clearing,
                'date' => Carbon::now(),
                'narration' => 'Payroll ' . $document->transaction_date . ' ' . $document->transaction_no,
                'reference' => $document->transaction_no,
                'credited' => false, // main account should be debited
                'main_account_amount' => $document->main_account_amount,
                'status' => 'closed',
                'base_id' => $document->id,
                'base_type' => $document->transaction_type,
                'base_num' => $document->transaction_no
            ]);

            $journalEntry->addLineItem(
                LineItem::create([
                    'account_id' => $document->account_id,
                    'description' => 'Payroll clearing',
                    'narration' => 'Payroll clearing',
                    'amount' => $document->main_account_amount,
                ])
            );

            $journalEntry->post();
        } elseif ($document->status == 'cancel') {
            EmployeeCommission::whereBetween('transaction_date', $pay_period)
                ->update([
                    'status' => 'cancel'
                ]);

            // create journal entry for debit payroll clearing
            $journalEntry = JournalEntry::create([
                'account_id' => $payroll_clearing,
                'date' => Carbon::now(),
                'narration' => 'Payroll ' . $document->transaction_date . ' ' . $document->transaction_no,
                'reference' => $document->transaction_no,
                'credited' => true, // main account should be debited
                'main_account_amount' => $document->main_account_amount,
                'status' => 'closed',
                'base_id' => $document->id,
                'base_type' => $document->transaction_type,
                'base_num' => $document->transaction_no
            ]);

            $journalEntry->addLineItem(
                LineItem::create([
                    'account_id' => $document->account_id,
                    'description' => 'Payroll clearing',
                    'narration' => 'Payroll clearing',
                    'amount' => $document->main_account_amount,
                ])
            );
        }
    }
}
