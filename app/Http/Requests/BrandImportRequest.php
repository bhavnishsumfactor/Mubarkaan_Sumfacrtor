<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class BrandImportRequest extends YokartFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|max:10000|mimes:xls,xlsx'
        ];
    }

    public function attributes()
    {
        return [
            'file' => __('Import file')
        ];
    }
}
