<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoController extends Controller
{
    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->success([
            'logo' => url('/files/logo/quickbilling-white.png'),
            'default' => url('/files/logo/quickbilling-circle.png'),
            'bgLogin' => url('/files/034.png'),
        ]);
    }

    public function store(Request $request)
    {
        //
    }
}
