(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[127],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Listing.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/Listing.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _frontend_Partials_NoRecordFound__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/frontend/Partials/NoRecordFound */ "./resources/js/frontend/Partials/NoRecordFound.vue");
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
  props: ["products", "listingGridView", "aspectRatio", "perPageRecords", "firstItem", "lastItem", "pagination", "perPage"],
  components: {
    NoRecordFound: _frontend_Partials_NoRecordFound__WEBPACK_IMPORTED_MODULE_0__["default"],
    ProductsPagination: function ProductsPagination() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Partials\\/Pagination$")("./".concat(currentTheme, "/Partials/Pagination"));
    },
    productCard: function productCard() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Partials\\/productCard$")("./".concat(currentTheme, "/Partials/productCard"));
    }
  },
  data: function data() {
    return {
      baseUrl: baseUrl,
      showRecords: 0,
      currentTheme: currentTheme
    };
  },
  methods: {
    currentPage: function currentPage(pageNumber) {
      this.$emit("updateRecords", "pagination", pageNumber);
    },
    updateRecords: function updateRecords() {
      this.$emit("updateRecords", "showRecords", this.showRecords);
    }
  },
  mounted: function mounted() {
    this.showRecords = this.perPage;
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Listing.vue?vue&type=template&id=2b75e2b7&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/Listing.vue?vue&type=template&id=2b75e2b7& ***!
  \*******************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "YK-productSection" },
    [
      _vm.products.data.length > 0
        ? _c(
            "div",
            {
              staticClass: "d-grid",
              attrs: {
                id: "collection-products",
                "data-view": _vm.listingGridView
              }
            },
            _vm._l(_vm.products.data, function(product, pKey) {
              return _c("product-card", {
                key: pKey,
                attrs: {
                  product: product,
                  imageTime: "",
                  aspectRatio: _vm.aspectRatio,
                  index: pKey
                }
              })
            }),
            1
          )
        : _vm._e(),
      _vm._v(" "),
      _vm.products.data.length > 0
        ? _c("section", { staticClass: "filter-bottom" }, [
            _c(
              "div",
              { staticClass: "row justify-content-between align-items-center" },
              [
                _c("div", { staticClass: "col-md-auto" }, [
                  _c("div", { staticClass: "showing-count" }, [
                    _vm._v(
                      _vm._s(_vm.$t("LBL_PAGINATION_SHOWING")) +
                        ":: " +
                        _vm._s(_vm.firstItem + " - " + _vm.lastItem) +
                        " " +
                        _vm._s(_vm.$t("LBL_PAGINATION_OF")) +
                        " " +
                        _vm._s(_vm.products.total)
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "col-md-auto" }, [
                  _c("div", { staticClass: "pagination-wrapper" }, [
                    _c(
                      "nav",
                      [
                        _c("products-pagination", {
                          attrs: { pagination: _vm.pagination },
                          on: { currentPage: _vm.currentPage }
                        })
                      ],
                      1
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "col-auto d-none d-md-block" }, [
                  _c("div", { staticClass: "sorting-filter" }, [
                    _c("div", { staticClass: "sorting-filter__item" }, [
                      _c("div", { staticClass: "select-dropdown" }, [
                        _c(
                          "a",
                          {
                            staticClass: "select-dropdown__current js-toggle",
                            attrs: { href: "#SORTBY-COUNT" }
                          },
                          [_vm._v(_vm._s(_vm.showRecords) + " Items")]
                        ),
                        _vm._v(" "),
                        _c(
                          "div",
                          {
                            staticClass: "select-dropdown__wrap",
                            attrs: { id: "SORTBY-COUNT" }
                          },
                          [
                            _c(
                              "ul",
                              { staticClass: "list list--vertical" },
                              _vm._l(_vm.perPageRecords, function(
                                perPageRecord,
                                index
                              ) {
                                return _c("li", { key: index }, [
                                  _c(
                                    "a",
                                    { attrs: { href: "javascript:void(0);" } },
                                    [_vm._v(_vm._s(perPageRecord) + " Items")]
                                  )
                                ])
                              }),
                              0
                            )
                          ]
                        )
                      ])
                    ])
                  ])
                ])
              ]
            )
          ])
        : _c("no-record-found", {
            attrs: { text1: _vm.$t("LBL_NO_PRODUCTS_FOUND"), size: "large" }
          })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Partials\\/Pagination$":
/*!*******************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Partials\/Pagination$ namespace object ***!
  \*******************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Partials/Pagination": [
		"./resources/js/frontend/Themes/base/Partials/Pagination.vue",
		134
	],
	"./fashion/Partials/Pagination": [
		"./resources/js/frontend/Themes/fashion/Partials/Pagination.vue",
		143
	]
};
function webpackAsyncContext(req) {
	if(!__webpack_require__.o(map, req)) {
		return Promise.resolve().then(function() {
			var e = new Error("Cannot find module '" + req + "'");
			e.code = 'MODULE_NOT_FOUND';
			throw e;
		});
	}

	var ids = map[req], id = ids[0];
	return __webpack_require__.e(ids[1]).then(function() {
		return __webpack_require__(id);
	});
}
webpackAsyncContext.keys = function webpackAsyncContextKeys() {
	return Object.keys(map);
};
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Partials\\/Pagination$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Partials\\/productCard$":
/*!********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Partials\/productCard$ namespace object ***!
  \********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Partials/productCard": [
		"./resources/js/frontend/Themes/base/Partials/productCard.vue",
		0,
		26
	],
	"./fashion/Partials/productCard": [
		"./resources/js/frontend/Themes/fashion/Partials/productCard.vue",
		0,
		30
	]
};
function webpackAsyncContext(req) {
	if(!__webpack_require__.o(map, req)) {
		return Promise.resolve().then(function() {
			var e = new Error("Cannot find module '" + req + "'");
			e.code = 'MODULE_NOT_FOUND';
			throw e;
		});
	}

	var ids = map[req], id = ids[0];
	return Promise.all(ids.slice(1).map(__webpack_require__.e)).then(function() {
		return __webpack_require__(id);
	});
}
webpackAsyncContext.keys = function webpackAsyncContextKeys() {
	return Object.keys(map);
};
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Partials\\/productCard$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Listing.vue":
/*!******************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Listing.vue ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Listing_vue_vue_type_template_id_2b75e2b7___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Listing.vue?vue&type=template&id=2b75e2b7& */ "./resources/js/frontend/Themes/fashion/Product/Listing.vue?vue&type=template&id=2b75e2b7&");
/* harmony import */ var _Listing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Listing.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/fashion/Product/Listing.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Listing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Listing_vue_vue_type_template_id_2b75e2b7___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Listing_vue_vue_type_template_id_2b75e2b7___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/fashion/Product/Listing.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Listing.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Listing.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Listing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Listing.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Listing.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Listing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Listing.vue?vue&type=template&id=2b75e2b7&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Listing.vue?vue&type=template&id=2b75e2b7& ***!
  \*************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Listing_vue_vue_type_template_id_2b75e2b7___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Listing.vue?vue&type=template&id=2b75e2b7& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Listing.vue?vue&type=template&id=2b75e2b7&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Listing_vue_vue_type_template_id_2b75e2b7___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Listing_vue_vue_type_template_id_2b75e2b7___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);