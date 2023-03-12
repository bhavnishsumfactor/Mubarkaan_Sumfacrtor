<template>
  <b-modal
    id="paymentLinkPopup"
    centered
    title="BootstrapVue"
    :hide-footer="true"
    no-close-on-backdrop
  >
    <template v-slot:modal-header="{ close }">
      <h5 class="modal-title">
        <span></span>
        {{ $t('LBL_CHOOSE_METHOD_TO_SHARE_PAYMENT_LINK') }}
      </h5>
      <button type="button" class="close" @click="close()"></button>
    </template>
    <div class="row">
      <div class="col-lg-12">
        <ul class="social-sharing">
          <li v-bind:class="[type == 'sms' ? 'active' : '']">
            <a
              class="icon"
              href="javascript:;"
              v-b-tooltip.hover
              :title="$t('TTL_SHARE_LINK_VIA_SMS')"
              @click="genratePaymentUrl('sms')"
            >
              <i class="fas fa-phone"></i>
            </a>
          </li>
          <li v-bind:class="[type == 'email' ? 'active' : '']">
            <a
              class="icon"
              href="javascript:;"
              v-b-tooltip.hover
              :title="$t('TTL_SHARE_LINK_VIA_EMAIL')"
              @click="genratePaymentUrl('email')"
            >
              <i class="fas fa-envelope"></i>
            </a>
          </li>
          <li v-bind:class="[type == 'copy' ? 'active' : '']">
            <a
              class="icon"
              href="javascript:;"
              v-b-tooltip.hover
              :title="$t('TTL_COPY_PAYMENT_URL')"
              @click="genratePaymentUrl('copy')"
            >
              <i class="fas fa-link"></i>
            </a>
          </li>
        </ul>
        <span v-if="shareSuccessMessage">{{shareSuccessMessage}}</span>
      </div>
    </div>
  </b-modal>
</template>
<script>
export default {
  data: function() {
    return {
      clicked: 0,
      type: ""
    };
  },
  props: ["statusData", "shareSuccessMessage"],
  methods: {
    genratePaymentUrl: function(type) {
      this.type = type;
      let formData = this.$serializeData({
        "order-id": this.statusData.id,
        type: type
      });
      this.$http
        .post(adminBaseUrl + "/order/payment-url", formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }

          if (type == "copy") {
            toastr.success(response.data.message, "", toastr.options);
            this.$bvModal.hide("paymentLinkPopup");
            let copyText = response.data.data;
            let $temp = $("<input>");
            $("body").append($temp);
            $temp.val(copyText).select();
            document.execCommand("copy");
            $temp.remove();
          } else {
            this.shareSuccessMessage = response.data.message;
          }
        });
    }
  },
  watch: {
    statusData() {
      this.type = "";
    }
  }
};
</script>
