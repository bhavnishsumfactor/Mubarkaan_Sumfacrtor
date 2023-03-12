<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class BlogPostRequest extends YokartFormRequest
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
            'post_title' => 'required',
            'post_author_name' => 'required',
            'bpc_short_description' => 'required',
            'bpc_description' => 'required',
            'ptc_bpcat_id' => 'required'
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
            'post_title' => __('Title'),
            'post_author_name' => __('Author Name'),
            'ptc_bpcat_id' => __('Category Name'),
            'bpc_short_description' => __('Short Description'),
            'bpc_description' => __('Description')
        ];
    }
}
