<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class EmailTemplateRequest extends YokartFormRequest
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
            'etpl_subject' => 'required',
            'etpl_body' => 'required'
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
            'etpl_subject' => 'subject',
            'etpl_body' => 'body',
        ];
    }
}
