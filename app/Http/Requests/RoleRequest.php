<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class RoleRequest extends YokartFormRequest
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
            $uniqueFieldRule = ','.$recordId.',role_id';
        }
        return [
            'role_name' => 'required|max:191|unique:admin_roles,role_name' . $uniqueFieldRule
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
            'role_name' => 'name',
        ];
    }
}
