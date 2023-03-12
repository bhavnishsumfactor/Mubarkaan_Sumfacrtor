@if(!empty($pageLoad) && $pageLoad == 1)
    <logo-shortcode>
        <img data-yk="" data-gjs-type="default" class="footer-logo" src="{{url('yokart/'.config('theme').'/media/logos/logo-default.png')}}" alt="" data-ratio="{{$ratio ?? '16:9'}}">
    </logo-shortcode>
@else
<div class="yk-logo logo" compid="{{ $cid ?? '' }}">
    <a href="{{url('') . '/'}}" compid="{{ $cid ?? '' }}" class="yk-container">
        <logo-shortcode>
            <img data-yk="" data-gjs-type="default" class="footer-logo" src="{{url('yokart/'.config('theme').'/media/logos/logo-default.png')}}" alt="" data-ratio="{{$ratio ?? '16:9'}}">
        </logo-shortcode>
    </a>
</div>
@endif