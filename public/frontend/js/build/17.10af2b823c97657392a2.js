(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[17],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Review/Index.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/Review/Index.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    RatingSummary: function RatingSummary() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/RatingSummary$")("./".concat(currentTheme, "/Product/Review/RatingSummary"));
    },
    RatingStars: function RatingStars() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RatingStars$")("./".concat(currentTheme, "/Product/RatingStars"));
    },
    NoReviews: function NoReviews() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/NoReviews$")("./".concat(currentTheme, "/Product/Review/NoReviews"));
    },
    ReviewList: function ReviewList() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/List$")("./".concat(currentTheme, "/Product/Review/List"));
    },
    CantPostReview: function CantPostReview() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/CantPostReview$")("./".concat(currentTheme, "/Product/Review/CantPostReview"));
    }
  },
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      page: 1,
      sort: "",
      reviews: [],
      pagination: [],
      avgRating: "",
      oneRating: "",
      twoRating: "",
      threeRating: "",
      fourRating: "",
      fiveRating: "",
      totalRating: "",
      reviewSectionLoaded: false,
      canPost: "",
      reviewsSort: {
        Newest: this.$t("LNK_FILTER_NEWEST"),
        Oldest: this.$t("LNK_FILTER_OLDEST"),
        MostHelpful: this.$t("LNK_FILTER_MOST_HELPFUL")
      }
    };
  },
  props: ["product"],
  methods: {
    getReviews: function getReviews() {
      var _this = this;

      var sortType = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "";
      var page = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "";

      if (sortType != "") {
        this.sort = sortType;
      }

      if (page != "") {
        this.page = page;
      }

      var formData = this.$serializeData({
        id: this.product.prod_id,
        sort: this.sort
      });
      this.$axios.post(baseUrl + "/product-reviews/get?page=" + this.page, formData).then(function (response) {
        _this.reviews = response.data.data.reviews.data;
        _this.pagination = response.data.data.reviews;
        _this.totalRating = response.data.data.totalRating;
        _this.avgRating = response.data.data.avgRating;
        _this.oneRating = response.data.data.oneRating;
        _this.twoRating = response.data.data.twoRating;
        _this.threeRating = response.data.data.threeRating;
        _this.fourRating = response.data.data.fourRating;
        _this.fiveRating = response.data.data.fiveRating;
        _this.canPost = response.data.data.can_post;
        _this.reviewSectionLoaded = true;
      });
    },
    writeAReview: function writeAReview() {
      if (this.$auth() === null) {
        this.$bvModal.show("loginModal");
        return;
      }

      if (this.canPost.status === false) {
        this.$bvModal.show("cantPostReviewModal");
        return;
      }

      if (this.canPost.status === true && this.canPost.redirect === true) {
        var params = "";

        if (typeof this.canPost.data.op_id != "undefined") {
          params = "?op_id=" + this.canPost.data.op_id;
        }

        window.location.href = baseUrl + "/user/reviews" + params;
        return;
      }

      this.$bvModal.show("reviewModel");
    }
  },
  mounted: function mounted() {
    this.getReviews("Newest");
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Review/Index.vue?vue&type=template&id=71ece198&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/Review/Index.vue?vue&type=template&id=71ece198& ***!
  \************************************************************************************************************************************************************************************************************************************/
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
  return _vm.reviews.length > 0 ||
    _vm.$configVal("DISPLAY_REVIEW_SECTION_IF_NO_REVIEW") == 1
    ? _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-xl-5" }, [
          _c("div", { staticClass: "section__header section__header-review" }, [
            _c("h2", [_vm._v(_vm._s(_vm.$t("LBL_CUSTOMER_REVIEWS")))]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "rating-holder" },
              [_c("rating-stars", { attrs: { rating: _vm.avgRating } })],
              1
            ),
            _vm._v(" "),
            _c(
              "a",
              {
                staticClass: "btn btn--line btn--md btn-border",
                attrs: { href: "javascript:;" },
                on: { click: _vm.writeAReview }
              },
              [_vm._v(_vm._s(_vm.$t("BTN_WRITE_A_REVIEW")))]
            )
          ])
        ]),
        _vm._v(" "),
        _vm.reviews.length > 0
          ? _c("div", { staticClass: "col-xl-7" }, [
              _c("div", { staticClass: "review-header" }, [
                _c(
                  "div",
                  {
                    staticClass:
                      "row justify-content-between align-items-center"
                  },
                  [
                    _c("div", { staticClass: "col-auto" }, [
                      _c(
                        "span",
                        { staticClass: "btn btn--line btn--md btn--black" },
                        [
                          _vm._v(
                            "Total Reviews (" + _vm._s(_vm.totalRating) + ")"
                          )
                        ]
                      )
                    ]),
                    _vm._v(" "),
                    _vm.reviews.length > 1
                      ? _c("div", { staticClass: "col-auto" }, [
                          _c("div", { staticClass: "select-dropdown" }, [
                            _c(
                              "a",
                              {
                                staticClass:
                                  "select-dropdown__current js-toggle",
                                attrs: { href: "#SORTBY" }
                              },
                              [_vm._v(_vm._s(_vm.sort))]
                            ),
                            _vm._v(" "),
                            _c(
                              "div",
                              {
                                staticClass: "select-dropdown__wrap",
                                attrs: { id: "SORTBY" }
                              },
                              [
                                _c(
                                  "ul",
                                  { staticClass: "list list--vertical" },
                                  _vm._l(_vm.reviewsSort, function(
                                    review,
                                    index
                                  ) {
                                    return _c("li", { key: index }, [
                                      _c(
                                        "a",
                                        {
                                          attrs: {
                                            href: "javascript:void(0);"
                                          },
                                          on: {
                                            click: function($event) {
                                              return _vm.getReviews(index)
                                            }
                                          }
                                        },
                                        [_vm._v(_vm._s(review))]
                                      )
                                    ])
                                  }),
                                  0
                                )
                              ]
                            )
                          ])
                        ])
                      : _vm._e()
                  ]
                )
              ]),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "review-list" },
                [
                  _vm.reviews.length == 0
                    ? _c("no-reviews", {
                        attrs: { canPost: _vm.canPost, product: _vm.product },
                        on: { refreshListing: _vm.getReviews }
                      })
                    : _c("review-list", {
                        attrs: {
                          reviews: _vm.reviews,
                          pagination: _vm.pagination
                        },
                        on: { refreshListing: _vm.getReviews }
                      })
                ],
                1
              )
            ])
          : _vm._e(),
        _vm._v(" "),
        _c("div", { staticClass: "col-xl-5" }, [
          _c(
            "div",
            { staticClass: "section__header section__header-review" },
            [
              _c("div", { staticClass: "product-review" }, [
                _vm.reviews.length > 0
                  ? _c("div", { staticClass: "product-review_head" }, [
                      _c("div", { staticClass: "section-content" }, [
                        _c("h2", [
                          _vm._v(_vm._s(_vm.$t("LBL_CUSTOMER_REVIEWS")))
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "d-flex" }, [
                          _vm.reviews.length > 0
                            ? _c(
                                "a",
                                {
                                  staticClass: "btn btn-icon btn-brand btn-sm",
                                  attrs: { href: "javascript:;" },
                                  on: { click: _vm.writeAReview }
                                },
                                [
                                  _c("span", [
                                    _c("i", { staticClass: "icon mr-2" }, [
                                      _c(
                                        "svg",
                                        {
                                          staticClass: "svg",
                                          attrs: {
                                            width: "18px",
                                            height: "18px"
                                          }
                                        },
                                        [
                                          _c("use", {
                                            attrs: {
                                              "xlink:href":
                                                _vm.baseUrl +
                                                "/yokart/media/retina/sprite.svg#icon-pencil"
                                            }
                                          })
                                        ]
                                      )
                                    ])
                                  ]),
                                  _vm._v(
                                    "\n                " +
                                      _vm._s(_vm.$t("BTN_WRITE_A_REVIEW")) +
                                      "\n              "
                                  )
                                ]
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.reviews.length > 1
                            ? _c("div", { staticClass: "dropdown" }, [
                                _c(
                                  "a",
                                  {
                                    staticClass:
                                      "dropdown-toggle btn btn-outline-secondary btn-sm ml-3",
                                    attrs: {
                                      "data-toggle": "dropdown",
                                      href: "javascript:;"
                                    }
                                  },
                                  [
                                    _c("span", [
                                      _vm.sort == "Newest"
                                        ? _c("span", [
                                            _vm._v(
                                              _vm._s(
                                                _vm.$t("LNK_FILTER_NEWEST")
                                              )
                                            )
                                          ])
                                        : _vm.sort == "Oldest"
                                        ? _c("span", [
                                            _vm._v(
                                              _vm._s(
                                                _vm.$t("LNK_FILTER_OLDEST")
                                              )
                                            )
                                          ])
                                        : _c("span", [
                                            _vm._v(
                                              _vm._s(
                                                _vm.$t(
                                                  "LNK_FILTER_MOST_HELPFUL"
                                                )
                                              )
                                            )
                                          ])
                                    ])
                                  ]
                                ),
                                _vm._v(" "),
                                _c(
                                  "div",
                                  {
                                    staticClass:
                                      "dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim"
                                  },
                                  [
                                    _c(
                                      "ul",
                                      { staticClass: "nav nav--block" },
                                      [
                                        _c("li", { staticClass: "nav__item" }, [
                                          _c(
                                            "a",
                                            {
                                              staticClass: "nav__link",
                                              attrs: { href: "javascript:;" },
                                              on: {
                                                click: function($event) {
                                                  return _vm.getReviews(
                                                    "Newest"
                                                  )
                                                }
                                              }
                                            },
                                            [
                                              _c(
                                                "span",
                                                {
                                                  staticClass: "nav__link-text"
                                                },
                                                [
                                                  _vm._v(
                                                    _vm._s(
                                                      _vm.$t(
                                                        "LNK_FILTER_NEWEST"
                                                      )
                                                    )
                                                  )
                                                ]
                                              )
                                            ]
                                          )
                                        ]),
                                        _vm._v(" "),
                                        _c("li", { staticClass: "nav__item" }, [
                                          _c(
                                            "a",
                                            {
                                              staticClass: "nav__link",
                                              attrs: { href: "javascript:;" },
                                              on: {
                                                click: function($event) {
                                                  return _vm.getReviews(
                                                    "Oldest"
                                                  )
                                                }
                                              }
                                            },
                                            [
                                              _c(
                                                "span",
                                                {
                                                  staticClass: "nav__link-text"
                                                },
                                                [
                                                  _vm._v(
                                                    _vm._s(
                                                      _vm.$t(
                                                        "LNK_FILTER_OLDEST"
                                                      )
                                                    )
                                                  )
                                                ]
                                              )
                                            ]
                                          )
                                        ]),
                                        _vm._v(" "),
                                        _c("li", { staticClass: "nav__item" }, [
                                          _c(
                                            "a",
                                            {
                                              staticClass: "nav__link",
                                              attrs: { href: "javascript:;" },
                                              on: {
                                                click: function($event) {
                                                  return _vm.getReviews(
                                                    "MostHelpful"
                                                  )
                                                }
                                              }
                                            },
                                            [
                                              _c(
                                                "span",
                                                {
                                                  staticClass: "nav__link-text"
                                                },
                                                [
                                                  _vm._v(
                                                    _vm._s(
                                                      _vm.$t(
                                                        "LNK_FILTER_MOST_HELPFUL"
                                                      )
                                                    )
                                                  )
                                                ]
                                              )
                                            ]
                                          )
                                        ])
                                      ]
                                    )
                                  ]
                                )
                              ])
                            : _vm._e()
                        ])
                      ])
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _vm.reviewSectionLoaded
                  ? _c(
                      "div",
                      { staticClass: "product-review_body" },
                      [
                        _vm.reviews.length > 0
                          ? _c("rating-summary", {
                              attrs: {
                                avgRating: _vm.avgRating,
                                oneRating: _vm.oneRating,
                                twoRating: _vm.twoRating,
                                threeRating: _vm.threeRating,
                                fourRating: _vm.fourRating,
                                fiveRating: _vm.fiveRating,
                                totalRating: _vm.totalRating,
                                reviewCount: _vm.reviews.length
                              }
                            })
                          : _vm._e(),
                        _vm._v(" "),
                        _vm.reviews.length == 0
                          ? _c("no-reviews", {
                              attrs: {
                                canPost: _vm.canPost,
                                product: _vm.product
                              },
                              on: { refreshListing: _vm.getReviews }
                            })
                          : _c("review-list", {
                              attrs: {
                                reviews: _vm.reviews,
                                pagination: _vm.pagination
                              },
                              on: { refreshListing: _vm.getReviews }
                            })
                      ],
                      1
                    )
                  : _vm._e()
              ]),
              _vm._v(" "),
              _c("cant-post-review", {
                attrs: { canPost: _vm.canPost, product: _vm.product }
              })
            ],
            1
          )
        ])
      ])
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



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

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/CantPostReview$":
/*!******************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/Review\/CantPostReview$ namespace object ***!
  \******************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/Review/CantPostReview": [
		"./resources/js/frontend/Themes/base/Product/Review/CantPostReview.vue",
		123
	],
	"./fashion/Product/Review/CantPostReview": [
		"./resources/js/frontend/Themes/fashion/Product/Review/CantPostReview.vue",
		124
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/CantPostReview$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/List$":
/*!********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/Review\/List$ namespace object ***!
  \********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/Review/List": [
		"./resources/js/frontend/Themes/base/Product/Review/List.vue",
		1,
		21
	],
	"./fashion/Product/Review/List": [
		"./resources/js/frontend/Themes/fashion/Product/Review/List.vue",
		1,
		22
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
	return Promise.all(ids.slice(1).map(__webpack_require__.e)).then(function() {
		return __webpack_require__(id);
	});
}
webpackAsyncContext.keys = function webpackAsyncContextKeys() {
	return Object.keys(map);
};
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/List$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/NoReviews$":
/*!*************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/Review\/NoReviews$ namespace object ***!
  \*************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/Review/NoReviews": [
		"./resources/js/frontend/Themes/base/Product/Review/NoReviews.vue",
		128
	],
	"./fashion/Product/Review/NoReviews": [
		"./resources/js/frontend/Themes/fashion/Product/Review/NoReviews.vue",
		131
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/NoReviews$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/RatingSummary$":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/Review\/RatingSummary$ namespace object ***!
  \*****************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/Review/RatingSummary": [
		"./resources/js/frontend/Themes/base/Product/Review/RatingSummary.vue",
		129
	],
	"./fashion/Product/Review/RatingSummary": [
		"./resources/js/frontend/Themes/fashion/Product/Review/RatingSummary.vue",
		132
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/RatingSummary$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Review/Index.vue":
/*!***********************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Review/Index.vue ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Index_vue_vue_type_template_id_71ece198___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=71ece198& */ "./resources/js/frontend/Themes/fashion/Product/Review/Index.vue?vue&type=template&id=71ece198&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/fashion/Product/Review/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Index_vue_vue_type_template_id_71ece198___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Index_vue_vue_type_template_id_71ece198___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/fashion/Product/Review/Index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Review/Index.vue?vue&type=script&lang=js&":
/*!************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Review/Index.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Review/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Review/Index.vue?vue&type=template&id=71ece198&":
/*!******************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Review/Index.vue?vue&type=template&id=71ece198& ***!
  \******************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_71ece198___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=template&id=71ece198& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Review/Index.vue?vue&type=template&id=71ece198&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_71ece198___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_71ece198___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);