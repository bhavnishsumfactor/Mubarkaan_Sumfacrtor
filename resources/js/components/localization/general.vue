<template>
<div>
	<div class="subheader" id="subheader">
		<div class="container">
			<div class="subheader__main">
				<h3 class="subheader__title">{{ $t('LBL_DATE_TIME_UNITS') }}</h3>
            </div>
		</div>
	</div>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<!--begin::Portlet-->
				<div class="portlet portlet--height-fluid">
					<div class="portlet__body pb-0">
						<div class="section">
							<div class="section_body">
								<div class="row justify-content-center">
									<div class="col-md-10">
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ $t('LBL_WEIGHT_SYSTEM') }}</label>
											<div class="col-md-8">
												<div class="radio-inline">
													<label class="radio" v-for="(unit, key) in units" :key="'weight'+key">
														<input type="radio" :value="key" v-model="adminsData.WEIGHT_UNIT" name="weight_unit"> {{$t(unit)}}<span></span> </label>
												</div>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-md-4 col-form-label">{{ $t('LBL_DIMENSION_SYSTEM') }}</label>
											<div class="col-md-8">
												<div class="radio-inline">
													<label class="radio" v-for="(unit, key) in units" :key="'dimension'+key">
														<input type="radio" :value="key" v-model="adminsData.DIMENSION_UNIT" name="dimension_unit"> {{$t(unit)}}<span></span> </label>
												</div>
											</div>
										</div>
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label">{{ $t('LBL_TIMEZONE') }}</label>
                                            <div class="col-md-8">
                                                <div class="radio-inline">
                                                    <select class="form-control"  v-model="adminsData.ADMIN_TIMEZONE" @change="onChange($event)">
                                                    <option disabled value>{{$t('LBL_SELECT_TIMEZONE')}}</option>
                                                    <option
                                                        v-for="timezone in timezones"
                                                        :key="timezone"
                                                        :value="timezone"
                                                    >{{timezone}}</option>
                                                    </select>
                                                    <span class="form-text text-muted">{{ $t("LBL_CURRENT_TIME") }} {{currentTime}}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label">{{ $t('LBL_DATE_FORMAT') }}</label>
                                            <div class="col-md-8">
                                                <div class="radio-inline">
                                                    <select class="form-control"  name="prod_type" v-model="adminsData.ADMIN_DATE_FORMAT">
                                                        <option disabled value>{{$t('LBL_SELECT_DATE_FORMAT')}}</option>
                                                        <option
                                                            v-for="(date, key) in dateFormat"
                                                            :key="date"
                                                            :value="key"
                                                        >{{date}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label">{{ $t('LBL_TIME_FORMAT') }}</label>
                                            <div class="col-md-8">
                                                <div class="radio-inline">
                                                    <select class="form-control" v-model="adminsData.ADMIN_TIME_FORMAT">
                                                        <option disabled value>{{$t('LBL_SELECT_TIME_FORMAT')}}</option>
                                                        <option v-for="(time, index1) in timeFormat"
                                                            :key="index1"
                                                            :value="index1"
                                                        >{{time}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
									</div>
								</div>
							</div>
						</div>
					</div>
                    <div class="portlet__foot text-center">
						<button type="submit" class="btn btn-wide btn-brand gb-btn gb-btn-primary" @click="updateSettings" :disabled="clicked==1" v-bind:class="clicked==1 && 'gb-is-loading'">{{$t('BTN_UPDATE_DATETIME_INFO')}}</button>
					</div>
				</div>
				<!--end::Portlet-->
			</div>
		</div>
	</div>
</div>
</template>
<script>

const tableFields = {
    'WEIGHT_UNIT': 0,
    'DIMENSION_UNIT': 0,
    'ADMIN_TIMEZONE': '',
    'ADMIN_DATE_FORMAT': '',
    'ADMIN_TIME_FORMAT': ''
};
export default {
    data: function() {
        return {
            adminsData: tableFields,
            units: [],
            clicked: 0,
            timezones:[],
            dateFormat:[],
            timeFormat:[],
            currentTime:''
        }
    },
    methods: {
        getSettings: function() {
            let formData = this.$serializeData({'keys': ['WEIGHT_UNIT', 'DIMENSION_UNIT', 'ADMIN_TIMEZONE', 'ADMIN_DATE_FORMAT','ADMIN_TIME_FORMAT']});
            this.$http.post(adminBaseUrl + '/settings/get', formData).then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                this.adminsData = response.data.data;
                this.units = response.data.data.units;
                this.currentTime = moment.tz(this.adminsData.ADMIN_TIMEZONE).format('YYYY-MM-DD HH:mm');
            });
        },
        onChange(event) {
            this.currentTime = moment.tz(event.target.value).format('YYYY-MM-DD HH:mm');
        },
        getTimezones: function() {
            this.getSettings();
            this.$http.get(adminBaseUrl + '/get-timzone').then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                this.timezones = response.data.data.timezone;
                this.dateFormat = response.data.data.dateFormat;
                this.timeFormat = response.data.data.timeFormat;
            });
        },
        updateSettings: function(input) {
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
                            toastr.success(response.data.message, '', toastr.options);
                            localStorage["dateformat"] = this.adminsData.ADMIN_DATE_FORMAT;
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
        this.getTimezones();
    }
}
</script>
