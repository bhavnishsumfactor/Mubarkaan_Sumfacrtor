<template>
<div>
	<div class="subheader" id="subheader">
		<div class="container">
			<div class="subheader__main">
				<h3 class="subheader__title">{{ $t('LBL_SYSTEM_SETTINGS') }}</h3> </div>
		</div>
	</div>
	<div class="container">
		<div class="grid-layout">
			<!--Begin:: App Aside Mobile Toggle-->
			<button class="grid-layout-close" id="user_profile_aside_close"> <i class="la la-close"></i> </button>
			<!--End:: App Aside Mobile Toggle-->
			<sidebar :tab="type"></sidebar>
			<!--Begin:: App Content-->
			<div class="grid-layout-content">
				<div class="portlet portlet--height-fluid">
					<div class="portlet__body">
						<div class="section">
							<div class="section__body">
								<div class="row justify-content-center">
									<div class="col-xl-8">
										<div class="form-group row">
											<label class="col-lg-5 col-form-label">{{ $t('LBL_AUTO_CLOSE_SYSTEM_MESSAGES') }}</label>
											<div class="col-lg-7">
												<div class="radio-inline">
													<label class="radio">
														<input type="radio" checked="checked" :value="1" v-model="adminsData.SYSTEM_MESSAGE_STATUS"> {{$t('Yes')}}<span></span> </label>
													<label class="radio">
														<input type="radio" :value="0" v-model="adminsData.SYSTEM_MESSAGE_STATUS"> {{$t('No')}}<span></span> </label>
												</div>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-5 col-form-label">{{ $t('LBL_TIME_FOR_AUTO_CLOSING_MESSAGES') }} <span class="text-nowrap">({{ $t('LBL_IN_SECONDS') }})</span></label>
											<div class="col-lg-7">
												<input type="number" :disabled="adminsData.SYSTEM_MESSAGE_STATUS == '0'" v-model="adminsData.SYSTEM_MESSAGE_CLOSE_TIME" name="systemMessage_value" v-validate="'required|numeric|between:1,60'" data-vv-validate-on="none" :data-vv-as="$t('LBL_CLOSE_TIME')" class="form-control" min="1" max="60" required /> <span v-if="errors.first('systemMessage_value')" class="error text-danger">{{ errors.first('systemMessage_value') }}</span> </div>
										</div>
										<div class="form-group row">
											<label class="col-lg-5 col-form-label">{{ $t('LBL_DISPLAY_POSITION') }}</label>
											<div class="col-lg-7">
												<ul class="list-switch list-switch--two">
													<li>
														<label class="radio m-0">
															<input type="radio" @click="displayPosition" checked="checked" :value="'toast-top-left'" v-model="adminsData.SYSTEM_MESSAGE_POSITION"> {{$t('LBL_TOP_LEFT')}}<span></span> </label>
													</li>
													<li>
														<label class="radio m-0">
															<input type="radio" @click="displayPosition" :value="'toast-top-center'" v-model="adminsData.SYSTEM_MESSAGE_POSITION"> {{$t('LBL_TOP_CENTER')}}<span></span> </label>
													</li>
													<li>
														<label class="radio m-0">
															<input type="radio" @click="displayPosition" :value="'toast-top-right'" v-model="adminsData.SYSTEM_MESSAGE_POSITION"> {{$t('LBL_TOP_RIGHT')}}<span></span> </label>
													</li>
													<li>
														<label class="radio m-0">
															<input type="radio" @click="displayPosition" :value="'toast-top-full-width'" v-model="adminsData.SYSTEM_MESSAGE_POSITION"> {{$t('LBL_TOP_FULL_WIDTH')}}<span></span> </label>
													</li>
													<li>
														<label class="radio m-0">
															<input type="radio" @click="displayPosition" :value="'toast-bottom-left'" v-model="adminsData.SYSTEM_MESSAGE_POSITION"> {{$t('LBL_BOTTOM_LEFT')}}<span></span> </label>
													</li>
													<li>
														<label class="radio m-0">
															<input type="radio" @click="displayPosition" :value="'toast-bottom-center'" v-model="adminsData.SYSTEM_MESSAGE_POSITION"> {{$t('LBL_BOTTOM_CENTER')}}<span></span> </label>
													</li>
													<li>
														<label class="radio m-0">
															<input type="radio" @click="displayPosition" :value="'toast-bottom-right'" v-model="adminsData.SYSTEM_MESSAGE_POSITION"> {{$t('LBL_BOTTOM_RIGHT')}}<span></span> </label>
													</li>
													<li>
														<label class="radio m-0">
															<input type="radio" @click="displayPosition" :value="'toast-bottom-full-width'" v-model="adminsData.SYSTEM_MESSAGE_POSITION"> {{$t('LBL_BOTTOM_FULL_WIDTH')}}<span></span> </label>
													</li>
												</ul>
											</div>
										</div>
								
										<div class="form-group row">
											<label class="col-lg-5 col-form-label">{{ $t('LBL_TODO_REMINDER') }}</label>
											<div class="col-lg-7">
												<div class="checkbox-inline">
													<label class="checkbox">
														<input @click="updateTodoSetting($event, 'TODO_REMINDER_EMAIL')" value="1" type="checkbox" :checked="adminsData.TODO_REMINDER_EMAIL=='1'"> {{$t('LBL_REMINDER_VIA_EMAIL')}} <span></span> </label>
													<label class="checkbox">
														<input @click="updateTodoSetting($event, 'TODO_REMINDER_SMS')" value="1" type="checkbox" :checked="adminsData.TODO_REMINDER_SMS=='1'"> {{$t('LBL_REMINDER_VIA_SMS')}} <span></span> </label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
                    <div class="portlet__foot">
                        <div class="form__actions text-center">
                            <button type="button" class="btn btn-brand btn-wide gb-btn gb-btn-primary" @click="updateSettings" :disabled='clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'">{{$t('BTN_SETTINGS_UPDATE')}}</button>
                        </div>
                    </div>
				</div>
			</div>
		</div>
		
	</div>
</div>
<!--End:: App Content-->
</div>
</div>
</div>
</template>
<script>
    import sidebar from './sidebar';
    const tableFields = {
        'SYSTEM_MESSAGE_STATUS': '',
        'SYSTEM_MESSAGE_CLOSE_TIME': '',
        'TODO_REMINDER_EMAIL': '',
        'TODO_REMINDER_SMS': '',
        'SYSTEM_MESSAGE_POSITION': ''
    };
    export default {
        components: {
            'sidebar': sidebar
        },
        data: function() {
            return {
                adminsData: tableFields,
                type: 'systemMessages',
                clicked: 0
            }
        },
        methods: {
            updateTodoSetting: function(event, key) {
                this.adminsData[key] = (event.target.checked == true) ? 1 : 0;
            },
            displayPosition: function() {
                let thisObj = this;
                setTimeout(function(){
                    toastr.options.timeOut = (thisObj.adminsData.SYSTEM_MESSAGE_STATUS == 1 && thisObj.adminsData.SYSTEM_MESSAGE_CLOSE_TIME != 0) ? thisObj.adminsData.SYSTEM_MESSAGE_CLOSE_TIME * 1000 : 0;
                    toastr.options.positionClass = thisObj.adminsData.SYSTEM_MESSAGE_POSITION;
                    toastr.success($t('NOTI_SAMPLE_NOTIFICATION'), '', toastr.options);
                }, 200);
            },
            getSettings: function() {
                let formData = this.$serializeData({
                    'keys': [
                        'SYSTEM_MESSAGE_STATUS', 'SYSTEM_MESSAGE_CLOSE_TIME', 'TODO_REMINDER_EMAIL', 'TODO_REMINDER_SMS', 'SYSTEM_MESSAGE_POSITION'
                    ]
                });
                this.$http.post(adminBaseUrl + '/settings/get', formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.adminsData = response.data.data;
                });
            },
            updateSettings: function() {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        this.clicked = 1;
                        let formData = this.$serializeData(this.adminsData);
                        this.$http.post(adminBaseUrl + '/settings/basedonkeys', formData)
                            .then((response) => { //success
                                this.clicked = 0;
                                if (response.data.status == false) {
                                    toastr.error(response.data.message, '', toastr.options);
                                    return;
                                }
                                toastr.options.timeOut = (this.adminsData.SYSTEM_MESSAGE_STATUS == 1 && this.adminsData.SYSTEM_MESSAGE_CLOSE_TIME != 0) ? this.adminsData.SYSTEM_MESSAGE_CLOSE_TIME * 1000 : 0;
                                toastr.options.positionClass = this.adminsData.SYSTEM_MESSAGE_POSITION;
                                toastr.success(response.data.message, '', toastr.options);
                            }, (response) => { //error
                                this.clicked = 0;
                                this.validateErrors(response);
                            });
                    } else {
                        this.clicked = 0;
                    }
                });
            },
            validateErrors: function(response) {
                let jsondata = response.data;
                Object.keys(jsondata.errors).forEach(key => {
                    this.errors.add({
                        field: key,
                        msg: jsondata.errors[key][0]
                    });
                });
            },
        },
        mounted: function() {
            this.getSettings();
        }
    }
</script>