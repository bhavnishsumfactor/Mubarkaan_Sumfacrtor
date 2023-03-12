<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <div class="with-arrow">
            <button
              class="btn"
              type="button"
              @click="swichOrder(previous)"
              v-if="previous"
            >
              <i class="fas fa-arrow-left"></i>
            </button>
            <h3 class="subheader__title">#{{ orders.order_id }}</h3>
            <button
              class="btn"
              type="button"
              @click="swichOrder(next)"
              v-if="next"
            >
              <i class="fas fa-arrow-right"></i>
            </button>
          </div>
        </div>
        <div class="subheader__toolbar">
          <div class="subheader__wrapper">
            <a
              class="btn btn-white btn-icon"
              v-b-tooltip.hover
              :title="$t('TTL_COMPLETE_ORDER')"
              href="javascript:;"
              v-bind:class="[!$canWrite('ORDERS') ? 'disabledbutton' : '']"
              @click="[$canWrite('ORDERS') ? completeOrder() : '']"
              v-if="orders.order_status != 4"
            >
              <i class="fas fa-box-open"></i>
            </a>

            <router-link
              :to="{ name: 'returnRequest', params: { id: orders.order_id } }"
              v-b-tooltip.hover
              :title="$t('TTL_CANCEL')"
              class="btn btn-subheader btn-icon"
              v-if="
                orders.order_status != 3 &&
                (orders.order_shipping_status == 1 ||
                  orders.order_shipping_status == 2) &&
                orders.products.length != orders.return_products_count
              "
              v-bind:class="[
                !$canWrite('ORDERS') ? 'disabledbutton' : '',
                this.orders.order_shipping_status == 4 ? 'disabledbutton' : '',
              ]"
            >
              <i class="far fa-times-circle"></i>
            </router-link>

            <a
              class="btn btn-subheader btn-icon"
              href="javascript:;"
              @click="printWindow()"
              v-b-tooltip.hover
              :title="$t('TTL_PRINT')"
              v-bind:class="[!$canWrite('ORDERS') ? 'disabledbutton' : '']"
            >
              <i class="fas fa-print"></i>
            </a>
            <a
              class="btn btn-subheader btn-icon"
              href="javascript:;"
              @click="downloadPackingSlip(orders.order_id)"
              v-if="
                totalProduct != orders.digital_products &&
                orders.order_shipping_status != 4 &&
                orders.order_status != 4
              "
              v-b-tooltip.hover
              :title="$t('TTL_PACKING_SLIP')"
              v-bind:class="[!$canWrite('ORDERS') ? 'disabledbutton' : '']"
            >
              <i class="far fa-file-alt"></i>
            </a>
            <a
              class="btn btn-subheader btn-icon"
              :href="baseUrl + '/order/download-pdf/' + orders.order_id"
              v-if="orders.invoice_count == 1"
              v-b-tooltip.hover
              :title="$t('TTL_ORDER_INVOICE')"
              v-bind:class="[!$canWrite('ORDERS') ? 'disabledbutton' : '']"
            >
              <i class="fas fa-file-invoice"></i>
            </a>

            <a
              class="btn btn-subheader btn-icon"
              href="javascript:;"
              v-if="
                orders.order_payment_method == 'cod' &&
                orders.payment_status != 2 &&
                orders.order_shipping_status == 3
              "
              @click="sendPaymentLink()"
              v-b-tooltip.hover
              :title="$t('TTL_PAYMENT_LINK')"
              v-bind:class="[!$canWrite('ORDERS') ? 'disabledbutton' : '']"
            >
              {{ $t("BTN_SHARE") }}
              <i class="fas fa-share-alt"></i>
            </a>

            <router-link :to="{ name: 'orders' }" class="btn btn-subheader">{{
              $t("BTN_BACK")
            }}</router-link>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="portlet portlet-no-min-height">
            <div
              class="
                portlet__head
                ribbon ribbon--dark ribbon--shadow ribbon--left ribbon--round
              "
            >
              <div class="ribbon__target" style="top: 15px; right: -2px">
                <span v-if="orders.order_payment_method != 'cod'">
                  <i class="far fa-credit-card"></i>
                  &nbsp &nbsp{{ orders.order_payment_status }}
                  {{ $t("LBL_VIA") }} {{ orders.order_payment_method }}
                </span>
                <span v-else>
                  <i class="far fa-cash"></i>
                  {{
                    orders.payment_status == 2
                      ? "Cash"
                      : $t("LBL_CASH_ON_DELIVERY")
                  }}
                  <span v-if="orders.payment_status == 2"
                    >({{ orders.order_payment_status }})</span
                  >
                </span>
              </div>

              <div class="portlet__head-toolbar">
                <p>
                  <i class="far fa-calendar-alt"></i>
                  &nbsp {{ orders.order_date_added | orderDateTime }}
                </p>
              </div>
            </div>

            <div class="portlet__body">
              <ul
                class="
                  list-group
                  list-cart
                  list-cart-order
                  list-order
                  list-order-return
                  mb-7
                "
              >
                <li
                  class="list-group-item"
                  v-for="(product, productKey) in orders.products"
                  :key="productKey"
                >
                  <div
                    class="list-inner"
                    v-bind:class="[
                      product.cancel_request &&
                      product.cancel_request.orrequest_qty == product.op_qty &&
                      product.cancel_request.orrequest_status == 1
                        ? 'disabledbutton'
                        : '',
                    ]"
                  >
                    <div class="product-profile">
                      <div class="product-profile__thumbnail">
                        <img
                          :src="
                            $productImage(
                              product.op_product_id,
                              product.op_pov_code,
                              product.images
                                ? $timestamp(product.images.afile_updated_at)
                                : '',
                              '39-52'
                            )
                          "
                          alt="..."
                          class="img-fluid"
                        />
                      </div>
                      <div class="product-profile__data">
                        <div class="title">{{ product.op_product_name }}</div>

                        <p class="options" v-if="product.op_product_variants">
                          <span
                            v-if="
                              JSON.parse(product.op_product_variants).styles
                            "
                            >{{
                              Object.values(
                                JSON.parse(product.op_product_variants).styles
                              ).join(" | ")
                            }}</span
                          >
                          <a
                            class="YK-GiftItem gift-item active"
                            href="javascript:;"
                            v-if="JSON.parse(product.op_product_variants).gift"
                            @click="displayGiftMessage()"
                          >
                            Gift wrap
                            <svg class="svg">
                              <use
                                :xlink:href="
                                  adminBaseUrl +
                                  '/images/retina/sprite.svg#gift-icon'
                                "
                                :href="
                                  adminBaseUrl +
                                  '/images/retina/sprite.svg#gift-icon'
                                "
                              />
                            </svg>
                          </a>
                        </p>
                      </div>
                    </div>
                    <div class="product-quantity">
                      <div class="by-quantity">
                        <span
                          >{{ currencySymbol
                          }}{{ product.op_product_price | numberFormat }}</span
                        >
                        <span>×</span>
                        <span>{{ product.op_qty }}</span>
                      </div>
                    </div>
                    <div class="product-price font-weight-bold">
                      {{ currencySymbol
                      }}{{
                        (product.op_product_price * product.op_qty)
                          | numberFormat
                      }}
                    </div>
                  </div>
                </li>
              </ul>

              <div class="row">
                <div class="col-md-6">
                  <div
                    class="bg-gray border rounded p-5"
                    v-if="totalRefundedAmount != 0"
                  >
                    <ul
                      class="
                        list-group
                        list-group-sm
                        list-group-flush-y
                        list-group-flush-x
                      "
                    >
                      <li class="list-group-item d-flex">
                        <span>{{ $t("LBL_ORDER_NET_TOTAL") }}</span>
                        <span class="ml-auto"
                          >{{ currencySymbol
                          }}{{ orders.order_net_amount | numberFormat }}</span
                        >
                      </li>

                      <li class="list-group-item d-flex">
                        <span>{{ $t("LBL_TOTAL_REFUND_AMOUNT") }}</span>
                        <span class="ml-auto"
                          >{{ currencySymbol
                          }}{{ totalRefundedAmount | numberFormat }}</span
                        >
                      </li>
                      <li
                        class="
                          list-group-item
                          d-flex
                          font-size-lg font-weight-bold
                        "
                      >
                        <span>{{ $t("LBL_TOTAL") }}</span>
                        <span class="ml-auto"
                          >{{ currencySymbol
                          }}{{
                            Math.abs(
                              orders.order_net_amount - totalRefundedAmount
                            ) | numberFormat
                          }}</span
                        >
                      </li>
                    </ul>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="bg-gray p-4 rounded h-100">
                    <ul
                      class="
                        list-group
                        list-group-sm
                        list-group-flush-y
                        list-group-flush-x
                      "
                    >
                      <li class="list-group-item d-flex">
                        <span>{{ $t("LBL_SUBTOTAL") }}</span>
                        <span class="ml-auto"
                          >{{ currencySymbol
                          }}{{ subTotal | numberFormat }}</span
                        >
                      </li>
                      <li class="list-group-item d-flex">
                        <span>{{ $t("LBL_TAX") }}</span>
                        <span class="ml-auto"
                          >{{ currencySymbol }}{{ tax | numberFormat }}</span
                        >
                      </li>

                      <li
                        class="list-group-item d-flex"
                        v-if="
                          orders.order_shipping_type != 1 &&
                          orders.products &&
                          orders.digital_products != orders.products.length
                        "
                      >
                        <span>{{ $t("LBL_SHIPPING") }}</span>
                        <span class="ml-auto">
                          <span v-if="shipping == 0">{{ $t("LBL_FREE") }}</span>
                          <span v-else
                            >{{ currencySymbol
                            }}{{ shipping | numberFormat }}</span
                          >
                        </span>
                      </li>
                      <li
                        class="list-group-item d-flex"
                        v-if="Object.keys(giftMessage).length > 0"
                      >
                        <span>{{ $t("LBL_GIFT_WRAP_AMOUNT") }}</span>
                        <span class="ml-auto">
                          <span v-if="giftprice == 0">{{
                            $t("LBL_FREE")
                          }}</span>
                          <span v-else
                            >{{ currencySymbol }}
                            {{ giftprice | numberFormat }}</span
                          >
                        </span>
                      </li>

                      <li class="list-group-item d-flex" v-if="discount != 0">
                        <span
                          >{{ $t("LBL_DISCOUNT") }}
                          {{ orders.order_discount_coupon_code }}</span
                        >
                        <span class="ml-auto"
                          >- {{ currencySymbol
                          }}{{ discount | numberFormat }}</span
                        >
                      </li>
                      <li
                        class="list-group-item d-flex"
                        v-if="rewardAmount != 0"
                      >
                        <span>{{ $t("LBL_REWARD_POINTS_AMOUNT") }}</span>
                        <span class="ml-auto"
                          >- {{ currencySymbol
                          }}{{ rewardAmount | numberFormat }}</span
                        >
                      </li>
                      <li
                        class="
                          list-group-item
                          d-flex
                          font-size-lg font-weight-bold
                        "
                      >
                        <span>{{ $t("LBL_TOTAL") }}</span>
                        <span class="ml-auto"
                          >{{ currencySymbol }}
                          {{ orders.order_net_amount | numberFormat }}</span
                        >
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <hr class="m-0" />
            <div
              class="portlet__body"
              v-bind:class="[!$canWrite('ORDERS') ? 'disabledbutton' : '']"
            >
              <h6>{{ $t("LBL_ORDERSTATUS") }}</h6>

              <ul
                class="order-status-click"
                v-bind:class="[
                  returnStatus == 1 || !$canWrite('ORDERS')
                    ? 'disabledbutton'
                    : '',
                  orders.order_shipping_status == 4 || orders.order_status == 4
                    ? 'disabledbutton'
                    : '',
                ]"
              >
                <li
                  v-for="(statusVal, statusId) in shippingStatus"
                  :key="statusId"
                  @click="updateOrderStatus(statusId, statusVal)"
                  v-bind:class="[
                    orders.order_shipping_status == statusId ? 'active' : '',
                  ]"
                >
                  <i class="icn">
                    <svg class="svg">
                      <use
                        :xlink:href="
                          baseUrl +
                          '/admin/images/retina/sprite.svg#' +
                          statusVal
                        "
                        :href="
                          baseUrl +
                          '/admin/images/retina/sprite.svg#' +
                          statusVal
                        "
                      />
                    </svg>
                  </i>
                  <span v-if="orders.order_shipping_type == 1">{{
                    statusVal | removeHyphen | camelCase
                  }}</span>

                  <div class="order__type">
                    <span
                      v-b-tooltip.hover
                      :title="
                        productType == 2 || productType == 3
                          ? $t('LBL_PHYSICAL_ORDER_STATUS')
                          : ''
                      "
                      v-if="
                        orders.order_shipping_type == 0 &&
                        (productType == 3 || productType == 1)
                      "
                      >{{ statusVal | removeHyphen | camelCase }}</span
                    >
                    <span
                      v-b-tooltip.hover
                      :title="
                        productType == 1 || productType == 3
                          ? $t('LBL_DIGITAL_ORDER_STATUS')
                          : ''
                      "
                      v-if="
                        orderDigitalStatus[statusVal] &&
                        orders.order_shipping_type == 0 &&
                        (productType == 3 || productType == 2)
                      "
                    >
                      <span v-if="productType == 1 || productType == 3">/</span>
                      {{
                        orderDigitalStatus[statusVal] | removeHyphen | camelCase
                      }}
                    </span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div
            class="portlet portlet-no-min-height"
            v-if="
              balance > 0 &&
              orders.payment_status != 2 &&
              orders.order_status != 4
            "
          >
            <div
              class="portlet__body"
              v-bind:class="[!$canWrite('ORDERS') ? 'disabledbutton' : '']"
            >
              <div class="alert alert-solid-dark alert-bold" role="alert">
                <div class="alert-icon">
                  <i class="flaticon-warning-sign"></i>
                </div>
                <div class="alert-text d-flex justify-content-between">
                  {{ $t("LBL_BALANCE") }} ({{ $t("LBL_CUSTOMER_OWES_YOU") }})
                  <span
                    >{{ orders.currency_symbol
                    }}{{ balance | numberFormat }}</span
                  >
                </div>
                <button
                  type="button"
                  class="btn btn-brand ml-5"
                  @click="updatePayment()"
                >
                  {{ $t("BTN_PAY_NOW") }}
                </button>
              </div>
            </div>
          </div>

          <div class="portlet portlet-no-min-height">
            <div class="portlet__head">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">{{ $t("LBL_TIMELINE") }}</h3>
              </div>
              <div
                class="portlet__head-toolbar"
                v-if="messages.length > 0 || displayMessage != false"
              >
                <!--<div class="portlet__head-actions">
                  <label class="checkbox">
                    <input
                      type="checkbox"
                      v-model="displayMessage"
                      @click="displayComment()"
                    />
                    {{ $t("LBL_SHOW_COMMENTS_ONLY") }}
                    <span></span>
                  </label>
                </div> -->
              </div>
            </div>
            <div
              class="portlet__body"
              v-bind:class="[!$canWrite('ORDERS') ? 'disabledbutton' : '']"
            >
              <div class="timeline-comments">
                <div class="timeline-avatar">
                  <span class="timeline-avatar-initials">{{ userName }}</span>
                </div>
                <form
                  class="form timeline-comments-box"
                  v-on:submit.prevent="validateRecord"
                >
                  <div class="input-group">
                    <input
                      class="form-control"
                      type="text"
                      :placeholder="$t('PLH_LEAVE_A_COMMENT')"
                      v-model="comment"
                      name="comment"
                      v-validate="'required'"
                      data-vv-validate-on="none"
                    />
                    <div class="input-group-append">
                      <button
                        class="btn btn-brand btn-wide gb-btn-primary"
                        :disabled="comment == '' || clicked == 1"
                        v-bind:class="clicked == 1 && 'gb-is-loading'"
                        type="submit"
                      >
                        {{ $t("BTN_POST") }}
                      </button>
                    </div>
                  </div>
                  <span
                    v-if="errors.first('comment')"
                    class="error text-danger"
                    >{{ errors.first("comment") }}</span
                  >
                </form>
              </div>
              <ul class="timeline my-5" id="timeline">
                <li
                  class="event comments default_status"
                  v-for="(shipping, sIndex) in shippingTypes"
                  :key="orders.order_id + '-' + sIndex"
                  v-if="
                    validateArray(sIndex, orders.order_shipping_status) == true
                  "
                >
                  <span class="timeline__date"></span>
                  <div class="data">
                    <span v-if="orders.order_shipping_type == 1">{{
                      pickupStatus[sIndex] | removeHyphen | camelCase
                    }}</span>

                    <span
                      v-else-if="
                        orders.digital_products != 0 &&
                        orders.digital_products == orders.products.length
                      "
                      >{{ $cleanShipStatus(digitalStatus[shipping]) }}</span
                    >

                    <span v-else>
                      {{ shipping | removeHyphen | camelCase }}
                      {{
                        orders.digital_products != 0 &&
                        orders.digital_products != orders.products.length
                          ? "/" + $cleanShipStatus(digitalStatus[shipping])
                          : ""
                      }}
                    </span>
                  </div>
                </li>
                <li
                  class="event"
                  v-bind:class="[
                    message.omsg_from_type == 1 ? 'admin' : '',
                    message.omsg_type == 4 ? 'status' : 'comments',
                    msgKey == 0 ? 'last-status' : '',
                  ]"
                  v-for="(message, msgKey) in messages"
                  :key="msgKey"
                  v-if="messages.length != 0"
                >
                  <span class="timeline__date">{{
                    message.omsg_created_at | formatDateTime
                  }}</span>

                  <div
                    v-if="
                      trackingEnabled == true &&
                      message.omsg_comment == 'shipped' &&
                      trackingInfo
                    "
                    class="data"
                  >
                    <span>
                      <a
                        href="javascrit:;"
                        class="dropdown-toggle"
                        @click.prevent="showTrackingInformation()"
                      >
                        <svg class="svg" width="20px" height="20px">
                          <use
                            :xlink:href="
                              activeThemeUrl +
                              '/media/retina/sprite.svg#' +
                              message.omsg_comment
                            "
                            :href="
                              activeThemeUrl +
                              '/media/retina/sprite.svg#' +
                              message.omsg_comment
                            "
                          />
                        </svg>
                        {{ $t("LBL_SHIPPED_VIA") }}
                        {{ trackingInfo.oainfo_courier_name.toUpperCase() }}
                        {{ " " + $t("LBL_TRACKING") + "# " }}
                        {{ trackingInfo.oainfo_tracking_id }}
                      </a>
                    </span>
                  </div>
                  <div v-else class="data">
                    <span v-if="message.comments">
                      <a
                        class="dropdown-toggle collapsed"
                        data-toggle="collapse"
                        :href="'#event' + message.omsg_id"
                        aria-expanded="false"
                        v-if="message.omsg_comment == 'shipped'"
                        @click="
                          copyTrackingInformation(
                            trackingInfo.oainfo_tracking_id
                          )
                        "
                      >
                        <svg class="svg" width="20px" height="20px">
                          <use
                            :xlink:href="
                              activeThemeUrl +
                              '/media/retina/sprite.svg#' +
                              message.omsg_comment
                            "
                            :href="
                              activeThemeUrl +
                              '/media/retina/sprite.svg#' +
                              message.omsg_comment
                            "
                          />
                        </svg>
                        {{ $t("LBL_SHIPPED_VIA") }}
                        {{ trackingInfo.oainfo_courier_name.toUpperCase() }}
                        {{ " " + $t("LBL_TRACKING") + "# " }}
                        {{ trackingInfo.oainfo_tracking_id }}
                      </a>
                      <a
                        class="dropdown-toggle collapsed"
                        data-toggle="collapse"
                        :href="'#event' + message.omsg_id"
                        aria-expanded="false"
                        v-else
                      >
                        <svg class="svg" width="20px" height="20px">
                          <use
                            :xlink:href="
                              activeThemeUrl +
                              '/media/retina/sprite.svg#' +
                              message.omsg_comment
                            "
                            :href="
                              activeThemeUrl +
                              '/media/retina/sprite.svg#' +
                              message.omsg_comment
                            "
                          />
                        </svg>
                        {{ message.omsg_comment | removeHyphen | camelCase }}
                      </a>
                      <div
                        class="collapse"
                        :id="'event' + message.omsg_id"
                        data-parent="#timeline"
                        style
                      >
                        <div class="mt-3">
                          <div
                            class="other-detail"
                            v-html="message.comments.omsg_comment"
                          ></div>
                        </div>
                      </div>
                    </span>
                    <span v-else v-html="message.omsg_comment"></span>
                  </div>
                </li>
              </ul>
              <pagination
                :pagination="pagination"
                @currentPage="currentPage"
              ></pagination>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div
            class="portlet portlet--tabs portlet-no-min-height"
            v-if="
              requestType.length > 0 &&
              (totalRefundAmount != 0 ||
                orders.order_payment_method == 'rewards' ||
                orders.return_products_count != 0)
            "
          >
            <div class="portlet__head">
              <div class="portlet__head-toolbar">
                <ul
                  role="tablist"
                  class="
                    nav
                    nav-tabs
                    nav-tabs-line
                    nav-tabs-line-danger
                    nav-tabs-line-2x
                    nav-tabs-line-right
                    nav-tabs-bold
                  "
                >
                  <li class="nav-item" v-if="inArray(1, requestType) === true">
                    <a
                      data-toggle="tab"
                      href="javascript:;"
                      customsearch
                      role="tab"
                      aria-selected="false"
                      class="nav-link"
                      v-bind:class="[activeReturnStatus == 1 ? 'active' : '']"
                      @click="calulateRefund(orders.products, 1)"
                      >{{ $t("LNK_RETURN_REQUEST") }}</a
                    >
                  </li>
                  <li class="nav-item" v-if="inArray(2, requestType) === true">
                    <a
                      data-toggle="tab"
                      href="javascript:;"
                      customsearch
                      role="tab"
                      aria-selected="false"
                      class="nav-link"
                      v-bind:class="[activeReturnStatus == 2 ? 'active' : '']"
                      @click="calulateRefund(orders.products, 2)"
                      >{{ $t("LNK_CANCELLATION_REQUEST") }}</a
                    >
                  </li>
                </ul>
              </div>
            </div>
            <div class="portlet__body">
              <ul
                class="
                  list-group
                  list-group-flush-x
                  list-group-flush-y
                  list-cart
                  list-cart-order
                  list-order
                  list-order-return
                  mb-2
                "
              >
                <li
                  class="list-group-item border-0"
                  v-for="(product, productKey) in orders.products"
                  :key="productKey"
                  v-if="
                    product.cancel_request != '' &&
                    product.cancel_request != null &&
                    product.cancel_request.orrequest_type == activeReturnStatus
                  "
                >
                  <div class="list-inner">
                    <div class="product-profile">
                      <div class="product-profile__thumbnail">
                        <router-link
                          :to="{
                            name: 'orderReturnDetail',
                            params: {
                              id: orders.order_id,
                              requestId: product.cancel_request.orrequest_id,
                            },
                          }"
                        >
                          <img
                            :src="
                              $productImage(
                                product.op_product_id,
                                product.op_pov_code,
                                product.images
                                  ? $timestamp(product.images.afile_updated_at)
                                  : '',
                                '39-52'
                              )
                            "
                            alt="..."
                            class="img-fluid"
                          />
                        </router-link>
                      </div>
                      <div class="product-profile__data">
                        <div class="title">
                          <router-link
                            :to="{
                              name: 'orderReturnDetail',
                              params: {
                                id: orders.order_id,
                                requestId: product.cancel_request.orrequest_id,
                              },
                            }"
                            class="text-body"
                            >{{ product.op_product_name }}</router-link
                          >
                        </div>
                        <p class="options" v-if="product.op_product_variants">
                          <span
                            v-if="
                              JSON.parse(product.op_product_variants).styles
                            "
                            >{{
                              Object.values(
                                JSON.parse(product.op_product_variants).styles
                              ).join(" | ")
                            }}</span
                          >
                        </p>
                        <div>
                          <div
                            class="dropdown mb-2"
                            v-bind:class="[
                              product.cancel_request.orrequest_status == 2 ||
                              product.cancel_request.orrequest_status == 3
                                ? 'disabledbutton'
                                : '',
                            ]"
                          >
                            <button
                              class="btn btn-link btn-sm dropdown-toggle"
                              type="button"
                              id="dropdownMenuButton"
                              data-toggle="dropdown"
                              aria-haspopup="true"
                              aria-expanded="false"
                            >
                              {{
                                orderReturnStatus[
                                  product.cancel_request.orrequest_status
                                ]
                              }}
                            </button>
                            <div
                              class="dropdown-menu"
                              aria-labelledby="dropdownMenuButton"
                            >
                              <a
                                class="dropdown-item"
                                href="javascript:;"
                                v-for="(
                                  returnStatus, returnkey
                                ) in orderReturnStatus"
                                :key="returnkey"
                                @click="
                                  updateReturnStatus(
                                    product,
                                    returnStatus,
                                    returnkey
                                  )
                                "
                                v-if="
                                  product.cancel_request.orrequest_status !=
                                    returnkey &&
                                  (returnkey != 1 ||
                                    product.cancel_request.orrequest_type == 1)
                                "
                                >{{ returnStatus }}</a
                              >
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="product-price font-weight-bold">
                      {{ currencySymbol
                      }}{{
                        (product.op_product_price *
                          product.cancel_request.orrequest_qty)
                          | numberFormat
                      }}
                    </div>
                  </div>
                </li>
              </ul>

              <div class="row">
                <div class="col">
                  <ul
                    class="
                      list-group
                      list-group-sm
                      list-group-flush-y
                      list-group-flush-x
                    "
                  >
                    <li class="list-group-item d-flex border-0 p-0">
                      <span>{{ $t("LBL_SUBTOTAL") }}</span>
                      <span class="ml-auto"
                        >{{ currencySymbol
                        }}{{ returnSubtotal | numberFormat }}</span
                      >
                    </li>
                    <li class="list-group-item d-flex border-0 py-2">
                      <span>{{ $t("LBL_TAX") }}</span>
                      <span class="ml-auto"
                        >{{ currencySymbol
                        }}{{ returnTax | numberFormat }}</span
                      >
                    </li>
                    <li
                      class="list-group-item d-flex border-0 py-2"
                      v-if="returnShipping != 0"
                    >
                      <span>{{ $t("LBL_SHIPPING") }}</span>
                      <span class="ml-auto"
                        >{{ currencySymbol
                        }}{{ returnShipping | numberFormat }}</span
                      >
                    </li>
                    <li
                      class="list-group-item d-flex border-0 py-2"
                      v-if="returnDiscount != 0"
                    >
                      <span>{{ $t("LBL_DISCOUNT_PRICE") }}</span>
                      <span class="ml-auto"
                        >- {{ currencySymbol
                        }}{{ returnDiscount | numberFormat }}</span
                      >
                    </li>
                    <li
                      class="list-group-item d-flex border-0 py-2"
                      v-if="returnGiftwrap != 0 && returnGiftwrap > 0"
                    >
                      <span>{{ $t("LBL_GIFT_WRAP_AMOUNT") }}</span>
                      <span class="ml-auto"
                        >{{ currencySymbol
                        }}{{ Math.abs(returnGiftwrap) | numberFormat }}</span
                      >
                    </li>
                    <li
                      class="list-group-item d-flex border-0 py-2"
                      v-if="returnReward != 0"
                    >
                      <span>{{ $t("LBL_REWARD_POINTS_AMOUNT") }}</span>
                      <span class="ml-auto"
                        >- {{ currencySymbol
                        }}{{ returnReward | numberFormat }}</span
                      >
                    </li>

                    <li class="list-group-item d-flex font-weight-bold py-2">
                      <span>{{ $t("LBL_TOTAL") }}</span>
                      <span class="ml-auto"
                        >{{ currencySymbol
                        }}{{ returnTotal | numberFormat }}</span
                      >
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="portlet portlet-no-min-height">
            <div class="portlet__head">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i class="fas fa-envelope"></i>
                  {{ $t("LBL_CONTACT_INFORMATION") }}
                </h3>
              </div>
            </div>
            <div class="portlet__body">
              <p class="list-text">
                <span class="lable">{{ $t("LBL_EMAIL") }}:</span>
                {{ orders.user_email }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_PHONE") }}:</span>
                {{
                  orders.user_phone != null && orders.user_phone != ""
                    ? orders.user_phone_country_id != null
                      ? "+" +
                        orders.user_phone_country_id +
                        " " +
                        orders.user_phone
                      : ""
                    : ""
                }}
              </p>
            </div>
          </div>
          <!--<div class="portlet portlet-no-min-height" v-if="orders.addition_info">
            <div class="portlet__head">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i class="fas fa-envelope"></i>
                  {{$t('LBL_SHIPPING_PARTNER_INFO')}}
                </h3>
              </div>
            </div>
            <div class="portlet__body">
              <div class="list-text">
                <span class="lable">{{$t('LBL_COMPANY')}}:</span>
                <span v-if="editCourierInfo == 1">
                  <input
                    class="form-control"
                    type="text"
                    v-model="orders.addition_info.oainfo_courier_name"
                    @blur="updateCourierInfo()"
                  />
                </span>
                <div class="edit-hover">
                  <span
                    v-if="editCourierInfo != 1"
                    v-html="nl2br(orders.addition_info.oainfo_courier_name)"
                  ></span>
                  <i
                    class="fas fa-pencil-alt"
                    v-if="editCourierInfo == 0"
                    @click="editCourierInfo = 1"
                  ></i>
                </div>
              </div>
              <div class="list-text">
                <span class="lable">Tracking #</span>
                <span v-if="editCourierInfo == 2">
                  <input
                    class="form-control"
                    type="text"
                    v-model="orders.addition_info.oainfo_tracking_id"
                    @blur="updateCourierInfo()"
                  />
                </span>
                <div class="edit-hover">
                  <span
                    v-if="editCourierInfo != 2"
                    v-html="nl2br(orders.addition_info.oainfo_tracking_id)"
                  ></span>
                  <i
                    class="fas fa-pencil-alt"
                    v-if="editCourierInfo == 0"
                    @click="editCourierInfo = 2"
                  ></i>
                </div>
              </div>
            </div>
          </div>-->
          <div
            class="portlet portlet-no-min-height"
            v-if="
              orders.transaction &&
              orders.transaction.txn_gateway_transaction_id
            "
          >
            <div class="portlet__head">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i class="fas fa-envelope"></i>
                  {{ $t("LBL_PAYMENT_DETAILS") }}
                </h3>
              </div>
            </div>
            <div class="portlet__body">
              <div class="payment-mode">
                <div
                  class="cc-payment"
                  v-if="JSON.parse(orders.transaction.txn_gateway_response)"
                >
                  <i class="icn">
                    <card-icons
                      :cardType="
                        JSON.parse(orders.transaction.txn_gateway_response).data
                          .payment_method_details &&
                        JSON.parse(orders.transaction.txn_gateway_response).data
                          .payment_method_details.card.network
                          ? $cleanString(
                              JSON.parse(
                                orders.transaction.txn_gateway_response
                              ).data.payment_method_details.card.network
                            )
                          : ''
                      "
                    ></card-icons>
                  </i>
                  <span
                    class="cc-num"
                    v-if="
                      JSON.parse(orders.transaction.txn_gateway_response).data
                        .payment_method_details
                    "
                    >**** **** ****
                    {{
                      cardNumber(
                        JSON.parse(orders.transaction.txn_gateway_response).data
                          .payment_method_details.card
                      )
                    }}</span
                  >
                  <span v-else-if="orders.paymentslug == 'Paypal'">
                    <img
                      :src="baseUrl + '/admin/images/retina/paypal-icon.png'"
                      width="20px"
                    />
                    {{ orders.paymentslug }}
                  </span>
                  <span v-else>Cash</span>
                </div>
                <div class="txt-id">
                  <h6>
                    <strong>{{ $t("LBL_TRANSACTION_NUMBER") }}</strong>
                  </h6>
                  <span>{{
                    orders.transaction.txn_gateway_transaction_id
                  }}</span>
                </div>
              </div>
            </div>
          </div>
          <div
            class="portlet portlet-no-min-height"
            v-for="addr in orders.address"
          >
            <div class="portlet__head">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i
                    class="fas"
                    v-bind:class="[
                      addr.oaddr_type == 1 ? 'fa-file-invoice' : '',
                      addr.oaddr_type == 2 ? 'fa-shipping-fast' : '',
                      addr.oaddr_type == 3 ? 'fa-people-carry' : '',
                    ]"
                  ></i>
                  {{ addressTypes[addr.oaddr_type] }}
                </h3>
              </div>
            </div>
            <div class="portlet__body">
              <p class="list-text">
                <span class="lable">{{ $t("LBL_NAME") }}:</span>
                {{ addr.oaddr_name }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_APARTMENT_NO") }}:</span>
                {{ addr.oaddr_address1 }}
              </p>
              <p class="list-text" v-if="addr.oaddr_address2">
                <span class="lable">{{ $t("LBL_ADDRESS") }}:</span>
                {{ addr.oaddr_address2 }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_CITY_STATE") }}:</span>
                {{ addr.oaddr_city + ", " + addr.oaddr_state }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_POSTAL_CODE") }}:</span>
                {{ addr.oaddr_postal_code }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_SELECT_COUNTRY") }}:</span>
                {{ addr.oaddr_country }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_PHONE") }}:</span>
                {{
                  addr.country ? "+" + addr.country.country_phonecode + " " : ""
                }}
                {{ addr.oaddr_phone }}
              </p>
              <div v-if="addr.oaddr_type == 3">
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_PICKUP_DATE") }}:</span>
                  {{
                    JSON.parse(orders.order_shipping_methods)["pick_ups"][
                      "pickup_date"
                    ]
                  }}
                </p>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_PICKUP_TIME") }}:</span>
                  {{
                    JSON.parse(orders.order_shipping_methods)["pick_ups"][
                      "pickup_time"
                    ]
                  }}
                </p>
              </div>
            </div>
          </div>

          <div
            class="portlet portlet-no-min-height"
            v-if="orders.bank_information"
          >
            <div class="portlet__head">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i class="fas fa-envelope"></i>
                  {{ $t("LBL_BANK_INFORMATION") }}
                </h3>
              </div>
            </div>

            <div class="portlet__body">
              <p class="list-text">
                <span class="lable">{{ $t("LBL_BANK_NAME") }}:</span>
                {{ orders.bank_information.orbinfo_name }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_BRANCH_NAME") }}:</span>
                {{ orders.bank_information.orbinfo_branch }}
              </p>

              <p class="list-text">
                <span class="lable">{{ $t("LBL_ACCOUNT_NAME") }}:</span>
                {{ orders.bank_information.orbinfo_account_name }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_ACCOUNT_NUMBER") }}:</span>
                {{ orders.bank_information.orbinfo_account_number }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_BRANCH_CODE") }}:</span>
                {{ orders.bank_information.orbinfo_branch_code }}
              </p>
              <p class="list-text" v-if="orders.bank_information.orbinfo_notes">
                <span class="lable">{{ $t("LBL_NOTES") }}:</span>
                {{ orders.bank_information.orbinfo_notes }}
              </p>
            </div>
          </div>
          <div class="portlet portlet-no-min-height" v-if="note">
            <div
              class="portlet__head"
              v-bind:class="[!$canWrite('ORDERS') ? 'disabledbutton' : '']"
            >
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i class="far fa-clipboard"></i>
                  {{ $t("LBL_NOTES") }}
                </h3>
              </div>
            </div>
            <div
              class="portlet__body"
              v-bind:class="[!$canWrite('ORDERS') ? 'disabledbutton' : '']"
            >
              <p>{{ note }}</p>
            </div>
          </div>

          <div class="portlet portlet-no-min-height">
            <div
              class="portlet__head"
              v-bind:class="[!$canWrite('ORDERS') ? 'disabledbutton' : '']"
            >
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i class="fas fa-user-tag"></i>
                  {{ $t("LBL_TAGS") }}
                </h3>
              </div>
            </div>
            <div
              class="portlet__body"
              v-bind:class="[!$canWrite('ORDERS') ? 'disabledbutton' : '']"
            >
              <div class>
                <vue-tags-input
                  class="vue-tags-style"
                  v-model="tag"
                  :tags="tags"
                  @tags-changed="validateTags"
                  :placeholder="$t('PLH_ADD_ORDER_TAGS')"
                  name="tag"
                  v-validate="'required'"
                  data-vv-validate-on="none"
                  :data-vv-as="$t('tag')"
                ></vue-tags-input>
                <span v-if="errors.first('tag')" class="error text-danger">{{
                  errors.first("tag")
                }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <order-shipping-status
      :statusData="statusData"
      :url="url"
      :shippingCourier="couriers"
      @closePopup="closePopup"
      @updateStatus="updateStatus"
    ></order-shipping-status>
    <cod-payment
      :statusData="statusData"
      :url="url"
      :adminsData="adminsData"
      :readonly="readonly"
      @closePopup="closePopup"
      @updateStatus="updateStatus"
    ></cod-payment>
    <share-payment-link
      :statusData="statusData"
      :shareSuccessMessage="shareSuccessMessage"
    ></share-payment-link>

    <tracking-information
      :informations="trackingCheckpoints"
      :orderId="orders.order_id"
    ></tracking-information>

    <b-modal
      id="giftModal"
      centered
      title="BootstrapVue"
      header-class="border-0"
      hide-header
      hide-footer
    >
      <button type="button" class="close" @click="$bvModal.hide('giftModal')">
        <span aria-hidden="true">×</span>
      </button>

      <div class="gift-details" v-if="Object.keys(giftMessage).length > 0">
        <div class="gift-details__img">
          <img data-yk :src="baseUrl + '/admin/images/retina/gift.svg'" alt />
        </div>
        <div class="row mb-4">
          <div class="col-md-6">
            <h6>
              <strong>{{ $t("LBL_FROM") }}:</strong>
            </h6>
            <p>{{ giftMessage.message.from }}</p>
          </div>
          <div class="col-md-6">
            <h6>
              <strong>{{ $t("LBL_TO") }}:</strong>
            </h6>
            <p>{{ giftMessage.message.to }}</p>
          </div>
        </div>
        <h6>
          <strong>{{ $t("LBL_MESSAGE") }}:</strong>
        </h6>
        <div class="gift-details__msg">
          <p>{{ giftMessage.message.message }}</p>
        </div>
      </div>
    </b-modal>
    <pending-request-model></pending-request-model>
  </div>
</template>

<script>
import VueTagsInput from "@johmun/vue-tags-input";
import orderShippingStatus from "./partial/shippingStatus";
import codPayment from "./partial/codPayment";
import trackingInformation from "./partial/trackingInformation";
import sharePaymentLink from "./partial/sharePaymentLink";
import CardIcons from "./partial/CardIcons";
import pendingRequestModel from "./partial/pandingRequests";
export default {
  components: {
    VueTagsInput,
    codPayment,
    sharePaymentLink,
    orderShippingStatus,
    trackingInformation,
    pendingRequestModel,
    CardIcons: CardIcons,
  },
  data: function () {
    return {
      currencySymbol: currencySymbol,
      adminsData: {},
      codApprovedModelId: "codApproved",
      shippingStatusPopupId: "shippingStatusPopup",
      paymentLinkPopupId: "paymentLinkPopup",
      trackingPopupId: "trackingInformation",
      pendingRequestPopupId: "pendingRequestModel",
      statusData: {},
      activeThemeUrl: activeThemeUrl,
      adminBaseUrl: adminBaseUrl,
      baseUrl: baseUrl,
      editnote: 0,
      editCourierInfo: 0,
      returnStatus: 0,
      balance: 0,
      clicked: 0,
      note: "",
      previous: "",
      next: "",
      url: "",
      tag: "",
      message: "",
      tags: [],
      orders: [],
      addressTypes: [],
      shippingStatus: [],
      orderStatus: [],
      orderReturnStatus: [],
      messages: [],
      shipping: 0,
      totalRefund: 0,
      refundAmount: 0,
      rewardAmount: 0,
      userName: "",
      comment: "",
      shareSuccessMessage: "",
      tax: 0,
      displayMessage: false,
      subTotal: 0,
      giftprice: 0,
      discount: 0,
      totalProduct: 0,
      returnSubtotal: 0,
      returnTax: 0,
      returnShipping: 0,
      returnTotal: 0,
      returnDiscount: 0,
      returnGiftwrap: 0,
      returnReward: 0,
      readonly: false,
      totalRefundAmount: 0,
      totalRefundedAmount: 0,
      requestType: [],
      activeReturnStatus: 0,
      shippingMesagesEnable: 0,
      allProductStatus: [],
      orderDigitalStatus: [],
      productType: 0,
      productRefunds: {},
      giftMessage: {},
      shareModelId: "shareModel",
      pagination: {
        total: 0,
        per_page: 12,
        from: 1,
        to: 0,
        current_page: 1,
        last_page: 0,
      },
      trackingEnabled: false,
      trackingInfo: [],
      trackingCheckpoints: [],
      shippingTypes: [],
      digitalStatus: [],
      pickupStatus: [],
      couriers: {},
    };
  },
  methods: {
    displayGiftMessage: function () {
      this.$bvModal.show("giftModal");
    },
    validateArray: function (index, shipStatus) {
      switch (shipStatus) {
        case 1:
          if (index != 3) {
            return true;
          }
          break;
        case 2:
          if (index != 3 && index != 2) {
            return true;
          }
          break;
        case 3:
          if (index != 3 && index != 2 && index != 1) {
            return true;
          }
          break;
        case 4:
          return false;
          break;
      }
    },
    validateTags: function (newTags) {
      this.tags = newTags;
      let formData = this.$serializeData({
        tags: JSON.stringify(this.tags),
      });
      formData.append("order-id", this.orders.order_id);
      this.$http.post(adminBaseUrl + "/orders/tags", formData).then(
        (response) => {
          //success
          if (response.data.status == false) {
            this.isLoading = false;
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
        },
        (response) => {
          //error
          this.validateErrors(response);
        }
      );
    },
    displayComment: function () {
      if (this.displayMessage == true) {
        this.displayMessage = false;
      } else {
        this.displayMessage = true;
      }
      this.loadCommentRecords();
    },
    saveRecord: function () {
      let formData = this.$serializeData(this.adminsData);
      formData.append("order-id", this.orders.order_id);
      this.$http.post(adminBaseUrl + "/orders/payment", formData).then(
        (response) => {
          //success
          if (response.data.status == false) {
            this.isLoading = false;
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          this.$bvModal.hide(this.codApprovedModelId);
          this.pageLoadData(this.orders.order_id);
          toastr.success(response.data.message, "", toastr.options);
        },
        (response) => {
          //error
          this.validateErrors(response);
        }
      );
    },
    updateCourierInfo: function () {
      let formData = this.$serializeData(this.orders.addition_info);

      this.$http
        .post(adminBaseUrl + "/orders/update-courier-info", formData)
        .then((response) => {
          this.editCourierInfo = 0;
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    currentPage: function (page) {
      this.loadCommentRecords(this.pagination.current_page);
    },
    printWindow: function () {
      window.print();
    },
    makePayment: function (adminsData) {
      let formData = this.$serializeData(adminsData);
      formData.append("order-id", this.orders.order_id);
      this.$http.post(adminBaseUrl + "/orders/payment", formData).then(
        (response) => {
          //success
          if (response.data.status == false) {
            this.isLoading = false;
            toastr.error(response.data.message, "", toastr.options);
            return;
          }

          this.pageLoadData(this.orders.order_id);
          this.$bvModal.hide(this.codApprovedModelId);
          toastr.success(response.data.message, "", toastr.options);
        },
        (response) => {
          //error
          this.validateErrors(response);
        }
      );
    },
    cardNumber: function (number) {
      if (number.network) {
        return number.last4.substr(number.last4.length - 4);
      }
      return "";
    },
    updatePayment: function (status = "", key = "", complete = 0) {
      let type = "";
      if (status == "") {
        status = this.shippingStatus[this.orders.order_shipping_status];
        type = "payment";
      }
      this.statusData = {
        id: this.orders.order_id,
        status: status,
        key: key,
        type: type,
        complete: complete,
      };
      this.adminsData.transaction_id = this.$rndStr();
      this.adminsData.amount = this.balance;
      this.adminsData.payment_method = "cash";
      this.readonly = "true";
      this.$bvModal.show(this.codApprovedModelId);
    },
    emptyFormValues: function () {
      this.commentData = {};
      this.url = "";
      this.statusData = {};
      this.message = "";
      this.adminsData = {};
      this.errors.clear();
    },
    updateReturnStatus: function (request, status, key) {
      let amount = this.calulateRefundByRequest(request);
      let id = request.cancel_request.orrequest_id;
      this.url = "return/";

      let gatwayType = this.orders.order_payment_method;
      if (this.orders.transaction) {
        if (
          this.inArray(this.orders.transaction.txn_gateway_type, [
            "cash",
            "card",
          ]) == false
        ) {
          gatwayType = this.orders.transaction.txn_gateway_type;
        }
      }

      if (
        status == "approved" &&
        gatwayType == "cod" &&
        this.orders.payment_status == 2
      ) {
        this.adminsData.payment_method = "Bank Transfer";
        this.statusData = {
          id: id,
          status: status,
          key: key,
          amount: amount,
        };

        this.$bvModal.show(this.codApprovedModelId);
        return false;
      } else {
        this.statusData = {
          id: id,
          status: status,
          key: key,
          amount: amount,
        };
        this.$bvModal.show(this.shippingStatusPopupId);
        return false;
      }
    },
    calulateRefundByRequest: function (block) {
      let price = block.op_product_price * block.cancel_request.orrequest_qty;
      let tax = block.cancel_request.oramount_tax;
      let discount = block.cancel_request.oramount_discount;
      let giftwrap = block.cancel_request.oramount_giftwrap_price;
      let reward = block.cancel_request.oramount_reward_price;
      let shipping = block.cancel_request.oramount_shipping;

      let total = parseFloat(price) + parseFloat(tax) + parseFloat(shipping);
      if (discount != 0) {
        total = total - discount;
      }
      if (reward != 0) {
        total = total - reward;
      }
      if (giftwrap != 0) {
        if (giftwrap > 0) {
          total = parseFloat(total) + parseFloat(Math.abs(giftwrap));
        } else {
          total = total - Math.abs(giftwrap);
        }
      }
      if (total < 0) {
        total = 0;
      }

      return total;
    },

    updateOrderStatus: function (
      id,
      status,
      key = 0,
      amount = 0,
      complete = 0
    ) {
      if (
        this.orders.order_shipping_status == id &&
        this.url == "" &&
        complete == 0
      ) {
        return false;
      }

      if (this.orders.pending_requests != 0) {
        this.$bvModal.show(this.pendingRequestPopupId);
        return false;
      }

      let digitalOrderType = 0;
      if (this.orders.digital_products == this.totalProduct) {
        digitalOrderType = 1;
      }
      if (this.orders.digital_products != 0 && this.requestType.length > 0) {
        digitalOrderType = this.validateDigitalRequests(this.orders);
      }
      let gatwayType = this.orders.order_payment_method;
      if (this.orders.transaction) {
        if (
          this.inArray(this.orders.transaction.txn_gateway_type, [
            "cash",
            "card",
          ]) == false
        ) {
          gatwayType = this.orders.transaction.txn_gateway_type;
        }
      }
      if (status == "delivered" && gatwayType == "cod" && this.balance > 0) {
        this.updatePayment(status, key, complete);
        return false;
      } else if (status == "delivered" && this.orders.digital_products != 0) {
        this.digitalAdditionalUpload(
          id,
          status,
          key,
          amount,
          complete,
          digitalOrderType
        );
        return;
      } else {
        this.statusData = {
          id: this.orders.order_id,
          status: status,
          key: key,
          amount: amount,
          digitalOrderType: digitalOrderType,
          complete: complete,
        };
        if (
          (this.shippingMesagesEnable == 0 && status != "shipped") ||
          (status == "shipped" && digitalOrderType == 1)
        ) {
          this.updateStatus(this.statusData);
          return false;
        }
        this.$bvModal.show(this.shippingStatusPopupId);
      }
    },
    validateDigitalRequests: function (order) {
      let physicalProductExist = [];
      Object.keys(order.products).forEach((pkey) => {
        if (
          order.products[pkey].cancel_request != "" &&
          order.products[pkey].cancel_request != null &&
          order.products[pkey].op_product_type == true
        ) {
          if (
            order.products[pkey].op_qty -
              order.products[pkey].cancel_request.orrequest_qty ==
            0
          ) {
            physicalProductExist.push(false);
          } else {
            physicalProductExist.push(true);
          }
        }
      });
      if (this.inArray(true, physicalProductExist)) {
        return 0;
      } else {
        return 1;
      }
    },
    digitalAdditionalUpload: function (
      id,
      status,
      key,
      amount,
      complete,
      digitalOrderType
    ) {
      let formData = this.$serializeData({
        "order-id": this.orders.order_id,
      });
      this.$http
        .post(adminBaseUrl + "/orders/digital-additional-upload", formData)
        .then((response) => {
          this.statusData = {
            id: this.orders.order_id,
            digitalCount: this.orders.digital_products,
            status: status,
            key: key,
            amount: amount,
            digitalOrderType: digitalOrderType,
            complete: complete,
            uploadInfo: response.data.data,
          };
          this.$bvModal.show(this.shippingStatusPopupId);
        });
    },
    isRequestPending: function (
      id,
      status,
      key,
      amount,
      complete,
      digitalOrderType
    ) {
      let formData = this.$serializeData({
        "order-id": this.orders.order_id,
      });
      this.$http
        .post(adminBaseUrl + "/orders/pending-requests", formData)
        .then((response) => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return false;
          }
          this.statusData = {
            id: this.orders.order_id,
            status: status,
            key: key,
            amount: amount,
            digitalOrderType: digitalOrderType,
            complete: complete,
          };
          if (
            (this.shippingMesagesEnable == 0 && status != "shipped") ||
            (status == "shipped" && digitalOrderType == 1)
          ) {
            this.updateStatus(this.statusData);
            return false;
          }
          this.$bvModal.show(this.shippingStatusPopupId);
        });
    },
    closePopup: function () {
      this.$bvModal.hide(this.codApprovedModelId);
      this.$bvModal.hide(this.shippingStatusPopupId);
      this.statusData = {};
      this.adminsData = {};
    },
    updateStatus: function (shipStatus, shipComment, adminsData = []) {
      if (shipStatus.type && shipStatus.type == "payment") {
        this.makePayment(adminsData);
        return;
      }

      let formData = this.$serializeData({
        status: shipStatus.status,
        complete: shipStatus.complete,
        "transfer-detail": JSON.stringify(adminsData),
      });

      if (shipComment) {
        formData.append("message", shipComment.message);
        formData.append("tracking_number", shipComment.tracking_number);
        formData.append("courier_name", shipComment.courier_name);
      }

      this.$http
        .post(
          adminBaseUrl + "/orders/" + this.url + "status/" + shipStatus.id,
          formData
        )
        .then((response) => {
          this.readonly = false;
          this.emptyFormValues();
          this.$bvModal.hide(this.shippingStatusPopupId);
          this.$bvModal.hide(this.codApprovedModelId);
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageLoadData(this.orders.order_id);
          this.loadCommentRecords();
        });
    },
    sendPaymentLink: function () {
      this.shareSuccessMessage = "";
      this.statusData = { id: this.orders.order_id };
      this.$bvModal.show(this.paymentLinkPopupId);
    },

    pageLoadData: function (id) {
      this.$http.get(adminBaseUrl + "/orders/" + id).then((response) => {
        let order = response.data.data.order;
        this.orders = order;
        this.note = order.order_notes ? order.order_notes : "";
        this.previous = response.data.data.previous;
        this.next = response.data.data.next;
        this.userName = response.data.data.userName;
        this.shippingStatus = response.data.data.shippingStatus;
        this.orderStatus = response.data.data.orderStatus;
        this.orderReturnStatus = response.data.data.orderReturnStatus;
        this.messages = response.data.data.messages.data;
        this.pagination = response.data.data.messages;
        this.addressTypes = response.data.data.addressTypes;
        this.shippingTypes = response.data.data.shippingTypes;
        this.digitalStatus = response.data.data.digitalStatus;
        this.pickupStatus = response.data.data.pickupStatus;
        this.totalProduct = order.products.length;
        this.orderDigitalStatus = response.data.data.orderDigitalStatus;
        this.productType = response.data.data.productType;
        this.shippingMesagesEnable = response.data.data.shippingMesages;
        this.trackingEnabled = response.data.data.trackingEnabled;
        this.trackingInfo = response.data.data.trackingInfo;
        let couriers = response.data.data.shippingCouriers;
        this.couriers = couriers.data;
        if (order.order_tax_charged) {
          this.tax = order.order_tax_charged;
        }
        if (order.order_shipping_value) {
          this.shipping = order.order_shipping_value;
        }
        if (order.order_discount_amount) {
          this.discount = order.order_discount_amount;
        }
        if (order.order_gift_amount) {
          this.giftprice = order.order_gift_amount;
        }
        if (order.order_reward_amount) {
          this.rewardAmount = order.order_reward_amount;
        }

        this.subTotal =
          parseFloat(
            order.order_net_amount - this.tax - this.shipping - this.giftprice
          ) +
          parseFloat(this.rewardAmount) +
          parseFloat(this.discount);
        this.varifyStatus(order);
        if (this.activeReturnStatus != 0) {
          this.calulateRefund(order.products, this.activeReturnStatus);
        }

        if (order.order_net_amount != 0) {
          this.calulateTotalRefund(order.products);
        }
        this.tags = response.data.data.tags.map((a) => {
          return {
            text: a,
          };
        });
        this.loadCommentRecords();
      });
    },
    completeOrder: function () {
      let type = "delivered";
      if (this.orders.order_shipping_type == 1) {
        type = "picked-up";
      }
      this.updateOrderStatus(4, type, 0, 0, 1);
    },
    calulateTotalRefund: function (block) {
      let refundedAmount = 0;
      let refundedGiftwrap = 0;
      this.totalRefundedAmount = 0;

      Object.keys(block).forEach((pkey) => {
        if (
          block[pkey].cancel_request != "" &&
          block[pkey].cancel_request != null
        ) {
          if (block[pkey].cancel_request.orrequest_status == 2) {
            refundedAmount +=
              parseFloat(
                block[pkey].op_product_price *
                  block[pkey].cancel_request.orrequest_qty
              ) +
              parseFloat(block[pkey].cancel_request.oramount_tax) -
              parseFloat(block[pkey].cancel_request.oramount_discount) +
              parseFloat(block[pkey].cancel_request.oramount_shipping) -
              parseFloat(block[pkey].cancel_request.oramount_reward_price);

            if (block[pkey].cancel_request.oramount_giftwrap_price != 0) {
              refundedGiftwrap += parseFloat(
                block[pkey].cancel_request.oramount_giftwrap_price
              );
            }
          }
        }
      });

      this.totalRefundedAmount = refundedAmount;
      if (refundedGiftwrap != 0) {
        if (refundedGiftwrap > 0) {
          this.totalRefundedAmount =
            refundedAmount + parseFloat(Math.abs(refundedGiftwrap));
        }
      }
    },
    calulateRefund: function (block, type = 0) {
      let returnSubtotal = 0;
      let returnTax = 0;
      let returnDiscount = 0;
      let returnGiftwrap = 0;
      let returnReward = 0;
      let returnShipping = 0;
      let refundedAmount = 0;
      let refundedGiftwrap = 0;
      if (type == 0) {
        this.requestType = [];
      }

      Object.keys(block).forEach((pkey) => {
        if (
          block[pkey].cancel_request != "" &&
          block[pkey].cancel_request != null
        ) {
          if (type == 0) {
            this.requestType.push(block[pkey].cancel_request.orrequest_type);
          }
          if (block[pkey].cancel_request.orrequest_type != type && type != 0) {
            return;
          }

          returnSubtotal += parseFloat(
            block[pkey].op_product_price *
              block[pkey].cancel_request.orrequest_qty
          );
          returnTax += parseFloat(block[pkey].cancel_request.oramount_tax);
          returnDiscount += parseFloat(
            block[pkey].cancel_request.oramount_discount
          );
          returnReward += parseFloat(
            block[pkey].cancel_request.oramount_reward_price
          );
          returnShipping += parseFloat(
            block[pkey].cancel_request.oramount_shipping
          );

          if (block[pkey].cancel_request.oramount_giftwrap_price != 0) {
            returnGiftwrap += parseFloat(
              block[pkey].cancel_request.oramount_giftwrap_price
            );
          }

          if (block[pkey].cancel_request.orrequest_status == 2) {
            refundedAmount +=
              parseFloat(
                block[pkey].op_product_price *
                  block[pkey].cancel_request.orrequest_qty
              ) +
              parseFloat(block[pkey].cancel_request.oramount_tax) -
              parseFloat(block[pkey].cancel_request.oramount_discount) +
              parseFloat(block[pkey].cancel_request.oramount_shipping) -
              parseFloat(block[pkey].cancel_request.oramount_reward_price);

            if (block[pkey].cancel_request.oramount_giftwrap_price != 0) {
              refundedGiftwrap += parseFloat(
                block[pkey].cancel_request.oramount_giftwrap_price
              );
            }
          }
        }
      });

      this.returnSubtotal = returnSubtotal;
      this.returnTax = returnTax;
      this.returnDiscount = returnDiscount;
      this.returnGiftwrap = returnGiftwrap;
      this.returnReward = returnReward;
      this.returnShipping = returnShipping;
      let total =
        parseFloat(returnSubtotal) +
        parseFloat(returnTax) +
        parseFloat(returnShipping);

      if (this.returnDiscount != 0) {
        total = total - this.returnDiscount;
      }

      if (this.returnReward != 0) {
        total = total - this.returnReward;
      }

      if (this.returnGiftwrap != 0) {
        if (this.returnGiftwrap > 0) {
          total = parseFloat(total) + parseFloat(Math.abs(this.returnGiftwrap));
        }
      }
      if (this.requestType.length > 0 && type == 0) {
        this.activeReturnStatus = this.requestType[0];
      }
      if (total < 0) {
        total = 0;
      }

      if (type == 0) {
        this.totalRefundAmount = total;
      } else {
        this.returnTotal = total;
        this.activeReturnStatus = type;
      }
    },
    inArray: function (needle, haystack) {
      var length = haystack.length;
      for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
      }
      return false;
    },
    varifyStatus: function (order) {
      let approvedStatus = [];
      let declinedStatus = [];
      this.refundedAmount = 0;
      Object.keys(order.products).forEach((pkey) => {
        if (
          order.products[pkey].op_product_variants &&
          JSON.parse(order.products[pkey].op_product_variants).gift
        ) {
          this.giftMessage = JSON.parse(
            order.products[pkey].op_product_variants
          ).gift;
          if (order.products[pkey].cancel_request) {
            if (order.products[pkey].cancel_request.orrequest_status == 1) {
              approvedStatus.push(true);
            } else {
              approvedStatus.push(false);
            }
            if (order.products[pkey].cancel_request.orrequest_status == 2) {
              declinedStatus.push(true);
            } else {
              declinedStatus.push(false);
            }
          }
        }
      });
      if (
        approvedStatus.length > 0 &&
        this.inArray(false, approvedStatus) == false &&
        order.order_status == 3
      ) {
        this.returnStatus = 1;
      } else {
        this.returnStatus = 0;
      }

      this.calulateRefund(order.products);
      this.balance =
        order.order_net_amount -
        (parseFloat(order.order_amount_charged) +
          parseFloat(this.totalRefundedAmount));
    },

    loadCommentRecords: function (pageNo) {
      let formData = this.$serializeData({
        "record-id": this.orders.order_id,
      });
      formData.append("comment", this.displayMessage);
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(adminBaseUrl + "/orders/load-comments?page=" + pageNo, formData)
        .then((response) => {
          this.messages = response.data.data.messages.data;
          this.pagination = response.data.data.messages;
        });
    },
    validateRecord: function () {
      this.clicked = 1;
      this.$validator.validate("comment", this.comment).then((res) => {
        if (res) {
          let formData = this.$serializeData({
            comment: this.comment,
          });
          formData.append("record-id", this.orders.order_id);
          this.$http.post(adminBaseUrl + "/orders/comments", formData).then(
            (response) => {
              //success
              if (response.data.status == false) {
                this.isLoading = false;
                toastr.error(response.data.message, "", toastr.options);
                return;
              }
              this.comment = "";
              toastr.success(response.data.message, "", toastr.options);
              this.loadCommentRecords();
              this.clicked = 0;
            },
            (response) => {
              //error
              this.validateErrors(response);
            }
          );
        }
      });
    },
    validateErrors: function (response) {
      let jsondata = response.data;
      Object.keys(jsondata.errors).forEach((key) => {
        this.errors.add({
          field: key,
          msg: jsondata.errors[key][0],
        });
      });
    },

    nl2br: function (str, is_xhtml) {
      if (typeof str === "undefined" || str === null) {
        return "";
      }
      var breakTag =
        is_xhtml || typeof is_xhtml === "undefined" ? "<br />" : "<br>";
      return (str + "").replace(
        /([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
        "$1" + breakTag + "$2"
      );
    },
    swichOrder: function (id) {
      this.pageLoadData(id);
      this.$router.push({
        name: "orderDetail",
        params: {
          id: id,
        },
      });
    },

    downloadPackingSlip: function (orderId) {
      window.open(adminBaseUrl + "/orders/download-packing-slip/" + orderId);
    },
    showTrackingInformation: function () {
      let formData = new FormData();
      formData.append("tracking_number", this.trackingInfo.oainfo_tracking_id);
      formData.append("courier_name", this.trackingInfo.oainfo_courier_name);
      formData.append("order_id", this.orders.order_id);
      this.$http
        .post(adminBaseUrl + "/orders/get-tracking-information", formData)
        .then(
          (response) => {
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            this.trackingCheckpoints = response.data.data.data;
            this.$bvModal.show(this.trackingPopupId);
          },
          (response) => {
            //error
          }
        );
      this.$bvModal.show(this.trackingPopupId);
    },
    copyTrackingInformation: function (trackingCode) {
      let $temp = $("<input>");
      $("body").append($temp);
      let copyText = trackingCode;
      $temp.val(copyText).select();
      document.execCommand("copy");
      $temp.remove();
      toastr.success("copy successfully", "", toastr.options);
    },
  },
  mounted: function () {
    let id = this.$route.params.id;
    this.pageLoadData(id);
  },
};
</script>