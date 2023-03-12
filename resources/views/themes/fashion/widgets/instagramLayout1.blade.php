@php
$firstRow = !empty($collections['images'])? array_slice($collections['images'], 0, 2) : [];
$secondRow = !empty($collections['images'])? array_slice($collections['images'], 2, 4) : [];
@endphp
@if($innerHtml==false)
<section class="yk-instagramLayout1 collection-insta-1" data-gjs-draggable="div.js-body">
  <instagramlayout1>
@endif
    <div class="insta-container">   
        <div class="insta-wrap">   
        @if(!empty($firstRow))
            @foreach($firstRow as $k=>$media)
                @php
                if($media['media_type'] == 'VIDEO'){
                    $media['media_url'] = $media['thumbnail_url'];
                }
                @endphp      
                <a class="insta" href="{{$media['permalink']}}" target="_blank"><img data-yk="" alt="" data-aspect-ratio="1:1" src="{{$media['media_url']}}"></a>         
            @endforeach
        @endif
        @if(count($firstRow) < 2)
            @for($img = 0; $img < (2 - count($firstRow)); $img++)          
                <a class="insta" href="javascript:;" target="_blank"><img data-yk="" alt="" data-aspect-ratio="1:1" src="{{ noImage('1_1/400x400.png') }}"></a>        
            @endfor
        @endif
        </div>
        <div class="insta-profile">
            <div class="handle">  
                <h6 class="yk-title">{{__("LBL_FOLLOW_US_ON_INSTAGRAM")}}</h6>         
                <a href="{{$collections['link'] ?? '#'}}" target="_blank" class="yk-link">@if(!empty($collections['handle'])) {{'@'.$collections['handle']}} @else {{__('LBL_DUMMY_INSTAGRAM_HANDLE')}} @endif</a>
            </div>
        </div>
        <div class="insta-wrap">   
        @if(!empty($secondRow))
            @foreach($secondRow as $k=>$media)
                @php
                if($media['media_type'] == 'VIDEO'){
                    $media['media_url'] = $media['thumbnail_url'];
                }
                @endphp
                <a class="insta" href="{{$media['permalink']}}" target="_blank"><img data-yk="" alt="" data-aspect-ratio="1:1" src="{{$media['media_url']}}"></a>         
            @endforeach
        @endif
        @if(count($secondRow) < 2)
            @for($img = 0; $img < (2 - count($secondRow)); $img++)        
                <a class="insta" href="javascript:;" target="_blank"><img data-yk="" alt="" data-aspect-ratio="1:1" src="{{ noImage('1_1/400x400.png') }}"></a>        
            @endfor
        @endif
        </div>
    </div>
@if($innerHtml==false)
  </instagramlayout1>
</section>
@endif
