<?php

namespace App\Http\Requests\Payrolls;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class StoreLoanRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'required',
            'transaction_date' => 'required',
            'loan_type' => 'required',
            'amount' => 'required',
            'interest_type' => 'required',
            'interest_rate' => 'required',
            'installment_amount' => 'required',
            'installment_start_date' => 'required',
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
            'employee_id.required' => __('validation')['required'],
            'transaction_date.required' => __('validation')['required'],
            'loan_type.required' => __('validation')['required'],
            'amount.required' => __('validation')['required'],
            'interest_type.required' => __('validation')['required'],
            'interest_rate.required' => __('validation')['required'],
            'installment_amount.required' => __('validation')['required'],
            'installment_start_date.required' => __('validation')['required'],
        ];
    }
}
