<section class="section yk-textWithTwoImagesOnRight" data-gjs-draggable="div.js-body">
    <div class="container yk-container" compid="{{ $cid ?? '' }}">
        <div class="row align-items-center justify-content-between  yk-internalContent">
            <div class="col-12 col-md-6 order-md-2 text-right">
                <img data-aspect-ratio="3:4" src="{{!empty($collections[1]['banner_cropped']) ? $collections[1]['banner_cropped'] : noImage('3_4/400x533.png')}}" data-yk="" alt="" class="img-fluid w-50">
                <div class="text-left mt-n13 mt-lg-n15 mb-10 mb-md-0">
                    <img data-aspect-ratio="3:4" src="{{!empty($collections[2]['banner_cropped']) ? $collections[2]['banner_cropped'] : noImage('3_4/400x533.png')}}" data-yk="" alt="" class="img-fluid w-75">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-5 order-md-1">
                <h6 class="heading-xxs mb-3 text-gray-400 yk-title">
                    {{__("LBL_WHO_WE_ARE")}}
                </h6>
                <h2 class="mb-md-5 yk-title">{{__("LBL_OUR_STORY")}}</h2>
                <p class="font-size-lg text-muted yk-caption">
                    {{__("LBL_DUMMY_TEXT")}}
                    <br>
                    <br>
                    {{__("LBL_DUMMY_TEXT")}}
                </p>
            </div>
        </div>
    </div>
</section>