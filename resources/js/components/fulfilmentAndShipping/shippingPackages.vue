<template>
<div>
<div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_SHIPPING_API') }}</h3>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="grid-layout">
            <!--Begin:: App Aside Mobile Toggle-->
            <button class="grid-layout-close" id="user_profile_aside_close">
                <i class="la la-close"></i>
            </button>
            <!--End:: App Aside Mobile Toggle-->

            <!--Begin:: App Content-->
            <div class="grid-layout-content">
                <div class="portlet portlet--height-fluid">
                    <div class="portlet__body">
                        <div class="section">
                            <div class="section__body pt-10">
                                <div class="row justify-content-center">
                                    <div class="col-md-7" v-bind:class="[(!$canWrite('SHIPPING_PACKAGES')) ? 'disabledbutton': '']">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">{{ $t('LBL_ENABLE_AFTERSHIP_API') }}</label>
                                            <div class="col-lg-8">
                                                <div class="radio-inline">
                                                    <label class="radio">
                                                        <input type="radio"  v-model="adminsData.pkg_publish" name="pkg_publish" :value="1"> {{$t('LBL_YES')}}<span></span>
                                                    </label>
                                                    <label class="radio">
                                                        <input type="radio" v-model="adminsData.pkg_publish" name="pkg_publish" :value="0" @change="saveRecords()">{{ $t('LBL_NO') }}<span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">{{ $t('LBL_AFTERSHIP_API_KEY') }} <span class="required">*</span></label>
                                            <div class="col-lg-8">
                                                <input type="text" v-model="adminsData.AFTERSHIP_KEY" name="apikey" v-validate="'required'" data-vv-validate-on="none" class="form-control" :disabled='adminsData.pkg_publish == 0'/>
                                                <span v-if="errors.first('apikey')" class="error text-danger">{{ errors.first('apikey') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet__foot">
                        <div class="form__actions text-center">
                            <button type="button" class="btn btn-brand btn-wide gb-btn gb-btn-primary" @click="updateSettings" :disabled='clicked==1 || isComplete' v-bind:class="[clicked==1 && 'gb-is-loading', (!$canWrite('SHIPPING_PACKAGES')) ? 'disabledbutton': '']">{{$t('BTN_SETTINGS_UPDATE')}}</button>
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

const tableFields = {
    'pkg_publish': '',
    'AFTERSHIP_KEY': ''
};
export default {
    
    data: function() {
        return {
            adminsData: tableFields,
            clicked: 0
        }
    },
    methods: {
        getSettings: function() {    
            this.$http.get(adminBaseUrl + '/shipping/tracking-api').then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                this.adminsData.pkg_publish = response.data.data.pkg_publish;
                if (response.data.data.configurations[0].pconf_value != '') {
                    this.adminsData.AFTERSHIP_KEY = response.data.data.configurations[0].pconf_value;
                }
            });
        },
        updateSettings: function() {
            this.$validator.validateAll().then(res => {
                if (res) {
                    this.clicked = 1;
                    this.saveRecords();
                } else {
                    this.clicked = 0;
                }
            });
        },
        saveRecords: function() {
            let formData = this.$serializeData(this.adminsData);
            this.$http.post(adminBaseUrl + '/shipping/tracking-api-update', formData)
                .then((response) => { //success
                    this.clicked = 0;
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                }, (response) => { //error
                    this.clicked = 0;
                    this.validateErrors(response);
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
    },
    computed: {
        isComplete() {
            return ( this.adminsData.pkg_publish == '' || (this.adminsData.AFTERSHIP_KEY == '' ) );
        }
    }
}
</script>