@php 
$dummyHtml = false;
$time = time();
@endphp

@if($productsinternalHtml==false)
<section class="section category-collectionLayout-1 yk-categoryCollectionLayout1" data-gjs-draggable="div.js-body">
  <div class="container yk-container" compid="{{ $cid ?? '' }}">
    <div class="section-content">
      <h2 class="yk-title">{{__("LBL_CATEGORY_COLLECTION_TITLE")}}</h2>
      <ul class="js-tabs yk-categories tabs tabs-index">

    
        @include('themes.'.config('theme').'.widgets.partials.getCategoriesTabbing')
      </ul>      
    </div>
    @endif
   
    <categorycollectionlayout1>        
        <div class="tabs-content yk-categories">
          @if(!empty($collections) && count($collections)>0)
          @php $categories = $collections->keyBy('cat_id'); 
          $k = 0;
          @endphp
          @foreach($categories as $key => $category)  
      
              <div id="{{$category->collection_id.'-'.$key.'-'. $k}}" class="content-data {{($k==0)?'active':''}}">
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
          @php
          $dummyHtml = true;
          @endphp
            <div id="{{$time}}" class="content-data d-block">
                <div class="collection-products" data-view="4">
                  @for($j = 0; $j < 4; $j++)
                    @include('themes.'.config('theme').'.partials.productCardDummy', ['aspectRatio' => productAspectRatio(), 'hideGallery' => true]) 
                  @endfor
              </div>
            </div> 
          @endif
        </div>       
      </categorycollectionlayout1>    
@if($productsinternalHtml==false || $dummyHtml == true)
        <div class="section-action section-action-center mt-5"><a href="#" class="btn btn-border btn-wide yk-link">{{__("BTN_VIEW_ALL")}}</a></div>
    </div>
</section>
@endif