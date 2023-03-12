<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_SYSTEM_SETTINGS') }}</h3>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="grid-layout">
        <!--Begin:: App Aside Mobile Toggle-->
        <button class="grid-layout-close" id="user_profile_aside_close">
          <i class="la la-close"></i>
        </button>
        <!--End:: App Aside Mobile Toggle-->

        <sidebar :tab="type"></sidebar>

        <!--Begin:: App Content-->
        <div class="grid-layout-content">
            <div class="portlet portlet--height-fluid">
                <div class="portlet__body">
                    <div class="row YK-logoSystemSettings">
                        <div class="col-md-4">
                        <div class="section pr-xl-5">
                            <div class="section__body">
                                <h3 class="section__title section__title-sm">{{ $t('LBL_ADMIN_LOGO') }}</h3>
                                <div class="form-group d-flex align-items-center mb-2">
                                    <label class="label pr-3">{{ $t('LBL_ASPECT_RATIO') | camelCase}}</label>
                                    <div class="radio-inline">
                                    <label class="radio">
                                        <input
                                        type="radio"
                                        v-model="ratio"
                                        @change="aspectRatioSelected($event)"
                                        name="aspect_ratio"
                                        value="1.0"
                                        /> 1:1
                                        <span></span>
                                    </label>
                                    <label class="radio">
                                        <input
                                        type="radio"
                                        v-model="ratio"
                                        @change="aspectRatioSelected($event)"
                                        checked="checked"
                                        name="aspect_ratio"
                                        value="1.77777"
                                        /> 16:9
                                        <span></span>
                                    </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class>
                                        <div class="dropzone dropzone-default dz-clickable dz-dropzone--center ratio-16by9" data-ratio="16:9" @click="$refs.cropperRef.openCropper()" @mouseover="logoAnimation.adminLogo.fileUploadClass = true" @mouseleave="logoAnimation.adminLogo.fileUploadClass = false">                            
                                            <div class="upload_cover">
                                                <div class="img--container uploded__img" v-if="croppedImageView != ''">
                                                    <img :src="croppedImageView" />
                                                    <div class="upload__action">
                                                        <button type="button" @click.stop="removeImage($event, logoAnimation.adminLogo.attachedFile, 'adminLogo')" v-if="croppedImageView != ''">
                                                            <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"></use>                               
                                                            </svg>
                                                        </button>
                                                        <button type="button" @click.stop="$refs.cropperRef.openCropper()" v-if="croppedImageView != ''">
                                                           <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div clas="img--container">
                                                    <div class="file-upload" v-bind:class="{ isactive: logoAnimation.adminLogo.fileUploadClass, fileVisiblity: logoAnimation.adminLogo.fileVisiblity}">
                                                            <img :src="baseUrl+'/admin/images/upload/upload_img.png'" />
                                                    </div>
                                                </div>
                                                <div class="dropzone-msg dz-message needsclick" v-if="croppedImageView == ''">
                                                    <h3 class="dropzone-msg-title">{{ $t('LBL_CLICK_HERE_TO_UPLOAD')}}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <cropper ref="cropperRef" :originalImage="originalImage" :originalElementId="'originalImageAdmin'" :modalId="'adminLogoModal'" :cropperImageId="'adminImage'" :title="$t('LBL_ADMIN_LOGO')" :aspectRatio="aspectRatio" :width="aspectRatio=='1.77777' ? 160 : 90" :height="90" v-on:childToParent="setImage" v-on:actualImageToParent="setActualImage" :icon="false" ></cropper>
                                        <img :src="originalImage" id="originalImageAdmin" style="display: none;" />
                                    </div>
                                    <p v-if="ratio=='1.0'" class="img-disclaimer py-2">
                                        <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
                                             {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 90x90 ' + $t('LBL_IN') + ' 1:1 ' + $t('LBL_ASPECT_RATIO') }}
                                    </p>
                                    <p v-if="ratio=='1.77777'" class="img-disclaimer py-2">
                                        <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
                                             {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 160x90 ' + $t('LBL_IN') + ' 16:9 ' + $t('LBL_ASPECT_RATIO') }}
                                    </p>
                                </div>
                                <div v-bind:class="[darkView == 0 ? 'disabledbutton' : '']">
                                    <h3 class="section__title section__title-sm">{{ $t('LBL_ADMIN_DARK_MODE_LOGO') }}</h3>
                                        <div class="form-group">
                                            <div class>
                                                <div class="dropzone dropzone-default dz-clickable dz-dropzone--center ratio-16by9" data-ratio="16:9" @click="$refs.cropperAdminDarkRef.openCropper()" @mouseover="logoAnimation.adminDarkModeLogo.fileUploadClass = true" @mouseleave="logoAnimation.adminDarkModeLogo.fileUploadClass = false">
                                                    <div class="upload_cover">
                                                        <div class="img--container uploded__img" v-if="croppedImageDarkView != ''">
                                                            <img :src="croppedImageDarkView" />
                                                            <div class="upload__action">
                                                                <button type="button" @click.stop="removeImage($event, logoAnimation.adminDarkModeLogo.attachedFile, 'adminDarkLogo')" v-if="croppedImageDarkView != ''">
                                                                    <svg>   
                                                                        <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"></use>                               
                                                                    </svg>
                                                                </button>
                                                                <button type="button" @click.stop="$refs.cropperAdminDarkRef.openCropper()" v-if="croppedImageDarkView != ''">
                                                                    <svg>   
                                                                        <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div clas="img--container">
                                                            <div class="file-upload" v-bind:class="{ isactive: logoAnimation.adminDarkModeLogo.fileUploadClass, fileVisiblity: logoAnimation.adminDarkModeLogo.fileVisiblity}">
                                                                <img :src="baseUrl+'/admin/images/upload/upload_img.png'" />
                                                            </div>
                                                        </div>
                                                        <div class="dropzone-msg dz-message needsclick" v-if="croppedImageDarkView == ''">
                                                            <h3 class="dropzone-msg-title">{{ $t('LBL_CLICK_HERE_TO_UPLOAD')}}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <cropper ref="cropperAdminDarkRef" :originalImage="originalImageAdminDark" :originalElementId="'originalImageAdminDark'" :modalId="'adminDarkLogoModal'" :cropperImageId="'adminDarkModeImage'" :title="$t('LBL_ADMIN_DARK_MODE_LOGO')" :aspectRatio="aspectRatio" :width="aspectRatio=='1.77777' ? 160 : 90" :height="90" v-on:childToParent="setAdminDarkModeImage" v-on:actualImageToParent="setActualAdminDarkModeImage" :icon="false">
                                                </cropper>
                                                <img :src="originalImageAdminDark" id="originalImageAdminDark" style="display: none;"/>
                                            </div>
                                            <p v-if="ratio=='1.0'" class="img-disclaimer py-2">
                                                <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong> 
                                             {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 90x90 ' + $t('LBL_IN') + ' 1:1 ' + $t('LBL_ASPECT_RATIO') }}
                                            </p>
                                            <p v-if="ratio=='1.77777'" class="img-disclaimer py-2">
                                                <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>                                                
                                             {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 16:9 ' + $t('LBL_IN') + ' 16:9 ' + $t('LBL_ASPECT_RATIO') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </div>
                        <div class="col-md-4">
                            <div class="section pl-xl-5">
                                <div class="section__body">
                                    <h3 class="section__title section__title-sm">{{ $t('LBL_FRONTEND_LOGO') }}</h3>
                                    <div class="form-group d-flex mb-2">
                                        <label class="label pr-3">{{ $t('LBL_ASPECT_RATIO') | camelCase}}</label>
                                        <div class="radio-inline">
                                            <label class="radio">
                                                <input type="radio" v-model="ratioFrontend" @change="aspectRatioSelectedFrontend($event)" name="aspect_ratio1" value="1.0"/> 1:1
                                                <span></span>
                                            </label>
                                            <label class="radio">
                                                <input type="radio" v-model="ratioFrontend" @change="aspectRatioSelectedFrontend($event)" checked="checked" name="aspect_ratio1" value="1.77777"/> 16:9
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class>
                                            <div class="dropzone dropzone-default dz-clickable dz-dropzone--center ratio-16by9" data-ratio="16:9" @click="$refs.cropperRef1.openCropper()" @mouseover="logoAnimation.frontendLogo.fileUploadClass = true" @mouseleave="logoAnimation.frontendLogo.fileUploadClass = false">                            
                                                <div class="upload_cover">
                                                    <div class="img--container uploded__img" v-if="croppedImageViewFrontend != ''">
                                                        <img :src="croppedImageViewFrontend" />
                                                        <div class="upload__action">
                                                            <button type="button" @click.stop="removeImage($event, logoAnimation.frontendLogo.attachedFile, 'frontendLogo')" v-if="croppedImageViewFrontend != ''">
                                                                <svg>   
                                                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"></use>                               
                                                                </svg>
                                                            </button>
                                                            <button type="button" @click.stop="$refs.cropperRef1.openCropper()" v-if="croppedImageViewFrontend != ''">
                                                                <svg>   
                                                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div clas="img--container">
                                                        <div class="file-upload" v-bind:class="{ isactive: logoAnimation.frontendLogo.fileUploadClass, fileVisiblity: logoAnimation.frontendLogo.fileVisiblity}">
                                                                <img :src="baseUrl+'/admin/images/upload/upload_img.png'" />
                                                        </div>
                                                    </div>
                                                    <div class="dropzone-msg dz-message needsclick" v-if="croppedImageViewFrontend == ''">
                                                        <h3 class="dropzone-msg-title">{{ $t('LBL_CLICK_HERE_TO_UPLOAD')}}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <cropper ref="cropperRef1" :originalImage="originalImageFrontend" :originalElementId="'originalImageFrontend'" :modalId="'frontendLogoModal'" :cropperImageId="'frontendImage'" :title="$t('LBL_FRONTEND_LOGO')" :aspectRatio="aspectRatioFrontend" :width="aspectRatioFrontend=='1.77777' ? 160 : 90" :height="90" v-on:childToParent="setImageFrontend" v-on:actualImageToParent="setActualImageFrontend" :icon="false"></cropper>
                                            <img :src="originalImageFrontend" id="originalImageFrontend" style="display: none;"/>
                                        </div>
                                        <p v-if="ratioFrontend=='1.0'" class="img-disclaimer py-2">
                                            <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
                                             {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 90x90 ' + $t('LBL_IN') + ' 1:1 ' + $t('LBL_ASPECT_RATIO') }}
                                        </p>
                                        <p v-if="ratioFrontend=='1.77777'" class="img-disclaimer py-2">
                                            <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
                                             {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 160x90 ' + $t('LBL_IN') + ' 16:9 ' + $t('LBL_ASPECT_RATIO') }}
                                        </p>
                                    </div>
                                    
                                    <div v-bind:class="[darkView == 0 ? 'disabledbutton' : '']">
                                        <h3 class="section__title section__title-sm">{{ $t('LBL_FRONTEND_DARK_MODE_LOGO') }}</h3>
                                        <div class="form-group">
                                            <div class>
                                                <div class="dropzone dropzone-default dz-clickable dz-dropzone--center ratio-16by9" data-ratio="16:9" @click="$refs.cropperFrontendDarkRef.openCropper()" @mouseover="logoAnimation.frontendDarkModeLogo.fileUploadClass = true" @mouseleave="logoAnimation.frontendDarkModeLogo.fileUploadClass = false">                            
                                                <div class="upload_cover">
                                                    <div class="img--container uploded__img" v-if="croppedFrontendImageDarkView != ''">
                                                        <img :src="croppedFrontendImageDarkView" />
                                                        <div class="upload__action">
                                                            <button type="button" @click.stop="removeImage($event, logoAnimation.frontendDarkModeLogo.attachedFile, 'frontendDarkLogo')" v-if="croppedFrontendImageDarkView != ''">
                                                                <svg>   
                                                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"></use>                               
                                                                </svg>
                                                            </button>
                                                            <button type="button" @click.stop="$refs.cropperFrontendDarkRef.openCropper()" v-if="croppedFrontendImageDarkView != ''">
                                                                <svg>   
                                                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div clas="img--container">
                                                        <div class="file-upload" v-bind:class="{ isactive: logoAnimation.frontendDarkModeLogo.fileUploadClass, fileVisiblity: logoAnimation.frontendDarkModeLogo.fileVisiblity}">
                                                            <img :src="baseUrl+'/admin/images/upload/upload_img.png'" />
                                                        </div>
                                                    </div>
                                                    <div class="dropzone-msg dz-message needsclick" v-if="croppedFrontendImageDarkView == ''">
                                                        <h3 class="dropzone-msg-title">{{ $t('LBL_CLICK_HERE_TO_UPLOAD')}}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <cropper ref="cropperFrontendDarkRef" :originalImage="originalImageFrontendDark" :originalElementId="'originalImageFrontendDark'" :modalId="'frontendDarkLogoModal'" :cropperImageId="'frontendDarkModeImage'" :title="$t('LBL_FRONTEND_DARK_MODE_LOGO')" :aspectRatio="aspectRatioFrontend" :width="aspectRatioFrontend=='1.77777' ? 160 : 90" :height="90" v-on:childToParent="setFrontendDarkModeImage" v-on:actualImageToParent="setActualFrontendDarkModeImage" :icon="false"></cropper>
                                            <img :src="originalImageFrontendDark" id="originalImageFrontendDark" style="display: none;"/>
                                            </div>
                                            <p v-if="ratioFrontend=='1.0'" class="img-disclaimer py-2">
                                            <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
                                             {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 90x90 ' + $t('LBL_IN') + ' 1:1 ' + $t('LBL_ASPECT_RATIO') }}
                                            </p>
                                            <p v-if="ratioFrontend=='1.77777'" class="img-disclaimer py-2">
                                            <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
                                             {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 160x90 ' + $t('LBL_IN') + ' 16:9 ' + $t('LBL_ASPECT_RATIO') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="section pl-xl-5">
                                <div class="section__body">
                                    <h3 class="section__title section__title-sm">{{ $t('LBL_FAVICON') }}</h3>
                                    <div class="pt-6"></div>
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <div class="dropzone dropzone-default dz-clickable dz-dropzone--center ratio-16by9" data-ratio="16:9">
                                                <div class="img--container" v-if="croppedImageViewFavicon != ''">
                                                    <img :src="croppedImageViewFavicon" />
                                                </div>
                                                <div class="dropzone-msg dz-message needsclick">
                                                    <h3 class="dropzone-msg-title" v-if="croppedImageViewFavicon == ''">{{ $t('LBL_CLICK_HERE_TO_UPLOAD')}}</h3>
                                                </div>
                                            </div>
                                            <input accept="image/*" type="file" ref="file" v-on:change="uploadFavicon()" class="custom-file-input" id="customFile"/>
                                        </div>
                                        <p class="img-disclaimer py-2">
                                            <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
                                             {{ $t('LBL_FAVICON_IMAGE_RESTRICTIONS') + ' 32x32 ' + $t('LBL_IN') + ' 1:1 ' + $t('LBL_ASPECT_RATIO') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <DeleteModel
                        :deletePopText="$t('LBL_DELETE_IMAGE_TEXT')"
                        :subText="deleteStatus.subMessage"
                        :recordId="deleteStatus.id"
                        @deleted="deleteRecord()"
                    ></DeleteModel>
                </div>
            </div>
        </div>
        <!--End:: App Content-->
      </div>
    </div>
  </div>
</template>
<script>
import sidebar from "./sidebar";
Vue.http.headers.common["content-type"] = "application/x-www-form-urlencoded";
export default {
  components: {
    sidebar: sidebar
  },
  data: function() {
    return {
        baseUrl: baseUrl,
        type: "logo",
        aspectRatio: 16 / 9,
        croppedImage: "",
        croppedImageView: "",
        originalImage: "",
        uploadedFile: "",
        ratio: "1.77777",
        aspectRatioFrontend: 16 / 9,
        croppedImageFrontend: "",
        croppedImageFavicon: "",
        croppedImageViewFrontend: "",
        croppedImageViewFavicon: "",
        originalImageFrontend: "",
        originalImageFavicon: "",
        uploadedFileFrontend: "",
        uploadedFileFavicon: "",
        ratioFrontend: "1.77777",
        favicon: "",
        darkView: 0,
        croppedImageDarkView: "",
        originalImageAdminDark: "",
        croppedFrontendImageDarkView: "",
        originalImageFrontendDark: "",
        modelId: "deleteModel",
        deleteStatus:{},
        logoAnimation: {
            adminLogo: {
                fileUploadClass: false,
                fileVisiblity: false,
                attachedFile: 0,
            },
            frontendLogo: {
                fileUploadClass: false,
                fileVisiblity: false,
                attachedFile: 0,
            },
            favLogo: {
                fileUploadClass: false,
                fileVisiblity: false,
                attachedFile: 0,
            },
            adminDarkModeLogo: {
                fileUploadClass: false,
                fileVisiblity: false,
                attachedFile: 0,
            },
            frontendDarkModeLogo: {
                fileUploadClass: false,
                fileVisiblity: false,
                attachedFile: 0,
            }
        },
        recordId: 0
    };
  },
  methods: {
    getMedia: function() {
      let formData = this.$serializeData({
        keys: ["ADMIN_LOGO_RATIO", "FRONTEND_LOGO_RATIO", "DARK_MODE_ENABLE"]
      });
      this.$http
        .post(adminBaseUrl + "/get-logo-details", formData)
        .then(response => {
            if (response.data.status == false) {
                toastr.error(response.data.message, "", toastr.options);
                return;
            }
            if (response.data.data.adminLogo != null) {
                this.logoAnimation.adminLogo.fileVisiblity = true;
                this.logoAnimation.adminLogo.fileUploadClass = false;
                this.croppedImage = this.croppedImageView = this.$getFileUrl("adminLogo", 0, 0, "thumb") + "/" + this.$rndStr();
                this.originalImage = this.uploadedFile = this.$getFileUrl("adminLogo", 0) + "/" + this.$rndStr();
                this.logoAnimation.adminLogo.attachedFile = response.data.data.adminLogo ? response.data.data.adminLogo.afile_id : "";
            }
            if (response.data.data.adminDarkModeLogo != null) {
                this.logoAnimation.adminDarkModeLogo.fileVisiblity = true;
                this.logoAnimation.adminDarkModeLogo.fileUploadClass = false;
                this.croppedImageDarkView = this.$getFileUrl("adminDarkModeLogo", 0, 0, "thumb") + "/" + this.$rndStr();
                this.originalImageAdminDark = this.uploadedFile = this.$getFileUrl("adminDarkModeLogo", 0) + "/" + this.$rndStr();
                this.logoAnimation.adminDarkModeLogo.attachedFile = response.data.data.adminDarkModeLogo ? response.data.data.adminDarkModeLogo.afile_id : "";
            }
            if (response.data.data.frontendDarkModeLogo != null) {
                this.logoAnimation.frontendDarkModeLogo.fileVisiblity = true;
                this.logoAnimation.frontendDarkModeLogo.fileUploadClass = false;
                this.croppedFrontendImageDarkView = this.$getFileUrl("frontendDarkModeLogo", 0, 0, "thumb") + "/" + this.$rndStr();
                this.originalImageFrontendDark = this.uploadedFileFrontend = this.$getFileUrl("frontendDarkModeLogo", 0) + "/" + this.$rndStr();
                this.logoAnimation.frontendDarkModeLogo.attachedFile = response.data.data.frontendDarkModeLogo ? response.data.data.frontendDarkModeLogo.afile_id : "";
            }
            if (response.data.data.frontendLogo != null) {
                this.logoAnimation.frontendLogo.fileVisiblity = true;
                this.logoAnimation.frontendLogo.fileUploadClass = false;
                this.croppedImageFrontend = this.croppedImageViewFrontend = this.$getFileUrl("frontendLogo", 0, 0, "thumb") + "/" + this.$rndStr();
                this.originalImageFrontend = this.uploadedFileFrontend = this.$getFileUrl("frontendLogo", 0) + "/" + this.$rndStr();
                this.logoAnimation.frontendLogo.attachedFile = response.data.data.frontendLogo ? response.data.data.frontendLogo.afile_id : "";
            }
            if (response.data.data.favicon != null) {
                this.croppedImageFavicon = this.croppedImageViewFavicon = this.$getFileUrl("favicon", 0, 0, "thumb") + "/" + this.$rndStr();
                this.originalImageFavicon = this.uploadedFileFavicon = this.$getFileUrl("favicon", 0) + "/" + this.$rndStr();
            }
            if (response.data.data.ADMIN_LOGO_RATIO == "1:1") {
                this.ratio = "1.0";
                this.aspectRatio = "1.0";
            } else {
                this.ratio = "1.77777";
                this.aspectRatio = "1.77777";
            }

            if (response.data.data.FRONTEND_LOGO_RATIO == "1:1") {
                this.ratioFrontend = "1.0";
                this.aspectRatioFrontend = "1.0";
            } else {
                this.ratioFrontend = "1.77777";
                this.aspectRatioFrontend = "1.77777";
            }
            this.darkView = response.data.data.DARK_MODE_ENABLE;
        });
    },
    aspectRatioSelected: function(event) {
      this.aspectRatio = event.target.value;
    },
    setImage: function(cropUrl, actualImage) {
      this.croppedImage = cropUrl;
      this.croppedImageView = URL.createObjectURL(cropUrl);
      if (actualImage == "") {
        //in case cropping already uploaded image
        this.uploadLogo("adminLogo");
      }
    },
    setActualImage: function(actualImage) {
        this.logoAnimation.adminLogo.fileVisiblity = true;
        this.logoAnimation.adminLogo.fileUploadClass = false;
        if (typeof actualImage != "string") {
            this.originalImage = URL.createObjectURL(actualImage);
            this.uploadedFile = actualImage;
        } else {
            this.uploadedFile = this.originalImage = actualImage;
        }
        this.uploadLogo("adminLogo");
    },
    setAdminDarkModeImage: function(cropUrl, actualImage) {
      this.originalImageAdminDark = this.croppedImage = cropUrl;
      this.croppedImageDarkView = URL.createObjectURL(cropUrl);
      if (actualImage == "") {
        //in case cropping already uploaded image
        this.uploadLogo("adminDarkModeLogo");
      }
    },
    setActualAdminDarkModeImage: function(actualImage) {
        this.logoAnimation.adminDarkModeLogo.fileVisiblity = true;
        this.logoAnimation.adminDarkModeLogo.fileUploadClass = false;
        if (typeof actualImage != "string") {
            this.originalImageAdminDark = URL.createObjectURL(actualImage);
            this.uploadedFile = actualImage;
        } else {
            this.uploadedFile = this.originalImageAdminDark = actualImage;
        }
        this.uploadLogo("adminDarkModeLogo");
    },
    uploadLogo(type) {
        let imageRatio = "";
        let formData = new FormData();
        if (this.ratio == "1.0") {
            imageRatio = "1:1";
        } else {
            imageRatio = "16:9";
        }
        formData.append("actualImage", this.uploadedFile);
        formData.append("cropImage", this.croppedImage);
        formData.append("imageRatio", imageRatio);
        formData.append("logoType", type);
        this.$http.post(adminBaseUrl + "/update-admin-logo", formData).then(
            response => {
                toastr.success(response.data.message, "", toastr.options);
                if (type == "adminLogo") {
                    this.$root.$emit("updateSiteLogo",this.$getFileUrl(type, 0, 0, "thumb") + "/" + this.$rndStr());
                    this.$root.$emit("updateSiteLogoRatio", imageRatio);
                    if (response.data.data.adminLogo != null) {
                        this.croppedImage = this.croppedImageView = this.$getFileUrl("adminLogo", 0, 0, "thumb") + "/" + this.$rndStr();
                        this.originalImage = this.uploadedFile = this.$getFileUrl("adminLogo", 0) + "/" + this.$rndStr();
                        this.logoAnimation.adminLogo.attachedFile = response.data.data.adminLogo ? response.data.data.adminLogo.afile_id : "";
                    }
                }
                if (type == "adminDarkModeLogo") {
                    this.$root.$emit("updateSiteLogoRatio");
                    if (response.data.data.adminDarkModeLogo != null) {
                        this.croppedImageDarkView = this.$getFileUrl("adminDarkModeLogo", 0, 0, "thumb") + "/" + this.$rndStr();
                        this.$root.$emit("updateAdminDarkLogo", this.croppedImageDarkView);
                        this.originalImageAdminDark = this.uploadedFile = this.$getFileUrl("adminDarkModeLogo", 0) + "/" + this.$rndStr();
                        this.logoAnimation.adminDarkModeLogo.attachedFile = response.data.data.adminDarkModeLogo ? response.data.data.adminDarkModeLogo.afile_id : "";
                    }
                }

            },
            response => {
                //error
                this.validateErrors(response);
            }
        );
    },
    aspectRatioSelectedFrontend: function(event) {
      this.aspectRatioFrontend = event.target.value;
    },
    uploadFavicon() {
      if (this.$refs.file.files[0].size > 1048576) {
        toastr.error(this.$t("NOTI_FAVICON_SIZE_ERROR"), "", {
          timeOut: 5000
        });
        return false;
      }
      let formData = new FormData();
      formData.append("file", this.$refs.file.files[0]);
      this.$http.post(adminBaseUrl + "/update-favicon", formData).then(
        response => {
          this.getMedia();
            setTimeout(function(){ 
                window.location.reload();
            }, 2000);
        },
        response => {
          //error
          this.validateErrors(response);
        }
      );
    },
    setImageFrontend: function(cropUrl, actualImage) {
      this.croppedImageFrontend = cropUrl;
      this.croppedImageViewFrontend = URL.createObjectURL(cropUrl);
      if (actualImage == "") {
        //in case cropping already uploaded image
        this.uploadLogoFrontend('frontendLogo');
      }
    },
    setActualImageFrontend: function(actualImage) {
        this.logoAnimation.frontendLogo.fileVisiblity = true;
        this.logoAnimation.frontendLogo.fileUploadClass = false;
        if (typeof actualImage != "string") {
            this.originalImageFrontend = URL.createObjectURL(actualImage);
            this.uploadedFileFrontend = actualImage;
        } else {
            this.uploadedFileFrontend = this.originalImageFrontend = actualImage;
        }
        this.uploadLogoFrontend('frontendLogo');
    },
    uploadLogoFrontend(type) {
      let imageRatio = "";
      let formData = new FormData();
      if (this.ratioFrontend == "1.0") {
        imageRatio = "1:1";
      } else {
        imageRatio = "16:9";
      }
      formData.append("actualImage", this.uploadedFileFrontend);
      formData.append("cropImage", this.croppedImageFrontend);
      formData.append("imageRatio", imageRatio);
      formData.append("logoType", type);

      this.$http.post(adminBaseUrl + "/update-frontend-logo", formData).then(
        response => {
            toastr.success(response.data.message, "", toastr.options);
            if (type == "frontendLogo") {
                if (response.data.data.frontendLogo != null) {
                    this.croppedImageFrontend = this.croppedImageViewFrontend = this.$getFileUrl("frontendLogo", 0, 0, "thumb") + "/" + this.$rndStr();
                    this.originalImageFrontend = this.uploadedFileFrontend = this.$getFileUrl("frontendLogo", 0) + "/" + this.$rndStr();
                    this.logoAnimation.frontendLogo.attachedFile = response.data.data.frontendLogo ? response.data.data.frontendLogo.afile_id : "";
                }
            }
            if (type == "frontendDarkModeLogo") {
                if (response.data.data.frontendDarkModeLogo != null) {
                    this.croppedFrontendImageDarkView = this.$getFileUrl("frontendDarkModeLogo", 0, 0, "thumb") + "/" + this.$rndStr();
                    this.originalImageFrontendDark = this.uploadedFileFrontend = this.$getFileUrl("frontendDarkModeLogo", 0) + "/" + this.$rndStr();
                    this.logoAnimation.frontendDarkModeLogo.attachedFile = response.data.data.frontendDarkModeLogo ? response.data.data.frontendDarkModeLogo.afile_id : "";
                }
            }
        },
        response => {
          //error
          this.validateErrors(response);
        }
      );
    },    
    setFrontendDarkModeImage: function(cropUrl, actualImage) {
      this.originalImageFrontendDark = this.croppedImageFrontend = cropUrl;
      this.croppedFrontendImageDarkView = URL.createObjectURL(cropUrl);
      if (actualImage == "") {
        //in case cropping already uploaded image
        this.uploadLogoFrontend("frontendDarkModeLogo");
      }
    },
    setActualFrontendDarkModeImage: function(actualImage) {
        this.logoAnimation.frontendDarkModeLogo.fileUploadClass = false;
        this.logoAnimation.frontendDarkModeLogo.fileVisiblity = true;
        if (typeof actualImage != "string") {
            this.originalImageFrontendDark = URL.createObjectURL(actualImage);
            this.uploadedFileFrontend = actualImage;
        } else {
            this.uploadedFileFrontend = this.originalImageAdminDark = actualImage;
        }
        this.uploadLogoFrontend("frontendDarkModeLogo");
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
    removeImage: function(event, afileId, type) {
        event.stopPropagation();
        this.deleteStatus.message = this.$t('LBL_DELETE_IMAGE_TEXT');
        this.deleteStatus.subMessage = "";
        if (afileId != "") {
            this.deleteStatus.type = type;
            this.recordId = afileId;
        }
        this.$bvModal.show(this.modelId);
    },
    deleteRecord: function() {
        if (this.recordId != 0) {
            this.$http.delete(adminBaseUrl + "/remove-attached-files/" + this.recordId)
                .then(response => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, "", toastr.options);
                        return;
                    }
                    this.emptyImageData(this.deleteStatus.type);
                    this.logoAnimation.adminLogo.attachedFile = 0;
                    this.recordId = 0;
                    this.deleteStatus.type = "";
                    toastr.success(response.data.message, "", toastr.options);
            });
        } 
        this.$bvModal.hide(this.modelId);
    },
    emptyImageData: function(type) {
        switch(type) {        
            case "adminLogo":
                this.croppedImage = "";
                this.croppedImageView = "";
                this.originalImage = "";
                this.uploadedFile = "";
                this.logoAnimation.adminLogo.fileUploadClass = false;
                this.logoAnimation.adminLogo.fileVisiblity = false;
            break;
            case "adminDarkLogo":
                this.croppedImageDarkView = "";
                this.originalImageAdminDark = "";
                this.uploadedFile = "";
                this.logoAnimation.adminDarkModeLogo.fileUploadClass = false;
                this.logoAnimation.adminDarkModeLogo.fileVisiblity = false;
            break;
            case "frontendLogo":
                this.croppedImageViewFrontend = "";
                this.originalImageFrontend = "";
                this.uploadedFile = "";
                this.logoAnimation.frontendLogo.fileUploadClass = false;
                this.logoAnimation.frontendLogo.fileVisiblity = false;
            break;
            case "frontendDarkLogo":
                this.croppedFrontendImageDarkView = "";
                this.originalImageFrontendDark = "";
                this.uploadedFile = "";
                this.logoAnimation.frontendDarkModeLogo.fileUploadClass = false;
                this.logoAnimation.frontendDarkModeLogo.fileVisiblity = false;
            break;
        }
        
    }
  },
  mounted: function() {
    this.getMedia();
  }
};
</script>