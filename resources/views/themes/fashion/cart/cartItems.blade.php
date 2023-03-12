<ul class="list-group list-cart list-cart-page">

    @foreach($cartCollection as $key => $cart)
    @php
    $product = $products[$key];
    $productId = $product->prod_id;
    $subRecordId = productColorId($product);
    $min = 1;
    $max = 1; 
    $outOFStock = productStockVerify($product, $cart->quantity, $product->pov_code);
    if(!empty($product)){
    $min = $product['prod_min_order_qty'];
    $max = ($product['prod_max_order_qty'] < $product['totalstock']) ? $product['prod_max_order_qty'] : $product['totalstock']; } $images=getProductImages($productId, $subRecordId); 
    $productUrl=getRewriteUrl('products', $productId); @endphp 
    <li class="list-group-item YK-cartloop @if($outOFStock == false){{'out-of-stock'}}@endif">
        <div class="product-profile">
            <div class="product-profile__thumbnail">
                <a href="{{$productUrl}}">
                <img data-yk="" data-ratio="{{productAspectRatio()}}" src="{{ !empty($images->first()) ? url('yokart/image/'.$images->first()->afile_id.'/'.getProdSize('small')) : noImage('../no_image.jpg') }}" alt="..." class="img-fluid">
                </a>
            </div>
            <div class="product-profile__data">
                <div class="title" data-toggle="tooltip" data-placement="top" title="{{ $cart->name }}"><a class="" href="{{$productUrl}}">{{$cart->name}} </a></div>
                @if($cart->attributes->styles)
                <div class="options text-capitalize">
                    <p class="">{{implode(' | ', $cart->attributes->styles)}}</p>
                    @if($outOFStock == false) <p class="text-danger">Out of Stock</p>@endif
                </div>
                @endif
                <p class="save-later">
                    <a href="javascript:;" onclick="savedForLater(this, '{{$product->prod_id}}', '{{$cart->id}}', '{{$product->pov_code}}')">
                        {{__("LNK_SAVE_FOR_LATER")}}
                    </a>
                    @if($giftEnable == 1 && $product->pc_gift_wrap_available == 1)
                    / <a class="YK-GiftItem gift-item @if(isset($cart['attributes']['gift'])){{'active'}}@endif" href="javascript:;" onclick="giftItemMessage(this, '{{$cart->id}}')" id="YK-GiftItem-{{str_replace('|', '-', $cart->id)}}">{{__("LNK_GIFT_WRAP")}}
                         <svg class="svg">
                            <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#gift-icon" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#gift-icon"></use>
                        </svg>
                </a>
                    @endif
                </p>
            </div>
        </div>
        <div class="product-quantity">
            <div class="quantity quantity-2 YK-quantity">
                <span class="decrease" onclick="decreaseQty(this, '{{$cart->id}}')">
                <i class="icn">
                        <svg class="svg">
                                    <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#minus" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#minus">
                                    </use>
                                </svg>
                            </i></span>
                <input class="qty-input no-focus YK-qty" data-page="product-view" title="{{__('PLH_AVAILABLE_QUANTITY')}}" type="text" name="quantity" max="{{$max}}" min="{{$min}}" value="{{$cart->quantity}}" readonly>
                <span class="increase" onclick="increaseQty(this, '{{$cart->id}}')"> <i class="icn">
                        <svg class="svg" >
                                    <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#add" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#add">
                                    </use>
                                </svg>
                            </i></span>
            </div>
        </div>
        <div class="product-price"> {{displayPrice($product->finalprice)}}
            @if($product->price != $product->finalprice)
            <del>{{displayPrice($product->price)}}</del>
            @endif
        </div>
        <div class="product-action">
            <ul class="list-actions">
                <li>
                    <a onclick="cartItemRemove(this,'{{$cart->id}}')" href="javascript:;">
                        <svg class="svg">
                            <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#remove" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#remove"></use>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
        </li>
        @endforeach
</ul>