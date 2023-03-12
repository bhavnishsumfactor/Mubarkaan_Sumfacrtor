<div class="yk-newsletterFullwidth" data-gjs-draggable="div.js-body">
    <section class="section section--bg collection-newsletter" style="background-image: url({{ !empty($collections['banner']) ? $collections['banner'] : noImage('4_1/2000x500.png')}})">
        <div class="container yk-container" compid="{{ $cid ?? '' }}">
            <div class="row">
                <div class="col-lg-5">
                    <div class="newsletter">
                        <h4 class="yk-styleable">{{$collections['text1'] ?? __('LBL_SIGNUP_FOR_NEWSLETTER')}}</h4>
                        <p class="yk-styleable">{{$collections['text2'] ?? __('LBL_NEWSLETTER_DESCRIPTION')}}</p>                    
                        <form newsletter-action method="POST" class="form-newsletter">
                            <div class="input-group">
                                <input type="email" value="" name="email" placeholder="{{__('PLH_ENTER_YOUR_EMAIL_ADDRESS')}}" class="form-control email-input" autocorrect="off" autocapitalize="off">
                                <button type="submit" class="btn btn-brand YK-subscribeNewsletter" name="subscribe">{{__("BTN_SUBSCRIBE")}}</button> 
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>