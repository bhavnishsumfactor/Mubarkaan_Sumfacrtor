@extends('layouts.admin.public')

@section('content')
    @if(!empty($_GET['via']) && $_GET['via']=='phone' && $smsActivePackage >= 1)
        <form class="form form--login" id="forgot_password_phone_form" onsubmit="return sendResetPasswordOtp()" method="POST" action="javascript:void(0)">    
    @else
        <form class="form form--login" id="forgot_password_form" onsubmit="return sendResetPasswordLink()" method="POST" action="javascript:void(0)">
    @endif  

    @csrf
    <div class="row pt-3 pb-3">
        <div class="col-6">
            <h3 class="mb-4">{{ __('LBL_RESET_PASSWORD') }}</h3>
        </div>
        <div class="col-6 pt-1">
            <div class="field-set text-right">
            
            @if(!empty($_GET['via']) && $_GET['via'] == 'phone')
                <a href="{{ route('adminResetPassword') }}" class="link">{{__('LNK_USE_EMAIL_INSTEAD')}}</a>
            @else
                @if($smsActivePackage >= 1)
                    <a href="{{ route('adminResetPassword') . '?via=phone' }}" class="link">{{__('LNK_USE_PHONE_INSTEAD')}}</a>
                @endif
            @endif                
            </div>
        </div>
    </div>
    @if(!empty($_GET['via']) && $_GET['via']=='phone' && $smsActivePackage >= 1)        
        @if(!empty(Session::get('phone')))
        <label>{{__('LBL_OTP')}}</label> 
            <div class="row">
                
                <div class="col-md-8">
                    <div class="form-group otp-field">
                        <input type="hidden" name="user_phone_code" value="{{Session::get('phone_code')}}">
                        <input type="hidden" name="user_phone" value="{{Session::get('phone')}}">
                        <input type="text" maxlength="4" class="form-control" name="otp" id="otpData">
                        <span class="invalid-feedback YK-otpError d-none"></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="button" name="YK-VerifyOtp" class="btn btn-primary btn-block YK-verifyOTP">{{__('BTN_VERIFY')}} <i class="arrow la la-long-arrow-right p-0"></i></button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        
                    </div>
                </div>
            </div>
        @else
            <p class="pb-2">{{__('LBL_ENTER_REGISTER_PHONE_NUMBER')}}</p>
            <div class="row">
                <div class="col-md-12">
					
                    <div class="form-group form-floating__group">
                        <input type="hidden" name="user_phone_country_code" value="{{$defaultCountry}}">
                        <input id="user_phone" type="hidden" name="user_phone" value="{{ old('user_phone') }}">
                        <input id="user_phone_mask" type="tel" data-inputmask-clearmaskonlostfocus="false" class="form-control" name="user_phone_mask" value="{{ old('user_phone_mask') }}">
                        @if ($errors->has('user_phone'))
                            <span class="invalid-feedback d-block" data-yk=""  role="yk-alert">
                                <strong>{{ $errors->first('user_phone') }}</strong>
                            </span>
                        @endif 
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="submit" disabled="disabled" class="btn btn-brand btn-block gb-btn gb-btn-primary" name="YK-SendOtp">{{ __('BTN_SEND_OTP') }}</button>
                </div>
            </div>
        @endif
    @else    
        <p class="pb-2">{{ __('LBL_ENTER_REGISTER_EMAIL') }} </p>
        <div class="form-group">
            <input class="form-control" id="email" name="email" placeholder="{{__('PLH_EMAIL')}}" type="text" value="{{ old('email') }}">
            @error('email')
                <span class="invalid-feedback" data-yk=""  role="yk-alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="submit" disabled="disabled" class="btn btn-brand btn-block gb-btn gb-btn-primary" name="YK-SendEmailLink">{{ __('LBL_SEND_LINK') }}</button>
            </div>
        </div>
    @endif    
        <p class="text-center d-flex justify-content-center py-4">
        @if(!empty($_GET['via']) && $_GET['via']=='phone' && $smsActivePackage >= 1)        
            @if(!empty(Session::get('phone')))
            <button type="button" class="otp__resend YK-resendOTP">{{__('BTN_RESEND')}} <i class="arrow la la-long-arrow-right p-0"></i></button>
            @endif
        @endif
        <a href="{{route('adminLogin')}}" class="link">{{__('LBL_BACK_TO_LOGIN')}}</a></p>
    
</form>
@section('css')

    <link href="{{ asset('vendors/css/intlTelInput.min.css')}}" rel="stylesheet" type="text/css">
    <style>
    .iti__flag {background-image: url({{asset('yokart/'.config('theme').'/images/flags.png')}});}
    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .iti__flag {background-image: url({{asset('yokart/'.config('theme').'/images/flags@2x.png')}});}
    }
    input[name="user_phone_mask"]{  padding-left: 100px !important; }
</style>
@endsection
@section('scripts')
    @if(!empty($_GET['via']) && $_GET['via']=='phone' && $smsActivePackage >= 1)
    <script src="{{asset('vendors/js/password.min.js')}}"></script>
    <script src="{{asset('vendors/js/intlTelInput.min.js')}}"></script>
    <script src="{{asset('vendors/js/jquery.inputmask.js')}}"></script>
    @endif
    <script>
    var smsPackage = {{ $smsActivePackage }};
            @if(!empty($_GET['via']) && $_GET['via']=='phone' && $smsActivePackage >= 1 && empty(Session::get('phone')))
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
            function sendResetPasswordOtp() 
            {		
                $('button[type="submit"]').attr("disabled", "disabled");
                let obj = $('#forgot_password_phone_form');
                let submitBtn = $('button[name="YK-SendOtp"]');
                submitBtn.addClass("gb-is-loading");
                obj.find('input').removeClass('is-invalid');
                obj.find('.invalid-feedback').remove();
                $.ajax({
                    url: "{{ route('adminResetPhone') }}",
                    type: "post",
                    data: obj.serialize(),
                    success: function(res) {
                        if (res.status == true) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        }
                        if (res.status == false) {
                            $('button[type="submit"]').removeAttr("disabled");
                            submitBtn.removeClass("gb-is-loading");
                            validateErrors(res.data, obj);
                            $('.invalid-feedback').show();
                        }
                    },
                    error: function(res) {
                        let errors = res.responseJSON.errors;
                        validateErrors(errors, obj);
                        $('.invalid-feedback').show();
                        submitBtn.removeClass("gb-is-loading");
                    }
                });
            }    
            function sendResetPasswordLink() 
            {		
                $('button[type="submit"]').attr("disabled", "disabled");
                let submitBtn = $('button[name="YK-SendEmailLink"]');
                submitBtn.addClass("gb-is-loading");
                let obj = $('#forgot_password_form');
                obj.find('input').removeClass('is-invalid');
                obj.find('.invalid-feedback').remove();
                $.ajax({
                    url: "{{ route('adminResetEmail') }}",
                    type: "post",
                    data: obj.serialize(),
                    success: function(res) {
                        submitBtn.removeClass("gb-is-loading");
                        if (res.status == true) {
                            toastr.success('', res.message);
                            setTimeout(() => {
                                window.location.href = adminBaseUrl + '/login';
                            }, 2000);
                        }
                        if (res.status == false) {
                            $('button[type="submit"]').removeAttr("disabled");
                            validateErrors(res.data, obj);
                            $('.invalid-feedback').show();
                        }
                    },
                    error: function(res) {
                        let errors = res.responseJSON.errors;
                        submitBtn.removeClass("gb-is-loading");
                        validateErrors(errors, obj);
                        $('.invalid-feedback').show();
                    }
                });
            }    
            $(document).ready(function() {
                $(document).on('click', '.YK-resendOTP', function(){  
                    let obj = $('#forgot_password_phone_form');
                    $.post("{{route('adminResendOtp')}}", {
                        'phone': $('input[name="user_phone"]').val(),
                        'user_phone_code':$('input[name="user_phone_code"]').val(),
                        "_token": "{{ csrf_token() }}"
                    }, function (res) {
                        if(res.status == false) {
                            toastr.error('', res.message);
                            setTimeout(() => {
                                window.location.href = "{{route('adminResetPassword')}}";
                            }, 1000);
                        }
                        toastr.success('', res.message);
                    });
                });
                $(document).on('click', '.YK-verifyOTP', function(){
                    $(".YK-otpError").text("");
                    $('.YK-otpError').removeClass('d-none').addClass('d-none');
                    $.post("{{route('adminVerifyOtp')}}", {
                        'phone': $('input[name="user_phone"]').val(),
                        'otp': $('input[name="otp"]').val(),
                        "_token": "{{ csrf_token() }}"
                    }, function (res) {
                        if (res.status == false) {
                            $(".YK-otpError").text(res.message);
                            $(".YK-otpError").css("display", "block");
                            $('.YK-otpError').removeClass('d-none');
                            return
                        }
                        toastr.success('', res.message);
                        setTimeout(() => {
                            window.location.href = res.data.url;
                        }, 2000);
                    });
                });
                $('input').on('keyup', function() {
                    if($("#user_phone_mask").val() == '') {
                        $('button[type="submit"]').attr('disabled', 'disabled');
                    } else {
                        $('button[type="submit"]').removeAttr('disabled'); 
                    }
                    if($("#otpData").val() == '') {
                        $('button[name="YK-VerifyOtp"]').attr('disabled', 'disabled');
                    } else {
                        $('button[name="YK-VerifyOtp"]').removeAttr('disabled');
                    }

                });
                $(".YK-verifyOTP").attr('disabled', 'disabled');
            });
    </script>
    @endsection
@endsection
