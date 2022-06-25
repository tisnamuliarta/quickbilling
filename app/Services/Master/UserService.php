<?php

namespace App\Services\Master;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $options = $request->options;
        $pages = isset($options->page) ? (int) $options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int) $options->itemsPerPage : 20;
        $sorts = isset($options->sortBy[0]) ? (string) $options->sortBy[0] : 'users.name';
        $order = isset($options->sortDesc[0]) ? 'DESC' : 'ASC';
        $search = isset($request->search) ? (string) $request->search : '';
        $select_data = isset($request->searchItem) ? (string) $request->searchItem : 'name';
        $select_role = isset($request->searchRole) ? (string) $request->searchRole : null;

        $offset = ($pages - 1) * $row_data;

        $result = [];
        $query = User::select(
            'users.*',
            DB::raw('
                (SELECT JSON_ARRAYAGG(t1.name)
                    FROM roles AS t1
                    LEFT JOIN model_has_roles AS t2 ON t1.id = t2.role_id
               WHERE t2.model_id = users.id ) as role
            ')
        )
            // ->leftJoin('model_has_roles as t1', 't1.model_id', 'users.id')
            // ->leftJoin('roles as t2', 't2.id', 't1.role_id')
            // ->where('roles.name', '<>', 'Personal')
            // ->distinct()
            ->orderBy($sorts, $order)
            ->when($select_data, function ($query) use ($select_data, $search, $select_role) {
                $data_query = $query;
                switch ($select_data) {
                    case 'Username':
                        $data_query->where('username', 'LIKE', '%'.$search.'%');
                        break;
                    case 'Name':
                        $data_query->where('name', 'LIKE', '%'.$search.'%');
                        break;
                    default:
                        $data_query->where('name', 'LIKE', '%'.$search.'%');
                        break;
                }

                if (isset($select_role)) {
                    $data_query->role($select_role);
                }

                return $data_query;
            });

        $result['total'] = $query->count();

        $all_data = $query->offset($offset)
            ->limit($row_data)
            ->with(['entity'])
            ->get();

        return array_merge($result, [
            'rows' => $all_data,
            'filter' => ['Username', 'Name'],
        ]);
    }

    /**
     * @param $request
     * @param $type
     * @return array
     */
    public function formData($request, $type): array
    {
        $request->request->remove('id');
        $request->request->remove('created_at');
        $request->request->remove('updated_at');
        $request->request->remove('deleted_at');
        $request->request->remove('destroyed_at');
        $request->request->remove('remember_token');
        $request->request->remove('two_factor_secret');
        $request->request->remove('two_factor_recovery_codes');
        $request->request->remove('role');
        $request->request->remove('entity');

        return $request->all();
    }
}
