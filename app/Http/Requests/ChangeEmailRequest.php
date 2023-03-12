<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;
use Auth;
use Hash;

class ChangeEmailRequest extends YokartFormRequest
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
            'code' => 'required',
            'email' => 'required|unique:users,user_email',
            'password' => 'required'
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
        $validator->after(function ($validator) {
            if (!Hash::check($this->password, $this->user()->user_password)) {
                $validator->errors()->add('password_confirmation', 'Your current password is incorrect.');
            }
        });
        return;
    }
}
