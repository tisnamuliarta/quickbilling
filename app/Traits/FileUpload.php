<?php

namespace App\Traits;

use App\Models\Settings\Setting;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait FileUpload
{
    /**
     * @param $dataFile
     * @param $path
     * @param $type
     * @return string
     */
    public function upload($dataFile, $path, $type): string
    {
        $extension = $dataFile->getClientOriginalExtension();

        $destinationPath = public_path($path);

        if (!file_exists($destinationPath)) {
            if (!mkdir($destinationPath, 0777, true) && !is_dir($destinationPath)) {
                throw new \RuntimeException(
                    sprintf(
                        'Directory "%s" was not created',
                        $destinationPath
                    )
                );
            }
        }

        $origin_name = $dataFile->getClientOriginalName();
        $nameWithoutExt = strtoupper(Str::slug(pathinfo($origin_name, PATHINFO_FILENAME))) . time();
        $fileName = $nameWithoutExt . '.' . $extension;

        $this->fileStored($extension, $dataFile, $destinationPath, $fileName, $type);

        return $fileName;
    }

    /**
     * @param $extension
     * @param $dataFile
     * @param $destinationPath
     * @param $fileName
     * @param $type
     * @return void
     */
    protected function fileStored($extension, $dataFile, $destinationPath, $fileName, $type)
    {
        $imageExtension = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
        if (Str::contains($extension, $imageExtension)) {
            if ($type == 'logo') {
                $setting = Setting::where('key', 'company_logo')->first();
                if ($setting->value) {
                    $concatFileName = $destinationPath . '/' . $setting->value;
                    if (file_exists($concatFileName)) {
                        unlink($concatFileName);
                    }
                }
            }

            $concatFileName = $destinationPath . '/' . $fileName;
            $imageFile = Image::make($dataFile->getRealPath());
            $imageFile->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $imageFile->save($concatFileName);
        } else {
            $dataFile->move($destinationPath, $fileName);
        }
    }
}
