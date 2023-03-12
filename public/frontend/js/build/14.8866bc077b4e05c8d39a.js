(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[14],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/Subscribe.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Partials/Subscribe.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme
    };
  },
  props: ['subscribeText']
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/Unsubscribe.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Partials/Unsubscribe.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme
    };
  },
  props: ['unsubscribeText']
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Cms.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Cms.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(toastr) {/* harmony import */ var _frontend_Partials_Subscribe__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/frontend/Partials/Subscribe */ "./resources/js/frontend/Partials/Subscribe.vue");
/* harmony import */ var _frontend_Partials_Unsubscribe__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/frontend/Partials/Unsubscribe */ "./resources/js/frontend/Partials/Unsubscribe.vue");
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    SubscribeModal: _frontend_Partials_Subscribe__WEBPACK_IMPORTED_MODULE_0__["default"],
    UnsubscribeModal: _frontend_Partials_Unsubscribe__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  props: ["pageHtml", "pageCss", "page_id"],
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      subscribeText: '',
      unsubscribeText: ''
    };
  },
  methods: {
    subscribeNewsletter: function subscribeNewsletter(values, thisVariable) {
      var thisObj = this;
      NProgress.start();
      this.$axios.post(baseUrl + "/newsletter", values).then(function (response) {
        NProgress.done();

        if (response.data.status) {
          thisObj.subscribeText = response.data.message;
          thisObj.$bvModal.show("subscribedModal");
          thisVariable.closest('form').find('input[name=email]').val('');
        } else {
          toastr.error(response.data.message);
        }
      });
    }
  },
  mounted: function mounted() {
    var thisObj = this;
    $(document).ready(function () {
      $(document).find('.yk-productCollectionLayout1 .yk-container, .yk-productCollectionLayout2 .yk-container, .yk-categoryCollectionLayout1 .yk-container, .yk-categoryCollectionLayout2 .yk-container').each(function () {
        $(this).find('.yk-link').attr('href', baseUrl + '/collection/' + thisObj.page_id + '/' + $(this).attr('compid'));
      });
    });

    function validateErrors(errors, obj) {
      Object.keys(errors).forEach(function (key) {
        if (!obj.find('input[name="' + key + '"], textarea[name="' + key + '"], select[name="' + key + '"]').hasClass('is-invalid')) {
          if (key.indexOf('.') !== -1) {
            var arr = key.split(".");
            var index = arr[0] + '[' + arr[1] + ']';
            obj.find('input[name="' + index + '"], textarea[name="' + index + '"], select[name="' + index + '"]').addClass('is-invalid');
            obj.find('input[name="' + index + '"], textarea[name="' + index + '"], select[name="' + index + '"]').closest('div').append('<div class="invalid-feedback">' + errors[key][0] + '</div>');
          } else {
            obj.find('input[name="' + key + '"], textarea[name="' + key + '"], select[name="' + key + '"]').addClass('is-invalid');
            obj.find('input[name="' + key + '"], textarea[name="' + key + '"], select[name="' + key + '"]').closest('div').append('<div class="invalid-feedback">' + errors[key][0] + '</div>');
          }
        }
      });
    }
    /*handle the contactform */


    $('.yk-contactForm input, .yk-contactForm textarea').removeAttr('readonly');
    $('form.yk-contactForm button[name="contactform"]').on('click', function (e) {
      e.preventDefault();
      var obj = $(this).closest('.yk-contactForm');
      obj.find('input,textarea,select').removeClass('is-invalid');
      obj.find('.invalid-feedback').remove();
      obj.find('button[name="contactform"]').addClass("gb-is-loading");
      var values = obj.find('input,textarea,select').serialize();
      NProgress.start();
      thisObj.$axios.post(baseUrl + "/contact", values).then(function (res) {
        NProgress.done();
        obj.find('button[name="contactform"]').removeClass("gb-is-loading");

        if (!res.data.status) {
          if (typeof res.data.data.errors != 'undefined') {
            validateErrors(res.data.data.errors, obj);
          } else {
            toastr.error(res.data.message);
          }

          return false;
        }

        events.contactUs();
        toastr.success(res.data.message);
        obj[0].reset();
      });
    });
    $(document).on('click', '.view_gallery', function () {
      $(this).hide();
      var closestobj = $(this).closest('.product');
      closestobj.find('.product3D').addClass('flip180');
      closestobj.find('.product-front').css('display', 'none');
      closestobj.find('.product-back').css('display', 'block');

      if ($.trim(closestobj.find(".slider-quick").html()) == "") {
        closestobj.find('.js-flipLoader').show();
        thisObj.$axios.post(baseUrl + "/product/get-gallery-images", {
          'product-id': $(this).data('productid'),
          'variant-id': $(this).data('subrecordid'),
          'prod-name': $(this).data('prodname')
        }).then(function (res) {
          closestobj.find('.js-flipLoader').hide();
          closestobj.find(".slider-quick").html(res.data);

          if (closestobj.find(".slider-quick picture").length > 1) {
            closestobj.find(".slider-quick").slick({
              slidesToShow: 1,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 1500,
              arrows: false,
              rtl: rtlValue,
              dots: true
            });
          }
        });
      }
    });
    $(document).on('click', '.flip-back', function () {
      $("#view-gallery-" + $(this).data('id')).show();
      var closestobj = $(this).closest('.product');
      closestobj.find('.product3D').removeClass('flip180');
      closestobj.find('.product-front').css('display', 'block');
      closestobj.find('.product-back').css('display', 'none');
    });
    /*handle the newsletter submission*/

    $('.YK-agreeTerms').on('click', function (e) {
      if ($(this).is(":checked")) {
        $(this).closest('.form').find('button[name="contactform"]').removeAttr('disabled');
      } else {
        $(this).closest('.form').find('button[name="contactform"]').attr('disabled', '');
      }
    });
    $('.YK-subscribeNewsletter').attr('disabled', 'disabled');
    $(document).on('keyup', '.yk-newsletter input[type="email"], .yk-newsletterFullwidth input[type="email"], .yk-newsletterCollection2 input[type="email"], .yk-footer input[name="email"]', function (e) {
      var emailValue = $(this).val();

      if (emailValue == "") {
        $(this).closest('form').find('.YK-subscribeNewsletter').attr('disabled', 'disabled');
      } else {
        $(this).closest('form').find('.YK-subscribeNewsletter').removeAttr('disabled');
      }
    });
    $('.YK-subscribeNewsletter').on('click', function (e) {
      e.preventDefault();
      var values = $(this).closest('form').serialize();
      $(this).closest('form').find('div.message').html('');
      thisObj.subscribeNewsletter(values, $(this));
    });
    var rtlValue = false;

    if ($('html').attr('dir') == 'rtl') {
      rtlValue = true;
    }

    $('.js-hero-banner').each(function () {
      if ($(this).children().length == 1) {
        $(this).removeClass('js-hero-banner');
      }
    });
    $('.js-hero-banner').each(function () {
      var thisObj = $(this);
      thisObj.slick({
        lazyLoad: 'progressive',
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        arrows: true,
        dots: true,
        rtl: rtlValue,
        autoplaySpeed: thisObj.attr('data-duration') + '000',
        prevArrow: '<div class="slick-nav prev-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
        nextArrow: '<div class="slick-nav next-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>'
      });
    });
    $('.slick-nav').on('click touch', function (e) {
      e.preventDefault();
      var arrow = $(this);

      if (!arrow.hasClass('animate')) {
        arrow.addClass('animate');
        setTimeout(function () {
          arrow.removeClass('animate');
        }, 1600);
      }
    });
    $('.js-collection-slider').each(function () {
      if ($(this).children('li').length <= 4) {
        $(this).removeClass('js-collection-slider');
      }
    });
    $(".js-collection-slider").slick({
      slidesToShow: 6,
      slidesToScroll: 1,
      autoplay: false,
      arrows: true,
      dots: false,
      rtl: rtlValue,
      autoplaySpeed: 2000,
      responsive: [{
        breakpoint: 1199,
        settings: {
          slidesToShow: 4,
          dots: false,
          arrows: false
        }
      }, {
        breakpoint: 991,
        settings: {
          slidesToShow: 3,
          arrows: false,
          dots: false
        }
      }, {
        breakpoint: 767,
        settings: {
          slidesToShow: 2,
          arrows: false,
          dots: false
        }
      }, {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
          arrows: false,
          dots: false
        }
      }]
    });
    $(".js-collection-slider-sm").slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      autoplay: false,
      arrows: true,
      dots: false,
      rtl: rtlValue,
      autoplaySpeed: 2000,
      responsive: [{
        breakpoint: 1030,
        settings: {
          slidesToShow: 3,
          dots: false,
          arrows: false
        }
      }, {
        breakpoint: 800,
        settings: {
          slidesToShow: 2,
          arrows: false,
          dots: false
        }
      }, {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          arrows: false,
          dots: false
        }
      }]
    });
    $('.yk-brandimages').each(function () {
      if ($(this).children('div').length <= 6) {
        $(this).removeClass('yk-brandimages');
      }
    });
    $(".yk-brandimages").slick({
      slidesToShow: 6,
      slidesToScroll: 1,
      autoplay: false,
      arrows: false,
      dots: false,
      rtl: rtlValue,
      autoplaySpeed: 2000,
      responsive: [{
        breakpoint: 1030,
        settings: {
          slidesToShow: 3,
          dots: false
        }
      }, {
        breakpoint: 800,
        settings: {
          slidesToShow: 2,
          arrows: false,
          dots: false
        }
      }, {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
          arrows: false,
          dots: false
        }
      }]
    });
    $(".js-slider-reviews").slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: false,
      arrows: true,
      dots: true,
      rtl: rtlValue,
      autoplaySpeed: 2000,
      responsive: [{
        breakpoint: 600,
        settings: {
          arrows: false
        }
      }]
    });
    $('.js-slider-testimonials').each(function () {
      if ($(this).children('.testimonials__item').length <= 3) {
        $(this).removeClass('js-slider-testimonials');
        $(this).addClass('testimonial-1-no-slider');
      }
    });
    $(".js-slider-testimonials").slick({
      slidesToShow: 3,
      slidesToScroll: 3,
      autoplay: false,
      arrows: true,
      dots: false,
      rtl: rtlValue,
      infinite: true,
      centerMode: true,
      centerPadding: 0,
      responsive: [{
        breakpoint: 1199,
        settings: {
          slidesToShow: 2,
          dots: true
        }
      }, {
        breakpoint: 800,
        settings: {
          slidesToShow: 2,
          dots: true
        }
      }, {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
          arrows: false,
          dots: true
        }
      }]
    });
    $('.yk-testimonialCollection2').each(function () {
      if ($(this).find('.yk-testimonials').children('.testimonials__item').length <= 2) {
        $(this).find('.slider-controls').remove();
      }
    }); //Start of Common Carousel

    var _carousel = $('.js-carousel');

    _carousel.each(function () {
      var _this$slick;

      var _this = $(this),
          _slidesToShow = _this.data("slides").toString().split(',');

      _this.slick((_this$slick = {
        slidesToShow: parseInt(_slidesToShow.length > 0 ? _slidesToShow[0] : "3"),
        slidesToScroll: 1,
        //appendDots: '.slider-controls[data-href="#' + _this.attr("id") + '"]',
        centerMode: _this.data("mode"),
        arrows: _this.data("arrows"),
        vertical: _this.data("vertical"),
        infinite: _this.data("infinite"),
        prevArrow: function () {
          return $('.prev-arrow[data-href="#' + _this.attr("id") + '"]');
        }(),
        nextArrow: function () {
          return $('.next-arrow[data-href="#' + _this.attr("id") + '"]');
        }(),
        autoplay: false,
        dots: true,
        pauseOnHover: false,
        centerPadding: 0,
        adaptiveHeight: true
      }, _defineProperty(_this$slick, "infinite", true), _defineProperty(_this$slick, "responsive", [{
        breakpoint: 1200,
        settings: {
          slidesToShow: parseInt(parseInt(_slidesToShow.length > 1 ? _slidesToShow[1] : "2")),
          vertical: false
        }
      }, {
        breakpoint: 992,
        settings: {
          slidesToShow: parseInt(parseInt(_slidesToShow.length > 2 ? _slidesToShow[2] : "1")),
          vertical: false
        }
      }, {
        breakpoint: 768,
        settings: {
          slidesToShow: parseInt(parseInt(_slidesToShow.length > 3 ? _slidesToShow[3] : "1")),
          vertical: false
        }
      }, {
        breakpoint: 576,
        settings: {
          slidesToShow: parseInt(parseInt(_slidesToShow.length > 4 ? _slidesToShow[4] : "1")),
          vertical: false
        }
      }]), _this$slick));
    }); //End of Common Carousel
    // blog slider


    $('.blog-slider-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      asNavFor: '.blog-slider-nav'
    });
    $('.blog-slider-nav').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      asNavFor: '.blog-slider-for',
      dots: false,
      focusOnSelect: true,
      responsive: [{
        breakpoint: 992,
        settings: {
          arrows: false,
          dots: true
        }
      }]
    });
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! toastr */ "./node_modules/toastr/toastr.js")))

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/Subscribe.vue?vue&type=template&id=2f0d24c8&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Partials/Subscribe.vue?vue&type=template&id=2f0d24c8& ***!
  \*******************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "b-modal",
    {
      attrs: {
        id: "subscribedModal",
        centered: "",
        title: "BootstrapVue",
        "header-class": "border-0",
        "hide-footer": ""
      },
      scopedSlots: _vm._u([
        {
          key: "modal-header",
          fn: function() {
            return [
              _c(
                "button",
                {
                  staticClass: "close",
                  attrs: { type: "button" },
                  on: {
                    click: function($event) {
                      return _vm.$bvModal.hide("subscribedModal")
                    }
                  }
                },
                [_vm._v("×")]
              )
            ]
          },
          proxy: true
        }
      ])
    },
    [
      _vm._v(" "),
      _c("div", { staticClass: "newsletter-wrapper subscribe" }, [
        _c("img", {
          attrs: {
            src:
              _vm.baseUrl +
              "/yokart/" +
              _vm.currentTheme +
              "/media/retina/subscribed.svg",
            alt: ""
          }
        }),
        _vm._v(" "),
        _c("h5", { staticClass: "YK-content" }, [
          _vm._v(_vm._s(_vm.subscribeText))
        ]),
        _vm._v(" "),
        _c(
          "button",
          {
            staticClass: "btn btn-outline-brand btn-wide",
            attrs: { type: "button" },
            on: {
              click: function($event) {
                return _vm.$bvModal.hide("subscribedModal")
              }
            }
          },
          [_vm._v(_vm._s(_vm.$t("BTN_CLOSE")))]
        )
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/Unsubscribe.vue?vue&type=template&id=a8984dba&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Partials/Unsubscribe.vue?vue&type=template&id=a8984dba& ***!
  \*********************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "b-modal",
    {
      attrs: {
        id: "unsubscribedModal",
        centered: "",
        title: "BootstrapVue",
        "header-class": "border-0",
        "hide-footer": ""
      },
      scopedSlots: _vm._u([
        {
          key: "modal-header",
          fn: function() {
            return [
              _c(
                "button",
                {
                  staticClass: "close",
                  attrs: { type: "button" },
                  on: {
                    click: function($event) {
                      return _vm.$bvModal.hide("unsubscribedModal")
                    }
                  }
                },
                [_vm._v("×")]
              )
            ]
          },
          proxy: true
        }
      ])
    },
    [
      _vm._v(" "),
      _c("div", { staticClass: "newsletter-wrapper subscribe" }, [
        _c("img", {
          attrs: {
            src:
              _vm.baseUrl +
              "/yokart/" +
              _vm.currentTheme +
              "/media/retina/unsubscibed.svg",
            alt: ""
          }
        }),
        _vm._v(" "),
        _c("h5", { staticClass: "YK-content" }, [
          _vm._v(_vm._s(_vm.unsubscribeText))
        ]),
        _vm._v(" "),
        _c(
          "button",
          {
            staticClass: "btn btn-outline-brand btn-wide",
            attrs: { type: "button" },
            on: {
              click: function($event) {
                return _vm.$bvModal.hide("unsubscribedModal")
              }
            }
          },
          [_vm._v(_vm._s(_vm.$t("BTN_CLOSE")))]
        )
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Cms.vue?vue&type=template&id=74ca0cbc&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Cms.vue?vue&type=template&id=74ca0cbc& ***!
  \*******************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c("div", {
        staticClass: "body",
        attrs: { id: "body", "data-yk": "" },
        domProps: { innerHTML: _vm._s(_vm.pageHtml) }
      }),
      _vm._v(" "),
      _c("style", {
        tag: "component",
        domProps: { innerHTML: _vm._s(_vm.pageCss) }
      }),
      _vm._v(" "),
      _c("subscribe-modal", { attrs: { subscribeText: _vm.subscribeText } }),
      _vm._v(" "),
      _c("unsubscribe-modal", {
        attrs: { unsubscribeText: _vm.unsubscribeText }
      })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return normalizeComponent; });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./resources/js/frontend/Partials/Subscribe.vue":
/*!******************************************************!*\
  !*** ./resources/js/frontend/Partials/Subscribe.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Subscribe_vue_vue_type_template_id_2f0d24c8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Subscribe.vue?vue&type=template&id=2f0d24c8& */ "./resources/js/frontend/Partials/Subscribe.vue?vue&type=template&id=2f0d24c8&");
/* harmony import */ var _Subscribe_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Subscribe.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Partials/Subscribe.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Subscribe_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Subscribe_vue_vue_type_template_id_2f0d24c8___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Subscribe_vue_vue_type_template_id_2f0d24c8___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Partials/Subscribe.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Partials/Subscribe.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/frontend/Partials/Subscribe.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscribe_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Subscribe.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/Subscribe.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscribe_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Partials/Subscribe.vue?vue&type=template&id=2f0d24c8&":
/*!*************************************************************************************!*\
  !*** ./resources/js/frontend/Partials/Subscribe.vue?vue&type=template&id=2f0d24c8& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscribe_vue_vue_type_template_id_2f0d24c8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Subscribe.vue?vue&type=template&id=2f0d24c8& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/Subscribe.vue?vue&type=template&id=2f0d24c8&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscribe_vue_vue_type_template_id_2f0d24c8___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscribe_vue_vue_type_template_id_2f0d24c8___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/frontend/Partials/Unsubscribe.vue":
/*!********************************************************!*\
  !*** ./resources/js/frontend/Partials/Unsubscribe.vue ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Unsubscribe_vue_vue_type_template_id_a8984dba___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Unsubscribe.vue?vue&type=template&id=a8984dba& */ "./resources/js/frontend/Partials/Unsubscribe.vue?vue&type=template&id=a8984dba&");
/* harmony import */ var _Unsubscribe_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Unsubscribe.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Partials/Unsubscribe.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Unsubscribe_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Unsubscribe_vue_vue_type_template_id_a8984dba___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Unsubscribe_vue_vue_type_template_id_a8984dba___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Partials/Unsubscribe.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Partials/Unsubscribe.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/frontend/Partials/Unsubscribe.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Unsubscribe_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Unsubscribe.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/Unsubscribe.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Unsubscribe_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Partials/Unsubscribe.vue?vue&type=template&id=a8984dba&":
/*!***************************************************************************************!*\
  !*** ./resources/js/frontend/Partials/Unsubscribe.vue?vue&type=template&id=a8984dba& ***!
  \***************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Unsubscribe_vue_vue_type_template_id_a8984dba___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Unsubscribe.vue?vue&type=template&id=a8984dba& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/Unsubscribe.vue?vue&type=template&id=a8984dba&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Unsubscribe_vue_vue_type_template_id_a8984dba___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Unsubscribe_vue_vue_type_template_id_a8984dba___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Cms.vue":
/*!******************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Cms.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Cms_vue_vue_type_template_id_74ca0cbc___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Cms.vue?vue&type=template&id=74ca0cbc& */ "./resources/js/frontend/Themes/fashion/Cms.vue?vue&type=template&id=74ca0cbc&");
/* harmony import */ var _Cms_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Cms.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/fashion/Cms.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Cms_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Cms_vue_vue_type_template_id_74ca0cbc___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Cms_vue_vue_type_template_id_74ca0cbc___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/fashion/Cms.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Cms.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Cms.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Cms_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Cms.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Cms.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Cms_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Cms.vue?vue&type=template&id=74ca0cbc&":
/*!*************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Cms.vue?vue&type=template&id=74ca0cbc& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Cms_vue_vue_type_template_id_74ca0cbc___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Cms.vue?vue&type=template&id=74ca0cbc& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Cms.vue?vue&type=template&id=74ca0cbc&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Cms_vue_vue_type_template_id_74ca0cbc___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Cms_vue_vue_type_template_id_74ca0cbc___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);