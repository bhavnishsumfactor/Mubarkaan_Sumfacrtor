<template>
  <li
    class="event"
    v-bind:class="[message.omsg_from_type == 1 ? 'admin' : '', message.omsg_type == 4 ? 'status':'comments', msgKey == 0 ? 'last-status':'']"
  >
    <span class="timeline__date">{{$dateTimeFormat(message.omsg_created_at)}}</span>
    <div v-if="trackingEnabled == true && message.omsg_comment == 'shipped'" class="data">
      <span>
        <a href="javascrit:;" class="dropdown-toggle" @click.prevent="showTrackingInformation()">
          <svg class="svg" width="20px" height="20px">
            <use
              :xlink:href="baseUrl +'/dashboard/media/retina/sprite.svg#'+ message.omsg_comment"
              :href="baseUrl +'/dashboard/media/retina/sprite.svg#'+ message.omsg_comment"
            />
          </svg>
          {{ $t('LBL_SHIPPED_VIA' ) }} {{ order.addition_info.oainfo_courier_name.toUpperCase() }} {{ ' '+ $t('LBL_TRACKING') + '# ' }} {{ order.addition_info.oainfo_tracking_id}}
        </a>
      </span>
    </div>
    <div v-else class="data">
      <span v-if="message.comments">
        <a
          class="dropdown-toggle collapsed"
          data-toggle="collapse"
          :href="'#event'+ message.omsg_id"
          aria-expanded="false"
          v-if="message.omsg_comment == 'shipped'"
          @click="copyTrackingInformation(order.addition_info.oainfo_tracking_id)"
        >
          <svg class="svg" width="20px" height="20px">
            <use
              :xlink:href="baseUrl +'/dashboard/media/retina/sprite.svg#'+ message.omsg_comment"
              :href="baseUrl +'/dashboard/media/retina/sprite.svg#'+ message.omsg_comment"
            />
          </svg>
          {{ $t('LBL_SHIPPED_VIA') }} {{ order.addition_info.oainfo_courier_name.toUpperCase() }} {{ ' ' + $t('LBL_TRACKING') + '# '}} {{ order.addition_info.oainfo_tracking_id}}
        </a>
        <a
          class="dropdown-toggle collapsed"
          data-toggle="collapse"
          :href="'#event'+ message.omsg_id"
          aria-expanded="false"
          v-else
        >
          <!-- <svg class="svg" width="20px" height="20px">
            <use
              :xlink:href="baseUrl +'/dashboard/media/retina/sprite.svg#'+ message.omsg_comment"
              :href="baseUrl +'/dashboard/media/retina/sprite.svg#'+ message.omsg_comment"
            />
          </svg>-->
          {{message.omsg_comment | removeHyphen | camelCase }}
        </a>
        <div class="collapse" :id="'event'+ message.omsg_id" style>
          <div class="mt-3">
            <div class="other-detail" v-html="message.comments.omsg_comment"></div>
          </div>
        </div>
      </span>
      <span v-else>{{message.omsg_comment | removeHyphen | camelCase }}</span>
    </div>
    <tracking-information
      :informations="trackingCheckpoints"
      :orderId="order.order_id"
      v-if="trackingEnabled == true && message.omsg_comment == 'shipped'"
    ></tracking-information>
  </li>
</template>
<script>
import trackingInformation from "@/buyerDashboard/Orders/trackingInformation";
export default {
  props: ["trackingEnabled", "message", "msgKey", "order"],
  components: {
    trackingInformation: trackingInformation
  },
  data: function() {
    return {
      baseUrl: baseUrl,
      trackingPopupId: "trackingInformation",
      trackingCheckpoints: []
    };
  },
  methods: {
    copyTrackingInformation: function(trackingCode) {
      let $temp = $("<input>");
      $("body").append($temp);
      let copyText = trackingCode;
      $temp.val(copyText).select();
      document.execCommand("copy");
      $temp.remove();
      toastr.success("copy successfully", "", toastr.options);
    },
    showTrackingInformation: function() {
      let formData = new FormData();
      formData.append(
        "tracking_number",
        this.order.addition_info.oainfo_tracking_id
      );
      formData.append(
        "courier_name",
        this.order.addition_info.oainfo_courier_name
      );
      formData.append("order_id", this.order.order_id);
      this.$axios
        .post(baseUrl + "/user/order/get-tracking-information", formData)
        .then(
          response => {
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            this.trackingCheckpoints = response.data.data.data;
            this.$bvModal.show(this.trackingPopupId);
          },
          response => {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
        );
      this.$bvModal.show(this.trackingPopupId);
    }
  }
};
</script>