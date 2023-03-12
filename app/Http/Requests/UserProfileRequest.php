<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;
use Auth;
use Hash;

class UserProfileRequest extends YokartFormRequest
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
            'user_fname' => 'required|string|max:191',
            'user_lname' => 'required|string|max:191',
            'user_dob' => 'required'
        ];
    }
}
