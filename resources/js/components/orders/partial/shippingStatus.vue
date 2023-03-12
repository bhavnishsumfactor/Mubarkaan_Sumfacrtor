<template>
  <b-modal id="shippingStatusPopup" centered title="BootstrapVue" no-close-on-backdrop>
    <template v-slot:modal-header="{ close }">
      <h5 class="modal-title">
        <span v-if="statusData && statusData.complete == 1">
          {{ $t("LBL_CHANGING_STATUS_FOR") }}
          {{ url != "" ? $t("LBL_REQUEST") : $t("LBL_ORDER") }} #{{
          statusData.id
          }}
          {{ $t("LBL_TO_COMPLETE") }}
        </span>
        <span v-else>
          {{ $t("LBL_CHANGING_STATUS_FOR") }}
          {{ url != "" ? $t("LBL_REQUEST") : $t("LBL_ORDER") }} #{{
          statusData.id
          }}
          {{ $t("LBL_TO") }} {{ statusData.status | removeHyphen }}
        </span>
      </h5>
      <button type="button" class="close" @click="closePopup()"></button>
    </template>
    <div class="row" v-if="statusData.digitalCount && statusData.uploadInfo">
      <div class="col-lg-12" v-for="(uploadList, index) in statusData.uploadInfo" :key="index">
        <div class="form-group" v-for="record in uploadList.op_qty" :key="record">
          <label>{{ uploadList.op_product_name + " " + record }}</label>
          <input
            type="file"
            name="courier_name"
            class="form-control"
            @change="
                uploadFile(
                  $event,
                  uploadList.op_id,
                  statusData.id,
                  uploadList.op_product_id
                )
              "
          />
        </div>
      </div>
    </div>
    <div class="row" v-if="statusData.status == 'shipped' && statusData.digitalOrderType == 0">
   
      <div class="col-lg-6">
        <div class="form-group" v-if="typeof shippingCourier != 'undefined' && Object.keys(shippingCourier).length > 0">
          <label>{{ $t("LBL_SHIPPING_CARRIER") }}</label>
          <select
            class="form-control"
            v-model="commentData.courier_name"
            name="courier_name"
            v-validate="'required'"
            :data-vv-as="$t('LBL_SHIPPING_CARRIER')"
            :disabled="Object.keys(shippingCourier).length == 1"
            data-vv-validate-on="none"
          >
            <option disabled value>{{ $t("LBL_SHIPPING_CARRIER") }}</option>
            <option
              v-for="(couriers, index) in shippingCourier"
              :key="index"
              :value="index"
            >{{ couriers }}</option>
          </select>
          <span v-if="errors.first('courier_name')" class="error text-danger">
            {{
            errors.first("courier_name")
            }}
          </span>
        </div>
        <div class="form-group" v-else>
          <label>{{ $t("LBL_SHIPPING_CARRIER") }}</label>
          <input
            type="text"
            class="form-control"
            v-model="commentData.courier_name"
            name="courier_name"
            v-validate="'required'"
            data-vv-validate-on="none"
            :data-vv-as="$t('LBL_SHIPPING_CARRIER')"
          />
          <span v-if="errors.first('courier_name')" class="error text-danger">
            {{
            errors.first("courier_name")
            }}
          </span>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group">
          <label>{{ $t("LBL_TRACKING_NUMBER") }}</label>
          <input
            type="text"
            class="form-control"
            v-model="commentData.tracking_number"
            name="tracking_number"
            v-validate="'required'"
            data-vv-validate-on="none"
            :data-vv-as="$t('LBL_TRACKING_NUMBER')"
          />
          <span
            v-if="errors.first('tracking_number')"
            class="error text-danger"
          >{{ errors.first("tracking_number") }}</span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <label>{{ $t("LBL_COMMENTS") }}</label>
          <textarea
            name="message"
            v-model="commentData.message"
            class="form-control"
            spellcheck="false"
            data-vv-validate-on="none"
          ></textarea>
        </div>
      </div>
    </div>
    <template v-slot:modal-footer>
      <button
        type="button"
        class="btn btn-brand"
        :disabled="statusChangeSaveClicked == 1 || uploaded == 0"
        @click="validateShippingStatus"
      >{{ $t("BTN_SHIPPING_STATUS_UPDATE") }}</button>
    </template>
  </b-modal>
</template>
<script>
const commentsFields = {
  message: "",
  tracking_number: "",
  courier_name: ""
};
export default {
  data: function() {
    return {
      clicked: 0,
      statusChangeSaveClicked: 0,
      uploaded: 1,
      commentData: commentsFields
    };
  },
  props: ["statusData", "url", "shippingCourier"],
  methods: {
    uploadFile: function(event, index, orderId, productId) {
      this.uploaded = 0;
      let formData = new FormData();
      formData.append("file", event.target.files[0]);
      formData.append("op_id", index);
      formData.append("order_id", orderId);
      formData.append("product_id", productId);
      this.$http
        .post(adminBaseUrl + "/orders/upload-digital-file", formData)
        .then(response => {
          this.uploaded = 1;
        });
    },
    validateShippingStatus: function() {
      this.$validator.validateAll(this.commentData).then(res => {
        if (res) {
          this.statusChangeSaveClicked = 1;
          this.$emit("updateStatus", this.statusData, this.commentData);
        } else {
          this.statusChangeSaveClicked = 0;
        }
      });
    },
    closePopup: function() {
      this.$emit("closePopup");
    }
  },
  watch: {
    statusData() {
      this.commentData.message = "";
      this.commentData.tracking_number = "";
      this.commentData.courier_name = "";
      this.statusChangeSaveClicked = 0;
    }
  }
};
</script>
