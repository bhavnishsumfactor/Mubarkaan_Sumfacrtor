<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header text-center">
			<h4 class="modal-title">{{ __('LBL_ADD_NEW_ADDRESS') }}</h4>
        	<button type="button" class="close" data-dismiss="modal">&times;</button>
      	</div>
		<div >
			<div class="modal-body">
				<form class="form form-floating" id="YK-shippingAddress1">
				<input type="hidden" name="addr_id" value="">
				@include('themes.'.config('theme').'.partials.checkoutForm',['shipping' => true])
				<div>
					<label class="checkbox">
						<input type="checkbox" id="agreeCondition" value="1">
							<i class="input-helper"></i> {{__('LBL_I_AGREE_TO_THE') }}
							<a target="_blank" href="{{getPageByType('terms')}}">{{__('LNK_TERMS_CONDITIONS') }}</a>
					</label>
				</div>
				</form>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-outline-brand" type="button" data-dismiss="modal">{{ __('BTN_ADDRESS_CANCEL') }}</button>
			<button class="btn btn-brand" id="YK-saveaddress" name="YK-saveaddress" type="button" onclick="saveAddress('shippingAddress1')">{{ __('BTN_ADDRESS_SUBMIT') }}</button>
		</div>
    </div>
</div>
<script>
(function() {
	$("#YK-saveaddress").addClass("disabled");
    $("#YK-saveaddress").attr("disabled", true);
})()
$("#agreeCondition").click(function() {
	if($(this).prop("checked") == true){
		$("#YK-saveaddress").attr("disabled", false);
		$("#YK-saveaddress").removeClass("disabled");
	}else{
		$("#YK-saveaddress").addClass("disabled");
		$("#YK-saveaddress").attr("disabled", true);
	}
});

</script>