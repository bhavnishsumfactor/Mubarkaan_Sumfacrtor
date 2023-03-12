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
                            <div class="col-md-8">
                                <div class="card">
                                    
                                    <div class="card-header">
                                        <div class="info__wrapper">
                                            <div class="info__media">
                                                <div class="img__wrapper" v-bind:class="[adminsData.GOOGLE_RECAPCHA_STATUS == 0 ? 'disabled' : '']">
                                                    <img :src="baseUrl+'/admin/images/third-party/recaptcha.jpg'" alt="" />
                                                </div>
                                            </div>
                                            <div class="info__content">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <h3>{{$t('LBL_SECURITY')}}</h3>
                                                        <a href="https://www.google.com/recaptcha/about/">https://www.google.com/recaptcha/about/</a>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <span class="switch switch--sm">
                                                            <label>
                                                                <input type="checkbox" v-model="adminsData.GOOGLE_RECAPCHA_STATUS" @change="updateStatus($event)">
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">{{ $t('LBL_RECAPTCHA_KEY') }} <span class="required">*</span></label>
                                            <div class="col-lg-8">
                                                <input type="text" v-model="adminsData.GOOGLE_RECAPCHA_SITE_KEY" name="sitekey" v-validate="'required'" data-vv-validate-on="none" class="form-control" :disabled="adminsData.GOOGLE_RECAPCHA_STATUS == 0"/>
                                                <span v-if="errors.first('sitekey')" class="error text-danger">{{ errors.first('sitekey') }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-0">
                                            <label class="col-lg-4 col-form-label">{{ $t('LBL_RECAPTCHA_SECRET') }} <span class="required">*</span></label>
                                            <div class="col-lg-8">
                                                <input type="text" v-model="adminsData.GOOGLE_RECAPCHA_SECRET_KEY" name="secretKey" v-validate="'required'" data-vv-validate-on="none" class="form-control" :disabled="adminsData.GOOGLE_RECAPCHA_STATUS == 0"/>
                                                <span v-if="errors.first('secretKey')" class="error text-danger">{{ errors.first('secretKey') }}</span>
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
                    <button type="button" class="btn btn-brand btn-wide gb-btn gb-btn-primary" @click="updateSettings" :disabled='clicked == 1 || isComplete' v-bind:class="clicked==1 && 'gb-is-loading'">{{$t('BTN_SETTINGS_UPDATE')}}</button>
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
    'GOOGLE_RECAPCHA_SITE_KEY': '',
    'GOOGLE_RECAPCHA_SECRET_KEY': '',
    'GOOGLE_RECAPCHA_STATUS': 0
};
export default {
    components: {
        'sidebar': sidebar
    },
    data: function() {
        return {
            adminsData: tableFields,
            type: 'security',
            clicked: 0,
            baseUrl:baseUrl
        }
    },
    methods: {
        getSettings: function() {
            let formData = this.$serializeData({'keys':['GOOGLE_RECAPCHA_SITE_KEY', 'GOOGLE_RECAPCHA_SECRET_KEY','GOOGLE_RECAPCHA_STATUS']});
            this.$http.post(adminBaseUrl + '/settings/get', formData).then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                this.adminsData = response.data.data;
                this.adminsData.GOOGLE_RECAPCHA_STATUS = parseInt(response.data.data.GOOGLE_RECAPCHA_STATUS);
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
        updateStatus: function(e) {
            this.adminsData.GOOGLE_RECAPCHA_STATUS = e.target.checked ? parseInt(1) : parseInt(0);
            if(!this.adminsData.GOOGLE_RECAPCHA_STATUS){
                this.saveRecords();
            }
        },
        saveRecords: function() {
            let formData = this.$serializeData(this.adminsData);
            this.$http.post(adminBaseUrl + '/settings/basedonkeys', formData)
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
            return ( this.adminsData.GOOGLE_RECAPCHA_STATUS == 0 || (this.adminsData.GOOGLE_RECAPCHA_SITE_KEY == '' || this.adminsData.GOOGLE_RECAPCHA_SECRET_KEY == '') );
        }
    }
}
</script>
