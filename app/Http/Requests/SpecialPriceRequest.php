<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class SpecialPriceRequest extends YokartFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'splprice_name' => 'required|max:255',
            'splprice_type' => 'required',
            'splprice_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'splprice_startdate' => 'required|date',
            'splprice_enddate' => 'required|date|after_or_equal:splprice_startdate',
            'splprice_include' => 'required',
            'splprice_includes' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'splprice_name' => 'Name',
            'splprice_type' => 'Type',
            'splprice_amount' => 'Amount',
            'splprice_startdate' => 'Start date',
            'splprice_enddate' => 'End date',
            'splprice_include' => 'Include',
            'splprice_includes' => 'Record linking',
        ];
    }
}
