<productcollectionlayout2>
    <div class="collections-mosaic yk-products">
        @if(!empty($products) && count($products)>0)
            @foreach($products as $k=>$product)
            @php
                $subRecordId = productColorId($product); 
                $images = getProductImages($product->prod_id, $subRecordId);
                $stock = false;
                $stock = productStockVerify($product, $product->prod_min_order_qty, $product->pov_code);
                /*$product->finalprice = ($escapeCurrency??'').displayPrice($product->finalprice); */
            @endphp
            <div class="collections-mosaic__column @if($stock != true) no-stock-wrap @endif">
                @if($stock != true)
                <div class="no-stock">{{__("LBL_OUT_OF_STOCK")}}</div>
                @endif
                <div class="tea-widget justify-content-end">
                    <div class="tea-widget__content deals__content">
                        <h3>{{Str::limit($product->prod_name, 25, $end='...')}}</h3>
                        {{--<p>{{ $product->finalprice }}</p>--}}
                    </div>
                    <a class="tea-widget__link js-fillcolor" href="{{!empty($product->prod_id) ? getRewriteUrl('products', $product->prod_id) : '#'}}">
                        @if(!empty($images->first()))
                        <img data-yk="" alt="" src="{{ url('yokart/image/'.$images->first()->afile_id.'/'.getProdSize('medium').'?t=' . strtotime($images->first()->afile_updated_at)) }}" data-aspect-ratio="{{productAspectRatio()}}">
                        @else
                        <img data-yk="" alt="" src="{{ productDummyImage() }}" data-aspect-ratio="{{productAspectRatio()}}">
                        @endif
                    </a>
                </div>
            </div>
            @endforeach
        @else
            @php
            $default_price = ($escapeCurrency??'').currencySymbol().'XX.XX';
            @endphp
            @for($i=0; $i < 5; $i++)
                <div class="collections-mosaic__column">
                    <div class="tea-widget justify-content-end">
                        <div class="tea-widget__content deals__content">
                            <h3>{{__("LBL_PRODUCT_TITLE")}}</h3>
                            {{--<p>{{$default_price}}</p>--}}
                        </div>
                        <a class="tea-widget__link js-fillcolor" href="#">
                            <img data-yk="" alt="" src="{{ productDummyImage() }}" data-aspect-ratio="{{productAspectRatio()}}">
                        </a>
                    </div>
                </div>
            @endfor
        @endif
    </div>
</productcollectionlayout2>