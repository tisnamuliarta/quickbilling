<?php

namespace App\Traits;

use App\Models\Settings\Setting;
use Illuminate\Support\Facades\DB;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param array|string $data
     * @param string|null $message
     * @param int|null $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data, string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param string|null $message
     * @param int $code
     * @param array|string|null $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(string $message = null, int $code = 422, $data = null)
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => $data
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

        $currency = Setting::leftJoin('currencies', 'currencies.code', 'settings.value')
            ->where('settings.key', 'company_currency_code')
            ->select('currencies.code', 'currencies.symbol')
            ->first();

        $arr_form['company_id'] = session('company_id');
        $arr_form['default_currency_code'] = $currency->code;
        $arr_form['default_currency_symbol'] = $currency->symbol;

        return $arr_form;
    }
}
