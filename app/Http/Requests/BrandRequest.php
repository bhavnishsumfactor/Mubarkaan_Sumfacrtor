<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class BrandRequest extends YokartFormRequest
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
        $uniqueFieldRule = '';
        $recordId =  last(request()->segments());
        if (is_numeric($recordId)) {
            $uniqueFieldRule = ','.$recordId.',brand_id';
        }
        return [
            'brand_name' => 'required|max:191|unique:brands,brand_name'.$uniqueFieldRule
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
            'brand_name' => 'Brand Name',
        ];
    }
}
