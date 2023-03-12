<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class PackageConfigurationRequest extends YokartFormRequest
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
        $fields = [];
        foreach (request()->all() as $key=>$value) {
          $fields[$key] = 'required';
        }
        return $fields;
    }
}
