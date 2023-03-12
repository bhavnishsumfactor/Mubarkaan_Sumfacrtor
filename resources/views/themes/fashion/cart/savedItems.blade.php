@if(count($savedProducts) > 0)
<h5 class="cart-title">{{__("LBL_SAVED_FOR_LATER")}} ({{count($savedProducts)}})</h5>
<ul class="list-group list-cart list-cart-saved-later YK-savedLater">
    @foreach($savedProducts as $product)
    @php
    $productId = $product->prod_id;
    $subRecordId = productColorId($product);
    $images = getProductImages($productId, $subRecordId);
    $productUrl = getRewriteUrl('products', $productId);
    $outOFStock = productStockVerify($product, 1, $product->pov_code);

    @endphp
    <li class="list-group-item YK-cartloop @if($outOFStock == false){{'out-of-stock'}}@endif">
        <div class="product-profile">
            <div class="product-profile__thumbnail">
                <a href="{{$productUrl}}">
                    <img data-yk="" class="img-fluid" data-ratio="{{productAspectRatio()}}" src="{{ !empty($images->first()) ? url('yokart/image/'.$images->first()->afile_id.'/' . getProdSize('small') . '?t=' . strtotime($images->first()->afile_updated_at)) : noImage('../no_image.jpg') }}" alt="...">
                </a>
            </div>
            <div class="product-profile__data">
                <div class="title"><a class="" href="{{$productUrl}}" data-toggle="tooltip" data-placement="top" title="{{ $product->prod_name }}">{{$product->prod_name}}</a>
                </div>
                @if($product->pov_display_title)
                <div class="options text-capitalize">
                    <p class="">{{str_replace('_', ' | ', $product->pov_display_title)}}</p>
                </div>
                @endif
                <button class="btn btn-outline-brand btn-sm product-profile__btn" onclick="moveToCart(this, '{{$product->usp_id}}')" type="button">{{__("BTN_MOVE_TO_BAG")}}</button>
            </div>
        </div>
        <div class="product-price">{{displayPrice($product->finalprice)}}
            @if($product->price != $product->finalprice)
            <del>{{displayPrice($product->price)}}</del>
            @endif
        </div>
        <div class="product-action">
            <ul class="list-actions">
                <li>
                    <a onclick="savedItemRemove(this,'{{$product->usp_id}}')" href="javascript:;">
                        <svg class="svg" width="24px" height="24px">
                            <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#remove" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#remove">
                            </use>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    @endforeach
</ul>
@endif