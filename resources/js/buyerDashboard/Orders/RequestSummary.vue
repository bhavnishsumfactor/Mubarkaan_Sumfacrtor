<template>
  <div class="body bg-dashboardx" id="body" data-yk>
    <section class="py-2 order-completed">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-9">
            <div class="thanks-screen text-center">
              <div class="success-animation">
                <div class="svg-box">
                  <svg class="circular green-stroke">
                    <circle
                      class="path"
                      cx="75"
                      cy="75"
                      r="50" 
                      fill="none"
                      stroke-width="5"
                      stroke-miterlimit="10"
                    />
                  </svg>
                  <svg class="checkmark green-stroke">
                    <g
                      transform="matrix(0.79961,8.65821e-32,8.39584e-32,0.79961,-489.57,-205.679)"
                    >
                      <path
                        class="checkmark__check"
                        fill="none"
                        d="M616.306,283.025L634.087,300.805L673.361,261.53"
                      />
                    </g>
                  </svg>
                </div>
              </div>

              <h2>
                {{ reasonTypes[orrequestType] }}
                {{ $t("LBL_REQUESTED") }}
              </h2>
              <h3 v-if="reasonTypes[orrequestType]">
                {{ reasonTypes[orrequestType] }} {{ $t("LBL_YOUR_ORDER_RETURN") }}
                <inertia-link
                  :href="
                    baseUrl +
                      '/user/orders/' +
                      orderId +
                      '/' +
                      $cleanString(reasonTypes[orrequestType])
                  "
                >{{ " #" + orderId }}</inertia-link>
                {{ $t("LBL_HAS_BEEN_PLACED") }}
              </h3>
              <p>
                {{ $t("LBL_RETURN_CONFIRMATION_SENT") }}
                <strong>{{ email }}</strong>
                {{ $t("LBL_RETURN_CONFIRMATION_MESSAGE") }}
              </p>
              <p class="placed">
                <span>
                  <svg class="svg" width="16px" height="16px">
                    <use
                      :xlink:href="
                        baseUrl +
                          '/dashboard/media/retina/sprite.svg#TimePlaced'
                      "
                      :href="
                        baseUrl +
                          '/dashboard/media/retina/sprite.svg#TimePlaced'
                      "
                    />
                  </svg>
                  <strong>{{ $t("LBL_TIME_PLACED") }}:</strong>
                  {{ $dateTimeFormat(requestDate) }}
                </span>

                <span>
                  <svg class="svg" width="16px" height="16px">
                    <use
                      :xlink:href="
                        baseUrl + '/dashboard/media/retina/sprite.svg#print'
                      "
                      :href="
                        baseUrl + '/dashboard/media/retina/sprite.svg#print'
                      "
                    />
                  </svg>
                  <a href="javascript:;" onclick="window.print();">{{ $t("LNK_PRINT") }}</a>
                </span>
              </p>
            </div>
            <ul class="completed-detail">
              <li>
                <h4>
                  <svg class="svg" width="22px" height="22px">
                    <use
                      :xlink:href="
                        baseUrl +
                          '/dashboard/media/retina/sprite.svg#completed-documents'
                      "
                      :href="
                        baseUrl +
                          '/dashboard/media/retina/sprite.svg#completed-documents'
                      "
                    />
                  </svg>
                  {{ $t("LBL_GET_RETURN_DOCS") }}
                </h4>
                <p>{{ $t("LBL_RETURN_DOCS_INSTRUCTIONS") }}</p>
              </li>
              <li>
                <h4>
                  <svg class="svg" width="22px" height="22px">
                    <use
                      :xlink:href="
                        baseUrl + '/dashboard/media/retina/sprite.svg#parcel'
                      "
                      :href="
                        baseUrl + '/dashboard/media/retina/sprite.svg#parcel'
                      "
                    />
                  </svg>
                  {{ $t("LBL_PREPARE_PARCEL") }}
                </h4>
                <p>{{ $t("LBL_PREPARE_PARCEL_INSTRUCTIONS1") + ' ' + $configVal("BUSINESS_NAME") + ' ' + $configVal("BUSINESS_ADDRESS1") + ' ' + $configVal("BUSINESS_ADDRESS2") + ' ' + $configVal("BUSINESS_CITY") + ' ' + stateName + ' ' + countryName + '. ' + $t("LBL_PREPARE_PARCEL_INSTRUCTIONS2") }}</p>
              </li>
              <li>
                <h4>
                  <svg class="svg" width="22px" height="22px">
                    <use
                      :xlink:href="
                        baseUrl +
                          '/dashboard/media/retina/sprite.svg#parsel-ready'
                      "
                      :href="
                        baseUrl +
                          '/dashboard/media/retina/sprite.svg#parsel-ready'
                      "
                    />
                  </svg>
                  {{ $t("LBL_LEAVE_PARCEL") }}
                </h4>
                <p>{{ $t("LBL_LEAVE_PARCEL_INSTRUCTIONS") }}</p>
              </li>
              <li v-if="comment">
                <h4>
                  <svg class="svg" width="22px" height="22px">
                    <use
                      :xlink:href="
                        baseUrl +
                          '/dashboard/media/retina/sprite.svg#parsel-ready'
                      "
                      :href="
                        baseUrl +
                          '/dashboard/media/retina/sprite.svg#parsel-ready'
                      "
                    />
                  </svg>
                  {{$t('LBL_ADDITIONAL_INFORMATION')}}
                </h4>
                <p>{{ comment }}</p>
              </li>
            </ul>
            <div class="row justify-content-center">
              <div class="col-md-12">
                <div class="completed-cart">
                  <div class="row justify-content-between">
                    <div class="col-md-7">
                      <h5>{{ $t("LBL_ORDER_LIST") }}</h5>
                      <div class="completed-cart-list scroll-y">
                        <ul class="list-group list-cart list-cart-checkout">
                          <li class="list-group-item" v-for="(record, rKey) in records" :key="rKey">
                            <div class="product-profile">
                              <div class="product-profile__thumbnail">
                                <a :href="$generateUrl(record.url_rewrite)">
                                  <img
                                    data-yk
                                    class="img-fluid"
                                    :src="
                                      $productImage(
                                        record.op_product_id,
                                        record.op_pov_code,  record.images ? record.images.afile_updated_at : '', '38-51'
                                      )
                                    "
                                    alt="..."
                                  />
                                </a>
                                <span class="product-qty">{{ record.orrequest_qty }}</span>
                              </div>
                              <div class="product-profile__data">
                                <div class="title">
                                  <a
                                    :href="$generateUrl(record.url_rewrite)"
                                  >{{ record.op_product_name }}</a>
                                </div>
                                <div
                                  class="options text-capitalize"
                                  v-if="record.op_product_variants"
                                >
                                  <p
                                    v-if="
                                      JSON.parse(record.op_product_variants)
                                        .styles
                                    "
                                  >
                                    {{
                                    Object.values(
                                    JSON.parse(record.op_product_variants)
                                    .styles
                                    ).join(" | ")
                                    }}
                                  </p>
                                </div>
                                <div class="return-reason">{{ $t("LBL_REASON") }}: {{ record.orrequest_reason }}</div>
                              </div>
                            </div>

                            <div class="product-price">{{ $priceFormat(record.op_product_price) }}</div>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <h5>Order Summary</h5>
                      <div class="cart-total mt-5">
                        <ul class="list-group list-group-flush-x list-group-flush-y">
                          <li class="list-group-item">
                            <span class="label">{{ $t("LBL_SUBTOTAL") }}</span>
                            <span class="ml-auto">{{ $priceFormat(subtotal) }}</span>
                          </li>

                          <li class="list-group-item" v-if="tax != 0">
                            <span class="label">{{ $t("LBL_TAX") }}</span>
                            <span class="ml-auto">{{ $priceFormat(tax) }}</span>
                          </li>
                          <li class="list-group-item" v-if="shipping != 0">
                            <span class="label">{{ $t("LBL_SHIPPING") }}</span>
                            <span class="ml-auto">{{ $priceFormat(shipping) }}</span>
                          </li>

                          <li class="list-group-item" v-if="discount != 0">
                            <span class="label">{{ $t("LBL_DISCOUNT") }}</span>
                            <span class="ml-auto">- {{ $priceFormat(discount) }}</span>
                          </li>

                          <li class="list-group-item" v-if="rewardPrice != 0">
                            <span class="label">{{ $t("LBL_REWARD_POINTS_AMOUNT") }}</span>
                            <span class="ml-auto">- {{ $priceFormat(rewardPrice) }}</span>
                          </li>

                          <li class="list-group-item" v-if="giftwrapAmount != 0 && giftwrapAmount > 0">
                            <span class="label">{{ $t("LBL_GIFT_WRAP_AMOUNT") }}</span>
                            <span class="ml-auto">{{ $priceFormat(giftwrapAmount) }}</span>
                          </li>

                          <li class="list-group-item hightlighted">
                            <span class="label">{{ $t("LBL_TOTAL") }}</span>
                            <span class="ml-auto">
                              {{
                              $priceFormat(total)
                              }}
                            </span>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <hr class="m-0" />
    <section class="section">
      <div class="container">
        <div class="any-question">
          <div class="row justify-content-center">
            <div class="col-md-7">
              <div class="row align-items-center py-4">
                <div class="col-md">
                  <div class>
                    <p>
                      <strong>{{ $t("LBL_ANY_QUESTIONS") }}</strong>
                    </p>
                    <p>{{ $t("LBL_EMAIL") }}: {{ $configVal("BUSINESS_EMAIL") }}</p>
                    <p>
                      {{ $t("LBL_PHONE") }}:
                      {{
                      "+" +
                      phonecode +
                      " " +
                      $configVal("BUSINESS_PHONE_NUMBER")
                      }}
                    </p>
                  </div>
                </div>
                <div class="col-md-auto">
                  <inertia-link
                    class="btn btn-brand btn-wide mt-3 mt-md-0"
                    :href="baseUrl + '/user/orders'"
                  >{{ $t("LNK_BACK_TO_ORDERS") }}</inertia-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
export default {
  props: [
    "records",
    "reasonTypes",
    "orderId",
    "stateName",
    "countryName",
    "phonecode",
    "comment"
  ],
  data: function() {
    return {
      baseUrl: baseUrl,
      subtotal: 0,
      tax: 0,
      shipping: 0,
      discount: 0,
      giftwrapAmount: 0,
      rewardPrice: 0,
      total: 0,
      email: "",
      orrequestType: "",
      requestDate: ""
    };
  },
  methods: {
    calculateRequests: function() {
      let subtotal = 0;
      let tax = 0;
      let shipping = 0;
      let discount = 0;
      let giftwrapAmount = 0;
      let rewardPrice = 0;
      Object.keys(this.records).forEach(rkey => {
        subtotal += parseFloat(
          this.records[rkey].op_product_price * this.records[rkey].orrequest_qty
        );
        tax += parseFloat(this.records[rkey].oramount_tax);
        shipping += parseFloat(this.records[rkey].oramount_shipping);
        discount += parseFloat(this.records[rkey].oramount_discount);
        giftwrapAmount += parseFloat(
          this.records[rkey].oramount_giftwrap_price
        );
        rewardPrice += parseFloat(this.records[rkey].oramount_reward_price);
      });
      this.subtotal = subtotal;
      this.tax = tax;
      this.shipping = shipping;
      this.discount = discount;
      this.giftwrapAmount = giftwrapAmount;
      this.rewardPrice = rewardPrice;

      let total =
        parseFloat(subtotal) +
        parseFloat(tax) +
        parseFloat(shipping) -
        parseFloat(discount) -
        parseFloat(rewardPrice);

      if (this.giftwrapAmount != 0) {
        if (this.giftwrapAmount > 0) {
          total = parseFloat(total) + parseFloat(Math.abs(this.giftwrapAmount));
        }
      }
      if (total < 0) {
        total = 0;
      }
      this.total = total;
      this.email = this.records[this.records.length - 1].user_email;
      this.requestDate = this.records[this.records.length - 1].orrequest_date;
      this.orrequestType = this.records[this.records.length - 1].orrequest_type;
    }
  },
  mounted: function() {
    this.calculateRequests();
  }
};
</script>
