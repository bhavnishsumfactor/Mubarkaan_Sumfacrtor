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
    <button class="grid-layout-close" id="user_profile_aside_close">
        <i class="la la-close"></i>
    </button>

    <sidebar :tab="type"></sidebar>

    <div class="grid-layout-content">
        <div class="portlet portlet--height-fluid">
            <div class="portlet__body">
                <div class="section">
                    <div class="section__body">
                        <div class="row justify-content-center">
                            <div class="col-xl-8">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">{{ $t('LBL_ASPECT_RATIO') | camelCase}}</label>
                                    <div class="col-lg-9">
                                        <div class="radio-inline">
                                            <label class="radio"><input type="radio" v-model="settingData.EMAIL_IMAGE_RATIO" @change="aspectRatioSelected($event)" name="aspect_ratio" value="1.0"> 1:1<span></span></label>
                                            <label class="radio"><input type="radio" v-model="settingData.EMAIL_IMAGE_RATIO" @change="aspectRatioSelected($event)" name="aspect_ratio" value="1.77777"> 16:9<span></span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">{{ $t('LBL_EMAIL_LOGO') }}</label>
                                    <div class="col-lg-9">
                                        <div class="avatar avatar--outline" id="user_avatar">
                                            <div class="avatar__holder" v-bind:style="{ backgroundImage: 'url(' + croppedImageView + ')'}"></div>
                                            <cropper ref="cropperRef" :title="$t('LBL_EMAIL_LOGO')" :aspectRatio="aspectRatio" :width="aspectRatio=='1.77777' ? 160 : 90" :height="90" v-on:childToParent="setImage" v-on:actualImageToParent="setActualImage"></cropper>
                                            <img :src="originalImage" id="originalImage" style="display: none;" />
                                        </div>
                                        <p class="img-disclaimer py-2" v-if="settingData.EMAIL_IMAGE_RATIO == '1.0'">
                                            <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
                                            {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 90x90 ' + $t('LBL_IN') + ' 1:1 ' + $t('LBL_ASPECT_RATIO') }}
                                        </p>
                                        <p class="img-disclaimer py-2"  v-if="settingData.EMAIL_IMAGE_RATIO == '1.77777'">
                                            <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
                                            {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 160x90 ' + $t('LBL_IN') + ' 16:9 ' + $t('LBL_ASPECT_RATIO') }}
                                        </p>
                                        
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h3 class="section__title section__title-sm">{{ $t('LBL_SOCIAL_LINKS') }}</h3>
                                    </div>
                                </div>
                                <ul class="list-switch list-switch--three web-social-switch">
                                    <li v-for="(val, key) in networks" v-bind:key="key" @click="socialDetails(key)" :class="[dataSocialDetails['EMAIL_SOCIAL_LINKS_'+ key.toUpperCase() ] != '' ? 'active' : '', key]">
                                        <span>
                                            <i class="svg--icon">
                                                <svg class="svg" width="24px" height="24px">
                                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#'+key" :href="baseUrl+'/admin/images/retina/sprite.svg#'+key"></use>
                                                </svg>
                                            </i>
                                            {{$t('LBL_'+val.toUpperCase())}}
                                        </span>
                                    </li>
                                </ul>
                                <div class="form-group row">
                                    <label class="col-lg-12 col-form-label d-flex">{{ $t('LBL_FOOTER_HTML') }} <a href="javascript:;" @click="resetFooterHtml" class="btn btn-icon btn-sm btn-brand ml-auto">{{ $t('BTN_REFRESH')}} </a></label>
                                    <div class="col-lg-12">
                                        <textarea class="form-control" id="editor" v-model="settingData.EMAIL_FOOTER_HTML" name="EMAIL_FOOTER_HTML" v-validate="'required'" :data-vv-as="$t('LBL_FOOTER_HTML')" data-vv-validate-on="none"></textarea>
                                        <span v-if="errors.first('footer_html')" class="error text-danger">@{{ errors.first('footer_html') }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>{{ $t('LBL_REPLACEMENT_VARIABLES') }}</label>
                                        <ul class="click-to-copy" v-if="settingData.EMAIL_FOOTER_HTML_REPLACEMENTS">
                                            <li v-for="(tags, index) in JSON.parse(settingData.EMAIL_FOOTER_HTML_REPLACEMENTS)" :key="index"
                                                @mouseover="copied = false"
                                                @click="copyToClipboard(index);"
                                                @mousedown="$event.target.classList.add('bounceIn')"
                                                @mouseup="$event.target.classList.remove('bounceIn')"
                                                v-bind:title="[copied==true ? $t('LBL_COPIED_TO_CLIPBOARD'): $t('LBL_CLICK_TO_COPY')]"
                                                v-b-tooltip.hover
                                                :data-val="index">
                                                    <div class="text">{{tags}}</div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-12 col-form-label d-flex">{{ $t('LBL_FOOTER2_HTML') }} <a href="javascript:;" @click="resetFooter2Html" class="btn btn-icon btn-sm btn-brand ml-auto">{{ $t('BTN_REFRESH')}} </a></label>
                                    <div class="col-lg-12">
                                        <textarea class="form-control" id="editor2" v-model="settingData.EMAIL_FOOTER2_HTML" name="EMAIL_FOOTER2_HTML" v-validate="'required'" :data-vv-as="$t('LBL_FOOTER2_HTML')" data-vv-validate-on="none"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>{{ $t('LBL_REPLACEMENT_VARIABLES') }}</label>
                                        <ul class="click-to-copy" v-if="settingData.EMAIL_FOOTER2_HTML_REPLACEMENTS">
                                            <li v-for="(tags, index) in JSON.parse(settingData.EMAIL_FOOTER2_HTML_REPLACEMENTS)" :key="index"
                                                @mouseover="copied = false"
                                                @click="copyToClipboard(index);"
                                                @mousedown="$event.target.classList.add('bounceIn')"
                                                @mouseup="$event.target.classList.remove('bounceIn')"
                                                v-bind:title="[copied==true ? $t('LBL_COPIED_TO_CLIPBOARD'): $t('LBL_CLICK_TO_COPY')]"
                                                v-b-tooltip.hover
                                                :data-val="index">
                                                    <div class="text">{{tags}}</div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="portlet__foot">                            
                <div class="form__actions text-center">
                    <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" form="settingsForm" @click="updateSettings" :disabled='clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_SETTINGS_UPDATE')}}</button>
                </div>
            </div>
            
        </div>
    </div>
</div>
</div>
    <b-modal id="socialDetailsModel" :title="$t('LBL_SOCIAL_DETAILS')" centered no-close-on-backdrop>
        <template v-slot:modal-header>
            <h5 class="modal-title" id="socialDetailsModel">
                <span>{{$t('LBL_SOCIAL_DETAILS') }}</span>
            </h5>
            <button type="button" class="close" @click="closePopup()"></button>
        </template>
        <div class="form-group row">
            <label class="col-lg-3 col-form-label">{{ detailType | camelCase }}</label>
            <div class="col-lg-9">
                <input type="text" name="link" v-model="socialTypeValue" class="form-control" 
                 v-validate="{url: {require_protocol: true }}"
                 data-vv-validate-on="none"
                />
                <span v-if="errors.first('link')" class="error text-danger">{{ errors.first('link') }}</span>
                <span class="form-text text-muted"
                >{{$t('LBL_SOCIAL_LINKS_DISCLAIMER')}}: http://example.com/abc</span>
            </div>
        </div>
        <template v-slot:modal-footer>
            <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" form="settingsForm" @click="updateSocialDetails" :disabled='socialclicked==1' v-bind:class="socialclicked==1 && 'gb-is-loading'">{{ $t('BTN_SETTINGS_UPDATE')}}</button>
        </template>
    </b-modal>
</div>
</template>
<script>

import VueColor from 'vue-color';
Vue.http.headers.common['content-type'] = 'application/x-www-form-urlencoded';

import sidebar from './sidebar';
export default {
    components: {
        'sidebar': sidebar
    },
    data: function() {
        return {
            activeThemeUrl: activeThemeUrl,
            baseUrl: baseUrl,
            type: 'emailTemplates',
            detailType: '',
            socialTypeValue: '',
            settingData: {
                EMAIL_BG_COLOR: '',
                EMAIL_FOOTER_HTML: '',
                EMAIL_FOOTER_HTML_REPLACEMENTS: '',
                EMAIL_FOOTER_DEFAULT_HTML: '',
                EMAIL_FOOTER2_HTML: '',
                EMAIL_FOOTER2_HTML_REPLACEMENTS: '',
                EMAIL_FOOTER2_DEFAULT_HTML: '',
                EMAIL_IMAGE_RATIO:''
            }, 
            colorValue: '#FF3A59',
            colors: {
                hex: '#FF3A59',
            },
            aspectRatio: '',
            croppedImage: '',
            croppedImageView: '',
            originalImage: '',
            uploadedFile: '',
            copied: false,
            clicked:0,
            socialclicked:0,
            myPlugins: [
                'advlist autolink lists link image charmap',
                'searchreplace visualblocks code fullscreen',
                'print preview anchor insertdatetime media',
                'paste code help wordcount table'
            ],
            toolBar: 'undo redo | formatselect | bold italic | \
            alignleft aligncenter alignright | \
            preview | fullscreen',
            networks:{
                'facebook' : 'Facebook',
                'twitter'   : 'Twiter',
                'youtube'   : 'Youtube',
                'instagram' : 'Instagram',
                'pinterest' : 'Pinterest'
            },
            dataSocialDetails: {}
        }
    },
    methods: {
        loadGeneralSettings: function() {
            this.$http.get(adminBaseUrl + '/email-templates/settings')
                .then((response) => {
                    this.settingData = response.data.data.settingData;
                    this.dataSocialDetails = response.data.data.socialDetails;
                    this.initTinyMce(this.settingData.EMAIL_FOOTER_HTML);
                    this.initTinyMce2(this.settingData.EMAIL_FOOTER2_HTML);
                    this.colorValue = this.settingData.EMAIL_BG_COLOR;
                    this.setAspectRatio();
                    this.croppedImage = this.croppedImageView = this.$getFileUrl('emailLogo', 0, 0, 'thumb') + '/' + this.$rndStr();
                    this.uploadedFile = this.originalImage = this.$getFileUrl('emailLogo', 0, 0, 'original') + '/' + this.$rndStr();
                });
        },        
        setAspectRatio: function() {
            if(this.settingData.EMAIL_IMAGE_RATIO == "1:1"){
                this.settingData.EMAIL_IMAGE_RATIO = "1.0";
                this.aspectRatio = "1.0";
            }else{
                this.settingData.EMAIL_IMAGE_RATIO = "1.77777";
                this.aspectRatio = "1.77777";
            }
        },
        resetFooterHtml: function() {
            this.settingData.EMAIL_FOOTER_HTML = this.settingData.EMAIL_FOOTER_DEFAULT_HTML;
            this.initTinyMce(this.settingData.EMAIL_FOOTER_DEFAULT_HTML);
        },
        resetFooter2Html: function() {
            this.settingData.EMAIL_FOOTER2_HTML = this.settingData.EMAIL_FOOTER2_DEFAULT_HTML;
            this.initTinyMce2(this.settingData.EMAIL_FOOTER2_DEFAULT_HTML);
        },
        updateColors(colors, bgcolor) {
            this.colors = colors;
            this.settingData.EMAIL_BG_COLOR = bgcolor;
        },
        updateFromPicker(color, colorValue) {
            this.colors = color;
            this.colorValue = colorValue;
        },
        aspectRatioSelected: function(event) {
            this.aspectRatio = event.target.value;
        },
        updateSettings: function() {
            this.$validator.validateAll().then(res => {
                if (res) {
                    this.clicked = 1;
                    if(this.settingData.EMAIL_IMAGE_RATIO == "1.0"){
                        this.settingData.EMAIL_IMAGE_RATIO = "1:1";
                    }else{
                        this.settingData.EMAIL_IMAGE_RATIO = "16:9";
                    }
                    let formData = this.$serializeData(this.settingData);
                    this.setAspectRatio();
                    formData.append('actualImage', this.uploadedFile);
                    formData.append('cropImage', this.croppedImage);
                    this.$http.post(adminBaseUrl + '/email-templates/settings', formData)
                        .then((response) => {
                            this.clicked = 0;
                            if (response.data.status == false) {
                                toastr.error(response.data.message, '', toastr.options);
                                return;
                            }
                            toastr.success(response.data.message, '', toastr.options);
                        }, (response) => {
                            this.clicked = 0;
                            this.validateErrors(response);
                        });
                } else {
                    this.clicked = 0;
                }
            });
        },
        setImage: function(cropUrl) {
            this.croppedImage = cropUrl;
            this.croppedImageView = URL.createObjectURL(cropUrl);
        },
        setActualImage: function(actualImage) {
            if (typeof actualImage != 'string') {
                this.originalImage = URL.createObjectURL(actualImage);
                this.uploadedFile = actualImage;
            } else {
                this.uploadedFile = this.originalImage = actualImage;
            }
        },
        initTinyMce: function(descriptionInitValue) {
            let thisObj = this;
            tinymce.remove("#editor");
            tinymce.init({
                selector:'#editor',
                height: 300,
                menubar: true,
                branding: false,
                plugins: this.myPlugins,
                toolbar: this.toolBar,
                images_upload_url: adminBaseUrl + '/editor/images',
                images_upload_credentials: true,
                content_css : this.activeThemeUrl + '/css/main-ltr.css',
                setup: function(editor) {
                    editor.on('init', function(e) {                        
                        editor.setContent(descriptionInitValue);
                    });
                    editor.on('change', function(e) {
                        thisObj.settingData.EMAIL_FOOTER_HTML = editor.getContent();
                    });
                }
            });
        },
        initTinyMce2: function(descriptionInitValue) {
            let thisObj = this;
            tinymce.remove("#editor2");
            tinymce.init({
                selector:'#editor2',
                height: 300,
                menubar: true,
                branding: false,
                plugins: this.myPlugins,
                toolbar: this.toolBar,
                images_upload_url: adminBaseUrl + '/editor/images',
                images_upload_credentials: true,
                content_css : this.activeThemeUrl + '/css/main-ltr.css',
                setup: function(editor) {
                    editor.on('init', function(e) {                        
                        editor.setContent(descriptionInitValue);
                    });
                    editor.on('change', function(e) {
                        thisObj.settingData.EMAIL_FOOTER2_HTML = editor.getContent();
                    });
                }
            });
        },
        socialDetails : function (key) {
            this.detailType = key;
            this.socialTypeValue = '';
            this.socialTypeValue = this.dataSocialDetails['EMAIL_SOCIAL_LINKS_'+ key.toUpperCase()];
            this.$bvModal.show('socialDetailsModel');
        },
        updateSocialDetails : function() {
            this.$validator.validateAll().then(res => {
                if (res) {
                    this.socialclicked = 1;
                    let postData = {};
                    postData['EMAIL_SOCIAL_LINKS_'+ this.detailType.toUpperCase()] = this.socialTypeValue;
                    let formData = this.$serializeData(postData);            
                    this.$http.post(adminBaseUrl + '/email-templates/settings', formData)
                        .then((response) => {
                            this.socialclicked = 0;
                            if (response.data.status == false) {
                                toastr.error(response.data.message, '', toastr.options);
                                return;
                            }
                            this.dataSocialDetails['EMAIL_SOCIAL_LINKS_'+ this.detailType.toUpperCase()] = this.socialTypeValue;
                            toastr.success(response.data.message, '', toastr.options);
                            this.$bvModal.hide('socialDetailsModel');
                        }, (response) => {
                            this.socialclicked = 0;
                        });
                } else {
                    this.socialclicked = 0;
                }
            });
        },
        closePopup: function() {
            this.$bvModal.hide('socialDetailsModel');
        },
        copyToClipboard : function(copyText) {
            let $temp = $("<input>");
            $("body").append($temp);
            $temp.val(copyText).select();
            document.execCommand("copy");
            $temp.remove();
            this.copied = true;
        }
    },
    mounted: function() {
        this.loadGeneralSettings();
    }
}
</script>
