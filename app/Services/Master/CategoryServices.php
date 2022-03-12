<?php

namespace App\Services\Master;

use Carbon\Carbon;
use Illuminate\Support\Str;

class CategoryServices
{
    public function storeData($detail, $request, $data, $type)
    {
        $data->name = Str::ucfirst($detail['name']);
        $data->code = Str::upper($detail['code']);
        if ($type == 'update') {
            $data->updated_at = Carbon::now();
        }
        $data->save();
    }
}
