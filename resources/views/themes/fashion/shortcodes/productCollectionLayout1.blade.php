<productcollectionlayout1>
    <ul class="js-collection-slider collection-slider products-grid yk-products justify-content-center">
        @if(!empty($products))
        @php $i = 0; @endphp
        @foreach($products as $product)
        <li class="item">
            @include('themes.'.config('theme').'.partials.productCard', ['aspectRatio' => productAspectRatio()])
        </li>
        @php
            if ($i==11) {
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
</productcollectionlayout1>