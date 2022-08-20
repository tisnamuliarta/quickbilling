<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreItemRequest;
use App\Models\Inventory\Resource;
use App\Services\Inventory\ResourceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
    public ResourceService $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(ResourceService $service)
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
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $result = [];
        $result['form'] = $this->form('resources');
        $result['form']['status'] = 'active';
        $result['url'] = url('/');

        $collection = collect($this->service->index($request));
        $result = $collection->merge($result);

        return response()->json($result->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreItemRequest  $request
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function store(StoreItemRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $item = Resource::create($this->service->formData($request, 'store'));

            // $this->processItemDetails($category, $item['id']);

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
     * @return JsonResponse
     */
    public function show($product)
    {
        $data = Resource::find($product);

        return $this->success([
            'rows' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreItemRequest  $request
     * @param $id
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function update(StoreItemRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = Resource::find($id);
            $forms = collect($this->service->formData($request, 'update', $id));
            //return $this->error('', 422, [$forms]);
            foreach ($forms as $index => $form) {
                $data->$index = $form;
            }
            $data->save();


            // $this->processItemDetails($category, $id);

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
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $details = Resource::find($id);
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
