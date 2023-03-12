<div class="product" data-ratio="{{$aspectRatio}}">
    <div class="product3D">
        <div class="product-front">
            <span class="badge-sale tag tag-primary"><span>{{__("BDG_SAVE")}} 10%</span></span>
            <a href="javascript:;">
                <picture class="product-img" data-ratio="{{$aspectRatio}}">
                    <source type="image/webp" srcset="{{ productDummyImage() }}">
                    <img data-yk="" src="{{ productDummyImage() }}" alt="">
                </picture>
            </a>
            <div class="product-actions">
                <button class="btn wishlist" type="button">
                    <i class="icn">
                        <svg class="svg">
                            <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#favorite-filled" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#favorite-filled"></use>

                        </svg>
                    </i>
                </button>
                @if(!isset($hideGallery))
                    <button class="btn view_gallery" type="button">
                        <i class="icn">
                            <svg class="svg">
                            <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#image-gallery-filled" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#image-gallery-filled"></use>
                            </svg>
                        </i>
                    </button>
                @endif
            </div>
        </div>
        @if(!isset($hideGallery))
            <div class="product-back" style="display:none;">
                <div class="flip-back">
                    <img data-yk="" src="{{url('yokart/'.config('theme'))}}/media/retina/remove.svg" alt="">
                </div>
                <div class="slider-quick">
                    <div class="slider-item">
                        <picture class="product-img" data-ratio="{{$aspectRatio}}">
                            <source type="image/webp" srcset="{{ productDummyImage() }}">
                            <img data-yk="" data-aspect-ratio="{{$aspectRatio}}" src="{{ productDummyImage() }}" alt="">
                        </picture>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="product-foot">
        <div class="product-options">
            <ul class="colors">
                <li><span style="background-color:red"></span></li>
                <li><span style="background-color:green"></span></li>
                <li><span style="background-color:purple"></span></li>
            </ul>
        </div>
        <div class="product_category">
            <a href="javascript:;">{{ __('LBL_CATEGORY')}}</a>
        </div>
        <div class="product_title"><a href="javascript:;">{{ __('LBL_PRODUCT')}}</a></div>
        <div class="product_price">
            <span class="notranslate">{{($escapeCurrency??'').currencySymbol().' XX.XX' }}</span>
        </div>
    </div>
</div>