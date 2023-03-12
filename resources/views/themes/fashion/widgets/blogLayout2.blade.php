@if($innerHtml==false)
<section class="section yk-blogLayout2" data-gjs-draggable="div.js-body">
    <div class="container yk-container" compid="{{ $cid ?? '' }}">
        <div class="section-content">
            <h2 class="yk-title">{{__("LBL_DUMMY_BLOG_COLLECTION_TITLE")}}</h2>
            <div class="section-action"><a href="{{route('blogListing')}}" class="link-arrow yk-link">{{__("BTN_VIEW_ALL")}}</a></div>
        </div>
@endif
@php
    $totalCount = 2;
@endphp
        <blogcollectionlayout2>
            <div class="row site-blog site-blog-2 mt-5 yk-blogposts">
                @php
                    $recordCount = 0;
                @endphp
                @if(!empty($collections) && $collections->count() > 0)
                    @foreach($collections as $collection)

                    @php 
                    
                $image = url('yokart/image/blogImage/'.$collection->post_id.'/0/690-517-webp?t=' .strtotime($collection->afile_updated_at));
                if (empty($collection->afile_id)) {
                    $image = noImage("4_3/1000x750.png");
                }
                @endphp
                    <figure class="col-md-6 slide">
                        <div class="slide-figure"><a href="{{createUrl($collection->urlRewrite)}}"><img data-yk="" src="{{$image}}" alt="{{displayImageAttr($collection->afile_name, $collection->afile_attribute_alt)}}" title="{{displayImageAttr($collection->afile_name, $collection->afile_attribute_title)}}" data-aspect-ratio="4:3"></a></div>
                        <figcaption class="data">
 
                            <h4><a href="{{createUrl($collection->urlRewrite)}}">{{$collection->post_title}}</a></h4>
                            <p>{{Str::limit($collection->content->bpc_short_description, 150, $end='...')}}</p>
                            <h5 class="from">{{__("LBL_BY")}} {{$collection->post_author_name}} {{__("LBL_ON")}} <span> {{getConvertedDateTime($collection->post_published_on, false)}}</span></h5>
                        </figcaption>
                    </figure>
                    @php $recordCount++; @endphp
                    @endforeach
                @endif
                @for($i = 0; $i < ($totalCount - $recordCount); $i++)
                    <figure class="col-md-6 slide">
                        <div class="slide-figure"><a href="javascript:;"><img data-yk="" alt="" src="{{ noImage('4_3/1000x750.png') }}" data-aspect-ratio="4:3"></a></div>
                        <figcaption class="data">
                            <h4><a href="javascript:;">{{__("LBL_DUMMY_BLOG_TITLE")}}</a></h4>
                            <p>{{__("LBL_DUMMY_BLOG_CONTENT")}}...</p>
                            <h5 class="from">{{__("LBL_BY_AUTHOR_ON")}} <span> {{__("LBL_DUMMY_DATE")}}</span></h5>
                        </figcaption>
                    </figure>
                @endfor
            </div>
        </blogcollectionlayout2>
@if($innerHtml==false)
    </div>
</section>
@endif