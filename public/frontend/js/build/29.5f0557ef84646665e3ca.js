(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[29],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Partials/productCard.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Partials/productCard.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-slick-carousel */ "./node_modules/vue-slick-carousel/dist/vue-slick-carousel.umd.js");
/* harmony import */ var vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-slick-carousel/dist/vue-slick-carousel.css */ "./node_modules/vue-slick-carousel/dist/vue-slick-carousel.css");
/* harmony import */ var vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_1__);
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
    VueSlickCarousel: vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0___default.a
  },
  props: ["product", "imageTime", "aspectRatio", "index"],
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      flip180: false,
      displayProductFront: true,
      displayProductBack: false,
      displayFlipLoader: false,
      productAspectRatio: "",
      media: [],
      isFav: false,
      gallerySliderSettings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1500,
        arrows: false,
        rtl: $("html").attr("dir") == "rtl" ? true : false,
        dots: true
      }
    };
  },
  methods: {
    updateFavorite: function updateFavorite() {
      var _this = this;

      if (this.$auth() === null) {
        this.$bvModal.show("loginModal");
        return;
      }

      var formData = this.$serializeData({
        id: this.product.prod_id,
        code: this.product.pov_code
      });
      this.$axios.post(baseUrl + "/product/favourite", formData).then(function (response) {
        if (response.data.status == false) {
          return false;
        }

        var result = response.data.data.product;
        var fav_id = null;
        var fav_code = null;

        if (result) {
          fav_id = result.favId;
          fav_code = result.ufp_pov_code;
        }

        _this.product.favId = fav_id;
        _this.product.ufp_pov_code = fav_code;
        _this.isFav = _this.checkFavorite(_this.product);
      });
    },
    viewGallery: function viewGallery() {
      var _this2 = this;

      this.flip180 = true;
      this.displayProductFront = false;
      this.displayProductBack = true;

      if (this.media.length === 0) {
        this.displayFlipLoader = true;
        this.$axios.post(baseUrl + "/product/get-gallery-images", {
          product_id: this.product.prod_id,
          pov_code: this.product.pov_code
        }).then(function (res) {
          _this2.displayFlipLoader = false;
          _this2.productAspectRatio = res.data.data.productAspectRatio;
          _this2.media = res.data.data.images;
        });
      }
    },
    closeGallery: function closeGallery() {
      this.flip180 = true;
      this.displayProductFront = true;
      this.displayProductBack = false;
    },
    checkFavorite: function checkFavorite(product) {
      if (product.favId && (product.ufp_pov_code == "" || product.ufp_pov_code == product.pov_code)) {
        return true;
      }

      return false;
    }
  },
  mounted: function mounted() {
    this.isFav = this.checkFavorite(this.product);
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Partials/productCard.vue?vue&type=template&id=20fd12bf&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Partials/productCard.vue?vue&type=template&id=20fd12bf& ***!
  \************************************************************************************************************************************************************************************************************************************/
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
    {
      staticClass: "product",
      class: [
        _vm.$productStockVerify(_vm.product) == false ? "no-stock-wrap" : ""
      ]
    },
    [
      _c("div", { staticClass: "product__body" }, [
        _c(
          "picture",
          {
            staticClass: "product__img",
            attrs: { "data-ratio": _vm.aspectRatio }
          },
          [
            _c("source", {
              attrs: {
                "data-aspect-ratio": _vm.aspectRatio,
                type: "image/png",
                srcset: _vm.$productImage(
                  _vm.product.prod_id,
                  _vm.product.pov_code,
                  _vm.imageTime,
                  _vm.$prodImgSize(_vm.aspectRatio, "medium")
                )
              }
            }),
            _vm._v(" "),
            _c("img", {
              attrs: {
                "data-aspect-ratio": _vm.aspectRatio,
                src: _vm.$productImage(
                  _vm.product.prod_id,
                  _vm.product.pov_code,
                  _vm.imageTime,
                  _vm.$prodImgSize(_vm.aspectRatio, "medium", true)
                ),
                alt: ""
              }
            })
          ]
        ),
        _vm._v(" "),
        _c("div", { staticClass: "product__actions" }, [
          _c(
            "button",
            {
              staticClass: "btn wishlist",
              class: [_vm.isFav == true ? "active" : ""],
              attrs: { type: "button" },
              on: {
                click: function($event) {
                  return _vm.updateFavorite(_vm.product)
                }
              }
            },
            [
              _c(
                "svg",
                {
                  staticClass: "svg",
                  attrs: { width: "17.5", height: "15.455" }
                },
                [
                  _c("use", {
                    attrs: {
                      "xlink:href":
                        _vm.baseUrl +
                        "/yokart/" +
                        _vm.currentTheme +
                        "/media/retina/sprite-new.svg#icon__heart",
                      href:
                        _vm.baseUrl +
                        "/yokart/" +
                        _vm.currentTheme +
                        "/media/retina/sprite-new.svg#icon__heart"
                    }
                  })
                ]
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "button",
            { staticClass: "btn view_gallery", attrs: { type: "button" } },
            [
              _c(
                "svg",
                {
                  staticClass: "svg",
                  attrs: { width: "16", height: "15.994" }
                },
                [
                  _c("use", {
                    attrs: {
                      "xlink:href":
                        _vm.baseUrl +
                        "/yokart/" +
                        _vm.currentTheme +
                        "/media/retina/sprite-new.svg#icon__gallery",
                      href:
                        _vm.baseUrl +
                        "/yokart/" +
                        _vm.currentTheme +
                        "/media/retina/sprite-new.svg#icon__gallery"
                    }
                  })
                ]
              )
            ]
          )
        ]),
        _vm._v(" "),
        _vm.product.discount != 0
          ? _c("div", { staticClass: "product__offbadge" }, [
              _vm._v(_vm._s(Math.round(_vm.product.discount)) + "% OFF")
            ])
          : _vm._e()
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "product__footer" }, [
        _c("div", { staticClass: "d-md-flex justify-content-between" }, [
          _c("div", { staticClass: "product__leftcol" }, [
            _c("h6", { staticClass: "product__category" }, [
              _c(
                "a",
                {
                  attrs: {
                    href: [
                      _vm.product.cat_id
                        ? _vm.baseUrl + "/category/" + _vm.product.cat_id
                        : "javascript:;"
                    ]
                  }
                },
                [
                  _vm._v(
                    _vm._s(_vm.product.cat_name ? _vm.product.cat_name : "")
                  )
                ]
              )
            ]),
            _vm._v(" "),
            _c("h2", { staticClass: "product__title" }, [
              _c(
                "a",
                { attrs: { href: _vm.$generateUrl(_vm.product.url_rewrite) } },
                [_vm._v(_vm._s(_vm.product.prod_name))]
              )
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "product__price" }, [
              _c("span", { staticClass: "product__price-new" }, [
                _vm._v(_vm._s(_vm.$priceFormat(_vm.product.finalprice)))
              ]),
              _vm._v(" "),
              parseFloat(_vm.product.price) !==
              parseFloat(_vm.product.finalprice)
                ? _c("span", { staticClass: "product__price-old" }, [
                    _vm._v(_vm._s(_vm.$priceFormat(_vm.product.price)))
                  ])
                : _vm._e()
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "product__rightcol mt-md-0 mt-2" }, [
            _vm.product.varients
              ? _c("div", { staticClass: "product__options" }, [
                  _c(
                    "ul",
                    { staticClass: "colors" },
                    [
                      _vm._l(
                        _vm.product.varients.filter(function(itm, indx) {
                          return indx < 4
                        }),
                        function(varient, index) {
                          return _c("li", { key: index }, [
                            _c("span", {
                              style: {
                                backgroundColor: varient.opn_color_code
                                  ? varient.opn_color_code
                                  : _vm.color.opn_value
                              }
                            })
                          ])
                        }
                      ),
                      _vm._v(" "),
                      _vm.product.varients.length > 4
                        ? _c("li", [
                            _vm._v(
                              "+" + _vm._s(_vm.product.varients.length - 4)
                            )
                          ])
                        : _vm._e()
                    ],
                    2
                  )
                ])
              : _vm._e()
          ])
        ])
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Partials/productCard.vue":
/*!***********************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Partials/productCard.vue ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _productCard_vue_vue_type_template_id_20fd12bf___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./productCard.vue?vue&type=template&id=20fd12bf& */ "./resources/js/frontend/Themes/fashion/Partials/productCard.vue?vue&type=template&id=20fd12bf&");
/* harmony import */ var _productCard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./productCard.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/fashion/Partials/productCard.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _productCard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _productCard_vue_vue_type_template_id_20fd12bf___WEBPACK_IMPORTED_MODULE_0__["render"],
  _productCard_vue_vue_type_template_id_20fd12bf___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/fashion/Partials/productCard.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Partials/productCard.vue?vue&type=script&lang=js&":
/*!************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Partials/productCard.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./productCard.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Partials/productCard.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Partials/productCard.vue?vue&type=template&id=20fd12bf&":
/*!******************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Partials/productCard.vue?vue&type=template&id=20fd12bf& ***!
  \******************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard_vue_vue_type_template_id_20fd12bf___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./productCard.vue?vue&type=template&id=20fd12bf& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Partials/productCard.vue?vue&type=template&id=20fd12bf&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard_vue_vue_type_template_id_20fd12bf___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard_vue_vue_type_template_id_20fd12bf___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);