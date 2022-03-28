<?php

namespace App\Traits;

use App\Models\Settings\Setting;

trait CompanyField
{
    /**
     * @return array
     */
    public function company(): array
    {
        $company = Setting::where('types', 'company')->get();
        $return = [];
        foreach ($company as $item) {
            $return[$item->key] = $item->value;
        }

        return $return;
    }
}
