<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Bank;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = Bank::select('id', 'name', 'code', 'swift_code', 'phone', 'address')->get();
        if (count($data) < 1) {
            $data = [
                [
                    'id' => null,
                    'name' => null,
                    'code' => null,
                    'swift_code' => null,
                    'phone' => null,
                    'address' => null,
                ],
            ];
        }
        $header = ['id', 'Name', 'Code', 'Swift Code', 'Phone', 'Address'];

        $simple = Bank::pluck('name');

        return $this->success([
            'data' => $data,
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
        $type = $request->type;
        DB::beginTransaction();
        try {
            foreach ($details as $detail) {
                if (empty($detail['name'])) {
                    return $this->error('Name cannot empty', '422');
                }

                $data = Bank::where('id', '=', $detail['id'])->first();
                if ($data) {
                    $data->name = Str::ucfirst($detail['name']);
                    $data->code = $detail['code'];
                    $data->swift_code = $detail['swift_code'];
                    $data->phone = $detail['phone'];
                    $data->address = $detail['address'];
                    $data->updated_at = Carbon::now();
                    $data->type = $type;
                } else {
                    $data = new Bank();
                    $data->name = Str::ucfirst($detail['name']);
                    $data->code = $detail['code'];
                    $data->swift_code = $detail['swift_code'];
                    $data->phone = $detail['phone'];
                    $data->address = $detail['address'];
                    $data->created_at = Carbon::now();
                    $data->created_by = $request->user()->id;
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
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $brand = Bank::find($id);

        return $this->success($brand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
            Bank::whereIn('id', $id)->delete();
            DB::commit();

            return $this->success([], 'Data updated!');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), '422');
        }
    }
}
