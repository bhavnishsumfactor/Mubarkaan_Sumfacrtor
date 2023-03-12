(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[28],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
var tableFields = {
  preview_title: "",
  preview_description: ""
};
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ["product", "canPost"],
  data: function data() {
    return {
      clicked: false,
      baseUrl: baseUrl,
      userData: tableFields,
      selectedRating: 0,
      imageIds: [],
      deleteIds: [],
      success: false,
      successMsg: ""
    };
  },
  methods: {
    submitReview: function submitReview() {
      var _this = this;

      var thisObj = this;
      this.clicked = true;
      var formData = this.$serializeData(this.userData);
      formData.append("preview_rating", this.selectedRating);
      formData.append("preview_prod_id", this.product.prod_id);
      formData.append("preview_order_id", this.canPost.order_id);
      formData.append("imageIds", this.imageIds);
      formData.append("deleteids", this.deleteIds);
      this.$axios.post(baseUrl + "/user/reviews", formData).then(function (response) {
        _this.clicked = false;
        _this.success = true;
        _this.successMsg = response.data.message;
        setTimeout(function () {
          thisObj.$bvModal.hide("reviewModel");

          _this.$emit("reviewSubmitted");
        }, 2000);
      });
    },
    removeImage: function removeImage(index, id) {
      var _this2 = this;

      this.$axios["delete"](baseUrl + "/user/reviews/images/" + id).then(function (response) {
        _this2.deleteIds.push(id);

        _this2.$delete(_this2.imageIds, index);
      });
    },
    // emptyForm: function() {
    //   this.userData = {
    //     preview_title: "",
    //     preview_description: "",
    //     preview_id: 0,
    //   };
    //   this.selectedRating = 0;
    //   this.imageIds = [];
    //   this.deleteIds = [];
    // },
    onSelectImage: function onSelectImage($event) {
      var _this3 = this;

      var formData = this.$serializeData({
        preview_id: this.userData.preview_id
      });
      var totalImages = parseInt(this.imageIds.length) + parseInt($event.target.files.length);

      if (totalImages > 5) {
        toastr.error("Max 5 images can be uploaded", "", toastr.options);
        return false;
      }

      var errorCount = 0;

      for (var i = 0; i < $event.target.files.length; i++) {
        if ($event.target.files[i].size > 2 * 1024 * 1024) {
          errorCount = errorCount + 1;
        } else {
          formData.append("preview_image[]", $event.target.files[i]);
        }
      }

      if (errorCount > 0) {
        toastr.error("Some Images with size more than 2 Mb are skipped. Please upload images with size less than 2 MB");
      }

      if (totalImages < errorCount) {
        return false;
      }

      this.$axios.post(baseUrl + "/user/reviews/images", formData).then(function (response) {
        if (response.data.data.length != 0) {
          _this3.imageIds = _this3.imageIds.concat(response.data.data);
        }
      });
    }
  }
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! toastr */ "./node_modules/toastr/toastr.js")))

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue?vue&type=template&id=5a6d6f8a&":
/*!**************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue?vue&type=template&id=5a6d6f8a& ***!
  \**************************************************************************************************************************************************************************************************************************************/
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
    "b-modal",
    {
      attrs: {
        id: "reviewModel",
        centered: "",
        title: "BootstrapVue",
        "hide-header": _vm.success,
        "hide-footer": _vm.success
      },
      scopedSlots: _vm._u([
        {
          key: "modal-header",
          fn: function() {
            return [
              _c("h5", { staticClass: "modal-title" }, [
                _vm._v(_vm._s(_vm.$t("LBL_REVIEW_YOUR_PURCHASE")))
              ]),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "close",
                  attrs: { type: "button" },
                  on: {
                    click: function($event) {
                      return _vm.$bvModal.hide("reviewModel")
                    }
                  }
                },
                [_c("span", [_vm._v("×")])]
              )
            ]
          },
          proxy: true
        },
        {
          key: "modal-footer",
          fn: function() {
            return [
              _c(
                "button",
                {
                  staticClass: "btn btn-brand btn-wide gb-btn gb-btn-primary",
                  class: _vm.clicked == 1 && "gb-is-loading",
                  attrs: {
                    type: "button",
                    disabled:
                      _vm.selectedRating == 0 ||
                      _vm.userData.preview_title == "" ||
                      _vm.clicked == 1
                  },
                  on: { click: _vm.submitReview }
                },
                [
                  _vm._v(
                    "\n      " + _vm._s(_vm.$t("BTN_REVIEW_SUBMIT")) + "\n    "
                  )
                ]
              )
            ]
          },
          proxy: true
        }
      ])
    },
    [
      _vm._v(" "),
      _vm.success === false
        ? _c(
            "form",
            {
              staticClass: "form",
              attrs: {
                id: "YK-submitReviewForm",
                method: "post",
                enctype: "multipart/form-data"
              }
            },
            [
              _c("div", { staticClass: "write-review" }, [
                _c("div", { staticClass: "write-review__purchase" }, [
                  _c("ul", { staticClass: "list-group" }, [
                    _c("li", { staticClass: "list-group-item" }, [
                      _c("div", { staticClass: "product-profile" }, [
                        _c(
                          "div",
                          { staticClass: "product-profile__thumbnail" },
                          [
                            _c("img", {
                              staticClass: "img-fluid",
                              attrs: {
                                "data-ratio": "3:4",
                                src: _vm.$productImage(
                                  _vm.product.prod_id,
                                  _vm.canPost.op_pov_code,
                                  _vm.product.images
                                    ? _vm.product.images.afile_updated_at
                                    : "",
                                  "38-51"
                                ),
                                alt: "..."
                              }
                            })
                          ]
                        ),
                        _vm._v(" "),
                        _c("div", { staticClass: "product-profile__data" }, [
                          _c("div", { staticClass: "title" }, [
                            _vm._v(_vm._s(_vm.product.prod_name))
                          ])
                        ])
                      ])
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "write-review__rate" }, [
                  _c("h6", [_vm._v(_vm._s(_vm.$t("LBL_RATE_THIS_PRODUCT")))]),
                  _vm._v(" "),
                  _c("div", { staticClass: "rating" }, [
                    _c(
                      "div",
                      {
                        staticClass: "rating-action",
                        attrs: { "data-rating": _vm.selectedRating }
                      },
                      [
                        _c(
                          "svg",
                          {
                            staticClass: "icon YK-ratingItem",
                            attrs: { width: "24", height: "24" },
                            on: {
                              click: function($event) {
                                _vm.selectedRating = 5
                              }
                            }
                          },
                          [
                            _c("use", {
                              attrs: {
                                "xlink:href":
                                  _vm.baseUrl +
                                  "/dashboard/media/retina/sprite.svg#star",
                                href:
                                  _vm.baseUrl +
                                  "/dashboard/media/retina/sprite.svg#star"
                              }
                            })
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "svg",
                          {
                            staticClass: "icon YK-ratingItem",
                            attrs: { width: "24", height: "24" },
                            on: {
                              click: function($event) {
                                _vm.selectedRating = 4
                              }
                            }
                          },
                          [
                            _c("use", {
                              attrs: {
                                "xlink:href":
                                  _vm.baseUrl +
                                  "/dashboard/media/retina/sprite.svg#star",
                                href:
                                  _vm.baseUrl +
                                  "/dashboard/media/retina/sprite.svg#star"
                              }
                            })
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "svg",
                          {
                            staticClass: "icon YK-ratingItem",
                            attrs: { width: "24", height: "24" },
                            on: {
                              click: function($event) {
                                _vm.selectedRating = 3
                              }
                            }
                          },
                          [
                            _c("use", {
                              attrs: {
                                "xlink:href":
                                  _vm.baseUrl +
                                  "/dashboard/media/retina/sprite.svg#star",
                                href:
                                  _vm.baseUrl +
                                  "/dashboard/media/retina/sprite.svg#star"
                              }
                            })
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "svg",
                          {
                            staticClass: "icon YK-ratingItem",
                            attrs: { width: "24", height: "24" },
                            on: {
                              click: function($event) {
                                _vm.selectedRating = 2
                              }
                            }
                          },
                          [
                            _c("use", {
                              attrs: {
                                "xlink:href":
                                  _vm.baseUrl +
                                  "/dashboard/media/retina/sprite.svg#star",
                                href:
                                  _vm.baseUrl +
                                  "/dashboard/media/retina/sprite.svg#star"
                              }
                            })
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "svg",
                          {
                            staticClass: "icon YK-ratingItem",
                            attrs: { width: "24", height: "24" },
                            on: {
                              click: function($event) {
                                _vm.selectedRating = 1
                              }
                            }
                          },
                          [
                            _c("use", {
                              attrs: {
                                "xlink:href":
                                  _vm.baseUrl +
                                  "/dashboard/media/retina/sprite.svg#star",
                                href:
                                  _vm.baseUrl +
                                  "/dashboard/media/retina/sprite.svg#star"
                              }
                            })
                          ]
                        )
                      ]
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "write-review__about" }, [
                  _c("h6", [
                    _vm._v(_vm._s(_vm.$t("LBL_GIVE_FEEDBACK_ABOUT_PRODUCT")))
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "form-group" }, [
                    _c("label", {}, [
                      _vm._v(_vm._s(_vm.$t("LBL_REVIEW_TITLE")))
                    ]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.userData.preview_title,
                          expression: "userData.preview_title"
                        }
                      ],
                      staticClass: "form-control",
                      class: [_vm.userData.preview_title != "" ? "filled" : ""],
                      attrs: {
                        type: "text",
                        name: "preview_title",
                        id: "preview_title"
                      },
                      domProps: { value: _vm.userData.preview_title },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(
                            _vm.userData,
                            "preview_title",
                            $event.target.value
                          )
                        }
                      }
                    })
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "form-group" }, [
                    _c("label", {}, [
                      _vm._v(_vm._s(_vm.$t("LBL_REVIEW_DESCRIPTION")))
                    ]),
                    _vm._v(" "),
                    _c("textarea", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.userData.preview_description,
                          expression: "userData.preview_description"
                        }
                      ],
                      staticClass: "form-control",
                      class: [
                        _vm.userData.preview_description != "" ? "filled" : ""
                      ],
                      staticStyle: { resize: "none" },
                      attrs: { cols: "30", rows: "10" },
                      domProps: { value: _vm.userData.preview_description },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(
                            _vm.userData,
                            "preview_description",
                            $event.target.value
                          )
                        }
                      }
                    })
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "write-review__media" }, [
                  _c(
                    "ul",
                    { staticClass: "list-media YK-uploadedImagesPreview" },
                    [
                      _vm._l(_vm.imageIds, function(imageId, imageKey) {
                        return _c(
                          "li",
                          { key: imageKey, staticClass: "remove-able" },
                          [
                            _c("img", {
                              attrs: {
                                "data-yk": "",
                                src:
                                  _vm.baseUrl +
                                  "/yokart/image/" +
                                  imageId +
                                  "/small",
                                alt: ""
                              }
                            }),
                            _vm._v(" "),
                            _c(
                              "button",
                              {
                                staticClass: "remove YK-removeReviewImage",
                                attrs: { type: "button" },
                                on: {
                                  click: function($event) {
                                    return _vm.removeImage(imageKey, imageId)
                                  }
                                }
                              },
                              [_c("i", { staticClass: "fas fa-times" })]
                            )
                          ]
                        )
                      }),
                      _vm._v(" "),
                      _c("li", { staticClass: "upload" }, [
                        _c("i", { staticClass: "fas fa-camera" }),
                        _vm._v(" "),
                        _c("input", {
                          attrs: {
                            type: "file",
                            accept: "image/*",
                            id: "images",
                            multiple: "multiple"
                          },
                          on: {
                            change: function($event) {
                              return _vm.onSelectImage($event)
                            }
                          }
                        })
                      ])
                    ],
                    2
                  )
                ])
              ])
            ]
          )
        : _c("div", { staticClass: "my-1 pb-5 text-center" }, [
            _c("div", { staticClass: "success-animation" }, [
              _c("div", { staticClass: "svg-box" }, [
                _c("svg", { staticClass: "circular green-stroke" }, [
                  _c("circle", {
                    staticClass: "path",
                    attrs: {
                      cx: "75",
                      cy: "75",
                      r: "50",
                      fill: "none",
                      "stroke-width": "5",
                      "stroke-miterlimit": "10"
                    }
                  })
                ]),
                _c("svg", { staticClass: "checkmark green-stroke" }, [
                  _c(
                    "g",
                    {
                      attrs: {
                        transform:
                          "matrix(0.79961,8.65821e-32,8.39584e-32,0.79961,-489.57,-205.679)"
                      }
                    },
                    [
                      _c("path", {
                        staticClass: "checkmark__check",
                        attrs: {
                          fill: "none",
                          d: "M616.306,283.025L634.087,300.805L673.361,261.53"
                        }
                      })
                    ]
                  )
                ])
              ])
            ]),
            _vm._v(" "),
            _c("p", [_vm._v(_vm._s(_vm.successMsg))])
          ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue":
/*!*************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _reviewForm_vue_vue_type_template_id_5a6d6f8a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./reviewForm.vue?vue&type=template&id=5a6d6f8a& */ "./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue?vue&type=template&id=5a6d6f8a&");
/* harmony import */ var _reviewForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./reviewForm.vue?vue&type=script&lang=js& */ "./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _reviewForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _reviewForm_vue_vue_type_template_id_5a6d6f8a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _reviewForm_vue_vue_type_template_id_5a6d6f8a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/Themes/base/Product/Review/reviewForm.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_reviewForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./reviewForm.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_reviewForm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue?vue&type=template&id=5a6d6f8a&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue?vue&type=template&id=5a6d6f8a& ***!
  \********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_reviewForm_vue_vue_type_template_id_5a6d6f8a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./reviewForm.vue?vue&type=template&id=5a6d6f8a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/Themes/base/Product/Review/reviewForm.vue?vue&type=template&id=5a6d6f8a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_reviewForm_vue_vue_type_template_id_5a6d6f8a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_reviewForm_vue_vue_type_template_id_5a6d6f8a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);