(window.webpackJsonp=window.webpackJsonp||[]).push([[8,50],{273:function(t,a,s){"use strict";s.r(a);var e=s(395).a,o=(s(423),s(387)),i=Object(o.a)(e,(function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",{staticClass:"login-page"},[s("div",{staticClass:"login-head"},[s("div",{staticClass:"login-logo-wrap"},[s("inertia-link",{staticClass:"login-logo",attrs:{href:t.baseUrl}},[s("img",{attrs:{"data-gjs-type":"default","data-yk":"",id:"yk-header-logo","data-lite":t.logo,"data-dark":t.darkLogo,src:t.logo,alt:"","data-ratio":t.ratio}})])],1),t._v(" "),s("h1",[t._v(t._s(t.$t("LBL_WELCOME_TO"))+" "+t._s(t.$configVal("BUSINESS_NAME")))])]),t._v(" "),s("div",{staticClass:"login-body"},[1==t.$configVal("FACEBOOK_CLIENT_STATUS")||1==t.$configVal("GOOGLE_CLIENT_STATUS")||1==t.$configVal("INSTAGRAM_CLIENT_STATUS")?s("div",{staticClass:"button-wrap"},[1==t.$configVal("FACEBOOK_CLIENT_STATUS")?s("button",{staticClass:"btn btn-social btn-facebook",attrs:{type:"button",onclick:t.socialLink("facebook")}},[s("i",{staticClass:"icn"},[s("svg",{staticClass:"svg"},[s("use",{attrs:{"xlink:href":t.baseUrl+"/yokart/media/retina/sprite.svg#facebook",href:t.baseUrl+"/yokart/media/retina/sprite.svg#facebook"}})])])]):t._e(),t._v(" "),1==t.$configVal("GOOGLE_CLIENT_STATUS")?s("button",{staticClass:"btn btn-social btn-google",attrs:{type:"button",onclick:t.socialLink("google")}},[s("i",{staticClass:"icn"},[s("svg",{staticClass:"svg"},[s("use",{attrs:{"xlink:href":t.baseUrl+"/yokart/media/retina/sprite.svg#google",href:t.baseUrl+"/yokart/media/retina/sprite.svg#google"}})])])]):t._e(),t._v(" "),1==t.$configVal("INSTAGRAM_CLIENT_STATUS")?s("button",{staticClass:"btn btn-social btn-instagram",attrs:{type:"button",onclick:t.socialLink("instagram")}},[s("i",{staticClass:"icn"},[s("svg",{staticClass:"svg"},[s("use",{attrs:{"xlink:href":t.baseUrl+"/yokart/media/retina/sprite.svg#instagram",href:t.baseUrl+"/yokart/media/retina/sprite.svg#instagram"}})])])]):t._e()]):t._e(),t._v(" "),s("div",{staticClass:"divider"},[s("span",[t._v(t._s(t.$t("LBL_OR_CONTINUE_WITH")))])]),t._v(" "),s("form",{staticClass:"form form-login form-floating",attrs:{id:"login-form",method:"POST"}},[s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-12"},["phone"==t.mode&&t.smsActivePackage>=1?s("div",{staticClass:"form-group form-floating__group"},[s("vue-tel-input",{attrs:{defaultCountry:t.defaultCountry,enabledCountryCode:!0,inputClasses:"form-control form-floating__field",placeholder:t.$t("PLH_ENTER_PHONE_NUMBER"),maxLen:15,autoFormat:"",validCharactersOnly:!0},on:{"country-changed":t.changeCountry,input:t.onPhoneNumberChange},model:{value:t.formData.user_phone,callback:function(a){t.$set(t.formData,"user_phone",a)},expression:"formData.user_phone"}})],1):s("div",{staticClass:"form-group form-floating__group"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.formData.username,expression:"formData.username"}],staticClass:"form-control form-floating__field",attrs:{type:"text",name:"username",autofocus:""},domProps:{value:t.formData.username},on:{input:function(a){a.target.composing||t.$set(t.formData,"username",a.target.value)}}}),t._v(" "),s("label",{staticClass:"form-floating__label"},[t._v(t._s(t.$t("LBL_EMAIL")))])])])]),t._v(" "),s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-12"},[s("div",{staticClass:"form-group form-floating__group password-field"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.formData.password,expression:"formData.password"}],staticClass:"form-control form-floating__field",attrs:{type:"password",id:"password",name:"password",autocomplete:"new-password"},domProps:{value:t.formData.password},on:{input:function(a){a.target.composing||t.$set(t.formData,"password",a.target.value)}}}),t._v(" "),s("label",{staticClass:"form-floating__label"},[t._v(t._s(t.$t("LBL_PASSWORD")))]),t._v(" "),s("i",{staticClass:"far fa-eye password-toggle",attrs:{id:"togglePassword"}})])])]),t._v(" "),s("div",{staticClass:"row"},[s("div",{staticClass:"col col-12 col-lg-6"},[s("div",{staticClass:"form-group"},[s("label",{staticClass:"checkbox"},[s("input",{attrs:{type:"checkbox",name:"remember",id:"remember"},on:{click:function(a){return t.rememberMe(a)}}}),t._v(t._s(t.$t("LBL_REMEMBER_ME"))+"\n                        "),s("i",{staticClass:"input-helper"})])])]),t._v(" "),s("div",{staticClass:"col col-12 col-lg-6 text-lg-right"},[s("div",{staticClass:"form-group"},[t.smsActivePackage>=1?s("div",{staticClass:"floated-txt"},[s("a",{staticClass:"loginVia",attrs:{href:"javascript:;"},on:{click:function(a){return a.preventDefault(),t.switchMode.apply(null,arguments)}}},[s("i",{staticClass:"icn"},[s("svg",{staticClass:"svg"},[s("use",{attrs:{"xlink:href":t.baseUrl+"/yokart/media/retina/sprite.svg#"+t.mode,href:t.baseUrl+"/yokart/media/retina/sprite.svg#"+t.mode}})])]),t._v(" "),"phone"==t.mode?s("span",[t._v(t._s(t.$t("LNK_USE_EMAIL_INSTEAD")))]):t._e(),t._v(" "),"email"==t.mode?s("span",[t._v(t._s(t.$t("LNK_USE_PHONE_INSTEAD")))]):t._e()])]):t._e()])])]),t._v(" "),s("div",{staticClass:"row"},[s("div",{staticClass:"col-md-12"},[s("div",{staticClass:"form-group"},[s("button",{staticClass:"btn btn-brand btn-block gb-btn gb-btn-primary btn-submit ",class:1==t.clicked&&"gb-is-loading",attrs:{type:"submit",id:"YK-loginBtn",disabled:!t.isComplete||1==t.clicked,name:"loginBtn"},on:{click:function(a){return a.stopPropagation(),t.login()}}},[t._v(t._s(t.$t("BTN_LOGIN")))])])])])])]),t._v(" "),s("div",{staticClass:"login-foot"},[s("p",{staticClass:"no-account"},[s("inertia-link",{staticClass:"link forgot-txt",attrs:{href:t.baseUrl+"/forgot-password"}},[t._v(t._s(t.$t("LNK_FORGOT_PASSWORD"))+"?")]),t._v(" "),s("span",{staticClass:"pipe"},[t._v("|")]),t._v(" "),s("inertia-link",{staticClass:"link",attrs:{href:t.baseUrl+"/register"}},[t._v(t._s(t.$t("LNK_REGISTER"))+"?")])],1)])])}),[],!1,null,"d8bde35a",null);a.default=i.exports},387:function(t,a,s){"use strict";function e(t,a,s,e,o,i,r,n){var l,c="function"==typeof t?t.options:t;if(a&&(c.render=a,c.staticRenderFns=s,c._compiled=!0),e&&(c.functional=!0),i&&(c._scopeId="data-v-"+i),r?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(r)},c._ssrRegister=l):o&&(l=n?function(){o.call(this,(c.functional?this.parent:this).$root.$options.shadowRoot)}:o),l)if(c.functional){c._injectStyles=l;var d=c.render;c.render=function(t,a){return l.call(a),d(t,a)}}else{var u=c.beforeCreate;c.beforeCreate=u?[].concat(u,l):[l]}return{exports:t,options:c}}s.d(a,"a",(function(){return e}))},395:function(t,a,s){"use strict";(function(t){a.a={props:["smsActivePackage","defaultCountry","ratio","darkLogo","logo","queryParams"],data:function(){return{baseUrl:baseUrl,currentTheme:currentTheme,mode:"email",clicked:0,unmaskedPhoneNumber:"",formData:{username:"tribe@dummyid.com",password:"Tribe@123",user_phone:"",user_phone_country_code:"",remember:""}}},methods:{login:function(){var a=this;this.clicked=1,""!=this.unmaskedPhoneNumber&&(this.formData.user_phone=this.unmaskedPhoneNumber);var s=this.$serializeData(this.formData);s.append("via",this.mode),this.$axios.post(baseUrl+"/login",s).then((function(s){a.clicked=0,!0===s.data.status?window.location.href=baseUrl:void 0!==s.data.data.url&&""!=s.data.data.url?a.$inertia.visit(baseUrl+s.data.data.url):t.error(s.data.message,"",t.options)}),(function(t){a.clicked=0}))},rememberMe:function(t){this.formData.remember=t.target.value},changeCountry:function(t){this.formData.user_phone_country_code=t.iso2},onPhoneNumberChange:function(t,a){this.formData.user_phone_country_code=a.country.iso2,this.unmaskedPhoneNumber=a.number.significant},validateErrors:function(t){var a=this,s=t.data;Object.keys(s.errors).forEach((function(t){a.errors.add({field:t,msg:s.errors[t][0]})}))},switchMode:function(){"email"==this.mode?(this.mode="phone",this.formData.username=""):(this.formData.username="tribe@dummyid.com",this.mode="email"),setTimeout((function(){floatingFormFix()}),200)},socialLink:function(t){return"window.location.href = '"+baseUrl+"/redirect/"+t+"/"+this.queryParams+"'"}},computed:{isComplete:function(){return this.formData.username&&this.formData.password||this.formData.user_phone&&this.formData.password}},mounted:function(){setTimeout((function(){floatingFormFix()}),200);var t=document.querySelector("#togglePassword"),a=document.querySelector("#password");t.addEventListener("click",(function(t){var s="password"===a.getAttribute("type")?"text":"password";a.setAttribute("type",s),this.classList.toggle("fa-eye-slash")}))}}}).call(this,s(230))},413:function(t,a,s){var e=s(424);"string"==typeof e&&(e=[[t.i,e,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};s(232)(e,o);e.locals&&(t.exports=e.locals)},423:function(t,a,s){"use strict";s(413)},424:function(t,a,s){(t.exports=s(231)(!1)).push([t.i,"\n.vue-tel-input[data-v-d8bde35a] {\n    flex: 1;\n}\n",""])}}]);