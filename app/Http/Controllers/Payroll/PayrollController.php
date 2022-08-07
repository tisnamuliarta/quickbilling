<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payrolls\StorePayrollRequest;
use App\Http\Requests\Payrolls\UpdatePayrollRequest;
use App\Models\Payroll\EmployeeCommission;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\PaySchedule;
use App\Services\Payroll\PayrollService;
use App\Traits\CompanyField;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use NumberToWords\NumberToWords;

class PayrollController extends Controller
{
    use CompanyField;

    public PayrollService $service;

    public function __construct(PayrollService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $result = [];
        $result['form'] = $this->form('payrolls');
        $result['form']['id'] = 0;
        $result['form']['line_items'] = [];

        $collection = collect($this->service->index($request));
        $result = $collection->merge($result);

        return $this->success($result->all());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function payPeriod(Request $request): JsonResponse
    {
        $pay_schedule_id = $request->pay_schedule_id;

        $schedule = PaySchedule::find($pay_schedule_id);

        $date_period = Carbon::parse($schedule->end_pay_period)->format('d');

        $date_from = Carbon::parse($schedule->end_day_period)->format('Y-m');
        $date_to = Carbon::parse($schedule->end_day_period)->addDays(365)->format('Y-m');

        $periods = CarbonPeriod::create($date_from, '1 month', $date_to);

        $period_list = [];
        foreach ($periods as $period) {
            $date = $period->format('Y-m-') . $date_period;
            $day_in_month = $period->format('d');
            $period_from = Carbon::parse($date)->addDays($day_in_month)
                ->addMonths(-1)
                ->format('Y-m-d');

            $period_list[] = $period_from . ' to ' . $date;
        }

        return $this->success([
            'data' => $period_list,
            'date_period' => $schedule
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getEmployeeCommission(Request $request): JsonResponse
    {
        $date_period = $request->date_period;
        $array_period = explode(' to ', $date_period);

        $commissions = EmployeeCommission::whereBetween('transaction_date', $array_period)
            ->select('employee_id', DB::raw('SUM(CONVERT(amount * quantity, DECIMAL)) as amount'))
            ->groupBy('employee_id', 'transaction_date', 'status')
            ->where('status', 'open')
            ->get();

        return $this->success([
            'data' => $commissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePayrollRequest $request
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function store(StorePayrollRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $pay_period = explode(' to ', $request->pay_period);
            $items = collect($request->line_items);
            $document = Payroll::create($this->service->formData($request, 'store'));

            $this->service->processItems($document, $items);

            $this->service->processPayroll($document, $pay_period);

            DB::commit();

            return $this->success([
                'id' => $document->id,
                'status' => 'update',
                'type' => $request->type,
            ], 'Data inserted!');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), 422, [
                'errors' => true,
                'Trace' => $exception->getTrace(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     * @throws \Exception
     */
    public function show($id): JsonResponse
    {
        $data = Payroll::where('id', '=', $id)
            ->first();

        $lineItems = $this->service->getSingleDocument($id);
        $form = $this->service->getForm();

        $result['data'] = $data;
        $result['data']['pay_period'] = ($data) ? $data->from_date . ' to ' . $data->to_date : null;
        $result['data']['line_items'] = $lineItems;
        $result['form'] = $form;
        $result['colHeaders'] = $this->service->colHeaders();
        $result['columns'] = $this->service->getColumns();
        $result['count'] = ($data) ? 1 : 0;

        return $this->success($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePayrollRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function update(UpdatePayrollRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $pay_period = explode(' to ', $request->pay_period);
            $items = collect($request->line_items);

            Payroll::where('id', '=', $id)
                ->update($this->service->formData($request, 'update'));

            $document = Payroll::find($id);

            $this->service->processPayroll($document, $pay_period);

            DB::commit();

            return $this->success([
                'id' => $document->id,
                'status' => 'update',
                'type' => $request->transaction_type,
            ], 'Data updated!');

        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), 422, [
                'errors' => true,
                'Trace' => $exception->getTrace(),
            ]);
        }
    }

    /**
     * @throws \Exception
     */
    public function printSlip(Request $request, $id): Response
    {
        $documents = Payroll::where('id', '=', $id)
            ->first();

        $lineItems = $this->service->getSingleDocument($id);

        $pdf = $this->pdfInstance($documents, $lineItems);

        $file_name = Str::upper($documents->transaction_no) . '.pdf';

        return $pdf->stream($file_name);
    }

    /**
     * @param $documents
     * @param $lineItems
     *
     * @return \Barryvdh\DomPDF\PDF
     *
     * @throws \NumberToWords\Exception\InvalidArgumentException
     * @throws \NumberToWords\Exception\NumberToWordsException
     */
    public function pdfInstance($documents, $lineItems): \Barryvdh\DomPDF\PDF
    {
        $company = $this->company();
        $numberToWords = new NumberToWords();
        $currencyTransformer = $numberToWords->getNumberTransformer((auth()->user()->locale));
        $amount = Str::upper($currencyTransformer->toWords(floatval($documents->main_account_amount)));

        App::setLocale(auth()->user()->locale);
        Carbon::setLocale(auth()->user()->locale);
        $document_date = Carbon::parse($documents->transaction_date)->isoFormat('D MMMM Y');

        return Pdf::loadView('export.payslip', compact(
            'documents',
            'company',
            'amount',
            'document_date',
            'lineItems'
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $details = Payroll::find($id);
        if ($details) {
            $details->delete();

            return $this->success([
                'errors' => false,
            ], 'Row deleted!');
        }

        return $this->error('Row not found', 422, [
            'errors' => true,
        ]);
    }
}
