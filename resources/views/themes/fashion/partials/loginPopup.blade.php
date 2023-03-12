@php
$configurations = getConfigValues([
    'FACEBOOK_CLIENT_STATUS',
    'GOOGLE_CLIENT_STATUS',
    'INSTAGRAM_CLIENT_STATUS',
    'BUSINESS_NAME'
]);
@endphp
<div class="modal-dialog modal-dialog-centered login-dialog" data-yk=""  role="yk-document">
	<div class="modal-content">
		<div class="modal-body"> <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<div class="login-page popup">
				<div class="login-head">
					<h1>{{__('LBL_WELCOME_TO')}} {{$configurations['BUSINESS_NAME']}} </h1>
				</div>
				<div class="login-body">					
					@if($configurations['FACEBOOK_CLIENT_STATUS'] == 1 || $configurations['GOOGLE_CLIENT_STATUS'] == 1 || $configurations['INSTAGRAM_CLIENT_STATUS'] == 1)
					<div class="button-wrap">
						@if($configurations['FACEBOOK_CLIENT_STATUS'] == 1)
							<button type="button" class="btn btn-social btn-facebook" onclick="window.location.href = '{{url('redirect/facebook')}}';">
								<i class="icn"><svg class="svg"><use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#facebook" href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#facebook"></use></svg></i>
							</button>
						@endif
						@if($configurations['GOOGLE_CLIENT_STATUS'] == 1)
							<button type="button" class="btn btn-social btn-google" onclick="window.location.href = '{{url('redirect/google')}}';">
								<i class="icn"><svg class="svg"><use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#google" href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#google"></use></svg></i>
							</button>
						@endif
						@if($configurations['INSTAGRAM_CLIENT_STATUS'] == 1)
							<button type="button" class="btn btn-social btn-instagram" onclick="window.location.href = '{{url('redirect/instagram')}}';">
								<i class="icn"><svg class="svg"><use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#instagram"href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#instagram"></use></svg></i>
							</button>
						@endif
					</div>

					<div class="divider">
						<span>{{__('LBL_OR_CONTINUE_WITH')}}</span>
					</div>
					@endif
					<form id="login-form" onsubmit="return loginPopup()" method="POST" action="javascript:void(0)" class="form form-login form-floating">
						@csrf
						<input name="via" type="hidden" id="via" value="email">
						<div class="YK-loginViaContainer">
							@include('themes.'.config('theme').'.partials.loginVia', ['via' => 'email'])
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<button type="submit"
										class="btn btn-brand btn-block btn-submit gb-btn gb-btn-primary" id="login" name="loginBtn">{{ __('BTN_LOGIN') }}</button>
								</div>
							</div>
						</div>
					</form>
					
				</div>
				<div class="login-foot">
					<p class="no-account">
					<a href="{{ route('forgotPassword') }}" class="link forgot-txt">{{ __('LNK_FORGOT_PASSWORD') }}?</a>
					<span class="pipe">|</span>
					<a class="link" href="{{ route('register') }}">{{ __('LNK_REGISTER') }}</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<script>		
	$(document).ready(function(){

		$(document).on('click', '#togglePassword', function(){
			var type = $('#password').attr("type") === 'password' ? 'text' : 'password';
			$('#password').attr("type", type);
			$(this).toggleClass('fa-eye-slash');
		});

		setLoginValidation();
		setLoginTrigger();
		$(document).on('click', '.YK-loginVia', function(){
			var via = $(this).data('via');
			$('[name="via"]').val(via);	
			$.ajax({
				url: "{{ url('/login') }}/" + via,
				type: "get",
				success: function(res) {
					$('.YK-loginViaContainer').html(res);
					setLoginValidation();
					setLoginTrigger();
					floatingFormFix();
				}
			});
		});
	});
	function loginPopup() 
	{
		$("#login").attr("disabled", true);
		let submitBtn = $("button[name='loginBtn'");
		loader(submitBtn);
		let obj = $('#login-form');
		$.ajax({
			url: "{{ route('frontendLogin') }}",
			type: "post",
			data: $('#login-form').serialize(),
			success: function(res) {
				if (res.status == true) {
					window.location.reload();
				}
				if (res.status == false) {
					loader(submitBtn, true);
                    if (typeof res.data.url != 'undefined') {
						window.location.href = baseUrl + res.data.url;
						return false;
                    }
					$("#login").attr("disabled", false);
					obj.find('input').removeClass('is-invalid');
					obj.find('.invalid-feedback').remove();
					toastr.error('Error', res.message);
				}
			},
			error: function(xhr, status, error) {
				loader(submitBtn, true);
				if(xhr.status == 429) {
					toastr.error("{{ __('LBL_TOO_MANY_ATTEMPTS') }}");
				}
				$("#login").attr("disabled", false);
				let errors = xhr.responseJSON.errors;
				validateErrors(errors, obj);
			}
        });
	}
</script>