(window.webpackJsonp=window.webpackJsonp||[]).push([[7],{279:function(t,e,s){"use strict";s.r(e);var a=s(421).a,i=(s(433),s(63)),n=Object(i.a)(a,(function(){var t=this,e=t._self._c;return e("b-modal",{attrs:{id:"changeEmailPhoneModel",centered:"",title:"BootstrapVue"},scopedSlots:t._u([{key:"modal-header",fn:function(){return[e("h5",{staticClass:"modal-title"},[t._v(t._s("email"==t.type?t.$t("LBL_UPDATE_EMAIL"):t.$t("LBL_UPDATE_PHONE")))]),t._v(" "),e("button",{staticClass:"close",attrs:{type:"button"},on:{click:function(e){return t.$bvModal.hide("changeEmailPhoneModel")}}},[e("span",{attrs:{"aria-hidden":"true"}},[t._v("×")])])]},proxy:!0},{key:"modal-footer",fn:function(){return[e("div",{staticClass:"d-flex w-100 justify-content-center"},["email"==t.type?e("button",{staticClass:"btn btn-brand btn-wide gb-btn gb-btn-primary",class:1==t.clicked&&"gb-is-loading",attrs:{type:"button",disabled:!t.updateBtn||!t.isEmailComplete||1==t.clicked||0==t.verifyOtp},on:{click:t.verifyUpdatedEmail}},[t._v(t._s(t.$t("BTN_CHANGE_EMAIL_SUBMIT")))]):t._e(),t._v(" "),"phone"==t.type&&0==t.smsPackageActive?e("button",{staticClass:"btn btn-brand btn-wide gb-btn gb-btn-primary",class:1==t.clicked&&"gb-is-loading",attrs:{type:"button",disabled:!t.isPhoneComplete||1==t.clicked},on:{click:t.updatePhoneDirectly}},[t._v(t._s(t.$t("BTN_CHANGE_PHONE_SUBMIT")))]):t._e(),t._v(" "),"phone"==t.type&&t.smsPackageActive>=1?e("button",{staticClass:"btn btn-brand btn-wide gb-btn gb-btn-primary",class:1==t.clicked&&"gb-is-loading",attrs:{type:"button",disabled:!t.isPhoneComplete||!t.updateBtn||1==t.clicked||0==t.verifyOtp},on:{click:t.verifyUpdatedPhone}},[t._v(t._s(t.$t("BTN_CHANGE_PHONE_SUBMIT")))]):t._e()])]},proxy:!0}])},[t._v(" "),e("form",{staticClass:"form form-login form-floating",attrs:{id:"YK-editEmailForm",method:"POST",action:"javascript:;"}},[e("div",{staticClass:"row justify-content-center mt-4 text-center"},[e("div",{staticClass:"col-md-8"},[e("div",{staticClass:"form-group mb-2"},[e("small",[t._v(t._s("email"==t.type?t.$t("LBL_ENTER_EMAIL_INSTRUCTIONS"):t.$t("LBL_ENTER_PHONE_INSTRUCTIONS")))])]),t._v(" "),e("div",{staticClass:"input-group form--fly"},["email"==t.type?e("input",{directives:[{name:"model",rawName:"v-model",value:t.userData.new_email,expression:"userData.new_email"}],staticClass:"form-control",attrs:{id:"new_email",type:"text",placeholder:t.$t("LBL_EMAIL"),name:"new_email"},domProps:{value:t.userData.new_email},on:{input:function(e){e.target.composing||t.$set(t.userData,"new_email",e.target.value)}}}):t._e(),t._v(" "),"phone"==t.type?e("vue-tel-input",{attrs:{defaultCountry:t.defaultCountryCode,validCharactersOnly:!0,enabledCountryCode:!0,inputClasses:"form-control",placeholder:t.$t("PLH_ENTER_PHONE_NUMBER"),maxLen:15},on:{"country-changed":t.changeCountry,input:t.onPhoneNumberChange},model:{value:t.userData.user_phone,callback:function(e){t.$set(t.userData,"user_phone",e)},expression:"userData.user_phone"}}):t._e(),t._v(" "),"email"==t.type?e("div",{staticClass:"input-group-append"},[e("button",{staticClass:"btn YK-resendCode btn-fly",class:[1==!t.isEmailComplete||1==t.counterRunning?"disabledbutton":""],attrs:{type:"button"},on:{click:function(e){return t.changeEmailAddress()}}},[0==t.verifyOtp?e("span",{staticClass:"YK-sendBtn"},[e("svg",{staticClass:"svg"},[e("use",{attrs:{"xlink:href":t.baseUrl+"/dashboard/media/retina/sprite.svg#submitfly"}})])]):t._e(),t._v(" "),1==t.resendOtp?e("span",{staticClass:"YK-resendBtn"},[t._v("\n                                    "+t._s(t.$t("BTN_RESEND"))+"\n                                ")]):t._e(),t._v(" "),1==t.verifyOtp&&0==t.resendOtp?e("span",{staticClass:"YK-timer"},[e("span",{staticClass:"txt-default font-weight-bold",attrs:{id:"time"}}),t._v(t._s(t.upDatedCounter)+" "+t._s(t.$t("LBL_SECONDS"))+" ")]):t._e()])]):t._e(),t._v(" "),"phone"==t.type&&t.smsPackageActive>=1?e("div",{staticClass:"input-group-append"},[e("button",{staticClass:"btn btn-fly YK-resendCode",class:[1==!t.isPhoneComplete||1==t.counterRunning?"disabledbutton":""],attrs:{type:"button"},on:{click:function(e){return t.changePhoneNumber()}}},[0==t.verifyOtp?e("span",{staticClass:"YK-sendBtn"},[e("svg",{staticClass:"svg"},[e("use",{attrs:{"xlink:href":t.baseUrl+"/dashboard/media/retina/sprite.svg#submitfly"}})])]):t._e(),t._v(" "),1==t.resendOtp?e("span",{staticClass:"YK-resendBtn"},[t._v("\n                                    "+t._s(t.$t("BTN_RESEND"))+"\n                                ")]):t._e(),t._v(" "),1==t.verifyOtp&&0==t.resendOtp?e("span",{staticClass:"YK-timer",class:[t.upDatedCounter>0?"disabledbutton":""]},[e("span",{staticClass:"txt-default font-weight-bold",attrs:{id:"time"}}),t._v(t._s(t.upDatedCounter)+" "+t._s(t.$t("LBL_SECONDS"))+" ")]):t._e()])]):t._e()],1),t._v(" "),"email"==t.type||"phone"==t.type&&t.smsPackageActive>=1?e("div",{staticClass:"otp-block YK-Verify-Otp mt-5",class:[0==t.verifyOtp?"disabledbutton":""]},[e("div",{staticClass:"form-group mt-5 mb-2"},[e("small",[t._v(t._s("email"==t.type?t.$t("LBL_CODE_SENT_EMAIL"):t.$t("LBL_CODE_SENT_PHONE")))])]),t._v(" "),e("div",{staticClass:"otp-block__body"},[e("div",{staticClass:"otp-enter"},[e("div",{staticClass:"otp-inputs"},[e("div",{staticClass:"digit-group YK-otpDigits",attrs:{"data-group-name":"digits","data-autosubmit":"false",autocomplete:"off"}},[e("input",{staticClass:"field-otp",attrs:{type:"text",maxlength:"1",placeholder:"*",id:"digit-1",name:"digit-1","data-next":"digit-2"}}),t._v(" "),e("input",{staticClass:"field-otp",attrs:{type:"text",maxlength:"1",placeholder:"*",id:"digit-2",name:"digit-2","data-next":"digit-3","data-previous":"digit-1"}}),t._v(" "),e("input",{staticClass:"field-otp",attrs:{type:"text",maxlength:"1",placeholder:"*",id:"digit-3",name:"digit-3","data-next":"digit-4","data-previous":"digit-2"}}),t._v(" "),e("input",{staticClass:"field-otp",attrs:{type:"text",maxlength:"1",placeholder:"*",id:"digit-4",name:"digit-4","data-next":"digit-5","data-previous":"digit-3"}})]),t._v(" "),t.showOtpMessage?e("span",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],attrs:{title:t.otpMessage}},[e("i",{staticClass:"fas fa-info-circle"})]):t._e()])])])]):t._e(),t._v(" "),"email"==t.type||"phone"==t.type&&t.smsPackageActive>=1?e("input",{staticClass:"d-none",attrs:{type:"text"}}):t._e(),t._v(" "),"email"==t.type||"phone"==t.type&&t.smsPackageActive>=1?e("input",{staticClass:"d-none",attrs:{type:"password"}}):t._e(),t._v(" "),"email"==t.type||"phone"==t.type&&t.smsPackageActive>=1?e("div",{staticClass:"YK-confirmPasswordInput",class:[0==t.verifyOtp?"disabledbutton":""]},[e("div",{staticClass:"form-group mt-5 mb-2"},[e("small",[t._v(t._s(t.$t("LBL_PASSWORD_TO_CONTINUE")))])]),t._v(" "),e("div",{staticClass:"form-group form-floating__group mt-2"},[e("input",{directives:[{name:"model",rawName:"v-model",value:t.userData.password,expression:"userData.password"}],staticClass:"form-control form-floating__field YK-password-confirm",class:[""!=t.userData.password?"filled":""],attrs:{name:"password_confirmation",type:"password",autocomplete:"new-password"},domProps:{value:t.userData.password},on:{input:function(e){e.target.composing||t.$set(t.userData,"password",e.target.value)}}}),t._v(" "),e("label",{staticClass:"form-floating__label"},[t._v(t._s(t.$t("LBL_CONFIRM_PASSWORD")))])])]):t._e()])])])])}),[],!1,null,"62a1d56c",null);e.default=n.exports},421:function(t,e,s){"use strict";(function(t){var s={new_email:"",password:"",user_phone:""};e.a={props:{type:String,smsPackageActive:Number},data:function(){return{baseUrl:baseUrl,userData:s,defaultCountryCode:"",clicked:0,verifyOtp:0,resendOtp:0,upDatedCounter:180,resetUpdatedCounter:180,counterRunning:0,dialCode:"",showOtpMessage:!1,otpMessage:""}},methods:{changeEmailAddress:function(){var e=this,s=this.$serializeData({new_email:this.userData.new_email});this.$axios.post(baseUrl+"/change-email/send-code",s).then((function(s){!1!==s.data.status?200==s.status&&(t.success(s.data.message,"",t.options),e.verifyOtp=1,1==e.resendOtp&&(e.resendOtp=0,e.upDatedCounter=e.resetUpdatedCounter),e.timeCounter(e.resetUpdatedCounter)):t.error(s.data.message,"",t.options)})).catch((function(e){Object.keys(e.response.data.errors).forEach((function(s){t.error(e.response.data.errors[s][0],"",t.options)}))}))},verifyUpdatedEmail:function(){var e=this,s=$("#YK-editEmailForm .otp-inputs input").map((function(){return $(this).val()})).get().join(""),a=this.$serializeData({email:this.userData.new_email,code:s,password:this.userData.password});this.$axios.post(baseUrl+"/change-email/verify-code",a).then((function(s){if(e.clicked=0,0==s.data.status)return $("#YK-editEmailForm .otp-inputs").find("input").val(""),$("#YK-editEmailForm .otp-inputs").find("input").removeClass("filled"),$("#YK-editEmailForm .otp-inputs").find("input").addClass("is-invalid"),$("#YK-editEmailForm .otp-inputs").find("input:first").focus(),(s.data.data.type="otp_unverified")?(e.showOtpMessage=!0,void(e.otpMessage=s.data.message)):void t.error(s.data.message,"",t.options);t.success(s.data.message,"",t.options),e.$root.$emit("updateUserEmail",e.userData.new_email),e.emptyField(),e.$bvModal.hide("changeEmailPhoneModel")})).catch((function(s){e.clicked=0,e.showOtpMessage=!1,Object.keys(s.response.data.errors).forEach((function(e){t.error(s.response.data.errors[e][0],"",t.options)}))}))},changePhoneNumber:function(){var e=this,s=this.$serializeData({phone:this.userData.user_phone,user_phone_country_id:this.defaultCountryCode});this.$axios.post(baseUrl+"/change-phone/send-code",s).then((function(s){e.clicked=0,!1!==s.data.status?200==s.status&&(t.success(s.data.message,"",t.options),e.verifyOtp=1,1==e.resendOtp&&(e.resendOtp=0,e.upDatedCounter=e.resetUpdatedCounter),e.timeCounter(e.resetUpdatedCounter)):t.error(s.data.message,"",t.options)})).catch((function(s){e.clicked=0,Object.keys(s.response.data.errors).forEach((function(e){t.error(s.response.data.errors[e][0],"",t.options)}))}))},updatePhoneDirectly:function(){var e=this,s=this.$serializeData({user_phone:this.userData.user_phone,user_phone_country_code:this.defaultCountryCode});this.$axios.post(baseUrl+"/change-phone/verify-code-directly",s).then((function(s){e.clicked=0,t.success(s.data.message,"",t.options),e.$root.$emit("updateUserPhone",e.dialCode,e.userData.user_phone),e.emptyField(),e.$bvModal.hide("changeEmailPhoneModel")})).catch((function(s){e.clicked=0,Object.keys(s.response.data.errors).forEach((function(e){t.error(s.response.data.errors[e][0],"",t.options)}))}))},verifyUpdatedPhone:function(){var e=this,s=$("#YK-editEmailForm .otp-inputs input").map((function(){return $(this).val()})).get().join(""),a=this.$serializeData({user_phone:this.userData.user_phone,code:s,password:this.userData.password,user_phone_country_code:this.defaultCountryCode});this.$axios.post(baseUrl+"/change-phone/verify-code",a).then((function(s){if(e.clicked=0,0==s.data.status)return"otp_unverified"==s.data.data.type?($("#YK-editEmailForm .otp-inputs").find("input").val(""),$("#YK-editEmailForm .otp-inputs").find("input").removeClass("filled"),$("#YK-editEmailForm .otp-inputs").find("input").addClass("is-invalid"),$("#YK-editEmailForm .otp-inputs").find("input:first").focus(),e.showOtpMessage=!0,void(e.otpMessage=s.data.message)):(s.data.data.type,void t.error(s.data.message,"",t.options));t.success(s.data.message,"",t.options),e.$root.$emit("updateUserPhone",e.dialCode,e.userData.user_phone),e.emptyField(),e.$bvModal.hide("changeEmailPhoneModel")})).catch((function(s){e.clicked=0,e.showOtpMessage=!1,Object.keys(s.response.data.errors).forEach((function(e){t.error(s.response.data.errors[e][0],"",t.options)}))}))},onPhoneNumberChange:function(t,e){this.defaultCountryCode=e.country.iso2,this.userData.user_phone=e.number.significant},changeCountry:function(t){this.defaultCountryCode=t.iso2,this.dialCode=t.dialCode},validEmail:function(){return/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i.test(this.userData.new_email)},timeCounter:function(t){var e=this;this.counterRunning=1;var s=setInterval((function(){if(0==--t)return clearInterval(s),e.resendOtp=1,e.upDatedCounter=0,void(e.counterRunning=0);e.upDatedCounter=t}),1e3)},emptyField:function(){this.clicked=0,this.verifyOtp=0,this.resendOtp=0,this.counterRunning=0,this.upDatedCounter=this.resetUpdatedCounter,this.userData.new_email="",this.userData.user_phone="",this.userData.password="",this.otpMessage="",this.showOtpMessage=!1}},computed:{isEmailComplete:function(){return this.userData.new_email&&this.validEmail()},isPhoneComplete:function(){return this.userData.user_phone},updateBtn:function(){var t=$("#YK-editEmailForm .otp-inputs input").map((function(){return $(this).val()})).get().join("");return this.userData.password&&4==t.length}},mounted:function(){$(document).on("keyup","#YK-editEmailForm .digit-group input",(function(t){""!=$(this).val()&&$(this).removeClass("is-invalid");var e=$($(this).parent());if(8===t.keyCode||37===t.keyCode){var s=e.find("input#"+$(this).data("previous"));s.length&&$(s).select()}else if(t.keyCode>=48&&t.keyCode<=57||t.keyCode>=65&&t.keyCode<=90||t.keyCode>=96&&t.keyCode<=105||39===t.keyCode){var a=e.find("input#"+$(this).data("next"));if(a.length){if(!(t.keyCode>=48&&t.keyCode<=57||t.keyCode>=96&&t.keyCode<=105))return void $(this).val("");$(a).select()}else e.data("autosubmit")&&e.submit()}}))}}}).call(this,s(64))},426:function(t,e,s){var a=s(434);"string"==typeof a&&(a=[[t.i,a,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};s(66)(a,i);a.locals&&(t.exports=a.locals)},433:function(t,e,s){"use strict";s(426)},434:function(t,e,s){(t.exports=s(65)(!1)).push([t.i,"\n.vue-tel-input[data-v-62a1d56c] {\n    flex:1;\n}\n",""])}}]);