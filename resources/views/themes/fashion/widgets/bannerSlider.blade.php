<section class="yk-bannerSlider pb-4" data-gjs-draggable="div.js-body">
    <bannerslider compid="{{ $cid ?? '' }}" class="yk-container">
        <div class="row justify-content-center" >
          @if(!empty($collections) && count($collections)>0)
          @php $i = 0; @endphp
          @foreach($collections as $k=>$collection)
          <div class="col text-center"><img data-yk="" alt="" data-aspect-ratio="4:1" src="{{$collection->desktop ?? ''}}"></div>
          @php
              if ($fromEditor) {
                  break;
              }
              $i++;
          @endphp
          @endforeach
          @else
          <div class="col text-center"><img data-yk="" alt="" src="{{ noImage('4_1/2000x500.png') }}" data-aspect-ratio="4:1" ></div>
          @endif
        </div>
        @if(!empty($fromEditor) && $fromEditor==true && !empty($collections) && count($collections)>1)
            <div class="text-center p-5"> + {{ count($collections) - 1 }} {{__("LBL_MORE_BANNER_IMAGES")}}</div>
        @endif  
    </bannerslider>
</section>
