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
                  <div class="card-body">
                    <div class="card-form">
                      <div class="card-list">
                        <div
                          class="card-item"
                          v-bind:class="{ '-active': isCardFlipped }"
                        >
                          <div class="card-item__side -front">
                            <div
                              class="card-item__focus"
                              v-bind:class="{ '-active': focusElementStyle }"
                              v-bind:style="focusElementStyle"
                              ref="focusElement"
                            ></div>
                            <div class="card-item__cover">
                              <img
                                :src="baseUrl + '/dashboard/media/map.svg'"
                                class="card-item__bg"
                              />
                            </div>
                            <div class="card-item__wrapper">
                              <div class="card-item__top">
                                <img
                                  :src="baseUrl + '/dashboard/media/chip.png'"
                                  class="card-item__chip"
                                />
                                <div class="card-item__type">
                                  <transition name="slide-fade-up">
                                    <img
                                      v-bind:src="
                                        'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' +
                                          getCardType +
                                          '.png'
                                      "
                                      v-if="getCardType"
                                      v-bind:key="getCardType"
                                      alt=""
                                      class="card-item__typeImg"
                                    />
                                  </transition>
                                </div>
                              </div>
                              <label
                                for="cardNumber"
                                class="card-item__number"
                                ref="cardNumber"
                              >
                                <template v-if="getCardType === 'amex'">
                                  <span
                                    v-for="(n, $index) in amexCardMask"
                                    :key="$index"
                                  >
                                    <transition name="slide-fade-up">
                                        <div class="card-item__numberItem" v-if="$index > 4 && $index < 14 && userData.number.length > $index && n.trim() !== ''">
                                            *
                                        </div>
                                        <div class="card-item__numberItem" :class="{ '-active': n.trim() === '' }" :key="$index" v-else-if="userData.number.length > $index">
                                            {{ userData.number[$index] }}
                                        </div>
                                      <div
                                        class="card-item__numberItem"
                                        :class="{ '-active': n.trim() === '' }"
                                        v-else
                                        :key="$index + 1"
                                      >
                                        {{ n }}
                                      </div>
                                    </transition>
                                  </span>
                                </template>

                                <template v-else>
                                  <span
                                    v-for="(n, $index) in otherCardMask"
                                    :key="$index"
                                  >
                                    <transition name="slide-fade-up">
                                      <div
                                        class="card-item__numberItem"
                                        v-if="
                                          $index > 4 &&
                                            $index < 15 &&
                                            userData.number.length > $index &&
                                            n.trim() !== ''
                                        "
                                      >
                                        *
                                      </div>
                                      <div
                                        class="card-item__numberItem"
                                        :class="{ '-active': n.trim() === '' }"
                                        :key="$index"
                                        v-else-if="
                                          userData.number.length > $index
                                        "
                                      >
                                        {{ userData.number[$index] }}
                                      </div>
                                      <div
                                        class="card-item__numberItem"
                                        :class="{ '-active': n.trim() === '' }"
                                        v-else
                                        :key="$index + 1"
                                      >
                                        {{ n }}
                                      </div>
                                    </transition>
                                  </span>
                                </template>
                              </label>
                              <div class="card-item__content">
                                <label
                                  for="cardName"
                                  class="card-item__info"
                                  ref="cardName"
                                >
                                  <div class="card-item__holder">
                                    {{ $t('LBL_CARD_HOLDER') }}
                                  </div>
                                  <transition name="slide-fade-up">
                                    <div
                                      class="card-item__name"
                                      v-if="userData.name.length"
                                      key="1"
                                    >
                                      <transition-group name="slide-fade-right">
                                        <span
                                          class="card-item__nameItem"
                                          v-for="(n,
                                          $index) in userData.name.replace(
                                            /\s\s+/g,
                                            ' '
                                          )"
                                          v-if="$index === $index"
                                          v-bind:key="$index + 1"
                                          >{{ n }}</span
                                        >
                                      </transition-group>
                                    </div>
                                    <div class="card-item__name" v-else key="2">
                                      {{ $t('LBL_FULL_NAME') }}
                                    </div>
                                  </transition>
                                </label>
                                <div class="card-item__date" ref="cardDate">
                                  <label
                                    for="cardMonth"
                                    class="card-item__dateTitle"
                                    >{{ $t('LBL_CARD_EXPIRES') }}</label
                                  >
                                  <label
                                    for="cardMonth"
                                    class="card-item__dateItem"
                                  >
                                    <transition name="slide-fade-up">
                                      <span
                                        v-if="userData.exp_month"
                                        v-bind:key="userData.exp_month"
                                        >{{ userData.exp_month }}</span
                                      >
                                      <span v-else key="2">{{ $t('LBL_CARD_EXPIRE_MONTH') }}</span>
                                    </transition>
                                  </label>
                                  /
                                  <label
                                    for="cardYear"
                                    class="card-item__dateItem"
                                  >
                                    <transition name="slide-fade-up">
                                      <span
                                        v-if="userData.exp_year"
                                        v-bind:key="userData.exp_year"
                                        >{{
                                          String(userData.exp_year).slice(2, 4)
                                        }}</span
                                      >
                                      <span v-else key="2">{{ $t('LBL_CARD_EXPIRE_YEAR') }}</span>
                                    </transition>
                                  </label>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="card-item__side -back">
                            <div class="card-item__cover">
                              <img
                                :src="baseUrl + '/dashboard/media/map.svg'"
                                class="card-item__bg"
                              />
                            </div>
                            <div class="card-item__band"></div>
                            <div class="card-item__cvv">
                              <div class="card-item__cvvTitle">{{ $t('LBL_CVV') }}</div>
                              <div class="card-item__cvvBand">
                                <span
                                  v-for="(n, $index) in userData.cvv"
                                  :key="$index"
                                >
                                  *
                                </span>
                              </div>
                              <div class="card-item__type">
                                <img
                                  v-bind:src="
                                    'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' +
                                      getCardType +
                                      '.png'
                                  "
                                  v-if="getCardType"
                                  class="card-item__typeImg"
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form card-form__inner">
                        <div class="form-group ">
                          <label for="cardNumber" class="label">{{
                            $t("LBL_CARD_NUMBER")
                          }}</label>
                          <input
                            type="text"
                            id="cardNumber"
                            class="form-control"
                            v-model="userData.number"
                            v-on:focus="focusInput"
                            v-on:blur="blurInput"
                            data-ref="cardNumber"
                            autocomplete="off"
                            v-bind:class="[errors.number ? 'is-invalid' : '']"
                          />
                        </div>
                        <div class="form-group ">
                          <label for="cardName" class="label">{{
                            $t("LBL_CARD_HOLDER")
                          }}</label>
                          <input
                            type="text"
                            id="cardName"
                            class="form-control"
                            v-model="userData.name"
                            v-bind:class="[errors.name ? 'is-invalid' : '']"
                            v-on:focus="focusInput"
                            v-on:blur="blurInput"
                            data-ref="cardName"
                            autocomplete="off"
                          />
                        </div>

                        <div class="form-group card-form__row">
                          <div class="card-form__col">
                            <div class="card-form__group">
                              <label for="cardMonth" class="label">{{
                                $t("LBL_EXPIRATION_DATE")
                              }}</label>
                              <select
                                class="form-control -select"
                                id="cardMonth"
                                v-model="userData.exp_month"
                                v-on:focus="focusInput"
                                v-on:blur="blurInput"
                                data-ref="cardDate"
                              >
                                <option value="" disabled selected>{{
                                  $t("LBL_MONTH")
                                }}</option>
                                <option
                                  v-bind:value="n < 10 ? '0' + n : n"
                                  v-for="n in 12"
                                  v-bind:disabled="n < minCardMonth"
                                  v-bind:key="n"
                                >
                                  {{ n < 10 ? "0" + n : n }}
                                </option>
                              </select>
                              <select
                                class="form-control -select"
                                id="cardYear"
                                v-model="userData.exp_year"
                                v-on:focus="focusInput"
                                v-on:blur="blurInput"
                                data-ref="cardDate"
                              >
                                <option value="" disabled selected>{{
                                  $t("LBL_YEAR")
                                }}</option>
                                <option
                                  v-bind:value="$index + minCardYear"
                                  v-for="(n, $index) in 12"
                                  v-bind:key="n"
                                >
                                  {{ $index + minCardYear }}
                                </option>
                              </select>
                            </div>
                          </div>
                          <div class="card-form__col -cvv">
                            <div class="">
                              <label for="cardCvv" class="label">{{
                                $t("LBL_CVV")
                              }}</label>
                              <input
                                type="text"
                                class="form-control"
                                id="cardCvv"
                                maxlength="4"
                                v-model="userData.cvv"
                                v-bind:class="[errors.cvv ? 'is-invalid' : '']"
                                v-on:focus="flipCard(true)"
                                v-on:blur="flipCard(false)"
                                autocomplete="off"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card-footer">

                    <div class="card-form-footer">
                      <inertia-link
                        class="btn btn-outline-gray btn-wide"
                        :href="baseUrl + '/user/cards'"
                        >{{ $t("BTN_DISCARD") }}</inertia-link
                      >

                      <button
                        class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                        type="button"
                        v-bind:class="[sending == true ? 'gb-is-loading' : '']"
                        :disabled="isComplete"
                        @click="saveCard(), (sending = true)"
                      >
                        {{ $t("BTN_SAVE_CARD") }}
                      </button>
                    </div>
                  </div>

                  <!--<form class="form form-floating fluid-height">
                    <div class="card-body">
                      <div class="row justify-content-center pt-6">
                        <div class="col-lg-8">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <input
                                  class="form-control form-floating__field"
                                  type="text"
                                  v-model="userData.name"
                                  v-bind:class="[
                                      userData.name != '' ? 'filled' : '',
                                      errors.name ? 'is-invalid' : '',
                                    ]"
                                />
                                <label class="form-floating">
                                  {{
                                  $t("LBL_CARDHOLDERS_NAME")
                                  }}
                                </label>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <input
                                  class="form-control form-floating__field"
                                  type="text"
                                  v-model="userData.number"
                                  v-bind:class="[
                                      userData.number != '' ? 'filled' : '',
                                      errors.number ? 'is-invalid' : '',
                                    ]"
                                />
                                <label class="form-floating">
                                  {{
                                  $t("LBL_CARD_NUMBER")
                                  }}
                                </label>
                                <span>
                                  <svg class="svg" width="24px" height="24px">
                                    <use
                                      :xlink:href="
                                          baseUrl +
                                          '/dashboard/media/retina/sprite.svg#cc-card'
                                        "
                                      :href="
                                          baseUrl +
                                          '/dashboard/media/retina/sprite.svg#cc-card'
                                        "
                                    />
                                  </svg>
                                </span>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group form-floating__group">
                                <select
                                  class="form-control form-floating__field filled"
                                  v-model="userData.exp_month"
                                >
                                  <option value>{{ $t("LBL_MONTH") }}</option>
                                  <option
                                    :value="month > 9 ? month : '0' + month"
                                    v-for="(month, mKey) in 12"
                                    :key="mKey"
                                  >{{ month > 9 ? month : "0" + month }}</option>
                                </select>
                                <label class="form-floating">
                                  {{
                                  $t("LBL_MONTH")
                                  }}
                                </label>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group form-floating__group">
                                <select
                                  class="form-control form-floating__field filled"
                                  v-model="userData.exp_year"
                                >
                                  <option value>{{ $t("LBL_YEAR") }}</option>

                                  <option
                                    :value="parseInt(cYear) + parseInt(yKey)"
                                    v-for="(year, yKey) in 11"
                                    :key="yKey"
                                  >{{ parseInt(cYear) + parseInt(yKey) }}</option>
                                </select>
                                <label class="form-floating">
                                  {{
                                  $t("LBL_YEAR")
                                  }}
                                </label>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group form-floating__group">
                                <input
                                  class="form-control form-floating__field"
                                  type="text"
                                  @keypress="$onlyNumber"
                                  v-model="userData.cvv"
                                  v-bind:class="[
                                      userData.cvv != '' ? 'filled' : '',
                                      errors.cvv ? 'is-invalid' : '',
                                    ]"
                                />
                                <label class="form-floating">{{ $t("LBL_CVV") }}</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                  <div class="card-footer">
                    <div class="row justify-content-center">
                      <div class="col-lg-8">
                        <div class="footer-actions">
                          <inertia-link
                            class="btn btn-outline-gray btn-wide"
                            :href="baseUrl + '/user/cards'"
                          >{{ $t("BTN_DISCARD") }}</inertia-link>

                          <button
                            class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                            type="button"
                            v-bind:class="[
                              sending == true ? 'gb-is-loading' : '',
                            ]"
                            :disabled="isComplete"
                            @click="saveCard(), (sending = true)"
                          >{{ $t("BTN_SAVE_CARD") }}</button>
                        </div>
                      </div>
                    </div>
                  </div>-->
                </div>
              </main>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import leftSidebar from "@/buyerDashboard/Sidebar";
import dasboardHeader from "@/buyerDashboard/Header";

const tableFields = {
  name: "",
  number: "",
  exp_month: "",
  exp_year: "",
  cvv: "",
};
export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader,
  },
  props: {
    errors: Object,
    gatewayErrors: String,
  },

  data: function() {
    return {
      sending: false,
      baseUrl: baseUrl,
      userData: tableFields,
      cYear: new Date().getFullYear(),
      cardName: "",
      cardNumber: "",
      cardMonth: "",
      cardYear: "",
      cardCvv: "",
      minCardYear: new Date().getFullYear(),
      amexCardMask: "#### ###### #####",
      otherCardMask: "#### #### #### ####",
      cardNumberTemp: "",
      isCardFlipped: false,
      focusElementStyle: null,
      isInputFocused: false,
    };
  },

  methods: {
    flipCard(status) {
      this.isCardFlipped = status;
    },
    focusInput(e) {
      this.isInputFocused = true;
      let targetRef = e.target.dataset.ref;
      let target = this.$refs[targetRef];
      this.focusElementStyle = {
        width: `${target.offsetWidth}px`,
        height: `${target.offsetHeight}px`,
        transform: `translateX(${target.offsetLeft}px) translateY(${target.offsetTop}px)`,
      };
    },
    blurInput() {
      let vm = this;
      setTimeout(() => {
        if (!vm.isInputFocused) {
          vm.focusElementStyle = null;
        }
      }, 300);
      vm.isInputFocused = false;
    },
    saveCard: function() {
      let formData = this.$serializeData(this.userData);
      this.$inertia.post(baseUrl + "/user/savecard", formData, {
        onFinish: () => (this.sending = false),
      });
    },
    emptyFormValues: function() {
      this.userData = {
        name: "",
        number: "",
        exp_month: "",
        exp_year: "",
        cvv: "",
      };
    },
  },
  mounted: function() {
    this.emptyFormValues();
    this.cardNumberTemp = this.otherCardMask;
    document.getElementById("cardNumber").focus();
  },
  watch: {
    gatewayErrors() {
      if (this.gatewayErrors !== "") {
        toastr.error(this.gatewayErrors, "", toastr.options);
      }
    },
    errors() {
      let thisVal = this;
      Object.keys(thisVal.errors).forEach(function(key) {
        toastr.error(thisVal.errors[key], "", toastr.options);
      });
    },
    cardYear() {
      if (this.userData.exp_month < this.minCardMonth) {
        this.userData.exp_month = "";
      }
    },
  },
  computed: {
    getCardType() {
      let number = this.userData.number;
      let re = new RegExp("^4");
      if (number.match(re) != null) return "visa";

      re = new RegExp("^(34|37)");
      if (number.match(re) != null) return "amex";

      re = new RegExp("^5[1-5]");
      if (number.match(re) != null) return "mastercard";

      re = new RegExp("^6011");
      if (number.match(re) != null) return "discover";

      re = new RegExp("^9792");
      if (number.match(re) != null) return "troy";

      return "visa"; // default type
    },
    generateCardNumberMask() {
      return this.getCardType === "amex"
        ? this.amexCardMask
        : this.otherCardMask;
    },
    minCardMonth() {
      if (this.userData.exp_year === this.minCardYear)
        return new Date().getMonth() + 1;
      return 1;
    },
    isComplete() {
      return (
        this.userData.name == "" ||
        this.userData.number == "" ||
        this.userData.exp_month == "" ||
        this.userData.exp_year == "" ||
        this.userData.cvv == ""
      );
    },
  },
};
</script>
