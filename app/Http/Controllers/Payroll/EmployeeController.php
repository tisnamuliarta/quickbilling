<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payrolls\StoreEmployeeRequest;
use App\Models\Financial\Category;
use App\Models\Payroll\Employee;
use App\Services\Financial\AccountService;
use App\Services\Payroll\EmployeeService;
use App\Traits\Financial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    use Financial;

    public EmployeeService $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(EmployeeService $service)
    {
        $this->service = $service;
        //        $this->middleware(['direct_permission:Roles-index'])->only(['index', 'show', 'permissionRole']);
        //        $this->middleware(['direct_permission:Roles-store'])->only(['store', 'storePermissionRole']);
        //        $this->middleware(['direct_permission:Roles-edits'])->only('update');
        //        $this->middleware(['direct_permission:Roles-erase'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $result = [];
        $result['form'] = $this->form('employees');
        $result['form']['status'] = 'active';

        $result['itemGender'] = [
            ['id' => 1, 'name' => __('Male')],
            ['id' => 2, 'name' => __('Female')],
        ];

        $result['paymentMethod'] = [
            ['id' => 1, 'name' => __('Cash')],
            ['id' => 2, 'name' => __('Direct Deposit')],
        ];

        $result['payFrequency'] = ['Per Week', 'Per Month', 'Per Quarter'];

        $result['payType'] = ['Salary', 'Per Hour', 'Commission'];

        $collection = collect($this->service->index($request));
        $result = $collection->merge($result);

        return $this->success($result->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmployeeRequest $request
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $employee = Employee::create($this->service->formData($request, 'store'));

            $this->processDetails($request, $employee);

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
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $data = Employee::find($id);

        return $this->success([
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreEmployeeRequest $request
     * @param int $id
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function update(StoreEmployeeRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            Employee::where('id', '=', $id)->update($this->service->formData($request, 'update'));

            $employee = Employee::find($id);

            $this->processDetails($request, $employee);

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
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $details = Employee::find($id);
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

    /**
     * @throws \Exception
     */
    protected function processDetails($request, $employee)
    {
        $accountService = new AccountService();
        $accountType = 'OPERATING_EXPENSE';
        $accountCategory = Category::where('name', 'Cost of labor - COS')->first();

        $accountService->createAccount(
            $request->first_name . ' ' . $request->last_name,
            $accountType,
            $accountCategory->id
        );

        $account = $this->getAccountIdByName($request->first_name . ' ' . $request->last_name, 'OPERATING_EXPENSE');
        $employee->account_id = $account;
        $employee->save();
    }
}
