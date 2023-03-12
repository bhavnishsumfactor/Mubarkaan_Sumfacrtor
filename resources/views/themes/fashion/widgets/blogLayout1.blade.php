@if($innerHtml==false)
<section class="section yk-blogLayout1" data-gjs-draggable="div.js-body">
    <div class="container yk-container" compid="{{ $cid ?? '' }}">
        <div class="section-content">
            <h2 class="yk-title">{{__("LBL_DUMMY_BLOG_COLLECTION_TITLE")}}</h2>
            <div class="section-action"><a href="{{route('blogListing')}}" class="link-arrow yk-link">{{__("BTN_VIEW_ALL")}}</a></div>
        </div>
@endif
        <blogcollectionlayout1>
            <div class="row site-blog mt-5 yk-blogposts justify-content-center">
                @if(!empty($collections) && $collections->count() > 0)
                @foreach($collections as $collection)

                @php 
                $image = url('yokart/image/blogImage/'.$collection->post_id.'/0/450-337-webp?t=' .strtotime($collection->afile_updated_at));
                if (empty($collection->afile_id)) {
                    $image = noImage("4_3/1000x750.png");
                }
            
                @endphp
                <div  class="col-md-4">
                <figure  class="slide">

                    <div class="slide-figure"><a href="{{createUrl($collection->urlRewrite)}}">
                    <img data-yk="" src="{{$image}}" alt="{{displayImageAttr($collection->afile_name, $collection->afile_attribute_alt)}}" title="{{displayImageAttr($collection->afile_name, $collection->afile_attribute_title)}}" data-aspect-ratio="4:3"></a></div>
                    <figcaption>
                        <h4><a href="{{createUrl($collection->urlRewrite)}}">{{$collection->post_title}}</a></h4>
                        <h5 class="from">{{__("LBL_BY")}} {{$collection->post_author_name}} {{__("LBL_ON")}} <span>{{getConvertedDateTime($collection->post_published_on, false)}}</span></h5>
                        <p>{{Str::limit($collection->content->bpc_short_description, 150, $end='...')}}</p>
                    </figcaption>
                </figure></div>
                @endforeach
                @else
                    @for($i = 0; $i < 3; $i++)
                        <div  class="col-md-4">
                            <figure  class="slide">
                                <div class="slide-figure"><a href="javascript:;"><img data-yk="" alt="" src="{{ noImage('4_3/1000x750.png') }}" data-aspect-ratio="4:3"></a></div>
                                <figcaption>
                                    <h4><a href="javascript:;">{{__("LBL_DUMMY_BLOG_TITLE")}}</a></h4>
                                    <h5 class="from">{{__("LBL_BY_AUTHOR_ON")}} <span>{{__("LBL_DUMMY_DATE")}}</span></h5>
                                    <p>{{__("LBL_DUMMY_BLOG_CONTENT")}}...</p>
                                </figcaption>
                            </figure>
                        </div>
                    @endfor
                @endif
            </div>
        </blogcollectionlayout1>
@if($innerHtml==false)
    </div>
</section>
@endif