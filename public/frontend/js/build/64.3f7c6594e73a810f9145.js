(window.webpackJsonp=window.webpackJsonp||[]).push([[64,66,67,88],{270:function(t,e,r){"use strict";r.r(e);var a=r(419),i=r.n(a),n=(r(420),r(412)),o=r.n(n);r(413);function s(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(t)))return;var r=[],a=!0,i=!1,n=void 0;try{for(var o,s=t[Symbol.iterator]();!(a=(o=s.next()).done)&&(r.push(o.value),!e||r.length!==e);a=!0);}catch(t){i=!0,n=t}finally{try{a||null==s.return||s.return()}finally{if(i)throw n}}return r}(t,e)||function(t,e){if(!t)return;if("string"==typeof t)return l(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);"Object"===r&&t.constructor&&(r=t.constructor.name);if("Map"===r||"Set"===r)return Array.from(t);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return l(t,e)}(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function l(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,a=new Array(e);r<e;r++)a[r]=t[r];return a}var u={components:{LightBox:i.a,VueSlickCarousel:o.a},data:function(){return{baseUrl:baseUrl,currentTheme:currentTheme,settings:{slidesToShow:1,slidesToScroll:1,infinite:!1},onPageRequest:!1,slider:{currentSlide:1,slideCount:this.productImages.length}}},props:["productImages","productDummyImage","productAspectRatio","isVarientChange"],computed:{media:function(){for(var t=[],e=0,r=Object.entries(this.productImages);e<r.length;e++){var a=s(r[e],2),i=(a[0],a[1]);t.push({src:this.$getUrlById(i.afile_id,this.$prodImgSize(this.productAspectRatio,"medium"),i.afile_updated_at),thumb:this.$getUrlById(i.afile_id,this.$prodImgSize(this.productAspectRatio,"small"),i.afile_updated_at)})}return t},dummyMedia:function(){var t=[];return t.push({src:this.productDummyImage,thumb:this.productDummyImage}),t}},watch:{isVarientChange:function(){this.isVarientChange&&(this.onPageRequest=!1)}},methods:{openGallery:function(t){""!=t?this.$refs.lightbox.showImage(t):this.$refs.lightboxdummy.showImage(0)},afterChangeCarousel:function(t){this.onPageRequest=!0,this.slider.currentSlide=(t||0)+1}}},c=r(403),d=Object(c.a)(u,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"gallery gallery--product"},[t.productImages.length>1?r("div",{staticClass:"slideCount"},[t._v(t._s(t.slider.currentSlide)),r("span",[t._v("|")]),t._v(t._s(t.slider.slideCount))]):t._e(),t._v(" "),t.productImages.length>0?r("div",{staticClass:"gallery__main js--product-gallery",attrs:{"data-aspect-ratio":t.productAspectRatio,id:"product-gallery"}},[r("vue-slick-carousel",t._b({on:{afterChange:t.afterChangeCarousel}},"vue-slick-carousel",t.settings,!1),t._l(t.productImages,(function(e,a){return r("div",{key:"slimg_"+a,staticClass:"gallery__item",class:0!=e.afile_enable_background&&"js-fillcolor"},[r("a",{staticClass:"gallery__img",on:{click:function(e){return t.openGallery(a)}}},[r("img",{attrs:{"data-aspect-ratio":t.productAspectRatio,"data-yk":"",src:t.$getUrlById(e.afile_id,t.$prodImgSize(t.productAspectRatio,"",!0),e.afile_updated_at),alt:null!=e.afile_attribute_alt&&"null"!=e.afile_attribute_alt.toLowerCase()?e.afile_attribute_alt:"",title:null!=e.afile_attribute_title&&"null"!=e.afile_attribute_title.toLowerCase()?e.afile_attribute_title:""}})])])})),0)],1):r("div",{staticClass:"gallery__main js--product-gallery",attrs:{"data-aspect-ratio":t.productAspectRatio,id:"product-gallery"}},[r("div",{staticClass:"gallery__item"},[r("a",{staticClass:"gallery__img",on:{click:function(e){return t.openGallery("")}}},[r("img",{attrs:{"data-aspect-ratio":t.productAspectRatio,"data-yk":"",alt:"",src:t.productDummyImage}})])])]),t._v(" "),t.media.length>0?r("LightBox",{ref:"lightbox",attrs:{media:t.media,showLightBox:!1}}):t._e(),t._v(" "),t.dummyMedia.length>0?r("LightBox",{ref:"lightboxdummy",attrs:{media:t.dummyMedia,showLightBox:!1}}):t._e()],1)}),[],!1,null,null,null);e.default=d.exports},403:function(t,e,r){"use strict";function a(t,e,r,a,i,n,o,s){var l,u="function"==typeof t?t.options:t;if(e&&(u.render=e,u.staticRenderFns=r,u._compiled=!0),a&&(u.functional=!0),n&&(u._scopeId="data-v-"+n),o?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(o)},u._ssrRegister=l):i&&(l=s?function(){i.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:i),l)if(u.functional){u._injectStyles=l;var c=u.render;u.render=function(t,e){return l.call(e),c(t,e)}}else{var d=u.beforeCreate;u.beforeCreate=d?[].concat(d,l):[l]}return{exports:t,options:u}}r.d(e,"a",(function(){return a}))}}]);