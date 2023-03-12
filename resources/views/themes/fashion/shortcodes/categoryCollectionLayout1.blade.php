@php
$time = time();  
@endphp
<categorycollectionlayout1>
    <div class="tabs-content yk-categories" > 
    @if(!empty($collections) && count($collections)>0)
    @php $categories = $collections->pluck('collection_id', 'cat_id')->toArray(); $k = 0; 

    @endphp

        @foreach($categories as $key=> $category)  
        <div id="{{$category.'-'.$key.'-'. $k}}" class="content-data {{($k==0)?'active':''}}">
            <div class="collection-products" data-view="4">
              
                @foreach($collections->where('cat_id', $key)->sortBy('collection_display_order') as $product)
              
                @include('themes.'.config('theme').'.partials.productCard3', ['product'=>$product, 'aspectRatio' => productAspectRatio()])
                @endforeach
              
            </div>
        </div>
        @php
              $k++;
              @endphp
        @endforeach
    @else
        <div id="{{$time}}"  style="display: block;">
            <div class="collection-products" data-view="4">
            @for($j = 0; $j < 4; $j++)
                 @include('themes.'.config('theme').'.partials.productCardDummy', ['aspectRatio' => productAspectRatio(), 'hideGallery' => true]) 
            @endfor
        </div>
        </div> 
    @endif
    </div>
</categorycollectionlayout1>