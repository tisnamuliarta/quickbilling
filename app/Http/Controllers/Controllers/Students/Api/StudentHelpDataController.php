<?php

namespace App\Http\Controllers\Students\Api;

use App\Http\Controllers\Controller;
use App\Models\KipWorthyReason;
use App\Models\Student;
use App\Models\StudentHelp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentHelpDataController extends Controller
{
    /**
     * @param $request
     * @return bool|string
     */
    public function validation($request)
    {
        $validator = Validator::make($request->all(), [
            //'kks_no' => 'required',
            'is_kps_receiver' => 'required',
            'kps_no' => 'required_if:is_kps_receiver,Y',
            'is_pip_worthy' => 'required',
            'pip_worthy_reason' => 'required_if:is_pip_worthy,Y',
            'pip_no' => 'required_if:is_pip_worthy,Y',
            'pip_name' => 'required_if:is_pip_worthy,Y',
            'is_kip_receiver' => 'required',
            'is_kip_physical_receiver' => 'required_if:is_kip_receiver,Y',
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
    public function store(Request $request): \Illuminate\Http\JsonResponse
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
                'is_help_complete' => 'Y'
            ]);
            if ($request->user()->student->details) {
                $this->update($request);
            } else {
                StudentHelp::create($request->all());
            }
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
        $student_details = StudentHelp::where("user_id", "=", $request->user()->student_id)->first();
        $student_details->fill($request->all());
        $student_details->save();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $student = $request->user()->student;
        return response()->json([
            'rows' => $this->dataDetails($student)
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexByUser(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $student = Student::select('*')
            ->where("id", "=", $id)
            ->first();
        return response()->json([
            'rows' => $this->dataDetails($student),
            'default' => $this->default($student),
        ]);
    }

    /**
     * @param $student
     * @return array
     */
    public function dataDetails($student): array
    {
        $details = (isset($student->details)) ? $student->details : null;

        return [
            'is_kps_receiver' => ($details) ? $details->is_kps_receiver : null,
            'kps_no' => ($details) ? $details->kps_no : null,
            'is_pip_worthy' => ($details) ? $details->is_pip_worthy : null,
            'pip_worthy_reason' => ($details) ? (int)$details->pip_worthy_reason : null,
            'pip_no' => ($details) ? $details->pip_no : null,
            'pip_name' => ($details) ? $details->pip_name : null,
            'is_kip_receiver' => ($details) ? $details->is_kip_receiver : null,
            'is_kip_physical_receiver' => ($details) ? $details->is_kip_physical_receiver : null,
        ];
    }

    /**
     * @param $student
     * @return null[]
     */
    public function default($student): array
    {
        $details = (isset($student->details)) ? $student->details : null;

        return [
            'is_kps_receiver' => null,
            'kps_no' => null,
            'is_pip_worthy' => null,
            'pip_worthy_reason' => null,
            'pip_no' => null,
            'pip_name' => null,
            'is_kip_receiver' => null,
            'is_kip_physical_receiver' => null,
        ];
    }
}
