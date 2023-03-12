<template>
    <b-modal id="changeEmailPhoneModel" centered title="BootstrapVue">
        <template v-slot:modal-header>
            <h5 class="modal-title">{{ type == "email" ? $t('LBL_UPDATE_EMAIL') :  $t('LBL_UPDATE_PHONE') }}</h5>
            <button type="button" class="close" @click="$bvModal.hide('changeEmailPhoneModel')">
                <span aria-hidden="true">×</span>
            </button>
        </template>
            <form id="YK-editEmailForm" method="POST" action="javascript:;" class="form form-login form-floating">
                <div class="row justify-content-center mt-4 text-center">
                    <div class="col-md-8">
                        <div class="form-group mb-2">
                            <small>{{ type == "email" ? $t('LBL_ENTER_EMAIL_INSTRUCTIONS') : $t('LBL_ENTER_PHONE_INSTRUCTIONS') }}</small>
                        </div>
                        <div class="input-group form--fly" >
                            <input id="new_email" type="text" :placeholder="$t('LBL_EMAIL')" class="form-control" name="new_email" v-model="userData.new_email" v-if="type=='email'">
                            <vue-tel-input v-model="userData.user_phone" :defaultCountry="defaultCountryCode" :validCharactersOnly="true" :enabledCountryCode="true" @country-changed="changeCountry"
                                @input="onPhoneNumberChange" inputClasses="form-control" :placeholder="$t('PLH_ENTER_PHONE_NUMBER')" :maxLen="15" v-if="type=='phone'"></vue-tel-input>
                            <div class="input-group-append" v-if="type=='email'">
                                <button class="btn YK-resendCode btn-fly" v-bind:class="[ !isEmailComplete == true || counterRunning == 1  ? 'disabledbutton' : '']" type="button"  @click="changeEmailAddress()">
                                    <span class="YK-sendBtn" v-if="verifyOtp == 0" >
                                        <svg class="svg">
                                            <use
                                                :xlink:href="
                                                baseUrl +
                                                    '/dashboard/media/retina/sprite.svg#submitfly'
                                                "
                                            />
                                        </svg>
                                    </span>
                                    <span class="YK-resendBtn" v-if="resendOtp == 1">
                                        {{$t('BTN_RESEND')}}
                                    </span>
                                    <span class="YK-timer" v-if="verifyOtp == 1 && resendOtp == 0" >
                                        <span class="txt-default font-weight-bold" id="time"></span>{{ upDatedCounter }} {{$t('LBL_SECONDS')}} </span>
                                </button>
                            </div>

                            <div class="input-group-append" v-if="type=='phone' && smsPackageActive >= 1">
                                <button class="btn btn-fly YK-resendCode" v-bind:class="[ !isPhoneComplete == true || counterRunning == 1 ? 'disabledbutton' : '']" type="button"  @click="changePhoneNumber()">
                                    <span class="YK-sendBtn" v-if="verifyOtp == 0" >
                                        <svg class="svg">
                                            <use
                                                :xlink:href="
                                                baseUrl +
                                                    '/dashboard/media/retina/sprite.svg#submitfly'
                                                "
                                            />
                                        </svg>
                                    </span>
                                    <span class="YK-resendBtn" v-if="resendOtp == 1">
                                        {{$t('BTN_RESEND')}}
                                    </span>
                                    <span class="YK-timer" v-if="verifyOtp == 1 && resendOtp == 0" v-bind:class="[ upDatedCounter > 0 ? 'disabledbutton' : '']" >
                                        <span class="txt-default font-weight-bold" id="time"></span>{{ upDatedCounter }} {{$t('LBL_SECONDS')}} </span>
                                </button>
                            </div>
                        </div>
                        <div class="otp-block YK-Verify-Otp mt-5" v-bind:class="[ verifyOtp == 0 ? 'disabledbutton' : '']" v-if="type=='email' || (type=='phone' && smsPackageActive >= 1)">
                            <div class="form-group mt-5 mb-2">
                                <small>{{ type=='email' ? $t('LBL_CODE_SENT_EMAIL') : $t('LBL_CODE_SENT_PHONE') }}</small>
                            </div>
                            <div class="otp-block__body">
                                <div class="otp-enter">
                                    <div class="otp-inputs">
                                        <div class="digit-group YK-otpDigits" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                                            <input class="field-otp" type="text" maxlength="1" placeholder="*"
                                                id="digit-1" name="digit-1" data-next="digit-2">
                                            <input class="field-otp" type="text" maxlength="1" placeholder="*"
                                                id="digit-2" name="digit-2" data-next="digit-3"
                                                data-previous="digit-1">
                                            <input class="field-otp" type="text" maxlength="1" placeholder="*"
                                                id="digit-3" name="digit-3" data-next="digit-4"
                                                data-previous="digit-2">
                                            <input class="field-otp" type="text" maxlength="1" placeholder="*"
                                                id="digit-4" name="digit-4" data-next="digit-5"
                                                data-previous="digit-3">
                                        </div>
                                        <span  v-b-tooltip.hover :title="otpMessage" v-if="showOtpMessage">
                                            <i class="fas fa-info-circle" ></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="text" class="d-none" v-if="type=='email' || (type=='phone' && smsPackageActive >= 1)">
                        <input type="password" class="d-none" v-if="type=='email' || (type=='phone' && smsPackageActive >= 1)">
                            <div class="YK-confirmPasswordInput" v-bind:class="[ verifyOtp == 0 ? 'disabledbutton' : '']"  v-if="type=='email' || (type=='phone' && smsPackageActive >= 1)">
                                <div class="form-group mt-5 mb-2">
                                    <small>{{$t('LBL_PASSWORD_TO_CONTINUE')}}</small>
                                </div>
                                <div class="form-group form-floating__group mt-2">
                                    <input class="form-control form-floating__field YK-password-confirm" name="password_confirmation" type="password" autocomplete="new-password" v-model="userData.password" v-bind:class="[ userData.password != '' ? 'filled' : '']">
                                        <label class="form-floating__label">{{ $t('LBL_CONFIRM_PASSWORD') }}</label>
                                </div>
                            </div>
                        </div>
                </div>
            </form>
        <template v-slot:modal-footer>
            <div class="d-flex w-100 justify-content-center">
                <button class="btn btn-brand btn-wide gb-btn gb-btn-primary" type="button" @click="verifyUpdatedEmail" :disabled="!updateBtn || !isEmailComplete || clicked==1 || verifyOtp == 0" v-bind:class="clicked==1 && 'gb-is-loading'" v-if="type=='email'">{{ $t('BTN_CHANGE_EMAIL_SUBMIT') }}</button>

                <button class="btn btn-brand btn-wide gb-btn gb-btn-primary" type="button" @click="updatePhoneDirectly" :disabled="!isPhoneComplete || clicked==1" v-bind:class="clicked==1 && 'gb-is-loading'" v-if="type=='phone' && smsPackageActive == 0">{{ $t('BTN_CHANGE_PHONE_SUBMIT') }}</button>
                <button class="btn btn-brand btn-wide gb-btn gb-btn-primary" type="button" @click="verifyUpdatedPhone" :disabled="!isPhoneComplete || !updateBtn || clicked==1 || verifyOtp == 0" v-bind:class="clicked==1 && 'gb-is-loading'" v-if="type=='phone' && smsPackageActive >= 1">{{ $t('BTN_CHANGE_PHONE_SUBMIT') }}</button>
            </div>
        </template>
    </b-modal>
</template>
<script>
const tableFields = {
    new_email: '',
    password: '',
    user_phone: ''
};
export default {
    props: {
        type: String,
        smsPackageActive: Number
    },
    data: function() {
        return {
            baseUrl: baseUrl,
            userData: tableFields,
            defaultCountryCode: "",
            clicked: 0,
            verifyOtp: 0,
            resendOtp: 0,
            upDatedCounter: 180,
            resetUpdatedCounter:180,
            counterRunning:0,
            dialCode: '',
            showOtpMessage:false,
            otpMessage: ''
        };
    },
    methods: {
        changeEmailAddress: function() {
            let formData = this.$serializeData({new_email: this.userData.new_email});
            this.$axios.post(baseUrl + "/change-email/send-code", formData)
                .then((response) => {
                if (response.data.status === false) {
                    toastr.error(response.data.message, "", toastr.options);
                    return;
                }
                if (response.status == 200) {
                    toastr.success(response.data.message, "", toastr.options);
                    this.verifyOtp = 1;
                    if (this.resendOtp == 1) {
                        this.resendOtp = 0;
                        this.upDatedCounter = this.resetUpdatedCounter;
                    }
                    this.timeCounter(this.resetUpdatedCounter);
                }
            }).catch(err => {
                Object.keys(err.response.data.errors).forEach(function (key) {
                    toastr.error(err.response.data.errors[key][0], "", toastr.options);
                });
           });
        },
        verifyUpdatedEmail: function() {
            var code = $('#YK-editEmailForm .otp-inputs input').map(function() {
                return $(this).val();
            }).get().join('');
            let formData = this.$serializeData({email: this.userData.new_email, code:code, password: this.userData.password});
            this.$axios.post(baseUrl + "/change-email/verify-code", formData)
                .then((response) => {
                this.clicked = 0;
                if (response.data.status == false) {
                    $('#YK-editEmailForm .otp-inputs').find('input').val('');
                    $('#YK-editEmailForm .otp-inputs').find('input').removeClass(
                        'filled');
                    $('#YK-editEmailForm .otp-inputs').find('input').addClass(
                        'is-invalid');
                    $('#YK-editEmailForm .otp-inputs').find('input:first').focus();
                    if (response.data.data.type="otp_unverified") {
                        this.showOtpMessage = true;
                        this.otpMessage = response.data.message;
                        return
                    }
                    toastr.error(response.data.message, "", toastr.options);                
                    return 
                }
                toastr.success(response.data.message, "", toastr.options);
                this.$root.$emit("updateUserEmail", this.userData.new_email);
                this.emptyField();
                this.$bvModal.hide("changeEmailPhoneModel");
            }).catch(err => {
                this.clicked = 0;
                this.showOtpMessage = false;
                Object.keys(err.response.data.errors).forEach(function (key) {
                    toastr.error(err.response.data.errors[key][0], "", toastr.options);
                });
           });
        },
        changePhoneNumber: function() {
            let formData = this.$serializeData({phone: this.userData.user_phone, user_phone_country_id: this.defaultCountryCode});
            this.$axios.post(baseUrl + "/change-phone/send-code", formData)
                .then((response) => {
                this.clicked = 0;
                if (response.data.status === false) {
                    toastr.error(response.data.message, "", toastr.options);
                    return;
                }
                if (response.status == 200) {
                    toastr.success(response.data.message, "", toastr.options);
                    this.verifyOtp = 1;
                    if (this.resendOtp == 1) {
                        this.resendOtp = 0;
                        this.upDatedCounter = this.resetUpdatedCounter;
                    }
                    this.timeCounter(this.resetUpdatedCounter);
                }
            }).catch(err => {
                this.clicked = 0;
                Object.keys(err.response.data.errors).forEach(function (key) {
                    toastr.error(err.response.data.errors[key][0], "", toastr.options);
                });
           });
        },
        updatePhoneDirectly: function() {
            let formData = this.$serializeData({user_phone: this.userData.user_phone, user_phone_country_code:this.defaultCountryCode});
            this.$axios.post(baseUrl + "/change-phone/verify-code-directly", formData)
                .then((response) => {
                this.clicked = 0;
                toastr.success(response.data.message, "", toastr.options);                
                this.$root.$emit("updateUserPhone", this.dialCode, this.userData.user_phone);
                this.emptyField();
                this.$bvModal.hide("changeEmailPhoneModel");
            }).catch(err => {
                this.clicked = 0;
                Object.keys(err.response.data.errors).forEach(function (key) {
                    toastr.error(err.response.data.errors[key][0], "", toastr.options);
                });
           });
        },
        verifyUpdatedPhone: function() {
            var code = $('#YK-editEmailForm .otp-inputs input').map(function() {
                return $(this).val();
            }).get().join('');
            let formData = this.$serializeData({user_phone: this.userData.user_phone, code:code, password: this.userData.password,user_phone_country_code:this.defaultCountryCode});
            this.$axios.post(baseUrl + "/change-phone/verify-code", formData)
                .then((response) => {
                this.clicked = 0;
                if (response.data.status == false) {
                    if (response.data.data.type =="otp_unverified") {
                        $('#YK-editEmailForm .otp-inputs').find('input').val('');
                        $('#YK-editEmailForm .otp-inputs').find('input').removeClass('filled');
                        $('#YK-editEmailForm .otp-inputs').find('input').addClass('is-invalid');
                        $('#YK-editEmailForm .otp-inputs').find('input:first').focus();
                        this.showOtpMessage = true;
                        this.otpMessage = response.data.message;
                        return
                    } else if(response.data.data.type == "password") {

                        toastr.error(response.data.message, "", toastr.options);
                        return
                    }
                    toastr.error(response.data.message, "", toastr.options);
                    return
                }
                toastr.success(response.data.message, "", toastr.options);                
                this.$root.$emit("updateUserPhone", this.dialCode, this.userData.user_phone);
                this.emptyField();
                this.$bvModal.hide("changeEmailPhoneModel");
            }).catch(err => {
                this.clicked = 0;
                this.showOtpMessage = false;
                Object.keys(err.response.data.errors).forEach(function (key) {
                    toastr.error(err.response.data.errors[key][0], "", toastr.options);
                });
           });
        },
        onPhoneNumberChange: function (data, obj) {
            this.defaultCountryCode = obj.country.iso2;
            this.userData.user_phone = obj.number.significant;
        },
        changeCountry: function (data) {
            this.defaultCountryCode = data.iso2;
            this.dialCode = data.dialCode;
        },
        validEmail: function () {
            let emailFormat = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
            return emailFormat.test(this.userData.new_email);
        },
        timeCounter: function(counter) {
            let thisVal = this
            this.counterRunning = 1;
            let interval = setInterval(function() {
                counter--;
                if (counter == 0) {
                    clearInterval(interval);
                    thisVal.resendOtp = 1;
                    thisVal.upDatedCounter = 0;
                    thisVal.counterRunning = 0;
                    return;
                } else {
                    thisVal.upDatedCounter = counter;
                }
            }, 1000);
        },
        emptyField: function() {
            this.clicked = 0;
            this.verifyOtp = 0;
            this.resendOtp = 0;
            this.counterRunning = 0;
            this.upDatedCounter = this.resetUpdatedCounter;
            this.userData.new_email = '';
            this.userData.user_phone = '';
            this.userData.password = '';
            this.otpMessage = '';
            this.showOtpMessage = false;
        }
    },
    computed: {
        isEmailComplete() {
            return (this.userData.new_email && this.validEmail());
        },
        isPhoneComplete() {
            return (this.userData.user_phone);
        },
        updateBtn() {
            let code = $('#YK-editEmailForm .otp-inputs input').map(function() {
                return $(this).val();
            }).get().join('');
            return (this.userData.password && code.length == 4);
        }
    },
    mounted: function() {
        $(document).on("keyup", '#YK-editEmailForm .digit-group input', function(e) {
            if($(this).val() != ''){
                $(this).removeClass('is-invalid');
            }
            var parent = $($(this).parent());
            if (e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));
                if (prev.length) {
                    $(prev).select();
                }
            } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <=
                    90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));
                if (next.length) {
                    if (!((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e
                            .keyCode <= 105))) {
                        $(this).val('');
                        return;
                    }
                    $(next).select();
                } else {
                    if (parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
        
    }
}
</script>
<style scoped>
.vue-tel-input {
    flex:1;
}
</style>