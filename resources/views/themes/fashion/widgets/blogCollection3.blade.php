@if($innerHtml==false)
<section class="section collection-blog-3 yk-blogCollection3" data-gjs-draggable="div.js-body">
    <div class="yk-container" compid="{{ $cid ?? '' }}">
@endif
@php
    $dummyPostCount = ($frontend === true) ? 2 : 1;
@endphp
    <div class="blog-slider yk-blogposts" >
        <blogcollection3> 
                @if(!empty($collections) && count($collections) > 0)               
                    @php $i = 0; 
                        $j=0;
                    
                    @endphp
                    <div class="blog">
                        <div class="blog-image">
                            <div class="blog-slider-for">
                                @foreach($collections as $collection)

                                
                                    @php

                                  
                                        if($frontend === false && $i > 0) {
                                            break;
                                        }
                                        $i++;
                                       
                                        $webImage = url('yokart/image/blogImage/'.$collection->post_id.'/0/952-714-webp?t=' .strtotime($collection->afile_updated_at));
                                        $image = url('yokart/image/blogImage/'.$collection->post_id.'/0/952-714?t=' .strtotime($collection->afile_updated_at));
                                        if (empty($collection->afile_id)) {
                                            $image = $webImage = noImage("4_3/1000x750.png");
                                        }
                                    @endphp    
                                        <a href="{{createUrl($collection->urlRewrite)}}">
                                            <picture class="blog_img">
                                                <source  data-aspect-ratio="4:3" type="image/webp" srcset="{{$webImage}}">
                                                <img data-yk=""  data-aspect-ratio="4:3" src="{{$image}}" alt="{{displayImageAttr($collection->afile_name, $collection->afile_attribute_alt)}}" title="{{displayImageAttr($collection->afile_name, $collection->afile_attribute_title)}}">
                                            </picture>
                                        </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="blog-content">
                            <div class="blog-slider-nav">
                                @foreach($collections as $collection)
                                    @php
                                        if($frontend === false && $j > 0) {
                                            break;
                                        }
                                        $j++;
                                    @endphp    
                                        <figcaption> 
                                            <h5 class="from">{{__("LBL_BY")}} {{$collection->post_author_name}} {{__("LBL_ON")}} <span>{{getConvertedDateTime($collection->post_published_on, false)}}</span></h5>
                                            <h4><a href="{{createUrl($collection->urlRewrite)}}">{{$collection->post_title}}</a></h4>
                                            <p>{{Str::limit($collection->content->bpc_short_description, 150)}}</p>
                                        </figcaption>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                        <div class="blog">
                            <div class="blog-image">
                                <div class="blog-slider-for">
                                    @for($i = 0; $i < $dummyPostCount; $i++)
                                        <a href="javascript:;">
                                            <picture class="blog_img">
                                                <source  data-aspect-ratio="4:3" type="image/webp" srcset="{{ noImage('4_3/1000x750.png') }}">
                                                <img  data-aspect-ratio="4:3" src="{{ noImage('4_3/1000x750.png') }}" data-yk="" alt="">
                                            </picture>
                                        </a>
                                    @endfor
                                </div>
                            </div>
                            <div class="blog-content">
                                <div class="blog-slider-nav">
                                    @for($i = 0; $i < $dummyPostCount; $i++)
                                        <figcaption>
                                            <h5 class="from">{{__("LBL_BY_AUTHOR_ON")}} <span>{{__("LBL_DUMMY_DATE")}}</span></h5>
                                            <h4><a href="javascript:;">{{__("LBL_DUMMY_BLOG_TITLE")}}</a></h4>
                                            <p>{{__("LBL_DUMMY_BLOG_CONTENT")}}...</p>
                                        </figcaption>
                                    @endfor
                                </div>
                            </div>
                        </div>
                @endif  
            @if($frontend === false && !empty($collections) && $collections->count() > 1)
                <div class="text-center p-5"> + {{ $collections->count() - 1 }} {{__("LBL_MORE_BLOGS")}}</div>
            @endif  
        </blogcollection3>
    </div>
@if($innerHtml==false)
    </div>
</section>
@endif