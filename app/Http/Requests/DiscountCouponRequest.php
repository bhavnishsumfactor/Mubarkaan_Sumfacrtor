<?php

namespace App\Http\Requests;

use App\Http\Requests\YokartFormRequest;

class DiscountCouponRequest extends YokartFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $recordId =  last(request()->segments());
        $uniqueCodeRule = '';
        if (is_numeric($recordId)) {
            $uniqueCodeRule .= ','.$recordId.',discountcpn_id';
        }
        return [
            'discountcpn_code' => 'required|max:15|unique:discount_coupons,discountcpn_code'.$uniqueCodeRule,
            'discountcpn_type' => 'required',
            'discountcpn_total_uses' => 'required|numeric',
            'discountcpn_uses_per_user' => 'required|numeric',
            'discountcpn_maxamt_limit' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'discountcpn_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'discountcpn_startdate' => 'required|date',
            'discountcpn_enddate' => 'required|date|after_or_equal:discountcpn_startdate'
        ];
    }

    public function attributes()
    {
        return [
            'discountcpn_code' => 'Code',
            'discountcpn_type' => 'Applies to',
            'discountcpn_total_uses' => 'Total uses',
            'discountcpn_uses_per_user' => 'Uses per user',
            'discountcpn_maxamt_limit' => 'Max amount limit',
            'discountcpn_amount' => 'Amount',
            'discountcpn_startdate' => 'Start date',
            'discountcpn_enddate' => 'End date'
        ];
    }
}
