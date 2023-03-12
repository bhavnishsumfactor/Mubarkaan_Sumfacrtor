@guest
    <div class="yk-address-form d-block">
        @include('themes.'.config('theme').'.checkout.guestCheckout')
    </div>
@else
<div class="step__section">
    <div class="step__section__head">
        <h3 class="step__section__head__title">@if($pickup != 0 || $isDigitalProduct == 1){{__('LBL_BILLING')}}@else{{__('LBL_DELIVERY')}}@endif</h3>
        @if(count($savedaddress) > 0)
        <a class="link-text yk-addAddressPopup" href="javascript:" onclick="addAddressPopup()">
            <i class="icn"> <svg class="svg">
                    <use xlink:href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#add')}}" href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#add')}}">
                    </use>
                </svg> </i>{{ __('LNK_ADD_NEW_ADDRESS') }}
        </a>
        <a class="link-text yk-closeAddressPopup d-none" href="javascript:">
            <i class="icn"> <svg class="svg">
                    <use xlink:href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#minus')}}" href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#minus')}}">
                    </use>
                </svg> </i>{{ __('LNK_DISCARD') }}
        </a>
        @endif
    </div>
    <form class="form form-floating" id="YK-shippingAddress">
        @if(count($savedaddress) > 0)
        @include('themes.'.config('theme').'.checkout.checkoutAddress', ['type' => 'shipping', 'digital' => false])
        @php $hide = true; @endphp
        @endif
        <div class="yk-address-form @if(count($savedaddress) == 0) {{ 'd-block' }} @else {{ 'd-none' }} @endif">
            @include('themes.'.config('theme').'.partials.checkoutForm', ['shipping'=>true, 'digital' => false])
        </div>

        @if(in_array(false, $outOFStock))
        <div class="out-of-stock mt-4 YK-itemsOutOfStock">{{ __('LBL_SOME_PRODUCTS_OUT_OF_STOCK') }}</div>
        @endif
    </form>
</div>
@endguest