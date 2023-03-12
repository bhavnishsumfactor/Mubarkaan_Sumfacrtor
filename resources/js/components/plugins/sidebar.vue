<template>
<!--Begin:: App Aside-->
<div class="grid-layout-aside" id="user_profile_aside">
    <!--begin:: Widgets/Applications/User/Profile1-->
    <div class="portlet portlet--height-fluid">
        <div class="portlet__body">
            <!--begin::Widget -->
            <div class="widget widget--user-profile-1">
                <div class="widget__body">
                    <div class="widget__items">
						<router-link :to="{name: 'settingsSmtp'}" class="widget__item" v-bind:class="[tab == 'smtp' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#email-SMTP'" :href="baseUrl+'/admin/images/retina/sprite.svg#email-SMTP'"></use>
									</svg>
								</span>
								<span class="widget__desc">
									{{ $t('LBL_EMAIL_SMTP') }}
								</span>
							</span>
						</router-link>
                    </div>
                </div>
            </div>
            <!--end::Widget -->
        </div>		
    </div>
    <!--end:: Widgets/Applications/User/Profile-->
</div>
<!--End:: App Aside-->
</template>
<script>

export default {
	data: function() {
		return {
			baseUrl: baseUrl
		}
	},
	methods: {
		
		
	},
	mounted: function() {
        //this.getSettings();
    }
}
</script>
