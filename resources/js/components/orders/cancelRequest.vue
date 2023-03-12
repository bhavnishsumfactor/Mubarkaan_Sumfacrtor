<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">
            {{ $t("LBL_ORDER") }} {{ reasonType }}
          </h3>
          <div class="subheader__breadcrumbs">
            <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{
              $t("LBL_ORDERS")
            }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="portlet">
            <div class="portlet__head align-items-center">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  {{ $t("LBL_ORDER") }} {{ reasonType }}
                </h3>
              </div>
              <p>
                {{ $t("LBL_ORDERS_NUMBER") }}:
                <strong>#{{ orders.order_id }}</strong>
              </p>
            </div>

            <div class="portlet__body">
              <ul
                class="list-group list-group-xs list-cart list-cart-order list-add-orders mb-5"
              >
                <li
                  class="list-group-item order-cancel-item"
                  v-for="(product, productKey) in orders.products"
                  :key="productKey"
                >
                  <div class="product-profile">
                    <div class="product-profile__thumbnail">
                      <a href="javascript:;">
                        <img
                          :src="
                            $productImage(
                              product.op_product_id,
                              product.op_pov_code, product.images ? $timestamp(product.images.afile_updated_at) : '', '39-52'
                            )
                          "
                          alt="..."
                          class="img-fluid"
                        />
                      </a>
                    </div>
                    <div class="product-profile__data">
                      <div class="title">
                        <a class="text-body" href="javascript:;">{{
                          product.op_product_name
                        }}</a>
                      </div>
                      <p class="options" v-if="product.op_product_variants">
                        <span
                          v-if="JSON.parse(product.op_product_variants).styles"
                          >{{
                            Object.values(
                              JSON.parse(product.op_product_variants).styles
                            ).join(" | ")
                          }}</span
                        >
                      </p>
                      <div
                        class="product-quantity mt-2"
                        v-if="product.cancel_request === null"
                      >
                        <div class="quantity quantity-2 d-inline-flex">
                          <span
                            class="decrease"
                            v-bind:class="
                              product.selectedQty == 0 ? 'disabled' : ''
                            "
                            @click="updateQty(product, 'less')"
                          >
                            <i class="icn">
                              <svg class="svg">
                                <use
                                  :xlink:href="
                                    baseUrl +
                                      '/dashboard/media/retina/sprite.svg#minus'
                                  "
                                  :href="
                                    baseUrl +
                                      '/dashboard/media/retina/sprite.svg#minus'
                                  "
                                ></use>
                              </svg>
                            </i>
                          </span>
                          <input
                            class="qty-input no-focus YK-qty"
                            data-page="product-view"
                            title="Available Quantity"
                            v-model="product.selectedQty"
                            type="text"
                            min="0"
                            :max="product.op_qty"
                            value="0"
                            readonly
                          />
                          <span
                            class="increase"
                            v-bind:class="
                              product.selectedQty == product.op_qty
                                ? 'disabled'
                                : ''
                            "
                            @click="updateQty(product, 'add')"
                          >
                            <i class="icn">
                              <svg class="svg">
                                <use
                                  :xlink:href="
                                    baseUrl +
                                      '/dashboard/media/retina/sprite.svg#add'
                                  "
                                  :href="
                                    baseUrl +
                                      '/dashboard/media/retina/sprite.svg#add'
                                  "
                                ></use>
                              </svg>
                            </i>
                          </span>
                        </div>

                        <span>x</span>
                        {{ currencySymbol
                        }}{{ product.op_product_price | numberFormat }}
                        <span
                          v-if="errors.first('selectedQty')"
                          class="error text-danger"
                          >{{ errors.first("selectedQty") }}</span
                        >
                      </div>
                    </div>
                  </div>

                  <div class="field-wrap-colm" v-if="product.cancel_request">
                    {{ $t("LBL_REQUEST_IS") }}
                    {{
                      orderReturnStatus[
                        product.cancel_request.orrequest_status
                      ]
                        | removeHyphen
                        | camelCase
                    }}
                  </div>
                  <div class="field-wrap-colm" v-else>
                    <select
                      class="form-control"
                      v-model="product.resaon"
                      :disabled="product.selectedQty == 0"
                    >
                      <option
                        v-for="(retrunReason, retrunKey) in retrunReasons"
                        :key="retrunKey"
                        :value="retrunReason.reason_id"
                        >{{ retrunReason.reason_title }}</option
                      >
                      <option :value="'others'">Others</option>
                    </select>
                  </div>
                  <div
                    class="product-price font-weight-bold"
                    v-if="product.cancel_request === null"
                  >
                    {{ currencySymbol }}
                    <span>{{
                      (product.op_product_price * product.selectedQty)
                        | numberFormat
                    }}</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="portlet">
            <div class="portlet__head">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">{{ $t("LBL_TOTAL") }}</h3>
              </div>
            </div>
            <div class="portlet__body">
              <div class="pb-5">
                <ul
                  class="list-group list-group-sm list-group-flush-y list-group-flush-x"
                >
                  <li class="list-group-item d-flex">
                    <span>{{ $t("LBL_SUBTOTAL") }}</span>
                    <span class="ml-auto"
                      >{{ currencySymbol }}{{ subTotal | numberFormat }}</span
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
                        orders.digital_products != orders.products.length
                    "
                  >
                    <span>{{ $t("LBL_SHIPPING") }}</span>
                    <span class="ml-auto">
                      <span
                        >{{ currencySymbol }}{{ shipping | numberFormat }}</span
                      >
                    </span>
                  </li>

                  <li class="list-group-item d-flex" v-if="discount != 0">
                    <span>{{ $t("LBL_DISCOUNT") }}</span>
                    <span class="ml-auto">
                      <span
                        >- {{ currencySymbol
                        }}{{ discount | numberFormat }}</span
                      >
                    </span>
                  </li>
                  <li class="list-group-item d-flex" v-if="reward != 0">
                    <span>{{ $t("LBL_REWARD_AMOUNT") }}</span>
                    <span class="ml-auto">
                      <span
                        >- {{ currencySymbol }}{{ reward | numberFormat }}</span
                      >
                    </span>
                  </li>
                  <li class="list-group-item d-flex" v-if="gift != 0">
                    <span>{{ $t("LBL_GIFT") }}</span>
                    <span class="ml-auto">
                      <span>
                        <span v-if="orders.order_shipping_status != 1">-</span>
                        {{ currencySymbol }}{{ gift | numberFormat }}
                      </span>
                    </span>
                  </li>

                  <li
                    class="list-group-item d-flex font-size-lg font-weight-bold"
                  >
                    <span>{{ $t("LBL_TOTAL") }}</span>
                    <span class="ml-auto"
                      >{{ currencySymbol }}{{ total | numberFormat }}</span
                    >
                  </li>
                </ul>
              </div>
              <h6 class="pb-2 text-center">
                {{ reasonType }} {{ $t("LBL_BY") }}
                {{ new Date() | formatDate }}
              </h6>

              <div class="form-group">
                <textarea
                  class="form-control"
                  name
                  id
                  cols="20"
                  rows="4"
                  v-model="comment"
                  spellcheck="false"
                ></textarea>
              </div>
              <div class="form-group">
                <button
                  class="btn btn-brand btn-block"
                  type="button"
                  :disabled="subTotal == 0"
                  @click="generateRequest()"
                >
                  {{ $t("LBL_CONFIRM_YOUR") }} {{ reasonType }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal
      id="declinedModel"
      centered
      title="BootstrapVue"
      no-close-on-backdrop
    >
      <template v-slot:modal-header="{ close }">
        <h5 class="modal-title">
          <span v-if="statusData">{{
            statusData.status | removeHyphen | camelCase
          }}</span>
          {{ $t("LBL_MESSAGE") }}
        </h5>
        <button type="button" class="close" @click="close()"></button>
      </template>
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group">
            <label>{{ $t("LBL_COMMENT") }}</label>
            <textarea
              name="message"
              v-model="commentData.message"
              class="form-control"
              spellcheck="false"
              v-validate="'required'"
              data-vv-validate-on="none"
            ></textarea>
            <span v-if="errors.first('message')" class="error text-danger">{{
              errors.first("message")
            }}</span>
          </div>
        </div>
      </div>
      <template v-slot:modal-footer>
        <button
          type="button"
          class="btn btn-brand"
          @click="validateShippingStatus"
        >
          {{ $t("BTN_ORDER_SAVE") }}
        </button>
      </template>
    </b-modal>
  </div>
</template>
<script>
const tableFields = {
  payment_comment: "",
  payment_method: "",
  transaction_id: "",
  amount: "",
};
const commentsFields = {
  message: "",
  tracking_number: "",
  courier_name: "",
};
export default {
  data: function() {
    return {
      currencySymbol: currencySymbol,
      orders: [],
      baseUrl: baseUrl,
      reasonType: "",
      adminsData: tableFields,
      commentData: commentsFields,
      orderReturnStatus: [],
      retrunReasons: [],
      readonly: false,
      statusData: {},
      comment: "",
      subTotal: 0,
      declinedModelId: "declinedModel",
      productDiscountCount: 0,
      discount: 0,
      selectedStatus: 0,
      tax: 0,
      totalProducts: 0,
      gift: 0,
      shipping: 0,
      total: 0,
      reward: 0,
      returnShipping: 0,
    };
  },
  methods: {
    updateQty: function(product, type) {
      if (type == "add") {
        if (product.op_qty == product.selectedQty) {
          return;
        }
        product.selectedQty = product.selectedQty + 1;
      } else {
        if (product.selectedQty == 0) {
          return;
        }
        product.selectedQty = product.selectedQty - 1;
      }
      this.update();
    },
    pageLoadData: function(id) {
      this.$http
        .post(adminBaseUrl + "/orders/request-details/" + id)
        .then((response) => {
          let data = response.data.data;
          this.orders = data.order;
          this.orderReturnStatus = data.orderReturnStatus;
          this.reasonType = data.reasonType;
          this.retrunReasons = data.retrunReasons;
          this.returnShipping = data.returnShipping;
          this.productDiscountCount = data.productDiscountCount;
          this.totalProducts = this.orders.products.length;
          let resaonId = 0;
          if (data.retrunReasons.length != 0) {
            resaonId = data.retrunReasons[0].reason_id;
          }
          Object.keys(this.orders.products).forEach((key) => {
            this.orders.products[key].selectedQty = 0;
            this.orders.products[key].resaon = resaonId;
          });
        });
    },
    generateRequest: function() {
      let selectedQty = 0;
      let returnItems = [];
      Object.keys(this.orders.products).forEach((key) => {
        if (this.orders.products[key].selectedQty != 0) {
          returnItems.push({
            id: this.orders.products[key].op_id,
            qty: this.orders.products[key].selectedQty,
            reason: this.orders.products[key].resaon,
          });
        }

        selectedQty += this.orders.products[key].selectedQty;
      });

      if (selectedQty == 0) {
        toastr.error(this.$t("NOTI_PLEASE_ENTER_QTY"), "", toastr.options);
        return;
      }

      let formData = this.$serializeData({
        items: JSON.stringify(returnItems),
        message: this.commentData.message,
        tracking_number: this.commentData.tracking_number,
        courier_name: this.commentData.courier_name,
        comment: this.comment,
        shipping: this.shipping,
        "order-id": this.orders.order_id,
        "selected-status": "apporved",
      });
      this.$http
        .post(adminBaseUrl + "/orders/create-request", formData)
        .then((response) => {
          this.$router.push({
            name: "orders",
          });
        });
    },
    validateShippingStatus: function() {
      this.$validator.validateAll(this.commentData).then((res) => {
        if (res) {
          this.selectedStatus = this.statusData.id;
          this.$bvModal.hide(this.declinedModelId);
        }
      });
    },

    updateOrderStatus: function(id, status) {
      this.statusData = {
        id: id,
        status: status,
      };

      this.$bvModal.show(this.declinedModelId);
    },
    inArray: function(needle, haystack) {
      var length = haystack.length;
      for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
      }
      return false;
    },
    update: function() {
      let subTotal = 0;
      let tax = 0;
      let totalTax = 0;
      let orderSubtotal = 0;
      let shipping = this.orders.order_shipping_value;
      let discount = 0;
      let maxQty = 0;
      let selectedQty = 0;
      let reward = 0;
      let gift = 0;
      this.reward = 0;
      let rewardPrice = 0;
      let discountAmount = 0;
      let productSubTotal = this.productSubTotal(this.orders);
      Object.keys(this.orders.products).forEach((key) => {
        if (this.orders.products[key].selectedQty != 0) {
          orderSubtotal =
            parseFloat(this.$orderSubTotal(this.orders)) +
            parseFloat(this.orders.order_tax_charged);
          if (
            this.orders.products[key].tax &&
            this.orders.products[key].tax.opc_type == "tax"
          ) {
            totalTax = this.orders.products[key].tax.opc_value;
            tax +=
              parseFloat(this.orders.products[key].tax.opc_value) *
              this.orders.products[key].selectedQty;
          }
          if (this.orders.order_discount_amount != 0) {
            if (this.orders.order_discount_amount != 0) {
              discountAmount = this.orders.products[key]
                .opainfo_discount_amount;
            }
          }
          if (this.orders.order_reward_amount != 0) {
            rewardPrice =
              (this.orders.order_net_amount *
                this.orders.products[key].opainfo_reward_rate) /
              100 /
              this.orders.products[key].op_qty;
          }

          subTotal +=
            this.orders.products[key].selectedQty *
            this.orders.products[key].op_product_price;

          if (
            this.orders.order_gift_amount &&
            this.orders.products[key].opainfo_gift_amount
          ) {
            gift += parseFloat(
              this.orders.products[key].opainfo_gift_amount *
                this.orders.products[key].selectedQty
            );
          }

          reward += parseFloat(
            rewardPrice * this.orders.products[key].selectedQty
          );
          discount += parseFloat(
            discountAmount * this.orders.products[key].selectedQty
          );
        }
        maxQty += parseFloat(this.orders.products[key].op_qty);
        selectedQty += parseFloat(this.orders.products[key].selectedQty);
      });
      if (
        maxQty != selectedQty ||
        (this.$inArray(this.orders.order_shipping_status, [1, 2]) != true &&
          this.$configVal("RETURN_SHIPPING_ENABLE") == 0)
      ) {
        shipping = 0;
      }

      this.subTotal = subTotal;
      this.tax = tax;
      this.shipping = shipping;
      this.gift = gift;
      this.discount = discount;
      let total =
        parseFloat(subTotal) +
        parseFloat(tax) +
        parseFloat(shipping) -
        discount;

      if (this.orders.order_shipping_status == 1) {
        total = parseFloat(total) + parseFloat(gift);
      }
      if (total < 0) {
        total = 0;
      }
      this.total = total;
      if (this.orders.order_reward_amount != 0) {
        this.total = reward;
        this.reward = total - reward > 0 ? parseFloat(total - reward) : 0;
      }
    },
    productSubTotal: function(order) {
      let subTotal = 0;
      Object.keys(order.products).forEach((key) => {
        subTotal += parseFloat(
          order.products[key].op_product_price * order.products[key].op_qty
        );
      });
      return subTotal;
    },
  },
  mounted: function() {
    let id = this.$route.params.id;
    this.pageLoadData(id);
  },
};
</script>
