<div class="sidebar-widgets">
    <form id="YK-filtersForm">
        @if($catListView == true)
        <div class="sidebar-widget">
            <div class="sidebar-widget__head" data-toggle="collapse" data-target="#widget-category" aria-expanded="true">{{__('LBL_CATEGORIES')}}</div>
            <div class="collapse sidebar-widget__body show" id="widget-category" data-parent="#collection-sidebar">
                <ul class="list-vertical">
                    @foreach($categoryRecords as $category)
                    <li>
                        <label class="checkbox">
                            <input type="checkbox" name="categories[{{$category->cat_id}}]" value="{{$category->cat_id}}" onclick="filterProducts()"> <i class="input-helper"></i>{{$category->cat_name}}</label>
                        </label>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <input type="hidden" name="catListView" value="{{$catListView}}">
        @else
        <div class="sidebar-widget">
            @php
            $searchedCategory = "";
            if(isset($categories)){
            $searchedCategory = $categories;
            }
            @endphp
            <div class="sidebar-widget__head" data-toggle="collapse" data-target="#widget-category" aria-expanded="true">{{__('LBL_CATEGORIES')}}</div>
            <div class="collapse category-menu sidebar-widget__body show" id="widget-category" data-parent="#collection-sidebar">
                <ul class="menu__nav YK-category-listing" id="accordionMenu" data-yk="" role="yk-tablist" aria-multiselectable="true">
                    @foreach($categoryRecords as $category)
                    @php
                    $detailPageUrl = '';
                    if($category->urlRewrite){
                    $detailPageUrl = $category->urlRewrite->urlrewrite_original;
                    if(!empty($category->urlRewrite->urlrewrite_custom)){
                    $detailPageUrl = $category->urlRewrite->urlrewrite_custom;
                    }
                    }
                    @endphp
                    @include('themes.'.config('theme').'.product.partials.sidebarChildCategories', ['searchedCategory' => $searchedCategory, 'detailPageUrl' => $detailPageUrl])
                    @endforeach
                </ul>
            </div>
        </div>
        @if($searchedCategory)
        <input type="hidden" name="categories[{{$searchedCategory}}]" value="{{$searchedCategory}}">
        <script>
            $(".YK-category-listing").find('li.active').each(function() {
                $(this).parents('li:eq(1)').find('i').removeClass("collapsed");

                $(this).parents('li:eq(1)').find(".panel-collapse").each(function() {
                    $(this).addClass("show");
                    $(this).find('i').removeClass("collapsed");
                });
            });
        </script>
        @endif
        @endif
        @if(count($brandRecords) > 1)
        <div class="sidebar-widget">
            <div class="sidebar-widget__head" data-toggle="collapse" data-target="#widget-brands" aria-expanded="false">{{__('LBL_BRANDS')}}</div>
            <div class="collapse YK-brandFilters sidebar-widget__body" id="widget-brands" data-parent="#collection-sidebar">
                @include('themes.'.config('theme').'.product.partials.brandWidgetFilters')
            </div>
        </div>
        @endif
        @if(isset($brands))
        <input type="hidden" name="brands[{{$brands}}]" value="{{$brands}}">
        @endif
        @if(isset($options))

        @foreach($options as $key=> $option)

        <div class="sidebar-widget">
            <div class="sidebar-widget__head" data-toggle="collapse" data-target="#widget-{{$option['name']}}" aria-expanded="false">{{__($option['name'])}}</div>
            <div class="collapse sidebar-widget__body YK-{{$option['name']}}Listing" id="widget-{{$option['name']}}" data-parent="#collection-sidebar">
                @include('themes.'.config('theme').'.product.partials.optionsWidgetFilters')
            </div>
        </div>


        @endforeach
        @endif
        <div class="sidebar-widget">
            <div class="sidebar-widget__head" data-toggle="collapse" data-target="#widget-price" aria-expanded="false">{{__("LBL_PRICE")}}</div>
            <div class="collapse sidebar-widget__body" id="widget-price" data-parent="#collection-sidebar">
                <div id="rangeSlider" class="price-slider"></div>
                <div class="slide__fields form">
                    <div class="price-input">
                        <div class="price-text-box">
                            <input type="text" class="input-filter form-control" id="priceFilterMinValue" data-defaultvalue="{{$minPrice}}" name="priceFilterMinValue" value="{{$minPrice}}">
                            <span class="rsText">{{currencySymbol()}}</span>
                        </div>
                    </div>
                    <span class="dash"> - </span>
                    <div class="price-input">
                        <div class="price-text-box">
                            <input type="text" class="input-filter form-control" id="priceFilterMaxValue" data-defaultvalue="{{$maxPrice}}" name="priceFilterMaxValue" value="{{$maxPrice}}">
                            <span class="rsText">{{currencySymbol()}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(count($conditions) > 1)
        <div class="sidebar-widget">
            <div class="sidebar-widget__head" data-toggle="collapse" data-target="#widget-conditions" aria-expanded="false">{{__('LBL_PRODUCT_CONDITION')}}</div>
            <div class="collapse sidebar-widget__body" id="widget-conditions" data-parent="#collection-sidebar">
                <ul class="list-vertical">
                    @foreach($conditions as $condition)
                    <li>
                        <label class="checkbox">
                            <input data-field-caption="" type="checkbox" name="prod-conditions[{{$condition}}]" value="{{$condition}}" @if(!empty($searchFilters['conditions']) && in_array($condition ,$searchFilters['conditions'])) {{'checked'}} @endif onclick="filterProducts()"> <i class="input-helper"></i>{{$allConditions[$condition]}}
                        </label>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        @if(isset($searchedBy))
        <input type="hidden" name="searchedBy" value="{{$searchedBy}}">
        @endif
        @if(isset($collection))
        <input type="hidden" name="collection" value="{{$collection}}">
        @endif
    </form>
</div>
<script>
    let minValue = '{{$minPrice}}';
    let maxValue = '{{$maxPrice}}';
    let stepsSlider = document.getElementById('rangeSlider');
    let priceFilterMinValue = document.getElementById('priceFilterMinValue');
    let priceFilterMaxValue = document.getElementById('priceFilterMaxValue');
    var inputs = [priceFilterMinValue, priceFilterMaxValue];
    noUiSlider.create(stepsSlider, {
        start: [{{$minPrice}}, {{$maxPrice}}],
        connect: true,
        decimals: 0,
        range: {
            'min': {{$minPrice}},
            'max': {{$maxPrice}}
        }
    });
    stepsSlider.noUiSlider.on('change', function(values, handle) {
        inputs[handle].value = values[handle];
        filterProducts();
    });

    stepsSlider.noUiSlider.on('update', function (values) {        
        $('input[name="priceFilterMinValue"]').val(parseInt(values[0]));
        $('input[name="priceFilterMaxValue"]').val(parseInt(values[1]));
    });
    inputs.forEach(function(input, handle) {
        input.addEventListener('change', function() {
            let sliderVal = parseInt(this.value);
            if (handle == 1 && sliderVal > maxValue) {
                sliderVal = parseInt(maxValue);
                $('input[name="priceFilterMaxValue"]').val(sliderVal);
            }
            if (handle == 0 && sliderVal < minValue) {
                sliderVal =  parseInt(minValue);
                $('input[name="priceFilterMinValue"]').val(sliderVal);
            }
            stepsSlider.noUiSlider.setHandle(handle, sliderVal);
            filterProducts();
        });
    });
</script>