<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Master\UserService;
use App\Traits\MasterData;
use App\Traits\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MasterUserController extends Controller
{
    use MasterData, RolePermission;

    protected $service;

    /**
     * MasterUserController constructor.
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
        $this->middleware(['direct_permission:Users-index'])->only(['index', 'show']);
        $this->middleware(['direct_permission:Users-store'])->only('store');
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $request->id;
        $validation = $this->validation($request, [
            'entity_id' => 'required',
            'username' => 'required|unique:users,username,' . $user_id,
            'email' => 'required|unique:users,email,' . $user_id,
            'name' => 'required',
            'role' => 'required',
            'enabled' => 'required',
        ]);
        if ($validation) {
            return $this->error($validation);
        }

        DB::beginTransaction();
        try {
            $role = $request->role;
            $user = User::create($this->service->formData($request, 'store'));

            $this->storeUserDetails($role, $user);

            DB::commit();

            return $this->success([
                "errors" => false
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
     * @param $roles
     * @param $user
     */
    protected function storeUserDetails($roles, $user)
    {
        $this->storeUserRole($roles, $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $user_id = $request->id;
        $validation = $this->validation($request, [
            'entity_id' => 'required',
            'username' => 'required|unique:users,username,' . $user_id,
            'email' => 'required|unique:users,email,' . $user_id,
            'name' => 'required',
            'role' => 'required',
            'enabled' => 'required',
        ]);
        if ($validation) {
            return $this->error($validation);
        }

        $form = $request->form;

        try {
            $role = $request->role;
            $user = User::find($id);
            $forms = collect($this->service->formData($request, 'update'));
            foreach ($forms as $index => $form) {
                $user->$index = $form;
            }
            $user->save();

            $this->storeUserDetails($role, $user);

            return $this->success([
                "errors" => false
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
    public function destroy($id)
    {
        $details = User::where("id", "=", $id)->first();
        if ($details) {
            User::where("id", "=", $id)->delete();
            return $this->success([
                "errors" => false
            ], 'Row deleted!');
        }

        return $this->error('Row not found', 422, [
            "errors" => true
        ]);
    }
}
