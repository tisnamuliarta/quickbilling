<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class StoreUserRequest extends FormRequest
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
            'entity_id' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'name' => 'required',
            'role' => 'required',
            'enabled' => 'required',
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
            'entity_id.required' => __('validation')['required'],
            'username.required' => __('validation')['required'],
            'email.required' => __('validation')['required'],
            'name.required' => __('validation')['required'],
            'role.required' => __('validation')['required'],
            'enabled.required' => __('validation')['required'],
            'username.unique' => __('validation')['unique'],
            'email.unique' => __('validation')['unique'],
        ];
    }
}
