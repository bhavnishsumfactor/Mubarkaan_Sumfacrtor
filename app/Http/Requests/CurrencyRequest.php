<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class CurrencyRequest extends YokartFormRequest
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
            'currency_symbol' => 'required',
            'currency_position' => 'required',
            'currency_is_default' => 'required'
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
            'currency_symbol' => __('symbol'),
            'currency_position' => __('position'),
            'currency_is_default' => __('default currency')
        ];
    }
}
