@if($innerHtml==false)
<section class="section collection-testimonial-2 yk-testimonialCollection2" data-gjs-draggable="div.js-body">
    <div class="container yk-container" compid="{{ $cid ?? '' }}">
        <div class="d-xl-flex testimonial-wrapper">
            <div class="section__header section__header-testimonial flex-xl-column d-md-flex justify-content-xl-center align-items-center align-items-xl-start">
                <div class="section__content">
                    <h2 class="yk-title">{{__("LBL_WHAT_CLIENTS_SAY")}}</h2>
                    <div class="section-action">
                            <a href="" target="_blank" class="link-arrow yk-link">{{__("BTN_VIEW_ALL")}}</a>
                    </div>
                </div>
                <div class="slider-controls ">
                    <a class="slider-btn prev-arrow" href="javascript:void(0);" data-href="#testimonials">
                    </a>
                    <a class="slider-btn next-arrow" href="javascript:void(0);" data-href="#testimonials">
                    </a>
                </div>
            </div>
            <div class="section__body p-0">  
                @endif
                <testimonialcollection2 compid="{{ $cid ?? '' }}">              
                        <div class="testimonials js-carousel yk-testimonials @if($fromEditor === true) d-flex @endif" id="testimonials" data-slides="2,3,2,1,1" data-infinite="false" data-arrows="true" data-custom="#testimonials">
                            @if(!empty($collections) && count($collections)>0)
                            @php $i = 0; @endphp
                                @foreach($collections as $collection)
                                    <div class="testimonials__item @if($fromEditor === true && $i == 0) slick-current @endif">
                                        <div class="testimonial">
                                            <div class="testimonial__content">
                                                <h4>{{$collection->testimonial_title}}</h4>
                                                <p>{{$collection->testimonial_description}}</p>
                                            </div>
                                            <div class="testimonial__avtar">                                            
                                                <div class="testimonial__avtar-img">
                                                    @if(!empty($collection->afile_id))
                                                        <img data-aspect-ratio="1:1" src="{{url('') . '/yokart/image/' . $collection->afile_id . '/100-100?t=' . strtotime($collection->testimonial_updated_at)}}" data-yk="" alt="">
                                                    @else
                                                        <img data-aspect-ratio="1:1" data-yk="" src="{{noImage('1_1/100x100.png')}}" alt="">
                                                    @endif                                                 
                                                </div>
                                                <div class="testimonial__avtar-detail">
                                                    <h6> {{ucwords($collection->testimonial_user_name)}}<h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @php
                                    if ($fromEditor && $i == 2) {
                                        break;
                                    }
                                    $i++;
                                @endphp
                                @endforeach
                            @else
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="testimonials__item @if($fromEditor === true && $i == 0) slick-current @endif">
                                        <div class="testimonial">
                                            <div class="testimonial__content">                                            
                                                <h4>{{__("LBL_TESTIMONIAL_DUMMY_TITLE")}}</h4>
                                                <p>{{__("LBL_DUMMY_TEXT")}}</p>
                                            </div>
                                            <div class="testimonial__avtar">
                                                <div class="testimonial__avtar-img"><img data-aspect-ratio="1:1" src="{{noImage('1_1/100x100.png')}}" data-yk="" alt=""></div>
                                                <div class="testimonial__avtar-detail">
                                                    <h6>{{__("LBL_CLIENT_NAME")}}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                        </div>
                    @if($fromEditor==true && !empty($collections) && count($collections)>3)
                        <div class="text-center p-5"> + {{ count($collections) - 3 }} {{__("LBL_MORE_TESTIMONIALS")}}</div>
                    @endif  
                </testimonialcollection2>  
                 @if($innerHtml==false)
            </div>  
        </div>
    </div>
</section>
@endif