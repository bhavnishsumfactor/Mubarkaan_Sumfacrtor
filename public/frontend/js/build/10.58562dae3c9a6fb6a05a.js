(window.webpackJsonp=window.webpackJsonp||[]).push([[10,50],{276:function(e,t,a){"use strict";a.r(t);var o=a(398).a,r=(a(427),a(387)),s=Object(r.a)(o,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"login-page"},[a("div",{staticClass:"login-head"},[a("div",{staticClass:"login-logo-wrap"},[a("inertia-link",{staticClass:"login-logo",attrs:{href:e.baseUrl}},[a("img",{attrs:{"data-gjs-type":"default","data-yk":"",id:"yk-header-logo","data-lite":e.logo,"data-dark":e.darkLogo,src:e.logo,alt:"","data-ratio":e.ratio}})])],1),e._v(" "),a("h1",[e._v(e._s(e.$t("LBL_COMPLETE_YOUR_ACCOUNT")))])]),e._v(" "),a("div",{staticClass:"login-body"},[a("form",{staticClass:"form form-login form-floating",attrs:{method:"POST",id:"social-login-form"}},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.serviceType,expression:"serviceType"}],attrs:{type:"hidden",name:"serviceType"},domProps:{value:e.serviceType},on:{input:function(t){t.target.composing||(e.serviceType=t.target.value)}}}),e._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:e.socialId,expression:"socialId"}],attrs:{type:"hidden",name:"socialId"},domProps:{value:e.socialId},on:{input:function(t){t.target.composing||(e.socialId=t.target.value)}}}),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group form-floating__group floated-txt_wrap"},[a("div",{staticClass:"floated-txt"},[a("a",{attrs:{href:"javascript:;"},on:{click:e.switchMode}},["phone"==e.mode&&e.smsActivePackage>=1?a("span",[e._v(e._s(e.$t("LNK_USE_EMAIL_INSTEAD")))]):a("span",[e._v(e._s(e.$t("LNK_USE_PHONE_INSTEAD")))])])]),e._v(" "),"phone"==e.mode&&e.smsActivePackage>=1?a("span",[a("vue-tel-input",{attrs:{defaultCountry:e.defaultCountry,enabledCountryCode:!0,inputClasses:"form-control form-floating__field",placeholder:e.$t("PLH_ENTER_PHONE_NUMBER"),maxLen:15,autoFormat:"",validCharactersOnly:!0},on:{"country-changed":e.changeCountry,input:e.onPhoneNumberChange},model:{value:e.formData.user_phone,callback:function(t){e.$set(e.formData,"user_phone",t)},expression:"formData.user_phone"}})],1):a("span",[a("input",{directives:[{name:"model",rawName:"v-model",value:e.formData.user_email,expression:"formData.user_email"}],staticClass:"form-control form-floating__field",attrs:{type:"text",name:"user_email",autofocus:"",tabindex:"3"},domProps:{value:e.formData.user_email},on:{input:function(t){t.target.composing||e.$set(e.formData,"user_email",t.target.value)}}}),e._v(" "),a("label",{staticClass:"form-floating__label"},[e._v(e._s(e.$t("LBL_EMAIL")))])])])])]),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group mb-2"},[a("label",{staticClass:"checkbox"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.agreedToTerms,expression:"agreedToTerms"}],attrs:{id:"termsConditions",type:"checkbox",name:"agree",value:"1",tabindex:"7"},domProps:{checked:Array.isArray(e.agreedToTerms)?e._i(e.agreedToTerms,"1")>-1:e.agreedToTerms},on:{change:function(t){var a=e.agreedToTerms,o=t.target,r=!!o.checked;if(Array.isArray(a)){var s=e._i(a,"1");o.checked?s<0&&(e.agreedToTerms=a.concat(["1"])):s>-1&&(e.agreedToTerms=a.slice(0,s).concat(a.slice(s+1)))}else e.agreedToTerms=r}}}),e._v(" "),a("i",{staticClass:"input-helper"}),e._v("\n                            "+e._s(e.$t("LBL_I_AGREE_TO_THE"))+" "),a("a",{attrs:{target:"_blank",href:e.termsUrl}},[e._v(e._s(e.$t("LNK_TERMS_CONDITIONS")))])])])])]),e._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group"},[a("button",{staticClass:"btn btn-brand btn-block gb-btn gb-btn-primary btn-submit",class:1==e.clicked&&"gb-is-loading",attrs:{type:"button",name:"submit-button",disabled:!e.isComplete||1==e.clicked},on:{click:function(t){return t.stopPropagation(),e.saveAdditionalDetails()}}},[e._v(e._s(e.$t("BTN_ACCOUNT_COMPLETION_SUBMIT"))+" "),a("i",{staticClass:"arrow la la-long-arrow-right"})])])])])])])])}),[],!1,null,"591c0db1",null);t.default=s.exports},387:function(e,t,a){"use strict";function o(e,t,a,o,r,s,i,n){var c,l="function"==typeof e?e.options:e;if(t&&(l.render=t,l.staticRenderFns=a,l._compiled=!0),o&&(l.functional=!0),s&&(l._scopeId="data-v-"+s),i?(c=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),r&&r.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(i)},l._ssrRegister=c):r&&(c=n?function(){r.call(this,(l.functional?this.parent:this).$root.$options.shadowRoot)}:r),c)if(l.functional){l._injectStyles=c;var d=l.render;l.render=function(e,t){return c.call(t),d(e,t)}}else{var u=l.beforeCreate;l.beforeCreate=u?[].concat(u,c):[c]}return{exports:e,options:l}}a.d(t,"a",(function(){return o}))},398:function(e,t,a){"use strict";(function(e){function a(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,o)}return a}function o(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}var r=/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;t.a={props:["ratio","darkLogo","logo","smsActivePackage","serviceType","socialId","defaultCountry","termsUrl"],data:function(){return{baseUrl:baseUrl,currentTheme:currentTheme,errors:[],clicked:0,agreedToTerms:"",unmaskedPhoneNumber:"",mode:"email",formData:{user_email:"",user_phone:"",user_phone_country_code:""}}},computed:{isComplete:function(){return this.agreedToTerms&&(this.formData.user_phone&&!0===this.checkIfComplete($("#user_phone_mask"))||this.formData.user_email&&!0===r.test(this.formData.user_email.trim()))}},methods:{saveAdditionalDetails:function(){var t=this;this.clicked=1;var r=function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?a(Object(r),!0).forEach((function(t){o(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):a(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},this.formData),s="";""!=this.unmaskedPhoneNumber&&(r.user_phone=this.unmaskedPhoneNumber),"email"===this.mode?(delete r.user_phone,delete r.user_phone_country_code,s=r.user_email):(delete r.user_email,s=r.user_phone);var i=this.$serializeData(r);i.append("socialId",this.socialId),i.append("serviceType",this.serviceType),this.$axios.post(baseUrl+"/additional-details",i).then((function(a){t.clicked=0,!0===a.data.status?void 0!==a.data.data.url&&""!=a.data.data.url?t.$inertia.visit(baseUrl+a.data.data.url,{},{onStart:function(e){events.registration({content_name:s})}}):e.error(a.data.message,"",e.options):void 0!==a.data.data.errors?t.errors=a.data.data.errors:e.error(a.data.message,"",e.options)}))},changeCountry:function(e){this.formData.user_phone_country_code=e.iso2},onPhoneNumberChange:function(e,t){this.formData.user_phone_country_code=t.country.iso2,this.unmaskedPhoneNumber=t.number.significant},switchMode:function(){"email"==this.mode?this.mode="phone":this.mode="email",setTimeout((function(){floatingFormFix()}),200)},checkIfComplete:function(e){return!!$(e).inputmask("isComplete")}},mounted:function(){setTimeout((function(){floatingFormFix()}),200)}}}).call(this,a(230))},415:function(e,t,a){var o=a(428);"string"==typeof o&&(o=[[e.i,o,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};a(232)(o,r);o.locals&&(e.exports=o.locals)},427:function(e,t,a){"use strict";a(415)},428:function(e,t,a){(e.exports=a(231)(!1)).push([e.i,"\n.vue-tel-input[data-v-591c0db1] {\n    flex: 1;\n}\n",""])}}]);