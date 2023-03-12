<template>
	<!--begin: User bar -->
	<div class="header__topbar-item header__topbar-item--user">
		<div class="header__topbar-wrapper d-flex align-items-center">
			<a href="javascript:;" @click="openEditProfilePage">
				<img data-yk="" alt="Pic" v-if="admin_image != ''" :src="admin_image" />       
				<span class="header__topbar-icon" v-else><b>{{ admin_name_badge }}</b></span>        
			</a>
		</div>
	</div>
	<!--end: User bar -->
</template>
<script>

export default {
	data: function() {
		return {
			baseUrl: baseUrl,
			admin_image: '',
			admin_name_badge: ''
		}
	},
	methods: {
		openEditProfilePage: function() {
            this.$router.push('/edit-profile');
        },
	},
	mounted: function() {
		this.$root.$on('adminImage', (data) => {
			this.admin_image = data;
		});
		this.$root.$on('adminNameBadge', (data) => {
			this.admin_name_badge = data;
		});		
	},
}
</script>
