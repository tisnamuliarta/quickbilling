<?php

namespace App\Http\Controllers\Students\Api;

use App\Http\Controllers\Controller;
use App\Models\Student\Expertise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExpertiseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $draw = intval($request->draw);
        $start = intval($request->start);
        $length = intval($request->length);
        $columns = $request->columns;
        $order = $request->order;
        $search = $request->search;
        $search = $search['value'];

        $col = '';
        $dir = "";

        $valid_columns = [];

        for ($i = 0; $i < count($columns); $i++) {
            if ($columns[$i]['data'] != "action" && $columns[$i]['data'] != "no") {
                $valid_columns[] = $columns[$i]['data'];
            }
            if (!empty($order)) {
                if ($order[0]['column'] == $i) {
                    $col = $columns[$i]['data'];
                    $dir = $order[0]['dir'];
                }
            }
        }

        if ($dir != "asc" && $dir != "desc") {
            $dir = "desc";
        }

        $query = DB::table("expertise as e")
            ->leftJoin('majors as m', 'e.major_id', 'm.id')
            ->selectRaw('e.id, e.name, e.description, m.name as major, e.slug, e.major_id');

        if ($order != null && $col != 'no') {
            if ($col === 'major') {
                $query->orderBy($col, $dir);
            } else {
                $query->orderBy($col, $dir);
            }
        }

        if (!empty($search)) {
            $x = 0;
            foreach ($valid_columns as $term) {
                if ($x == 0) {
                    $query->where($term, "LIKE", "%$search%");
                } else {
                    $query->orWhere($term, "LIKE", "%$search%");
                }
                $x++;
            }
        }
        $query->offset($start)->limit($length);
        $datatable = $query->get();
        $data = [];
        $edit = __('admin_menu.edit');
        $delete = __('admin_menu.delete');
        foreach ($datatable as $index => $rows) {
            $id = $rows->id;
            $data[] = [
                "no" => $index + 1,
                "id" => $rows->id,
                "name" => $rows->name,
                "description" => $rows->description,
                "major" => $rows->major,
                "slug" => $rows->slug,
                "action" => '<button id="btn_major_add' . $rows->id . '" data-id-btn="btn-warning' . $id . '"
                                class="btn btn-sm btn-warning mr-1">' . $edit . '</button>
                             <button  id="btn_major_delete' . $rows->id . '" data-id-btn="btn-danger' . $id . '"
                                class="btn btn-sm btn-danger mr-1">' . $delete . '</button>'
            ];
        }

        $total_data = $this->totalData();
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );

        return response()->json($output);
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
                $form_data = new Expertise();
                $form_data->name = $request->name;
                $form_data->description = $request->description;
                $form_data->major_id = $request->major_id;
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
     * @param \App\Student $student
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data_form = Expertise::where("id", "=", $id)
            ->selectRaw("
                id
                ,name
                ,description
                ,major_id
                ,slug
            ")
            ->first();

        return response()->json([
            "row" => $data_form
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Student $student
     * @return void
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Student $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $form_data = Expertise::where("id", "=", $id)->first();
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
            Expertise::where("id", "=", $id)->delete();
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
        return Expertise::count();
    }
}
