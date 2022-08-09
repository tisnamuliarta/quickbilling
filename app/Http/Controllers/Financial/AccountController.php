<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Financial\StoreAccountRequest;
use App\Services\Financial\AccountService;
use IFRS\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public AccountService $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(AccountService $service)
    {
        $this->service = $service;
        //    $this->middleware(['direct_permission:Roles-index'])->only(['index', 'show', 'permissionRole']);
        //    $this->middleware(['direct_permission:Roles-store'])->only(['store', 'storePermissionRole']);
        //    $this->middleware(['direct_permission:Roles-edits'])->only('update');
        //    $this->middleware(['direct_permission:Roles-erase'])->only('destroy');
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
        $type = isset($request->type) ? (string)$request->type : 'index';

        if ($type == 'index') {
            $result = [];
            $result['form'] = $this->form('accounts');
            $result['form']['account_type_list'] = $this->getEnumValues('accounts', 'account_type');
            //$result = array_merge($result, $this->service->index($request));
            $collection = collect($this->service->index($request));
            $result = $collection->merge($result);
        } else {
            if ($type == 'All') {
                $type = '';
            }
            $result = $this->service->dataByType($type);
        }

        return $this->success($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAccountRequest $request
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function store(StoreAccountRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            Account::create($this->service->formData($request));

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
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        $start_date = $request->date_from;
        $end_date = $request->date_to;
        $data = Account::find($id);

        $result['data'] = $data;

        $collect = $data->getTransactions($start_date, $end_date);
        $result = array_merge($result, $collect);
        //$collect->merge($data);

        return $this->success($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreAccountRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function update(StoreAccountRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            Account::where('id', '=', $id)->update($this->service->formData($request));

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
    public function destroy($id): JsonResponse
    {
        $details = Account::find($id);
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
