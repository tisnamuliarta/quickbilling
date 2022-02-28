<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\MasterData;
use App\Traits\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MasterUserController extends Controller
{
    use MasterData, RolePermission;

    /**
     * MasterUserController constructor.
     */
    public function __construct()
    {
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
        $options = $request->options;
        $pages = isset($options->page) ? (int)$options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 20;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "users.name";
        $order = isset($options->sortDesc[0]) ? "DESC" : "ASC";
        $search = isset($request->search) ? (string)$request->search : "";
        $select_data = isset($request->searchItem) ? (string)$request->searchItem : "name";
        $select_role = isset($request->searchRole) ? (string)$request->searchRole : null;

        $offset = ($pages - 1) * $row_data;

        $result = array();
        $query = User::select(
            'users.*',
            DB::raw("
                (SELECT JSON_ARRAYAGG(t1.name)
                    FROM roles AS t1
                    LEFT JOIN model_has_roles AS t2 ON t1.id = t2.role_id
               WHERE t2.model_id = users.id ) as role
            ")
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
                        $data_query->where('username', 'LIKE', '%' . $search . '%');
                        break;
                    case 'Name':
                        $data_query->where('name', 'LIKE', '%' . $search . '%');
                        break;
                }

                if (isset($select_role)) {
                    $data_query->role($select_role);
                }

                return $data_query;
            });

        $result["total"] = $query->count();

        $all_data = $query->offset($offset)
            ->limit($row_data)
            ->get();

        $result = array_merge($result, [
            "rows" => $all_data,
            "filter" => ['Username', 'Name'],
        ]);
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($this->validation($request)) {
            return $this->error($this->validation($request), 422, [
                "errors" => true
            ]);
        }

        $form = $request->form;
//        $roles = $request->form['role'];
//        foreach ($roles as $role) {
//            return response()->json($role['id']);
//        }
        //return response()->json($form);

        DB::beginTransaction();
        try {
            $data = [
                'username' => $form['username'],
                'name' => $form['name'],
                'password' => bcrypt($form['password']),
                'email' => $form['email'],
                'is_active' => $form['is_active'],
            ];

            $user = User::create($data);

            $this->storeUserDetails($request, $user);

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
     * @param $request
     * @return false|string
     */
    protected function validation($request)
    {
        $messages = [
            'form.username' => 'Username Field is required!',
            'form.email' => 'Email Field is required!',
            'form.name' => 'Name Access Field is required!',
            'form.role' => 'Role Field is required!',
            'form.is_active' => 'Status is required!',
        ];
        $user_id = $request->form['id'];
        $validator = Validator::make($request->all(), [
            'form.username' => 'required|unique:users,username,' . $user_id,
            'form.email' => 'required|unique:users,email,' . $user_id,
            'form.name' => 'required',
            'form.role' => 'required',
            'form.is_active' => 'required',
        ], $messages);


        $string_data = "";
        if ($validator->fails()) {
            foreach (collect($validator->messages()) as $error) {
                foreach ($error as $items) {
                    $string_data .= $items . " \n  ";
                }
            }
            return $string_data;
        } else {
            return false;
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
        if (intval($id)) {
            $user = User::where("user_id", "=", $id)->first();
            return response()->json([
                "sub_id" => [
                    "U_UserName" => $user['U_UserName'],
                    "user_id" => $user['user_id'],
                ]
            ]);
        } else {
            return response()->json([
                "sub_id" => [
                    "U_UserName" => null,
                    "user_id" => null,
                ]
            ]);
        }
    }

    /**
     * @param $request
     * @param $user
     */
    protected function storeUserDetails($request, $user)
    {
        $this->storeUserRole($request, $user);
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
        if ($this->validation($request)) {
            return $this->error($this->validation($request), 422, [
                "errors" => true
            ]);
        }

        $form = $request->form;

        try {
            $data = [
                'username' => $form['username'],
                'name' => $form['name'],
                'email' => $form['email'],
                'is_active' => $form['is_active'],
            ];

            User::where("id", "=", $id)->update($data);

            $user = User::find($id);

            $this->storeUserDetails($request, $user);

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
