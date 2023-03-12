<template>
  <li class="accordion" role="tablist">
    <div
      class="orders-head"
      block
      v-b-toggle="'accordion' + order.orrequest_id"
      @click="loadComments(order.orrequest_id)"
      variant="info"
    >
      <div class="col-label">
        <h3>
          <span>{{ $t("LBL_REQUEST_NUMBER") }}</span>
          #{{ order.order_id + "-" + order.orrequest_id }}
        </h3>
      </div>
      <div class="col-label">
        <h3>
          <span>{{ $t("LBL_ORDER_DATE") }}</span>
          <span class="date">
            {{ $dateTimeFormat(order.orrequest_date, "date") }}
            <time>{{ $dateTimeFormat(order.orrequest_date, "time") }}</time>
          </span>
        </h3>
      </div>
      <div class="col-label">
        <h3>
          <span>{{ $t("LBL_ORDERS_NUMBER") }}</span>
          #{{ order.order_id }}
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
                  returnStatus[order.orrequest_status]
              "
              :href="
                baseUrl +
                  '/dashboard/media/retina/sprite.svg#' +
                  returnStatus[order.orrequest_status]
              "
            />
          </svg>
          {{
          returnStatus[order.orrequest_status].replace("-", " ") | camelCase
          }}
        </h3>
      </div>
      <span class="triger"></span>
    </div>
    <div class="orders-body">
      <b-collapse :id="'accordion' + order.orrequest_id" accordion="my-accordion" role="tabpanel">
        <div class="order-blocks">
          <div class="order-blocks__left">
            <ul class="list-group list-cart list-cart-checkout scroll-y">
              <li
                class="list-group-item"
                v-bind:class="[order.cancelRequest ? 'product-return' : '']"
              >
                <div class="product-profile">
                  <div class="product-profile__thumbnail">
                    <a :href="$generateUrl(order.url_rewrite)">
                      <img
                        data-yk
                        :src="
                          $productImage(order.op_product_id, order.op_pov_code, order.images ? order.images.afile_updated_at : '', '38-51')
                        "
                        class="img-fluid"
                        data-ratio="3:4"
                      />
                    </a>
                  </div>
                  <div class="product-profile__data">
                    <div class="title">
                      <a :href="$generateUrl(order.url_rewrite)">{{ order.op_product_name }}</a>
                    </div>
                    <div class="options text-capitalize">
                      <p>
                        <span class="product-qty">{{$t('LBL_QTY')}}: {{ order.orrequest_qty }}</span>
                        <span
                          v-if="
                            order.op_product_variants &&
                              JSON.parse(order.op_product_variants).styles
                          "
                        >
                          {{
                          Object.values(
                          JSON.parse(order.op_product_variants).styles
                          ).join(" | ")
                          }}
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="product-price">{{ $priceFormat(order.op_product_price) }}</div>
              </li>
            </ul>

            <div class="cart-total mt-6">
              <ul class="list-group list-group-flush list-group-flush-x">
                <li class="list-group-item border-0">
                  <span class="label">{{ $t("LBL_SUBTOTAL") }}</span>
                  <span class="ml-auto">
                    {{
                    $priceFormat(order.op_product_price * order.orrequest_qty)
                    }}
                  </span>
                </li>

                <li class="list-group-item" v-if="order.oramount_tax != 0">
                  <span class="label">{{ $t("LBL_TAX") }}</span>
                  <span class="ml-auto">{{ $priceFormat(order.oramount_tax) }}</span>
                </li>
                <li
                  class="list-group-item"
                  v-if="
                    order.oramount_shipping != 0 &&
                      order.order_shipping_type != 1
                  "
                >
                  <span class="label">{{ $t("LBL_SHIPPING") }}</span>
                  <span class="ml-auto">{{ $priceFormat(order.oramount_shipping) }}</span>
                </li>
                <li class="list-group-item" v-if="order.oramount_discount != 0">
                  <span class="label">
                    {{ $t("LBL_DISCOUNT_COUPON") }}
                    <i
                      class="fas fa-info-circle"
                      v-b-tooltip.hover
                      :title="order.order_discount_coupon_code"
                    ></i>
                  </span>
                  <span class="ml-auto">-{{ $priceFormat(order.oramount_discount) }}</span>
                </li>
                <li class="list-group-item" v-if="order.oramount_giftwrap_price != 0 && order.oramount_giftwrap_price > 0">
                  <span class="label">{{ $t("LBL_GIFT_WRAP_AMOUNT") }}</span>
                  <span class="ml-auto">
                    {{
                    $priceFormat(Math.abs(order.oramount_giftwrap_price))
                    }}
                  </span>
                </li>
                <li class="list-group-item" v-if="order.oramount_reward_price != 0">
                  <span class="label">{{ $t("LBL_REWARD_POINTS_AMOUNT") }}</span>
                  <span class="ml-auto">
                    {{ order.oramount_reward_price > 0 ? "-" : ""
                    }}{{ $priceFormat(order.oramount_reward_price) }}
                  </span>
                </li>

                <li class="list-group-item hightlighted">
                  <span class="label">{{ $t("LBL_TOTAL") }}</span>
                  <span class="ml-auto">{{ $priceFormat(calculateTotal(order)) }}</span>
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
                  @submit.prevent="sentMessage(order.orrequest_id)"
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
                    <div class="input-group-append">
                      <button
                        type="button"
                        class="btn-fly"
                        @click="
                          sentMessage(order.orrequest_id), (sending = true)
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
                  </div>
                </form>
              </div>
              <div class="timelines-wrap scroll-y" infinite-wrapper>
                <ul class="timeline my-5" id="timeline" v-if="messages.length > 0">
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
                  @infinite="infiniteHandler($event, order.orrequest_id)"
                ></infinite-loading>
              </div>
            </div>
          </div>
          <div class="order-blocks__right">
            <ul class="order-contacts">
              <li
                class="order-contacts-block"
                v-if="order.orrequest_type == 1 && order.orrequest_status != 3"
              >
                <h4>{{ selectedTabs | camelCase }} Address:</h4>
                <div class="address-info">
                  <p>{{ $configVal("BUSINESS_NAME") }},</p>
                  <p>{{ $configVal("BUSINESS_ADDRESS1") }},</p>
                  <p>{{ $configVal("BUSINESS_ADDRESS2") + "," }}</p>
                  <p>{{ $configVal("BUSINESS_CITY") + "," + stateName }}</p>
                  <p>{{ countryName }}</p>
                </div>
              </li>
              <li class="order-contacts-block">
                <h4>
                  <i class="icn">
                    <svg class="svg" width="16px" height="16px">
                      <use
                        :xlink:href="
                          baseUrl +
                            '/dashboard/media/retina/sprite.svg#icon__documents'
                        "
                        :href="
                          baseUrl +
                            '/dashboard/media/retina/sprite.svg#icon__documents'
                        "
                      />
                    </svg>
                  </i>
                  {{ $t("LBL_GET_RETURN_DOCS") }}
                </h4>
                <div class="address-info">
                  <p>{{ $t("LBL_RETURN_DOCS_INSTRUCTIONS") }}</p>
                </div>
              </li>
              <li class="order-contacts-block">
                <h4>
                  <i class="icn">
                    <svg class="svg" width="16px" height="16px">
                      <use
                        :xlink:href="
                          baseUrl +
                            '/dashboard/media/retina/sprite.svg#icon__parsel'
                        "
                        :href="
                          baseUrl +
                            '/dashboard/media/retina/sprite.svg#icon__parsel'
                        "
                      />
                    </svg>
                  </i>
                  {{ $t("LBL_PREPARE_PARCEL") }}
                </h4>
                <div class="address-info">
                  <p>{{ $t("LBL_PREPARE_PARCEL_INSTRUCTIONS1") + ' ' + $configVal("BUSINESS_NAME") + ' ' + $configVal("BUSINESS_ADDRESS1") + ' ' + $configVal("BUSINESS_ADDRESS2") + ' ' + $configVal("BUSINESS_CITY") + ' ' + stateName + ' ' + countryName + '. ' + $t("LBL_PREPARE_PARCEL_INSTRUCTIONS2") }}</p>
                </div>
              </li>

              <li class="order-contacts-block">
                <h4>
                  <i class="icn">
                    <svg class="svg" width="16px" height="16px">
                      <use
                        :xlink:href="
                          baseUrl +
                            '/dashboard/media/retina/sprite.svg#shipping'
                        "
                        :href="
                          baseUrl +
                            '/dashboard/media/retina/sprite.svg#shipping'
                        "
                      />
                    </svg>
                  </i>
                  {{ $t("LBL_LEAVE_PARCEL") }}
                </h4>
                <div class="address-info">
                  <p>{{ $t("LBL_PREPARE_PARCEL_INSTRUCTIONS1") + ' ' + $configVal("BUSINESS_NAME") + ' ' + $configVal("BUSINESS_ADDRESS1") + ' ' + $configVal("BUSINESS_ADDRESS2") + ' ' + $configVal("BUSINESS_CITY") + ' ' + stateName + ' ' + countryName + '. ' + $t("LBL_PREPARE_PARCEL_INSTRUCTIONS2") }}</p>
                </div>
              </li>
              <li class="order-contacts-block" v-if="order.bank_information">
                <h4>
                  <i class="icn">
                    <svg class="svg" width="16px" height="16px">
                      <use
                        :xlink:href="
                          baseUrl +
                            '/dashboard/media/retina/sprite.svg#icon__parsel'
                        "
                        :href="
                          baseUrl +
                            '/dashboard/media/retina/sprite.svg#icon__parsel'
                        "
                      />
                    </svg>
                  </i>
                  Bank Information
                </h4>
                <div class="address-info">
                  <p class="list-text">
                    <strong>{{ $t("LBL_BANK_NAME") }}:</strong>
                    {{ order.bank_information.orbinfo_name }}
                  </p>
                  <p class="list-text">
                    <strong>{{ $t("LBL_BRANCH_NAME") }}:</strong>
                    {{ order.bank_information.orbinfo_branch }}
                  </p>

                  <p class="list-text">
                    <strong>{{ $t("LBL_ACCOUNT_NAME") }}:</strong>
                    {{ order.bank_information.orbinfo_account_name }}
                  </p>
                  <p class="list-text">
                    <strong>{{ $t("LBL_ACCOUNT_NUMBER") }}:</strong>
                    {{ order.bank_information.orbinfo_account_number }}
                  </p>
                  <p class="list-text">
                    <strong>{{ $t("LBL_BRANCH_CODE") }}:</strong>
                    {{ order.bank_information.orbinfo_branch_code }}
                  </p>
                  <p class="list-text" v-if="order.bank_information.orbinfo_notes">
                    <strong>{{ $t("LBL_NOTES") }}:</strong>
                    {{ order.bank_information.orbinfo_notes }}
                  </p>
                </div>
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
import MessagesTimeLine from "@/buyerDashboard/Orders/MessagesTimeLine";
export default {
  components: {
    ratingStars: ratingStars,
    MessagesTimeLine: MessagesTimeLine
  },
  props: [
    "order",
    "shippingTypes",
    "selectedTabs",
    "stateName",
    "countryName",
    "index",
    "addressTypes",
    "trackingEnabled",
    "returnStatus"
  ],
  data: function() {
    return {
      sending: false,
      page: 1,
      infiniteId: +new Date(),
      baseUrl: baseUrl,
      auth: window.auth,
      comment: "",
      messages: [],
      notes: "",
      giftMessage: {},
      pagination: {
        total: 0,
        per_page: 12,
        from: 1,
        to: 0,
        current_page: 1,
        last_page: 0
      }
    };
  },
  methods: {
    updateNote: function() {
      this.note = this.notes.trim();
      let formData = this.$serializeData({
        notes: this.notes
      });
      this.$axios
        .post(baseUrl + "/order/notes/" + this.order.order_id, formData)
        .then(response => {
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    receivedConfirmation: function() {
      let formData = this.$serializeData({
        "order-id": this.order.order_id
      });
      this.$axios
        .post(baseUrl + "/order/received/", formData)
        .then(response => {
          this.order.additionInfo = { oainfo_received_confirmation: 1 };
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    infiniteHandler($state, orderId) {
      let formData = this.$serializeData({
        "record-id": orderId,
        type: this.selectedTabs
      });

      this.$axios
        .post(
          baseUrl + "/user/orders/load-comments?page=" + this.page,
          formData
        )
        .then(response => {
          if (response.data.data.length) {
            this.page += 1;
            this.messages.push(...response.data.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        });
    },
    loadComments: function(orderId, pageNo = 1) {
      this.page = 1;
      this.infiniteId += 1;
      let formData = this.$serializeData({
        "record-id": orderId,
        type: this.selectedTabs
      });
      this.$axios
        .post(baseUrl + "/user/orders/load-comments?page=" + pageNo, formData)
        .then(response => {
          this.page += 1;
          this.messages = response.data.data.data;
        });
    },

    sentMessage: function(orderId) {
      if (this.comment == "") {
        return false;
      }
      let formData = this.$serializeData({
        "record-id": orderId,
        comment: this.comment,
        type: this.selectedTabs
      });
      this.$axios
        .post(baseUrl + "/user/orders/comments", formData)
        .then(response => {
          this.sending = false;
          this.comment = "";
          this.page = 1;
          this.infiniteId += 1;
          this.loadComments(orderId);
        });
    },
    calculateTotal: function(order) {
      let subTotal = order.op_product_price * order.orrequest_qty;
      let total =
        parseFloat(subTotal) +
        parseFloat(order.oramount_tax) +
        parseFloat(order.oramount_shipping) -
        parseFloat(order.oramount_discount) -
        parseFloat(order.oramount_reward_price);

      if (
        order.oramount_giftwrap_price != 0 &&
        order.oramount_giftwrap_price > 0
      ) {
       
        total = parseFloat(total) + parseFloat(order.oramount_giftwrap_price);
      }
      return total;
    }
  },
  mounted: function() {
    if (this.index == 0) {
      this.loadComments(this.order.orrequest_id);
    }
  }
};
</script>
