
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
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">                                                             
                                            <tr>
                                                <td style="font-weight: 700;">Email</td>
                                                <td style="text-align: right;font-weight: 700;">{{$businessDetails['BUSINESS_EMAIL']}}</td>
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
                                        @php $x= 0; @endphp
                                            @foreach($order->address as $address)
                        
                                              <td style="padding:15px;@if($x == 0){{'border-right: 1px solid #ddd;'}}@endif">
                                                    <h4 style="margin:0;font-size:18px;font-weight:bold;padding-bottom: 5px;">{{$addressTypes[$address->oaddr_type]}} To</h4>
                                                    <p style="margin:0;padding-bottom: 15px;">{{$address->oaddr_name}} {{$address->oaddr_address1}} @if($address->oaddr_address2){{', '.$address->oaddr_address2}}@endif ,{{$address->oaddr_city}}, {{$address->oaddr_state}},  {{$address->oaddr_country}}</p>
                                                </td>
                                                @php $x++; @endphp

                                                @endforeach
                                           

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
                                                   <p><strong>Order:</strong>  #{{$order->order_id}} </p>
                                                   <p><strong>Payment Status:</strong> {{$paymentTypes[$order->order_status]}} </p>
                                                   <p><strong>Total Items: {{count($order->products)}} </p>
                                                </td>
                                                <td style="padding:15px;">
                                                    <p><strong>Order Date:</strong>   {{getConvertedDateTime($order->order_date_added)}}</p>
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
                                                <th style="padding:10px 15px;text-align: center;">HSN (Tax%)</th>                                                
                                                <th style="padding:10px 15px;text-align: center;">Qty</th>                                                
                                                <th style="padding:10px 15px;text-align: center;">MRP (Rs)</th>                                                
                                                <th style="padding:10px 15px;text-align: center;">Savings (Rs)</th>                                                
                                                <th style="padding:10px 15px;text-align: center;">Total Amt (Rs)</th>                                                
                                            </tr>
                                            <tr>
                                                <th colspan="7" style="padding:10px 15px;text-align: left;background-color: #f0f0f0;font-size: 18px;">ORDER ITEMS</th>                                             
                                            </tr>
                                            @php 
                                    $x = 1;
                                  $calculatedRefund = [];
                                    @endphp
                                                @foreach($order->products as $product)
                                                @php 
                                                $qty = $product->op_qty;
                                               
                                               if($product->cancelRequest && $product->cancelRequest->orrequest_status == 2){
                                                  $qty =  $product->op_qty - $product->cancelRequest->orrequest_qty;
                                                    $calculatedRefund['subtotal'][] =  $product->op_product_price * $product->cancelRequest->orrequest_qty;
                                                    $calculatedRefund['tax'][] =  $product->cancelRequest->oramount_tax;
                                                    $calculatedRefund['shipping'][] =  $product->cancelRequest->oramount_shipping;
                                                    $calculatedRefund['discount'][] =  $product->cancelRequest->oramount_discount;
                                                    $calculatedRefund['giftwrap_amount'][] =  $product->cancelRequest->oramount_giftwrap_price;
                                                    $calculatedRefund['rewardprice'][] = $product->cancelRequest->oramount_reward_price;

                                                }  
                                          
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
                                                <td style="padding:10px 15px;text-align: center;">22029090 (18.0)</td>                                             
                                                <td style="padding:10px 15px;text-align: center;">{{$qty}}</td>                                             
                                                <td style="padding:10px 15px;text-align: center;">80.00</td>                                             
                                                <td style="padding:10px 15px;text-align: center;">79.00</td>                                             
                                                <td style="padding:10px 15px;text-align: center;">{{displayPrice($product->op_product_price)}}</td>                                             
                                            </tr>
                                             @php $x++; @endphp
                                            @endforeach
                                            
                                        </table>                                        
                                    </td>
                                </tr>
                            </table>

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
                                    $total = $order->order_net_amount;
                                  if(count($calculatedRefund) > 0){
                                    $subTotal = $subTotal - array_sum($calculatedRefund['subtotal']);
                                    $taxPrice = $taxPrice - array_sum($calculatedRefund['tax']);
                                    $shippingPrice = $shippingPrice - array_sum($calculatedRefund['shipping']);
                                    $discountPrice = $discountPrice - array_sum($calculatedRefund['discount']);
                                    $rewardPrice = $rewardPrice - array_sum($calculatedRefund['rewardprice']);
                                    $giftPrice = $giftPrice - abs(array_sum($calculatedRefund['giftwrap_amount']));


                                    $refundtotal = array_sum($calculatedRefund['subtotal']) + array_sum($calculatedRefund['tax']) + array_sum($calculatedRefund['shipping']) - array_sum($calculatedRefund['discount']) - array_sum($calculatedRefund['rewardprice']);
                                
                                    if(array_sum($calculatedRefund['giftwrap_amount']) != 0){
                                        if(array_sum($calculatedRefund['giftwrap_amount']) < 0){
                                            $refundtotal = $refundtotal - abs(array_sum($calculatedRefund['giftwrap_amount']));
                                        }else{
                                            $refundtotal = $refundtotal + array_sum($calculatedRefund['giftwrap_amount']);
                                        }
                                    }
                                    $total =  $total - $refundtotal;
                                  }
                                
                                    @endphp

                            <table width="100%" border="0" cellpadding="0" cellspacing="0">                                                             
                                <tr>
                                    <td style="padding:15px;vertical-align: top;">
                                        <h2 style="font-size: 20px;text-align: center;">{{$businessDetails['BUSINESS_NAME']}}</h2>
                                        <span style="padding-top: 150px;display: block;text-align: center;">Authorized Signatory </span>
                                    </td>
                                    <td style="text-align: center;">                                       
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">                                                             
                                            <tr>
                                                <th style="padding:15px;background-color: #f0f0f0;border:1px solid #ddd;border-right:none;border-top: 0;" colspan="4">Tax break-up</th>                                                
                                            </tr>
                                            <tr>
                                                <th style="padding:10px 15px;border:1px solid #ddd;">GST%</th>                                                
                                                <th style="padding:10px 15px;border:1px solid #ddd;">Taxable Amount</th>                                                
                                                <th style="padding:10px 15px;border:1px solid #ddd;">SGST</th>                                                
                                                <th style="padding:10px 15px;border:1px solid #ddd;border-right:none;">CGST</th>                                                
                                            </tr>
                                            <tr>
                                                <td style="padding:10px 15px;border:1px solid #ddd;">0.00 </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;">1.00 </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;">0.00 </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;border-right:none;">0.00 </td>                                            
                                            </tr>
                                            <tr>
                                                <td style="padding:10px 15px;border:1px solid #ddd;">12.00  </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;">416.08 </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;">24.96 </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;border-right:none;">24.96 </td>                                            
                                            </tr>
                                            <tr>
                                                <td style="padding:10px 15px;border:1px solid #ddd;">12.00  </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;">416.08 </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;">24.96 </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;border-right:none;">24.96 </td>                                            
                                            </tr>
                                            <tr>
                                                <td style="padding:10px 15px;border:1px solid #ddd;">5.00  </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;">157.16 </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;">3.92 </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;border-right:none;">3.92 </td>                                            
                                            </tr>
                                            <tr>
                                                <td style="padding:10px 15px;font-size: 12px;border:1px solid #ddd;">Delivery Charges* </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;">157.16 </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;">3.92 </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;border-right:none;">3.92 </td>                                            
                                            </tr>
                                            <tr>
                                                <td style="padding:10px 15px;border:1px solid #ddd;font-size:16px;"><strong>Grand Total</strong> </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;font-size:16px;"><strong>157.16 </strong></td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;font-size:16px;"><strong>3.92</strong> </td>                                            
                                                <td style="padding:10px 15px;border:1px solid #ddd;border-right:none;font-size:16px;"><strong>3.92</strong> </td>                                            
                                            </tr>
                                        
                                        </table>                                        
                                    </td>
                                </tr>
                            </table> 

                            <table width="100%" border="0" cellpadding="0" cellspacing="0">                                                             
                                <tr>
                                    <td style="padding:20px 15px;border-top:1px solid #ddd">                                       
                                        <p><strong>Return Policy:</strong> If the item is defective or not as described, you may return it during delivery directly or you may request for return within 10 days of delivery
                                            for items that are defective or are different from what you ordered. Items must be complete (including freebies), free from damages and for items returned
                                            for being different from what you ordered, they must be unopened as well.
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
