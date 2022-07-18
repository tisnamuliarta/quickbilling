<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\PriceList;
use App\Services\Inventory\PriceListService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Inventory\StorePriceListRequest;
use App\Http\Requests\Inventory\UpdatePriceListRequest;

class PriceListController extends Controller
{
    public PriceListService $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(PriceListService $service)
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
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = [];
        $result['form'] = $this->form('price_lists');
        $collection = collect($this->service->index($request));
        $result = $collection->merge($result);

        return $this->success($result->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePriceListRequest  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function store(StorePriceListRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            PriceList::create($this->service->formData($request, 'store'));

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $data = PriceList::find($id);

        return $this->success([
            'rows' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePriceListRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function update(UpdatePriceListRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            PriceList::where('id', '=', $id)
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $details = PriceList::find($id);
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
