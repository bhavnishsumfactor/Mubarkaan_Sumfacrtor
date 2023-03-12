<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;
use Auth;
use Hash;

class ChangePhoneDirectRequest extends YokartFormRequest
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
            'user_phone' => 'required|numeric|unique:users'
        ];
    }
}
