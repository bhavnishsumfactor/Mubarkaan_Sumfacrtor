<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class UserGroupRequest extends YokartFormRequest
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
        $recordId =  last(request()->segments());
        $uniqueRule = '';
        $sometimes = '';
        $required = 'required|';
        if (is_numeric($recordId)) {
            $uniqueRule .= ','.$recordId.',ugroup_id';
            $sometimes = 'sometimes|';
        }
        return [
            'ugroup_name' => $sometimes.$required.'unique:user_groups,ugroup_name'.$uniqueRule
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
            'ugroup_name' => 'group name',
        ];
    }
}
