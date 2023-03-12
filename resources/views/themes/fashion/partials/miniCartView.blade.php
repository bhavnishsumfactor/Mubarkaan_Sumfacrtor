@if (count($cartCollection))
<div class="mini-cart__head">
    <h6><strong>{{ __('LBL_VIEW_BAG') }} ({{count($cartCollection)}})</strong></h6>
    @if($earnRewardPoints != 0)
    <div class="cart__points"> <svg class="svg" width="20px" height="20px">
            <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#rewards" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#rewards">
            </use>
        </svg>{{ __('LBL_EARN') . ' ' . $earnRewardPoints . ' ' . __('LBL_POINTS_WITH_THIS_ORDER') }} <span></span>
    </div>
    @endif
</div>

<div class="mini-cart__body yk-perfectScrollbarxx scroll-y" style="max-height:350px;">
    <ul class="list-group list-cart list-cart-checkout list-cart">
        @foreach($cartCollection as $key => $cart)
        @php
        $productId = $cart->product_id;
        $subRecordId = productColorId($products[$key]);
        $images = getProductImages($productId, $subRecordId);
        $productUrl = getRewriteUrl('products', $productId);
        @endphp
        <li class="list-group-item">
            <div class="product-profile">
                <div class="product-profile__thumbnail">
                    <a href="{{$productUrl}}">
                        <img data-yk="" class="img-fluid" data-ratio="{{productAspectRatio()}}" src="{{ !empty($images->first()) ? url('yokart/image/'.$images->first()->afile_id.'/' . getProdSize('small').'?t=' . strtotime($images->first()->afile_updated_at)) : noImage('../no_image.jpg') }}" alt="...">
                    </a>
                    <span class="product-qty">{{$cart->quantity}}</span>
                </div>
                <div class="product-profile__data">
                    <div class="title"><a href="{{$productUrl}}">{{ Str::substr($cart->name, 0, 50)}}</a></div>
                    @if($cart->attributes->styles)
                    <div class="options text-capitalize">
                        <p class="">{{implode(' | ', $cart->attributes->styles)}}</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="product-price">{{displayPrice($products[$key]->finalprice)}}
                <!-- @if($products[$key]->price != $products[$key]->finalprice)
                <del>{{displayPrice($products[$key]->price)}}</del>
                @endif -->
            </div>
        </li>
        @endforeach
    </ul>
    
</div>
<div class="mini-cart__foot">
<div class="cart-total">
        <ul class="list-group">
        @if($couponValue != 0)
            <li class="list-group-item hightlighted border-0 mt-0">
                <span class="label">{{ __('LBL_DISCOUNT') }}</span> <span class="ml-auto">-{{displayPrice(abs($couponValue))}}</span>
            </li>
            @endif
            <li class="list-group-item hightlighted border-0 mt-0">
                <span class="label">{{ __('LBL_TOTAL') }}</span> <span class="ml-auto">{{displayPrice($total)}}</span>
            </li>
        </ul>
    </div> 

    <div class="buttons-group pt-3">
        <button class="btn btn-outline-gray" onclick="window.location.href='{{route('productListing')}}'" type="button">{{ __('BTN_SHOP_MORE') }}</button>
        <button class="btn btn-brand" onclick="window.location.href='{{route('cartindex')}}'" type="button">{{ __('BTN_VIEW_BAG') }}</button>
    </div>
</div>
@else
<div class="mini-cart__body">
    <div class="no-data-found">
        <div class="img">
            <img data-yk="" alt="" src="{{url('yokart/'.config('theme'))}}/media/retina/empty-state-cart.svg" style="max-width:150px">
        </div>
        <div class="data">
            <h5>{{ __('LBL_SHOPPING_BAG_EMPTY') }}</h5>
            <div class="buttons-group pt-3">
                <button class="btn btn-brand" onclick="window.location.href='{{route('productListing')}}'" type="button">{{ __('BTN_SHOP_NOW') }}</button>
                <button class="btn btn-outline-gray" onclick="window.location.href='{{route('cartindex')}}'" type="button">{{ __('BTN_VIEW_BAG') }}</button>
            </div>
        </div>
    </div>
</div>
@endif
<script>
   /* new PerfectScrollbar('.yk-perfectScrollbar');*/
</script>