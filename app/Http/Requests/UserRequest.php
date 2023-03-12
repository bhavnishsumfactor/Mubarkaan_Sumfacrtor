<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class UserRequest extends YokartFormRequest
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
        $requiredPassword = 'required|';
        $oldRequiredPassword = '';
        if (is_numeric($recordId)) {
            $uniqueRule .= ','.$recordId.',user_id';
            $sometimes = 'sometimes|';
        }
        return [
            'user_fname' => $sometimes.$required,
            'user_lname' => $sometimes.$required,
            'user_email' => $sometimes.$required.'email|unique:users,user_email'.$uniqueRule,
            'user_phone' => $sometimes.$required.'unique:users,user_phone'.$uniqueRule,
            'user_password' => $sometimes.$requiredPassword.'same:user_confirm_password|regex:/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'user_old_password' =>$oldRequiredPassword
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
            'user_confirm_password' => 'confirm password',
        ];
    }

    public function messages()
    {
        return [
            'user_password.regex' => 'Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.',
         ];
    }
}
