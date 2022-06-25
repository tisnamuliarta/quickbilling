<?php

namespace App\Services\Settings;

use App\Models\Settings\Setting;
use App\Traits\FileUpload;
use IFRS\Models\Currency;
use IFRS\Models\Entity;

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

            $entity = Entity::find($request->user()->entity_id);
            if ($index == 'advanced_currency') {
                $currency = Currency::where('currency_code', $setting)->first();
                if ($currency) {
                    $entity->currency_id = $currency->id;
                    $entity->save();
                }
            }

            if ($index == 'advanced_multi_currency') {
                $entity->multi_currency = (int) $setting;
                $entity->save();
            }

            if ($index == 'advanced_first_month_fiscal_year') {
                $entity->year_start = (int) $setting;
                $entity->save();
            }

            if ($setting == true) {
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
                'value' => $value,
            ]);
    }
}
