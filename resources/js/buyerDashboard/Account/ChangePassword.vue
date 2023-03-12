<template>
    <b-modal id="changePasswordModel" centered title="BootstrapVue">
        <template v-slot:modal-header>
            <h5 class="modal-title">{{$t('LBL_CHANGE_PASSWORD')}}</h5>
            <button type="button" class="close" @click="$bvModal.hide('changePasswordModel')">
                <span aria-hidden="true">×</span>
            </button>
        </template>
        <form id="YK-changePasswordForm" method="POST" action="javascript:;" class="form form-login form-floating">
            <div class="row justify-content-center mt-4">
                <div class="col-md-8">
                    <div class="form-group form-floating__group password-field">
                        <input type="password" class="form-control form-floating__field" id="old_password" name="old_password" v-model="userData.old_password" v-bind:class="[ userData.old_password != '' ? 'filled' : '']">
                        <label class="form-floating__label">{{ $t('LBL_OLD_PASSWORD') }}</label>
                    </div>
                    <div class="form-group form-floating__group password-field">
                        <input type="password" v-model="userData.new_password" class="form-control form-floating__field " id="new_password" name="new_password" ref="password" @focus="pswd_info = 1" @blur="pswd_info = 0" @keyup="onPasswordInput($event)" v-bind:class="[ userData.new_password != '' ? 'filled' : '']">
                        <label class="form-floating__label">{{ $t('LBL_NEW_PASSWORD') }}</label>
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
                    </div>
                    <div class="form-group form-floating__group password-field">
                        <input type="password" class="form-control form-floating__field " id="confirm_password" name="confirm_password" v-model="userData.confirm_password" v-bind:class="[ userData.new_password != '' ? 'filled' : '']">
                        <label class="form-floating__label">{{ $t('LBL_CONFIRM_PASSWORD') }}</label>
                        <div class="input-icon__icon input-icon__icon--right" >
                            <span>
                                <i class="password-toggle">
                                    <img :src="baseUrl + '/admin/images/retina/check.svg'" width="16px" height="16px" v-if="(userData.new_password != '' && userData.confirm_password != '' && userData.new_password == userData.confirm_password)">
                                    <img :src="baseUrl + '/admin/images/retina/cancel.svg'" width="16px" height="16px" v-else-if="(userData.new_password != '' && userData.confirm_password != '' && (userData.new_password != userData.confirm_password))">
                                </i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <template v-slot:modal-footer>
             <div class="d-flex w-100 justify-content-center">
                <button class="btn btn-brand btn-wide gb-btn gb-btn-primary" id="YK-changePasswordBtn" 
                        name="changePasswordBtn" :disabled="!isComplete || clicked==1" v-bind:class="clicked==1 && 'gb-is-loading'"  @click="changePassword()" type="button">
                    {{$t('BTN_CHANGE_PASSWORD_SUBMIT')}}
                </button>
             </div>
        </template>
    </b-modal>
</template>
<script>
const tableFields = {
    old_password: '',
    new_password: "",
    confirm_password: ""
};
export default {
    data: function() {
        return {
            baseUrl: baseUrl,
            userData: tableFields,
            passwordValidate : {
                length: false,
                letter: false,
                capital: false,
                number: false,
                special: false
            },
            clicked: 0,
            pswd_info : 0,
            displayConfirmPasswordCheck : false,
            changePasswordFormValidated : false,
        };
    },
    methods: {
        changePassword: function() {
            this.clicked = 1;
            let formData = this.$serializeData(this.userData);
            this.$axios.post(baseUrl + "/user/change-password", formData)
                .then((response) => {
                this.clicked = 0;
                if (response.status == 200) {
                    toastr.success(response.data.message, "", toastr.options);
                }
                this.emptyData();
                this.$bvModal.hide("changePasswordModel");
           }).catch(err => {
                this.clicked = 0;
                Object.keys(err.response.data.errors).forEach(function (key) {
                    toastr.error(err.response.data.errors[key][0], "", toastr.options);
                });
           });
        },
        validatePasswordForm : function() {
            return (!Object.values(this.passwordValidate).includes(false) && this.userData.old_password != '' && this.userData.new_password != '' && this.userData.confirm_password != '' && this.userData.new_password == this.userData.confirm_password);
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
        emptyData: function() {
            this.userData.old_password = '';
            this.userData.new_password = '';
            this.userData.confirm_password = '';
        }
    },
    computed: {
        isComplete() {
        return (
            this.userData.old_password &&
            this.userData.new_password &&
            this.userData.confirm_password &&
           (this.userData.new_password == this.userData.confirm_password)
        );
        }
    },
};
</script>