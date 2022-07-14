<?php

namespace App\Traits;

use App\Models\Financial\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string|null  $message
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data, string $message = null, int $code = 200): \Illuminate\Http\JsonResponse
    {
        $collection = collect([
            'status' => 'Success',
            'message' => $message,
            'locale' => session('locale'),
        ]);

        $merge = $collection->merge($data);

        return response()->json($merge->all(), $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param  string|null  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(string $message = null, int $code = 422, $data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => $data,
            'locale' => session('locale'),
        ], $code);
    }

    /**
     * @param $table
     * @return array
     */
    protected function form($table): array
    {
        $forms = DB::getSchemaBuilder()->getColumnListing($table);
        $arr_form = [];
        foreach ($forms as $form) {
            if ($form != 'id' || $form != 'created_at' || $form != 'updated_at' ||
                $form != 'company_id' || $form != 'deleted_at'
            ) {
                $arr_form[$form] = null;
            }
        }

        $currency = Currency::where('currency_code', auth()->user()->entity->currency->currency_code)
            ->select('currencies.currency_code', 'currencies.currency_code')
            ->first();

        $arr_form['entity_id'] = auth()->user()->entity_id;
        $arr_form['default_currency_code'] = (isset($currency)) ? $currency->currency_code : null;
        $arr_form['default_currency_symbol'] = (isset($currency)) ? $currency->currency_symbol : null;

        return $arr_form;
    }

    /**
     * @param $table
     * @param $column
     * @return array
     */
    public function getEnumValues($table, $column): array
    {
        $type = DB::select(DB::raw("SHOW COLUMNS FROM $table WHERE Field = '{$column}'"))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $items = [];
        foreach (explode(',', $matches[1]) as $value) {
            $items[] = trim($value, "'");
        }

        return $items;
    }

    /**
     * @param $request
     * @param $rules
     * @return false|string
     */
    protected function validation($request, $rules)
    {
        $validator = Validator::make($request->all(), $rules);

        $string_data = '';
        if ($validator->fails()) {
            foreach (collect($validator->messages()) as $error) {
                foreach ($error as $items) {
                    $string_data .= $items." \n  ";
                }
            }

            return $string_data;
        } else {
            return false;
        }
    }
}
