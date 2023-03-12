(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[62],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/AccountVerification.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Auth/AccountVerification.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  props: ["ratio", "darkLogo", "logo", "user", "step", "slug", "loginType"],
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      verifyClicked: 0,
      interval: "",
      code: {
        digit1: "",
        digit2: "",
        digit3: "",
        digit4: ""
      }
    };
  },
  computed: {
    isComplete: function isComplete() {
      return this.code.digit1 && this.code.digit2 && this.code.digit3 && this.code.digit4;
    }
  },
  methods: {
    verifyOtp: function verifyOtp() {
      var _this = this;

      this.verifyClicked = 1;
      var submitBtn = $(".yk-verify");
      $(".yk-verify").attr("disabled", true);
      var otp = $(".otp-inputs input").map(function () {
        return $(this).val();
      }).get().join("");
      var submitData = this.$serializeData({
        slug: this.slug,
        otp: otp,
        type: this.loginType
      });
      this.$axios.post(baseUrl + "/account/verify", submitData).then(function (response) {
        _this.verifyClicked = 0;

        if (response.data.status == true) {
          $(".YK-Verify-Otp").addClass("d-none");
          $(".YK-otpVerifiedSuccess").removeClass("d-none");
          /* setTimeout(() => {
                      this.$inertia.visit(baseUrl + "/login");
                  }, 2000);*/
        } else {
          _this.clearCode();

          $(".otp-inputs").find("input").removeClass("filled");
          $(".otp-inputs").find("input").addClass("is-invalid");
          $(".otp-inputs").find("input:first").focus();
          $("#errorInfo").removeClass("d-none");
          $("#errorInfo").html("<i class=\"fas fa-info-circle\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".concat(response.data.message, "\"></i>"));
          $('[data-toggle="tooltip"]').tooltip();
        }
      }, function (error) {
        _this.verifyClicked = 0;
      });
    },
    resendCodeRequest: function resendCodeRequest() {
      var _this2 = this;

      var submitData = this.$serializeData({
        slug: this.slug
      });
      this.$axios.post(baseUrl + "/account/verification", submitData).then(function () {
        clearInterval(_this2.interval);

        _this2.timeCounter(180);
      });
    },
    timeCounter: function timeCounter(counter) {
      $("#timer").show();
      $(".YK-resendOtp").hide();
      $("#time").text(counter);
      this.interval = setInterval(function () {
        counter--;

        if (counter <= 0) {
          clearInterval(this.interval);
          $("#timer").hide();
          $(".YK-resendOtp").show();
          return;
        } else {
          $("#time").text(counter);
        }
      }, 1000);
    },
    enterDigit: function enterDigit(e) {
      var currentElm = e.target;
      var parent = $($(currentElm).parent());

      if (e.keyCode === 8 || e.keyCode === 37) {
        var prev = parent.find("input#" + $(currentElm).data("previous"));

        if (prev.length) {
          $(prev).select();
        }
      } else if (e.keyCode >= 48 && e.keyCode <= 57 || e.keyCode >= 65 && e.keyCode <= 90 || e.keyCode >= 96 && e.keyCode <= 105 || e.keyCode === 39) {
        var next = parent.find("input#" + $(currentElm).data("next"));

        if (next.length) {
          if (!(e.keyCode >= 48 && e.keyCode <= 57 || e.keyCode >= 96 && e.keyCode <= 105)) {
            $(currentElm).val("");
            return;
          }

          $(next).select();
        } else {
          if (parent.data("autosubmit")) {
            parent.submit();
          }
        }
      }
    },
    clearCode: function clearCode() {
      this.code = {
        digit1: "",
        digit2: "",
        digit3: "",
        digit4: ""
      };
    }
  },
  mounted: function mounted() {
    this.timeCounter(180);
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/AccountVerification.vue?vue&type=template&id=306721d0&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Auth/AccountVerification.vue?vue&type=template&id=306721d0& ***!
  \*************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "signup-full" }, [
    _c("div", { staticClass: "signup-full-head" }, [
      _c(
        "div",
        { staticClass: "logo" },
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
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "signup-full-body" }, [
      _c("div", { staticClass: "flow-cms" }, [
        _c("h1", [_vm._v(_vm._s(_vm.$t("LBL_ACCOUNT_VERIFICATION_HEADING")))]),
        _vm._v(" "),
        _vm.step == "email-verify"
          ? _c("p", [
              _vm._v(
                "\n        " +
                  _vm._s(
                    _vm.$t("LBL_ACCOUNT_VERIFICATION_EMAIL_INSTRUCTIONS")
                  ) +
                  "\n        " +
                  _vm._s(_vm.user.user_email) +
                  "\n      "
              )
            ])
          : _c("p", [
              _vm._v(
                "\n        " +
                  _vm._s(
                    _vm.$t("LBL_ACCOUNT_VERIFICATION_PHONE_INSTRUCTIONS")
                  ) +
                  "\n        " +
                  _vm._s(_vm.user.user_phone) +
                  "\n      "
              )
            ]),
        _vm._v(" "),
        _c("div", { staticClass: "otp-block YK-Verify-Otp mt-5" }, [
          _c("div", { staticClass: "otp-block__body" }, [
            _c("div", { staticClass: "otp-enter" }, [
              _c("div", { staticClass: "otp-inputs" }, [
                _c(
                  "div",
                  {
                    staticClass: "digit-group",
                    attrs: {
                      "data-group-name": "digits",
                      "data-autosubmit": "false",
                      autocomplete: "off"
                    }
                  },
                  [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.code.digit1,
                          expression: "code.digit1"
                        }
                      ],
                      staticClass: "field-otp",
                      attrs: {
                        type: "text",
                        maxlength: "1",
                        placeholder: "*",
                        id: "digit-1",
                        name: "digit-1",
                        "data-next": "digit-2"
                      },
                      domProps: { value: _vm.code.digit1 },
                      on: {
                        keyup: function($event) {
                          return _vm.enterDigit($event)
                        },
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.code, "digit1", $event.target.value)
                        }
                      }
                    }),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.code.digit2,
                          expression: "code.digit2"
                        }
                      ],
                      staticClass: "field-otp",
                      attrs: {
                        type: "text",
                        maxlength: "1",
                        placeholder: "*",
                        id: "digit-2",
                        name: "digit-2",
                        "data-next": "digit-3",
                        "data-previous": "digit-1"
                      },
                      domProps: { value: _vm.code.digit2 },
                      on: {
                        keyup: function($event) {
                          return _vm.enterDigit($event)
                        },
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.code, "digit2", $event.target.value)
                        }
                      }
                    }),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.code.digit3,
                          expression: "code.digit3"
                        }
                      ],
                      staticClass: "field-otp",
                      attrs: {
                        type: "text",
                        maxlength: "1",
                        placeholder: "*",
                        id: "digit-3",
                        name: "digit-3",
                        "data-next": "digit-4",
                        "data-previous": "digit-2"
                      },
                      domProps: { value: _vm.code.digit3 },
                      on: {
                        keyup: function($event) {
                          return _vm.enterDigit($event)
                        },
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.code, "digit3", $event.target.value)
                        }
                      }
                    }),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.code.digit4,
                          expression: "code.digit4"
                        }
                      ],
                      staticClass: "field-otp",
                      attrs: {
                        type: "text",
                        maxlength: "1",
                        placeholder: "*",
                        id: "digit-4",
                        name: "digit-4",
                        "data-next": "digit-5",
                        "data-previous": "digit-3"
                      },
                      domProps: { value: _vm.code.digit4 },
                      on: {
                        keyup: function($event) {
                          return _vm.enterDigit($event)
                        },
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.code, "digit4", $event.target.value)
                        }
                      }
                    }),
                    _vm._v(" "),
                    _c("div", {
                      staticClass: "error-info d-none",
                      attrs: { id: "errorInfo" }
                    })
                  ]
                )
              ]),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass:
                    "btn btn-brand btn-wide gb-btn gb-btn-primary yk-verify",
                  class: _vm.verifyClicked == 1 && "gb-is-loading",
                  attrs: {
                    disabled: !_vm.isComplete || _vm.verifyClicked == 1,
                    type: "button"
                  },
                  on: { click: _vm.verifyOtp }
                },
                [
                  _vm._v(
                    "\n              " +
                      _vm._s(_vm.$t("BTN_VERIFY")) +
                      "\n            "
                  )
                ]
              )
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "otp-block__footer" }, [
            _c(
              "p",
              { staticClass: "YK-Verify-Otp-timer", attrs: { id: "timer" } },
              [
                _vm._v(
                  "\n            " +
                    _vm._s(_vm.$t("LBL_CODE_EXPIRES_IN")) +
                    ":\n            "
                ),
                _c("span", {
                  staticClass: "txt-success font-weight-bold",
                  attrs: { id: "time" }
                }),
                _vm._v(_vm._s(_vm.$t("LBL_SECONDS")) + "\n          ")
              ]
            ),
            _vm._v(" "),
            _c(
              "p",
              {
                staticClass: "YK-resendOtp",
                on: { click: _vm.resendCodeRequest }
              },
              [
                _vm._v(
                  "\n            " +
                    _vm._s(_vm.$t("LBL_DIDNT_GET_CODE")) +
                    "\n            "
                ),
                _c(
                  "a",
                  {
                    staticClass: "txt-success font-weight-bold",
                    attrs: { href: "javascript:;", id: "resendOtpBtn" }
                  },
                  [
                    _vm._v(
                      "\n              " + _vm._s(_vm.$t("BTN_RESEND")) + "!"
                    )
                  ]
                )
              ]
            )
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "otp-block YK-otpVerifiedSuccess d-none" }, [
          _c("div", { staticClass: "otp-success" }, [
            _vm.step == "email-verify"
              ? _c("img", {
                  staticClass: "img",
                  attrs: {
                    "data-yk": "",
                    src: _vm.baseUrl + "/yokart/media/retina/otp-complete.svg",
                    alt: ""
                  }
                })
              : _c("img", {
                  staticClass: "img",
                  attrs: {
                    "data-yk": "",
                    src: _vm.baseUrl + "/yokart/media/retina/otp-complete.svg",
                    alt: ""
                  }
                })
          ])
        ])
      ])
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

/***/ "./resources/js/frontend/Auth/AccountVerification.vue":
/*!************************************************************!*\
  !*** ./resources/js/frontend/Auth/AccountVerification.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AccountVerification_vue_vue_type_template_id_306721d0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AccountVerification.vue?vue&type=template&id=306721d0& */ "./resources/js/frontend/Auth/AccountVerification.vue?vue&type=template&id=306721d0&");
/* harmony import */ var _AccountVerification_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AccountVerification.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Auth/AccountVerification.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AccountVerification_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AccountVerification_vue_vue_type_template_id_306721d0___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AccountVerification_vue_vue_type_template_id_306721d0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Auth/AccountVerification.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Auth/AccountVerification.vue?vue&type=script&lang=js&":
/*!*************************************************************************************!*\
  !*** ./resources/js/frontend/Auth/AccountVerification.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountVerification_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./AccountVerification.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/AccountVerification.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountVerification_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Auth/AccountVerification.vue?vue&type=template&id=306721d0&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/frontend/Auth/AccountVerification.vue?vue&type=template&id=306721d0& ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountVerification_vue_vue_type_template_id_306721d0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./AccountVerification.vue?vue&type=template&id=306721d0& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/AccountVerification.vue?vue&type=template&id=306721d0&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountVerification_vue_vue_type_template_id_306721d0___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AccountVerification_vue_vue_type_template_id_306721d0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);