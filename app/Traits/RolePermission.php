<?php

namespace App\Traits;

use App\Models\Master\Permission;
use App\Models\Master\Role;
use App\Models\User;

trait RolePermission
{
    /**
     * @param $parent_id
     * @param $menu_name
     * @return mixed
     */
    protected function getLatestMenuByParent($parent_id, $menu_name)
    {
        return Permission::where('parent_id', '=', $parent_id)
            // ->where('menu_name', '=', $menu_name)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * @param $request
     * @param $order_line
     * @param  string  $suffix
     * @param  string  $insert_role
     * @return void
     */
    protected function generatePermission($request, $order_line, string $suffix = '-index', string $insert_role = 'N')
    {
        $data = [
            'name' => $request->name,
            'menu_name' => $request->menu_name,
            'parent_id' => $request->parent_id,
            'icon' => $request->icon,
            'route_name' => $request->route_name,
            'has_child' => $request->has_child,
            'has_route' => $request->has_route,
            'order_line' => $order_line,
            'is_crud' => $request->is_crud,
            'guard_name' => $request->guard_name,
        ];

        if ($request->is_crud == 'Y') {
            $suffix = ['index', 'store', 'edits', 'erase'];

            foreach ($suffix as $value) {
                $data['name'] = $request->name.'-'.$value;
                $old_name = $request->old_name.'-'.$value;

                $permission = Permission::where('name', '=', $old_name)->first();
                if ($permission) {
                    Permission::where('id', '=', $permission->id)->update($data);
                    $permission = Permission::where('name', '=', $data['name'])->first();
                } else {
                    $permission = Permission::create($data);
                }

                $this->assignPermissionToRole($permission, $insert_role, $request);
            }
        } else {
            $data['name'] = $request->name.$suffix;
            $old_name = $request->old_name.$suffix;
            $permission = Permission::where('name', '=', $old_name)->first();

            if ($permission) {
                Permission::where('id', '=', $permission->id)->update($data);
                $permission = Permission::where('name', '=', $data['name'])->first();
            } else {
                $permission = Permission::create($data);
            }

            $this->assignPermissionToRole($permission, $insert_role, $request);
        }
    }

    /**
     * @param $permission
     * @param $insert_role
     * @param $request
     */
    protected function assignPermissionToRole($permission, $insert_role, $request)
    {
        if ($insert_role == 'Y') {
            foreach ($request->role as $item) {
                $role = Role::where('name', '=', $item)->first();
                $permissions = Permission::where('id', '=', $permission->id)->first();

                $permissions->assignRole($role->name);

                $this->assignPermissionToUser($permission, $role);
            }
        }
    }

    /**
     * @param $permission
     * @param $role
     */
    protected function assignPermissionToUser($permission, $role)
    {
        $users = User::leftJoin('model_has_roles', 'model_has_roles.model_id', 'users.id')
            ->select('users.*')
            ->where('model_has_roles.role_id', '=', $role->id)
            ->get();

        if ($users) {
            foreach ($users as $user) {
                $user->givePermissionTo($permission->name);
            }
        }
    }

    /**
     * @param $role
     * @param $detail
     * @param $key
     */
    protected function actionStoreRolePermission($role, $detail, $key)
    {
        $permission = Permission::where('name', $detail['permission'].'-'.$key)
            ->first();

        if ($permission) {
            if ($detail[$key] == 'Y') {
                $role->givePermissionTo($detail['permission'].'-'.$key);
            } else {
                $role->revokePermissionTo($detail['permission'].'-'.$key);
            }
        }
    }

    /**
     * @param $role
     * @param $detail
     * @param $key
     */
    protected function actionRemovePermission($role, $detail, $key)
    {
        $permission = Permission::where('name', $detail['permission'].'-'.$key)
            ->first();

        if ($permission) {
            $role->revokePermissionTo($detail['permission'].'-'.$key);
        }
    }

    /**
     * @param $request
     * @param $role_name
     * @return bool
     */
    protected function checkRole($request, $role_name)
    {
        $roles = $request->user()->roles;

        foreach ($roles as $role) {
            if ($role->name == $role_name) {
                return true;
            }
        }

        return false;
    }
}
