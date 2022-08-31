<?php

namespace App\Http\Requests\Payrolls;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class StorePayrollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'transaction_no' => 'required',
            'narration' => 'required',
            'account_id' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        App::setLocale(auth()->user()->locale);

        return [
            'transaction_no.required' => __('validation')['required'],
            'narration.required' => __('validation')['required'],
            'account_id.required' => __('validation')['required'],
        ];
    }
}
