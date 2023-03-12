<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_SUB_ADMIN_SETUP') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_USERS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <router-link :to="{name: 'subAdmins'}" class="subheader__breadcrumbs-link">{{ $t('LBL_SUB_ADMINS')}}</router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_SETUP')}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center" id="manage-admins">
                <div class="col-lg-8">
                    <!--begin::Portlet-->
                    <div class="portlet portlet-no-min-height" id="page_portlet">
                        <div class="portlet__body">
                            <div class="form-group row justify-content-center">
                                <label class="col-lg-3">
                                    {{ $t('LBL_ADMIN_NAME')}}
                                    <span class="required">*</span>
                                </label>
                                <div class="col-lg-5">
                                    <input type="text" v-model="adminsData.admin_name" name="admin_name" v-validate="'required'" :data-vv-as="$t('LBL_ADMIN_NAME')" data-vv-validate-on="none" class="form-control" />
                                    <span v-if="errors.first('admin_name')" class="error text-danger">{{errors.first('admin_name')}}</span>
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label class="col-lg-3">
                                    {{ $t('LBL_ADMIN_USERNAME')}}
                                    <span class="required">*</span>
                                </label>
                                <div class="col-lg-5">
                                    <input type="text" v-model="adminsData.admin_username" name="admin_username" v-validate="'required'" :data-vv-as="$t('LBL_ADMIN_USERNAME')" data-vv-validate-on="none" class="form-control" />
                                    <span v-if="errors.first('admin_username')" class="error text-danger">{{ errors.first('admin_username') }}</span>
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label class="col-lg-3">
                                    {{ $t('LBL_ADMIN_PHONE')}}
                                    <span class="required">*</span>
                                </label>
                                <div class="col-lg-5">
                                    <vue-tel-input v-if="defaultCountryCode !='' " v-model="adminsData.admin_phone" :defaultCountry="defaultCountryCode" :enabledCountryCode="true" @country-changed="changeCountry" @input="onPhoneNumberChange" inputClasses="form-control" :placeholder="$t('PLH_ENTER_PHONE_NUMBER')" :validCharactersOnly="true"></vue-tel-input>
                                    <span v-if="errors.first('admin_phone')" class="error text-danger">{{ errors.first('admin_phone') }}</span>
                                    <span v-if="errors.first('admin_phone_country_id')" class="error text-danger">{{ errors.first('admin_phone_country_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label class="col-lg-3">
                                    {{ $t('LBL_ADMIN_EMAIL')}}
                                    <span class="required">*</span>
                                </label>
                                <div class="col-lg-5">
                                    <input type="email" v-model="adminsData.admin_email" name="admin_email" v-validate="'required'" :data-vv-as="$t('LBL_ADMIN_EMAIL')" data-vv-validate-on="none" class="form-control" />
                                    <span v-if="errors.first('admin_email')" class="error text-danger">{{ errors.first('admin_email') }}</span>
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <label class="col-lg-3">
                                    {{ $t('LBL_ADMIN_PASSWORD')}}
                                    <span class="required">*</span>
                                </label>
                                <div class="col-lg-5">
                                    <div class="input-icon input-icon--right">
                                        <input type="password" 
                                            v-model="adminsData.admin_password" 
                                            name="admin_password" 
                                            v-validate="'required'" 
                                            ref="password" 
                                            :data-vv-as="$t('LBL_ADMIN_PASSWORD')" 
                                            data-vv-validate-on="none" class="form-control" 
                                            @focus="pswd_info = 1" @blur="pswd_info = 0"
                                            @keyup="onPasswordInput($event)"/>

                                        <div class="input-icon__icon input-icon__icon--right" v-if="!Object.values(passwordValidate).includes(false)">
                                            <span>
                                                <i class="password-toggle">
                                                    <img :src="baseUrl + '/admin/images/retina/check.svg'" width="16px" height="16px">
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
                                    <!---->
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <label class="col-lg-3"> {{ $t('LBL_ADMIN_CONFIRM_PASSWORD')}}
                                    <span class="required">*</span>
                                </label>
                                <div class="col-lg-5">
                                    <div class="input-icon input-icon--right">
                                        <input type="password" 
                                            v-model="adminsData.admin_confirm_password" 
                                            name="admin_confirm_password" 
                                            v-validate="'required|confirmed:password'" 
                                            :data-vv-as="$t('LBL_ADMIN_CONFIRM_PASSWORD')" 
                                            data-vv-validate-on="none" 
                                            class="form-control"
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
                            <div class="form-group row justify-content-center">
                                <label class="col-lg-3">
                                    {{ $t('LBL_ADMIN_ROLE')}}
                                    <span class="required">*</span>
                                </label>
                                <div class="col-lg-5">
                                    <select v-model="adminsData.admin_role_id" name="admin_role_id" v-validate="'required'" :data-vv-as="$t('LBL_ADMIN_ROLE')" data-vv-validate-on="none" class="form-control">
                                        <option value disabled>{{ $t('LBL_SELECT_ONE')}}</option>
                                        <option v-for="role in roles" :value="role.role_id" :key="role.role_id">{{role.role_name}}</option>
                                    </select>
                                    <span v-if="errors.first('admin_role_id')" class="error text-danger">{{ errors.first('admin_role_id') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="portlet__foot">
                            <div class="row">
                                <div class="col">
                                    <router-link :to="{name: 'subAdmins'}" class="btn btn-secondary">{{ $t('BTN_DISCARD')}}</router-link>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-wide btn-brand gb-btn gb-btn-primary" @click="validateRecord()" :disabled="!isComplete || clicked==1" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_ADMIN_CREATE')}}</button>
                                </div>
                            </div>
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
  admin_name: "",
  admin_username: "",
  admin_email: "",
  admin_phone_country_id: "",
  admin_phone: "",
  admin_role_id: "",
  admin_password: "",
  admin_confirm_password: ""
};
export default {
  data: function() {
    return {
        baseUrl: baseUrl,
        records: [],
        adminsData: tableFields,
        roles: [],
        countries: [],
        clicked: 0,
        defaultCountryCode : '',
        countryCode: '',
        unmaskedPhoneNumber:'',
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
    };
  },
  computed: {
    isComplete() {
      return (
        this.adminsData.admin_name &&
        this.adminsData.admin_username &&
        this.adminsData.admin_email &&
        this.adminsData.admin_phone &&
        this.adminsData.admin_role_id &&
        this.adminsData.admin_password &&
        this.adminsData.admin_confirm_password && 
        (this.changePasswordFormValidated || this.passClicked==1)
      );
    }
  },
  methods: {
    validateRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          this.clicked = 1;
          let input = this.adminsData;
          this.saveRecord(input);
        } else {
          this.clicked = 0;
        }
      });
    },
    saveRecord: function(input) {
        if (this.unmaskedPhoneNumber != '') {
            input.admin_phone = this.unmaskedPhoneNumber;
        }
        let formData = this.$serializeData(input);
        formData.append('country_code', this.countryCode);
        this.$http.post(adminBaseUrl + "/sub-admins", formData).then(
        response => {
          //success
          if (response.data.status == false) {
            this.clicked = 0;
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.clicked = 0;
          this.$router.push({ name: "subAdmins" });
        },
        response => {
          //error
          this.clicked = 0;
          this.validateErrors(response);
        }
      );
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
        this.$http.post(adminBaseUrl + "/sub-admins/roles").then(response => {
            this.roles = response.data.data.roles;
            this.countries = response.data.data.countries;
            this.defaultCountryCode = response.data.data.defaultCountryCode;
        });
    },
    emptyFormValues: function() {
      this.adminsData = {
        admin_name: "",
        admin_username: "",
        admin_email: "",
        admin_phone_country_id: "",
        admin_phone: "",
        admin_role_id: "",
        admin_password: "",
        admin_confirm_password: ""
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
    this.emptyFormValues();
    this.pageLoadData();
  }
};
</script>
<style lang="css" scoped>
    .vue-tel-input {
        flex: 1;
    }
</style>