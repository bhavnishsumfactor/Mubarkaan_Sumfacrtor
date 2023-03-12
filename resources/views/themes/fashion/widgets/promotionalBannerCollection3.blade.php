<section class="collection-banner yk-promotionalBannerCollection3" data-gjs-draggable="div.js-body">        
        <div class="banners yk-container" compid="{{ $cid ?? '' }}">
            @php
                $banner = empty($collections['cropped']) ? noImage('4_1/2000x500.png') : $collections['cropped'];
                $bannerWebp = empty($collections['cropped']) ? noImage('4_1/2000x500.png') : $collections['webp'];
            @endphp
            <div class="banner">
                <a href="{{$collections['link'] ?? 'javascript:;'}}" target="{{$collections['target'] ?? '_self'}}">
                    <picture class="banner_img">
                        <source  data-aspect-ratio="4:1" type="image/webp" srcset="{{$bannerWebp}}">
                        <img  data-aspect-ratio="4:1" src="{{$banner}}" data-yk="" alt="">
                    </picture>
                </a>
            </div>               
        </div>        
</section>