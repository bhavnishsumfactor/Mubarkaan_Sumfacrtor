<template>
		<b-modal id="send_mail" centered title="BootstrapVue">
			<template v-slot:modal-header="{ close }">
				<h5 class="modal-title" id="exampleModalLabel">{{ $t('LBL_COMPOSE_MAIL')}}</h5>
				<button type="button" class="close" @click="$bvModal.hide('send_mail')"></button>
			</template>
	  			<div class="form-group row">
		  			<label class="col-xl-3 col-lg-3 col-form-label">{{ $t('LBL_COMPOSE_MAIL_SUBJECT') }}  <span class="required">*</span></label>
		  				<div class="col-lg-9 col-xl-9">
			  				<input type="text" v-model="emailData.subject" name="subject" v-validate="'required'" :data-vv-as="$t('LBL_COMPOSE_MAIL_SUBJECT')" data-vv-validate-on="none" class="form-control" />
			  				<span v-if="errors.first('subject')" class="error text-danger">{{ errors.first('subject') }}</span>
		  				</div>
	  		    </div>
		  		<div class="form-group row">
			  			<label class="col-xl-3 col-lg-3 col-form-label">{{ $t('LBL_COMPOSE_MAIL_MESSAGE') }}  <span class="required">*</span></label>
			  				<div class="col-lg-9 col-xl-9">
				  				<textarea class="form-control" rows="5" v-model="emailData.message" name="message" v-validate="'required'" :data-vv-as="$t('LBL_COMPOSE_MAIL_MESSAGE')" data-vv-validate-on="none"></textarea>
				  				<span v-if="errors.first('message')" class="error text-danger">{{ errors.first('message') }}</span>
			  				</div>
		  		</div>
			<template v-slot:modal-footer>
				<button type="button" class="btn btn-brand gb-btn gb-btn-primary sendMailBtn" data-dismiss="modal"  :disabled='clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'" @click="sendEmail(recordId)">{{ $t('BTN_COMPOSE_MAIL_SEND') }}</button>
			</template>
	    </b-modal>
</template>
<script>

export default{
	props:['recordId'],
	data: function () {
		return{
			clicked: 0,
			emailData: []
		}
	}, 
	methods:{
		sendEmail: function(recordId) {
			this.$validator.validateAll().then(res=>{
				if(res) {
					this.clicked = 1;
					let formData = this.$serializeData(this.emailData);
					this.$http.post(adminBaseUrl+'/users/sendmail/'+recordId, formData).then((response) => {
						if (response.data.status == false) {
							this.clicked = 0;
							toastr.error(response.data.message, '', toastr.options);
							return;
						}
						this.clicked = 0;
						this.$bvModal.hide('send_mail');
						this.emailData = [];
						toastr.success(response.data.message, '', toastr.options);
					});
				} else {
					this.clicked = 0;
				}
			});
       },
	}
}
</script>
