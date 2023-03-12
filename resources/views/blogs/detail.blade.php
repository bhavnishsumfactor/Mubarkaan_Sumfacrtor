@extends('blogs.layout')
@section('content')
@section('metaTags')
<!-- OG Blog Facebook Meta [ -->
<meta property="og:type" content="blog" />
<meta property="og:title" content="{{$blog->post_title}}" />
<meta property="og:site_name" content="Yo!Kart" />
<meta property="og:image" content="{{url('yokart/image/blogImage/'.$blog->post_id.'/0/1000-750?t=' . strtotime($blog->post_updated_at))}}" />
<meta property="og:url" content="{{request()->url()}}" />
<meta property="og:description" content="{{\Str::words( strip_tags($blog->content->bpc_description), 25, '...')}}" />
<!-- ]   -->

<!--Here is the Twitter Card code for this blog  -->
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{$blog->post_title}}">
<meta name="twitter:description" content="{{\Str::words( strip_tags($blog->content->bpc_description), 25, '...')}}">
<meta name="twitter:image:src" content="{{url('yokart/image/blogImage/'.$blog->post_id.'/0/1000-750?t=' . strtotime($blog->post_updated_at))}}">
<!-- End Here is the Twitter Card code for this product  -->
@endsection
<div class="main blog--listing">
<section class="section">
      <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="inner-block text-center">                
                    <div class="blog-date">{{__("LBL_BY")}} <strong>{{$blog->post_author_name}}</strong> - <span>{{ getConvertedDateTime($blog->post_created_at) }}</span></div>
                    <h1>{{$blog->post_title}}</h1>
                </div>
            </div>
        </div>
        @if(!empty($blog->afile_id)) 
        <div class="section pt-5">
            <div class="blog_listing_media text-center">
                <img src="{{url('yokart/image/blogImage/'.$blog->post_id.'/0/1000-750?t=' . strtotime($blog->post_updated_at))}}" alt="{{displayImageAttr($blog->afile_name, $blog->afile_attribute_alt)}}" title="{{displayImageAttr($blog->afile_name, $blog->afile_attribute_title)}}" data-aspect-ratio="4:3" data-yk="">
            </div>
        </div>
        @endif
       
        <div class="row">
            <div class="col-xl-2">
                    <div class="blog-social full--fill">
                    @php 
                        $sharethisNetworks = shareThis();
                    @endphp
                    @if(!empty($sharethisNetworks))
                        @foreach($sharethisNetworks as $key => $network)
                            @php
                                $explodedNetwork = explode('_', $key);
                                $type = strtolower(end($explodedNetwork));
                                $icon = 'fab fa-' . $type;
                                if ($type == 'facebook') {
                                    $icon = 'fab fa-facebook-f';
                                }elseif ($type == 'wechat') {
                                    $icon = 'fab fa-weixin';
                                }elseif ($type == 'email') {
                                    $icon = 'far fa-envelope';
                                }                                                   
                            @endphp                                
                            <a class="{{$type}}" href="javascript:;" data-title="{{$blog->post_title}}" data-network="{{$type}}" data-url="{{request()->url()}}">
                                <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('blogs/images/retina/sprite.svg#'.$type)}}"></use></svg></i>
                            </a>
                        @endforeach
                    @endif                        
                    </div>
                </div>
                <div class="col-xl-10">
                    <div class="cms">
                    {!!$blog->content->bpc_description!!}
                    </div> 
                </div>
            </div>
        </section>
        @if($blog->post_comment_opened == 1)
         <section class="section bg-gray comment--section">
           <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-md-10">
                        <div class="form-inner text-center">
                      
                            @guest
                             <a href="{{route('login')}}"  class="btn btn-blog btn-dark btn-wide"> {{__('BTN_LOGIN_TO_POST_COMMENT')}}</a>
                           @else
                           <h3>{{__("LBL_LEAVE_A_COMMENT")}}</h3>
                            <form   method="post" action="" id="YK-comment_form" accept-charset="UTF-8" class="comment-form "> 
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                     <textarea rows="7" name="comment" id="comment-body"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                     <input type="hidden" value="{{$blog->post_id}}" name="post_id">
                                            <button type="button" onclick="saveComments()" class="btn btn-blog btn-dark btn-wide">{{__('BTN_POST_COMMENT')}}</button>
                                            <div class="YK-comment_success">
                                            </div>
                                    </div>
                                    <p>{{__("LBL_ALL_COMMENTS_ARE_CHECKED")}}</p>
                                </div>
                            </form>
                            @endguest
                        </div>
                        <div class="comment-list-wrapper">
                            @php  $checkLogoExists = fileExists('frontendLogo', 0, 0); @endphp
                            <ul class="list-comments">
                                @foreach($postComments as $k=>$postComment)
                                @php  $checkFileExists = fileExists('userProfileImage', $postComment->bpcomment_user_id, 0); 
                                    
                                @endphp
                                <li class="comment @if($k==0) first @elseif($k==(count($postComments)-1)) last mb-0 @endif">
                                        <div class="comment-head">
                                            <div class="avatar">
                                                @if($checkFileExists)
                                                    <img src="{{getFileUrl('userProfileImage', $postComment->bpcomment_user_id, 0, '32-32')}}" data-yk="" alt="">
                                                @else
                                                    <img src="{{url('yokart/'.config('theme')).'/media/retina/admin-profile-user-icon.svg'}}" data-yk="" alt="">
                                                @endif
                                            </div>
                                            <div class="user__detail">
                                               <span class="auther"> {{__('LBL_BY')}}  {{Str::title($postComment->user_name)}} {{__('LBL_ON')}}</span> <time>{{ getConvertedDateTime($postComment->bpcomment_added_on) }}</time>
                                                
                                            </div>
                                            
                                        </div>

                                    <div class="comment-content">
                                    <p>{!! nl2br($postComment->bpcomment_content) !!}</p>
                                    @if(isset($replies[$postComment->bptc_bpcomment_id]) && count($replies[$postComment->bptc_bpcomment_id])>0)
                                    <ul>
                                        @foreach($replies[$postComment->bptc_bpcomment_id] as $k1=>$replay)
                                        <li>
                                            <div class="comment-head">
                                                    <div class="avatar">
                                                        @if($checkLogoExists)
                                                            <img src="{{getFileUrl('frontendLogo', 0, 0, '32-32')}}" data-yk="" alt="">
                                                        @else
                                                            <img src="{{url('yokart/'.config('theme')).'/media/retina/user-icon.svg'}}" data-yk="" alt="">
                                                        @endif
                                                    </div>
                                                    <div class="user__detail">
                                                    <span class="auther"> {{__('LBL_BY')}} {{Str::title($businessName)}} {{__('LBL_ON')}}</span> <time>{{ getConvertedDateTime($replay->bpcomment_added_on) }}</time>
                                                    </div>
                                            </div>
                                            <div class="comment-content">
                                                <p>{!! nl2br($replay->bpcomment_content) !!}</p>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                                @endforeach

                            </ul>
                            
                        </div>
                    </div>
                </div>
           </div>
        </section>
        @endif
                @if(count($relatedBlogs) > 0)
        <section class="section related--blog">
            <div class="container">
                <div class="box">
                    <h3>{{__("LBL_RELATED_BLOGS")}}</h3>
                    <div class="box-item box-main-js">
                    @foreach($relatedBlogs as $relatedBlog)
                        <div class="slider-item">
                            <div class="box__inner">
                                <div class="box-head inner-block">
                                    <div class="box-media">
                                    <a href="{{url('').'/'.$relatedBlog->record_slug}}" ><img data-yk="" src="{{url('yokart/image/blogImage/'.$relatedBlog->post_id.'/0/1000-750')}}" alt="{{displayImageAttr($relatedBlog->afile_name, $relatedBlog->afile_attribute_alt)}}" title="{{displayImageAttr($relatedBlog->afile_name, $relatedBlog->afile_attribute_title)}}" data-aspect-ratio="4:3"></a>
                                    </div>
                                    <div class="blog-date">{{__('LBL_BY')}} <strong>{{$relatedBlog->post_author_name}}</strong> - <span>{{ getConvertedDateTime($blog->post_created_at) }}</span></div>
                                </div>
                                <div class="box-body">
                                   <h3><a href="{{url('').'/'.$relatedBlog->record_slug}}" >{{$relatedBlog->post_title}}</a></h3>
                                   <p>{{Str::limit($relatedBlog->bpc_short_description, 200)}}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    
                        
                    </div>
                   
                </div>
            </div>
        </section>
        @endif
     
  </div>
@section('scripts')
<script  src="https://ws.sharethis.com/button/buttons.js"></script>
<script  src="//platform-api.sharethis.com/js/sharethis.js"></script>

<script>

$('.box-main-js').slick({
    dots: false,
    infinite: false,
    speed: 400,
    slidesToShow: 3,
    slidesToScroll: 3,
    responsive: [{
            breakpoint: 767,
            settings: {
                slidesToShow: 1,
            }
        },
        {
            breakpoint: 1199,
            settings: {
                slidesToShow: 2,
            }
        }
    ]
});

</script>
@endsection
@endsection
