(window.webpackJsonp=window.webpackJsonp||[]).push([[118],{281:function(t,e,r){"use strict";r.r(e);var n=r(436),s=r.n(n),c=(r(437),{props:["recentViewProducts","aspectRatio"],components:{VueSlickCarousel:s.a,productCard:function(){return r(435)("./".concat(currentTheme,"/Partials/productCard"))}},data:function(){return{settings:{arrows:!0,dots:!0,slidesPerRow:6},currentTheme:currentTheme}}}),o=r(432),i=Object(o.a)(c,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("section",{staticClass:"section bg-grayx"},[r("div",{staticClass:"container"},[r("div",{staticClass:"section-content section-content-center"},[r("h2",[t._v(t._s(t.$t("LBL_RECENTLY_VIEWED")))])]),t._v(" "),r("ul",{staticClass:"js-fourColumn-slider collection-slider"},[r("vue-slick-carousel",t._b({},"vue-slick-carousel",t.settings,!1),t._l(t.recentViewProducts,(function(e,n){return r("li",{key:n,staticClass:"item"},[r("product-card",{attrs:{index:n,product:e,imageTime:"",aspectRatio:t.aspectRatio}})],1)})),0)],1)])])}),[],!1,null,null,null);e.default=i.exports},435:function(t,e,r){var n={"./base/Partials/productCard":[237,0,122],"./fashion/Partials/productCard":[238,0,133]};function s(t){if(!r.o(n,t))return Promise.resolve().then((function(){var e=new Error("Cannot find module '"+t+"'");throw e.code="MODULE_NOT_FOUND",e}));var e=n[t],s=e[0];return Promise.all(e.slice(1).map(r.e)).then((function(){return r(s)}))}s.keys=function(){return Object.keys(n)},s.id=435,t.exports=s}}]);