@extends('layouts.admin.public')

@section('content')
    <form class="form form--login" id="reset_password_form" onsubmit="return resetPassword()" method="POST" action="javascript:void(0)" >
        @csrf
        <h3 class="mb-4">{{ __('LBL_RESET_PASSWORD') }}</h3>
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <p>{{$email}}</p>
            </div>
            <div class="form-group" style="position: relative;">
               
                <input class="form-control @error('password') is-invalid @enderror" id="password" name="password"  placeholder="{{ __('PLH_NEW_PASSWORD') }}" type="password" autocomplete="new-password" tabindex="5">
                <i class="field-validation-icon">
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
                @error('password')
                    <span class="invalid-feedback" data-yk=""  role="yk-alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input class="form-control" id="password-confirm" name="password_confirmation" placeholder="{{ __('PLH_CONFIRM_PASSWORD') }}" type="password" autocomplete="no" tabindex="6">
                <i class="field-validation-icon">
                    <img class="YK-isInvalidConfirmPassword d-none" src="{{url('yokart/'. config('theme'))}}/media/retina/cancel.svg" width="16px" height="16px" data-yk="" alt="">
                    <img class="YK-isValidConfirmPassword d-none" src="{{url('yokart/'. config('theme'))}}/media/retina/check.svg" width="16px" height="16px" data-yk="" alt="">
                </i>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" disabled="disabled" class="btn btn-brand btn-block gb-btn gb-btn-primary"  name="YK-ResetBtn">{{ __('BTN_RESET_PASSWORD') }}</button>
                </div>
            </div>
            <p class="text-center py-4"><a href="{{route('adminLogin')}}" class="link">{{__('LBL_BACK_TO_LOGIN')}}</a></p>
    </form>
@section('scripts')
<script>
    function resetPassword() 
	{		
        let submitBtn = $('button[name="YK-ResetBtn"]');
		submitBtn.attr("disabled", "disabled");
        submitBtn.addClass("gb-is-loading");
        let obj = $('#reset_password_form');
        obj.find('input').removeClass('is-invalid');
        obj.find('.invalid-feedback').remove();
		$.ajax({
			url: "{{ route('adminPasswordUpdate') }}",
			type: "post",
			data: obj.serialize(),
			success: function(res) {                
                submitBtn.removeClass("gb-is-loading");
				if (res.status == true) {
                    toastr.success('', res.message);                  
				}
				if (res.status == false) {
                    toastr.error('', res.message);  
                }
                setTimeout(() => {
                    window.location.href = adminBaseUrl + '/login';
                }, 2000);
            },
            error: function(res) {
				let errors = res.responseJSON.errors;
                validateErrors(errors, obj);
			}
        });
    }
    $(document).ready(function(){
        $('input').on('keyup', function() { 
            var empty = false;
            $('input').each(function() {
                if ($(this).val() == '') {
                    empty = true;
                }
            });
            if (empty) {
                $('button[type="submit"]').attr('disabled', 'disabled'); 
            } else {
                $('button[type="submit"]').removeAttr('disabled'); 
            }
        });

        $(document).on('click', 'input#user_password', function() {
            $('#pswd_info').show();
        });

        $('input#password').keyup(function() {
            let pswd = $(this).val();
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

        $('input#password-confirm').keyup(function() {
            var newPass = $(this).val();
            var oldPass = $('input#password').val();
            if(oldPass == '' ||  newPass == ''){
                $('.YK-isInvalidConfirmPassword').addClass('d-none');
                $('.YK-isValidConfirmPassword').addClass('d-none');
                return false;
            }
            if(oldPass == newPass){
                $('.YK-isInvalidConfirmPassword').addClass('d-none');
                $('.YK-isValidConfirmPassword').removeClass('d-none');
                $('button[type="submit"]').removeAttr('disabled');
            }else{
                $('.YK-isInvalidConfirmPassword').removeClass('d-none');
                $('.YK-isValidConfirmPassword').addClass('d-none');
                $('button[type="submit"]').attr('disabled', 'disabled');
            }
        });
    }); 
</script>
@endsection
@endsection
