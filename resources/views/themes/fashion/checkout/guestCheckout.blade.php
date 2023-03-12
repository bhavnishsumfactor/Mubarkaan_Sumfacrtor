@php
    $configurations = getConfigValues([
        'FACEBOOK_CLIENT_STATUS',
        'GOOGLE_CLIENT_STATUS',
        'INSTAGRAM_CLIENT_STATUS'
    ]);
@endphp
<div class="step__section">
    
    
    <div class="step__section__head yk-loginClick">
    <h5 class="step__section__head__title ">{{ __('LBL_ALREADY_HAVE_ACCOUNT') }}? </h5>
    <a class="link-text" href="#login-quick" id="yk-login-quick-btn">{{ __('LNK_CLICK_HERE') }} 
        <i class="fa fa-question-circle ml-1 d-none d-md-block" data-container="body" data-toggle="popover" data-placement="top" data-content="{{__('LBL_CLICK_HERE_TO_LOGIN')}}"></i> </a> 
    </div>

        <div class="step__section__head yk-registerClick">
    <h5 class="step__section__head__title">{{ __("LBL_DIDNT_HAVE_AN_ACCOUNT") }}? </h5>
    <a class="link-text" href="{{ route('register') }}">{{ __('LNK_REGISTER_NOW') }} 
        <i class="fa fa-question-circle ml-1 d-none d-md-block" data-container="body" data-toggle="popover" data-placement="top" data-content="{{__('LBL_CLICK_HERE_TO_SIGNUP')}}"></i> </a> 
    </div>
    
   
    <div class="collapse @if(!empty($errors) && count($errors) > 0) {{ 'show' }}@endif" id="login-quick">
       
                   <form id="login-form" onsubmit="return loginPopup()" method="POST" action="javascript:void(0)" class="form form-login form-floating">
                    @csrf                    
                    <h6 class="">{{ __('LBL_RETURNING_CUSTOMER') }}</h6> 
                    <input name="via" type="hidden" id="via" value="email">
                    <div class="YK-loginViaContainer">
						@include('themes.'.config('theme').'.partials.loginVia', ['via' => 'email', 'isGuest' => true])
					</div>
                    <div class="row">
                        <div class="col-md-6">                           
                                <button type="submit" class="btn btn-brand btn-block btn-submit" name="guestCheckoutLgn" id="guestCheckoutLgn">
                                {{ __('BTN_LOGIN') }} <i class="arrow la la-long-arrow-right"></i>
                                </button> 
                                </div>                            
                           
                            <div class="col-md-6 mt-2 mt-md-0">
                                <button type="button" class="btn btn-outline-brand btn-block" id="yk-guest-checkout-btn"  data-target="#guestaddress-quick">{{ __('BTN_CONTINUE_AS_GUEST') }} <i class="arrow la la-long-arrow-right"></i>
                            </button></div>
                       
                    </div>
                </form>
               
                    @if($configurations['FACEBOOK_CLIENT_STATUS'] == 1 || $configurations['GOOGLE_CLIENT_STATUS'] == 1 || $configurations['INSTAGRAM_CLIENT_STATUS'] == 1)
                    <div class="divider">
                        <span>Or</span>
                    </div>
                    <div class="button-wrap">
                        @if($configurations['FACEBOOK_CLIENT_STATUS'] == 1)
                        <button type="button" class="btn btn-social btn-facebook" onclick="window.location.href = '{{url('redirect/facebook')}}';">
                            <i class="icn">
                                <svg class="svg"><use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#facebook" href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#facebook"></use>
                                </svg>
                            </i>
                        </button>
                        @endif
                        @if($configurations['INSTAGRAM_CLIENT_STATUS'] == 1)
                            <button type="button" class="btn btn-social btn-instagram" onclick="window.location.href = '{{url('redirect/instagram')}}';">
                                <i class="icn"><svg class="svg"><use xlink:href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#instagram"href="{{ asset('yokart/'.config('theme')) }}/media/retina/sprite.svg#instagram"></use></svg></i>
                            </button>
                        @endif
                        @if($configurations['GOOGLE_CLIENT_STATUS'] == 1)
                            <button type="button" class="btn btn-social btn-google" onclick="window.location.href = '{{url('redirect/google')}}';">
                                <i class="icn">
                                    <svg class="svg">
                                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#google"
                                            href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#google">
                                        </use>
                                    </svg>
                                </i>
                            </button>
                        @endif
                        
                    </div>
                    @endif
            
        
    </div>
    <div class="collapse show" id="guestaddress-quick">
        <span class="YK-newAddress" style="display:@if($hide == true){{'none'}}@endif">
            @include('themes.'.config('theme').'.partials.checkoutForm', ['shipping'=>true, 'digital' => false]) 
            @auth
                <div>
                    <label class="checkbox">
                        <input name="addr_shipping_default" type="checkbox" value="1" >{{__('LBL_USE_AS_DEFAULT_ADDRESS')}} <i class="input-helper"></i>
                    </label>
                </div>
            @endauth
        </span>
    </div>
</div>
<script>
    var togglePassword = document.querySelector('#togglePassword');
	var password = document.querySelector('#password');
    togglePassword.addEventListener('click', function (e) {
        var type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
	});
    $(".yk-registerClick").hide();
    $("#yk-login-quick-btn").click(function(){
        if(!$("#login-quick").hasClass('show')) {
            $(".yk-loginClick").hide();
            $(".yk-agreeTerms").hide();
            $(".yk-registerClick").show();
            $("#login-quick").addClass('show');
            $("#guestaddress-quick").removeClass('show');
            $(".checkout-actions").hide();
        }
    });
    $("#yk-guest-checkout-btn").click(function(){
        if ($("#login-quick").hasClass('show')) {
            $("#login-quick").removeClass('show');
            $("#guestaddress-quick").addClass('show');
            $(".yk-loginClick").show();
            $(".yk-agreeTerms").show();
            $(".yk-registerClick").hide();
            $(".checkout-actions").show();
        }
    });
    $(document).ready(function(){
	
		$(document).on('click', '.YK-loginVia', function(){
			var via = $(this).data('via');
			$('[name="via"]').val(via);	
			$.ajax({
				url: "{{ url('/login') }}/" + via,
				type: "get",
				success: function(res) {
					$('.YK-loginViaContainer').html(res);
					setGuestLoginValidation();
					setGuestLoginTrigger();
					floatingFormFix();
				}
			});
		});
	});    
    function loginPopup() 
	{
        $("#login").attr("disabled", true);
        $("#guestCheckoutLgn").addClass("gb-btn gb-btn-primary gb-is-loading");
		$("#login").addClass("spinner spinner--right spinner--md spinner--light");
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
                    if (typeof res.data.url != 'undefined') {
						window.location.href = baseUrl + res.data.url;
						return false;
                    }
					$("#login").removeClass("spinner spinner--right spinner--md spinner--light");
					$("#login").attr("disabled", false);
					obj.find('input').removeClass('is-invalid');
					obj.find('.invalid-feedback').remove();
					toastr.error('Error', res.message);
				}
			},
			error: function(xhr, status, error) {
				if(xhr.status == 429) {
					toastr.error("{{ __('LBL_TOO_MANY_ATTEMPTS') }}");
				}	
				$("#login").removeClass("spinner spinner--right spinner--md spinner--light");			
				$("#login").attr("disabled", false);
				let errors = xhr.responseJSON.errors;
				validateErrors(errors, obj);
			}
        });
	}
</script>