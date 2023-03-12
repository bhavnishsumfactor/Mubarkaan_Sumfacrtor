<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">
            {{ $t("LBL_THIRD_PARTY_INTEGRATIONS") }}
          </h3>
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
            <div class="portlet__body p-0">
                  <div class="row justify-content-center">
                    <div class="col-md-8">
                      <div class="card my-8">
                            <div class="card-header">
                                <div class="info__wrapper">
                                    <div class="info__media">
                                        <div class="img__wrapper">
                                            <img :src="baseUrl+'/admin/images/third-party/facebook-pixel.png'" alt="" />
                                        </div>
                                    </div>
                                    <div class="info__content">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h3> {{ $t("LBL_FACEBOOK_PIXEL") }} </h3>
                                                <a href="https://facebook.com/events_manager">https://facebook.com/events_manager</a>
                                            </div>
                                            <div class="col-md-2">
                                            </div>
                                        </div>
                                    </div>
                              </div>
                            </div>
                        <div class="card-body">
                            
                          <form
                            class="form form--label-right"
                            v-on:submit.prevent="updatePixelCode"
                          >
                            <div class="row form-group mb-0">
                                  <label class="col-lg-4 col-form-label">{{
                                    $t("LBL_PIXEL_ID")
                                  }}</label>
                                  <div class="col-lg-8">
                                    <div class="row">
                                      <div class="col-md-8">
                                        <input
                                          type="text"
                                          v-model="adminsData.FACEBOOK_PIXEL_ID"
                                          name="fb_pixel_id"
                                          class="form-control"
                                        />
                                      </div>
                                      <div class="col-md-4">
                                        <button
                                          type="submit"
                                          class="btn btn-brand gb-btn gb-btn-primary w-100"
                                          :disabled="
                                            pixelclicked == 1 ||
                                              adminsData.FACEBOOK_PIXEL_ID == ''
                                          "
                                          v-bind:class="
                                            pixelclicked == 1 && 'gb-is-loading'
                                          "
                                        >
                                          {{ $t("BTN_SETTINGS_UPDATE") }}
                                        </button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                          </form>
                        </div>
                      </div>
                      <div class="card my-8">
                            <div class="card-header">
                                <div class="info__wrapper">
                                    <div class="info__media">
                                        <div class="img__wrapper">
                                            <img :src="baseUrl+'/admin/images/third-party/google-analytics.png'" alt="" alt="" />
                                        </div>
                                    </div>
                                    <div class="info__content">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h3>{{ $t("LBL_GOOGLE_ANALYTICS") }}</h3>
                                                <a href="https://analytics.google.com/analytics/web/">https://analytics.google.com/analytics/web/</a>
                                            </div>
                                            <div class="col-md-2">

                                            </div>
                                        </div>
                                        
                                    </div>
                              </div>
                            </div>
                        <div class="card-body upload_dropzone_width">
                         
                            <form class="form form--label-right" v-on:submit.prevent="updateGoogleAnalytics">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">{{$t("LBL_CLIENT_ID")}}</label>
                                    <div class="col-lg-8">
                                        <input type="text" v-model="adminsData.analytics['GOOGLE_ANALYTICS_CLIENT_ID']" name="GOOGLE_ANALYTICS_CLIENT_ID" @input="googleAnalyticsHandler" class="form-control"/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                  <label class="col-lg-4 col-form-label">{{
                                    $t("LBL_SECRET_KEY")
                                  }}</label>
                                  <div class="col-lg-8">
                                    <input
                                      type="text"
                                      v-model="
                                        adminsData.analytics[
                                          'GOOGLE_ANALYTICS_SECRET_KEY'
                                        ]
                                      "
                                      name="GOOGLE_ANALYTICS_SECRET_KEY"
                                      @input="googleAnalyticsHandler"
                                      class="form-control"
                                    />
                                  </div>
                                </div>

                                <div class="form-group row">
                                  <label class="col-lg-4 col-form-label">{{
                                    $t("LBL_ANALYTICS_ID")
                                  }}</label>
                                  <div class="col-lg-8">
                                    <input
                                      type="text"
                                      v-model="
                                        adminsData.analytics[
                                          'GOOGLE_ANALYTICS_ANALYTICS_ID'
                                        ]
                                      "
                                      name="GOOGLE_ANALYTICS_ANALYTICS_ID"
                                      class="form-control"
                                    />
                                  </div>
                                </div>

                                <div class="form-group row">
                                  <label class="col-lg-4 col-form-label">{{
                                    $t("LBL_ANALYTICS_VIEW_ID")
                                  }}</label>
                                  <div class="col-lg-8">
                                    <input
                                      type="text"
                                      v-model="
                                        adminsData.analytics[
                                          'GOOGLE_ANALYTICS_VIEW_ID'
                                        ]
                                      "
                                      name="GOOGLE_ANALYTICS_ANALYTICS_ID"
                                      class="form-control"
                                    />
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class="col-lg-4 col-form-label">{{
                                    $t("LBL_SITE_TRACKING_CODE")
                                  }}</label>
                                  <div class="col-lg-8">
                                    <textarea
                                      class="form-control"
                                      v-model="
                                        adminsData.analytics[
                                          'GOOGLE_ANALYTICS_SITE_TRACKING_CODE'
                                        ]
                                      "
                                      name="GOOGLE_ANALYTICS_SITE_TRACKING_CODE"
                                      rows="5"
                                    ></textarea>
                                  </div>
                                </div>
                                <!--<div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">{{ $t('LBL_ACCOUNT_STATUS')}}</label>
                                                    <div class="col-lg-8">
                                                        <div class="d-flex align-items-center">
                                                            <div v-if="adminsData.analytics['GOOGLE_ANALYTICS_VERIFIED']" class="pr-3">
                                                                <svg v-if="adminsData.analytics['GOOGLE_ANALYTICS_VERIFIED'] == 0" alt="Unverified" width="24px" height="24px" class="not-varified-account">
                                                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#close-icon'" :href="baseUrl+'/admin/images/retina/sprite.svg#close-icon'"/>
                                                                </svg>
                                                                <svg v-else alt="verified" width="24px" height="24px" class="varified-account">
                                                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#right-tick'" :href="baseUrl+'/admin/images/retina/sprite.svg#right-tick'"/>
                                                                </svg>
                                                            </div>
                                                            <div v-if="adminsData.analytics['GOOGLE_ANALYTICS_VERIFIED'] == 0">
                                                                <a href="javascript:;" id="verifyCredentials" @click="verifyCredentials()" title="verify" class="btn--text not-varified">{{ $t('LNK_VERIFY_CREDENTIALS') }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>-->
                                <div class="form-group row">
                                  <label class="col-lg-4 col-form-label"
                                    >{{
                                      $t(
                                        "LBL_GOOGLE_ANALYTICS_SERVICE_CREDENTIALS"
                                      )
                                    }}
                                    <span>
                                      <i
                                        class="fas fa-info-circle"
                                        data-container="body"
                                        v-b-tooltip.hover
                                        :title="
                                          $t(
                                            'TTL_GOOGLE_ANALYTICS_SERVICE_CREDENTIALS'
                                          )
                                        "
                                      ></i>
                                    </span>
                                  </label>

                                  <div class="col-lg-8">
                                    <vue-dropzone
                                      id="dropzone_2"
                                      ref="googleAnalyticsDropzone"
                                      v-on:vdropzone-success="
                                        analyticsCredentailUploadSuccess
                                      "
                                      :options="dropzoneGoogleAnalytics"
                                      :useCustomSlot="true"
                                      @vdropzone-mounted="loadExistingFiles"
                                      v-on:vdropzone-removed-file="
                                        removeAnalyticsFile
                                      "
                                      class="dropzone dropzone-default dropzone-brand dz-clickable"
                                    >
                                      <div class="dz-message">
                                        <div class="dropzone-msg">
                                          <i
                                            class="fas fa-upload fa-5x mb-4"
                                          ></i>
                                          <h3 class="dropzone-msg-title">
                                            {{ $t("LBL_CLICK_DROP_TO_UPLOAD") }}
                                          </h3>
                                        </div>
                                      </div>
                                    </vue-dropzone>
                                    <span
                                      v-if="errors.first('xmlfile')"
                                      class="error text-danger"
                                      >{{ errors.first("file") }}</span
                                    >
                                    <span class="form-text text-muted">{{
                                      $t("LBL_UPLOAD_SERVICE_CREDENTIALS_FILE")
                                    }}</span>
                                  </div>
                                </div>
                              
                            <div class="form__actions">
                              <div class="row">
                                <div class="col-lg-4 col-xl-4"></div>
                                <div class="col-lg-8 col-xl-8">
                                  <button
                                    type="submit"
                                    class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                                    :disabled="
                                      analyticsclicked == 1 ||
                                        googleAnalyticsCompleted
                                    "
                                    v-bind:class="
                                      analyticsclicked == 1 && 'gb-is-loading'
                                    "
                                  >
                                    {{ $t("BTN_SETTINGS_UPDATE") }}
                                  </button>
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                      <div class="card my-8">
                          <div class="card-header">
                              <div class="info__wrapper">
                                <div class="info__media">
                                  <div class="img__wrapper">
                                        <img :src="baseUrl+'/admin/images/third-party/google-tag-manager.png'" alt="" alt="" />
                                    </div>
                                </div>
                                <div class="info__content">
                                  <h3>{{ $t("LBL_GOOGLE_TAG_MANAGER") }}</h3>
                                  <a href="javascript:void(0);">https://marketingplatform.google.com/about/tag-manager/</a>
                                </div>
                              </div>
                          </div>
                        <div class="card-body upload_dropzone_width">
                          <form
                            class="form form--label-right"
                            v-on:submit.prevent="updateGoogleTagManager"
                          >
                                <div class="form-group row">
                                  <label class="col-lg-4 col-form-label">{{
                                    $t("LBL_HEAD_SCRIPT")
                                  }}</label>
                                  <div class="col-lg-8">
                                    <textarea
                                      class="form-control"
                                      rows="6"
                                      v-model="
                                        adminsData.tagManager[
                                          'GOOGLE_TAG_MANAGER_HEAD_SCRIPT'
                                        ]
                                      "
                                      name="headscript"
                                      v-validate="'required'"
                                      data-vv-validate-on="none"
                                    ></textarea>
                                    <span
                                      v-if="errors.first('headscript')"
                                      class="error text-danger"
                                      >{{ errors.first("headscript") }}</span
                                    >
                                  </div>
                                </div>

                                <div class="form-group row">
                                  <label class="col-lg-4 col-form-label">{{
                                    $t("LBL_BODY_SCRIPT")
                                  }}</label>
                                  <div class="col-lg-8">
                                    <textarea
                                      class="form-control"
                                      rows="6"
                                      v-model="
                                        adminsData.tagManager[
                                          'GOOGLE_TAG_MANAGER_BODY_SCRIPT'
                                        ]
                                      "
                                      name="bodyscript"
                                      v-validate="'required'"
                                      data-vv-validate-on="none"
                                    ></textarea>
                                    <span
                                      v-if="errors.first('bodyscript')"
                                      class="error text-danger"
                                      >{{ errors.first("bodyscript") }}</span
                                    >
                                  </div>
                                </div>
                              
                            <div class="form__actions">
                              <div class="row">
                                <div class="col-lg-4 col-xl-4"></div>
                                <div class="col-lg-8 col-xl-8">
                                  <button
                                    type="submit"
                                    class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                                    :disabled="
                                      tagclicked == 1 ||
                                        adminsData.tagManager[
                                          'GOOGLE_TAG_MANAGER_BODY_SCRIPT'
                                        ] == '' ||
                                        adminsData.tagManager[
                                          'GOOGLE_TAG_MANAGER_HEAD_SCRIPT'
                                        ] == ''
                                    "
                                    v-bind:class="
                                      tagclicked == 1 && 'gb-is-loading'
                                    "
                                  >
                                    {{ $t("BTN_SETTINGS_UPDATE") }}
                                  </button>
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>

                      <div class="card my-8">
                          <div class="card-header">
                              <div class="info__wrapper">
                                <div class="info__media">
                                  <div class="img__wrapper">
                                    <img :src="baseUrl+'/admin/images/third-party/google-webmaster-tools-logo.jpg'" alt="" alt="" />
                                  </div>
                                </div>
                                <div class="info__content">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h3>{{ $t("LBL_GOOGLE_WEBMASTER") }}</h3>
                                            <a href="javascript:void(0);">https://www.google.com/intl/en/webmasters/#?modal_active=none</a>
                                        </div>
                                        <div class="col-md-2">
                                        </div>
                                    </div>
                                </div>
                              </div>
                          </div>
                          <div class="card-body">
                              
                               <form class="form form--label-right">
                                <div class="form-group row">
                                      <label class="col-lg-4 col-form-label">{{
                                        $t("LBL_HTML_FILE_VERIFICATION")
                                      }}</label>
                                      <div class="col-lg-8">
                                        <vue-dropzone
                                          id="dropzone_2"
                                          ref="GoogleDropzone"
                                          v-on:vdropzone-file-added="
                                            fileAddedGoogle
                                          "
                                          v-on:vdropzone-success="
                                            fileUploadSuccessGoogle
                                          "
                                          @vdropzone-mounted="
                                            loadExistingFileGoogleWebmaster
                                          "
                                          v-on:vdropzone-removed-file="
                                            removeGoogleHtml
                                          "
                                          :options="dropzoneOptionsGoogle"
                                          :useCustomSlot="true"
                                          class="dropzone dropzone-default dropzone-brand dz-clickable"
                                        >
                                          <div class="dz-message">
                                            <div class="dropzone-msg">
                                              <i
                                                class="fas fa-upload fa-5x mb-4"
                                              ></i>
                                              <h3 class="dropzone-msg-title">
                                                {{ $t("LBL_CLICK_OR_DRAGDROP_UPLOAD_HTML_FILE") }}
                                              </h3>
                                            </div>
                                          </div>
                                        </vue-dropzone>
                                        <span
                                          v-if="errors.first('htmlfile')"
                                          class="error text-danger"
                                          >{{ errors.first("file") }}</span
                                        >
                                        <small>{{
                                          $t("LBL_UPLOAD_HTML_FILE")
                                        }}</small>
                                      </div>
                                    </div>
                              </form>
                          </div>
                      </div>
                      <div class="card my-8">
                          <div class="card-header">
                                <div class="info__wrapper">
                                <div class="info__media">
                                  <div class="img__wrapper">
                                      <img :src="baseUrl+'/admin/images/third-party/bing-webmaster.png'" alt="" />
                                  </div>
                                </div>
                                <div class="info__content">
                                  <h3>{{ $t("LBL_BING_WEBMASTER") }}</h3>
                                  <a href="https://www.bing.com/toolbox/webmaster" target="_blank" >https://www.bing.com/toolbox/webmaster/</a>
                                </div>
                              </div>
                          </div>
                          <div class="card-body">
                              
                               <form class="form form--label-right">
                                 <div class="form-group row ">
                                      <label class="col-lg-4 col-form-label">{{
                                        $t("LBL_XML_FILE_AUTHENTICATION")
                                      }}</label>
                                      <div class="col-lg-8">
                                        <vue-dropzone id="dropzone_2" ref="BingDropzone"
                                          v-on:vdropzone-file-added="
                                            fileAddedBing
                                          "
                                          v-on:vdropzone-success="
                                            fileUploadSuccessBing
                                          "
                                          @vdropzone-mounted="
                                            loadExistingFileBingWebmaster
                                          "
                                          v-on:vdropzone-removed-file="
                                            removeBingXml
                                          "
                                          :options="dropzoneOptionsBing"
                                          :useCustomSlot="true"
                                          class="dropzone dropzone-default dropzone-brand dz-clickable"
                                        >
                                          <div class="dz-message">
                                            <div class="dropzone-msg">
                                              <i
                                                class="fas fa-upload fa-5x mb-4"
                                              ></i>
                                              <h3 class="dropzone-msg-title">
                                                {{
                                                  $t(
                                                    "LBL_CLICK_OR_DRAGDROP_UPLOAD_XML_FILE"
                                                  )
                                                }}
                                              </h3>
                                            </div>
                                          </div>
                                        </vue-dropzone>
                                        <span
                                          v-if="errors.first('xmlfile')"
                                          class="error text-danger"
                                          >{{ errors.first("file") }}</span
                                        >
                                        <span class="form-text text-muted">{{
                                          $t("LBL_UPLOAD_BING_XML_FILE")
                                        }}</span>
                                      </div>
                                    </div>
                              </form>
                          </div>
                      </div>
                      </div>
                    </div>
                  </div>
                
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
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
const tableFields = {
  analytics: {},
  tagManager: {},
  FACEBOOK_PIXEL_ID: "",
};
export default {
  components: {
    sidebar: sidebar,
    vueDropzone: vue2Dropzone,
  },
  data: function() {
    return {
      adminsData: tableFields,
      type: "analytics",
      baseUrl: baseUrl,
      pixelclicked: 0,
      tagclicked: 0,
      analyticsclicked: 0,
      analyticsCredentialUrl: "",
      analyticsCredentialName: "",
      analyticsCredentialSize: "",
      bingWebmasterFileSize: "",
      googleWebmasterFileName: "",
      googleWebmasterFileSize: "",
      dropzoneOptionsGoogle: {
        //url: 'https://httpbin.org/post',
        url: adminBaseUrl + "/googlewebmaster",
        thumbnailWidth: 150,
        maxFilesize: 2,
        addRemoveLinks: true,
        maxFiles: 1,
        acceptedFiles: "text/html",
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("value"),
        }
      },
      dropzoneGoogleAnalytics: {
        url: adminBaseUrl + "/googleanalytics/upload-service-credentials",
        thumbnailWidth: 150,
        maxFilesize: 2,
        addRemoveLinks: true,
        maxFiles: 1,
        acceptedFiles: ".json",
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("value"),
        },
      },      
      viewHtml: false,
      disableUploadGoogle: true,
      viewHtmlFile: "",
      dropzoneOptionsBing: {
        //url: 'https://httpbin.org/post',
        url: adminBaseUrl + "/bingwebmaster",
        thumbnailWidth: 150,
        maxFilesize: 2,
        addRemoveLinks: true,
        maxFiles: 1,
        acceptedFiles: "text/xml",
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("value"),
        },
      },
      //viewXML: false,
      disableUploadBing: true,
      viewXMLFile: "",
    };
  },
  methods: {
    getGoogleAnalyticsData: function() {
      this.$http.get(adminBaseUrl + "/analytics").then((response) => {
        //
        if (response.data.status == false) {
          toastr.error(response.data.message, "", toastr.options);
          return;
        }
        this.adminsData = response.data.data;
        this.analyticsCredentialUrl =
          response.data.data.analytics.credentials_url;
        this.analyticsCredentialName =
          response.data.data.analytics.credentials_name;
        this.analyticsCredentialSize =
          response.data.data.analytics.credentials_size;
        this.loadExistingFiles();
      });
    },
    googleAnalyticsHandler: function() {
      if (this.adminsData.analytics.GOOGLE_ANALYTICS_VERIFIED == 1) {
        this.adminsData.analytics.GOOGLE_ANALYTICS_VERIFIED = 0;
      }
    },
    verifyCredentials: function() {
      $("#verifyCredentials").addClass(
        "spinner spinner--right spinner--md spinner--light"
      );
      this.$http
        .post(adminBaseUrl + "/analytics/store/analytics", this.adminsData)
        .then(
          (response) => {
            this.$http
              .get(adminBaseUrl + "/googleanalytics/authenticate")
              .then((response) => {
                //
                if (response.data.status == false) {
                  toastr.error(response.data.message, "", toastr.options);
                  return;
                }
                window.location = response.data.data.url;
              });
          },
          (response) => {
            //error
            this.validateErrors(response);
          }
        );
    },
    updatePixelCode: function() {
      this.pixelclicked = 1;
      let facebookPixel = new FormData();
      facebookPixel.append(
        "FACEBOOK_PIXEL_ID",
        this.adminsData.FACEBOOK_PIXEL_ID
      );
      this.$http
        .post(adminBaseUrl + "/analytics/store/pixel", facebookPixel)
        .then(
          (response) => {
            //success
            this.pixelclicked = 0;
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
          },
          (response) => {
            //error
            this.pixelclicked = 0;
            this.validateErrors(response);
          }
        );
    },
    updateGoogleAnalytics: function() {
      this.analyticsclicked = 1;
      this.$http
        .post(
          adminBaseUrl + "/analytics/store/analytics",
          this.$serializeData(this.adminsData.analytics)
        )
        .then(
          (response) => {
            //success
            this.analyticsclicked = 0;
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
          },
          (response) => {
            //error
            this.analyticsclicked = 0;
            this.validateErrors(response);
          }
        );
    },
    updateGoogleTagManager: function() {
      this.tagclicked = 1;
      this.$http
        .post(
          adminBaseUrl + "/analytics/store/tagManager",
          this.$serializeData(this.adminsData.tagManager)
        )
        .then(
          (response) => {
            //success
            this.tagclicked = 0;
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
          },
          (response) => {
            //error
            this.tagclicked = 0;
            this.validateErrors(response);
          }
        );
    },
    checkGoogleHtml: function() {
      this.$http.get(adminBaseUrl + "/googlewebmaster").then((response) => {
        if (response.data.data.url != "") {
          this.viewHtmlFile = response.data.data.url;
          this.viewHtml = true;
          this.googleWebmasterFileName = response.data.data.name;
          this.googleWebmasterFileSize = response.data.data.size;
          this.loadExistingFileGoogleWebmaster();
        }
      });
    },
    removeGoogleHtml: function() {
      if (this.$refs.GoogleDropzone.dropzone.disabled !== true) {
        this.$http
          .delete(adminBaseUrl + "/googlewebmaster")
          .then((response) => {
            this.viewHtmlFile = "";
            this.viewHtml = false;
            toastr.success(response.data.message, "", toastr.options);
            this.$refs.GoogleDropzone.removeAllFiles(true);
          });
      }
    },
    fileAddedGoogle: function(file) {
      let alreadyUploaded = this.$refs.GoogleDropzone.getAcceptedFiles();
      if (alreadyUploaded.length > 0) {
        this.$refs.GoogleDropzone.removeFile(alreadyUploaded[0]);
      }
    },
    fileUploadSuccessGoogle: function(file, response) {
      this.disableUploadGoogle = false;
    },
    uploadGoogleHtml: function(event) {
      $(event.target).addClass(
        "spinner spinner--sm spinner--right spinner--light"
      );
      this.errors.clear();
      let importFile = this.$refs.GoogleDropzone.getAcceptedFiles();
      if (importFile.length == 1) {
        let formData = new FormData();
        formData.append("file", importFile[0]);
        this.$http
          .post(adminBaseUrl + "/googlewebmaster", formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          })
          .then(
            (response) => {
              //success
              if (response.data.status == false) {
                toastr.error(response.data.message, "", toastr.options);
                return;
              }
              this.viewHtmlFile = baseUrl + "/" + response.data.data.url;
              this.viewHtml = true;
              toastr.success(response.data.message, "", toastr.options);
              this.$refs.GoogleDropzone.removeAllFiles();
              $(event.target).removeClass(
                "spinner spinner--sm spinner--right spinner--light"
              );
            },
            (response) => {
              //error
              $(event.target).removeClass(
                "spinner spinner--sm spinner--right spinner--light"
              );
              this.validateErrors(response);
            }
          );
      } else {
        $(event.target).removeClass(
          "spinner spinner--sm spinner--right spinner--light"
        );
        this.errors.add({
          field: "htmlfile",
          msg: "Please upload the file first",
        });
      }
    },
    checkBingXml: function() {
      this.$http.get(adminBaseUrl + "/bingwebmaster").then((response) => {
        if (response.data.data.url != "") {
          this.viewXMLFile = response.data.data.url;
          this.bingWebmasterFileSize = response.data.data.size;
          this.viewXML = true;
          this.loadExistingFileBingWebmaster();
        }
      });
    },
    removeBingXml: function() {
      if (this.$refs.BingDropzone.dropzone.disabled !== true) {
        this.$http.delete(adminBaseUrl + "/bingwebmaster").then((response) => {
          this.viewXMLFile = "";
          this.viewXML = false;
          toastr.success(response.data.message, "", toastr.options);
          this.$refs.BingDropzone.removeAllFiles(true);
        });
      }
    },
    fileAddedBing: function(file) {
      let alreadyUploaded = this.$refs.BingDropzone.getAcceptedFiles();
      $(file.previewElement)
        .find(".dz-image img")
        .attr(
          "src",
          baseUrl + "/public/yokart/fashion/media/retina/xml-file.svg"
        );
      if (alreadyUploaded.length > 0) {
        this.$refs.BingDropzone.removeFile(alreadyUploaded[0]);
      }
    },
    fileUploadSuccessBing: function(file, response) {
      if (response.status == false) {
        toastr.error(response.message, "", toastr.options);
        return;
      }
      toastr.success(response.message, "", toastr.options);
    },
    validateErrors: function(response) {
      let jsondata = response.data;
      Object.keys(jsondata.errors).forEach((key) => {
        this.errors.add({
          field: key,
          msg: jsondata.errors[key][0],
        });
      });
    },
    viewAnalyticsCredentails: function() {
      this.$http
        .get(adminBaseUrl + "/googleanalytics/view-analytics-credentails")
        .then((response) => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
          } else {
            window.open(response.data.data, "_blank");
          }
        });
    },
    analyticsCredentailUploadSuccess: function(file, response) {
      if (response.status == false) {
        toastr.error(response.message, "", toastr.options);
        return;
      }
      $(file.previewElement)
        .find(".dz-image img")
        .attr(
          "src",
          baseUrl + "/public/yokart/fashion/media/retina/json-file.svg"
        );
      toastr.success(response.message, "", toastr.options);
    },
    loadExistingFiles: function() {
      if (
        this.analyticsCredentialName != "" &&
        this.analyticsCredentialUrl != ""
      ) {
        var mockFile = {
          name: this.analyticsCredentialName,
          size: this.analyticsCredentialSize,
        }; //Any name, size.
        this.$refs.googleAnalyticsDropzone.manuallyAddFile(
          mockFile,
          this.analyticsCredentialUrl
        );
        this.$refs.googleAnalyticsDropzone.dropzone.emit(
          "thumbnail",
          mockFile,
          baseUrl + "/public/yokart/fashion/media/retina/json-file.svg"
        );
      }
    },
    removeAnalyticsFile: function() {
      if (this.$refs.googleAnalyticsDropzone.dropzone.disabled !== true) {
        this.$http
          .delete(adminBaseUrl + "/googleanalytics/remove-credentails")
          .then((response) => {
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
            }
            toastr.success(response.data.message, "", toastr.options);
            this.$refs.googleAnalyticsDropzone.removeAllFiles(true);
          });
      }
    },
    loadExistingFileGoogleWebmaster: function() {
      if (this.viewHtmlFile != "") {
        var mockFile = {
          name: this.googleWebmasterFileName,
          size: this.googleWebmasterFileSize,
        }; //Any name, size.
        this.$refs.GoogleDropzone.manuallyAddFile(mockFile, this.viewHtmlFile);
        this.$refs.GoogleDropzone.dropzone.emit(
          "thumbnail",
          mockFile,
          baseUrl + "/public/yokart/fashion/media/retina/html-file.svg"
        );
      }
    },
    loadExistingFileBingWebmaster: function() {
      if (this.viewXMLFile != "") {
        var mockFile = {
          name: "BingSiteAuth.xml",
          size: this.bingWebmasterFileSize,
        }; //Any name, size.
        this.$refs.BingDropzone.manuallyAddFile(mockFile, this.viewXMLFile);
        this.$refs.BingDropzone.dropzone.emit(
          "thumbnail",
          mockFile,
          baseUrl + "/public/yokart/fashion/media/retina/xml-file.svg"
        );
      }
    },
  },
  mounted() {
    this.getGoogleAnalyticsData();
    this.checkGoogleHtml();
    this.checkBingXml();
    if (this.$route.query.message == "success") {
      toastr.success("verified successfully", "", {
        timeOut: 5000,
      });
      this.$router.replace("/seo/analytics");
    }
  },
  computed: {
    googleAnalyticsCompleted() {
      return (
        this.adminsData.analytics["GOOGLE_ANALYTICS_CLIENT_ID"] == "" ||
        this.adminsData.analytics["GOOGLE_ANALYTICS_SECRET_KEY"] == "" ||
        this.adminsData.analytics["GOOGLE_ANALYTICS_ANALYTICS_ID"] == "" ||
        this.adminsData.analytics["GOOGLE_ANALYTICS_VIEW_ID"] == "" ||
        this.adminsData.analytics["GOOGLE_ANALYTICS_SITE_TRACKING_CODE"] == ""
      );
    },
  },
};
</script>
