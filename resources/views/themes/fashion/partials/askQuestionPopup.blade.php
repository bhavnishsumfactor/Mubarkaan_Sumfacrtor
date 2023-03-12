
<div class="modal-dialog  modal-dialog-centered">
    <!-- Modal content-->  
    <div class="modal-content">
		<div class="modal-header text-center">
			<h4 class="modal-title">{{ __('LBL_ASK_A_QUESTION') }}</h4>
        	<button type="button" class="close" data-dismiss="modal">&times;</button>
      	</div>
		<div class="scroll-y yk-perfectScrollbar"  style="position: relative;max-height: 343px;">
			<div class="modal-body">
				<form  class="form" method="post" id="askQuestion" action="javascript:void(0)">
					@csrf
					<input type="hidden" name="thread_product_id" value="{{ $productId }}">
					<div class="form-group">
						<label>{{ __('LBL_SUBJECT') }}</label>
						<input type="text" class="form-control @error('subject') is-required @enderror" id="subject" name="subject">
						
					</div>
					<div class="form-group">
						<label>{{ __('LBL_WRITE_YOUR_QUERY') }}</label>
						<textarea class="form-control  @error('message') is-required @enderror" style="resize: none;" id="description" name="message" cols="30" rows="10"></textarea>
					</div>
				</form>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-brand btn-wide gb-btn gb-btn-primary" disabled="true" name="submitQuestionBtn" type="button" onclick="askQuestionSubmit()">{{ __('BTN_ASK_QUESTION_SUBMIT') }}</button>
		</div>
    </div>
</div>
<script>	
	function askQuestionSubmit()
	{
		let productId = '{{ $productId }}';
		let varientCode = '{{ $varientCode }}';
		let submitBtn = $('button[name="submitQuestionBtn"]');
		loader(submitBtn);
		let obj = $('#askQuestion');		
		let formData = $('#askQuestion').serializeArray();
		formData.push({ 'name': 'thread_product_variant','value' : varientCode });
		formData.push({ 'name': 'thread_product_id','value' : productId });
		$.ajax({
			url: "{{ route('submitQuestion') }}",
			type: "post",
			data: formData,
			success: function(res) {
				loader(submitBtn, true);
				if (res.status == true) {
					$("#dataModal .modal-content").html('<div class="modal-body"><div class="my-1 pb-5 text-center"><div class="success-animation"><div class="svg-box"><svg class="circular green-stroke"><circle class="path" cx="75" cy="75" r="50" fill="none" stroke-width="5" stroke-miterlimit="10" /></svg><svg class="checkmark green-stroke"><g transform="matrix(0.79961,8.65821e-32,8.39584e-32,0.79961,-489.57,-205.679)"><path class="checkmark__check" fill="none" d="M616.306,283.025L634.087,300.805L673.361,261.53" /></g></svg></div></div><p>'+res.message+'</p></div></div>');
					setTimeout(() => {
						$("#dataModal").empty();
						$('#dataModal').modal("hide");
					}, 2000);
				}
				if (res.status == false) {
					$("#submit").attr("disabled", true);
					obj.find('input').removeClass('is-invalid');
					obj.find('.invalid-feedback').remove();
					toastr.error('Error', res.message);
				}
			},
			error: function(xhr, status, error) {
				loader(submitBtn, true);
				$("#submit").attr("disabled", false);
				let errors = xhr.responseJSON.errors;
				validateErrors(errors, obj);
			}
        });
	}
	if (!$('.yk-perfectScrollbar').hasClass('ps')) {
    	new PerfectScrollbar('.yk-perfectScrollbar');
  	}
</script>
