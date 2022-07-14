<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StorePermissionRequest;
use App\Models\Master\ListPermission;
use App\Traits\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class MasterPermissionController extends Controller
{
    use RolePermission;

    /**
     * MasterUserController constructor.
     */
    public function __construct()
    {
        // $this->middleware(['direct_permission:Permissions-index'])->only(['index', 'show']);
        $this->middleware(['direct_permission:Permissions-store'])->only(['index', 'show', 'store']);
        $this->middleware(['direct_permission:Permissions-edits'])->only('update');
        $this->middleware(['direct_permission:Permissions-erase'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $row_data = isset($request->itemsPerPage) ? (int) $request->itemsPerPage : 20;
        $sorts = isset($request->sortBy[0]) ? (string) $request->sortBy[0] : 'order_line';
        $order = isset($request->sortDesc[0]) ? (($request->sortDesc[0]) ? 'desc' : 'asc') : 'asc';
        $search = (isset($request->search)) ? $request->search : '';

        $result = [];
        $query = ListPermission::select('*')
            ->where('menu_name', 'LIKE', '%'.$search.'%')
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        $parents = Permission::where('has_child', 'Y')
            //->whereIsNull('route_name')
            ->select('id', 'menu_name')
            ->get();

        $data_parent = [];
        foreach ($parents as $parent) {
            $data_parent[] = $parent->menu_name;
        }

        $all_rows = Permission::groupBy(['menu_name'])->select('menu_name')->get();
        $arr_rows = [];
        foreach ($all_rows as $item) {
            $arr_rows[] = $item->menu_name;
        }

        $result = array_merge($result, [
            'simple' => $arr_rows,
            'parent' => $data_parent,
        ]);

        $collection = collect($query);
        $merge = $collection->merge($result);

        return $this->success($merge->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePermissionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function store(StorePermissionRequest $request): \Illuminate\Http\JsonResponse
    {
        $form = $request->all();
        DB::beginTransaction();
        try {
            $this->processPermission($form, 'store');

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
                    $increment = '.'.str_repeat('0', $decimal - 1).'1';
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
            $this->generatePermission((object) $data, $order_line, '-index', 'Y');
        } else {
            if (isset($form['index'])) {
                $this->generatePermission((object) $data, $order_line, '-index', 'Y');
            }

            if (isset($form['store'])) {
                $this->generatePermission((object) $data, $order_line, '-store', 'Y');
            }

            if (isset($form['edits'])) {
                $this->generatePermission((object) $data, $order_line, '-edits', 'Y');
            }

            if (isset($form['erase'])) {
                $this->generatePermission((object) $data, $order_line, '-erase', 'Y');
            }
        }
    }

    /**
     * @param $form
     * @return array
     */
    protected function data($form): array
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
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $menu_name = $request->menu_name;
        $data = DB::select("call sp_single_permission('$menu_name') ");

        return $this->success([
            'data' => $data[0],
            'role_name' => ['superuser'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StorePermissionRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function update(StorePermissionRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $form = $request->all();
        DB::beginTransaction();
        try {
            $this->processPermission($form, 'update');
//            return response()->json($this->processPermission($form, 'update'), 422);

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
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        Permission::where('menu_name', '=', $id)->delete();

        return $this->success([
            'errors' => false,
        ], 'Row deleted!');
    }
}
