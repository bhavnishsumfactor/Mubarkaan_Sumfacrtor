(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[49],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************/
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
  props: ["productImages", "productDummyImage", "productAspectRatio"],
  mounted: function mounted() {
    var $status = $('.slideCount');
    var $slickElement = $('.js--product-gallery');
    $slickElement.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
      var i = (currentSlide ? currentSlide : 0) + 1;
      $status.html(i + '<span>|</span>' + slick.slideCount);
    });
    $('.js--product-gallery').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      infinite: false
    });
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=template&id=6417ef01&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=template&id=6417ef01& ***!
  \*********************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "gallery gallery--product" }, [
    _vm.productImages.length > 1
      ? _c("div", { staticClass: "slideCount" })
      : _vm._e(),
    _vm._v(" "),
    _vm.productImages.length > 0
      ? _c(
          "div",
          {
            staticClass: "gallery__main js--product-gallery",
            attrs: {
              "data-aspect-ratio": _vm.productAspectRatio,
              id: "product-gallery"
            }
          },
          _vm._l(_vm.productImages, function(images, ikey) {
            return _c(
              "div",
              {
                key: "slimg_" + ikey,
                staticClass: "gallery__item",
                class: images.afile_enable_background != 0 && "js-fillcolor"
              },
              [
                _c(
                  "a",
                  {
                    staticClass: "gallery__img fresco",
                    attrs: {
                      href: images.thumb,
                      "data-fresco-options": "thumbnail: " + images.small,
                      "data-fresco-group": "product_slider_thumbs"
                    }
                  },
                  [
                    _c("img", {
                      attrs: {
                        "data-aspect-ratio": _vm.productAspectRatio,
                        "data-yk": "",
                        src: images.default,
                        alt: images.afile_attribute_alt,
                        title: images.afile_attribute_title
                      }
                    })
                  ]
                )
              ]
            )
          }),
          0
        )
      : _c(
          "div",
          {
            staticClass: "gallery__main js--product-gallery",
            attrs: {
              "data-aspect-ratio": _vm.productAspectRatio,
              id: "product-gallery"
            }
          },
          [
            _c("div", { staticClass: "gallery__item" }, [
              _c(
                "a",
                {
                  staticClass: "gallery__img fresco",
                  attrs: {
                    href: _vm.productDummyImage,
                    "data-fresco-options":
                      "thumbnail: " + _vm.productDummyImage,
                    "data-fresco-group": "product_slider_thumbs"
                  }
                },
                [
                  _c("img", {
                    attrs: {
                      "data-aspect-ratio": _vm.productAspectRatio,
                      "data-yk": "",
                      alt: "",
                      src: _vm.productDummyImage
                    }
                  })
                ]
              )
            ])
          ]
        )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/SliderImages.vue":
/*!********************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/SliderImages.vue ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SliderImages_vue_vue_type_template_id_6417ef01___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SliderImages.vue?vue&type=template&id=6417ef01& */ "./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=template&id=6417ef01&");
/* harmony import */ var _SliderImages_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SliderImages.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SliderImages_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SliderImages_vue_vue_type_template_id_6417ef01___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SliderImages_vue_vue_type_template_id_6417ef01___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/base/Product/SliderImages.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SliderImages_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./SliderImages.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SliderImages_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=template&id=6417ef01&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=template&id=6417ef01& ***!
  \***************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SliderImages_vue_vue_type_template_id_6417ef01___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./SliderImages.vue?vue&type=template&id=6417ef01& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=template&id=6417ef01&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SliderImages_vue_vue_type_template_id_6417ef01___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SliderImages_vue_vue_type_template_id_6417ef01___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);