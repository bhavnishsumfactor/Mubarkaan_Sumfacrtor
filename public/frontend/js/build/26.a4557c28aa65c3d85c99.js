(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[26],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Partials/productCard.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Partials/productCard.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************/
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


/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    VueSlickCarousel: vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0___default.a
  },
  props: ["product", 'imageTime', "aspectRatio", "index"],
  data: function data() {
    return {
      baseUrl: baseUrl,
      flip180: false,
      displayProductFront: true,
      displayProductBack: false,
      displayFlipLoader: false,
      productAspectRatio: '',
      media: [],
      isFav: false,
      gallerySliderSettings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1500,
        arrows: false,
        rtl: $('html').attr('dir') == 'rtl' ? true : false,
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
          'product_id': this.product.prod_id,
          'pov_code': this.product.pov_code
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Partials/productCard.vue?vue&type=template&id=7663b0ac&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Partials/productCard.vue?vue&type=template&id=7663b0ac& ***!
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
    {
      staticClass: "product",
      class: [
        _vm.$productStockVerify(_vm.product) == false ? "no-stock-wrap" : ""
      ],
      attrs: { "data-ratio": _vm.aspectRatio }
    },
    [
      _vm.$productStockVerify(_vm.product) == false
        ? _c("div", { staticClass: "no-stock" }, [
            _vm._v(_vm._s(_vm.$t("LBL_OUT_OF_STOCK")))
          ])
        : _vm._e(),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass: "product3D",
          class: [_vm.flip180 === true ? "flip180" : ""]
        },
        [
          _c(
            "div",
            {
              staticClass: "product-front",
              class: [_vm.displayProductFront === true ? "d-block" : "d-none"]
            },
            [
              _vm.product.discount != 0
                ? _c("span", { staticClass: "badge-sale tag tag-primary" }, [
                    _c("span", [
                      _vm._v(
                        _vm._s(_vm.$t("BDG_SAVE")) +
                          " " +
                          _vm._s(Math.round(_vm.product.discount)) +
                          "%"
                      )
                    ])
                  ])
                : _vm._e(),
              _vm._v(" "),
              _c(
                "a",
                { attrs: { href: _vm.$generateUrl(_vm.product.url_rewrite) } },
                [
                  _c(
                    "picture",
                    {
                      staticClass: "product-img",
                      attrs: { "data-ratio": _vm.aspectRatio }
                    },
                    [
                      _c("source", {
                        attrs: {
                          type: "image/webp",
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
                          "data-yk": "",
                          "data-aspect-ratio": _vm.aspectRatio,
                          src: _vm.$productImage(
                            _vm.product.prod_id,
                            _vm.product.pov_code,
                            _vm.imageTime,
                            _vm.$prodImgSize(_vm.aspectRatio, "medium", true)
                          )
                        }
                      })
                    ]
                  )
                ]
              )
            ]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "product-back js-fillcolor",
              class: [_vm.displayProductBack === true ? "d-block" : "d-none"]
            },
            [
              _c(
                "div",
                {
                  staticClass: "loader-flip",
                  class: [_vm.displayFlipLoader === true ? "d-block" : "d-none"]
                },
                [
                  _c("img", {
                    attrs: {
                      "data-yk": "",
                      src: _vm.baseUrl + "/yokart/media/loading.gif",
                      alt: ""
                    }
                  })
                ]
              ),
              _vm._v(" "),
              _c(
                "div",
                {
                  staticClass: "flip-back",
                  attrs: { "data-id": _vm.product.prod_id },
                  on: { click: _vm.closeGallery }
                },
                [
                  _c("img", {
                    attrs: {
                      "data-yk": "",
                      src: _vm.baseUrl + "/yokart/media/retina/remove.svg",
                      alt: ""
                    }
                  })
                ]
              ),
              _vm._v(" "),
              _vm.media.length > 0
                ? _c(
                    "div",
                    { staticClass: "slider-quick" },
                    [
                      _c(
                        "vue-slick-carousel",
                        _vm._b(
                          {},
                          "vue-slick-carousel",
                          _vm.gallerySliderSettings,
                          false
                        ),
                        _vm._l(_vm.media, function(images, ikey) {
                          return _c(
                            "div",
                            {
                              key: "slimg_" + ikey,
                              staticClass: "slider-item"
                            },
                            [
                              _c(
                                "picture",
                                {
                                  staticClass: "product-img",
                                  attrs: {
                                    "data-ratio": _vm.productAspectRatio
                                  }
                                },
                                [
                                  _c("source", {
                                    attrs: {
                                      type: "image/webp",
                                      src: _vm.$getUrlById(
                                        images.afile_id,
                                        _vm.$prodImgSize(
                                          _vm.productAspectRatio,
                                          "medium",
                                          true
                                        ),
                                        images.afile_updated_at
                                      ),
                                      title:
                                        images.afile_attribute_title != null &&
                                        images.afile_attribute_title.toLowerCase() !=
                                          "null"
                                          ? images.afile_attribute_title
                                          : ""
                                    }
                                  }),
                                  _vm._v(" "),
                                  _c("img", {
                                    attrs: {
                                      "data-yk": "",
                                      "data-aspect-ratio":
                                        _vm.productAspectRatio,
                                      src: _vm.$getUrlById(
                                        images.afile_id,
                                        _vm.$prodImgSize(
                                          _vm.productAspectRatio,
                                          "medium"
                                        ),
                                        images.afile_updated_at
                                      ),
                                      alt:
                                        images.afile_attribute_alt != null &&
                                        images.afile_attribute_alt.toLowerCase() !=
                                          "null"
                                          ? images.afile_attribute_alt
                                          : "",
                                      title:
                                        images.afile_attribute_title != null &&
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
                : _vm._e()
            ]
          )
        ]
      ),
      _vm._v(" "),
      _c("div", { staticClass: "product-foot" }, [
        _c("div", { staticClass: "product-actions" }, [
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
              _c("i", { staticClass: "icn" }, [
                _c("svg", { staticClass: "svg" }, [
                  _c("use", {
                    attrs: {
                      "xlink:href":
                        _vm.baseUrl +
                        "/yokart/media/retina/sprite.svg#favorite-filled",
                      href:
                        _vm.baseUrl +
                        "/yokart/media/retina/sprite.svg#favorite-filled"
                    }
                  })
                ])
              ])
            ]
          ),
          _vm._v(" "),
          _c(
            "button",
            {
              staticClass: "btn",
              class: [_vm.displayProductFront === true ? "d-flex" : "d-none"],
              attrs: {
                id: "view-gallery-" + _vm.product.prod_id,
                "data-productId": _vm.product.prod_id,
                "data-prodname": _vm.product.prod_name,
                type: "button"
              },
              on: { click: _vm.viewGallery }
            },
            [
              _c("i", { staticClass: "icn" }, [
                _c("svg", { staticClass: "svg" }, [
                  _c("use", {
                    attrs: {
                      "xlink:href":
                        _vm.baseUrl +
                        "/yokart/media/retina/sprite.svg#image-gallery-filled",
                      href:
                        _vm.baseUrl +
                        "/yokart/media/retina/sprite.svg#image-gallery-filled"
                    }
                  })
                ])
              ])
            ]
          )
        ]),
        _vm._v(" "),
        _vm.product.varients
          ? _c("div", { staticClass: "product-options" }, [
              _c("h6", [_vm._v(_vm._s(_vm.$t("LBL_COLORS")))]),
              _vm._v(" "),
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
                        _vm._v("+" + _vm._s(_vm.product.varients.length - 4))
                      ])
                    : _vm._e()
                ],
                2
              )
            ])
          : _vm._e(),
        _vm._v(" "),
        _c("div", { staticClass: "product_category" }, [
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
            [_vm._v(_vm._s(_vm.product.cat_name ? _vm.product.cat_name : ""))]
          )
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "product_title" }, [
          _c(
            "a",
            { attrs: { href: _vm.$generateUrl(_vm.product.url_rewrite) } },
            [_vm._v(_vm._s(_vm.product.prod_name))]
          )
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "product_price" }, [
          _c("span", { staticClass: "notranslate" }, [
            _vm._v(
              "\n          " + _vm._s(_vm.$priceFormat(_vm.product.finalprice))
            )
          ]),
          _vm._v(" "),
          _vm.product.price != _vm.product.finalprice
            ? _c("del", { staticClass: "product_price_old notranslate" }, [
                _vm._v(
                  "\n        " +
                    _vm._s(_vm.$priceFormat(_vm.product.price)) +
                    "\n      "
                )
              ])
            : _vm._e()
        ])
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Themes/base/Partials/productCard.vue":
/*!********************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Partials/productCard.vue ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _productCard_vue_vue_type_template_id_7663b0ac___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./productCard.vue?vue&type=template&id=7663b0ac& */ "./resources/js/frontend/Themes/base/Partials/productCard.vue?vue&type=template&id=7663b0ac&");
/* harmony import */ var _productCard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./productCard.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/base/Partials/productCard.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _productCard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _productCard_vue_vue_type_template_id_7663b0ac___WEBPACK_IMPORTED_MODULE_0__["render"],
  _productCard_vue_vue_type_template_id_7663b0ac___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/base/Partials/productCard.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Partials/productCard.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Partials/productCard.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./productCard.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Partials/productCard.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Partials/productCard.vue?vue&type=template&id=7663b0ac&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Partials/productCard.vue?vue&type=template&id=7663b0ac& ***!
  \***************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard_vue_vue_type_template_id_7663b0ac___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./productCard.vue?vue&type=template&id=7663b0ac& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Partials/productCard.vue?vue&type=template&id=7663b0ac&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard_vue_vue_type_template_id_7663b0ac___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard_vue_vue_type_template_id_7663b0ac___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);