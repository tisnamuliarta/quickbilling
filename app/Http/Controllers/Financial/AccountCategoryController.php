<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Financial\UpdateAccountCategoryRequest;
use App\Models\Financial\Category;
use App\Services\Financial\AccountCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountCategoryController extends Controller
{
    public AccountCategoryService $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(AccountCategoryService $service)
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
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = [];
        $result['form'] = $this->form('categories');
        $result['category_type_list'] = $this->getEnumValues('categories', 'category_type');
        //$result = array_merge($result, $this->service->index($request));
        $collection = collect($this->service->index($request));
        $result = $collection->merge($result);

        return $this->success($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UpdateAccountCategoryRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function store(UpdateAccountCategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            Category::create($this->service->formData($request));

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
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $data = Category::find($id);

        return $this->success([
            'rows' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAccountCategoryRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function update(UpdateAccountCategoryRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            Category::where('id', '=', $id)->update($this->service->formData($request));

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
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $details = Category::find($id);
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
