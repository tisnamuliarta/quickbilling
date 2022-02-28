<?php

namespace App\Http\Controllers\Students\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Student\ListSchool;
use App\Models\Student\ReportCard;
use App\Models\Student\Student;
use App\Traits\StudentHelper;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentRegisterController extends Controller
{
    use StudentHelper;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register()
    {
        return view('ppdb.register', [
            'majors' => $this->majors(),
            'expertise' => $this->expertiseAll(),
            'checkOpen' => $this->checkOpenRegistration()
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function announcement()
    {
        return view('ppdb.announcement');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function studentAnnouncement(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $ppdb = $this->ppdbThisYear();
            $student = Student::where("no_nisn", $request->no_nisn)
                ->where("ppdb_id", "=", $ppdb->id)
                ->first();
            //$user = User::where('student_id', $student->id)->count();
            if ($student) {
                if ($student->approval_step == 'G') {
                    return response()->json([
                        'header' => 'Selamat!',
                        'status' => "Anda LULUS seleksi PPDB Tahun " . date('Y') . "/" . (date('Y') + 1) . " " . env('APP_NAME') .
                            "\n Silahkan login untuk mendaftar ulang dengan memasukkan \n\n
                        User Code: Kode pendaftaran \n
                        Password: tanggal lahir dengan format TAHUN-BULAN-HARI (tanda tanda '-')
                        Misalkan 25 Desember 2000 menjadi 20001225"
                    ]);
                } else {
                    if ($ppdb->close_date == date('Y-m-d')) {
                        return response()->json([
                            'header' => 'Maaf!',
                            'status' => "Anda Tidak LULUS seleksi PPDB Tahun " . date('Y') . "/"
                                . (date('Y') + 1) . " " . env('APP_NAME')
                        ]);
                    } else {
                        return response()->json([
                            'header' => 'Maaf!',
                            'status' => "Pengumuman PPDB Tahun " . date('Y') . "/" . (date('Y') + 1) . " "
                                . env('APP_NAME') . " akan dilaksanakan pada tangga " . $ppdb->close_date
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'header' => 'Error',
                    'status' => 'Anda belum terdaftar sebagai calon siswa ' . env('APP_NAME') .
                        ' Tahun Ajaran ' . $ppdb->start_year . '/' . $ppdb->end_year
                ]);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'header' => 'Error',
                'status' => $exception->getMessage()
            ]);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function print()
    {
        return view('ppdb.print');
    }

    /**
     * @param $nisn
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function printRegistration(Request $request)
    {
        $nisn = $request->no_nisn;
        $student = Student::where('no_nisn', "=", $nisn)->first();
        if ($student) {
            return view('ppdb.print-registration', [
                'student' => $student
            ]);
        } else {
            abort(404);
        }
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fotgetId()
    {
        return view('ppdb.fogetId');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeForgetId(Request $request)
    {
        $nisn = $request->nisn;
        $student = Student::where("no_nisn", $nisn)->first();
        return redirect()->back()->with('student', $student);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function expertiseByMajor(Request $request)
    {
        $data_id = $request->major;
        return response()->json($this->expertise($data_id));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            if ($this->validation($request)) {
                return response()->json([
                    "error" => true,
                    "validHeader" => true,
                    "errors" => $this->validation($request)
                ], 422);
            }
            // $validate = $request->validated();
            $ppdb = $this->ppdbThisYear();
            $term = 'SMK2TB-' . date('Y');
            $registration_code = $this->generateCode('students', $term, 'ppdb_code', 4, $ppdb);

            $student = new Student();
            $student->name = $request->name;
            $student->born_place = $request->born_place;
            $student->dob = $request->dob;
            $student->no_nisn = $request->no_nisn;
            $student->old_school = strtoupper($request->old_school);
            $student->no_nik = $request->no_nik;
            $student->gender = $request->gender;
            $student->address = $request->address;
            $student->hasKIP = ($request->hasKIP == 'Ya') ? 'Y' : 'N';
            $student->no_phone = $request->no_phone;
            $student->name_father = $request->name_father;
            $student->nik_father = $request->nik_father;
            $student->address_father = $request->address_father;
            $student->major_id = $request->major_id;
            $student->expertise_id = $request->expertise_id;
            $student->name_mother = $request->name_mother;
            $student->nik_mother = $request->nik_mother;
            $student->address_mother = $request->address_mother;
            $student->agree_tos = ($request->agreeTos) ? 'Y' : 'N';
            $student->ppdb_id = $ppdb->id;
            $student->ppdb_code = $registration_code;
            $student->save();

            $check_school = ListSchool::where("name", "=", $request->old_school)->first();
            if (!$check_school) {
                $list_school = new ListSchool();
                $list_school->name = strtoupper($request->old_school);
                $list_school->save();
            }

            $this->storeReportCard($request, $student->id, 'ppdb');
            return response()->json([
                'messages' => 'Data berhasil disimpan! Silahkan kunjungi website kami untuk info selanjutnya.',
                'error' => false
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'messages' => $exception->getMessage()
            ], 422);
        }
    }

    /**
     * @param $request
     * @param $id
     * @param string $string
     */
    private function storeReportCard($request, $id, string $string)
    {
        $report = new ReportCard();
        $report->lesson = 'Bahasa Indonesia';
        $report->semester_1 = $request->lesson_bindo1;
        $report->semester_2 = $request->lesson_bindo2;
        $report->semester_3 = $request->lesson_bindo3;
        $report->semester_4 = $request->lesson_bindo4;
        $report->semester_5 = $request->lesson_bindo5;
        $report->type = $string;
        $report->student_id = $id;
        $report->save();

        $report = new ReportCard();
        $report->lesson = 'Bahasa Inggris';
        $report->semester_1 = $request->lesson_bing1;
        $report->semester_2 = $request->lesson_bing2;
        $report->semester_3 = $request->lesson_bing3;
        $report->semester_4 = $request->lesson_bing4;
        $report->semester_5 = $request->lesson_bing5;
        $report->type = $string;
        $report->student_id = $id;
        $report->save();

        $report = new ReportCard();
        $report->lesson = 'IPA';
        $report->semester_1 = $request->lesson_ipa1;
        $report->semester_2 = $request->lesson_ipa2;
        $report->semester_3 = $request->lesson_ipa3;
        $report->semester_4 = $request->lesson_ipa4;
        $report->semester_5 = $request->lesson_ipa5;
        $report->type = $string;
        $report->student_id = $id;
        $report->save();

        $report = new ReportCard();
        $report->lesson = 'Matematika';
        $report->semester_1 = $request->lesson_mtk1;
        $report->semester_2 = $request->lesson_mtk2;
        $report->semester_3 = $request->lesson_mtk3;
        $report->semester_4 = $request->lesson_mtk4;
        $report->semester_5 = $request->lesson_mtk5;
        $report->type = $string;
        $report->student_id = $id;
        $report->save();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $no_nisn = $request->no_nisn;
        $student = Student::where('no_nisn', "=", $no_nisn)->first();
        if ($student) {
            $pdf_file = PDF::loadView('report.registration', [
                'student' => $student,
            ]);
            return $pdf_file->download("$no_nisn.pdf");
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Data Peserta tidak ditemukan!'
            ]);
        }
    }

    /**
     * @return string
     */
    public function textcode()
    {
        $term = 'SMK2TB-' . date('Y');
        return $this->generateCode('students', $term, 'ppdb_code', 4);
    }

    /**
     * @return false|\Illuminate\Support\MessageBag
     */
    protected function validation($request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        $string_data = "";
        if ($validator->fails()) {
            return $validator->messages();
//            foreach (collect($validator->messages()) as $error) {
//                foreach ($error as $items) {
//                    $string_data .= $items . " \n  ";
//                }
//            }
//            return $string_data;
        } else {
            return false;
        }
    }

    /**
     * @return string[]
     */
    protected function rules()
    {
        $before_date = date('Y') - 21 . '-01-01';
        $rules = [
            'name' => 'required|string',
            'name_father' => 'required',
            'name_mother' => 'required',
            'born_place' => 'required|string',
            'dob' => 'required|date|after:' . $before_date,
            'no_nisn' => 'required|max:10|min:10|regex:/^[0-9 ]+$/|unique:students,no_nisn',
            'expertise_id' => 'required',
            'major_id' => 'required',
            'nik_father' => 'required',
            'nik_mother' => 'required',
            'old_school' => 'required|string',
            'no_nik' => 'required|max:16|min:16|regex:/^([0-9\s+\-\+\(\)]*)$/|unique:students,no_nik',
            'gender' => 'required|string',
            'address' => 'required|string',
            'address_father' => 'required',
            'address_mother' => 'required',
            'hasKIP' => 'required',
            'agree_tos' => 'required',
            'parentCheck' => 'required',
            'no_phone' => 'required|phone:ID',
            'average_ipa' => 'required|numeric|lte:100',
            'average_mtk' => 'required|numeric|lte:100',
            'average_bing' => 'required|numeric|lte:100',
            'average_bindo' => 'required|numeric|lte:100',
        ];

        for ($i = 1; $i <= 5; $i++) {
            $rules = array_merge($rules, [
                'lesson_bindo' . $i => 'required|numeric|lte:100',
                'lesson_bing' . $i => 'required|numeric|lte:100',
                'lesson_mtk' . $i => 'required|numeric|lte:100',
                'lesson_ipa' . $i => 'required|numeric|lte:100',
            ]);
        }
        return $rules;
    }
}
