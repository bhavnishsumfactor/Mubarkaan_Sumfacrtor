(window.webpackJsonp=window.webpackJsonp||[]).push([[10,30,34],{267:function(s,t,a){"use strict";a.r(t);var e={props:["pageType","messageNav"],data:function(){return{baseUrl:baseUrl,activeCardTab:window.activeCardTab,languages:[],currencies:[],selectedLanguage:{},selectedCurrency:{},defaultFlag:"",auth:window.auth,profileImage:""}},methods:{isUrl:function(s){return s==this.pageType},switchLanguage:function(s){this.$axios.post(baseUrl+"/user/switch-language",{language:s}).then((function(t){1==t.data.status&&(window.location.href=window.location.href+"?language="+s+"#googtrans(auto|"+s+")")}))},getUserUnreadMessage:function(){this.$axios.get(baseUrl+"/user/unread-message-count").then((function(s){1==s.data.status&&(0==s.data.data?document.getElementById("yk-messageCount").innerHTML="":document.getElementById("yk-messageCount").innerHTML=s.data.data)}))}},mounted:function(){var s=this;this.profileImage=this.baseUrl+"/"+this.$getFileUrl("userProfileImage",auth.user_id,0,"50-50"),this.$root.$on("updateProfileImage",(function(t){s.profileImage=t}));var t=this;this.languages=window.languages,this.currencies=window.currencies,this.selectedLanguage=window.selectedLanguage,this.selectedCurrency=window.selectedCurrency,this.defaultFlag=window.defaultFlag,this.getUserUnreadMessage(),setInterval((function(){t.getUserUnreadMessage()}),6e4)}},i=a(63),n=Object(i.a)(e,(function(){var s=this,t=s._self._c;return t("div",{},[t("div",{staticClass:"sidebar account-menu",attrs:{id:"account-menu","data-close-on-click-outside":"account-menu"}},[t("div",{staticClass:"sidebar_body"},["mobile"===s.$mq||"tablet"===s.$mq?t("div",{staticClass:"user-card"},[t("div",{staticClass:"user-card_inner"},[t("span",{staticClass:"user-card_photo"},[t("img",{staticClass:"YK-userAvatar",attrs:{src:s.profileImage,alt:""}})]),s._v(" "),t("div",{attrs:{clss:""}},[t("h4",{staticClass:"user-card_name"},[t("strong",[s._v("Hi")]),s._v(" "),t("br"),s._v("\n              "+s._s(s.auth.user_fname+" "+s.auth.user_lname)+"\n            ")])])])]):s._e(),s._v(" "),t("ul",{staticClass:"menu__nav"},[t("li",{staticClass:"menu__head"},[s._v(s._s(s.$t("LBL_ORDERS")))]),s._v(" "),t("li",{staticClass:"menu__item",class:[s.isUrl("orders")?"active":""]},[t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("orders")?s.baseUrl+"/user/orders":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#order-return"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v(s._s(s.$t("LBL_ORDERS_AND_RETURNS")))])])],1),s._v(" "),3==this.$configVal("PRODUCT_TYPES")||2==this.$configVal("PRODUCT_TYPES")?t("li",{staticClass:"menu__item",class:[s.isUrl("download")?"active":""]},[t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("download")?s.baseUrl+"/user/orders/download":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#downloads"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v("\n              "+s._s(s.$t("LBL_DOWNLOADS"))+"\n            ")])])],1):s._e(),s._v(" "),t("li",{staticClass:"menu__head"},[s._v(s._s(s.$t("LBL_ACTIVITY")))]),s._v(" "),t("li",{staticClass:"menu__item",class:[s.isUrl("favorite")?"active":""]},[t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("favorite")?s.baseUrl+"/user/favorite":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#favorite"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v("\n              "+s._s(s.$t("LBL_FAVORITES"))+"\n            ")])])],1),s._v(" "),1==this.$configVal("REWARD_POINTS_ENABLE")&&1==this.$configVal("REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE")?t("li",{staticClass:"menu__item",class:[s.isUrl("share-earn")?"active":""]},[t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("share-earn")?s.baseUrl+"/user/share-earn":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#share-earn"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v("\n              "+s._s(s.$t("LBL_SHARE_AND_EARN"))+"\n            ")])])],1):s._e(),s._v(" "),t("li",{staticClass:"menu__item",class:[s.isUrl("reviews")?"active":""]},[t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("reviews")?s.baseUrl+"/user/reviews":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#reviews"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v("\n              "+s._s(s.$t("LBL_REVIEWS"))+"\n            ")])])],1),s._v(" "),1==this.$configVal("REWARD_POINTS_ENABLE")?t("li",{staticClass:"menu__item",class:[s.isUrl("reward-points")?"active":""]},[t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("reward-points")?s.baseUrl+"/user/reward-points":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#reward-points"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v("\n              "+s._s(s.$t("LBL_REWARD_POINTS"))+"\n            ")])])],1):s._e(),s._v(" "),t("li",{staticClass:"menu__item",class:[s.isUrl("coupons")?"active":""]},[t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("coupons")?s.baseUrl+"/user/coupons":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#coupons"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v("\n              "+s._s(s.$t("LBL_DISCOUNT_COUPONS"))+"\n            ")])])],1),s._v(" "),t("li",{staticClass:"menu__item",class:[s.isUrl("messages")?"active":""]},[s.messageNav?t("a",{staticClass:"menu__link",attrs:{href:"javascript:;"},on:{click:function(t){return s.$emit("switchLayout",!1)}}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#messages"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v(s._s(s.$t("LBL_MESSAGES")))]),s._v(" "),t("span",{staticClass:"badge badge--brand yk-messageCount",attrs:{id:"yk-messageCount"}})]):t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("messages")?s.baseUrl+"/user/messages":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#messages"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v(s._s(s.$t("LBL_MESSAGES")))]),s._v(" "),t("span",{staticClass:"badge badge--brand yk-messageCount",attrs:{id:"yk-messageCount"}})])],1),s._v(" "),t("li",{staticClass:"menu__head"},[s._v(s._s(s.$t("LBL_ACCOUNT")))]),s._v(" "),t("li",{staticClass:"menu__item",class:[s.isUrl("profile")?"active":""]},[t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("profile")?s.baseUrl+"/user/profile":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#profile"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v("\n              "+s._s(s.$t("LBL_PROFILE"))+"\n            ")])])],1),s._v(" "),t("li",{staticClass:"menu__item",class:[s.isUrl("address")?"active":""]},[t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("address")?s.baseUrl+"/user/address":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#address-book"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v("\n              "+s._s(s.$t("LBL_ADDRESS_BOOK"))+"\n            ")])])],1),s._v(" "),s.activeCardTab?t("li",{staticClass:"menu__item",class:[s.isUrl("cards")?"active":""]},[t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("cards")?s.baseUrl+"/user/cards":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#cards"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v("\n              "+s._s(s.$t("LBL_SAVED_CARDS"))+"\n            ")])])],1):s._e(),s._v(" "),t("li",{staticClass:"menu__item",class:[s.isUrl("localization")?"active":""]},[t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("localization")?s.baseUrl+"/user/localization":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#localization"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v(s._s(s.$t("LBL_LOCALIZATION")))])])],1),s._v(" "),1==this.$configVal("ACCEPT_COOKIE_ENABLE")?t("li",{staticClass:"menu__item",class:[s.isUrl("cookie-preferences")?"active":""]},[t("inertia-link",{staticClass:"menu__link",attrs:{href:0==s.isUrl("cookie-preferences")?s.baseUrl+"/user/cookie-preferences":"javascript:;"}},[t("i",{staticClass:"menu__link-icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#localization"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v(s._s(s.$t("LBL_COOKIE_PREFERENCES")))])])],1):s._e(),s._v(" "),t("li",{staticClass:"menu__item"},[t("a",{staticClass:"menu__link logout",attrs:{href:s.baseUrl+"/logout"}},[t("i",{staticClass:"menu__link-icn icn"},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#logout"}})])]),s._v(" "),t("span",{staticClass:"menu__link-text"},[s._v(s._s(s.$t("LNK_LOGOUT")))])])])])]),s._v(" "),s._m(0)]),s._v(" "),t("div",{staticClass:"back-overlay"})])}),[function(){var s=this._self._c;return s("div",{staticClass:"sidebar_foot"},[s("div",{staticClass:"powered-by text-center"})])}],!1,null,null,null);t.default=n.exports},268:function(s,t,a){"use strict";a.r(t);function e(s){return(e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(s){return typeof s}:function(s){return s&&"function"==typeof Symbol&&s.constructor===Symbol&&s!==Symbol.prototype?"symbol":typeof s})(s)}var i={data:function(){return{baseUrl:baseUrl,auth:window.auth,profileImage:""}},methods:{switchLanguage:function(s){this.$axios.post(baseUrl+"/user/switch-language",{language:s}).then((function(t){1==t.data.status&&(window.location.href=window.location.href+"?language="+s+"#googtrans(auto|"+s+")")}))}},mounted:function(){var s=this;this.profileImage=this.baseUrl+"/"+this.$getFileUrl("userProfileImage",auth.user_id,0,"50-50",Date.now()),this.$root.$on("updateProfileImage",(function(t){s.profileImage=t})),this.languages=window.languages,this.currencies=window.currencies,this.selectedLanguage=window.selectedLanguage,this.selectedCurrency=window.selectedCurrency,this.defaultFlag=window.defaultFlag,$("body").find("*[data-trigger]").click((function(){var s=$(this).data("trigger")+"--on";$("body").hasClass(s)?$("body").removeClass(s):$("body").addClass(s)})),void 0!==$.cookie(systemPrefix+"ThemeStyle")&&("light"==$.cookie(systemPrefix+"ThemeStyle")?$("#yk-header-logo").attr("src",$("#yk-header-logo").attr("data-lite")):""!=$("#yk-header-logo").attr("data-dark")&&$("#yk-header-logo").attr("src",$("#yk-header-logo").attr("data-dark"))),$("body").find("*[data-target-close]").click((function(){var s=$(this).data("target-close");$("body").toggleClass(s+"--on")})),$("body").mouseup((function(s){""==$(s.target).data("trigger")||"undefined"===e($(s.target).data("trigger"))?$("body").find("*[data-close-on-click-outside]").each((function(t,a){var e=$(a);e.is(s.target)||$.contains(e[0],s.target)||$("body").removeClass(e.data("close-on-click-outside")+"--on")})):s.preventDefault()})),$(".menu__link").click((function(){$("body").removeClass("account-menu--on")}))}},n=a(63),r=Object(n.a)(i,(function(){var s=this,t=s._self._c;return t("header",{staticClass:"account-area_head"},[t("a",{staticClass:"hemburger",attrs:{href:"javascript:;","data-trigger":"account-menu"}},[t("svg",{staticClass:"svg"},[t("use",{attrs:{"xlink:href":s.baseUrl+"/dashboard/media/retina/sprite.svg#hemburger"}})])]),s._v(" "),t("div",{staticClass:"logo"},[t("a",{attrs:{href:s.baseUrl}},[t("img",{attrs:{id:"yk-header-logo","data-lite":s.baseUrl+"/"+this.$getFileUrl("frontendLogo",0,0,"thumb"),"data-dark":s.baseUrl+"/"+this.$getFileUrl("frontendDarkModeLogo",0,0,"thumb"),src:s.baseUrl+"/"+this.$getFileUrl("frontendLogo",0,0,"thumb"),alt:"","data-ratio":this.$configVal("FRONTEND_LOGO_RATIO")}})])]),s._v(" "),t("div",{staticClass:"quick"},["mobile"!==s.$mq&&"tablet"!==s.$mq?t("div",{staticClass:"user-card"},[t("div",{staticClass:"user-card_inner"},[t("span",{staticClass:"user-card_photo"},[t("img",{staticClass:"YK-userAvatar",attrs:{src:s.profileImage,alt:""}})]),s._v(" "),t("div",{attrs:{clss:""}},[t("h4",{staticClass:"user-card_name"},[t("strong",[s._v(" Hi")]),s._v(" "),t("br"),s._v("\n            "+s._s(s.auth.user_fname+" "+s.auth.user_lname)+"\n          ")])])])]):s._e()])])}),[],!1,null,null,null);t.default=r.exports},295:function(s,t,a){"use strict";a.r(t);var e=a(430).a,i=a(63),n=Object(i.a)(e,(function(){var s=this,t=s._self._c;return t("div",{staticClass:"body bg-dashboard",attrs:{id:"body","data-yk":""}},[t("section",{staticClass:"section"},[t("div",{staticClass:"container"},[t("div",{staticClass:"row justify-content-center"},[t("div",{staticClass:"col-xl-10"},[t("div",{staticClass:"account-area"},[t("dasboard-header"),s._v(" "),t("left-sidebar",{attrs:{pageType:"cookie-preferences"}}),s._v(" "),t("main",{staticClass:"main-content",attrs:{"data-yk":"",role:"yk-main-content"}},[t("div",{staticClass:"card"},[t("div",{staticClass:"card-head"},["mobile"===s.$mq?t("h2",[s._v(s._s(s.$t("LBL_COOKIE_PREFERENCES")))]):s._e()]),s._v(" "),t("div",{staticClass:"card-body"},[t("div",{staticClass:"form"},[t("div",{staticClass:"row justify-content-center"},[t("div",{staticClass:"col-lg-10"},[t("div",{staticClass:"personalisation"},[t("p",{domProps:{innerHTML:s._s(s.$configVal("ADVANCED_PREFERENCES_COOKIE_TEXT"))}}),s._v(" "),t("ul",[t("li",[t("div",{staticClass:"YK-fieldCookie"},[t("label",{staticClass:"yk-label col-form-label checkbox",attrs:{for:"yk-field-functional"}},[t("input",{staticClass:"yk-choice checkbox disabledbutton",attrs:{value:"1",type:"checkbox",id:"yk-field-functional",name:"yk-ac-functional",rel:"functional",disabled:"disabled"},domProps:{checked:1==s.settings.functional}}),s._v(" "),t("strong",[s._v(s._s(s.$t("LBL_FUNCTIONAL_COOKIES")))])]),s._v(" "),t("div",{staticClass:"yk-pseudolabel"},[t("p",{domProps:{innerHTML:s._s(s.$configVal("FUNCTIONAL_COOKIE_TEXT"))}})])])]),s._v(" "),t("li",[t("div",{staticClass:"YK-fieldCookie"},[t("label",{staticClass:"yk-label col-form-label checkbox",attrs:{for:"yk-field-analytics"}},[t("input",{staticClass:"yk-choice checkbox",attrs:{value:"1",type:"checkbox",id:"yk-field-analytics",rel:"analytics",name:"yk-ac-analytics"},domProps:{checked:1==s.settings.analytics},on:{change:function(t){return s.onSwitchStatus(t,"analytics")}}}),s._v(" "),t("strong",[s._v(s._s(s.$t("LBL_STATISTICAL_ANALYSIS_COOKIE")))])]),s._v(" "),t("div",{staticClass:"yk-pseudolabel"},[t("p",{domProps:{innerHTML:s._s(s.$configVal("STATISTICAL_ANALYSIS_COOKIE_TEXT"))}})])])]),s._v(" "),t("li",[t("div",{staticClass:"YK-fieldCookie"},[t("label",{staticClass:"yk-label col-form-label checkbox",attrs:{for:"yk-field-customization"}},[t("input",{staticClass:"yk-choice checkbox",attrs:{value:"1",type:"checkbox",id:"yk-field-customization",rel:"customization",name:"yk-ac-personalized"},domProps:{checked:1==s.settings.personalized},on:{change:function(t){return s.onSwitchStatus(t,"personalized")}}}),s._v(" "),t("strong",[s._v(s._s(s.$t("LBL_PERSONALISE_COOKIE")))])]),s._v(" "),t("div",{staticClass:"yk-pseudolabel"},[t("p",{domProps:{innerHTML:s._s(s.$configVal("PERSONALIZE_COOKIE_TEXT"))}})])])]),s._v(" "),t("li",[t("div",{staticClass:"YK-fieldCookie"},[t("label",{staticClass:"yk-label col-form-label checkbox",attrs:{for:"yk-field-advertising"}},[t("input",{staticClass:"yk-choice checkbox",attrs:{value:"1",type:"checkbox",id:"yk-field-advertising",rel:"advertising",name:"yk-ac-advertising"},domProps:{checked:1==s.settings.advertising},on:{change:function(t){return s.onSwitchStatus(t,"advertising")}}}),s._v(" "),t("strong",[s._v(s._s(s.$t("LBL_ADVERTISING_SOCIAL_MEDIA_COOKIE")))])]),s._v(" "),t("div",{staticClass:"yk-pseudolabel"},[t("p",{domProps:{innerHTML:s._s(s.$configVal("ADVERTISING_SOCIAL_MEDIA_COOKIE_TEXT"))}})])])])])])])])])]),s._v(" "),t("div",{staticClass:"card-footer"},[t("div",{staticClass:"text-center"},[t("div",{staticClass:"row justify-content-center"},[t("div",{staticClass:"col-lg-6"},[t("button",{staticClass:"btn btn-brand btn-wide gb-btn gb-btn-primary",class:[1==s.sending?"gb-is-loading":""],attrs:{type:"button"},on:{click:function(t){s.updateInfo(),s.sending=!0}}},[s._v(s._s(s.$t("BTN_SAVE")))])])])])])])])],1)])])])])])}),[],!1,null,null,null);t.default=n.exports},430:function(s,t,a){"use strict";(function(s){var e=a(267),i=a(268);t.a={components:{leftSidebar:e.default,dasboardHeader:i.default},props:["settings"],data:function(){return{sending:!1,baseUrl:baseUrl}},methods:{onSwitchStatus:function(s,t){!1===s.target.checked?this.settings[t]=0:this.settings[t]=1},updateInfo:function(){var t=this,a=this.$serializeData(this.settings);this.$axios.post(baseUrl+"/user/cookie-preferences",a).then((function(a){t.sending=!1,s.success(a.data.message,"",s.options),setTimeout((function(){window.location.reload()}),1e3)}))}}}}).call(this,a(64))}}]);