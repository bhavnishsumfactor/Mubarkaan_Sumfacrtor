<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'cards'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card">
                  <div class="card-head">
                    <h2 v-if="$mq === 'mobile'">{{ $t("LBL_SAVED_CARDS") }}</h2>
                  </div>
                  <div
                    class="card-body"
                    v-bind:class="[
                      cards.length == 0 &&  loadPage == true ? 'no-data-found-wrap' : '',
                    ]"
                  >
                    <ul class="saved-cards" v-if="loadPage == false">
                      <li class="skeleton-cards" v-for="(i, key) in 2" :key="key">
                        <div class="payment-card__photo">
                          <svg
                            viewBox="0 0 38 24"
                            xmlns="http://www.w3.org/2000/svg"
                            data-yk
                            role="yk-img"
                            width="38"
                            height="24"
                            aria-labelledby="pi-visa"
                            class="svg payment-list__item skeleton"
                          >&nbsp;</svg>
                        </div>
                        <div class="cards-detail my-4">
                          <h6 class="skeleton">&nbsp;</h6>
                          <p class="skeleton">&nbsp;</p>
                        </div>

                        <div class="row justify-content-between">
                          <div class="col-auto">
                            <div class="cards-detail">
                              <h6 class="skeleton">&nbsp;</h6>
                              <p class="skeleton">&nbsp;</p>
                            </div>
                          </div>

                          <div class="col-auto">
                            <div class="cards-detail">
                              <h6 class="skeleton">&nbsp;</h6>
                              <p class="skeleton">&nbsp;</p>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                    <ul class="saved-cards" v-if="cards.length > 0">
                      <card-lists
                        v-for="(card, ckey) in cards"
                        :key="ckey"
                        :cartName="cardName(card)"
                        :cardType="cardType(card)"
                        :cardNumber="cardNumber(card)"
                        :cardId="cardId(card)"
                        :index="ckey"
                        :cardExpire="cardExpireDate(card)"
                        :isDefaultCard="
                          (methodType == 'AuthorizeDotNet' &&
                            card.defaultPaymentProfile) ||
                          (defaultCardId != '' && defaultCardId == card.id)
                            ? 1
                            : 0
                        "
                        @markDefaultCard="markDefaultCard"
                        @confirmDelete="confirmDelete"
                      ></card-lists>
                    </ul>

                    <div
                      class="no-data-found no-data-found--sm"
                      v-if="cards.length == 0 && loadPage == true"
                    >
                      <div class="img">
                        <img
                          data-yk
                          :src="
                            baseUrl +
                            '/dashboard/media/retina/no-saved-cards.svg'
                          "
                          alt
                        />
                      </div>
                      <div class="data">
                        <h2>{{ $t("LBL_NO_CARDS_FOUND") }}</h2>
                        <inertia-link
                          class="btn btn-outline-brand btn-wide"
                          :href="baseUrl + '/user/card/add'"
                        >{{ $t("BTN_ADD_CARD") }}</inertia-link>
                      </div>
                    </div>

                    <inertia-link
                      v-if="cards.length > 0"
                      class="float-add"
                      :href="baseUrl + '/user/card/add'"
                    >+</inertia-link>
                  </div>
                </div>
                <accepted-cards v-if="cards.length != 0"></accepted-cards>
              </main>
            </div>
          </div>
        </div>
        <delete-layout
          :deletePopText="deleteStatus.message"
          :recordId="deleteStatus.id"
          @deleted="deleteRecord(recordId)"
        ></delete-layout>
      </div>
    </section>
  </div>
</template>
<script>
import leftSidebar from "@/buyerDashboard/Sidebar";
import dasboardHeader from "@/buyerDashboard/Header";
import CardLists from "@/buyerDashboard/Account/Cards/Listing";
import AcceptedCards from "@/buyerDashboard/Account/Cards/AcceptedCards";
import deleteLayout from "@/common/popups/delete";

export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader,
    deleteLayout: deleteLayout,
    CardLists: CardLists,
    AcceptedCards: AcceptedCards
  },
  data: function() {
    return {
      baseUrl: baseUrl,
      modelId: "deleteModel",
      deleteStatus: {},
      recordId: 0,
      loadPage: false,
      methodType: "",
      cards: [],
      defaultCardId: ""
    };
  },
  methods: {
    cardName: function(record) {
      if (this.methodType == "AuthorizeDotNet") {
        return record.billTo.firstName;
      }
      return record.name;
    },
    cardType: function(record) {
      if (this.methodType == "AuthorizeDotNet") {
        return record.payment.creditCard.cardType;
      }
      return record.brand;
    },
    cardNumber: function(record) {
      if (this.methodType == "AuthorizeDotNet") {
        return record.payment.creditCard.cardNumber.substr(
          record.payment.creditCard.cardNumber.length - 4
        );
      }
      return record.last4;
    },
    cardId: function(record) {
      if (this.methodType == "AuthorizeDotNet") {
        return record.customerPaymentProfileId;
      }
      return record.id;
    },
    cardExpireDate: function(record) {
      if (this.methodType == "AuthorizeDotNet") {
        return "";
      }
      return record.exp_month + "/" + record.exp_year;
    },
    deleteRecord: function(recordId) {
      this.$axios
        .delete(baseUrl + "/user/card/delete/" + recordId)
        .then(response => {
          if (response.data.status == true) {
            this.$delete(this.cards, this.deleteStatus.index);
            this.$bvModal.hide(this.modelId);
          }
        });
    },
    getListing: function() {
      this.$axios.get(baseUrl + "/user/cards/listing").then(response => {
        this.cards = response.data.data.cards;
        this.methodType = response.data.data.type;
        this.loadPage = true;
        if (response.data.data.defaultCardId) {
          this.defaultCardId = response.data.data.defaultCardId;
        }
      });
    },
    confirmDelete: function(dataid, key) {
      this.recordId = dataid;
      this.deleteStatus = {
        message: this.$t("LBL_DELETE_CARD_CONFIRMATION"),
        id: dataid,
        index: key
      };
      this.$bvModal.show(this.modelId);
    },
    markDefaultCard: function(dataid) {
      if (this.defaultCardId == dataid) {
        return false;
      }
      this.$axios
        .post(baseUrl + "/user/card/set-default/", { cardId: dataid })
        .then(response => {
          if (response.data.status == true) {
            this.defaultCardId = dataid;
          }
        });
    }
  },
  mounted: function() {
    this.getListing();
  }
};
</script>