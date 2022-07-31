<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ReceiptProduction;
use App\Services\Financial\AccountMappingService;
use App\Services\Inventory\IssueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IssueController extends Controller
{
    public IssueService $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(IssueService $service)
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
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $result = [];
        $result['form'] = $this->form('production_receipts');
        $result['form']['transaction_type'] = 'issue';
        $collection = collect($this->service->index($request));
        $result = $collection->merge($collection);

        return $this->success($result->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            ReceiptProduction::create($this->service->formData($request, 'store'));

            $accountMapping = new AccountMappingService();
            $account_id = $accountMapping->getAccountByName('WIP Inventory Account')->account_id;
            // process issue for production
            $this->service->processIssue($document, $items, 'Issue for production based on ', $account_id);

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $copy_from_id = $request->copyFromId;

            if (isset($copy_from_id)) {
                if (intval($copy_from_id) != 0) {
                    $id = $copy_from_id;
                }
            }

            $data = ReceiptProduction::where('id', '=', $id)
                ->with(['lineItems', 'account'])
                ->first();

            $form = $this->service->getForm('PE');

            return $this->success([
                'data' => $data,
                'count' => ($data) ? 1 : 0,
                'form' => $form
            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                'trace' => $exception->getTrace(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function update(Request $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            ReceiptProduction::where('id', '=', $id)->update($this->service->formData($request, 'update'));

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
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $details = ReceiptProduction::find($id);
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
