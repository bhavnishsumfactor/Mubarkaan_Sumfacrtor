<template>
  <div class="header-menu-holder">
    <menubar v-if="$mq === 'desktop' || $mq === 'laptop'" :isTablet="false"></menubar>

    <div class="header__topbar">
      <div class="header__topbar-item" id="quick_search_toggle">
        <div class="header__topbar-wrapper" @click="openModal()">
          <span class="header__topbar-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink"
              width="24px"
              height="24px"
              viewBox="0 0 24 24"
              version="1.1"
              class="svg-icon"
            >
              <g transform="translate(-507 -2549)">
                <g transform="translate(508 2550)">
                  <g transform="translate(0 0)">
                    <path
                      d="M21.865,21.217l-6.694-6.694a8.741,8.741,0,1,0-.648.648l6.694,6.694a.458.458,0,1,0,.648-.648ZM8.708,16.5A7.792,7.792,0,1,1,16.5,8.708,7.8,7.8,0,0,1,8.708,16.5Z"
                      transform="translate(0 0)"
                    />
                  </g>
                </g>
              </g>
            </svg>
          </span>
        </div>
        <b-modal
          id="modal-1"
          title="BootstrapVue"
          :body-class="'p-0'"
          centered
          hide-header
          hide-footer
        >
          <div class="dropdown-menu--search">
            <div class="quick-search">
              <div class="SearchBar">
                <div class="dummy-content" v-if="searchKeyword == ''">
                  Search your store and YO!kart's resources, like apps, blogs,
                  and help.
                </div>
                <form method="get" class="quick-search__form">
                  <div class="input-group">
                    <div class="SearchBar-icon-spacing">
                      <div class="SearchIcon">
                        <svg
                          style="width: 12px; height: 12px"
                          height="16"
                          viewBox="0 0 16 16"
                          width="16"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            d="M12.6 11.2c.037.028.073.059.107.093l3 3a1 1 0 1 1-1.414 1.414l-3-3a1.009 1.009 0 0 1-.093-.107 7 7 0 1 1 1.4-1.4zM7 12A5 5 0 1 0 7 2a5 5 0 0 0 0 10z"
                            fill-rule="evenodd"
                          ></path>
                        </svg>
                      </div>
                    </div>
                    <input
                      type="text"
                      name="prod_brand_id"
                      :placeholder="$t('Go to...')"
                      aria-label="Search Brand"
                      autocomplete="off"
                      class="form-control quick-search__input"
                      data-toggle="dropdown"
                      aria-expanded="true"
                      v-model="searchKeyword"
                      @keyup="globalSearch"
                      ref="inputSearch"
                    />
                  </div>
                  <div
                    class="quick-search-wrapper scroll ps"
                    v-bind:class="[searchKeyword != '' ? 'show' : 'hide']"
                  >
                    <perfect-scrollbar style="max-height: 300px">
                      <div
                        class="result__bar"
                        v-for="(searchRecord, index) in searchRecords['data']"
                        :key="index"
                        v-if="searchRecords['data']"
                      >
                        <div class="search__result">
                          <div class="result-inner d-flex">
                            <div class="Icon--external">
                              <svg
                                class="SVGInline-svg SVGInline--cleaned-svg SVG-svg Icon-svg Icon--external-svg SVG--color-svg SVG--color--gray200-svg"
                                style="width: 12px; height: 12px"
                                height="16"
                                viewBox="0 0 16 16"
                                width="16"
                                xmlns="http://www.w3.org/2000/svg"
                              >
                                <path
                                  d="M2 4v10h10v-3a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h4a1 1 0 1 1 0 2zm5.707 5.707a1 1 0 1 1-1.414-1.414l6.3-6.298H9.017a.998.998 0 1 1 0-1.995h5.986A.995.995 0 0 1 16 .998v5.986a.998.998 0 1 1-1.995 0V3.406z"
                                  fill-rule="evenodd"
                                ></path>
                              </svg>
                            </div>
                            <div class="d-flex flex-column w-100">
                              <a
                                class="text-hover-primary"
                                @click="
                                  redirectPage(
                                    searchRecord.url,
                                    searchRecord.id,
                                    searchRecord.title
                                  )
                                "
                                href="javascript:;"
                              >
                                <div
                                  v-html="
                                    $options.filters.highlight(
                                      searchRecord.title,
                                      searchKeyword
                                    )
                                  "
                                ></div>
                              </a>
                              <div class="d-flex">
                                <p>in {{ searchRecord.type }}</p>
                                <span
                                  class="ml-auto"
                                  v-if="searchRecord['type'] == 'products'"
                                >
                                  {{
                                    searchRecord.varients_count
                                      ? searchRecord.varients_sum +
                                        " available " +
                                        searchRecord.varients_count +
                                        " variants "
                                      : searchRecord.qty + " available"
                                  }}
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div
                        class="result__bar"
                        v-for="(searchRecord, index) in searchRecords['module']"
                        :key="index"
                        v-if="searchRecords['module']"
                      >
                        <div class="search-head">
                          <h6 class="search-head-title">{{ index }}</h6>
                        </div>
                        <div class="search__result">
                          <div
                            class="result-inner d-flex align-items-center"
                            v-for="(searchValues, searchKey) in searchRecord"
                            :key="searchKey"
                          >
                            <div class="Icon--external">
                              <svg
                                class="SVGInline-svg SVGInline--cleaned-svg SVG-svg Icon-svg Icon--external-svg SVG--color-svg SVG--color--gray200-svg"
                                style="width: 12px; height: 12px"
                                height="16"
                                viewBox="0 0 16 16"
                                width="16"
                                xmlns="http://www.w3.org/2000/svg"
                              >
                                <path
                                  d="M2 4v10h10v-3a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h4a1 1 0 1 1 0 2zm5.707 5.707a1 1 0 1 1-1.414-1.414l6.3-6.298H9.017a.998.998 0 1 1 0-1.995h5.986A.995.995 0 0 1 16 .998v5.986a.998.998 0 1 1-1.995 0V3.406z"
                                  fill-rule="evenodd"
                                ></path>
                              </svg>
                            </div>
                            <div class="d-flex flex-column">
                              <a
                                class="text-hover-primary"
                                @click="redirectPage(searchKey, '')"
                                href="javascript:;"
                              >
                                <div
                                  v-html="
                                    $options.filters.highlight(
                                      $t(searchValues),
                                      searchKeyword
                                    )
                                  "
                                ></div>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>


                      <div
                        class="result__bar"
                       
                        v-if="searchRecords['resource']"
                      >
                        <div class="search-head">
                          <h6 class="search-head-title">{{ $t("LBL_RESOURCES") }}</h6>
                        </div>
                        <div class="search__result">
                          <div
                            class="result-inner d-flex align-items-center"
                             v-for="(searchRecord, index) in searchRecords['resource']"
                            :key="index"
                          >
                            <div class="Icon--external">
                              <svg
                                class="SVGInline-svg SVGInline--cleaned-svg SVG-svg Icon-svg Icon--external-svg SVG--color-svg SVG--color--gray200-svg"
                                style="width: 12px; height: 12px"
                                height="16"
                                viewBox="0 0 16 16"
                                width="16"
                                xmlns="http://www.w3.org/2000/svg"
                              >
                                <path
                                  d="M2 4v10h10v-3a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h4a1 1 0 1 1 0 2zm5.707 5.707a1 1 0 1 1-1.414-1.414l6.3-6.298H9.017a.998.998 0 1 1 0-1.995h5.986A.995.995 0 0 1 16 .998v5.986a.998.998 0 1 1-1.995 0V3.406z"
                                  fill-rule="evenodd"
                                ></path>
                              </svg>
                            </div>
                            <div class="d-flex flex-column">
                              <a
                                class="text-hover-primary"
                                @click="redirectPage('resources', '', searchRecord.resource_title)"
                                href="javascript:;"
                              >
                                <div
                                  v-html="
                                    $options.filters.highlight(
                                      $t(searchRecord.resource_title),
                                      searchKeyword
                                    )
                                  "
                                ></div>
                              </a>
                              <p class="list__item-content">
                                            {{searchRecord.resource_description.replace(/(<([^>]+)>)/gi, "").substring(0, 60)+'...'}}
                                          </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </perfect-scrollbar>
                  </div>
                </form>
              </div>
              <div class="SearchFooter-container">
                <div class="SearchFooter-animation-wrapper">
                  <p>
                    {{$t('LBL_PRESS')}} <kbd>{{$t('LBL_CTRL_5')}}</kbd> {{$t('LBL_AGAIN_NATIVE_SEARCH')}}.
                  </p>
                  <input type="checkbox" v-model="searchEnabled" @click="searchChange($event)"/>
                </div>
              </div>
            </div>
          </div>
        </b-modal>
      </div>

      <div
        class="header__topbar-item"
        data-toggle="tooltip"
        data-placement="top"
        :title="$t('TTL_STORE')"
        @click="storePreview(baseUrl)"
      >
        <div class="header__topbar-wrapper">
          <span class="header__topbar-icon">
            <i class="icn">
              <svg class="svg" width="26" height="26">
                <use
                  :xlink:href="
                    baseUrl + '/admin/images/retina/sprite.svg#store'
                  "
                ></use>
              </svg>
            </i>
          </span>
        </div>
      </div>
      <div
        class="header__topbar-item"
        data-toggle="tooltip"
        data-placement="top"
        :title="$t('TTL_CLEAR_CACHE')"
        @click="clearCache"
      >
        <div class="header__topbar-wrapper">
          <span class="header__topbar-icon">
            <i class="icn">
              <svg class="svg" width="26" height="26">
                <use
                  :xlink:href="
                    baseUrl + '/admin/images/retina/sprite.svg#cache-clear'
                  "
                ></use>
              </svg>
            </i>
          </span>
        </div>
      </div>

      <div
        class="header__topbar-item"
        data-toggle="tooltip"
        data-placement="top"
        :title="$t('Switch between Dark/Light')"
        v-if="enableDarkMode != '0'"
      >
        <div class="header__topbar-wrapper">
          <span class="header__topbar-icon">
            <a class="light" href="javascript:void(0)" @click="switchTheme">
              <i class="icn">
                <svg class="svg" width="24" height="24">
                  <use
                    :xlink:href="
                      baseUrl +
                      '/admin/images/retina/sprite.svg#' +
                      selectedTheme
                    "
                    :href="
                      baseUrl +
                      '/admin/images/retina/sprite.svg#' +
                      selectedTheme
                    "
                  ></use>
                </svg>
              </i>
            </a>
          </span>
        </div>
      </div>
      <notificationbar></notificationbar>
      <userprofilebar></userprofilebar>
    </div>
  </div>
</template>
<script>
import NProgress from "nprogress";
var VueCookie = require("vue-cookie");
Vue.use(VueCookie);
export default {
  data: function () {
    return {
      searchKeyword: "",
      searchRecords: {},
      baseUrl: baseUrl,
      enableDarkMode: darkMode,
      selectedTheme: "dark",
      searchEnabled: 1,
    };
  },
  methods: {
    redirectPage: function (name, id = 0, query = "") {
      this.$bvModal.hide("modal-1");
      $("#quick_search_toggle").click();
      this.searchKeyword = "";
      if (id != 0 && name == "orderReturnDetail") {
        let ids = id.split("<>");
        this.$router.push({
          name: name,
          params: {
            id: ids[0],
            requestId: ids[1],
          },
        });
        return false;
      }
      if (id != 0 && (name == "orderDetail" || name == "editShippingProfile")) {
        this.$router.push({
          name: name,
          params: {
            id: id,
          },
        });
        return false;
      }

      if (query == "") {
        this.$router.push({
          name: name,
        });
        return false;
      }
      this.$router.push({
        name: name,
        query: {
          s: query,
        },
      });
    },
    storePreview: function (url) {
      window.open(url, "_blank");
    },
    switchTheme: function () {
        if (this.selectedTheme == "dark") {
            this.selectedTheme = "light";
            this.$cookie.set("yk-Admin-ThemeStyle", "dark", {
                expires: "1M",
            });
            $("html").attr("data-theme", "dark");
        } else if(this.selectedTheme == "light") {
            this.selectedTheme = "dark";
            this.$cookie.set("yk-Admin-ThemeStyle", "light", {
                expires: "1M",
            });
            $("html").attr("data-theme", "light");
        }
        this.$root.$emit("updateThemeSwitchLogo");
    },
    clearCache: function () {
      NProgress.start();
      this.$http.get(adminBaseUrl + "/clear-cache").then((response) => {
        NProgress.done();
        if (response.data.status == true) {
          toastr.success(response.data.message, "", toastr.options);
          window.location.reload(true);
        }
      });
    },
    globalSearch: function () {
      let formData = this.$serializeData({
        keyword: this.searchKeyword,
      });
      this.$http
        .post(adminBaseUrl + "/global-search", formData)
        .then((response) => {
          this.searchRecords = response.data.data;
        });
    },
    openModal: function () {
      this.searchKeyword = "";
      this.$bvModal.show("modal-1");
      let thisObj= this;
      setTimeout(function(){
          thisObj.$refs.inputSearch.focus();
      },300);
    },
    searchChange: function() {
        localStorage.setItem('yk-search', (this.searchEnabled === true || this.searchEnabled === 1) ? 0 : 1);
    }
  },
  mounted() {
      let localStorageSearch = localStorage.getItem('yk-search');
      if(localStorageSearch !== null) {
          this.searchEnabled = parseInt(localStorageSearch);
      } 
    let thisObj = this;
    let adminTheme = this.$cookie.get("yk-Admin-ThemeStyle");
    if (adminTheme) {
      if(adminTheme == "dark") {
         this.selectedTheme = 'light';
          $("html").attr("data-theme", "dark");
      } else if(adminTheme == "light") {
        this.selectedTheme = 'dark';
          $("html").attr("data-theme", "light");
      }
      
    }
    this.$root.$on("updateDarkMode", (data) => {
      this.enableDarkMode = data;
    });
    window.addEventListener("keydown", function (e) {
        if (e.keyCode === 114 || (e.ctrlKey && e.keyCode === 70)) {
            if(thisObj.searchEnabled) {
                e.preventDefault();
                thisObj.openModal();
            }
        }
    });
  },
};
</script>
