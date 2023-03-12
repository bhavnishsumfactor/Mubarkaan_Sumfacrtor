@if($via == 'email')
<div id="email" class="form-group form-floating__group YK-loginViaParentCls mb-3">
    <input type="text" class="form-control form-floating__field @error('username') is-invalid @enderror" id="username"
        value="{{config('app.userName') }}" name="username">
    <label class="form-floating__label">{{ __('LBL_EMAIL') }}</label>
</div>
@else
<div id="phone" class="form-group form-floating__group mb-3 YK-loginViaParentCls ">
    <input type="hidden" name="user_phone_country_code" value="us">
    <input id="user_phone" type="hidden" name="user_phone" value="{{ old('username') }}">
    <input id="user_phone_mask" type="tel" data-inputmask-clearmaskonlostfocus="false"
        class="form-control  @error('username') is-invalid @enderror" name="user_phone_mask">
    @if ($errors->has('username'))
    <span class="invalid-feedback d-block" data-yk="" role="yk-alert">
        <strong>{{ $errors->first('username') }}</strong>
    </span>
    @endif
</div>
<script>
    var input = document.querySelector("#user_phone_mask");
    if (typeof iti != 'undefined') {
        iti.destroy();
    }
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
</script>
@endif

<div class="form-group form-floating__group password-field">
    <input type="password" class="form-control form-floating__field @error('password') is-invalid @enderror"
        value="{{config('app.userPassword') }}" id="password" name="password">
    <label class="form-floating__label">{{ __('LBL_PASSWORD') }}</label>
    <i class="far fa-eye password-toggle" id="togglePassword"></i>
</div>
<div class="row">
    <div class="col col-12 @if(isset($isGuest)) col-lg-4 @else col-lg-6 @endif">
        <div class="form-group">
            @if(!isset($isGuest))
            <label class="checkbox"><input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked'
                    : '' }}>{{ __('LBL_REMEMBER_ME') }}
                <i class="input-helper"></i>
            </label>
            @endif
        </div>
    </div>
    <div class="col col-12 @if(isset($isGuest)) col-lg-8 @else col-lg-6 @endif text-lg-right">
        <div class="form-group">
            @if($via == 'email')
            @if($smsActivePackage >= 1)
            <div class="floated-txt">
                <a href="javascript:;" class="YK-loginVia loginVia" data-via="phone"><i class="icn"><svg class="svg">
                            <use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#phone"
                                href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#phone"></use>
                        </svg></i>{{__('LNK_USE_PHONE_INSTEAD')}}</a>
            </div>
            @endif
            @else
            <div class="floated-txt">
                <a href="javascript:;" class="YK-loginVia loginVia" data-via="email"><i class="icn"><svg class="svg">
                            <use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#email"
                                href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#email"></use>
                        </svg></i>{{__('LNK_USE_EMAIL_INSTEAD')}}</a>
            </div>
            @endif
        </div>
    </div>
</div>