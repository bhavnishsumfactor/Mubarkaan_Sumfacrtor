<template>
  <!--Begin:: App Aside-->

  <aside class="app-nav">
    <ul>
      <li>
        <router-link
          :to="{ name: 'mobileHome' }"
          class="app-nav_item"
          v-bind:class="[tab == 'home' ? 'active' : '']"
        >
          <i class="app-nav_icn">
            <svg class="svg">
              <use
                :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#app-home'"
                :href="baseUrl + '/admin/images/retina/sprite.svg#app-home'"
              />
            </svg>
          </i>

          <span class="app-nav_title">{{ $t("LBL_APP_HOME_SCREEN") }}</span>
        </router-link>
      </li>
      <li>
        <router-link
          :to="{ name: 'mobileExplore' }"
          class="app-nav_item"
          v-bind:class="[tab == 'explore' ? 'active' : '']"
        >
          <i class="app-nav_icn">
            <svg class="svg">
              <use
                :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#language'"
                :href="baseUrl + '/admin/images/retina/sprite.svg#language'"
              />
            </svg>
          </i>
          <span class="app-nav_title">{{ $t("LBL_EXPLORE") }}</span>
        </router-link>
      </li>
      <li>
        <router-link
          :to="{ name: 'mobileTheme' }"
          class="app-nav_item"
          v-bind:class="[tab == 'theme' ? 'active' : '']"
        >
          <i class="app-nav_icn">
            <svg class="svg">
              <use
                :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#app-theme'"
                :href="baseUrl + '/admin/images/retina/sprite.svg#app-theme'"
              />
            </svg>
          </i>
          <span class="app-nav_title">{{ $t("LBL_APP_THEME") }}</span>
        </router-link>
      </li>
     
      
      <li>
        <router-link
          :to="{ name: 'mobileSettings' }"
          class="app-nav_item"
          v-bind:class="[tab == 'settings' ? 'active' : '']"
        >
          <i class="app-nav_icn">
            <svg class="svg">
              <use
                :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#app-setting'"
                :href="baseUrl + '/admin/images/retina/sprite.svg#app-setting'"
              />
            </svg>
          </i>
          <span class="app-nav_title">{{ $t("LBL_APP_SETTINGS") }}</span>
        </router-link>
      </li>
      <li>
        <router-link
          :to="{ name: 'appNotiTemplates' }"
          class="app-nav_item"
          v-bind:class="[tab == 'app-noti-templates' ? 'active' : '']"
        >
          <i class="app-nav_icn">
            <svg class="svg">
              <use
                :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#app-setting'"
                :href="baseUrl + '/admin/images/retina/sprite.svg#app-setting'"
              />
            </svg>
          </i>
          <span class="app-nav_title">{{ $t("LBL_APP_NOTIFICATION_TEMPLATES") }}</span>
        </router-link>
      </li>
     <li>
        <router-link
          :to="{ name: 'mobileLangLabels' }"
          class="app-nav_item"
          v-bind:class="[tab == 'langlabels' ? 'active' : '']"
        >
          <i class="app-nav_icn">
            <svg class="svg">
              <use
                :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#language'"
                :href="baseUrl + '/admin/images/retina/sprite.svg#language'"
              />
            </svg>
          </i>
          <span class="app-nav_title">{{ $t("LBL_LANGUAGE_LABELS") }}</span>
        </router-link>
      </li>
       <li v-if="contentPagesEnabled == 1">
        <router-link
          :to="{ name: 'mobilePrivacy' }"
          class="app-nav_item"
          v-bind:class="[tab == 'privacy' ? 'active' : '']"
        >
          <i class="app-nav_icn">
            <svg class="svg">
              <use
                :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#app-privacy'"
                :href="baseUrl + '/admin/images/retina/sprite.svg#app-privacy'"
              />
            </svg>
          </i>
          <span class="app-nav_title">{{ $t("LBL_APP_PRIVACY") }}</span>
        </router-link>
      </li>

      <li v-if="contentPagesEnabled == 1">
        <router-link
          :to="{ name: 'mobileTerms' }"
          class="app-nav_item"
          v-bind:class="[tab == 'terms' ? 'active' : '']"
        >
          <i class="app-nav_icn">
            <svg class="svg">
              <use
                :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#app-terms'"
                :href="baseUrl + '/admin/images/retina/sprite.svg#app-terms'"
              />
            </svg>
          </i>
          <span class="app-nav_title">{{ $t("LBL_APP_TERMS") }}</span>
        </router-link>
      </li>
      <li v-for="(appContentPage, index) in appContentPages" :key="index">
        <router-link :to="{name: 'contentPages', params: {id: appContentPage.page_id }}"  class="app-nav_item">
          <i class="app-nav_icn">
            <svg class="svg">
              <use
                :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#app-page'"
                :href="baseUrl + '/admin/images/retina/sprite.svg#app-page'"
              />
            </svg>
          </i>
         <span class="app-nav_title">{{ appContentPage.page_title }}</span>
        </router-link>
      </li>
        <li>
        <a href="javascript:;" class="app-nav_item" @click="addNewPage()">
        <i class="app-nav_icn">
            <svg class="svg">
              <use
                :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#app-page-add'"
                :href="baseUrl + '/admin/images/retina/sprite.svg#app-page-add'"
              />
            </svg>
          </i>
          </a>
      </li>
      
    </ul>
    <b-modal id="contentPageForm" size="md" centered title="BootstrapVue">
    <template v-slot:modal-header>
      <h5 class="modal-title" id="exampleModalLabel">
        <span>Add Page</span>
      </h5>
      <button type="button" class="close" @click="$bvModal.hide('contentPageForm')"></button>
    </template>
    <div class="form-group row">
      <label class="col-md-12 col-form-label" for="validationCustom01">Page Title</label>
      <div class="col-md-12">
        <input type="text" class="form-control" placeholder="Page Title" v-model="pageTitle" />
      </div>
    </div>
    <template v-slot:modal-footer>
      <button type="submit" :disabled="pageTitle == ''" class="btn btn-brand " @click="savePage">
        <span>Save</span>
      </button>
    </template>
  </b-modal>
  </aside>
</template>
<script>
export default {
  props: ["tab", "deletedPageId"],
  data: function() {
    return {
      baseUrl: baseUrl,
      pageTitle: '',
      contentPagesEnabled: contentPagesEnabled,
      appContentPages: JSON.parse(appContentPages),
    };
  },
  methods:{
      addNewPage:function(){
        this.pageTitle = '';
        this.$bvModal.show("contentPageForm");
     },
      savePage:function(){
        let formData = new FormData();
        formData.append("title", this.pageTitle);
        formData.append("type", "contentpages");
        this.$http
          .post(adminBaseUrl + "/app/store/records", formData)
          .then(response => {
           this.$bvModal.hide("contentPageForm");
             location.reload();
          });
      }
  },
};
</script>
