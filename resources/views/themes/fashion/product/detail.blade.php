@php
$config = getConfigValues([
    'PRODUCT_DETAIL_DISPLAY_WARRANTY',
    'PRODUCT_DETAIL_DISPLAY_RETURN',
    'COD_ENABLE',
    'BUSINESS_NAME'
]);
@endphp
@extends('layouts.full')
@section('content')
@php
$subRecordId = 0;
if($product->colorOptions->count() > 0){
$subRecordId = $product->colorOptions->first()->poption_id;
}
$sysCurrency = getSystemCurrency();
$avgRating = avgRating($product->prod_id);

@endphp
@section('metaTags')
<!-- OG Product Facebook Meta [ -->
<meta property="og:type" content="product" />
<meta property="og:title" content="{{$product->prod_name}}" />
<meta property="og:site_name" content="{{$config ['BUSINESS_NAME']}}" />
<meta property="og:image" content="{{url('yokart/image/productImages/'.$product->prod_id.'/'.$subRecordId.'/thumb?t=' . strtotime($product->prod_updated_on))}}" />
<meta property="og:url" content="{{request()->url()}}" />
<meta property="og:description" content="{{ \Str::words( strip_tags($product->prod_description), 25, '...') }}" />
<!-- ]   -->
<!--Here is the Twitter Card code for this product  -->
<meta name="twitter:card" content="product">
<meta name="twitter:title" content="{{$product->prod_name}}">
<meta name="twitter:description" content="{{ \Str::words( strip_tags($product->prod_description), 25, '...') }}">
<meta name="twitter:image:src" content="{{url('yokart/image/productImages/'.$product->prod_id.'/'.$subRecordId.'/thumb?t=' . strtotime($product->prod_updated_on))}}">
<!-- End Here is the Twitter Card code for this product  -->
@endsection
@section('schemaCodes')
<!-- Product Schema Code -->
<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "Product",
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "{{$avgRating['rating']}}",
            "reviewCount": "{{$avgRating['count']}}"
        },
        "description": "{{\Str::words($product->prod_description, 25, '...')}}",
        "name": "{{$product->prod_name}}",
        "image": "{{url('yokart/image/productImages/'.$product->prod_id.'/'.$subRecordId.'/thumb?t=' . strtotime($product->prod_updated_on))}}",
        "offers": {
            "@type": "Offer",
            "availability": "http://schema.org/InStock",
            "price": "{{$product->finalprice}}",
            "priceCurrency": "{{$sysCurrency->currency_code}}"
        }
    }
</script>
<!-- End Product Schema Code -->
@endsection
<div class="body" id="body" data-yk="" role="yk-main">
    <div class="bg-detail">
    
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="YK-image-slider product-gallery-section">

                        @include('themes.'.config('theme').'.product.partials.sliderImages')

                        </div>                  
                    </div><!--col-->
                    <div class="col-md-6">
                        <span class="YK-addTocartHtml">
                            @include('themes.'.config('theme').'.product.partials.addToCartSection')
                        </span>
                    </div><!--col-->
                <div>         
            </div>
        </section>
        @if($product->prod_type != 2)
        <section class="">            
                 <div class="list-services">
                          
                            <ul>
                                @if( $config['PRODUCT_DETAIL_DISPLAY_WARRANTY'] != 0)
                                <li>
                                    <div class="list-services__box">
                                        <i class="icon">
                                            <svg class="svg">
                                                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#warranty" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#warranty">
                                                </use>
                                            </svg>
                                        </i>
                                        <div class="detail">
                                            @php
                                            $warrantyAge = __("LBL_NO");
                                            if($product->pc_warranty_age != 0){
                                            $warrantyAge = convertDays($product->pc_warranty_age);
                                            }
                                            @endphp
                                            <h6>{{$warrantyAge . ' ' . __("LBL_X_WARRANTY")}}</h6>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            
                                @if( $config['PRODUCT_DETAIL_DISPLAY_RETURN'] != 0)
                                <li>
                                    <div class="list-services__box">
                                        <i class="icon"> <svg class="svg">
                                                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#return" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#return">
                                                </use>
                                            </svg></i>
                                        <div class="detail">
                                            @php
                                            $returnAge = __("LBL_NO_RETURN");
                                            if($product->pc_return_age != 0){
                                            $returnAge = __("LBL_RETURN_WITHIN") . ' ' . convertDays($product->pc_return_age);
                                            }
                                            @endphp
                                            <h6>{{$returnAge}}</h6>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($product->prod_cod_available == 1 && $config['COD_ENABLE'] == 1)
                                <li>
                                    <div class="list-services__box">
                                        <i class="icon"> <svg class="svg">
                                                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#cod" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#cod">
                                                </use>
                                            </svg></i>
                                        <div class="detail">
                                            <h6>{{__("LBL_COD_AVAILABLE")}}</h6>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($product->prod_self_pickup != 1)
                                <li>
                                    <div class="list-services__box">
                                        <i class="icon"> <svg class="svg">
                                                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#pick-up" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#pick-up">
                                                </use>
                                            </svg></i>
                                        <div class="detail">
                                            <h6>{{__("LBL_PICK_UP")}}</h6>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($product->prod_self_pickup != 2)
                                <li>
                                    <div class="list-services__box">
                                        <i class="icon"> <svg class="svg">

                                       
                                                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#shipping-icon" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#shipping-icon">
                                                </use>
                                            </svg></i>
                                        <div class="detail">
                                            <h6>{{__("LBL_SHIPPING")}}</h6>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>  
                            
                          
                    </div>
            
        </section>
        @endif     
        @if(!empty($buytogetherproducts) && count($buytogetherproducts)>0)
        <section class="section">
             <div class="container">                    
                        <div class="section-content section-content-center">
                            <h2>{{__("LBL_RECOMMENDED_PRODUCTS")}}</h2>
                        </div>
                   
                            
                             <ul class="js-fourColumn-slider collection-slider">
                                    @foreach($buytogetherproducts as $buytogether)
                                        <li class="item">
                                            @include('themes.'.config('theme').'.partials.productCard2', ['product'=>$buytogether, 'aspectRatio' => productAspectRatio()])
                                        </li>
                                    @endforeach
                                </ul>
                                                                    
                    
             </div>                        
        </section>
        @endif      
        <section class="section">

                     @php
                        $videoLinks = explode("\n", $product->pc_video_link);
                        $embedLink = '';
                      
                                @endphp
            <div class="container @if(empty($product->pc_video_link) || $product->pc_video_link == "null" || count($videoLinks) == 0 ){{'container--md'}}@endif">   
            @if(count($videoLinks) > 0 && ($videoLinks[0] != '' && $videoLinks[0] != 'null'))   
            <div class="video-section">      
            <div class="product-detail__info">
            @endif
                        @if(!empty($product->prod_description))
                        <div class="section-content">                            
                                <h2>{{__("LBL_PRODUCT_DESCRIPTION")}}</h2>                            
                        </div>                        
                        <input type="hidden" value="{{$product->prod_id}}" class="YK-productId"> 
                        
                            <div class="yk-expandable">
                                <div class="rte text--pull">
                                    <div class="cms expandable-content expandable-content--expandable Yk-expandable-section" aria-expanded="false" style="">
                                        {!! str_replace('src="public', 'src="'.url('').'/public', $product->prod_description) !!}
                                      <!--  <button class="expandable-content__toggle Yk-expandable-button">
                                        <span class="expandable-content__toggle-text" data-view-more="View less" data-view-less="View more">{{__("LNK_VIEW_MORE")}}</span>
                                    </button>  -->
                                    </div>
                                   
                                </div>        
                            </div>
                        @endif  
                        @if(count($videoLinks) > 0 && ($videoLinks[0] != '' && $videoLinks[0] != 'null'))   
                                        </div>   
                                           
            @endif
                        
                       
                                @if(count($videoLinks) > 0 && ($videoLinks[0] != '' && $videoLinks[0] != 'null'))                    
                                <div class="product-video-wrapper">                            
                                        <div class="@if(count($videoLinks) > 1 ){{'js-video-slider'}}@endif video-slider">
                                            @foreach($videoLinks as $link)
                                                @php $embedLink = parseYouTubeurl($link); @endphp
                                                @if(!empty($embedLink))
                                                    <div class="slider__item">
                                                        <div class="video-wrap"><iframe width="100%" height="315" src="//www.youtube.com/embed/{{$embedLink}}" allowfullscreen=""></iframe></div>
                                                    </div>
                                                @endif
                                            @endforeach
                            
                            
                                        </div>                                  
                                            
                                                                
                                </div>                     
                                @endif
                
           
            @if(count($videoLinks) > 0 && ($videoLinks[0] != '' && $videoLinks[0] != 'null'))   
                                        </div>   
                                           
            @endif

         
                  
        </section> 
  
                      
                      @if(!empty($specifications) || (!empty($product->pc_isbn) || !empty($product->pc_hsn) ||
                            !empty($product->pc_sac) && !empty($product->pc_upc) ) && ($product->pc_isbn !='null' ||
                            $product->pc_hsn != 'null' || $product->pc_sac != 'null' && $product->pc_upc != 'null' ) )

                            <section class="section pt-0">
            <div class="container container--md">
            
            <div class="section-content">
                            <h2>{{ __('LBL_PRODUCT_ADDITIONAL_INFORMATION') }}</h2>
                        </div>
                                <div class="yk-expandable">                              
                                    <div class="expandable-content Yk-expandable-section" aria-expanded="false">
                                        <div class="rte text--pull">
                                            @if((!empty($product->pc_isbn) && strtolower($product->pc_isbn) != 'null') || (!empty($product->pc_hsn) && strtolower($product->pc_hsn) != 'null') ||
                                            (!empty($product->pc_sac) && strtolower($product->pc_sac) != 'null') || (!empty($product->pc_upc) && strtolower($product->pc_upc) != 'null'))
                                                <div class="cms">
                                                    <table class="cms-table">
                                                        <tbody>
                                                            @if($product->pc_isbn != "" && strtolower($product->pc_isbn) != 'null')
                                                            <tr>
                                                                <th width="40%">{{ __('LBL_ISBN') }}</th>
                                                                <td>{{ $product->pc_isbn != 'null' ? $product->pc_isbn : ''}}</td>
                                                            </tr>
                                                            @endif
                                                            @if($product->pc_hsn != "" && strtolower($product->pc_hsn) != 'null')
                                                            <tr>
                                                                <th width="40%">{{ __('LBL_HSN') }}</th>
                                                                <td>{{ $product->pc_hsn != 'null' ? $product->pc_hsn : ''}}</td>
                                                            </tr>
                                                            @endif
                                                            @if($product->pc_sac != "" && strtolower($product->pc_sac) != 'null')
                                                            <tr>
                                                                <th width="40%">{{ __('LBL_SAC') }}</th>
                                                                <td>{{ $product->pc_sac != 'null' ? $product->pc_sac : ''}} </td>
                                                            </tr>
                                                            @endif
                                                            @if($product->pc_upc != "" && strtolower($product->pc_upc) != 'null')
                                                            <tr>
                                                                <th width="40%">{{ __('LBL_UPC') }}</th>
                                                                <td>{{ $product->pc_upc != 'null' ? $product->pc_upc : ''}}</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            @foreach($specifications as $title => $specification)
                                                <div class="cms">
                                                    @if($title)<h6 class="mb-0">{{ucfirst($title)}}</h6>@endif
                                                    <table class="cms-table">
                                                        <tbody>
                                                            @foreach($specification['value'] as $index => $value)
                                                            <tr>
                                                                <th width="40%">{{$specification['key'][$index]}}</th>
                                                                <td>{{$value}}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endforeach
                                        </div>                                        
                                    </div>                                  
                                </div>
                                             
            </div>
        </section>  
                            @endif


                   

        <section class="section pt-0">
            <div class="container container--md"> 
              <div class="Yk-reviews" id="reviews"></div>
            </div> 
        </section> 
        @if(!empty($relatedproducts) && count($relatedproducts)>0)
        <section class="section pt-0">
            <div class="container">                        
                        <div class="section-content">
                                <h2>{{ __('LBL_RELATED_PRODUCTS') }}</h2>
                            </div>
                            <div class="product-block-list__item product-block-list__item--content">
                                <div class="js-fourColumn-slider collection-slider horizontal-slider">
                                    @foreach($relatedproducts as $related)
                                        <div class="item">
                                            @include('themes.'.config('theme').'.partials.productCard2', ['product'=>$related, 'aspectRatio' => productAspectRatio()])
                                        </div>
                                    @endforeach
                                    </div>
                            </div>                          
                 
            </div> 
        </section> 
                        @endif      
            
       
    </div>

   
    @include('themes.'.config('theme').'.product.partials.recentlyViewedProducts')
</div>
@php $sharethisNetworks = shareThis(); @endphp
@if($sharethisNetworks->count() > 0)
<div class="modal fade" id="modalsocial" data-yk="" role="yk-dialog">
    <div class="modal-dialog modal-dialog-centered" data-yk="" role="yk-document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__("LBL_SHARE")}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="socail-follow socail-follow-lg socail-follow--popup text-center">
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
                    <a class="icon icon-{{$type}} st-custom-button" data-title="{{$product->prod_name}}" data-network="{{$type}}" data-url="{{request()->url()}}"><i class="{{$icon}}"></i></a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<div class="modal fade" id="modalSizeChart" data-yk="" role="yk-dialog">
    <div class="modal-dialog modal-dialog-centered" data-yk="" role="yk-document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__("LBL_SIZE_CHART")}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img data-yk="" alt="" src="{{getFileUrl('productChartUpload', $product->prod_id)}}">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade YK-addOrEditReview" id="modalwrite-review" data-yk="" role="yk-dialog"></div>
@section('scripts')
    {{--<script src="{{ asset('vendors/js/sliders.js') }}"></script>--}}
    <script  src="{{ asset('vendors/fresco/js/fresco.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/fresco/css/fresco.css')}}" />

<script>
    let decreaseQuantity = $('.YK-quantity').find('.decrease');
    $(decreaseQuantity).addClass("disabled");
    let scrolltoReview = "{{$_GET['review'] ?? ''}}";

    function getReviews(sort = '', page = 1) {
        $.ajax({
            url: baseUrl + '/product-reviews/get?page=' + page,
            type: "post",
            data: {
                id: '{{$record_id}}',
                sort: sort.replace(' ', '')
            },
            success: function(res) {
                $('.Yk-reviews').html(res);
                if (sort != '') {
                    $('.YK-sortType').text(sort);
                }
                if (scrolltoReview != '') {
                    $('html, body').animate({
                        scrollTop: $("#reviews").offset().top
                    }, 500);
                    scrolltoReview = '';
                }

            }
        });
    }
    $(document).ready(function() {
        getReviews('', '{{$page}}');
        $(document).on('click', '.YK-reviewMoreImages', function(event) {
            $(this).parent().find('li:nth-child(4) a').trigger('click');
        });
        $(document).on('click', '.YK-reviewsPaginate a.page-link', function(e) {
            /* pagination data */
            e.preventDefault();
            getReviews('', $(this).attr('href').split('?page=')[1]);
        });
        $(document).on('click', '.YK-sortReviews', function(e) {
            /* sort */
            let sortType = $(this).text();
            getReviews(sortType);
        });
        $('.Yk-expandable-section').each(function() {
            if ($(this).height() < 150) {
                $(this).removeClass('expandable-content');
                $(this).closest('.yk-expandable').find('.Yk-expandable-button').remove();
            }
        });
        $('.Yk-expandable-button').click(function() {
            let closestobt = $(this).closest('.yk-expandable').find('.Yk-expandable-section');
            if (closestobt.attr('aria-expanded') == 'true') {
                closestobt.attr('aria-expanded', false);
                $(this).find('span:last').html($(this).find('span:last').attr('data-view-less'));
            } else {
                closestobt.attr('aria-expanded', true);
                $(this).find('span:last').html($(this).find('span:last').attr('data-view-more'));
            }
        });
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() > 20) {
            $('#YKFixedSection').addClass('sticky-product-detail');
        } else {
            $('#YKFixedSection').removeClass('sticky-product-detail');
        }
    });

    $(document).ready(function() {
        $('.js-video-slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
        });
    });
</script>
<script  src="https://ws.sharethis.com/button/buttons.js"></script>
<script  src="//platform-api.sharethis.com/js/sharethis.js"></script>
@endsection
@endsection