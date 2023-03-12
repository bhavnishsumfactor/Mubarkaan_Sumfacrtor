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
						<router-link :to="{name: 'thirdpartyAnalytics'}" class="widget__item" v-bind:class="[tab == 'analytics' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#analytics'" :href="baseUrl+'/admin/images/retina/sprite.svg#analytics'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LBL_ANALYTICS') }}
								</span>
							</span>
						</router-link>

						<router-link :to="{name: 'thirdpartyCurrency'}" class="widget__item" v-bind:class="[tab == 'currency' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#currency'" :href="baseUrl+'/admin/images/retina/sprite.svg#currency'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LBL_CURRENCY') }}
								</span>
							</span>
						</router-link>

						<router-link :to="{name: 'thirdpartyMarketingTools'}" class="widget__item" v-bind:class="[tab == 'marketingtools' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
								<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#marketing-tools'" :href="baseUrl+'/admin/images/retina/sprite.svg#marketing-tools'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LBL_EMAIL_MARKETING') }}
								</span>
							</span>
						</router-link>
						
						<router-link :to="{name: 'thirdpartyGeoLocation'}" class="widget__item" v-bind:class="[tab == 'geolocation' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#geo-location'" :href="baseUrl+'/admin/images/retina/sprite.svg#geo-location'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LBL_GEO_LOCATION') }}
								</span>
							</span>
						</router-link>

						<router-link :to="{name: 'thirdpartyLanguage'}" class="widget__item" v-bind:class="[tab == 'language' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#language'" :href="baseUrl+'/admin/images/retina/sprite.svg#language'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LBL_LANGUAGE') }}
								</span>
							</span>
						</router-link>

						<router-link :to="{name: 'thirdpartyLiveChat'}" class="widget__item" v-bind:class="[tab == 'livechat'? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#live-chat'" :href="baseUrl+'/admin/images/retina/sprite.svg#live-chat'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LBL_LIVE_CHAT') }}
								</span>
							</span>
						</router-link>

						<router-link :to="{name: 'thirdpartySecurity'}" class="widget__item" v-bind:class="[tab == 'security'? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#security'" :href="baseUrl+'/admin/images/retina/sprite.svg#security'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LBL_SECURITY') }}
								</span>
							</span>
						</router-link>

						<router-link :to="{name: 'thirdpartySMSTools'}" class="widget__item" v-bind:class="[tab == 'smstools' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#sms'" :href="baseUrl+'/admin/images/retina/sprite.svg#sms'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LBL_SMS_TOOLS') }}
								</span>
							</span>
						</router-link>

						<router-link :to="{name: 'thirdpartySocialKeys'}" class="widget__item" v-bind:class="[tab == 'socialkeys' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#social-networks'" :href="baseUrl+'/admin/images/retina/sprite.svg#social-networks'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LBL_SOCIAL_LOGIN_KEYS') }}
								</span>
							</span>
						</router-link>
						
						<router-link :to="{name: 'trackingApi'}" class="widget__item" v-bind:class="[tab == 'trackingApi' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#tracking-api'" :href="baseUrl+'/admin/images/retina/sprite.svg#tracking-api'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LBL_TRACKING_API') }}
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
	props: ['tab'],
	data: function() {
		return {
			baseUrl: baseUrl	
		}
	},
}
</script>
