<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payrolls\StorePayrollRequest;
use App\Http\Requests\Payrolls\UpdatePayrollRequest;
use App\Models\Payroll\Payroll;
use App\Services\Payroll\PayrollService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
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

    public function payPeriod(Request $request)
    {
        //
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
            Payroll::create($this->service->formData($request));

            DB::commit();

            return $this->success([
                'errors' => false,
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
     */
    public function show($id): JsonResponse
    {
        $data = Payroll::where('id', '=', $id)
            ->with(['lineItems'])
            ->first();

        $form = $this->service->getForm();
        return $this->success([
            'data' => $data,
            'form' => $form,
            'count' => ($data) ? 1 : 0,
            'colHeaders' => $this->service->colHeaders(),
            'columns' => $this->service->getColumns()
        ]);
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
            Payroll::where('id', '=', $id)
                ->update($this->service->formData($request));
            DB::commit();

            return $this->success([
                'errors' => false,
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
