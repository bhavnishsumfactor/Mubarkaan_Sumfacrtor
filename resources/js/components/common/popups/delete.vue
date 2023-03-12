<template>
	<b-modal id="deleteModel" centered title="BootstrapVue" header-class="border-0"  hide-footer>
		<template v-slot:modal-header>
           <button type="button" class="close" @click="$bvModal.hide('deleteModel');"></button>
		</template>
		<div class="my-1 pb-5 text-center">
			<h5 class="">{{deletePopText}}</h5>
			<p class="" v-if="subText">{{subText}}</p>
		<button type="button" class="btn btn-brand gb-btn gb-btn-primary mt-8" data-dismiss="modal" @click="deleteRecord" :disabled='clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_DELETE_POPUP_SUBMIT')}}</button>
		</div>
   </b-modal>
</template>
<script>
	export default{
		data: function() {
            return {                
                clicked: 0
            }
        },
		props: ['deletePopText', 'subText'],
		methods: {
			deleteRecord: function() {
				this.clicked = 1;
				this.$emit('deleted');
				setTimeout(() => {
					this.clicked = 0;
				}, 500);
			}
		}
	}
</script>
