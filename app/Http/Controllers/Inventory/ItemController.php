<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreItemRequest;
use App\Models\File\File;
use App\Models\Inventory\Item;
use App\Services\Inventory\ItemService;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    use FileUpload;

    public ItemService $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(ItemService $service)
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = [];
        $result['form'] = $this->form('items');
        $result['form']['temp_id'] = mt_rand(100000, 999999999999);
        $result['form']['is_sell'] = true;
        $result['form']['is_purchase'] = false;
        $result['form']['inventory_account'] = 8;
        $result['form']['sell_account_id'] = 124;
        $result['form']['buy_account_id'] = 125;
        $result['url'] = url('/');

        $collection = collect($this->service->index($request));
        $result = $collection->merge($result);

        return response()->json($result->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreItemRequest  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function store(StoreItemRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $category = $request->category;

            $item = Item::create($this->service->formData($request, 'store'));

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($product)
    {
        $data = Item::find($product);

        return $this->success([
            'rows' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreItemRequest  $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function update(StoreItemRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $item = Item::where('id', '=', $id)
                ->update($this->service->formData($request, 'update', $id));

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $details = Item::find($id);
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
     * @param $category
     * @param $id
     * @return void
     */
    protected function processItemDetails($category, $id)
    {
        $this->service->processItemCategory($category, $id);

        // this->checkFile($category, $id);
    }

    /**
     * @param $request
     * @param $id
     * @return void
     */
    protected function checkFile($request, $id)
    {
        $file = File::where('fileable_type', 'item');
    }
}
