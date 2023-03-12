<template>
  <b-modal id="testimonialCollection" size="lg" centered title="BootstrapVue">
    <template v-slot:modal-header>
      <h5 class="modal-title" id="exampleModalLabel">
        <span>Testimonial Collection</span>
      </h5>
      <button type="button" class="close" @click="closePopup(true)"></button>
    </template>
    <div class="form-group row">
      <div class="col-md-12">
        <input type="text" class="form-control" placeholder="Title" v-model="pageTitle" />
      </div>
    </div>
    <div class="form-group">
      <input
        type="text"
        :placeholder="$t('PLH_BRAND_PLEASE_SEARCH')"
        aria-label="Search Brand"
        autocomplete="off"
        class="form-control"
        data-toggle="dropdown"
        aria-expanded="true"
        v-model="testimonialName"
        @keyup="searchTestimonials"
      />
      <div class="dropdown-menu dropdown-menu-fit dropdown-menu-anim dropdown-suggestions">
        <ul class="nav nav--block">
          <perfect-scrollbar :style="'max-height: 200px'">
            <li
              class="dropdown-item nav__item"
              v-for="(testimonial, index) in testimonials"
              :key="index"
            >
              <a
                href="javascript:;"
                @click="selectedTestimonial(testimonial)"
              >{{ testimonial.label }}</a>
            </li>
          </perfect-scrollbar>
        </ul>
      </div>
    </div>
    <perfect-scrollbar :style="'max-height: 400px'" v-if="selectedCollections.length > 0">
      <SlickList
        lockAxis="y"
        class="list-group list-group__collections"
        @sort-end="sortEnd"
        v-model="selectedCollections"
        tag="ul"
      >
        <SlickItem
          class="list-group-item"
          v-for="(selectedCollection, sindex) in selectedCollections"
          :index="sindex"
          :key="sindex"
          tag="li"
        >
          <div class="row align-items-center">
            <div class="col-auto">
              <i class="icon fa fa-arrows-alt handle"></i>
            </div>
            <div class="col">
              <h5>{{selectedCollection.name}}</h5>

              <div
                class="file-input overflow-hidden"
                v-if="collectionType != 'testimonial-collection2'"
              >
                <button
                  type="button"
                  @click="openCropper(sindex)"
                  class="btn btn-secondary btn-wide"
                >Upload a file</button>
              </div>
              <p class="img-disclaimer editor" v-if="collectionType != 'testimonial-collection2'">
                <strong>Image Disclaimer:</strong> File type must be a .jpg, .gif or .png smaller than 2MB and at least 250x250 in 1:1 aspect ratio
              </p>
            </div>
            <div class="col-auto" v-if="collectionType != 'testimonial-collection2'">
              <ul class="list-media">
                <li class="media">
                  <img
                    data-yk
                    alt
                    class="croppedImage"
                    :src="$getFileUrlById(selectedCollection.imgid,'105-105',selectedCollection.imgtime)"
                  />
                  <div class="media-actions" v-if="selectedCollection.imgid">
                    <button type="button" @click="deleteImage(sindex)" class="btn btn-icon btn-sme">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </li>
              </ul>
              <div class="progress" v-if="progressBar[sindex] > 0 && progressBar[sindex] < 100" style="height: 1.5rem;">
                  <div class="progress-bar" role="progressbar" :style="'width: ' + progressBar[sindex] + '%;'" :aria-valuenow="progressBar[sindex]" aria-valuemin="0" aria-valuemax="100" v-html="progressBar[sindex] + '%'"></div>
              </div> 
            </div>
            <div class="actions">
              <button type="button" class="btn btn-icon" @click="deleteCatCollection1(sindex)">
                <svg>
                  <use :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#delete-icon'" />
                </svg>
              </button>
            </div>
          </div>
        </SlickItem>
      </SlickList>
    </perfect-scrollbar>
    <div class="no-data-found" v-else>
      <div class="img">
        <img :src="this.$noDataImage()" alt />
      </div>
      <div class="data">
        <p>{{ $t('LBL_USE_ICONS_FOR_ADVANCED_ACTIONS') }}</p>
      </div>
    </div>
    <cropper
      ref="cropperRef"
      :icon="false"
      :aspectRatio="1"
      :width="1116"
      :height="1116"
      :modalId="'modal_cropper'"
      @closePopup="closePopup"
      v-on:childToParent="setImage"
      v-on:actualImageToParent="setActualImage"
    ></cropper>
    <img :src="originalImage" id="originalImage" style="display: none;" />
    <template v-slot:modal-footer>
      <button
        type="submit"
        :disabled="selectedCollections.length == 0 || pageTitle == ''"
        class="btn btn-brand"
        @click="saveCollection"
      >
        <span>Embed</span>
      </button>
    </template>
  </b-modal>
</template>
<script>
import { SlickList, SlickItem } from "vue-slicksort";

export default {
  components: {
    SlickItem,
    SlickList
  },
  props: ["clickEvent", "pageId", "acId", "collectionId", "collectionType"],
  data() {
    return {
      baseUrl: baseUrl,
      adminBaseUrl: adminBaseUrl,
      progressBar: {},
      croppedImage: "",
      croppedImageView: "",
      pageTitle: "",
      originalImage: "",
      uploadedFile: "",
      testimonialName: "",
      testimonials: [],
      listingIndex: 0,
      sortCollection: [],
      selectedCollections: []
    };
  },
  watch: {
    clickEvent: function() {
      this.sortCollection = [];
      this.selectedCollections = [];
      this.pageTitle = "";
      if (this.collectionId) {
        this.getRecords();
      }
    }
  },
  methods: {
    sortEnd({ oldIndex, newIndex }) {
      let thisObj = this;
      setTimeout(function() {
        let maxVal = Math.max(oldIndex, newIndex);
        let minVal = Math.min(oldIndex, newIndex);
        for (let x = minVal; x <= maxVal; x++) {
          thisObj.selectedCollections[x].order = thisObj.sortCollection[x];
        }
      }, 100);
    },
    closePopup: function(close = false) {
      if (close) {
        this.$emit("pageLoadData", true);
        this.$bvModal.hide("testimonialCollection");
        return;
      }
      $("#testimonialCollection").show();
    },
    saveCollection: function() {
      let formData = new FormData();
      formData.append("collection", JSON.stringify(this.selectedCollections));
      formData.append("page-id", this.pageId);
      formData.append("ac-id", this.acId);
      formData.append("actr-id", this.collectionId);
      formData.append("type", this.collectionType);
      formData.append("title", this.pageTitle);
      this.$http
        .post(adminBaseUrl + "/app/store/records", formData)
        .then(response => {
          this.closePopup(true);
        });
    },
    setImage: function(cropUrl) {
      this.croppedImage = cropUrl;
      this.croppedImageView = URL.createObjectURL(cropUrl);
      this.uploadImages();
    },
    openCropper: function(index) {
      this.listingIndex = index;
      let orgImage = "";
      if (this.selectedCollections[index].originalImage) {
        orgImage = this.selectedCollections[index].originalImage;
        this.uploadedFile = this.croppedImage = this.croppedImageView = this.$getFileUrlById(
          this.selectedCollections[index].imgid,
          "thumb",
          this.selectedCollections[index].imgtime
        );
      }

      this.originalImage = orgImage;
  //    $("#testimonialCollection").hide();
      let thisObj = this;
      setTimeout(function() {
        thisObj.$refs.cropperRef.openCropper();
      }, 100);
    },
    setActualImage: function(actualImage) {
      if (typeof actualImage != "string") {
        this.originalImage = URL.createObjectURL(actualImage);
        this.uploadedFile = actualImage;
      } else {
        this.uploadedFile = this.originalImage = actualImage;
      }
      this.uploadImages();
    },
    uploadImages: function() {
      let _this = this;
      if (this.uploadedFile == "") {
        return;
      }
      let formData = new FormData();
      formData.append("actualImage", this.uploadedFile);
      formData.append("cropImage", this.croppedImage);
      let acrd_id = this.selectedCollections[this.listingIndex].acrd_id;
      let subid = this.selectedCollections[this.listingIndex].record_id;
      if (acrd_id != 0) {
        subid = 0;
      }
      if (
        this.selectedCollections[this.listingIndex].rand_id == 0 &&
        acrd_id == 0
      ) {
        acrd_id = this.$rndInt(8);
      } else if (
        this.selectedCollections[this.listingIndex].rand_id != 0 &&
        acrd_id == 0
      ) {
        acrd_id = this.selectedCollections[this.listingIndex].rand_id;
      }

      formData.append("id", acrd_id);

      formData.append("subid", subid);
      formData.append("type", "appTestimonialCollection");

         $("#testimonialCollection").show();
      let pIndex = this.listingIndex;   
      this.$http
        .post(adminBaseUrl + "/app/upload-images", formData,{progress:function(e){
          if (e.lengthComputable) {
            _this.progressBar[pIndex] = (e.loaded / e.total * 100).toFixed(2)
          }
        }})
        .then(response => {
            this.selectedCollections[this.listingIndex].imgid =
            response.data.data.afile_id;
          this.selectedCollections[this.listingIndex].imgtime =
            response.data.data.afile_updated_at;
          this.selectedCollections[this.listingIndex].rand_id = acrd_id;
          this.selectedCollections[
            this.listingIndex
          ].originalImage = this.originalImage;
          this.listingIndex = "";
          this.uploadedFile = "";
          this.originalImage = "";
        });
    },
    deleteCatCollection1: function(index) {
      if (this.selectedCollections[index].acrd_id != 0) {
        this.$emit(
          "deleteCollRecordById",
          this.selectedCollections[index].acrd_id,
          this.selectedCollections[index].imgid
        );
      } else if (this.selectedCollections[index].imgid != 0) {
        this.$emit("deleteImageById", this.selectedCollections[index].imgid);
      }
      this.$delete(this.selectedCollections, index);
      this.$delete(this.sortCollection, index);
    },
    deleteImage: function(index) {
      this.$emit("deleteImageById", this.selectedCollections[index].imgid);
      this.selectedCollections[index].imgid = 0;
      this.selectedCollections[index].imgtime = "";
      this.selectedCollections[index].originalImage = "";
    },

    searchTestimonials: function() {
      let formData = this.$serializeData({
        type: "testimonial",
        search: this.testimonialName
      });
      this.$http
        .post(adminBaseUrl + "/app/collection/search-records", formData)
        .then(response => {
          this.testimonials = response.data.data;
        });
    },
    selectedTestimonial: function(record) {
      if (
        this.$collectionNameExist(record.label, this.selectedCollections) ==
        true
      ) {
        toastr.error("You have selected duplicate record", "", toastr.options);
        return;
      }
      let maxSortVal = 1;
      if (this.sortCollection.length > 0) {
        maxSortVal = Math.max.apply(null, this.sortCollection) + 1;
      }
      this.selectedCollections.push({
        record_id: record.value,
        name: record.label,
        imgid: 0,
        originalImage: "",
        imgtime: "",
        order: maxSortVal,
        acrd_id: 0
      });
      this.sortCollection.push(maxSortVal);
      this.testimonialName = "";
    },
    getRecords: function() {
      let formData = this.$serializeData({
        type: this.collectionType,
        "collection-id": this.collectionId
      });
      this.$http
        .post(adminBaseUrl + "/app/collection", formData)
        .then(response => {
          this.selectedCollections = [];
          let editData = response.data.data.editData;
          Object.keys(editData).forEach(key => {
            this.pageTitle = editData[key].actr_title;
            this.selectedCollections.push({
              record_id: editData[key].testimonial_id,
              name: editData[key].testimonial_title,
              acrd_id: editData[key].acrd_id,
              order: editData[key].acrd_display_order,
              imgid: editData[key].images ? editData[key].images.afile_id : 0,
              originalImage: editData[key].images
                ? this.$getFileUrlById(
                    editData[key].images.afile_id,
                    "original",
                    editData[key].images.afile_updated_at
                  )
                : "",
              imgtime: editData[key].images
                ? editData[key].images.afile_updated_at
                : ""
            });
            this.sortCollection.push(editData[key].acrd_display_order);
          });
        });
    }
  },
  mounted() {
    let thisVal = this;
    $(document).ready(function() {
      $(document).on("click", "#modal_cropper", function(event) {
        if (
          $("#modal_cropper").length &&
          !$(event.target).closest(".modal-content").length
        ) {
          thisVal.$bvModal.hide("modal_cropper");
          $("#testimonialCollection").show();
        }
      });
    });
  }
};
</script>
