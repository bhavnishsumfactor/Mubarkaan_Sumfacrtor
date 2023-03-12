<template>
  <b-modal
    id="tagsCollection"
    size="md"
    centered
    title="BootstrapVue"
    no-close-on-backdrop
  >
    <template v-slot:modal-header>
      <h5 class="modal-title" id="exampleModalLabel">
        <span>Tag Collection</span>
      </h5>
      <button type="button" class="close" @click="closePopup()"></button>
    </template>
    <div class="row justify-content-center my-4" v-if="collectionType == 23">
      <div class="col-md-12" >
     <label for="" class="col-form-label">Caption goes here</label>

        <div class="form-group" v-for="(ulTag, index) in ulTags" :key="index">
          <div class="icon-group actions">
            <input type="text" v-model="ulTag.name" class="form-control" />
            <a
              href="javascript:;"
              class="btn btn-icon btn-light ml-2 "
              v-bind:class="[index == 0 ? 'disabled' : '']"
              @click="deleteTags(index)"
            >
              <svg>
                <use
                  :href="
                    baseUrl + '/admin/images/retina/sprite.svg#delete-icon'
                  "
                ></use>
              </svg>
            </a>
            <button
              class="btn btn-icon btn-light ml-2"
              @click="addTags()"
              v-if="index == ulTags.length - 1"
            >
              <i data-v-588946e8="" class="fas fa-plus"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center my-4" v-if="collectionType == 24">
      <div class="col-md-12">
     <label for="" class="col-form-label">Caption goes here</label>
        <input
          type="text"
          autocomplete="off"
          class="form-control mb-3"
          v-model="text"
        />
        <ul class="list-group">
          <li class="list-group-item">
            <div class="row">
              <div class="col">
                <div class="file-input overflow-hidden">
                  <button
                    type="button"
                    @click="openCropper()"
                    class="btn btn-secondary btn-wide"
                  >
                    Upload a file
                  </button>
                </div>
                <p class="img-disclaimer editor">
                  <strong>Image Disclaimer:</strong> File type must be a .jpg,
                  .gif or .png smaller than 2MB and at least 250x250 in 1:1 aspect
                  ratio
                </p>
              </div>
              <div class="col-auto preview-image YK-preview">
                <ul class="list-media">
                  <li class="media">
                    <img
                      data-yk
                      alt
                      class="croppedImage"
                      :src="$getFileUrlById(imgid, '105-105', imgtime)"
                    />
                    <div class="media-actions" v-if="imgid">
                      <button
                        type="button"
                        @click="deleteImage()"
                        class="btn btn-icon btn-sme"
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
      <cropper
        ref="cropperRef"
        :icon="false"
        :aspectRatio="1.77"
        :width="1116"
        :height="627"
        :modalId="'modal_cropper'"
        @closePopup="closePopup"
        v-on:childToParent="setImage"
        v-on:actualImageToParent="setActualImage"
      ></cropper>
      <img :src="originalImage" id="originalImage" style="display: none;" />
    </div>
    <div
      class="row justify-content-center my-4"
      v-else-if="collectionType != 22"
    >
      <div class="col-md-12">
        <div class="form-group">
          <label for="" class="col-form-label">Caption goes here</label>
        <input
          type="text"
          autocomplete="off"
          class="form-control"
          v-model="text"
        />
        </div>
      </div>
    </div>
    <div class="row justify-content-center my-4" v-else>
      <div class="col-md-12">
         <div class="form-group">
          <label for="" class="col-form-label">Caption goes here</label>
        <textarea class="form-control" v-model="text"></textarea>
      </div>
      </div>
    </div>
    <template v-slot:modal-footer>
      <button
        type="submit"
        :disabled="!isComplete"
        class="btn btn-brand"
        @click="saveCollection"
      >
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
      croppedImage: "",
      croppedImageView: "",
      originalImage: "",
      uploadedFile: "",
      imgid: 0,
      imgtime: "",
      text: "",
      acrd_id: 0,
      rand_id: 0,
      ulTags: [
        {
          name: "",
          acrd_id: 0,
        },
      ],
      listingIndex: 0,
    };
  },
  computed: {
    isComplete() {
      if (this.collectionType == 23) {
        return this.ulTags.length != 0 && this.ulTags.length == this.liCount();
      } else if (this.collectionType == 24) {
        return this.imgid != 0 && this.text != "";
      } else {
        return this.text != "";
      }
    },
  },
  watch: {
    clickEvent: function() {
      this.text = "";
      this.croppedImage = "";
      this.croppedImageView = "";
      this.originalImage = "";
      this.uploadedFile = "";
      this.imgid = 0;
      this.imgtime = "";
      this.ulTags = [
        {
          name: "",
          acrd_id: 0,
        },
      ];
      if (this.collectionId) {
        this.getRecords();
      }
    },
  },
  methods: {
    setImage: function(cropUrl) {
      this.croppedImage = cropUrl;
      this.croppedImageView = URL.createObjectURL(cropUrl);
      this.uploadImages();
    },
    deleteImage: function(index) {
      this.$emit("deleteImageById", this.imgid);
      this.imgid = 0;
      this.imgtime = "";
      this.originalImage = "";
      this.rand_id = 0;
    },
    openCropper: function(index) {
      let orgImage = "";
      if (this.originalImage) {
        orgImage = this.originalImage;
        this.uploadedFile = this.croppedImage = this.croppedImageView = this.$getFileUrlById(
          this.imgid,
          "thumb",
          this.imgtime
        );
      }

      this.originalImage = orgImage;
      $("#tagsCollection").hide();
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
      if (this.uploadedFile == "") {
        return;
      }
      let formData = new FormData();
      formData.append("actualImage", this.uploadedFile);
      formData.append("cropImage", this.croppedImage);
      let acrd_id = this.acrd_id;
      let subid = 0;

      if (this.rand_id == 0 && acrd_id == 0) {
        acrd_id = this.$rndInt(8);
      } else if (this.rand_id != 0 && acrd_id == 0) {
        acrd_id = this.rand_id;
      }
      formData.append("id", acrd_id);
      formData.append("subid", subid);
      formData.append("type", "appImageCollection");
        $("#tagsCollection").show();
      this.$http
        .post(adminBaseUrl + "/app/upload-images", formData)
        .then((response) => {
          this.imgid = response.data.data.afile_id;
          this.imgtime = response.data.data.afile_updated_at;
          this.rand_id = acrd_id;
        });
    },
    liCount: function() {
      let count = 0;
      Object.keys(this.ulTags).forEach((key) => {
        if (this.ulTags[key].name != "") {
          count = count + 1;
        }
      });
      return count;
    },
    closePopup: function() {
      this.$emit("pageLoadData", true);
      this.$bvModal.hide("tagsCollection");
    },
    addTags: function(name = "", id = 0) {
      this.ulTags.push({
        name: name,
        acrd_id: id,
      });
    },
    deleteTags: function(index) {
      if (this.ulTags[index].acrd_id != 0) {
        this.$emit("deleteCollRecordById", this.ulTags[index].acrd_id);
      }
      this.$delete(this.ulTags, index);
    },
    saveCollection: function() {
      let formData = new FormData();
      formData.append("collection", this.text);
      formData.append("page-id", this.pageId);
      formData.append("actr-id", this.collectionId);
      formData.append("ac-id", this.acId);
      formData.append("img_id", this.imgid);
      formData.append("acrd_id", this.acrd_id);
      formData.append("ui_tags", JSON.stringify(this.ulTags));
      formData.append("collectionType", this.collectionType);
      formData.append("type", "tags");
      this.$http
        .post(adminBaseUrl + "/app/store/records", formData)
        .then((response) => {
          this.closePopup();
        });
    },
    getRecords: function() {
      let formData = this.$serializeData({
        type: "tags",
        "collection-id": this.collectionId,
      });
      this.$http
        .post(adminBaseUrl + "/app/collection", formData)
        .then((response) => {
          let editData = response.data.data.editData;
          if (
            editData.length > 0 &&
            editData.length == 1 &&
            this.collectionType != 23
          ) {
            this.text = editData[0].acrd_description;
            this.acrd_id = editData[0].acrd_id;
            if (editData[0].image) {
              this.imgid = editData[0].image.afile_id;
              this.imgtime = editData[0].image.afile_updated_at;
              this.originalImage = this.$getFileUrlById(
                editData[0].image.afile_id,
                "original",
                editData[0].image.afile_updated_at
              );
            }
          } else if (editData.length > 0 && this.collectionType == 23) {
            this.deleteTags(0);
            Object.keys(editData).forEach((key) => {
              this.addTags(
                editData[key].acrd_description,
                editData[key].acrd_id
              );
            });
          }
        });
    },
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
          $("#tagsCollection").show();
        }
      });
    });
  },
};
</script>
