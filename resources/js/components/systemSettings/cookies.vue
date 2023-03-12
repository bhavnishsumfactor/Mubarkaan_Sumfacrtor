<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_SYSTEM_SETTINGS') }}</h3>
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
        <div class="portlet">
            <div class="portlet__body">
                <div class="section">
                    <div class="section__body">
                        <div class="row justify-content-center">
                            <div class="col-xl-8">
                                <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">{{ $t('LBL_ENABLE_COOKIES') }}</label>
                                                <div class="col-lg-8">
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                            <input type="radio" checked="checked" :value="1" v-model="adminsData.ACCEPT_COOKIE_ENABLE" name="ACCEPT_COOKIE_ENABLE"> {{$t('Yes')}}<span></span>
                                                        </label>
                                                        <label class="radio">
                                                            <input type="radio" name="ACCEPT_COOKIE_ENABLE" :value="0" v-model="adminsData.ACCEPT_COOKIE_ENABLE"> {{$t('No')}}<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">{{ $t('LBL_COOKIE_TEXT') }}</label>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control" rows="6" v-model="adminsData.ACCEPT_COOKIE_TEXT" name="ACCEPT_COOKIE_TEXT" :disabled="adminsData.ACCEPT_COOKIE_ENABLE == 0" v-validate="adminsData.ACCEPT_COOKIE_ENABLE==1 ? 'required' : '' " data-vv-validate-on="none" :data-vv-as="$t('LBL_COOKIE_TEXT')">
                                                    </textarea>
                                                    <span v-if="errors.first('ACCEPT_COOKIE_TEXT')" class="error text-danger">{{ errors.first('ACCEPT_COOKIE_TEXT') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">{{ $t('LBL_ENABLE_ADVANCED_COOKIE_PREFERENCES') }}</label>
                                                <div class="col-lg-8">
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                            <input type="radio" checked="checked" :value="1" v-model="adminsData.ENABLE_ADVANCED_COOKIE_PREFERENCES" name="ENABLE_ADVANCED_COOKIE_PREFERENCES"> {{$t('LBL_YES')}}<span></span>
                                                        </label>
                                                        <label class="radio">
                                                            <input type="radio" name="ENABLE_ADVANCED_COOKIE_PREFERENCES" :value="0" v-model="adminsData.ENABLE_ADVANCED_COOKIE_PREFERENCES"> {{$t('LBL_NO')}}<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">{{ $t('LBL_ADVANCED_COOKIE_PREFERENCES_TEXT') }}</label>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control" rows="6" v-model="adminsData.ADVANCED_PREFERENCES_COOKIE_TEXT" name="ADVANCED_PREFERENCES_COOKIE_TEXT" :disabled="adminsData.ACCEPT_COOKIE_ENABLE == 0 || adminsData.ENABLE_ADVANCED_COOKIE_PREFERENCES == 0" v-validate="adminsData.ENABLE_ADVANCED_COOKIE_PREFERENCES==1 ? 'required' : '' " data-vv-validate-on="none" :data-vv-as="$t('LBL_ADVANCED_COOKIE_PREFERENCES_TEXT')">
                                                    </textarea>
                                                    <span v-if="errors.first('ADVANCED_PREFERENCES_COOKIE_TEXT')" class="error text-danger">{{ errors.first('ADVANCED_PREFERENCES_COOKIE_TEXT') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">{{ $t('LBL_FUNCTIONAL_COOKIE_TEXT') }}</label>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control" rows="6" v-model="adminsData.FUNCTIONAL_COOKIE_TEXT" name="FUNCTIONAL_COOKIE_TEXT" :disabled="adminsData.ACCEPT_COOKIE_ENABLE == 0 || adminsData.ENABLE_ADVANCED_COOKIE_PREFERENCES == 0" v-validate="adminsData.ENABLE_ADVANCED_COOKIE_PREFERENCES==1 ? 'required' : '' " data-vv-validate-on="none" :data-vv-as="$t('LBL_FUNCTIONAL_COOKIE_TEXT')">
                                                    </textarea>
                                                    <span v-if="errors.first('FUNCTIONAL_COOKIE_TEXT')" class="error text-danger">{{ errors.first('FUNCTIONAL_COOKIE_TEXT') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">{{ $t('LBL_STATISTICAL_ANALYSIS_COOKIE_TEXT') }}</label>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control" rows="6" v-model="adminsData.STATISTICAL_ANALYSIS_COOKIE_TEXT" name="STATISTICAL_ANALYSIS_COOKIE_TEXT" :disabled="adminsData.ACCEPT_COOKIE_ENABLE == 0 || adminsData.ENABLE_ADVANCED_COOKIE_PREFERENCES == 0" v-validate="adminsData.ENABLE_ADVANCED_COOKIE_PREFERENCES==1 ? 'required' : '' " data-vv-validate-on="none" :data-vv-as="$t('LBL_STATISTICAL_ANALYSIS_COOKIE_TEXT')">
                                                    </textarea>
                                                    <span v-if="errors.first('STATISTICAL_ANALYSIS_COOKIE_TEXT')" class="error text-danger">{{ errors.first('STATISTICAL_ANALYSIS_COOKIE_TEXT') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">{{ $t('LBL_PERSONALISE_COOKIE_TEXT') }}</label>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control" rows="6" v-model="adminsData.PERSONALIZE_COOKIE_TEXT" name="PERSONALIZE_COOKIE_TEXT" :disabled="adminsData.ACCEPT_COOKIE_ENABLE == 0 || adminsData.ENABLE_ADVANCED_COOKIE_PREFERENCES == 0" v-validate="adminsData.ENABLE_ADVANCED_COOKIE_PREFERENCES==1 ? 'required' : '' " data-vv-validate-on="none" :data-vv-as="$t('LBL_PERSONALISE_COOKIE_TEXT')">
                                                    </textarea>
                                                    <span v-if="errors.first('PERSONALIZE_COOKIE_TEXT')" class="error text-danger">{{ errors.first('PERSONALIZE_COOKIE_TEXT') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">{{ $t('LBL_AD_SOCIAL_MEDIA_COOKIE_TEXT') }}</label>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control" rows="6" v-model="adminsData.ADVERTISING_SOCIAL_MEDIA_COOKIE_TEXT" name="ADVERTISING_SOCIAL_MEDIA_COOKIE_TEXT" :disabled="adminsData.ACCEPT_COOKIE_ENABLE == 0 || adminsData.ENABLE_ADVANCED_COOKIE_PREFERENCES == 0" v-validate="adminsData.ENABLE_ADVANCED_COOKIE_PREFERENCES==1 ? 'required' : '' " data-vv-validate-on="none" :data-vv-as="$t('LBL_AD_SOCIAL_MEDIA_COOKIE_TEXT')">
                                                    </textarea>
                                                    <span v-if="errors.first('ADVERTISING_SOCIAL_MEDIA_COOKIE_TEXT')" class="error text-danger">{{ errors.first('ADVERTISING_SOCIAL_MEDIA_COOKIE_TEXT') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">{{ $t('LBL_ENABLE_DOUBLE_OPTIN') }}</label>
                                                <div class="col-lg-8">
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                            <input type="radio" checked="checked" :value="1" v-model="adminsData.NEWSLETTER_DOUBLE_OPTIN" name="NEWSLETTER_DOUBLE_OPTIN"> {{$t('LBL_YES')}}<span></span>
                                                        </label>
                                                        <label class="radio">
                                                            <input type="radio" name="NEWSLETTER_DOUBLE_OPTIN" :value="0" v-model="adminsData.NEWSLETTER_DOUBLE_OPTIN"> {{$t('LBL_NO')}}<span></span>
                                                        </label>
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
                    <button type="button" class="btn btn-brand btn-wide" @click="updateSettings" :disabled='clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'">{{$t('BTN_SETTINGS_UPDATE')}}</button>
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
    'ACCEPT_COOKIE_TEXT': '',
    'ACCEPT_COOKIE_ENABLE': '',
    'ENABLE_ADVANCED_COOKIE_PREFERENCES': '',
    'FUNCTIONAL_COOKIE_TEXT': '',
    'ADVANCED_PREFERENCES_COOKIE_TEXT': '',
    'STATISTICAL_ANALYSIS_COOKIE_TEXT': '',
    'PERSONALIZE_COOKIE_TEXT': '',
    'ADVERTISING_SOCIAL_MEDIA_COOKIE_TEXT': '',
    'NEWSLETTER_DOUBLE_OPTIN': ''
};
export default {
    components: {
        'sidebar': sidebar
    },
    data: function() {
        return {
            adminsData: tableFields,
            type: 'cookies',
            clicked: 0
        }
    },
    methods: {
        getSettings: function() {
            let formData = this.$serializeData({'keys':['ACCEPT_COOKIE_ENABLE','ACCEPT_COOKIE_TEXT', 'ENABLE_ADVANCED_COOKIE_PREFERENCES', 'FUNCTIONAL_COOKIE_TEXT', 'ADVANCED_PREFERENCES_COOKIE_TEXT',
            'STATISTICAL_ANALYSIS_COOKIE_TEXT', 'PERSONALIZE_COOKIE_TEXT', 'ADVERTISING_SOCIAL_MEDIA_COOKIE_TEXT','NEWSLETTER_DOUBLE_OPTIN']});
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
