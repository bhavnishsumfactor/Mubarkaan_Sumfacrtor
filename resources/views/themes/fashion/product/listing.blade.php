@extends('layouts.full')

@section('content')
@php
$gridDefault = getConfigValueByName('LISTING_GRID_DEFAULT');
@endphp
<div class="body" id="body" data-yk="" >
   
    <section class="listing-head">
    @if(!empty($category) && fileExists('productCategoryBanner', $category->cat_id))

    <picture class="img-category">
                <source data-aspect-ratio="5:1" srcset="{{getFileUrl('productCategoryBanner', $category->cat_id, 0, '600-120-webp')}}" media="(max-width: 767px)">
                <source data-aspect-ratio="5:1" srcset="{{getFileUrl('productCategoryBanner', $category->cat_id, 0, '1200-240-webp')}}" media="(max-width: 1024px)">
                <source data-aspect-ratio="5:1" srcset="{{getFileUrl('productCategoryBanner', $category->cat_id, 0, '2000-400-webp')}}">
                <img  data-aspect-ratio="5:1" data-yk=""  src="{{getFileUrl('productCategoryBanner', $category->cat_id, 0, '2000-400')}}" alt="{{$category->uploadImages->afile_attribute_alt ?? ''}}" title="{{$category->uploadImages->afile_attribute_title ?? ''}}" >
    </picture>   
        @else
        
            @endif
            @php
            $title = __("LBL_PRODUCTS");
            if(isset($_GET['s']) && $_GET['s'] != ""){
            $title = $_GET['s'];
            }elseif(!empty($category)){
            $title = $category->cat_name;
            }elseif(!empty($brand)){
            $title = $brand->brand_name;
            }
            @endphp
            <div class="container">
                <div class="listing-head_inner">
                    <div class="listing-head_title">
                                <h1>{{$title}}</h1>
                    </div>

                        <nav class="breadcrumb" data-yk=""  aria-label="breadcrumbs">
                            <li class="breadcrumb-item ">
                                <a href="{{url('/')}}" title="Back to the frontpage">{{__("LNK_HOME")}}</a>
                            </li>
                            <li class="breadcrumb-item">
                                {{$title}}
                            </li>
                        </nav>

                </div>
            </div>
           
    </section>
    <section class="section listing-page">
            <div class="container">
                <div class="collection-top-bar @if(count($products) == 0){{'no-data'}}@endif">
                  
                    <div class="collection-options">
                        @if(count($products) > 0)
                        <div class="collection-view d-none d-xl-block">
                            <div class="wc-col-switch row align-items-center">
                                <div class="col-auto" id="collection-view">
                                    <a class="four @if($gridDefault=='4') active @endif d-none d-md-block YK-listview" href="javascript: void(0)" data-num="4" data-col="3" onclick="changeProductView(4,this)"></a>
                                    <a class="five @if($gridDefault=='5') active @endif d-none d-md-block YK-listview" href="javascript: void(0)" data-num="5" data-col="4" onclick="changeProductView(5,this)"></a>
                                </div>
                            </div>
                        </div>
                        <div class="collection-sort">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <select class="filter-select custom-select select-fancyx" name="sortBy" onchange="filterProducts()">
                                        <option value="new">{{__("LNK_FILTER_WHATS_NEW")}}</option>
                                        <option value="popularity">{{__("LNK_FILTER_BEST_SELLING")}}</option>
                                        <option value="rating">{{__("LNK_FILTER_BEST_RATED")}}</option>
                                        <option value="discounted">{{__("LNK_FILTER_MOST_DISCOUNTED")}}</option>
                                        <option value="priceDesc">{{__("LNK_FILTER_PRICE_HIGH_TO_LOW")}}</option>
                                        <option value="priceAsc">{{__("LNK_FILTER_PRICE_LOW_TO_HIGH")}}</option>
                                        <option value="alphabetically">{{__("LNK_FILTER_A_TO_Z")}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="collection-listing">
                    @if(count($products) > 0)
                    <aside class="collection-sidebar YK-sidebar" id="collection-sidebar" data-close-on-click-outside="collection-sidebar">
                        @include('themes.'.config('theme').'.product.partials.filterSkeleton')
                    </aside>
                    <div class="collection-content YK-listingRightContainer">
                        <div class="YK-productSection">
                            @include('themes.'.config('theme').'.product.partials.productGrid')                            
                        </div>
                    </div>
                    @else
                    @include('themes.'.config('theme').'.partials.noRecordFound', ['text1'=> __("LBL_NO_PRODUCTS_FOUND"),'size'=> 'large'])

                    @endif
                </div>
            </div>
        </section>

        @include('themes.'.config('theme').'.product.partials.recentlyViewedProducts')
</div>
@section('scripts')
<script src="{{ asset('yokart/'.config('theme').'/js/nouislider.js') }}"></script>
<script >
    function changeProductView(dataRow, el) {
        $(".YK-listview").removeClass('active');
        $("#collection-products").attr('data-view', dataRow);
        $(el).addClass('active');
    }
    @if(isset($_GET['s']) && $_GET['s'] != "")
    events.search({
        search_string: "{{$_GET['s']}}"
    });
    @endif
    @php
    if (empty($filterType) && empty($filterValue) && empty($searchedBy)) {
        $filterType = "";
        $filterValue = "";
        $searchedBy = "";
    }
    if (count($products) > 0) {
        @endphp
        getFilters('{{$filterType}}', "{{$filterValue}}", "{{$searchedBy}}", );
        @php
    }
    @endphp
</script>
<script src="{{ asset('yokart/'.config('theme').'/js/vendors/jquery.nice-select.js') }}"></script>

@endsection
@section('css')
 
<!-- <link rel='stylesheet' href="{{ asset('vendors/css/nouislider.css') }}"> -->
@endsection
@endsection