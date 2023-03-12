@extends('layouts.lite')
@php
$configurations = getConfigValues([
'FRONTEND_LOGO_RATIO'
]);
$darkLogo = getFrontendDarkLogo();
$logo = getFrontendLogo()  != '' ? getFrontendLogo() : noImage('160x40.png');
$ratio = $configurations['FRONTEND_LOGO_RATIO'] ?? '16:9';
@endphp
@section('content')
<div class="login-page reset-password">
    <div class="login-head">
        <div class="login-logo-wrap">
            <a href="{{url('')}}" class="login-logo">
                @include('themes.'.config('theme').'.shortcodes.logo')
            </a>
        </div>
        <h1>{{__('LBL_RESET_PASSWORD')}}</h1>
    </div>
    <div class="login-body">
        <form id="resetPasswordForm" class="form form-login form-floating" method="POST"
            action="{{ route('passwordUpdate') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            @if($via == "email")
                <p>{{__('LBL_RESET_PASSWORD_EMAIL_INSTRUCTIONS')}} {{$user->user_email}}</p>
            @elseif($via == "phone")
                <p>{{__('LBL_RESET_PASSWORD_PHONE_INSTRUCTIONS')}} {{$user->user_phone}}</p>     
            @endif
            @if(empty(Session::get('errors')))
            <div class="otp-block YK-Verify-Otp mt-5">
                <div class="otp-block__body">
                    <div class="otp-enter">
                        <div class="otp-inputs">
                            <div class="digit-group" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                                <input class="field-otp" type="text" maxlength="1"
                                    placeholder="*" id="digit-1" name="digit-1" data-next="digit-2">
                                <input class="field-otp" type="text" maxlength="1"
                                    placeholder="*" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1">
                                <input class="field-otp" type="text" maxlength="1"
                                    placeholder="*" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2">
                                <input class="field-otp" type="text" maxlength="1"
                                    placeholder="*" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3">
                                <div class='error-info d-none' id="errorInfo">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-dark btn-wide gb-btn gb-btn-primary yk-verify" disabled="disabled" onclick="verifyOtp()"
                            type="button">{{ __('BTN_VERIFY') }}</button>
                    </div>
                </div>
                <div class="otp-block__footer">
                    <p class="YK-Verify-Otp-timer" id="timer">{{ __('LBL_CODE_EXPIRES_IN') }}:
                        <span class="txt-success font-weight-bold" id="time"></span> {{__('LBL_SECONDS')}}
                    </p>
                    <p class="YK-resendOtp" onclick="resendCodeRequest()">{{ __("LBL_DIDNT_GET_CODE") }} <a
                            class="txt-success font-weight-bold" href="javascript:;" id="resendOtpBtn">
                            {{ __("BTN_RESEND") }}!</a> 
                    </p>                    
                </div>
            </div>
            <div class="otp-block YK-otpVerifiedSuccess d-none">
                <div class="otp-success">
                    @if($via == "email")
                        <img class="img" src="{{asset('yokart/' . config('theme') . '/media/retina/otp-complete.svg')}}"  data-yk="" alt="">
                    @elseif($via == "phone")
                        <img class="img" src="{{asset('yokart/' . config('theme') . '/media/retina/otp-complete.svg')}}"  data-yk="" alt="">
                    @endif
                </div>
            </div>
            @endif
            <div class="form-group form-floating__group">
                <input class="form-control form-floating__field @error('password') is-invalid @enderror"
                    id="user_password" name="password" type="password" autocomplete="no" @if(empty(Session::get('errors'))) disabled="disabled" @endif>
                <label class="form-floating__label">{{ __('LBL_NEW_PASSWORD') }}</label>
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
                @error('password')
                <span class="invalid-feedback" data-yk="" role="yk-alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group form-floating__group password-field">                
                <input class="form-control form-floating__field" id="password-confirm" name="password_confirmation"
                    type="password" autocomplete="no" @if(empty(Session::get('errors'))) disabled="disabled" @endif>
                <label class="form-floating__label">{{ __('LBL_CONFIRM_PASSWORD') }}</label>              
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-brand btn-block gb-btn gb-btn-primary btn-submit" id="submit-button" @if(empty(Session::get('errors'))) disabled="disabled" @endif>{{ __('BTN_RESET_PASSWORD_SUBMIT') }} <i
                                class="arrow la la-long-arrow-right"></i></button>
                    </div>
                </div>
            </div>
            <input type="hidden" id="otpFld" name="otp" value=""/>
        </form>
    </div>
    <div class="login-foot">
    <p class="no-account">
                <a class="link" href="{{ route('login') }}">{{ __('LNK_LOGIN') }}</a> &nbsp; | &nbsp; 
                <a class="link" href="{{ route('register') }}">{{ __('LNK_REGISTER') }}</a>
            </p>
    </div>
</div>
@section('scripts')
<script>
var slug = "{{ $token }}";
var interval = '';
verifyOtp = function() {
    let submitBtn = $('.yk-verify');
    $('.yk-verify').attr('disabled',true);
    submitBtn.addClass("gb-is-loading");
    let otp = $('.otp-inputs input').map(function(){
        return $(this).val();
    }).get().join('');
    $.post("{{route('verifyPasswordVerificationCode')}}", {'slug': slug, 'otp' : otp, "_token": "{{ csrf_token() }}" }, function (response) {
        submitBtn.removeClass("gb-is-loading");
        if (response.status == true) {
            $(".YK-Verify-Otp").addClass('d-none');
            $(".YK-otpVerifiedSuccess").removeClass('d-none');
            $('#user_password, #password-confirm').removeAttr('disabled'); 
            $("#otpFld").val(otp);

        } else {
            $('.otp-inputs').find('input').val('');
            $('.otp-inputs').find('input').removeClass('filled');
            $('.otp-inputs').find('input').addClass('is-invalid');
            $('.otp-inputs').find('input:first').focus();
            //toastr.error(response.message);
            $("#errorInfo").removeClass('d-none');
            $("#errorInfo").html(`<i class="fas fa-info-circle YK-tooltip" data-toggle="tooltip" data-placement="top" title="${response.message}"></i>`);
            $('[data-toggle="tooltip"]').tooltip();
        }           
    });
}
resendCodeRequest = function() {
    $.post("{{route('resendPasswordVerificationCode', $via)}}", {
        'slug': slug,
        "_token": "{{ csrf_token() }}"
    }, function (res) {
        clearInterval(interval);
        timeCounter(180);
    });
}

$('.digit-group').find('input').each(function() {
    $(this).attr('maxlength', 1);
    $(this).on('keyup', function(e) {
        var parent = $($(this).parent());
        if(e.keyCode === 8 || e.keyCode === 37) {
            var prev = parent.find('input#' + $(this).data('previous'));
            if(prev.length) {
                $(prev).select();
            }
        } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
            var next = parent.find('input#' + $(this).data('next'));
            if(next.length) {
                if(!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105))){
                    $(this).val('');
                    return;
                }
                $(next).select();
            } else {
                if(parent.data('autosubmit')) {
                    parent.submit();
                }
            }
        }
    });
});
$(function(){
    floatingFormFix();

    $(document).on('click', '#submit-button', function(e) {
        e.preventDefault();
        $(this).attr('disabled', true);
        $(this).addClass("gb-is-loading");
        let obj = $('#resetPasswordForm');
        obj.submit();
    });

    $(document).on('keyup', 'input[type="password"]', function() {
        var confirmPass = $('input#password-confirm').val();
        var newPass = $('input#user_password').val();
        
        if (newPass == '' || confirmPass == '' || newPass != confirmPass || $('#pswd_info').find('ul li.invalid').length > 0) {
            $('#submit-button').attr("disabled", true);
            return false;
        }
        $('#submit-button').attr("disabled", false);
    });
    $(document).on('click', 'input#user_password', function() {
        $('#pswd_info').show();
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
    }).focus(function() {
        $('#pswd_info').show();
    }).blur(function() {
        $('#pswd_info').hide();
    });
    
    $(document).on('keyup', '.digit-group input', function(){
        var isEmptyCount = 0;
        $('.digit-group').find('input').each(function() {
            if($(this).val() == ""){
                isEmptyCount = isEmptyCount + 1;
            }
        });
        if(isEmptyCount > 0){
            $('.yk-verify').attr('disabled', 'disabled');
        }else{
            $('.yk-verify').removeAttr('disabled');
        }
    });
})
timeCounter = function(counter) {
    $("#timer").show();
    $(".YK-resendOtp").hide();
    $('#time').text(counter); 
    interval = setInterval(function() {
        counter--;            
        if (counter <= 0) {
            clearInterval(interval);
            $("#timer").hide();
            $(".YK-resendOtp").show();
            return;
        }else{
            $('#time').text(counter);              
        }
    }, 1000);
}
@if (Session::get('sendCode'))
    timeCounter(180);
@else
    $("#timer").hide();
@endif
</script>
@endsection
@endsection