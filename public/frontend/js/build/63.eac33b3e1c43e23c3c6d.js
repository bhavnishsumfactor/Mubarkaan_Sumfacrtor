(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[63],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/SocialShare.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/SocialShare.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      baseUrl: baseUrl
    };
  },
  props: ["sharethisNetworks", "productName"],
  methods: {
    getSocialType: function getSocialType(type) {
      return type.replace("SHARETHIS_", "").toLowerCase();
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/SocialShare.vue?vue&type=template&id=c4797c4c&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/SocialShare.vue?vue&type=template&id=c4797c4c& ***!
  \********************************************************************************************************************************************************************************************************************************/
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
        id: "socialShareModal",
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
                _vm._v(_vm._s(_vm.$t("LBL_SHARE")))
              ]),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "close",
                  attrs: { type: "button" },
                  on: {
                    click: function($event) {
                      return _vm.$bvModal.hide("socialShareModal")
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
      _c(
        "div",
        {
          staticClass:
            "socail-follow socail-follow-lg socail-follow--popup text-center"
        },
        _vm._l(_vm.sharethisNetworks, function(socialShare, socialKey) {
          return _c(
            "div",
            { key: socialKey },
            [
              _c(
                "ShareNetwork",
                {
                  directives: [
                    {
                      name: "b-tooltip",
                      rawName: "v-b-tooltip",
                      value: _vm.$camelCase(_vm.getSocialType(socialKey)),
                      expression: "$camelCase(getSocialType(socialKey))"
                    }
                  ],
                  attrs: {
                    network: _vm.getSocialType(socialKey),
                    url: _vm.uniqueUrl,
                    title: _vm.productName
                  }
                },
                [
                  _c("span", { staticClass: "icon" }, [
                    _c("i", { staticClass: "svg--icon" }, [
                      _c("svg", { staticClass: "svg" }, [
                        _c("use", {
                          attrs: {
                            "xlink:href":
                              _vm.baseUrl +
                              "/dashboard/media/retina/sprite.svg#share-" +
                              _vm.getSocialType(socialKey),
                            href:
                              _vm.baseUrl +
                              "/dashboard/media/retina/sprite.svg#share-" +
                              _vm.getSocialType(socialKey)
                          }
                        })
                      ])
                    ])
                  ])
                ]
              )
            ],
            1
          )
        }),
        0
      )
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/SocialShare.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/SocialShare.vue ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SocialShare_vue_vue_type_template_id_c4797c4c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SocialShare.vue?vue&type=template&id=c4797c4c& */ "./resources/js/frontend/Themes/base/Product/SocialShare.vue?vue&type=template&id=c4797c4c&");
/* harmony import */ var _SocialShare_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SocialShare.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/base/Product/SocialShare.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SocialShare_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SocialShare_vue_vue_type_template_id_c4797c4c___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SocialShare_vue_vue_type_template_id_c4797c4c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/base/Product/SocialShare.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/SocialShare.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/SocialShare.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialShare_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./SocialShare.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/SocialShare.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialShare_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/SocialShare.vue?vue&type=template&id=c4797c4c&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/SocialShare.vue?vue&type=template&id=c4797c4c& ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialShare_vue_vue_type_template_id_c4797c4c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./SocialShare.vue?vue&type=template&id=c4797c4c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/SocialShare.vue?vue&type=template&id=c4797c4c&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialShare_vue_vue_type_template_id_c4797c4c___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialShare_vue_vue_type_template_id_c4797c4c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);