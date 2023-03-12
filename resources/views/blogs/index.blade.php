@php
$config = getConfigValues([
    'BUSINESS_NAME'
]);
@endphp
@extends('blogs.layout')
@section('metaTags')
  <meta property="og:type" content="blog" />
  <meta property="og:title" content="{{ $config['BUSINESS_NAME'] }}" />
  <meta property="og:site_name" content="{{ $config['BUSINESS_NAME'] }}" />
  <meta property="og:image" content="{{$logo}}" />
  <meta property="og:url" content="{{ route('blogListing') }}" />
  
  <meta name="twitter:card" content="home">
  <meta name="twitter:title" content="{{ $config['BUSINESS_NAME'] }}">
  <meta name="twitter:description" content="{{ $config['BUSINESS_NAME'] }}">
  <meta name="twitter:image:src" content="{{$logo}}">
@endsection
@section('content')
<div class="blog-wrapper">
    <div class="container">
        <section class="js-blog-slider blog-slider site--blog">
            @foreach($featuredBlogs as $featuredBlog)
                <figure class="slide__figure">
                    <div class="row no-gutters w-100">
                        <div class="col-xl-8 col-md-7">
                            <div class="slide-img"><a href="{{url('').'/'.$featuredBlog->record_slug}}"><div class="slide-img-wrap"><img data-yk="" src="{{url('yokart/image/blogImage/'.$featuredBlog->post_id.'/0/1000-750?t=' . strtotime($featuredBlog->post_updated_at))}}" alt="" data-aspect-ratio="4:3"></div></a></div>
                        </div>
                        <div class="col-xl-4 col-md-5">
                            <figcaption>
                                <div class="blog_slider_detail inner-block">
                                    <div class="blog-date">{{__("LBL_BY")}} <strong>{{$featuredBlog->post_author_name}}</strong> - <span>{{ getConvertedDateTime($featuredBlog->post_created_at) }}</span></div>
                                    <h3>{{$featuredBlog->post_title}}</h3>
                                    <p>{{Str::limit($featuredBlog->bpc_short_description, 200)}}</p>
                                    <a href="{{url('').'/'.$featuredBlog->record_slug}}" class="btn btn-blog btn-outline-dark btn-wide">{{__("BTN_READ_MORE")}}</a>
                                </div>
                            </figcaption>   
                        </div>
                    </div>
                </figure>
            @endforeach
        </section>
    </div>
</div>
@if(empty($_GET['query']))
    @foreach($latestBlogs as $blogKey => $latestBlog)
        <section class="section blog--block">
            <div class="container">
                @if($blogKey == 1)
                    <div class="row align-items-center">
                        <div class="col-xl-8 col-md-7">
                            @include('blogs.image')
                        </div>  
                        <div class="col-xl-3 col-md-5 offset-xl-1">
                            @include('blogs.summary')
                        </div> 
                    </div> 
                @else
                    <div class="row align-items-center flex-column-reverse flex-md-row">
                        <div class="col-xl-3 col-md-5">
                            @include('blogs.summary')
                        </div>  
                        <div class="col-xl-8 col-md-7 offset-xl-1">
                            @include('blogs.image')
                        </div> 
                    </div> 
                @endif
            </div>
        </section>
    @endforeach
@endif
<section class="section blog--content pt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md blog-content"> 
                @if($blogs->count() == 0)  
                <div class="no-data-found  no-data-found--md ">
                    <div class="img">
                        <img data-yk="" src="{{asset('blogs/images/retina/no-blogs.svg')}}" alt="">
                    </div>
                    <div class="data">
                        <h2>{{__("LBL_NO_BLOGS")}}</h2>
                        <p>{{__("LBL_THERE_ARE_NO_BLOGS_TO_SHOW")}} </p>
                    </div>
                </div>    
                @else         
                    @foreach($blogs as $blog)
                        <div class="row d-flex mb-4">
                            <div class="col-md-6">
                                <div class="inner-media">
                                <a href="{{url('').'/'.$blog->record_slug}}" > <img data-yk="" src="@if(!empty($blog->afile_id)) {{ url('yokart/image/blogImage/'.$blog->post_id.'/0/1000-750?t=' . strtotime($blog->post_updated_at)) }} @else {{noImage('4_3/1000x750.png')}} @endif" alt="{{displayImageAttr($blog->afile_name, $blog->afile_attribute_alt)}}" title="{{displayImageAttr($blog->afile_name, $blog->afile_attribute_title)}}" data-aspect-ratio="4:3"></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inner-block">
                                    <h3><a href="{{url('').'/'.$blog->record_slug}}" >{{$blog->post_title}}</a></h3>
                                    <p>{{Str::limit($blog->bpc_short_description, 200)}}</p>
                                    <div class="blog-date">{{__("LBL_BY")}} <strong>{{$blog->post_author_name}}</strong> - <span>{{ getConvertedDateTime($blog->post_created_at) }}</span></div>
                                    <a href="{{url('').'/'.$blog->record_slug}}" class="btn btn-blog btn-wide">Read more</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <sidebar class="col offset-lg-1 flex-grow-0 blog-sidebar YK-blogSidebarContent" id="blog-sidebar"></sidebar>                
        </div>  
        <div class="pagination-wrapper">        
            @if(count($blogs) > 0)    
                {{ $blogs->links('partials.pagination') }}
            @endif            
        </div>
    </div>
</section>
@include('blogs.subscription')
@section('scripts')
    <script>
        let query= "{{ app('request')->input('query') }}";
        $('.js-blog-slider').slick({
            dots: false,
            arrows: true,
            infinite: false,
            speed: 300,
            default: true,
            responsive: [{
                    breakpoint: 767,
                    settings: {
                        arrows: false,
                    }
                },
                {
                    breakpoint: 1199,
                    settings: {
                        arrows: false,
                    }
                }
            ]
        });
        blogSidebarContent(query);
    </script>
@endsection
@endsection

