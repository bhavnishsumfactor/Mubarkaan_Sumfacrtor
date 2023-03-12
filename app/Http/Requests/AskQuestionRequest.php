<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class AskQuestionRequest extends YokartFormRequest
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
			'subject' => 'required|max:255',
			'message' => 'required',
		];
    }
	
	/*public function attributes()
    {
        return [
            'subject' => __('Title'),
            'message' => __('Message'),
        ];
    }*/

    public function messages()
    {
        return [
            'subject.required' => 'The title field is required.',
            'message.required' => 'The message1 field is required.',
         ];
    }
}
