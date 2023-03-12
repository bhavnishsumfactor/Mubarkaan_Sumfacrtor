<ul class="list-vertical  @if($key  == 1){{'list-colors'}}@endif">
    @php $opt = 0;
    $type = $option['name'];
    @endphp
    @foreach($option['data'] as $optionKey => $op)

    <li style="@if($opt > 5){{'display:none'}} @endif">
        <label class="checkbox">
            <input data-field-caption="" type="checkbox" name="options[{{$option['name']}}][{{$op['opn_id']}}]" @if(!empty($selectedFilters) && in_array($op['opn_id'], $selectedFilters)){{'checked'}}@endif id="option-{{$op['opn_value']}}" value="{{$op['opn_id']}}" onclick="filterProducts('','', '', '{{$type}}')">
            @if($key == 1)
            <span class="color-display" style="background-color:{{($op['opn_color_code']) ?? $op['opn_value']}};"></span>
            @endif
            {{$op['opn_value']}}</label>

        </label>
    </li>
    @php $opt++; @endphp

    @endforeach
</ul>

@if(6 < $option['total']) @php $type=$option['name']; @endphp <div class="loadmore">
    <a class="link" href="javascript:;" onclick="loadFilters('options', '{{$key}}', '{{$type}}')">{{ __('LNK_LOAD_MORE') }}</a>
    </div>
    @endif