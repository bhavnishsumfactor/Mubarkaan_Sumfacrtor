<template>
  <div>
    <b-modal :id="modalElementId" ref="foo" centered>
      <template v-slot:modal-header>
        <h5 class="modal-title" id="exampleModalLabel">{{cropperTitle}}</h5>
        <button type="button" class="close" @click="$bvModal.hide(modalElementId);">×</button>
      </template>
      <div id="cropper-body">
        <div class="image-wrapper">
          <img :id="cropperImageElementId" :src="imageSrc" />
        </div>
        <canvas id="preview" style="display:none;"></canvas>
      </div>
      <template v-slot:modal-footer>
        <div class="file-input overflow-hidden">
          <label
            class="btn btn-outline-secondary"
            data-original-title="Change avatar"
            @click="openCropper()"
          >
            {{$t('BTN_UPLOAD_PICTURE')}}
            <input
              class="file-input-field cropper-upload-picture"
              type="file"
              accept="image/*"
              @change="onSelectImage($event)"
              ref="fileInput"
            />
          </label>
        </div>
        <button
          type="button"
          class="btn btn-brand"
          id="crop"
          @click="onCrop($event)"
        >{{$t('BTN_CROP_SAVE')}}</button>
      </template>
    </b-modal>
    <input
      type="file"
      :class="'YK-tempUploadButton-' + originalImageElement"
      @change="onSelectImage($event)"
      accept="image/*"
      style="display: none;"
    />
    <label
      data-toggle="tooltip"
      title
      data-original-title="Change avatar"
      class="avatar__upload yk-outerCropEdit"
      @click="openCropper()"
      v-if="icon != false"
    >
      <i class="fa fa-pen"></i>
    </label>
    <label class="avatar__upload" @click="deleteImage()" v-if="deleteicon">
      <i class="fas fa-2x fa-times" style="font-size: 1.2rem;"></i>
    </label>
  </div>
</template>
<script>
import Cropper from "cropperjs";
import "cropperjs/dist/cropper.css";
var data = {
  coords: {
    x: 0,
    y: 0,
    width: 0,
    height: 0,
    rotate: 0,
    scaleX: 0,
    scaleY: 0
  },
  imageSrc: baseUrl + "/no_image.jpg",
  cropperImage: "",
  actualImage: "",
  cropper: null
};
var options = {
  aspectRatio: 1.77777,
  crop: function(e) {
    data.coords.x = e.detail.x;
    data.coords.y = e.detail.y;
    data.coords.width = e.detail.width;
    data.coords.height = e.detail.height;
    data.coords.rotate = e.detail.rotate;
    data.coords.scaleX = e.detail.scaleX;
    data.coords.scaleY = e.detail.scaleY;
  }
};
export default {
  props: [
    "aspectRatio",
    "deleteicon",
    "icon",
    "title",
    "originalElementId",
    "modalId",
    "cropperImageId",
    "originalImage",
    "width",
    "height"
  ],
  data: function() {
    return data;
  },
  computed: {
    cropperTitle: function() {
      if (typeof this.title == "undefined") {
        return "Crop Tool";
      }
      return this.title;
    },
    originalImageElement: function() {
      if (typeof this.originalElementId == "undefined") {
        return "originalImage";
      }
      return this.originalElementId;
    },
    modalElementId: function() {
      if (typeof this.modalId == "undefined") {
        return "modal_6";
      }
      return this.modalId;
    },
    cropperImageElementId: function() {
      if (typeof this.cropperImageId == "undefined") {
        return "image";
      }
      return this.cropperImageId;
    },
    originalImageUrl: function() {
      if (typeof this.cropperImageId == "undefined") {
        return data.imageSrc;
      }
      return this.originalImage;
    }
  },
  methods: {
    emitToParent(event) {
      this.$emit("childToParent", this.cropperImage, data.actualImage);
      if (typeof data.actualImage != "undefined" && data.actualImage != "") {
        this.$emit("actualImageToParent", data.actualImage);
      }
      setTimeout(function() {
        data.imageSrc = data.actualImage = "";
      }, 100);
    },
    deleteImage: function() {
      this.$emit("deleteImage");
    },
    openCropper: function() {
      var thisObj = this;

      let image = document.getElementById(thisObj.originalImageElement);
      if (image.src != window.location.href.replace(window.location.hash, "")) {
        thisObj.destroyCropper();
        this.$bvModal.show(thisObj.modalElementId);
      }
      setTimeout(function() {
        if (
          typeof thisObj.aspectRatio != "undefined" &&
          thisObj.aspectRatio != ""
        ) {
          options.aspectRatio = thisObj.aspectRatio;
        }
        if (
          image.src == window.location.href.replace(window.location.hash, "")
        ) {
          document.querySelector(
            ".YK-tempUploadButton-" + thisObj.originalImageElement
          ).value = "";
          $(".YK-tempUploadButton-" + thisObj.originalImageElement).trigger(
            "click"
          );
          thisObj.destroyCropper();
        } else {
          let imageCropped = document.getElementById(
            thisObj.cropperImageElementId
          );
          data.cropper = new Cropper(imageCropped, options);
          data.imageSrc = image.src;
          data.cropper.replace(image.src);
        }
      }, 20);
    },
    onSelectImage: function($event) {
      if ($event.target.files.length == 0) {
        return false;
      }
      let img = $event.target.files[0];
      if (img.size > 2 * 1024 * 1024) {
        toastr.error(this.$t("LBL_IMAGE_SIZE_VALIDATION"));
        this.$refs.fileInput.value = null;
        return false;
      }
      let tmppath = URL.createObjectURL(img);
      data.imageSrc = tmppath;
      document.getElementById(this.originalImageElement).src = tmppath;
      data.actualImage = img;
      this.openCropper();
    },
    onCrop: function($event) {
      let mimeType = "image/jpeg";
      let cropped = data.cropper.getCroppedCanvas({
        width: this.width,
        height: this.height
      });
      cropped.toBlob(
        croppedBlob => {
          data.cropperImage = croppedBlob;
          this.emitToParent();
        },
        mimeType,
        1
      );
      this.$bvModal.hide(this.modalElementId);
      this.$refs.fileInput.value = null;
    },
    aspectRatioSelected: function() {
      document.getElementById(this.cropperImageElementId).src = data.imageSrc;
      let image = document.getElementById(this.cropperImageElementId);
      options.aspectRatio = this.aspectRatio;
      data.cropper.destroy();
      data.cropper = new Cropper(image, options);
      data.cropper.replace(data.imageSrc);
    },
    destroyCropper: function() {
      if (typeof data.cropper != "undefined" && data.cropper != null) {
        data.cropper.destroy();
      }
      data.imageSrc = "";
    }
  }
};
</script>
