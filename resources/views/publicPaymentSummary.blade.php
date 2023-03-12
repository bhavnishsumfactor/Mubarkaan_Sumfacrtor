@php
$configurations = getConfigValues([
'FACEBOOK_PIXEL_ID',
'GOOGLE_TAG_MANAGER_HEAD_SCRIPT',
'GOOGLE_TAG_MANAGER_BODY_SCRIPT',
'SCHEMA_CODES_DEFAULT_SCRIPT',
'BUSINESS_NAME',
'FRONTEND_FONT_FAMILY',
'PRIMARY_COLOR',
'PRIMARY_COLOR_INVERSE',
'GOOGLE_ANALYTICS_SITE_TRACKING_CODE',
'GOOGLE_ANALYTICS_ANALYTICS_ID',
'FRONTEND_LOGO_RATIO'
]);
$logo = getFrontendLogo();
$favicon = getFileUrl('favicon', 0, 0, 'thumb');
if (empty($favicon)) {
$favicon = noImage("160x40.png");
}
$acceptCookie = config('acceptcookie');
$language = request()->session()->get('language');
@endphp
<!DOCTYPE html>
<html lang="{{$language['code']}}" data-theme="light" dir="{{$language['direction']}}"
    data-version="{{config('app.version')}}" prefix="og: http://ogp.me/ns#">

<head>
    <meta charset="utf-8" />
    @php
    $record_id = ($record_id ?? null);
    $metatags = getMetaTags($record_id);
    @endphp
    <title>{{ $configurations['BUSINESS_NAME'] ?? '' }} - {{$metatags->meta_title ?? ''}}</title>
    <meta name="description" content="{{$metatags->meta_description ?? ''}}" />
    <meta name="keywords" content="{{$metatags->meta_keywords ?? ''}}" />
    {!!$metatags->meta_other_meta_tags ?? ''!!}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(Request::isSecure())
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif
    <link rel="shortcut icon" href="{{ $favicon }}" />

    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1)
    {!!$configurations['GOOGLE_TAG_MANAGER_HEAD_SCRIPT']!!}
    @endif
    @if(!empty($configurations['SCHEMA_CODES_DEFAULT_SCRIPT']))
    <!-- Default Schema Code -->
    {!!$configurations['SCHEMA_CODES_DEFAULT_SCRIPT']!!}
    <!-- End Default Schema Code -->
    @endif
    @yield('schemaCodes')
    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1 && !empty($configurations['FACEBOOK_PIXEL_ID']))
    <!-- Facebook Pixel Code -->
    <script>
        let fbPixel = true;
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{$configurations["FACEBOOK_PIXEL_ID"]}}');
        fbq('track', 'PageView');
    </script>
    <noscript><img data-yk="" height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{$configurations['FACEBOOK_PIXEL_ID']}}&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code  -->
    @endif
    <script src="{{ asset('vendors/js/events.js') }}"></script>

    @if($configurations['FRONTEND_FONT_FAMILY'] == 'Mulish')
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    @elseif($configurations['FRONTEND_FONT_FAMILY'] == 'Roboto')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @elseif($configurations['FRONTEND_FONT_FAMILY'] == 'Poppins')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    @elseif(cleanSpaces(strtolower($configurations['FRONTEND_FONT_FAMILY'])) == 'opensans')
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap"
        rel="stylesheet">
    @endif
    <style>
    body{font-family: "{{$configurations['FRONTEND_FONT_FAMILY']}}", sans-serif !important;}
    :root { 
        --brand-color: {{ !empty($configurations['PRIMARY_COLOR']) ? $configurations['PRIMARY_COLOR'] : '#ff0055'}} !important;
        --brand-color-inverse: {{ !empty($configurations['PRIMARY_COLOR_INVERSE']) ? $configurations['PRIMARY_COLOR_INVERSE'] : '#FFFFFF'}} !important;
    }
  
    </style>

    <link href="{{ asset('yokart/'.config('theme').'/css/main-' . $language['direction'] . '.css') }}" rel="stylesheet"
        type="text/css" rel="preload">
    <script src="{{ asset('admin/vendors/jquery.min.js') }}"></script>
    <script src="{{ asset('vendors/js/jquery.cookie.min.js') }}"></script>
    <link href="{{ asset('vendors/css/jquery.datetimepicker.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('vendors/css/toastr.min.css') }}" rel="stylesheet">
    <script src="{{ asset('vendors/js/toastr.min.js') }}"></script>

    @toastr_render

    <script>
        let currentUrl = "{{url()->current()}}";
        let baseUrl = "{{url('/')}}";
        @php
        preg_match('#[^\.]+[\.]{1}[^\.]+$#', $_SERVER['SERVER_NAME'], $matches);
        @endphp
        let domain = "{{ $matches[0] ?? 'localhost' }}";
        let languages = '{!!json_encode(getLanguages())!!}';
        let pageDefaultLanguage = "{{$language['code']}}";
        var twoCheckoutSellerId = "{{  getTwoCheckoutConfig()}}";
       
    </script>
  
</head>
<!-- end::Head -->
<!-- begin::Body -->

<body class="payment">
    <header class="payment-header" data-header="" role="header">
        <div class="container payment-header_inner">
            <a class="logo" href="{{url('/')}}"><img
                    src="{{ $logo ?? asset('yokart/'.config('theme').'/media/logos/logo.png') }}"
                    data-ratio="{{$configurations['FRONTEND_LOGO_RATIO'] ?? '16:9'}}" alt=""></a>
            <div class="payment-amount">
                <ul>
                    <li><span class="lable">{{__("LBL_ORDER")}}:</span>#{{$order->order_id}}</li>
                    <li><span class="lable">{{__("LBL_ORDER_DATE")}} :</span>
                        {{getConvertedDateTime($order->order_date_added)}}</li>
                </ul>
            </div>
        </div>
    </header>
    <div class="content" data-content="">
        <div class="container">
            <div class="main">
                <main class="main__content">
                    <h6 class="d-none d-xl-block"> {{__("LBL_ORDER_SUMMARY")}}</h6>
                    <ul class="list-group list-cart list-cart-checkout">
                        @php
                        $calculatedRefund = [];
                        @endphp
                        @foreach($order->products as $product)
                        @php
                        $qty = $product->op_qty;
                        if($product->cancelRequest && $product->cancelRequest->orrequest_status == 2){
                        $qty = $product->op_qty - $product->cancelRequest->orrequest_qty;
                        $calculatedRefund['subtotal'][] = $product->op_product_price *
                        $product->cancelRequest->orrequest_qty;
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
                        @if($qty > 0)
                        <li class="list-group-item">
                            <div class="product-profile">
                                <div class="product-profile__thumbnail">
                                    <a href="{{$productUrl}}">
                                        <img class="img-fluid" data-ratio="3:4"
                                            src="{{ !empty($images->first()) ? url('yokart/image/'.$images->first()->afile_id.'/120-158?t=' . strtotime($images->first()->afile_updated_at)) : noImage('../no_image.jpg')}}"
                                            alt="...">
                                    </a>
                                    <span class="product-qty">{{$qty}}</span>
                                </div>
                                <div class="product-profile__data">
                                    <div class="title"><a class=""
                                            href="{{$productUrl}}">{{$product->op_product_name}}</a></div>
                                    <div class="options text-capitalize">
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
                            </div>
                            <div class="product-price">{{$order->currency_symbol.''.$product->op_product_price}}</div>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                    <div class="cart-total">
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

                        $subTotal = $order->order_net_amount - $taxPrice - $shippingPrice - $giftPrice + $discountPrice
                        + $rewardPrice;
                        $total = $order->order_net_amount;
                        if(count($calculatedRefund) > 0){
                        $subTotal = $subTotal - array_sum($calculatedRefund['subtotal']);
                        $taxPrice = $taxPrice - array_sum($calculatedRefund['tax']);
                        $shippingPrice = $shippingPrice - array_sum($calculatedRefund['shipping']);
                        $discountPrice = $discountPrice - array_sum($calculatedRefund['discount']);
                        $rewardPrice = $rewardPrice - array_sum($calculatedRefund['rewardprice']);
                        $giftPrice = $giftPrice - abs(array_sum($calculatedRefund['giftwrap_amount']));
                        $refundtotal = array_sum($calculatedRefund['subtotal']) + array_sum($calculatedRefund['tax']) +
                        array_sum($calculatedRefund['shipping']) - array_sum($calculatedRefund['discount']) -
                        array_sum($calculatedRefund['rewardprice']);

                        if(array_sum($calculatedRefund['giftwrap_amount']) != 0){
                        if(array_sum($calculatedRefund['giftwrap_amount']) < 0){ $refundtotal=$refundtotal -
                            abs(array_sum($calculatedRefund['giftwrap_amount'])); }else{ $refundtotal=$refundtotal +
                            array_sum($calculatedRefund['giftwrap_amount']); } } $total=$total - $refundtotal; } @endphp
                            <div class="">
                            <ul class="list-group list-group-flush list-group-flush-x">
                                <li class="list-group-item border-0">
                                    <span class="label">{{__("LBL_SUBTOTAL")}}</span> <span
                                        class="ml-auto">{{displayPrice($subTotal)}}</span>
                                </li>
                                <li class="list-group-item ">
                                    <span class="label">{{__("LBL_TAX")}}</span> <span class="ml-auto">@if($taxPrice ==
                                        0){{'Free'}}@else{{displayPrice($taxPrice)}}@endif</span>
                                </li>
                                <li class="list-group-item ">
                                    <span class="label">{{__("LBL_SHIPPING")}}</span> <span
                                        class="ml-auto">@if($shippingPrice ==
                                        0){{'Free'}}@else{{displayPrice($shippingPrice)}}@endif</span>
                                </li>
                                @if($discountPrice != 0)
                                <li class="list-group-item ">
                                    <span class="label">{{__("LBL_DISCOUNT")}}</span> <span
                                        class="ml-auto">-{{displayPrice($discountPrice)}}</span>
                                </li>
                                @endif
                                @if($rewardPrice != 0)
                                <li class="list-group-item ">
                                    <span class="label">{{__("LBL_REWARD_POINTS_AMOUNT")}}</span> <span
                                        class="ml-auto">-{{displayPrice($rewardPrice)}}</span>
                                </li>
                                @endif
                                @if($giftPrice != 0)
                                <li class="list-group-item ">
                                    <span class="label">{{__("LBL_GIFT_WRAP_AMOUNT")}}</span> <span
                                        class="ml-auto">{{displayPrice($giftPrice)}}</span>
                                </li>
                                @endif
                                <li class="list-group-item hightlighted border-0">
                                    <span class="label">{{__("LBL_TOTAL")}}</span> <span
                                        class="ml-auto">{{displayPrice($total)}}</span>
                                </li>
                            </ul>
                    </div>
            </div>
            </main>
        </div>
        @php $billingAddress = $order->address->where('oaddr_type', 1)->first();
        $phoneDialCode = ($billingAddress->country) ? $billingAddress->country->country_phonecode : '';
        @endphp
        <aside class="sidebar" role="complementary">
            <div class="sidebar__content">
                <div class="customer-detail">
                    <h6>{{__("LBL_CUSTOMER_DETAIL")}}</h6>
                    @if($billingAddress)
                    <p>
                        <strong> {{$billingAddress->oaddr_name}}</strong> <br>
                        {{$billingAddress->oaddr_email}}<br>
                        {{$billingAddress->oaddr_address1}}<br>
                        @if($billingAddress->oaddr_address2)
                        {{$billingAddress->oaddr_address2}}<br>
                        @endif
                        {{$billingAddress->oaddr_city}}, {{$billingAddress->oaddr_state}}<br>
                        {{$billingAddress->oaddr_country}} <br>
                        {{$billingAddress->oaddr_postal_code}} <br>
                        {{"+" . $phoneDialCode . " " . $billingAddress->oaddr_phone}}
                    </p>
                    @endif
                </div>
                @if($isCreditCard)
                <hr>
                <h6>Payment</h6>
                <form class="form form-floating" id="YK-paymentForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-floating__group">
                                <input type="text" class="form-control form-floating__field" placeholder=""
                                    name="number" id="number">
                                <label class="form-floating__label">{{__("LBL_CARD_NUMBER")}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-floating__group">
                                <input type="text" class="form-control form-floating__field" placeholder="" name="name"
                                    id="name">
                                <label class="form-floating__label">{{__("LBL_CARDHOLDERS_NAME")}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group form-floating__group">
                                <input type="text" name="exp_month" class="form-control form-floating__field"
                                    placeholder="" id="">
                                <label class="form-floating__label">{{__("LBL_MONTH")}}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-floating__group">
                                <input type="text" name="exp_year" class="form-control form-floating__field"
                                    placeholder="" id="">
                                <label class="form-floating__label">{{__("LBL_YEAR")}}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-floating__group">
                                <input type="text" name="cvv" class="form-control form-floating__field" placeholder=""
                                    id="">
                                <label class="form-floating__label">{{__("LBL_CVV")}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-brand btn-block"
                            onclick="makeOrderPayment('{{$order->order_id}}', '{{$paymentGateway}}', '{{$total}}')"
                            type="button">{{__("BTN_PAY")}} {{$order->currency_symbol . '' . displayPrice($total,
                            false)}}</button>
                    </div>
                    <div class="text-center">
                        <p><i class="fas fa-lock"></i> {{__("LBL_SECURE_PAYMENTS")}}</p>
                    </div>
                </form>
                @endif
            </div>
        </aside>
    </div>
    </div>
    <link rel="stylesheet" href="{{ asset('vendors/css/jquery-ui.css') }}">
    <link href="{{ asset('vendors/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <script src="{{ asset('vendors/js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/js/cart.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        floatingFormFix = function() {
            $('.form-floating').find('input, textarea, select').each(function() {
                if ($(this).val() && $(this).val().length > 0 || $(this).val() != '') {
                    $(this).addClass('filled')
                } else {
                    $(this).removeClass('filled')
                }
            });
        }
        $(document).on('keyup', '.form-floating input, .form-floating textarea', function() {
            if ($(this).val().length > 0) {
                $(this).addClass('filled')
            } else {
                $(this).removeClass('filled')
            }
        }); 
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({
                    pageLanguage: 'en',
                    includedLanguages: Object.keys(languages).join(','),
                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
                }, 'google_translate_element');
            }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>
    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1 &&
    !empty($configurations['GOOGLE_ANALYTICS_ANALYTICS_ID']))
    <script async
        src="https://www.googletagmanager.com/gtag/js?id={!!$configurations['GOOGLE_ANALYTICS_ANALYTICS_ID']!!}">
    </script>
    {!!$configurations['GOOGLE_ANALYTICS_SITE_TRACKING_CODE']!!}
    @endif
    </script>
</body>