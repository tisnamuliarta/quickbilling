<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ItemUnit;
use App\Services\Inventory\ItemUnitService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemUnitController extends Controller
{
    public ItemUnitService $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(ItemUnitService $service)
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
        $result['form'] = $this->form('item_units');
        $result = array_merge($result, $this->service->index($request));

        return $this->success($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validation = $this->validation($request, [
            'name' => 'required|unique:item_units',
        ]);
        if ($validation) {
            return $this->error($validation, 422, [
                'errors' => true,
            ]);
        }

        DB::beginTransaction();
        $form = $request->form;
        try {
            ItemUnit::create($this->service->formData($request, 'store'));

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
        $data = ItemUnit::where('id', '=', $id)->get();

        return $this->success([
            'rows' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validation = $this->validation($request, [
            'name' => 'required',
        ]);
        if ($validation) {
            return $this->error($validation, 422, [
                'errors' => true,
            ]);
        }

        try {
            ItemUnit::where('id', '=', $id)->update($this->service->formData($request, 'update'));

            return $this->success([
                'errors' => false,
            ], 'Data updated!');
        } catch (\Exception $exception) {
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
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $details = ItemUnit::find($id);
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
