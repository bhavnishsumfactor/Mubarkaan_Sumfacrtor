@if($innerHtml==false)
<section class="section collection-categories-4 yk-categoryCollection4" data-gjs-draggable="div.js-body">
    <div class="container yk-container" compid="{{ $cid ?? '' }}">
        <div class="section-content">
            <h2 class="yk-title">{{__("LBL_DUMMY_CATEGORY_COLLECTION4_TITLE")}}</h2>
        </div>
@endif
@php
    $totalCount = 6;
@endphp
        <categoryCollection4>
            <div class="categories yk-categories">
                @php
                    $recordCount = 0;
                @endphp
                @if(!empty($collections) && count($collections)>0)
                    @foreach($collections as $k=>$collection)
                    @php 
                   
                    $size = '223-223';
                        if ($recordCount == 0 || $recordCount == 5) {
                            $size = '461-461';
                        }
                   
                            $image = url('yokart/image/categoryCollection4/'.$collection->collection_id.'/0/'.$size.'?t=' .strtotime($collection->afile_updated_at));
                            $webp = url('yokart/image/categoryCollection4/'.$collection->collection_id.'/0/'.$size.'-webp?t=' .strtotime($collection->afile_updated_at));
                            if (empty($collection->afile_id)) {
                                $image = $webp = noImage("1_1/800x800.png");
                            }
                
                         @endphp       
                        <div class="categories-item">                       
                            <a class="categories_link" href="{{createUrl($collection->urlRewrite)}}">
                                <picture class="categories_img">
                                    <source  data-aspect-ratio="1:1" type="image/webp" srcset="{{$webp}}">
                                    <img  data-aspect-ratio="1:1" src="{{$image}}" alt="">
                                </picture>
                                <div class="categories_detail">
                                    <div class="categories_tittle">{{$collection->cat_name}}</div>
                                </div>
                            </a>
                        </div>
                    @php $recordCount++; @endphp
                    @endforeach
                @endif
                @for ($i = 0; $i < ($totalCount - $recordCount); $i++)
                    <div class="categories-item">
                        <a class="categories_link" href="javascript:;">
                            <picture class="categories_img">
                                <source  data-aspect-ratio="1:1" type="image/webp" srcset="{{ noImage('1_1/800x800.png') }}">
                                <img  data-aspect-ratio="1:1" src="{{ noImage('1_1/800x800.png') }}" data-yk="" alt="">
                            </picture>
                            <div class="categories_detail">
                                <div class="categories_tittle">{{__("LBL_CATEGORY")}}</div>
                            </div>
                        </a>
                    </div>                        
                @endfor 
            </div>
        </categoryCollection4> 
@if($innerHtml==false)   
    </div>
</section>
@endif