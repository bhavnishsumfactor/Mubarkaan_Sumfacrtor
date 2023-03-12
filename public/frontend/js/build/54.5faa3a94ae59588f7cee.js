(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[54],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    RatingStars: function RatingStars() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RatingStars$")("./".concat(currentTheme, "/Product/RatingStars"));
    },
    AskQuestion: function AskQuestion() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/AskQuestion$")("./".concat(currentTheme, "/Product/AskQuestion"));
    },
    SocialShare: function SocialShare() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/SocialShare$")("./".concat(currentTheme, "/Product/SocialShare"));
    }
  },
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme
    };
  },
  props: ["product", "rating", "sharethisNetworks", "pageUrl"],
  methods: {
    askQuestions: function askQuestions(id, code) {
      var thisObj = this;
      code = code != '' ? code : 0;
      NProgress.start();
      $.get(baseUrl + '/product/' + id + '/' + code + '/ask-questions', function (response) {
        NProgress.done();

        if (response.status == true) {
          thisObj.$bvModal.show("askQuestionModal");
          setTimeout(function () {
            floatingFormFix();
          }, 200);
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=template&id=df19415e&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=template&id=df19415e& ***!
  \*************************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "product-detail js-add-to-cartloop" },
    [
      _c("div", { staticClass: "breadcrumb-wrapper" }, [
        _c(
          "ul",
          {
            staticClass: "breadcrumb",
            attrs: {
              "data-yk": "",
              role: "yk-navigation",
              "aria-label": "breadcrumbs"
            }
          },
          [
            _c("li", { staticClass: "breadcrumb-item " }, [
              _c(
                "a",
                {
                  attrs: { href: _vm.baseUrl, title: "Back to the home page" }
                },
                [_vm._v(_vm._s(_vm.$t("LNK_HOME")))]
              )
            ]),
            _vm._v(" "),
            _vm.product.cat_name
              ? _c("li", { staticClass: "breadcrumb-item" }, [
                  _c("a", { attrs: { href: _vm.product.category_url } }, [
                    _vm._v(_vm._s(_vm.product.cat_name))
                  ])
                ])
              : _vm._e(),
            _vm._v(" "),
            _c(
              "li",
              {
                staticClass: "breadcrumb-item",
                attrs: { title: _vm.product.prod_name }
              },
              [_vm._v(_vm._s(_vm.product.short_prod_name))]
            )
          ]
        )
      ]),
      _vm._v(" "),
      _vm.$configVal("PRODUCT_DETAIL_DISPLAY_BRAND") != 0 &&
      _vm.product.brand_name != ""
        ? _c("div", { staticClass: "product-detail__brand" }, [
            _c("a", { attrs: { href: _vm.product.brand_url } }, [
              _vm._v(_vm._s(_vm.product.brand_name))
            ])
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("h1", { staticClass: "product-detail__title" }, [
        _vm._v(_vm._s(_vm.product.prod_name))
      ]),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass:
            "row justify-content-between no-gutters product-detail__row"
        },
        [
          _c("div", { staticClass: "col-auto" }, [
            _c("div", { staticClass: "d-xl-flex align-items-center py-2" }, [
              _c("div", { staticClass: "product-detail__rating" }, [
                _c(
                  "div",
                  { staticClass: "rating-holder" },
                  [
                    _c("rating-stars", { attrs: { rating: _vm.rating } }),
                    _vm._v(" "),
                    _vm.rating != 0
                      ? _c("p", [
                          _c("a", { attrs: { href: "#reviews" } }, [
                            _vm._v("(" + _vm._s(_vm.rating) + " out of 5)")
                          ])
                        ])
                      : _vm._e()
                  ],
                  1
                )
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "product-detail__meta" }, [
                _vm.product.sku &&
                _vm.$configVal("PRODUCT_DETAIL_DISPLAY_SKU") != 0
                  ? _c("span", { staticClass: "product-sku" }, [
                      _vm._v(
                        "\n                            " +
                          _vm._s(_vm.$t("LBL_SKU")) +
                          " : " +
                          _vm._s(_vm.product.sku)
                      )
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _vm.product.prod_model &&
                _vm.$configVal("PRODUCT_DETAIL_DISPLAY_MODEL") != 0
                  ? _c("span", { staticClass: "product-model" }, [
                      _vm._v(
                        "\n                            " +
                          _vm._s(_vm.$t("LBL_MODEL")) +
                          " : " +
                          _vm._s(_vm.product.prod_model)
                      )
                    ])
                  : _vm._e()
              ])
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col-auto" }, [
            _c("div", { staticClass: "d-flex align-items-center py-2" }, [
              _c(
                "a",
                {
                  staticClass: "askquestion",
                  attrs: { href: "javascript:;" },
                  on: {
                    click: function($event) {
                      return _vm.askQuestions(
                        _vm.product.prod_id,
                        _vm.product.pov_code
                      )
                    }
                  }
                },
                [
                  _c("span", { staticClass: "askquestion__icon" }, [
                    _c(
                      "svg",
                      {
                        attrs: {
                          xmlns: "http://www.w3.org/2000/svg",
                          width: "24",
                          height: "24",
                          viewBox: "0 0 24 24"
                        }
                      },
                      [
                        _c("path", {
                          attrs: {
                            d:
                              "M2559.65,159.894h-14.4a1.805,1.805,0,0,0-1.8,1.8v16.2l3.6-3.6h12.6a1.805,1.805,0,0,0,1.8-1.8v-10.8A1.805,1.805,0,0,0,2559.65,159.894Zm-7.2,11.7h-6.3v-1.8h6.3Zm6.3-3.6h-12.6v-1.8h12.6Zm0-3.6h-12.6v-1.8h12.6Z",
                            transform: "translate(-2540.45 -156.894)"
                          }
                        })
                      ]
                    )
                  ]),
                  _vm._v(" "),
                  _c("span", { staticClass: "askquestion__lbl" }, [
                    _vm._v(_vm._s(_vm.$t("LBL_ASK_A_QUESTION")))
                  ])
                ]
              ),
              _vm._v(" "),
              _c(
                "a",
                {
                  staticClass: "product-share",
                  attrs: { href: "javascript:;" },
                  on: {
                    click: function($event) {
                      return _vm.$bvModal.show("socialShareModal")
                    }
                  }
                },
                [
                  _c("span", { staticClass: "product-share__icon" }, [
                    _c(
                      "svg",
                      {
                        attrs: {
                          xmlns: "http://www.w3.org/2000/svg",
                          width: "24",
                          height: "24",
                          viewBox: "0 0 24 24"
                        }
                      },
                      [
                        _c("g", { attrs: { transform: "translate(0.9 1)" } }, [
                          _c("circle", {
                            attrs: {
                              cx: "3",
                              cy: "3",
                              r: "3",
                              transform: "translate(11.2 2)"
                            }
                          }),
                          _vm._v(" "),
                          _c("circle", {
                            attrs: {
                              cx: "3",
                              cy: "3",
                              r: "3",
                              transform: "translate(11.2 12)"
                            }
                          }),
                          _vm._v(" "),
                          _c("circle", {
                            attrs: {
                              cx: "3",
                              cy: "3",
                              r: "3",
                              transform: "translate(3 7)"
                            }
                          }),
                          _vm._v(" "),
                          _c("path", {
                            attrs: {
                              fill: "none",
                              "stroke-width": "2px",
                              stroke: "currentColor",
                              d: "M20857.2-974l-8.2,5,8.2,5",
                              transform: "translate(-20842 979)"
                            }
                          })
                        ])
                      ]
                    )
                  ]),
                  _vm._v(" "),
                  _c("span", { staticClass: "product-share__lbl" }, [
                    _vm._v(_vm._s(_vm.$t("LBL_SHARE")))
                  ])
                ]
              )
            ])
          ])
        ]
      ),
      _vm._v(" "),
      _c("div", { staticClass: "divider" }),
      _vm._v(" "),
      _c("ask-question", {
        attrs: {
          productId: _vm.product.prod_id,
          varientCode: _vm.product.pov_code
        }
      }),
      _vm._v(" "),
      _c("social-share", {
        attrs: {
          sharethisNetworks: _vm.sharethisNetworks,
          productName: _vm.product.prod_name,
          pageUrl: _vm.pageUrl
        }
      })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/AskQuestion$":
/*!*******************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/AskQuestion$ namespace object ***!
  \*******************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/AskQuestion": [
		"./resources/js/frontend/Themes/base/Product/AskQuestion.vue",
		61
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/AskQuestion$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RatingStars$":
/*!*******************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/RatingStars$ namespace object ***!
  \*******************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/RatingStars": [
		"./resources/js/frontend/Themes/base/Product/RatingStars.vue",
		60
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RatingStars$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/SocialShare$":
/*!*******************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/SocialShare$ namespace object ***!
  \*******************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/SocialShare": [
		"./resources/js/frontend/Themes/base/Product/SocialShare.vue",
		63
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/SocialShare$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/AddToCartSection.vue":
/*!************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/AddToCartSection.vue ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AddToCartSection_vue_vue_type_template_id_df19415e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AddToCartSection.vue?vue&type=template&id=df19415e& */ "./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=template&id=df19415e&");
/* harmony import */ var _AddToCartSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AddToCartSection.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AddToCartSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AddToCartSection_vue_vue_type_template_id_df19415e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AddToCartSection_vue_vue_type_template_id_df19415e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/base/Product/AddToCartSection.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AddToCartSection.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=template&id=df19415e&":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=template&id=df19415e& ***!
  \*******************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_template_id_df19415e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AddToCartSection.vue?vue&type=template&id=df19415e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=template&id=df19415e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_template_id_df19415e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_template_id_df19415e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);