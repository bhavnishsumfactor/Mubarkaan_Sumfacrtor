@if($productsinternalHtml == false)
<section class="section yk-productCollectionLayout1" data-gjs-draggable="div.js-body">
    <div class="container yk-container" compid="{{ $cid ?? '' }}">
        @include('themes.'.config('theme').'.widgets.partials.productCollectionLayout1')
    </div>
</section>
@else
    @include('themes.'.config('theme').'.widgets.partials.productCollectionLayout1')
@endif