<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Http\Requests\Production\StoreProductionRequest;
use App\Models\Productions\Production;
use App\Services\Financial\AccountMappingService;
use App\Services\Inventory\IssueService;
use App\Services\Production\ProductionService;
use App\Traits\Financial;
use App\Traits\InventoryHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    use InventoryHelper;
    use Financial;

    public ProductionService $service;
    public IssueService $issue;

    /**
     * MasterUserController constructor.
     *
     * @param ProductionService $service
     * @param IssueService $issue
     */
    public function __construct(ProductionService $service, IssueService $issue)
    {
        $this->service = $service;
        $this->issue = $issue;
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
        try {
            $result['form'] = $this->form('productions');
            $result['form']['line_items'] = [];
            $collect = collect($this->service->index($request));
            $result = $collect->merge($result);

            return $this->success($result->all());
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                $exception->getTrace(),
            ]);
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function arrowAction(Request $request): JsonResponse
    {
        try {
            $type = $request->type;
            $status = $request->status;
            $document = $request->document;

            $query = Production::where('transaction_type', $type);
            $row = [];
            if ($status == 'prev') {
                $row = $query->where('id', '<', $document)
                    ->orderBy('id', 'desc')
                    ->first();
            } elseif ($status == 'next') {
                $row = $query->where('id', '>', $document)->first();
            }

            if (!$row) {
                return $this->error('Document not found', 404);
            }

            return $this->success([
                'id' => $row->id,
            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductionRequest $request
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function store(StoreProductionRequest $request): JsonResponse
    {
        $items = collect($request->line_items);

        $validate_details = $this->validateDetails($items);
        if ($validate_details['error']) {
            return $this->error($validate_details['message']);
        }

        DB::beginTransaction();
        try {
            // return $this->error('', 422, $this->service->formData($request, 'store'));
            $document = Production::create($this->service->formData($request, 'store'));

            $this->service->processItems($items, $document);

            if ($document->base_id) {
                $doc = Production::find($document->base_id);
                if ($doc) {
                    $doc->status = 'closed';
                    $doc->save();
                }
            }

            $this->processIssueForProduction($document, $items);

            DB::commit();

            return $this->success([
                'id' => $document->id,
                'status' => 'update',
                'type' => $request->type,
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
     * @param $details
     *
     * @return array
     * @throws \Exception
     */
    protected function validateDetails($details): array
    {
        if (count($details) == 0) {
            return ['error' => true, 'message' => 'Details cannot empty!'];
        }

        foreach ($details as $index => $detail) {
            $lines = $index + 1;

            if (!array_key_exists('item_id', $detail)) {
                return ['error' => true, 'message' => "Line ${lines}: Item cannot empty!"];
            } elseif (empty($detail['item_id'])) {
                return ['error' => true, 'message' => "Line ${lines}: Item cannot empty!"];
            }

            if (!array_key_exists('whs_code', $detail)) {
                return ['error' => true, 'message' => "Line ${lines}: Warehouse cannot empty!"];
            } elseif (empty($detail['whs_code'])) {
                return ['error' => true, 'message' => "Line ${lines}: Warehouse cannot empty!"];
            }

            if (!array_key_exists('item_type', $detail)) {
                return ['error' => true, 'message' => "Line ${lines}: Item type empty!"];
            } elseif (empty($detail['item_type'])) {
                return ['error' => true, 'message' => "Line ${lines}: Item type empty!"];
            }

            if ($detail['item_type'] == 'item') {
                $whs_id = $this->getWhsIdByName($detail['whs_code']);
                $item_id = $detail['item_id'];

                $item_whs = $this->getItemWarehouse($item_id, $whs_id);

                if ($item_whs->available_qty <= 0) {
                    throw new \Exception("Line ${lines}: available quantity must greater than 0!", 1);
                }
            }

            if (empty($detail['base_qty'])) {
                return ['error' => true, 'message' => "Line ${lines}: Quantity cannot empty!"];
            }
            if ($detail['base_qty'] == 0) {
                return ['error' => true, 'message' => "Line ${lines}: Quantity cannot 0!"];
            }
        }

        return ['error' => false];
    }

    /**
     * @param $document
     * @param $items
     *
     * @return void
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     * @throws \Exception
     */
    protected function processIssueForProduction($document, $items)
    {
        if ($document->status == 'closed') {
            // calculate cost after receipt
            $item_warehouse = $this->getItemWarehouse($document->item_id, $document->warehouse_id);
            if (!$item_warehouse) {
                throw new \Exception('Item warehouse not found', 1);
            }

//            $price = $document->commission_rate;
//            $quantity = $document->planned_qty;
//
//            $prev_cost = round(floatval($item_warehouse->item_cost), 2);
//
//            $temp_cost = round($quantity * $price, 2);
//
//            $item_cost = ($item_warehouse->available_qty != 0) ?
//                round(($temp_cost + $prev_cost) / $item_warehouse->available_qty, 2) : $price;
//
//            $item_warehouse->item_cost = $item_cost;
//            $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty + $quantity;
//            $item_warehouse->save();


            $accountMapping = new AccountMappingService();
            $account_id = $accountMapping->getAccountByName('WIP Inventory Account')->account_id;
            // process issue for production
            $this->issue->processIssue($document, $items, 'Issue for production based on ', $account_id);

            //process receive from production
            $account_id = $accountMapping->getAccountByName('WIP Inventory Account')->account_id;
            $account_line = (isset($document->item->inventory_account)) ? $document->item->inventory_account
                : $accountMapping->getAccountByName('Inventory Account')->account_id;

            $this->issue->processReceipt($document, $account_line, 'Receipt production base on ', $account_id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param         $id
     *
     * @return JsonResponse
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

            $data = Production::where('id', '=', $id)
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
     * @param $id
     *
     * @return JsonResponse
     */
    public function getAudit($id): JsonResponse
    {
        $data = Production::find($id);

        return $this->success([
            'audit' => $data->audits()->with('user')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreProductionRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function update(StoreProductionRequest $request, int $id): JsonResponse
    {
        try {
            $document = Production::find($id);
            $items = collect($request->line_items);

            $validate_details = $this->validateDetails($items);
            if ($validate_details['error']) {
                return $this->error($validate_details['message']);
            }
            $forms = collect($this->service->formData($request, 'update'));
            //return $this->error('', 422, [$forms]);
            foreach ($forms as $index => $form) {
                $document->$index = $form;
            }
            $document->save();

            $this->service->processItems($items, $document);

            $this->processIssueForProduction($document, $items);

            DB::commit();

            return $this->success([
                'id' => $document->id,
                'status' => 'update',
                'type' => $request->transaction_type,
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
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $document = Production::find($id);
        if ($document) {
            $have_child = Production::where('base_id', $document->id)->count();
            if ($have_child > 0) {
                return $this->error('Cannot delete because document have relationship with other document!');
            } else {
                $document->delete();

                return $this->success([
                    'errors' => false,
                ], 'Row deleted!');
            }
        }

        return $this->error('Row not found', 422, [
            'errors' => true,
        ]);
    }
}
