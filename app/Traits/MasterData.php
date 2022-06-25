<?php

namespace App\Traits;

use App\Models\Master\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

trait MasterData
{
    use RolePermission;

    /**
     * @param $roles
     * @param $user
     */
    protected function storeUserRole($roles, $user)
    {
        $user_roles = User::where('id', '=', $user->id)->first();
        foreach ($user_roles->roles as $value) {
            foreach ($roles as $role) {
                $id = array_key_exists('id', (array) $role) ? $role['id'] : $role;
                $role_id = Role::where('name', '=', $id)->first();
                if ($value->name != $role_id->name) {
                    DB::table('model_has_roles')
                        ->where('model_id', '=', $user->id)
                        ->where('model_type', '=', 'App\Models\User')
                        ->delete();
                    $permissions = DB::select('call sp_role_permissions ('.$role_id->id.')');
                    foreach ($permissions as $permission) {
                        $this->actionRemovePermission($user, (array) $permission, 'index');
                        $this->actionRemovePermission($user, (array) $permission, 'store');
                        $this->actionRemovePermission($user, (array) $permission, 'edits');
                        $this->actionRemovePermission($user, (array) $permission, 'erase');
                    }
                }
            }
        }

        foreach ($roles as $role) {
            $id = array_key_exists('id', (array) $role) ? $role['id'] : $role;
            $this->processUserRolePermission($id, $user);
        }
    }

    /**
     * @param $id
     * @param $user
     * @return void
     */
    protected function processUserRolePermission($id, $user)
    {
        $role_id = Role::where('name', '=', $id)->first();
        $permissions = DB::select('call sp_role_permissions ('.$role_id->id.')');
        $user->assignRole($role_id->name);

        foreach ($permissions as $permission) {
            $this->actionStoreRolePermission($user, (array) $permission, 'index');
            $this->actionStoreRolePermission($user, (array) $permission, 'store');
            $this->actionStoreRolePermission($user, (array) $permission, 'edits');
            $this->actionStoreRolePermission($user, (array) $permission, 'erase');
        }
    }
}
