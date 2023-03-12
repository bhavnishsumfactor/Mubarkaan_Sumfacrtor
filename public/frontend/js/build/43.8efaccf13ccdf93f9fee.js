(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[43],{

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
      auth: window.auth,
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
    updatePayment: function updatePayment(order) {
      var formData = this.$serializeData({
        order_id: order.order_id
      });
      this.$axios.post(baseUrl + "/order/payment-url", formData).then(function (response) {
        window.open(response.data.data.url, "_self");
      });
    },
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
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "body", attrs: { id: "body", "data-yk": "" } },
    [
      _c("section", { staticClass: "py-2 order-completed" }, [
        _c("div", { staticClass: "container" }, [
          _c("div", { staticClass: "row justify-content-center" }, [
            _c("div", { staticClass: "col-xl-9" }, [
              _c("div", { staticClass: "thanks-screen text-center" }, [
                _c("div", { staticClass: "success-animation" }, [
                  _vm.success == true
                    ? _c("div", { staticClass: "svg-box" }, [
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
                        _vm._v(" "),
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
                                  d:
                                    "M616.306,283.025L634.087,300.805L673.361,261.53"
                                }
                              })
                            ]
                          )
                        ])
                      ])
                    : _c("div", { staticClass: "svg-box" }, [
                        _c("svg", { staticClass: "circular red-stroke" }, [
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
                        _vm._v(" "),
                        _c("svg", { staticClass: "cross red-stroke" }, [
                          _c(
                            "g",
                            {
                              attrs: {
                                transform:
                                  "matrix(0.79961,8.65821e-32,8.39584e-32,0.79961,-502.652,-204.518)"
                              }
                            },
                            [
                              _c("path", {
                                staticClass: "first-line",
                                attrs: {
                                  d: "M634.087,300.805L673.361,261.53",
                                  fill: "none"
                                }
                              })
                            ]
                          ),
                          _vm._v(" "),
                          _c(
                            "g",
                            {
                              attrs: {
                                transform:
                                  "matrix(-1.28587e-16,-0.79961,0.79961,-1.28587e-16,-204.752,543.031)"
                              }
                            },
                            [
                              _c("path", {
                                staticClass: "second-line",
                                attrs: { d: "M634.087,300.805L673.361,261.53" }
                              })
                            ]
                          )
                        ])
                      ])
                ]),
                _vm._v(" "),
                _c("h2", [
                  _vm._v(
                    "\n                             " +
                      _vm._s(
                        _vm.success == true
                          ? _vm.$t("LBL_THANK_YOU")
                          : _vm.$t("LBL_PAYMENT_ERROR_MESSAGE")
                      )
                  )
                ]),
                _vm._v(" "),
                _c("h3", [
                  _vm._v(
                    "\n                             " +
                      _vm._s(_vm.$t("LBL_YOUR_ORDER")) +
                      " \n                             "
                  ),
                  _c(
                    "a",
                    {
                      attrs: {
                        href: _vm.auth
                          ? _vm.baseUrl + "/user/orders/" + _vm.order.order_id
                          : "javascript:;"
                      }
                    },
                    [_c("strong", [_vm._v("#" + _vm._s(_vm.order.order_id))])]
                  ),
                  _vm._v(
                    "\n                              " +
                      _vm._s(
                        _vm.success == true
                          ? _vm.$t("LBL_HAS_BEEN_PLACED")
                          : _vm.$t("LBL_ORDER_HAS_BEEN_GENERATED")
                      ) +
                      "\n                         "
                  )
                ]),
                _vm._v(" "),
                _vm.success == true
                  ? _c("h3", [
                      _vm._v(
                        " " + _vm._s(_vm.$t("LBL_ORDER_PAYMENT_DETAIL_UPDATE"))
                      )
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _vm.success == true
                  ? _c("p", [
                      _vm._v(
                        "\n                             " +
                          _vm._s(_vm.$t("LBL_ORDER_CONFIRMATION_SENT")) +
                          " "
                      ),
                      _c("strong", [
                        _vm._v(
                          _vm._s(
                            _vm.billAddress.oaddr_email
                              ? _vm.billAddress.oaddr_email
                              : _vm.order.user_email
                          )
                        )
                      ]),
                      _vm._v(
                        " " +
                          _vm._s(_vm.$t("LBL_ORDER_CONFIRMATION_MESSAGE")) +
                          "\n                         "
                      )
                    ])
                  : _c("p", [
                      _vm._v(
                        "\n                              " +
                          _vm._s(_vm.$t("LBL_ORDER_EMAIL_SENT")) +
                          _vm._s(
                            _vm.billAddress.oaddr_email
                              ? _vm.billAddress.oaddr_email
                              : _vm.order.user_email
                          ) +
                          " " +
                          _vm._s(_vm.$t("LBL_ORDER_EMAIL_SEND_DECRIPTION")) +
                          "\n                         "
                      )
                    ]),
                _vm._v(" "),
                _c("p", { staticClass: "placed" }, [
                  _c("span", [
                    _c(
                      "svg",
                      {
                        staticClass: "svg",
                        attrs: { width: "16px", height: "16px" }
                      },
                      [
                        _c("use", {
                          attrs: {
                            "xlink:href":
                              _vm.baseUrl +
                              "/yokart/media/retina/sprite.svg#TimePlaced",
                            href:
                              _vm.baseUrl +
                              "/yokart/media/retina/sprite.svg#TimePlaced"
                          }
                        })
                      ]
                    ),
                    _vm._v(" "),
                    _c("strong", [
                      _vm._v(_vm._s(_vm.$t("LBL_TIME_PLACED")) + ":")
                    ]),
                    _vm._v(
                      " " +
                        _vm._s(
                          _vm.$dateTimeFormat(_vm.order.order_date_added)
                        ) +
                        "\n                             "
                    )
                  ]),
                  _vm._v(" "),
                  _c("span", [
                    _c(
                      "svg",
                      {
                        staticClass: "svg",
                        attrs: { width: "16px", height: "16px" }
                      },
                      [
                        _c("use", {
                          attrs: {
                            "xlink:href":
                              _vm.baseUrl +
                              "/yokart/media/retina/sprite.svg#print",
                            href:
                              _vm.baseUrl +
                              "/yokart/media/retina/sprite.svg#print"
                          }
                        })
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "a",
                      {
                        attrs: { href: "javascript:;" },
                        on: {
                          click: function($event) {
                            return _vm.printWindow()
                          }
                        }
                      },
                      [_vm._v(_vm._s(_vm.$t("LNK_PRINT")))]
                    )
                  ])
                ])
              ]),
              _vm._v(" "),
              _c(
                "ul",
                { staticClass: "completed-detail" },
                [
                  _vm._l(_vm.order.address, function(address, index) {
                    return _c("li", { key: index }, [
                      _c("h4", [
                        _c(
                          "svg",
                          {
                            staticClass: "svg",
                            attrs: { width: "22px", height: "22px" }
                          },
                          [
                            _c("use", {
                              attrs: {
                                "xlink:href": _vm.addressIconUrl(),
                                href: _vm.addressIconUrl(address.oaddr_type)
                              }
                            })
                          ]
                        ),
                        _vm._v(
                          " " +
                            _vm._s(_vm.addressTypes[address.oaddr_type]) +
                            " "
                        )
                      ]),
                      _vm._v(" "),
                      _c("p", [
                        _c("strong", [_vm._v(_vm._s(address.oaddr_name))]),
                        _vm._v(" "),
                        _c("br"),
                        _vm._v(
                          " " +
                            _vm._s(address.oaddr_address1) +
                            " \n                      \n                             " +
                            _vm._s(
                              address.oaddr_address2 !=
                                "" + ", " + address.oaddr_address2
                            ) +
                            "\n                               "
                        ),
                        _c("br"),
                        _vm._v(
                          _vm._s(address.oaddr_city) +
                            ", " +
                            _vm._s(address.oaddr_state) +
                            " \n                               "
                        ),
                        _c("br"),
                        _vm._v(
                          " " +
                            _vm._s(address.oaddr_country) +
                            "\n                                 "
                        ),
                        _c("br"),
                        _vm._v(
                          _vm._s(
                            "+" +
                              (address.country
                                ? address.country.country_phonecode
                                : "") +
                              " " +
                              address.oaddr_phone
                          ) + "\n                             "
                        )
                      ]),
                      _vm._v(" "),
                      address.oaddr_type == 3
                        ? _c("span", [
                            _c("p", [
                              _c("strong", [
                                _vm._v(
                                  " " +
                                    _vm._s(
                                      JSON.parse(
                                        _vm.orders.order_shipping_methods
                                      )["pick-ups"]["pickup-date"]
                                    )
                                )
                              ])
                            ]),
                            _vm._v(" "),
                            _c("p", [
                              _c("strong", [
                                _vm._v(
                                  " " +
                                    _vm._s(
                                      JSON.parse(
                                        _vm.orders.order_shipping_methods
                                      )["pick-ups"]["pickup-time"]
                                    )
                                )
                              ])
                            ])
                          ])
                        : _vm._e()
                    ])
                  }),
                  _vm._v(" "),
                  _c("li", [
                    _c("h4", [
                      _c(
                        "svg",
                        {
                          staticClass: "svg",
                          attrs: { width: "22px", height: "22px" }
                        },
                        [
                          _c("use", {
                            attrs: {
                              "xlink:href":
                                _vm.baseUrl +
                                "/yokart/media/retina/sprite.svg#payment-detail",
                              href:
                                _vm.baseUrl +
                                "/yokart/media/retina/sprite.svg#payment-detail"
                            }
                          })
                        ]
                      ),
                      _vm._v(" " + _vm._s(_vm.$t("LBL_PAYMENT_DETAILS")) + " ")
                    ]),
                    _vm._v(" "),
                    _c("p", [
                      _c("strong", [
                        _vm._v(_vm._s(_vm.$t("LBL_PAYMENT_METHOD")) + " : ")
                      ]),
                      _vm._v(" " + _vm._s(_vm.order.order_payment_method))
                    ]),
                    _vm._v(" "),
                    _c("p", [
                      _c("strong", [
                        _vm._v(_vm._s(_vm.$t("LBL_PAYMENT_STATUS")) + " : ")
                      ]),
                      _vm._v(
                        _vm._s(_vm.paymentTypes[_vm.order.payment_status]) + " "
                      )
                    ])
                  ])
                ],
                2
              ),
              _vm._v(" "),
              _c("div", { staticClass: "row justify-content-center" }, [
                _c("div", { staticClass: "col-md-12" }, [
                  _c("div", { staticClass: "completed-cart" }, [
                    _c("div", { staticClass: "row justify-content-between" }, [
                      _c("div", { staticClass: "col-md-7" }, [
                        _c("h5", [_vm._v(_vm._s(_vm.$t("LBL_ORDER_LIST")))]),
                        _vm._v(" "),
                        _c(
                          "div",
                          { staticClass: "completed-cart-list scroll-y" },
                          [
                            _c(
                              "ul",
                              {
                                staticClass:
                                  "list-group list-cart list-cart-checkout"
                              },
                              _vm._l(_vm.order.products, function(
                                product,
                                index
                              ) {
                                return _vm.calcaulateQuanity(product) != 0
                                  ? _c(
                                      "li",
                                      {
                                        key: index,
                                        staticClass: "list-group-item"
                                      },
                                      [
                                        _c(
                                          "div",
                                          { staticClass: "product-profile" },
                                          [
                                            _c(
                                              "div",
                                              {
                                                staticClass:
                                                  "product-profile__thumbnail"
                                              },
                                              [
                                                _c(
                                                  "a",
                                                  {
                                                    attrs: {
                                                      href: _vm.$generateUrl(
                                                        product.url_rewrite
                                                      )
                                                    }
                                                  },
                                                  [
                                                    _c("img", {
                                                      staticClass: "img-fluid",
                                                      attrs: {
                                                        "data-yk": "",
                                                        srcset: _vm.$productImage(
                                                          product.op_product_id,
                                                          product.op_pov_code,
                                                          "",
                                                          _vm.$prodImgSize(
                                                            _vm.aspectRatio,
                                                            "medium"
                                                          )
                                                        ),
                                                        "data-ratio":
                                                          _vm.aspectRatio
                                                      }
                                                    })
                                                  ]
                                                ),
                                                _vm._v(" "),
                                                _c(
                                                  "span",
                                                  {
                                                    staticClass: "product-qty"
                                                  },
                                                  [
                                                    _vm._v(
                                                      _vm._s(
                                                        _vm.calcaulateQuanity(
                                                          product
                                                        )
                                                      )
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
                                                  "product-profile__data"
                                              },
                                              [
                                                _c(
                                                  "div",
                                                  { staticClass: "title" },
                                                  [
                                                    _c(
                                                      "a",
                                                      {
                                                        attrs: {
                                                          href: _vm.$generateUrl(
                                                            product.url_rewrite
                                                          )
                                                        }
                                                      },
                                                      [
                                                        _vm._v(
                                                          _vm._s(
                                                            product.op_product_name
                                                          )
                                                        )
                                                      ]
                                                    )
                                                  ]
                                                ),
                                                _vm._v(" "),
                                                JSON.parse(
                                                  product.op_product_variants
                                                ).styles
                                                  ? _c("p", {}, [
                                                      _vm._v(
                                                        _vm._s(
                                                          Object.values(
                                                            JSON.parse(
                                                              product.op_product_variants
                                                            ).styles
                                                          ).join(" | ")
                                                        )
                                                      )
                                                    ])
                                                  : _vm._e()
                                              ]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "div",
                                              { staticClass: "product-price" },
                                              [
                                                _vm._v(
                                                  " " +
                                                    _vm._s(
                                                      _vm.$priceFormat(
                                                        product.op_product_price
                                                      )
                                                    )
                                                )
                                              ]
                                            )
                                          ]
                                        )
                                      ]
                                    )
                                  : _vm._e()
                              }),
                              0
                            )
                          ]
                        )
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "col-md-4" }, [
                        _c("div", { staticClass: "sticky-item" }, [
                          _c("h5", [
                            _vm._v(_vm._s(_vm.$t("LBL_ORDER_SUMMARY")))
                          ]),
                          _vm._v(" "),
                          _c("div", { staticClass: "cart-total mt-5" }, [
                            _c(
                              "ul",
                              {
                                staticClass:
                                  "list-group list-group-flush-x list-group-flush-y"
                              },
                              [
                                _c(
                                  "li",
                                  { staticClass: "list-group-item border-0" },
                                  [
                                    _c("span", { staticClass: "label" }, [
                                      _vm._v(_vm._s(_vm.$t("LBL_SUBTOTAL")))
                                    ]),
                                    _vm._v(" "),
                                    _c("span", { staticClass: "ml-auto" }, [
                                      _vm._v(
                                        _vm._s(_vm.$priceFormat(_vm.subTotal))
                                      )
                                    ])
                                  ]
                                ),
                                _vm._v(" "),
                                _c("li", { staticClass: "list-group-item " }, [
                                  _c("span", { staticClass: "label" }, [
                                    _vm._v(_vm._s(_vm.$t("LBL_TAX")))
                                  ]),
                                  _vm._v(" "),
                                  _c("span", { staticClass: "ml-auto" }, [
                                    _vm._v(
                                      _vm._s(
                                        _vm.tax == 0
                                          ? "Free"
                                          : _vm.$priceFormat(_vm.$tax)
                                      )
                                    )
                                  ])
                                ]),
                                _vm._v(" "),
                                _vm.order.order_shipping_type != 1 &&
                                _vm.order.products.length !=
                                  _vm.order.digital_products
                                  ? _c(
                                      "li",
                                      { staticClass: "list-group-item " },
                                      [
                                        _c("span", { staticClass: "label" }, [
                                          _vm._v(_vm._s(_vm.$t("LBL_SHIPPING")))
                                        ]),
                                        _vm._v(" "),
                                        _c("span", { staticClass: "ml-auto" }, [
                                          _vm._v(
                                            _vm._s(
                                              _vm.shipping == 0
                                                ? "Free"
                                                : _vm.$priceFormat(_vm.shipping)
                                            )
                                          )
                                        ])
                                      ]
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                _vm.discount != 0
                                  ? _c(
                                      "li",
                                      { staticClass: "list-group-item " },
                                      [
                                        _c("span", { staticClass: "label" }, [
                                          _vm._v(_vm._s(_vm.$t("LBL_DISCOUNT")))
                                        ]),
                                        _vm._v(" "),
                                        _c("span", { staticClass: "ml-auto" }, [
                                          _vm._v(
                                            "-" +
                                              _vm._s(
                                                _vm.$priceFormat(_vm.discount)
                                              )
                                          )
                                        ])
                                      ]
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                _vm.reward != 0
                                  ? _c(
                                      "li",
                                      { staticClass: "list-group-item " },
                                      [
                                        _c("span", { staticClass: "label" }, [
                                          _vm._v(
                                            _vm._s(
                                              _vm.$t("LBL_REWARD_POINTS_AMOUNT")
                                            )
                                          )
                                        ]),
                                        _vm._v(" "),
                                        _c("span", { staticClass: "ml-auto" }, [
                                          _vm._v(
                                            "-" +
                                              _vm._s(
                                                _vm.$priceFormat(_vm.reward)
                                              )
                                          )
                                        ])
                                      ]
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                _vm.gift != 0
                                  ? _c(
                                      "li",
                                      { staticClass: "list-group-item " },
                                      [
                                        _c("span", { staticClass: "label" }, [
                                          _vm._v(
                                            _vm._s(
                                              _vm.$t("LBL_GIFT_WRAP_AMOUNT")
                                            )
                                          )
                                        ]),
                                        _vm._v(" "),
                                        _c("span", { staticClass: "ml-auto" }, [
                                          _vm._v(
                                            _vm._s(_vm.$priceFormat(_vm.gift))
                                          )
                                        ])
                                      ]
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                _c(
                                  "li",
                                  {
                                    staticClass: "list-group-item hightlighted"
                                  },
                                  [
                                    _c("span", { staticClass: "label" }, [
                                      _vm._v(_vm._s(_vm.$t("LBL_TOTAL")))
                                    ]),
                                    _vm._v(" "),
                                    _c("span", { staticClass: "ml-auto" }, [
                                      _vm._v(
                                        _vm._s(_vm.$priceFormat(_vm.total))
                                      )
                                    ])
                                  ]
                                )
                              ]
                            )
                          ])
                        ])
                      ])
                    ])
                  ])
                ])
              ])
            ])
          ])
        ])
      ]),
      _vm._v(" "),
      _c("hr", {}),
      _vm._v(" "),
      _c("section", { staticClass: "section" }, [
        _c("div", { staticClass: "container" }, [
          _c("div", { staticClass: "any-question" }, [
            _c("div", { staticClass: "row justify-content-center" }, [
              _c("div", { staticClass: "col-md-7" }, [
                _c("div", { staticClass: "row align-items-center py-4" }, [
                  _c("div", { staticClass: "col-md" }, [
                    _c("div", {}, [
                      _c("p", [
                        _c("strong", [
                          _vm._v(_vm._s(_vm.$t("LBL_ANY_QUESTIONS")))
                        ])
                      ]),
                      _vm._v(" "),
                      _c("p", [
                        _vm._v(
                          _vm._s(_vm.$t("LBL_EMAIL")) +
                            ": " +
                            _vm._s(_vm.$configVal("BUSINESS_EMAIL"))
                        )
                      ]),
                      _vm._v(" "),
                      _c("p", [
                        _vm._v(
                          _vm._s(_vm.$t("LBL_PHONE")) +
                            ": " +
                            _vm._s(_vm.phone) +
                            " "
                        )
                      ])
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "col-md-auto" }, [
                    _vm.success == true
                      ? _c(
                          "a",
                          {
                            staticClass: "btn btn-brand btn-wide mt-3 mt-md-0",
                            attrs: { href: _vm.baseUrl + "/products" }
                          },
                          [
                            _vm._v(
                              "\n                                     " +
                                _vm._s(_vm.$t("LBL_CONTINUE_SHOPPING"))
                            )
                          ]
                        )
                      : _c(
                          "a",
                          {
                            staticClass: "btn btn-brand btn-wide mt-3 mt-md-0",
                            attrs: { href: "javascript:;" },
                            on: {
                              click: function($event) {
                                return _vm.updatePayment(_vm.order)
                              }
                            }
                          },
                          [
                            _vm._v(
                              "\n                                     " +
                                _vm._s(_vm.$t("LBL_ORDER_PAYMENT_UPDATE"))
                            )
                          ]
                        )
                  ])
                ])
              ])
            ])
          ])
        ])
      ])
    ]
  )
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