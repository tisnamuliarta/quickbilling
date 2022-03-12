<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $type = $request->type;
        $data = [];
        $header = [];

        $query = Category::where('type', $type);

        if ($type == 'Item Category' || $type == 'Account Category' || $type == 'Tax Category') {
            $data = clone $query;
            $data = $data->select('id', 'name')->orderBy('name')->get();
            if (count($data) < 1) {
                $data = [
                    [
                        'id' => null,
                        'name' => null,
                    ]
                ];
            }
            $header = ['id', 'Name'];
        }

        $simple = clone $query;
        $simple = $simple->orderBy('name')->pluck('name');

        return $this->success([
            'rows' => $data,
            'header' => $header,
            'simple' => $simple,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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

                $data = Category::where('id', '=', $detail['id'])->first();
                if ($data) {
                    $data->name = Str::ucfirst($detail['name']);
                    $data->updated_at = Carbon::now();
                    $data->type = $type;
                } else {
                    $data = new Category();
                    $data->name = Str::ucfirst($detail['name']);
                    $data->type = $type;
                    $data->code = Str::upper(Str::limit($detail['name'], 15));
                    $data->color = '';
                    $data->company_id = session('company_id');
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
        $brand = Category::find($id);
        return $this->success($brand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            Category::whereIn('id', $id)->delete();
            DB::commit();
            return $this->success([], 'Data updated!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage(), '422');
        }
    }
}
