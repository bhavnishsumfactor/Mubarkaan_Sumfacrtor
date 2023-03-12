(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[19],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(toastr) {function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
var emailFormat = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['ratio', 'darkLogo', 'logo', 'smsActivePackage', 'serviceType', 'socialId', 'defaultCountry', 'termsUrl'],
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      errors: [],
      clicked: 0,
      agreedToTerms: '',
      unmaskedPhoneNumber: '',
      mode: 'email',
      formData: {
        user_email: "",
        user_phone: "",
        user_phone_country_code: ""
      }
    };
  },
  computed: {
    isComplete: function isComplete() {
      return this.agreedToTerms && (this.formData.user_phone && this.checkIfComplete($('#user_phone_mask')) === true || this.formData.user_email && emailFormat.test(this.formData.user_email.trim()) === true);
    }
  },
  methods: {
    saveAdditionalDetails: function saveAdditionalDetails() {
      var _this = this;

      this.clicked = 1;

      var inputData = _objectSpread({}, this.formData);

      var contentName = "";

      if (this.unmaskedPhoneNumber != '') {
        inputData.user_phone = this.unmaskedPhoneNumber;
      }

      if (this.mode === 'email') {
        delete inputData.user_phone;
        delete inputData.user_phone_country_code;
        contentName = inputData.user_email;
      } else {
        delete inputData.user_email;
        contentName = inputData.user_phone;
      }

      var submitData = this.$serializeData(inputData);
      submitData.append('socialId', this.socialId);
      submitData.append('serviceType', this.serviceType);
      this.$axios.post(baseUrl + "/additional-details", submitData).then(function (response) {
        _this.clicked = 0;

        if (response.data.status === true) {
          if (typeof response.data.data.url != 'undefined' && response.data.data.url != "") {
            _this.$inertia.visit(baseUrl + response.data.data.url, {}, {
              onStart: function onStart(visit) {
                events.registration({
                  content_name: contentName
                });
              }
            });
          } else {
            toastr.error(response.data.message, "", toastr.options);
          }
        } else {
          if (typeof response.data.data.errors != 'undefined') {
            _this.errors = response.data.data.errors;
          } else {
            toastr.error(response.data.message, "", toastr.options);
          }
        }
      });
    },
    changeCountry: function changeCountry(data) {
      this.formData.user_phone_country_code = data.iso2;
    },
    onPhoneNumberChange: function onPhoneNumberChange(data, obj) {
      this.formData.user_phone_country_code = obj.country.iso2;
      this.unmaskedPhoneNumber = obj.number.significant;
    },
    switchMode: function switchMode() {
      if (this.mode == 'email') {
        this.mode = 'phone';
      } else {
        this.mode = 'email';
      }

      setTimeout(function () {
        floatingFormFix();
      }, 200);
    },
    checkIfComplete: function checkIfComplete(el) {
      if ($(el).inputmask("isComplete")) {
        return true;
      } else {
        return false;
      }
    }
  },
  mounted: function mounted() {
    setTimeout(function () {
      floatingFormFix();
    }, 200);
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! toastr */ "./node_modules/toastr/toastr.js")))

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=style&index=0&id=f8b17372&lang=css&scoped=true&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=style&index=0&id=f8b17372&lang=css&scoped=true& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.vue-tel-input[data-v-f8b17372] {\n    flex: 1;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=style&index=0&id=f8b17372&lang=css&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=style&index=0&id=f8b17372&lang=css&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--5-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--5-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./SocialLoginAdditionalDetails.vue?vue&type=style&index=0&id=f8b17372&lang=css&scoped=true& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=style&index=0&id=f8b17372&lang=css&scoped=true&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=template&id=f8b17372&scoped=true&":
/*!**********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=template&id=f8b17372&scoped=true& ***!
  \**********************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "login-page" }, [
    _c("div", { staticClass: "login-head" }, [
      _c(
        "div",
        { staticClass: "login-logo-wrap" },
        [
          _c(
            "inertia-link",
            { staticClass: "login-logo", attrs: { href: _vm.baseUrl } },
            [
              _c("img", {
                attrs: {
                  "data-gjs-type": "default",
                  "data-yk": "",
                  id: "yk-header-logo",
                  "data-lite": _vm.logo,
                  "data-dark": _vm.darkLogo,
                  src: _vm.logo,
                  alt: "",
                  "data-ratio": _vm.ratio
                }
              })
            ]
          )
        ],
        1
      ),
      _vm._v(" "),
      _c("h1", [_vm._v(_vm._s(_vm.$t("LBL_COMPLETE_YOUR_ACCOUNT")))])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "login-body" }, [
      _c(
        "form",
        {
          staticClass: "form form-login form-floating",
          attrs: { method: "POST", id: "social-login-form" }
        },
        [
          _c("input", {
            directives: [
              {
                name: "model",
                rawName: "v-model",
                value: _vm.serviceType,
                expression: "serviceType"
              }
            ],
            attrs: { type: "hidden", name: "serviceType" },
            domProps: { value: _vm.serviceType },
            on: {
              input: function($event) {
                if ($event.target.composing) {
                  return
                }
                _vm.serviceType = $event.target.value
              }
            }
          }),
          _vm._v(" "),
          _c("input", {
            directives: [
              {
                name: "model",
                rawName: "v-model",
                value: _vm.socialId,
                expression: "socialId"
              }
            ],
            attrs: { type: "hidden", name: "socialId" },
            domProps: { value: _vm.socialId },
            on: {
              input: function($event) {
                if ($event.target.composing) {
                  return
                }
                _vm.socialId = $event.target.value
              }
            }
          }),
          _vm._v(" "),
          _c("div", { staticClass: "row" }, [
            _c("div", { staticClass: "col-md-12" }, [
              _c(
                "div",
                {
                  staticClass:
                    "form-group form-floating__group floated-txt_wrap"
                },
                [
                  _c("div", { staticClass: "floated-txt" }, [
                    _c(
                      "a",
                      {
                        attrs: { href: "javascript:;" },
                        on: { click: _vm.switchMode }
                      },
                      [
                        _vm.mode == "phone" && _vm.smsActivePackage >= 1
                          ? _c("span", [
                              _vm._v(_vm._s(_vm.$t("LNK_USE_EMAIL_INSTEAD")))
                            ])
                          : _c("span", [
                              _vm._v(_vm._s(_vm.$t("LNK_USE_PHONE_INSTEAD")))
                            ])
                      ]
                    )
                  ]),
                  _vm._v(" "),
                  _vm.mode == "phone" && _vm.smsActivePackage >= 1
                    ? _c(
                        "span",
                        [
                          _c("vue-tel-input", {
                            attrs: {
                              defaultCountry: _vm.defaultCountry,
                              enabledCountryCode: true,
                              inputClasses: "form-control form-floating__field",
                              placeholder: _vm.$t("PLH_ENTER_PHONE_NUMBER"),
                              maxLen: 15,
                              autoFormat: "",
                              validCharactersOnly: true
                            },
                            on: {
                              "country-changed": _vm.changeCountry,
                              input: _vm.onPhoneNumberChange
                            },
                            model: {
                              value: _vm.formData.user_phone,
                              callback: function($$v) {
                                _vm.$set(_vm.formData, "user_phone", $$v)
                              },
                              expression: "formData.user_phone"
                            }
                          })
                        ],
                        1
                      )
                    : _c("span", [
                        _c("input", {
                          directives: [
                            {
                              name: "model",
                              rawName: "v-model",
                              value: _vm.formData.user_email,
                              expression: "formData.user_email"
                            }
                          ],
                          staticClass: "form-control form-floating__field",
                          attrs: {
                            type: "text",
                            name: "user_email",
                            autofocus: "",
                            tabindex: "3"
                          },
                          domProps: { value: _vm.formData.user_email },
                          on: {
                            input: function($event) {
                              if ($event.target.composing) {
                                return
                              }
                              _vm.$set(
                                _vm.formData,
                                "user_email",
                                $event.target.value
                              )
                            }
                          }
                        }),
                        _vm._v(" "),
                        _c("label", { staticClass: "form-floating__label" }, [
                          _vm._v(_vm._s(_vm.$t("LBL_EMAIL")))
                        ])
                      ])
                ]
              )
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "row" }, [
            _c("div", { staticClass: "col-md-12" }, [
              _c("div", { staticClass: "form-group mb-2" }, [
                _c("label", { staticClass: "checkbox" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.agreedToTerms,
                        expression: "agreedToTerms"
                      }
                    ],
                    attrs: {
                      id: "termsConditions",
                      type: "checkbox",
                      name: "agree",
                      value: "1",
                      tabindex: "7"
                    },
                    domProps: {
                      checked: Array.isArray(_vm.agreedToTerms)
                        ? _vm._i(_vm.agreedToTerms, "1") > -1
                        : _vm.agreedToTerms
                    },
                    on: {
                      change: function($event) {
                        var $$a = _vm.agreedToTerms,
                          $$el = $event.target,
                          $$c = $$el.checked ? true : false
                        if (Array.isArray($$a)) {
                          var $$v = "1",
                            $$i = _vm._i($$a, $$v)
                          if ($$el.checked) {
                            $$i < 0 && (_vm.agreedToTerms = $$a.concat([$$v]))
                          } else {
                            $$i > -1 &&
                              (_vm.agreedToTerms = $$a
                                .slice(0, $$i)
                                .concat($$a.slice($$i + 1)))
                          }
                        } else {
                          _vm.agreedToTerms = $$c
                        }
                      }
                    }
                  }),
                  _vm._v(" "),
                  _c("i", { staticClass: "input-helper" }),
                  _vm._v(
                    "\n                            " +
                      _vm._s(_vm.$t("LBL_I_AGREE_TO_THE")) +
                      " "
                  ),
                  _c("a", { attrs: { target: "_blank", href: _vm.termsUrl } }, [
                    _vm._v(_vm._s(_vm.$t("LNK_TERMS_CONDITIONS")))
                  ])
                ])
              ])
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "row" }, [
            _c("div", { staticClass: "col-md-12" }, [
              _c("div", { staticClass: "form-group" }, [
                _c(
                  "button",
                  {
                    staticClass:
                      "btn btn-brand btn-block gb-btn gb-btn-primary btn-submit",
                    class: _vm.clicked == 1 && "gb-is-loading",
                    attrs: {
                      type: "button",
                      name: "submit-button",
                      disabled: !_vm.isComplete || _vm.clicked == 1
                    },
                    on: {
                      click: function($event) {
                        $event.stopPropagation()
                        return _vm.saveAdditionalDetails()
                      }
                    }
                  },
                  [
                    _vm._v(
                      _vm._s(_vm.$t("BTN_ACCOUNT_COMPLETION_SUBMIT")) + " "
                    ),
                    _c("i", { staticClass: "arrow la la-long-arrow-right" })
                  ]
                )
              ])
            ])
          ])
        ]
      )
    ])
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

/***/ "./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue":
/*!*********************************************************************!*\
  !*** ./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SocialLoginAdditionalDetails_vue_vue_type_template_id_f8b17372_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SocialLoginAdditionalDetails.vue?vue&type=template&id=f8b17372&scoped=true& */ "./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=template&id=f8b17372&scoped=true&");
/* harmony import */ var _SocialLoginAdditionalDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SocialLoginAdditionalDetails.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _SocialLoginAdditionalDetails_vue_vue_type_style_index_0_id_f8b17372_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./SocialLoginAdditionalDetails.vue?vue&type=style&index=0&id=f8b17372&lang=css&scoped=true& */ "./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=style&index=0&id=f8b17372&lang=css&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _SocialLoginAdditionalDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SocialLoginAdditionalDetails_vue_vue_type_template_id_f8b17372_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SocialLoginAdditionalDetails_vue_vue_type_template_id_f8b17372_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "f8b17372",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialLoginAdditionalDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./SocialLoginAdditionalDetails.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialLoginAdditionalDetails_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=style&index=0&id=f8b17372&lang=css&scoped=true&":
/*!******************************************************************************************************************************!*\
  !*** ./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=style&index=0&id=f8b17372&lang=css&scoped=true& ***!
  \******************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialLoginAdditionalDetails_vue_vue_type_style_index_0_id_f8b17372_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--5-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--5-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./SocialLoginAdditionalDetails.vue?vue&type=style&index=0&id=f8b17372&lang=css&scoped=true& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=style&index=0&id=f8b17372&lang=css&scoped=true&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialLoginAdditionalDetails_vue_vue_type_style_index_0_id_f8b17372_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialLoginAdditionalDetails_vue_vue_type_style_index_0_id_f8b17372_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialLoginAdditionalDetails_vue_vue_type_style_index_0_id_f8b17372_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialLoginAdditionalDetails_vue_vue_type_style_index_0_id_f8b17372_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=template&id=f8b17372&scoped=true&":
/*!****************************************************************************************************************!*\
  !*** ./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=template&id=f8b17372&scoped=true& ***!
  \****************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialLoginAdditionalDetails_vue_vue_type_template_id_f8b17372_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./SocialLoginAdditionalDetails.vue?vue&type=template&id=f8b17372&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/SocialLoginAdditionalDetails.vue?vue&type=template&id=f8b17372&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialLoginAdditionalDetails_vue_vue_type_template_id_f8b17372_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SocialLoginAdditionalDetails_vue_vue_type_template_id_f8b17372_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);