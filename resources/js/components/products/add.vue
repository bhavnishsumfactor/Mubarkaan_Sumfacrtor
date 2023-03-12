<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_PRODUCTS') }}</h3>
          <div class="subheader__breadcrumbs"> 
            <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home yk-home-icon">
              <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <router-link
              :to="{name: 'products'}"
              class="subheader__breadcrumbs-link"
            >{{ $t('LNK_PRODUCTS') }}</router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link" v-if="!$route.params.id">{{$t('LBL_ADD')}}</span>
            <span class="subheader__breadcrumbs-link" v-if="$route.params.id">{{$t('LBL_EDIT')}}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="portlet" id="manage-admins" v-cloak>
        <div class="portlet__body portlet__body--fit">
          <div class="wizard-v2 wizard-v2--white" id="wizard_v2" data-f-a-tbitwizard-state="first">
            <div class="wizard-v2__aside">
              <sidebar :tab="tab" :type="type" :selectedPage="$route.params.id ? 'editform' : ''" @next="next" :logs="logs"></sidebar>
            </div>
            <div class="wizard-v2__wrapper">
               <div class="progress rounded-0" style="height: 1.5rem;" v-if="!$route.params.id">
                  <div
                    class="progress-bar"
                    role="progressbar"
                    v-bind:style="{ width: bar.toFixed()+ '%' }"
                    aria-valuenow="25"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    v-html="bar.toFixed() + '%'"
                  ></div>
                  
                </div>
                 <!-- <div class="product-progress">
                <div class="progress" style="height: 1.5rem;">
                  <div
                    class="progress-bar bg-success"
                    role="progressbar"
                    v-bind:style="{ width: bar+ '%' }"
                    aria-valuenow="25"
                    aria-valuemin="0"
                    aria-valuemax="100"
                  ></div>
                </div>
                <span class="status">{{step}}/{{totalStep}}</span>
              </div> -->
              <general-info
                v-if="tab == 'general'"
                :prod_id="prod_id"
                @next="next"
                @previous="previous"
                @progressBar="progressBar"
                @lastUpdatedLogs="setLogs"
              ></general-info>
              <price-info
                v-if="tab == 'price'"
                :prod_id="prod_id"
                @next="next"
                @previous="previous"
                @progressBar="progressBar"
              ></price-info>
              <options-info
                v-if="tab == 'options'"
                :prod_id="prod_id"
                @next="next"
                @previous="previous"
                @progressBar="progressBar"
              ></options-info>
              <attributes-info
                v-if="tab == 'attributes'"
                :prod_id="prod_id"
                @next="next"
                @previous="previous"
                @progressBar="progressBar"
              ></attributes-info>
              <media-info
                v-if="tab == 'media'"
                :prod_id="prod_id"
                @next="next"
                @previous="previous"
                @progressBar="progressBar"
              ></media-info>
              <digital-info
                v-if="tab == 'digital'"
                :prod_id="prod_id"
                @next="next"
                @previous="previous"
                @progressBar="progressBar"
              ></digital-info>
            </div>
          </div>

          
          
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import sidebar from "./sideBarNav";
import generalInfo from "./generalInfo";
import priceInfo from "./priceInfo";
import optionsInfo from "./optionsInfo";
import attributesInfo from "./attributesInfo";
import mediaInfo from "./mediaInfo";
import digitalInfo from "./digitalInfo";
export default {
  components: {
    sidebar,
    generalInfo,
    priceInfo,
    optionsInfo,
    attributesInfo,
    mediaInfo,
    digitalInfo
  },
  data: function() {
    return {
      tab: "general",
      type: "",
      prod_id: "",
      bar: 0,
      step: "1",
      totalStep: "5",
      logs:[]
    };
  },
  methods: {
    next: function(id, tab, type) {
      this.prod_id = id;
      this.tab = tab;
      this.type = type;
    },
    previous: function(tab, type) {
      this.tab = tab;
      this.type = type;
    },
    progressBar: function(process, step, totalStep) {
      this.bar = process;
      this.step = step;
      this.totalStep = totalStep;
    },
    setLogs:function(log) {
        this.logs = log
    }
  }
};
</script>