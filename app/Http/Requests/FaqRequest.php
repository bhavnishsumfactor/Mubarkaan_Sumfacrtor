<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;
use Illuminate\Validation\Rule;

class FaqRequest extends YokartFormRequest
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
            'faq_category' => 'required',
            'faq_title' => [
                'required',
                Rule::unique('faqs', 'faq_title')->ignore($this->faq, 'faq_id')
            ],
            'faq_content' => 'required'
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
            'faq_category' => 'category',
            'faq_title' => 'title',
            'faq_content' => 'content'
        ];
    }
}
