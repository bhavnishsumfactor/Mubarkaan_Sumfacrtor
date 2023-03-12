@extends('layouts.admin.public')

@section('content')
<form class="form form--login" id="login_form" onsubmit="return login()" method="POST" action="javascript:void(0)">
    @csrf
    <h3 class="mb-4">{{ __('LBL_LOGIN') }}</h3>
    <div class="form-group">
        <input class="form-control @error('username') is-invalid @enderror" id="username" autocomplete="off"
            name="username" placeholder="{{ __('PLH_USERNAME') . ' / ' . __('PLH_EMAIL') }}" type="text"
            value="{{ old('username') ? old('username') : config('app.adminName') }}">
        @error('username')
        <span class="invalid-feedback" data-yk="" role="yk-alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <input class="form-control @error('password') is-invalid @enderror" id="password" name="password"
            placeholder="{{ __('PLH_PASSWORD') }}" type="password" autocomplete="new-password"
            value="{{config('app.adminPassword')}}">
        @error('password')
        <span class="invalid-feedback" data-yk="" role="yk-alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="row pb-3">
        <div class="col-6">
            <div class="field-set ">
                <label class="switch switch--sm remember-me">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span></span>{{ __('LBL_REMEMBER_ME') }} </label>
            </div>
        </div>
        <div class="col-6 pt-1">
            <div class="field-set text-right">
                <a href="{{ route('adminResetPassword') }}" class="link">{{ __('LNK_FORGOT_PASSWORD') }}?</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="submit" disabled="disabled" class="btn btn-brand btn-block gb-btn gb-btn-primary"
                name="YK-LoginBtn">{{__('BTN_LOGIN')}}</button>
        </div>
    </div>
</form>
@section('scripts')
<script>
    function login() {
        $('button[type="submit"]').attr("disabled", "disabled");
        let submitBtn = $('button[name="YK-LoginBtn"]');
        let obj = $('#login_form');
        submitBtn.addClass("gb-is-loading");
        obj.find('input').removeClass('is-invalid');
        obj.find('.invalid-feedback').remove();
        $.ajax({
            url: "{{ route('adminLogin') }}",
            type: "post",
            data: obj.serialize(),
            success: function(res) {
                if (res.status == true) {
                    window.location.href = adminBaseUrl + res.data.url;
                }
                if (res.status == false) {
                    submitBtn.removeAttr("disabled");
                    submitBtn.removeClass("gb-is-loading");
                    validateErrors(res.data, obj);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (jqXHR.status == 419) {
                    toastr.error("{{ __('NOTI_ADMIN_LOGIN_SOMETHING_WENT_WRONG') }}");
                    window.location.reload();
                }
            }
        });
    }
    $(document).ready(function() {
        $('input[name="username"], input[name="password"]').on('keyup', function() {
            if ($("#username").val() == '' || $("#password").val() == '') {
                $('button[type="submit"]').attr('disabled', 'disabled');
            } else {
                $('button[type="submit"]').removeAttr('disabled');
            }
        });
        if ($("#username").val() != '' && $("#password").val() != '') {
            $('button[type="submit"]').removeAttr('disabled');
        }
    });
</script>
@endsection
@endsection