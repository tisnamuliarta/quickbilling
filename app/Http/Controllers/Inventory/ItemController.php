<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreItemRequest;
use App\Models\File\File;
use App\Models\Inventory\Item;
use App\Services\Financial\AccountMappingService;
use App\Services\Inventory\ItemService;
use App\Services\Inventory\ResourceService;
use App\Traits\Accounting;
use App\Traits\FileUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    use FileUpload;
    use Accounting;

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
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $accountMapping = new AccountMappingService();
        $result = [];
        $result['form'] = $this->form('items');
        $result['form']['temp_id'] = mt_rand(100000, 999999999999);
        $result['form']['is_sell'] = true;
        $result['form']['is_purchase'] = false;
        $result['form']['issue_method'] = 'backflush';
        $result['form']['material_type'] = 'production';
        $result['form']['valuation_method'] = 'moving average';
        $result['form']['inventory_account'] = $accountMapping->getAccountByName('Inventory Account')->account_id;
        $result['form']['revenue_account_id'] = $accountMapping->getAccountByName('Revenue Account')->account_id;
        $result['form']['expense_account_id'] = $accountMapping->getAccountByName('Expense Account')->account_id;
        $result['form']['cogs_account_id'] = $accountMapping->getAccountByName('Cost of Goods Sold Account')
            ->account_id;
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
     * @return JsonResponse
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
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function update(StoreItemRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = Item::find($id);
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
