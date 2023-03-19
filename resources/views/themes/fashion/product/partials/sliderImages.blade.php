
<div class="gallery gallery--product">
@if(count($productImages) > 1)
    <div class="slideCount" ></div> 
    @endif
    <div class="gallery__main js--product-gallery" data-aspect-ratio="{{productAspectRatio()}}" id="product-gallery">             
            @if(count($productImages) > 0)
            @foreach($productImages as $images)
            <div class="gallery__item @if($images->afile_enable_background != 0){{'js-fillcolor'}}@endif ">
                <a class="gallery__img fresco" href="{{url('yokart/image/'.$images->afile_id.'/'.getProdSize('thumb').'?t=' . strtotime($images->afile_updated_at))}}" data-fresco-options="thumbnail: '{{url('yokart/image/'.$images->afile_id.'/'.getProdSize('small').'?t=' . strtotime($images->afile_updated_at))}}'" data-fresco-group="product_slider_thumbs">
                    <img data-aspect-ratio="{{productAspectRatio()}}" data-yk="" src="{{url('yokart/image/'.$images->afile_id.'/'.getProdSize('', true).'?t=' . strtotime($images->afile_updated_at))}}"  alt="{{ (strtolower($images->afile_attribute_alt) != 'null') ? $images->afile_attribute_alt : ''}}" title="{{ (strtolower($images->afile_attribute_title)) != 'null' ? $images->afile_attribute_title : '' }}">
                </a>
            </div>
            @endforeach
            @else
            <div class="gallery__item"> 
                <a class="gallery__img fresco" href="{{productDummyImage()}}" data-fresco-options="thumbnail: '{{productDummyImage()}}'" data-fresco-group="product_slider_thumbs">
                    <img data-aspect-ratio="{{productAspectRatio()}}" data-yk="" alt="" src="{{productDummyImage()}}">
                </a>
            </div>
            @endif            
    </div>    
</div>
<script  src="{{ asset('js/jquery.elevatezoom.js') }}"></script>
<script>
    
    // $("img").elevateZoom({easing : true});
    
    $(document).ready(function() {
      
        var $status = $('.slideCount');
      
            var $slickElement = $('.js--product-gallery');
            $slickElement.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
             
                //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
                var i = (currentSlide ? currentSlide : 0) + 1;
                $status.html(i + '<span>|</span>' + slick.slideCount);
                  // add hover on current slide
                  $(slick.$slides.get(currentSlide ? currentSlide : 0)).find('img:first').elevateZoom({easing : true});
            });

        $('.js--product-gallery').slick({
            slidesToShow: 1,
            slidesToScroll: 1, 
            infinite: false,
        });      
  

    });







</script>