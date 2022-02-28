<?php

namespace App\Http\Controllers\Students\Api;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use App\Models\Student\StudentParent;
use App\Models\Student\StudentParentData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentParentController extends Controller
{
    /**
     * @param $request
     * @return bool|string
     */
    public function validation($request)
    {
        $validator = Validator::make($request->all(), [
            'father_born_place' => 'required',
            'father_dob' => 'required',
            'father_education' => 'required',
            'father_job' => 'required',
            'father_income' => 'required',
            'father_special_need' => 'required',
            'mother_born_place' => 'required',
            'mother_dob' => 'required',
            'mother_education' => 'required',
            'mother_job' => 'required',
            'mother_income' => 'required',
            'mother_special_need' => 'required',
            'guardian_parent_born_place' => 'required',
            'guardian_parent_dob' => 'required',
            'guardian_parent_education' => 'required',
            'guardian_parent_job' => 'required',
            'guardian_parent_income' => 'required',
            'guardian_parent_special_need' => 'required',
            'name_father' => 'required',
            'nik_father' => 'required',
            'name_mother' => 'required',
            'nik_mother' => 'required',
        ]);

        $string_data = "";
        if ($validator->fails()) {
            foreach (collect($validator->messages()) as $error) {
                foreach ($error as $items) {
                    $string_data .= $items . ", \n  ";
                }
            }
            return $string_data;
        } else {
            return false;
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $is_file_complete = $request->user()->student->is_file_complete;
        if ($is_file_complete == 'Y') {
            return response()->json([
                "errors" => true,
                "message" => 'Gagal update, data sudah terkirim!'
            ], 422);
        }
        if ($this->validation($request)) {
            return response()->json([
                "errors" => true,
                "message" => $this->validation($request)
            ], 422);
        }
        try {
            $request->request->add([
                'user_id' => $request->user()->student_id,
                'is_parent_complete' => 'Y'
            ]);
            if ($request->user()->student->details) {
                $this->update($request);
            } else {
                StudentParent::create($request->all());
            }

            $student_details = StudentParentData::where("id", "=", $request->user()->student_id)->first();
            $student_details->fill($request->all());
            $student_details->save();

            return response()->json([
                "errors" => false,
                "message" => 'Data tersimpan, Lanjut ke langkah selanjutnya!'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                "errors" => true,
                "message" => $exception->getMessage()
            ], 422);
        }
    }

    /**
     * @param $request
     */
    public function update($request)
    {
        $student_details = StudentParent::where("user_id", "=", $request->user()->student_id)->first();
        $student_details->fill($request->all());
        $student_details->save();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $student = $request->user()->student;
        return response()->json([
            'rows' => $this->details($student)
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexByUser(Request $request, $id)
    {
        $student = Student::select('*')
            ->where("id", "=", $id)
            ->first();
        return response()->json([
            'rows' => $this->details($student),
            'default' => $this->default($student),
        ]);
    }

    /**
     * @param $student
     * @return null[]
     */
    public function default($student)
    {
        $details = (isset($student->details)) ? $student->details : null;
        return [
            'father_born_place' => null,
            'father_dob' => null,
            'father_education' => null,
            'father_job' => null,
            'father_income' => null,
            'father_special_need' => null,
            'mother_born_place' => null,
            'mother_dob' => null,
            'mother_education' => null,
            'mother_job' => null,
            'mother_income' => null,
            'mother_special_need' => null,
            'guardian_parent_name' => null,
            'guardian_parent_nik' => null,
            'guardian_parent_born_place' => null,
            'guardian_parent_dob' => null,
            'guardian_parent_education' => null,
            'guardian_parent_job' => null,
            'guardian_parent_income' => null,
            'guardian_parent_special_need' => null,
            'name_father' => null,
            'nik_father' => null,
            'name_mother' => null,
            'nik_mother' => null,
        ];
    }

    /**
     * @param $student
     * @return array
     */
    public function details($student)
    {
        $details = (isset($student->details)) ? $student->details : null;

        return [
            'father_born_place' => ($details) ? $details->father_born_place : null,
            'father_dob' => ($details) ? $details->father_dob : null,
            'father_education' => ($details) ? (int)$details->father_education : null,
            'father_job' => ($details) ? (int)$details->father_job : null,
            'father_income' => ($details) ? (int)$details->father_income : null,
            'father_special_need' => ($details) ? (int)$details->father_special_need : null,
            'mother_born_place' => ($details) ? $details->mother_born_place : null,
            'mother_dob' => ($details) ? $details->mother_dob : null,
            'mother_education' => ($details) ? (int)$details->mother_education : null,
            'mother_job' => ($details) ? (int)$details->mother_job : null,
            'mother_income' => ($details) ? (int)$details->mother_income : null,
            'mother_special_need' => ($details) ? (int)$details->mother_special_need : null,
            'guardian_parent_name' => ($details) ? $details->guardian_parent_name : null,
            'guardian_parent_nik' => ($details) ? $details->guardian_parent_nik : null,
            'guardian_parent_born_place' => ($details) ? $details->guardian_parent_born_place : null,
            'guardian_parent_dob' => ($details) ? $details->guardian_parent_dob : null,
            'guardian_parent_education' => ($details) ? (int)$details->guardian_parent_education : null,
            'guardian_parent_job' => ($details) ? (int)$details->guardian_parent_job : null,
            'guardian_parent_income' => ($details) ? (int)$details->guardian_parent_income : null,
            'guardian_parent_special_need' => ($details) ? (int)$details->guardian_parent_special_need : null,
            'name_father' => ($student) ? $student->name_father : null,
            'nik_father' => ($student) ? $student->nik_father : null,
            'name_mother' => ($student) ? $student->name_mother : null,
            'nik_mother' => ($student) ? $student->nik_mother : null,
        ];
    }
}
