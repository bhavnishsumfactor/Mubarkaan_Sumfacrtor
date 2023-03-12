(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[35],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/NoRecordFound.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Partials/NoRecordFound.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ["size", "heading", "headImage", "text1", "text2", "anchor"],
  data: function data() {
    return {
      baseUrl: baseUrl,
      currentTheme: currentTheme
    };
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _frontend_Partials_NoRecordFound__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/frontend/Partials/NoRecordFound */ "./resources/js/frontend/Partials/NoRecordFound.vue");
/* harmony import */ var _frontend_Auth_LoginPopup__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/frontend/Auth/LoginPopup */ "./resources/js/frontend/Auth/LoginPopup.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    LoginModal: _frontend_Auth_LoginPopup__WEBPACK_IMPORTED_MODULE_1__["default"],
    RecentlyViewed: function RecentlyViewed() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RecentlyViewed$")("./".concat(currentTheme, "/Product/RecentlyViewed"));
    },
    FilterSkeleton: function FilterSkeleton() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/FilterSkeleton$")("./".concat(currentTheme, "/Product/FilterSkeleton"));
    },
    Filters: function Filters() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Filters$")("./".concat(currentTheme, "/Product/Filters"));
    },
    Listing: function Listing() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Listing$")("./".concat(currentTheme, "/Product/Listing"));
    },
    LoadMoreFilterPopup: function LoadMoreFilterPopup() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/LoadMoreFilterPopup$")("./".concat(currentTheme, "/Product/LoadMoreFilterPopup"));
    },
    FilterTags: function FilterTags() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/FilterTags$")("./".concat(currentTheme, "/Product/FilterTags"));
    },
    NoRecordFound: _frontend_Partials_NoRecordFound__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: ["products", "catIds", "recentViewProducts", "aspectRatio", "perPageRecords", "firstItem", "lastItem", "perPage", "searchedBy", "searchKeyword"],
  data: function data() {
    return {
      baseUrl: baseUrl,
      pageTitle: this.$t("LBL_PRODUCTS"),
      currentTheme: currentTheme,
      listingGridView: 4,
      productFirstItem: 0,
      productLastItem: 0,
      pagination: [],
      productRecords: [],
      loadMoreApplied: [],
      brandFilters: {},
      filters: [],
      moreFilters: [],
      sortBy: {
        "new": this.$t("LNK_FILTER_WHATS_NEW"),
        popularity: this.$t("LNK_FILTER_BEST_SELLING"),
        rating: this.$t("LNK_FILTER_BEST_RATED"),
        discounted: this.$t("LNK_FILTER_MOST_DISCOUNTED"),
        priceDesc: this.$t("LNK_FILTER_PRICE_HIGH_TO_LOW"),
        priceAsc: this.$t("LNK_FILTER_PRICE_LOW_TO_HIGH"),
        alphabetically: this.$t("LNK_FILTER_A_TO_Z")
      },
      sortingBy: "new",
      randomStr: "",
      loadMoreStr: "",
      showRecords: "",
      appliedFilters: {},
      loadMore: false,
      removeTagData: {}
    };
  },
  methods: {
    changeProductView: function changeProductView(gridType) {
      this.listingGridView = gridType;
    },
    getRecords: function getRecords() {
      var _this = this;

      var pageNo = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
      var formData = this.$serializeData(this.appliedFilters);

      if (this.searchedBy && this.searchedBy.type == "categories") {
        formData.append("record_id", this.searchedBy.record.cat_id);
      } else if (this.searchedBy && this.searchedBy.type == "brands") {
        formData.append("record_id", this.searchedBy.record.brand_id);
      } else if (this.searchedBy && this.searchedBy.type == "collection") {
        formData.append("record_id", this.searchedBy.record);
      }

      formData.append("searchedBy", this.searchedBy ? this.searchedBy.type : "");
      formData.append("search_items", this.searchKeyword);
      formData.append("sortBy", this.sortingBy);
      formData.append("showRecords", this.showRecords);
      formData.append("load_more", this.loadMore);

      if (this.loadMore == true) {
        formData.append("search_id", this.moreFilters.seachType.searched_id);
        formData.append("last_filters", this.moreFilters.seachType.type);
      }

      this.$axios.post(baseUrl + "/products?page=" + pageNo, formData).then(function (response) {
        if (response.data.status == false) {
          return false;
        }

        var result = response.data.data;
        _this.pagination = result.products;
        _this.productRecords = result.products;
        _this.productFirstItem = result.firstItem;
        _this.productLastItem = result.lastItem;

        if (_this.loadMore == true && _this.moreFilters.seachType.type == "brands") {
          _this.brandFilters = result.brandRecords;
        } else if (_this.loadMore == true && _this.moreFilters.seachType.type == "options") {
          _this.filters.options[_this.moreFilters.seachType.searched_id] = result.options[_this.moreFilters.seachType.searched_id];
          _this.loadMoreStr = _this.$generateRandomString();
        }
      });
    },
    getFilters: function getFilters() {
      var _this2 = this;

      var formData = this.$serializeData({
        search_items: this.searchKeyword
      });

      if (this.searchedBy && this.searchedBy.type == "categories") {
        formData.append("categories", this.searchedBy.record.cat_id);
      } else if (this.searchedBy && this.searchedBy.type == "brands") {
        formData.append("brands", this.searchedBy.record.brand_id);
      } else if (this.searchedBy && this.searchedBy.type == "collection") {
        formData.append("collection", this.searchedBy.record);
      }

      if (this.searchedBy) {
        formData.append("searchedBy", this.searchedBy.type);
      }

      this.$axios.post(baseUrl + "/product/filters", formData).then(function (response) {
        if (response.data.status == false) {
          return false;
        }

        var result = response.data.data;
        _this2.filters = result;
        _this2.brandFilters = result.brandRecords;
      });
    },
    updateRecords: function updateRecords(type, record) {
      this.removeTagData = [];
      this.loadMoreApplied = [];
      this.loadMore = false;

      switch (type) {
        case "pagination":
          this.getRecords(record);
          break;

        case "showRecords":
          this.showRecords = record;
          this.getRecords();
          break;

        case "filters":
          this.appliedFilters = record;
          this.randomStr = this.$generateRandomString();
          this.getRecords();
          break;

        case "tags_update":
          this.appliedFilters = record;
          this.randomStr = this.$generateRandomString();
          break;

        case "remove_tags":
          this.removeTagData = record;
          break;

        case "load_more":
          this.loadMoreFilters(record);
          break;

        case "load_more_filters":
          this.appliedFilters = record;
          this.loadMoreApplied = record;
          this.loadMore = true;
          this.getRecords();
          break;
      }
    },
    loadMoreFilters: function loadMoreFilters(record) {
      var _this3 = this;

      this.moreFilters = [];
      var formData = this.$serializeData({
        search_type: record.search_type,
        search_id: record.searchId
      });

      if (this.searchedBy && this.searchedBy.type == "categories") {
        formData.append("categories", this.searchedBy.record.cat_id);
      } else if (this.searchedBy && this.searchedBy.type == "brands") {
        formData.append("brands", this.searchedBy.record.brand_id);
      } else if (this.searchedBy && this.searchedBy.type == "collection") {
        formData.append("collection", this.searchedBy.record);
      }

      if (this.searchedBy) {
        formData.append("searchedBy", this.searchedBy.type);
      }

      this.$axios.post(baseUrl + "/products/load-filters/" + record.type, formData).then(function (response) {
        if (response.data.status == false) {
          return false;
        }

        _this3.moreFilters = response.data.data;

        _this3.$bvModal.show("loadFilterModel");
      });
    }
  },
  mounted: function mounted() {
    this.listingGridView = this.$configVal("LISTING_GRID_DEFAULT");
    this.pagination = this.products;
    this.productRecords = this.products;
    this.productFirstItem = this.firstItem;
    this.productLastItem = this.lastItem;
    this.showRecords = this.perPage;
    this.getFilters();

    if (this.searchKeyword) {
      this.pageTitle = this.searchKeyword;
    } else if (this.searchedBy && this.searchedBy.type == "categories") {
      this.pageTitle = this.searchedBy.record.cat_name;
    } else if (this.searchedBy && this.searchedBy.type == "brands") {
      this.pageTitle = this.searchedBy.record.brand_name;
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=style&index=0&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=style&index=0&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.filter-line {\n  fill: none;\n  stroke-width: 1.5px;\n  stroke: currentColor;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=style&index=0&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=style&index=0&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader??ref--5-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--5-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=style&index=0&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=style&index=0&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/NoRecordFound.vue?vue&type=template&id=7098ad82&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Partials/NoRecordFound.vue?vue&type=template&id=7098ad82& ***!
  \***********************************************************************************************************************************************************************************************************************/
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
    {
      staticClass: "no-data-found",
      class: [
        _vm.size == "" || _vm.size == "small" ? "no-data-found--sm" : "",
        _vm.size == "medium" ? "no-data-found--md" : "",
        _vm.size == "large" ? "no-data-found--lg" : "",
        _vm.size == "xlarge" ? "no-data-found--xl" : ""
      ]
    },
    [
      _c("div", { staticClass: "img" }, [
        _c("img", {
          attrs: {
            "data-yk": "",
            src: _vm.headImage
              ? _vm.headImage
              : _vm.baseUrl + "/yokart/media/retina/empty/no-found.svg",
            alt: ""
          }
        })
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "data" }, [
        _c("h2", [
          _vm._v(
            _vm._s(_vm.heading ? _vm.heading : _vm.$t("LBL_NO_RESULTS_FOUND"))
          )
        ]),
        _vm._v(" "),
        _vm.text1
          ? _c("p", [
              _vm._v(_vm._s(_vm.text1) + "\n            "),
              _vm.text2
                ? _c("span", [_vm._v("{{'"), _c("br"), _vm._v("'+text2}}")])
                : _vm._e()
            ])
          : _vm._e(),
        _vm._v(" "),
        _vm.anchor
          ? _c("span", { domProps: { innerHTML: _vm._s(_vm.anchor) } })
          : _vm._e()
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=template&id=a0d8b676&":
/*!*****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=template&id=a0d8b676& ***!
  \*****************************************************************************************************************************************************************************************************************************/
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
      _vm.searchedBy &&
      _vm.searchedBy.type == "categories" &&
      _vm.searchedBy.record &&
      _vm.searchedBy.record.upload_images
        ? _c("section", { staticClass: "bg-category" }, [
            _c("div", { staticClass: "container" }, [
              _c("img", {
                attrs: {
                  src: _vm.$getFileUrl(
                    "productCategoryBanner",
                    _vm.searchedBy.record.cat_id,
                    0,
                    "600-120-webp",
                    _vm.$timestamp(
                      _vm.searchedBy.record.upload_images.afile_updated_at
                    )
                  )
                }
              })
            ])
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("section", [
        _c("div", { staticClass: "container" }, [
          _c(
            "nav",
            {
              staticClass: "breadcrumb",
              attrs: {
                "data-yk": "",
                role: "yk-navigation",
                "aria-label": "breadcrumbs"
              }
            },
            [
              _c("li", { staticClass: "breadcrumb-item" }, [
                _c("a", { attrs: { href: _vm.baseUrl, title: "" } }, [
                  _vm._v(_vm._s(_vm.$t("LNK_HOME")))
                ])
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "breadcrumb-item" }, [
                _vm._v(_vm._s(_vm.pageTitle))
              ])
            ]
          )
        ])
      ]),
      _vm._v(" "),
      _c("section", [
        _c("div", { staticClass: "container" }, [
          _c("div", { staticClass: "filter-topbar" }, [
            _c(
              "div",
              { staticClass: "row justify-content-between align-items-center" },
              [
                _c("div", { staticClass: "col-md-auto" }, [
                  _c("div", { staticClass: "collection-top-bar__title" }, [
                    _c("h1", [_c("strong", [_vm._v(_vm._s(_vm.pageTitle))])])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "col-md-auto" }, [
                  _c("div", { staticClass: "sorting-filter" }, [
                    _c(
                      "div",
                      { staticClass: "sorting-filter__item d-none d-xl-block" },
                      [
                        _c(
                          "div",
                          {
                            staticClass: "sorting-filter__view",
                            attrs: { id: "collection-view" }
                          },
                          [
                            _c("span", [_vm._v("View")]),
                            _vm._v(" "),
                            _c(
                              "a",
                              {
                                staticClass: "three YK-listview",
                                class: [
                                  _vm.listingGridView == 4 ? "active" : ""
                                ],
                                attrs: {
                                  href: "javascript: void(0)",
                                  "data-num": "4",
                                  "data-col": "3"
                                },
                                on: {
                                  click: function($event) {
                                    return _vm.changeProductView(4)
                                  }
                                }
                              },
                              [_vm._v("4")]
                            ),
                            _vm._v(" "),
                            _c(
                              "a",
                              {
                                staticClass:
                                  "four d-none d-md-block YK-listview",
                                class: [
                                  _vm.listingGridView == 5 ? "active" : ""
                                ],
                                attrs: {
                                  href: "javascript: void(0)",
                                  "data-num": "5",
                                  "data-col": "4"
                                },
                                on: {
                                  click: function($event) {
                                    return _vm.changeProductView(5)
                                  }
                                }
                              },
                              [_vm._v("5")]
                            )
                          ]
                        )
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass:
                          "sorting-filter__item sorting-filter--trigger"
                      },
                      [
                        _c(
                          "a",
                          {
                            staticClass: "sorting-filter__trigger js-toggle",
                            attrs: { href: "#collection-sidebar" }
                          },
                          [
                            _c("span", [_vm._v("Hide Filters")]),
                            _vm._v(" "),
                            _c(
                              "span",
                              { staticClass: "sorting-filter__icon" },
                              [
                                _c(
                                  "svg",
                                  {
                                    attrs: {
                                      xmlns: "http://www.w3.org/2000/svg",
                                      width: "16",
                                      height: "14",
                                      viewBox: "0 0 16 14"
                                    }
                                  },
                                  [
                                    _c(
                                      "g",
                                      {
                                        attrs: {
                                          transform: "translate(-1270.5 -548.5)"
                                        }
                                      },
                                      [
                                        _c("line", {
                                          staticClass: "filter-line",
                                          attrs: {
                                            x2: "16",
                                            transform: "translate(1270.5 551.5)"
                                          }
                                        }),
                                        _vm._v(" "),
                                        _c("line", {
                                          staticClass: "filter-line",
                                          attrs: {
                                            x2: "16",
                                            transform: "translate(1270.5 559.5)"
                                          }
                                        }),
                                        _vm._v(" "),
                                        _c("line", {
                                          staticClass: "filter-line",
                                          attrs: {
                                            y2: "6",
                                            transform: "translate(1282.5 548.5)"
                                          }
                                        }),
                                        _vm._v(" "),
                                        _c("line", {
                                          staticClass: "filter-line",
                                          attrs: {
                                            y2: "6",
                                            transform: "translate(1274.5 556.5)"
                                          }
                                        })
                                      ]
                                    )
                                  ]
                                )
                              ]
                            )
                          ]
                        )
                      ]
                    ),
                    _vm._v(" "),
                    _c("div", { staticClass: "sorting-filter__item" }, [
                      _c("div", { staticClass: "select-dropdown" }, [
                        _c(
                          "a",
                          {
                            staticClass: "select-dropdown__current js-toggle",
                            attrs: { href: "#SORTBY" }
                          },
                          [_vm._v(_vm._s(_vm.sortingBy))]
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
                              _vm._l(_vm.sortBy, function(sort, sindex) {
                                return _c("li", { key: sindex }, [
                                  _c(
                                    "a",
                                    {
                                      attrs: { href: "javascript:void(0);" },
                                      on: {
                                        change: function($event) {
                                          _vm.getRecords(sort),
                                            (_vm.sortingBy = sort)
                                        }
                                      }
                                    },
                                    [_vm._v(_vm._s(sort))]
                                  )
                                ])
                              }),
                              0
                            )
                          ]
                        )
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "sorting-filter__item" }, [
                      _c("div", { staticClass: "select-dropdown" }, [
                        _c(
                          "a",
                          {
                            staticClass: "select-dropdown__current js-toggle",
                            attrs: { href: "#SORTBY-COUNT" }
                          },
                          [_vm._v(_vm._s(_vm.perPage) + " Items")]
                        ),
                        _vm._v(" "),
                        _c(
                          "div",
                          {
                            staticClass: "select-dropdown__wrap",
                            attrs: { id: "SORTBY-COUNT" }
                          },
                          [
                            _c(
                              "ul",
                              { staticClass: "list list--vertical" },
                              _vm._l(_vm.perPageRecords, function(
                                perPageRecord,
                                index
                              ) {
                                return _c("li", { key: index }, [
                                  _c(
                                    "a",
                                    { attrs: { href: "javascript:void(0);" } },
                                    [_vm._v(_vm._s(perPageRecord) + " Items")]
                                  )
                                ])
                              }),
                              0
                            )
                          ]
                        )
                      ])
                    ])
                  ])
                ])
              ]
            )
          ])
        ])
      ]),
      _vm._v(" "),
      _c("section", [
        _c(
          "div",
          { staticClass: "container" },
          [
            Object.keys(_vm.appliedFilters).length != 0
              ? _c("filter-tags", {
                  attrs: {
                    appliedFilters: _vm.appliedFilters,
                    filters: _vm.filters,
                    brandFilters: _vm.brandFilters,
                    randomStr: _vm.randomStr
                  },
                  on: { updateRecords: _vm.updateRecords }
                })
              : _vm._e()
          ],
          1
        )
      ]),
      _vm._v(" "),
      _vm.products.data.length > 0
        ? _c("section", [
            _c("div", { staticClass: "container" }, [
              _c("div", { staticClass: "d-xl-flex collection-listing" }, [
                _c(
                  "div",
                  {
                    staticClass: "collection-sidebar YK-sidebar",
                    attrs: { id: "collection-sidebar" }
                  },
                  [
                    _vm.filters.length == 0
                      ? _c("filter-skeleton")
                      : _c("filters", {
                          attrs: {
                            filters: _vm.filters,
                            brandFilters: _vm.brandFilters,
                            loadMoreApplied: _vm.loadMoreApplied,
                            removeTagData: _vm.removeTagData,
                            loadMore: _vm.loadMore,
                            loadMoreStr: _vm.loadMoreStr,
                            moreFilters: _vm.moreFilters
                          },
                          on: { updateRecords: _vm.updateRecords }
                        })
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "collection-content flex-grow-1" },
                  [
                    _c("listing", {
                      attrs: {
                        perPageRecords: _vm.perPageRecords,
                        firstItem: _vm.productFirstItem,
                        perPage: _vm.perPage,
                        lastItem: _vm.productLastItem,
                        products: _vm.productRecords,
                        aspectRatio: _vm.aspectRatio,
                        pagination: _vm.pagination,
                        listingGridView: _vm.listingGridView
                      },
                      on: { updateRecords: _vm.updateRecords }
                    })
                  ],
                  1
                )
              ])
            ])
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("section", { staticClass: "section listing-page" }, [
        _c("div", { staticClass: "container" }, [
          _c("div", {
            staticClass: "collection-top-bar",
            class: [_vm.products.data.length == 0 ? "no-data" : ""]
          }),
          _vm._v(" "),
          _vm.products.data.length > 0
            ? _c("div", { staticClass: "collection-listing" }, [
                _c("div", {
                  staticClass: "collection-content YK-listingRightContainer"
                })
              ])
            : _c(
                "div",
                { staticClass: "collection-listing" },
                [
                  _c("no-record-found", {
                    attrs: {
                      text1: _vm.$t("LBL_NO_PRODUCTS_FOUND"),
                      size: "large"
                    }
                  })
                ],
                1
              )
        ])
      ]),
      _vm._v(" "),
      _vm.recentViewProducts && _vm.recentViewProducts.length != 0
        ? _c("recently-viewed", {
            attrs: {
              recentViewProducts: _vm.recentViewProducts,
              aspectRatio: _vm.aspectRatio
            }
          })
        : _vm._e(),
      _vm._v(" "),
      _c("load-more-filter-popup", {
        attrs: { filters: _vm.moreFilters, appliedFilters: _vm.appliedFilters },
        on: { updateRecords: _vm.updateRecords }
      }),
      _vm._v(" "),
      _c("login-modal")
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Partials/NoRecordFound.vue":
/*!**********************************************************!*\
  !*** ./resources/js/frontend/Partials/NoRecordFound.vue ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _NoRecordFound_vue_vue_type_template_id_7098ad82___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NoRecordFound.vue?vue&type=template&id=7098ad82& */ "./resources/js/frontend/Partials/NoRecordFound.vue?vue&type=template&id=7098ad82&");
/* harmony import */ var _NoRecordFound_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NoRecordFound.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Partials/NoRecordFound.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _NoRecordFound_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _NoRecordFound_vue_vue_type_template_id_7098ad82___WEBPACK_IMPORTED_MODULE_0__["render"],
  _NoRecordFound_vue_vue_type_template_id_7098ad82___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Partials/NoRecordFound.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Partials/NoRecordFound.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/js/frontend/Partials/NoRecordFound.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NoRecordFound_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./NoRecordFound.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/NoRecordFound.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NoRecordFound_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Partials/NoRecordFound.vue?vue&type=template&id=7098ad82&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/frontend/Partials/NoRecordFound.vue?vue&type=template&id=7098ad82& ***!
  \*****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NoRecordFound_vue_vue_type_template_id_7098ad82___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./NoRecordFound.vue?vue&type=template&id=7098ad82& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/NoRecordFound.vue?vue&type=template&id=7098ad82&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NoRecordFound_vue_vue_type_template_id_7098ad82___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NoRecordFound_vue_vue_type_template_id_7098ad82___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/FilterSkeleton$":
/*!**********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/FilterSkeleton$ namespace object ***!
  \**********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/FilterSkeleton": [
		"./resources/js/frontend/Themes/base/Product/FilterSkeleton.vue",
		136
	],
	"./fashion/Product/FilterSkeleton": [
		"./resources/js/frontend/Themes/fashion/Product/FilterSkeleton.vue",
		145
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/FilterSkeleton$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/FilterTags$":
/*!******************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/FilterTags$ namespace object ***!
  \******************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/FilterTags": [
		"./resources/js/frontend/Themes/base/Product/FilterTags.vue",
		137
	],
	"./fashion/Product/FilterTags": [
		"./resources/js/frontend/Themes/fashion/Product/FilterTags.vue",
		31
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/FilterTags$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Filters$":
/*!***************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/Filters$ namespace object ***!
  \***************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/Filters": [
		"./resources/js/frontend/Themes/base/Product/Filters.vue",
		9,
		23
	],
	"./fashion/Product/Filters": [
		"./resources/js/frontend/Themes/fashion/Product/Filters.vue",
		9,
		24
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Filters$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Listing$":
/*!***************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/Listing$ namespace object ***!
  \***************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/Listing": [
		"./resources/js/frontend/Themes/base/Product/Listing.vue",
		124
	],
	"./fashion/Product/Listing": [
		"./resources/js/frontend/Themes/fashion/Product/Listing.vue",
		125
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Listing$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/LoadMoreFilterPopup$":
/*!***************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/LoadMoreFilterPopup$ namespace object ***!
  \***************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/LoadMoreFilterPopup": [
		"./resources/js/frontend/Themes/base/Product/LoadMoreFilterPopup.vue",
		26
	],
	"./fashion/Product/LoadMoreFilterPopup": [
		"./resources/js/frontend/Themes/fashion/Product/LoadMoreFilterPopup.vue",
		32
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/LoadMoreFilterPopup$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RecentlyViewed$":
/*!**********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/RecentlyViewed$ namespace object ***!
  \**********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/RecentlyViewed": [
		"./resources/js/frontend/Themes/base/Product/RecentlyViewed.vue",
		0,
		126
	],
	"./fashion/Product/RecentlyViewed": [
		"./resources/js/frontend/Themes/fashion/Product/RecentlyViewed.vue",
		0,
		129
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/RecentlyViewed$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Index.vue":
/*!****************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Index.vue ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Index_vue_vue_type_template_id_a0d8b676___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=a0d8b676& */ "./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=template&id=a0d8b676&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Index_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Index.vue?vue&type=style&index=0&lang=css& */ "./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Index_vue_vue_type_template_id_a0d8b676___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Index_vue_vue_type_template_id_a0d8b676___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/fashion/Product/Index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=style&index=0&lang=css&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=style&index=0&lang=css& ***!
  \*************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader??ref--5-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--5-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=template&id=a0d8b676&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=template&id=a0d8b676& ***!
  \***********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_a0d8b676___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=template&id=a0d8b676& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Index.vue?vue&type=template&id=a0d8b676&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_a0d8b676___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_a0d8b676___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);