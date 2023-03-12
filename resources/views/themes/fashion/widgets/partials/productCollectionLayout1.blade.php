@php 
$dummyHtml = empty($products) ? true : false;
@endphp
@if($productsinternalHtml == false)
<div class="section-content">
    <h2 class="yk-title">{{__("LBL_COLLECTION_TITLE")}}</h2>
    @if((!empty($products) && count($products) > 12) || $dummyHtml)
        <div class="section-action"><a href="#" class="link-arrow yk-link">{{__("BTN_VIEW_ALL")}}</a></div>
    @endif 
</div>
@endif
<productcollectionlayout1>
    <ul class="js-collection-slider collection-slider products-grid yk-products justify-content-center">
        @if(!empty($products))
        @php $i = 0; @endphp
        @foreach($products as $product) 
        <li class="item">
            @include('themes.'.config('theme').'.partials.productCard', ['aspectRatio' => productAspectRatio()])
        </li>
        @php
            if ($i==3) {
                break;
            }
            $i++;
        @endphp
        @endforeach
        @else
            @for($i = 0; $i < 4; $i++)
                <li class="item">
                    @include('themes.'.config('theme').'.partials.productCardDummy', ['aspectRatio' => productAspectRatio()])
                </li>
            @endfor
        @endif
    </ul>

    @if(!empty($products) && count($products)>4)
        <div class="text-center p-5"> + {{ count($products) - 4 }} {{__("LBL_MORE_PRODUCTS")}}</div>
    @endif  
</productcollectionlayout1>