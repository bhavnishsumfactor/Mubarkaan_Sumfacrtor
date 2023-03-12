<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class ProductReviewCommentRequest extends YokartFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reply' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'reply' => 'Reply'
        ];
    }
}
