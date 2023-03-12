<template>
  <div class="body bg-dashboard" id="body" data-yk>
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <form class="form" id="YK-cancelRequest">
                  <div class="row no-gutters">
                    <div class="col-md-8">
                      <div class="card card-orders">
                        <div class="card-head pb-0">
                          <h6 class="m-0">
                            {{
                              $inArray(order.order_shipping_status, [1, 2])
                                ? $t("LBL_ORDER_CANCELLATION")
                                : $t("LBL_RETURN_ORDER")
                            }}
                          </h6>
                          <p>
                            {{ $t("LBL_ORDERS_NUMBER") }}:
                            <strong>#{{ order.order_id }}</strong>
                          </p>
                        </div>
                        <div class="card-body">
                          <div class="scroll-y" style="max-height:600px;">
                            <ul class="list-group list-cart list-cart-return">
                              <li
                                class="list-group-item"
                                v-for="(product, pKey) in order.products"
                                :key="pKey"
                              >
                                <div class="row">
                                  <div class="col-12 col-md-6 product-profile">
                                    <div class="product-profile__thumbnail">
                                      <a
                                        :href="
                                          $generateUrl(product.url_rewrite)
                                        "
                                      >
                                        <img
                                          data-yk
                                          class="img-fluid"
                                          data-ratio="3:4"
                                          :src="
                                            $productImage(
                                              product.op_product_id,
                                              product.op_pov_code, product.images ? product.images.afile_updated_at : '', '68-91'
                                            )
                                          "
                                          alt="..."
                                        />
                                      </a>
                                    </div>
                                    <div class="product-profile__data">
                                      <div class="title">
                                        <a
                                          class
                                          :href="
                                            $generateUrl(product.url_rewrite)
                                          "
                                        >
                                          {{ product.op_product_name }}
                                        </a>
                                      </div>
                                      <div
                                        class="options text-capitalize"
                                        v-if="product.op_product_variants"
                                      >
                                        <p
                                          v-if="
                                            JSON.parse(
                                              product.op_product_variants
                                            ).styles
                                          "
                                        >
                                          {{
                                            Object.values(
                                              JSON.parse(
                                                product.op_product_variants
                                              ).styles
                                            ).join(" | ")
                                          }}
                                        </p>
                                      </div>

                                      <div
                                        class="product-price"
                                        v-if="
                                          $returnVerify(
                                            order.order_shipping_status,
                                            order.order_date_updated,
                                            product,
                                            requestStatus
                                          ).status == true
                                        "
                                      >
                                        {{
                                          $priceFormat(
                                            product.op_product_price *
                                              product.selectedQty
                                          )
                                        }}
                                      </div>
                                    </div>
                                  </div>

                                  <div
                                    class="col-12 col-md-6"
                                    v-if="
                                      $returnVerify(
                                        order.order_shipping_status,
                                        order.order_date_updated,
                                        product,
                                        requestStatus
                                      ).status == true
                                    "
                                  >
                                    <div
                                      class="product-quantity product-quantity-price YK-quantity"
                                    >
                                      <div class="quantity quantity-2">
                                        <span
                                          class="decrease"
                                          v-bind:class="
                                            product.selectedQty == 0
                                              ? 'disabled'
                                              : ''
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
                                          :title="$t('PLH_AVAILABLE_QUANTITY')"
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
                                            product.selectedQty ==
                                            product.op_qty
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
                                      <span class="side-price">
                                        x
                                        {{
                                          $priceFormat(product.op_product_price)
                                        }}
                                      </span>
                                    </div>
                                    <select
                                      class="form-control custom-select YK-selectReason"
                                      v-model="product.reason"
                                      :disabled="product.selectedQty == 0"
                                      @change="updateCalculation()"
                                    >
                                      <option
                                        :value="reason.reason_id"
                                        v-for="(reason,
                                        rIndex) in retrunReasons"
                                        :key="rIndex"
                                        >{{ reason.reason_title }}</option
                                      >

                                      <option value="other">{{
                                        $t("LBL_OTHER")
                                      }}</option>
                                    </select>
                                  </div>
                                  <div v-else>
                                    {{
                                      $returnVerify(
                                        order.order_shipping_status,
                                        order.order_date_updated,
                                        product,
                                        requestStatus
                                      ).message
                                    }}
                                  </div>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="sticky-summary">
                        <div
                          class="card"
                          v-if="
                            (order.order_payment_method == 'cod' &&
                              order.payment_status == 2 &&
                              order.transaction) ||
                              (order.transaction &&
                                $inArray(order.transaction.txn_gateway_type, [
                                  'cash',
                                  'card',
                                ]))
                          "
                        >
                          <span
                            v-if="
                              bankDetailInfo !== '' &&
                                bankDetailInfo.orbinfo_name
                            "
                          >
                            <div class="card__header">
                              <h5 class="card__title">
                                {{ $t("LBL_BANK_DETAIL") }}
                              </h5>
                              <button
                                class="btn btn-outline-brand btn-sm"
                                type="button"
                                @click="$bvModal.show('bankInfo')"
                              >
                                {{ $t("BTN_EDIT") }}
                              </button>
                            </div>

                            <div class="card__section pt-0">
                              <ul class="list-view">
                                <li>
                                  <span class="lable"
                                    >{{ $t("LBL_BANK_NAME") }}:</span
                                  >
                                  {{ bankDetailInfo.orbinfo_name }}
                                </li>
                                <li>
                                  <span class="lable"
                                    >{{ $t("LBL_BRANCH_NAME") }}:</span
                                  >
                                  {{ bankDetailInfo.orbinfo_branch }}
                                </li>
                                <li>
                                  <span class="lable"
                                    >{{ $t("LBL_ACCOUNT_NAME") }}:</span
                                  >
                                  {{ bankDetailInfo.orbinfo_account_name }}
                                </li>
                                <li>
                                  <span class="lable"
                                    >{{ $t("LBL_ACCOUNT_NUMBER") }}:</span
                                  >
                                  {{ bankDetailInfo.orbinfo_account_number }}
                                </li>
                                <li>
                                  <span class="lable"
                                    >{{ $t("LBL_BRANCH_CODE") }} :</span
                                  >
                                  {{ bankDetailInfo.orbinfo_branch_code }}
                                </li>
                                <li v-if="bankDetailInfo.orbinfo_notes">
                                  <span class="lable"
                                    >{{ $t("LBL_NOTE") }}:</span
                                  >
                                  {{ bankDetailInfo.orbinfo_notes }}
                                </li>
                              </ul>
                            </div>
                          </span>
                          <div class="card__section" v-else>
                            <button
                              class="btn btn-outline-brand btn-block"
                              type="button"
                              @click="$bvModal.show('bankInfo')"
                            >
                              <i class="fas fa-plus"></i>
                              {{ $t("BTN_ADD_BANK_ACCOUNT") }}
                            </button>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card__section">
                            <div class="cart-total">
                              <ul class="list-group">
                                <li class="list-group-item border-0">
                                  <span class="label">
                                    {{ $t("LBL_SUBTOTAL") }}
                                  </span>
                                  <span class="ml-auto">{{
                                    $priceFormat(subTotal)
                                  }}</span>
                                </li>
                                <li class="list-group-item">
                                  <span class="label">{{ $t("LBL_TAX") }}</span>
                                  <span class="ml-auto">{{
                                    $priceFormat(tax)
                                  }}</span>
                                </li>

                                <li
                                  class="list-group-item"
                                  v-if="
                                    order.order_shipping_type != 1 &&
                                      order.digital_products !=
                                        order.products.length
                                  "
                                >
                                  <span class="label">
                                    {{ $t("LBL_SHIPPING") }}
                                  </span>
                                  <span class="ml-auto">{{
                                    $priceFormat(shipping)
                                  }}</span>
                                </li>
                                <li class="list-group-item">
                                  <span class="label">
                                    {{ $t("LBL_DISCOUNT") }}
                                  </span>
                                  <span class="ml-auto"
                                    >{{ discount != 0 ? "-" : ""
                                    }}{{ $priceFormat(discount) }}</span
                                  >
                                </li>
                                <li
                                  class="list-group-item"
                                  v-if="
                                    order.order_gift_amount != 0 && gift != 0 && order.order_shipping_status == 1 
                                  "
                                >
                                  <span class="label">
                                    {{ $t("LBL_GIFT") }}
                                  </span>
                                  <span class="ml-auto">
                                    {{ $priceFormat(gift) }}
                                  </span>
                                </li>
                                <li
                                  class="list-group-item"
                                  v-if="$configVal('REWARD_POINTS_ENABLE') == 1"
                                >
                                  <span class="label">
                                    {{ $t("LBL_REWARD_AMOUNT") }}
                                  </span>
                                  <span class="ml-auto"
                                    >{{ reward != 0 ? "-" : ""
                                    }}{{ $priceFormat(reward) }}</span
                                  >
                                </li>

                                <li class="list-group-item">
                                  <span class="label">
                                    {{ $t("LBL_TOTAL") }}
                                  </span>
                                  <span class="ml-auto">{{
                                    $priceFormat(total)
                                  }}</span>
                                </li>
                                <li class="list-group-item hightlighted">
                                  <span class="label">
                                    {{ $t("LBL_TOTAL_ESTIMATED_REFUND") }}
                                  </span>
                                  <span class="ml-auto">
                                    {{
                                      order.payment_status == 2
                                        ? $priceFormat(total)
                                        : $priceFormat(0)
                                    }}
                                  </span>
                                </li>
                              </ul>

                              <p class="included">
                                {{
                                  $inArray(order.order_shipping_status, [1, 2])
                                    ? $t("LBL_ORDER_CANCELLATION")
                                    : "Return"
                                }}
                                By
                                {{
                                  $dateTimeFormat(
                                    new Date()
                                      .toJSON()
                                      .slice(0, 10)
                                      .replace(/-/g, "/"),
                                    "date"
                                  )
                                }}
                              </p>
                              <div class="form">
                                <div class="form-group">
                                  <label>
                                    {{ $t("LBL_ADDITIONAL_INFORMATION") }}
                                  </label>

                                  <textarea
                                    class="form-control"
                                    v-model="comment"
                                    cols="30"
                                    rows="10"
                                    spellcheck="false"
                                    v-bind:class="[
                                      comment != '' ? 'filled' : '',
                                    ]"
                                  ></textarea>
                                </div>
                              </div>

                              <div class="form-group">
                                <button
                                  class="btn btn-pd-0 btn-brand btn-block gb-btn gb-btn-primary"
                                  type="button"
                                  v-bind:class="[
                                    sending == true ? 'gb-is-loading' : '',
                                  ]"
                                  name="YK-returnProcessBtn"
                                  @click="generateRequest(), (sending = true)"
                                  :disabled="validate()"
                                >
                                  {{
                                    $t("BTN_CONFIRM_YOUR_REQUEST") +
                                      " " +
                                      reasonTypes[reasonType]
                                  }}
                                </button>
                              </div>
                              <div class="text-center py-1">
                                <inertia-link
                                  class="link-text"
                                  :href="baseUrl + '/user/orders'"
                                >
                                  <i class="icn">
                                    <svg class="svg">
                                      <use
                                        :xlink:href="
                                          baseUrl +
                                            '/dashboard/media/retina/sprite.svg#arrow-left'
                                        "
                                        :href="
                                          baseUrl +
                                            '/dashboard/media/retina/sprite.svg#arrow-left'
                                        "
                                      />
                                    </svg>
                                  </i>
                                  {{ $t("LNK_BACK_TO_ORDERS") }}
                                </inertia-link>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </main>
            </div>
          </div>
          <bank-information-form
            :bankDetailInfo="bankDetailInfo"
            @addBankDetails="addBankDetails"
          ></bank-information-form>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import leftSidebar from "@/buyerDashboard/Sidebar";
import dasboardHeader from "@/buyerDashboard/Header";
import BankInformationForm from "@/buyerDashboard/Orders/BankInformationForm";
export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader,
    BankInformationForm: BankInformationForm,
  },
  props: [
    "order",
    "paymentTypes",
    "returnStatus",
    "addressTypes",
    "reasonType",
    "reasonTypes",
    "requestStatus",
    "retrunReasons",
    "productDiscountCount",
    "bankDetails",
  ],
  data: function() {
    return {
      baseUrl: baseUrl,
      sending: false,
      tax: 0,
      gift: 0,
      shipping: 0,
      total: 0,
      reward: 0,
      subTotal: 0,
      discount: 0,
      bankDetailInfo: [],
      selectedReason: [],
      comment: "",
    };
  },
  methods: {
    validate: function() {
      if (this.subTotal == 0) {
        return true;
      }
      if (
        (this.order.order_payment_method == "cod" &&
          this.order.payment_status == 2 &&
          this.order.transaction) ||
        (this.order.transaction &&
          this.$inArray(this.order.transaction.txn_gateway_type, [
            "cash",
            "card",
          ]))
      ) {
        if (this.bankDetailInfo == "") {
          return true;
        }
      }
      // if (this.$inArray(true, this.selectedReason) == true) {
      //   return true;
      // }
      return false;
    },
    generateRequest: function() {
      let formData = this.$serializeData({ comment: this.comment });
      formData.append("items", JSON.stringify(this.order.products));
      formData.append("bank", JSON.stringify(this.bankDetailInfo));
      formData.append("shipping", this.shipping);
      formData.append("type", this.reasonType);
      formData.append("order-id", this.order.order_id);
      this.$inertia.post(baseUrl + "/user/order/return-items", formData, {
        onFinish: () => (this.sending = false),
      });
    },
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
      this.updateCalculation();
      this.$forceUpdate();
    },
    updateCalculation: function() {
      let subTotal = 0;
      let tax = 0;
      let totalTax = 0;
      let productTax = 0;
      this.reward = 0;
      let shipping = this.order.order_shipping_value;
      let discount = 0;
      let maxQty = 0;
      let selectedQty = 0;
      let reward = 0;
      let gift = 0;
      let rewardPrice = 0;
      let discountAmount = 0;
      let selectedReason = [];

      Object.keys(this.order.products).forEach((key) => {
        if (this.order.products[key].selectedQty != 0) {
          if (this.order.products[key].reason == "other") {
            selectedReason.push(true);
          }
          subTotal +=
            this.order.products[key].selectedQty *
            this.order.products[key].op_product_price;
          if (
            this.order.products[key].tax &&
            this.order.products[key].tax.opc_type == "tax"
          ) {
            totalTax =
              parseFloat(this.order.products[key].tax.opc_value) *
              this.order.products[key].selectedQty;
            productTax =
              parseFloat(this.order.products[key].tax.opc_value) *
              this.order.products[key].op_qty;
          }
          tax += totalTax;
          if (this.order.order_discount_amount != 0) {
            discountAmount = this.order.products[key].opainfo_discount_amount;
          }
          if (this.order.order_reward_amount != 0) {
            rewardPrice =
              (this.order.order_net_amount *
                this.order.products[key].opainfo_reward_rate) /
              100 /
              this.order.products[key].op_qty;
          }

          if (
            this.order.order_gift_amount != 0 &&
            this.order.products[key].opainfo_gift_amount
          ) {
            gift += parseFloat(
              this.order.products[key].opainfo_gift_amount *
                this.order.products[key].selectedQty
            );
          }
          reward += parseFloat(
            rewardPrice * this.order.products[key].selectedQty
          );
          discount += parseFloat(
            discountAmount * this.order.products[key].selectedQty
          );
        }
        maxQty += parseFloat(this.order.products[key].op_qty);
        selectedQty += parseFloat(this.order.products[key].selectedQty);
      });
      if (
        maxQty != selectedQty ||
        (this.$inArray(this.order.order_shipping_status, [1, 2]) != true &&
          this.$configVal("RETURN_SHIPPING_ENABLE") == 0)
      ) {
        shipping = 0;
      }
      let total =
        parseFloat(subTotal) +
        parseFloat(tax) +
        parseFloat(shipping) -
        discount.toFixed(2);

      this.subTotal = subTotal;
      this.tax = tax;
      this.shipping = shipping;
      this.gift = gift;
      this.selectedReason = selectedReason;

      this.discount = discount;

      if (this.order.order_shipping_status == 1) {
        total = parseFloat(total) + parseFloat(gift);
      } 
      if (total < 0) {
        total = 0;
      }
      this.total = total;

      if (this.order.order_reward_amount != 0) {
        if (this.order.order_shipping_status != 1 && gift != 0) {
          this.total = parseFloat(reward) - gift;
          this.reward = parseFloat(total) + gift;
          return;
        }
        this.total = reward;
        this.reward = total - reward > 0 ? parseFloat(total - reward) : 0;
      }
    },
    updateListing: function() {
      let defaultReason = "other";
      if (this.retrunReasons.length > 0) {
        defaultReason = this.retrunReasons[0].reason_id;
      }
      Object.keys(this.order.products).forEach((key) => {
        this.order.products[key].selectedQty = 0;
        this.order.products[key].reason = defaultReason;
      });
      if (this.bankDetails) {
        this.bankDetailInfo = this.bankDetails;
      }
      this.$forceUpdate();
    },
    addBankDetails: function(details) {
      this.bankDetailInfo = details;
      this.$bvModal.hide("bankInfo");
    },
    productSubTotal: function(order) {
      let subTotal = 0;
      Object.keys(this.order.products).forEach((key) => {
        subTotal += parseFloat(
          this.order.products[key].op_product_price *
            this.order.products[key].op_qty
        );
      });
      return subTotal;
    },
  },
  mounted: function() {
    this.updateListing();
  },
};
</script>
