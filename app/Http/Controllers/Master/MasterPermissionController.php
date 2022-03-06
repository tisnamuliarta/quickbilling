<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ListPermission;
use App\Traits\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class MasterPermissionController extends Controller
{
    use RolePermission;

    /**
     * MasterUserController constructor.
     */
    public function __construct()
    {
//        $this->middleware(['direct_permission:Permissions-index'])->only(['index', 'show']);
//        $this->middleware(['direct_permission:Permissions-store'])->only('store');
//        $this->middleware(['direct_permission:Permissions-edits'])->only('update');
//        $this->middleware(['direct_permission:Permissions-erase'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $options = json_decode($request->options);
        $pages = isset($options->page) ? (int)$options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 20;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "order_line";
        $order = isset($options->sortDesc[0]) ? (($options->sortDesc[0]) ? 'asc' : 'desc') : "asc";
        $offset = ($pages - 1) * $row_data;

        $result = array();
        $query = ListPermission::select('*');

        $result["total"] = $query->count();

        $parents = Permission::where('has_child', 'Y')
            //->whereIsNull('route_name')
            ->select('id', 'menu_name')
            ->get();

        $data_parent = [];
        foreach ($parents as $parent) {
            $data_parent[] = $parent->menu_name;
        }

        $all_data = $query->offset($offset)
            ->orderBy($sorts, $order)
            ->limit($row_data)
            ->get();

        $all_rows = Permission::groupBy(['menu_name'])->select('menu_name')->get();
        $arr_rows = [];
        foreach ($all_rows as $item) {
            $arr_rows[] = $item->menu_name;
        }

        $result = array_merge($result, [
            'rows' => $all_data,
            'simple' => $arr_rows,
            'parent' => $data_parent
        ]);
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
        if ($this->validation($request)) {
            return $this->error($this->validation($request), 422, [
                "errors" => true
            ]);
        }

        $form = $request->form;
        DB::beginTransaction();
        try {
            $this->processPermission($form, 'store');

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
     * @param $form
     * @param $type
     * @return void
     */
    protected function processPermission($form, $type)
    {
        $data = $this->data($form);
        $order_line = 0;
        $data_menu = $this->getLatestMenuByParent(intval($data['parent_id']), $data['menu_name']);
        if ($type == 'store') {
            if ($data_menu) {
                $line = substr($data_menu->order_line, 2, strlen($data_menu->order_line));
                if ($line) {
                    $order_line = floatval($data_menu->order_line);
                    $decimal = strlen(strrchr($data_menu->order_line, '.')) - 1;
                    $increment = '.' . str_repeat('0', $decimal - 1) . '1';
                    $order_line += $increment;
                } else {
                    $order_line = $data_menu->order_line + 1;
                }
            } else {
                $order_line++;
            }
        } else {
            $order_line = $form['order_line'];
        }
        $order_line = $form['order_line'];

        if ($form['is_crud'] == 'Y') {
            $this->generatePermission((object)$data, $order_line, '-index', 'Y');
        } else {
            if (isset($form['index'])) {
                $this->generatePermission((object)$data, $order_line, '-index', 'Y');
            }

            if (isset($form['store'])) {
                $this->generatePermission((object)$data, $order_line, '-store', 'Y');
            }

            if (isset($form['edits'])) {
                $this->generatePermission((object)$data, $order_line, '-edits', 'Y');
            }

            if (isset($form['erase'])) {
                $this->generatePermission((object)$data, $order_line, '-erase', 'Y');
            }
        }
    }


    /**
     * @param $form
     *
     * @return array
     */
    protected function data($form)
    {
        $parent = Permission::where('menu_name', $form['parent_name'])->first();
        return [
            'name' => $form['menu_name'],
            'menu_name' => $form['menu_name'],
            'parent_id' => ($parent) ? $parent->id : 0,
            'icon' => $form['icon'],
            'route_name' => $form['route_name'],
            'has_child' => $form['has_child'],
            'has_route' => $form['has_route'],
            'order_line' => $form['order_line'],
            'is_crud' => $form['is_crud'],
            'role' => $form['role'],
            'old_name' => $form['old_name'],
            'guard_name' => 'web',
        ];
    }

    /**
     * @param $request
     *
     * @return false|string
     */
    protected function validation($request)
    {
        $messages = [
            'form.menu_name' => 'Menu Name is required!',
            'form.order_line' => 'Order line field is required!',
            'form.role' => 'Role field is required!',
        ];

        $validator = Validator::make($request->all(), [
            'form.menu_name' => 'required',
            'form.order_line' => 'required',
            'form.role' => 'required',
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
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $menu_name = $request->menu_name;
        $data = DB::select("call sp_single_permission('$menu_name') ");

        return $this->success([
            'rows' => $data[0],
            'role_name' => ['superuser']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if ($this->validation($request)) {
            return $this->error($this->validation($request), 422, [
                "errors" => true
            ]);
        }

        $form = $request->form;
        DB::beginTransaction();
        try {
            $this->processPermission($form, 'update');
//            return response()->json($this->processPermission($form, 'update'), 422);

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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $details = Permission::where("id", "=", $id)->first();
        if ($details) {
            Permission::where("id", "=", $id)->delete();
            return $this->success([
                "errors" => false
            ], 'Row deleted!');
        }

        return $this->error('Row not found', 422, [
            "errors" => true
        ]);
    }
}
