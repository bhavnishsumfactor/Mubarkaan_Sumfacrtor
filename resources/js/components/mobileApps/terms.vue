<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_MOBILE_APPS') }}</h3>
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
                <div class="col-md-5">
                  <div class="app__collections info__collections">
                    <div class="app__collections-head d-flex justify-content-between">
                    <h3>Terms & Condition</h3>
                  </div>
                    <div class="app__collections-wrapper ">
                      <container
                        behaviour="copy"
                        group-name="1"
                        :get-child-payload="getChildPayload1"
                      >
                        <draggable :key="rIndex" v-for="(record, rIndex) in records">
                        <tags-collection :type="record.ac_type"></tags-collection>
                        </draggable>
                      </container>
                    </div>
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="device__preview-wrapper">
                    <div class="device__preview">
                      <div class="device__screen">
                        <div class="app__collection app__header mb-0">
                          <div class="app_collection-content">
                            <div class="head__content">
                              <div class="head__content-left">
                                <div class="head__item logo">
                                  <img
                                      :src="
                                    baseUrl +
                                      '/admin/images/app-home/logo.png'
                                  "
                                    />
                                </div>
                              </div>
                              <div class="head__content-right">
                                <div class="head__item">
                                  <img
                                      :src="
                                    baseUrl +
                                      '/admin/images/app-home/search.png'
                                  "
                                    />
                                </div>
                                <div class="head__item">
                                  <img
                                      :src="
                                    baseUrl +
                                      '/admin/images/app-home/cart.png'
                                  "
                                    />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div
                          class="scroll-y"
                          style="max-height:496px;min-height:496px;padding: 1rem;"
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
                          <tags-collection :type="appRecord.ac_type" :appRecord="appRecord"
                             @deleteCollection="deleteCollection"
                                @editCollection="editCollection"
                                :id="srIndex"
                                ></tags-collection>
                              </draggable> 
                          </container>
                       
                        </div>
                        <div class="app__collection app__navigation mb-0">
                          <div class="image-wrapper">
                            <img :src="baseUrl + '/admin/images/app-home/navigation.png'" />
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
     <tags-collection-form
      :clickEvent="clickEvent"
      @pageLoadData="pageLoadData"
      :acId="acId"
      :pageId="pageId"
      :collectionId="collectionId"
      :collectionType="collectionType"
      @deleteCollRecordById="deleteCollRecordById"
    ></tags-collection-form>
  </div>
</template>

<script>
import { Container, Draggable } from "vue-smooth-dnd";
import sidebar from "./sidebar";
import TagsCollection from "./partial/TagsCollection";
import TagsCollectionForm from "./popups/TagsCollectionForm";

export default {
  name: "Copy",
  components: {
    sidebar: sidebar,
    TagsCollection,
    TagsCollectionForm,
    Container,
    Draggable
  },
  data() {
    return {
      pageId: 3,
      type: "terms",
      clickEvent: "",
      acId: "",
      collectionId: "",
      collectionType: "",
      baseUrl: baseUrl,
      records: [],
      appRecords: [],
      dropPlaceholderOptions: {
        className: "drop-preview",
        animationDuration: "150",
        showOnTop: true
      }
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
        this.clickEvent = this.$rndStr();
        this.acId = payload.ac_id;
        this.collectionType = payload.ac_type;
         this.$bvModal.show("tagsCollection");
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
    editCollection: function(editId) {
      this.collectionId =  this.appRecords[editId].actr_id;
      this.collectionType = this.appRecords[editId].ac_type;
      this.clickEvent = this.$rndStr();
      this.$bvModal.show("tagsCollection"); 
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
      pageLoadData: function(appRecordsOnly = false) {
      let formData = this.$serializeData({
        "page-id": this.pageId,
        "records-type": appRecordsOnly
      });
      this.$http
        .post(adminBaseUrl + "/app/recrods", formData)
        .then(response => {
          if (appRecordsOnly == false) {
            this.records = response.data.data.defaultListing;
          }
          this.appRecords = response.data.data.appListing;
          this.collectionId = 0;
        });
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
