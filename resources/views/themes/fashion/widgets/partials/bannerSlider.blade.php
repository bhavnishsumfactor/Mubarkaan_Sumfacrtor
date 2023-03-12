<div class="">
    <div class="hero-banner js-hero-banner" data-duration="{{$data['slider_duration']??'2'}}">
      @if(!empty($data['slides']) && count($data['slides'])>0)
    
        @foreach($data['slides'] as $k=>$slide)
      @if(count($slide->images) > 0)
          @if(!empty($slide->slide_url))
            <a href="{{$slide->slide_url}}" target="{{$slide->slide_target}}">
          @endif
      
              <picture>    
              
              @foreach($slide->images as $image)
                @php
                $slideImageUrl = url('yokart/image/'.$image->afile_id . '/2000-500-webp?t='.strtotime($image->afile_updated_at));
                $slideOrgImageUrl = url('yokart/image/'.$image->afile_id. '/2000-500?t='.strtotime($image->afile_updated_at));
                $slideRatio = "4:1";
                $slideMedia =  "media='(min-width: 1200px)'";
                 if($image->afile_device == 2){
                      $slideImageUrl = url('yokart/image/'.$image->afile_id.'/1200-800-webp?t='.strtotime($image->afile_updated_at));
                    $slideRatio = "3:2";
                    $slideMedia =  "media='(max-width: 1024px)'";

                 }elseif($image->afile_device == 3){
                  $slideImageUrl = url('yokart/image/'.$image->afile_id.'/800-600-webp?t='.strtotime($image->afile_updated_at));
                  $slideRatio = "4:3";
                  $slideMedia =  "media='(max-width: 767px)'";
                 }
                 @endphp

                <source data-aspect-ratio="{{$slideRatio}}" type="image/webp" srcset="{{$slideImageUrl}}" {!!$slideMedia!!} >
                @endforeach
             
                <img data-aspect-ratio="{{$slideRatio}}" src="{{$slideOrgImageUrl}}" data-yk="" alt="">             
              </picture>
          @if(!empty($slide->slide_url))
            </a>
          @endif
          @endif
        @endforeach
      @else
        @for($i = 0; $i < 1; $i++)
          <picture>    
            <source data-aspect-ratio="4:3" srcset="{{ noImage('4_3/800x600.png') }}" media="(max-width: 767px)">
            <source data-aspect-ratio="3:2" srcset="{{ noImage('3_2/1200x800.png') }}" media="(max-width: 1024px)">
            <source data-aspect-ratio="4:1" srcset="{{ noImage('4_1/2000x500.png') }}" media="(min-width: 1200px)">
            <img data-aspect-ratio="4:1" src="{{ noImage('4_1/2000x500.png') }}" data-yk="" alt="">             
          </picture>
        @endfor
      @endif
    </div>
</div>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="44px" height="44px" id="circle" fill="none" stroke="currentColor">
        <circle r="20" cy="22" cx="22" id="test">
    </symbol>
</svg>
