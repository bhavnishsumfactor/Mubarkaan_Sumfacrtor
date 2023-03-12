<div class="yk-newsletterCollection2" data-gjs-draggable="div.js-body">
    <section class="section collection-newsletter-2" style="background-image: url({{ !empty($collections['banner']) ? $collections['banner'] : noImage('6_1/1800x300.png')}})">
        <div class="container yk-container" compid="{{ $cid ?? '' }}">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="yk-styleable">{{$collections['text'] ?? __('LBL_NEWSLETTER_TRY_SOMETHING_NEW')}}</h4>
                </div>
                <div class="col-md-8">
                    <div class="newsletter-form-wrapper">
                        <form newsletter-action method="POST" class="form-newsletter">
                            <div class="input-group">
                                <input type="email" value="" name="email" placeholder="{{__('PLH_ENTER_YOUR_EMAIL_ADDRESS')}}" class="form-control email-input" autocorrect="off" autocapitalize="off">
                                <button type="submit" name="subscribe" class="btn btn-brand YK-subscribeNewsletter">{{__("BTN_SUBSCRIBE")}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>