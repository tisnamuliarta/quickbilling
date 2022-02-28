<?php

namespace App\Http\Controllers\Students\Api;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $options = json_decode($request->options);
        $year_local = date('Y');
        $pages = isset($options->page) ? (int)$options->page : 1;
        $filter = isset($request->filter) ? (string)$request->filter : $year_local;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 1000;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "id";
        $order = isset($options->sortDesc[0]) ? "DESC" : "ASC";

        $search = isset($request->q) ? (string)$request->q : "";
        $type = isset($request->type) ? $request->type : null;
        $select_data = isset($request->selectData) ? (string)$request->selectData : "id";
        $offset = ($pages - 1) * $row_data;
        $username = $request->user()->U_UserCode;

        $current_year = date('Y');
        $next_year = (date('Y') + 1);

        $result = array();
        $query = StudentRegistration::orderBy($sorts, $order)
            ->select("*",
                DB::raw("CONCAT(start_year, '/', end_year) AS doc_year"),
                DB::raw("
                CASE
                    WHEN is_open = 'Y' THEN 'BUKA'
                    ELSE 'TUTUP'
                END as status
            "),
            );

        $result["total"] = $query->count();
        $all_data = $query->offset($offset)
            ->limit($row_data)
            ->get();

        $result = array_merge($result, [
            "rows" => $all_data,
            "filter" => ['All'],
            "current_year" => $current_year,
            "next_year" => $next_year
        ]);
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $id = $request->id;
            if ($id) {
                $this->update($request, $id);

                return response()->json([
                    "error" => false,
                    "msg" => "Data updated!"
                ]);
            } else {
                if ($this->checkDuplicate($request->form["start_year"]) > 0) {
                    return response()->json([
                        "error" => true,
                        "msg" => "Tahun awal tidak boleh sama!"
                    ]);
                } else {
                    $form = $request->form;
                    $form_data = new StudentRegistration();
                    $form_data->start_year = $form["start_year"];
                    $form_data->end_year = $form["end_year"];
                    $form_data->open_date = $form["open_date"];
                    $form_data->is_open = $form["is_open"];
                    $form_data->created_by = $request->user()->id;
                    $form_data->save();

                    return response()->json([
                        "error" => false,
                        "msg" => "Data saved!"
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "msg" => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data_form = StudentRegistration::where("id", "=", $id)
            ->selectRaw("
                id
                ,start_year
                ,end_year
                ,open_date
                ,is_open
            ")
            ->first();

        return response()->json([
            "row" => $data_form
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $form_data = StudentRegistration::where("id", "=", $id)->first();
        if ($form_data) {
            $form = $request->form;

            $form_data->start_year = $form["start_year"];
            $form_data->end_year = $form["end_year"];
            $form_data->open_date = $form["open_date"];
            $form_data->is_open = $form["is_open"];
            $form_data->updated_by = $request->user()->id;
            $form_data->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            StudentRegistration::where("id", "=", $id)->delete();
            return response()->json([
                "error" => false,
                "msg" => "Data deleted successfuly!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "msg" => $e->getMessage()
            ]);
        }
    }

    /**
     * @return mixed
     */
    private function totalData()
    {
        return StudentRegistration::count();
    }

    /**
     * @param $start_year
     * @return mixed
     */
    private function checkDuplicate($start_year)
    {
        return StudentRegistration::where("start_year", "=", $start_year)->count();
    }
}
