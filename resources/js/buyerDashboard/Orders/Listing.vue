<template>
  <li class="accordion" role="tablist">
    <div
      class="orders-head"
      block
      v-b-toggle="'accordion' + order.order_id"
      @click="loadComments(order.order_id)"
      variant="info"
    >
      <div class="col-label">
        <h3>
          <span>{{ $t("LBL_ORDER_DATE") }}</span>
          <span class="date">
            {{ $dateTimeFormat(order.order_date_added, "date") }}
            <time>{{ $dateTimeFormat(order.order_date_added, "time") }}</time>
          </span>
        </h3>
      </div>
      <div class="col-label">
        <h3>
          <span>{{ $t("LBL_ORDERS_NUMBER") }}</span>
          #{{ order.order_id }}
          <svg
            class="svg"
            v-if="order.order_gift_amount && order.order_gift_amount > 0"
          >
            <use
              :xlink:href="
                baseUrl + '/dashboard/media/retina/sprite.svg#gift-icon'
              "
              :href="baseUrl + '/dashboard/media/retina/sprite.svg#gift-icon'"
            />
          </svg>
        </h3>
      </div>
      <div class="col-label">
        <h3>
          <span>{{ $t("LBL_ORDER_STATUS") }}</span>

          <svg class="svg" width="20px" height="20px">
            <use
              :xlink:href="
                baseUrl +
                '/dashboard/media/retina/sprite.svg#' +
                currentShippingStatus(order)
              "
              :href="
                baseUrl +
                '/dashboard/media/retina/sprite.svg#' +
                currentShippingStatus(order)
              "
            />
          </svg>
          {{ $cleanShipStatus(currentShippingStatus(order)) }}
        </h3>
      </div>
      <div class="col-products">
        <ul class="media-more show">
          <li v-for="(product, pKey) in order.products.slice(0, 4)" :key="pKey">
            <span
              class="circle"
              v-b-tooltip.hover
              :title="product.op_product_name"
            >
              <img
                :src="
                  $productImage(
                    product.op_product_id,
                    product.op_pov_code,
                    product.images ? product.images.afile_updated_at : '',
                    '30-30'
                  )
                "
                data-yk
              />
            </span>
          </li>

          <li v-if="order.products.length > 4">
            <span class="plus-more">+{{ order.products.length - 4 }}</span>
          </li>
        </ul>
      </div>
      <span class="triger"></span>
    </div>
    <div class="orders-body">
      <b-collapse
        :id="'accordion' + order.order_id"
        accordion="my-accordion"
        role="tabpanel"
      >
        <!--<div class="shipping-from">
          <div class="shipping-from__address">
            <i class="icn">
              <svg class="svg" width="20px" height="20px">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#shipped'
                  "
                  :href="baseUrl + '/dashboard/media/retina/sprite.svg#shipped'"
                />
              </svg>
            </i>
            {{
            order.order_shipping_type == 1
            ? $t("LBL_PICKUP")
            : $t("LBL_SHIPPING")
            }}
            {{
            order.products.length +
            " " +
            $t("LBL_ITEMS_FROM") +
            " " +
            $configVal("BUSINESS_NAME") +
            ","
            }}
            <span
              class="location"
            >
              {{
              $configVal("BUSINESS_CITY") + ", " + stateName
              }}
            </span>
          </div>
          <div class="shipping-from__action btn-group">
            <a
              class="btn btn-outline-brand btn-sm"
              href="javascript:;"
              @click="updatePayment(order)"
              v-if="
                order.payment_status == 1 &&
                  order.order_status != 4 &&
                  (order.order_payment_method != 'rewards' ||
                    order.order_payment_method != 'cod')
              "
            >Update payment details</a>

            <a
              class="btn btn-outline-brand btn-sm"
              :href="baseUrl + '/user/reorder/' + order.order_id"
            >{{ $t("BTN_BUY_AGAIN") }}</a>
            <inertia-link
              class="btn btn-outline-brand btn-sm"
              :href="baseUrl + '/user/order/cancel/' + order.order_id"
              v-if="isRequestExist(order)"
            >
              {{
              $inArray(order.order_shipping_status, [1, 2])
              ? $t("BTN_CANCEL_ORDER")
              : $t("BTN_RETURN_ORDER")
              }}
            </inertia-link>
            <a
              class="btn btn-outline-brand btn-sm"
              :href="baseUrl + '/order/download-pdf/' + order.order_id"
              v-if="order.invoice_count"
            >{{ $t("BTN_INVOICE") }}</a>
          </div>
        </div>-->
        <div class="order-blocks">
          <div class="order-blocks__left">
            <ul class="list-group list-cart list-cart-checkout scroll-y">
              <li
                class="list-group-item"
                v-for="(product, pKey) in order.products"
                :key="pKey"
              >
                <div class="product-profile">
                  <div class="product-profile__thumbnail">
                    <a :href="$generateUrl(product.url_rewrite)">
                      <img
                        data-yk
                        :src="
                          $productImage(
                            product.op_product_id,
                            product.op_pov_code,
                            product.images
                              ? product.images.afile_updated_at
                              : '',
                            '38-51'
                          )
                        "
                        class="img-fluid"
                        data-ratio="3:4"
                      />
                    </a>

                    <span
                      class="return-status"
                      v-bind:class="[
                        product.cancel_request.orrequest_status == 3
                          ? 'danger'
                          : '',
                        product.cancel_request.orrequest_status == 2
                          ? 'info'
                          : '',
                        product.cancel_request.orrequest_status == 1
                          ? 'success'
                          : '',
                      ]"
                      v-if="product.cancel_request"
                      v-b-tooltip.hover
                      :title="cancelStatus(product.cancel_request)"
                    ></span>
                  </div>
                  <div class="product-profile__data">
                    <div class="title">
                      <a :href="$generateUrl(product.url_rewrite)">
                        {{ product.op_product_name }}
                      </a>
                    </div>
                    <div class="options text-capitalize">
                      <p>
                        <inertia-link
                          :href="
                            baseUrl +
                            '/user/orders/download/' +
                            product.op_order_id +
                            '/' +
                            product.op_product_id
                          "
                          v-b-tooltip.hover="'Download'"
                          v-if="product.op_product_type == 2"
                        >
                          <i class="icn-digital">
                            <svg class="svg" width="14px" height="14px">
                              <use
                                :xlink:href="
                                  baseUrl +
                                  '/dashboard/media/retina/sprite.svg#digital-icn'
                                "
                                :href="
                                  baseUrl +
                                  '/dashboard/media/retina/sprite.svg#digital-icn'
                                "
                              />
                            </svg>
                          </i>
                        </inertia-link>

                        <span class="product-qty"
                          >{{ $t("LBL_QTY") }}: {{ product.op_qty }}</span
                        >
                      </p>
                      <div class="product-variants-gift d-md-flex">
                        <span
                          class="mr-2 d-block"
                          v-if="
                            product.op_product_variants &&
                            JSON.parse(product.op_product_variants).styles
                          "
                        >
                          Options:
                          {{
                            Object.values(
                              JSON.parse(product.op_product_variants).styles
                            ).join(" | ")
                          }}
                        </span>
                        <a
                          class="YK-GiftItem gift-item active"
                          href="javascript:;"
                          v-if="
                            product.op_product_variants &&
                            JSON.parse(product.op_product_variants).gift
                          "
                          @click="
                            $emit(
                              'displayGiftMessage',
                              JSON.parse(product.op_product_variants).gift
                            )
                          "
                        >
                          <svg class="svg m-0">
                            <use
                              :xlink:href="
                                baseUrl +
                                '/dashboard/media/retina/sprite.svg#gift-icon'
                              "
                              :href="
                                baseUrl +
                                '/dashboard/media/retina/sprite.svg#gift-icon'
                              "
                            />
                          </svg>
                        </a>
                      </div>
                    </div>
                    <div class="mt-2" v-if="product.product_review">
                      <inertia-link :href="baseUrl + '/user/reviews'">
                        <rating-stars
                          :rating="product.product_review.preview_rating"
                        ></rating-stars>
                      </inertia-link>
                    </div>
                    <div class="actions" v-else>
                      <!-- -->
                      <button
                        class="btn btn-sm btn-icon link-text"
                        :disabled="
                          (product.cancel_request === null ||
                            (product.cancel_request != '' &&
                              product.cancel_request.orrequest_status == 0)) &&
                          (order.order_shipping_status == 1 ||
                            order.order_shipping_status == 2)
                        "
                        @click="$emit('writeReview', index, pKey)"
                      >
                        {{ $t("BTN_WRITE_A_REVIEW") }}
                      </button>
                    </div>
                  </div>
                </div>
                <div class="product-price">
                  {{ $priceFormat(product.op_product_price) }}
                </div>
              </li>
            </ul>

            <div class="cart-total mt-6">
              <ul class="list-group list-group-flush list-group-flush-x">
                <li class="list-group-item border-0">
                  <span class="label">{{ $t("LBL_SUBTOTAL") }}</span>
                  <span class="ml-auto">
                    {{ $priceFormat($orderSubTotal(order)) }}
                  </span>
                </li>
                <li class="list-group-item">
                  <span class="label">{{ $t("LBL_TAX") }}</span>
                  <span class="ml-auto">
                    <span
                      v-bind:class="[
                        order.order_tax_charged && order.order_tax_charged > 0
                          ? ''
                          : 'txt-success',
                      ]"
                    >
                      {{
                        order.order_tax_charged && order.order_tax_charged > 0
                          ? $priceFormat(order.order_tax_charged)
                          : $t("LBL_FREE")
                      }}
                    </span>
                  </span>
                  <del v-if="order.order_tax_charged == 0">{{
                    $priceFormat("00.00")
                  }}</del>
                </li>

                <li
                  class="list-group-item"
                  v-if="
                    order.order_discount_amount &&
                    order.order_discount_amount > 0
                  "
                >
                  <span class="label">
                    {{ $t("LBL_DISCOUNT_COUPON") }}
                    <i
                      class="fas fa-info-circle"
                      v-b-tooltip.hover
                      :title="order.order_discount_coupon_code"
                    ></i>
                  </span>
                  <span class="ml-auto"
                    >-{{ $priceFormat(order.order_discount_amount) }}</span
                  >
                </li>

                <li
                  class="list-group-item"
                  v-if="
                    order.order_shipping_type != 1 &&
                    order.digital_products != order.products.length
                  "
                >
                  <span class="label">{{ $t("LBL_SHIPPING") }}</span>
                  <span
                    class="ml-auto"
                    v-bind:class="[
                      order.order_shipping_value &&
                      order.order_shipping_value > 0
                        ? ''
                        : 'txt-success',
                    ]"
                  >
                    {{
                      order.order_shipping_value &&
                      order.order_shipping_value > 0
                        ? $priceFormat(order.order_shipping_value)
                        : $t("LBL_FREE")
                    }}
                  </span>
                  <del v-if="order.order_shipping_value == 0">{{
                    $priceFormat("00.00")
                  }}</del>
                </li>
                <li
                  class="list-group-item"
                  v-if="
                    order.order_reward_amount && order.order_reward_amount > 0
                  "
                >
                  <span class="label">
                    {{ $t("LBL_REWARD_POINTS_AMOUNT") }}
                  </span>
                  <span class="ml-auto"
                    >-{{ $priceFormat(order.order_reward_amount) }}</span
                  >
                </li>
                <li
                  class="list-group-item"
                  v-if="Object.keys(giftMessage).length > 0"
                >
                  <span class="label">{{ $t("LBL_GIFT_WRAP_AMOUNT") }}</span>
                  <span class="ml-auto">
                    <del v-if="order.order_gift_amount == 0">
                      {{ $priceFormat("00.00") }}
                    </del>
                    {{
                      order.order_gift_amount && order.order_gift_amount > 0
                        ? $priceFormat(order.order_gift_amount)
                        : $t("LBL_FREE")
                    }}
                  </span>
                </li>
                <li class="list-group-item hightlighted">
                  <span class="label">{{ $t("LBL_TOTAL") }}</span>
                  <span class="ml-auto">
                    {{ $priceFormat(order.order_net_amount) }}
                  </span>
                </li>
              </ul>
            </div>
            <div class="timeline-orders mt-6">
              <div class="timeline-comments">
                <div class="timeline-avatar">
                  <span class="timeline-avatar-initials">
                    <img
                      data-yk
                      :src="
                        baseUrl +
                        '/' +
                        this.$getFileUrl(
                          'userProfileImage',
                          auth.user_id,
                          0,
                          '50-50'
                        )
                      "
                      data-ratio="1:1"
                    />
                  </span>
                </div>
                <form
                  :id="'YK-message-' + order.order_id"
                  @submit.prevent="sentMessage(order.order_id)"
                  class="form timeline-comments-box"
                >
                  <div class="input-group">
                    <input
                      type="text"
                      v-model="comment"
                      name="comment"
                      :placeholder="$t('PLH_LEAVE_COMMENT') + '..'"
                      class="form-control"
                    />
                    <button
                      type="button"
                      class="btn-fly"
                      @click.prevent="
                        sentMessage(order.order_id), (sending = true)
                      "
                      :disabled="this.comment == ''"
                      v-bind:class="[sending == true ? 'gb-is-loading' : '']"
                    >
                      <svg class="svg">
                        <use
                          :xlink:href="
                            baseUrl +
                            '/dashboard/media/retina/sprite.svg#submitfly'
                          "
                        />
                      </svg>
                    </button>
                  </div>
                </form>
              </div>
              <div class="timelines-wrap scroll-y" infinite-wrapper>
                <ul class="timeline my-5">
                  <li
                    class="event comments default_status"
                    v-for="(shipping, sIndex) in shippingTypes"
                    :key="order.order_id + '-' + sIndex"
                    v-if="
                      validateArray(sIndex, order.order_shipping_status) == true
                    "
                  >
                    <span class="timeline__date"></span>
                    <div class="data">
                      <span v-if="order.order_shipping_type == 1">{{
                        $cleanShipStatus(pickupStatus[sIndex])
                      }}</span>
                      <span
                        v-else-if="
                          order.digital_products != 0 &&
                          order.digital_products == order.products.length
                        "
                        >{{ $cleanShipStatus(digitalStatus[shipping]) }}</span
                      >
                      <span v-else>
                        {{ $cleanShipStatus(shipping) }}
                        {{
                          order.digital_products != 0 &&
                          order.digital_products != order.products.length
                            ? "/" + $cleanShipStatus(digitalStatus[shipping])
                            : ""
                        }}
                      </span>
                    </div>
                  </li>
                  <messages-time-line
                    v-for="(message, msgKey) in messages"
                    :key="msgKey"
                    :message="message"
                    :msgKey="msgKey"
                    :trackingEnabled="trackingEnabled"
                    :order="order"
                  ></messages-time-line>
                </ul>
                <infinite-loading
                  v-if="messages.length > 5"
                  :forceUseInfiniteWrapper="true"
                  :identifier="infiniteId"
                  @infinite="infiniteHandler($event, order.order_id)"
                ></infinite-loading>
              </div>

              <span
                v-if="
                  $inArray(order.order_shipping_status, [3, 4]) &&
                  order.additionInfo &&
                  order.additionInfo.oainfo_courier_name
                "
              >
                <hr />
                <div class="info-box">
                  <h6>{{ $t("LBL_TRACK_YOUR_ORDER") }}</h6>
                  <p class="mb-2">{{ $t("LBL_TRACK_ORDER_INSTRUCTIONS") }}</p>
                  <div class="row">
                    <div class="col-auto">
                      <span class="bold">{{ $t("LBL_COURIER") }}</span>
                      <p>{{ order.additionInfo.oainfo_courier_name }}</p>
                    </div>
                    <div class="col">
                      <span class="bold">
                        {{ $t("LBL_TRACKING_REFERENCE") }}
                      </span>
                      <p>{{ order.additionInfo.oainfo_tracking_id }}</p>
                    </div>
                  </div>
                </div>
                <span
                  v-if="order.additionInfo.oainfo_received_confirmation != 1"
                >
                  <hr />
                  <div class="info-box YK-orderReceived">
                    <h6>{{ $t("LBL_HAVE_RECEIVED_ORDER") }}</h6>
                    <p>{{ $t("LBL_CONFIRM_ORDER") }}</p>
                    <div class="mt-3">
                      <button
                        class="btn btn-brand btn-wide"
                        @click="receivedConfirmation()"
                        type="button"
                      >
                        {{ $t("BTN_CONFIRM") }}
                      </button>
                    </div>
                  </div>
                </span>
              </span>
            </div>
          </div>
          <div class="order-blocks__right">
            <div class="btn-group btn-group-pill orders-actions">
              <a
                class="btn btn-outline-gray btn-sm"
                href="javascript:;"
                @click="updatePayment(order)"
                v-if="
                  order.payment_status == 1 &&
                  order.order_status != 4 &&
                  (order.order_payment_method != 'rewards' ||
                    order.order_payment_method != 'cod')
                "
                >Update payment details</a
              >

              <a
                class="btn btn-outline-gray btn-sm"
                v-bind:class="[addToCart == true ? 'disabled' : '']"
                @click="addToCart = true"
                :href="baseUrl + '/user/reorder/' + order.order_id"
                >{{ $t("BTN_BUY_AGAIN") }}</a
              >
              <inertia-link
                class="btn btn-outline-gray btn-sm"
                :href="baseUrl + '/user/order/cancel/' + order.order_id"
                v-if="isRequestExist(order)"
              >
                {{
                  $inArray(order.order_shipping_status, [1, 2])
                    ? $t("BTN_CANCEL_ORDER")
                    : $t("BTN_RETURN_ORDER")
                }}
              </inertia-link>
              <a
                class="btn btn-outline-gray btn-sm"
                :href="baseUrl + '/order/download-pdf/' + order.order_id"
                v-if="order.invoice_count"
                >{{ $t("BTN_INVOICE") }}</a
              >
            </div>

            <ul class="order-contacts">
              <li
                class="order-contacts-block"
                v-if="
                  order.transaction &&
                  order.transaction.txn_gateway_transaction_id
                "
              >
                <h4>{{ $t("LBL_PAYMENT_DETAILS") }} :</h4>

                <div class="payment-mode">
                  <div
                    class="cc-payment"
                    v-if="JSON.parse(order.transaction.txn_gateway_response)"
                  >
                    <i class="icn">
                      <card-icons
                        :cardType="
                          JSON.parse(order.transaction.txn_gateway_response)
                            .data.payment_method_details &&
                          JSON.parse(order.transaction.txn_gateway_response)
                            .data.payment_method_details.card.network
                            ? $cleanString(
                                JSON.parse(
                                  order.transaction.txn_gateway_response
                                ).data.payment_method_details.card.network
                              )
                            : ''
                        "
                      ></card-icons>
                    </i>
                    <span
                      class="cc-num"
                      v-if="
                        JSON.parse(order.transaction.txn_gateway_response).data
                          .payment_method_details
                      "
                    >
                      **** **** ****
                      {{
                        cardNumber(
                          JSON.parse(order.transaction.txn_gateway_response)
                            .data.payment_method_details.card
                        )
                      }}
                    </span>
                    <span v-else-if="order.order_payment_method == 'Paypal'">
                      <img
                        :src="
                          baseUrl + '/dashboard/media/retina/paypal-icon.png'
                        "
                        width="20px"
                      />
                      {{ order.order_payment_method }}
                    </span>
                    <span v-else>
                      <i class="icn">
                        <svg class="svg" width="16px" height="16px">
                          <use
                            :xlink:href="
                              baseUrl + '/dashboard/media/retina/sprite.svg#cod'
                            "
                            :href="
                              baseUrl + '/dashboard/media/retina/sprite.svg#cod'
                            "
                          />
                        </svg>
                      </i>
                      {{ $t("LBL_CASH") }}s
                    </span>
                  </div>
                  <div class="txt-id">
                    <h6>
                      <strong>{{ $t("LBL_TRANSACTION_NUMBER") }}</strong>
                    </h6>
                    <span>
                      {{ order.transaction.txn_gateway_transaction_id }}
                    </span>
                  </div>
                </div>
              </li>
              <li
                class="order-contacts-block"
                v-for="(address, adIndex) in order.address"
                :key="adIndex"
              >
                <h4>{{ $t(addressTypes[address.oaddr_type]) }}:</h4>
                <div class="address-info">
                  <p>{{ address.oaddr_name }},</p>
                  <p>{{ address.oaddr_address1 }},</p>
                  <p>
                    {{
                      address.oaddr_address2 ? address.oaddr_address2 + "," : ""
                    }}
                    {{ address.oaddr_city + ", " + address.oaddr_state }}
                    {{ address.oaddr_postal_code }},
                  </p>
                  <p>{{ address.oaddr_country }},</p>
                  <p class="c-info">
                    <strong>
                      <i class="icn">
                        <svg class="svg" width="16px" height="16px">
                          <use
                            :xlink:href="
                              baseUrl +
                              '/dashboard/media/retina/sprite.svg#phone'
                            "
                            :href="
                              baseUrl +
                              '/dashboard/media/retina/sprite.svg#phone'
                            "
                          />
                        </svg>
                      </i>
                      {{
                        address.country != null
                          ? "+" + address.country.country_phonecode
                          : ""
                      }}
                      {{ address.oaddr_phone }}
                    </strong>
                  </p>

                  <p
                    class="c-info"
                    v-if="
                      address.oaddr_type == 3 &&
                      JSON.parse(order.order_shipping_methods)
                    "
                  >
                    <strong>
                      <i class="icn">
                        <svg class="svg" width="16px" height="16px">
                          <use
                            :xlink:href="
                              baseUrl +
                              '/dashboard/media/retina/sprite.svg#calender'
                            "
                            :href="
                              baseUrl +
                              '/dashboard/media/retina/sprite.svg#calender'
                            "
                          />
                        </svg>
                      </i>
                      {{
                        JSON.parse(order.order_shipping_methods)["pick_ups"][
                          "pickup_date"
                        ]
                      }}
                    </strong>
                  </p>
                  <p
                    class="c-info"
                    v-if="
                      address.oaddr_type == 3 &&
                      JSON.parse(order.order_shipping_methods)
                    "
                  >
                    <strong>
                      <i class="icn">
                        <svg class="svg" width="16px" height="16px">
                          <use
                            :xlink:href="
                              baseUrl +
                              '/dashboard/media/retina/sprite.svg#clock'
                            "
                            :href="
                              baseUrl +
                              '/dashboard/media/retina/sprite.svg#clock'
                            "
                          />
                        </svg>
                      </i>
                      {{
                        JSON.parse(order.order_shipping_methods)["pick_ups"][
                          "pickup_time"
                        ]
                      }}
                    </strong>
                  </p>
                </div>
              </li>
              <li class="order-contacts-block" v-if="notes">
                <h4>{{ $t("LBL_ORDER_NOTES") }}:</h4>

                <div class="payment-mode">{{ notes }}</div>
              </li>
            </ul>
          </div>
        </div>
      </b-collapse>
    </div>
  </li>
</template>
<script>
import ratingStars from "@/common/ratingStars";
import CardIcons from "@/common/CardIcons";
import MessagesTimeLine from "@/buyerDashboard/Orders/MessagesTimeLine";
export default {
  components: {
    ratingStars: ratingStars,
    MessagesTimeLine: MessagesTimeLine,
    CardIcons: CardIcons,
  },
  props: [
    "order",
    "shippingTypes",
    "pickupStatus",
    "digitalStatus",
    "selectedTabs",
    "stateName",
    "index",
    "addressTypes",
    "trackingEnabled",
    "pickedStatus",
    "returnStatus",
    "shippedStatus",
  ],
  data: function () {
    return {
      sending: false,
      addToCart: false,
      page: 1,
      infiniteId: +new Date(),
      baseUrl: baseUrl,
      auth: window.auth,
      comment: "",
      messages: [],
      notes: "",
      pagination: {
        total: 0,
        per_page: 12,
        from: 1,
        to: 0,
        current_page: 1,
        last_page: 0,
      },
      giftMessage: {},
    };
  },
  methods: {
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
    cancelStatus: function (cancelRequest) {
      let type =
        cancelRequest.orrequest_type == 1
          ? this.$t("LBL_STATUS_RETURN")
          : this.$t("LBL_STATUS_CANCELLATION");
      let request =
        cancelRequest.orrequest_status == 0
          ? this.$t("LBL_STATUS_REQUESTED")
          : this.$camelCase(this.returnStatus[cancelRequest.orrequest_status]);
      return type + " " + request;
    },
    receivedConfirmation: function () {
      let formData = this.$serializeData({
        "order-id": this.order.order_id,
      });
      this.$axios
        .post(baseUrl + "/order/received/", formData)
        .then((response) => {
          this.order.additionInfo = { oainfo_received_confirmation: 1 };
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    infiniteHandler($state, orderId) {
      let formData = this.$serializeData({
        "record-id": orderId,
        type: this.selectedTabs,
      });
      this.$axios
        .post(
          baseUrl + "/user/orders/load-comments?page=" + this.page,
          formData
        )
        .then((response) => {
          if (response.data.data.data.length) {
            this.page += 1;
            this.messages.push(...response.data.data.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        });
    },
    loadComments: function (orderId, pageNo = 1) {
      this.page = 1;
      this.infiniteId += 1;
      let formData = this.$serializeData({
        "record-id": orderId,
        type: this.selectedTabs,
      });
      this.$axios
        .post(baseUrl + "/user/orders/load-comments?page=" + pageNo, formData)
        .then((response) => {
          this.messages = response.data.data.data;
          this.page += 1;
        });
    },
    cardNumber: function (number) {
      if (number.network) {
        return number.last4.substr(number.last4.length - 4);
      }
      return "";
    },
    isRequestExist: function (order) {
      let digital = true;
      if (
        order.digital_products == order.products.length &&
        this.$inArray(order.order_shipping_status, [1, 2]) == false
      ) {
        digital = false;
      }
      if (
        order.order_shipping_status != 3 &&
        order.order_status != 4 &&
        order.order_status != 3 &&
        order.products.length != order.return_products_count &&
        digital == true
      ) {
        return true;
      }
      return false;
    },
    currentShippingStatus: function (order) {
      let shipStatus = "";
      if (this.selectedTabs != "history") {
        if (order.order_shipping_type == 1) {
          shipStatus = this.pickedStatus[order.order_shipping_status];
        } else {
          if (order.digital_products == order.products.length) {
            shipStatus =
              this.digitalStatus[
                this.shippedStatus[order.order_shipping_status]
              ];
          } else {
            shipStatus = this.shippedStatus[order.order_shipping_status];
          }
        }
      } else {
        shipStatus = "completed";

        if (order.order_status != 4 && order.order_status != 3) {
          if (order.order_shipping_type == 1) {
            shipStatus = this.pickedStatus[order.order_shipping_status];
          } else {
            if (order.digital_products == order.products.length) {
              shipStatus =
                this.digitalStatus[
                  this.shippedStatus[order.order_shipping_status]
                ];
            } else {
              shipStatus = this.shippedStatus[order.order_shipping_status];
            }
          }
        } else if (order.order_status != 4 && order.order_status == 3) {
          if (this.$inArray(order.order_shipping_status, [1, 2])) {
            shipStatus = "cancelled";
          } else {
            shipStatus = "returned";
          }
        }
      }

      return shipStatus;
    },
    sentMessage: function (orderId) {
      if (this.comment == "") {
        return false;
      }
      let formData = this.$serializeData({
        "record-id": orderId,
        comment: this.comment,
        type: this.selectedTabs,
      });
      this.$axios
        .post(baseUrl + "/user/orders/comments", formData)
        .then((response) => {
          this.sending = false;
          this.comment = "";
          this.page = 1;
          this.infiniteId += 1;
          this.loadComments(orderId);
        });
    },

    updatePayment: function (order) {
      let formData = this.$serializeData({
        order_id: order.order_id,
      });
      this.$axios
        .post(baseUrl + "/order/payment-url", formData)
        .then((response) => {
          window.open(response.data.data.url, "_self");
        });
    },
    giftMessages: function () {
      this.giftMessage = {};
      let order = this.order;
      Object.keys(order.products).forEach((pkey) => {
        if (
          order.products[pkey].op_product_variants &&
          JSON.parse(order.products[pkey].op_product_variants).gift
        ) {
          this.giftMessage = JSON.parse(
            order.products[pkey].op_product_variants
          ).gift;
        }
      });
    },
  },
  mounted: function () {
    this.notes = this.order.order_notes;
    this.giftMessages();
  },
};
</script>
