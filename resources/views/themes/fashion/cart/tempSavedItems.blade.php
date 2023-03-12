@if(count($tempSavedProducts) > 0)
    @php 
        $switchOrderText = 'shipping';
        if($selectedShipping == 'shipping'){
            $switchOrderText = 'pickup';
        }
        $switchEntireOrder = validateEntireShippigType($shippingIds, $switchOrderText);
    @endphp
    <ul class="list-group list-cart list-cart-saved-later">    
        <li class="list-group-item">
            <div class="info info-ship">
                <span class="info__inner"><i class="icn"> 
                    <svg class="svg">
                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#info"
                            href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#info">
                        </use>
                    </svg>
                    </i>   
                    <p>{{__("LBL_SOME_ITEMS_NOT_AVAILABLE_FOR") . ' ' . $selectedShipping}} 
                        @if($switchEntireOrder)<a href="javascript:;" class="btn btn-outline-brand btn-sm ml-2" onclick="switchShipping('{{$switchOrderText}}')">{{ucfirst($switchOrderText) . ' ' . __("LNK_ENTIRE_ORDER")}}</a>@endif</p>
                </span>
                <ul class="list-actions">
                    <li>
                        <a href="javascript:;" onclick="deleteTempProducts()"><svg class="svg" width="24px" height="24px">
                                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#remove"
                                    href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#remove">
                                </use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @foreach($tempSavedProducts as $product)
        @php
            $productId = $product->prod_id;
            $subRecordId = productColorId($product);
            $images = getProductImages($productId, $subRecordId);
            $productUrl = getRewriteUrl('products', $productId);
        @endphp
        <li class="list-group-item YK-cartloop">
            <div class="product-profile">
                <div class="product-profile__thumbnail">
                    <a href="{{$productUrl}}">
                    <img data-yk="" class="img-fluid" data-ratio="{{productAspectRatio()}}" src="{{ !empty($images->first()) ? url('yokart/image/'.$images->first()->afile_id.'/'.getProdSize('small').'?t=' . strtotime($images->first()->afile_updated_at)) : noImage('../no_image.jpg') }}" alt="...">
                    </a>
                </div>
                <div class="product-profile__data">
                    <div class="title">
                        <a class="" href="{{$productUrl}}" data-toggle="tooltip" data-placement="top" title="{{ $product->prod_name }}">{{$product->prod_name}}</a>
                    </div>
                    @if($product->pov_display_title)
                        <div class="options text-capitalize">
                            <p class="">{{str_replace('_', ' | ', $product->pov_display_title)}}</p>
                        </div>
                    @endif
                    <p class="txt-brand pt-2">{{__("LBL_NOT_AVAILABLE_FOR") . ' ' . $selectedShipping}}</p>
                </div>
            </div>
            <div class="action">
                <button class="link" type="button"  onclick="savedForLater(this, '{{$product->prod_id}}', 0, '{{$product->pov_code}}')"> {{__("BTN_SAVE_FOR_LATER")}}</button>
            </div>
        </li>
        @endforeach
    </ul>
@endif