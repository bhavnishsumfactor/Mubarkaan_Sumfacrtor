<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;
use Auth;
use Hash;
use App\Models\Country;
use Illuminate\Validation\Rule;

class AdminRequest extends YokartFormRequest
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
        $uniqueEmailRule = '';
        $uniqueRule = '';
        $sometimes = '';
        $required = 'required|';
        $requiredPassword = 'required|';
        $oldRequiredPassword = '';
        if (is_numeric($recordId)) {
            $uniqueEmailRule .= ','.$recordId.',admin_id';
            $sometimes = 'sometimes|';
            $uniqueRule .= ','.$recordId.',admin_id';
        }
        if (request()->segments()[1] == 'change-password') {
            $required = '';
            $oldRequiredPassword = 'required';
        }
        if (request()->segments()[1] == 'edit-profile') {
            $requiredPassword = '';
        }
        $rules = [
            'admin_name' => $sometimes.$required,
            'admin_email' => $sometimes.$required.'email|unique:admins,admin_email'.$uniqueEmailRule,
            'admin_username' => $sometimes.$required.'unique:admins,admin_username'.$uniqueEmailRule,
            //'admin_phone' => $sometimes.$required.'unique:admins,admin_phone'.$uniqueRule,
            'admin_password' => $sometimes.$requiredPassword.'same:admin_confirm_password|regex:/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'admin_old_password' => [
                $oldRequiredPassword, function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::guard('admin')->user()->admin_password)) {
                        $fail('Old Password didn\'t match');
                    }
                },
            ],
            'admin_role_id' => $sometimes.$required
        ];
        if (request()->segments()[1] != 'change-password') {
            $country = Country::select('country_id')->where('country_code', strtoupper(request()->input('country_code')))->first();
            if (isset($country->country_id) && !empty($country->country_id)) {
                $admin_phone_country_id = $country->country_id;
            } 
            $tempRules = [ 'required', 'numeric' ];
            $tempRules[] = Rule::unique('admins')->ignore($recordId, 'admin_id')->where('admin_phone_country_id', $admin_phone_country_id);
            $rules['admin_phone'] = $tempRules;
        }
        return $rules;
        
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'confirmpassword' => 'confirm password',
            'role_id' => 'role',
        ];
    }
    public function messages()
    {
        return [
            'admin_password.regex' => 'Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.',
            'admin_password.same' =>  'The password and confirm password must match.',
         ];
    }
}

