<?php

namespace App\Traits;

use App\Models\Option;
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
            $arr_form[$form] = null;
        }
        return $arr_form;
    }

    /**
     * @param $name
     * @param $type
     * @return void
     */
    public function createOption($name, $type)
    {
        $check_option = Option::where('option_name', '=', $name)->first();
        if (!$check_option) {
            Option::create([
                'option_name' => $name,
                'option_type' => $type
            ]);
        }
    }

}
