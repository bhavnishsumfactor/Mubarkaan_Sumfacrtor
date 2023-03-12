(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[20],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue?vue&type=script&lang=js& ***!
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    },
    SizeChart: function SizeChart() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/SizeChart$")("./".concat(currentTheme, "/Product/SizeChart"));
    }
  },
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      isFav: false,
      sending: false,
      qty: 1,
      decreaseCls: "",
      selectedOption: {},
      increaseCls: ""
    };
  },
  props: ["product", "rating", "sharethisNetworks", "pageUrl", "sizeExist", "sizeChartImage", "allVarients", "selectedVarient", "otherVarients", "options", "shipping"],
  computed: {
    min: function min() {
      return this.product.prod_min_order_qty > 0 ? this.product.prod_min_order_qty : 1;
    },
    max: function max() {
      return this.product.prod_max_order_qty < this.product.totalstock ? this.product.prod_max_order_qty : this.product.totalstock;
    }
  },
  methods: {
    updateFavorite: function updateFavorite() {
      var _this = this;

      var thisObj = this;
      var formData = this.$serializeData({
        id: this.product.prod_id,
        code: this.product.pov_code
      });
      this.$axios.post(baseUrl + "/product/favourite", formData).then(function (response) {
        if (response.data.status == false) {
          thisObj.$bvModal.show("loginModal");
          return false;
        }

        var result = response.data.data.product;
        var fav_id = null;
        var fav_code = null;

        if (result) {
          fav_id = result.favId;
          fav_code = result.ufp_pov_code;
        }

        _this.product.favId = fav_id;
        _this.product.ufp_pov_code = fav_code;
        _this.isFav = _this.checkFavorite(_this.product);
      });
    },
    addToCart: function addToCart() {
      this.sending = true;
      var thisObj = this;

      if (this.$productStockVerify(this.product) != false && this.shipping == true) {
        this.$addToCart(this.product.prod_id, this.product.pov_code, false, true);
        setTimeout(function () {
          thisObj.sending = false;
        }, 1500);
      } else {
        this.sending = false;
        return false;
      }
    },
    increaseQty: function increaseQty(event) {
      if (this.$productStockVerify(this.product) == false) {
        return false;
      }

      if (this.decreaseCls == "disabled") {
        this.decreaseCls = "";
      }

      if (this.qty >= parseInt(this.max)) {
        return false;
      }

      var newVal = parseInt(this.qty) + 1;

      if (newVal == parseInt(this.max)) {
        this.increaseCls = "disabled";
      }

      this.qty = newVal;
    },
    decreaseQty: function decreaseQty(event) {
      if (this.$productStockVerify(this.product) == false) {
        return false;
      }

      if (this.increaseCls == "disabled") {
        this.increaseCls = "";
      }

      if (this.qty <= parseInt(this.min)) {
        return false;
      }

      var newVal = parseInt(this.qty) - 1;

      if (newVal == parseInt(this.min)) {
        this.decreaseCls = "disabled";
      }

      this.qty = newVal;
    },
    getVarient: function getVarient(varientCode) {
      var _this2 = this;

      this.$emit("selectedVarient", varientCode);
      setTimeout(function () {
        _this2.isFav = _this2.checkFavorite(_this2.product);
      }, 1000);
    },
    checkFavorite: function checkFavorite(product) {
      if (product.favId && (product.ufp_pov_code == "" || product.ufp_pov_code == product.pov_code)) {
        return true;
      }

      return false;
    },
    askQuestions: function askQuestions(id, code) {
      if (this.$auth() === null) {
        this.$bvModal.show("loginModal");
        return;
      }

      this.$bvModal.show("askQuestionModal");
      setTimeout(function () {
        floatingFormFix();
      }, 200);
    },
    updateOptions: function updateOptions() {
      var _this3 = this;

      this.selectedOption = {};
      var options = this.product.pov_code.split("|");
      Object.keys(this.options).forEach(function (key) {
        _this3.selectedOption[key] = [];
        Object.keys(_this3.options[key].poption_id).forEach(function (op) {
          if (_this3.$inArray(_this3.options[key].poption_id[op], options)) {
            _this3.selectedOption[key] = _this3.options[key].poption_id[op];
          }
        });
      });
    }
  },
  updated: function updated() {
    this.isFav = this.checkFavorite(this.product);
  },
  mounted: function mounted() {
    this.isFav = this.checkFavorite(this.product);
    this.qty = this.product.prod_min_order_qty > 0 ? this.product.prod_min_order_qty : 1;

    if (this.$productStockVerify(this.product) == false) {
      this.decreaseCls = this.increaseCls = "disabled";
    }

    this.updateOptions();
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue?vue&type=template&id=165cb7e6&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue?vue&type=template&id=165cb7e6& ***!
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
    "div",
    { staticClass: "product-details" },
    [
      _vm.$configVal("PRODUCT_DETAIL_DISPLAY_BRAND") != 0 &&
      _vm.product.brand_name != ""
        ? _c("h6", { staticClass: "product__category" }, [
            _c(
              "a",
              {
                attrs: {
                  href: [
                    _vm.product.brand_id
                      ? _vm.baseUrl + "/brand/" + _vm.product.brand_id
                      : "javascript:;"
                  ]
                }
              },
              [_vm._v(_vm._s(_vm.product.brand_name))]
            )
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("h2", { staticClass: "product__title" }, [
        _vm._v(_vm._s(_vm.product.prod_name))
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "product__rating" }, [
        _c("div", { staticClass: "product__rating-stars" }, [
          _c(
            "div",
            { staticClass: "rating-holder" },
            [_c("rating-stars", { attrs: { rating: _vm.rating } })],
            1
          )
        ]),
        _vm._v(" "),
        _vm.rating != 0
          ? _c("div", { staticClass: "product__rating-count" }, [
              _c("span", [_vm._v("(" + _vm._s(_vm.rating) + " out of 5)")])
            ])
          : _vm._e()
      ]),
      _vm._v("\n  " + _vm._s(_vm.product.pov_code) + "\n  "),
      _vm._l(_vm.options, function(option, opIndex) {
        return _c("div", { key: opIndex, staticClass: "attribute-selectors" }, [
          _c("div", { staticClass: "options-articles" }, [
            _c(
              "ul",
              _vm._l(option.poption_id, function(optionData, opKey) {
                return _c("li", { key: opKey }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.selectedOption[opIndex],
                        expression: "selectedOption[opIndex]"
                      }
                    ],
                    staticClass: "control-input",
                    attrs: {
                      type: "radio",
                      name: "varient-" + opIndex,
                      "data-label": "black",
                      id: "checkbox_" + optionData
                    },
                    domProps: {
                      value: optionData,
                      checked: _vm._q(_vm.selectedOption[opIndex], optionData)
                    },
                    on: {
                      change: function($event) {
                        return _vm.$set(_vm.selectedOption, opIndex, optionData)
                      }
                    }
                  }),
                  _vm._v(" "),
                  option.option_type == 1
                    ? _c(
                        "label",
                        {
                          staticClass: "control-label control-label-color",
                          attrs: { for: "checkbox_" + optionData, title: opKey }
                        },
                        [
                          _c("span", {
                            staticClass: "color text-capitalize",
                            style:
                              "background-color:" +
                              (option.option_color[optionData]
                                ? option.option_color[optionData]
                                : opKey)
                          })
                        ]
                      )
                    : _c(
                        "label",
                        {
                          staticClass: "control-label",
                          attrs: { for: "checkbox_" + optionData }
                        },
                        [
                          _vm._v(
                            "\n            " + _vm._s(option) + "\n            "
                          ),
                          _c("span", { staticClass: "size text-capitalize" }, [
                            _vm._v(_vm._s(option.option_name[optionData]))
                          ])
                        ]
                      )
                ])
              }),
              0
            )
          ])
        ])
      }),
      _vm._v(" "),
      _c("div", { staticClass: "quantity" }, [
        _c("span", {
          staticClass: "decrease",
          class: [_vm.decreaseCls != "" ? _vm.decreaseCls : ""],
          on: {
            click: function($event) {
              return _vm.decreaseQty(this)
            }
          }
        }),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.qty,
              expression: "qty"
            }
          ],
          staticClass: "qty-input no-focus YK-qty",
          attrs: {
            max: _vm.max,
            min: _vm.min,
            "data-page": "product-view",
            title: _vm.$t("PLH_AVAILABLE_QUANTITY"),
            type: "text",
            name: "quantity",
            readonly: ""
          },
          domProps: { value: _vm.qty },
          on: {
            input: function($event) {
              if ($event.target.composing) {
                return
              }
              _vm.qty = $event.target.value
            }
          }
        }),
        _vm._v(" "),
        _c("span", {
          staticClass: "increase",
          class: [_vm.increaseCls != "" ? _vm.ncreaseCls : ""],
          on: {
            click: function($event) {
              return _vm.increaseQty(this)
            }
          }
        })
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "price-wrapper" }, [
        _c("div", { staticClass: "product__price" }, [
          _c("span", { staticClass: "product__price-new" }, [
            _vm._v(_vm._s(_vm.$priceFormat(_vm.product.finalprice)))
          ]),
          _vm._v(" "),
          parseFloat(_vm.product.price) !== parseFloat(_vm.product.finalprice)
            ? _c("span", { staticClass: "product__price-old" }, [
                _vm._v(_vm._s(_vm.$priceFormat(_vm.product.price)))
              ])
            : _vm._e()
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "tax-inclusive" }, [
          _vm._v(
            _vm._s(
              _vm.$configVal("PRICE_INCLUDING_TAX") == 1
                ? _vm.$t("LBL_INCLUDING") + " " + _vm.$t("LBL_X_OF_ALL_TAXES")
                : _vm.$t("LBL_EXCLUDING") + " " + _vm.$t("LBL_X_OF_ALL_TAXES")
            )
          )
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "product__cart" }, [
        _c(
          "button",
          _vm._b(
            {
              staticClass:
                "btn btn--black btn--lg btn--line btn-block d-flex justify-content-md-between justify-content-center",
              class: [_vm.sending == true ? "gb-is-loading" : ""],
              on: { click: _vm.addToCart }
            },
            "button",
            [
              _vm.$productStockVerify(_vm.product) == false &&
              _vm.shipping == false
                ? "disabled"
                : ""
            ],
            false
          ),
          [
            _vm._v(
              _vm._s(
                _vm.$productStockVerify(_vm.product) == false
                  ? _vm.$t("LBL_OUT_OF_STOCK")
                  : _vm.$t("BTN_ADD_TO_CART")
              )
            )
          ]
        )
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "product__action" }, [
        _c(
          "a",
          {
            staticClass: "add-wishlist",
            class: [_vm.isFav == true ? "active" : ""],
            attrs: { href: "javascript:void(0);" },
            on: {
              click: function($event) {
                return _vm.updateFavorite(_vm.product)
              }
            }
          },
          [
            _c("span", [
              _c(
                "svg",
                {
                  attrs: {
                    xmlns: "http://www.w3.org/2000/svg",
                    width: "15.258",
                    height: "13.5",
                    viewBox: "0 0 15.258 13.5"
                  }
                },
                [
                  _c("path", {
                    staticClass: "wishlist",
                    attrs: {
                      d:
                        "M15.02,5.558a3.62,3.62,0,0,0-5.121,0l-.7.7-.7-.7a3.621,3.621,0,0,0-5.121,5.121l.7.7L9.2,16.5l5.121-5.121.7-.7a3.62,3.62,0,0,0,0-5.121Z",
                      transform: "translate(-1.573 -3.747)"
                    }
                  })
                ]
              )
            ]),
            _vm._v("\n      Add To Wishlist\n    ")
          ]
        ),
        _vm._v(" "),
        _c(
          "a",
          {
            staticClass: "askquestion",
            attrs: { href: "javascript:void(0);" },
            on: {
              click: function($event) {
                return _vm.askQuestions(
                  _vm.product.prod_id,
                  _vm.product.pov_code
                )
              }
            }
          },
          [
            _c("span", [
              _c(
                "svg",
                {
                  attrs: {
                    xmlns: "http://www.w3.org/2000/svg",
                    width: "15.193",
                    height: "12",
                    viewBox: "0 0 15.193 12"
                  }
                },
                [
                  _c("path", {
                    attrs: {
                      d:
                        "M12.83,2.623A9.839,9.839,0,0,0,5.863,1.382,7.06,7.06,0,0,0,1.615,3.469a4.219,4.219,0,0,0,0,6.14C1.928,11.043,0,12.681,0,12.681a5.049,5.049,0,0,0,5.863-.976,9.85,9.85,0,0,0,6.967-1.244c3.146-2.008,3.156-5.819,0-7.838ZM7.76,10.448a8.956,8.956,0,0,1-2.324-.3l-.521.5a4.741,4.741,0,0,1-.965.722,3.776,3.776,0,0,1-1.364.389c.026-.047.05-.094.073-.138a3.328,3.328,0,0,0,.422-2.6A3.2,3.2,0,0,1,1.708,6.539c0-2.163,2.71-3.915,6.051-3.915s6.051,1.752,6.051,3.915S11.1,10.448,7.76,10.448ZM4.857,7.47a.9.9,0,1,1,.889-.918v.013a.9.9,0,0,1-.889.905ZM6.8,6.58A.9.9,0,0,1,8.61,6.549v.016A.9.9,0,0,1,6.8,6.58Zm3.782.889a.9.9,0,1,1,.889-.918v.013a.894.894,0,0,1-.889.905Z",
                      transform: "translate(0 -1.216)"
                    }
                  })
                ]
              )
            ]),
            _vm._v("\n      " + _vm._s(_vm.$t("LBL_ASK_A_QUESTION")) + "\n    ")
          ]
        )
      ]),
      _vm._v(" "),
      _c("social-share", {
        attrs: {
          sharethisNetworks: _vm.sharethisNetworks,
          productName: _vm.product.prod_name,
          pageUrl: _vm.pageUrl
        }
      }),
      _vm._v(" "),
      _c("ask-question", {
        attrs: {
          productId: _vm.product.prod_id,
          varientCode: _vm.product.pov_code
        }
      }),
      _vm._v(" "),
      _c("size-chart", { attrs: { sizeChartImage: _vm.sizeChartImage } })
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



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
		135
	],
	"./fashion/Product/AskQuestion": [
		"./resources/js/frontend/Themes/fashion/Product/AskQuestion.vue",
		143
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
		139
	],
	"./fashion/Product/RatingStars": [
		"./resources/js/frontend/Themes/fashion/Product/RatingStars.vue",
		146
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

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/SizeChart$":
/*!*****************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/SizeChart$ namespace object ***!
  \*****************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/SizeChart": [
		"./resources/js/frontend/Themes/base/Product/SizeChart.vue",
		140
	],
	"./fashion/Product/SizeChart": [
		"./resources/js/frontend/Themes/fashion/Product/SizeChart.vue",
		147
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/SizeChart$";
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
		141
	],
	"./fashion/Product/SocialShare": [
		"./resources/js/frontend/Themes/fashion/Product/SocialShare.vue",
		149
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

/***/ "./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue":
/*!***************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue ***!
  \***************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AddToCartSection_vue_vue_type_template_id_165cb7e6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AddToCartSection.vue?vue&type=template&id=165cb7e6& */ "./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue?vue&type=template&id=165cb7e6&");
/* harmony import */ var _AddToCartSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AddToCartSection.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AddToCartSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AddToCartSection_vue_vue_type_template_id_165cb7e6___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AddToCartSection_vue_vue_type_template_id_165cb7e6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AddToCartSection.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue?vue&type=template&id=165cb7e6&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue?vue&type=template&id=165cb7e6& ***!
  \**********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_template_id_165cb7e6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AddToCartSection.vue?vue&type=template&id=165cb7e6& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue?vue&type=template&id=165cb7e6&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_template_id_165cb7e6___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AddToCartSection_vue_vue_type_template_id_165cb7e6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);