<section class="section collection-banner yk-promotionalBannerCollection2" data-gjs-draggable="div.js-body">
        <div class="container yk-container yk-images" compid="{{ $cid ?? '' }}">
            <div class="banners">
                @php
                    $noimage = noImage('4_3/800x600.png');
                @endphp
                @if(isset($collections))
                @foreach($collections as $collection)
                @php
                $image = $collection['cropped'];
                $webp = $collection['webp'];
                    if(empty($collection['cropped'])){
                        $image = $noimage;
                        $webp = $noimage;
                    }
                @endphp
                
                <div class="banner">
                    <a href="{{$collection['link'] ?? 'javascript:;'}}" target="{{$collection['target'] ?? '_self'}}">
                        <picture class="banner_img">
                            <source  data-aspect-ratio="4:3" type="image/webp" srcset="{{$webp}}">
                            <img  data-aspect-ratio="4:3" src="{{$image}}" data-yk="" alt="">
                        </picture>
                    </a>
                </div>
                @endforeach
                @endif
                {{--<div class="banner"> 
                    <a href="{{$collections[2]['link'] ?? 'javascript:;'}}" target="{{$collections[2]['target'] ?? '_self'}}">
                        <picture class="banner_img">
                            <source  data-aspect-ratio="4:3" type="image/webp" srcset="{{$image2}}">
                            <img  data-aspect-ratio="4:3" src="{{$image2}}" data-yk="" alt="">
                        </picture>
                    </a>
                </div>--}}
            </div>
        </div>
    </section>