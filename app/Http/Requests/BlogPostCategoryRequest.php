<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class BlogPostCategoryRequest extends YokartFormRequest
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
            $uniqueFieldRule = ','.$recordId.',bpcat_id';
        }
        return [
            'bpcat_name' => 'required|max:191|unique:blog_post_categories,bpcat_name'.$uniqueFieldRule,
            'bpcat_parent' => 'required'
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
            'bpcat_name' => __('Name'),
            'bpcat_parent' => __('Parent')
        ];
    }
}
