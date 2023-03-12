<template>
  <li v-bind:class="[isDefaultCard ? 'selected' : '', getCardType(cardType)]">
    <ul class="list-actions">
      <li
       v-b-tooltip.hover
        title="This is your default card"
        v-if="isDefaultCard"
      >
        <label class="radio">
          <input name="card_id" type="radio" checked="checked" />
          <i class="input-helper"></i>
        </label>
      </li>

      <li
        v-b-tooltip.hover
        title="Set as default card"
        v-else
      >
        <label class="radio">
          <input
            name="card_id"
            type="radio"
            @click.capture="markDefaultCard(cardId)"
          />
          <i class="input-helper"></i>
        </label>
      </li>
      <li  v-b-tooltip.hover title="Remove Card">
        <a href="javascript:;" @click.capture="confirmDelete(cardId, index)">
          <svg class="svg" width="24px" height="24px">
            <use
              :xlink:href="baseUrl + '/dashboard/media/retina/sprite.svg#bin'"
              :href="baseUrl + '/dashboard/media/retina/sprite.svg#bin'"
            ></use>
          </svg>
        </a>
      </li>
    </ul>
    <div class="payment-card__photo">
      <card-icons :cardType="$cleanString(cardType)"></card-icons>
    </div>
    <div class="cards-detail my-4">
      <h6>{{ $t("LBL_CARD_NUMBER") }}</h6>
      <p>**** **** **** {{ cardNumber }}</p>
    </div>

    <div class="row justify-content-between">
      <div class="col-auto">
        <div class="cards-detail">
          <h6>{{ $t("LBL_CARD_HOLDER") }}</h6>
          <p>{{ cartName }}</p>
        </div>
      </div>

      <div class="col-auto">
        <div class="cards-detail" v-if="cardExpire != ''">
          <h6>{{ $t("LBL_EXPIRY_DATE") }}</h6>
          <p>{{ cardExpire }}</p>
        </div>
      </div>
    </div>
  </li>
</template>
<script>
import CardIcons from "@/common/CardIcons";

export default {
  props: [
    "cartName",
    "cardType",
    "cardNumber",
    "cardId",
    "cardExpire",
    "isDefaultCard",
    "index",
  ],
  components: {
    CardIcons: CardIcons,
  },
  data: function () {
    return {
      baseUrl: baseUrl,
    };
  },
  methods: {
    confirmDelete: function (dataid, key) {
      this.$emit("confirmDelete", dataid, key);
    },
    markDefaultCard: function (dataid) {
      this.$emit("markDefaultCard", dataid);
    },
    getCardType : function(cardType) {
        if (cardType != '') {
            return (cardType.toLowerCase()).split(" ").join('_');
        }
        
    }
  },
};
</script>