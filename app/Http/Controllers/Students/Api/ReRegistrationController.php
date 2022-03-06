<?php

namespace App\Http\Controllers\Students\Api;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use App\Models\Transactions\Attachment;
use Illuminate\Http\Request;

class ReRegistrationController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendData(Request $request)
    {
        $student = Student::where("id", "=", $request->user()->student->id)->first();
        $student->is_file_complete = 'Y';
        $student->save();

        return response()->json(true);
    }

    /**
     * @param $query
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function dataDetails($query, $request): \Illuminate\Http\JsonResponse
    {
        $icon_success = 'Y';
        $icon_pending = 'N';

        $check_upload_photo = false;
        $details = false;
        $help = false;
        $parent = false;

        $student = $query;
        $check_upload_photo = Attachment::where("source_id", "=", $student->id)
            ->where("type", "=", "Photo")
            ->first();

        if ($query->details) {
            $details = $student->details->is_details_complete;
            $help = $student->details->is_help_complete;
            $parent = $student->details->is_parent_complete;
        }
        $data = [
            [
                "no" => 1,
                "id" => $student->id,
                "step_desc" => "Upload Pas Foto",
                "type" => "photo",
                "status" => ($check_upload_photo) ? $icon_success : $icon_pending,
            ],
            [
                "no" => 2,
                "id" => $student->id,
                "step_desc" => "Data Pribadi",
                "type" => "data_details",
                "status" => ($details == 'Y') ? $icon_success : $icon_pending,
            ],
            [
                "no" => 3,
                "id" => $student->id,
                "step_desc" => "Data Bantuan",
                "type" => "data_help",
                "status" => ($help == 'Y') ? $icon_success : $icon_pending,
            ],
            [
                "no" => 4,
                "id" => $student->id,
                "step_desc" => "Data Orang Tua/Wali",
                "type" => "data_parent",
                "status" => ($parent == 'Y') ? $icon_success : $icon_pending,
            ],
            [
                "no" => 5,
                "id" => $student->id,
                "step_desc" => "Data Nilai",
                "type" => "data_score",
                "status" => $icon_success,
            ],
            [
                "no" => 6,
                "id" => $student->id,
                "step_desc" => "Data Registrasi",
                "type" => "data_regist",
                "status" => 'Y',
            ],
        ];

        $total_data = count($data);
        $output = array(
            "total" => $total_data,
            "rows" => $data
        );

        return response()->json($output);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataByDetails(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $student = Student::select('*')
            ->where("id", "=", $id)
            ->first();

        return $this->dataDetails($student, $request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->dataDetails($request->user()->student, $request);
    }
}
