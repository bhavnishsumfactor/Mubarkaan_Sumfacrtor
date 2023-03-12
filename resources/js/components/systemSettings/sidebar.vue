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
						<router-link :to="{name: 'settingsLogo'}" class="widget__item" v-bind:class="[tab == 'logo'? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#logo'" :href="baseUrl+'/admin/images/retina/sprite.svg#logo'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LBL_LOGO') }}
								</span>
							</span>
						</router-link>
						<router-link :to="{name: 'settingsCms'}" class="widget__item" v-bind:class="[tab == 'cms' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#cms'" :href="baseUrl+'/admin/images/retina/sprite.svg#cms'"></use>
									</svg>
								</span>
								<span class="widget__desc">
									{{ $t('LBL_CMS') }}
								</span>
							</span>
						</router-link>
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
						<router-link :to="{name: 'settingsSystemMessage'}" class="widget__item" v-bind:class="[tab == 'systemMessages' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#notification'" :href="baseUrl+'/admin/images/retina/sprite.svg#notification'"></use>
									</svg>
								</span>
								<span class="widget__desc">
									{{ $t('LBL_SYSTEM_NOTIFICATIONS') }}
								</span>
							</span>
						</router-link>
						<router-link :to="{name: 'settingsEmailTemplates'}" class="widget__item" v-bind:class="[tab == 'emailTemplates' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#email-template'" :href="baseUrl+'/admin/images/retina/sprite.svg#email-template'"></use>
									</svg>
								</span>
								<span class="widget__desc">
									{{ $t('LBL_EMAIL_TEMPLATES') }}
								</span>
							</span>
						</router-link>
						<router-link :to="{name: 'settingsSharing'}" class="widget__item" v-bind:class="[tab == 'socialSharing'? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#social-networks'" :href="baseUrl+'/admin/images/retina/sprite.svg#social-networks'"></use>
									</svg>
								</span>
								<span class="widget__desc">
									{{ $t('LBL_SOCIAL_NETWORKS') }}
								</span>
							</span>
						</router-link>
						<router-link :to="{name: 'settingsCookies'}" class="widget__item" v-bind:class="[tab == 'cookies'? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#cookies'" :href="baseUrl+'/admin/images/retina/sprite.svg#cookies'"></use>
									</svg>
								</span>
								<span class="widget__desc">
									{{ $t('LBL_COOKIES') }}
								</span>
							</span>
						</router-link>						
                    </div>
                </div>
            </div>
            <!--end::Widget -->
        </div>
		<div class="portlet__foot">
			<div class="maintenance-mode">
				<div class="row justify-content-between align-items-center">
					<label class="col col-form-label ">
						<i class="">
							<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								viewBox="0 0 480.003 480.003" style="enable-background:new 0 0 480.003 480.003;" xml:space="preserve">
								<g>
									<g>
										<path d="M477.657,402.346l-120-120c-3.128-3.128-8.184-3.128-11.312,0l-10.344,10.344l-12.688-12.688l10.344-10.344
											c3.128-3.128,3.128-8.184,0-11.312l-26.344-26.344l40-40h68.688c2.12,0,4.16-0.84,5.656-2.344l56-56
											c1.504-1.496,2.344-3.536,2.344-5.656v-56c0-3.232-1.952-6.152-4.936-7.392c-3-1.248-6.44-0.56-8.72,1.736l-53.656,53.656h-52.688
											V67.314l53.656-53.656c2.288-2.288,2.976-5.728,1.736-8.72c-1.24-2.984-4.16-4.936-7.392-4.936h-56c-2.12,0-4.16,0.84-5.656,2.344
											l-56,56c-1.504,1.496-2.344,3.536-2.344,5.656v68.688l-56,56L103.225,59.914l-7.464-29.856c-0.64-2.552-2.488-4.624-4.952-5.552
											l-64-24c-2.928-1.088-6.24-0.384-8.464,1.832l-16,16c-2.216,2.224-2.936,5.536-1.84,8.472l24,64c0.928,2.464,3,4.32,5.552,4.952
											l29.848,7.464l128.784,128.776l-56,56H64.001c-2.12,0-4.16,0.84-5.656,2.344l-56,56c-1.504,1.496-2.344,3.536-2.344,5.656v56
											c0,3.232,1.952,6.152,4.936,7.392c3,1.232,6.432,0.552,8.72-1.736l53.656-53.656h52.688v52.688l-53.656,53.656
											c-2.288,2.288-2.976,5.728-1.736,8.72c1.24,2.984,4.16,4.936,7.392,4.936h56c2.12,0,4.16-0.84,5.656-2.344l56-56
											c1.504-1.496,2.344-3.536,2.344-5.656v-68.688l40-40l26.344,26.344c3.128,3.128,8.184,3.128,11.312,0l10.344-10.344l12.688,12.688
											l-10.344,10.344c-3.128,3.128-3.128,8.184,0,11.312l120,120c1.56,1.56,3.608,2.344,5.656,2.344c2.048,0,4.096-0.784,5.656-2.344
											l64-64C480.785,410.53,480.785,405.474,477.657,402.346z M69.657,90.346c-1.024-1.024-2.312-1.752-3.712-2.104l-27.928-6.984
											l-20.712-55.24l8.712-8.712l55.24,20.712l6.984,27.928c0.352,1.408,1.08,2.688,2.104,3.712l130.344,130.344l-20.688,20.688
											L69.657,90.346z M226.345,290.346l-48,48c-1.504,1.496-2.344,3.536-2.344,5.656v68.688l-51.312,51.312H91.313l42.344-42.344
											c1.504-1.496,2.344-3.536,2.344-5.656v-64c0-4.416-3.584-8-8-8h-64c-2.12,0-4.16,0.84-5.656,2.344L16.001,388.69v-33.376
											l51.312-51.312h68.688c2.12,0,4.16-0.84,5.656-2.344l64-64l32-32l64-64c1.504-1.496,2.344-3.536,2.344-5.656V67.314l51.312-51.312
											h33.376l-42.344,42.344c-1.504,1.496-2.344,3.536-2.344,5.656v64c0,4.416,3.584,8,8,8h64c2.12,0,4.16-0.84,5.656-2.344
											l42.344-42.344v33.376l-51.312,51.312h-68.688c-2.12,0-4.16,0.84-5.656,2.344l-48,48L226.345,290.346z M408.001,460.69
											L299.313,352.002l10.344-10.344c3.128-3.128,3.128-8.184,0-11.312l-24-24c-3.128-3.128-8.184-3.128-11.312,0l-10.344,10.344
											l-20.688-20.688l52.688-52.688l20.688,20.688l-10.344,10.344c-3.128,3.128-3.128,8.184,0,11.312l24,24
											c3.128,3.128,8.184,3.128,11.312,0l10.344-10.344l108.688,108.688L408.001,460.69z"/>
									</g>
								</g>
								<g>
									<g>
										<rect x="392.032" y="350.026" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -154.3377 395.3261)" width="16" height="67.879"/>
									</g>
								</g>
								<g>
									<g>
										<rect x="343.993" y="324.656" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -134.4688 347.2996)" width="16" height="22.624"/>
									</g>
								</g>
							</svg>
						</i> {{ $t('LBL_MAINTENANCE_MODE') }}
					</label> 
					<div class="col-auto">
						<label class="switch switch--sm m-0">
							<input type="checkbox" name="MAINTENANCE_MODE" v-model="adminsData.MAINTENANCE_MODE" @change="onSwitchStatus($event)"> 
							<span v-b-tooltip.hover :title="adminsData.MAINTENANCE_MODE == 0 ? $t('TTL_ENABLE') : $t('TTL_DISABLE') "></span>
						</label>
					</div>
				</div>
			</div>
		</div>
    </div>
    <!--end:: Widgets/Applications/User/Profile-->

</div>
<!--End:: App Aside-->
</template>
<script>
const tableFields = {
    'MAINTENANCE_MODE': ''
};
export default {
    props: ['tab'],
	data: function() {
		return {
			baseUrl: baseUrl,
			adminsData: tableFields	
		}
	},
	methods: {
		getSettings: function() {
            let formData = this.$serializeData({'keys':['MAINTENANCE_MODE']});
            this.$http.post(adminBaseUrl + '/settings/get', formData).then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
				this.adminsData = response.data.data;
				if (this.adminsData.MAINTENANCE_MODE == "false") {
					this.adminsData.MAINTENANCE_MODE = 0;
				} else {
					this.adminsData.MAINTENANCE_MODE = 1;
				}
            });
		},
		updateSettings: function(formData) {
            this.$validator.validateAll().then(res => {
                if (res) {
					//let formData = this.$serializeData(this.adminsData);
                    this.$http.post(adminBaseUrl + '/settings/basedonkeys', formData)
                        .then((response) => { //success
                            if (response.data.status == false) {
                              toastr.error(response.data.message, '', toastr.options);
                              return;
                            }
                            toastr.success(response.data.message, '', toastr.options);
                        }, (response) => { //error
                            this.validateErrors(response);
                        });
                } 
            });
		},
		onSwitchStatus: function (event) {
			let formData = this.$serializeData({
				MAINTENANCE_MODE: event.target.checked,
			});
			this.updateSettings(formData);
		}
	},
	mounted: function() {
        this.getSettings();
    }
}
</script>
