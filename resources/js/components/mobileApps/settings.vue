<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t("LBL_MOBILE_APPS") }}</h3>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="portlet">
        <div class="portlet__body portlet__body--fit">
          <div class="app-page">
            <sidebar :tab="type"></sidebar>
            <div class="app-main">
              <div class="row justify-content-center">
                <div class="col-md-12">
                  <div class="app-setteing__wrapper">
                    <div class="card">
                      <div class="card-header">
                        <h3>General Settings</h3>
                      </div>
                      <div class="card-body">
                        <div class="form-group row justify-content-center mb-2">
                          <label class="col-lg-9 col-form-label"
                            >I want to display Buy Together Products when item is added to cart</label
                          >
                          <div class="col-lg-2">
                            <label class="switch switch--sm">
                              <input 
                              type="checkbox"
                              name="APP_DISPLAY_BUY_TOGETHER"
                              v-model="adminsData.APP_DISPLAY_BUY_TOGETHER"
                              @change="onSwitchStatus($event, 'APP_DISPLAY_BUY_TOGETHER')"
                              />
                              <span></span>
                            </label>
                          </div>
                        </div>
                       
                        
                         <div class="form-group row justify-content-center mb-2">
                          <label class="col-lg-9 col-form-label"
                            >I will not use web view and shall create separate T&C and Privacy Policy screens</label
                          >
                          <div class="col-lg-2">
                            <label class="switch switch--sm">
                              <input 
                              type="checkbox"
                              name="APP_CONTENT_PAGES_ENABLED"
                              v-model="adminsData.APP_CONTENT_PAGES_ENABLED"
                              @change="onSwitchStatus($event, 'APP_CONTENT_PAGES_ENABLED')"
                               />
                              <span></span>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header">
                        <h3>Blank Search Categories</h3>
                      </div>
                      <div class="card-body">
                       <label class="col-lg-9 col-form-label"
                            >Please link categories to be displayed on the search page</label>
                         <vue-tags-input class="vue-tags-style" v-model="tag" :tags="tags" ref="productsTagify" :autocomplete-items="autocompleteProducts" @tags-changed="saveTags" :add-only-from-autocomplete="true" :placeholder="$t('Search categories')"> </vue-tags-input>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header">
                        <h3>Store location to be displayed on the contact us page</h3>
                      </div>
                      <div class="card-body">
                        <div class="form-group row justify-content-center mb-2" v-if="googleMapApiStatus == 1">
                          <div class="col-md-12 col-form-label">Search your store address to populate store coordinates</div>

                          <div class="col-md-12">
                      
                            <GmapAutocomplete 
                                type="text" 
                                  name="BUSINESS_ADDRESS" 
                                  :value="adminsData.BUSINESS_ADDRESS"
                                  class="form-control"
                                  @place_changed='setPlace' 
                                  :placeholder="$t('LBL_ENTER_BUSINESS_ADDRESS')">
                            </GmapAutocomplete>
                          </div>
                        </div>
                        <div class="form-group row justify-content-center mb-2">
                          <label class="col-md-6 col-form-label">Latitude</label>
                          <div class="col-md-5">
                            <input type="text" 
                                  name="BUSINESS_LATITUDE" 
                                  v-model="adminsData.BUSINESS_LATITUDE"
                                  data-vv-as="Business Latitude" 
                                  data-vv-validate-on="none" 
                                  class="form-control" 
                                  aria-required="false" 
                                  aria-invalid="false">
                          </div>
                        </div>
                       <div class="form-group row justify-content-center mb-2">
                          <label class="col-md-6 col-form-label">Longitude</label>
                          <div class="col-md-5">
                            <input type="text" 
                                    name="BUSINESS_LONGITUDE" 
                                    :value="adminsData.BUSINESS_LONGITUDE"
                                    data-vv-as="Business Latitude" 
                                    data-vv-validate-on="none" 
                                    class="form-control" 
                                    aria-required="false" 
                                    aria-invalid="false">
                          </div>
                        </div>             
                      </div>
                      <div class="row mb-2">
                          <div class="col-md-4"></div>
                          <div class="col-md-6">
                            <button
                              type="submit"
                              class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                              @click="updateSettings"
                            >{{$t('BTN_PRODUCT_SETTINGS_UPDATE')}}</button>
                          </div>
                        </div>
                    </div>
                    <div class="card">
                      <div class="card-header">
                        <h3>Mobile App Default Language</h3>
                      </div>
                      <div class="card-body">
                        <select v-model="adminsData.MOBILE_DEFAULT_LANGUAGE" @change="updateSettings('MOBILE_DEFAULT_LANGUAGE')" class="input-edited form-control" v-validate="'required'">
                          <option disabled value>{{ $t('LBL_SELECT_DEFAULT_LANGUAGE') }}</option>
                          <option v-for="(language, key) in languages" :value="key" :key="key">
                            {{language}}
                          </option>
                        </select>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header">
                        <h3>Mobile App Firebase Push Notification Key</h3>
                      </div>
                      <div class="card-body">
                        <div class="row mb-2">
                          <div class="col-md-12">
                            <input type="text" 
                                v-model="adminsData.FCM_SERVER_KEY" 
                                data-vv-as="FCM Server Key" 
                                data-vv-validate-on="none" 
                                class="form-control" 
                                aria-required="false" 
                                aria-invalid="false">
                          </div>
                        </div>
                        <div class="row mb-2">
                          <div class="col-md-12 text-center">
                            <button
                              type="submit"
                              class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                              @click="updateSettings"
                            >{{$t('BTN_PRODUCT_SETTINGS_UPDATE')}}</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import VueTagsInput from "@johmun/vue-tags-input";
import sidebar from "./sidebar";
const tableFields = {
    'APP_DISPLAY_BUY_TOGETHER': '',
    'APP_REVIEWS_AUTO_APPROVED': '',
    'APP_DISPLAY_SIMILAR_PRODUCTS': '',
    'APP_CONTENT_PAGES_ENABLED': '',
    'BUSINESS_LATITUDE': '',
    'BUSINESS_LONGITUDE': '',
    'BUSINESS_ADDRESS': '',
    'MOBILE_DEFAULT_LANGUAGE': 'en',
    'FCM_SERVER_KEY':''
};
export default {
  components: {
    sidebar: sidebar,
    VueTagsInput,
  },
  data: function() {
    return {
      type: "settings",
      tag: "",
      tags: [],
      languages: [],
      autocompleteProducts: [],
      adminsData: tableFields,
      baseUrl: baseUrl,
      googleMapApiStatus: googleMapApiStatus
    };
  },
  watch: {
    'tag': 'initItems'
  },
  methods: {
    async setPlace(place){
        this.adminsData.BUSINESS_ADDRESS = place.formatted_address;
        if(place.geometry){
          if(place.geometry.location.lat() != ''){
              this.adminsData.BUSINESS_LATITUDE = place.geometry.location.lat();
          }
          if(place.geometry.location.lng() != ''){
              this.adminsData.BUSINESS_LONGITUDE = place.geometry.location.lng();
          }
        }
    },
    async initItems() {
        if (this.tag.length < 2) return;
        const res = await fetch(adminBaseUrl + '/categories/search?query=' + this.tag);
        const response = await res.json();
        this.autocompleteProducts = response.data.map(a => {
            return {
                text: a.label,
                id: a.value
            };
        });
    },
    saveTags: function(newTags) {
     this.autocompleteProducts = [];
      this.tags = newTags;
      let formData = this.$serializeData({
        tags: JSON.stringify(this.tags),
        type: "categories"     
      });
      this.$http
        .post(adminBaseUrl + "/app/store/records", formData)
        .then(response => {
        });
    },
    onSwitchStatus: function(event, model) {
      this.adminsData[model] = event.target.checked == false ? 0 : 1;
      this.updateSettings(model);
      
    },  
    getSettings: function() {
      let formData = this.$serializeData({
        type: "categories"
      });
      this.$http
        .post(adminBaseUrl + "/app/collection", formData)
        .then(response => {
          let result = response.data.data.editData;
          this.adminsData.APP_DISPLAY_BUY_TOGETHER = parseInt(result.config.APP_DISPLAY_BUY_TOGETHER);
          this.adminsData.APP_REVIEWS_AUTO_APPROVED = parseInt(result.config.APP_REVIEWS_AUTO_APPROVED);
          this.adminsData.APP_DISPLAY_SIMILAR_PRODUCTS = parseInt(result.config.APP_DISPLAY_SIMILAR_PRODUCTS);
          this.adminsData.APP_CONTENT_PAGES_ENABLED = parseInt(result.config.APP_CONTENT_PAGES_ENABLED);
          this.adminsData.BUSINESS_LATITUDE = result.config.BUSINESS_LATITUDE;
          this.adminsData.BUSINESS_LONGITUDE = result.config.BUSINESS_LONGITUDE;
          this.adminsData.BUSINESS_ADDRESS = result.config.BUSINESS_ADDRESS;
          this.adminsData.MOBILE_DEFAULT_LANGUAGE = result.config.MOBILE_DEFAULT_LANGUAGE;
          this.adminsData.FCM_SERVER_KEY = result.config.FCM_SERVER_KEY;
          this.languages = result.languages;
          this.tags = result.categories.map(a => {
          return {
             text: a.cat_name,
             id: a.cat_id
            };
          });
        });

      }, 
      updateSettings: function(section = '') {
          this.$validator.validateAll().then(res => {
              if (res) {
                  this.clicked = 1;
                  let formData = this.$serializeData(this.adminsData);
                  this.$http.post(adminBaseUrl + '/settings/basedonkeys', formData)
                      .then((response) => { //success
                          this.clicked = 0;
                          if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                          }
                          toastr.success(response.data.message, '', toastr.options);

                          if(section == "APP_CONTENT_PAGES_ENABLED"){
                            location.reload();
                          }
                      }, (response) => { //error
                          this.clicked = 0;
                          this.validateErrors(response);
                      });
              } else {
                  this.clicked = 0;
              }
          });
        },
        validateErrors: function(response) {
            let jsondata = response.data;
            Object.keys(jsondata.errors).forEach(key => {
                this.errors.add({
                    field: key,
                    msg: jsondata.errors[key][0]
                });
            });
        },
  },
  mounted: function() {
    this.getSettings();
  },
};
</script>
