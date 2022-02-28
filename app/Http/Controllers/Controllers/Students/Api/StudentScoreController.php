<?php

namespace App\Http\Controllers\Students\Api;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use Illuminate\Http\Request;

class StudentScoreController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function scoreByUser(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $student = Student::select('*')
            ->where("id", "=", $id)
            ->first();

        return response()->json([
            'rows' => $this->dataDetails($student),
            'default' => $this->default($student)
        ]);
    }

    /**
     * @param $student
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataDetails($student)
    {
        $data = $student;
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
            "average_ipa" => $total[3],
            "average_mtk" => $total[2],
            "average_bing" => $total[1],
            "average_bindo" => $total[0],
        ], $lesson_data);
        return $row_data;
    }

    /**
     * @param $student
     * @return \Illuminate\Http\JsonResponse
     */
    public function default($student)
    {
        $data = $student;
        $lesson_data = [];
        $total = [];
        $total[0] = 0;
        $total[1] = 0;
        $total[2] = 0;
        $total[3] = 0;
        for ($j = 1; $j <= 5; $j++) {
            $semester = 'semester_' . $j;
            $lesson = [
                'lesson_bindo' . $j => null,
                'lesson_bing' . $j => null,
                'lesson_mtk' . $j => null,
                'lesson_ipa' . $j => null,
            ];

            $total[0] = null;
            $total[1] = null;
            $total[2] = null;
            $total[3] = null;
            $lesson_data = array_merge($lesson_data, $lesson);
        }
        $row_data = array_merge([
            "average_ipa" => $total[3],
            "average_mtk" => $total[2],
            "average_bing" => $total[1],
            "average_bindo" => $total[0],
        ], $lesson_data);
        return $row_data;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function score(Request $request)
    {
        $student = $request->user()->student;
        return $this->dataDetails($student);
    }
}
