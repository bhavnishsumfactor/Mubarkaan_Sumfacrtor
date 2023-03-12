<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class PageRequest extends YokartFormRequest
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
            'page_name' => 'required|max:191|unique:pages,page_name'
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
            'page_name' => 'name',
        ];
    }
}
