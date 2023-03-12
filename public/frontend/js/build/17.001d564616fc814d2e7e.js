(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[17],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/SliderImages.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_image_lightbox__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-image-lightbox */ "./node_modules/vue-image-lightbox/dist/vue-image-lightbox.min.js");
/* harmony import */ var vue_image_lightbox__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_image_lightbox__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_image_lightbox_dist_vue_image_lightbox_min_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-image-lightbox/dist/vue-image-lightbox.min.css */ "./node_modules/vue-image-lightbox/dist/vue-image-lightbox.min.css");
/* harmony import */ var vue_image_lightbox_dist_vue_image_lightbox_min_css__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_image_lightbox_dist_vue_image_lightbox_min_css__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var vue_slick_carousel__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-slick-carousel */ "./node_modules/vue-slick-carousel/dist/vue-slick-carousel.umd.js");
/* harmony import */ var vue_slick_carousel__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(vue_slick_carousel__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! vue-slick-carousel/dist/vue-slick-carousel.css */ "./node_modules/vue-slick-carousel/dist/vue-slick-carousel.css");
/* harmony import */ var vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_3__);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr && (typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]); if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

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
//
//
//
//
//




/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    LightBox: vue_image_lightbox__WEBPACK_IMPORTED_MODULE_0___default.a,
    VueSlickCarousel: vue_slick_carousel__WEBPACK_IMPORTED_MODULE_2___default.a
  },
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: false
      },
      onPageRequest: false,
      slider: {
        currentSlide: 1,
        slideCount: this.productImages.length
      }
    };
  },
  props: ["productImages", "productDummyImage", "productAspectRatio", "isVarientChange"],
  computed: {
    media: function media() {
      var tempMedia = [];

      for (var _i = 0, _Object$entries = Object.entries(this.productImages); _i < _Object$entries.length; _i++) {
        var _Object$entries$_i = _slicedToArray(_Object$entries[_i], 2),
            key = _Object$entries$_i[0],
            images = _Object$entries$_i[1];

        tempMedia.push({
          src: this.$getUrlById(images.afile_id, this.$prodImgSize(this.productAspectRatio, 'medium'), images.afile_updated_at),
          thumb: this.$getUrlById(images.afile_id, this.$prodImgSize(this.productAspectRatio, 'small'), images.afile_updated_at)
        });
      }

      return tempMedia;
    },
    dummyMedia: function dummyMedia() {
      var tempMedia = [];
      tempMedia.push({
        src: this.productDummyImage,
        thumb: this.productDummyImage
      });
      return tempMedia;
    }
  },
  watch: {
    isVarientChange: function isVarientChange() {
      if (this.isVarientChange) {
        this.onPageRequest = false;
      }
    }
  },
  methods: {
    openGallery: function openGallery(t) {
      if (t != '') {
        this.$refs.lightbox.showImage(t);
      } else {
        this.$refs.lightboxdummy.showImage(0);
      }
    },
    afterChangeCarousel: function afterChangeCarousel(currentSlide) {
      this.onPageRequest = true;
      this.slider.currentSlide = (currentSlide ? currentSlide : 0) + 1;
    }
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
  return _c(
    "div",
    { staticClass: "gallery gallery--product" },
    [
      _vm.productImages.length > 1
        ? _c("div", { staticClass: "slideCount" }, [
            _vm._v(_vm._s(_vm.slider.currentSlide)),
            _c("span", [_vm._v("|")]),
            _vm._v(_vm._s(_vm.slider.slideCount))
          ])
        : _vm._e(),
      _vm._v("\n        " + _vm._s(_vm.productImages) + "\n        "),
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
            [
              _c(
                "vue-slick-carousel",
                _vm._b(
                  { on: { afterChange: _vm.afterChangeCarousel } },
                  "vue-slick-carousel",
                  _vm.settings,
                  false
                ),
                _vm._l(_vm.productImages, function(images, ikey) {
                  return _c(
                    "div",
                    {
                      key: "slimg_" + ikey,
                      staticClass: "gallery__item",
                      class:
                        images.afile_enable_background != 0 && "js-fillcolor"
                    },
                    [
                      _c(
                        "a",
                        {
                          staticClass: "gallery__img",
                          on: {
                            click: function($event) {
                              return _vm.openGallery(ikey)
                            }
                          }
                        },
                        [
                          _c("img", {
                            attrs: {
                              "data-aspect-ratio": _vm.productAspectRatio,
                              "data-yk": "",
                              src: _vm.$getUrlById(
                                images.afile_id,
                                _vm.$prodImgSize(
                                  _vm.productAspectRatio,
                                  "",
                                  true
                                ),
                                images.afile_updated_at
                              ),
                              alt:
                                images.afile_attribute_alt.toLowerCase() !=
                                "null"
                                  ? images.afile_attribute_alt
                                  : "",
                              title:
                                images.afile_attribute_title.toLowerCase() !=
                                "null"
                                  ? images.afile_attribute_title
                                  : ""
                            }
                          })
                        ]
                      )
                    ]
                  )
                }),
                0
              )
            ],
            1
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
                    staticClass: "gallery__img",
                    on: {
                      click: function($event) {
                        return _vm.openGallery("")
                      }
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
          ),
      _vm._v(" "),
      _vm.media.length > 0
        ? _c("LightBox", {
            ref: "lightbox",
            attrs: { media: _vm.media, showLightBox: false }
          })
        : _vm._e(),
      _vm._v(" "),
      _vm.dummyMedia.length > 0
        ? _c("LightBox", {
            ref: "lightboxdummy",
            attrs: { media: _vm.dummyMedia, showLightBox: false }
          })
        : _vm._e()
    ],
    1
  )
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