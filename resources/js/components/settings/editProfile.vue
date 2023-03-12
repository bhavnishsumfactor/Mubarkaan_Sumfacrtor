<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_EDIT_PROFILE') }}</h3>
                <div class="subheader__breadcrumbs">
                    <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </router-link>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <span class="subheader__breadcrumbs-link">{{$t('LBL_EDIT_PROFILE')}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="grid-layout">
            <button class="grid-layout-close" id="user_profile_aside_close">
                <i class="la la-close"></i>
            </button>
            <profile-sidebar :tab="'editProfile'"></profile-sidebar>
            <div class="grid-layout-content">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="portlet portlet--height-fluid">
                            <div class="portlet__body">
                                <div class="section">
                                    <div class="section__body">
                                        <div class="row justify-content-center">
                                            <div class="col-md-8">      
                                                <form class="form form--label-right">
                                                    <input type="hidden" name="id" v-model="adminsData.admin_id">
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">{{ $t('LBL_FULL_NAME') }} <span class="required">*</span></label>
                                                        <div class="col-lg-9">
                                                            <input type="text" v-model="adminsData.admin_name" name="name" v-validate="'required'" data-vv-validate-on="none" class="form-control" />
                                                            <span v-if="errors.first('name')" class="error text-danger">{{ errors.first('name') }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">{{ $t('LBL_USERNAME') }}</label>
                                                        <div class="col-lg-9">
                                                            <input type="text" v-model="adminsData.admin_username" name="username" v-validate="'required'" data-vv-validate-on="none" class="form-control" disabled/>
                                                            <span v-if="errors.first('username')" class="error text-danger">{{ errors.first('username') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">{{ $t('LBL_EMAIL_ADDRESS') }}</label>
                                                        <div class="col-lg-9">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                                                <input type="text" v-model="adminsData.admin_email" name="email" :disabled="true" v-validate="'required'" data-vv-validate-on="none" aria-describedby="basic-addon1" class="form-control" />
                                                            </div>
                                                            <span v-if="errors.first('email')" class="error text-danger">{{ errors.first('email') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">{{ $t('LBL_PHONE') }}</label>
                                                        <div class="col-lg-9 ">
                                                            <div class="input-group">
                                                                <vue-tel-input v-if=" defaultCountryCode !=''"  v-model="adminsData.admin_phone" :defaultCountry="defaultCountryCode" :enabledCountryCode="true" @country-changed="changeCountry" @input="onPhoneNumberChange" inputClasses="form-control" placeholder="Enter a phone number" :validCharactersOnly="true"></vue-tel-input>
                                                            </div>
                                                            <span v-if="errors.first('admin_phone')" class="error text-danger">{{ errors.first('admin_phone') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">{{ $t('LBL_PREFERRED_LANGUAGE') }}</label>
                                                        <div class="col-lg-9">
                                                            <div class="input-group">
                                                                <select v-model="adminsData.admin_default_lang" class="form-control">
                                                                    <option v-for="langRecord in langRecords" :value="langRecord.lang_id" :key="langRecord.lang_id">{{langRecord.lang_name}}</option>
                                                                </select>
                                                            </div>
                                                            <span class="form-text text-muted">{{ $t('LBL_WHEN_YOU_ARE_LOGGEDIN') }} {{ appName }}, {{ $t("LBL_THIS_LANGUAGE_WILL_BE_DISPLAYED") }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">{{ $t('LBL_TIMEZONE') }}</label>
                                                        <div class="col-lg-9">
                                                            <div class="input-group">
                                                                <select v-model="adminsData.admin_default_timezone" class="form-control">
                                                                    <option :value="''">{{ $t('LBL_SELECT_TIMEZONE') }}</option>
                                                                    <option v-for="timezonerecord in timeZoneRecords" :value="timezonerecord" :key="timezonerecord">{{timezonerecord}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="portlet__foot">
                                <div class="row justify-content-center">
                                    <div class="col-auto">
                                        <button type="submit" :disabled="!isComplete || clicked==1" v-bind:class="clicked==1 && 'gb-is-loading'" class="btn btn-brand btn-wide gb-btn gb-btn-primary" @click="editUser">{{ $t('BTN_SUBMIT') }}</button>&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>


import profileSidebar from './profileSidebar';
Vue.http.headers.common['content-type'] = 'application/x-www-form-urlencoded';
const tableFields = {
    'admin_id': '',
    'admin_name': '',
    'admin_username': '',
    'admin_email': '',
    'admin_phone_country_id': '',
    'admin_phone': '',
    'role_name': '',
    'admin_default_lang': '',
    'admin_default_timezone': ''
};
export default {
    components: {
        'profile-sidebar': profileSidebar
    },
    data: function() {
        return {
            countries: [],
            langRecords: [],
            timeZoneRecords: [],
            appName: '',
            adminsData: tableFields,
            clicked: 0,
            defaultCountryCode : '',
            countryCode: '',
            unmaskedPhoneNumber:''
        }
    },
    computed: {
        isComplete() {
            return this.adminsData.admin_name;
        },
    },
    methods: {
        getUser: function() {
            this.$http.get(adminBaseUrl + '/get-user').then((response) => {
                let roleName = 'Super Admin';
                if (response.data.data.admin_role_id != null) {
                    roleName = response.data.data.admin_role.role_name;
                }
                this.langRecords = response.data.lang;
                this.countries = response.data.countries;
                this.appName = response.data.appName;
                this.timeZoneRecords = response.data.timezone;
                
                this.adminsData = response.data.data;
                this.adminsData.admin_default_timezone = this.adminsData.admin_default_timezone != null ? this.adminsData.admin_default_timezone : '';
                this.adminsData.admin_phone_country_id = response.data.data.admin_phone_country_id;
                this.adminsData.role_name = roleName;
                this.defaultCountryCode = (response.data.data.country_code) ? response.data.data.country_code.toLowerCase() : 'us';
            });
        },
        editUser: function() {
            this.$validator.validateAll().then(res => {
                if (res) {
                    let input = this.adminsData;
                    if (input.id != '') {
                        this.updateUser(input);
                    }
                }
            });
        },
        updateUser: function(input) {
            if (this.unmaskedPhoneNumber != '') {
                input.admin_phone = this.unmaskedPhoneNumber;
            }
            this.clicked = 1;
            let formData = this.$serializeData(input);
            formData.append('country_code', this.countryCode);
            this.$http.post(adminBaseUrl + '/edit-profile/' + input.admin_id, formData)
                .then((response) => { //success
                    this.clicked = 0;
                    this.getUser();
                    localStorage.setItem('timezone', this.adminsData.admin_default_timezone);
                    toastr.success(response.data.success, '', {
                        timeOut: 5000
                    });
                }, (response) => { //error
                    this.validateErrors(response);
                    this.clicked = 0;
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
        changeCountry: function(data) {
            this.countryCode = data.iso2;
        },
        onPhoneNumberChange: function(data,obj) {
            this.countryCode = obj.country.iso2;
            this.unmaskedPhoneNumber = obj.number.significant;
        }
    },
    mounted: function() {
        this.getUser();
    }
}
</script>
<style lang="css" scoped>
    .vue-tel-input {
        flex: 1;
    }
</style>
