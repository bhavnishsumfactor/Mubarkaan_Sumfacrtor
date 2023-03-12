(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[46],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Detail.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/Detail.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _frontend_Auth_LoginPopup__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/frontend/Auth/LoginPopup */ "./resources/js/frontend/Auth/LoginPopup.vue");
/* harmony import */ var vue_slick_carousel__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-slick-carousel */ "./node_modules/vue-slick-carousel/dist/vue-slick-carousel.umd.js");
/* harmony import */ var vue_slick_carousel__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_slick_carousel__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-slick-carousel/dist/vue-slick-carousel.css */ "./node_modules/vue-slick-carousel/dist/vue-slick-carousel.css");
/* harmony import */ var vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_2__);
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    LoginModal: _frontend_Auth_LoginPopup__WEBPACK_IMPORTED_MODULE_0__["default"],
    VueSlickCarousel: vue_slick_carousel__WEBPACK_IMPORTED_MODULE_1___default.a,
    SliderImages: function SliderImages() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/SliderImages$")("./".concat(currentTheme, "/Product/SliderImages"));
    },
    AddToCartSection: function AddToCartSection() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/AddToCartSection$")("./".concat(currentTheme, "/Product/AddToCartSection"));
    },
    productCard: function productCard() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Partials\\/productCard$")("./".concat(currentTheme, "/Partials/productCard"));
    },
    productCard2: function productCard2() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Partials\\/productCard2$")("./".concat(currentTheme, "/Partials/productCard2"));
    },
    Reviews: function Reviews() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/Index$")("./".concat(currentTheme, "/Product/Review/Index"));
    },
    AdditionalInfo: function AdditionalInfo() {
      return __webpack_require__("./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/AdditionalInfo$")("./".concat(currentTheme, "/Product/AdditionalInfo"));
    }
  },
  props: ["specifications", "buytogetherproducts", "relatedproducts", "record_id", "product", "recentViewProducts", "productImages", "allVarients", "rating", "sizeExist", "sizeChartImage", "published", "shipping", "page", "productDummyImage", "productAspectRatio", "sharethisNetworks", "page_url", "selectedVarient", "otherVarients", "options", "aspectRatio"],
  data: function data() {
    var _ref;

    return _ref = {
      additionalInfoExist: false,
      baseUrl: baseUrl,
      currentTheme: currentTheme,
      v_rating: this.rating,
      v_product: this.product,
      v_productImages: this.productImages,
      v_allVarients: this.allVarients,
      v_sizeExist: this.sizeExist,
      v_sizeChartImage: this.sizeChartImage,
      v_published: this.published,
      v_shipping: this.shipping,
      v_sharethisNetworks: this.sharethisNetworks,
      v_pageUrl: this.page_url,
      v_selectedVarient: this.selectedVarient,
      v_isVarientChange: false,
      v_otherVarients: this.otherVarients
    }, _defineProperty(_ref, "v_otherVarients", this.otherVarients), _defineProperty(_ref, "v_productAspectRatio", this.productAspectRatio), _defineProperty(_ref, "v_productDummyImage", this.productDummyImage), _defineProperty(_ref, "sliderSettingsVideo", {
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true
    }), _defineProperty(_ref, "sliderSettings4Column", {
      slidesToShow: 6,
      slidesToScroll: 1,
      autoplay: false,
      arrows: true,
      dots: false,
      draggable: false,
      rtl: $("html").attr("dir") == "rtl" ? true : false,
      autoplaySpeed: 2000,
      responsive: [{
        breakpoint: 1030,
        settings: {
          slidesToShow: 3,
          dots: false,
          arrows: false
        }
      }, {
        breakpoint: 800,
        settings: {
          slidesToShow: 3,
          arrows: false,
          dots: false
        }
      }, {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
          arrows: false,
          dots: false
        }
      }]
    }), _defineProperty(_ref, "videoLinks", typeof this.product.pc_video_link != "undefined" && this.product.pc_video_link != null ? this.product.pc_video_link.split("\n") : []), _ref;
  },
  methods: {
    hasAttributesInfo: function hasAttributesInfo() {
      var status = false;
      this.additionalInfoExist = false;

      if (this.$isNotNull(this.product.pc_isbn) || this.$isNotNull(this.product.pc_hsn) || this.$isNotNull(this.product.pc_sac) || this.$isNotNull(this.product.pc_upc)) {
        this.additionalInfoExist = true;
        status = true;
      }

      return status;
    },
    hasAdditionalInfo: function hasAdditionalInfo() {
      this.additionalInfoExist = false;

      if (Object.values(this.specifications).length > 0 || this.hasAttributesInfo() === true) {
        this.additionalInfoExist = true;
        status = true;
      }

      return status;
    },
    addBaseUrl: function addBaseUrl(prod_description) {
      return prod_description.replace('src="public', 'src="' + baseUrl + "/public");
    },
    getWarrentyLabel: function getWarrentyLabel(pc_warranty_age) {
      var warrantyAge = this.$t("LBL_NO");

      if (pc_warranty_age != 0) {
        warrantyAge = this.$convertDays(pc_warranty_age);
      }

      return warrantyAge + " " + this.$t("LBL_X_WARRANTY");
    },
    getReturnLabel: function getReturnLabel(pc_return_age) {
      var returnAge = this.$t("LBL_NO_RETURN");

      if (pc_return_age != 0) {
        returnAge = this.$t("LBL_RETURN_WITHIN") + " " + this.$convertDays(pc_return_age);
      }

      return returnAge;
    },
    getVarientData: function getVarientData(varientCode) {
      var _this = this;

      var formData = this.$serializeData({
        "product-id": this.product.prod_id,
        type: "pov_code",
        varientCode: varientCode
      });
      this.$axios.post(baseUrl + "/product/product-varient-price", formData).then(function (response) {
        var res = response.data.data;
        _this.v_product = res.product;
        _this.v_productImages = res.productImages;
        _this.v_allVarients = res.allVarients;
        _this.v_rating = res.rating;
        _this.v_sizeExist = res.sizeExist;
        _this.v_sizeChartImage = res.sizeChartImage;
        _this.v_published = res.published;
        _this.v_shipping = res.shipping;
        _this.v_sharethisNetworks = res.sharethisNetworks;
        _this.v_pageUrl = res.page_url;
        _this.v_selectedVarient = res.selectedVarient;
        _this.v_isVarientChange = true;
        _this.v_otherVarients = res.otherVarients;
        _this.v_productDummyImage = res.productDummyImage;
        _this.v_productAspectRatio = res.productAspectRatio;
      });
    }
  },
  mounted: function mounted() {
    this.sliderSettings4Column.draggable = this.buytogetherproducts.length > 6 ? true : false;
    this.hasAdditionalInfo();
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Detail.vue?vue&type=template&id=4997f924&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/fashion/Product/Detail.vue?vue&type=template&id=4997f924& ***!
  \******************************************************************************************************************************************************************************************************************************/
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
      staticClass: "body",
      attrs: { id: "body", "data-yk": "", role: "yk-main" }
    },
    [
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
                  _vm._v("Home")
                ])
              ]),
              _vm._v(" "),
              _vm.product.cat_name
                ? _c("li", { staticClass: "breadcrumb-item" }, [
                    _c(
                      "a",
                      {
                        attrs: {
                          href: [
                            _vm.product.cat_id
                              ? _vm.baseUrl + "/category/" + _vm.product.cat_id
                              : "javascript:;"
                          ]
                        }
                      },
                      [_vm._v(_vm._s(_vm.product.cat_name))]
                    )
                  ])
                : _vm._e(),
              _vm._v(" "),
              _c(
                "li",
                {
                  staticClass: "breadcrumb-item",
                  attrs: { title: _vm.product.prod_name }
                },
                [
                  _vm._v(
                    _vm._s(_vm.$setStringLength(_vm.product.prod_name, 60))
                  )
                ]
              )
            ]
          )
        ])
      ]),
      _vm._v(" "),
      _c("section", { staticClass: "section pt-4" }, [
        _c("div", { staticClass: "container" }, [
          _c("div", { staticClass: "row" }, [
            _c(
              "div",
              { staticClass: "col-md-6 col-xl-8" },
              [
                _c("slider-images", {
                  attrs: {
                    productImages: _vm.v_productImages,
                    productAspectRatio: _vm.v_productAspectRatio,
                    productDummyImage: _vm.v_productDummyImage,
                    isVarientChange: _vm.v_isVarientChange
                  }
                })
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-6 col-xl-4" },
              [
                _c("add-to-cart-section", {
                  attrs: {
                    product: _vm.v_product,
                    options: _vm.options,
                    rating: _vm.v_rating,
                    sharethisNetworks: _vm.v_sharethisNetworks,
                    pageUrl: _vm.v_pageUrl,
                    sizeExist: _vm.v_sizeExist,
                    sizeChartImage: _vm.v_sizeChartImage,
                    allVarients: _vm.v_allVarients,
                    selectedVarient: _vm.v_selectedVarient,
                    otherVarients: _vm.v_otherVarients,
                    shipping: _vm.v_shipping
                  },
                  on: { selectedVarient: _vm.getVarientData }
                })
              ],
              1
            )
          ])
        ])
      ]),
      _vm._v(" "),
      _c("section", { staticClass: "section details-block" }, [
        _c("div", { staticClass: "container" }, [
          _c("div", { staticClass: "row" }, [
            _c("div", { staticClass: "col-xl-8" }, [
              _vm.product.prod_description
                ? _c("div", { staticClass: "details-block__description" }, [
                    _c("h3", [
                      _c("b", [
                        _vm._v(_vm._s(_vm.$t("LBL_PRODUCT_DESCRIPTION")))
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", {
                      staticClass: "cms",
                      domProps: {
                        innerHTML: _vm._s(
                          _vm.addBaseUrl(_vm.product.prod_description)
                        )
                      }
                    })
                  ])
                : _vm._e(),
              _vm._v(" "),
              _vm.additionalInfoExist === true
                ? _c("div", { staticClass: "details-block__btns" }, [
                    _c(
                      "a",
                      {
                        staticClass: "btn btn--lg btn--line btn-border",
                        attrs: { href: "javascript:;" },
                        on: {
                          click: function($event) {
                            return _vm.$bvModal.show("additionalInfo")
                          }
                        }
                      },
                      [
                        _vm._v(
                          _vm._s(_vm.$t("LBL_PRODUCT_ADDITIONAL_INFORMATION"))
                        )
                      ]
                    )
                  ])
                : _vm._e()
            ]),
            _vm._v(" "),
            _vm.product.prod_type != 2
              ? _c("div", { staticClass: "col-xl-4" }, [
                  _c("ul", { staticClass: "list-features" }, [
                    _vm.$configVal("PRODUCT_DETAIL_DISPLAY_WARRANTY") != 0
                      ? _c("li", [
                          _c("div", { staticClass: "feature" }, [
                            _c("span", { staticClass: "feature__icon" }, [
                              _c("img", {
                                attrs: {
                                  src:
                                    _vm.baseUrl +
                                    "/yokart/" +
                                    _vm.currentTheme +
                                    "/" +
                                    "media/retina/icon__warranty.svg"
                                }
                              })
                            ]),
                            _vm._v(" "),
                            _c("h5", { staticClass: "feature__lbl" }, [
                              _vm._v(
                                _vm._s(
                                  _vm.getWarrentyLabel(
                                    _vm.product.pc_warranty_age
                                  )
                                )
                              )
                            ])
                          ])
                        ])
                      : _vm._e(),
                    _vm._v(" "),
                    _c("li", [
                      _vm.$configVal("PRODUCT_DETAIL_DISPLAY_RETURN") != 0
                        ? _c("div", { staticClass: "feature" }, [
                            _c("span", { staticClass: "feature__icon" }, [
                              _c("img", {
                                attrs: {
                                  src:
                                    _vm.baseUrl +
                                    "/yokart/" +
                                    _vm.currentTheme +
                                    "/" +
                                    "media/retina/icon__return2.svg"
                                }
                              })
                            ]),
                            _vm._v(" "),
                            _c("h5", { staticClass: "feature__lbl" }, [
                              _vm._v(
                                _vm._s(
                                  _vm.getReturnLabel(_vm.product.pc_return_age)
                                )
                              )
                            ])
                          ])
                        : _vm._e()
                    ]),
                    _vm._v(" "),
                    _vm.product.prod_cod_available == 1 &&
                    _vm.$configVal("COD_ENABLE") == 1
                      ? _c("li", [
                          _c("div", { staticClass: "feature" }, [
                            _c("span", { staticClass: "feature__icon" }, [
                              _c("img", {
                                attrs: {
                                  src:
                                    _vm.baseUrl +
                                    "/yokart/" +
                                    _vm.currentTheme +
                                    "/" +
                                    "media/retina/icon__cod.svg"
                                }
                              })
                            ]),
                            _vm._v(" "),
                            _c("h5", { staticClass: "feature__lbl" }, [
                              _vm._v(_vm._s(_vm.$t("LBL_COD_AVAILABLE")))
                            ])
                          ])
                        ])
                      : _vm._e(),
                    _vm._v(" "),
                    _vm.product.prod_self_pickup != 1
                      ? _c("li", [
                          _c("div", { staticClass: "feature" }, [
                            _c("span", { staticClass: "feature__icon" }, [
                              _c("img", {
                                attrs: {
                                  src:
                                    _vm.baseUrl +
                                    "/yokart/" +
                                    _vm.currentTheme +
                                    "/" +
                                    "media/retina/icon__pickup.svg"
                                }
                              })
                            ]),
                            _vm._v(" "),
                            _c("h5", { staticClass: "feature__lbl" }, [
                              _vm._v(_vm._s(_vm.$t("LBL_PICK_UP")))
                            ])
                          ])
                        ])
                      : _vm._e()
                  ])
                ])
              : _vm._e()
          ])
        ])
      ]),
      _vm._v(" "),
      _c("section", { staticClass: "section bg-gray section--bg" }, [
        _c(
          "div",
          { staticClass: "container container--lg" },
          [_c("reviews", { attrs: { product: _vm.product } })],
          1
        )
      ]),
      _vm._v(" "),
      _vm.videoLinks.length > 0 &&
      _vm.videoLinks[0] != "" &&
      _vm.videoLinks[0] != "null"
        ? _c("section", { staticClass: "section pt-0" }, [
            _c("div", { staticClass: "container container--md" }, [
              _c("div", { staticClass: "section-content" }, [
                _c("h2", [_vm._v(_vm._s(_vm.$t("LBL_VIDEO")))])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "product-video-wrapper" }, [
                _c(
                  "div",
                  { staticClass: "video-slider" },
                  [
                    _c(
                      "vue-slick-carousel",
                      _vm._b(
                        {},
                        "vue-slick-carousel",
                        _vm.sliderSettingsVideo,
                        false
                      ),
                      _vm._l(_vm.videoLinks, function(link, vlIndex) {
                        return _c("div", { key: vlIndex }, [
                          link != null && _vm.$parseYouTubeurl(link) !== false
                            ? _c("div", { staticClass: "slider__item" }, [
                                _c("div", { staticClass: "video-wrap" }, [
                                  _c("iframe", {
                                    attrs: {
                                      width: "100%",
                                      height: "315",
                                      src:
                                        "//www.youtube.com/embed/" +
                                        _vm.$parseYouTubeurl(link),
                                      allowfullscreen: ""
                                    }
                                  })
                                ])
                              ])
                            : _vm._e()
                        ])
                      }),
                      0
                    )
                  ],
                  1
                )
              ])
            ])
          ])
        : _vm._e(),
      _vm._v(" "),
      _vm.buytogetherproducts.length > 0
        ? _c("section", { staticClass: "section section--slider" }, [
            _c("div", { staticClass: "container" }, [
              _c(
                "div",
                { staticClass: "section__header d-flex align-items-center" },
                [
                  _c("h2", [
                    _vm._v(_vm._s(_vm.$t("LBL_RECOMMENDED_PRODUCTS")))
                  ]),
                  _vm._v(" "),
                  _vm._m(0)
                ]
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "section__body" },
                [
                  _c(
                    "vue-slick-carousel",
                    _vm._b(
                      { staticClass: "d-grid" },
                      "vue-slick-carousel",
                      _vm.sliderSettings4Column,
                      false
                    ),
                    _vm._l(_vm.buytogetherproducts, function(
                      buytogether,
                      btIndex
                    ) {
                      return _c(
                        "div",
                        { key: btIndex, staticClass: "item" },
                        [
                          _c("product-card2", {
                            attrs: {
                              product: buytogether,
                              imageTime: buytogether.get_updated_image
                                ? buytogether.get_updated_image.afile_updated_at
                                : "",
                              aspectRatio: _vm.aspectRatio,
                              index: btIndex
                            }
                          })
                        ],
                        1
                      )
                    }),
                    0
                  ),
                  _vm._v(" "),
                  _vm._m(1)
                ],
                1
              )
            ])
          ])
        : _vm._e(),
      _vm._v(" "),
      _vm.relatedproducts.length > 0
        ? _c("section", { staticClass: "section section--slider" }, [
            _c("div", { staticClass: "container" }, [
              _c(
                "div",
                { staticClass: "section__header d-flex align-items-center" },
                [
                  _c("h2", [_vm._v(_vm._s(_vm.$t("LBL_RELATED_PRODUCTS")))]),
                  _vm._v(" "),
                  _vm._m(2)
                ]
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "section__body" },
                [
                  _c(
                    "vue-slick-carousel",
                    _vm._b(
                      { staticClass: "d-grid" },
                      "vue-slick-carousel",
                      _vm.sliderSettings4Column,
                      false
                    ),
                    _vm._l(_vm.relatedproducts, function(related, rtIndex) {
                      return _c(
                        "div",
                        { key: rtIndex, staticClass: "item" },
                        [
                          _c("product-card2", {
                            attrs: {
                              product: related,
                              imageTime: related.get_updated_image
                                ? related.get_updated_image.afile_updated_at
                                : "",
                              aspectRatio: _vm.aspectRatio,
                              index: rtIndex
                            }
                          })
                        ],
                        1
                      )
                    }),
                    0
                  ),
                  _vm._v(" "),
                  _vm._m(3)
                ],
                1
              )
            ])
          ])
        : _vm._e(),
      _vm._v(" "),
      _vm.recentViewProducts.length > 0
        ? _c("section", { staticClass: "section bg-grayx" }, [
            _c("div", { staticClass: "container" }, [
              _c(
                "div",
                { staticClass: "section__header d-flex align-items-center" },
                [_c("h2", [_vm._v(_vm._s(_vm.$t("LBL_RECENTLY_VIEWED")))])]
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "section__body" },
                [
                  _c(
                    "vue-slick-carousel",
                    _vm._b(
                      {},
                      "vue-slick-carousel",
                      _vm.sliderSettings4Column,
                      false
                    ),
                    _vm._l(_vm.recentViewProducts, function(
                      recentViewProduct,
                      rvIndex
                    ) {
                      return _c(
                        "div",
                        { key: rvIndex, staticClass: "item" },
                        [
                          _c("product-card", {
                            attrs: {
                              product: recentViewProduct,
                              imageTime: recentViewProduct.get_updated_image
                                ? recentViewProduct.get_updated_image
                                    .afile_updated_at
                                : "",
                              aspectRatio: _vm.aspectRatio,
                              index: rvIndex
                            }
                          })
                        ],
                        1
                      )
                    }),
                    0
                  )
                ],
                1
              )
            ])
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("login-modal"),
      _vm._v(" "),
      _c("additional-info", {
        attrs: {
          product: _vm.product,
          additionalInfoExist: _vm.additionalInfoExist,
          specifications: _vm.specifications
        }
      })
    ],
    1
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "section__header-action ml-auto" }, [
      _c(
        "a",
        {
          staticClass: "btn btn--black btn--md btn--line",
          attrs: { href: "#" }
        },
        [_vm._v("All Products")]
      )
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass: "slider-controls mt-4",
        attrs: { "data-href": "#recommended" }
      },
      [
        _c("a", {
          staticClass: "slider-btn prev-arrow",
          attrs: { href: "javascript:void(0);" }
        }),
        _vm._v(" "),
        _c("a", {
          staticClass: "slider-btn next-arrow",
          attrs: { href: "javascript:void(0);" }
        })
      ]
    )
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "section__header-action ml-auto" }, [
      _c(
        "a",
        {
          staticClass: "btn btn--black btn--md btn--line",
          attrs: { href: "#" }
        },
        [_vm._v("All Products")]
      )
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass: "slider-controls mt-4",
        attrs: { "data-href": "#recommended" }
      },
      [
        _c("a", {
          staticClass: "slider-btn prev-arrow",
          attrs: { href: "javascript:void(0);" }
        }),
        _vm._v(" "),
        _c("a", {
          staticClass: "slider-btn next-arrow",
          attrs: { href: "javascript:void(0);" }
        })
      ]
    )
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Partials\\/productCard$":
/*!********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Partials\/productCard$ namespace object ***!
  \********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Partials/productCard": [
		"./resources/js/frontend/Themes/base/Partials/productCard.vue",
		0,
		25
	],
	"./fashion/Partials/productCard": [
		"./resources/js/frontend/Themes/fashion/Partials/productCard.vue",
		0,
		29
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Partials\\/productCard$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Partials\\/productCard2$":
/*!*********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Partials\/productCard2$ namespace object ***!
  \*********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Partials/productCard2": [
		"./resources/js/frontend/Themes/base/Partials/productCard2.vue",
		133
	],
	"./fashion/Partials/productCard2": [
		"./resources/js/frontend/Themes/fashion/Partials/productCard2.vue",
		30
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Partials\\/productCard2$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/AddToCartSection$":
/*!************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/AddToCartSection$ namespace object ***!
  \************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/AddToCartSection": [
		"./resources/js/frontend/Themes/base/Product/AddToCartSection.vue",
		18
	],
	"./fashion/Product/AddToCartSection": [
		"./resources/js/frontend/Themes/fashion/Product/AddToCartSection.vue",
		20
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/AddToCartSection$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/AdditionalInfo$":
/*!**********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/AdditionalInfo$ namespace object ***!
  \**********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./fashion/Product/AdditionalInfo": [
		"./resources/js/frontend/Themes/fashion/Product/AdditionalInfo.vue",
		142
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/AdditionalInfo$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/Index$":
/*!*********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/Review\/Index$ namespace object ***!
  \*********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/Review/Index": [
		"./resources/js/frontend/Themes/base/Product/Review/Index.vue",
		19
	],
	"./fashion/Product/Review/Index": [
		"./resources/js/frontend/Themes/fashion/Product/Review/Index.vue",
		17
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/Review\\/Index$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/SliderImages$":
/*!********************************************************************************************!*\
  !*** ./resources/js/frontend/Themes lazy ^\.\/.*\/Product\/SliderImages$ namespace object ***!
  \********************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./base/Product/SliderImages": [
		"./resources/js/frontend/Themes/base/Product/SliderImages.vue",
		1,
		28
	],
	"./fashion/Product/SliderImages": [
		"./resources/js/frontend/Themes/fashion/Product/SliderImages.vue",
		1,
		148
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
webpackAsyncContext.id = "./resources/js/frontend/Themes lazy recursive ^\\.\\/.*\\/Product\\/SliderImages$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Detail.vue":
/*!*****************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Detail.vue ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Detail_vue_vue_type_template_id_4997f924___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Detail.vue?vue&type=template&id=4997f924& */ "./resources/js/frontend/Themes/fashion/Product/Detail.vue?vue&type=template&id=4997f924&");
/* harmony import */ var _Detail_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Detail.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/fashion/Product/Detail.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Detail_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Detail_vue_vue_type_template_id_4997f924___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Detail_vue_vue_type_template_id_4997f924___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/fashion/Product/Detail.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Detail.vue?vue&type=script&lang=js&":
/*!******************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Detail.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Detail_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Detail.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Detail.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Detail_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/fashion/Product/Detail.vue?vue&type=template&id=4997f924&":
/*!************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/fashion/Product/Detail.vue?vue&type=template&id=4997f924& ***!
  \************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Detail_vue_vue_type_template_id_4997f924___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Detail.vue?vue&type=template&id=4997f924& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/fashion/Product/Detail.vue?vue&type=template&id=4997f924&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Detail_vue_vue_type_template_id_4997f924___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Detail_vue_vue_type_template_id_4997f924___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);