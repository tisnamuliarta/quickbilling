<?php

namespace App\Services\Settings;

use App\Models\Settings\Setting;
use IFRS\Models\Entity;
use Illuminate\Support\Arr;

class EntityService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $query = Entity::first();
        $simple = Entity::select('id', 'name')->get();
        $logo = Setting::where('key', 'company_logo')->first();

        return [
            'data' => $query,
            'status' => ($query) ? 'update' : 'insert',
            'simple' => $simple,
            'logo' => $logo,
            'url' => url('/'),
        ];
    }

    /**
     * @param $request
     * @return array
     */
    public function formData($request): array
    {
        $data = $request->all();

        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'destroyed_at');
        Arr::forget($data, 'id');

        return $data;
    }
}
