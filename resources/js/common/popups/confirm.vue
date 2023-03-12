<template>
	<b-modal id="ConfirmModel" centered title="BootstrapVue">
		<template v-slot:modal-header>
           <h5 class="modal-title" id="exampleModalLabel">{{ $t('LBL_CONFIRM_POPUP_TITLE') }}</h5>
           <button type="button" class="close" @click="$bvModal.hide('ConfirmModel')"></button>
		</template>
           {{ confirmPopText }}.
		<template v-slot:modal-footer>
           <button type="button" class="btn btn-primary" data-dismiss="modal" @click="confirmRecordAction">{{ $t('BTN_CONFIRM_POPUP_SUBMIT')}}</button>
		</template>
   </b-modal>
</template>
<script>
	export default{
		props: ['confirmPopText'],
		methods: {
			confirmRecordAction: function() {
				this.$emit('confirm');
			}
		}
	}
</script>
