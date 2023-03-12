@extends('layouts.lite')
@php
$configurations = getConfigValues([
'FRONTEND_LOGO_RATIO'
]);
$content_name = "";
if($step == "signup-email-verify"){
    $content_name = $user->user_email;
}elseif($step == "signup-phone-verify"){
    $content_name = $user->user_phone;
}
$darkLogo = getFrontendDarkLogo();
$logo = getFrontendLogo()  != '' ? getFrontendLogo() : noImage('160x40.png');
$ratio = $configurations['FRONTEND_LOGO_RATIO'] ?? '16:9';
@endphp
@section('content')

@if (Session::get('registered'))
    <script>
    events.registration({
        content_name: "{{$content_name}}"
    });
    </script>
@endif
<div class="signup-full">
    <div class="signup-full-head">
        <div class="logo">
            <a href="{{url('')}}">
                @include('themes.'.config('theme').'.shortcodes.logo')
            </a>
        </div>
    </div>
    <div class="signup-full-body">
        <div class="flow-cms">
            <h1>{{__('LBL_REGISTER_SUCCESS')}}</h1>
            
                @if($step == "signup-email-verify")
                    <p>{{__('LBL_REGISTERED_EMAIL_INSTRUCTIONS')}} {{$user->user_email}}</p>
                @elseif($step == "signup-phone-verify")
                    <p>{{__('LBL_REGISTERED_PHONE_INSTRUCTIONS')}} {{$user->user_phone}}</p>                   
                @else
                    <p>{{__('LBL_REGISTERED_INSTRUCTIONS')}}</p>
                @endif
                @if($step != "signup")
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
                                <button class="btn btn-brand btn-wide gb-btn gb-btn-primary yk-verify" disabled="disabled" onclick="verifyOtp()"
                                    type="button">{{ __('BTN_VERIFY') }}</button>
                            </div>
                        </div>
                        <div class="otp-block__footer">
                            <p class="YK-Verify-Otp-timer" id="timer">{{ __('LBL_CODE_EXPIRES_IN') }}:
                                <span class="txt-success font-weight-bold" id="time"></span>{{__('LBL_SECONDS')}}
                            </p>
                            <p class="YK-resendOtp" onclick="resendCodeRequest()">{{ __("LBL_DIDNT_GET_CODE") }} <a
                                    class="txt-success font-weight-bold" href="javascript:;" id="resendOtpBtn">
                                    {{ __("BTN_RESEND") }}!</a> 
                            </p>                    
                        </div>
                    </div>
                    <div class="otp-block YK-otpVerifiedSuccess d-none">
                        <div class="otp-success">
                            @if($step == "signup-email-verify")
                                <img class="img" src="{{asset('yokart/' . config('theme') . '/media/retina/otp-complete.svg')}}"  data-yk="" alt="">
                            @elseif($step == "signup-phone-verify")
                                <img class="img" src="{{asset('yokart/' . config('theme') . '/media/retina/otp-complete.svg')}}"  data-yk="" alt="">
                            @endif
                        </div>
                    </div>
                @endif
        </div>
    </div>
    <div class="signup-full-foot">
        @if($step == "signup")
            <div class="cta">
                <a class="btn btn-outline-brand" href="{{ route('login') }}">{{__('LNK_LOGIN')}}</a>
            </div>
        @endif
    </div>
</div>
@section('scripts')
<script>
var slug = "{{ $slug }}";
var signupType = "{{ $signupType }}";
var interval = '';
@if($step == "signup")
    setTimeout(() => {
        window.location.href = "{{route('login')}}";
    }, 2000);
@endif
verifyOtp = function() {
    let submitBtn = $('.yk-verify');
    $('.yk-verify').attr('disabled',true);
    submitBtn.addClass("gb-is-loading");
    let otp = $('.otp-inputs input').map(function(){
        return $(this).val();
    }).get().join('');
    $.post("{{route('verifyCode')}}", {'slug': slug, 'otp' : otp, 'type' : signupType, "_token": "{{ csrf_token() }}" }, function (response) {
        submitBtn.removeClass("gb-is-loading");
        if (response.status == true) {
            $(".YK-Verify-Otp").addClass('d-none');
            $(".YK-otpVerifiedSuccess").removeClass('d-none');
            setTimeout(() => {
                window.location.href = "{{route('login')}}";
            }, 2000);
        } else {
            $('.otp-inputs').find('input').val('');
            $('.otp-inputs').find('input').removeClass('filled');
            $('.otp-inputs').find('input').addClass('is-invalid');
            $('.otp-inputs').find('input:first').focus();
            $("#errorInfo").removeClass('d-none');
            $("#errorInfo").html(`<i class="fas fa-info-circle YK-tooltip" data-toggle="tooltip" data-placement="top" title="${response.message}"></i>`);
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
}
resendCodeRequest = function() {
    $.post("{{route('resendVerificationCode')}}", {
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
    $(document).on('keyup', '.digit-group input', function(){        
        var isEmptyCount = 0;
        $('.digit-group').find('input').each(function() {
            if($(this).val() == ""){
                isEmptyCount = isEmptyCount + 1;
            }else{
                $(this).removeClass('is-invalid');
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
@if (Session::get('registered'))
    timeCounter(180);
@else
    $("#timer").hide();
@endif
</script>
@endsection
@endsection