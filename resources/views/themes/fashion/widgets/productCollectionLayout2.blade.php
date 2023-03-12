@if($productsinternalHtml == false)
<section class="section yk-productCollectionLayout2" data-gjs-draggable="div.js-body">
    <div class="container yk-container" compid="{{ $cid ?? '' }}">
        @include('themes.'.config('theme').'.widgets.partials.productCollectionLayout2')       
    </div>
</section>
@else
@include('themes.'.config('theme').'.widgets.partials.productCollectionLayout2')       
@endif