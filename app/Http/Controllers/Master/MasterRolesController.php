<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class MasterRolesController extends Controller
{
    use RolePermission;

    /**
     * MasterUserController constructor.
     */
    public function __construct()
    {
        $this->middleware(['direct_permission:Roles-index'])->only(['index', 'show', 'permissionRole']);
        $this->middleware(['direct_permission:Roles-store'])->only([
            'store', 'storePermissionRole', 'index', 'show', 'permissionRole',
        ]);
        $this->middleware(['direct_permission:Roles-edits'])->only('update');
        $this->middleware(['direct_permission:Roles-erase'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $options = $request->options;
        $pages = isset($options->page) ? (int) $options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int) $options->itemsPerPage : 20;
        $sorts = isset($options->sortBy[0]) ? (string) $options->sortBy[0] : 'name';
        $order = isset($options->sortDesc[0]) ? (string) $options->sortDesc[0] : 'desc';
        $offset = ($pages - 1) * $row_data;

        $result = [];
        $query = Role::selectRaw("*, 'actions' as ACTIONS");

        $result['total'] = $query->count();

        $all_data = $query->offset($offset)
            ->orderBy($sorts, $order)
            ->limit($row_data)
            ->get();

        $arr_rows = Role::pluck('name');

        $result = array_merge($result, [
            'rows' => $all_data,
            'simple' => $arr_rows,
        ]);

        return $this->success($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validation = $this->validation($request, [
            'form.name' => 'Name is required!',
        ]);
        if ($validation) {
            return $this->error($validation);
        }

        $form = $request->form;
        try {
            $data = [
                'name' => $form['name'],
                'guard_name' => 'web',
                'description' => $form['description'],
            ];
            Role::create($data);

            return $this->success([
                'errors' => false,
            ], 'Data inserted!');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                'errors' => true,
                'Trace' => $exception->getTrace(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $data = Role::where('id', '=', $id)->get();

        return $this->success([
            'rows' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validation = $this->validation($request, [
            'form.name' => 'Name is required!',
        ]);
        if ($validation) {
            return $this->error($validation);
        }

        $form = $request->form;
        try {
            $data = [
                'name' => $form['name'],
                'guard_name' => 'web',
                'description' => $form['description'],
            ];

            Role::where('id', '=', $id)->update($data);

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
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $details = Role::where('id', '=', $id)->first();
        if ($details) {
            Role::where('id', '=', $id)->delete();

            return $this->success([
                'errors' => false,
            ], 'Row deleted!');
        }

        return $this->error('Row not found', 422, [
            'errors' => true,
        ]);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissionRole(Request $request)
    {
        $form = json_decode($request->form);
        $role = Role::where('name', $form->name)->first();
        $permissions = DB::select('call sp_role_permissions ('.$role->id.')');

        return $this->success([
            'rows' => $permissions,
        ]);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePermissionRole(Request $request)
    {
        $details = collect($request->details);
        DB::beginTransaction();
        try {
            $role = Role::where('name', $request->form['name'])->first();
            foreach ($details as $detail) {
                // return $detail['permission'] . '-index';
                $this->actionStoreRolePermission($role, $detail, 'index');
                $this->actionStoreRolePermission($role, $detail, 'store');
                $this->actionStoreRolePermission($role, $detail, 'edits');
                $this->actionStoreRolePermission($role, $detail, 'erase');

                foreach ($role->users as $item) {
                    $user = User::find($item->id);
                    $this->actionStoreRolePermission($user, $detail, 'index');
                    $this->actionStoreRolePermission($user, $detail, 'store');
                    $this->actionStoreRolePermission($user, $detail, 'edits');
                    $this->actionStoreRolePermission($user, $detail, 'erase');
                }
            }

            DB::commit();

            return $this->success([], 'Data Updated');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), 422, [
                'errors' => true,
                'Trace' => $exception->getTrace(),
            ]);
        }
    }
}
