<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <div class="with-arrow mb-3">
            <h3 class="subheader__title">#{{this.$route.params.requestId}}</h3>
          </div>
        </div>
        <div class="subheader__toolbar">
          <div class="subheader__wrapper">
            <a
              class="btn btn-subheader"
              href="javascript:;"
              @click="printWindow()"
            >{{ $t('BTN_PRINT') }}</a>
            <router-link :to="{name: 'orders'}" class="btn btn-white">{{ $t('BTN_BACK') }}</router-link>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="portlet portlet-no-min-height">
            <div
              class="portlet__head ribbon ribbon--dark ribbon--shadow ribbon--left ribbon--round"
            >
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">{{returnType | camelCase}} Request</h3>
              </div>
            </div>

            <span
              v-for="(product,productKey) in orders.products"
              v-if="product.cancel_request"
              :key="productKey"
            >
              <div class="portlet__body">
                <ul class="list-group list-cart list-cart-order list-order list-order-return">
                  <li class="list-group-item">
                    <div class="list-inner">
                      <div class="product-profile">
                        <div class="product-profile__thumbnail">
                          <a href="javascript:;">
                            <img
                              :src="$productImage(product.op_product_id, product.op_pov_code, product.images ? $timestamp(product.images.afile_updated_at) : '', '39-52')"
                              alt="..."
                              class="img-fluid"
                            />
                          </a>
                        </div>
                        <div class="product-profile__data">
                          <div class="title">
                            <a class="text-body" href="javascript:;">{{product.op_product_name}}</a>
                          </div>
                          <p
                            class="product-option-wrap"
                            v-if="JSON.parse(product.op_product_variants).styles"
                          >{{Object.values(JSON.parse(product.op_product_variants).styles).join(' | ')}}</p>
                        </div>
                      </div>
                      <div class="by-quantity">
                        <span>{{currencySymbol}}{{product.op_product_price | numberFormat}}</span>
                        <span>×</span>
                        <span>{{product.cancel_request.orrequest_qty}}</span>
                      </div>
                      <div
                        class="price font-weight-bold"
                      >{{currencySymbol}}{{product.op_product_price * product.cancel_request.orrequest_qty | numberFormat}}</div>
                    </div>
                  </li>
                </ul>

                <div class="row">
                  <div class="col"></div>
                  <div class="col">
                    <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x mt-5">
                      <li class="list-group-item d-flex">
                        <span>{{$t("LBL_SUBTOTAL")}}</span>
                        <span class="ml-auto">{{currencySymbol}}{{returnSubtotal | numberFormat}}</span>
                      </li>
                      <li class="list-group-item d-flex">
                        <span>{{$t("LBL_TAX")}}</span>
                        <span class="ml-auto">{{currencySymbol}}{{returnTax | numberFormat}}</span>
                      </li>
                      <li class="list-group-item d-flex" v-if="returnShipping != 0">
                        <span>{{$t("LBL_SHIPPING")}}</span>
                        <span class="ml-auto">{{currencySymbol}}{{returnShipping | numberFormat}}</span>
                      </li>
                      <li class="list-group-item d-flex" v-if="returnDiscount != 0">
                        <span>{{$t("LBL_DISCOUNT_PRICE")}}</span>
                        <span class="ml-auto">- {{currencySymbol}}{{returnDiscount | numberFormat}}</span>
                      </li>
                      <li
                        class="list-group-item d-flex"
                        v-if="returnGiftwrap != 0 && returnGiftwrap > 0"
                      >
                        <span>{{$t("LBL_GIFT_WRAP_AMOUNT")}}</span>
                        <span
                          class="ml-auto"
                        >{{currencySymbol}}{{Math.abs(returnGiftwrap) | numberFormat}}</span>
                      </li>
                      <li class="list-group-item d-flex" v-if="returnReward != 0">
                        <span>{{$t("LBL_REWARD_POINTS_AMOUNT")}}</span>
                        <span class="ml-auto">- {{currencySymbol}}{{returnReward | numberFormat}}</span>
                      </li>

                      <li class="list-group-item d-flex">
                        <span>{{$t("LBL_TOTAL")}}</span>
                        <span class="ml-auto">{{currencySymbol}}{{returnTotal| numberFormat}}</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <hr class="m-0" />
              <div class="portlet__body">
                <h6>{{$t("LBL_REQUEST_STATUS")}}</h6>

                <ul
                  class="order-status-click"
                  v-bind:class="[product.cancel_request.orrequest_status == 2 ? 'disabledbutton' : '']"
                >
                  <li
                    v-for="(orderReturnStatusVal, orderReturnStatusId) in orderReturnStatus"
                    :key="orderReturnStatusId"
                    v-if="product.cancel_request.orrequest_type != 2 || orderReturnStatusId != 1"
                    v-bind:class="[product.cancel_request.orrequest_status == orderReturnStatusId ? 'active' : '',
                                        product.cancel_request.orrequest_status == 2 ? 'disabledbutton' : '',
                                        ]"
                    @click="updateOrderStatus(orderReturnStatusId, orderReturnStatusVal , product.cancel_request.orrequest_type, productKey)"
                  >
                    <i class="icn">
                      <svg class="svg">
                        <use
                          :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#'+orderReturnStatusVal"
                          :href="baseUrl + '/admin/images/retina/sprite.svg#'+orderReturnStatusVal"
                        />
                      </svg>
                    </i>
                    {{orderReturnStatusVal | removeHyphen | camelCase }}
                  </li>
                </ul>
              </div>
            </span>
          </div>

          <div class="portlet portlet-no-min-height">
            <div class="portlet__head">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">{{$t("LBL_TIMELINE")}}</h3>
              </div>
            </div>
            <div class="portlet__body">
              <div class="timeline-comments">
                <div class="timeline-avatar">
                  <span class="timeline-avatar-initials">{{userName}}</span>
                </div>
                <form class="form timeline-comments-box" v-on:submit.prevent="validateRecord ">
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
                        v-bind:class="clicked==1 && 'gb-is-loading'"
                        type="submit"
                      >{{$t('BTN_POST')}}</button>
                    </div>
                  </div>
                  <span
                    v-if="errors.first('comment')"
                    class="error text-danger"
                  >{{errors.first('comment')}}</span>
                </form>
              </div>

              <ul class="timeline my-5" id="timeline" v-if="messages.length > 0">
                <li
                  class="event"
                  v-bind:class="[message.omsg_from_type == 1 ? 'admin' : '', message.omsg_type == 4 ? 'status':'comments', msgKey == 0 ? 'last-status':'']"
                  :data-date="message.omsg_created_at  | formatDateTime"
                  v-for="(message, msgKey) in messages"
                  :key="msgKey"
                >
                  <!-- Toggle -->
                  <span class="timeline__date"></span>
                  <div class="data">
                    <span v-if="message.comments">
                      <a
                        class="dropdown-toggle collapsed"
                        data-toggle="collapse"
                        :href="'#event'+ message.omsg_id"
                        aria-expanded="false"
                      >
                        <!--  <svg class="svg" width="20px" height="20px">
                        <use
                          :xlink:href="activeThemeUrl +'/media/retina/sprite.svg#'+ message.omsg_comment"
                          :href="activeThemeUrl +'/media/retina/sprite.svg#'+ message.omsg_comment"
                        />
                        </svg>-->
                        {{message.omsg_comment | removeHyphen | camelCase }}
                      </a>
                      <!-- Collapse -->
                      <div
                        class="collapse"
                        :id="'event'+ message.omsg_id"
                        data-parent="#timeline"
                        style
                      >
                        <div class="mt-3">
                          <div class="other-detail" v-html="message.comments.omsg_comment"></div>
                        </div>
                      </div>
                    </span>
                    <span v-else v-html="message.omsg_comment"></span>
                  </div>
                </li>
              </ul>
              <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="portlet portlet--tabs portlet-no-min-height">
            <div class="portlet__head">
              <div class="portlet__head-toolbar">
                <ul
                  role="tablist"
                  class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold"
                >
                  <li class="nav-item">
                    <a
                      data-toggle="tab"
                      href="javascript:;"
                      customsearch
                      role="tab"
                      aria-selected="false"
                      class="nav-link active"
                    >{{$t('LNK_ORDER_DETAIL')}}</a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="portlet__body p-0">
              <ul
                class="list-group list-group-flush-x list-group-flush-y px-5 pt-5 list-cart list-cart-order list-order list-order-return mb-2"
              >
                <li
                  class="list-group-item border-0"
                  v-for="(product, productKey) in orders.products"
                  :key="productKey"
                >
                  <div class="list-inner">
                    <div class="product-profile">
                      <div class="product-profile__thumbnail">
                        <a href="javascript:;">
                          <img
                            :src="$productImage(product.op_product_id, product.op_pov_code, product.images ? $timestamp(product.images.afile_updated_at) : '', '39-52')"
                            alt="..."
                            class="img-fluid"
                          />
                        </a>
                      </div>
                      <div class="product-profile__data">
                        <div class="title">
                          <a class="text-body" href="javascript:;">{{product.op_product_name}}</a>
                        </div>

                        <p class="options" v-if="product.op_product_variants">
                          <span
                            v-if="JSON.parse(product.op_product_variants).styles"
                          >{{Object.values(JSON.parse(product.op_product_variants).styles).join(' | ')}}</span>
                        </p>

                        <div class="product-quantity">
                          <div class="by-quantity">
                            <span>{{currencySymbol}}{{ product.op_product_price | numberFormat }}</span>
                            <span>×</span>
                            <span>{{product.op_qty}}</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div
                      class="product-price font-weight-bold"
                    >{{currencySymbol}}{{ (product.op_product_price * product.op_qty) | numberFormat }}</div>
                  </div>
                </li>
              </ul>
              <div class="row">
                <div class="col">
                  <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x m-5">
                    <li class="list-group-item d-flex border-0 py-2">
                      <span>{{$t("LBL_SUBTOTAL")}}</span>
                      <span class="ml-auto">{{currencySymbol}}{{subTotal | numberFormat}}</span>
                    </li>
                    <li class="list-group-item d-flex border-0 py-2">
                      <span>{{$t("LBL_TAX")}}</span>
                      <span class="ml-auto">{{currencySymbol}}{{tax | numberFormat}}</span>
                    </li>
                    <li class="list-group-item d-flex border-0 py-2">
                      <span>{{$t("LBL_SHIPPING")}}</span>
                      <span class="ml-auto">
                        <span v-if="shipping == 0">{{$t("LBL_FREE")}}</span>
                        <span v-else>{{currencySymbol}}{{shipping | numberFormat}}</span>
                      </span>
                    </li>
                    <li
                      class="list-group-item d-flex border-0 py-2"
                      v-if="Object.keys(giftMessage).length > 0 && giftprice > 0"
                    >
                      <span>{{$t("LBL_GIFT_WRAP_AMOUNT")}}</span>
                      <span class="ml-auto">
                        <span v-if="giftprice == 0">{{$t("LBL_FREE")}}</span>
                        <span v-else>{{currencySymbol}}{{giftprice | numberFormat}}</span>
                      </span>
                    </li>

                    <li class="list-group-item d-flex border-0 py-2" v-if="discount != 0">
                      <span>{{$t("LBL_DISCOUNT")}} {{orders.order_discount_coupon_code}}</span>
                      <span class="ml-auto">- {{currencySymbol}}{{discount | numberFormat}}</span>
                    </li>
                    <li class="list-group-item d-flex border-0 py-2" v-if="rewardAmount != 0">
                      <span>{{$t("LBL_REWARD_POINTS_AMOUNT")}}</span>
                      <span class="ml-auto">{{ currencySymbol }}{{ rewardAmount | numberFormat }}</span>
                    </li>
                    <li class="list-group-item d-flex font-weight-bold py-2">
                      <span>{{$t("LBL_TOTAL")}}</span>
                      <span
                        class="ml-auto"
                      >{{currencySymbol}}{{orders.order_net_amount | numberFormat}}</span>
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
                  {{$t("LBL_CONTACT_INFORMATION")}}
                </h3>
              </div>
            </div>
            <div class="portlet__body">
              <p class="list-text">
                <span class="lable">{{$t("LBL_EMAIL")}}:</span>
                {{orders.user_email}}
              </p>
              <p class="list-text">
                <span class="lable">{{$t("LBL_PHONE")}}:</span>
                {{orders.user_phone_country_id ? '+'+ orders.user_phone_country_id : ''}} {{orders.user_phone}}
              </p>
            </div>
          </div>

          <div class="portlet portlet-no-min-height" v-for="addr in orders.address">
            <div class="portlet__head">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i
                    class="fas"
                    v-bind:class="[addr.oaddr_type == 1 ? 'fa-file-invoice' : '', 
                    addr.oaddr_type == 2 ? 'fa-shipping-fast' : '', addr.oaddr_type == 3 ? 'fa-people-carry' : '']"
                  ></i>
                  {{addressTypes[addr.oaddr_type]}}
                </h3>
              </div>
            </div>
            <div class="portlet__body">
              <p class="list-text">
                <span class="lable">{{$t('LBL_NAME')}}:</span>
                {{addr.oaddr_name}}
              </p>
              <p class="list-text">
                <span class="lable">{{$t("LBL_APARTMENT_NO")}}:</span>
                {{addr.oaddr_address1}}
              </p>
              <p class="list-text" v-if="addr.oaddr_address2">
                <span class="lable">{{$t("LBL_ADDRESS")}}:</span>
                {{addr.oaddr_address2}}
              </p>
              <p class="list-text">
                <span class="lable">{{$t('LBL_CITY_STATE')}}:</span>
                {{addr.oaddr_city+', '+addr.oaddr_state}}
              </p>
              <p class="list-text">
                <span class="lable">{{$t('LBL_POSTAL_CODE')}}:</span>
                {{addr.oaddr_postal_code}}
              </p>
              <p class="list-text">
                <span class="lable">{{$t('LBL_COUNTRY')}}:</span>
                {{addr.oaddr_country}}
              </p>
              <p class="list-text">
                <span class="lable">{{$t('LBL_PHONE')}}:</span>
                {{addr.country ? '+'+addr.country.country_phonecode : ''}} {{addr.oaddr_phone}}
              </p>
            </div>
          </div>
          <div class="portlet portlet-no-min-height" v-if="Object.keys(giftMessage).length > 0">
            <div class="portlet__head">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i class="icn">
                    <svg class="svg" width="16px" height="16px">
                      <use
                        :xlink:href="activeThemeUrl +'/media/retina/sprite.svg#test'"
                        :href="activeThemeUrl +'/media/retina/sprite.svg#test'"
                      />
                    </svg>
                  </i>
                  {{$t('LBL_GIFT_DETAILS')}}
                </h3>
              </div>
            </div>
            <div class="portlet__body">
              <p class="list-text">
                <span class="lable">{{$t('LBL_TO')}}:</span>
                {{giftMessage.message.to}}
              </p>
              <p class="list-text">
                <span class="lable">{{$t('LBL_FROM')}}:</span>
                {{giftMessage.message.from}}
              </p>
              <p class="list-text">
                <span class="lable">{{$t('LBL_MESSAGE')}}:</span>
                {{giftMessage.message.message}}
              </p>
            </div>
          </div>

          <div class="portlet portlet-no-min-height" v-if="orders.bank_information">
            <div class="portlet__head">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i class="fas fa-envelope"></i>
                  {{$t('LBL_BANK_INFORMATION')}}
                </h3>
              </div>
            </div>

            <div class="portlet__body">
              <p class="list-text">
                <span class="lable">{{$t('LBL_BANK_NAME')}}:</span>
                {{orders.bank_information.orbinfo_name}}
              </p>
              <p class="list-text">
                <span class="lable">{{$t('LBL_BRANCH_NAME')}}:</span>
                {{orders.bank_information.orbinfo_branch}}
              </p>

              <p class="list-text">
                <span class="lable">{{$t('LBL_ACCOUNT_NAME')}}:</span>
                {{orders.bank_information.orbinfo_account_name}}
              </p>
              <p class="list-text">
                <span class="lable">{{$t('LBL_ACCOUNT_NUMBER')}}:</span>
                {{orders.bank_information.orbinfo_account_number}}
              </p>
              <p class="list-text">
                <span class="lable">{{$t('LBL_BRANCH_CODE')}}:</span>
                {{orders.bank_information.orbinfo_branch_code}}
              </p>
              <p class="list-text" v-if="orders.bank_information.orbinfo_notes">
                <span class="lable">{{$t('LBL_NOTES')}}:</span>
                {{orders.bank_information.orbinfo_notes}}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <shipping-status
      :statusData="statusData"
      :url="url"
      @closePopup="closePopup"
      @updateStatus="updateStatus"
    ></shipping-status>
    <cod-payment
      :statusData="statusData"
      :url="url"
      :adminsData="adminsData"
      :readonly="readonly"
      @closePopup="closePopup"
      @updateStatus="updateStatus"
    ></cod-payment>
  </div>
</template>

<script>
import shippingStatus from "./partial/shippingStatus";
import codPayment from "./partial/codPayment";

export default {
  components: {
    shippingStatus,
    codPayment
  },
  data: function() {
    return {
      currencySymbol: currencySymbol,
      adminsData: {},
      codApprovedModelId: "codApproved",
      shippingStatusPopupId: "shippingStatusPopup",
      statusData: {},
      baseUrl: baseUrl,
      activeThemeUrl: activeThemeUrl,
      editnote: 0,
      returnStatus: 0,
      balance: 0,
      clicked: 0,
      note: "",
      readonly: false,
      url: "true",
      message: "",
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
      returnDiscount: 0,
      returnGiftwrap: 0,
      returnReward: 0,
      userName: "",
      comment: "",
      returnType: "",
      tax: 0,
      displayMessage: 0,
      subTotal: 0,
      giftprice: 0,
      discount: 0,
      totalProduct: 0,
      allProductStatus: [],
      courier_name: "",
      tracking_number: "",
      refundTransactionId: "",
      notes: "",
      returnSubtotal: 0,
      returnTax: 0,
      returnShipping: 0,
      returnTotal: 0,
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
    displayComment: function() {
      if (this.displayMessage == true) {
        this.displayMessage = 0;
      } else {
        this.displayMessage = true;
      }
      this.loadCommentRecords();
    },

    currentPage: function(page) {
      this.loadCommentRecords(this.pagination.current_page);
    },
    printWindow: function() {
      window.print();
    },
    closePopup: function(id) {
      this.$bvModal.hide(id);
      this.$bvModal.hide(this.shippingStatusPopupId);
      this.statusData = {};
    },
    updateOrderStatus: function(id, status, type, key) {
      let gatwayType = this.orders.order_payment_method;
      if (this.orders.transaction) {
        if (
          this.inArray(this.orders.transaction.txn_gateway_type, [
            "cash",
            "card"
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
        this.statusData = {
          id: this.$route.params.requestId,
          status: status,
          type: "returnApproved",
          key: key,
          dropdownKey: id,
          complete: 0
        };
        this.adminsData.payment_method = "Bank Transfer";
        this.adminsData.amount = this.returnTotal.toFixed(2);
        this.$bvModal.show(this.codApprovedModelId);
        return false;
      }
      this.statusData = {
        id: this.$route.params.requestId,
        status: status,
        type: type,
        key: key,
        dropdownKey: id,
        complete: 0
      };

      this.$bvModal.show(this.shippingStatusPopupId);
    },
    updateStatus: function(shipStatus, shipComment, adminsData = []) {
      if (shipStatus.type && shipStatus.type == "returnApproved") {
        this.approvedPayment(adminsData);
        return;
      }

      this.orders.products[shipStatus.key].cancel_request.orrequest_status =
        shipStatus.dropdownKey;
      let formData = this.$serializeData({
        status: shipStatus.status,
        message: shipComment.message,
        complete: shipStatus.complete,
        tracking_number: shipComment.tracking_number,
        courier_name: shipComment.courier_name,
        amount: this.returnTotal,
        "transfer-detail": JSON.stringify(adminsData)
      });

      this.$http
        .post(adminBaseUrl + "/orders/return/status/" + shipStatus.id, formData)
        .then(response => {
          this.url = "";
          this.statusData = {};
          this.adminsData = {};
          this.$bvModal.hide(this.shippingStatusPopupId);
          this.$bvModal.hide(this.codApprovedModelId);
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.loadCommentRecords();
        });
    },
    approvedPayment: function(adminsData) {
      let formData = this.$serializeData(adminsData);
      formData.append("order-id", this.orders.order_id);
      formData.append("request-id", this.$route.params.requestId);
      this.$http.post(adminBaseUrl + "/orders/retun/approved", formData).then(
        response => {
          //success
          if (response.data.status == false) {
            this.isLoading = false;
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          this.$bvModal.hide(this.codApprovedModelId);
          this.$bvModal.hide(this.shippingStatusPopupId);
          this.pageLoadData(this.orders.order_id, this.$route.params.requestId);
          toastr.success(response.data.message, "", toastr.options);
        },
        response => {
          //error
          this.validateErrors(response);
        }
      );
    },
    updateNote: function() {
      this.note = this.note.trim();
      let formData = this.$serializeData({
        note: this.note
      });
      formData.append("order-id", this.orders.order_id);
      this.$http
        .post(adminBaseUrl + "/orders/update-notes", formData)
        .then(response => {
          this.editnote = 0;
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    pageLoadData: function(id, requestId) {
      this.$http
        .get(adminBaseUrl + "/return-order/" + id + "/" + requestId)
        .then(response => {
          //
          let order = response.data.data.order;
          this.orders = order;
          this.userName = response.data.data.userName;
          this.shippingStatus = response.data.data.shippingStatus;
          this.orderStatus = response.data.data.orderStatus;
          this.orderReturnStatus = response.data.data.orderReturnStatus;
          this.messages = response.data.data.messages.data;
          this.pagination = response.data.data.messages;
          this.addressTypes = response.data.data.addressTypes;
          this.totalProduct = order.products.length;

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
        });
    },
    inArray: function(needle, haystack) {
      var length = haystack.length;
      for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
      }
      return false;
    },
    varifyStatus: function(order) {
      let approvedStatus = [];
      let declinedStatus = [];
      let returnType = "Return";

      Object.keys(order.products).forEach(pkey => {
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

          this.notes = order.products[pkey].cancel_request.orrequest_comment;
          this.calulateRefund(order.products[pkey]);
          if (
            order.products[pkey].op_product_variants &&
            JSON.parse(order.products[pkey].op_product_variants).gift
          ) {
            this.giftMessage = JSON.parse(
              order.products[pkey].op_product_variants
            ).gift;
          }
          if (order.products[pkey].cancel_request.orrequest_type == 2) {
            returnType = "cancellation";
          }
          this.returnType = returnType;
          this.refundTransactionId =
            order.products[pkey].cancel_request.txn_gateway_transaction_id;
        }
      });
      if (
        this.inArray(false, approvedStatus) == false &&
        order.order_status == 3
      ) {
        this.returnStatus = 1;
      } else {
        this.returnStatus = 0;
      }

      if (
        this.inArray(false, declinedStatus) == false &&
        order.order_status == 3
      ) {
        this.totalRefund = 0;
      }
      this.balance =
        order.order_net_amount - this.totalRefund - order.order_amount_charged;
    },
    calulateRefund: function(block) {
      this.returnSubtotal =
        block.op_product_price * block.cancel_request.orrequest_qty;
      this.returnTax = block.cancel_request.oramount_tax;
      this.returnDiscount = block.cancel_request.oramount_discount;
      this.returnGiftwrap = block.cancel_request.oramount_giftwrap_price;
      this.returnReward = block.cancel_request.oramount_reward_price;
      this.returnShipping = block.cancel_request.oramount_shipping;

      let total =
        parseFloat(this.returnSubtotal) +
        parseFloat(this.returnTax) +
        parseFloat(this.returnShipping);
      if (this.returnDiscount != 0) {
        total = total - this.returnDiscount;
      }
      if (this.returnReward != 0) {
        total = total - this.returnReward;
      }
      if (this.returnGiftwrap != 0 && this.returnGiftwrap > 0) {
        total = parseFloat(total) + parseFloat(Math.abs(this.returnGiftwrap));
      }
      if (total < 0) {
        total = 0;
      }
      this.returnTotal = total;
    },
    loadCommentRecords: function(pageNo) {
      let formData = this.$serializeData({
        "record-id": this.$route.params.requestId,
        type: "return"
      });
      formData.append("comment", this.displayMessage);
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(adminBaseUrl + "/orders/load-comments?page=" + pageNo, formData)
        .then(response => {
          this.messages = response.data.data.messages.data;
          this.pagination = response.data.data.messages;
        });
    },
    validateRecord: function() {
      this.clicked = 1;
      this.$validator.validate("comment", this.comment).then(res => {
        if (res) {
          let formData = this.$serializeData({
            comment: this.comment,
            type: "return"
          });
          formData.append("record-id", this.$route.params.requestId);
          this.$http.post(adminBaseUrl + "/orders/comments", formData).then(
            response => {
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
            response => {
              //error
              this.validateErrors(response);
            }
          );
        }
      });
    },
    validateErrors: function(response) {
      let jsondata = response.data;
      Object.keys(jsondata.errors).forEach(key => {
        this.errors.add({
          field: key,
          msg: jsondata.errors[key][0]
        });
      });
    },

    nl2br: function(str, is_xhtml) {
      if (typeof str === "undefined" || str === null) {
        return "";
      }
      var breakTag =
        is_xhtml || typeof is_xhtml === "undefined" ? "<br />" : "<br>";
      return (str + "").replace(
        /([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
        "$1" + breakTag + "$2"
      );
    }
  },
  mounted: function() {
    let id = this.$route.params.id;
    let requestId = this.$route.params.requestId;
    this.pageLoadData(id, requestId);
  }
};
</script>