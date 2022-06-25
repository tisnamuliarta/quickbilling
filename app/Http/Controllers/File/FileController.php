<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Models\File\File;
use App\Models\Settings\Setting;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileController extends Controller
{
    use FileUpload;

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $type = $request->type;
        if ($type == 'company_logo') {
            $setting = Setting::where('key', 'company_logo')->first();

            return $this->success([
                'rows' => $setting->value,
                'total' => 1,
            ]);
        }
        $attachment = File::where('fileable_id', '=', (int) $request->temp_id)
            ->where('fileable_type', $type);

        return $this->success([
            'rows' => $attachment->get(),
            'total' => $attachment->count(),
        ]);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file.*' => 'required|mimes:pdf,docx,docx,png,jpg,jpeg|max:8048',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), '422');
        }
        try {
            $data_file = $request->file('file');

            if ($request->type == 'company_logo') {
                $fileName = $this->fileName($data_file);

                $this->upload($data_file, '/files/logo', 'logo', $fileName);

                Setting::where('key', 'company_logo')
                    ->update([
                        'value' => $fileName,
                    ]);

                return $this->success([
                    'row' => $fileName,
                    'total' => 1,
                ], 'Data saved!');
            }

            $extension = $data_file->getClientOriginalExtension();

            $destination_path = public_path('/files/files');

            if (! file_exists($destination_path)) {
                if (! mkdir($destination_path, 0777, true) && ! is_dir($destination_path)) {
                    throw new \RuntimeException(
                        sprintf(
                            'Directory "%s" was not created',
                            $destination_path
                        )
                    );
                }
            }

            $origin_name = $data_file->getClientOriginalName();
            $name_no_ext = strtoupper(Str::slug(pathinfo($origin_name, PATHINFO_FILENAME))).time();
            $file_name = $name_no_ext.'.'.$extension;
            $data_file->move($destination_path, $file_name);

            $data = [
                'company_id' => $request->user()->entity_id,
                'filename' => $file_name,
                'extension' => $extension,
                'directory' => url('/files/files/'.$file_name),
                'fileable_id' => (int) $request->temp_id,
                'fileable_type' => $request->type,
            ];

            File::create($data);

            $count_attachment = File::where('fileable_type', '=', $request->type)
                ->where('fileable_id', '=', (int) $request->temp_id)
                ->count();

            return $this->success([
                'count' => $count_attachment,
            ], 'Document Uploaded!');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), '422');
        }
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            $attachment = File::where('id', '=', $request->id)
                ->first();

            if ($attachment) {
                $file = '/files/files/'.$attachment->filename;
                unlink(public_path().$file);
                File::where('id', '=', $attachment->id)
                    ->delete();

                return $this->success('', 'File deleted!');
            } else {
                return $this->error('File not found', 422);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
}
