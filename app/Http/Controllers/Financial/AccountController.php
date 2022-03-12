<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\Financial\Account;
use App\Traits\Accounting;
use App\Traits\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    use Categories;
    use Accounting;

    /**
     * MasterUserController constructor.
     */
    public function __construct()
    {
//        $this->middleware(['direct_permission:Roles-index'])->only(['index', 'show', 'permissionRole']);
//        $this->middleware(['direct_permission:Roles-store'])->only(['store', 'storePermissionRole']);
//        $this->middleware(['direct_permission:Roles-edits'])->only('update');
//        $this->middleware(['direct_permission:Roles-erase'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $options = $request->options;
        $pages = isset($options->page) ? (int)$options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 100;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "number";
        $order = isset($options->sortDesc[0]) ? (string)$options->sortDesc[0] : "asc";
        $offset = ($pages - 1) * $row_data;

        $result = array();
        $query = Account::selectRaw(
            " accounts.*,
             categories.name as category,
             banks.name as bank,
             taxes.name as tax,
             'actions' as ACTIONS "
        )
            ->leftJoin('categories', 'categories.id', 'accounts.category_id')
            ->leftJoin('taxes', 'taxes.id', 'accounts.tax_id')
            ->leftJoin('banks', 'banks.id', 'accounts.bank_id')
            ->where('categories.type', 'Account Category');

        $result["total"] = $query->count();

        $all_data = $query->offset($offset)
            ->orderBy($sorts, $order)
            ->limit($row_data)
            ->get();

        $arr_rows = Account::pluck('name');

        $result = array_merge($result, [
            "rows" => $all_data,
            "simple" => $arr_rows
        ]);
        return $this->success($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($this->validation($request)) {
            return $this->error($this->validation($request), 422, [
                "errors" => true
            ]);
        }
        DB::beginTransaction();
        $form = $request->form;
        try {
            $data = [
                'name' => $form['name'],
                'company_id' => session('company_id'),
                'category_id' => $this->categoryIdByName($form['category']),
                'bank_id' => (array_key_exists('bank', $form)) ? $this->bankIdByName($form['bank']) : 0,
                'details' => (array_key_exists('details', $form)) ? $form['details'] : '',
                'opening_balance' => (array_key_exists('opening_balance', $form)) ? $form['opening_balance'] : 0,
                'descriptions' => (array_key_exists('descriptions', $form)) ? $form['descriptions'] : '',
                'number' => (array_key_exists('number', $form)) ? $form['number'] : '',
                'currency_code' => 'IDR',
            ];
            Account::create($data);

            DB::commit();
            return $this->success([
                "errors" => false
            ], 'Data inserted!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage(), 422, [
                "errors" => true,
                "Trace" => $exception->getTrace()
            ]);
        }
    }

    /**
     * @param $request
     * @return false|string
     */
    protected function validation($request)
    {
        $messages = [
            'form.name' => 'Name is required!',
            'form.number' => 'Account Number is required!',
        ];

        $validator = Validator::make($request->all(), [
            'form.name' => 'required',
            'form.number' => 'required',
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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $data = Account::where("id", "=", $id)->get();

        return $this->success([
            'rows' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
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

            Account::where("id", "=", $id)->update($data);

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
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $details = Account::where("id", "=", $id)->first();
        if ($details) {
            Account::where("id", "=", $id)->delete();
            return $this->success([
                "errors" => false
            ], 'Row deleted!');
        }

        return $this->error('Row not found', 422, [
            "errors" => true
        ]);
    }
}
