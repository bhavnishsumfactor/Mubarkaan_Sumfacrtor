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
                <div class="col-md-7">
                  <div class="app__collections">
                      <div class="app__collections-head">
                    <ul class="nav nav-tabs nav-tabs-pill" role="tablist">
                        <li class="nav-item" v-for="(tab, index) in tabs" :key="index" @click="filterAppRecords(index)"> 
                          <a class="nav-link" data-toggle="tab" v-bind:class="[activeTab ==  index? 'active' : '']"  href="javascript:;" role="tab">{{tab}}</a>
                        </li>
                    </ul>
                    </div>
                 
                    <div class="app__collections-wrapper">
                     
                      <container
                        behaviour="copy"
                        group-name="1"
                        :get-child-payload="getChildPayload1"
                      >
                
                        <draggable :key="rIndex" v-for="(record, rIndex) in records" >
                            <category-collection1 v-if="record.ac_type == 1" ></category-collection1>
                            <banner-slider-collection1 v-else-if="record.ac_type == 2"></banner-slider-collection1>
                            <banner-slider-collection2 v-else-if="record.ac_type == 3"></banner-slider-collection2>
                            <product-collection1 v-else-if="record.ac_type == 4"></product-collection1>
                            <brand-collection1 v-else-if="record.ac_type == 5"></brand-collection1>
                            <testimonial-collection1 v-else-if="record.ac_type == 6"></testimonial-collection1>
                            <blog-collection1 v-else-if="record.ac_type == 7"></blog-collection1>
                            <product-collection2 v-else-if="record.ac_type == 8"></product-collection2>
                            <category-collection2 v-else-if="record.ac_type == 9"></category-collection2>
                            <brand-collection2 v-else-if="record.ac_type == 10"></brand-collection2>
                            <banner-slider-collection3 v-else-if="record.ac_type == 11"></banner-slider-collection3>
                            <category-collection3 v-else-if="record.ac_type == 12"></category-collection3>
                            <blog-collection2 v-else-if="record.ac_type == 13"></blog-collection2>
                         <!--   <recent-bought-collection1 v-else-if="record.ac_type == 14"></recent-bought-collection1> -->
                            <testimonial-collection2 v-else-if="record.ac_type == 15"></testimonial-collection2>
                          </draggable>
                      </container>
                      
                    </div>
                     </isotope>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="device__preview-wrapper">
                    <div class="device__preview">
                      <div class="device__screen">
                        <div class="app__collection app__header mb-0">
                          <div class="image-wrapper">
                            <img
                              :src="
                                baseUrl + '/admin/images/app-home/header.png'
                              "
                            />
                          </div>
                        </div>
                        <div
                          class="scroll-y"
                          style="max-height:496px;min-height:496px;padding: 0 1rem;"
                        >
                          <container
                            group-name="1"
                            drag-class="card-ghost"
                            drop-class="card-ghost-drop"
                            :drop-placeholder="dropPlaceholderOptions"
                            :get-child-payload="getChildPayload2"
                            @drop="onDrop('appRecords', $event)"
                          >
                        

                <draggable :key="srIndex" v-for="(appRecord, srIndex) in appRecords">
                              <category-collection1
                                v-if="appRecord.ac_type == 1"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></category-collection1>
                              <banner-slider-collection1
                                v-if="appRecord.ac_type == 2"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></banner-slider-collection1>
                              <banner-slider-collection2
                                v-if="appRecord.ac_type == 3"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></banner-slider-collection2>
                              <product-collection1
                                v-else-if="appRecord.ac_type == 4"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></product-collection1>
                              <brand-collection1
                                v-else-if="appRecord.ac_type == 5"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></brand-collection1>
                              <testimonial-collection1
                                v-if="appRecord.ac_type == 6"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></testimonial-collection1>
                              <blog-collection1
                                v-else-if="appRecord.ac_type == 7"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></blog-collection1>
                              <product-collection2
                                v-else-if="appRecord.ac_type == 8"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></product-collection2>
                              <category-collection2
                                v-if="appRecord.ac_type == 9"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></category-collection2>
                              <brand-collection2
                                v-else-if="appRecord.ac_type == 10"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></brand-collection2>
                              <banner-slider-collection3
                                v-if="appRecord.ac_type == 11"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></banner-slider-collection3>
                              <category-collection3
                                v-if="appRecord.ac_type == 12"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></category-collection3>
                              <blog-collection2
                                v-else-if="appRecord.ac_type == 13"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></blog-collection2>
                          <!--    <recent-bought-collection1
                                v-else-if="appRecord.ac_type == 14"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></recent-bought-collection1> -->
                              <testimonial-collection2
                                v-if="appRecord.ac_type == 15"
                                @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                :appRecord="appRecord"
                              ></testimonial-collection2>
                            </draggable>
                              

            
                          </container>
                        </div>
                        <div class="app__collection app__navigation mb-0">
                          <div class="image-wrapper">

                            <img :src="baseUrl +'/admin/images/app-home/navigation.png'" />
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
    <category-collection-form
      :clickEvent="clickEvent"
      @pageLoadData="pageLoadData"
      :acId="acId"
      :pageId="pageId"
      :collectionId="collectionId"
      :collectionType="collectionType"
      @deleteImageById="deleteImageById"
      @deleteCollRecordById="deleteCollRecordById"
    ></category-collection-form>
    <banner-slider-collection-form
      :clickEvent="bsclickEvent"
      @pageLoadData="pageLoadData"
      :acId="acId"
      :pageId="pageId"
      :collectionId="collectionId"
      :collectionType="collectionType"
      @deleteImageById="deleteImageById"
      @deleteCollRecordById="deleteCollRecordById"
    ></banner-slider-collection-form>
    <brand-collection-form
      :clickEvent="bClickEvent"
      @pageLoadData="pageLoadData"
      :acId="acId"
      :pageId="pageId"
      :collectionType="collectionType"
      :collectionId="collectionId"
       @deleteImageById="deleteImageById"
      @deleteCollRecordById="deleteCollRecordById"
    ></brand-collection-form>
    <blog-collection-form
      :clickEvent="bgClickEvent"
      @pageLoadData="pageLoadData"
      :collectionType="collectionType"
      :acId="acId"
      :pageId="pageId"
      :collectionId="collectionId"
      @deleteCollRecordById="deleteCollRecordById"
    ></blog-collection-form>
    <testimonial-collection-form
      :clickEvent="testimonialClickEvent"
      @pageLoadData="pageLoadData"
      :acId="acId"
      :pageId="pageId"
      :collectionId="collectionId"
      :collectionType="collectionType"
      @deleteImageById="deleteImageById"
      @deleteCollRecordById="deleteCollRecordById"
    ></testimonial-collection-form>
    <product-collection-form
      :clickEvent="prodClickEvent"
      @pageLoadData="pageLoadData"
      :acId="acId"
      :pageId="pageId"
      :collectionId="collectionId"
      :collectionType="collectionType"
      @deleteCollRecordById="deleteCollRecordById"
    ></product-collection-form>
  </div>
</template>
<script>

import { Container, Draggable } from "vue-smooth-dnd";
import sidebar from "./sidebar";
import CategoryCollection1 from "./partial/CategoryCollection1";
import CategoryCollection2 from "./partial/CategoryCollection2";
import CategoryCollection3 from "./partial/CategoryCollection3";
import BlogCollection1 from "./partial/BlogCollection1";
import BlogCollection2 from "./partial/BlogCollection2";
import BrandCollection1 from "./partial/BrandCollection1";
import BrandCollection2 from "./partial/BrandCollection2";
import ProductCollection1 from "./partial/ProductCollection1";
import ProductCollection2 from "./partial/ProductCollection2";
import BannerSliderCollection1 from "./partial/BannerSliderCollection1";
import BannerSliderCollection2 from "./partial/BannerSliderCollection2";
import BannerSliderCollection3 from "./partial/BannerSliderCollection3";
import TestimonialCollection1 from "./partial/TestimonialCollection1";
import TestimonialCollection2 from "./partial/TestimonialCollection2";
import CategoryCollectionForm from "./popups/CategoryCollectionForm";
import BrandCollectionForm from "./popups/BrandCollectionForm";
import BlogCollectionForm from "./popups/BlogCollectionForm";
import TestimonialCollectionForm from "./popups/TestimonialCollectionForm";
import ProductCollectionForm from "./popups/ProductCollectionForm";
import BannerSliderCollectionForm from "./popups/BannerSliderCollectionForm";
// import RecentBoughtCollection1 from "./partial/RecentBoughtCollection1";
export default {
  name: "Copy",
  components: {
    sidebar: sidebar,
    Container,
    CategoryCollection1,
    CategoryCollection2,
    CategoryCollection3,
    BlogCollection1,
    BlogCollection2,
    BrandCollection1,
    BrandCollection2,
    ProductCollection1,
    ProductCollection2,
    BannerSliderCollection1,
    BannerSliderCollection2,
    BannerSliderCollection3,
    TestimonialCollection1,
    TestimonialCollection2,
    CategoryCollectionForm,
  //  RecentBoughtCollection1,
    BrandCollectionForm,
    BlogCollectionForm,
    TestimonialCollectionForm,
    BannerSliderCollectionForm,
    ProductCollectionForm,
    Draggable
  },
  data() {
    return {
      pageId: 1,
      acId: 0,
      collectionId: 0,
      activeTab: "all",
      tabs:{
        'all': 'ALL',
        'categories': 'Categories',
        'brands': 'Brands',
        'banners': 'Banners',
        'blogs': 'Blogs',
        'testimonial': 'Testimonial',
        'products': 'Products',
    //    'recentBought': 'Recent Bought',
      },
      collectionType: "",
      dropPlaceholderOptions: {
        className: "drop-preview",
        animationDuration: "150",
        showOnTop: true
      },
      type: "home",
      clickEvent: "",
      bClickEvent: "",
      bgClickEvent: "",
      bsclickEvent: "",
      testimonialClickEvent: "",
      prodClickEvent: "",
      baseUrl: baseUrl,
      records: [],
      defaultAppListing: [],
      appRecords: []
    };
  },
  methods: {
    onDrop(collection, dropResult) {
      this[collection] = this.applyDrag(this[collection], dropResult);
    },
    getChildPayload1(index) {
      return this.records[index];
    },
    getChildPayload2(index) {
      return this.appRecords[index];
    },
    applyDrag: function(arr, dragResult) {
      const { removedIndex, addedIndex, payload } = dragResult;
      if (removedIndex === null && addedIndex === null) return arr;
      if (payload && removedIndex === null) {
        this.acId = payload.ac_id;
        this.bindingRecords(payload.ac_type);
      }
      const result = [...arr];
      let itemToAdd = payload;

      if (removedIndex !== null) {
        itemToAdd = result.splice(removedIndex, 1)[0];
      }

      if (addedIndex !== null) {
        result.splice(addedIndex, 0, itemToAdd);
      }
      if (removedIndex !== null) {
        this.updateInternalSorting(dragResult, result);
      }
      return result;
    },
    filterAppRecords: function(key) {
      this.activeTab = key;
      if(key == 'all') {
        this.records = this.defaultAppListing;
        return false;
      }
      let filteredRecords = [];
      let acType = '';
      Object.keys(this.defaultAppListing).forEach(valkey => {
        acType = this.defaultAppListing[valkey].ac_type;
        switch(key) {
          case 'categories':
            if(this.$inArray(acType,[1,9,12]) == true){
              filteredRecords.push(this.defaultAppListing[valkey]);
            }
             break;
          case 'brands':
            if(this.$inArray(acType,[5,10]) == true){
              filteredRecords.push(this.defaultAppListing[valkey]);
            }
             break;
          case 'banners':
            if(this.$inArray(acType,[2,3,11]) == true){
              filteredRecords.push(this.defaultAppListing[valkey]);
            }
            break;
          case 'blogs':
             if(this.$inArray(acType,[7,13]) == true){
              filteredRecords.push(this.defaultAppListing[valkey]);
            }
           break;
          case 'testimonial':
             if(this.$inArray(acType,[6,15]) == true){
              filteredRecords.push(this.defaultAppListing[valkey]);
            }
            break;
          case 'products':
            if(this.$inArray(acType,[4,8]) == true){
              filteredRecords.push(this.defaultAppListing[valkey]);
            }
           break;
          // case 'recentBought':
          //    if(this.$inArray(acType,[14]) == true){
          //     filteredRecords.push(this.defaultAppListing[valkey]);
          //   }
          //   break;
         }
     });
      this.records = filteredRecords;
   },
  
    updateInternalSorting: function(dragResult, result) {
      const { removedIndex, addedIndex, payload } = dragResult;
      let maxVal = Math.max(removedIndex, addedIndex);
      let minVal = Math.min(removedIndex, addedIndex);

      let sortArray = [];
      for (let x = minVal; x <= maxVal; x++) {
        result[x].actr_display_order = x + 1;
        sortArray.push({
          id: result[x].actr_id,
          order: result[x].actr_display_order
        });
      }
      let formData = new FormData();
      formData.append("sorting", JSON.stringify(sortArray));
      this.$http
        .post(adminBaseUrl + "/app/collection/sorting-update", formData)
        .then(response => {});
    },
    bindingRecords: function(type, collectionId = 0) {
      this.collectionId = collectionId;
      switch (type) {
        case 1:
          this.collectionType = "category-collection1";
          this.clickEvent = this.$rndStr();
          this.$bvModal.show("categoryCollection");
          break;
        case 2:
          this.collectionType = "banner-slider-collection1";
          this.bsclickEvent = this.$rndStr();
          this.$bvModal.show("bannerSliderCollection");
          break;
        case 3:
          this.collectionType = "banner-slider-collection2";
          this.bsclickEvent = this.$rndStr();
          this.$bvModal.show("bannerSliderCollection");
          break;
        case 4:
          this.collectionType = "product-collection1";
          this.prodClickEvent = this.$rndStr();
          this.$bvModal.show("productCollection");
          break;
        case 5:
          this.collectionType = "brand-collection1";
          this.bClickEvent = this.$rndStr();
          this.$bvModal.show("brandCollection");
          break;
        case 6:
          this.collectionType = "testimonial-collection1";
          this.testimonialClickEvent = this.$rndStr();
          this.$bvModal.show("testimonialCollection");
          break;
        case 7:
          this.collectionType = "blog-collection1";
          this.bgClickEvent = this.$rndStr();
          this.$bvModal.show("blogCollection");
          break;
        case 8:
          this.prodClickEvent = this.$rndStr();
          this.collectionType = "product-collection2";
          this.$bvModal.show("productCollection");
          break;
        case 9:
          this.clickEvent = this.$rndStr();
          this.collectionType = "category-collection2";
          this.$bvModal.show("categoryCollection");
          break;
        case 10:
          this.bClickEvent = this.$rndStr();
          this.collectionType = "brand-collection2";
          this.$bvModal.show("brandCollection");
          break;
        case 11:
          this.collectionType = "banner-slider-collection3";
          this.bsclickEvent = this.$rndStr();
          this.$bvModal.show("bannerSliderCollection");
        break;
        case 12:
          this.collectionType = "category-collection3";
          this.clickEvent = this.$rndStr();
          this.$bvModal.show("categoryCollection");
          break;
        case 13:
          this.collectionType = "blog-collection2";
          this.bgClickEvent = this.$rndStr();
          this.$bvModal.show("blogCollection");
          break;
          // case 14:
          //   this.saveRecentBougthCollection();
          // break;
        case 15:
          this.collectionType = "testimonial-collection2";
          this.testimonialClickEvent = this.$rndStr();
          this.$bvModal.show("testimonialCollection");
          break;
      }
    },
    // saveRecentBougthCollection: function(id = 0){
    //   let formData = new FormData();
    //   formData.append("page-id", this.pageId);
    //   formData.append("actr-id", id);
    //   formData.append("ac-id", this.acId);
    //   formData.append("type", "recent-bought-collection1");
    //   this.$http
    //     .post(adminBaseUrl + "/app/store/records", formData)
    //     .then(response => {
    //       this.pageLoadData(true);
    //     });
    // },
    editCollection: function(editId) {
      this.bindingRecords(
        this.appRecords[editId].ac_type,
        this.appRecords[editId].actr_id
      );
    },

    pageLoadData: function(appRecordsOnly = false) {
      let formData = this.$serializeData({
        "page-id": this.pageId,
        "records-type": appRecordsOnly
      });
      this.$http
        .post(adminBaseUrl + "/app/recrods", formData)
        .then(response => {
          if (appRecordsOnly == false) {
            response.data.data.defaultListing = response.data.data.defaultListing.filter((val)=>val.ac_type !=14)
            this.records = response.data.data.defaultListing;
           this.defaultAppListing = response.data.data.defaultListing;
          }
         this.appRecords = response.data.data.appListing;
        });
    },
    deleteImageById: function(id = 0) {
      let formData = this.$serializeData({
        "img-id": id
      });
      this.$http
        .post(adminBaseUrl + "/app/delete/image", formData)
        .then(response => {});
    },
    deleteCollection: function(id, type = "") {
      let formData = this.$serializeData({
        id: this.appRecords[id].actr_id,
        type: type
      });
      this.$http
        .post(adminBaseUrl + "/app/delete/collection", formData)
        .then(response => {});
      this.$delete(this.appRecords, id);
    },
    deleteCollRecordById: function(recordId, imgId = 0, type = "") {
      let formData = this.$serializeData({
        img_id: imgId,
        record_id: recordId,
        type: type
      });
      this.$http
        .post(adminBaseUrl + "/app/delete/record", formData)
        .then(response => {});
    }
  },
  mounted: function() {
    this.pageLoadData();
  }
};
</script>
