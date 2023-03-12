<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class ReviewRequest extends YokartFormRequest
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
            'preview_rating' => 'required|numeric|digits_between:1,5',
            'preview_title' => 'required|string|max:255'
        ];
    }
    
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'preview_rating' => 'rating',
            'preview_title' => 'title'
        ];
    }
}
