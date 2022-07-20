<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\Financial\Account;
use App\Models\Financial\AccountMapping;
use App\Services\Financial\AccountMappingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountMappingController extends Controller
{
    public AccountMappingService $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(AccountMappingService $service)
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
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $mapping = AccountMapping::select('*')
            ->orderBy('type')
            ->get();
        $result = [];
        foreach ($mapping as $item) {
            $result[] = [
                'id' => $item->id,
                'account_id' => $item->account_id,
                'type' => $item->type,
                'name' => $item->name,
                'account' => ($item->account) ? $item->account->code : null,
                'account_name' => ($item->account) ? $item->account->name : null
            ];
        }

        return $this->success([
            'data' => $result,
            'colHeaders' => $this->service->colHeaders(),
            'columns' => $this->service->columns(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        $form = $request->all();
        try {
            foreach ($form as $item) {
                // throw new \Exception($item['id'], 1);
                AccountMapping::where('id', $item['id'])
                    ->update([
                        'account_id' => $item['account_id'],
                        'name' => $item['name'],
                        'type' => $item['type'],
                    ]);
            }

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
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $data = Account::where('id', '=', $id)->get();

        return $this->success([
            'rows' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validation = $this->validation($request, [
            'form.name' => 'Name is required!',
            'form.number' => 'Account Number is required!',
        ]);
        if ($validation) {
            return $this->error($validation, 422, [
                'errors' => true,
            ]);
        }

        $form = $request->form;
        try {
            Account::where('id', '=', $id)->update($this->service->formData($request));

            return $this->success([
                'errors' => false,
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
    public function destroy($id): JsonResponse
    {
        $details = Account::where('id', '=', $id)->first();
        if ($details) {
            Account::where('id', '=', $id)->delete();

            return $this->success([
                'errors' => false,
            ], 'Row deleted!');
        }

        return $this->error('Row not found', 422, [
            'errors' => true,
        ]);
    }
}
