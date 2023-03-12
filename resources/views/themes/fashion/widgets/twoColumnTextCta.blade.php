<section class="section bg-light yk-twoColumnTextCta" data-gjs-draggable="div.js-body">
    <div class="container yk-container" compid="{{ $cid ?? '' }}">
        <div class="section__head text-center">          
            <h2 class="mb-md-5 pb-xl-5 yk-title">{{__("LBL_FASHION_DEMOCRACY")}}</h2>            
        </div>
        <div class="row justify-content-center mb-9 font-size-lg text-gray-500">
            <div class="col-12 col-md-5">
                <p class="yk-caption">                    
                    {{__("LBL_DUMMY_TEXT")}}  <br>
                    <br>{{__("LBL_DUMMY_TEXT")}}
                </p>
            </div>
            <div class="col-12 col-md-6 col-lg-5 font-size-lg text-gray-500">
                <p class="yk-caption">
                    {{__("LBL_DUMMY_TEXT")}} <br>
                    <br>{{__("LBL_DUMMY_TEXT")}}
                </p>
            </div>
        </div>
        @if(!empty($collections['cta_link']))
            <div class="row">
                <div class="col-12 text-center">
                    <a class="btn btn-brand" href="{{$collections['cta_link'] ?? ''}}" target="{{$collections['cta_target'] ?? '_self'}}">
                    {{$collections['cta_label'] ?? __("BTN_SHOP_NOW")}} <i class="fe fe-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>