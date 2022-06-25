<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ItemCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $brands = ItemCategory::select('category_id', 'category_name')->get();
        if (count($brands) < 1) {
            $brands = [
                [
                    'category_id' => null,
                    'category_name' => null,
                ],
            ];
        }

        return $this->success([
            'rows' => $brands,
            'header' => ['Brand Id', 'Category Name'],
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
                if (empty($detail['category_name'])) {
                    return $this->error('Brand cannot empty', '422');
                }
                $brand = ItemCategory::where('category_id', '=', $detail['category_id'])->first();
                if ($brand) {
                    $brand->category_name = Str::ucfirst($detail['category_name']);
                    $brand->updated_at = Carbon::now();
                    $brand->updated_by = $request->user()->id;
                } else {
                    $brand = new ItemCategory();
                    $brand->category_name = Str::ucfirst($detail['category_name']);
                    $brand->created_at = Carbon::now();
                    $brand->created_by = $request->user()->id;
                }
                $brand->save();
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
        $brand = ItemCategory::find($id);

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
            ItemCategory::whereIn('category_id', $id)->delete();
            DB::commit();

            return $this->success([], 'Data updated!');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), '422');
        }
    }
}
