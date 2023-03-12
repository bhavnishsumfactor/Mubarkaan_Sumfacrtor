<ul class="selected-filter">
    @php
    $display = 0;
    $catTags = true;
    $brandTags = true;
    if(isset($filters['searchedBy']) && $filters['searchedBy'] == "categories"){
    $catTags = false;
    }elseif(isset($filters['searchedBy']) && $filters['searchedBy'] == "brands"){
    $brandTags = false;
    }
    @endphp
    @if(!empty($filters['categories']) && isset($filters['catListView']))
    @php
    $catFilters = getCategoriesNames($filters['categories']);
    $display = 1;
    @endphp
    @foreach($catFilters as $catKey => $catFilter)
    <li>
        <span>{{$catFilter}}</span>
        <a href="javascript:;" class="remove" onclick="removeFiltersByTags('categories', '{{$catKey}}')"><i class="fas fa-times"></i></a>
    </li>
    @endforeach
    @endif
    @if(!empty($filters['brands']) && $brandTags == true)
    @php
    $brandFilters = getBrandNames($filters['brands']);
    $display = 1;
    @endphp
    @foreach($brandFilters as $brandKey => $brandfilter)
    <li>
        <span>{{$brandfilter}}</span>
        <a href="javascript:;" class="remove" onclick="removeFiltersByTags('brands', '{{$brandKey}}')"><i class="fas fa-times"></i></a>
    </li>
    @endforeach
    @endif
    @if(!empty($filters['options']))
    @php
    $optionFilters = getOptionNames(Arr::flatten($filters['options']));
    $display = 1;
    @endphp
    @foreach($optionFilters as $key => $optionVal)
    <li>
        <span class="text-capitalize">{{$optionVal}}</span>
        <a href="javascript:;" class="remove" onclick="removeFiltersByTags('option', '{{$optionVal}}')"><i class="fas fa-times"></i></a>
    </li>
    @endforeach
    @endif
    @if(!empty($filters['price-range']))
    @php
    $display = 1;
    @endphp
    <li>
        <span>{{displayPrice($filters['priceFilterMinValue']) . ' - ' . displayPrice($filters['priceFilterMaxValue'])}}</span>
        <a href="javascript:;" class="remove" onclick="removePriceFilter()"><i class="fas fa-times"></i></a>
    </li>
    @endif
    @if(!empty($filters['prod-conditions']))
    @php
    $conditions = getProductConditions(); $display = 1;
    @endphp
    @foreach($filters['prod-conditions'] as $condition)
    <li>
        <span>{{$conditions[$condition]}}</span>
        <a href="javascript:;" class="remove" onclick="removeFiltersByTags('prod-conditions', '{{$condition}}')"><i class="fas fa-times"></i></a>
    </li>
    @endforeach
    @endif
    @if($display == 1)
    <li><a class="link" href="javascript:;" onclick="clearFilters()">{{__("LNK_CLEAR")}}</a></li>
    @endif
</ul>