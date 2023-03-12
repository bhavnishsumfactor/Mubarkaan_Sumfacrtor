@extends('layouts.lite')
@php
$configurations = getConfigValues([
'FRONTEND_LOGO_RATIO',
'BUSINESS_NAME'
]);
$darkLogo = getFrontendDarkLogo();
$logo = getFrontendLogo()  != '' ? getFrontendLogo() : noImage('160x40.png');
$ratio = $configurations['FRONTEND_LOGO_RATIO'] ?? '16:9';
@endphp
@section('content')
<div class="login-page">
    <div class="login-head">
        <div class="login-logo-wrap">
            <a href="{{url('')}}" class="login-logo">
                @include('themes.'.config('theme').'.shortcodes.logo') 
            </a>
        </div>
        <h1>{{__('LBL_COMPLETE_YOUR_ACCOUNT')}}</h1>
    </div>
    <div class="login-body">
        <form method="POST" action="{{ route('saveAdditionalDetails') }}" id="social-login-form" class="form form-login form-floating">
            @csrf
            <input type="hidden" name="serviceType" value={{$serviceType}}>
            <input type="hidden" name="socialId" value={{$socialId}}>
            <div class="row">
                <div class="col-md-12">
                    @if(!empty($_GET['via']) && $_GET['via']=='phone' && $smsActivePackage >= 1)
                    <input name="via" type="hidden" id="via" value="phone">
                    <div class="form-group form-floating__group floated-txt_wrap">
                        <div class="floated-txt">
                            <a href="{{ url('additional-details/' . $serviceType . '/' . $socialId) }}" class="">{{__('LNK_USE_EMAIL_INSTEAD')}}</a>
                        </div>
                        <input type="hidden" name="user_phone_country_code" value="us">
                        <input id="user_phone" type="hidden" name="user_phone" value="{{ old('user_phone') }}">
                        <input id="user_phone_mask" type="tel" data-inputmask-clearmaskonlostfocus="false"
                            class="form-control @error('user_phone') is-invalid @enderror"
                            name="user_phone_mask">
                        @if ($errors->has('user_phone'))
                        <span class="invalid-feedback d-block" data-yk="" role="yk-alert">
                            <strong>{{ $errors->first('user_phone') }}</strong>
                        </span>
                        @endif
                    </div>
                    @else
                    <input name="via" type="hidden" id="via" value="email">
                    <div class="form-group form-floating__group floated-txt_wrap">
                        <div class="floated-txt">
                            <a href="{{ url('additional-details/' . $serviceType . '/' . $socialId) . '?via=phone' }}" class="">{{__('LNK_USE_PHONE_INSTEAD')}}</a>
                        </div>
                        <input type="text"
                            class="form-control form-floating__field @error('user_email') is-invalid @enderror"
                            id="user_email" name="user_email" value="{{ old('user_email') }}" required autofocus>
                        <label class="form-floating__label">{{ __('LBL_EMAIL') }}</label>
                        @if ($errors->has('user_email'))
                        <span class="invalid-feedback" data-yk="" role="yk-alert">
                            <strong>{{ $errors->first('user_email') }}</strong>
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                    <label class="checkbox">
                            <input id="termsConditions" type="checkbox" name="agree" value="1"> <i
                                class="input-helper"></i>
                            {{__('LBL_I_AGREE_TO_THE')}} <a target="_blank"
                                href="{{getPageByType('terms')}}">{{__('LNK_TERMS_CONDITIONS')}}</a> </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-group"> <button type="button" id="YK-socialLoginBtn" class="btn btn-brand gb-btn gb-btn-primary btn-block btn-submit" disabled="disabled" readonly="readonly">{{__('BTN_ACCOUNT_COMPLETION_SUBMIT')}} <i class="arrow la la-long-arrow-right"></i></button></div>
                    </div>
                </div>
            </div> 

        </form>
    </div>
</div>
@section('scripts')
    <script >  
    $(function(){
        $(document).on('click', '#YK-socialLoginBtn', function(e) {
            e.preventDefault();
            $(this).attr("disabled", true);
            $(this).addClass("gb-is-loading");
            let obj = $('#social-login-form');
            obj.submit();
        });
    });
        @if(!empty($_GET['via']) && $_GET['via'] == 'phone' && $smsActivePackage >= 1)
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
                    $("#user_phone_mask").inputmask(placeholder).focus();
                }, 300);
            }
            $('#user_phone_mask').blur(function() {
                $('#user_phone').val($('#user_phone_mask').inputmask('unmaskedvalue'));
            });
        @endif
        
        var emailFormat = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        setInputValidation = function() {
            var emailorPhone = '';
            if($("#user_email").length == 1){
                emailorPhone = ($("#user_email").val() == '' || emailFormat.test($("#user_email").val().trim()) == false);
            }else{
                emailorPhone = ($('#user_phone_mask').val() == '' || checkIfComplete($('#user_phone_mask')) === false);
            }        
            if (emailorPhone || !document.getElementById('termsConditions').checked) {
                $("#YK-socialLoginBtn").attr("disabled", true);
            } else {
                $("#YK-socialLoginBtn").attr("disabled", false);
            }
        }
        checkIfComplete = function(el) {
            if ($(el).inputmask("isComplete")) {
                return true
            } else {
                return false
            }
        }
        $('[type="checkbox"]').click(function() {
            setInputValidation();
        });
        $("[name='user_phone_mask'], input[name='user_email']").on("keyup", function() {
            setInputValidation();
        });
    </script>
@endsection
@endsection
