<?php

namespace App\Http\Controllers\Students\Api;

use App\Http\Controllers\Controller;
use App\Models\Student\District;
use App\Models\Student\Regency;
use App\Models\Student\Village;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function regency(Request $request)
    {
        $regency = Regency::where('province_id', '=', $request->province_id)->orderBy('name')->get();

        $options = "<option value=''> Pilih Kabupaten </option>";
        foreach ($regency as $item) {
            $options .= "<option value=' $item->id '> $item->name </option>";
        }
        return response()->json(
            $options
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function districts(Request $request)
    {
        $district = District::where('regency_id', '=', $request->regency_id)->orderBy('name')->get();
        $options = "<option value=''> Pilih Kecamatan </option>";
        foreach ($district as $item) {
            $options .= "<option value=' $item->id '> $item->name </option>";
        }
        return response()->json(
            $options
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function villages(Request $request)
    {
        $villages = Village::where('district_id', '=', $request->district_id)->orderBy('name')->get();
        $options = "<option value=''> Pilih Desa </option>";
        foreach ($villages as $item) {
            $options .= "<option value=' $item->id '> $item->name </option>";
        }
        return response()->json(
            $options
        );
    }
}
