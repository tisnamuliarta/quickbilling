<?php

namespace App\Http\Controllers\Students\Api;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
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

        $query = Student::select('*');

        if ($order != null && $col != 'no') {
            if (isset($col)) {
                if ($col == 'major') {
                    $query->orderBy('major_id', $dir);
                } elseif ($col == 'status') {
                    $query->orderBy('approval_step', $dir);
                } else {
                    $query->orderBy($col, $dir);
                }
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
        $query->where("approval_step", "=", "A");
        $query->offset($start)->limit($length);
        $datatable = $query->distinct()->get();
        $data = [];
        $view = __('admin_menu.view');
        foreach ($datatable as $index => $rows) {
            $id = $rows->id;
            $button = '<div class="btn-group btn-group-sm">
                            <button id="btn_prospect_view' . $rows->id . '" data-id-btn="btn-info' . $id . '"
                                class="btn btn-sm btn-info mr-1 btn-sm btn-icon icon-left"
                                data-toggle="tooltip" title="' . $view . '">
                                <i class="fas fa-info-circle"></i></button>
                                </div>';

            if ($rows->approval_step == 'P') {
                $status = '<div class="badge badge-warning">Pending</div>';
            } elseif ($rows->approval_step == 'R') {
                $status = '<div class="badge badge-danger">Reject</div>';
            } elseif ($rows->approval_step == 'G') {
                if ($rows->is_file_complete == 'N') {
                    $status = '<div class="badge badge-info">Re Registration - Belum Lengkap</div>';
                } else {
                    $status = '<div class="badge badge-info">Re Registration - Lengkap</div>';
                }
            } else {
                $status = '<div class="badge badge-success">Diterima</div>';
            }

            $lesson_data = [];
            $i = 0;
            for ($j = 1; $j <= 5; $j++) {
                $semester = 'semester_' . $j;
                $indexRow = $i;
                $lesson = [
                    'bindo_semester_' . $j => $rows->reportCards[$indexRow]->$semester,
                    'bing_semester_' . $j => $rows->reportCards[$indexRow + 1]->$semester,
                    'mtk_semester_' . $j => $rows->reportCards[$indexRow + 2]->$semester,
                    'ipa_semester_' . $j => $rows->reportCards[$indexRow + 3]->$semester,
                ];
                $lesson_data = array_merge($lesson_data, $lesson);
            }

            $data[] = array_merge([
                "no" => $index + 1,
                "ppdb_code" => $rows->ppdb_code,
                "id" => $rows->id,
                "name" => $rows->name,
                "born_place" => $rows->born_place,
                "old_school" => $rows->old_school,
                "no_nisn" => $rows->no_nisn,
                "no_nik" => $rows->no_nik,
                "dob" => $rows->dob,
                "gender" => $rows->gender,
                "address" => $rows->address,
                "major_id" => $rows->major_id,
                "expertise_id" => $rows->expertise_id,
                "hasKIP" => ($rows->hasKIP == 'Y') ? 'Ya' : 'Tidak',
                "no_phone" => $rows->no_phone,
                "nik_father" => $rows->nik_father,
                "address_father" => $rows->address_father,
                "name_father" => $rows->name_father,
                "name_mother" => $rows->name_mother,
                "nik_mother" => $rows->nik_mother,
                "address_mother" => $rows->address_mother,
                "major_name" => $rows->major->name,
                "expertise_name" => $rows->expertise->name,
                "major" => $rows->major->name . '/' . $rows->expertise->name,
                "action" => $button,
                "status" => $status,
            ], $lesson_data);
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
     * @return mixed
     */
    private function totalData()
    {
        return Student::where("approval_step", "=", "A")->distinct()->count();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Student $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Student $student
     * @return \Illuminate\Http\Response
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
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Student $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
