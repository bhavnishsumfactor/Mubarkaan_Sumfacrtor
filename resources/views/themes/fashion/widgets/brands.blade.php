@if($innerHtml==false)
<section class="section yk-brands" compid="{{ $cid ?? '' }}" data-gjs-draggable="div.js-body">
    <div class="container yk-container" compid="{{ $cid ?? '' }}">
        <div class="section-content">
            <h2 class="yk-title">{{__("LBL_DUMMY_BRAND_COLLECTION_TITLE")}}</h2>
        </div>
@endif
        <brands compid="{{ $cid ?? '' }}">
            <div class="brands js-brands yk-brandimages">
                @if(!empty($brands) && count($brands)>0)
                @php $i = 0; @endphp
                @foreach($brands as $brand)
                    <div class="brand">


                        <a class=" lift text-center" href="{{createUrl($brand->urlRewrite)}}">
                            <img data-yk="" class="brand-img" data-aspect-ratio="{{ imageConfig("BRAND.ASPECTRATIO") }}" src="{{ !empty($brand->afile_id) ? url('/yokart/image/'.$brand->afile_id.'/160-40-web?t='.strtotime($brand->brand_updated_at)) : '' }}" alt="{{notEmpty($brand->afile_attribute_alt) ?? ''}}" title="{{notEmpty($brand->afile_attribute_title) ?? ''}}">
                        </a>
                    </div>
                @php
                    if ($fromEditor && $i==4) {
                        break;
                    }
                    $i++;
                @endphp
                @endforeach
                @else
                    @for ($i = 0; $i < 5; $i++)
                    <div class="brand"><a class=" lift text-center" href="javascript:;"><img data-yk="" alt="" class="brand-img" data-aspect-ratio="{{ imageConfig("BRAND.ASPECTRATIO") }}" src='{{ noImage(imageConfig("BRAND.DUMMY")) }}'></a></div>
                    @endfor
                @endif
            </div>
            @if(!empty($fromEditor) && $fromEditor==true && !empty($brands) && count($brands)>5)
                <div class="text-center p-5"> + {{ count($brands) - 5 }} {{__("LBL_MORE_BRANDS")}}</div>
            @endif  
        </brands>
        @if($innerHtml==false)
    </div>
</section>
@endif  
