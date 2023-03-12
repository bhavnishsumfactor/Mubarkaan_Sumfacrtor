@php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

$configurations = \Cache::get('configuration')->toArray();

@endphp
@include('layouts.headSection', ['dashboard' => 1])

@include('partials.googleTranslate')

<body class="@if(config('app.localSite') == true){{'demo-header--on'}}@endif">
    @if(config('app.localSite') == true)
    @include('restore-system.header', ["sectionType" =>"desktop"])
    @endif
    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1)
    {!!$configurations['GOOGLE_TAG_MANAGER_BODY_SCRIPT']!!}

    @endif
    @php
    $success = true;
    if($order->payment_status == Order::PAYMENT_PENDING && $order->order_payment_method != "rewards" && $order->order_payment_method != "cod"){
    $success = false;
    }
    @endphp
    <div class="body" id="body" data-yk="" >
        <section class="py-2 order-completed">

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-9">
                        <div class="thanks-screen text-center">

                            <!-- Icon -->

                            <div class="success-animation">
                                @if($success)
                                <div class="svg-box">
                                    <svg class="circular green-stroke">
                                        <circle class="path" cx="75" cy="75" r="50" fill="none" stroke-width="5" stroke-miterlimit="10" />
                                    </svg>
                                    <svg class="checkmark green-stroke">
                                        <g transform="matrix(0.79961,8.65821e-32,8.39584e-32,0.79961,-489.57,-205.679)">
                                            <path class="checkmark__check" fill="none" d="M616.306,283.025L634.087,300.805L673.361,261.53" />
                                        </g>
                                    </svg>
                                </div>
                                @else
                                <div class="svg-box">
                                    <svg class="circular red-stroke">
                                        <circle class="path" cx="75" cy="75" r="50" fill="none" stroke-width="5" stroke-miterlimit="10" />
                                    </svg>
                                    <svg class="cross red-stroke">
                                        <g transform="matrix(0.79961,8.65821e-32,8.39584e-32,0.79961,-502.652,-204.518)">
                                            <path class="first-line" d="M634.087,300.805L673.361,261.53" fill="none" />
                                        </g>
                                        <g transform="matrix(-1.28587e-16,-0.79961,0.79961,-1.28587e-16,-204.752,543.031)">
                                            <path class="second-line" d="M634.087,300.805L673.361,261.53" />
                                        </g>
                                    </svg>
                                </div>
                                @endif
                            </div>

                            <!-- Heading -->
                            <h2>
                                @if($success){{__("LBL_THANK_YOU")}}@else {{__('LBL_PAYMENT_ERROR_MESSAGE')}}@endif</h2>
                            <h3>
                                {{__("LBL_YOUR_ORDER")}} <a href="@if(Auth::check()) {{ route('userOrders', $order->order_id)}} @else {{'javascript:;'}} @endif"><strong>#{{$order->order_id}}</strong></a> @if($success){{__("LBL_HAS_BEEN_PLACED")}}@else {{__('LBL_ORDER_HAS_BEEN_GENERATED')}}@endif
                            </h3>
                            @if(empty($success))
                            <h3> {{__('LBL_ORDER_PAYMENT_DETAIL_UPDATE')}}</h3>
                            @endif
                            <!-- Text -->
                            <p>
                                @if($success)
                                {{__("LBL_ORDER_CONFIRMATION_SENT")}} <strong>{{($billAddress->oaddr_email ?? $order->user_email)}}</strong> {{__("LBL_ORDER_CONFIRMATION_MESSAGE")}}
                                @else
                                {{__('LBL_ORDER_EMAIL_SENT')}} {{($billAddress->oaddr_email ?? $order->user_email)}} {{__('LBL_ORDER_EMAIL_SEND_DECRIPTION')}}
                                @endif

                            </p>
                            <p class="placed">
                                <span>
                                    <svg class="svg" width="16px" height="16px">
                                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#TimePlaced" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#TimePlaced">
                                        </use>
                                    </svg> <strong>{{__("LBL_TIME_PLACED")}}:</strong> {{getConvertedDateTime($order->order_date_added)}}
                                </span>

                                <span>
                                    <svg class="svg" width="16px" height="16px">
                                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#print" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#print">
                                        </use>
                                    </svg> <a href="javascript:;" onclick="window.print();">{{__("LNK_PRINT")}}</a>
                                </span>
                            </p>
                        </div>

                        <ul class="completed-detail">
                            @foreach($order->address as $address)
                            @php
                            $addressIcon = '';
                            if($address->oaddr_type == '1'){
                            $addressIcon = 'billing-detail';
                            }elseif($address->oaddr_type == '2'){
                            $addressIcon = 'shipping';
                            }elseif($address->oaddr_type == '3'){
                            $addressIcon = 'pickup';
                            }

                            $phoneDialCode = ($address->country) ? $address->country->country_phonecode : '';
                            @endphp
                            <li>
                                <h4> <svg class="svg" width="22px" height="22px">
                                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#{{$addressIcon}}" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#{{$addressIcon}}">
                                        </use>
                                    </svg> {{$addressTypes[$address->oaddr_type]}} </h4>
                                <p> <strong>{{$address->oaddr_name}}</strong> <br> {{$address->oaddr_address1}} @if($address->oaddr_address2){{', '.$address->oaddr_address2}}@endif <br>{{$address->oaddr_city}}, {{$address->oaddr_state}} <br> {{$address->oaddr_country}}
                                    <br>{{"+" . $phoneDialCode . " " . $address->oaddr_phone}}
                                </p>

                                @if($address->oaddr_type == 3)
                                @php $shippingData = json_decode($order->order_shipping_methods, true);
                                if(isset($shippingData['pick_ups'])){
                                echo '<p><strong>'.$shippingData['pick_ups']['pickup_date'].'</strong></p>
                                <p><strong>'.$shippingData['pick_ups']['pickup_time'].'</strong></p>';
                                }
                                @endphp
                                @endif
                                </p>
                            </li>
                            @endforeach

                            <li>
                                <h4> <svg class="svg" width="22px" height="22px">
                                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#payment-detail" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#payment-detail">
                                        </use>
                                    </svg> {{__("LBL_PAYMENT_DETAILS")}} </h4>

                                <p> <strong>{{__("LBL_PAYMENT_METHOD")}} : </strong> {{$order->order_payment_method}}</p>
                                <p> <strong>{{__("LBL_PAYMENT_STATUS")}} : </strong>{{$paymentTypes[$order->payment_status]}} </p>


                            </li>
                        </ul>

                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="completed-cart">
                                    <div class="row justify-content-between">
                                        <div class="col-md-7">
                                            <h5>{{__("LBL_ORDER_LIST")}}</h5>
                                            <div class="completed-cart-list scroll-y">
                                                <ul class="list-group list-cart list-cart-checkout">
                                                    @php

                                                    $calculatedRefund = [];
                                                    @endphp
                                                    @foreach($order->products as $product)
                                                    @php
                                                    $qty = $product->op_qty;

                                                    if($product->cancelRequest && $product->cancelRequest->orrequest_status == 2){
                                                    $qty = $product->op_qty - $product->cancelRequest->orrequest_qty;
                                                    $calculatedRefund['subtotal'][] = $product->op_product_price * $product->cancelRequest->orrequest_qty;
                                                    $calculatedRefund['tax'][] = $product->cancelRequest->oramount_tax;
                                                    $calculatedRefund['shipping'][] = $product->cancelRequest->oramount_shipping;
                                                    $calculatedRefund['discount'][] = $product->cancelRequest->oramount_discount;
                                                    $calculatedRefund['giftwrap_amount'][] = $product->cancelRequest->oramount_giftwrap_price;
                                                    $calculatedRefund['rewardprice'][] = $product->cancelRequest->oramount_reward_price;

                                                    }
                                                    $subRecordId = 0;
                                                    if(!empty($product->op_pov_code)){
                                                    $subRecordId = getImageByVariantCode($product->op_product_id, $product->op_pov_code);
                                                    }
                                                    $images = getProductImages($product->op_product_id, $subRecordId);
                                                    $productUrl = getRewriteUrl('products', $product->op_product_id);

                                                    @endphp
                                                    <li class="list-group-item">
                                                        <div class="product-profile">
                                                            <div class="product-profile__thumbnail">
                                                                <a href="{{$productUrl}}">
                                                                    <img data-yk="" class="img-fluid" data-ratio="{{productAspectRatio()}}" src="{{ !empty($images->first()) ? url('yokart/image/'.$images->first()->afile_id.'/'.getProdSize('medium').'?t=' . strtotime($images->first()->afile_updated_at)) : noImage('../no_image.jpg')}}" alt="...">
                                                                </a>
                                                                <span class="product-qty">{{$qty}}</span>
                                                            </div>
                                                            <div class="product-profile__data">
                                                                <div class="title"><a class="" href="{{$productUrl}}">{{$product->op_product_name}}</a></div>
                                                                @php
                                                                $variants = [];
                                                                if(!empty($product->op_product_variants)){
                                                                $variants = json_decode($product->op_product_variants, true);
                                                                }
                                                                @endphp

                                                                @if(!empty($variants['styles']))
                                                                @php $variantNames = []; @endphp
                                                                @foreach($variants['styles'] as $key => $variant)
                                                                @php $variantNames[] = $variant; @endphp
                                                                @endforeach
                                                                @php $variantNames = implode(" | ", $variantNames); @endphp
                                                                <p class="">{{Str::title($variantNames)}}</p>
                                                                @endif

                                                            </div>
                                                        </div>

                                                        <div class="product-price">{{displayPrice($product->op_product_price)}}</div>

                                                    </li>
                                                    @endforeach

                                                </ul>
                                            </div>
                                        </div>
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
                                        if(array_sum($calculatedRefund['giftwrap_amount']) < 0){ $refundtotal=$refundtotal - abs(array_sum($calculatedRefund['giftwrap_amount'])); }else{ $refundtotal=$refundtotal + array_sum($calculatedRefund['giftwrap_amount']); } } $total=$total - $refundtotal; } @endphp <div class="col-md-4">
                                            <div class="sticky-item">
                                                <h5>{{__("LBL_ORDER_SUMMARY")}}</h5>
                                                <div class="cart-total mt-5">
                                                    <ul class="list-group list-group-flush-x list-group-flush-y">
                                                        <li class="list-group-item border-0">
                                                            <span class="label">{{__("LBL_SUBTOTAL")}}</span> <span class="ml-auto">{{displayPrice($subTotal)}}</span>
                                                        </li>
                                                        <li class="list-group-item ">
                                                            <span class="label">{{__("LBL_TAX")}}</span> <span class="ml-auto">@if($taxPrice == 0){{'Free'}}@else{{displayPrice($taxPrice)}}@endif</span>
                                                        </li>
                                                        @if($order->order_shipping_type != Order::ORDER_PICKUP && count($order->products) != $order->digital_products )

                                                        <li class="list-group-item ">
                                                            <span class="label">{{__("LBL_SHIPPING")}}</span> <span class="ml-auto">@if($shippingPrice == 0){{'Free'}}@else{{displayPrice($shippingPrice)}}@endif</span>
                                                        </li>
                                                        @endif
                                                        @if($discountPrice != 0)
                                                        <li class="list-group-item ">
                                                            <span class="label">{{__("LBL_DISCOUNT")}}</span> <span class="ml-auto">-{{displayPrice($discountPrice)}}</span>
                                                        </li>
                                                        @endif
                                                        @if($rewardPrice != 0)
                                                        <li class="list-group-item ">
                                                            <span class="label">{{__("LBL_REWARD_POINTS_AMOUNT")}}</span> <span class="ml-auto">-{{displayPrice($rewardPrice)}}</span>
                                                        </li>
                                                        @endif
                                                        @if($giftPrice != 0)
                                                        <li class="list-group-item ">
                                                            <span class="label">{{__("LBL_GIFT_WRAP_AMOUNT")}}</span> <span class="ml-auto">{{displayPrice($giftPrice)}}</span>
                                                        </li>
                                                        @endif
                                                        <li class="list-group-item hightlighted">
                                                            <span class="label">{{__("LBL_TOTAL")}}</span> <span class="ml-auto">{{displayPrice($total)}}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    <hr class="">
    <section class="section">
        <div class="container">
            <div class="any-question">
                <div class="row justify-content-center">
                    <div class="col-md-7">
                        <div class="row align-items-center py-4">
                            <div class="col-md">
                                <div class="">
                                    <p><strong>{{__("LBL_ANY_QUESTIONS")}}</strong></p>
                                    <p>{{__("LBL_EMAIL")}}: {{$configurations["BUSINESS_EMAIL"]}}</p>
                                    <p>{{__("LBL_PHONE")}}: {{$phone}} </p>

                                </div>
                            </div>
                            <div class="col-md-auto">
                                @if(!empty($success))
                                <a class="btn btn-brand btn-wide mt-3 mt-md-0" href="{{url('/products')}}">
                                    {{__("LBL_CONTINUE_SHOPPING")}}</a>
                                @else
                                <a class="btn btn-brand btn-wide mt-3 mt-md-0" href="javascript:;" onclick="updatePaymentMethod({{ $order->order_id }})">
                                    {{__('LBL_ORDER_PAYMENT_UPDATE')}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
    @section('scripts')
    <script>
        let currencyCode = "{{ Session::get('currencyCode') }}";
        let currencyValue = "{{ Session::get('currencyValue') }}";
        if (currencyCode != "" && currencyValue != "") {
            events.purchase({
                currency: currencyCode,
                value: currencyValue
            });
        }
        window.onpopstate = function() {};
        history.pushState({}, '');
    </script>
    @endsection
</body>

</html>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    var twoCheckoutSellerId = '{{ getTwoCheckoutConfig() }}';
</script>
<script src="{{ asset('vendors/js/cart.js') }}"></script>