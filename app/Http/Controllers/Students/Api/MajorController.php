<?php

namespace App\Http\Controllers\Students\Api;

use App\Http\Controllers\Controller;
use App\Models\Student\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MajorController extends Controller
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
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 20;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "U_Name";
        $order = isset($options->sortDesc[0]) ? (string)$options->sortDesc[0] : "desc";
        $offset = ($pages - 1) * $row_data;

        $result = array();
        $query = Major::selectRaw("*, 'actions' as ACTIONS");

        $result["total"] = $query->count();

        $all_data = $query->offset($offset)
            ->orderBy($sorts, $order)
            ->limit($row_data)
            ->get();

        $all_rows = Major::all();

        $result = array_merge($result, [
            "rows" => $all_data,
            "all_data" => $all_rows
        ]);
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allMajor(Request $request)
    {
        return response()->json([
            'major' => Major::all()
        ]);
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
                $form_data = new Major();
                $form_data->name = $request->name;
                $form_data->description = $request->description;
                $form_data->slug = Str::slug($request->name);
                $form_data->save();

                return response()->json([
                    "error" => false,
                    "msg" => "Data saved!"
                ]);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data_form = Major::where("id", "=", $id)
            ->selectRaw("
                id
                ,name
                ,description
                ,slug
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $form_data = Major::where("id", "=", $id)->first();
        if ($form_data) {
            $form_data->name = $request->name;
            $form_data->description = $request->description;
            $form_data->slug = Str::slug($request->name);
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
            Major::where("id", "=", $id)->delete();
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
        return Major::count();
    }
}
