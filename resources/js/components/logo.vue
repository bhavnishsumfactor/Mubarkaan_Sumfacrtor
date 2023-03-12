<template>
	<div class="header__brand" id="header_brand">
		<menubar v-if="$mq === 'tablet'" :isTablet="true"></menubar>
		<a class="header__brand-logo" href="javascript:;" @click="openDashboardPage">
			<img alt="Logo" :src="siteLogo" class="header__brand-logo-default" :data-ratio="ratio" />
		</a>
	</div>
</template>
<script>
var VueCookie = require("vue-cookie");
Vue.use(VueCookie);
export default {
	data: function() {
		return {
			baseUrl: baseUrl,
			logo: siteLogo,
			lightLogo:lightLogo,
			ratio: siteLogoRatio,
			darkLogo:darkLogo
		}
	},
	computed: {
        siteLogo: function () {
            return (this.logo!='') ? this.logo: this.baseUrl + '/admin/images/logos/logo.png';
        }
	},
	methods: {
		openDashboardPage: function() {
            this.$router.push('/');
        },
		changeLogoBasedOnCookie: function() {
			if(this.$cookie.get('yk-Admin-ThemeStyle') == "dark") {
				this.logo = this.darkLogo;
			} else if(this.$cookie.get('yk-Admin-ThemeStyle') == "light") {
				this.logo = this.lightLogo;
			}
		}
	},
	mounted: function() {
		this.$root.$on('updateSiteLogo', (data) => {
			this.logo = data;
		});
		this.$root.$on('updateSiteLogoRatio', (data) => {
			this.ratio = data;
		});
		this.$root.$on('updateThemeSwitchLogo', () => {
			this.changeLogoBasedOnCookie();
		});
		this.$root.$on('updateAdminDarkLogo', (data) => {
				this.darkLogo = data;
		});
		this.changeLogoBasedOnCookie();
	},
}
</script>
