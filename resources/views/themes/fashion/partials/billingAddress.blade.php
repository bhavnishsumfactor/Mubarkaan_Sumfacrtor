@if(count($savedaddress) > 0)
    <div class="text-right mb-2">
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
    </div>
@endif
@if(count($savedaddress) > 0)
    @include('themes.'.config('theme').'.checkout.checkoutAddress', ['type' => 'billing', 'digital' => $digital])
@endif
<div class="yk-address-form @if(count($savedaddress) == 0) {{ 'd-block' }} @else {{ 'd-none' }} @endif">
    @include('themes.'.config('theme').'.partials.checkoutForm', ['shipping'=>false, 'digital' => $digital])
</div>