<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class TestimonialRequest extends YokartFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'testimonial_user_name' => 'required|max:255',
            'testimonial_title' => 'required|max:255',
            'testimonial_description' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'testimonial_user_name' => 'Testimonial Username',
            'testimonial_title' => 'Testimonial Title',
            'testimonial_description' => 'Testimonial Description'
        ];
    }
}
