(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[13],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Image.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Blog/Image.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ["blog"],
  data: function data() {
    return {
      baseUrl: baseUrl
    };
  },
  methods: {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Index.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Blog/Index.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-slick-carousel */ "./node_modules/vue-slick-carousel/dist/vue-slick-carousel.umd.js");
/* harmony import */ var vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-slick-carousel/dist/vue-slick-carousel.css */ "./node_modules/vue-slick-carousel/dist/vue-slick-carousel.css");
/* harmony import */ var vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_slick_carousel_dist_vue_slick_carousel_css__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Image__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Image */ "./resources/js/frontend/Blog/Image.vue");
/* harmony import */ var _Summary__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Summary */ "./resources/js/frontend/Blog/Summary.vue");
/* harmony import */ var _Subscription__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Subscription */ "./resources/js/frontend/Blog/Subscription.vue");
/* harmony import */ var _frontend_Partials_Pagination__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @/frontend/Partials/Pagination */ "./resources/js/frontend/Partials/Pagination.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    VueSlickCarousel: vue_slick_carousel__WEBPACK_IMPORTED_MODULE_0___default.a,
    BlogImage: _Image__WEBPACK_IMPORTED_MODULE_2__["default"],
    BlogSummary: _Summary__WEBPACK_IMPORTED_MODULE_3__["default"],
    Subscription: _Subscription__WEBPACK_IMPORTED_MODULE_4__["default"],
    Pagination: _frontend_Partials_Pagination__WEBPACK_IMPORTED_MODULE_5__["default"]
  },
  props: ["latestBlogs", "blogs", "featuredBlogs", "query", "categories"],
  data: function data() {
    return {
      baseUrl: baseUrl,
      featuredSliderSettings: {
        dots: false,
        arrows: true,
        infinite: false,
        speed: 300,
        "default": true,
        responsive: [{
          breakpoint: 767,
          settings: {
            arrows: false
          }
        }, {
          breakpoint: 1199,
          settings: {
            arrows: false
          }
        }]
      }
    };
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Subscription.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Blog/Subscription.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      baseUrl: baseUrl
    };
  },
  methods: {
    subscribeNewsletter: function subscribeNewsletter(values, thisVariable) {
      var thisObj = this;
      NProgress.start();
      this.$axios.post(baseUrl + "/newsletter", values).then(function (response) {
        NProgress.done();

        if (response.data.status) {
          toastr.success(response.data.message);
          thisVariable.closest('form').find('input[name=email]').val('');
        } else {
          toastr.error(response.data.message);
        }
      });
    }
  },
  mounted: function mounted() {
    var thisObj = this;
    $('.YK-subscribeNewsletter').attr('disabled', 'disabled');
    $(document).on('keyup', '.yk-newsletter input[type="email"], .yk-newsletterFullwidth input[type="email"], .yk-newsletterCollection2 input[type="email"], .yk-footer input[name="email"]', function (e) {
      var emailValue = $(this).val();

      if (emailValue == "") {
        $(this).closest('form').find('.YK-subscribeNewsletter').attr('disabled', 'disabled');
      } else {
        $(this).closest('form').find('.YK-subscribeNewsletter').removeAttr('disabled');
      }
    });
    $('.YK-subscribeNewsletter').on('click', function (e) {
      e.preventDefault();
      var values = $(this).closest('form').serialize();
      $(this).closest('form').find('div.message').html('');
      thisObj.subscribeNewsletter(values, $(this));
    });
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! toastr */ "./node_modules/toastr/toastr.js")))

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Summary.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Blog/Summary.vue?vue&type=script&lang=js& ***!
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
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ["blog"],
  data: function data() {
    return {
      baseUrl: baseUrl
    };
  },
  methods: {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/Pagination.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Partials/Pagination.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    links: Array
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Subscription.vue?vue&type=style&index=0&id=1932f3aa&scoped=true&lang=css&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Blog/Subscription.vue?vue&type=style&index=0&id=1932f3aa&scoped=true&lang=css& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\nsvg .a[data-v-1932f3aa] {\n    fill: none;\n    stroke: #e72548;\n    stroke-linecap: round;\n    stroke-linejoin: round;\n    stroke-width: 3px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Subscription.vue?vue&type=style&index=0&id=1932f3aa&scoped=true&lang=css&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Blog/Subscription.vue?vue&type=style&index=0&id=1932f3aa&scoped=true&lang=css& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--5-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--5-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./Subscription.vue?vue&type=style&index=0&id=1932f3aa&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Subscription.vue?vue&type=style&index=0&id=1932f3aa&scoped=true&lang=css&");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Image.vue?vue&type=template&id=ab992386&":
/*!***********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Blog/Image.vue?vue&type=template&id=ab992386& ***!
  \***********************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "blog-media" }, [
    _c("a", { attrs: { href: _vm.baseUrl + "/" + _vm.blog.record_slug } }, [
      _c("img", {
        attrs: {
          "data-yk": "",
          src: _vm.blog.blog_image,
          alt: _vm.blog.img_alt,
          title: _vm.blog.img_title,
          "data-aspect-ratio": "4:3"
        }
      })
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Index.vue?vue&type=template&id=a32d4b98&":
/*!***********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Blog/Index.vue?vue&type=template&id=a32d4b98& ***!
  \***********************************************************************************************************************************************************************************************************/
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
    [
      _c("div", { staticClass: "blog-wrapper" }, [
        _c("div", { staticClass: "container" }, [
          _c(
            "section",
            { staticClass: "blog-slider site--blog" },
            [
              _c(
                "vue-slick-carousel",
                _vm._b(
                  {},
                  "vue-slick-carousel",
                  _vm.featuredSliderSettings,
                  false
                ),
                _vm._l(_vm.featuredBlogs, function(featuredBlog, findex) {
                  return _c(
                    "figure",
                    {
                      key: "featuredBlog_" + findex,
                      staticClass: "slide__figure"
                    },
                    [
                      _c("div", { staticClass: "row no-gutters w-100" }, [
                        _c("div", { staticClass: "col-xl-8 col-md-7" }, [
                          _c("div", { staticClass: "slide-img" }, [
                            _c(
                              "a",
                              {
                                attrs: {
                                  href:
                                    _vm.baseUrl + "/" + featuredBlog.record_slug
                                }
                              },
                              [
                                _c("div", { staticClass: "slide-img-wrap" }, [
                                  _c("img", {
                                    attrs: {
                                      "data-yk": "",
                                      src:
                                        _vm.baseUrl +
                                        "/yokart/image/blogImage/" +
                                        featuredBlog.post_id +
                                        "/0/1000-750?t=" +
                                        featuredBlog.timestamp,
                                      alt: "",
                                      "data-aspect-ratio": "4:3"
                                    }
                                  })
                                ])
                              ]
                            )
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "col-xl-4 col-md-5" }, [
                          _c("figcaption", [
                            _c(
                              "div",
                              { staticClass: "blog_slider_detail inner-block" },
                              [
                                _c("div", { staticClass: "blog-date" }, [
                                  _vm._v(_vm._s(_vm.$t("LBL_BY")) + " "),
                                  _c("strong", [
                                    _vm._v(
                                      _vm._s(featuredBlog.post_author_name)
                                    )
                                  ]),
                                  _vm._v(" - "),
                                  _c("span", [
                                    _vm._v(_vm._s(featuredBlog.post_date))
                                  ])
                                ]),
                                _vm._v(" "),
                                _c("h3", [
                                  _vm._v(_vm._s(featuredBlog.post_title))
                                ]),
                                _vm._v(" "),
                                _c("p", [
                                  _vm._v(
                                    _vm._s(
                                      featuredBlog.bpc_short_description.substring(
                                        0,
                                        200
                                      )
                                    )
                                  )
                                ]),
                                _vm._v(" "),
                                _c(
                                  "a",
                                  {
                                    staticClass:
                                      "btn btn-blog btn-outline-dark btn-wide",
                                    attrs: {
                                      href:
                                        _vm.baseUrl +
                                        "/" +
                                        featuredBlog.record_slug
                                    }
                                  },
                                  [_vm._v(_vm._s(_vm.$t("BTN_READ_MORE")))]
                                )
                              ]
                            )
                          ])
                        ])
                      ])
                    ]
                  )
                }),
                0
              )
            ],
            1
          )
        ])
      ]),
      _vm._v(" "),
      _vm.query === ""
        ? _c(
            "div",
            _vm._l(_vm.latestBlogs, function(latestBlog, lbindex) {
              return _c(
                "section",
                {
                  key: "latestBlog_" + lbindex,
                  staticClass: "section blog--block"
                },
                [
                  _c("div", { staticClass: "container" }, [
                    lbindex === 1
                      ? _c("div", { staticClass: "row align-items-center" }, [
                          _c(
                            "div",
                            { staticClass: "col-xl-8 col-md-7" },
                            [_c("blog-image", { attrs: { blog: latestBlog } })],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "div",
                            { staticClass: "col-xl-3 col-md-5 offset-xl-1" },
                            [
                              _c("blog-summary", {
                                attrs: { blog: latestBlog }
                              })
                            ],
                            1
                          )
                        ])
                      : _c(
                          "div",
                          {
                            staticClass:
                              "row align-items-center flex-column-reverse flex-md-row"
                          },
                          [
                            _c(
                              "div",
                              { staticClass: "col-xl-3 col-md-5" },
                              [
                                _c("blog-summary", {
                                  attrs: { blog: latestBlog }
                                })
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c(
                              "div",
                              { staticClass: "col-xl-8 col-md-7 offset-xl-1" },
                              [
                                _c("blog-image", {
                                  attrs: { blog: latestBlog }
                                })
                              ],
                              1
                            )
                          ]
                        )
                  ])
                ]
              )
            }),
            0
          )
        : _vm._e(),
      _vm._v(" "),
      _c("section", { staticClass: "section blog--content pt-5" }, [
        _c("div", { staticClass: "container" }, [
          _c("div", { staticClass: "row" }, [
            _c("div", { staticClass: "col-12 col-md blog-content" }, [
              _vm.blogs.length === 0
                ? _c(
                    "div",
                    { staticClass: "no-data-found no-data-found--md" },
                    [
                      _c("div", { staticClass: "img" }, [
                        _c("img", {
                          attrs: {
                            "data-yk": "",
                            src:
                              _vm.baseUrl + "/blogs/images/retina/no-blogs.svg",
                            alt: ""
                          }
                        })
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "data" }, [
                        _c("h2", [_vm._v(_vm._s(_vm.$t("LBL_NO_BLOGS")))]),
                        _vm._v(" "),
                        _c("p", [
                          _vm._v(
                            _vm._s(_vm.$t("LBL_THERE_ARE_NO_BLOGS_TO_SHOW")) +
                              " "
                          )
                        ])
                      ])
                    ]
                  )
                : _c(
                    "div",
                    _vm._l(_vm.blogs.data, function(blog, bindex) {
                      return _c(
                        "div",
                        {
                          key: "blog_" + bindex,
                          staticClass: "row d-flex mb-4"
                        },
                        [
                          _c("div", { staticClass: "col-md-6" }, [
                            _c("div", { staticClass: "inner-media" }, [
                              _c(
                                "a",
                                {
                                  attrs: {
                                    href: _vm.baseUrl + "/" + blog.record_slug
                                  }
                                },
                                [
                                  _c("img", {
                                    attrs: {
                                      "data-yk": "",
                                      src: blog.blog_image,
                                      alt: blog.img_alt,
                                      title: blog.img_title,
                                      "data-aspect-ratio": "4:3"
                                    }
                                  })
                                ]
                              )
                            ])
                          ]),
                          _vm._v(" "),
                          _c("div", { staticClass: "col-md-6" }, [
                            _c("div", { staticClass: "inner-block" }, [
                              _c("h3", [
                                _c(
                                  "a",
                                  {
                                    attrs: {
                                      href: _vm.baseUrl + "/" + blog.record_slug
                                    }
                                  },
                                  [_vm._v(_vm._s(blog.post_title))]
                                )
                              ]),
                              _vm._v(" "),
                              _c("p", [
                                _vm._v(_vm._s(blog.bpc_short_description))
                              ]),
                              _vm._v(" "),
                              _c("div", { staticClass: "blog-date" }, [
                                _vm._v(_vm._s(_vm.$t("LBL_BY")) + " "),
                                _c("strong", [
                                  _vm._v(_vm._s(blog.post_author_name))
                                ]),
                                _vm._v(" - "),
                                _c("span", [_vm._v(_vm._s(blog.post_date))])
                              ]),
                              _vm._v(" "),
                              _c(
                                "a",
                                {
                                  staticClass: "btn btn-blog btn-wide",
                                  attrs: {
                                    href: _vm.baseUrl + "/" + blog.record_slug
                                  }
                                },
                                [_vm._v("Read more")]
                              )
                            ])
                          ])
                        ]
                      )
                    }),
                    0
                  )
            ]),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass: "col offset-lg-1 flex-grow-0 blog-sidebar",
                attrs: { id: "blog-sidebar" }
              },
              [
                _c("div", { staticClass: "block-sticky" }, [
                  _c("div", { staticClass: "panel-box-blog" }, [
                    _c("h6", { staticClass: "mb-3" }, [
                      _vm._v(_vm._s(_vm.$t("LBL_BLOGS_CATEGORIES")))
                    ]),
                    _vm._v(" "),
                    _c(
                      "ul",
                      {
                        staticClass:
                          "list-group list-group-flush-x mb-5 list-categories"
                      },
                      _vm._l(_vm.categories, function(category, cindex) {
                        return _c(
                          "li",
                          {
                            key: "category_" + cindex,
                            staticClass: "list-group-item"
                          },
                          [
                            _c(
                              "a",
                              {
                                staticClass:
                                  "d-flex justify-content-between align-items-center",
                                attrs: {
                                  href:
                                    _vm.baseUrl + "/blogs/" + category.bpcat_id
                                }
                              },
                              [
                                _vm._v(
                                  _vm._s(category.bpcat_name) +
                                    "(" +
                                    _vm._s(category.blogs_count) +
                                    ")"
                                ),
                                _vm._m(0, true)
                              ]
                            )
                          ]
                        )
                      }),
                      0
                    )
                  ])
                ])
              ]
            )
          ]),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "pagination-wrapper" },
            [
              _c("pagination", {
                staticClass: "mt-6",
                attrs: { links: _vm.blogs.links }
              })
            ],
            1
          )
        ])
      ]),
      _vm._v(" "),
      _c("subscription")
    ],
    1
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("span", [
      _c("i", {
        staticClass: "fa fa-chevron-right",
        attrs: { "aria-hidden": "true" }
      })
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Subscription.vue?vue&type=template&id=1932f3aa&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Blog/Subscription.vue?vue&type=template&id=1932f3aa&scoped=true& ***!
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
  return _c("section", { staticClass: "section section-subscribe" }, [
    _c("div", { staticClass: "container" }, [
      _c("div", { staticClass: "row justify-content-center" }, [
        _c("div", { staticClass: "col-xl-5 col-md-7" }, [
          _c("div", { staticClass: "subscribe-wrapper" }, [
            _c("div", { staticClass: "subscribe-icon mb-2" }, [
              _c(
                "svg",
                {
                  attrs: {
                    xmlns: "http://www.w3.org/2000/svg",
                    width: "44.178",
                    height: "35",
                    viewBox: "0 0 44.178 35"
                  }
                },
                [
                  _c("g", { attrs: { transform: "translate(-0.911 -4.5)" } }, [
                    _c("path", {
                      staticClass: "a",
                      attrs: {
                        d:
                          "M7,6H39a4.012,4.012,0,0,1,4,4V34a4.012,4.012,0,0,1-4,4H7a4.012,4.012,0,0,1-4-4V10A4.012,4.012,0,0,1,7,6Z"
                      }
                    }),
                    _vm._v(" "),
                    _c("path", {
                      staticClass: "a",
                      attrs: {
                        d: "M43,9,23,23,3,9",
                        transform: "translate(0 1)"
                      }
                    })
                  ])
                ]
              )
            ]),
            _vm._v(" "),
            _c("h3", [_vm._v(_vm._s(_vm.$t("LBL_SUBSCRIBE_TO_NEWSLETTER")))]),
            _vm._v(" "),
            _c("p", [
              _vm._v(_vm._s(_vm.$t("LBL_BE_FIRST_TO_KNOW_ABOUT")) + ".")
            ]),
            _vm._v(" "),
            _c(
              "form",
              { staticClass: "yk-newsletter", attrs: { method: "POST" } },
              [
                _c("input", {
                  attrs: {
                    type: "email",
                    name: "email",
                    placeholder: _vm.$t("PLH_ENTER_YOUR_EMAIL_ADDRESS")
                  }
                }),
                _vm._v(" "),
                _vm._m(0)
              ]
            )
          ])
        ])
      ])
    ])
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "button",
      {
        staticClass: "btn btn-submit btn-block YK-subscribeNewsletter",
        attrs: { type: "submit", name: "subscribe", value: "Submit" }
      },
      [_c("i", { staticClass: "fa fa-arrow-right ml-2" })]
    )
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Summary.vue?vue&type=template&id=61428488&":
/*!*************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Blog/Summary.vue?vue&type=template&id=61428488& ***!
  \*************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "inner-block" }, [
    _c("a", { attrs: { href: _vm.baseUrl + "/" + _vm.blog.record_slug } }, [
      _c("h3", [_vm._v(_vm._s(_vm.blog.post_title))])
    ]),
    _vm._v(" "),
    _c("p", [_vm._v(_vm._s(_vm.blog.bpc_short_description.substring(0, 200)))]),
    _vm._v(" "),
    _c("div", { staticClass: "blog-date" }, [
      _vm._v(_vm._s(_vm.$t("LBL_BY")) + " "),
      _c("strong", [_vm._v(_vm._s(_vm.blog.post_author_name))]),
      _vm._v(" - "),
      _c("span", [_vm._v(_vm._s(_vm.blog.post_date))])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/Pagination.vue?vue&type=template&id=4f1b5c90&":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Partials/Pagination.vue?vue&type=template&id=4f1b5c90& ***!
  \********************************************************************************************************************************************************************************************************************/
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
  return _vm.links.length > 3
    ? _c("div", [
        _c("div", { staticClass: "flex flex-wrap -mb-1" }, [
          _c(
            "div",
            { staticClass: "pagination" },
            _vm._l(_vm.links, function(link, p) {
              return _c(
                "li",
                {
                  key: p,
                  staticClass: "page-item",
                  class: { active: link.active }
                },
                [
                  link.url === null
                    ? _c(
                        "span",
                        {
                          staticClass: "page-link",
                          domProps: { innerHTML: _vm._s(link.label) }
                        },
                        [_vm._v("1")]
                      )
                    : _c("inertia-link", {
                        staticClass: "page-link",
                        attrs: { href: link.url },
                        domProps: { innerHTML: _vm._s(link.label) }
                      })
                ],
                1
              )
            }),
            0
          )
        ])
      ])
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Blog/Image.vue":
/*!**********************************************!*\
  !*** ./resources/js/frontend/Blog/Image.vue ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Image_vue_vue_type_template_id_ab992386___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Image.vue?vue&type=template&id=ab992386& */ "./resources/js/frontend/Blog/Image.vue?vue&type=template&id=ab992386&");
/* harmony import */ var _Image_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Image.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Blog/Image.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Image_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Image_vue_vue_type_template_id_ab992386___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Image_vue_vue_type_template_id_ab992386___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Blog/Image.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Blog/Image.vue?vue&type=script&lang=js&":
/*!***********************************************************************!*\
  !*** ./resources/js/frontend/Blog/Image.vue?vue&type=script&lang=js& ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Image_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Image.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Image.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Image_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Blog/Image.vue?vue&type=template&id=ab992386&":
/*!*****************************************************************************!*\
  !*** ./resources/js/frontend/Blog/Image.vue?vue&type=template&id=ab992386& ***!
  \*****************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Image_vue_vue_type_template_id_ab992386___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Image.vue?vue&type=template&id=ab992386& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Image.vue?vue&type=template&id=ab992386&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Image_vue_vue_type_template_id_ab992386___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Image_vue_vue_type_template_id_ab992386___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/frontend/Blog/Index.vue":
/*!**********************************************!*\
  !*** ./resources/js/frontend/Blog/Index.vue ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Index_vue_vue_type_template_id_a32d4b98___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=a32d4b98& */ "./resources/js/frontend/Blog/Index.vue?vue&type=template&id=a32d4b98&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Blog/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Index_vue_vue_type_template_id_a32d4b98___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Index_vue_vue_type_template_id_a32d4b98___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Blog/Index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Blog/Index.vue?vue&type=script&lang=js&":
/*!***********************************************************************!*\
  !*** ./resources/js/frontend/Blog/Index.vue?vue&type=script&lang=js& ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Blog/Index.vue?vue&type=template&id=a32d4b98&":
/*!*****************************************************************************!*\
  !*** ./resources/js/frontend/Blog/Index.vue?vue&type=template&id=a32d4b98& ***!
  \*****************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_a32d4b98___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=template&id=a32d4b98& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Index.vue?vue&type=template&id=a32d4b98&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_a32d4b98___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_a32d4b98___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/frontend/Blog/Subscription.vue":
/*!*****************************************************!*\
  !*** ./resources/js/frontend/Blog/Subscription.vue ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Subscription_vue_vue_type_template_id_1932f3aa_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Subscription.vue?vue&type=template&id=1932f3aa&scoped=true& */ "./resources/js/frontend/Blog/Subscription.vue?vue&type=template&id=1932f3aa&scoped=true&");
/* harmony import */ var _Subscription_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Subscription.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Blog/Subscription.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Subscription_vue_vue_type_style_index_0_id_1932f3aa_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Subscription.vue?vue&type=style&index=0&id=1932f3aa&scoped=true&lang=css& */ "./resources/js/frontend/Blog/Subscription.vue?vue&type=style&index=0&id=1932f3aa&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Subscription_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Subscription_vue_vue_type_template_id_1932f3aa_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Subscription_vue_vue_type_template_id_1932f3aa_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "1932f3aa",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Blog/Subscription.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Blog/Subscription.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/js/frontend/Blog/Subscription.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Subscription.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Subscription.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Blog/Subscription.vue?vue&type=style&index=0&id=1932f3aa&scoped=true&lang=css&":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/frontend/Blog/Subscription.vue?vue&type=style&index=0&id=1932f3aa&scoped=true&lang=css& ***!
  \**************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_style_index_0_id_1932f3aa_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--5-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--5-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./Subscription.vue?vue&type=style&index=0&id=1932f3aa&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Subscription.vue?vue&type=style&index=0&id=1932f3aa&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_style_index_0_id_1932f3aa_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_style_index_0_id_1932f3aa_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_style_index_0_id_1932f3aa_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_style_index_0_id_1932f3aa_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/frontend/Blog/Subscription.vue?vue&type=template&id=1932f3aa&scoped=true&":
/*!************************************************************************************************!*\
  !*** ./resources/js/frontend/Blog/Subscription.vue?vue&type=template&id=1932f3aa&scoped=true& ***!
  \************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_template_id_1932f3aa_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Subscription.vue?vue&type=template&id=1932f3aa&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Subscription.vue?vue&type=template&id=1932f3aa&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_template_id_1932f3aa_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Subscription_vue_vue_type_template_id_1932f3aa_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/frontend/Blog/Summary.vue":
/*!************************************************!*\
  !*** ./resources/js/frontend/Blog/Summary.vue ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Summary_vue_vue_type_template_id_61428488___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Summary.vue?vue&type=template&id=61428488& */ "./resources/js/frontend/Blog/Summary.vue?vue&type=template&id=61428488&");
/* harmony import */ var _Summary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Summary.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Blog/Summary.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Summary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Summary_vue_vue_type_template_id_61428488___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Summary_vue_vue_type_template_id_61428488___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Blog/Summary.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Blog/Summary.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/frontend/Blog/Summary.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Summary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Summary.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Summary.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Summary_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Blog/Summary.vue?vue&type=template&id=61428488&":
/*!*******************************************************************************!*\
  !*** ./resources/js/frontend/Blog/Summary.vue?vue&type=template&id=61428488& ***!
  \*******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Summary_vue_vue_type_template_id_61428488___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Summary.vue?vue&type=template&id=61428488& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Blog/Summary.vue?vue&type=template&id=61428488&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Summary_vue_vue_type_template_id_61428488___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Summary_vue_vue_type_template_id_61428488___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/frontend/Partials/Pagination.vue":
/*!*******************************************************!*\
  !*** ./resources/js/frontend/Partials/Pagination.vue ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Pagination_vue_vue_type_template_id_4f1b5c90___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Pagination.vue?vue&type=template&id=4f1b5c90& */ "./resources/js/frontend/Partials/Pagination.vue?vue&type=template&id=4f1b5c90&");
/* harmony import */ var _Pagination_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Pagination.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Partials/Pagination.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Pagination_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Pagination_vue_vue_type_template_id_4f1b5c90___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Pagination_vue_vue_type_template_id_4f1b5c90___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Partials/Pagination.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Partials/Pagination.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/js/frontend/Partials/Pagination.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Pagination_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Pagination.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/Pagination.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Pagination_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Partials/Pagination.vue?vue&type=template&id=4f1b5c90&":
/*!**************************************************************************************!*\
  !*** ./resources/js/frontend/Partials/Pagination.vue?vue&type=template&id=4f1b5c90& ***!
  \**************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Pagination_vue_vue_type_template_id_4f1b5c90___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Pagination.vue?vue&type=template&id=4f1b5c90& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Partials/Pagination.vue?vue&type=template&id=4f1b5c90&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Pagination_vue_vue_type_template_id_4f1b5c90___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Pagination_vue_vue_type_template_id_4f1b5c90___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);