@php
$listview = getConfigValueByName('LISTING_GRID_DEFAULT');
@endphp
@if(isset($filters))
@include('themes.'.config('theme').'.product.partials.filterTags')
@php $listview = $filters['listview']; @endphp
@endif
@if(count($products) > 0)
<div id="collection-products" data-view="{{$listview}}" class="collection-products products-grid">
    @foreach($products as $product)
    @include('themes.'.config('theme').'.partials.productCard', ['aspectRatio' => productAspectRatio()])
    @endforeach
</div>
@else
@include('themes.'.config('theme').'.partials.noRecordFound', ['text1'=> __("LBL_NO_PRODUCTS_FOUND"),'size'=> 'large'])
@endif

<div id="collection-footer" class="collection-footer">
    <div class="pagination-wrapper">
        @if(count($products) > 0)
        {{ $products->links('partials.pagination') }}
        <div class="show-all">
            <select class="form-control custom-select custom-select-sm select-show Yk-showRecords" onchange="filterProducts()">
                @foreach(productPerPageListingRecords() as $perPageListingRecords)
                <option value="{{$perPageListingRecords}}" @if($products->perPage() == $perPageListingRecords) {!!'selected="selected"'!!}@endif>{{$perPageListingRecords}}</option>
                @endforeach
            </select>
            <span class="showing-all">{{__("LBL_PAGINATION_SHOWING")}}: <strong>{{$products->firstItem()}} - {{$products->lastItem()}}</strong> {{__("LBL_PAGINATION_OF")}} <strong>{{$products->total()}}</strong>
            </span>
        </div>
        @endif
    </div>
</div>
<button data-trigger="collection-sidebar" class=" btn-float btn-filter">
    <i class="icn">
        <svg class="svg">
            <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#filter" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#filter"></use>
        </svg>
    </i>
</button>