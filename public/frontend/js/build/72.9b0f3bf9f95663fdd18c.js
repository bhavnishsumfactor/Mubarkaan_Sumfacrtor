(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[72],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    RatingStars: function RatingStars() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RatingStars$")("./".concat(currentTheme, "/Product/RatingStars"));
    },
    AskQuestion: function AskQuestion() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/AskQuestion$")("./".concat(currentTheme, "/Product/AskQuestion"));
    },
    SocialShare: function SocialShare() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/SocialShare$")("./".concat(currentTheme, "/Product/SocialShare"));
    }
  },
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      isFav: false
    };
  },
  props: ["product", "rating", "sharethisNetworks", "pageUrl", "sizeExist", "allVarients", "selectedVarient", "otherVarients", "shipping"],
  computed: {
    min: function min() {
      return product.prod_min_order_qty > 0 ? product.prod_min_order_qty : 1;
    },
    max: function max() {
      return product.prod_max_order_qty < product.totalstock ? product.prod_max_order_qty : product.totalstock;
    }
  },
  methods: {
    getWhiteClass: function getWhiteClass(selectedoption) {
      // let whiteClass = "";
      // if (selectedoption.option_type == 1) {
      //     if (selectedoption.opn_color_code == "#FFF" || selectedoption.opn_color_code == "#FFFFFF" || selectedoption.opn_value == 'white') {
      //         whiteClass = "color-shadow";
      //     }
      // }
      // return whiteClass;
      return selectedoption.whiteClass;
    },
    getDisplayCode: function getDisplayCode(selectedoption) {
      // let displayCode = "";
      // if (typeof selectedoption.opn_color_code != 'undefined' && selectedoption.opn_color_code != '') {
      //     displayCode = selectedoption.opn_color_code;
      // } else {
      //     displayCode = selectedoption.opn_value;
      // }
      // return displayCode;
      return selectedoption.displayCode;
    },
    getVarient: function getVarient(varientCode) {
      var _this = this;

      this.$emit("selectedVarient", varientCode);
      setTimeout(function () {
        _this.isFav = _this.checkFavorite(_this.product);
      }, 1000);
    },
    updateFavorite: function updateFavorite() {
      var _this2 = this;

      var formData = this.$serializeData({
        id: this.product.prod_id,
        code: this.product.pov_code
      });
      this.$axios.post(baseUrl + "/product/favourite", formData).then(function (response) {
        if (response.data.status == false) {
          return false;
        }

        var result = response.data.data.product;
        var fav_id = null;
        var fav_code = null;

        if (result) {
          fav_id = result.favId;
          fav_code = result.ufp_pov_code;
        }

        _this2.product.favId = fav_id;
        _this2.product.ufp_pov_code = fav_code;
        _this2.isFav = _this2.checkFavorite(_this2.product);
      });
    },
    checkFavorite: function checkFavorite(product) {
      if (product.favId && (product.ufp_pov_code == "" || product.ufp_pov_code == product.pov_code)) {
        return true;
      }

      return false;
    },
    increaseQty: function increaseQty(event) {
      var cartid = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
      console.log(this.$productStockVerify(this.product));

      if (this.$productStockVerify(this.product) == false) {
        return false;
      }

      var qtyobj = $(event).closest('.YK-quantity').find('.YK-qty');
      var decobj = $(event).closest('.YK-quantity').find('.decrease');
      var qtyVal = this.getQtyVal();

      if ($(decobj).hasClass('disabled')) {
        $(decobj).removeClass("disabled");
      }

      console.log(qtyVal, parseInt(qtyobj.attr('max')));

      if (qtyVal >= parseInt(qtyobj.attr('max'))) {
        return false;
      }

      ;
      var newVal = parseInt(qtyVal) + 1;

      if (newVal == parseInt(qtyobj.attr('max'))) {
        $(event).addClass("disabled");
      }

      qtyobj.val(newVal);

      if (cartid != '') {
        this.cartUpdate(qtyobj, cartid, newVal);
      }
    },
    decreaseQty: function decreaseQty(event) {
      var cartid = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';

      if (this.$productStockVerify(this.product) == false) {
        return false;
      }

      var qtyobj = $(event).closest('.YK-quantity').find('.YK-qty');
      var incobj = $(event).closest('.YK-quantity').find('.increase');
      var qtyVal = qtyobj.val();

      if ($(incobj).hasClass('disabled')) {
        $(incobj).removeClass("disabled");
      }

      if (qtyVal <= parseInt(qtyobj.attr('min'))) {
        return false;
      }

      ;
      var newVal = parseInt(qtyVal) - 1;

      if (newVal == parseInt(qtyobj.attr('min'))) {
        $(event).addClass("disabled");
      }

      qtyobj.val(newVal);

      if (cartid != '') {
        this.cartUpdate(qtyobj, cartid, newVal);
      }
    },
    cartUpdate: function cartUpdate(event, id, qty) {
      var formData = this.$serializeData({
        cartId: id,
        qty: qty
      });
      this.$axios.post(baseUrl + "/cart/update", formData).then(function (response) {
        if (response.data.status == false) {
          event.val(response.data.data);
          toastr.error(response.data.message);
          return false;
        }

        Object.keys(response.data.data).forEach(function (key) {
          $('.YK-' + key).html(response.data.data[key]);
        });
      });
    },
    addToCart: function addToCart() {
      if (this.$productStockVerify(this.product) != false && this.shipping == true) {
        this.$addToCart(this.product.prod_id, this.product.pov_code, false, true);
      } else {
        return false;
      }
    },
    askQuestions: function askQuestions(id, code) {
      var thisObj = this;
      code = code != '' ? code : 0;
      NProgress.start();
      $.get(baseUrl + '/product/' + id + '/' + code + '/ask-questions', function (response) {
        NProgress.done();

        if (response.status == true) {
          thisObj.$bvModal.show("askQuestionModal");
          setTimeout(function () {
            floatingFormFix();
          }, 200);
        }
      });
    }
  },
  updated: function updated() {
    this.isFav = this.checkFavorite(this.product);
  },
  mounted: function mounted() {
    this.isFav = this.checkFavorite(this.product);
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! toastr */ "./node_modules/toastr/toastr.js")))

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=template&id=df19415e&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=template&id=df19415e& ***!
  \*************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {}
var staticRenderFns = []



/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/AskQuestion$":
/*!*******************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/AskQuestion$ namespace object ***!
  \*******************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/AskQuestion": [
		"./resources/js/frontend/Themes/base/Product/AskQuestion.vue",
		66
	]
};
function webpackAsyncContext(req) {
	if(!__webpack_require__.o(map, req)) {
		return Promise.resolve().then(function() {
			var e = new Error("Cannot find module '" + req + "'");
			e.code = 'MODULE_NOT_FOUND';
			throw e;
		});
	}

	var ids = map[req], id = ids[0];
	return __webpack_require__.e(ids[1]).then(function() {
		return __webpack_require__(id);
	});
}
webpackAsyncContext.keys = function webpackAsyncContextKeys() {
	return Object.keys(map);
};
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/AskQuestion$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RatingStars$":
/*!*******************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/RatingStars$ namespace object ***!
  \*******************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/RatingStars": [
		"./resources/js/frontend/Themes/base/Product/RatingStars.vue",
		69
	]
};
function webpackAsyncContext(req) {
	if(!__webpack_require__.o(map, req)) {
		return Promise.resolve().then(function() {
			var e = new Error("Cannot find module '" + req + "'");
			e.code = 'MODULE_NOT_FOUND';
			throw e;
		});
	}

	var ids = map[req], id = ids[0];
	return __webpack_require__.e(ids[1]).then(function() {
		return __webpack_require__(id);
	});
}
webpackAsyncContext.keys = function webpackAsyncContextKeys() {
	return Object.keys(map);
};
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RatingStars$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/SocialShare$":
/*!*******************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/SocialShare$ namespace object ***!
  \*******************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/SocialShare": [
		"./resources/js/frontend/Themes/base/Product/SocialShare.vue",
		71
	]
};
function webpackAsyncContext(req) {
	if(!__webpack_require__.o(map, req)) {
		return Promise.resolve().then(function() {
			var e = new Error("Cannot find module '" + req + "'");
			e.code = 'MODULE_NOT_FOUND';
			throw e;
		});
	}

	var ids = map[req], id = ids[0];
	return __webpack_require__.e(ids[1]).then(function() {
		return __webpack_require__(id);
	});
}
webpackAsyncContext.keys = function webpackAsyncContextKeys() {
	return Object.keys(map);
};
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/SocialShare$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/AddToCartSection.vue":
/*!************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/AddToCartSection.vue ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AddToCartSection_vue_vue_type_template_id_df19415e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AddToCartSection.vue?vue&type=template&id=df19415e& */ "./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=template&id=df19415e&");
/* harmony import */ var _AddToCartSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AddToCartSection.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AddToCartSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AddToCartSection_vue_vue_type_template_id_df19415e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AddToCartSection_vue_vue_type_template_id_df19415e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/base/Product/AddToCartSection.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AddToCartSection.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=template&id=df19415e&":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=template&id=df19415e& ***!
  \*******************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_template_id_df19415e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AddToCartSection.vue?vue&type=template&id=df19415e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/AddToCartSection.vue?vue&type=template&id=df19415e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_template_id_df19415e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_template_id_df19415e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);