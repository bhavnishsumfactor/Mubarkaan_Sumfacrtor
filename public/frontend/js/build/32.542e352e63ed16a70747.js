(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[32],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/OrderSummary.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/OrderSummary.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  props: ["order", "billAddress", "paymentTypes", "addressTypes", "shippingTypes", "phone", "success", "aspectRatio"],
  data: function data() {
    return {
      baseUrl: baseUrl,
      subtotal: 0,
      shipping: 0,
      tax: 0,
      discount: 0,
      reward: 0,
      gift: 0,
      subTotal: 0,
      total: 0
    };
  },
  methods: {
    addressIconUrl: function addressIconUrl(type) {
      if (type == 1) {
        return baseUrl + '/yokart/media/retina/sprite.svg#billing-detail';
      } else if (type == 2) {
        return baseUrl + '/yokart/media/retina/sprite.svg#shipping';
      } else if (type == 3) {
        return baseUrl + '/yokart/media/retina/sprite.svg#pickup';
      }
    },
    calcaulateQuanity: function calcaulateQuanity(product) {
      return product.cancel_request && product.cancel_request.orrequest_status ? product.op_qty - product.cancel_request.orrequest_qty : product.op_qty;
    },
    printWindow: function printWindow() {
      window.print();
    },
    calcaulateTotal: function calcaulateTotal() {
      this.tax = this.order.order_tax_charged;
      this.shipping = this.order.order_shipping_value;
      this.discount = this.order.order_discount_amount;
      this.reward = this.order.order_reward_amount;
      this.gift = this.order.order_gift_amount;
      this.total = this.order.order_net_amount;
      this.subTotal = parseFloat(this.total - this.tax - this.shipping - this.gift) + parseFloat(parseFloat(this.discount) + parseFloat(this.reward));
      var returnSubtotal = 0;
      var returnTax = 0;
      var returnDiscount = 0;
      var returnGiftwrap = 0;
      var returnReward = 0;
      var returnShipping = 0;
      var block = this.order.products;
      Object.keys(this.order.products).forEach(function (pkey) {
        if (block[pkey].cancel_request) {
          returnSubtotal += parseFloat(block[pkey].op_product_price * block[pkey].cancel_request.orrequest_qty);
          returnTax += parseFloat(block[pkey].cancel_request.oramount_tax);
          returnDiscount += parseFloat(block[pkey].cancel_request.oramount_discount);
          returnReward += parseFloat(block[pkey].cancel_request.oramount_reward_price);
          returnShipping += parseFloat(block[pkey].cancel_request.oramount_shipping);

          if (block[pkey].cancel_request.oramount_giftwrap_price != 0) {
            returnGiftwrap += parseFloat(block[pkey].cancel_request.oramount_giftwrap_price);
          }

          if (block[pkey].cancel_request.orrequest_status == 2 && block[pkey].cancel_request.oramount_giftwrap_price != 0) {
            refundedGiftwrap += parseFloat(block[pkey].cancel_request.oramount_giftwrap_price);
          }
        }
      });

      if (returnSubtotal != 0) {
        this.subTotal = this.subTotal - returnSubtotal;
        this.tax = this.tax - returnTax;
        this.shipping = this.shipping - returnShipping;
        this.discount = this.discount - returnDiscount;
        this.reward = this.reward - returnReward;
        this.gift = this.gift - Math.abs(returnGiftwrap);
        var total = parseFloat(returnSubtotal) + parseFloat(returnTax) + parseFloat(returnShipping);

        if (returnDiscount != 0) {
          total = total - returnDiscount;
        }

        if (this.returnReward != 0) {
          total = total - returnReward;
        }

        if (this.returnGiftwrap != 0) {
          if (this.returnGiftwrap > 0) {
            total = parseFloat(total) + parseFloat(Math.abs(returnGiftwrap));
          }
        }

        if (total < 0) {
          total = 0;
        }

        this.total = this.total - total;
      }
    }
  },
  mounted: function mounted() {
    this.calcaulateTotal();
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/OrderSummary.vue?vue&type=template&id=62bfd767&":
/*!*************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/OrderSummary.vue?vue&type=template&id=62bfd767& ***!
  \*************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {}
var staticRenderFns = []



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

/***/ "./resources/js/frontend/OrderSummary.vue":
/*!************************************************!*\
  !*** ./resources/js/frontend/OrderSummary.vue ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _OrderSummary_vue_vue_type_template_id_62bfd767___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./OrderSummary.vue?vue&type=template&id=62bfd767& */ "./resources/js/frontend/OrderSummary.vue?vue&type=template&id=62bfd767&");
/* harmony import */ var _OrderSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./OrderSummary.vue?vue&type=script&lang=js& */ "./resources/js/frontend/OrderSummary.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _OrderSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _OrderSummary_vue_vue_type_template_id_62bfd767___WEBPACK_IMPORTED_MODULE_0__["render"],
  _OrderSummary_vue_vue_type_template_id_62bfd767___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/OrderSummary.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/OrderSummary.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/frontend/OrderSummary.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./OrderSummary.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/OrderSummary.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderSummary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/OrderSummary.vue?vue&type=template&id=62bfd767&":
/*!*******************************************************************************!*\
  !*** ./resources/js/frontend/OrderSummary.vue?vue&type=template&id=62bfd767& ***!
  \*******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderSummary_vue_vue_type_template_id_62bfd767___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./OrderSummary.vue?vue&type=template&id=62bfd767& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/OrderSummary.vue?vue&type=template&id=62bfd767&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderSummary_vue_vue_type_template_id_62bfd767___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OrderSummary_vue_vue_type_template_id_62bfd767___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);