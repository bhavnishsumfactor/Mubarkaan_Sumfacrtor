<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class UrlRewriteRequest extends YokartFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $uniqueFieldRule = '';
        $recordId =  request()->input('urlrewrite_id');
        if (is_numeric($recordId)) {
            $uniqueFieldRule = ','.$recordId.',urlrewrite_id';
        }
        return [
            'urlrewrite_custom' => 'nullable|max:191|unique:url_rewrites,urlrewrite_custom'.$uniqueFieldRule
        ];
    }

    public function attributes()
    {
        return [
            'urlrewrite_custom' => 'custom url',
        ];
    }
}
