(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[26],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ["filters", "appliedFilters"],
  data: function data() {
    return {
      records: {},
      selectedFilter: [],
      userData: {
        price_range: "",
        brands: [],
        option: {},
        options: '',
        range: [0, 0],
        conditions: []
      },
      colors: {}
    };
  },
  watch: {
    filters: function filters() {
      if (this.filters.length != 0) {
        this.sortFilteData();
      }
    }
  },
  methods: {
    range: function range(start, stop) {
      var result = ["1"];

      for (var x = start.charCodeAt(0), end = stop.charCodeAt(0); x <= end; ++x) {
        result.push(String.fromCharCode(x));
      }

      return result;
    },
    sortFilteData: function sortFilteData() {
      var filters = this.filters.data;
      this.colors = this.filters.colors;
      var number = "";
      var records = {};
      var x = 0;
      Object.keys(filters).forEach(function (key) {
        number = key.toLowerCase().substring(0, 1);

        if (Number.isInteger(number)) {
          if (typeof records["#"] == "undefined") {
            records["#"] = {};
          }

          records["#"][x] = {
            label: key,
            value: filters[key]
          };
        } else {
          if (typeof records[number] == "undefined") {
            records[number] = {};
          }

          records[number][x] = {
            label: key,
            value: filters[key]
          };
        }

        x++;
      });
      this.records = records;
      this.setFilersData();
    },
    updateRecords: function updateRecords() {
      if (this.filters.seachType.type == "brands") {
        this.userData.brands = this.selectedFilter;
      } else if (this.filters.seachType.type == "options") {
        this.userData.option[this.filters.seachType.searched_id] = this.selectedFilter;
      }

      if (Object.keys(this.userData.option).length != 0) {
        this.userData.options = JSON.stringify(this.userData.option);
      }

      this.$emit("updateRecords", "load_more_filters", this.userData);
    },
    setFilersData: function setFilersData() {
      if (this.filters.seachType.type == "brands") {
        this.selectedFilter = this.appliedFilters.brands;
      } else if (this.filters.seachType.type == "options") {
        if (this.appliedFilters.options) {
          var options = JSON.parse(this.appliedFilters.options);
          this.selectedFilter = options[this.filters.seachType.searched_id];
        }
      }
    }
  },
  mounted: function mounted() {
    $(document).on("mouseenter", ".bfilter-js li", function (e) {
      e.preventDefault();
      $(".brandList-js").addClass("filter-directory_disabled");
      $(".filter-directory_list_title").addClass("filter-directory_disabled");
      $(".b-" + $(this).attr("data-item").toLowerCase()).removeClass("filter-directory_disabled");
      var lbl = $(this).attr("data-item");
      var txt = "";
      $(".filter-directory_list_title").each(function () {
        txt = $(this).attr("data-item").trim().toUpperCase();

        if (txt == lbl.toUpperCase()) {
          $(this).removeClass("filter-directory_disabled");
        }
      });

      if (typeof $(".filter-directory_list_title:not(.filter-directory_disabled)").position() != "undefined") {
        $("#YKbrandfiltersListing").stop().animate({
          scrollLeft: 0
        }, 0);
        var offset = $(".filter-directory_list_title:not(.filter-directory_disabled)").position().left;
        $("#YKbrandfiltersListing").stop().animate({
          scrollLeft: offset - 20
        }, 0);
      }
    });
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue?vue&type=template&id=68d01cbe&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue?vue&type=template&id=68d01cbe& ***!
  \****************************************************************************************************************************************************************************************************************************************/
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
        id: "loadFilterModel",
        size: "lg",
        centered: "",
        title: "BootstrapVue",
        "hide-footer": ""
      },
      scopedSlots: _vm._u([
        {
          key: "modal-header",
          fn: function() {
            return [
              _c("h5", { staticClass: "modal-title" }, [
                _vm._v(_vm._s(_vm.filters.seachType.searchName))
              ]),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "close",
                  attrs: { type: "button" },
                  on: {
                    click: function($event) {
                      return _vm.$bvModal.hide("loadFilterModel")
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
      _c("div", { staticClass: "modal-body" }, [
        _c("div", { staticClass: "filter-directory" }, [
          _c("div", { staticClass: "filter-directory_bar" }, [
            _c("input", {
              staticClass: "form-control filter-directory_search_input",
              attrs: {
                type: "text",
                placeholder: "Search",
                onkeyup: "autoKeywordSearch(this.value)"
              }
            }),
            _vm._v(" "),
            _c(
              "ul",
              { staticClass: "filter-directory_indices bfilter-js" },
              _vm._l(_vm.range("A", "Z"), function(element, index) {
                return _c(
                  "li",
                  {
                    key: index,
                    class: [
                      _vm.records[element.toLowerCase()]
                        ? ""
                        : "filter-directory_disabled"
                    ],
                    attrs: { "data-item": element == "1" ? "Empty" : element }
                  },
                  [
                    _c(
                      "a",
                      {
                        attrs: {
                          href:
                            element == "1"
                              ? "#Empty"
                              : "#" + element.toLowerCase()
                        }
                      },
                      [_vm._v(_vm._s(element == "1" ? "#" : element))]
                    )
                  ]
                )
              }),
              0
            )
          ])
        ]),
        _vm._v(" "),
        _c("form", { attrs: { id: "YK-morefilters" } }, [
          _c(
            "ul",
            {
              staticClass: "filter-directory_list",
              class: [Object.keys(_vm.colors).length != 0 ? "list-colors" : ""],
              attrs: { id: "YKbrandfiltersListing" }
            },
            _vm._l(_vm.records, function(record, key) {
              return _c(
                "li",
                {
                  key: key,
                  staticClass: "filter-directory_list_title",
                  class: key == "1" ? "#" : key,
                  attrs: {
                    "data-item": key == "1" ? "#" : key,
                    id: key == "1" ? "#" : key
                  }
                },
                [
                  _vm._v(
                    "\n          " +
                      _vm._s(key == "1" ? "#" : key) +
                      "\n          "
                  ),
                  _c(
                    "ul",
                    _vm._l(record, function(data, dkey) {
                      return _c(
                        "li",
                        {
                          key: dkey,
                          staticClass: "brandList-js",
                          class: [key == "1" ? "b-empty" : "b-" + key],
                          attrs: {
                            "data-caption": [key == "1" ? "empty" : key]
                          }
                        },
                        [
                          _c("label", { staticClass: "checkbox" }, [
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.selectedFilter,
                                  expression: "selectedFilter"
                                }
                              ],
                              attrs: { type: "checkbox" },
                              domProps: {
                                value: data["value"],
                                checked: Array.isArray(_vm.selectedFilter)
                                  ? _vm._i(_vm.selectedFilter, data["value"]) >
                                    -1
                                  : _vm.selectedFilter
                              },
                              on: {
                                change: [
                                  function($event) {
                                    var $$a = _vm.selectedFilter,
                                      $$el = $event.target,
                                      $$c = $$el.checked ? true : false
                                    if (Array.isArray($$a)) {
                                      var $$v = data["value"],
                                        $$i = _vm._i($$a, $$v)
                                      if ($$el.checked) {
                                        $$i < 0 &&
                                          (_vm.selectedFilter = $$a.concat([
                                            $$v
                                          ]))
                                      } else {
                                        $$i > -1 &&
                                          (_vm.selectedFilter = $$a
                                            .slice(0, $$i)
                                            .concat($$a.slice($$i + 1)))
                                      }
                                    } else {
                                      _vm.selectedFilter = $$c
                                    }
                                  },
                                  function($event) {
                                    return _vm.updateRecords()
                                  }
                                ]
                              }
                            }),
                            _vm._v(" "),
                            Object.keys(_vm.colors).length != 0
                              ? _c("span", {
                                  staticClass: "color-display",
                                  style:
                                    "background-color:" +
                                    (_vm.colors[data["label"]] &&
                                    _vm.colors[data["label"]] != "null"
                                      ? _vm.colors[data["label"]]
                                      : data["label"])
                                })
                              : _vm._e(),
                            _vm._v(" "),
                            _c("span", { staticClass: "lb-txt" }, [
                              _vm._v(_vm._s(data["label"]))
                            ])
                          ])
                        ]
                      )
                    }),
                    0
                  )
                ]
              )
            }),
            0
          )
        ])
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue":
/*!***************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue ***!
  \***************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _LoadMoreFilterPopup_vue_vue_type_template_id_68d01cbe___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LoadMoreFilterPopup.vue?vue&type=template&id=68d01cbe& */ "./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue?vue&type=template&id=68d01cbe&");
/* harmony import */ var _LoadMoreFilterPopup_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LoadMoreFilterPopup.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _LoadMoreFilterPopup_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _LoadMoreFilterPopup_vue_vue_type_template_id_68d01cbe___WEBPACK_IMPORTED_MODULE_0__["render"],
  _LoadMoreFilterPopup_vue_vue_type_template_id_68d01cbe___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadMoreFilterPopup_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./LoadMoreFilterPopup.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadMoreFilterPopup_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue?vue&type=template&id=68d01cbe&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue?vue&type=template&id=68d01cbe& ***!
  \**********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadMoreFilterPopup_vue_vue_type_template_id_68d01cbe___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./LoadMoreFilterPopup.vue?vue&type=template&id=68d01cbe& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue?vue&type=template&id=68d01cbe&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadMoreFilterPopup_vue_vue_type_template_id_68d01cbe___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadMoreFilterPopup_vue_vue_type_template_id_68d01cbe___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);