<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class ProductCategoryRequest extends YokartFormRequest
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
            $uniqueFieldRule = ','.$recordId.',cat_id';
        }
        return [
            'cat_name' => 'required|max:191',
            'cat_parent' => 'required'
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
            'cat_name' => __('Name'),
            'cat_parent' => __('Parent')
        ];
    }
}
