<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_ADMIN_CHANGE_PASSWORD') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_ADMIN_CHANGE_PASSWORD')}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="grid-layout">
                <button class="grid-layout-close" id="user_profile_aside_close">
                    <i class="la la-close"></i>
                </button>
                <profile-sidebar :tab="'changePassword'"></profile-sidebar>
                <div class="grid-layout-content">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="portlet portlet--height-fluid">
                                <div class="portlet__body">
                                    <div class="section">
                                        <div class="section__body">
                                            <div class="row justify-content-center">
                                                <div class="col-md-8">    
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">{{ $t('LBL_OLD_PASSWORD') }}</label>
                                                        <div class="col-lg-9">
                                                            <input type="password" name="admin_old_password" v-validate="'required'" :data-vv-as="$t('LBL_OLD_PASSWORD')" data-vv-validate-on="none" class="form-control" v-model="adminsData.admin_old_password" />
                                                            <span v-if="errors.first('admin_old_password')" class="error text-danger">{{ errors.first('admin_old_password') }}</span>

                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">{{ $t('LBL_PASSWORD') }}</label>
                                                        <div class="col-lg-9">
                                                            <div class="input-icon input-icon--right">
                                                                <input type="password" 
                                                                    name="admin_password" 
                                                                    v-validate="'required'" 
                                                                    :data-vv-as="$t('LBL_PASSWORD')" 
                                                                    data-vv-validate-on="none" 
                                                                    class="form-control" 
                                                                    v-model="adminsData.admin_password" 
                                                                    @focus="pswd_info = 1" 
                                                                    @blur="pswd_info = 0"
                                                                    @keyup="onPasswordInput($event)"
                                                                />
                                                                <div class="input-icon__icon input-icon__icon--right" v-if="!Object.values(passwordValidate).includes(false)">
                                                                    <span>
                                                                        <i class="password-toggle">
                                                                            <img :src="baseUrl + '/admin/images/retina/check.svg'" width="16px" height="16px">
                                                                        </i>
                                                                    </span>
                                                                </div>
                                                                <div id="pswd_info" v-show="pswd_info">
                                                                    <h4>{{ $t('LBL_PASSWORD_MUST_CONTAIN') }}:</h4>
                                                                    <ul>
                                                                        <li id="length" v-bind:class="[ passwordValidate.length === true ? 'valid' : 'invalid']">{{$t('MSG_VALIDATE_LENGTH')}}</li>
                                                                        <li id="letter" v-bind:class="[ passwordValidate.letter === true ? 'valid' : 'invalid']">{{$t('MSG_VALIDATE_LETTER')}}</li>
                                                                        <li id="capital" v-bind:class="[ passwordValidate.capital === true ? 'valid' : 'invalid']">{{$t('MSG_VALIDATE_UPPERCASE')}}</li>
                                                                        <li id="number" v-bind:class="[ passwordValidate.number === true ? 'valid' : 'invalid']">{{$t('MSG_VALIDATE_NUMBER')}}</li>
                                                                        <li id="special" v-bind:class="[ passwordValidate.special === true ? 'valid' : 'invalid']">{{$t('MSG_VALIDATE_SPECIAL_CHAR')}}</li>
                                                                    </ul>
                                                                </div>

                                                                <span v-if="errors.first('admin_password')" class="error text-danger">{{ errors.first('admin_password') }}</span>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">{{ $t('LBL_CONFIRM_PASSWORD') }}</label>
                                                        <div class="col-lg-9">
                                                            <div class="input-icon input-icon--right">
                                                                <input type="password" 
                                                                    name="admin_confirm_password" 
                                                                    :data-vv-as="$t('LBL_CONFIRM_PASSWORD')" 
                                                                    v-validate="'required'" 
                                                                    data-vv-validate-on="none" 
                                                                    class="form-control" 
                                                                    v-model="adminsData.admin_confirm_password" 
                                                                    autocomplete="new-password"
                                                                    @keyup="onConfirmPasswordInput($event)"
                                                                />
                                                                <div class="input-icon__icon input-icon__icon--right" v-if="displayConfirmPasswordCheck === true">
                                                                    <span>
                                                                        <i class="password-toggle">
                                                                            <img :src="baseUrl + '/admin/images/retina/check.svg'" width="16px" height="16px">
                                                                        </i>
                                                                    </span>
                                                                </div>
                                                                <span v-if="errors.first('admin_confirm_password')" class="error text-danger">{{ errors.first('admin_confirm_password') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet__foot">
                                    <div class="row justify-content-center">
                                        <div class="col-auto">
                                            <button type="submit" @click="editPassword" :disabled="!isComplete || clicked==1" v-bind:class="clicked==1 && 'gb-is-loading'" class="btn btn-brand btn-wide gb-btn gb-btn-primary">{{ $t('BTN_SUBMIT') }}</button>
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
    const tableFields = {
        'admin_password': '',
        'admin_name': '',
        'admin_confirm_password': '',
        'admin_old_password': '',
        'role_name': ''
    };
    export default {
        components: {
            'profile-sidebar': profileSidebar
        },
        data: function() {
            return {
                baseUrl: baseUrl,
                records: [],
                uploadedImage: '',
                clicked: 0,
                adminsData: tableFields,
                pswd_info : 0,
                displayConfirmPasswordCheck : false,
                changePasswordFormValidated : false,
                passwordValidate : {
                    length: false,
                    letter: false,
                    capital: false,
                    number: false,
                    special: false
                },
            }
        },
        computed: {
            isComplete() {
                return (this.adminsData.admin_password && this.adminsData.admin_confirm_password && this.adminsData.admin_old_password && (this.changePasswordFormValidated || this.passClicked==1));
            },
        },
        methods: {
            editPassword: function() {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        this.updatePassword();
                    }
                });
            },
            getUser: function() {
                this.$http.get(adminBaseUrl + '/get-user').then((response) => {
                    let roleName = this.$t('LBL_SUPER_ADMIN');
                    if (response.data.data.admin_role_id != null) {
                        roleName = response.data.data.admin_role.role_name;
                    }
                    this.appName = response.data.appName;
                    this.noImage = response.data.noImage;
                    this.adminsData.role_name = roleName;
                    this.adminsData.admin_name = response.data.data.admin_name;
                    this.uploadedImage = this.$getFileUrl('profileImage', response.data.data.admin_profile_image.afile_record_id, 0, 'thumb');
                });
            },
            updatePassword: function() {
                this.clicked = 1;
                let formData = this.$serializeData(this.adminsData);
                this.$http.post(adminBaseUrl + '/change-password', formData)
                    .then((response) => { //success
                        toastr.success(response.data.success, '', {
                            timeOut: 5000
                        });
                        this.clicked = 0;
                        setTimeout(function() {
                            location.href = adminBaseUrl + '/logout';
                        }, 5000);
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
            validatePasswordForm : function() {
                return (!Object.values(this.passwordValidate).includes(false) && this.adminsData.admin_password != '' && this.adminsData.admin_confirm_password != '' && this.adminsData.admin_password == this.adminsData.admin_confirm_password);
            },
            onPasswordInput : function(event) {
                var pswd = event.target.value;
                if ( pswd.length < 8 ) {
                    this.passwordValidate.length = false;
                } else {
                    this.passwordValidate.length = true;
                }
                if ( pswd.match(/[A-z]/) ) {
                    this.passwordValidate.letter = true;
                } else {
                    this.passwordValidate.letter = false;
                }
                if ( pswd.match(/[A-Z]/) ) {
                    this.passwordValidate.capital = true;
                } else {
                    this.passwordValidate.capital = false;
                }
                if ( pswd.match(/[^\w]/) ) {
                    this.passwordValidate.special = true;
                } else {
                    this.passwordValidate.special = false;
                }
                if ( pswd.match(/\d/) ) {
                    this.passwordValidate.number = true;
                } else {
                    this.passwordValidate.number = false;
                }
                this.changePasswordFormValidated = this.displayConfirmPasswordCheck = this.validatePasswordForm() ? true : false;
            },
            onConfirmPasswordInput : function(event) {
                this.changePasswordFormValidated = this.displayConfirmPasswordCheck = this.validatePasswordForm() ? true : false;
            },
        },
        mounted: function() {
            this.getUser();
        }
    }
</script>