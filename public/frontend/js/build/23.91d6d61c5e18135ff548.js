(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[23],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/ResetPassword.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Auth/ResetPassword.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ['ratio', 'darkLogo', 'logo', 'token', 'user', 'mode'],
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      errors: [],
      formData: {
        user_password: "",
        user_password_confirmation: ""
      },
      requestVerified: 0,
      verifyClicked: 0,
      resetClicked: 0,
      interval: '',
      code: {
        digit1: '',
        digit2: '',
        digit3: '',
        digit4: ''
      },
      displayPswdInfo: 0,
      validConfirmPassword: '',
      validPassword: 0,
      passwordValidate: {
        length: false,
        letter: false,
        capital: false,
        number: false,
        special: false
      }
    };
  },
  computed: {
    isComplete: function isComplete() {
      return this.code.digit1 && this.code.digit2 && this.code.digit3 && this.code.digit4;
    },
    isResetComplete: function isResetComplete() {
      return this.formData.user_password && this.formData.user_password_confirmation && !Object.values(this.passwordValidate).includes(false) && this.validConfirmPassword === 1;
    }
  },
  methods: {
    forgotPassword: function forgotPassword() {
      var _this = this;

      this.resetClicked = 1;
      var submitData = this.$serializeData(this.formData);
      submitData.append('token', this.token);
      this.$axios.post(baseUrl + "/password/reset", submitData).then(function (response) {
        _this.resetClicked = 0;

        if (response.data.status === true) {
          toastr.success(response.data.message, "", toastr.options);
          setTimeout(function () {
            _this.$inertia.get(baseUrl + "/login");
          }, 2000);
        } else {
          if (typeof response.data.data.errors != 'undefined') {
            _this.errors = response.data.data.errors;
          } else {
            toastr.error(response.data.message, "", toastr.options);
          }
        }
      });
    },
    verifyOtp: function verifyOtp() {
      var _this2 = this;

      this.verifyClicked = 1;
      $('.yk-verify').attr('disabled', true);
      var otp = $('.otp-inputs input').map(function () {
        return $(this).val();
      }).get().join('');
      var submitData = this.$serializeData({
        'slug': this.token,
        'otp': otp
      });
      this.$axios.post(baseUrl + "/reset-password/verify", submitData).then(function (response) {
        _this2.verifyClicked = 0;

        if (response.data.status == true) {
          $(".YK-Verify-Otp").addClass('d-none');
          $(".YK-otpVerifiedSuccess").removeClass('d-none');
          _this2.requestVerified = 1;
        } else {
          _this2.clearCode();

          $('.otp-inputs').find('input').removeClass('filled');
          $('.otp-inputs').find('input').addClass('is-invalid');
          $('.otp-inputs').find('input:first').focus();
          $("#errorInfo").removeClass('d-none');
          $("#errorInfo").html("<i class=\"fas fa-info-circle\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"".concat(response.data.message, "\"></i>"));
          $('[data-toggle="tooltip"]').tooltip();
        }
      }, function (error) {
        _this2.verifyClicked = 0;
      });
    },
    resendCodeRequest: function resendCodeRequest() {
      var _this3 = this;

      var submitData = this.$serializeData({
        'slug': this.token
      });
      this.$axios.post(baseUrl + "/reset-password/verification/" + this.mode, submitData).then(function () {
        clearInterval(_this3.interval);

        _this3.timeCounter(180);
      });
    },
    timeCounter: function timeCounter(counter) {
      $("#timer").show();
      $(".YK-resendOtp").hide();
      $('#time').text(counter);
      this.interval = setInterval(function () {
        counter--;

        if (counter <= 0) {
          clearInterval(this.interval);
          $("#timer").hide();
          $(".YK-resendOtp").show();
          return;
        } else {
          $('#time').text(counter);
        }
      }, 1000);
    },
    enterDigit: function enterDigit(e) {
      var currentElm = e.target;
      var parent = $($(currentElm).parent());

      if (e.keyCode === 8 || e.keyCode === 37) {
        var prev = parent.find('input#' + $(currentElm).data('previous'));

        if (prev.length) {
          $(prev).select();
        }
      } else if (e.keyCode >= 48 && e.keyCode <= 57 || e.keyCode >= 65 && e.keyCode <= 90 || e.keyCode >= 96 && e.keyCode <= 105 || e.keyCode === 39) {
        var next = parent.find('input#' + $(currentElm).data('next'));

        if (next.length) {
          if (!(e.keyCode >= 48 && e.keyCode <= 57 || e.keyCode >= 96 && e.keyCode <= 105)) {
            $(currentElm).val('');
            return;
          }

          $(next).select();
        } else {
          if (parent.data('autosubmit')) {
            parent.submit();
          }
        }
      }
    },
    clearCode: function clearCode() {
      this.code = {
        digit1: '',
        digit2: '',
        digit3: '',
        digit4: ''
      };
    },
    typeInConfirmPassword: function typeInConfirmPassword() {
      if (this.formData.user_password == '' || this.formData.user_password_confirmation == '') {
        this.validConfirmPassword = '';
        return false;
      }

      if (this.formData.user_password == this.formData.user_password_confirmation) {
        this.validConfirmPassword = 1;
      } else {
        this.validConfirmPassword = 0;
      }
    },
    validatePasswordForm: function validatePasswordForm() {
      return !Object.values(this.passwordValidate).includes(false) && this.formData.user_password != "" && this.formData.user_confirm_password != "" && this.formData.user_password == this.formData.user_confirm_password;
    },
    typeInPassword: function typeInPassword(event) {
      var pswd = event.target.value;

      if (pswd.length < 8) {
        this.passwordValidate.length = false;
      } else {
        this.passwordValidate.length = true;
      }

      if (pswd.match(/[A-z]/)) {
        this.passwordValidate.letter = true;
      } else {
        this.passwordValidate.letter = false;
      }

      if (pswd.match(/[A-Z]/)) {
        this.passwordValidate.capital = true;
      } else {
        this.passwordValidate.capital = false;
      }

      if (pswd.match(/[^\w]/)) {
        this.passwordValidate.special = true;
      } else {
        this.passwordValidate.special = false;
      }

      if (pswd.match(/\d/)) {
        this.passwordValidate.number = true;
      } else {
        this.passwordValidate.number = false;
      }

      this.changePasswordFormValidated = this.displayConfirmPasswordCheck = this.validatePasswordForm() ? true : false;
    }
  },
  mounted: function mounted() {
    this.timeCounter(180);
    setTimeout(function () {
      floatingFormFix();
    }, 200);
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! toastr */ "./node_modules/toastr/toastr.js")))

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/ResetPassword.vue?vue&type=template&id=164be0f2&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Auth/ResetPassword.vue?vue&type=template&id=164be0f2& ***!
  \*******************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "login-page forgot-password" }, [
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
      _c("h1", [_vm._v(_vm._s(_vm.$t("LBL_RESET_PASSWORD")))])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "login-body" }, [
      _c(
        "form",
        {
          staticClass: "form form-login form-floating",
          attrs: { method: "POST", id: "YK-resetPasswordForm" }
        },
        [
          _c("input", {
            directives: [
              {
                name: "model",
                rawName: "v-model",
                value: _vm.token,
                expression: "token"
              }
            ],
            attrs: { type: "hidden", name: "token" },
            domProps: { value: _vm.token },
            on: {
              input: function($event) {
                if ($event.target.composing) {
                  return
                }
                _vm.token = $event.target.value
              }
            }
          }),
          _vm._v(" "),
          _vm.mode == "phone"
            ? _c("p", [
                _vm._v(
                  _vm._s(_vm.$t("LBL_RESET_PASSWORD_PHONE_INSTRUCTIONS")) +
                    " " +
                    _vm._s(_vm.user.user_phone_full)
                )
              ])
            : _c("p", [
                _vm._v(
                  _vm._s(_vm.$t("LBL_RESET_PASSWORD_EMAIL_INSTRUCTIONS")) +
                    " " +
                    _vm._s(_vm.user.user_email)
                )
              ]),
          _vm._v(" "),
          _vm.requestVerified === 0
            ? _c("div", [
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
                                  _vm.$set(
                                    _vm.code,
                                    "digit1",
                                    $event.target.value
                                  )
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
                                  _vm.$set(
                                    _vm.code,
                                    "digit2",
                                    $event.target.value
                                  )
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
                                  _vm.$set(
                                    _vm.code,
                                    "digit3",
                                    $event.target.value
                                  )
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
                                  _vm.$set(
                                    _vm.code,
                                    "digit4",
                                    $event.target.value
                                  )
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
                        [_vm._v(_vm._s(_vm.$t("BTN_VERIFY")))]
                      )
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "otp-block__footer" }, [
                    _c(
                      "p",
                      {
                        staticClass: "YK-Verify-Otp-timer",
                        attrs: { id: "timer" }
                      },
                      [
                        _vm._v(
                          _vm._s(_vm.$t("LBL_CODE_EXPIRES_IN")) +
                            ":\n                            "
                        ),
                        _c("span", {
                          staticClass: "txt-success font-weight-bold",
                          attrs: { id: "time" }
                        }),
                        _vm._v(
                          _vm._s(_vm.$t("LBL_SECONDS")) +
                            "\n                        "
                        )
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
                        _vm._v(_vm._s(_vm.$t("LBL_DIDNT_GET_CODE")) + " "),
                        _c(
                          "a",
                          {
                            staticClass: "txt-success font-weight-bold",
                            attrs: { href: "javascript:;", id: "resendOtpBtn" }
                          },
                          [
                            _vm._v(
                              "\n                                " +
                                _vm._s(_vm.$t("BTN_RESEND")) +
                                "!"
                            )
                          ]
                        )
                      ]
                    )
                  ])
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "otp-block YK-otpVerifiedSuccess d-none" },
                  [
                    _c("div", { staticClass: "otp-success" }, [
                      _vm.mode == "email"
                        ? _c("img", {
                            staticClass: "img",
                            attrs: {
                              "data-yk": "",
                              src:
                                _vm.baseUrl +
                                "/yokart/media/retina/otp-complete.svg",
                              alt: ""
                            }
                          })
                        : _c("img", {
                            staticClass: "img",
                            attrs: {
                              "data-yk": "",
                              src:
                                _vm.baseUrl +
                                "/yokart/media/retina/otp-complete.svg",
                              alt: ""
                            }
                          })
                    ])
                  ]
                )
              ])
            : _vm._e(),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "form-group form-floating__group password-field" },
            [
              _c("input", {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.formData.user_password,
                    expression: "formData.user_password"
                  }
                ],
                staticClass: "form-control form-floating__field",
                attrs: {
                  type: "password",
                  disabled: _vm.requestVerified === 0,
                  id: "user_password",
                  name: "user_password",
                  tabindex: "5",
                  autocomplete: "new-password"
                },
                domProps: { value: _vm.formData.user_password },
                on: {
                  focus: function($event) {
                    _vm.displayPswdInfo = 1
                  },
                  blur: function($event) {
                    _vm.displayPswdInfo = 0
                  },
                  keyup: function($event) {
                    return _vm.typeInPassword($event)
                  },
                  input: function($event) {
                    if ($event.target.composing) {
                      return
                    }
                    _vm.$set(_vm.formData, "user_password", $event.target.value)
                  }
                }
              }),
              _vm._v(" "),
              _c("label", { staticClass: "form-floating__label" }, [
                _vm._v(_vm._s(_vm.$t("LBL_PASSWORD")))
              ]),
              _vm._v(" "),
              typeof _vm.errors["user_password"] != "undefined"
                ? _c("span", { staticClass: "invalid-feedback d-block" }, [
                    _c("strong", [
                      _vm._v(_vm._s(_vm.$t(_vm.errors["user_password"][0])))
                    ])
                  ])
                : _vm._e(),
              _vm._v(" "),
              _c("i", { staticClass: "password-toggle" }, [
                !Object.values(_vm.passwordValidate).includes(false)
                  ? _c("img", {
                      staticClass: "YK-isValidPassword",
                      attrs: {
                        src: _vm.baseUrl + "/yokart/media/retina/check.svg",
                        width: "16px",
                        height: "16px",
                        "data-yk": "",
                        alt: ""
                      }
                    })
                  : _vm._e()
              ]),
              _vm._v(" "),
              _c(
                "div",
                {
                  style: _vm.displayPswdInfo == 1 && "display:block",
                  attrs: { id: "pswd_info" }
                },
                [
                  _c("h4", [
                    _vm._v(_vm._s(_vm.$t("LBL_PASSWORD_MUST_CONTAIN")) + ":")
                  ]),
                  _vm._v(" "),
                  _c("ul", [
                    _c(
                      "li",
                      {
                        class: [
                          _vm.passwordValidate.length === true
                            ? "valid"
                            : "invalid"
                        ],
                        attrs: { id: "length" }
                      },
                      [_vm._v(_vm._s(_vm.$t("MSG_VALIDATE_LENGTH")))]
                    ),
                    _vm._v(" "),
                    _c(
                      "li",
                      {
                        class: [
                          _vm.passwordValidate.letter === true
                            ? "valid"
                            : "invalid"
                        ],
                        attrs: { id: "letter" }
                      },
                      [_vm._v(_vm._s(_vm.$t("MSG_VALIDATE_LETTER")))]
                    ),
                    _vm._v(" "),
                    _c(
                      "li",
                      {
                        class: [
                          _vm.passwordValidate.capital === true
                            ? "valid"
                            : "invalid"
                        ],
                        attrs: { id: "capital" }
                      },
                      [_vm._v(_vm._s(_vm.$t("MSG_VALIDATE_UPPERCASE")))]
                    ),
                    _vm._v(" "),
                    _c(
                      "li",
                      {
                        class: [
                          _vm.passwordValidate.number === true
                            ? "valid"
                            : "invalid"
                        ],
                        attrs: { id: "number" }
                      },
                      [_vm._v(_vm._s(_vm.$t("MSG_VALIDATE_NUMBER")))]
                    ),
                    _vm._v(" "),
                    _c(
                      "li",
                      {
                        class: [
                          _vm.passwordValidate.special === true
                            ? "valid"
                            : "invalid"
                        ],
                        attrs: { id: "special" }
                      },
                      [_vm._v(_vm._s(_vm.$t("MSG_VALIDATE_SPECIAL_CHAR")))]
                    )
                  ])
                ]
              )
            ]
          ),
          _vm._v(" "),
          _c("div", { staticClass: "form-group form-floating__group" }, [
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.formData.user_password_confirmation,
                  expression: "formData.user_password_confirmation"
                }
              ],
              staticClass: "form-control form-floating__field",
              attrs: {
                disabled: _vm.requestVerified === 0,
                id: "user_password-confirm",
                type: "password",
                name: "user_password_confirmation",
                autocomplete: "no",
                tabindex: "6"
              },
              domProps: { value: _vm.formData.user_password_confirmation },
              on: {
                keyup: _vm.typeInConfirmPassword,
                input: function($event) {
                  if ($event.target.composing) {
                    return
                  }
                  _vm.$set(
                    _vm.formData,
                    "user_password_confirmation",
                    $event.target.value
                  )
                }
              }
            }),
            _vm._v(" "),
            _c("label", { staticClass: "form-floating__label" }, [
              _vm._v(_vm._s(_vm.$t("LBL_CONFIRM_PASSWORD")))
            ]),
            _vm._v(" "),
            _vm.validConfirmPassword !== ""
              ? _c("i", { staticClass: "password-toggle" }, [
                  _vm.validConfirmPassword === 0
                    ? _c("img", {
                        staticClass: "YK-isInvalidConfirmPassword",
                        attrs: {
                          src: _vm.baseUrl + "/yokart/media/retina/cancel.svg",
                          width: "16px",
                          height: "16px",
                          "data-yk": "",
                          alt: ""
                        }
                      })
                    : _vm.validConfirmPassword === 1
                    ? _c("img", {
                        staticClass: "YK-isValidConfirmPassword",
                        attrs: {
                          src: _vm.baseUrl + "/yokart/media/retina/check.svg",
                          width: "16px",
                          height: "16px",
                          "data-yk": "",
                          alt: ""
                        }
                      })
                    : _vm._e()
                ])
              : _vm._e()
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
                    class: _vm.resetClicked == 1 && "gb-is-loading",
                    attrs: {
                      type: "button",
                      name: "submit-button",
                      disabled:
                        !_vm.isResetComplete ||
                        _vm.resetClicked == 1 ||
                        _vm.requestVerified === 0
                    },
                    on: {
                      click: function($event) {
                        $event.stopPropagation()
                        return _vm.forgotPassword()
                      }
                    }
                  },
                  [
                    _vm._v(_vm._s(_vm.$t("BTN_RESET_PASSWORD_SUBMIT")) + " "),
                    _c("i", { staticClass: "arrow la la-long-arrow-right" })
                  ]
                )
              ])
            ])
          ])
        ]
      )
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "login-foot" }, [
      _c(
        "p",
        { staticClass: "no-account" },
        [
          _c(
            "inertia-link",
            { staticClass: "link", attrs: { href: _vm.baseUrl + "/login" } },
            [_vm._v(_vm._s(_vm.$t("LNK_LOGIN")) + "?")]
          ),
          _vm._v("   |   \n            "),
          _c(
            "inertia-link",
            { staticClass: "link", attrs: { href: _vm.baseUrl + "/register" } },
            [_vm._v(_vm._s(_vm.$t("LNK_REGISTER")) + "?")]
          )
        ],
        1
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

/***/ "./resources/js/frontend/Auth/ResetPassword.vue":
/*!******************************************************!*\
  !*** ./resources/js/frontend/Auth/ResetPassword.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ResetPassword_vue_vue_type_template_id_164be0f2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ResetPassword.vue?vue&type=template&id=164be0f2& */ "./resources/js/frontend/Auth/ResetPassword.vue?vue&type=template&id=164be0f2&");
/* harmony import */ var _ResetPassword_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ResetPassword.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Auth/ResetPassword.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ResetPassword_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ResetPassword_vue_vue_type_template_id_164be0f2___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ResetPassword_vue_vue_type_template_id_164be0f2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Auth/ResetPassword.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Auth/ResetPassword.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/frontend/Auth/ResetPassword.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ResetPassword_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ResetPassword.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/ResetPassword.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ResetPassword_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Auth/ResetPassword.vue?vue&type=template&id=164be0f2&":
/*!*************************************************************************************!*\
  !*** ./resources/js/frontend/Auth/ResetPassword.vue?vue&type=template&id=164be0f2& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ResetPassword_vue_vue_type_template_id_164be0f2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ResetPassword.vue?vue&type=template&id=164be0f2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/ResetPassword.vue?vue&type=template&id=164be0f2&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ResetPassword_vue_vue_type_template_id_164be0f2___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ResetPassword_vue_vue_type_template_id_164be0f2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);