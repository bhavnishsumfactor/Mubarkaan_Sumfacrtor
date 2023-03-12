<template>
  <li v-bind:class="addClass(reward.urp_type, reward.points)">
    <div
      class="points-timeline__block"
      block
      v-b-toggle="'accordion' + reward.urp_order_id + '' +requestId"
      v-bind:class="[
        reward.order_products.length != 0 && reward.urp_type != 4
          ? 'dropdown-toggle'
          : '',
      ]"
    >
      <div class="points-timeline__block_media">
        <img data-yk :src="addImage(reward.urp_type, reward)" alt />
      </div>
      <div class="points-timeline__block_data">
        <div class="points-timeline__block_title" v-html="rewardTitle(reward.urp_type, reward)"></div>
        <div class="points-timeline__block_date">{{ $dateTimeFormat(reward.date, "date") }}</div>

        <div class="points-timeline__block_origin" v-html="rewardMessage(reward.urp_type, reward)"></div>
      </div>
    </div>
    <b-collapse :id="'accordion' + reward.urp_order_id + '' +requestId">
      <ul
        class="points-list-group scroll-y"
        v-if="reward.order_products.length != 0 && reward.urp_type != 4"
      >
        <li
          class="points-list-group-item"
          v-for="(product, pKey) in reward.order_products"
          :key="pKey"
          v-if="(reward.refund_products !== null ? (reward.refund_products.op_id == product.op_id  && product.reward_points.opc_value) : product.reward_points.opc_value)"
          v-bind:class="[reward.urp_type != 5 && product.refund_points ? 'disabled' : '']"
        >
      
          <a target="_blank" :href="baseUrl + '/product/' + product.op_product_id">
            <div class="product-profile">
              <div class="product-profile__thumbnail">
                <img
                  :src="
                    $productImage(product.op_product_id, product.op_pov_code, product.images ? product.images.afile_updated_at : '', '48-64')
                  "
                  data-yk
                  alt
                />
              </div>
              <div class="product-profile__data">
                <div class="title">{{ product.op_product_name }}</div>
                <div class="options">
                  <strong>{{ product.reward_points.opc_value }}</strong>
                  {{ $t("LBL_ORDER_EARNED_POINTS_MESSAGE") }}
                </div>
              </div>
            </div>
          </a>
        </li>
      </ul>
    </b-collapse>
  </li>
</template>
<script>
import imageAttributes from "../../../components/seo/imageAttributes.vue";
export default {
  components: { imageAttributes },
  props: ["rewardTypes", "reward", "requestId"],
  data: function() {
    return {
      baseUrl: baseUrl,
      type: ""
    };
  },
  methods: {
    rewardTitle: function(type, reward) {
      switch (this.$cleanString(this.rewardTypes[type])) {
        case "orderearnedpoint":
          return (
           '<span class="text-success">'+ this.calculateTotal(reward.order_products) +
            "</span> " +
            this.$t("LBL_POINTS_EARNED") + " #" + reward.urp_order_id
          );
          break;
        case "orderredeemedpoint":
          return '<span class="text-danger">-' + Math.abs(reward.points) + "</span> " + this.$t("LBL_POINTS_REDEEMED");
          break;
        case "expiredpoint":
          return '<span class="text-danger">-' + reward.points + "</span> " + this.$t("LBL_POINTS_EXPIRED");
          break;
        case "birthdaypoint":
          return '<span class="text-success">'+reward.points + "</span> " + this.$t("LBL_POINTS_EARNED");
          break;
        case "registrationpoint":
          return '<span class="text-success">'+reward.points + "</span> " + this.$t("LBL_POINTS_EARNED");
          break;
        case "adminupdatedpoint":
          return (
            '<span class="text-success">'+Math.abs(reward.points) +
            "</span> " +
            this.$t("LBL_POINTS") +
            " " +
            (reward.points > 0
              ? this.$t("LBL_POINTS_CREDITED")
              : this.$t("LBL_POINTS_DEBITED"))
          );
          break;
        case "signupbonus":
          return (
             '<span class="text-success">'+Math.abs(reward.points) +
            "</span> " +
            this.$t("LBL_POINTS_EARNED_AS_SIGNUP_BONUS")
          );
          break;
        case "returnedpoints":
          return (
            '<span class="text-danger">-'  + Math.abs(reward.points) +
            "</span> " +
            this.$t("LBL_POINTS_RETURNED") + " #" + reward.urp_order_id
          );
          break;
        default:
          return "";
      }
    },
    rewardMessage: function(type, reward) {
      switch (this.$cleanString(this.rewardTypes[type])) {
        case "registrationpoint":
          var identifier = "";
          if (reward.referral_phone) {
            identifier =
              (reward.country_phonecode != ""
                ? "+" + reward.country_phonecode + " "
                : "") +
              "" +
              reward.referral_phone;
          } else if (reward.referral_email) {
            identifier = reward.referral_email;
          } else {
            identifier = this.$t("LBL_ANONYMOUS");
          }
          return (
            this.$t("LBL_HURRAY") +
            "! " +
            identifier +
            " " +
            this.$t("LBL_JOINED") +
            " " +
            this.$configVal("BUSINESS_NAME")
          );
          break;
        case "birthdaypoint":
          return (
            this.$t("LBL_HAPPY_BIRTHDAY_HERE_ARE") +
            " " +
            reward.points +
            " " +
            this.$t("LBL_POINTS_FOR_BIRTHDAY")
          );
          break;
        case "orderredeemedpoint":
          return (
            Math.abs(reward.points) +
            " " +
            this.$t("LBL_ORDER_REDEEMED_POINTS_MESSAGE") +
            " #" +
            reward.urp_order_id
          );
          break;
        case "expiredpoint":
          return "";
          break;
        case "adminupdatedpoint":
          return reward.comments;
          break;
        default:
          return "";
      }
    },
    addClass: function(type, points) {
      switch (this.$cleanString(this.rewardTypes[type])) {
        case "registrationpoint":
          return "referral";
          break;
        case "birthdaypoint":
          return "birthday";
          break;
        case "orderearnedpoint":
          return "earned";
          break;
        case "orderredeemedpoint":
          return "redeemed";
          break;
        case "expiredpoint":
          return "expired";
          break;
        case "returnedpoints":
          return "returned";
          break;
        case "adminupdatedpoint":
          return points > 0 ? "credited" : "debited";
          break;
        default:
          return "expired";
      }
    },
    addImage: function(type, reward) {
      switch (this.$cleanString(this.rewardTypes[type])) {
        case "registrationpoint":
          return baseUrl + "/dashboard/media/retina/signup-points.svg";
          break;
        case "birthdaypoint":
          return baseUrl + "/dashboard/media/retina/birthday-points.svg";
          break;
        case "orderearnedpoint":
          return (
            baseUrl + "/dashboard/media/retina/points-earned-for-product.svg"
          );
          break;
        case "orderredeemedpoint":
          return (
            baseUrl +
            "/dashboard/media/retina/product-return-deduction-points.svg"
          );
          break;
        case "expiredpoint":
          return baseUrl + "/dashboard/media/retina/expired-points.svg";
          break;
        case "returnedpoints":
          return baseUrl + "/dashboard/media/retina/returned_points.svg";
          break;
        case "adminupdatedpoint":
          if (reward.points > 0) {
            return baseUrl + "/dashboard/media/retina/credited-points.svg";
          } else {
            return baseUrl + "/dashboard/media/retina/dedited-points.svg";
          }
          break;
        default:
          return baseUrl + "/dashboard/media/retina/credited-points.svg";
      }
    },
    calculateTotal: function(products) {
      let total = 0;
      Object.keys(products).forEach(function(key) {
        total += parseFloat(products[key].reward_points.opc_value);
      });
      return total;
    }
  }
};
</script>
