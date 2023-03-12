@if($innerHtml==false)
<section class="section yk-testimonialLayout1 collection-testimonial-1" data-gjs-draggable="div.js-body">
    <div class="yk-testimonialposts" data-yk=""  role="yk-testimonials">
        <div class="container yk-container" compid="{{ $cid ?? '' }}">
            <div class="section__header text-center">
                <h2 class="yk-title">{{__("LBL_WHAT_CLIENTS_SAY")}}</h2>
            </div>
            @endif
            @php
                $dummyPostCount = ($fromEditor === true) ? 3 : 4;
            @endphp
            <div class="section__body p-0"> 
                <testimoniallayout1 compid="{{ $cid ?? '' }}">
                    <div class="js-slider-testimonials testimonials yk-testimonials  @if($fromEditor === true) d-flex @endif">
                        @if(!empty($collections) && count($collections)>0)
                        @php $i = 0; @endphp
                            @foreach($collections as $collection)
                            <div class="testimonials__item  @if($fromEditor === true && $i == 1) slick-current @endif">
                                <div class="testimonial">
                                    <div class="testimonial__content">
                                        <h4>{{$collection->testimonial_title}}</h4>
                                        <p>{{$collection->testimonial_description}}</p>
                                    </div>
                                    <div class="testimonial__avtar">                                
                                        <div class="testimonial__avtar-img">
                                            @if(!empty($collection->afile_id))
                                                <img data-aspect-ratio="1:1" data-yk="" src="{{url('') . '/yokart/image/' . $collection->afile_id . '/100-100?t=' . strtotime($collection->testimonial_updated_at)}}" alt="">
                                            @else
                                                <img data-aspect-ratio="1:1" data-yk="" src="{{noImage('1_1/100x100.png')}}" alt="">
                                            @endif      
                                        </div>
                                        <div class="testimonial__avtar-detail">
                                            <h6>{{ucwords($collection->testimonial_user_name)}}</h6>
                                            @if(!empty($collection->testimonial_designation))
                                                <span>{{$collection->testimonial_designation}}</span>
                                            @endif
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
                            @for($i = 0; $i < $dummyPostCount; $i++)
                                <div class="testimonials__item  @if($fromEditor === true && $i == 1) slick-current @endif">
                                    <div class="testimonial">
                                        <div class="testimonial__content">
                                            <h4>{{__("LBL_TESTIMONIAL_DUMMY_TITLE")}}</h4>
                                            <p>{{__("LBL_DUMMY_TEXT")}}</p>
                                        </div>
                                        <div class="testimonial__avtar">
                                            <div class="testimonial__avtar-img">
                                                <img data-yk="" data-aspect-ratio="1:1" src="{{noImage('1_1/100x100.png')}}" alt="">
                                            </div>
                                            <div class="testimonial__avtar-detail">
                                                <h6>{{__("LBL_CLIENT_NAME")}}</h6>
                                                <span>{{__("LBL_DESIGNATION")}}</span>
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
                </testimoniallayout1>
            </div>
@if($innerHtml==false)
        </div>
    </div>
</section>
@endif  