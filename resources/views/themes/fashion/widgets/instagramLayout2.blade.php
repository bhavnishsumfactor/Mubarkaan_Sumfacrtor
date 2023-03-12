@if($innerHtml==false)
<section class="yk-instagramLayout2 collection-insta-2 position-relative" data-gjs-draggable="div.js-body">
    <div class="yk-container container" compid="{{ $cid ?? '' }}">
      <instagramlayout2>
@endif
    <div class="insta-wrap">   
        @if(!empty($collections['images']))
            @foreach($collections['images'] as $k => $media)
                @php
                if($media['media_type'] == 'VIDEO'){
                    $media['media_url'] = $media['thumbnail_url'];
                }
                @endphp        
                <a class="insta" href="{{$media['permalink']}}" target="_blank"><img data-yk="" alt="" data-aspect-ratio="1:1" src="{{$media['media_url']}}"></a>         
            @endforeach
        @endif
        @php 
            $imgCount = !empty($collections['images']) ? count($collections['images']): 0;
        @endphp
        @if($imgCount < 16)
            @for($img = 0; $img < (16 - $imgCount); $img++)            
                <a class="insta" href="javascript:;" target="_blank"><img data-yk="" alt="" data-aspect-ratio="1:1" src="{{ noImage('1_1/400x400.png') }}"></a>        
            @endfor
        @endif
    </div>
    <div class="insta-profile">
        <div class="handle"> 
            <i class="fab fa-instagram h3 mb-2"></i>              
            <h2 class="mb-md-5 yk-title">@if(!empty($collections['handle'])) {{'@'.$collections['handle']}} @else {{__('LBL_DUMMY_INSTAGRAM_HANDLE')}} @endif</h2>                
            <a href="{{$collections['link'] ?? '#'}}" target="_blank" class="link yk-link">{{__('LNK_GO_TO_INSTAGRAM')}}</a>
        </div>
    </div>
@if($innerHtml==false)
      </instagramlayout2>
    </div>
</section>
@endif
