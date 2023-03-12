<template>
  <b-modal id="bannerSliderCollection" size="lg" centered title="BootstrapVue">
    <template v-slot:modal-header>
      <h5 class="modal-title" id="exampleModalLabel">
        <span>Slider Collection</span>
      </h5>
      <button type="button" class="close" @click="closePopup()"></button>
    </template>
    <div class="form-group row">
      <label class="col-md-4 col-form-label" for="validationCustom01">Title</label>
      <div class="col-md-8">
        <input type="text" class="form-control" placeholder="Title" v-model="pageTitle" />
      </div>
    </div>
    <div class="form-group row">
      <label class="col-md-4 col-form-label" for="validationCustom01">Slide duration (in seconds)</label>
      <div class="col-md-8">
        <input
          type="number"
          min="1"
          max="10"
          class="form-control"
          name="slide_duration"
          placeholder="Enter in seconds"
          v-model="duration"
        />
      </div>
    </div>
    <div id="tabs">
      <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x" role="tablist">
        <li role="presentation" v-for="(tab, index) in tabs" :key="index" class="nav-item">
          <a
            href="#"
            role="tab"
            data-toggle="tab"
            class="nav-link"
            v-bind:class="{active:tab.isActive}"
          >
            <span @click.stop.prevent="setActive(tab)">Slide {{index+1}}</span>
            <i
              class="fas fa-times ml-2"
              v-bind:class="[tabs.length == 1 ? 'disabled':'']"
              @click="tabs.length != 1 ?  deleteTab(index) :''"
            ></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="javascript:;" @click="openNewTab">
            <i class="fa fa-plus ml-2"></i>
          </a>
        </li>
      </ul>
      <div class="tab-content">
        <div
          v-for="(tab, index) in tabs"
          role="tabpanel"
          :key="index"
          class="tab-pane"
          v-bind:class="{active:tab.isActive}"
        >
          <div class="form-group row">
            <label class="col-md-4 col-form-label">I will link</label>
            <div class="col-md-8">
              <select
                class="form-control"
                v-model="tab.linkType"
                @change="updateLink(index)"
                :disabled="duration ==''"
              >
                <option value readonly disabled selected>Choose...</option>
                <option
                  v-for="(linkType, lIndex) in linkTypes"
                  :value="lIndex"
                  :key="lIndex"
                >{{linkType}}</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <input
              type="text"
              :placeholder="$t('PLH_PLEASE_SEARCH')"
              autocomplete="off"
              class="form-control"
              data-toggle="dropdown"
              :disabled="tab.linkType == ''"
              aria-expanded="true"
              v-model="tab.searchKeyword"
              @keyup="searchRecords(tab.searchKeyword,tab.linkType)"
            />
            <div
              class="dropdown-menu dropdown-menu-fit dropdown-menu-anim dropdown-suggestions"
              v-if="tab.linkType != 5 "
            >
              <ul class="nav nav--block">
                <perfect-scrollbar :style="'max-height: 200px'">
                  <li
                    class="dropdown-item nav__item"
                    v-for="(searchedRecord, sindex) in searchedRecords"
                    :key="sindex"
                  >
                    <a
                      href="javascript:;"
                      @click="selectedSearched(searchedRecord, index)"
                    >{{ searchedRecord.label }}</a>
                  </li>
                </perfect-scrollbar>
              </ul>
            </div>

            <ul class="tagify-custom my-2" v-if="tab.selectedLinks.length > 0">
              <li v-for="(selectedLink, lIndex) in tab.selectedLinks" :key="lIndex">
                <span>{{selectedLink.name}}</span>
                <a
                  href="javascript:;"
                  class="remove"
                  @click="deleteLinks(tab.selectedLinks, lIndex)"
                >
                  <i class="fas fa-times"></i>
                </a>
              </li>
            </ul>
          </div>
          <ul class="list-group list-group-lg list-uploads mb-4">
            <li class="list-group-item yk-slide" data-type="desktop" data-aspect-ratio="4">
              <div class="row">
                <div class="col-12">
                  <div class="file-input overflow-hidden d-block">
                    <button
                      type="button"
                      class="btn btn-secondary btn-wide"
                      :disabled="duration == ''"
                      @click="openCropper(index)"
                    >Upload a file</button>
                  </div>
                 
                  <p class="img-disclaimer editor">
                    <strong>Desktop:</strong> File type must be a .jpg, .gif or .png smaller than 2MB and at least 2000x500 in 4:1 aspect ratio
                  </p>
                   <div class="progress" v-if="progressBar > 0 && progressBar < 100" style="height: 1.5rem;">
                      <div class="progress-bar" role="progressbar" :style="'width: ' + progressBar + '%;'" :aria-valuenow="progressBar" aria-valuemin="0" aria-valuemax="100" v-html="progressBar + '%'"></div>
                  </div>  
                </div>
                <div class="col-12" v-if="tab.imgid != 0">
                  <ul class="list-media">
                    <li class="media">
                      <img
                        data-yk
                        alt
                        class="croppedImage"
                        :src="$getFileUrlById(tab.imgid,collectionType == 'banner-slider-collection3' ? '96-92' : '200-100',tab.imgtime)"
                      />
                      <div class="media-actions">
                        <button
                          type="button"
                          @click="deleteImage(index)"
                          class="btn btn-icon btn-sm"
                        >
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <cropper
      ref="cropperRef"
      :icon="false"
      :aspectRatio="collectionType == 'banner-slider-collection3' ? 0.66 : 1.777"
      :width="collectionType == 'banner-slider-collection3' ? 558 : 1116"
      :height="collectionType == 'banner-slider-collection3' ? 882 : 558"
      :modalId="'modal_cropper'"
      @closePopup="closePopup"
      v-on:childToParent="setImage"
      v-on:actualImageToParent="setActualImage"
    ></cropper>
    <img :src="originalImage" id="originalImage" style="display: none;" />
    <template v-slot:modal-footer>
      <button type="submit" :disabled="!isComplete" class="btn btn-brand" @click="saveCollection">
        <span>Embed</span>
      </button>
    </template>
  </b-modal>
</template>
<script>
export default {
  props: ["clickEvent", "pageId", "acId", "collectionId", "collectionType"],
  data() {
    return {
      baseUrl: baseUrl,
      adminBaseUrl: adminBaseUrl,
      progressBar: 0,
      searchedRecords: [],
      duration: "",
      croppedImage: "",
      pageTitle: "",
      croppedImageView: "",
      originalImage: "",
      uploadedFile: "",
      listingIndex: 0,
      linkTypes: {},
      tabs: [
        {
          id: 0,
          isActive: true,
          linkType: "",
          selectedLinks: [],
          imgid: 0,
          originalImage: "",
          imgtime: "",
          searchKeyword: "",
          acs_id: 0
        }
      ],
      activeTab: {},
      selectedCollections: []
    };
  },
  watch: {
    clickEvent: function() {
      this.tabs = [];
      this.duration = "";
      this.pageTitle = "";
      this.getRecords();
    }
  },
  computed: {
    isComplete() {
      return (
        this.tabs.length != 0 &&
        this.uploadedImageCount() == this.tabs.length &&
        this.duration != "" &&
        this.pageTitle != ""
      );
    }
  },
  methods: {
    uploadedImageCount: function() {
      let count = 0;
      Object.keys(this.tabs).forEach(key => {
        if (this.tabs[key].imgid != 0) {
          count = count + 1;
        }
      });
      return count;
    },
    updateLink: function(index) {
      this.tabs[index].selectedLinks = [];
    },
    deleteLinks: function(records, index) {
      this.$delete(records, index);
    },

    deleteTab: function(index) {
      if (index != 0 && this.tabs[index].isActive == true) {
        let nextIndex = index - 1;
        this.tabs[nextIndex].isActive = true;
        this.setActive(this.tabs[nextIndex]);
      }

      if (this.tabs[index].acs_id != 0) {
        this.$emit(
          "deleteCollRecordById",
          this.tabs[index].acs_id,
          this.tabs[index].imgid,
          "banner"
        );
      } else if (this.tabs[index].imgid != 0) {
        this.$emit("deleteImageById", this.tabs[index].imgid);
      }
      this.$delete(this.tabs, index);
    },
    setActive: function(tab) {
      let self = this;
      tab.isActive = true;
      this.activeTab = tab;
      this.tabs.forEach(function(tab) {
        if (tab.id !== self.activeTab.id) {
          tab.isActive = false;
        }
      });
    },
    openNewTab: function() {
      let newTab = {
        id: this.tabs.length,
        isActive: true,
        linkType: "",
        searchKeyword: "",
        selectedLinks: [],
        imgid: 0,
        originalImage: "",
        imgtime: "",
        rand_id: 0,
        acs_id: 0
      };
      this.tabs.push(newTab);
      this.setActive(newTab);
    },

    closePopup: function() {
      this.$emit("pageLoadData", true);
      this.$bvModal.hide("bannerSliderCollection");
    },
    saveCollection: function() {
      let formData = new FormData();
      formData.append("collection", JSON.stringify(this.tabs));
      formData.append("page-id", this.pageId);
      formData.append("ac-id", this.acId);
      formData.append("actr-id", this.collectionId);
      formData.append("duration", this.duration);
      formData.append("type", this.collectionType);
      formData.append("title", this.pageTitle);
      this.$http
        .post(adminBaseUrl + "/app/store/records", formData)
        .then(response => {
          this.closePopup();
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
      if (this.tabs[index].originalImage) {
        orgImage = this.tabs[index].originalImage;
        this.uploadedFile = this.croppedImage = this.croppedImageView = this.$getFileUrlById(
          this.tabs[index].imgid,
          "thumb",
          this.tabs[index].imgtime
        );
      }
      this.originalImage = orgImage;
  //    $("#bannerSliderCollection").hide();
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
      let acs_id = this.tabs[this.listingIndex].acs_id;
      let subid = this.tabs[this.listingIndex].record_id;
      if (acs_id != 0) {
        subid = 0;
      }
      if (this.tabs[this.listingIndex].rand_id == 0 && acs_id == 0) {
        acs_id = this.$rndInt(8);
      } else if (this.tabs[this.listingIndex].rand_id != 0 && acs_id == 0) {
        acs_id = this.tabs[this.listingIndex].rand_id;
      }
      formData.append("id", acs_id);

      formData.append("subid", subid);
      formData.append("type", "appBannerSliderCollection");
       $("#bannerSliderCollection").show();
      this.$http
        .post(adminBaseUrl + "/app/upload-images", formData,{progress:function(e){
          if (e.lengthComputable) {
            _this.progressBar = (e.loaded / e.total * 100).toFixed(2)
          }
        }})
        .then(response => {
          this.tabs[this.listingIndex].imgid = response.data.data.afile_id;
          this.tabs[this.listingIndex].imgtime =
            response.data.data.afile_updated_at;
          this.tabs[this.listingIndex].originalImage = this.originalImage;
          this.listingIndex = "";
          this.uploadedFile = "";
          this.originalImage = "";
        });
    },
    deleteImage: function(index) {
      this.$emit("deleteImageById", this.tabs[index].imgid);
      this.tabs[index].imgid = 0;
      this.tabs[index].imgtime = "";
      this.tabs[index].originalImage = "";
      this.tabs[index].rand_id = 0;
    },
    selectedSearched: function(record, index) {
      if (
        this.$collectionNameExist(
          record.label,
          this.tabs[index].selectedLinks
        ) == true
      ) {
        toastr.error("You have selected duplicate record", "", toastr.options);
        return;
      }
      this.tabs[index].selectedLinks.push({
        record_id: record.value,
        name: record.label,
        acs_id: 0
      });
      this.tabs[index].searchKeyword = "";
      this.searchedRecords = [];
    },
    searchRecords: function(searchKeyword, type) {
      if (type == 5) {
        return;
      }
      let formData = this.$serializeData({
        type: this.linkTypes[type],
        search: searchKeyword
      });
      this.$http
        .post(adminBaseUrl + "/app/collection/search-records", formData)
        .then(response => {
          this.searchedRecords = response.data.data;
        });
    },
    getRecords: function() {
      let formData = this.$serializeData({
        type: this.collectionType,
        "collection-id": this.collectionId
      });
      this.$http
        .post(adminBaseUrl + "/app/collection", formData)
        .then(response => {
          this.linkTypes = response.data.data.linkingTypes;
          this.selectedCollections = [];
          let editData = response.data.data.editData;
          let newTab = {};
          Object.keys(editData).forEach(key => {
            this.duration = editData[key].actr_slide_duration;
            this.pageTitle = editData[key].actr_title;

            newTab = {
              id: key,
              isActive: key == 0 ? true : false,
              searchKeyword:
                editData[key].acs_type == 5 && editData[key].custom
                  ? editData[key].custom.acsl_value
                  : "",
              rand_id: 0,
              linkType: editData[key].acs_type,
              selectedLinks: this.bindingLinks(editData[key]),
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
                : "",
              acs_id: editData[key].acs_id
            };
            if (this.tabs[key]) {
              this.deleteTab(key);
            }
            this.tabs.push(newTab);
          });
        });
    },
    bindingLinks: function(records) {
      switch (records.acs_type) {
        case 1:
          return records.brands;
          break;
        case 2:
          return records.categories;
          break;
        case 3:
          return records.products;
          break;
        case 4:
          return records.pages;
          break;
        case 5:
          return [];
          break;
        default:
          return [];
          break;  
      }
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
          $("#bannerSliderCollection").show();
        }
      });
    });
  }
};
</script>
