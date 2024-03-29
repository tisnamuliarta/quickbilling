<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreWarehouseRequest;
use App\Http\Requests\Inventory\UpdateWarehouseRequest;
use App\Models\Inventory\Warehouse;
use App\Services\Financial\AccountMappingService;
use App\Services\Inventory\WarehouseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public WarehouseService $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(WarehouseService $service)
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
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $accountMapping = new AccountMappingService();
        $result = [];
        $result['form'] = $this->form('warehouses');
        $result['form']['inventory_account_id'] = $accountMapping->getAccountByName('Inventory Account')->account_id;
        $collection = collect($this->service->index($request));
        $result = $collection->merge($result);

        return $this->success($result->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreWarehouseRequest  $request
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function store(StoreWarehouseRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            Warehouse::create($this->service->formData($request, 'store'));

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
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $data = Warehouse::find($id);

        return $this->success([
            'rows' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateWarehouseRequest  $request
     * @param  int  $id
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function update(UpdateWarehouseRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            Warehouse::where('id', '=', $id)
                ->update($this->service->formData($request, 'update'));
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
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $details = Warehouse::find($id);
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
