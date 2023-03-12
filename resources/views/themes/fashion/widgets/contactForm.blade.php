@php
$contactConfig = getConfigValues(['BUSINESS_NAME', 'BUSINESS_PHONE_NUMBER', 'BUSINESS_EMAIL']);
@endphp
<form class="form yk-contactForm" method="POST">
    <div class="row">
        <div class="col-lg-6 col-12">
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="{{__('LBL_NAME')}}" id="contactName" required="" data-msg="Please enter first your name">
            </div>
        </div>
        <div class="col-lg-6 col-12">
            <div class="form-group">
                <input type="email" class="form-control" id="contactTitle" placeholder="{{__('LBL_EMAIL_ADDRESS')}}" name="email" id="contactEmail" required="">
            </div>
        </div>
        <div class="col-lg-12 col-12">
            <div class="form-group">
                <select class="form-control" name="title" id="contactTitle" placeholder="{{__('LBL_WHAT_IS_YOUR_CONCERN')}}?" required="">
                    <option value="Buying">{{__("LBL_BUYING_ON")}} {{$contactConfig['BUSINESS_NAME']}}</option>
                    <option value="Shipping">{{__("LBL_SHIPPING")}}</option>
                    <option value="Account">{{__("LBL_ACCOUNT")}}</option>
                    <option value="Product I received">{{__("LBL_PRODUCT_RECEIVED")}}</option>
                    <option value="Returns/exchanges">{{__("LBL_RETURN_EXCHANGES")}}</option>
                    <option value="Other">{{__("LBL_OTHER")}}</option>
                    <option value="Technical Issue">{{__("LBL_TECHNICAL_ISSUE")}}</option>
                    <option value="Feedback">{{__("LBL_FEEDBACK")}}</option>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group message">
                <textarea class="form-control" rows="4" id="contactMessage" name="message" id="message" placeholder="{{__('PLH_CONTACT_US_ENQUIRY')}} ..." required=""></textarea>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" class="YK-agreeTerms" value="1">
                    <i class="input-helper"></i> {{__("LBL_I_AGREE_TO_THE")}} <a href="{{getPageByType('terms')}}"
                        target="_blank">{{__("LNK_TERMS_CONDITIONS")}}</a>
                </label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group button">
                <input type="hidden" name="recaptcha" id="recaptcha">
                <button type="submit" disabled="" name="contactform" class="btn btn-brand btn-wide gb-btn gb-btn-primary">{{__("BTN_CONTACT_US_SUBMIT")}}</button>
            </div>
        </div>
    </div>
</form>