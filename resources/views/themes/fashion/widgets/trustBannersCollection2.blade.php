<section class="section collection-services collection-services-2 yk-trustbannersection" data-gjs-draggable="div.js-body">
    <div class="container yk-container" compid="{{ $cid ?? '' }}">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="panel-services">
                    <div class="panel-services__icon">
                        <svg class="svg">
                            <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#icon-secure" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#icon-secure"></use>
                        </svg>                       
                    </div>
                    <div class="detail">
                        <h3>{{__("LBL_PAYMENT_SECURE")}}</h3>
                        <p>{{__("LBL_PAYMENT_SECURE_CAPTION")}}</p>
                    </div>
                </div> 
            </div>
            <div class="col-lg-3 col-6">
                <div class="panel-services">
                    <div class="panel-services__icon">
                        <svg class="svg">
                          <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#icon-shipping" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#icon-shipping"></use>
                        </svg>
                    </div>
                    <div class="detail">
                        <h3>{{__("LBL_FREE_SHIPPING")}}</h3>
                        <p>{{__("LBL_FREE_SHIPPING_CAPTION")}}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="panel-services">
                     <div class="panel-services__icon">
                        <svg class="svg">
                          <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#icon-return" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#icon-return"></use>
                        </svg>
                    </div>
                    <div class="detail">
                        <h3>{{__("LBL_DAYS_RETURN")}}</h3>
                        <p>{{__("LBL_DAYS_RETURN_CAPTION")}}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="panel-services">
                    <div class="panel-services__icon">
                        <svg class="svg">
                          <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#icon-support" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#icon-support"></use>
                        </svg>
                    </div>
                    <div class="detail">
                        <h3>{{__("LBL_SUPPORT")}}</h3>
                        <p>{{__("LBL_SUPPORT_CAPTION")}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
