<template>
	<div class="header__topbar-item header__topbar-item--quick-panel">
		<div class="header__topbar-wrapper" data-trigger="quick-panel">
			<span class="header__topbar-icon YK-quickPanel" @click="reloadQuickPanel" id="quick_panel_toggler_btn">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="svg-icon">
					<g>
						<path fill="currentColor" d="M9.708,11.083H2.375A1.409,1.409,0,0,1,1,9.708V2.375A1.409,1.409,0,0,1,2.375,1H9.708a1.409,1.409,0,0,1,1.375,1.375V9.708a1.409,1.409,0,0,1-1.375,1.375M2.375,1.917a.431.431,0,0,0-.458.4.469.469,0,0,0,0,.053V9.708a.433.433,0,0,0,.4.459.468.468,0,0,0,.053,0H9.708a.434.434,0,0,0,.459-.406.468.468,0,0,0,0-.053V2.375a.432.432,0,0,0-.406-.458.468.468,0,0,0-.053,0Z" />
						<path fill="currentColor" d="M21.625,11.083H14.292a1.409,1.409,0,0,1-1.375-1.375V2.375A1.409,1.409,0,0,1,14.292,1h7.333A1.409,1.409,0,0,1,23,2.375V9.708a1.409,1.409,0,0,1-1.375,1.375M14.292,1.917a.433.433,0,0,0-.459.4.468.468,0,0,0,0,.053V9.708a.434.434,0,0,0,.406.459.468.468,0,0,0,.053,0h7.333a.432.432,0,0,0,.458-.406.469.469,0,0,0,0-.053V2.375a.431.431,0,0,0-.405-.458.469.469,0,0,0-.053,0Z" />
						<path fill="currentColor" d="M9.708,23H2.375A1.409,1.409,0,0,1,1,21.625V14.292a1.409,1.409,0,0,1,1.375-1.375H9.708a1.409,1.409,0,0,1,1.375,1.375v7.333A1.409,1.409,0,0,1,9.708,23M2.375,13.833a.432.432,0,0,0-.458.406.468.468,0,0,0,0,.053v7.333a.431.431,0,0,0,.4.458H9.708a.433.433,0,0,0,.459-.405.468.468,0,0,0,0-.053V14.292a.434.434,0,0,0-.406-.459.468.468,0,0,0-.053,0Z" />
						<path fill="currentColor" d="M21.625,23H14.292a1.409,1.409,0,0,1-1.375-1.375V14.292a1.409,1.409,0,0,1,1.375-1.375h7.333A1.409,1.409,0,0,1,23,14.292v7.333A1.409,1.409,0,0,1,21.625,23m-7.333-9.167a.434.434,0,0,0-.459.406.468.468,0,0,0,0,.053v7.333a.432.432,0,0,0,.406.458h7.386a.431.431,0,0,0,.458-.405.469.469,0,0,0,0-.053V14.292a.433.433,0,0,0-.405-.459.468.468,0,0,0-.053,0Z" />
					</g>
				</svg>
				<span class="badge badge--danger YK-quickPanelCount" v-if="notificationCount > 0">{{notificationCount}}</span>
			</span>
		</div>
	</div>
</template>
<script>

export default {
	data: function() {
		return {
			baseUrl: baseUrl,
			notificationCount: 0
		}
	},
	methods: {
		recheckNotifications: function () {
			var self = this;
			self.$http.get(adminBaseUrl + '/get-unread-notifications').then((response) => {
				if (response.data.status == true) {					
					if(response.data.data > self.$data.notificationCount && self.$data.notificationCount != 0){
						toastr.success('You have new notifications', '', toastr.options);
					}		
					self.$data.notificationCount = response.data.data;
				}
			});	
		},
		reloadQuickPanel: function () {
			this.$root.$emit('reloadQuickPanel', 1);
		}
	},
	mounted: function() {
		var self = this;
		
		var firstTime = setInterval(function () {
			self.recheckNotifications();
			window.clearTimeout(firstTime);
		}, 2000);

		setInterval(function () {
			self.recheckNotifications();
		}, 120000);
		
	},
}
</script>
