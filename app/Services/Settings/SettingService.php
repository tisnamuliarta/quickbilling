<?php

namespace App\Services\Settings;

use App\Models\Settings\Setting;
use App\Traits\FileUpload;

class SettingService
{
    use FileUpload;

    /**
     * get form for frontend
     *
     * @param $page
     * @return array
     */
    public function getForm($page): array
    {
        $settings = Setting::where('types', $page)->get();
        $form = [];
        foreach ($settings as $setting) {
            $form[$setting->key] = $setting->value;
        }

        return $form;
    }

    /**
     * @param $settings
     * @return void
     */
    public function store($request)
    {
        $settings = $request->all();

        if ($request->hasFile('company_logo_temp')) {
            $file = $request->file('company_logo_temp');

            $fileName = $this->fileName($file);

            $this->upload($file, '/files/logo', 'logo', $fileName);

            $this->update('company_logo', $fileName);
        }

        foreach ($settings as $index => $setting) {
            if ($setting != 'null' && $index != 'company_logo') {
                $this->update($index, $setting);
            }
        }
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    protected function update($key, $value)
    {
        Setting::where('key', $key)
            ->update([
                'value' => $value
            ]);
    }
}
