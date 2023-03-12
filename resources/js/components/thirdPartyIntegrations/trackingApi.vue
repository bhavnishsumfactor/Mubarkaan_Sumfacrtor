<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_THIRD_PARTY_INTEGRATIONS') }}</h3>
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
            <sidebar :tab="type"></sidebar>
            <!--Begin:: App Content-->
            <div class="grid-layout-content">
                <div class="portlet portlet--height-fluid">
                    <div class="portlet__body">
                        <div class="section">
                            <div class="section__body pt-10">
                                <div class="row justify-content-center">
                                    <div class="col-md-8" v-bind:class="[(!$canWrite('TRACKING_API')) ? 'disabledbutton': '']">
                                        <div class="card">
                                            
                                            <div class="card-header">
                                                <div class="info__wrapper">
                                                    <div class="info__media" v-bind:class="[adminsData.pkg_publish == 0 ? 'disabled' : '']">
                                                        <div class="img__wrapper">
                                                            <img :src="baseUrl+'/admin/images/third-party/aftership_logo.svg'" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="info__content">
                                                        <div class="row">
                                                            <div class="col-md-10">
                                                                <h3>{{$t('LBL_AFTERSHIP')}}</h3>
                                                                <a href="https://www.aftership.com" target="_blank">https://www.aftership.com/</a>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <span class="switch switch--sm">
                                                                    <label>
                                                                        <input type="checkbox" name="pkg_publish" v-model="adminsData.pkg_publish" @change="updateStatus($event)">
                                                                        <span></span>
                                                                    </label>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <!--<div class="form-group row">
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
                                                </div>-->
                                                <div class="form-group row mb-0">
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
                        </div>
                    </div>
                    <div class="portlet__foot">
                        <div class="form__actions text-center">
                            <button type="button" class="btn btn-brand btn-wide gb-btn gb-btn-primary" @click="updateSettings" :disabled='clicked==1 || isComplete' v-bind:class="[clicked==1 && 'gb-is-loading', (!$canWrite('TRACKING_API')) ? 'disabledbutton': '']">{{$t('BTN_SETTINGS_UPDATE')}}</button>
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
    'pkg_publish': '',
    'AFTERSHIP_KEY': ''
};
export default {
    components: {
        'sidebar': sidebar
    },
    data: function() {
        return {
            adminsData: tableFields,
            type: 'trackingApi',
            clicked: 0,
            baseUrl:baseUrl
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
                if (Object.keys(response.data.data).length > 0) {
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
        updateStatus: function(e) {
            this.adminsData.pkg_publish = e.target.checked ? parseInt(1) : parseInt(0);
            if(!this.adminsData.pkg_publish){
                this.saveRecords();
            }
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
