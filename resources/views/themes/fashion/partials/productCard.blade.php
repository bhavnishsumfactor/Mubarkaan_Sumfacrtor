@php
$stock = false;
$subRecordId = 0;
$stock = productStockVerify($product, $product->prod_min_order_qty, $product->pov_code);
$subRecordId = productColorId($product);
$code = $product->pov_code ?? 0;


$images = getProductImages($product->prod_id, $subRecordId);
$currentImages = $webpCurrentImages= productDummyImage();
$bgColour = 1;
$altValue = $titleValue = "";
if(!empty($productImage = $images->first())){

   
    
$currentImages = url('yokart/product/image/'.$product->prod_id.'/'.$code.'/'.getProdSize('medium').'?t=' .strtotime($productImage->afile_updated_at));
$webpCurrentImages = url('yokart/product/image/'.$product->prod_id.'/'.$code.'/'.getProdSize('medium',true).'?t=' .strtotime($productImage->afile_updated_at));




$bgColour = $productImage->afile_enable_background;
$altValue = ((!empty($productImage->afile_attribute_alt) && strtolower($productImage->afile_attribute_alt) != 'null') ? $productImage->afile_attribute_alt : '');
$titleValue =((!empty($productImage->afile_attribute_title) && strtolower($productImage->afile_attribute_title) != 'null') ? $productImage->afile_attribute_alt : '');
}
if(empty($favorite)){
$favorite = 0;
}
$colorOption = $product->colorOptions;
$detailPageUrl = $product->urlRewrite->urlrewrite_original;
if(!empty($product->urlRewrite->urlrewrite_custom)){
$detailPageUrl = $product->urlRewrite->urlrewrite_custom;
}
@endphp


<div class="product @if($stock != true) no-stock-wrap @endif YK-productListing" data-ratio="{{$aspectRatio}}">
    @if($stock != true)
    <div class="no-stock">{{__("LBL_OUT_OF_STOCK")}}</div>
    @endif
    <div class="product3D  @if(getConfigValueByName('BACKGROUND_COLOR_ENABLED') == 0 && $bgColour != 0){{'outline'}}@endif">
        <div class="product-front @if($bgColour != 0){{'js-fillcolor'}}@endif">
            @if($product->discount != 0)
            <span class="badge-sale tag tag-primary"><span>{{__("BDG_SAVE")}}
                    {{round($product->discount)}}%</span></span>
            @endif
            <a href="{{url($detailPageUrl)}}">
                <picture class="product-img" data-ratio="{{$aspectRatio}}">
                    <source  type="image/webp" srcset="{{$webpCurrentImages}}"  title="{{$titleValue}}">
                    <img  data-yk="" data-aspect-ratio="{{$aspectRatio}}" src="{{$currentImages}}" alt="{{$altValue}}" title="{{$titleValue}}">
                </picture>
            </a>
            
        </div>
        <div class="product-back js-fillcolor" style="display:none;">
            <div class="loader-flip js-flipLoader"><img data-yk="" alt="" src="{{url('yokart/'.config('theme'))}}/media/loading.gif">
            </div>
            <div class="flip-back" data-id="{{$product->prod_id}}">
                <img data-yk="" src="{{url('yokart/'.config('theme'))}}/media/retina/remove.svg" alt="">
            </div>
            <div class="slider-quick"></div>
        </div>
    </div>
    <div class="product-foot @if((empty($colorOption) && count($colorOption) == 0) && !(!empty($favorite) && $favorite && !empty($product->pov_display_title))) no-options @endif">
    <div class="product-actions">
                <button class="btn wishlist @if(getProductfavorite($product) == true){{'active'}}@endif" type="button" onclick="updateFavourite(this, '{{$product->prod_id}}', '{{$product->pov_code}}', '{{$favorite}}')"><i class="icn" >
                        <svg class="svg">
                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#favorite-filled" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#favorite-filled"></use>

                        </svg>
                    </i>
                </button>
                @if(!empty($images) && count($images) > 0)
                <button class="btn view_gallery" id="view-gallery-{{$product->prod_id}}" data-productId="{{$product->prod_id}}" data-subrecordid="{{$subRecordId}}" data-prodname="{{$product->prod_name}}" type="button">
                    <i class="icn" >
                        <svg class="svg">
                            <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#image-gallery-filled" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#image-gallery-filled"></use>
                        </svg>
                    </i>
                </button>
                @endif
                @if(!empty($favorite) && $favorite)
                <button class="btn" onclick="addTocart({{ $product->prod_id }},'',true,0,'{{$product->pov_code}}')" type="button">
                    <i class="fa fa-cart-plus"></i>
                </button>
                @endif
            </div>

        @if((!empty($colorOption) && count($colorOption) > 0) || (!empty($favorite) && $favorite &&
        !empty($product->pov_display_title)))
        <div class="product-options">
            @if(!empty($favorite) && $favorite && !empty($product->pov_display_title))
            <div class="options text-capitalize">
                <p class="">{{Str::title(str_replace('_', ' | ', $product->pov_display_title))}}</p>
            </div>
            @elseif(!empty($colorOption) && count($colorOption) > 0)
            <h6>{{__("LBL_COLORS")}}</h6>
            <ul class="colors">
                @php
                $colorCount = $colorOption->count();
                @endphp
                @foreach($colorOption as $k=>$color)
                @php
                if ($k == 4) {
                break;
                }
                @endphp
                <li><span style="background:{{ $color->opn_color_code ? $color->opn_color_code : $color->opn_value }}"></span>
                </li>
                @endforeach
                @if($colorCount > 4)
                <li>+{{$colorCount - 4}}</li>
                @endif
            </ul>
            @endif
        </div>
        @endif

        <div class="product_category">
            <a href="{{($product->cat_id) ? route('categoryListing', $product->cat_id) : 'javascript:;'}}">{{!empty( $product->cat_name ) ? $product->cat_name : '' }}</a>
        </div>
        <div class="product_title"><a href="{{url($detailPageUrl)}}">{{$product->prod_name}}</a></div>
        <div class="product_price">
            <span class="notranslate">
                {{($escapeCurrency??'').displayPrice($product->finalprice)}}</span>
            @if($product->price != $product->finalprice)
            <del class="product_price_old notranslate">{{($escapeCurrency??'').displayPrice($product->price)}}</del>
            @endif
        </div>


    </div>
</div>