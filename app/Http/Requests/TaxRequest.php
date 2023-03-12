<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class TaxRequest extends YokartFormRequest
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
            'taxgrp_name' => 'required',
            'tgrule_name' => 'required',
            'tgrule_rate' => 'required',
            'tgrule_country_id' => 'required',
            'tgrule_state_type' => 'required',
            'state_ids' => 'required',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
   
    
    public function messages()
    {
        return [
            'taxgrp_name.required' => 'The tax group name field is required.',
            'taxgrp_description.required' => 'The tax summary field is required.',
            'tgtype_name.required' => 'The tax rule name field is required.',
            'tgtype_rate.required' => 'The tax rule rate field is required.',
            'tgtype_country_id.required' => 'The tax rule country field is required.',
            'tgtype_state_type.required' => 'The tax rule states specific field is required.',
            'state_ids.required' => 'The tax rule states field is required.',
         ];
    }
}
