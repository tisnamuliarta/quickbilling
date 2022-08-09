<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\EmployeeCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeCommissionController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $pages = isset($request->page) ? (int)$request->page : 1;
        $type = (isset($request->type)) ? $request->type : '';
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 10;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'id';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $search = (isset($request->search)) ? $request->search : '';
        $offset = ($pages - 1) * $row_data;

        $query = EmployeeCommission::with(['transaction', 'lineItem', 'employee'])
            ->orderBy($sorts, $order);

        $result["total"] = $query->count();

        $all_data = $query->offset($offset)
            ->orderBy($sorts, $order)
            ->limit($row_data)
            ->get();

        $result = array_merge($result, [
            'data' => $all_data,
        ]);

        return $this->success($result);
    }
}
