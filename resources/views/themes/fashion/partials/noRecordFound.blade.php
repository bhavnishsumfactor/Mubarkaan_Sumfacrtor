<div class="no-data-found @if(empty($size) || $size == 'small') no-data-found--sm @elseif($size == 'medium') no-data-found--md @elseif($size == 'large') no-data-found--lg @elseif($size == 'xlarge') no-data-found--xl  @endif">
    <div class="img">
        <img data-yk="" src="{{ $headImage ?? asset('yokart/'.config('theme').'/media/retina/empty/no-found.svg') }}" alt="">
    </div>
    <div class="data">
        <h2>{{ $heading ?? __("LBL_NO_RESULTS_FOUND") }}</h2>
        @if(isset($text1))<p>{{$text1}} @if(isset($text2)) <br/>{{$text2}} @endif</p>@endif
        @if(!empty($anchor))
            {!! $anchor !!}
        @endif
    </div>
</div>

