<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\Documents\StoreDocumentRequest;
use App\Models\Documents\Document;
use App\Services\Documents\DocumentService;
use App\Services\Transactions\InventoryService;
use App\Services\Transactions\PurchaseService;
use App\Services\Transactions\SalesService;
use App\Traits\InventoryHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    use InventoryHelper;

    public DocumentService $service;
    public PurchaseService $purchase;
    public SalesService $sales;
    public InventoryService $inventory;

    /**
     * MasterUserController constructor.
     *
     * @param DocumentService $service
     * @param PurchaseService $purchase
     * @param SalesService $sales
     * @param InventoryService $inventory
     */
    public function __construct(
        DocumentService  $service,
        PurchaseService  $purchase,
        SalesService     $sales,
        InventoryService $inventory
    ) {
        $this->service = $service;
        $this->purchase = $purchase;
        $this->sales = $sales;
        $this->inventory = $inventory;
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
            return $this->success($this->service->index($request));
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

            $query = Document::where('transaction_type', $type);
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
     * @param StoreDocumentRequest $request
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function store(Request $request): JsonResponse
    {
        $this->validateRequest($request);

        $items = collect($request->line_items);
        $tax_details = collect($request->tax_details);
        $sales_persons = collect($request->sales_persons);

        $validate_details = $this->validateDetails($items, $request->transaction_type, $request->id, '');
        if ($validate_details['error']) {
            return $this->error($validate_details['message']);
        }

        DB::beginTransaction();
        try {
            // return $this->error('', 422, $this->service->formData($request, 'store'));
            $document = Document::create($this->service->formData($request, 'store'));

            $this->service->processItems($items, $document, $tax_details);

            $this->service->processSalesPerson($sales_persons, $document);

            if ($document->base_id) {
                $doc = Document::find($document->base_id);
                if ($doc) {
                    $doc->status = 'closed';
                    $doc->save();
                }
            }
            // process inventory qty
            $this->processInventory($document);

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
     * @param $document
     *
     * @return void
     * @throws \Exception
     */
    protected function processInventory($document)
    {
        switch ($document->transaction_type) {
            // purchase
            case 'PO':
                $this->purchase->purchaseOrderTransaction($document->lineItems);
                break;

            case 'GR':
                $this->purchase->goodsReceiptPurchaseOrderTransaction($document);
                break;

            case 'GN':
                $this->purchase->goodsReturnTransaction($document);
                break;
            // sales
            case 'SO':
                $this->sales->salesOrderTransaction($document->lineItems);
                break;

            case 'SD':
                $this->sales->deliveryTransaction($document);
                break;

            case 'SR':
                $this->sales->salesReturnTransaction($document);
                break;

            case 'GI':
            case 'GE':
                $this->inventory->goodsIssueTransaction($document);
                break;
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
            $type = $request->type;
            $copy_from_id = $request->copyFromId;

            if (isset($copy_from_id)) {
                if (intval($copy_from_id) != 0) {
                    $id = $copy_from_id;
                }
            }

            $data = Document::where('id', '=', $id)
                ->with([
                    'lineItems',
                    'entity',
                    'parent',
                    'child',
                    'taxDetails' => function ($query) use ($type) {
                        $query->where('type', '=', $type);
                    },
                    'salesPerson' => function ($query) use ($type) {
                        $query->where('document_type', '=', $type);
                    },
                ])
                ->first();

            $form = $this->service->getForm(($data) ? $data->transaction_type : $type);

            return $this->success([
                'data' => $data,
                'form' => $form,
                'count' => ($data) ? 1 : 0,
                'audits' => ($id != 0) ? $data->audits()->with('user')->get() : [],
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
        $data = Document::find($id);

        return $this->success([
            'audit' => $data->audits()->with('user')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDocumentRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $this->validateRequest($request);

        try {
            $action = $request->updateStatus;

            $inventory = ['GI', 'GE'];
            if (Str::contains($request->transaction_type, $inventory)) {
                throw new \Exception('Cannot change document that have been posted!', 1);
            }

            $document = Document::find($id);
            switch ($action) {
                case 'closed':
                case 'cancel':
                    $this->service->updateStatus($id, $action);
                    break;

                default:
                    $items = collect($request->line_items);
                    $tax_details = collect($request->tax_details);
                    $sales_persons = collect($request->sales_persons);

                    $validate_details = $this->validateDetails(
                        $items,
                        $request->transaction_type,
                        $request->id,
                        $action
                    );
                    if ($validate_details['error']) {
                        return $this->error($validate_details['message']);
                    }
                    $forms = collect($this->service->formData($request, 'update'));
                    //return $this->error('', 422, [$forms]);
                    foreach ($forms as $index => $form) {
                        $document->$index = $form;
                    }
                    $document->save();

                    $this->service->processItems($items, $document, $tax_details);

                    $this->service->processSalesPerson($sales_persons, $document);

                    // process inventory qty
                    $this->processInventory($document);
                    break;
            }

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
        $document = Document::find($id);
        if ($document) {
            $have_child = Document::where('base_id', $document->id)->count();
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
