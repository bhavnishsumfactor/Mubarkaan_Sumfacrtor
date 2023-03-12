<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class UserCardRequest extends YokartFormRequest
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
            'number' => 'required|numeric|digits_between:13,19',
            'name' => 'required|string|max:191',
            'exp_month' => 'required|digits:2',
            'exp_year' => 'required|digits:4',
            'cvv' => 'required|numeric|digits_between:3,4'
        ];
    }
    
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'number' => 'card number',
            'exp_month' => 'expiry month',
            'exp_year' => 'expiry year',
        ];
    }
}
