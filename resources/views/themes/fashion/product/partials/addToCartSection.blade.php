@php
$varient =[];
$code = 0;
if($product->pov_code){
$varient = explode('|', $product->pov_code);
unset($varient[0]);
$varient = array_filter($varient);

$code = $product->pov_code;
}
$qty = ($product->prod_min_order_qty > 0) ? $product->prod_min_order_qty: 1;
$maxQty = ($product->prod_max_order_qty < $product->totalstock) ?
    $product->prod_max_order_qty: $product->totalstock;

    $stock = productStockVerify($product, $qty, $code);
    $x=0;
    $sharethisNetworks = shareThis();
    $config = getConfigValues([
    'PRODUCT_DETAIL_DISPLAY_CANCELLATION',
    'PRODUCT_DETAIL_DISPLAY_SKU',
    'PRODUCT_DETAIL_DISPLAY_WARRANTY',
    'PRODUCT_DETAIL_DISPLAY_RETURN',
    'PRODUCT_DETAIL_DISPLAY_MODEL',
    'PRODUCT_DETAIL_DISPLAY_BRAND',
    'PRICE_INCLUDING_TAX',
    'GOOGLE_MAPS_API_STATUS',
    'COD_ENABLE',
    ]);
    $taxInclude = $config['PRICE_INCLUDING_TAX'];
    @endphp

    <div class="product-detail js-add-to-cartloop">
        <div class="breadcrumb-wrapper">
            <ul class="breadcrumb" data-yk="" role="yk-navigation" aria-label="breadcrumbs">
                <li class="breadcrumb-item ">
                    <a href="{{url('/')}}" title="Back to the home page">{{__("LNK_HOME")}}</a>
                </li>
                @if($product->cat_name)
                <li class="breadcrumb-item">
                    <a href="{{getRewriteUrl('categories',$product->cat_id)}}">{{$product->cat_name}}</a>
                </li>
                @endif
                <li class="breadcrumb-item" title="{{$product->prod_name}}">
                    {{Str::limit($product->prod_name, 60, $end='...')}}</li>
            </ul>
        </div>
        <!--breadcrumb-wrapper-->
        @if($config['PRODUCT_DETAIL_DISPLAY_BRAND'] != 0 && !empty($product->brand_name))
        <div class="product-detail__brand"><a
                href="{{getRewriteUrl('brands', $product->brand_id)}}">{{$product->brand_name}}</a></div>
        @endif
        <h1 class="product-detail__title">{{$product->prod_name}}</h1>


        <div class="row justify-content-between no-gutters product-detail__row">
            <div class="col-auto">
                <div class="d-xl-flex align-items-center py-2">

                    <div class="product-detail__rating">
                        <div class="rating-holder">
                            @if($rating != 0)
                            @include('themes.'.config('theme').'.product.partials.ratingStars', ['rating' => $rating])
                            <p><a href="#reviews">({{$rating}} out of 5)</a></p>
                            @else
                            @include('themes.'.config('theme').'.product.partials.ratingStars', ['rating' => 0])

                            @endif
                        </div>
                    </div>
                    <!--rating-->

                    <div class="product-detail__meta">
                        @if($product->sku && $config['PRODUCT_DETAIL_DISPLAY_SKU'] != 0)
                        <span class="product-sku">
                            {{__("LBL_SKU")}} : {{$product->sku}}</span>
                        @endif
                        @if($product->prod_model && $config['PRODUCT_DETAIL_DISPLAY_MODEL'] != 0)
                        <span class="product-model">
                            {{__("LBL_MODEL")}} : {{$product->prod_model}}</span>
                        @endif
                    </div>
                    <!--product meta-->

                </div>
                <!--d-flex-->
            </div>
            <div class="col-auto">
                <div class="d-flex align-items-center py-2">

                    <a class="askquestion" href="javascript:;" onclick="askQuestions('{{ $product->prod_id }}', '{{($product->pov_code) ?? 0 }}', this)">
                        <span class="askquestion__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path
                                    d="M2559.65,159.894h-14.4a1.805,1.805,0,0,0-1.8,1.8v16.2l3.6-3.6h12.6a1.805,1.805,0,0,0,1.8-1.8v-10.8A1.805,1.805,0,0,0,2559.65,159.894Zm-7.2,11.7h-6.3v-1.8h6.3Zm6.3-3.6h-12.6v-1.8h12.6Zm0-3.6h-12.6v-1.8h12.6Z"
                                    transform="translate(-2540.45 -156.894)" />
                            </svg>
                        </span>
                        <span class="askquestion__lbl">{{ __('LBL_ASK_A_QUESTION')}}</span>
                    </a>

                    <a class="product-share" data-toggle="modal" href="#modalsocial">
                        <span class="product-share__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <g transform="translate(0.9 1)">
                                    <circle cx="3" cy="3" r="3" transform="translate(11.2 2)" />
                                    <circle cx="3" cy="3" r="3" transform="translate(11.2 12)" />
                                    <circle cx="3" cy="3" r="3" transform="translate(3 7)" />
                                    <path fill="none" stroke-width="2px" stroke="currentColor"
                                        d="M20857.2-974l-8.2,5,8.2,5" transform="translate(-20842 979)" />
                                </g>
                            </svg>
                        </span>
                        <span class="product-share__lbl">{{ __('LBL_SHARE')}}</span>
                    </a>

                    <!-- @if($sharethisNetworks->count() > 0)
                            <div class="socail-follow socail-follow-detail">
                                @php
                                $totalSocialLinks = $sharethisNetworks;
                                if($sharethisNetworks->count() > 4){
                                $totalSocialLinks = array_slice($sharethisNetworks->toArray(),0, 4);
                                }
                                @endphp
                                @foreach($totalSocialLinks as $key => $network)
                                @php
                                $explodedNetwork = explode('_', $key);
                                $type = strtolower(end($explodedNetwork));
                                $icon = 'fab fa-' . $type;
                                if ($type == 'facebook') {
                                $icon = 'fab fa-facebook-f';
                                }elseif ($type == 'wechat') {
                                $icon = 'fab fa-weixin';
                                }elseif ($type == 'email') {
                                $icon = 'far fa-envelope';
                                }
                                @endphp
                                <a class="icon icon-{{$type}} st-custom-button" data-title="{{$product->prod_name}}" data-network="{{$type}}" data-url="{{url('/product/' . $product->prod_id)}}"><i class="{{$icon}}"></i></a>
                                @endforeach
                                @if($sharethisNetworks->count() > 4)
                                <a class="icon" data-toggle="modal" href="#modalsocial"><span class="more">{{$sharethisNetworks->count() - 4}}+</span></a>
                                @endif
                            </div>
                        @endif -->
                </div>
            </div>
        </div>
        <div class="divider"></div>


        <div class="product-detail__price">
            <div class="block-label">
                {{__("LBL_PRICE")}}:
                <span>
                    @php
                    $taxLabel = 'LBL_EXCLUDING';
                    if($taxInclude == 1){
                    $taxLabel = 'LBL_INCLUDING';
                    }
                    @endphp
                    {{__($taxLabel) . ' ' . __("LBL_X_OF_ALL_TAXES")}}
                </span>
            </div>
            <div class="price-wrappar">
                <div class="price" value="{{$product->finalprice}}">{{displayPrice($product->finalprice)}}</div>
                @if($product->price != $product->finalprice)
                <div class="sale-price"><del>{{displayPrice($product->price)}}</del>

                    @if($product->discount != 0)

                    <span>
                        <span class="spl-price">{{__("BDG_SAVE")}}
                            {{round($product->discount)}}% </span></span>

                    @endif
                </div>
                @endif
            </div>


        </div>


        <div class="product-selectors">
            <div class="product-quantity">
                <div class="product-selectors__lbl">{{__("LBL_QUANTITY")}}: </div>

                <div class="quantity quantity-detail YK-quantity">
                    <span class="decrease @if($stock != true){{'disabled'}}@endif"
                        @if($stock==true)onclick="decreaseQty(this)" @endif>
                        <i class="icn">
                            <svg class="svg">
                                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#minus"
                                    href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#minus">
                                </use>
                            </svg>
                        </i>
                    </span>
                    <input class="qty-input no-focus YK-qty" max="{{$maxQty}}" min="{{$qty}}" data-page="product-view"
                        title="{{__('PLH_AVAILABLE_QUANTITY')}}" type="text" name="quantity" value="{{$qty}}" readonly>
                    <span class="increase @if($stock != true){{'disabled'}}@endif" @if($stock==true)
                        onclick="increaseQty(this)" @endif>
                        <i class="icn">
                            <svg class="svg">
                                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#add"
                                    href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#add">
                                </use>
                            </svg>
                        </i>
                    </span>
                </div>
            </div>
            <!--product-quantity-->
            @if($sizeExist || count($allVarients) > 0)
            <div class="product-detail__option">
                <div class="product-selectors__lbl">
                    <span>{{__('LBL_OPTIONS')}}</span>
                    @if($sizeExist)
                    <a class="size-chart" data-toggle="modal" href="#modalSizeChart">
                        <i>
                            <svg class="svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                                <g transform="translate(-1322 -528)">
                                    <path
                                        d="M7118.169-3872.891v12.848a.432.432,0,0,1-.435.429h-3.467a.429.429,0,0,1-.429-.428v-1.713H7116v-.856h-2.164v-1.285h1.3v-.857h-1.3v-1.285H7116v-.857h-2.164v-1.285h1.3v-.856h-1.3v-1.285H7116v-.857h-2.164v-1.713a.429.429,0,0,1,.429-.429h3.467A.433.433,0,0,1,7118.169-3872.891Z"
                                        transform="translate(-6434.403 -1760.393) rotate(45)" />
                                </g>
                            </svg>
                        </i>
                        <span> @if(!empty($product->pc_file_title) && strtolower($product->pc_file_title) != "null")
                            {{$product->pc_file_title}} @else {{ __('LBL_SIZE_CHART') }}
                            @endif</span>
                    </a>
                    @endif
                </div>
                @if(count($allVarients) > 0)
                <div class="dropdown">
                                @php
                                $selectedVarient = $allVarients->where('pov_id', $product->pov_id)->first();

                                $x = 1;
                                @endphp
                                <a class="@if(count($allVarients) > 1) {{'dropdown-toggle'}} @endif dropdown-select" data-toggle="dropdown"
                                    href="javascript:;" aria-expanded="false">
                                    <span><span class="">
                                            @foreach($selectedVarient->options as $selectedoption)

                                            @if($selectedoption->option_type == 1)
                                            @php 
                                                    $whiteClass = "";
                                                    if($selectedoption->opn_color_code == "#FFF" || $selectedoption->opn_color_code == "#FFFFFF" || $selectedoption->opn_value == 'white'){
                                                        $whiteClass = "color-shadow";
                                                    }
                                                      @endphp  
                                            <span class="color-display {{$whiteClass}}"
                                                style="background-color:{{$selectedoption->opn_color_code ?? $selectedoption->opn_value}};"></span>
                                            @endif
                                            {{ucfirst($selectedoption->opn_value)}}
                                            {{$selectedVarient->options_count != $x ? ' | ' : ''}}
                                            @php $x++; @endphp
                                            @endforeach</span></span>
                                </a>
                                @if(count($allVarients) > 1)
                                <div class="dropdown-menu dropdown-menu-fit  dropdown-menu-anim" 
                                x-placement="bottom-start"                                    
                                    >
                                    <ul class="nav nav--block scroll-y" style="max-height:270px;">
                                        @foreach($allVarients->where('pov_id', '!=',$product->pov_id) as $varient)
                                        <li class="nav__item">
                                            <a class="nav__link" href="javascript:;"
                                                onclick="getVarient('{{$varient->pov_code}}')">
                                                <span class="nav__link-text">
                                                    @php $x = 1; @endphp
                                                    @foreach($varient->options as $option)

                                                    @if($option->option_type == 1)

                                                    @php 
                                                    $whiteClass = "";
                                                    if($option->opn_color_code == "#FFF" || $option->opn_color_code == "#FFFFFF" || $option->opn_value == 'white'){
                                                        $whiteClass = "color-shadow";
                                                    }
                                                      @endphp   
                                                    <span class="color-display {{$whiteClass}}"
                                                        style="background-color:{{$option->opn_color_code ?? $option->opn_value}};"></span>
                                                    @endif
                                                    {{ucfirst($option->opn_value)}}
                                                    {{$varient->options_count != $x ? ' | ' : ''}}
                                                    @php $x++; @endphp
                                                    @endforeach
                                                </span>
                                            </a>
                                        </li>
                                        @endforeach

                                    </ul>
                                </div>
                                @endif
                            </div> 
        

                </select>
                @endif

            </div>
            @endif
        </div>


        <div class="product-detail__cart">
            <div class="product-add-to-cart">
                <button class="btn btn-brand btn-wide add-cart gb-btn gb-btn-primary" name="YK-addToCartBtn"
                    @if($shipping==true && $stock !=false)
                    onclick="addTocart('{{$product->prod_id}}', this, true, 0, '{{$product->pov_code}}')" @endif @if($stock==false ||
                    $shipping==false) disabled @endif>
                    @if($stock == false)
                    {{__("LBL_OUT_OF_STOCK")}}
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24.75" height="24" viewBox="0 0 24.75 24">
                        <path class="b"
                            d="M21.687,13.75H9.091l.281,1.375H20.906a1.031,1.031,0,0,1,1.006,1.26l-.237,1.043a2.407,2.407,0,1,1-2.733.447H9.933a2.406,2.406,0,1,1-2.881-.368L4.034,2.75h-3A1.031,1.031,0,0,1,0,1.719V1.031A1.031,1.031,0,0,1,1.031,0H5.437a1.031,1.031,0,0,1,1.01.825L6.841,2.75H23.718a1.031,1.031,0,0,1,1.006,1.26l-2.031,8.938A1.031,1.031,0,0,1,21.687,13.75ZM17.531,7.219H15.469V5.5a.687.687,0,0,0-.688-.687h-.687a.687.687,0,0,0-.687.688V7.219H11.344a.687.687,0,0,0-.687.687v.688a.687.687,0,0,0,.687.687h2.063V11a.687.687,0,0,0,.688.688h.687A.687.687,0,0,0,15.469,11V9.281h2.062a.687.687,0,0,0,.687-.687V7.906A.687.687,0,0,0,17.531,7.219Z"
                            transform="translate(0 1)" />
                    </svg>
                    {{__("BTN_ADD_TO_CART")}}
                    @endif
                </button>
                <button class="btn add-wishlist @if(getProductfavorite($product) == true){{'active'}}@endif"
                    onclick="updateFavourite(this, '{{$product->prod_id}}', '{{$product->pov_code}}')">
                    <svg class="svg m-0">
                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#wishlist"
                            href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#wishlist"></use>
                    </svg>
                    <!-- {{__("BTN_FAVOURITE")}} -->
                </button>
            </div>
        </div>


        {{--<div class="product-detail__availablity-check">
                @if($product->prod_type != 2 && $config['GOOGLE_MAPS_API_STATUS'] == 1)
                    
                    @if(isset($pincode))
                    @if($shipping == true)
                    <div class="sucess">{{__("LBL_SHIPPING_AVAILABLE")}}
    </div>
    @else
    <div class="error">{{__("LBL_SHIPPING_NOT_AVAILABLE")}}</div>
    @endif
    @endif
    <form action="" class="form available-form" id="YK-zipcode">
        <div class="mb-2">
            <span class="available-form__icon pr-2">
                <svg class="svg" width="28px" height="28px">
                    <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#pick-up"
                        href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#pick-up">
                    </use>
                </svg>
            </span>
            <span>{{__("LBL_CHECK_STOCK_AVAILABLE_AT_PINCODE")}}</span>
        </div>
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" placeholder="Pin code" value="{{$pincode ?? ''}}" name="pincode"
                        class="form-control ">
                    <input type="button" class="btn btn-secondary" name="" value="submit"
                        onclick="varifyShipping('{{$product->prod_id}}')">
                </div>
            </div>
            <div class="col-auto">
                <div class="product-available-msg">
                    <span>Delivery by:</span> 27 Feb, Saturday
                </div>
            </div>
        </div>
    </form>

    @endif
    </div>--}}



    </div>
    <!--product-detail-->