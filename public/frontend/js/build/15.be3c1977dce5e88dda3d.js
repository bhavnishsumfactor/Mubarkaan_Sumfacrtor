(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[15],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/LoginPopup.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Auth/LoginPopup.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************/
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
var defaultUsername = 'tribe@dummyid.com';
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      mode: 'email',
      clicked: 0,
      unmaskedPhoneNumber: '',
      formData: {
        username: defaultUsername,
        password: "Tribe@123",
        user_phone: "",
        user_phone_country_code: "",
        remember: ""
      }
    };
  },
  props: ["smsActivePackage", "defaultCountry", "ratio", "darkLogo", "logo", "queryParams"],
  methods: {
    login: function login() {
      var _this = this;

      this.clicked = 1;

      if (this.unmaskedPhoneNumber != '') {
        this.formData.user_phone = this.unmaskedPhoneNumber;
      }

      var submitData = this.$serializeData(this.formData);
      submitData.append('via', this.mode);
      this.$axios.post(baseUrl + "/login", submitData).then(function (response) {
        _this.clicked = 0;

        if (response.data.status === true) {
          window.location.href = baseUrl;
        } else {
          if (typeof response.data.data.url != 'undefined' && response.data.data.url != "") {
            _this.$inertia.visit(baseUrl + response.data.data.url);
          } else {
            toastr.error(response.data.message, "", toastr.options);
          }
        }
      }, function (error) {
        _this.clicked = 0; // this.validateErrors(error);
      });
    },
    rememberMe: function rememberMe(event) {
      this.formData.remember = event.target.value;
    },
    changeCountry: function changeCountry(data) {
      this.formData.user_phone_country_code = data.iso2;
    },
    onPhoneNumberChange: function onPhoneNumberChange(data, obj) {
      this.formData.user_phone_country_code = obj.country.iso2;
      this.unmaskedPhoneNumber = obj.number.significant;
    },
    validateErrors: function validateErrors(response) {
      var _this2 = this;

      var jsondata = response.data;
      Object.keys(jsondata.errors).forEach(function (key) {
        _this2.errors.add({
          field: key,
          msg: jsondata.errors[key][0]
        });
      });
    },
    switchMode: function switchMode() {
      if (this.mode == 'email') {
        this.mode = 'phone';
        this.formData.username = "";
      } else {
        this.formData.username = defaultUsername;
        this.mode = 'email';
      }

      setTimeout(function () {
        floatingFormFix();
      }, 200);
    },
    socialLink: function socialLink(type) {
      return "window.location.href = '" + baseUrl + "/redirect/" + type + "/" + this.queryParams + "'";
    }
  },
  computed: {
    isComplete: function isComplete() {
      return this.formData.username && this.formData.password || this.formData.user_phone && this.formData.password;
    }
  },
  mounted: function mounted() {
    setTimeout(function () {
      floatingFormFix();
    }, 200);
    var togglePassword = document.querySelector('#togglePassword');
    var password = document.querySelector('#password');
    togglePassword.addEventListener('click', function (e) {
      // toggle the type attribute
      var type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type); // toggle the eye slash icon

      this.classList.toggle('fa-eye-slash');
    });
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! toastr */ "./node_modules/toastr/toastr.js")))

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/LoginPopup.vue?vue&type=style&index=0&id=614f80ea&lang=css&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Auth/LoginPopup.vue?vue&type=style&index=0&id=614f80ea&lang=css&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.vue-tel-input[data-v-614f80ea] {\n    flex: 1;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/LoginPopup.vue?vue&type=style&index=0&id=614f80ea&lang=css&scoped=true&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Auth/LoginPopup.vue?vue&type=style&index=0&id=614f80ea&lang=css&scoped=true& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--5-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--5-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./LoginPopup.vue?vue&type=style&index=0&id=614f80ea&lang=css&scoped=true& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/LoginPopup.vue?vue&type=style&index=0&id=614f80ea&lang=css&scoped=true&");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/LoginPopup.vue?vue&type=template&id=614f80ea&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Auth/LoginPopup.vue?vue&type=template&id=614f80ea&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************/
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
  return _c("b-modal", {
    attrs: {
      id: "loginModal",
      centered: "",
      title: "BootstrapVue",
      "header-class": "border-0",
      "hide-footer": ""
    },
    scopedSlots: _vm._u([
      {
        key: "modal-header",
        fn: function() {
          return [
            _c(
              "button",
              {
                staticClass: "close",
                attrs: { type: "button" },
                on: {
                  click: function($event) {
                    return _vm.$bvModal.hide("subscribedModal")
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
  })
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

/***/ "./resources/js/frontend/Auth/LoginPopup.vue":
/*!***************************************************!*\
  !*** ./resources/js/frontend/Auth/LoginPopup.vue ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _LoginPopup_vue_vue_type_template_id_614f80ea_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LoginPopup.vue?vue&type=template&id=614f80ea&scoped=true& */ "./resources/js/frontend/Auth/LoginPopup.vue?vue&type=template&id=614f80ea&scoped=true&");
/* harmony import */ var _LoginPopup_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LoginPopup.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Auth/LoginPopup.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _LoginPopup_vue_vue_type_style_index_0_id_614f80ea_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./LoginPopup.vue?vue&type=style&index=0&id=614f80ea&lang=css&scoped=true& */ "./resources/js/frontend/Auth/LoginPopup.vue?vue&type=style&index=0&id=614f80ea&lang=css&scoped=true&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _LoginPopup_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _LoginPopup_vue_vue_type_template_id_614f80ea_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _LoginPopup_vue_vue_type_template_id_614f80ea_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "614f80ea",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Auth/LoginPopup.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Auth/LoginPopup.vue?vue&type=script&lang=js&":
/*!****************************************************************************!*\
  !*** ./resources/js/frontend/Auth/LoginPopup.vue?vue&type=script&lang=js& ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginPopup_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./LoginPopup.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/LoginPopup.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginPopup_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Auth/LoginPopup.vue?vue&type=style&index=0&id=614f80ea&lang=css&scoped=true&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/frontend/Auth/LoginPopup.vue?vue&type=style&index=0&id=614f80ea&lang=css&scoped=true& ***!
  \************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginPopup_vue_vue_type_style_index_0_id_614f80ea_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--5-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--5-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./LoginPopup.vue?vue&type=style&index=0&id=614f80ea&lang=css&scoped=true& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/LoginPopup.vue?vue&type=style&index=0&id=614f80ea&lang=css&scoped=true&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginPopup_vue_vue_type_style_index_0_id_614f80ea_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginPopup_vue_vue_type_style_index_0_id_614f80ea_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginPopup_vue_vue_type_style_index_0_id_614f80ea_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginPopup_vue_vue_type_style_index_0_id_614f80ea_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/frontend/Auth/LoginPopup.vue?vue&type=template&id=614f80ea&scoped=true&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/frontend/Auth/LoginPopup.vue?vue&type=template&id=614f80ea&scoped=true& ***!
  \**********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginPopup_vue_vue_type_template_id_614f80ea_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./LoginPopup.vue?vue&type=template&id=614f80ea&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Auth/LoginPopup.vue?vue&type=template&id=614f80ea&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginPopup_vue_vue_type_template_id_614f80ea_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoginPopup_vue_vue_type_template_id_614f80ea_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);