<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ItemUnit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $type = $request->type;
        $data = [];
        $header = [];
        $data = ItemUnit::select('id', 'name')->get();
        if (count($data) < 1) {
            $data = [
                [
                    'id' => null,
                    'name' => null,
                ],
            ];
        }
        $header = ['id', 'Name'];

        $simple = ItemUnit::pluck('name');

        return $this->success([
            'rows' => $data,
            'header' => $header,
            'simple' => $simple,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $details = collect($request->details);
        DB::beginTransaction();
        try {
            foreach ($details as $detail) {
                if (empty($detail['name'])) {
                    return $this->error('Name cannot empty', '422');
                }

                $data = ItemUnit::where('id', '=', $detail['id'])->first();
                if ($data) {
                    $data->name = Str::ucfirst($detail['name']);
                    $data->updated_at = Carbon::now();
                } else {
                    $data = new ItemUnit();
                    $data->name = Str::ucfirst($detail['name']);
                    $data->created_at = Carbon::now();
                }
                $data->save();
            }
            DB::commit();

            return $this->success([], 'Rows updated!');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), '422');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            ItemUnit::whereIn('id', $id)->delete();
            DB::commit();

            return $this->success([], 'Data updated!');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), '422');
        }
    }
}
