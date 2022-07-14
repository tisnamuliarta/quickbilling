<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\StoreSettingRequest;
use App\Http\Requests\Settings\UpdateSettingRequest;
use App\Models\Settings\Setting;
use App\Services\Settings\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public $setting;

    public function __construct(SettingService $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $page = $request->page;

        return $this->success([
            'form' => $this->setting->getForm($page),
            'url' => url('/'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Settings\StoreSettingRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSettingRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->setting->store($request);

            return $this->success([], 'Data saved!');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), '422', [
                'errors' => $exception->getMessage(),
                'trace' => $exception->getTrace(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Settings\UpdateSettingRequest  $request
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Settings\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
