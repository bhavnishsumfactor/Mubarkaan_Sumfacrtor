<div class="yk-promotionalBannerLayout1" data-gjs-draggable="div.js-body">
    <section class="section section--bg parallax-bg collection-parallax" style="background-image: url({{ !empty($collections['banner']) ? $collections['banner'] : noImage('2_1/2000x1000.png')}})">
        <div class="container yk-container" compid="{{ $cid ?? '' }}">
            <div class="row justify-content-end">
                <div class="col-md-5">
                    <div class="collection-parallax_data">
                        <h4 class="yk-styleable">{{$collections['text1'] ?? __('LBL_PROMOTIONAL_BANNER_TEXT1')}}</h4>
                        <h2 class="display-1 text-bold yk-styleable">{{$collections['text2'] ?? __('LBL_PROMOTIONAL_BANNER_TEXT2')}}</h2>
                        <a class="btn btn-brand yk-styleable" href="{{$collections['cta_link'] ?? '#'}}" target="{{$collections['cta_target'] ?? '_self'}}">{{$collections['cta_label'] ?? __('BTN_BROWSE_CATALOG')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
