<?php

namespace App\Services\Master;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request)
    {
        $pages = isset($request->page) ? (int) $request->page : 1;
        $row_data = isset($request->itemsPerPage) ? (int) $request->itemsPerPage : 20;
        $sorts = isset($request->sortBy[0]) ? (string) $request->sortBy[0] : 'users.name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'ASC';
        $search = isset($request->search) ? (string) $request->search : '';
        $select_data = isset($request->searchItem) ? (string) $request->searchItem : 'name';
        $select_role = isset($request->searchRole) ? (string) $request->searchRole : null;

        $query = User::select(
            'users.*',
            DB::raw('
                (SELECT JSON_ARRAYAGG(t1.name)
                    FROM roles AS t1
                    LEFT JOIN model_has_roles AS t2 ON t1.id = t2.role_id
               WHERE t2.model_id = users.id ) as role
            ')
        )
            ->orderBy($sorts, $order)
            ->when($select_data, function ($query) use ($select_data, $search, $select_role) {
                $data_query = $query;
                switch ($select_data) {
                    case 'Username':
                        $data_query->where('username', 'LIKE', '%'.$search.'%');
                        break;
                    case 'Name':
                    default:
                        $data_query->where('name', 'LIKE', '%'.$search.'%');
                        break;
                }

                if (isset($select_role)) {
                    $data_query->role($select_role);
                }

                return $data_query;
            })
            ->with(['entity'])
            ->paginate($row_data);

        return $query;
    }

    /**
     * @param $request
     * @param $type
     * @return array
     */
    public function formData($request, $type): array
    {
        $data = $request->all();
        Arr::forget($data, 'id');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'destroyed_at');
        Arr::forget($data, 'remember_token');
        Arr::forget($data, 'two_factor_secret');
        Arr::forget($data, 'two_factor_recovery_codes');
        Arr::forget($data, 'role');
        Arr::forget($data, 'entity');

        return $data;
    }
}
