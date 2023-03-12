<template>
  <div v-if="showEmpty == 0 && pageLoaded == 1">
    <kanban-board
      :stages="stages"
      :blocks="blocks"
      @update-block="updateBlock"
      :moves="false"
      v-if="blocks && Object.keys(blocks).length > 0"
    >
      <div v-for="(stage, index) in stages" :slot="stage" :key="stage">
        <h2>
          <span>
            <i class="icn">
              <svg class="svg">
                <use
                  :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#'+stage"
                  :href="baseUrl + '/admin/images/retina/sprite.svg#'+stage"
                />
              </svg>
            </i>
            <span v-if="tabtype != 'open'">{{ stage | removeHyphen }}</span>
            <span
              v-b-tooltip.hover
              :title="productType == 2 || productType == 3 ? $t('LBL_PHYSICAL_ORDER_STATUS') :''"
              v-if="tabtype == 'open' && (productType == 3 || productType == 1)"
            >{{ stage | removeHyphen }}</span>
            <span
              v-b-tooltip.hover
              :title="productType == 1 || productType == 3 ? $t('LBL_DIGITAL_ORDER_STATUS') :''"
              v-if="orderDigitalStatus[stage] && tabtype == 'open' && (productType == 3 || productType == 2)"
            >
              <span v-if="productType == 1 || productType == 3">/</span>
              {{ orderDigitalStatus[stage] | removeHyphen }}
            </span>
          </span>
          <span class="count">{{ total[stage] }}</span>
        </h2>
      </div>

      <div
        class="ribbon ribbon--shadow ribbon--left ribbon--round"
        v-for="(block, indexKey) in blocks"
        :slot="block.id"
        :key="block.id"
      >
        <div class="order-details">
          <div class="order-number">
            <h6>
              <router-link
                :to="{name: 'orderReturnDetail', params: {id: block.order_id, requestId: block.id}}"
                class="digits"
                v-if="url != ''"
              >
                <span
                  v-b-tooltip.hover
                  :title="[block.orrequest_type == 2 ? $t('TTL_CANCELLATION_REQUEST') + ' #': $t('TTL_RETURN_REQUEST') + ' #']"
                >{{block.id}}</span>
              </router-link>
              <router-link
                :to="{name: 'orderDetail', params: {id: block.order_id}}"
                class="digits"
                v-b-tooltip.hover
                :title="$t('TTL_ORDER') + ' #'"
                v-else
              >
                #{{block.order_id}}
                <a
                  class="YK-GiftItem gift-item active"
                  href="javascript:;"
                  v-if="block.order_gift_amount != 0"
                >
                  <svg class="svg">
                    <use
                      :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#gift-icon'"
                      :href="baseUrl+'/admin/images/retina/sprite.svg#gift-icon'"
                    />
                  </svg>
                </a>
              </router-link>
            </h6>
            <div class="action">
              <a
                class="btn btn-sm btn-icon"
                href="javascript:;"
                @click="orderView(block.order_id)"
                v-b-tooltip.hover
                :title="$t('TTL_QUICK_ORDER')"
              >
                <i class="icn">
                  <svg class="svg">
                    <use
                      :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#vision'"
                      :href="baseUrl + '/admin/images/retina/sprite.svg#vision'"
                    />
                  </svg>
                </i>
              </a>

              <a
                class="btn btn-sm btn-icon"
                href="javascript:;"
                v-b-tooltip.hover
                :title="$t('TTL_PACKING_SLIP')"
                @click="downloadPackingSlip(block.order_id)"
                v-if="block.total_products != block.digital_products"
              >
                <i class="icn">
                  <svg class="svg">
                    <use
                      :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#invoice'"
                      :href="baseUrl + '/admin/images/retina/sprite.svg#invoice'"
                    />
                  </svg>
                </i>
              </a>

              <a
                class="btn btn-sm btn-icon"
                href="javascript:;"
                @click="sendPaymentLink(block)"
                v-if="url == ''"
                v-b-tooltip.hover
                :title="$t('TTL_PAYMENT_LINK')"
                v-bind:class="[block.order_payment_method == 'cod' && block.payment_status != 2 && block.status == 'shipped' ? '': 'disabled'] "
              >
                <i class="icon__svg">
                  <svg>
                    <use
                      :xlink:href="baseUrl +'/admin/images/retina/sprite.svg#share-icon'"
                      :href="baseUrl +'/admin/images/retina/sprite.svg#share-icon'"
                    />
                  </svg>
                </i>
              </a>
            </div>
          </div>
          <ul>
            <li class="order" v-if="url != ''">
              <i class="icn">
                <svg class="svg" width="16" height="16">
                  <use
                    :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#user-orders'"
                    :href="baseUrl + '/admin/images/retina/sprite.svg#user-orders'"
                  />
                </svg>
              </i>
              <span v-b-tooltip.hover title="Order #">{{ block.order_id }}</span>
            </li>
            <li class="date">
              <i class="icn">
                <svg class="svg" width="16" height="16">
                  <use
                    :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#date'"
                    :href="baseUrl + '/admin/images/retina/sprite.svg#date'"
                  />
                </svg>
              </i>
              <span v-b-tooltip.hover title="Order date">{{ block.order_date_added | formatDate }}</span>
            </li>
            <li class="total">
              <i class="icn">
                <svg class="svg" width="16" height="16">
                  <use
                    :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#total'"
                    :href="baseUrl + '/admin/images/retina/sprite.svg#total'"
                  />
                </svg>
              </i>
              <span
                v-b-tooltip.hover
                :title="[url != '' ? $t('TTL_ORDER_AMOUNT') : $t('TTL_AMOUNT')]"
              >{{ currencySymbol }}{{ block.order_net_amount | numberFormat}}</span>
            </li>
            <li class="refundtotal" v-if="block.op_product_price">
              <i class="icn">
                <svg class="svg" width="16" height="16">
                  <use
                    :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#total'"
                    :href="baseUrl + '/admin/images/retina/sprite.svg#total'"
                  />
                </svg>
              </i>
              <span>
                {{ currencySymbol }}
                <span
                  v-b-tooltip.hover
                  :title="[block.orrequest_type == 2 ? $t('TTL_CANCELLATION_AMOUNT') : $t('TTL_RETURN_AMOUNT') ]"
                  v-html="calulateRefund(block)"
                ></span>
              </span>
            </li>
            <li class="p-status">
              <i class="icn">
                <svg class="svg" width="16" height="16">
                  <use
                    :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#p-status'"
                    :href="baseUrl + '/admin/images/retina/sprite.svg#p-status'"
                  />
                </svg>
              </i>
              <span
                v-b-tooltip.hover
                :title="$t('TTL_PAYMENT_STATUS')"
              >{{ block.order_payment_status }}</span>
            </li>
            <li class="method">
              <i class="icn">
                <svg class="svg" width="16" height="16">
                  <use
                    :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#method'"
                    :href="baseUrl + '/admin/images/retina/sprite.svg#method'"
                  />
                </svg>
              </i>
              <span
                :id="'YK-payment_method_'+block.id"
                :data-id="indexKey"
                v-b-tooltip.hover
                :title="$t('TTL_PAYMENT_METHOD')"
              >{{block.order_payment_method == 'cod' ? 'Cash' : block.order_payment_method}}</span>
            </li>
          </ul>
        </div>
        <div class="actions" v-if="url == ''">
          <router-link
            :to="{name: 'returnRequest', params: {id: block.order_id}}"
            class="links"
            v-if="block.order_shipping_status == 1 || block.order_shipping_status == 2"
          >{{$t('LNK_ORDER_CANCEL')}}</router-link>

          <button
            @click="markAsCompleted(block)"
            type="button"
            class="links"
            v-if="block.status != 'approved' && block.order_status != 4"
          >{{$t('LNK_ORDER_COMPLETE')}}</button>
        </div>
      </div>
      <div v-for="stage in stages" :key="stage" :slot="`footer-${stage}`">
        <div class="text-center mt-3" v-if="total[stage] > rows[stage]">
          <button
            @click.prevent="() => loadMore($event, stage)"
            class="btn btn-outline-secondary btn-sm"
          >{{$t('BTN_ORDER_LOADMORE')}}</button>
        </div>
      </div>
    </kanban-board>

    <div id="secondary-toast-container" class="toast-bottom-center" v-if="showUndo">
      <div class="toast toast-info">
        <div class="toast-message">
          {{$t('LBL_CLICK')}}
          <a @click="undoCompletion">{{$t('LNK_UNDO')}}</a>
          {{$t('LBL_IF_DONE_BY_MISTAKE')}}
        </div>
      </div>
    </div>

    <shipping-status
      :statusData="statusData"
      :url="url"
      :shippingCourier="couriers"
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
import vueKanban from "vue-kanban";
import shippingStatus from "./partial/shippingStatus";
import codPayment from "./partial/codPayment";
Vue.use(vueKanban);

export default {
  components: {
    shippingStatus,
    codPayment
  },
  props: ["returnstatus", "orderSearch", "updateSearch", "tabtype"],
  data: function() {
    return {
      currencySymbol: currencySymbol,
      adminsData: {},
      baseUrl: baseUrl,
      stages: [],
      blocks: [],
      orderId: 0,
      message: "",
      readonly: false,
      shippingStatusPopupId: "shippingStatusPopup",
      pendingRequestPopupId: "pendingRequestModel",
      paymentLinkPopupId: "paymentLinkPopup",
      codApprovedModelId: "codApproved",
      orderStatus: [],
      orderDigitalStatus: [],
      listPrimitive: null,
      total: [],
      rows: [],
      url: "",
      rowperpage: 5,
      showUndo: false,
      undoRecordId: "",
      undoRecordStatus: "",
      productType: "",
      shareSuccessMessage: "",
      orderDetail: {},
      orderBlock: {},
      statusData: {},
      pageLoaded: 0,
      shippingMesagesEnable: 0,
      showEmpty: 1,
      couriers: []
    };
  },
  watch: {
    returnstatus: function(newVal, oldVal) {
      // watch it
      this.url = "";
      if (this.returnstatus != "") {
        this.url = "return/";
      }
    },
    updateSearch: function() {
      this.kanbanPageRecords();
    }
  },
  methods: {
    kanbanPageRecords: function() {
      this.emptyValues();
      if (this.returnstatus != "") {
        this.url = "return/";
      }
      let formData = this.$serializeData({
        type: this.returnstatus,
        "active-tab": this.tabtype,
        search: JSON.stringify(this.orderSearch)
      });
      this.$http
        .post(adminBaseUrl + "/orders/" + this.url + "list", formData)
        .then(response => {
          let stagesArr = [];
          for (let [key, value] of Object.entries(
            response.data.data.statuses
          )) {
            stagesArr.push(value);
          }
          this.stages = stagesArr;
          this.total = response.data.data.total;
          this.rows = response.data.data.rows;
          this.blocks = response.data.data.blocks;
          this.$emit("kanbanList", Object.keys(this.blocks).length);
          this.orderStatus = response.data.data.orderStatus;
          this.productType = response.data.data.productType;
          this.orderDigitalStatus = response.data.data.orderDigitalStatus;
          this.showEmpty = response.data.data.showEmpty;
          this.shippingMesagesEnable = response.data.data.shippingMesages;
          this.pageLoaded = 1;
          let couriers = response.data.data.shippingCouriers;
          if (couriers && couriers.length > 0) {
            this.couriers = this.couriers.data;
          }
        });
    },
    calulateRefund: function(block, numberFormat = true) {
      let price = block.op_product_price * block.orrequest_qty;
      let tax = block.oramount_tax;
      let discount = block.oramount_discount;
      let giftwrap = block.oramount_giftwrap_price;
      let reward = block.oramount_reward_price;
      let shipping = block.oramount_shipping;

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
      if (numberFormat == true) {
        return total.toLocaleString(undefined, { maximumFractionDigits: 2 });
      }

      return total;
    },
    loadMore: function(event, stage) {
      let formData = this.$serializeData({
        rows: this.rows[stage],
        status: stage,
        type: this.returnstatus,
        search: JSON.stringify(this.orderSearch)
      });
      $(event.target).addClass("spinner spinner--sm spinner--danger");
      this.$http
        .post(adminBaseUrl + "/orders/load-more", formData)
        .then(response => {
          let thisObj = this;
          response.data.data.map(function(block) {
            thisObj.blocks.push(block);
          });
          this.rows[stage] = Number(this.rows[stage]) + this.rowperpage;
          $(event.target).removeClass("spinner spinner--sm spinner--danger");
        });
    },
    orderView: function(orderId) {
      this.$emit("quickViewOrder", orderId);
    },
    emptyValues() {
      this.stages = this.blocks = this.total = this.rows = [];
    },
    updateBlock(id, status, type, complete = 0) {
      let key = $("#YK-payment_method_" + id).attr("data-id");

      if (this.blocks[key].pending_requests != 0) {
        this.$bvModal.show(this.pendingRequestPopupId);
        return false;
      }

      let gatwayType = this.blocks[key].order_payment_method;
      let digitalOrderType = 0;
      if (
        this.blocks[key].digital_products == this.blocks[key].total_products
      ) {
        digitalOrderType = 1;
      }
      if (this.blocks[key].transaction) {
        if (
          this.inArray(this.blocks[key].transaction.txn_gateway_type, [
            "cash",
            "card"
          ]) == false
        ) {
          gatwayType = this.blocks[key].transaction.txn_gateway_type;
        }
      }

      if (
        status == "approved" &&
        gatwayType == "cod" &&
        this.blocks[key].payment_status == 2
      ) {
        this.adminsData.payment_method = "Bank Transfer";
        this.adminsData.amount = this.blocks[key].order_amount_charged;
        this.statusData = {
          id: id,
          status: status,
          complete: complete,
          digitalOrderType: digitalOrderType
        };
        this.$bvModal.show(this.codApprovedModelId);
        return false;
      } else if (
        status == "delivered" &&
        gatwayType == "cod" &&
        this.blocks[key].payment_status != 2
      ) {
        this.adminsData.amount = this.blocks[key].order_net_amount;
        this.adminsData.transaction_id = this.$rndStr();
        this.adminsData.payment_method = "cash";
        this.readonly = "true";
        this.statusData = {
          id: id,
          status: status,
          complete: complete,
          digitalOrderType: digitalOrderType
        };
        this.$bvModal.show(this.codApprovedModelId);
        return false;
      } else if (
        status == "shipped" &&
        gatwayType == "cod" &&
        this.blocks[key].payment_status != 2
      ) {
        this.isRequestPending(id, status, complete, digitalOrderType);
        return;
      } else if (status == "delivered" && digitalOrderType == 1) {
        this.digitalAdditionalUpload(id, status, complete, digitalOrderType);
        return;
      } else {
        this.statusData = {
          id: id,
          status: status,
          complete: complete,
          digitalOrderType: digitalOrderType
        };
        if (
          (this.shippingMesagesEnable == 0 && status != "shipped") ||
          (status == "shipped" &&
            digitalOrderType == 1 &&
            this.shippingMesagesEnable == 0)
        ) {
          this.updateStatus(this.statusData);
          return false;
        }
        this.$bvModal.show(this.shippingStatusPopupId);
        return false;
      }
    },
    digitalAdditionalUpload: function(
      orderId,
      status,
      complete,
      digitalOrderType
    ) {
      let formData = this.$serializeData({
        "order-id": orderId
      });
      this.$http
        .post(adminBaseUrl + "/orders/digital-additional-upload", formData)
        .then(response => {
          this.statusData = {
            id: orderId,
            status: status,
            complete: complete,
            digitalOrderType: digitalOrderType,
            uploadInfo: response.data.data
          };
          this.$bvModal.show(this.shippingStatusPopupId);
        });
    },

    closePopup: function(id) {
      this.$bvModal.hide(id);
      this.$bvModal.hide(this.shippingStatusPopupId);
      this.$bvModal.hide(this.codApprovedModelId);
      this.statusData = {};
      this.adminsData = {};
      this.kanbanPageRecords();
    },

    updateStatus(shipStatus, shipComment = "", adminsData = []) {
      let objIndex = this.blocks.findIndex(obj => obj.id == shipStatus.id);
      let formData = this.$serializeData({
        status: shipStatus.status,
        complete: shipStatus.complete,
        type: this.returnstatus,
        amount: this.calulateRefund(this.blocks[objIndex], false),
        "transfer-detail": JSON.stringify(adminsData)
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
        .then(response => {
          toastr.success(response.data.message, "", {
            timeOut: 5000
          });
          this.statusData = {};
          this.message = "";
          this.adminsData = {};
          this.$bvModal.hide(this.shippingStatusPopupId);
          this.$bvModal.hide(this.codApprovedModelId);
          this.kanbanPageRecords();
        });
    },
    markAsCompleted: function(block) {
      let type = "delivered";
      if (block.order_shipping_type == 1) {
        type = "picked-up";
      }
      this.updateBlock(block.id, type, 0, 1);
    },

    sendPaymentLink: function(block) {
      this.shareSuccessMessage = "";
      this.orderBlock = block;
      this.statusData = { id: block.id };
      this.$bvModal.show(this.paymentLinkPopupId);
    },

    inArray: function(needle, haystack) {
      var length = haystack.length;
      for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
      }
      return false;
    },
    undoCompletion: function() {
      this.showUndo = false;
      let formData = this.$serializeData({
        status: this.undoRecordStatus
      });
      this.$http
        .post(adminBaseUrl + "/orders/status/" + this.undoRecordId, formData)
        .then(response => {
          toastr.success(
            this.$t("NOTI_YOUR_PREVIOUS_ACTION_UNDONE"),
            "",
            toastr.options
          );
          this.undoRecordId = "";
          this.undoRecordStatus = "";
          this.kanbanPageRecords();
        });
    },
    downloadPackingSlip: function(orderId) {
      window.open(adminBaseUrl + "/orders/download-packing-slip/" + orderId);
    }
  },
  mounted: function() {
    this.kanbanPageRecords();
  }
};
</script>
<style>
#secondary-toast-container .toast {
  opacity: 1;
  color: white;
}
#secondary-toast-container {
  position: fixed;
  width: 300px;
}
</style>