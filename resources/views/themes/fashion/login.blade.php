@extends('layouts.lite')
@php
$configurations = getConfigValues([
'FACEBOOK_CLIENT_STATUS',
'GOOGLE_CLIENT_STATUS',
'INSTAGRAM_CLIENT_STATUS',
'FRONTEND_LOGO_RATIO',
'BUSINESS_NAME'
]);
$queryParams = !empty(request()->get('referralcode')) ? '?referralcode=' . request()->get('referralcode') : '';
@endphp
@section('content')
<div class="login-page">
    <div class="login-head">
        <div class="login-logo-wrap">
            @php
            $darkLogo = getFrontendDarkLogo();
            $logo = getFrontendLogo() != '' ? getFrontendLogo() : noImage('160x40.png');
            $ratio = $configurations['FRONTEND_LOGO_RATIO'] ?? '16:9';
            @endphp
            <a href="{{url('')}}" class="login-logo">
                @include('themes.'.config('theme').'.shortcodes.logo')
            </a>
        </div>
        <h1>{{__('LBL_WELCOME_TO')}} {{$configurations['BUSINESS_NAME']}}</h1>
    </div>
    <div class="login-body">
        @if($configurations['FACEBOOK_CLIENT_STATUS'] == 1 || $configurations['GOOGLE_CLIENT_STATUS'] == 1 ||
        $configurations['INSTAGRAM_CLIENT_STATUS'] == 1)
        <div class="button-wrap">
            @if($configurations['FACEBOOK_CLIENT_STATUS'] == 1)
            <button type="button" class="btn btn-social btn-facebook"
                onclick="window.location.href = '{{url('redirect/facebook') . $queryParams}}';">
                <i class="icn"><svg class="svg">
                        <use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#facebook"
                            href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#facebook"></use>
                    </svg></i>
            </button>
            @endif
            @if($configurations['GOOGLE_CLIENT_STATUS'] == 1)
            <button type="button" class="btn btn-social btn-google"
                onclick="window.location.href = '{{url('redirect/google') . $queryParams}}';">
                <i class="icn"><svg class="svg">
                        <use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#google"
                            href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#google"></use>
                    </svg></i>
            </button>
            @endif
            @if($configurations['INSTAGRAM_CLIENT_STATUS'] == 1)
            <button type="button" class="btn btn-social btn-instagram"
                onclick="window.location.href = '{{url('redirect/instagram') . $queryParams}}';">
                <i class="icn"><svg class="svg">
                        <use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#instagram"
                            href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#instagram"></use>
                    </svg></i>
            </button>
            @endif
        </div>

        <div class="divider">
            <span>{{__('LBL_OR_CONTINUE_WITH')}}</span>
        </div>
        @endif
        <form id="login-form" method="POST" action="{{ route('frontendLogin') }}" class="form form-login form-floating">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    @if(!empty($_GET['via']) && $_GET['via']=='phone' && $smsActivePackage >= 1)
                    <input name="via" type="hidden" id="via" value="phone">
                    <div class="form-group form-floating__group">
                        <input type="hidden" name="user_phone_country_code" value="us">
                        <input id="user_phone" type="hidden" name="user_phone" value="{{ old('username') }}">
                        <input id="user_phone_mask" type="tel" data-inputmask-clearmaskonlostfocus="false"
                            class="form-control @error('username') is-invalid @enderror" name="user_phone_mask">
                        @if ($errors->has('username'))
                        <span class="invalid-feedback d-block" data-yk="" role="yk-alert">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                    @else
                    <input name="via" type="hidden" id="via" value="email">
                    <div class="form-group form-floating__group">

                        <input type="text"
                            class="form-control form-floating__field @error('username') is-invalid @enderror"
                            id="username" name="username" value="{{ old('username') ??  config('app.userName')  }}"
                            required autofocus>
                        <label class="form-floating__label">{{ __('LBL_EMAIL') }}</label>
                        @if ($errors->has('username'))
                        <span class="invalid-feedback" data-yk="" role="yk-alert">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-floating__group password-field">
                        <input type="password"
                            class="form-control form-floating__field @error('password') is-invalid @enderror"
                            id="password" name="password" required autocomplete="new-password"
                            value="{{config('app.userPassword') }}">
                        <label class="form-floating__label">{{ __('LBL_PASSWORD') }}</label>
                        <i class="far fa-eye password-toggle" id="togglePassword"></i>
                        @if ($errors->has('password'))
                        <span class="invalid-feedback" data-yk="" role="yk-alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col col-12 col-lg-6">
                    <div class="form-group">
                        <label class="checkbox"><input type="checkbox" name="remember" id="remember" {{ old('remember')
                                ? 'checked' : '' }}>{{ __('LBL_REMEMBER_ME') }}
                            <i class="input-helper"></i>
                        </label>
                    </div>
                </div>
                <div class="col col-12 col-lg-6 text-lg-right">
                    <div class="form-group">
                        @if($smsActivePackage != 0)
                        @if(!empty($_GET['via']) && $_GET['via']=='phone' && $smsActivePackage >= 1)
                        <div class="floated-txt">
                            <a href="{{ route('login') }}" class="loginVia">
                                <i class="icn"><svg class="svg">
                                        <use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#email"
                                            href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#email">
                                        </use>
                                    </svg></i>{{__('LNK_USE_EMAIL_INSTEAD')}}</a>
                        </div>
                        @else
                        <div class="floated-txt">
                            <a href="{{ route('login') . '?via=phone' }}" class="loginVia">
                                <i class="icn"><svg class="svg">
                                        <use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#phone"
                                            href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#phone">
                                        </use>
                                    </svg></i>{{__('LNK_USE_PHONE_INSTEAD')}}</a>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-brand btn-block gb-btn gb-btn-primary btn-submit "
                            id="YK-loginBtn" name="loginBtn">{{ __('BTN_LOGIN') }}</button>
                    </div>
                </div>
            </div>
        </form>


    </div>
    <div class="login-foot">
        <p class="no-account">
            <a href="{{ route('forgotPassword') }}" class="link forgot-txt">{{ __('LNK_FORGOT_PASSWORD') }}?</a>
            <span class="pipe">|</span>
            <a class="link" href="{{ route('register') . $queryParams }}">{{ __('LNK_REGISTER') }}</a>
        </p>
    </div>
</div>
@section('scripts')
<script>
    setTimeout(function() {
        floatingFormFix();
    }, 200);

    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function(e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
    });

    setInputFilter = function(textbox, inputFilter) {
        ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(
            event) {
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
    setLoginValidation = function() {
        let conditionId = ($("#via").val() == "email") ? 'username' : 'user_phone_mask';
        if ($("#" + conditionId).val() == '' || $("#password").val() == '') {
            $("#login-form").find('[name="loginBtn"]').attr("disabled", true);
        } else {
            $("#login-form").find('[name="loginBtn"]').attr("disabled", false);
        }
        $("input[type='text'], [name='user_phone_mask'], textarea, input[type='password']").on("keyup", function() {
            if ($("#" + conditionId).val() == '' || $("#password").val() == '') {
                $("#login-form").find('[name="loginBtn"]').attr("disabled", true);
            } else {
                $("#login-form").find('[name="loginBtn"]').attr("disabled", false);
            }
        });
    }
    setLoginValidation();
    $(document).on('click', '#YK-loginBtn', function(e) {
        e.preventDefault();
        let submitBtn = $("button[name='loginBtn']");
        loader(submitBtn);
        let obj = $('#login-form');
        obj.submit();
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

    function invokeChange() {
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