@if($innerHtml==false)
<section class="yk-instagramCollection3 collection-insta-3" data-gjs-draggable="div.js-body">
    <instagramcollection3 class="yk-container" compid="{{ $cid ?? '' }}">
@endif    
    <div class="insta-wrap">
        @if(!empty($collections['images']))
            @foreach($collections['images'] as $k => $media)
                @php
                    if($media['media_type'] == 'VIDEO'){
                        $media['media_url'] = $media['thumbnail_url'];
                    }
                @endphp
                <a class="insta" href="{{$media['permalink']}}" target="_blank"> <img data-yk=""  data-aspect-ratio="1:1" src="{{$media['media_url']}}" alt=""></a>
            @endforeach
        @endif
        @php 
            $imgCount = !empty($collections['images']) ? count($collections['images']): 0;
        @endphp
        @if($imgCount < 6)
            @for($img = 0; $img < (6 - $imgCount); $img++)
                <a class="insta" href="javascript:;"><img data-yk=""  data-aspect-ratio="1:1" src="{{ noImage('1_1/400x400.png') }}" alt=""></a>
            @endfor
        @endif
    </div>
    <div class="insta-profile">
        <div class="handle">
            <span class="name yk-title">{{__("LBL_FOLLOW_US_ON_INSTAGRAM")}}</span>
            <a href="{{$collections['link'] ?? 'javascript:;'}}" target="_blank">@if(!empty($collections['handle'])) {{'@'.$collections['handle']}} @else {{__('LBL_DUMMY_INSTAGRAM_HANDLE')}} @endif</a>
        </div>
    </div>
@if($innerHtml==false)
    </instagramcollection3>
</section>
@endif