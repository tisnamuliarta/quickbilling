<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Http\Requests\Production\StoreProductionRequest;
use App\Models\Productions\Production;
use App\Services\Production\ProductionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public ProductionService $service;

    /**
     * MasterUserController constructor.
     *
     * @param ProductionService $service
     */
    public function __construct(ProductionService $service)
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
     * @return JsonResponse
     */
    public function arrowAction(Request $request): JsonResponse
    {
        try {
            $type = $request->type;
            $status = $request->status;
            $document = $request->document;

            $query = Production::where('type', $type);
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

            if ($document->status == 'closed') {
                $this->service->processIssue($document, $items);
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
     * Display the specified resource.
     *
     * @param Request $request
     * @param $id
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

            if ($document->status == 'closed') {
                $this->service->processIssue($document, $items);
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
