<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class LanguageRequest extends YokartFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|max:10000|mimes:xls'
        ];
    }

    public function attributes()
    {
        return [
            'file' => __('Import file')
        ];
    }
}
