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
        <div class="login-page forgot-password">
            <div class="login-head">
                <div class="login-logo-wrap">
                    <a href="{{url('')}}" class="login-logo">
                        @include('themes.'.config('theme').'.shortcodes.logo') 
                    </a>
                </div>
                <h1> {{__('LBL_FORGOT_PASSWORD')}}</h1>
            </div>
        <div class="login-body">
            @if(!empty($_GET['via']) && $_GET['via']=='phone' && $smsActivePackage >= 1)
            <p>{{__('LBL_FORGOT_PASSWORD_PHONE_INSTRUCTIONS')}}</p>
            @else
            <p>{{__('LBL_FORGOT_PASSWORD_EMAIL_INSTRUCTIONS')}}</p>
            @endif
                <div class="yk-forgotPassword">
                    <form id="YK-forgotForm" class="form form-login form-floating" method="POST" action="{{ route('forgotPasswordPost') }}">
                    @csrf
                    @if(!empty($_GET['via']) && $_GET['via']=='phone' && $smsActivePackage >= 1)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-floating__group mb-0 ">
                                <!-- <label>{{__('LBL_PHONE')}}</label> -->
                                <input type="hidden" name="user_phone_country_code" value="us">
                                <input id="user_phone" type="hidden" name="user_phone" value="{{ old('user_phone') }}">
                                <input id="user_phone_mask" type="tel" data-inputmask-clearmaskonlostfocus="false" class="form-control @error('user_phone') is-invalid @enderror" name="user_phone_mask" value="{{ old('user_phone_mask') }}">
                                @if ($errors->has('user_phone'))
                                <span class="invalid-feedback d-block" data-yk=""  role="yk-alert">
                                <strong>{{ $errors->first('user_phone') }}</strong>
                                </span>
                                @endif 
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-floating__group   @if($smsActivePackage >= 1) mb-0  @endif">                                
                                <input id="user_email" type="text" class="form-control form-floating__field @error('user_email') is-invalid @enderror" name="user_email" value="{{ old('user_email') }}" required autofocus>
                                <label class="form-floating__label">{{ __('LBL_EMAIL') }}</label>
                                @if ($errors->has('user_email'))
                                <span class="invalid-feedback" data-yk=""  role="yk-alert">
                                <strong>{{ $errors->first('user_email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($smsActivePackage != 0)
                        <div class="divider">
                            <span>{{__('LBL_OR_CONTINUE_WITH')}}</span>
                        </div>
                        @if(!empty($_GET['via']) && $_GET['via']=='phone' && $smsActivePackage >= 1)
                            <div class="floated-txt text-center">
                                <a href="{{ route('forgotPassword') }}" class="loginVia"><i class="icn"><svg class="svg">
                                    <use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#phone"
                                        href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#phone"></use>
                                </svg></i>{{__('LNK_USE_EMAIL_INSTEAD_LARGE')}}</a>
                            </div>
                        @else
                            <div class="floated-txt text-center">
                                <a href="{{ route('forgotPassword') . '?via=phone' }}" class="loginVia"><i class="icn"><svg class="svg">
                                    <use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#email"
                                        href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#email"></use>
                                </svg></i>{{__('LNK_USE_PHONE_INSTEAD_LARGE')}}</a>            
                            </div>
                        @endif
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" disabled="true" class="btn btn-brand btn-block gb-btn gb-btn-primary btn-submit" name="YK-forgotBtn">{{ __('BTN_FORGOT_PASSWORD_SUBMIT') }} <i class="arrow la la-long-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            
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
         $(document).on('click', '[name="YK-forgotBtn"]', function(e) {
            e.preventDefault();
            var obj = $('#YK-forgotForm');
            loader($(this));
            obj.submit();
        });
        $(document).on('click', '.YK-resendOTP', function(){  
            $.post("{{route('resetPhone')}}", {
                'phone': $('input[name="user_phone"]').val(),
                "_token": "{{ csrf_token() }}"
            }, function (res) {
                toastr.success('', res.message);
            });
        });
        $(document).on('click', '.YK-verifyOTP', function(){  
            $.post("{{route('adminVerifyOtp')}}", {
                'phone': $('input[name="user_phone"]').val(),
                'otp': $('input[name="otp"]').val(),
                "_token": "{{ csrf_token() }}"
            }, function (res) {
                if (res.status) {
                    toastr.success('', res.message);
                }
                toastr.error('', res.message);
            });
        });
        function sendResetPasswordOtp() 
        {		
            $('button[type="submit"]').attr("disabled", "disabled");
            let obj = $('#forgot_password_phone_form');
            obj.find('input').removeClass('is-invalid');
            obj.find('.invalid-feedback').remove();
            $.ajax({
                url: "{{ route('resetPhone') }}",
                type: "post",
                data: obj.serialize(),
                success: function(res) {
                    if (res.status == true) {
                        toastr.success('', res.message);
                        $(".yk-forgotPassword").empty();
                        $(".yk-forgotPassword").html(res.data.html);
                    }
                    if (res.status == false) {
                        $('button[type="submit"]').removeAttr("disabled");
                        toastr.error(res.data.user_phone);
                    }
                },
                error: function(res) {
                    let errors = res.responseJSON.errors;
                    
                }
            });
        } 
        var emailFormat = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        $("input[type='text'], input[type='tel']").on("keyup", function() {
            @if(!empty($_GET['via']) && $_GET['via']=='phone' && $smsActivePackage >= 1)
                if ($("#user_phone_mask").val() == '' || checkIfComplete($('#user_phone_mask')) === false) {
                    $("button[name='YK-forgotBtn']").attr("disabled", true);
                } else {
                    $(".invalid-feedback").remove();
                    $("button[name='YK-forgotBtn']").attr("disabled", false);
                }
            @else
                if ($("#user_email").val() == '' || emailFormat.test($("#user_email").val().trim()) == false) {
                    $("button[name='YK-forgotBtn']").attr("disabled", true);
                } else {
                    $(".invalid-feedback").remove();
                    $("button[name='YK-forgotBtn']").attr("disabled", false);
                }
            @endif
        });
        checkIfComplete = function(el) {
            if ($(el).inputmask("isComplete")) {
                return true
            } else {
                return false
            }
        }
        @if($smsActivePackage >= 1 && !empty($_GET['via']) && $_GET['via']=='phone')
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
        </script>
        @endsection
        @endsection