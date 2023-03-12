(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[54],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    RatingSummary: function RatingSummary() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/RatingSummary$")("./".concat(currentTheme, "/Product/Review/RatingSummary"));
    },
    RatingStars: function RatingStars() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RatingStars$")("./".concat(currentTheme, "/Product/RatingStars"));
    }
  },
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      page: 1,
      sort: 1,
      reviews: []
    };
  },
  props: ["avgRating", "oneRating", "twoRating", "threeRating", "fourRating", "fiveRating", "totalRating", "reviewCount"]
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue?vue&type=template&id=368f8a8f&":
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue?vue&type=template&id=368f8a8f& ***!
  \********************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "overall-rating" }, [
    _c("div", { staticClass: "overall-rating__left" }, [
      _c("div", { staticClass: "overall-rating__count" }, [
        _vm._v(_vm._s(Math.round(_vm.avgRating)) + ".0"),
        _c("span", [_vm._v("/5")])
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "rating-holder" },
        [_c("rating-stars", { attrs: { rating: Math.round(_vm.avgRating) } })],
        1
      ),
      _vm._v(" "),
      _vm.reviewCount != 0
        ? _c("p", [
            _c("strong", [
              _vm._v(
                _vm._s(
                  _vm.$t("LBL_BASED_ON") +
                    " " +
                    _vm.reviewCount +
                    " " +
                    _vm.$t("LBL_REVIEWS")
                ) + " "
              )
            ])
          ])
        : _vm._e()
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "index-rating-bar" }, [
      _c("ul", [
        _c("li", [
          _c("div", { staticClass: "index-rating" }, [
            _c("span", { staticClass: "index-ratinglevel" }, [_vm._v("5")]),
            _vm._v(" "),
            _c(
              "svg",
              { staticClass: "svg", attrs: { width: "12", height: "12" } },
              [
                _c("use", {
                  attrs: {
                    "xlink:href": _vm.baseUrl + "/media/retina/sprite.svg#star",
                    href: _vm.baseUrl + "/media/retina/sprite.svg#star"
                  }
                })
              ]
            )
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "progress" }, [
            _c("span", {
              style:
                "width: " +
                Math.round((_vm.fiveRating / _vm.totalRating) * 100) +
                "%;"
            })
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "index-count" }, [
            _vm._v(_vm._s(_vm.fiveRating))
          ])
        ]),
        _vm._v(" "),
        _c("li", [
          _c("div", { staticClass: "index-rating" }, [
            _c("span", { staticClass: "index-ratinglevel" }, [_vm._v("4")]),
            _vm._v(" "),
            _c(
              "svg",
              { staticClass: "svg", attrs: { width: "12", height: "12" } },
              [
                _c("use", {
                  attrs: {
                    "xlink:href": _vm.baseUrl + "/media/retina/sprite.svg#star",
                    href: _vm.baseUrl + "/media/retina/sprite.svg#star"
                  }
                })
              ]
            )
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "progress" }, [
            _c("span", {
              style:
                "width: " +
                Math.round((_vm.fourRating / _vm.totalRating) * 100) +
                "%;"
            })
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "index-count" }, [
            _vm._v(_vm._s(_vm.fourRating))
          ])
        ]),
        _vm._v(" "),
        _c("li", [
          _c("div", { staticClass: "index-rating" }, [
            _c("span", { staticClass: "index-ratinglevel" }, [_vm._v("3")]),
            _vm._v(" "),
            _c(
              "svg",
              { staticClass: "svg", attrs: { width: "12", height: "12" } },
              [
                _c("use", {
                  attrs: {
                    "xlink:href": _vm.baseUrl + "/media/retina/sprite.svg#star",
                    href: _vm.baseUrl + "/media/retina/sprite.svg#star"
                  }
                })
              ]
            )
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "progress" }, [
            _c("span", {
              style:
                "width: " +
                Math.round((_vm.threeRating / _vm.totalRating) * 100) +
                "%;"
            })
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "index-count" }, [
            _vm._v(_vm._s(_vm.threeRating))
          ])
        ]),
        _vm._v(" "),
        _c("li", [
          _c("div", { staticClass: "index-rating" }, [
            _c("span", { staticClass: "index-ratinglevel" }, [_vm._v("2")]),
            _vm._v(" "),
            _c(
              "svg",
              { staticClass: "svg", attrs: { width: "12", height: "12" } },
              [
                _c("use", {
                  attrs: {
                    "xlink:href": _vm.baseUrl + "/media/retina/sprite.svg#star",
                    href: _vm.baseUrl + "/media/retina/sprite.svg#star"
                  }
                })
              ]
            )
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "progress" }, [
            _c("span", {
              style:
                "width: " +
                Math.round((_vm.twoRating / _vm.totalRating) * 100) +
                "%;"
            })
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "index-count" }, [
            _vm._v(_vm._s(_vm.twoRating))
          ])
        ]),
        _vm._v(" "),
        _c("li", [
          _c("div", { staticClass: "index-rating" }, [
            _c("span", { staticClass: "index-ratinglevel" }, [_vm._v("1")]),
            _vm._v(" "),
            _c(
              "svg",
              { staticClass: "svg", attrs: { width: "12", height: "12" } },
              [
                _c("use", {
                  attrs: {
                    "xlink:href": _vm.baseUrl + "/media/retina/sprite.svg#star",
                    href: _vm.baseUrl + "/media/retina/sprite.svg#star"
                  }
                })
              ]
            )
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "progress" }, [
            _c("span", {
              style:
                "width: " +
                Math.round((_vm.oneRating / _vm.totalRating) * 100) +
                "%;"
            })
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "index-count" }, [
            _vm._v(_vm._s(_vm.oneRating))
          ])
        ])
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



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
		84
	],
	"./fashion/Product/RatingStars": [
		"./resources/js/frontend/Themes/fashion/Product/RatingStars.vue",
		91
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

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/RatingSummary$":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/Review\/RatingSummary$ namespace object ***!
  \*****************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/Review/RatingSummary": [
		"./resources/js/frontend/Themes/base/Product/Review/RatingSummary.vue",
		130
	],
	"./fashion/Product/Review/RatingSummary": [
		"./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue",
		133
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/RatingSummary$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue":
/*!*******************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _RatingSummary_vue_vue_type_template_id_368f8a8f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RatingSummary.vue?vue&type=template&id=368f8a8f& */ "./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue?vue&type=template&id=368f8a8f&");
/* harmony import */ var _RatingSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RatingSummary.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _RatingSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _RatingSummary_vue_vue_type_template_id_368f8a8f___WEBPACK_IMPORTED_MODULE_0__["render"],
  _RatingSummary_vue_vue_type_template_id_368f8a8f___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RatingSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./RatingSummary.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RatingSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue?vue&type=template&id=368f8a8f&":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue?vue&type=template&id=368f8a8f& ***!
  \**************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RatingSummary_vue_vue_type_template_id_368f8a8f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./RatingSummary.vue?vue&type=template&id=368f8a8f& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue?vue&type=template&id=368f8a8f&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RatingSummary_vue_vue_type_template_id_368f8a8f___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RatingSummary_vue_vue_type_template_id_368f8a8f___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);