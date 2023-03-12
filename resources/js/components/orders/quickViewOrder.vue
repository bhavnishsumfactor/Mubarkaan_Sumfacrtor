<template>
  <b-modal id="quickViewModel" size="lg" centered title="BootstrapVue">
    <template v-slot:modal-header>
      <h5 class="modal-title" id="exampleModalLabel">{{ $t('LBL_ORDER') + ' #' + orders.order_id}}</h5>
      <button type="button" class="close" @click="$bvModal.hide('quickViewModel')"></button>
    </template>
    <div
      class="ribbon ribbon--dark ribbon--shadow ribbon--left ribbon--round"
      v-if="typeof orders.order_id != 'undefined' "
    >
      <div
        class="ribbon__target"
        style="top:0px; right: -17px;"
      >{{orders.order_payment_status}} Via {{orders.order_payment_method}}</div>

      <div class="row">
        <div class="col-md-6">
          <p class="list-text">
            <span class="lable">{{$t("LBL_CUSTOMER")}}:</span>
            {{orders.username}}
          </p>
          <p class="list-text">
            <span class="lable">{{$t("LBL_DATE")}}:</span>
            {{orders.order_date_added | formatDateTime}}
          </p>
        </div>
      </div>
      <hr />
      <perfect-scrollbar :style="'max-height:400px'">
        <table class="table table-justified">
          <thead>
            <tr>
              <th>{{ '#' }}</th>
              <th>{{ $t('LBL_IMAGE') }}</th>
              <th>{{ $t('LBL_ITEM') }}</th>
              <th>{{ $t('LBL_QTY') }}</th>
              <th>{{ $t('LBL_RATE') }}</th>
              <th>{{ $t('LBL_TOTAL') }}</th>
            </tr> 
          </thead> 
          <tbody>
            <tr v-for="(product, productKey) in orders.products" :key="productKey">
              <td scope="row">{{ productKey+1 }}</td>
              <td>
                <img
                  class="pro-img"
                  :src="$productImage(product.op_product_id, product.op_pov_code, product.images ? $timestamp(product.images.afile_updated_at) : '', '30-40')"
                  width="30"
                />
              </td>
              <td>
                {{ product.op_product_name }}
                <p class="options" v-if="product.op_product_variants">
                  <span
                    v-if="JSON.parse(product.op_product_variants).styles"
                  >{{Object.values(JSON.parse(product.op_product_variants).styles).join(' | ')}}</span>
                </p>
              </td>
              <td>{{ product.op_qty }}</td>
              <td>{{currencySymbol}}{{ product.op_product_price | numberFormat }}</td>
              <td>{{currencySymbol}}{{ (product.op_product_price * product.op_qty) | numberFormat }}</td>
            </tr>
          </tbody>
        </table>
      </perfect-scrollbar>
      <hr class="m-0" />
      <div class="row mt-4">
        <div class="col"></div>
        <div class="col">
          <table class="table table-justified">
            <tfoot>
              <tr>
                <td style="border-top:0;">{{$t('LBL_SUBTOTAL')}}:</td>
                <td style="border-top:0;">
                  <strong>{{currencySymbol}}{{subTotal | numberFormat}}</strong>
                </td>
              </tr>
              <tr></tr>
              <tr>
                <td>{{$t('LBL_TAX')}}:</td>
                <td>
                  <strong>{{currencySymbol}}{{tax | numberFormat}}</strong>
                </td>
              </tr>
              <tr
                v-if="orders.order_shipping_type != 1 && orders.digital_products != orders.products.length"
              >
                <td>{{$t('LBL_SHIPPING')}}:</td>
                <td>
                  <strong>
                    <span class="ml-auto">
                      <span v-if="shipping == 0">Free</span>
                      <span v-else>{{currencySymbol}}{{shipping | numberFormat}}</span>
                    </span>
                  </strong>
                </td>
              </tr>
              <tr v-if="giftprice != 0">
                <td>{{$t('Gift Wrap Amount:')}}</td>
                <td>
                  <strong>
                    <span class="ml-auto">
                      <span v-if="giftprice == 0">{{$t("LBL_FREE")}}</span>
                      <span v-else>{{currencySymbol}}{{giftprice | numberFormat}}</span>
                    </span>
                  </strong>
                </td>
              </tr>
              <tr v-if="discount != 0">
                <td>Discount {{orders.order_discount_coupon_code}}</td>
                <td>- {{currencySymbol}}{{discount | numberFormat}}</td>
              </tr>
              <tr v-if="rewardAmount != 0">
                <td>{{$t('LBL_REWARD_POINTS_AMOUNT')}}:</td>
                <td>
                  <strong>- {{ currencySymbol }}{{ rewardAmount | numberFormat }}</strong>
                </td>
              </tr>
              <tr>
                <td>{{$t('LBL_TOTAL')}}:</td>
                <td>
                  <strong>{{currencySymbol}}{{orders.order_net_amount | numberFormat}}</strong>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
    <template v-slot:modal-footer="{cancel}">
      <button type="button" class="btn btn-brand" @click="cancel()">{{ $t('BTN_CLOSE')}}</button>
    </template>
  </b-modal>
</template>

<script>
export default {
  props: ["orderId", "clickEvent"],
  data: function() {
    return {
      currencySymbol: currencySymbol,
      orders: [],
      shipping: 0,
      rewardAmount: 0,
      userName: "",
      comment: "",
      tax: 0,
      subTotal: 0,
      giftprice: 0,
      discount: 0
    };
  },
  watch: {
    clickEvent: function(newVal, oldVal) {
      // watch it
      this.quickViewOrder();
    }
  },
  methods: {
    quickViewOrder: function() {
      this.$http
        .get(adminBaseUrl + "/orders/" + this.orderId)
        .then(response => {
          this.$bvModal.show("quickViewModel");
          let order = response.data.data.order;
          this.orders = order;

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
        });
    }
  },
  mounted: function() {
    // this.quickViewOrder();
  }
};
</script>