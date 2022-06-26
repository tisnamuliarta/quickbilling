<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreUserRequest;
use App\Models\User;
use App\Services\Master\UserService;
use App\Traits\MasterData;
use App\Traits\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterUserController extends Controller
{
    use MasterData, RolePermission;

    protected UserService $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
        // $this->middleware(['direct_permission:Users-index'])->only(['index', 'show']);
        $this->middleware(['direct_permission:Users-store'])->only(['index', 'show', 'store']);
        $this->middleware(['direct_permission:Users-edits'])->only('update');
        $this->middleware(['direct_permission:Users-erase'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = [];
        $result['form'] = $this->form('users');
        $result = array_merge($result, $this->service->index($request));

        return $this->success($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreUserRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $role = $request->role;
            $user = User::create($this->service->formData($request, 'store'));

            $this->storeUserDetails($role, $user);

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
     * @param $roles
     * @param $user
     */
    protected function storeUserDetails($roles, $user)
    {
        $this->storeUserRole($roles, $user);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);

        return $this->success($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreUserRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(StoreUserRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $role = $request->role;
            $user = User::find($id);
            $forms = collect($this->service->formData($request, 'update'));
            foreach ($forms as $index => $form) {
                $user->$index = $form;
            }
            $user->save();

            $this->storeUserDetails($role, $user);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $details = User::where('id', '=', $id)->first();
        if ($details) {
            User::where('id', '=', $id)->delete();

            return $this->success([
                'errors' => false,
            ], 'Row deleted!');
        }

        return $this->error('Row not found', 422, [
            'errors' => true,
        ]);
    }
}
