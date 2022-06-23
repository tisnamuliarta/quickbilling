<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Documents\Document;
use App\Services\Documents\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public DocumentService $service;

    /**
     * MasterUserController constructor.
     *
     * @param \App\Services\Documents\DocumentService $service
     */
    public function __construct(DocumentService $service)
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->success($this->service->index($request));
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                $exception->getTrace()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrowAction(Request $request)
    {
        try {
            $type = $request->type;
            $status = $request->status;
            $document = $request->document;

            $query = Document::where('type', $type);
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
                'id' => $row->id
            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validation = $this->validation($request, [
            'document_number' => 'required',
            'contact_id' => 'required',
        ]);
        if ($validation) {
            return $this->error($validation);
        }

        $items = collect($request->items);
        $tax_details = collect($request->tax_details);

        $validate_details = $this->validateDetails($items);
        if ($validate_details['error']) {
            return $this->error($validate_details['message']);
        }

        DB::beginTransaction();
        try {
            $document = Document::create($this->service->formData($request, 'store'));

            if ($document->parent_id !== 0) {
                $doc = Document::find($document->parent_id);
                if ($doc) {
                    $doc->status = 'closed';
                    $doc->save();
                }
            }

            $this->service->processItems($items, $document, $tax_details);

            DB::commit();
            return $this->success([
                "id" => $document->id,
                "status" => "update",
                "type" => $request->type
            ], 'Data inserted!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage(), 422, [
                "errors" => true,
                "Trace" => $exception->getTrace()
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $type = $request->type;
            $copy_from_id = $request->copyFromId;

            if (isset($copy_from_id)) {
                if (intval($copy_from_id) != 0) {
                    $id = $copy_from_id;
                }
            }

            $data = Document::where("id", "=", $id)
                ->with(['items', 'taxDetails', 'entity', 'parent', 'child'])
                ->first();

            $form = $this->service->getForm(($data) ? $data->type : $type);

            return $this->success([
                'rows' => $data,
                'form' => $form,
                'count' => ($data) ? 1 : 0,
                'action' => ($id != 0) ? $this->service->mappingAction($type, $id) : [],
                'audits' => ($id != 0) ? $data->audits()->with('user')->get() : []
            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                'trace' => $exception->getTrace()
            ]);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAudit($id)
    {
        $data = Document::find($id);
        return $this->success([
            'audit' => $data->audits()->with('user')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request, int $id)
    {
        $validation = $this->validation($request, [
            'document_number' => 'required',
            'contact_id' => 'required',
        ]);
        if ($validation) {
            return $this->error($validation);
        }

        $items = collect($request->items);
        $tax_details = collect($request->tax_details);

        $validate_details = $this->validateDetails($items);
        if ($validate_details['error']) {
            return $this->error($validate_details['message']);
        }

        try {
            // Document::where("id", "=", $id)->update($this->service->formData($request, 'update'));

            $document = Document::find($id);
            $forms = collect($this->service->formData($request, 'update'));
            foreach ($forms as $index => $form) {
                $document->$index = $form;
            }
            $document->save();

            $this->service->processItems($items, $document, $tax_details);

            DB::commit();
            return $this->success([
                "id" => $document->id,
                "status" => "update",
                "type" => $request->type
            ], 'Data updated!');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                "errors" => true,
                "Trace" => $exception->getTrace()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $document = Document::find($id);
        if ($document) {
            $document->delete();
            return $this->success([
                "errors" => false
            ], 'Row deleted!');
        }

        return $this->error('Row not found', 422, [
            "errors" => true
        ]);
    }
}
