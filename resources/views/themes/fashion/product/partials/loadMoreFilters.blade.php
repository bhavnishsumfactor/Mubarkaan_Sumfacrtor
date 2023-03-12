@php
$arrangeData = [];
$x= 0;

foreach($records as $key => $record){
$firstAlfa = strtolower(substr($key, 0, 1));
if (is_numeric($firstAlfa)) {
$arrangeData['#'][$x]['label'] = $key;
$arrangeData['#'][$x]['value'] = $record;
} else {
$arrangeData[$firstAlfa][$x]['label'] = $key;
$arrangeData[$firstAlfa][$x]['value'] = $record;
}
$range = range('A', 'Z');
array_unshift($range, "#");
$x++;
}
@endphp
<div class="modal-dialog modal-dialog-centered modal-lg" data-yk="" role="yk-document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ $type }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="filter-directory">
                <div class="filter-directory_bar">
                    <input class="form-control filter-directory_search_input" type="text" placeholder="Search" onkeyup="autoKeywordSearch(this.value)">
                    <ul class="filter-directory_indices bfilter-js">
                        @foreach( $range as $elements)
                        @if ($elements == "#")
                        <li data-item="Empty"><a href="{{$elements}}Empty">{{$elements}}</a></li>
                        @else
                        <li data-item="{{$elements}}" @if(empty($arrangeData[strtolower($elements)]))class="filter-directory_disabled" @endif><a href="#{{$elements}}">{{$elements}}</a></li>
                        @endif
                        @endforeach
                    </ul>
                </div>
                <div>
                    <form id="YK-morefilters">
                        <ul class="filter-directory_list  @if(count($colors) > 0 ){{'list-colors'}}@endif" id="YKbrandfiltersListing">
                            @foreach($arrangeData as $key => $arrangeRecords)
                            <li class="filter-directory_list_title {{$key}}" data-item="{{$key}}" id="{{$key}}" style="">{{strtoupper($key)}}</li>
                            @foreach($arrangeRecords as $arrangeRecord)
                            @php $dataKey = ($key == "#") ? "empty" : $key; @endphp
                            <li class="brandList-js b-{{$dataKey}}" data-caption="{{$dataKey}}" style="">
                                <label class="checkbox">
                                    <input name="@if($type != 'brands')options[{{$type}}][{{$arrangeRecord['value']}}]@else brands[{{$arrangeRecord['value']}}]@endif" @if(!empty($filters) && in_array($arrangeRecord['value'], $filters)){{'checked'}}@endif onclick="filterProducts(this, true, '', '{{$type}}')" value="{{$arrangeRecord['value']}}" data-title="{{$arrangeRecord['label']}}" data-id="{{$searchedId}}" data-type="{{$type}}" type="checkbox">
                                    @if(count($colors) > 0 )
                                    <span class="color-display" style="background-color:{{($colors[$arrangeRecord['label']])??$arrangeRecord['label']}};"></span>
                                    @endif
                                    <span class="lb-txt">{{$arrangeRecord['label']}} </span>
                                </label>
                            </li>
                            @endforeach
                            @endforeach
                        </ul>
                    </form>
                </div>
            </div>
        </div>
        <script >
            (function() {
                function scrollHorizontally(e) {
                    e = window.event || e;
                    var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
                    document.getElementById('YKbrandfiltersListing').scrollLeft -= (delta * 40); // Multiplied by 40
                    e.preventDefault();
                }
                if (document.getElementById('YKbrandfiltersListing').addEventListener) {
                    // IE9, Chrome, Safari, Opera
                    document.getElementById('YKbrandfiltersListing').addEventListener('mousewheel', scrollHorizontally, false);
                    // Firefox
                    document.getElementById('YKbrandfiltersListing').addEventListener('DOMMouseScroll', scrollHorizontally, false);
                } else {
                    // IE 6/7/8
                    document.getElementById('YKrandfiltersListing').attachEvent('onmousewheel', scrollHorizontally);
                }
            })();
        </script>