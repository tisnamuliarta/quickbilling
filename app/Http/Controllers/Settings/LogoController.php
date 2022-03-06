<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\Setting;
use Illuminate\Http\Request;

class LogoController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $logo = Setting::where('key', '=', 'company_logo')->first();
        if ($logo->value) {
            return $this->success([
               'logo' => url('/files/logo/'.$logo->value)
            ]);
        }

        return $this->success([
            'logo' => url('/files/logo/tizapps.svg')
        ]);
    }

    public function store(Request $request)
    {
        //
    }
}
