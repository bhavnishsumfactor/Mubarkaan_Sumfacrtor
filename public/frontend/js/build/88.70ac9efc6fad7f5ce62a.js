(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[88],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ["product", "additionalInfoExist", "specifications"]
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue?vue&type=template&id=3dc14992&":
/*!**************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue?vue&type=template&id=3dc14992& ***!
  \**************************************************************************************************************************************************************************************************************************************/
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
        id: "additionalInfo",
        centered: "",
        title: "BootstrapVue",
        "hide-footer": ""
      },
      scopedSlots: _vm._u([
        {
          key: "modal-header",
          fn: function() {
            return [
              _c("h4", { staticClass: "modal-title" }, [
                _vm._v(_vm._s(_vm.$t("LBL_PRODUCT_ADDITIONAL_INFORMATION")))
              ]),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "close",
                  attrs: { type: "button" },
                  on: {
                    click: function($event) {
                      return _vm.$bvModal.hide("additionalInfo")
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
      _vm.additionalInfoExist === true
        ? _c("section", { staticClass: "section pt-0" }, [
            _c("div", { staticClass: "container container--md" }, [
              _c("div", { staticClass: "yk-expandable" }, [
                _c(
                  "div",
                  {
                    staticClass: "expandable-content Yk-expandable-section",
                    attrs: { "aria-expanded": "false" }
                  },
                  [
                    _c(
                      "div",
                      { staticClass: "rte text--pull" },
                      [
                        _vm.additionalInfoExist === true
                          ? _c("div", { staticClass: "cms" }, [
                              _c("table", { staticClass: "cms-table" }, [
                                _c("tbody", [
                                  _vm.$isNotNull(_vm.product.pc_isbn)
                                    ? _c("tr", [
                                        _c("th", { attrs: { width: "40%" } }, [
                                          _vm._v(_vm._s(_vm.$t("LBL_ISBN")))
                                        ]),
                                        _vm._v(" "),
                                        _c("td", [
                                          _vm._v(
                                            _vm._s(
                                              _vm.product.pc_isbn != "null"
                                                ? _vm.product.pc_isbn
                                                : ""
                                            )
                                          )
                                        ])
                                      ])
                                    : _vm._e(),
                                  _vm._v(" "),
                                  _vm.$isNotNull(_vm.product.pc_hsn)
                                    ? _c("tr", [
                                        _c("th", { attrs: { width: "40%" } }, [
                                          _vm._v(_vm._s(_vm.$t("LBL_HSN")))
                                        ]),
                                        _vm._v(" "),
                                        _c("td", [
                                          _vm._v(
                                            _vm._s(
                                              _vm.product.pc_hsn != "null"
                                                ? _vm.product.pc_hsn
                                                : ""
                                            )
                                          )
                                        ])
                                      ])
                                    : _vm._e(),
                                  _vm._v(" "),
                                  _vm.$isNotNull(_vm.product.pc_sac)
                                    ? _c("tr", [
                                        _c("th", { attrs: { width: "40%" } }, [
                                          _vm._v(_vm._s(_vm.$t("LBL_SAC")))
                                        ]),
                                        _vm._v(" "),
                                        _c("td", [
                                          _vm._v(
                                            _vm._s(
                                              _vm.product.pc_sac != "null"
                                                ? _vm.product.pc_sac
                                                : ""
                                            )
                                          )
                                        ])
                                      ])
                                    : _vm._e(),
                                  _vm._v(" "),
                                  _vm.$isNotNull(_vm.product.pc_upc)
                                    ? _c("tr", [
                                        _c("th", { attrs: { width: "40%" } }, [
                                          _vm._v(_vm._s(_vm.$t("LBL_UPC")))
                                        ]),
                                        _vm._v(" "),
                                        _c("td", [
                                          _vm._v(
                                            _vm._s(
                                              _vm.product.pc_upc != "null"
                                                ? _vm.product.pc_upc
                                                : ""
                                            )
                                          )
                                        ])
                                      ])
                                    : _vm._e()
                                ])
                              ])
                            ])
                          : _vm._e(),
                        _vm._v(" "),
                        _vm._l(_vm.specifications, function(
                          specification,
                          title
                        ) {
                          return _c("div", { key: title, staticClass: "cms" }, [
                            title
                              ? _c("h6", { staticClass: "mb-0" }, [
                                  _vm._v(_vm._s(_vm.$camelCase(title)))
                                ])
                              : _vm._e(),
                            _vm._v(" "),
                            _c("table", { staticClass: "cms-table" }, [
                              _c(
                                "tbody",
                                _vm._l(specification.value, function(
                                  value,
                                  index
                                ) {
                                  return _c("tr", { key: index }, [
                                    _c("th", { attrs: { width: "40%" } }, [
                                      _vm._v(_vm._s(specification.key[index]))
                                    ]),
                                    _vm._v(" "),
                                    _c("td", [_vm._v(_vm._s(value))])
                                  ])
                                }),
                                0
                              )
                            ])
                          ])
                        })
                      ],
                      2
                    )
                  ]
                )
              ])
            ])
          ])
        : _vm._e()
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue":
/*!*************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AdditionalInfo_vue_vue_type_template_id_3dc14992___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AdditionalInfo.vue?vue&type=template&id=3dc14992& */ "./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue?vue&type=template&id=3dc14992&");
/* harmony import */ var _AdditionalInfo_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AdditionalInfo.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AdditionalInfo_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AdditionalInfo_vue_vue_type_template_id_3dc14992___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AdditionalInfo_vue_vue_type_template_id_3dc14992___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdditionalInfo_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AdditionalInfo.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdditionalInfo_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue?vue&type=template&id=3dc14992&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue?vue&type=template&id=3dc14992& ***!
  \********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AdditionalInfo_vue_vue_type_template_id_3dc14992___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AdditionalInfo.vue?vue&type=template&id=3dc14992& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue?vue&type=template&id=3dc14992&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AdditionalInfo_vue_vue_type_template_id_3dc14992___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AdditionalInfo_vue_vue_type_template_id_3dc14992___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);