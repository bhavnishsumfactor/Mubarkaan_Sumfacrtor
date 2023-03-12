<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class UserLocalizationRequest extends YokartFormRequest
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
            'user_country_id' => 'required',
            'user_language_id' => 'required',
            'user_currency_id' => 'required',
            'user_timezone' => 'required',
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
            'user_country_id' => 'region',
            'user_language_id' => 'language',
            'user_currency_id' => 'currency',
            'user_timezone' => 'timezone',
        ];
    }
}
