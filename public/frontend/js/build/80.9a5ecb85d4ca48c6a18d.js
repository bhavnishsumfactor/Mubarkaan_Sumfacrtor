(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[80],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Partials/productCard2.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Partials/productCard2.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************/
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
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ["product", 'imageTime', "aspectRatio", "index"],
  data: function data() {
    return {
      baseUrl: baseUrl
    };
  },
  methods: {}
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Partials/productCard2.vue?vue&type=template&id=55e31210&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Partials/productCard2.vue?vue&type=template&id=55e31210& ***!
  \**********************************************************************************************************************************************************************************************************************************/
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
      _c("div", { staticClass: "product-body" }, [
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
                    ),
                    alt: _vm.product.prod_name
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
                    ),
                    alt: _vm.product.prod_name
                  }
                })
              ]
            )
          ]
        )
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "product-foot" }, [
        _c(
          "a",
          {
            staticClass: "product_title",
            attrs: {
              href: _vm.$generateUrl(_vm.product.url_rewrite),
              tabindex: "0"
            }
          },
          [_vm._v(_vm._s(_vm.product.prod_name))]
        ),
        _vm._v(" "),
        _c("div", { staticClass: "product_price" }, [
          _c("div", { staticClass: "price" }, [
            _vm._v(_vm._s(_vm.$priceFormat(_vm.product.finalprice)))
          ]),
          _vm._v(" "),
          _vm.product.price != _vm.product.finalprice
            ? _c("div", { staticClass: "sale-price text-linethrough" }, [
                _c("del", [_vm._v(_vm._s(_vm.$priceFormat(_vm.product.price)))])
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

/***/ "./resources/js/frontend/Themes/base/Partials/productCard2.vue":
/*!*********************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Partials/productCard2.vue ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _productCard2_vue_vue_type_template_id_55e31210___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./productCard2.vue?vue&type=template&id=55e31210& */ "./resources/js/frontend/Themes/base/Partials/productCard2.vue?vue&type=template&id=55e31210&");
/* harmony import */ var _productCard2_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./productCard2.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/base/Partials/productCard2.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _productCard2_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _productCard2_vue_vue_type_template_id_55e31210___WEBPACK_IMPORTED_MODULE_0__["render"],
  _productCard2_vue_vue_type_template_id_55e31210___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/base/Partials/productCard2.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Partials/productCard2.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Partials/productCard2.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard2_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./productCard2.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Partials/productCard2.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard2_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Partials/productCard2.vue?vue&type=template&id=55e31210&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Partials/productCard2.vue?vue&type=template&id=55e31210& ***!
  \****************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard2_vue_vue_type_template_id_55e31210___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./productCard2.vue?vue&type=template&id=55e31210& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Partials/productCard2.vue?vue&type=template&id=55e31210&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard2_vue_vue_type_template_id_55e31210___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productCard2_vue_vue_type_template_id_55e31210___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);