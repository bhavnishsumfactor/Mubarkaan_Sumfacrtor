<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;
use Auth;
use Hash;

class ChangePasswordRequest extends YokartFormRequest
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
            'old_password' => 'required',
            'confirm_password' => 'required',
            'new_password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'
        ];
    }
    
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        if (!empty($this->old_password)) {
            $validator->after(function ($validator) {
                if (!Hash::check($this->old_password, $this->user()->user_password)) {
                    $validator->errors()->add('old_password', 'Your current password is incorrect.');
                }
            });
        }
        return;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'old_password' => 'old password',
            'new_password' => 'new password',
            'confirm_password' => 'confirm password'
        ];
    }

    public function messages()
    {
        return [
            'new_password.regex' => 'Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.',
         ];
    }
}
