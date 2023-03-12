<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class StoreAddressRequest extends YokartFormRequest
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
            'saddr_name' => 'required',
            'saddr_country_id' => 'required',
            'saddr_state_id' => 'required',
            'saddr_city' => 'required',
            'saddr_address' => 'required',
            'saddr_postal_code' => 'required',
            'saddr_phone' => 'required',
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
            'saddr_name' => 'store name',
            'saddr_country_id' => 'country name',
            'saddr_state_id' => 'state name',
            'saddr_city' => 'city name',
            'saddr_address' => 'address',
            'saddr_postal_code' => 'postal code',
            'saddr_phone' => 'phone',
        ];
    }
}
