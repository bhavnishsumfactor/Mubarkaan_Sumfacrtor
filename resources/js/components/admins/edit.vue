<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title"> {{ $canWrite('ADMIN_USERS') ? $t('LBL_EDITING') + ' -' : ''}} {{adminsData.admin_name}}</h3>
                <div class="subheader__breadcrumbs">
                    <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </router-link>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <span class="subheader__breadcrumbs-link">{{$t('LBL_USERS')}}</span>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <router-link :to="{name: 'subAdmins'}" class="subheader__breadcrumbs-link">{{ $t('LBL_SUB_ADMINS')}}</router-link>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <span class="subheader__breadcrumbs-link">{{$t('LBL_EDIT')}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center" id="manage-admins">
            <div class="col-lg-8">
                <!--begin::Portlet-->
                <div class="portlet portlet-no-min-height" id="page_portlet">
                    <div class="portlet__body" v-bind:class="[(!$canWrite('ADMIN_USERS')) ? 'disabledbutton': '']">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ $t('LBL_ADMIN_NAME')}} <span class="required">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" v-model="adminsData.admin_name" name="admin_name" v-validate="'required'" :data-vv-as="$t('LBL_ADMIN_NAME')" data-vv-validate-on="none" class="form-control" />
                                <span v-if="errors.first('admin_name')" class="error text-danger">{{errors.first('admin_name')}}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ $t('LBL_ADMIN_USERNAME')}} <span class="required">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" v-model="adminsData.admin_username" name="admin_username" v-validate="'required'" :data-vv-as="$t('LBL_ADMIN_USERNAME')" data-vv-validate-on="none" class="form-control" disabled/>
                                <span v-if="errors.first('admin_username')" class="error text-danger">{{ errors.first('admin_username') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ $t('LBL_ADMIN_PHONE')}} <span class="required">*</span></label>
                            <div class="col-lg-9">
                                <vue-tel-input v-if="defaultCountryCode !='' " v-model="adminsData.admin_phone" :defaultCountry="defaultCountryCode" :enabledCountryCode="true" @country-changed="changeCountry" @input="onPhoneNumberChange" inputClasses="form-control" :placeholder="$t('PLH_ENTER_PHONE_NUMBER')" :validCharactersOnly="true"></vue-tel-input>
                                <span v-if="errors.first('admin_phone')" class="error text-danger">{{ errors.first('admin_phone') }}</span>
                                <span v-if="errors.first('admin_phone_country_id')" class="error text-danger">{{ errors.first('admin_phone_country_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ $t('LBL_ADMIN_EMAIL')}} <span class="required">*</span></label>
                            <div class="col-lg-9">
                                <input type="email" v-model="adminsData.admin_email" name="admin_email" v-validate="'required'" :data-vv-as="$t('LBL_ADMIN_EMAIL')" data-vv-validate-on="none" class="form-control" />
                                <span v-if="errors.first('admin_email')" class="error text-danger">{{ errors.first('admin_email') }}</span>
                                
                                <span class="form-text text-muted"> 
                                   <a href="javascript:;" @click="openChangePasswordPopup">{{$t('LNK_CHANGE_PASSWORD')}}</a>
                                </span>
                            </div>

                            <!--end::Portlet-->
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ $t('LBL_ADMIN_ROLE')}} <span class="required">*</span></label>
                                <div class="col-lg-9">
                                <select  v-model="adminsData.admin_role_id" name="admin_role_id" v-validate="'required'" :data-vv-as="$t('LBL_ADMIN_ROLE')" data-vv-validate-on="none" class="form-control">
                                <option value="" disabled>{{ $t('Select one')}}</option>
                                <option v-for="role in roles" :value="role.role_id" :key="role.role_id">{{role.role_name}}</option>
                                
                                </select>
                                <span v-if="errors.first('admin_role_id')" class="error text-danger">{{ errors.first('admin_role_id') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ $t('LBL_CREATED')}}</label>
                            <div class="col-lg-9">
                               <span class="ml-auto">{{ createdByUser ? createdByUser + ' |' : '' }}  <span class="time">{{ createdAt |  formatDateTime}}</span> </span>
                            </div>
                            <!--end::Portlet-->
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">{{ $t('LBL_LAST_UPDATED')}}</label>
                            <div class="col-lg-9">
                                <span class="ml-auto">{{ updatedByUser ? updatedByUser + ' |' : ''}}  <span class="time">{{ updatedAt | formatDateTime}}</span> </span>
                            </div>
                            <!--end::Portlet-->
                        </div>
                    </div>
                    <div class="portlet__foot">
                        <div class="row">
                            <div class="col">
                                <router-link :to="{name: 'subAdmins'}" class="btn btn-secondary">{{ $t('BTN_DISCARD')}}</router-link>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-wide btn-brand gb-btn gb-btn-primary" @click="validateRecord()" :disabled="!isComplete || clicked==1 || (!$canWrite('ADMIN_USERS'))" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_SUBADMIN_UPDATE')}}</button>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <b-modal id="passwordUpdateModal" centered title="BootstrapVue">
        <template v-slot:modal-header>
            <h5 class="modal-title" id="exampleModalLabel">{{ $t('LBL_CHANGE_PASSWORD') }}</h5>
            <button type="button" class="close" @click="$bvModal.hide('passwordUpdateModal')"></button>
        </template>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">{{$t('LBL_PASSWORD') }}</label>
                    <div class="col-lg-9">
                        <div class="input-icon input-icon--right">
                            <input type="password" v-model="adminsData.admin_password" name="admin_password" class="form-control" ref="new-password" autocomplete="new-password" @focus="pswd_info = 1" @blur="pswd_info = 0" @keyup="onPasswordInput($event)" v-validate="'required'" :data-vv-as="$t('LBL_PASSWORD')"/>
                            <div class="input-icon__icon input-icon__icon--right" v-if="!Object.values(passwordValidate).includes(false)">
                                <span>
                                    <i class="password-toggle">
                                        <img :src="baseUrl + '/admin/images/retina/check.svg'" width="16px" height="16px"/>
                                    </i>
                                </span>
                            </div>
                            <div id="pswd_info" v-show="pswd_info">
                                <h4>{{$t('LBL_PASSWORD_MUST_CONTAIN')}}:</h4>
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
            </div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">{{$t('LBL_CONFIRM_PASSWORD') }}</label>
                    <div class="col-lg-9">
                        <div class="input-icon input-icon--right">
                            <input type="password" v-model="adminsData.admin_confirm_password" name="admin_confirm_password" ref="confirm-password" class="form-control" autocomplete="new-password" @keyup="onConfirmPasswordInput($event)"/>
                            <div class="input-icon__icon input-icon__icon--right" v-if="displayConfirmPasswordCheck === true">
                                <span>
                                    <i class="password-toggle">
                                    <img
                                        :src="baseUrl + '/admin/images/retina/check.svg'"
                                        width="16px"
                                        height="16px"
                                    />
                                    </i>
                                </span>
                            </div>
                            <div class="input-icon__icon input-icon__icon--right" v-if="displayConfirmPasswordCheck === false">
                                <span>
                                    <i class="password-toggle">
                                    <img
                                        :src="baseUrl + '/admin/images/retina/check.svg'"
                                        width="16px"
                                        height="16px"
                                    />
                                    </i>
                                </span>
                            </div>
                            <span v-if="errors.first('admin_confirm_password')" class="error text-danger">{{ errors.first('admin_confirm_password') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <template v-slot:modal-footer>
            <button type="button" class="btn btn-secondary" @click="$bvModal.hide('passwordUpdateModal')">{{ $t('BTN_DISCARD')}}</button>
            <button type="button" :disabled="!changePasswordFormValidated || passClicked==1" @click="updatePassword()" class="btn btn-brand">
                {{ $t('BTN_CHANGE_PASSWORD_SUBMIT')}}</button>
        </template>
    </b-modal>
</div>
</template>
<script>

const tableFields = {
    'admin_id': '',
    'admin_name': '',
    'admin_username': '',
    'admin_email': '',
    'admin_phone_country_id': '',
    'admin_phone': '',
    'admin_role_id': '',
    'admin_password': '',
    'admin_confirm_password': ''
};
export default {	
	data: function () {
        return {
            baseUrl: baseUrl,
			passwordUpdate:false,
			records: [],
			adminsData:tableFields,
            roles: [], 
            clicked: 0,
            defaultCountryCode : '',
            countryCode: '',
            unmaskedPhoneNumber:'',
            createdByUser:'',
            updatedByUser:'',
            updatedAt:'',
            createdAt:'',
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
            passClicked: 0
       }
    },
    computed: {
        isComplete () {
            return this.adminsData.admin_name && this.adminsData.admin_username && this.adminsData.admin_email
            && this.adminsData.admin_phone_country_id && this.adminsData.admin_phone && this.adminsData.admin_role_id;
        }
    },
	methods: {
		validateRecord: function() {
			this.$validator.validateAll().then(res => {
				if (res) {
                    this.clicked = 1;
					let input = this.adminsData;
					this.updateRecord(input);
				} else {
                    this.clicked = 0;
                }
			});
		},
		updateRecord: function(input) {
            if (this.unmaskedPhoneNumber != '') {
                input.admin_phone = this.unmaskedPhoneNumber;
            }
            let formData = this.$serializeData(input);
            formData.append('country_code', this.countryCode);
            formData.append('_method', 'PUT');            
				this.$http.post(adminBaseUrl + '/sub-admins/'+input.admin_id, formData)
				.then((response) => { //success
					if (response.data.status == false) { 
						this.clicked = 0;
						toastr.error(response.data.message, '', toastr.options);
						return;
					}
                    toastr.success(response.data.message, '', toastr.options);
                    this.clicked = 0;
                    this.$router.push({ name: 'subAdmins'})
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
        pageLoadData: function() {
            let id = this.$route.params.id;
            this.$http.get(adminBaseUrl + '/sub-admins/'+id+'/edit')
                .then((response) => { //
                    this.roles = response.data.data.roles;                    
                    this.adminsData = response.data.data.record;
                    this.defaultCountryCode = (response.data.data.record.country_code === null) ? 'us': response.data.data.record.country_code.toLowerCase();
                    if (this.adminsData.created_at != null) {
                        this.createdByUser = this.adminsData.created_at.admin_username;
                    }
                    if (this.adminsData.updated_at != null) {
                        this.updatedByUser = this.adminsData.updated_at.admin_username;
                    }
                    this.updatedAt = this.adminsData.admin_updated_at ? this.adminsData.admin_updated_at : '';
                    this.createdAt = this.adminsData.admin_created_at ? this.adminsData.admin_created_at : '';
                });
        },
        emptyFormValues: function() {
            this.adminsData = {
                'admin_id': '',
                'admin_name': '',
                'admin_username': '',
                'admin_email': '',
                'admin_phone_country_id': '',
                'admin_phone': '',
                'admin_role_id': '',
                'admin_password': '',
                'admin_confirm_password': ''
            };
            this.errors.clear();
        },
        changeCountry: function(data) {
            this.countryCode = data.iso2;
        },
        onPhoneNumberChange: function(data,obj) {
            this.countryCode = obj.country.iso2;
            this.unmaskedPhoneNumber = obj.number.significant;
        },
        emptyUpdatedFieldValue : function() {
            this.createdByUser = '';
            this.updatedByUser = '';
            this.updatedAt = '';
            this.createdAt = '';
        },
        validatePasswordForm : function() {
            return (!Object.values(this.passwordValidate).includes(false) && this.adminsData.admin_password != '' && this.adminsData.admin_confirm_password != '' && this.adminsData.admin_password == this.adminsData.admin_confirm_password);
        },
        validatePasswordConfirmPassword : function() {
            return (this.adminsData.admin_password == this.adminsData.admin_confirm_password)
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
        openChangePasswordPopup: function() {
            this.adminsData.admin_password = '';
            this.adminsData.admin_confirm_password = '';
            this.adminsData.displayConfirmPasswordCheck = false;
            (
                this.passwordValidate = {
                    length: false,
                    letter: false,
                    capital: false,
                    number: false,
                    special: false
                });
            this.$bvModal.show("passwordUpdateModal");
        },
        updatePassword : function() {
            let id = this.$route.params.id;
            let formData = this.$serializeData({admin_password: this.adminsData.admin_password, admin_confirm_password:  this.adminsData.admin_confirm_password });
            this.$http.post(adminBaseUrl + '/sub-admins/'+ id + '/password-update', formData)
				.then((response) => { //success
					if (response.data.status == false) { 
						this.clicked = 0;
						toastr.error(response.data.message, '', toastr.options);
						return;
					}
                    toastr.success(response.data.message, '', toastr.options);
                    this.clicked = 0;
                    this.$bvModal.hide("passwordUpdateModal");
            }, (response) => { //error
                this.clicked = 0;
                this.validateErrors(response);
			});
        }
    },
    mounted: function() {
        this.emptyFormValues();
		this.pageLoadData();
	}
}
</script>