<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\ProductBrand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $brands = ProductBrand::select('product_brand_id', 'brand_name')->get();
        if (count($brands) < 1) {
            $brands = [
                [
                    'product_brand_id' => null,
                    'brand_name' => null,
                ]
            ];
        }

        return $this->success([
            'rows' => $brands,
            'header' => ['Brand Id', 'Brand Name']
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
        DB::beginTransaction();
        try {
            foreach ($details as $detail) {
                if (empty($detail['brand_name'])) {
                    return $this->error('Brand cannot empty', '422');
                }
                $brand = ProductBrand::where('product_brand_id', '=', $detail['product_brand_id'])->first();
                if ($brand) {
                    $brand->brand_name = Str::ucfirst($detail['brand_name']);
                    $brand->updated_at = Carbon::now();
                    $brand->updated_by = $request->user()->id;
                } else {
                    $brand = new ProductBrand();
                    $brand->brand_name = Str::ucfirst($detail['brand_name']);
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
        $brand = ProductBrand::find($id);
        return $this->success($brand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param ProductBrand $productBrand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductBrand $productBrand)
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
            ProductBrand::whereIn('product_brand_id', $id)->delete();
            DB::commit();
            return $this->success([], 'Data updated!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage(), '422');
        }
    }
}
