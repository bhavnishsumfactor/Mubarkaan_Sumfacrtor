<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'address'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card">
                  <div class="card-head">
                    <h2 v-if="$mq === 'mobile'">{{ $t('LBL_ADDRESS_BOOK') }}</h2>
                  </div>
                  <div
                    class="card-body"
                    v-bind:class="[
                      addresses.length == 0 ? 'no-data-found-wrap' : '',
                    ]"
                  >
                    <ul class="my-addresses" v-if="addresses.length > 0">
                      <li v-for="(address, addressKey) in addresses" :key="addressKey">
                        <div class="my-addresses__body">
                          <address class="delivery-address">
                            <div class="dropdown action">
                              <a
                                class
                                data-toggle="dropdown"
                                href="javascript:;"
                                aria-expanded="false"
                              >
                                <i class="icn">
                                  <svg class="svg">
                                    <use
                                      :xlink:href="
                                        baseUrl +
                                          '/dashboard/media/retina/sprite.svg#options'
                                      "
                                    />
                                  </svg>
                                </i>
                              </a>

                              <div
                                class="dropdown-menu dropdown-menu-fit dropdown-menu-anim dropdown-menu-right"
                              >
                                <ul class="nav nav--block">
                                  <li class="nav__item">
                                    <inertia-link
                                      class="nav__link"
                                      :href="
                                        baseUrl +
                                          '/user/address/' +
                                          address.addr_id +
                                          '/edit'
                                      "
                                    >
                                      <span class="nav__link-text">{{ $t("BTN_EDIT") }}</span>
                                    </inertia-link>
                                  </li>
                                  <li class="nav__item">
                                    <a
                                      class="nav__link"
                                      href="javascript:;"
                                      @click.capture="
                                        confirmDelete(address.addr_id)
                                      "
                                    >
                                      <span class="nav__link-text">
                                        {{
                                        $t("BTN_DELETE")
                                        }}
                                      </span>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </div>

                            <h5>
                              <span
                                class="address-title"
                                v-b-tooltip.hover
                                :title="
                                  address.addr_first_name +
                                    ' ' +
                                    address.addr_last_name
                                "
                              >
                                {{
                                address.addr_first_name +
                                " " +
                                address.addr_last_name
                                }}
                              </span>
                              <span
                                class="tag"
                                v-b-tooltip.hover
                                :title="address.addr_title"
                              >{{ address.addr_title }}</span>
                            </h5>
                            <p>
                              {{
                              address.addr_apartment
                              ? address.addr_apartment
                              : ""
                              }}
                              {{
                              address.addr_address1
                              ? address.addr_address1
                              : ""
                              }}
                            </p>
                            <p>
                              {{
                              address.addr_address2
                              ? address.addr_address2
                              : ""
                              }}
                            </p>
                            <p>{{ address.addr_city }}</p>

                            <p>
                              {{ address.state.state_name }}
                              {{ address.addr_postal_code }}
                            </p>
                            <p>{{ address.country.country_name }}</p>
                          </address>
                        </div>
                        <div class="my-addresses__footer">
                          <p class="phone-txt">
                            <i class="icn">
                              <svg class="svg" width="16px" height="16px">
                                <use
                                  :xlink:href="baseUrl+'/dashboard/media/retina/sprite.svg#phone'"
                                  :href="baseUrl+'/dashboard/media/retina/sprite.svg#phone'"
                                />
                              </svg>
                            </i>
                            {{
                            "+" +
                            address.phonecountry.country_phonecode +
                            " " +
                            address.addr_phone
                            }}
                          </p>
                          <ul class="chips">
                            <li
                              v-bind:class="[
                                address.addr_shipping_default == 1
                                  ? 'is-active'
                                  : '',
                              ]"
                            >
                              <a
                                href="javascript:;"
                                v-b-tooltip.hover
                                :title="$t('TTL_DEFAULT_SHIPPING_ADDRESS')"
                                class="chip"
                                v-if="address.addr_shipping_default == 1"
                              >
                                <i class="fas fa-shipping-fast"></i>
                              </a>
                              <a
                                href="javascript:;"
                                v-b-tooltip.hover
                                :title="$t('TTL_SET_SHIPPING_DEFAULT')"
                                class="chip"
                                @click="
                                  updateBillingStatus(
                                    address.addr_id,
                                    'addr_shipping_default'
                                  )
                                "
                                v-else
                              >
                                <i class="fas fa-shipping-fast"></i>
                              </a>
                            </li>
                            <li
                              v-bind:class="[
                                address.addr_billing_default == 1
                                  ? 'is-active'
                                  : '',
                              ]"
                            >
                              <a
                                href="javascript:;"
                                v-b-tooltip.hover
                                :title="$t('TTL_DEFAULT_BILLING_ADDRESS')"
                                class="chip"
                                v-if="address.addr_billing_default == 1"
                              >
                                <i class="fas fa-file-invoice"></i>
                              </a>
                              <a
                                href="javascript:;"
                                v-b-tooltip.hover
                                :title="$t('TTL_SET_BILLING_DEFAULT')"
                                class="chip"
                                @click="
                                  updateBillingStatus(
                                    address.addr_id,
                                    'addr_billing_default'
                                  )
                                "
                                v-else
                              >
                                <i class="fas fa-file-invoice"></i>
                              </a>
                            </li>
                          </ul>
                        </div>
                      </li>
                    </ul>
                    <infinite-loading :identifier="infiniteId" @infinite="infiniteHandler"></infinite-loading>
                    <div
                      class="no-data-found no-data-found--sm"
                      v-if="addresses.length == 0 && pageLoad == true"
                    >
                      <div class="img">
                        <img
                          data-yk
                          :src="
                            baseUrl + '/dashboard/media/retina/no-address.svg'
                          "
                          alt
                        />
                      </div>
                      <div class="data">
                        <h2>{{ $t("LBL_NO_ADDRESS_FOUND") }}</h2>
                      </div>
                    </div>
                    <inertia-link class="float-add" :href="baseUrl + '/user/address/add'">+</inertia-link>
                  </div>
                </div>
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
import deleteLayout from "@/common/popups/delete";

export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader,
    deleteLayout: deleteLayout
  },
  data: function() {
    return {
      baseUrl: baseUrl,
      modelId: "deleteModel",
      deleteStatus: {},
      addresses: [],
      page: 1,
      infiniteId: +new Date(),
      pageLoad: false,
      recordId: 0
    };
  },
  methods: {
    infiniteHandler($state) {
      let formData = this.$serializeData({
        total: this.addresses.length
      });

      this.$axios
        .post(baseUrl + "/user/address/load-records", formData)
        .then(response => {
          if (response.data.data.length) {
            this.page += 1;
            this.addresses.push(...response.data.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        });
    },
    getListing: function() {
      this.page = 1;
      this.infiniteId += 1;
      this.$axios.get(baseUrl + "/user/address/listing").then(response => {
        this.pageLoad = true;
        this.addresses = response.data.data.data;
      });
    },
    confirmDelete: function(dataid) {
      this.recordId = dataid;
      this.deleteStatus = {
        message: this.$t("LBL_DELETE_ADDRESS_CONFIRMATION"),
        id: dataid
      };
      this.$bvModal.show(this.modelId);
    },
    deleteRecord: function(recordId) {
      this.$axios
        .post(baseUrl + "/user/address/delete", {
          addr_id: recordId
        })
        .then(response => {
          this.$bvModal.hide(this.modelId);
          this.getListing();
        });
  
    },
    updateBillingStatus: function(recordId, type) {
      this.$axios
        .post(baseUrl + "/user/address/status-update", {
          addr_id: recordId,
          type: type
        })
        .then(response => {
             this.$bvModal.hide(this.modelId);
          this.getListing();
        });
    }
  },
  mounted: function() {
    this.getListing();
  }
};
</script>
