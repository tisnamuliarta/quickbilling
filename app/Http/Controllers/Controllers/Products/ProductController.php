<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $pagination = (object)$request->pagination;
        $pages = isset($pagination->page) ? (int)$pagination->page : 1;
        $row_data = isset($pagination->itemsPerPage) ? (int)$pagination->itemsPerPage : 20;
        $sorts = isset($pagination->sortBy[0]) ? (string)$pagination->sortBy[0] : 'product_name';
        $order = isset($pagination->sortDesc[0]) ? 'ASC' : 'DESC';
        $data_status = isset($request->dataStatus) ? (string)$request->dataStatus : 'open';

        $search = isset($request->q) ? (string)$request->q : '';
        $select_data = isset($request->selectData) ? (string)$request->selectData : 'product_name';
        $offset = ($pages - 1) * $row_data;
        $form = $this->form('products');

        $result = array();
        $query = Product::selectRaw("*, 'actions' as ACTIONS");

        $result["total"] = $query->count();

        $all_data = $query->offset($offset)
            ->orderBy($sorts, $order)
            ->limit($row_data)
            ->get();

        $result = array_merge($result, [
            "rows" => $all_data,
            'form' => $form,
        ]);

        return $this->success($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($this->validation($request)) {
            return $this->error($this->validation($request), 422, [
                "errors" => true
            ]);
        }

        $form = $request->form;
        try {
            $data = [
                'name' => $form['name'],
                'guard_name' => 'web',
                'description' => $form['description'],
            ];
            Product::create($data);

            return $this->success([
                "errors" => false
            ], 'Data inserted!');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                "errors" => true,
                "Trace" => $exception->getTrace()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($product)
    {
        $data = Product::where("product_id", "=", $product)->first();

        return $this->success([
            'rows' => $data
        ]);
    }

    /**
     * @param $request
     * @return false|string
     */
    protected function validation($request)
    {
        $messages = [
            'form.product_name' => 'Product Name is required!',
        ];

        $validator = Validator::make($request->all(), [
            'form.product_name' => 'required',
        ], $messages);

        $string_data = "";
        if ($validator->fails()) {
            foreach (collect($validator->messages()) as $error) {
                foreach ($error as $items) {
                    $string_data .= $items . " \n  ";
                }
            }
            return $string_data;
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if ($this->validation($request)) {
            return $this->error($this->validation($request), 422, [
                "errors" => true
            ]);
        }

        $form = $request->form;
        try {
            $data = [
                'name' => $form['name'],
                'guard_name' => 'web',
                'description' => $form['description'],
            ];

            Product::where("product_id", "=", $id)->update($data);

            return $this->success([
                "errors" => false
            ], 'Data updated!');
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                "errors" => true,
                "Trace" => $exception->getTrace()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $details = Product::where("product_id", "=", $id)->first();
        if ($details) {
            Product::where("product_id", "=", $id)->delete();
            return $this->success([
                "errors" => false
            ], 'Row deleted!');
        }

        return $this->error('Row not found', 422, [
            "errors" => true
        ]);
    }
}
