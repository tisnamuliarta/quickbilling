<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Http\Requests\Production\StoreProductionRequest;
use App\Models\Inventory\ReceiptProduction;
use App\Services\Production\ProductionIssueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionIssueController extends Controller
{
    public ProductionIssueService $service;

    /**
     * MasterUserController constructor.
     *
     * @param ProductionIssueService $service
     */
    public function __construct(ProductionIssueService $service)
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
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            return $this->success($this->service->index($request, 'PI'));
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                $exception->getTrace(),
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function arrowAction(Request $request): JsonResponse
    {
        try {
            $type = $request->type;
            $status = $request->status;
            $document = $request->document;

            $query = ReceiptProduction::where('type', $type);
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
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function store(StoreProductionRequest $request): JsonResponse
    {
        $items = collect($request->line_items);
        $tax_details = collect($request->tax_details);
        $sales_persons = collect($request->sales_persons);

        $validate_details = $this->validateDetails($items);
        if ($validate_details['error']) {
            return $this->error($validate_details['message']);
        }

        DB::beginTransaction();
        try {
            // return $this->error('', 422, $this->service->formData($request, 'store'));
            $document = ReceiptProduction::create($this->service->formData($request, 'store'));

            $this->service->processItems($items, $document, $tax_details);

            if ($document->base_id) {
                $doc = Production::find($document->base_id);
                if ($doc) {
                    $doc->status = 'closed';
                    $doc->save();
                }
            }

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
     * @return array
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

            if (empty($detail['quantity'])) {
                return ['error' => true, 'message' => "Line ${lines}: Quantity cannot empty!"];
            }
            if ($detail['quantity'] == 0) {
                return ['error' => true, 'message' => "Line ${lines}: Quantity cannot 0!"];
            }
        }

        return ['error' => false];
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param $id
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

            $data = ReceiptProduction::where('id', '=', $id)
                ->with(['lineItems', 'taxDetails', 'entity', 'parent', 'child', 'salesPerson'])
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
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function update(StoreProductionRequest $request, int $id): JsonResponse
    {
        try {
            $document = Production::find($id);
            $items = collect($request->line_items);
            $tax_details = collect($request->tax_details);

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

            $this->service->processItems($items, $document, $tax_details);
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
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $document = \App\Models\Inventory\ReceiptProduction::find($id);
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
