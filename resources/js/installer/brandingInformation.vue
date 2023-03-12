<template>
    <div class="card">
        <div class="card_body">
            <div class="steps-data">
                <div class="data form active">                   
                    <div class="row justify-content-center">
                        <div class="col-md-9">
                             <div class="row form-group">
                                 <label class="col-md-5 col-form-label">{{ $t('Product aspect ratio')}}</label>
                                 <div class="col-md-7">
                                    <div class="radio-inline">
                                        <label class="radio">
                                            <input type="radio" v-model="installerData.config.MEDIA_TYPES" checked="checked"  name="product_aspect_ratio" value="1"/> 1:1
                                            <span></span>
                                        </label>
                                        <label class="radio">
                                            <input type="radio" v-model="installerData.config.MEDIA_TYPES" name="product_aspect_ratio" value="2"/> 16:9
                                            <span></span>
                                        </label>
                                        <label class="radio">
                                            <input type="radio" v-model="installerData.config.MEDIA_TYPES" name="product_aspect_ratio" value="3"/> 3:4
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                 <label class="col-md-5 col-form-label">{{ $t('Brand Logo aspect ratio')}}</label>
                                 <div class="col-md-7">
                                    <div class="radio-inline">
                                        <label class="radio">
                                            <input type="radio" v-model="installerData.config.ADMIN_LOGO_RATIO" @change="aspectRatioSelected($event)" name="aspect_ratio" value="1.0"/> 1:1
                                            <span></span>
                                        </label>
                                        <label class="radio">
                                            <input type="radio" v-model="installerData.config.ADMIN_LOGO_RATIO" @change="aspectRatioSelected($event)" checked="checked" name="aspect_ratio" value="1.77777"/> 16:9
                                            <span></span>
                                        </label>
                                    </div>
                                  </div>
                            </div>
                                
                            <div>
                                <div class="dropzone dropzone-default dz-clickable dz-dropzone--center ratio-16by9" data-ratio="" @click="$refs.cropperRef.openCropper()" @mouseover="logoAnimation.adminLogo.fileUploadClass = true" @mouseleave="logoAnimation.adminLogo.fileUploadClass = false">                            
                                    <div class="upload_cover">
                                        <div class="img--container uploded__img" v-if="croppedImageView != ''">
                                            <img :src="croppedImageView" />
                                            <div class="upload__action">
                                                <button type="button" @click.stop="removeImage($event)" v-if="croppedImageView != ''">
                                                    <svg>   
                                                        <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"></use>                               
                                                    </svg>
                                                </button>
                                                <button type="button" @click.stop="$refs.cropperRef.openCropper()" v-if="croppedImageView != ''">
                                                    <svg>   
                                                        <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'"></use>                               
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
                                            <h3 class="dropzone-msg-title">{{ $t('Click here to upload brand logo')}}</h3>
                                        </div>
                                    </div>
                                </div>
                                <cropper ref="cropperRef" :originalImage="originalImage" :originalElementId="'originalImageAdmin'" :modalId="'adminLogoModal'" :cropperImageId="'adminImage'" :title="'Admin Logo'" :aspectRatio="aspectRatio" :width="aspectRatio=='1.77777' ? 160 : 90" :height="90" v-on:childToParent="setImage" v-on:actualImageToParent="setActualImage" :icon="false" ></cropper>
                                <img :src="originalImage" id="originalImageAdmin" style="display: none;" />

                            </div>
                                <p v-if="ratio=='1.0'" class="img-disclaimer my-4">
                                    <img class="icn" width="32px" :src="baseUrl+'/installer/images/Crop.svg'" >
                                    <span><strong>{{ $t('Image disclaimer:') }}</strong>{{ $t('File type must be a .jpg, .gif or .png smaller than 5MB and at least 90x90 in 1:1 aspect ratio.') }}</span>
                                </p>
                                <p v-if="ratio=='1.77777'" class="img-disclaimer my-4">
                                    <img class="icn" width="32px" :src="baseUrl+'/installer/images/Crop.svg'" > <span><strong>{{ $t('Image disclaimer:') }}</strong>
                                    {{ $t('File type must be a .jpg, .gif or .png smaller than 5MB and at least 160x90 in 16:9 aspect ratio.') }}</span>
                                </p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group colorpicker">
                                        <label>{{ $t('Admin Theme Color') }}</label>                                        
                                        <colorpicker :colorValue="backendColorValuePrimary" :colors="backendColorsPrimary" @updateColors="updateBackendColorsPrimary" @updateFromPicker="updateBackendFromPickerPrimary" name="backendbgcolorprimary" /> 
                                        <span v-if="errors.first('ADMIN_PRIMARY_COLOR')" class="error text-danger">
                                            {{ errors.first('ADMIN_PRIMARY_COLOR') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group colorpicker">
                                        <label>{{ $t('Admin Theme Color Inverse') }}</label>                                         
                                        <colorpicker :colorValue="backendColorValuePrimaryInverse" :colors="backendColorsPrimaryInverse" @updateColors="updateBackendColorsPrimaryInverse" @updateFromPicker="updateBackendFromPickerPrimaryInverse" name="backendbgcolorprimaryinverse" /> 
                                        <span v-if="errors.first('ADMIN_PRIMARY_COLOR_INVERSE')" class="error text-danger">
                                            {{ errors.first('ADMIN_PRIMARY_COLOR_INVERSE') }}
                                        </span> 
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group colorpicker">
                                        <label>{{ $t('Frontend Theme Color') }}</label>
                                        <colorpicker :colorValue="colorValuePrimary" :colors="colorsPrimary" @updateColors="updateColorsPrimary" @updateFromPicker="updateFromPickerPrimary" name="bgcolorprimary" /> 
                                        <span v-if="errors.first('PRIMARY_COLOR')" class="error text-danger">
                                            {{ errors.first('PRIMARY_COLOR') }}
                                        </span> 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="colorpicker">
                                        <label >{{ $t('Frontend Theme Color Inverse') }}</label>
                                        <colorpicker :colorValue="colorValuePrimaryInverse" :colors="colorsPrimaryInverse" @updateColors="updateColorsPrimaryInverse" @updateFromPicker="updateFromPickerPrimaryInverse" name="bgcolorprimaryinverse" /> 
                                        <span v-if="errors.first('PRIMARY_COLOR_INVERSE')" class="error text-danger">
                                            {{ errors.first('PRIMARY_COLOR_INVERSE') }}
                                        </span> 
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
        <div class="card_foot">
            <button class="btn btn-outline-secondary btn-wide" @click="previous()">{{ $t('Back') }}</button>
            <button class="btn btn-brand btn-wide" @click="next()">{{ $t('Next') }}</button>
        </div>
    </div>
</template>
<style scoped>
#cropper-body .image-wrapper {
    overflow: hidden;
    min-height: 350px;
}
</style>
<script>
export default {
    props: ["installerData"],
    data: function() {
        return { 
            baseUrl: baseUrl,
            aspectRatio: 16 / 9,
            croppedImage: "",
            croppedImageView: "",
            originalImage: "",
            uploadedFile: "",
            ratio: "1.77777",
            logoAnimation: {
                adminLogo: {
                    fileUploadClass: false,
                    fileVisiblity: false,
                    attachedFile: 0,
                }
            },
            deleteStatus:{},
            modelId: "deleteModel",
            colorValuePrimary: "",
            colorValuePrimaryInverse: "",
            backendColorValuePrimary: "",
			backendColorValuePrimaryInverse: "",
            backendColorValuePrimaryTemp: "",
            backendColorValuePrimaryInverseTemp: "",
            colorsPrimary: {
				hex: ""
			},
			colorsPrimaryInverse: {
				hex: ""
            },
            backendColorsPrimary: { 
				hex: ""
			},
			backendColorsPrimaryInverse: {
				hex: ""
			},
        }
    },
    methods: {
        next: function() {
            this.updateConfigFile();
            this.$emit('next', 7, this.installerData);
        },
        updateConfigFile:function() {
            let formData = this.$serializeData(this.installerData);
            this.$http.post(baseUrl + "/installer/update-config", formData);
        },
        previous: function() {
            this.$emit('previous', 7, this.installerData);
        },
        setImage: function(cropUrl, actualImage) {
            this.croppedImage = cropUrl;
            this.croppedImageView = URL.createObjectURL(cropUrl);
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
            this.installerData.actualImage = this.uploadedFile;
            this.installerData.cropImage = this.croppedImage;
            this.installerData.config.ADMIN_LOGO_RATIO = this.ratio;
      
        },
        updateBackendColorsPrimary(colors, bgcolor) {
			this.backendColorsPrimary = colors;
			this.installerData.config.ADMIN_PRIMARY_COLOR = bgcolor;
		},
		updateBackendColorsPrimaryInverse(colors, bgcolor) {
			this.backendColorsPrimaryInverse = colors;
			this.installerData.config.ADMIN_PRIMARY_COLOR_INVERSE = bgcolor;
        },
        updateFromPickerPrimary(color, colorValue) {
			this.colorsPrimary = color;
			this.colorValuePrimary = colorValue;
		},
		updateFromPickerPrimaryInverse(color, colorValue) {
			this.colorsPrimaryInverse = color;
			this.colorValuePrimaryInverse = colorValue;
        },
        updateBackendFromPickerPrimary(color, colorValue) {
			this.backendColorsPrimary = color;
			this.backendColorValuePrimary = colorValue;
		},
		updateBackendFromPickerPrimaryInverse(color, colorValue) {
			this.backendColorsPrimaryInverse = color;
			this.backendColorValuePrimaryInverse = colorValue;
        },
        updateColorsPrimaryInverse(colors, bgcolor) {
			this.colorsPrimaryInverse = colors;
			this.installerData.config.PRIMARY_COLOR = bgcolor;
        },
        updateColorsPrimary(colors, bgcolor) {
			this.colorsPrimary = colors;
			this.installerData.config.PRIMARY_COLOR_INVERSE = bgcolor;
        },
        aspectRatioSelected: function(event) {
            this.aspectRatio = event.target.value;
        },
        removeImage: function(event) {
            event.stopPropagation();
            this.deleteStatus.message = this.$t('LBL_DELETE_IMAGE_TEXT');
            this.deleteStatus.subMessage = "";
            this.$bvModal.show(this.modelId);
        },
        deleteRecord: function() {
            this.croppedImage = "";
            this.croppedImageView = "";
            this.originalImage = "";
            this.uploadedFile = "";
            this.logoAnimation.adminLogo.fileUploadClass = false;
            this.logoAnimation.adminLogo.fileVisiblity = false;
            this.$bvModal.hide(this.modelId);
            toastr.success("successfully deleted", "", toastr.options);
        }

    }
}
</script>