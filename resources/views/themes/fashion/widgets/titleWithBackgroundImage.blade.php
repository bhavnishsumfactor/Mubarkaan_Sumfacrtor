<div class="yk-titleWithBackgroundImage" data-gjs-draggable="div.js-body">
    <section class="title_with_backgroundimage" style="background-image: url({{ !empty($collections['banner']) ? $collections['banner'] : noImage('4_1/2000x500.png')}});">
        <div class="container yk-container" compid="{{ $cid ?? '' }}">
            <div class="row justify-content-center py-15 bg-cover">
                <div class="col-10">
                    <h1 class="mb-0 text-center text-white">{{$collections['text1'] ?? __("LBL_WE_BELIEVE_WE_MAKE_STYLISH")}}</h1>
                </div>
            </div>
        </div>
    </section>
</div>
