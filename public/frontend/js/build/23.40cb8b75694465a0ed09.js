(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[23],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/Index.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/Index.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
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
    RecentlyViewed: function RecentlyViewed() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RecentlyViewed$")("./".concat(currentTheme, "/Product/RecentlyViewed"));
    },
    FilterSkeleton: function FilterSkeleton() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/FilterSkeleton$")("./".concat(currentTheme, "/Product/FilterSkeleton"));
    },
    Listing: function Listing() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Listing$")("./".concat(currentTheme, "/Product/Listing"));
    }
  },
  props: ["products", "catIds", "recentViewProducts", "aspectRatio"],
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      listingGridView: 4
    };
  },
  methods: {
    changeProductView: function changeProductView(gridType) {}
  },
  mounted: function mounted() {
    this.listingGridView = this.$configVal('LISTING_GRID_DEFAULT');
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/Index.vue?vue&type=template&id=0cbb723a&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/Index.vue?vue&type=template&id=0cbb723a& ***!
  \**************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "body", attrs: { id: "body", "data-yk": "" } },
    [
      _c("section", { staticClass: "section listing-page" }, [
        _c("div", { staticClass: "container" }, [
          _c(
            "div",
            {
              staticClass: "collection-top-bar",
              class: [_vm.products.data.length == 0 ? "no-data" : ""]
            },
            [
              _c("div", { staticClass: "collection-options" }, [
                _vm.products.data.length > 0
                  ? _c(
                      "div",
                      { staticClass: "collection-view d-none d-xl-block" },
                      [
                        _c(
                          "div",
                          {
                            staticClass: "wc-col-switch row align-items-center"
                          },
                          [
                            _c(
                              "div",
                              {
                                staticClass: "col-auto",
                                attrs: { id: "collection-view" }
                              },
                              [
                                _c("a", {
                                  staticClass:
                                    "four d-none d-md-block YK-listview",
                                  class: [
                                    _vm.listingGridView == 4 ? "active" : ""
                                  ],
                                  attrs: {
                                    href: "javascript: void(0)",
                                    "data-num": "4",
                                    "data-col": "3"
                                  },
                                  on: {
                                    click: function($event) {
                                      return _vm.changeProductView(4)
                                    }
                                  }
                                }),
                                _vm._v(" "),
                                _c("a", {
                                  staticClass:
                                    "five d-none d-md-block YK-listview",
                                  class: [
                                    _vm.listingGridView == 5 ? "active" : ""
                                  ],
                                  attrs: {
                                    href: "javascript: void(0)",
                                    "data-num": "5",
                                    "data-col": "4"
                                  },
                                  on: {
                                    click: function($event) {
                                      return _vm.changeProductView(5)
                                    }
                                  }
                                })
                              ]
                            )
                          ]
                        )
                      ]
                    )
                  : _vm._e(),
                _vm._v(" "),
                _vm.products.data.length > 0
                  ? _c("div", { staticClass: "collection-sort" }, [
                      _c("div", { staticClass: "row align-items-center" }, [
                        _c("div", { staticClass: "col-auto" }, [
                          _c(
                            "select",
                            {
                              staticClass:
                                "filter-select custom-select select-fancyx",
                              attrs: {
                                name: "sortBy",
                                onchange: "filterProducts()"
                              }
                            },
                            [
                              _c("option", { attrs: { value: "new" } }, [
                                _vm._v(_vm._s(_vm.$t("LNK_FILTER_WHATS_NEW")))
                              ]),
                              _vm._v(" "),
                              _c("option", { attrs: { value: "popularity" } }, [
                                _vm._v(
                                  _vm._s(_vm.$t("LNK_FILTER_BEST_SELLING"))
                                )
                              ]),
                              _vm._v(" "),
                              _c("option", { attrs: { value: "rating" } }, [
                                _vm._v(_vm._s(_vm.$t("LNK_FILTER_BEST_RATED")))
                              ]),
                              _vm._v(" "),
                              _c("option", { attrs: { value: "discounted" } }, [
                                _vm._v(
                                  _vm._s(_vm.$t("LNK_FILTER_MOST_DISCOUNTED"))
                                )
                              ]),
                              _vm._v(" "),
                              _c("option", { attrs: { value: "priceDesc" } }, [
                                _vm._v(
                                  _vm._s(_vm.$t("LNK_FILTER_PRICE_HIGH_TO_LOW"))
                                )
                              ]),
                              _vm._v(" "),
                              _c("option", { attrs: { value: "priceAsc" } }, [
                                _vm._v(
                                  _vm._s(_vm.$t("LNK_FILTER_PRICE_LOW_TO_HIGH"))
                                )
                              ]),
                              _vm._v(" "),
                              _c(
                                "option",
                                { attrs: { value: "alphabetically" } },
                                [_vm._v(_vm._s(_vm.$t("LNK_FILTER_A_TO_Z")))]
                              )
                            ]
                          )
                        ])
                      ])
                    ])
                  : _vm._e()
              ])
            ]
          ),
          _vm._v(" "),
          _vm.products.data.length > 0
            ? _c("div", { staticClass: "collection-listing" }, [
                _c(
                  "aside",
                  {
                    staticClass: "collection-sidebar YK-sidebar",
                    attrs: {
                      id: "collection-sidebar",
                      "data-close-on-click-outside": "collection-sidebar"
                    }
                  },
                  [_c("filter-skeleton")],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  {
                    staticClass: "collection-content YK-listingRightContainer"
                  },
                  [
                    _c("listing", {
                      attrs: {
                        products: _vm.products,
                        aspectRatio: _vm.aspectRatio,
                        listingGridView: _vm.listingGridView
                      }
                    })
                  ],
                  1
                )
              ])
            : _c("div", { staticClass: "collection-listing" })
        ])
      ]),
      _vm._v(" "),
      _c("recently-viewed", {
        attrs: {
          recentViewProducts: _vm.recentViewProducts,
          aspectRatio: _vm.aspectRatio
        }
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

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/FilterSkeleton$":
/*!**********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/FilterSkeleton$ namespace object ***!
  \**********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/FilterSkeleton": [
		"./resources/js/frontend/Themes/base/Product/FilterSkeleton.vue",
		55
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/FilterSkeleton$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Listing$":
/*!***************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/Listing$ namespace object ***!
  \***************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/Listing": [
		"./resources/js/frontend/Themes/base/Product/Listing.vue",
		1,
		53
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Listing$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RecentlyViewed$":
/*!**********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/RecentlyViewed$ namespace object ***!
  \**********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/RecentlyViewed": [
		"./resources/js/frontend/Themes/base/Product/RecentlyViewed.vue",
		6,
		1,
		56
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RecentlyViewed$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/Index.vue":
/*!*************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/Index.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Index_vue_vue_type_template_id_0cbb723a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=0cbb723a& */ "./resources/js/frontend/Themes/base/Product/Index.vue?vue&type=template&id=0cbb723a&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/base/Product/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Index_vue_vue_type_template_id_0cbb723a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Index_vue_vue_type_template_id_0cbb723a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/base/Product/Index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/Index.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/Index.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/Index.vue?vue&type=template&id=0cbb723a&":
/*!********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/Index.vue?vue&type=template&id=0cbb723a& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_0cbb723a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=template&id=0cbb723a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/Index.vue?vue&type=template&id=0cbb723a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_0cbb723a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_0cbb723a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);