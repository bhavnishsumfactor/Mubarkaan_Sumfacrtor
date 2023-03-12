<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class ShippingBoxPackageRequest extends YokartFormRequest
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
            'sbpkg_name' => 'required',
            'sbpkg_length' => 'required',
            'sbpkg_width' => 'required',
            'sbpkg_heigth' => 'required',
            'sbpkg_dimension_type' => 'required',
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
            'sbpkg_name.required' => 'The name field is required.',
            'sbpkg_length.required' => 'The length field is required.',
            'sbpkg_width.required' => 'The width field is required.',
            'sbpkg_heigth.required' => 'The heigth field is required.',
            'sbpkg_dimension_type.required' => 'The type field is required.',
         ];
    }



}
