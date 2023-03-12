@php
$cartCount = cartCount();
@endphp
<div class="header-cart YK-MiniCartHeader">
    <a class="shopping-cart YK-shoppingCart" @if(isMobileDevice()) href="{{route('cartindex')}}" @elseif(url()->current() ==  route('cartindex')) href="javascript:;" @else href="javascript:;" onclick="ykMiniCart()" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @endif>
        <i class="icn">
            <svg class="svg">
                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#shopping-bag" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#shopping-bag"></use>
            </svg>
        </i>
        <span class="icn-txt">Cart </span>
        <span class="cart-items YK-cartItems" data-item-counts="{{$cartCount}}">{{$cartCount}}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim header-cart-dropdown" x-placement="top-end">
        <div class="mini-cart YK-miniCart"></div>
    </div>
</div>