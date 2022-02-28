<?php

namespace App\Http\Controllers\Students\Api;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Student\Student;
use App\Models\Student\StudentDetails;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentDetailsController extends Controller
{
    /**
     * @param $request
     * @return bool|string
     */
    public function validation($request)
    {
        $validator = Validator::make($request->all(), [
            'no_bird_card' => 'required',
            'religion_id' => 'required',
            'special_need_id' => 'required',
            'nationality' => 'required',
            'province_id' => 'required',
            'regency_id' => 'required',
            'district_id' => 'required',
            'village_id' => 'required',
            'dusun_name' => 'required',
            'rt_name' => 'required',
            'rw_name' => 'required',
            'zip_code' => 'required',
            'residence_id' => 'required',
            'transportation_id' => 'required',
            'family_order' => 'required',
            'sibling_number' => 'required',
            'blood_group_id' => 'required',
            'home_phone' => 'required',
            'email' => 'required|email',
            'extracurricular_id' => 'required',
            'height' => 'required',
            'head_circumference' => 'required',
            'weight' => 'required',
            'school_home_distance' => 'required',
            'travel_time' => 'required',
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
                'is_details_complete' => 'Y'
            ]);
            if ($request->user()->student->details) {
                $this->update($request);
            } else {
                StudentDetails::create($request->all());
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
        $student_details = StudentDetails::where("user_id", "=", $request->user()->student_id)->first();
        $student_details->fill($request->all());
        $student_details->save();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function studentPhoto(Request $request): \Illuminate\Http\JsonResponse
    {
        $image = Attachment::where('source_id', "=", $request->user()->student_id)
            ->where('type', '=', 'Photo')
            ->first();
        return response()->json([
            'src' => asset('/docs' . $image->path),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function studentPhotoByUser(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $image = Attachment::where('source_id', "=", $id)
            ->where('type', '=', 'Photo')
            ->first();
        return response()->json([
            'src' => asset('/docs' . $image->path),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function downloadUserImage(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $image = File::where('source_id', "=", $id)
            ->where('type', '=', 'Photo')
            ->first();
        return response()->json([
            'src' => base64_encode(asset('/docs' . $image->path)),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePhoto(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $is_file_complete = $request->user()->student->is_file_complete;
        if ($is_file_complete == 'Y') {
            return response()->json([
                "errors" => true,
                "message" => 'Gagal update, data sudah terkirim!'
            ], 422);
        }

        $string_data = "";
        if ($validator->fails()) {
            foreach (collect($validator->messages()) as $error) {
                foreach ($error as $items) {
                    $string_data .= $items . ", \n  ";
                }
            }
            return response()->json([
                "errors" => true,
                "message" => $string_data
            ], 422);
        }
        try {
            $old_image = Attachment::where('source_id', "=", $request->user()->student_id)->first();
            if ($old_image) {
                if (file_exists(public_path('docs/images/' . $old_image->name))) {
                    unlink(public_path('docs/images/' . $old_image->name));
                }
                $old_image->delete();
            }

            $image = $request->file;
            // Make a image name based on user name and current timestamp
            $name = Str::slug($request->user()->name) . '_' . time();
            // Define folder path
            $folder = '/images/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
            $request->merge([
                'name' => $name . '.' . $image->getClientOriginalExtension()
            ]);
            $request->request->add([
                'extension' => $image->getClientOriginalExtension(),
                'path' => $filePath,
                'type' => 'Photo',
                'desc' => 'Profile Photo',
                'source_id' => $request->user()->student_id,
            ]);

            Attachment::create($request->all());

            return response()->json([
                "errors" => false,
                "message" => 'Foto berhasil tersimpan'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                "errors" => true,
                "message" => $exception->getMessage() . ' ' . $exception->getLine()
            ], 422);
        }
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param null $folder
     * @param string $disk
     * @param null $filename
     * @return false|string
     */
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $file = $uploadedFile->storeAs($folder, $name . '.' . $uploadedFile->getClientOriginalExtension(), $disk);

        return $file;
    }

    /**
     * @param $request
     * @param $details
     * @return array
     */
    public function dataDetails($request, $details): array
    {
        return [
            'no_bird_card' => ($details) ? $details->no_bird_card : null,
            'religion_id' => ($details) ? $details->religion_id : null,
            'special_need_id' => ($details) ? $details->special_need_id : null,
            'nationality' => ($details) ? $details->nationality : null,
            'province_id' => ($details) ? (int)$details->province_id : null,
            'regency_id' => ($details) ? (int)$details->regency_id : null,
            'district_id' => ($details) ? (int)$details->district_id : null,
            'village_id' => ($details) ? (int)$details->village_id : null,
            'dusun_name' => ($details) ? $details->dusun_name : null,
            'rt_name' => ($details) ? $details->rt_name : null,
            'rw_name' => ($details) ? $details->rw_name : null,
            'zip_code' => ($details) ? $details->zip_code : null,
            'residence_id' => ($details) ? $details->residence_id : null,
            'transportation_id' => ($details) ? $details->transportation_id : null,
            'family_order' => ($details) ? $details->family_order : null,
            'sibling_number' => ($details) ? $details->sibling_number : null,
            'blood_group_id' => ($details) ? $details->blood_group_id : null,
            'home_phone' => ($details) ? $details->home_phone : null,
            'email' => ($details) ? $details->email : null,
            'extracurricular_id' => ($details) ? $details->extracurricular_id : null,
            'height' => ($details) ? $details->height : null,
            'head_circumference' => ($details) ? $details->head_circumference : null,
            'weight' => ($details) ? $details->weight : null,
            'school_home_distance' => ($details) ? $details->school_home_distance : null,
            'travel_time' => ($details) ? $details->travel_time : null,
        ];
    }

    /**
     * @param $request
     * @param $details
     * @return null[]
     */
    public function dataDefault($request, $details): array
    {
        return [
            'no_bird_card' => null,
            'religion_id' => null,
            'special_need_id' => null,
            'nationality' => null,
            'province_id' => null,
            'regency_id' => null,
            'district_id' => null,
            'village_id' => null,
            'dusun_name' => null,
            'rt_name' => null,
            'rw_name' => null,
            'zip_code' => null,
            'residence_id' => null,
            'transportation_id' => null,
            'family_order' => null,
            'sibling_number' => null,
            'blood_group_id' => null,
            'home_phone' => null,
            'email' => null,
            'extracurricular_id' => null,
            'height' => null,
            'head_circumference' => null,
            'weight' => null,
            'school_home_distance' => null,
            'travel_time' => null,
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function details(Request $request): \Illuminate\Http\JsonResponse
    {
        $student = $request->user()->student;
        return response()->json([
            'rows' => $this->dataDetails($request, $student->details)
        ]);
        //return $this->dataDetails($request, $student);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailsByUser(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $student = Student::select('*')
            ->where("id", "=", $id)
            ->first();

        return response()->json([
            'rows' => $this->dataDetails($request, $student->details),
            'default' => $this->dataDefault($request, $student->details),
        ]);
    }
}
