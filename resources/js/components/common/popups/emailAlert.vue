<template>
	<b-modal id="emailAlertModel" centered title="BootstrapVue" header-class="border-0"  hide-footer>
		<template v-slot:modal-header>
           <button type="button" class="close" @click="$bvModal.hide('emailAlertModel');"></button>
		</template>
		<div class="my-1 pb-5 text-center">
			<h5 class="">{{alertPopText}}</h5>
			<p class="" v-if="subText">{{subText}}</p>
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
		props: ['alertPopText', 'subText']
	}
</script>
