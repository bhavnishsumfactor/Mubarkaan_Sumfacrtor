(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[25],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Detail.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Blog/Detail.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(toastr) {//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  props: ["blog", "sharethisNetworks", "blogUrl", "logoImg", "postComments", "businessName", "relatedBlogs", "loggedin"],
  data: function data() {
    return {
      baseUrl: baseUrl,
      comment: "",
      errors: []
    };
  },
  methods: {
    saveComments: function saveComments() {
      var _this = this;

      this.errors = [];
      var commentData = this.$serializeData({
        'post_id': this.blog.post_id,
        'comment': this.comment
      });
      this.$axios.post(baseUrl + "/blog/comment/save", commentData).then(function (response) {
        if (response.data.status == true) {
          _this.comment = "";
          /**this.$inertia.visit(this.blogUrl, {
              only: ['postComments']
          }) */

          toastr.success(response.data.message);
        } else {
          if (typeof response.data.data.errors != 'undefined') {
            _this.errors = response.data.data.errors;
          } else {
            toastr.error(response.data.message, "", toastr.options);
          }
        }
      });
    }
  },
  mounted: function mounted() {
    $('.box-main-js').slick({
      dots: false,
      infinite: false,
      speed: 400,
      slidesToShow: 3,
      slidesToScroll: 3,
      responsive: [{
        breakpoint: 767,
        settings: {
          slidesToShow: 1
        }
      }, {
        breakpoint: 1199,
        settings: {
          slidesToShow: 2
        }
      }]
    });
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! toastr */ "./node_modules/toastr/toastr.js")))

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Detail.vue?vue&type=template&id=91d60842&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Blog/Detail.vue?vue&type=template&id=91d60842& ***!
  \************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "main blog--listing" }, [
    _c("section", { staticClass: "section" }, [
      _c("div", { staticClass: "container" }, [
        _c("div", { staticClass: "row justify-content-center" }, [
          _c("div", { staticClass: "col-xl-10" }, [
            _c("div", { staticClass: "inner-block text-center" }, [
              _c("div", { staticClass: "blog-date" }, [
                _vm._v(_vm._s(_vm.$t("LBL_BY")) + " "),
                _c("strong", [_vm._v(_vm._s(_vm.blog.post_author_name))]),
                _vm._v(" - "),
                _c("span", [_vm._v(_vm._s(_vm.blog.post_date))])
              ]),
              _vm._v(" "),
              _c("h1", [_vm._v(_vm._s(_vm.blog.post_title))])
            ])
          ])
        ]),
        _vm._v(" "),
        _vm.blog.afile_id != ""
          ? _c("div", { staticClass: "section pt-5" }, [
              _c("div", { staticClass: "blog_listing_media text-center" }, [
                _c("img", {
                  attrs: {
                    src: _vm.blog.blog_image,
                    alt: _vm.blog.img_alt,
                    title: _vm.blog.img_title,
                    "data-aspect-ratio": "4:3",
                    "data-yk": ""
                  }
                })
              ])
            ])
          : _vm._e(),
        _vm._v(" "),
        _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col-xl-2" }, [
            _c(
              "div",
              { staticClass: "blog-social full--fill" },
              _vm._l(_vm.sharethisNetworks, function(network, index) {
                return _c(
                  "ShareNetwork",
                  {
                    key: "network_" + index,
                    class: network.type,
                    attrs: {
                      network: network.type,
                      url: _vm.blogUrl,
                      title: _vm.blog.post_title
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
                                "/blogs/images/retina/sprite.svg#" +
                                network.type
                            }
                          })
                        ])
                      ])
                    ])
                  ]
                )
              }),
              1
            )
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col-xl-10" }, [
            _c("div", {
              staticClass: "cms",
              domProps: { innerHTML: _vm._s(_vm.blog.content.bpc_description) }
            })
          ])
        ])
      ])
    ]),
    _vm._v(" "),
    _vm.blog.post_comment_opened == 1
      ? _c("section", { staticClass: "section bg-gray comment--section" }, [
          _c("div", { staticClass: "container" }, [
            _c("div", { staticClass: "row justify-content-center" }, [
              _c("div", { staticClass: "col-xl-7 col-md-10" }, [
                _c("div", { staticClass: "form-inner text-center" }, [
                  _vm.loggedin === false
                    ? _c(
                        "a",
                        {
                          staticClass: "btn btn-blog btn-dark btn-wide",
                          attrs: { href: _vm.baseUrl + "/login" }
                        },
                        [
                          _vm._v(
                            " " + _vm._s(_vm.$t("BTN_LOGIN_TO_POST_COMMENT"))
                          )
                        ]
                      )
                    : _c("div", [
                        _c("h3", [
                          _vm._v(_vm._s(_vm.$t("LBL_LEAVE_A_COMMENT")))
                        ]),
                        _vm._v(" "),
                        _c(
                          "form",
                          {
                            staticClass: "comment-form ",
                            attrs: {
                              method: "post",
                              action: "",
                              id: "YK-comment_form",
                              "accept-charset": "UTF-8"
                            }
                          },
                          [
                            _c("div", { staticClass: "row" }, [
                              _c(
                                "div",
                                { staticClass: "col-md-12 form-group" },
                                [
                                  _c("textarea", {
                                    directives: [
                                      {
                                        name: "model",
                                        rawName: "v-model",
                                        value: _vm.comment,
                                        expression: "comment"
                                      }
                                    ],
                                    attrs: {
                                      rows: "7",
                                      name: "comment",
                                      id: "comment-body"
                                    },
                                    domProps: { value: _vm.comment },
                                    on: {
                                      input: function($event) {
                                        if ($event.target.composing) {
                                          return
                                        }
                                        _vm.comment = $event.target.value
                                      }
                                    }
                                  }),
                                  _vm._v(" "),
                                  typeof _vm.errors["comment"] != "undefined"
                                    ? _c(
                                        "span",
                                        {
                                          staticClass:
                                            "invalid-feedback d-block"
                                        },
                                        [
                                          _c("strong", [
                                            _vm._v(
                                              _vm._s(
                                                _vm.$t(_vm.errors["comment"][0])
                                              )
                                            )
                                          ])
                                        ]
                                      )
                                    : _vm._e()
                                ]
                              ),
                              _vm._v(" "),
                              _c("div", { staticClass: "col-md-12" }, [
                                _c(
                                  "button",
                                  {
                                    staticClass:
                                      "btn btn-blog btn-dark btn-wide",
                                    attrs: { type: "button" },
                                    on: { click: _vm.saveComments }
                                  },
                                  [_vm._v(_vm._s(_vm.$t("BTN_POST_COMMENT")))]
                                ),
                                _vm._v(" "),
                                _c("div", { staticClass: "YK-comment_success" })
                              ]),
                              _vm._v(" "),
                              _c("p", [
                                _vm._v(
                                  _vm._s(_vm.$t("LBL_ALL_COMMENTS_ARE_CHECKED"))
                                )
                              ])
                            ])
                          ]
                        )
                      ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "comment-list-wrapper" }, [
                  _c(
                    "ul",
                    { staticClass: "list-comments" },
                    _vm._l(_vm.postComments, function(postComment, k) {
                      return _c(
                        "li",
                        {
                          key: "postComment_" + k,
                          class: [
                            "comment",
                            k === 0 ? "first" : "",
                            k == _vm.postComments.length - 1 ? "last mb-0" : ""
                          ]
                        },
                        [
                          _c("div", { staticClass: "comment-head" }, [
                            _c("div", { staticClass: "avatar" }, [
                              _c("img", {
                                attrs: {
                                  src: postComment.user_image,
                                  "data-yk": "",
                                  alt: ""
                                }
                              })
                            ]),
                            _vm._v(" "),
                            _c("div", { staticClass: "user__detail" }, [
                              _c("span", { staticClass: "auther" }, [
                                _vm._v(
                                  " " +
                                    _vm._s(_vm.$t("LBL_BY")) +
                                    "  " +
                                    _vm._s(postComment.user_name) +
                                    " " +
                                    _vm._s(_vm.$t("LBL_ON"))
                                )
                              ]),
                              _vm._v(" "),
                              _c("time", [
                                _vm._v(_vm._s(postComment.comment_date))
                              ])
                            ])
                          ]),
                          _vm._v(" "),
                          _c("div", { staticClass: "comment-content" }, [
                            _c("p", {
                              domProps: {
                                innerHTML: _vm._s(postComment.bpcomment_content)
                              }
                            }),
                            _vm._v(" "),
                            postComment.replies.length > 0
                              ? _c(
                                  "ul",
                                  _vm._l(postComment.replies, function(
                                    reply,
                                    rindex
                                  ) {
                                    return _c(
                                      "li",
                                      {
                                        key:
                                          "reply_" +
                                          postComment.bptc_bpcomment_id +
                                          "_comment_" +
                                          rindex
                                      },
                                      [
                                        _c(
                                          "div",
                                          { staticClass: "comment-head" },
                                          [
                                            _c(
                                              "div",
                                              { staticClass: "avatar" },
                                              [
                                                _c("img", {
                                                  attrs: {
                                                    src: _vm.logoImg,
                                                    "data-yk": "",
                                                    alt: ""
                                                  }
                                                })
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "div",
                                              { staticClass: "user__detail" },
                                              [
                                                _c(
                                                  "span",
                                                  { staticClass: "auther" },
                                                  [
                                                    _vm._v(
                                                      " " +
                                                        _vm._s(
                                                          _vm.$t("LBL_BY")
                                                        ) +
                                                        " " +
                                                        _vm._s(
                                                          _vm.businessName
                                                        ) +
                                                        " " +
                                                        _vm._s(_vm.$t("LBL_ON"))
                                                    )
                                                  ]
                                                ),
                                                _vm._v(" "),
                                                _c("time", [
                                                  _vm._v(
                                                    _vm._s(reply.bpcomment_date)
                                                  )
                                                ])
                                              ]
                                            )
                                          ]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "div",
                                          { staticClass: "comment-content" },
                                          [
                                            _c("p", {
                                              domProps: {
                                                innerHTML: _vm._s(
                                                  reply.bpcomment_content
                                                )
                                              }
                                            })
                                          ]
                                        )
                                      ]
                                    )
                                  }),
                                  0
                                )
                              : _vm._e()
                          ])
                        ]
                      )
                    }),
                    0
                  )
                ])
              ])
            ])
          ])
        ])
      : _vm._e(),
    _vm._v(" "),
    _vm.relatedBlogs.length > 0
      ? _c("section", { staticClass: "section related--blog" }, [
          _c("div", { staticClass: "container" }, [
            _c("div", { staticClass: "box" }, [
              _c("h3", [_vm._v(_vm._s(_vm.$t("LBL_RELATED_BLOGS")))]),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "box-item box-main-js" },
                _vm._l(_vm.relatedBlogs, function(relatedBlog, rbkey) {
                  return _c(
                    "div",
                    {
                      key: "relatedBlogs_" + rbkey,
                      staticClass: "slider-item"
                    },
                    [
                      _c("div", { staticClass: "box__inner" }, [
                        _c("div", { staticClass: "box-head inner-block" }, [
                          _c("div", { staticClass: "box-media" }, [
                            _c(
                              "a",
                              {
                                attrs: {
                                  href:
                                    _vm.baseUrl + "/" + relatedBlog.record_slug
                                }
                              },
                              [
                                _c("img", {
                                  attrs: {
                                    "data-yk": "",
                                    src: relatedBlog.rblog_image,
                                    alt: relatedBlog.img_alt,
                                    title: relatedBlog.img_title,
                                    "data-aspect-ratio": "4:3"
                                  }
                                })
                              ]
                            )
                          ]),
                          _vm._v(" "),
                          _c("div", { staticClass: "blog-date" }, [
                            _vm._v(_vm._s(_vm.$t("LBL_BY")) + " "),
                            _c("strong", [
                              _vm._v(_vm._s(relatedBlog.post_author_name))
                            ]),
                            _vm._v(" - "),
                            _c("span", [_vm._v(_vm._s(relatedBlog.rblog_date))])
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "box-body" }, [
                          _c("h3", [
                            _c(
                              "a",
                              {
                                attrs: {
                                  href:
                                    _vm.baseUrl + "/" + relatedBlog.record_slug
                                }
                              },
                              [_vm._v(_vm._s(relatedBlog.post_title))]
                            )
                          ]),
                          _vm._v(" "),
                          _c("p", [
                            _vm._v(
                              _vm._s(
                                relatedBlog.bpc_short_description.substring(
                                  0,
                                  200
                                )
                              )
                            )
                          ])
                        ])
                      ])
                    ]
                  )
                }),
                0
              )
            ])
          ])
        ])
      : _vm._e()
  ])
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

/***/ "./resources/js/frontend/Blog/Detail.vue":
/*!***********************************************!*\
  !*** ./resources/js/frontend/Blog/Detail.vue ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Detail_vue_vue_type_template_id_91d60842___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Detail.vue?vue&type=template&id=91d60842& */ "./resources/js/frontend/Blog/Detail.vue?vue&type=template&id=91d60842&");
/* harmony import */ var _Detail_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Detail.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Blog/Detail.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Detail_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Detail_vue_vue_type_template_id_91d60842___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Detail_vue_vue_type_template_id_91d60842___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Blog/Detail.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Blog/Detail.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/frontend/Blog/Detail.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Detail_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Detail.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Detail.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Detail_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Blog/Detail.vue?vue&type=template&id=91d60842&":
/*!******************************************************************************!*\
  !*** ./resources/js/frontend/Blog/Detail.vue?vue&type=template&id=91d60842& ***!
  \******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Detail_vue_vue_type_template_id_91d60842___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Detail.vue?vue&type=template&id=91d60842& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Detail.vue?vue&type=template&id=91d60842&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Detail_vue_vue_type_template_id_91d60842___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Detail_vue_vue_type_template_id_91d60842___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);