<ul class="list-vertical">
    @php $b= 0; @endphp
    @foreach($brandRecords['list'] as $bardKey => $brand)
    @if(!empty($brand))
    <li style="@if($b > 5){{'display:none'}} @endif">
        <label class="checkbox">
            <input data-field-caption="" type="checkbox" name="brands[{{$bardKey}}]" value="{{$bardKey}}" onclick="filterProducts()" @if(!empty($selectedFilters) && in_array($bardKey, $selectedFilters)){{'checked'}}@endif> <i class="input-helper"></i> {{$brand}} </label>
    </li>

    @php $b++; @endphp
    @endif
    @endforeach
</ul>
@if($brandRecords['loadMore'] == 'true')
<div class="loadmore">
    <a class="link" href="javascript:;" onclick="loadFilters('brands')">{{ __('LNK_LOAD_MORE') }}</a>
</div>
@endif