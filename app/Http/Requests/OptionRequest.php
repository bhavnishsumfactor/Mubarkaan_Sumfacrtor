<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class OptionRequest extends YokartFormRequest
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
            $uniqueFieldRule = ','.$recordId.',option_id';
        }
        return [
            'option_name' => 'required|max:191|unique:options,option_name'.$uniqueFieldRule
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
            'option_name' => 'name',
        ];
    }
}
