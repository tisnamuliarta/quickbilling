<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Services\Production\ProductionIssueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductionReceiptController extends Controller
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
            return $this->success($this->service->index($request, 'PR'));
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                $exception->getTrace(),
            ]);
        }
    }
}
