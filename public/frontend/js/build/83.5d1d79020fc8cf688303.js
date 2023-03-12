(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[83],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/FilterTags.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/FilterTags.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ["appliedFilters", "filters", "brandFilters", "randomStr"],
  data: function data() {
    return {
      currencySymbol: window.currentCurrency.currency_symbol,
      options: {},
      filterView: false,
      optionsVal: []
    };
  },
  watch: {
    randomStr: function randomStr() {
      this.options = this.appliedFilters.options;
      this.optionsValues();
    }
  },
  methods: {
    filtersExists: function filtersExists() {
      this.filterView = false;

      if (this.appliedFilters.brands.length != 0 || this.appliedFilters.conditions.length != 0 || this.appliedFilters.price_range || this.optionsVal != 0) {
        this.filterView = true;
      }
    },
    getOptionName: function getOptionName(options, selectedOption) {
      var length = options.length;

      for (var i = 0; i < length; i++) {
        if (options[i].opn_id == selectedOption) return options[i].opn_value;
      }

      return false;
    },
    searchBrands: function searchBrands(selectedBrand, allBrands) {
      var length = allBrands.length;

      for (var i = 0; i < length; i++) {
        if (allBrands[i].value == selectedBrand) return allBrands[i].label;
      }

      return "";
    },
    optionsValues: function optionsValues() {
      var _this = this;

      this.optionsVal = [];
      var thisval = this;

      if (this.appliedFilters.options) {
        var options = JSON.parse(this.appliedFilters.options);
        Object.keys(options).forEach(function (key) {
          Object.keys(options[key]).forEach(function (keyVal) {
            thisval.optionsVal.push({
              id: options[key][keyVal],
              key: key,
              name: _this.getOptionName(thisval.filters.options[key].data, options[key][keyVal])
            });
          });
        });
        this.filtersExists();
      }
    }
  },
  mounted: function mounted() {
    this.options = this.appliedFilters.options;
    this.optionsValues();
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/FilterTags.vue?vue&type=template&id=bdeb080e&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/FilterTags.vue?vue&type=template&id=bdeb080e& ***!
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
  return _vm.filterView
    ? _c("div", [
        _c(
          "ul",
          { staticClass: "selected-filter" },
          [
            _vm._l(_vm.appliedFilters.brands, function(selectedBrand, bKey) {
              return _vm.appliedFilters.brands
                ? _c("li", { key: bKey }, [
                    _c("span", [
                      _vm._v(
                        _vm._s(
                          _vm.searchBrands(selectedBrand, _vm.brandFilters.list)
                        )
                      )
                    ]),
                    _vm._v(" "),
                    _c(
                      "a",
                      {
                        staticClass: "remove",
                        attrs: { href: "javascript:;" },
                        on: {
                          click: function($event) {
                            return _vm.$emit("updateRecords", "remove_tags", {
                              type: "brands",
                              key: bKey,
                              id: selectedBrand
                            })
                          }
                        }
                      },
                      [_c("i", { staticClass: "fas fa-times" })]
                    )
                  ])
                : _vm._e()
            }),
            _vm._v(" "),
            _vm._l(_vm.optionsVal, function(selectedOption, OKey) {
              return _c("li", { key: OKey }, [
                _c("span", [_vm._v(_vm._s(selectedOption.name))]),
                _vm._v(" "),
                _c(
                  "a",
                  {
                    staticClass: "remove",
                    attrs: { href: "javascript:;" },
                    on: {
                      click: function($event) {
                        return _vm.$emit("updateRecords", "remove_tags", {
                          type: "options",
                          key: selectedOption.key,
                          id: selectedOption.id
                        })
                      }
                    }
                  },
                  [_c("i", { staticClass: "fas fa-times" })]
                )
              ])
            }),
            _vm._v(" "),
            _vm._l(_vm.appliedFilters.conditions, function(
              selectedConditions,
              CKey
            ) {
              return _vm.appliedFilters.conditions
                ? _c("li", { key: CKey }, [
                    _c("span", [
                      _vm._v(
                        _vm._s(_vm.filters.allConditions[selectedConditions])
                      )
                    ]),
                    _vm._v(" "),
                    _c(
                      "a",
                      {
                        staticClass: "remove",
                        attrs: { href: "javascript:;" },
                        on: {
                          click: function($event) {
                            return _vm.$emit("updateRecords", "remove_tags", {
                              type: "conditions",
                              key: CKey,
                              id: selectedConditions
                            })
                          }
                        }
                      },
                      [_c("i", { staticClass: "fas fa-times" })]
                    )
                  ])
                : _vm._e()
            }),
            _vm._v(" "),
            _vm.appliedFilters.price_range
              ? _c("li", [
                  _c("span", [
                    _vm._v(
                      _vm._s(
                        _vm.currencySymbol +
                          "" +
                          _vm.appliedFilters.price_range[0] +
                          " - " +
                          _vm.currencySymbol +
                          "" +
                          _vm.appliedFilters.price_range[1]
                      )
                    )
                  ]),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "remove",
                      attrs: { href: "javascript:;" },
                      on: {
                        click: function($event) {
                          return _vm.$emit("updateRecords", "remove_tags", {
                            type: "price"
                          })
                        }
                      }
                    },
                    [_c("i", { staticClass: "fas fa-times" })]
                  )
                ])
              : _vm._e(),
            _vm._v(" "),
            _c("li", [
              _c(
                "a",
                {
                  staticClass: "link",
                  attrs: { href: "javascript:;" },
                  on: {
                    click: function($event) {
                      return _vm.$emit("updateRecords", "remove_tags", {
                        type: "clear_all"
                      })
                    }
                  }
                },
                [_vm._v(_vm._s(_vm.$t("LNK_CLEAR")))]
              )
            ])
          ],
          2
        )
      ])
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/FilterTags.vue":
/*!******************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/FilterTags.vue ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _FilterTags_vue_vue_type_template_id_bdeb080e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./FilterTags.vue?vue&type=template&id=bdeb080e& */ "./resources/js/frontend/Themes/base/Product/FilterTags.vue?vue&type=template&id=bdeb080e&");
/* harmony import */ var _FilterTags_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./FilterTags.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/base/Product/FilterTags.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _FilterTags_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _FilterTags_vue_vue_type_template_id_bdeb080e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _FilterTags_vue_vue_type_template_id_bdeb080e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/base/Product/FilterTags.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/FilterTags.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/FilterTags.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FilterTags_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./FilterTags.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/FilterTags.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FilterTags_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/FilterTags.vue?vue&type=template&id=bdeb080e&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/FilterTags.vue?vue&type=template&id=bdeb080e& ***!
  \*************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FilterTags_vue_vue_type_template_id_bdeb080e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./FilterTags.vue?vue&type=template&id=bdeb080e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/FilterTags.vue?vue&type=template&id=bdeb080e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FilterTags_vue_vue_type_template_id_bdeb080e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FilterTags_vue_vue_type_template_id_bdeb080e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);