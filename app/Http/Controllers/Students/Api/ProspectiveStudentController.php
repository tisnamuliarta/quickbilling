<?php

namespace App\Http\Controllers\Students\Api;

use App\Exports\ProspectiveStudentExport;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Student\ListSchool;
use App\Models\Student\Student;
use App\Models\Student\StudentRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProspectiveStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $options = json_decode($request->options);
        $year_local = date('Y');
        $pages = isset($options->page) ? (int)$options->page : 1;
        $filter = isset($request->filter) ? (string)$request->filter : $year_local;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 20;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "ppdb_code";
        $order = isset($options->sortDesc[0]) ? (string)$options->sortDesc[0] : "desc";
        $offset = ($pages - 1) * $row_data;
        $search_status = $request->searchStatus;
        $search_year = ($request->searchByYear) ?: date('Y');
        $search = $request->search;
        $search_item = $request->searchItem;

        $result = array();
        $query = Student::select("students.*",
            DB::raw("
                CASE
                WHEN approval_step = 'P' THEN 'Pending'
                WHEN approval_step = 'G' AND is_file_complete = 'Y' THEN 'Daftar Ulang - Lengkap'
                WHEN approval_step = 'G' AND is_file_complete = 'N' THEN 'Daftar Ulang - Belum Lengkap'
                WHEN approval_step = 'R' THEN 'Ditolak'
                WHEN approval_step = 'A' THEN 'Diterima'
                END As status
            "),

            DB::raw("
            CASE
                WHEN approval_step = 'P' THEN '#FFE082'
                WHEN approval_step = 'G' AND is_file_complete = 'Y' THEN '#80CBC4'
                WHEN approval_step = 'G' AND is_file_complete = 'N' THEN '#FF7043'
                WHEN approval_step = 'R' THEN '#EF5350'
                WHEN approval_step = 'A' THEN '#66BB6A'
            END As Color
            "),
        )->when($search_year, function ($query) use ($search_status, $search, $search_year, $search_item) {
            $data_query = $query;

            switch ($search_item) {
                case 'name':
                    $data_query->whereRaw('name LIKE( \'%' . $search . '%\') ');
                    break;
                case 'ppdb_code':
                    $data_query->whereRaw('ppdb_code LIKE( \'%' . $search . '%\') ');
                    break;
                case 'no_nisn':
                    $data_query->whereRaw('no_nisn LIKE( \'%' . $search . '%\') ');
                    break;
            }

            if ($search_year) {
                $data_query->where("B.start_year", "=", $search_year);
            }

            if ($search_status) {
                $data_query->where("approval_step", "=", $search_status);
            }
            return $data_query;
        })
            ->leftJoin("ppdb as B", "B.id", "students.ppdb_id");

        $result["total"] = $query->count();

        $all_data = $query->offset($offset)
            ->orderBy($sorts, $order)
            ->limit($row_data)
            ->get();

        $item_search = [
            ['text' => 'Nama', 'value' => 'name'],
            ['text' => 'Kode Pendaftaran', 'value' => 'ppdb_code'],
            ['text' => 'NISN', 'value' => 'no_nisn'],
        ];

        $year_register = StudentRegistration::all();
        $item_year = [];
        foreach ($year_register as $item) {
            $item_year[] = [
                'text' => $item->start_year . '/' . $item->end_year,
                'value' => $item->start_year
            ];
        }

        $item_status = [
            ['text' => 'Pending', 'value' => 'P'],
            ['text' => 'Daftar Ulang', 'value' => 'G'],
            ['text' => 'Tolak', 'value' => 'R'],
            ['text' => 'Terima', 'value' => 'A'],
        ];

        $result = array_merge($result, [
            "rows" => $all_data,
            "item_search" => $item_search,
            "item_year" => $item_year,
            "item_status" => $item_status,
            "current_year" => $search_year
        ]);
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $data = Student::where("id", "=", $id)->first();
        $lesson_data = [];
        $total = [];

        $total[0] = 0;
        $total[1] = 0;
        $total[2] = 0;
        $total[3] = 0;

        for ($j = 1; $j <= 5; $j++) {
            $semester = 'semester_' . $j;
            $lesson = [
                'lesson_bindo' . $j => $data->reportCards[0]->$semester,
                'lesson_bing' . $j => $data->reportCards[1]->$semester,
                'lesson_mtk' . $j => $data->reportCards[2]->$semester,
                'lesson_ipa' . $j => $data->reportCards[3]->$semester,
            ];
            $total[0] += $data->reportCards[0]->$semester;
            $total[1] += $data->reportCards[1]->$semester;
            $total[2] += $data->reportCards[2]->$semester;
            $total[3] += $data->reportCards[3]->$semester;
            $lesson_data = array_merge($lesson_data, $lesson);
        }

        $row_data = array_merge([
            "ppdb_code" => $data->ppdb_code,
            "id" => $data->id,
            "name" => $data->name,
            "born_place" => $data->born_place,
            "old_school" => $data->old_school,
            "no_nisn" => $data->no_nisn,
            "no_nik" => $data->no_nik,
            "dob" => $data->dob,
            "gender" => $data->gender,
            "address" => $data->address,
            "major_id" => $data->major_id,
            "expertise_id" => $data->expertise_id,
            "hasKIP" => $data->hasKIP,
            "no_phone" => $data->no_phone,
            "nik_father" => $data->nik_father,
            "address_father" => $data->address_father,
            "name_father" => $data->name_father,
            "name_mother" => $data->name_mother,
            "nik_mother" => $data->nik_mother,
            "address_mother" => $data->address_mother,
            "major_name" => $data->major_name,
            "expertise_name" => $data->expertise_name,
            "major" => $data->major,
            "parentCheck" => $data->parentCheck,
            "average_ipa" => $total[3],
            "average_mtk" => $total[2],
            "average_bing" => $total[1],
            "average_bindo" => $total[0],
        ], $lesson_data);

        $min_date = (date('Y') - 21) . '-01';

        $list_school = ListSchool::select('name')->distinct()->get();
        return response()->json([
            'form' => $row_data,
            'min_date' => $min_date,
            'list_school' => $list_school
        ]);
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Student $student
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Student $student, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $status = $request->status;
            $status = $this->studentStatus($status);
            $data_student = $student->find($id);
            if ($data_student) {
                $data_student->approval_step = $status;
                $data_student->save();

                $this->createUser($data_student);

                return response()->json([
                    'error' => false,
                    'message' => 'Status siswa sudah diubah!'
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Terdapat error, tidak dapat mengubah status siswa!'
                ]);
            }
        } catch (\Exception $error) {
            return response()->json([
                'error' => true,
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'message' => $error->getMessage()
            ]);
        }
    }

    /**
     * @param $student
     */
    public function createUser($student)
    {
        $time = time();
        if ($student->approval_step == 'G') {
            $username = $student->ppdb_code;
            $role = Role::where('name', 'Re-registration')->first();
            if (User::where('username', $username)->count() < 1) {
                $user = User::create([
                    'name' => $student->name,
                    'username' => $username,
                    'student_id' => $student->id,
                    'email' => 'student' . $time . '@smkn2toilibarat.com',
                    'password' => bcrypt(str_replace('-', '', $student->dob)),
                    'role_id' => $role->id
                ]);
            }
        } elseif ($student->approval_step == 'A') {
            $role = Role::where('name', 'Student')->first();
            $username = $student->ppdb_code;
            if (User::where('username', $username)->count() < 1) {
                $user = User::create([
                    'name' => $student->name,
                    'username' => $username,
                    'student_id' => $student->id,
                    'email' => 'student' . $time . '@smkn2toilibarat.com',
                    'password' => bcrypt(str_replace('-', '', $student->dob)),
                    'role_id' => $role->id
                ]);
            }
        }
    }

    /**
     * @param $status
     * @return string
     */
    public function studentStatus($status): string
    {
        switch ($status) {
            case 'Pending':
                $status = 'P';
                break;
            case 'Daftar Ulang':
                $status = 'G';
                break;
            case 'Tolak':
                $status = 'R';
                break;
            case 'Terima':
                $status = 'A';
        }
        return $status;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportData(Request $request)
    {
        $options = json_decode($request->options);
        $year_local = date('Y');
        $pages = isset($options->page) ? (int)$options->page : 1;
        $filter = isset($request->filter) ? (string)$request->filter : $year_local;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 20;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "ppdb_code";
        $order = isset($options->sortDesc[0]) ? (string)$options->sortDesc[0] : "desc";
        $offset = ($pages - 1) * $row_data;
        $search_status = $request->searchStatus;
        $search_year = ($request->searchByYear) ?: date('Y');
        $search = $request->search;
        $search_item = $request->searchItem;
        $destination_path = public_path("docs/");
        if (!file_exists($destination_path)) {
            if (!mkdir($destination_path, 0777, true) && !is_dir($destination_path)) {
                throw new \RuntimeException(sprintf(
                    'Directory "%s" was not created',
                    $destination_path
                ));
            }
        }

        // Excel::store(new ProspectiveStudentGeneralExport($search_year, 'general'), 'student.xlsx', 'public');
        return (new ProspectiveStudentExport($search_year))->download('student.xlsx');
    }
}
