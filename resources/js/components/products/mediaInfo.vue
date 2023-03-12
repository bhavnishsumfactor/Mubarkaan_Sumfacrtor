<style scoped>
#originalImage {
  display: none;
}
</style>
<template>
  <form
    class="form"
    id="form"
    novalidate="novalidate"
    v-on:submit.prevent="saveMediaInfo"
    v-bind:class="[(!$canWrite('PRODUCTS')) ? 'disabledbutton': '']"
  >
    <div
      class="wizard-v2__content"
      data-f-a-tbitwizard-type="step-content"
      data-f-a-tbitwizard-state="current"
    >
      <div class="form__section form__section--first">
        <div class="row mb-5">
          <div class="col-md-12" v-if="optionExists">
            <label class="checkbox">
              <input type="checkbox" v-model="mediaFormFields.uploadImage" />
              {{$t('LBL_UPLOAD_SEPARATE_IMAGE_FOR_EACH_OPTION')}}
              <span></span>
            </label>
          </div>
        </div>

        <cropper
          ref="cropperRef"
          :aspectRatio="aspectRatio"
          :width="width"
          :height="height"
          :originalImage="originalImage"
          v-on:childToParent="setImage"
          v-on:actualImageToParent="setActualImage"
          :icon="false"
          :backgroundOption="backgroundOption"
          :backgroundYes="backgroundYes"
        ></cropper>
        <img :src="originalImage" id="originalImage" />
        <div class="form-group row" v-if="mediaFormFields.uploadImage == 0">
          <label class="col-md-3 col-form-label">{{$t('LBL_IMAGES')}}</label>
          <div class="col-md-9">
            <div class="file-input">
              <button
                type="button"
                class="btn btn-secondary btn-wide js-imageUpload"
                @click="$canWrite('PRODUCTS') ? openCropper() : ''"
                :disabled="!$canWrite('PRODUCTS')"
              >{{$t("BTN_UPLOAD_FILE")}}</button>
            </div>
            <p class="img-disclaimer py-2">
              <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
              {{ $t('LBL_IMAGE_RESTRICTIONS') + ' ' + pixels + ' ' + $t('LBL_IN') + ' ' + ratio + ' ' + $t('LBL_ASPECT_RATIO') }}
            </p>
            <ul class="list-media mt-4">
              
              <li
                class="media"
                v-for="(images, index) in productImages"
                :key="index"
                :id="'product-image-'+images.afile_id"
                v-if="images.afile_record_subid === 0"
              >
              <div  v-if= "images.afile_id !== -1">
                <img
                  class
                  :src="'yokart/image/'+images.afile_id+'/105-140?t=' + $timestamp(images.afile_updated_at)"
                />

                <div class="media-actions actions">
                  <button
                    type="button"
                    class="btn btn-icon btn-sm btn-pro-delete"
                    @click="deleteImage(images.afile_id)"
                  >
                    <svg>
                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" />
                    </svg>
                  </button>

                  <button
                    type="button"
                    class="btn btn-icon btn-sm btn-pro-edit"
                    data-ratio="4:1"
                    @click="editImage(images.afile_id, images.afile_record_id, images.afile_record_subid, images.afile_updated_at, images.afile_enable_background)"
                  >
                    <svg>
                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" />
                    </svg>
                  </button>
                </div>
                </div>
                 <div v-if= "images.afile_id == -1" class="uploading">
                  <div  class="spinner-border">

                  </div>

              </div>
              </li>
            
            </ul>
          </div>
        </div>
        <div
          class="form-group row"
          v-else-if="mediaFormFields.uploadImage == 1"
          :key="optionIndex"
          v-for="(optionUpload, optionIndex) in optionUploads"
        >
          <label
            class="col-md-3 col-form-label"
            v-html="mediaFormFields.optionVarientTitle[optionIndex]"
          ></label>

          <div class="col-md-9">
            <div class="file-input">
              <button
                type="button"
                class="btn btn-secondary btn-wide js-imageUpload"
                @click="$canWrite('PRODUCTS') ? openCropper(mediaFormFields.optionVarientCode[optionIndex]) : ''"
                :disabled="!$canWrite('PRODUCTS')"
              >{{ $t('BTN_UPLOAD_FILE') }}</button>
            </div>
            <p class="img-disclaimer py-2">
              <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
              {{ $t('LBL_IMAGE_RESTRICTIONS') + ' ' + pixels + ' ' + $t('LBL_IN') + ' ' + ratio + ' ' + $t('LBL_ASPECT_RATIO') }}
            </p>
            <ul class="list-media mt-4">
              <li
                class="media"
                v-for="(images, index) in productImages"
                :key="index"
                :id="'product-image-'+images.afile_id"
                v-if="images.afile_record_subid ==  mediaFormFields.optionVarientCode[optionIndex]"
              > 
              <div v-if= "images.afile_id !== -1">
                <img
                  class
                  :src="'yokart/image/'+images.afile_id+'/105-140?t=' + $timestamp(images.afile_updated_at)"
                />
                <div class="media-actions actions">
                  <button
                    type="button"
                    class="btn btn-icon btn-sm btn-pro-delete"
                    @click="deleteImage(images.afile_id)"
                  >
                    <svg>
                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" />
                    </svg>
                  </button>
                  <button
                    type="button"
                    class="btn btn-icon btn-sm btn-pro-edit"
                    data-ratio="4:1"
                    @click="editImage(images.afile_id, images.afile_record_id, images.afile_record_subid, images.afile_updated_at)"
                  >
                    <svg>
                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" />
                    </svg>
                  </button>
                </div>
                </div>
                <div v-if= "images.afile_id == -1" class="uploading">
                  <div  class="spinner-border">

                  </div>

              </div>
              </li>
              
            </ul>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-form-label">{{$t('LBL_VIDEO')}}</label>
          <div class="col-md-9">
            <textarea class="form-control" rows="3" v-model="mediaFormFields.pc_video_link"></textarea>
            <span class="form-text text-muted">{{$t('LBL_ONLY_YOUTUBE_URL_OR_EMBED_CODE')}}.</span>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 col-form-label">{{$t('LBL_PRODUCT_PUBLISHED')}}</label>
          <div class="col-md-9">
            <select class="form-control" v-model="mediaFormFields.prod_published">
              <option value disabled>{{$t('LBL_SELECT')}}</option>
              <option
                v-for="(productPublish, index) in productPublished"
                :key="index"
                :value="index"
              >{{productPublish}}</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 col-form-label">{{$t('LBL_PRODUCT_PUBLISHED_FROM')}}</label>
          <div class="col-md-9">
            <date-pick
              v-model="mediaFormFields.prod_publish_from"
              :parseDate="parseDate"
              :formatDate="formatDate"
              :format="$dateTimeFormat(false)"
              :isDateDisabled="isPastDate"
              :inputAttributes="{class: 'form-control', readonly: true}"
              class="d-block"
            ></date-pick>
          </div>
        </div>
      </div>
    </div>
    <div class="form__actions">
      <button
        class="btn btn-secondary btn-wide"
        @click="previous()"
        type="button"
      >{{$t('BTN_PREVIOUS')}}</button>
      <button
        class="btn btn-brand btn-wide gb-btn gb-btn-primary ml-auto"
        v-if="prod_type == 2"
        @click="exitClicked=1;saveMediaInfo();"
        :disabled="exitClicked==1"
        v-bind:class="exitClicked==1 && 'gb-is-loading'"
      >{{$t('BTN_SAVE_AND_EXIT')}}</button> &nbsp;
      <button
        class="btn btn-brand btn-wide gb-btn gb-btn-primary"
        v-if="prod_type == 2"
        data-f-a-tbitwizard-type="action-next"
        @click="clicked=1;saveMediaInfo();"
        :disabled="clicked==1"
        v-bind:class="clicked==1 && 'gb-is-loading'"
      >{{$t('BTN_NEXT')}}</button> &nbsp;
      <button
        class="btn btn-brand btn-wide gb-btn gb-btn-primary"
        v-if="prod_type == 1"
        data-f-a-tbitwizard-type="action-next"
        @click="exitClicked=1;saveMediaInfo();"
        :disabled="exitClicked==1 || !$canWrite('PRODUCTS')"
        v-bind:class="exitClicked==1 && 'gb-is-loading'"
      >{{$t('BTN_FINISH')}}</button>
    </div>
  </form>
</template>
<script>
import NProgress from "nprogress";
import "nprogress/nprogress.css";
NProgress.configure({ showSpinner: true });
import DatePick from "vue-date-pick";
import "vue-date-pick/dist/vueDatePick.css";
import fecha from "fecha";
const mediaFields = {
  uploadImage: 0,
  optionVarientTitle: [],
  optionVarientCode: [],
  prod_published: "",
  prod_publish_from: "",
  pc_video_link: ""
};
export default {
  components: {
    DatePick
  },
  props: ["prod_id"],
  data: function() {
    return {
      tab: "media",
      baseUrl: baseUrl,
      productImages: [],
      optionUploads: [],
      productPublished: [],
      mediaFormFields: mediaFields,
      croppedImage: "",
      addBackground: "",
      backgroundOption: "",
      backgroundYes: "0",
      originalImage: "",
      aspectRatio: 1,
      subRecordId: "",
      prod_type: "",
      clicked: 0,
      exitClicked: 0,
      optionExists: false,
      croppedImageView: "",
      uploadedFile: "",
      replaceAttachedFile: 0,
      height: "",
      width: "",
      pixels: "",
      ratio: ""
    };
  },
  methods: {
    parseDate(dateString, format) {
      if (dateString != "Invalid date") {
        return fecha.parse(dateString, format);
      }
    },
    formatDate(dateObj, format) {
      return fecha.format(dateObj, format);
    },
    isPastDate: function(date) {
      const currentDate = new Date().setDate(new Date().getDate() - 1);
      return date < currentDate;
    },
    setImage: function(cropUrl, actualImage, background) {
      this.croppedImage = cropUrl;
      this.addBackground = background;
      if (actualImage == "") {
        //in case cropping already uploaded image
        this.uploadImages();
      }
    },
    setActualImage: function(actualImage) {
      if (typeof actualImage != "string") {
        this.originalImage = URL.createObjectURL(actualImage);
        this.actualImage = actualImage;
      } else {
        this.originalImage = actualImage;
        this.actualImage = actualImage;
      }
      this.uploadImages();
    },
    saveMediaInfo: function() {
      if (this.prod_type != 2) {
        this.$emit("progressBar", 100, 5, 5);
      }

      /*if (this.mediaFormFields.prod_publish_from != "") {
        this.mediaFormFields.prod_publish_from = moment(
          this.mediaFormFields.prod_publish_from
        ).format(this.$dateTimeFormat(false));
      }*/
      let formData = this.$serializeData(this.mediaFormFields);
      formData.append("prod_id", this.prod_id);
      this.$http
        .post(adminBaseUrl + "/products/save/" + this.tab, formData)
        .then(
          response => {
            //success
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
            this.clicked = 0;

            if (this.exitClicked == 1 || this.prod_type != 2) {
              this.$router.push({
                name: "products"
              });
            } else {
              this.exitClicked = 0;
              this.$emit(
                "next",
                response.data.data.prod_id,
                "digital",
                this.prod_type
              );
            }
          },
          response => {
            //error
            this.exitClicked = this.clicked = 0;
            this.validateErrors(response);
          }
        );
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
    pageLoadData: function() {
      let prod_id = this.prod_id;
      this.$http
        .post(adminBaseUrl + "/products/page-load-data", {
          tab: this.tab,
          prod_id: prod_id
        })
        .then(response => {
          //success
          if (response.data.status == true) {
            let productResponse = response.data.data;
            if (productResponse.productMedia.length != 0) {
              this.mediaFormFields.uploadImage = 1;
            }
            this.productPublished = productResponse.published;
            this.productImages = productResponse.editInfo.productImages;
            this.aspectRatio = productResponse.aspectRatio;
            this.pixels = productResponse.pixels;
            this.ratio = productResponse.ratio;
            this.height = productResponse.height;
            this.width = productResponse.width;
            this.backgroundOption = productResponse.backgroundOption;
            this.optionUploads = productResponse.productMedia;
            this.optionExists = productResponse.enableOptions;
            this.mediaFormFields.prod_published =
              productResponse.editInfo.product.prod_published;
             
            if (productResponse.editInfo.product.prod_publish_from) {
              this.mediaFormFields.prod_publish_from = moment(
                productResponse.editInfo.product.prod_publish_from
              ).format(this.$dateTimeFormat(false));
            }
            this.mediaFormFields.pc_video_link =
              productResponse.editInfo.product.pc_video_link == "null"
                ? ""
                : productResponse.editInfo.product.pc_video_link;
            this.prod_type = productResponse.editInfo.product.prod_type;
            Object.keys(productResponse.productMedia).forEach(key => {
              this.mediaFormFields.optionVarientTitle[key] =
                productResponse.productMedia[key].poption_name;
              this.mediaFormFields.optionVarientCode[key] =
                productResponse.productMedia[key].poption_id;
            });
            this.progressBarUpdate(this.prod_type);

            this.verifyImages(productResponse.editInfo.productImages);
          }
        });
    },
    verifyImages: function(images) {
      var varientImages =
        images.find(item => item.afile_record_subid != 0) !== undefined
          ? true
          : false;
      if (varientImages == false) {
        this.mediaFormFields.uploadImage = 0;
        return;
      }
    },
    progressBarUpdate: function(type) {
      let totalStep = 5;
      let bar = (100 / totalStep) * 4;

      if (type == 2) {
        totalStep = 6;
        bar = (100 / totalStep) * 4;
      }
      this.$emit("progressBar", bar, 5, totalStep);
    },
    deleteImage: function(id) {
      $("#product-image-" + id).remove();
      let formData = this.$serializeData({
        id: id
      });
      this.$http
        .post(adminBaseUrl + "/products/delete-file", formData)
        .then(response => {
          this.pageLoadData();
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    uploadImages: function() {
      if(this.replaceAttachedFile){
          this.productImages = this.productImages.map(val=>{
            if(this.replaceAttachedFile === val.afile_id){
              val.afile_id = -1; 
            }
            return val;
          })
      }else{ 
          this.productImages.push({afile_id: -1,afile_record_subid : 0})
      }
      
      let formData = new FormData();
      formData.append("actualimage", this.actualImage);
      formData.append("cropimage", this.croppedImage);
      formData.append("addbackground", this.addBackground);
      formData.append("sub-record-id", this.subRecordId);
      formData.append("attached-id", this.replaceAttachedFile);
      //ds
      this.$http
        .post(
          adminBaseUrl + "/products/upload-product-images/" + this.prod_id,
          formData
        )
        .then(
          response => {
            this.replaceAttachedFile = 0;
            this.productImages = [];
            this.productImages = response.data.data;
            toastr.success(response.data.message, "", toastr.options);
            this.$refs.cropperRef.destroyCropper();
            this.originalImage = "";
            NProgress.done();
          },
          response => {
            //error
            NProgress.done();
            this.validateErrors(response);
          }
        );
    },
    openCropper: function(subRecordId = 0) {
      this.subRecordId = subRecordId;
      this.$refs.cropperRef.openCropper();
    },
    previous: function() {
      this.$emit("previous", "attributes", this.prod_type);
    },
    editImage: function(
      afile_id,
      productId,
      subRecordId = 0,
      updatedTime = "",
      enableBackground = "0"
    ) {
      this.replaceAttachedFile = afile_id;
      this.backgroundYes = enableBackground;
      this.croppedImageView = this.croppedImage = this.originalImage = this.uploadedFile =
        "";
      if (afile_id != null) {
        this.croppedImage = this.croppedImageView =
          baseUrl +
          "/yokart/image/" +
          afile_id +
          "/thumb?t=" +
          this.$timestamp(updatedTime);
        this.originalImage =
          baseUrl +
          "/yokart/image/" +
          afile_id +
          "/original?t=" +
          this.$timestamp(updatedTime);
      }
      this.subRecordId = subRecordId;
      setTimeout(() => {
        this.$refs.cropperRef.openCropper();
      }, 100);
      
    }
  },
  mounted: function() {
    this.pageLoadData();
  }
};
</script>