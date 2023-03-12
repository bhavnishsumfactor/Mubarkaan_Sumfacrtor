@php
$configurations = getConfigValues([
'ENABLE_301_REDIRECTION',
'FACEBOOK_PIXEL_ID',
'GOOGLE_TAG_MANAGER_HEAD_SCRIPT',
'GOOGLE_TAG_MANAGER_BODY_SCRIPT',
'SCHEMA_CODES_DEFAULT_SCRIPT',
'SYSTEM_MESSAGE_STATUS', 'SYSTEM_MESSAGE_CLOSE_TIME',
'BUSINESS_NAME',
'GOOGLE_ANALYTICS_SITE_TRACKING_CODE',
'GOOGLE_ANALYTICS_ANALYTICS_ID',
'SYSTEM_MESSAGE_POSITION',
'FRONTEND_FONT_FAMILY',
'PRIMARY_COLOR',
'PRIMARY_COLOR_INVERSE',
'BACKGROUND_COLOR_ENABLED',
'ACCEPT_COOKIE_ENABLE',
'ACCEPT_COOKIE_TEXT',
'ENABLE_ADVANCED_COOKIE_PREFERENCES',
'FUNCTIONAL_COOKIE_TEXT',
'ADVANCED_PREFERENCES_COOKIE_TEXT',
'STATISTICAL_ANALYSIS_COOKIE_TEXT',
'PERSONALIZE_COOKIE_TEXT',
'ADVERTISING_SOCIAL_MEDIA_COOKIE_TEXT'
]);
$logo = getLogo();
@endphp
@section('metaTags')
  <meta property="og:type" content="website" />
  <meta property="og:title" content="{{ $configurations['BUSINESS_NAME'] }}" />
  <meta property="og:site_name" content="{{ $configurations['BUSINESS_NAME'] }}" />
  <meta property="og:image" content="{{$logo}}" />
  <meta property="og:url" content="{{ route('blogListing') }}" />
  
  <meta name="twitter:card" content="home">
  <meta name="twitter:title" content="{{ $configurations['BUSINESS_NAME'] }}">
  <meta name="twitter:description" content="{{ $configurations['BUSINESS_NAME'] }}">
  <meta name="twitter:image:src" content="{{$logo}}"> 
@endsection
@include('layouts.headSection')

<body class="checkout">
    @if(config('app.localSite') == true)
    @include('restore-system.header', ["sectionType" =>"desktop"])
    @endif
    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1)
    {!!$configurations['GOOGLE_TAG_MANAGER_BODY_SCRIPT']!!}
    @endif
    <!-- <link href="{{ asset('vendors/css/jquery.datetimepicker.css') }}" rel="stylesheet" type="text/css"> -->
    <link href="{{ asset('vendors/css/pignose.calendar.css') }}" rel="stylesheet" type="text/css">
    <header class="header-checkout" data-header="" role="header">
        <div class="container header-checkout_inner">
            <a class="logo-checkout-main" href="{{url('/')}}">
                <img data-yk="" id="yk-header-logo" src="{{getFrontendLogo()  != '' ? getFrontendLogo() : noImage('160x40.png') }}" data-dark="{{ getFrontendDarkLogo() }}" data-lite="{{getFrontendLogo()  != '' ? getFrontendLogo() : noImage('160x40.png') }}" data-ratio="{{ $configurations['FRONTEND_LOGO_RATIO'] ?? '16:9'}}" alt="">
            </a>
            <ul class="progressbar">

                <li id="deliveryStep" class="is-active">
                    @if($pickup != 0 || $isDigitalProduct == 1) {{__('LBL_BILLING')}} @else{{__('LBL_DELIVERY')}} @endif
                </li>
               
            @if($isDigitalProduct != 1)
                <li id="shippingInfo" class="">
                @if(Cart::isDigitalProduct() == true) {{'Review'}} @else {{ __('LBL_SHIPPING') }}    @endif 
                </li>
             @endif
                <li id="paymentInfo" class="">
                    {{ __('LBL_PAYMENT') }}
                </li>
            </ul>
        </div>
    </header>
    @include('partials.themeSwitch')
    <aside data-yk="" role="yk-complementary">
        <button class="order-summary-toggle" data-trigger="order-summary">
            <div class="container">
                <span class="order-summary-toggle__inner">
                    <span class="order-summary-toggle__icon-wrapper mr-2">
                        <svg width="20" height="19" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__icon">
                            <path d="M17.178 13.088H5.453c-.454 0-.91-.364-.91-.818L3.727 1.818H0V0h4.544c.455 0 .91.364.91.818l.09 1.272h13.45c.274 0 .547.09.73.364.18.182.27.454.18.727l-1.817 9.18c-.09.455-.455.728-.91.728zM6.27 11.27h10.09l1.454-7.362H5.634l.637 7.362zm.092 7.715c1.004 0 1.818-.813 1.818-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817zm9.18 0c1.004 0 1.817-.813 1.817-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817z">
                            </path>
                        </svg>
                    </span>
                    <span class="order-summary-toggle__text">
                        <span>{{ __('LBL_ORDER_SUMMARY') }} <i class="arrow">
                                <svg class="svg">
                                    <use xlink:href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#arrow-right')}}" href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#arrow-right')}}"></use>
                                </svg>
                            </i></span>
                    </span>
                    <span class="order-summary-toggle__total-recap total-recap">
                        <span class="total-recap__final-price YK-total"></span>
                    </span>
                </span>
            </div>
        </button>
    </aside>
    @php
    $outOFStock = [];
    $qty = '0';
    foreach($products as $productId => $product){
    if(isset($cartCollection[$productId])){
    $qty = $cartCollection[$productId]['quantity'];
    }
    $outOFStock[$productId] = productStockVerify($product, $qty, $product->pov_code);
    }
    $hide = false;
    @endphp
    <div class="content checkout--section" data-content="">
        <div class="container">
            <div class="main">
                <main class="main__content">
                <input type="hidden" name="selected_address_id" value="">
                    <div class="steps yk-stepOuter">
                        <!-- begin::Step -->
                        <div class="YK-deliveryStep yk-step step active" data-yk="" role="yk-step:1" data-step="deliveryStep">

                            @include('themes.'.config('theme').'.partials.checkoutFirstStep')

                        </div>
                        <!-- end::Step -->
                        @if($isDigitalProduct != 1)
                        <!-- begin::Step -->
                        <div class="YK-shippingInfo yk-step step" data-yk="" role="yk-step:2" data-step="shippingInfo"></div>
                        <!-- end::Step -->
                        @endif
                        <!-- begin::Step -->
                        <div class="YK-paymentInfo yk-step step" data-yk="" role="yk-step:3" data-step="paymentInfo"></div>
                        <!-- end::Step -->
                    </div>
                    <div class="step-actions">
                        <div class="agree yk-agreeTerms">
                            <label class="checkbox">
                                <input type="checkbox" id="agreeTermsCondition" value="1">
                                <i class="input-helper"></i> {{__('LBL_I_AGREE_TO_THE') }}
                                <a href="{{getPageByType('terms')}}" target="_blank">{{__('LNK_TERMS_CONDITIONS') }}</a>
                            </label>
                        </div>
                        <div class="actions">
                            <a class="btn btn-outline-gray btn-wide yk-backBtn" href="#">
                                {{ __('LNK_CHECKOUT_BACK') }}</a>
                            <button name="button" id="checkoutProceed" type="button" name="YK-checkoutContinueBtn" class="btn btn-brand btn-wide gb-btn gb-btn-primary yk-continueBtn" @if(in_array(false, $outOFStock)) disabled @else onclick="continueCheckoutForm()" @endif><span class="">{{ __('BTN_CONTINUE') }}</span></button>
                        </div>
                    </div>
                </main>
            </div>
            <aside class="sidebar" data-yk="" role="yk-complementary">
                <div class="sidebar__content">
                    <div id="order-summary" class="order-summary">
                        <h3 class="d-none d-xl-block"> {{__("LBL_ORDER_SUMMARY")}}</h3>
                        <div class="order-summary__sections">
                            <div class="order-summary__section order-summary__section--product-list">
                                <div class="order-summary__section__content scroll-y yk-perfectScrollbar" style="position: relative;max-height: 400px;">
                                    <ul class="list-group list-cart list-cart-checkout">
                                        @foreach($cartCollection as $key => $cart)
                                        @php
                                        $productId = $cart->product_id;
                                        $subRecordId = productColorId($products[$key]);
                                        $images = getProductImages($productId, $subRecordId);
                                        $productUrl = getRewriteUrl('products', $productId);
                                        @endphp
                                        <li class="list-group-item @if($outOFStock[$key] == false){{'out-of-stock'}}@endif">
                                            <div class="product-profile">
                                                <div class="product-profile__thumbnail">
                                                    <a href="{{$productUrl}}">
                                                        <img data-yk="" class="img-fluid" data-ratio="{{productAspectRatio()}}" src="{{ !empty($images->first()) ? url('yokart/image/'.$images->first()->afile_id.'/'.getProdSize('small').'?t=' . strtotime($images->first()->afile_updated_at)) : noImage('../no_image.jpg') }}" alt="...">
                                                    </a>
                                                    <span class="product-qty">{{$cart->quantity}}</span>
                                                </div>
                                                <div class="product-profile__data">
                                                    <div class="title"><a class="" href="{{$productUrl}}">{{$cart->name}}</a></div>
                                                    @if($cart->attributes->styles)
                                                    <div class="options text-capitalize">
                                                        <p class="">{{implode(' | ', $cart->attributes->styles)}}</p>
                                                    </div>
                                                    @endif
                                                    @if($outOFStock[$key] == false)
                                                    <div class="options">
                                                        <p class="text-danger">{{__("LBL_OUT_OF_STOCK")}}</p>
                                                    </div>@endif
                                                </div>
                                            </div>
                                            <div class="product-price">{{displayPrice($products[$key]->finalprice * $cart->quantity)}}</div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="order-summary__section order-summary__section--total-lines">
                                <div class="cart-total">
                                    <div class="YK-cartInfo"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

        </div>
    </div>
    <div id="dataModal" class="modal fixed-right fade">

    </div>
    <link rel="stylesheet" href="{{ asset('vendors/css/jquery-ui.css') }}">
    <link href="{{ asset('vendors/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <script src="{{ asset('vendors/js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/js/ui-functions.js') }}"></script>
    <script src="{{ asset('yokart/'.config('theme').'/js/creditCardValidator.js') }}"></script>
    <script src="{{ asset('vendors/js/nprogress.min.js') }}"></script>
    <script>
        NProgress.configure({ trickleSpeed: 150, showSpinner: false });
        var headers = {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        };
        
        var twoCheckoutSellerId = "{{  getTwoCheckoutConfig()}}";
        var isDigitalProduct = "{{  $isDigitalProduct }}";
        var defaultCountry = "{{ getDefaultCountryCode() }}";
    </script>
    <script src="{{ asset('vendors/js/cart.js') }}"></script>
    <script src="{{ asset('vendors/js/php-date-formatter.min.js') }}"></script>
    <script src="{{ asset('yokart/'.config('theme').'/js/vendors/jquery.mousewheel.js') }}"></script>
  
    <script src="{{ asset('vendors/js/pignose.calendar.full.js') }}"></script>
    <script src="{{ asset('vendors/js/form-validation.js') }}"></script>
    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1)
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={!!$configurations['GOOGLE_ANALYTICS_ANALYTICS_ID']!!}"></script>
    <!-- Google Analytics site Tracking code -->
    {!!$configurations['GOOGLE_ANALYTICS_SITE_TRACKING_CODE']!!}
    @endif

    <script src="{{ asset('vendors/js/toastr.min.js') }}"></script>

    @toastr_render
    <script>
        window.addEventListener('load', (event) => {
            $('body').removeClass('loading');
        });
        $(document).ready(function() {
            new PerfectScrollbar('.yk-perfectScrollbar');

            $("#checkoutProceed").attr("disabled", true);
            if ($("#addr_id").val() != '') {
                $("#agreeTermsCondition").click(function() {
                    let agreeCheckbox = $(this).prop("checked");
                    if ($('.YK-oldAddress').hasClass('active')) {
                        if (($("#agreeTermsCondition").prop("checked") == true) && $("#addr_id").val() != '' && $(".YK-itemsOutOfStock").length == 0) {
                            $("#checkoutProceed").attr("disabled", false);
                        } else {
                            $("#checkoutProceed").attr("disabled", true);
                        }
                    } else if ($('.yk-address-form').hasClass('d-block')) {
                        if ((($("#agreeTermsCondition").prop("checked") == false) || $(".YK-itemsOutOfStock").length == 1 || $("#addr_first_name").val() == '' || $("#addr_last_name").val() == "" || $("#addr_email").val() == "" || $("#addr_title").val() == "" || $("#addr_address1").val() == "" || $("#addr_city").val() == "" || $("#addr_postal_code").val() == "" || $("#addr_phone_mask").val() == '')) {
                            $("#checkoutProceed").attr("disabled", true);
                        } else {
                            $("#checkoutProceed").attr("disabled", false);
                        }
                    }
                });
            }

            if (typeof $("input[name='addr_id']:checked").val() != '' && $(".YK-oldAddress li").length > 0) {
                $(".YK-oldAddress").addClass('active');
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('keyup', '.form-floating input, .form-floating textarea', function() {
            if ($(this).val().length > 0) {
                $(this).addClass('filled');
            } else {
                $(this).removeClass('filled');
            }
        });
        $(document).on('click', '.form-floating select', function() {
            if ($(this).val() != '') {
                $(this).addClass('filled');
            } else {
                $(this).removeClass('filled');
            }
        });

        function floatingFormFix() {
            $('.form-floating').find('input, textarea, select').each(function() {
                if ($(this).val() && $(this).val().length > 0 || $(this).val() != '') {
                    $(this).addClass('filled');
                } else {
                    $(this).removeClass('filled');
                }
            });
        }
        $(function() {
            floatingFormFix();
            $('[data-toggle="popover"]').popover({ trigger: "hover" });
        });
        calShippingTax();

        function changeUserAddress(addressId, type, digital) {           
            $('.YK-oldAddress').find('li').removeClass('selected');
            $('.YK-editAddress-' + addressId).closest('li').addClass('selected');
            if ($(".YK-paymentInfo").hasClass("active")) {
                $("#checkoutProceed").attr("disabled", false);
                $("#YK-billingAddress").find('input[name="addr_id"]').val($("#YK-billingAddress").find('input[name="addr_id"]:checked').attr("attr-val"));
            }
            if(type == "billing"){
                calculateDigitalTax(addressId, '', digital);
            }
        }
    </script>
    @include('partials.googleTranslate')
    @if(config('app.localSite') == true)
    @include('restore-system.index')
    @endif
    @include('partials.acceptCookies')
</body>

</html>
