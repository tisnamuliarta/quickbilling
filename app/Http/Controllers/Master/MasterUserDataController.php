<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterUserDataController extends Controller
{
    use RolePermission;

    // Menu

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userPermission(Request $request)
    {
        $form = json_decode($request->form);
        $user = User::find($form->id);
        $permissions = DB::select('call sp_user_permissions (' . $user->id . ')');

        return $this->success([
            'data' => $permissions,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function storeUserPermission(Request $request)
    {
        $details = collect($request->details);
        $form = $request->form;

        DB::beginTransaction();
        try {
            $user = User::find($form['id']);

            foreach ($details as $detail) {
                $this->actionStoreRolePermission($user, $detail, 'index');
                $this->actionStoreRolePermission($user, $detail, 'store');
                $this->actionStoreRolePermission($user, $detail, 'edits');
                $this->actionStoreRolePermission($user, $detail, 'erase');
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userRoles(Request $request)
    {
        $form = $request->form;
        $user = User::find($form->id);

        return $this->success([
            'data' => $user->roles,
        ]);
    }
}
