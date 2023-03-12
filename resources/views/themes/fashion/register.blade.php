@extends('layouts.lite')
@php
$configurations = getConfigValues([
    'FRONTEND_LOGO_RATIO',
    'BUSINESS_NAME'
]);
@endphp
@section('metaTags')
    <meta property="og:type" content="Registration" />
    <meta property="og:title" content="{{ $configurations['BUSINESS_NAME'] }}" />
    <meta property="og:site_name" content="{{ $configurations['BUSINESS_NAME'] }}" />
    <meta property="og:image" content="{{ getLogo() }}" />
    <meta property="og:url" content="{{ route('register') }}" />
@endsection

@section('content')
<div class="login-page register">
    <div class="login-head">
        <div class="login-logo-wrap">
            @php
                $darkLogo = getFrontendDarkLogo();
                $logo = getFrontendLogo()  != '' ? getFrontendLogo() : noImage('160x40.png');
                $ratio = $configurations['FRONTEND_LOGO_RATIO'] ?? '16:9';
            @endphp
            <a href="{{url('')}}" class="login-logo">
                @include('themes.'.config('theme').'.shortcodes.logo')     
            </a>
        </div>
        <h1>{{__('LBL_CREATE_AN_ACCOUNT')}}</h1>
    </div>
    <div class="login-body">
        <form method="POST" action="{{ route('register') }}" class="form form-login form-floating" id="YK-RegisterForm">
            @csrf
            <input type="hidden" name="referralcode" value="@if($referralCode){{$referralCode}}@endif">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-floating__group">
                        <input type="text"
                            class="form-control form-floating__field @error('user_fname') is-invalid @enderror"
                            name="user_fname" value="{{ old('user_fname') }}" autocomplete="no" id="user_fname" tabindex="1">
                        <label class="form-floating__label">{{ __('LBL_FIRST_NAME') }}</label>
                        @if ($errors->has('user_fname'))
                        <span class="invalid-feedback" data-yk="" role="yk-alert">
                            <strong>{{ $errors->first('user_fname') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-floating__group">
                        <input type="text"
                            class="form-control form-floating__field @error('user_lname') is-invalid @enderror"
                            name="user_lname" value="{{ old('user_lname') }}" autocomplete="no" id="user_lname" tabindex="2">
                        <label class="form-floating__label">{{ __('LBL_LAST_NAME') }}</label>
                        @if ($errors->has('user_lname'))
                        <span class="invalid-feedback" data-yk="" role="yk-alert">
                            <strong>{{ $errors->first('user_lname') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @if(!empty($_GET['via']) && $_GET['via']=='phone' && !empty($smsPackage))
           
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-floating__group">
                        <input type="hidden" name="user_phone_country_code"
                            value="{{ old('user_phone_country_code') }}">
                        <input id="user_phone" type="hidden" name="user_phone" value="{{ old('user_phone') }}">
                        <input id="user_phone_mask" type="tel" data-inputmask-clearmaskonlostfocus="false"
                            class="form-control  @error('user_phone') is-invalid @enderror"
                            name="user_phone_mask" value="{{ old('user_phone_mask') }}" tabindex="3">
                        @if ($errors->has('user_phone'))
                        <span class="invalid-feedback d-block" data-yk="" role="yk-alert">
                            <strong>{{ $errors->first('user_phone') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            @else

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-floating__group">
                        <input id="user_email" type="email"
                            class="form-control form-floating__field @error('user_email') is-invalid @enderror"
                            name="user_email" value="{{ old('user_email') }}" tabindex="3">
                        <label class="form-floating__label">{{__('LBL_EMAIL')}}</label>
                        @if ($errors->has('user_email'))
                        <span class="invalid-feedback" data-yk="" role="yk-alert">
                            <strong>{{ $errors->first('user_email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-floating__group">
                        <input id="user_password" type="password" class="form-control form-floating__field @error('user_password') is-invalid @enderror" name="user_password" autocomplete="new-password" tabindex="5">
                        <label class="form-floating__label">{{__('LBL_PASSWORD')}}</label>
                        <i class="password-toggle">
                            <img class="YK-isValidPassword d-none" src="{{url('yokart/' . config('theme'))}}/media/retina/check.svg" width="16px" height="16px" data-yk="" alt="">
                        </i>
                        <div id="pswd_info">
                            <h4>{{__('LBL_PASSWORD_MUST_CONTAIN')}}:</h4>
                            <ul>
                                <li id="length" class="invalid">{{__('MSG_VALIDATE_LENGTH')}}</li>
                                <li id="letter" class="invalid">{{__('MSG_VALIDATE_LETTER')}}</li>
                                <li id="capital" class="invalid">{{__('MSG_VALIDATE_UPPERCASE')}}</li>
                                <li id="number" class="invalid">{{__('MSG_VALIDATE_NUMBER')}}</li>
                                <li id="special" class="invalid">{{__('MSG_VALIDATE_SPECIAL_CHAR')}}</li>
                            </ul>
                        </div>
                        
                        @if ($errors->has('user_password'))
                            <span class="invalid-feedback" data-yk=""  role="yk-alert">
                                <strong>{{ $errors->first('user_password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-floating__group">
                        <input id="user_password-confirm" type="password" class="form-control form-floating__field"
                            name="user_password_confirmation" autocomplete="no" tabindex="6">
                        <label class="form-floating__label">{{__('LBL_CONFIRM_PASSWORD')}}</label>
                        <i class="password-toggle">
                            <img class="YK-isInvalidConfirmPassword d-none" src="{{url('yokart/' . config('theme'))}}/media/retina/cancel.svg" width="16px" height="16px" data-yk="" alt="">
                            <img class="YK-isValidConfirmPassword d-none" src="{{url('yokart/' . config('theme'))}}/media/retina/check.svg" width="16px" height="16px" data-yk="" alt="">
                        </i>
                        @if ($errors->has('user_password_confirmation'))
                        <span class="invalid-feedback" data-yk="" role="yk-alert">
                            <strong>{{ $errors->first('user_password_confirmation') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            @if(!empty($recaptchaData['GOOGLE_RECAPCHA_SITE_KEY']))
            <input type="hidden" name="recaptcha" id="recaptcha">
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-2">
                        <label class="checkbox">
                            <input id="termsConditions" type="checkbox" name="agree" value="1" tabindex="7"> <i
                                class="input-helper"></i>
                            {{__('LBL_I_AGREE_TO_THE')}} <a target="_blank"
                                href="{{getPageByType('terms')}}">{{__('LNK_TERMS_CONDITIONS')}}</a> </label>
                    </div>
                </div>
            </div>
            @if(empty($_GET['via']) || (!empty($_GET['via']) && $_GET['via']=='phone' && empty($smsPackage)))
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="checkbox">
                                <input type="checkbox" name="subscribe" value="1" tabindex="8"> <i
                                    class="input-helper"></i>
                                {{__("LBL_SIGNUP_SUBSCRIBE_NEWSLETTER")}} </label>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                <div class="form-group mb-0">
                            <button type="submit" class="btn btn-brand btn-block gb-btn gb-btn-primary btn-submit" tabindex="9" disabled="disabled"
                                id="YK-registerBtn" readonly="readonly" name="YK-registerBtn">{{__('BTN_REGISTER')}} <i
                                    class="arrow la la-long-arrow-right"></i></button>
                        </div>
                </div>
            </div>
            @php
                $queryParams = !empty(request()->get('referralcode')) ? '?referralcode=' . request()->get('referralcode') : '';
            @endphp 
            @if(!empty($smsPackage))
                <div class="divider">
                    <span>{{__('LBL_OR_CONTINUE_WITH')}}</span>
                </div>           
                @if(!empty($_GET['via']) && $_GET['via']=='phone')                
                    <div class="floated-txt text-center">
                        <a href="{{ route('register') . $queryParams }}"  class="loginVia">
                        <i class="icn"><svg class="svg">
                            <use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#email"
                                href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#email"></use>
                        </svg></i>
                        {{__('LNK_USE_EMAIL_INSTEAD_LARGE')}}</a>
                    </div>
                @else
                    @php
                        $phoneQueryParams = !empty(request()->get('referralcode')) ? '&referralcode=' . request()->get('referralcode') : '';
                    @endphp
                    <div class="floated-txt text-center">
                        <a href="{{ route('register') . '?via=phone' . $phoneQueryParams }}" class="loginVia">
                        <i class="icn"><svg class="svg">
                            <use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#phone"
                                href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#phone"></use>
                        </svg></i>
                            {{__('LNK_USE_PHONE_INSTEAD_LARGE')}}</a>
                    </div>  
                @endif
            @endif
        </form>


    </div>

    <div class="login-foot pt-7">

                <p class="no-account"> 
                    <a href="{{ route('forgotPassword') }}" class="link forgot-txt">{{ __('LNK_FORGOT_PASSWORD') }}?</a>
                    <span class="pipe">|</span>
                    <a class="link" href="{{ route('login') . $queryParams }}">{{ __('LNK_LOGIN') }}</a>
					
				</p>
    </div>
</div>
@section('scripts')
<script >  
    $(document).ready(function(){
        $(document).on('click', 'input#user_password', function() {
            $('#pswd_info').show();
        });         
        $('input#user_password-confirm').keyup(function() {
            var newPass = $(this).val();
            var oldPass = $('input#user_password').val();
            if(oldPass == '' ||  newPass == ''){
                $('.YK-isInvalidConfirmPassword').addClass('d-none');
                $('.YK-isValidConfirmPassword').addClass('d-none');
                return false;
            }
            if(oldPass == newPass){
                $('.YK-isInvalidConfirmPassword').addClass('d-none');
                $('.YK-isValidConfirmPassword').removeClass('d-none');
            }else{
                $('.YK-isInvalidConfirmPassword').removeClass('d-none');
                $('.YK-isValidConfirmPassword').addClass('d-none');
            }
        });
        
        $('input#user_password').keyup(function() {
            var pswd = $(this).val();
            if ( pswd.length < 8 ) {
                $('#length').removeClass('valid').addClass('invalid');
            } else {
                $('#length').removeClass('invalid').addClass('valid');
            }
            if ( pswd.match(/[A-z]/) ) {
                $('#letter').removeClass('invalid').addClass('valid');
            } else {
                $('#letter').removeClass('valid').addClass('invalid');
            }
            if ( pswd.match(/[A-Z]/) ) {
                $('#capital').removeClass('invalid').addClass('valid');
            } else {
                $('#capital').removeClass('valid').addClass('invalid');
            }
            if ( pswd.match(/[^\w]/) ) {
                $('#special').removeClass('invalid').addClass('valid');
            } else {
                $('#special').removeClass('valid').addClass('invalid');
            }
            if ( pswd.match(/\d/) ) {
                $('#number').removeClass('invalid').addClass('valid');
            } else {
                $('#number').removeClass('valid').addClass('invalid');
            }
            
            if($('#pswd_info').find('ul li.invalid').length == 0){
                $('.YK-isValidPassword').removeClass('d-none');
            }else{
                $('.YK-isValidPassword').addClass('d-none');
            }
        }).focus(function() {
            $('#pswd_info').show();
        }).blur(function() {
            $('#pswd_info').hide();
        });
              
        $(document).on('click', '#YK-registerBtn', function(e) {
            e.preventDefault();
            let obj = $('#YK-RegisterForm');
            let submitBtn = obj.find('button[name="YK-registerBtn"]');
            loader(submitBtn);
            obj.submit();
        });
        @if(!empty($_GET['via']) && $_GET['via']=='phone' && !empty($smsPackage))
            var input = document.querySelector("#user_phone_mask");
            var iti = window.intlTelInput(input, {
                separateDialCode: true,
                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                    var placeholderVal = selectedCountryPlaceholder.replace(/[0-9]/g, "9").replace(/_/g, "9");
                    $("#user_phone_mask").inputmask(placeholderVal);
                    return placeholderVal;
                },
                utilsScript: "{{asset('yokart/'.config('theme').'/js/utils.js')}}"
            });  
            iti.setCountry("{{ $defaultCountry }}"); 
            invokeChange();
            input.addEventListener("countrychange", function() {
                invokeChange();
            });
            function invokeChange () {
                setTimeout(() => {
                    var selected = iti.getSelectedCountryData();
                    $('[name=user_phone_country_code]').val(selected.iso2);
                    var placeholder = $('#user_phone_mask').attr('placeholder');
                    $("#user_phone_mask").inputmask(placeholder);
                }, 300);
            }
            $('#user_phone_mask').blur(function() {
                $('#user_phone').val($('#user_phone_mask').inputmask('unmaskedvalue'));
            });
        @endif
        $('[type="checkbox"]').click(function(){
            var emailorPhone = '';
            if($("#user_email").length == 1){
                emailorPhone = ($("#user_email").val() == '' || emailFormat.test($("#user_email").val().trim()) == false);
            }else{
                emailorPhone = ($('#user_phone_mask').val() == '' || checkIfComplete($('#user_phone_mask')) === false);
            }
            if(document.getElementById('termsConditions').checked) {
                if( ($("#user_fname").val() == '' || $("#user_lname").val() == '' || emailorPhone || $("#user_password").val() == '' || $("#user_password-confirm").val() == '' || !document.getElementById('termsConditions').checked || $('input#user_password-confirm').val() != $('input#user_password').val() || $('#pswd_info').find('ul li.invalid').length != 0) ) {
                    $("#YK-RegisterForm button[name='YK-registerBtn']").attr("disabled", true);
                } else {
                    $("#YK-RegisterForm button[name='YK-registerBtn']").attr("disabled", false);
                }            
            }else{
                if( ($("#user_fname").val() == '' || $("#user_lname").val() == '' || emailorPhone || $("#user_password").val() == '' || $("#user_password-confirm").val() == '' || !document.getElementById('termsConditions').checked || $('input#user_password-confirm').val() != $('input#user_password').val() || $('#pswd_info').find('ul li.invalid').length != 0) ) {
                    $("#YK-RegisterForm button[name='YK-registerBtn']").attr("disabled", true);
                } else {
                    $('.btn-submit').attr('disabled', 'disabled').attr("disabled", false);
                }
            
            }
        });
    });
    setInputFilter = function(textbox, inputFilter) {
        ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
            textbox.addEventListener(event, function() {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
            });
        });
    }
    var emailFormat = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    setRegisterValidation = function() {
        /* Restrict Fist and last name
        setInputFilter(document.getElementById("user_fname"), function(value) {
            return /^[a-zA-Z\s]*$/.test(value);
        });
        setInputFilter(document.getElementById("user_lname"), function(value) {
            return /^[a-zA-Z\s]*$/.test(value);
        });*/

        var emailorPhone = '';
        if($("#user_email").length == 1){
            emailorPhone = ($("#user_email").val() == '' || emailFormat.test($("#user_email").val().trim()) == false);
        }else{
            emailorPhone = ($('#user_phone_mask').val() == '' || checkIfComplete($('#user_phone_mask')) === false);
        }        
        if (($("#user_fname").val() == '' || $("#user_lname").val() == '' || emailorPhone || $(
                    "#user_password").val() == '' || $("#user_password-confirm").val() == '' || !document
                .getElementById('termsConditions').checked || $('input#user_password-confirm').val() != $('input#user_password').val() || $('#pswd_info').find('ul li.invalid').length != 0)) {
            $("#YK-RegisterForm button[name='YK-registerBtn']").attr("disabled", true);
        } else {
            $("#YK-RegisterForm button[name='YK-registerBtn']").attr("disabled", false);
        }
    }
    checkIfComplete = function(el) {
        if ($(el).inputmask("isComplete")) {
            return true
        } else {
            return false
        }
    }
    setLoginTrigger = function() {
        $("input[type='text'], [name='user_phone_mask'], input[type='email'], textarea, input[type='password']").on("keyup", function() {
            var emailorPhone = '';
            if($("#user_email").length == 1){
                emailorPhone = ($("#user_email").val() == '' || emailFormat.test($("#user_email").val().trim()) == false);
            }else{
                emailorPhone = ($('#user_phone_mask').val() == '' || checkIfComplete($('#user_phone_mask')) === false);
            }
            if (($("#user_fname").val() == '' || $("#user_lname").val() == '' || emailorPhone ||
                    $("#user_password").val() == '' || $("#user_password-confirm").val() == '' || !document
                    .getElementById('termsConditions').checked || $('input#user_password-confirm').val() != $('input#user_password').val() || $('#pswd_info').find('ul li.invalid').length != 0)) {
                $("#YK-RegisterForm button[name='YK-registerBtn']").attr("disabled", true);
            } else {
                $("#YK-RegisterForm button[name='YK-registerBtn']").attr("disabled", false);
            }
        });
    }
    setRegisterValidation();
    setLoginTrigger();
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                floatingFormFix();
            }, 500);
        });
    })();
</script>
@if(!empty($recaptchaData['GOOGLE_RECAPCHA_SITE_KEY']) && !Request::exists('sess') &&
$recaptchaData['GOOGLE_RECAPCHA_STATUS'] == 1)
<script>
    var siteKey = "{!! $recaptchaData['GOOGLE_RECAPCHA_SITE_KEY'] !!}";
</script>
<script src="https://www.google.com/recaptcha/api.js?render={!! $recaptchaData['GOOGLE_RECAPCHA_SITE_KEY'] !!}">
</script>
<script>
grecaptcha.ready(function() {
    grecaptcha.execute(siteKey, {
        action: 'register'
    }).then(function(token) {
        if (token) {
            document.getElementById('recaptcha').value = token;
        }
    });
});
</script>
@endif
@endsection
@endsection