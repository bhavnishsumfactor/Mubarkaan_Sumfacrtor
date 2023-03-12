<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class UserAddressRequest extends YokartFormRequest
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
		$rules = [
            'addr_first_name' => 'required',
            'addr_last_name' => 'required',
			'addr_title' => 'required',
			'addr_address1' => 'required',
			'addr_city' => 'required',
			'addr_country_id' => 'required|integer',
			'addr_state_id' => 'required|integer',
			'addr_postal_code' => 'required|numeric',
			'addr_phone' => 'required|numeric',
		];
		return $rules;
        
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
		$title = [
            'addr_first_name' => 'first name',
            'addr_last_name' => 'last name',
            'addr_title' => 'title',     
            'addr_address1' => 'address1',
			'addr_city' => 'city name',
			'addr_country_id' => 'country name',
			'addr_state_id' => 'state name',
			'addr_postal_code' => 'postal code',
			'addr_phone' => 'phone number',
        ];
        return $title;
    }
}
