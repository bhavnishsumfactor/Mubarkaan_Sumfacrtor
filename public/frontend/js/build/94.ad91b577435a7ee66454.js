(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[94],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Categories.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Categories.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_masonry_wall__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-masonry-wall */ "./node_modules/vue-masonry-wall/dist/vue-masonry-wall.esm.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    VueMasonryWall: vue_masonry_wall__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: ["categories"],
  data: function data() {
    return {
      baseUrl: baseUrl
    };
  },
  computed: {
    items: function items() {
      var items = [];

      for (var i = 0; i < this.categories.length; i++) {
        items.push({
          key: "".concat(i),
          cat_name: "".concat(this.categories[i].cat_name),
          cat_image: "".concat(this.getCategoryImage(this.categories[i])),
          cat_url: "".concat(this.$generateUrl(this.categories[i].url_rewrite))
        });
      }

      return items;
    }
  },
  methods: {
    getCategoryImage: function getCategoryImage(category) {
      var image = this.$noImage('4_1/480x120.png');

      if (category.upload_images != null) {
        image = this.$getUrlById(category.upload_images.afile_id, '480-120', category.upload_images.afile_updated_at);
      }

      return image;
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Categories.vue?vue&type=template&id=674b50cb&":
/*!***********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Categories.vue?vue&type=template&id=674b50cb& ***!
  \***********************************************************************************************************************************************************************************************************/
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
      _c("section", { staticClass: "bg-gray" }, [
        _c("div", { staticClass: "container" }, [
          _c(
            "nav",
            {
              staticClass: "breadcrumb",
              attrs: { "data-yk": "", "aria-label": "breadcrumbs" }
            },
            [
              _c("li", { staticClass: "breadcrumb-item " }, [
                _c(
                  "a",
                  {
                    attrs: { href: _vm.baseUrl, title: "Back to the frontpage" }
                  },
                  [_vm._v(_vm._s(_vm.$t("LNK_HOME")))]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "breadcrumb-item " }, [
                _c("a", { attrs: { href: "javascipt:;" } }, [
                  _vm._v(_vm._s(_vm.$t("LNK_CATEGORIES")))
                ])
              ])
            ]
          )
        ])
      ]),
      _vm._v(" "),
      _c("section", { staticClass: "section" }, [
        _c("div", { staticClass: "container" }, [
          _c("div", { staticClass: "section-content section-content-center" }, [
            _c("h2", [_vm._v(_vm._s(_vm.$t("LBL_ALL_CATEGORIES")))])
          ]),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "masonry" },
            [
              _c("vue-masonry-wall", {
                attrs: { items: _vm.items, options: { width: 400 } },
                scopedSlots: _vm._u([
                  {
                    key: "default",
                    fn: function(ref) {
                      var item = ref.item
                      return [
                        _c("div", { staticClass: "masonry-content" }, [
                          _c("div", { staticClass: "categories-thumb" }, [
                            _c("a", { attrs: { href: item.cat_url } }, [
                              _c(
                                "div",
                                {
                                  staticClass: "aspect-ratio",
                                  staticStyle: { "padding-bottom": "45%" }
                                },
                                [
                                  _c("div", {
                                    staticClass: "categories-thumb-bg",
                                    style:
                                      "background-image: url(" +
                                      item.cat_image +
                                      ");"
                                  })
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "h6",
                                { staticClass: "categories-thumb-heading" },
                                [_vm._v(_vm._s(item.cat_name))]
                              )
                            ])
                          ]),
                          _vm._v(" "),
                          _vm.categories[item.key].childs.length > 0
                            ? _c(
                                "ul",
                                _vm._l(
                                  _vm.categories[item.key].childs,
                                  function(category, ckey) {
                                    return _c("li", { key: ckey }, [
                                      _c(
                                        "a",
                                        {
                                          attrs: {
                                            href: _vm.$generateUrl(
                                              category.url_rewrite
                                            )
                                          }
                                        },
                                        [_vm._v(_vm._s(category.cat_name))]
                                      )
                                    ])
                                  }
                                ),
                                0
                              )
                            : _vm._e()
                        ])
                      ]
                    }
                  }
                ])
              })
            ],
            1
          )
        ])
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Categories.vue":
/*!**********************************************!*\
  !*** ./resources/js/frontend/Categories.vue ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Categories_vue_vue_type_template_id_674b50cb___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Categories.vue?vue&type=template&id=674b50cb& */ "./resources/js/frontend/Categories.vue?vue&type=template&id=674b50cb&");
/* harmony import */ var _Categories_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Categories.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Categories.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Categories_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Categories_vue_vue_type_template_id_674b50cb___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Categories_vue_vue_type_template_id_674b50cb___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Categories.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Categories.vue?vue&type=script&lang=js&":
/*!***********************************************************************!*\
  !*** ./resources/js/frontend/Categories.vue?vue&type=script&lang=js& ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Categories.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Categories.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Categories.vue?vue&type=template&id=674b50cb&":
/*!*****************************************************************************!*\
  !*** ./resources/js/frontend/Categories.vue?vue&type=template&id=674b50cb& ***!
  \*****************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_template_id_674b50cb___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Categories.vue?vue&type=template&id=674b50cb& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Categories.vue?vue&type=template&id=674b50cb&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_template_id_674b50cb___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Categories_vue_vue_type_template_id_674b50cb___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);