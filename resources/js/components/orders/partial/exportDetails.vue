<template>
  <b-modal
    id="exportDetailPopup"
    centered
    title="BootstrapVue"
    @hidden="closeModal"
    size="lg"
    no-close-on-backdrop
  >
    <template v-slot:modal-header="{ close }">
      <h5 class="modal-title">
        <span></span>
        {{ $t('LBL_EXPORT') }}
      </h5>
      <button type="button" class="close" @click="close()"></button>
    </template>

    <div class="export-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group row">
            <div class="col-md-12">
              <p>{{$t('LBL_SELECT_EXPORT_DATA_TYPE')}}</p>
              <div class="radio-inline">
                <label class="radio">
                  <input type="radio" checked="checked" v-model="type" value="0" />
                  {{$t('LBL_ORDERS')}}
                  <span></span>
                </label>
                <label class="radio">
                  <input type="radio" v-model="type" value="2" />
                  {{$t('LBL_CANCELLATION')}}
                  <span></span>
                </label>
                <label class="radio">
                  <input type="radio" v-model="type" value="1" />
                  {{$t('LBL_RETURN')}}
                  <span></span>
                </label>
                <label class="radio">
                  <input type="radio" v-model="type" value="4" />
                  {{$t('LBL_COMPLETED')}}
                  <span></span>
                </label>
                <label class="radio">
                  <input type="radio" v-model="type" value="5" />
                  {{$t('LBL_INVOICES')}}
                  <span></span>
                </label>
              </div>
            </div>
          </div>
          <hr />
          <h5 class="mb-4">{{$t('LBL_ORDER_FILTERS')}}</h5>
          <div class="form-group row">
            <label class="col-md-5 col-form-label">{{$t('LBL_FULFILLMENT_TYPE')}}</label>
            <div class="col-md-6" v-bind:class="[type != 0 && type != 4  ? 'disabledbutton' : '']">
              <div class="dropdown">
                <button
                  class="btn btn-default btn-block dropdown-toggle"
                  type="button"
                  id="dropdownFulfillmentStatus"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >{{ fulfillment[fulfillment_status]}}</button>
                <div class="dropdown-menu" aria-labelledby="dropdownFulfillmentStatus">
                  <a
                    class="dropdown-item"
                    href="javascript:;"
                    v-for="(fstatus, fstatusKey) in fulfillment"
                    :key="fstatusKey"
                    @click="fulfillment_status = fstatusKey, order_shipping_status = 'all'"
                    v-if="fulfillment_status != fstatusKey"
                  >{{fstatus}}</a>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label
              class="col-md-5 col-form-label"
            >{{fulfillment_status == 1 ? 'Pickup Status' : $t('LBL_SHIPPING_STATUS')}}</label>
            <div class="col-md-6" v-bind:class="[type != 0 ? 'disabledbutton' : '']">
              <div class="dropdown" v-if="fulfillment_status == 'all' || fulfillment_status == 0">
                <button
                  class="btn btn-default btn-block dropdown-toggle"
                  type="button"
                  id="dropdownMenuButton"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <span v-if="order_shipping_status == 'all'">{{$t('LBL_EXPORT_FILTER_ALL')}}</span>
                  <span
                    v-b-tooltip.hover
                    :title="productType == 2 || productType == 3 ? $t('LBL_PHYSICAL_ORDER_STATUS') :''"
                    v-if="order_shipping_status != 'all' && (productType == 3 || productType == 1)"
                  >{{ shippingStatus[order_shipping_status] | removeHyphen | camelCase}}</span>
                  <span
                    v-b-tooltip.hover
                    :title="productType == 1 || productType == 3 ? $t('LBL_DIGITAL_ORDER_STATUS') :''"
                    v-if="orderDigitalStatus[shippingStatus[order_shipping_status]] && order_shipping_status != 'all' && (productType == 3 || productType == 2)"
                  >
                    {{productType == 3 || productType == 1 ? '/' : ''}}
                    {{ orderDigitalStatus[shippingStatus[order_shipping_status]] | removeHyphen | camelCase}}
                  </span>
                  <span
                    v-b-tooltip.hover
                    :title="'Pickup Order Status'"
                    v-if="fulfillment_status == 'all' && order_shipping_status != 'all'"
                  >{{'/ '+ $cleanShipStatus(pickupStatus[order_shipping_status]) }}</span>
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a
                    class="dropdown-item"
                    href="javascript:;"
                    @click="order_shipping_status = 'all'"
                    v-if="order_shipping_status != 'all'"
                  >
                    <span>{{$t('LBL_EXPORT_FILTER_ALL')}}</span>
                  </a>
                  <a
                    class="dropdown-item"
                    href="javascript:;"
                    v-for="(shipStatus, shipkey) in shippingStatus"
                    :key="shipkey"
                    @click="order_shipping_status = shipkey"
                    v-if="order_shipping_status != shipkey"
                  >
                    {{productType == 3 || productType == 1 ?$cleanShipStatus(shipStatus) : ''}}
                    {{orderDigitalStatus[shipStatus] && (productType == 3 || productType == 2) ? '/ '+ $cleanShipStatus(orderDigitalStatus[shipStatus]) : ''}}
                    {{fulfillment_status == 'all' ? '/ '+ $cleanShipStatus(pickupStatus[shipkey]) : ''}}
                  </a>
                </div>
              </div>
              <div class="dropdown" v-if="fulfillment_status == 1">
                <button
                  class="btn btn-default btn-block dropdown-toggle"
                  type="button"
                  id="dropdownPaymentStatus"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >{{ order_shipping_status == 'all' ? $t('LBL_EXPORT_FILTER_ALL') : pickupStatus[order_shipping_status]}}</button>
                <div class="dropdown-menu" aria-labelledby="dropdownPaymentStatus">
                  <a
                    class="dropdown-item"
                    href="javascript:;"
                    @click="order_shipping_status = 'all'"
                    v-if="order_shipping_status != 'all'"
                  >
                    <span>{{$t('LBL_EXPORT_FILTER_ALL')}}</span>
                  </a>
                  <a
                    class="dropdown-item"
                    href="javascript:;"
                    v-for="(pickStatus, pickKey) in pickupStatus"
                    :key="pickKey"
                    @click="order_shipping_status = pickKey"
                    v-if="order_shipping_status != pickKey"
                  >{{$cleanShipStatus(pickStatus)}}</a>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-5 col-form-label">{{$t('LBL_PAYMENT_STATUS')}}</label>
            <div
              class="col-md-6"
              v-bind:class="[type != 0 && type != 2 && type != 4   ? 'disabledbutton' : '']"
            >
              <div class="dropdown">
                <button
                  class="btn btn-default btn-block dropdown-toggle"
                  type="button"
                  id="dropdownPaymentStatus"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >{{ paymentTypes[payment_status]}}</button>
                <div class="dropdown-menu" aria-labelledby="dropdownPaymentStatus">
                  <a
                    class="dropdown-item"
                    href="javascript:;"
                    v-for="(paymentType, paymentKey) in paymentTypes"
                    :key="paymentKey"
                    @click="payment_status = paymentKey"
                    v-if="payment_status != paymentKey"
                  >{{paymentType}}</a>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-5 col-form-label">{{$t('LBL_DATE')}}</label>
            <div class="col-md-7">
              <div class="row">
                <div class="col-md-5">
                  <div class="dropdown">
                    <button
                      class="btn btn-default btn-block dropdown-toggle"
                      type="button"
                      id="dropdownDateStatus"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >{{ dateTypes[date_status]}}</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownDateStatus">
                      <a
                        class="dropdown-item"
                        href="javascript:;"
                        v-for="(dateType, dateKey) in dateTypes"
                        :key="dateKey"
                        @click="date_status = dateKey"
                        v-if="date_status != dateKey"
                      >{{dateType}}</a>
                    </div>
                  </div>
                </div>
                <div
                  class="col-md-6"
                  v-bind:class="[date_status != 'custom' ? 'disabledbutton' : '']"
                >
                  <date-picker
                    v-model="dateRange"
                    type="date"
                    range
                    value-type="format"
                    format="YYYY-MM-DD"
                    :placeholder="$t('PLH_SELECT_DATE')"
                    class="w-100"
                    :input-class="'form-control'"
                  ></date-picker>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <template v-slot:modal-footer>
      <button type="button" class="btn btn-brand mx-auto" @click="exportData">{{ $t('BTN_EXPORT')}}</button>
    </template>
  </b-modal>
</template>
<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
export default {
  data: function() {
    return {
      order_shipping_status: "all",
      type: 0,
      uploaded: 0,
      fulfillment_status: "all",
      payment_status: "all",
      date_status: "today",
      fulfillment: {
        all: "All",
        0: "Ship",
        1: "Pickup"
      },
      paymentTypes: {
        all: "All",
        2: "Paid",
        1: "Unpaid"
      },
      dateTypes: {
        today: "Today",
        week: "Week",
        month: "Month",
        year: "Year",
        custom: "Custom"
      },
      dateRange: []
    };
  },
  props: [
    "shippingStatus",
    "productType",
    "orderDigitalStatus",
    "pickupStatus"
  ],

  methods: {
    exportData: function() {
      if (this.type == 5) {
        this.exportInvoice();
        return false;
      }
      let formData = this.$serializeData({
        shipping_status: this.order_shipping_status,
        type: this.type,
        fulfillment_status: this.fulfillment_status,
        payment_status: this.payment_status,
        date_status: this.date_status,
        dateRange: this.dateRange
      });

      this.$http
        .post(adminBaseUrl + "/orders/orders-export", formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.$bvModal.hide("exportDetailPopup");
          window.open(response.data.data, "_blank");
        });
    },
    closeModal: function() {
      this.type = 0;
      this.dateRange = [];
    },
    exportInvoice: function() {
      let formData = this.$serializeData({
        date_status: this.date_status,
        dateRange: this.dateRange
      });

      this.$http
        .post(adminBaseUrl + "/orders/invoice-export", formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.$bvModal.hide("exportDetailPopup");
          window.open(response.data.data, "_blank");
        });
    }
  }
};
</script>
