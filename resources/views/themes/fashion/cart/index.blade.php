@extends('layouts.full')

@section('content')
<div class="body" id="body" data-yk="" >
    @php
    $pickupSettings = getConfigValueByName('PICK_UP_ENABLE');
    $shippingEnable = validateShippigType($shippingType , 'shipping');
    $pickupEnable = validateShippigType($shippingType , 'pickup');
    @endphp
    <section class="section   @if ($cartCollectionCount != 0){{'cart-page'}}@endif">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    @if ($cartCollectionCount > 0)
                    <div class="row justify-content-between">
                        <div class="col-xl-7 col-lg-7">
                            @if($pickupSettings == 1 && count($pickupAddress) > 0)
                            <div class="shiporpickup">
                                <ul>
                                    <li><input class="control-input YK-shipping" value="shipping" type="radio" id="shipping" @if($shippingEnable==false){{'disabled'}}@endif name="shippingType" @if($selectedShipping=='shipping' || $selectedShipping=="" ){{'checked'}}@endif onclick="switchShipping()">
                                        <label class="control-label @if($shippingEnable == false){{'disabled'}}@endif" for="shipping">
                                            <svg class="svg">
                                                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#shipping" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#shipping">
                                                </use>
                                            </svg> {{__("LBL_SHIP_MY_ORDER")}}
                                        </label>
                                    </li>
                                    <li><input class="control-input YK-pickup" value="pickup" id="pickup" onclick="switchShipping()" type="radio" @if($pickupEnable==false){{'disabled'}}@endif @if($selectedShipping=='pickup' ){{'checked'}}@endif name="shippingType" id="pickup">
                                        <label class="control-label @if($pickupEnable == false){{'disabled'}}@endif" for="pickup">
                                            <svg class="svg">
                                                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#pickup" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#pickup">
                                                </use>
                                            </svg> {{__("LBL_PICKUP_IN_STORE")}}
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            @endif
                            <div class="cart-blocks">
                                <div class="YK-tempProducts"></div>
                                <div class="YK-cartProducts"></div>
                                <div class="YK-savedProducts">
                                    @include('themes.'.config('theme').'.cart.savedItems')
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-5">
                            <div class="sticky-summary">
                                <div class="card">
                                    <div class="card_body">
                                        <div class="cart-total">
                                            <div class="YK-cartInfo"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="secure">
                                    <p><i class="fas fa-lock"></i> {{ __('LBL_SECURE_PAYMENTS') }}</p>
                                    <div class="payment-list justify-content-center">
                                        <svg class="payment-list__item" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" data-yk="" role="yk-img" width="38" height="24" aria-labelledby="pi-visa">
                                            <title id="pi-visa">{{__('TTL_VISA')}}</title>
                                            <path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                                            </path>
                                            <path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                                            </path>
                                            <path d="M28.3 10.1H28c-.4 1-.7 1.5-1 3h1.9c-.3-1.5-.3-2.2-.6-3zm2.9 5.9h-1.7c-.1 0-.1 0-.2-.1l-.2-.9-.1-.2h-2.4c-.1 0-.2 0-.2.2l-.3.9c0 .1-.1.1-.1.1h-2.1l.2-.5L27 8.7c0-.5.3-.7.8-.7h1.5c.1 0 .2 0 .2.2l1.4 6.5c.1.4.2.7.2 1.1.1.1.1.1.1.2zm-13.4-.3l.4-1.8c.1 0 .2.1.2.1.7.3 1.4.5 2.1.4.2 0 .5-.1.7-.2.5-.2.5-.7.1-1.1-.2-.2-.5-.3-.8-.5-.4-.2-.8-.4-1.1-.7-1.2-1-.8-2.4-.1-3.1.6-.4.9-.8 1.7-.8 1.2 0 2.5 0 3.1.2h.1c-.1.6-.2 1.1-.4 1.7-.5-.2-1-.4-1.5-.4-.3 0-.6 0-.9.1-.2 0-.3.1-.4.2-.2.2-.2.5 0 .7l.5.4c.4.2.8.4 1.1.6.5.3 1 .8 1.1 1.4.2.9-.1 1.7-.9 2.3-.5.4-.7.6-1.4.6-1.4 0-2.5.1-3.4-.2-.1.2-.1.2-.2.1zm-3.5.3c.1-.7.1-.7.2-1 .5-2.2 1-4.5 1.4-6.7.1-.2.1-.3.3-.3H18c-.2 1.2-.4 2.1-.7 3.2-.3 1.5-.6 3-1 4.5 0 .2-.1.2-.3.2M5 8.2c0-.1.2-.2.3-.2h3.4c.5 0 .9.3 1 .8l.9 4.4c0 .1 0 .1.1.2 0-.1.1-.1.1-.1l2.1-5.1c-.1-.1 0-.2.1-.2h2.1c0 .1 0 .1-.1.2l-3.1 7.3c-.1.2-.1.3-.2.4-.1.1-.3 0-.5 0H9.7c-.1 0-.2 0-.2-.2L7.9 9.5c-.2-.2-.5-.5-.9-.6-.6-.3-1.7-.5-1.9-.5L5 8.2z" fill="#142688"></path>
                                        </svg>
                                        <svg class="payment-list__item" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" data-yk="" role="yk-img" width="38" height="24" aria-labelledby="pi-master">
                                            <title id="pi-master">{{__('TTL_MASTERCARD')}}</title>
                                            <path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                                            </path>
                                            <path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                                            </path>
                                            <circle fill="#EB001B" cx="15" cy="12" r="7"></circle>
                                            <circle fill="#F79E1B" cx="23" cy="12" r="7"></circle>
                                            <path fill="#FF5F00" d="M22 12c0-2.4-1.2-4.5-3-5.7-1.8 1.3-3 3.4-3 5.7s1.2 4.5 3 5.7c1.8-1.2 3-3.3 3-5.7z">
                                            </path>
                                        </svg>
                                        <svg class="payment-list__item" xmlns="http://www.w3.org/2000/svg" data-yk="" role="yk-img" viewBox="0 0 38 24" width="38" height="24" aria-labelledby="pi-american_express">
                                            <title id="pi-american_express">{{__('TTL_AMERICAN_EXPRESS')}}</title>
                                            <g fill="none">
                                                <path fill="#000" d="M35,0 L3,0 C1.3,0 0,1.3 0,3 L0,21 C0,22.7 1.4,24 3,24 L35,24 C36.7,24 38,22.7 38,21 L38,3 C38,1.3 36.6,0 35,0 Z" opacity=".07"></path>
                                                <path fill="#006FCF" d="M35,1 C36.1,1 37,1.9 37,3 L37,21 C37,22.1 36.1,23 35,23 L3,23 C1.9,23 1,22.1 1,21 L1,3 C1,1.9 1.9,1 3,1 L35,1">
                                                </path>
                                                <path fill="#FFF" d="M8.971,10.268 L9.745,12.144 L8.203,12.144 L8.971,10.268 Z M25.046,10.346 L22.069,10.346 L22.069,11.173 L24.998,11.173 L24.998,12.412 L22.075,12.412 L22.075,13.334 L25.052,13.334 L25.052,14.073 L27.129,11.828 L25.052,9.488 L25.046,10.346 L25.046,10.346 Z M10.983,8.006 L14.978,8.006 L15.865,9.941 L16.687,8 L27.057,8 L28.135,9.19 L29.25,8 L34.013,8 L30.494,11.852 L33.977,15.68 L29.143,15.68 L28.065,14.49 L26.94,15.68 L10.03,15.68 L9.536,14.49 L8.406,14.49 L7.911,15.68 L4,15.68 L7.286,8 L10.716,8 L10.983,8.006 Z M19.646,9.084 L17.407,9.084 L15.907,12.62 L14.282,9.084 L12.06,9.084 L12.06,13.894 L10,9.084 L8.007,9.084 L5.625,14.596 L7.18,14.596 L7.674,13.406 L10.27,13.406 L10.764,14.596 L13.484,14.596 L13.484,10.661 L15.235,14.602 L16.425,14.602 L18.165,10.673 L18.165,14.603 L19.623,14.603 L19.647,9.083 L19.646,9.084 Z M28.986,11.852 L31.517,9.084 L29.695,9.084 L28.094,10.81 L26.546,9.084 L20.652,9.084 L20.652,14.602 L26.462,14.602 L28.076,12.864 L29.624,14.602 L31.499,14.602 L28.987,11.852 L28.986,11.852 Z">
                                                </path>
                                            </g>
                                        </svg>
                                        <svg class="payment-list__item" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" width="38" height="24" data-yk="" role="yk-img" aria-labelledby="pi-paypal">
                                            <title id="pi-paypal">{{__('TTL_PAYPAL')}}</title>
                                            <path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                                            </path>
                                            <path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                                            </path>
                                            <path fill="#003087" d="M23.9 8.3c.2-1 0-1.7-.6-2.3-.6-.7-1.7-1-3.1-1h-4.1c-.3 0-.5.2-.6.5L14 15.6c0 .2.1.4.3.4H17l.4-3.4 1.8-2.2 4.7-2.1z">
                                            </path>
                                            <path fill="#3086C8" d="M23.9 8.3l-.2.2c-.5 2.8-2.2 3.8-4.6 3.8H18c-.3 0-.5.2-.6.5l-.6 3.9-.2 1c0 .2.1.4.3.4H19c.3 0 .5-.2.5-.4v-.1l.4-2.4v-.1c0-.2.3-.4.5-.4h.3c2.1 0 3.7-.8 4.1-3.2.2-1 .1-1.8-.4-2.4-.1-.5-.3-.7-.5-.8z">
                                            </path>
                                            <path fill="#012169" d="M23.3 8.1c-.1-.1-.2-.1-.3-.1-.1 0-.2 0-.3-.1-.3-.1-.7-.1-1.1-.1h-3c-.1 0-.2 0-.2.1-.2.1-.3.2-.3.4l-.7 4.4v.1c0-.3.3-.5.6-.5h1.3c2.5 0 4.1-1 4.6-3.8v-.2c-.1-.1-.3-.2-.5-.2h-.1z">
                                            </path>
                                        </svg>

                                        <svg class="payment-list__item" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" data-yk="" role="yk-img" width="38" height="24" aria-labelledby="pi-diners_club">
                                            <title id="pi-diners_club">{{__('TTL_DINERS_CLUB')}}</title>
                                            <path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                                            </path>
                                            <path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                                            </path>
                                            <path d="M12 12v3.7c0 .3-.2.3-.5.2-1.9-.8-3-3.3-2.3-5.4.4-1.1 1.2-2 2.3-2.4.4-.2.5-.1.5.2V12zm2 0V8.3c0-.3 0-.3.3-.2 2.1.8 3.2 3.3 2.4 5.4-.4 1.1-1.2 2-2.3 2.4-.4.2-.4.1-.4-.2V12zm7.2-7H13c3.8 0 6.8 3.1 6.8 7s-3 7-6.8 7h8.2c3.8 0 6.8-3.1 6.8-7s-3-7-6.8-7z" fill="#3086C8"></path>
                                        </svg>

                                        <svg class="payment-list__item" xmlns="http://www.w3.org/2000/svg" data-yk="" role="yk-img" viewBox="0 0 38 24" width="38" height="24" aria-labelledby="pi-discover">
                                            <title id="pi-discover">{{__('TTL_DISCOVER')}}</title>
                                            <path d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z" fill="#000" opacity=".07"></path>
                                            <path d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32" fill="#FFF"></path>
                                            <path d="M37 16.95V21c0 1.1-.9 2-2 2H23.228c7.896-1.815 12.043-4.601 13.772-6.05z" fill="#EDA024"></path>
                                            <path fill="#494949" d="M9 11h20v2H9z"></path>
                                            <path d="M22 12c0 1.7-1.3 3-3 3s-3-1.4-3-3 1.4-3 3-3c1.7 0 3 1.3 3 3z" fill="#EDA024"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="no-data-found">
                        <div class="img"> <img data-yk="" src="{{url('yokart/'.config('theme'))}}/media/retina/empty-state-cart.svg" alt=""></div>
                        <div class="data">
                            <h2>{{ __('LBL_SHOPPING_BAG_EMPTY') }}</h2>
                            <p>{{ __('LBL_SHOPPING_BAG_EMPTY_CAPTION') }}</p>
                            <div class="action"><a class="btn btn-outline-brand btn-wide" href="{{ route('productListing') }}">{{ __('BTN_VIEW_ALL_PRODUCTS') }}</a></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @if ($cartCollectionCount == 0 && count($savedProducts) > 0)
            <div class="row justify-content-center mt-10">
                <div class="col-lg-7">
                    <div class="YK-savedProducts">
                        @include('themes.'.config('theme').'.cart.savedItems')
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    @if(!empty($buyTogetherProducts) && count($buyTogetherProducts)>0)
    <section class="section bg-white">
        <div class="container">
            <div class="section-content section-content-center">
                <h2>{{__("LBL_YOU_MAY_ALSO_LIKE")}}</h2>
            </div>
            <ul class="js-fourColumn-slider collection-slider">
                @foreach($buyTogetherProducts as $buytogether)
                <li class="item">
                    @include('themes.'.config('theme').'.partials.productCard', ['product'=>$buytogether, 'redirect' => false, 'aspectRatio' => productAspectRatio()])
                </li>
                @endforeach
            </ul>
        </div>
    </section>
    @endif
</div>

@section('scripts')
<script>
    let cartItems = "{{ $cartCollectionCount }}";
    $(document).ready(function() {
        $('.YK-checkout').click(function(e) {
            e.preventDefault();
            events.initiateCheckout();
            window.location.href = $(this).attr('href');
        });
    });
    if (cartItems > 0) {
        switchShipping();
    }
</script>
@endsection
@endsection