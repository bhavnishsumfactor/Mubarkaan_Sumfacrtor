<table width="100%" cellspacing="0" cellpadding="20" border="0" style="font-size: 14px;background: #f2f2f2;font-family: Arial, sans-serif;">
    <tr>
        <td>
            <table width="1100px" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;border:2px solid #ddd;background-color: #fff;">
                <tr>
                    <td>
                        <!--main Start-->


                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="font-size: 32px;font-weight: 700;color: #000;padding: 15px;text-align:center;border-bottom: 1px solid #ddd;">Tax Invoice</td>
                            </tr>
                        </table>



                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:15px;border-bottom: 1px solid #ddd;">
                                    <h4 style="margin:0;font-size:18px;font-weight:bold;padding-bottom: 5px;">Sold By: {{$businessDetails['BUSINESS_NAME']}} ,</h4>
                                    <p style="margin:0;padding-bottom: 5px;">{{$businessDetails['BUSINESS_ADDRESS1']}} @if($businessDetails['BUSINESS_ADDRESS2']) {{','. $businessDetails['BUSINESS_ADDRESS2']}}@endif</p>
                                    <p style="margin:0;padding-bottom: 15px;">{{$businessDetails['BUSINESS_CITY']}}, {{$businessDetails['BUSINESS_STATE']}}, {{$businessDetails['BUSINESS_COUNTRY']}} - {{$businessDetails['BUSINESS_PINCODE']}}</p>

                                    @if(!empty($taxDetails['TAX_GOV_INFO']))
                                    @php $explodeTaxInfo = explode(',', $taxDetails['TAX_GOV_INFO']) @endphp

                                    <table width="100%" border="0" cellpadding="0" cellspacing="0">

                                        @php $x = 1; @endphp
                                        <tr>
                                            @foreach($explodeTaxInfo as $explodeValue)
                                            <td style="font-weight: 700; padding-top:10px; @if($x % 2 == 0){{'text-align: right;'}}@endif">{{$explodeValue}}</td>
                                            @if($x % 2 == 0)</tr>
                                        <tr>@endif
                                            @php $x++; @endphp
                                            @endforeach
                                        </tr>
                            </tr>
                        </table>
                        @endif
                    </td>
                </tr>
            </table>



            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="border-bottom: 1px solid #ddd;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                @php $x= 0; @endphp
                                @foreach($order->address as $address)

                                <td style="padding:15px;@if($x == 0){{'border-right: 1px solid #ddd;'}}@endif">
                                    <h4 style="margin:0;font-size:18px;font-weight:bold;padding-bottom: 5px;">{{$addressTypes[$address->oaddr_type]}} To</h4>
                                    <p style="margin:0;padding-bottom: 15px;">{{$address->oaddr_name}} {{$address->oaddr_address1}} @if($address->oaddr_address2){{', '.$address->oaddr_address2}}@endif ,{{$address->oaddr_city}}, {{$address->oaddr_state}}, {{$address->oaddr_country}}</p>
                                </td>
                                @php $x++; @endphp

                                @endforeach

                                taxDetailArray
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>


            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="border-bottom: 1px solid #ddd;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:15px;">
                                    <p><strong>Order:</strong> #{{$order->order_id}} </p>
                                    <p><strong>Invoice Number:</strong> {{$invoiceNumber}}</p>
                                    <p><strong>Payment Methods:</strong> {{$order->order_payment_method}} </p>

                                </td>
                                <td style="padding:15px;">
                                    <p><strong>Order Date:</strong> {{getConvertedDateTime($order->order_date_added)}}</p>
                                    <p><strong>Invoice Date:</strong> {{getConvertedDateTime($invoiceDate)}}</p>
                                    <p><strong>Total Items: {{count($order->products)}} </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>



            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="border-bottom: 1px solid #ddd;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <th style="padding:10px 15px;text-align: left;">S.No.</th>
                                <th style="padding:10px 15px;text-align: left;">Item</th>
                                @if($taxDetails['TAX_CODE'] == 1)
                                <th style="padding:10px 15px;text-align: center;">Category Tax Code</th>
                                @endif
                                <th style="padding:10px 15px;text-align: center;">Qty</th>
                                <th style="padding:10px 15px;text-align: center;">MRP</th>
                                <th style="padding:10px 15px;text-align: center;">Total Amt</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="padding:10px 15px;text-align: left;background-color: #f0f0f0;font-size: 18px;">ORDER ITEMS</th>
                            </tr>

                            @php
                            $x = 1;
                            $calculatedRefund = [];
                            $totalPrice = 0;
                            $totalCost = 0;
                            $totalQty = 0;
                            @endphp
                            @foreach($order->products as $product)
                            @php
                            $qty = $product->op_qty;
                            $totalPrice += $product->op_product_price * $qty;
                            $totalCost += $product->op_product_price;
                            $totalQty += $qty;
                            $variants = [];
                            if(!empty($product->op_product_variants)){
                            $variants = json_decode($product->op_product_variants, true);
                            }

                            @endphp
                            <tr>
                                <td style="padding:10px 15px;text-align: left;">{{$x}}</td>
                                <td style="padding:10px 15px;text-align: left;">{{$product->op_product_name}}

                                    @if(!empty($variants['styles']))
                                    @php $variantNames = []; @endphp
                                    @foreach($variants['styles'] as $key => $variant)
                                    @php $variantNames[] = $variant; @endphp
                                    @endforeach
                                    @php $variantNames = implode(" | ", $variantNames); @endphp
                                    {{Str::title($variantNames)}}
                                    @endif
                                </td>
                                @if($taxDetails['TAX_CODE'] == 1)
                                <td style="padding:10px 15px;text-align: center;">{{$product->additionInfo->opainfo_cat_tax_code}}</td>
                                @endif
                                <td style="padding:10px 15px;text-align: center;">{{$qty}}</td>
                                <td style="padding:10px 15px;text-align: center;">{{displayPrice($product->op_product_price)}}</td>

                                <td style="padding:10px 15px;text-align: center;">{{displayPrice(($product->op_product_price * $qty))}}</td>
                            </tr>
                            @php $x++; @endphp
                            @endforeach

                            <tr>
                                <td style="padding:10px 15px;font-size:18px;text-align: left;font-weight:700;background-color: #f0f0f0;" colspan="3">Summary </td>
                                <td style="padding:10px 15px;text-align: center;background-color: #f0f0f0;font-size: 16px;"><strong>{{$totalQty}}</strong></td>
                                <td style="padding:10px 15px;text-align: center;background-color: #f0f0f0;font-size: 16px;"><strong>{{displayPrice($totalCost)}}</strong></td>

                                <td style="padding:10px 15px;text-align: center;background-color: #f0f0f0;font-size: 16px;"><strong>{{displayPrice($totalPrice)}}</strong></td>
                            </tr>
                            @php

                            $shippingPrice = 0;
                            $taxPrice = 0;
                            $discountPrice = 0;
                            $rewardPrice = 0;
                            $giftPrice = 0;
                            if(!empty($order->order_shipping_value)){
                            $shippingPrice = $order->order_shipping_value;
                            }

                            if(!empty($order->order_tax_charged)){
                            $taxPrice = $order->order_tax_charged;
                            }

                            if(!empty($order->order_discount_amount)){
                            $discountPrice = $order->order_discount_amount;
                            }
                            if(!empty($order->order_reward_amount)){
                            $rewardPrice = $order->order_reward_amount;
                            }
                            if(!empty($order->order_gift_amount)){
                            $giftPrice = $order->order_gift_amount;
                            }


                            $subTotal = $order->order_net_amount - $taxPrice - $shippingPrice - $giftPrice + $discountPrice + $rewardPrice;
                            @endphp
                            <tr>
                                <td style="padding:15px 15px;font-size:20px;text-align: left;font-weight:700; vertical-align: top;" colspan="3" rowspan="4">@if($order->order_discount_amount != 0) You have SAVED {{displayPrice($order->order_discount_amount)}} on this order. @endif</td>
                                <td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="2">SubTotal</td>
                                <td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="1">{{displayPrice($subTotal)}}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="2">Tax</td>
                                <td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="1">@if($taxPrice == 0){{'FREE'}}@else{{displayPrice($taxPrice)}}@endif</td>
                            </tr>
                            @if($order->order_shipping_type != Order::ORDER_PICKUP && $order->digital_products != count($order->products))
                            <tr>
                                <td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="2">Delivery Charges</td>
                                <td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="1">@if($shippingPrice == 0){{'FREE'}}@else{{displayPrice($shippingPrice)}}@endif</td>
                            </tr>
                            @endif
                            @if($discountPrice != 0)
                            <tr>
                                <td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="2">Discount Coupon</td>
                                <td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="1">{{displayPrice($discountPrice)}}</td>
                            </tr>
                            @endif
                            @if($rewardPrice != 0)
                            <tr>
                                <td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="2">Reward Amount</td>
                                <td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="1">{{displayPrice($rewardPrice)}}</td>
                            </tr>
                            @endif
                            @if($giftPrice != 0)
                            <tr>
                                <td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="2">Gift Price</td>
                                <td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="1">{{displayPrice($giftPrice)}}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="padding:10px 15px;text-align: center;font-weight:700;font-size: 18px;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="2"><strong>GRAND TOTAL</strong> </td>
                                <td style="padding:10px 15px;text-align: center;font-weight:700;font-size: 18px;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="1"><strong>{{displayPrice($order->order_net_amount)}}</strong></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            @php
            $taxDetailArray = [];
            $taxDetailArray['label'] = [];       
            $taxDetailArray['value'] = [];          
            foreach($taxInfo as $taxDetail){
            $taxDetailArray['label'][$taxDetail['optl_key']] = $taxDetail['optl_key'];
            $taxDetailArray['value'][$taxDetail['optl_op_id']][$taxDetail['optl_key']] = $taxDetail['optl_value'];

            }

            @endphp
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <td style="padding:15px;">
                        <h2 style="font-size: 20px;text-align: center;">{{$businessDetails['BUSINESS_NAME']}}</h2>
                        <span style="padding-top: 150px;display: block;text-align: center;">Authorized Signatory </span>
                    </td>
                    <td style="text-align: center;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <th style="padding:15px;background-color: #f0f0f0;border:1px solid #ddd;border-top: 0;" colspan="{{count($taxDetailArray['label']) +2}}">Tax break-up</th>
                            </tr>
                            <tr>
                                <th style="padding:10px 15px;border:1px solid #ddd;">{{$taxDetails['TAX_NAME']}} %</th>
                                <th style="padding:10px 15px;border:1px solid #ddd;">Taxable Amount</th>
                                @foreach($taxDetailArray['label'] as $label)
                                <th style="padding:10px 15px;border:1px solid #ddd;">{{$label}}</th>
                                @php $x++; @endphp
                                @endforeach
                            </tr>
                            @php $taxSum = []; $totalTaxableAmount = []; @endphp
                            @foreach($taxDetailArray['value'] as $key => $taxAmount)
                            @php
                            $taxPrice = 0;
                            $productPrice = 0;
                            if($product = $order->products->where('op_id', $key)->first()){
                            $productPrice = $product->op_product_price * $product->op_qty;
                            $totalTaxableAmount[] = $productPrice;
                            }
                            @endphp
                            <tr>
                                <td style="padding:10px 15px;border:1px solid #ddd;">{{($product->opainfo_tgtype_rate) ?? 0}}</td>
                                <td style="padding:10px 15px;border:1px solid #ddd;">{{$productPrice}}</td>
                                @foreach($taxDetailArray['label'] as $label)
                                @php

                                $tax = 0;
                                if(isset($taxAmount[$label])){
                                $tax = $taxAmount[$label];
                                }
                                $taxSum[$label][] = $tax; @endphp
                                <td style="padding:10px 15px;border:1px solid #ddd;">{{$tax}}</td>
                                @endforeach

                            </tr>
                            @endforeach
                            <tr>
                                <td style="padding:10px 15px;border:1px solid #ddd;font-size:16px;"><strong>Grand Total</strong> </td>
                                <td style="padding:10px 15px;border:1px solid #ddd;font-size:16px;"><strong>{{array_sum($totalTaxableAmount)}}</strong> </td>
                                @foreach($taxSum as $sumVal)
                                <td style="padding:10px 15px;border:1px solid #ddd;font-size:16px;"><strong>{{array_sum($sumVal)}}</strong> </td>
                                @endforeach
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>

            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding:20px 15px;border-top:1px solid #ddd">
                        <p><strong>{{$taxDetails['TAX_ADDITIONAL_INFO_TITLE']}}:</strong>{{$taxDetails['TAX_ADDITIONAL_INFO_DESCRIPTION']}}</p>
                    </td>
                </tr>
            </table>

            <!--main End-->
        </td>
    </tr>
</table>
</td>
</tr>
</table>