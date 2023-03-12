<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_BRANDS') }}</h3>
          <div class="subheader__breadcrumbs">
            <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
              <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_PRODUCTS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_BRANDS')}}</span>
          </div>
        </div>
        <div class="subheader__toolbar">
          <a
            @click="openAddPage"
            class="btn btn-white"
            href="javascript:void(0);"
            v-bind:class="[(!$canWrite('BRANDS')) ? 'disabledbutton no-drop': '']"
          >
            <i class="fas fa-plus"></i>
            {{ $t('BTN_ADD') }}
          </a>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class v-bind:class="[(showEmpty == 1) ? 'col-md-12': 'col-md-8']">
          <div class="portlet portlet--height-fluid">
            <div class="portlet__body pb-0 bg-gray flex-grow-0" v-if="showEmpty == 0">
              <div class="form-group">
                <div class="input-icon input-icon--left">
                  <input
                    type="text"
                    class="form-control"
                    :placeholder="$t('PLH_SEARCH')"
                    id="generalSearch"
                    :readonly="records.length == 0 && searchData.search === ''"
                    v-model="searchData.search"
                    @keyup="searchRecords"
                  />
                  <span class="input-icon__icon input-icon__icon--left">
                    <span>
                      <i class="la la-search"></i>
                    </span>
                  </span>
                  <span
                    class="input-icon__icon input-icon__icon--right"
                    v-if="searchData.search!=''"
                    @click="searchData.search='';selectedPage=false;searchRecords();"
                  >
                    <span>
                      <i class="fas fa-times"></i>
                    </span>
                  </span>
                </div>
              </div>
            </div>
              
            <hr class="m-0" />
            <div class="portlet__body portlet__body--fit" v-if="showEmpty == 0 && pageLoaded==1">
              <div class="section mb-0">
                <div class="section__content">
                  <table class="table table-data table-justified">
                    <thead>
                      <tr>
                        <th>{{ '#' }}</th>
                        <th></th>
                        <th>{{ $t('LBL_BRAND_NAME') }}</th>
                        <th>{{ $t('LBL_PUBLISH') }}</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody
                      v-if="loading==false && records.length == 0 && searchData.search != '' && showEmpty == 0 && pageLoaded==1"
                    >
                      <tr>
                        <td colspan="5">
                          <noRecordsFound></noRecordsFound>
                        </td>
                      </tr>
                    </tbody>
                  
                    <tbody v-else>
                      <tr v-for="(record, index) in records" :key="index">
                        <td scope="row">{{ pagination.from+index }}</td>
                        <td>
                          <span>
                            <img
                              class="brand-img"
                              v-if="record.attached_file_count > 0"
                              :src="imageUrl(record)"
                            />
                            <img class="brand-img" v-else :src="$noImage('4_1/160x40.png')" />
                          </span>
                        </td>
                        <td>
                          <a
                            href="javascript:;"
                            @click.prevent="editRecord(record)"
                            v-if="!$canWrite('BRANDS')"
                          >{{ record.brand_name }}</a>
                          <div v-else>{{ record.brand_name }}</div>
                        </td>
                        <td>
                          <div>
                            <label class="switch switch--sm">
                              <input
                                type="checkbox"
                                name="brand_publish"
                                v-model="record.brand_publish"
                                @change="onSwitchStatus($event, record.brand_id)"
                                :disabled="!$canWrite('BRANDS')"
                              />
                              <span
                                v-b-tooltip.hover
                                :title="record.brand_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') "
                              ></span>
                            </label>
                          </div>
                        </td>
                        <td style="white-space:nowrap;">
                          <div class="actions">
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              v-b-tooltip.hover
                              :title="$t('TTL_VIEW_BRAND_PAGE')"
                              @click.stop="previewBrand(record.url_rewrite)"
                              href="javascript:;"
                              v-bind:class="[!$canRead('BRANDS') ? 'disabled no-drop': '']"
                            >
                               <svg>   
                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#links'" :href="baseUrl+'/admin/images/retina/sprite.svg#links'"></use>                               
                                </svg>
                            </a>
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              v-bind:class="[!$canRead('BRANDS') ? 'disabled no-drop': '']"
                              v-b-tooltip.hover
                              :title="$t('TTL_VIEW_PRODUCTS_FOR_BRAND')"
                              @click.stop="seeBrandProducts(record.brand_id)"
                              href="javascript:;"
                            >
                              <svg>   
                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#view'" :href="baseUrl+'/admin/images/retina/sprite.svg#view'"></use>                               
                                </svg>
                            </a>
                            <a
                              class="btn btn-sm btn-light btn-icon"
                              href="javascript:;"
                              v-bind:class="[!$canWrite('BRANDS') ? 'disabled no-drop': '']"
                              v-b-tooltip.hover
                              :title="$t('TTL_EDIT')"
                              @click.prevent="editRecord(record)"
                            >
                              <svg>   
                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" :href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'"></use>                               
                                </svg>
                            </a>
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              href="javascript:;"
                              v-bind:class="[!$canWrite('BRANDS') ? 'disabled no-drop': '']"
                              @click.capture="confirmDelete($event, record.brand_id)"
                              v-b-tooltip.hover
                              :title="$t('TTL_DELETE')"
                            >
                              <svg>   
                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" :href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"></use>                               
                                </svg>
                            </a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="portlet__body" v-if="showEmpty == 1 && pageLoaded==1">
              <div class="get-started">
                <div class="get-started-arrow d-flex justify-content-end mb-5">
                  <img
                    :src="baseUrl+'/admin/images/get-started-graphic/get-started-arrow-head.svg'"
                  />
                </div>
                <div class="no-content d-flex text-center flex-column mb-7">
                  <p>
                    {{ $t('LBL_CLICK_THE') }}
                    <a
                      href="javascript:;"
                      @click="openAddPage"
                      class="btn btn-brand btn-small"
                      v-bind:class="[(!$canWrite('BRANDS')) ? 'disabledbutton no-drop': '']"
                    >
                      <i class="fas fa-plus"></i>
                      {{ $t('BTN_ADD') }}
                    </a>
                    {{ $t('LBL_BUTTON_TO_CREATE_BRANDS') }}
                  </p>
                </div>
                <div class="get-started-media">
                  <img :src="baseUrl+'/admin/images/get-started-graphic/get-started-brands.svg'" />
                </div>
              </div>
            </div>
            <div v-else>
              <loader v-if="loading"></loader>
            </div>
            <div class="portlet__foot" v-if="records.length > 0">
              <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
            </div>
          </div>
        </div>
        <div class="col-md-4" v-if="showEmpty == 0">
          <div class="portlet portlet--height-fluid">
            <div class="portlet__head" v-if="selectedPage">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title" v-if="selectedPage == 'editform'">
                  {{ $canWrite('BRANDS') ? $t('LBL_EDITING') + ' -' : ''}}
                  <span
                    class="editing-txt"
                  >{{editText}}</span>
                </h3>
                <h3
                  class="portlet__head-title"
                  v-if="selectedPage == 'addform'"
                >{{ $t('LBL_NEW_BRAND_SETUP')}}</h3>
              </div>
              <div class="portlet__head-toolbar" v-if="selectedPage == 'editform'">
                <div class="portlet__head-actions" id="tooltip-target-1">
                  <i class="fas fa-info-circle"></i>
                </div>
                <b-popover
                  target="tooltip-target-1"
                  triggers="hover"
                  placement="top"
                  class="popover-cover"
                >
                  <div class="list-stats">
                    <div class="lable">
                      <span class="stats_title">{{ $t('LBL_CREATED') }}:</span>
                      <span
                        class="time"
                      >{{ createdByUser ? createdByUser+ ' |' : '' }} {{ createdAt | formatDateTime}}</span>
                    </div>
                    <div class="lable">
                      <span class="stats_title">{{ $t('LBL_LAST_UPDATED') }}:</span>
                      <span
                        class="time"
                      >{{ updatedByUser ? updatedByUser+ ' |' : ''}} {{ updatedAt | formatDateTime}}</span>
                    </div>
                  </div>
                </b-popover>
              </div>
            </div>
            <div
              class="portlet__body"
              v-if="selectedPage"
              v-bind:class="[(!$canWrite('BRANDS')) ? 'disabledbutton': '']"
            >
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>
                      {{ $t('LBL_BRAND_NAME') }}
                      <span class="required">*</span>
                    </label>
                    <input
                      v-if="adminsData.brand_id != ''"
                      type="hidden"
                      name="id"
                      v-model="adminsData.brand_id"
                    />
                    <input
                      type="text"
                      v-model="adminsData.brand_name"
                      name="brand_name"
                      v-validate="'required'"
                      :data-vv-as="$t('LBL_BRAND_NAME')"
                      data-vv-validate-on="none"
                      class="form-control"
                    />
                    <span
                      v-if="errors.first('brand_name')"
                      class="error text-danger"
                    >{{ $t(errors.first('brand_name')) }}</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>{{ $t('LBL_BRAND_IMAGE')}}</label>
                    <div
                      data-ratio="4:1"
                      class="dropzone dropzone-default dropzone-brand dz-clickable ratio-4by1"
                      @click="(croppedImageView == '') ? [$refs.cropperRef.openCropper(), fileUploadClass = true]  : ''"
                      @mouseover="fileUploadClass = true"
                      @mouseleave="fileUploadClass = false"
                    >
                      <div class="upload_cover">
                        <div class="img--container uploded__img" v-if="croppedImageView != ''">
                          <img :src="croppedImageView" />
                          <div class="upload__action">
                            <button
                              type="button"
                              @click="removeImage($event, attachedFile)"
                              v-if="croppedImageView != ''"
                            >
                             <svg>   
                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"></use>                               
                            </svg>
                              
                            </button>
                            <button
                              type="button"
                              @click="$refs.cropperRef.openCropper()"
                              v-if="croppedImageView != ''"
                            >
                             <svg>   
                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'"></use>                               
                            </svg>
                            </button>
                          </div>
                        </div>
                        <div clas="img--container  ">
                          <div
                            class="file-upload"
                            v-bind:class="{ isactive: fileUploadClass, fileVisiblity: fileVisiblity}"
                          >
                            <img :src="baseUrl+'/admin/images/upload/upload_img.png'" />
                          </div>
                        </div>
                        <div
                          class="dropzone-msg dz-message needsclick"
                          v-if="croppedImageView == ''"
                        >
                          <h3 class="dropzone-msg-title">{{ $t('LBL_CLICK_HERE_TO_UPLOAD')}}</h3>
                        </div>
                      </div>
                    </div>
                    <cropper
                      ref="cropperRef"
                      :title="adminsData.brand_name"
                      :icon="false"
                      :aspectRatio="4"
                      :width="160"
                      :height="40"
                      v-on:childToParent="setImage"
                      v-on:actualImageToParent="setActualImage"
                    ></cropper>
                    <img :src="originalImage" id="originalImage" style="display: none;" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <p
                    class="img-disclaimer py-2"
                  >{{ $t('LBL_IMAGE_RESTRICTIONS') + ' 160 x 40 ' + $t('LBL_PIXELS') + $t('LBL_IN') + ' 4:1 ' + $t('LBL_ASPECT_RATIO') }}</p>
                </div>
              </div>
            </div>
            <div class="portlet__body" v-if="selectedPage == ''">
              <div class="no-data-found">
                <div class="img">
                  <img :src="this.$noDataImage()" alt />
                </div>
                <div class="data">
                  <p>{{ $t('LBL_USE_ICONS_FOR_ADVANCED_ACTIONS') }}</p>
                </div>
              </div>
            </div>
            <div class="portlet__foot" v-if="selectedPage != ''">
              <div class="row">
                <div class="col">
                  <button
                    type="reset"
                    class="btn btn-secondary ml-2"
                    @click="cancel()"
                  >{{ $t('BTN_DISCARD')}}</button>
                </div>
                <div class="col-auto">
                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    :disabled="!isComplete || clicked==1 || (!$canWrite('BRANDS'))"
                    v-if="selectedPage == 'addform'"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{ $t('BTN_CREATE') }}</button>
                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    :disabled="!isComplete || clicked==1 || (!$canWrite('BRANDS'))"
                    v-if="selectedPage == 'editform'"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{ $t('BTN_ADMIN_UPDATE') }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <DeleteModel
          :deletePopText="deleteStatus.message"
          :subText="deleteStatus.subMessage"
          :recordId="deleteStatus.id"
          @deleted="deleteRecord(recordId)"
        ></DeleteModel>
      </div>
    </div>
  </div>
</template>
<script>
const tableFields = {
  brand_id: "",
  brand_name: ""
};
const searchFields = {
  search: ""
};
export default {
  data: function() {
    return {
      adminsData: tableFields,
      baseUrl: baseUrl,
      selectedPage: false,
      records: [],
      recordId: "",
      croppedImage: "",
      croppedImageView: "",
      originalImage: "",
      uploadedFile: "",
      modelId: "deleteModel",
      search: "",
      addFrom: false,
      searchData: searchFields,
      pagination: [],
      clicked: 0,
      loading: false,
      editText: "",
      createdByUser: "",
      updatedByUser: "",
      updatedAt: "",
      createdAt: "",
      deleteStatus: {},
      pageLoaded: 0,
      showEmpty: 1,
      fileUploadClass: false,
      fileVisiblity: false,
      attachedFile: 0
    };
  },
  watch: {
    $route: "refreshedSearchData"
  },
  computed: {
    isComplete() {
      return this.adminsData.brand_name;
    }
  },
  methods: {
    refreshedSearchData() {
      if (this.$route.query.s) {
        this.searchData.search = this.$route.query.s;
      }
        this.pageRecords(1, true);
    },
    setImage: function(cropUrl) {
      this.croppedImage = cropUrl;
      this.croppedImageView = URL.createObjectURL(cropUrl);
    },
    currentPage: function(page) {
      this.pageRecords(page);
    },
    setActualImage: function(actualImage) {
      this.fileVisiblity = true;
      this.fileUploadClass = false;
      if (typeof actualImage != "string") {
        this.originalImage = URL.createObjectURL(actualImage);
        this.uploadedFile = actualImage;
      } else {
        this.uploadedFile = this.originalImage = actualImage;
      }
    },
    searchRecords: function() {
      if (this.selectedPage !== false) {
        this.selectedPage = false;
      }
      this.pageRecords(1);
    },
    pageRecords: function(pageNo, pageLoad = false) {
      this.loading = pageLoad;
      let formData = this.$serializeData(this.searchData);
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(adminBaseUrl + "/brands/list?page=" + pageNo, formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            this.$router.push({ name: "unAuthorised" });
            return;
          }
          this.records = response.data.data.brands.data;
          this.showEmpty = response.data.data.showEmpty;
          this.loading = false;
          this.pagination = response.data.data.brands;
          this.pageLoaded = 1;
        });
    },
    onSwitchStatus: function(event, id) {
      let formData = this.$serializeData({
        status: event.target.checked
      });
      this.$http
        .post(adminBaseUrl + "/brands/status/" + id, formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    seeBrandProducts: function(brandId) {
      this.$router.push({
        name: "products",
        query: {
          brand_id: brandId
        }
      });
    },
    openAddPage: function() {
      this.emptyFormValues();
      this.selectedPage = "addform";
      if (this.searchData.search != "") {
        this.searchData.search = "";
        this.pageRecords();
      }
      this.showEmpty = 0;
      this.emptyImageData();
    },
    editRecord: function(record) {
      this.emptyImageData();
      this.emptyUpdatedFieldValue();
      this.adminsData.brand_id = record.brand_id;
      this.adminsData.brand_name = record.brand_name;
      if (record.created_at != null && "admin_username" in record.created_at) {
        this.createdByUser = record.created_at.admin_username;
      }
      if (record.updated_at != null && "admin_username" in record.updated_at) {
        this.updatedByUser = record.updated_at.admin_username;
      }
      this.updatedAt = record.brand_updated_at ? record.brand_updated_at : "";
      this.createdAt = record.brand_created_at ? record.brand_created_at : "";
      this.croppedImageView = this.croppedImage = this.originalImage = this.uploadedFile =
        "";
      if (record.attached_file_count > 0) {
        this.fileVisiblity = true;
        this.fileUploadClass = false;
        this.croppedImage = this.croppedImageView = this.$getFileUrl(
          "brandLogo",
          record.brand_id,
          0,
          "thumb",
          this.$timestamp(record.brand_updated_at)
        );
        this.originalImage = this.$getFileUrl(
          "brandLogo",
          record.brand_id,
          0,
          "original",
          this.$timestamp(record.brand_updated_at)
        );
        this.attachedFile = record.attached_file.afile_id
          ? record.attached_file.afile_id
          : "";
      } else {
        this.attachedFile = 0;
      }
      this.editText = record.brand_name;
      this.selectedPage = "editform";
    },
    emptyFormValues: function() {
      this.adminsData = {
        brand_id: "",
        brand_name: ""
      };
      this.editText = this.uploadedFile = this.croppedImage = this.croppedImageView = this.originalImage =
        "";
      this.errors.clear();
    },
    validateRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          let input = this.adminsData;
          this.clicked = 1;
          if (input.brand_id != "") {
            this.updateRecord(this.adminsData);
          } else {
            this.saveRecord(input);
          }
        } else {
          this.clicked = 0;
        }
      });
    },
    updateRecord: function(input) {
      let formData = this.$serializeData(input);
      formData.append("actualImage", this.uploadedFile);
      formData.append("cropImage", this.croppedImage);
      formData.append("_method", "PUT");
      this.$http
        .post(adminBaseUrl + "/brands/" + input.brand_id, formData)
        .then(
          response => {
            //success
            this.clicked = 0;
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
            this.pageRecords(this.pagination.current_page);
            this.cancel();
            this.attachedFile = 0;
          },
          response => {
            //error
            this.clicked = 0;
            this.validateErrors(response);
          }
        );
    },
    confirmDelete: function(event, dataid) {
      this.recordId = dataid;
      this.deleteStatus.id = dataid;
      this.deleteStatus = {
        message: this.$t("LBL_DELETE_BRAND_TEXT"),
        subMessage: this.$t("LBL_DELETE_BRAND_SUB_TEXT"),
        id: dataid,
        type:2
      };
      this.$bvModal.show(this.modelId);
    },
    deleteRecord: function(recordId) {
        if (this.attachedFile != 0 && this.deleteStatus.type == 1) {
            this.$http.delete(adminBaseUrl + "/remove-attached-files/" + recordId)
            .then(response => {
                if (response.data.status == false) {
                toastr.error(response.data.message, "", toastr.options);
                return;
                }
                this.emptyImageData();
                this.deleteStatus.type = 0;
                this.attachedFile = 0;
                toastr.success(response.data.message, "", toastr.options);
                this.pageRecords(this.pagination.current_page);
            });
        } else if (this.deleteStatus.type == 0) {
            this.emptyImageData();
        } else {
            this.$http.delete(adminBaseUrl + "/brands/" + recordId)
            .then(response => {
                if (response.data.status == false) {
                toastr.error(response.data.message, "", toastr.options);
                return;
                }
                toastr.success(response.data.message, "", toastr.options);
                this.pageRecords(this.pagination.current_page);
                this.emptyImageData();
                this.selectedPage = "";
            });
        }
        this.$bvModal.hide(this.modelId);
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
    saveRecord: function(input) {
      let formData = this.$serializeData(input);
      formData.append("actualImage", this.uploadedFile);
      formData.append("cropImage", this.croppedImage);
      this.$http.post(adminBaseUrl + "/brands", formData).then(
        response => {
          //success
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords(this.pagination.current_page);
          this.selectedPage = false;
          this.clicked = 0;
        },
        response => {
          //error
          this.clicked = 0;
          this.validateErrors(response);
        }
      );
    },
    imageUrl: function(brand) {
      return this.$getFileUrl(
        "brandLogo",
        brand.brand_id,
        0,
        "80-20",
        this.$timestamp(brand.brand_updated_at)
      );
    },
    cancel: function() {
      this.selectedPage = false;
      if (this.records.length == 0) {
        this.showEmpty = 1;
      }
    },
    previewBrand: function(url) {

      if(url && url.urlrewrite_custom) {
        url = baseUrl + '/' + url.urlrewrite_custom;
      }else if (url && url.urlrewrite_original){
        url = baseUrl + '/' + url.urlrewrite_original;
     }
      window.open(url, "_blank");
    },
    emptyUpdatedFieldValue: function() {
        this.createdByUser = "";
        this.updatedByUser = "";
        this.updatedAt = "";
        this.createdAt = "";
        this.fileUploadClass = false;
        this.fileVisiblity = false;
    },
    emptyImageData: function() {      
      this.croppedImage = "";
      this.croppedImageView = "";
      this.originalImage = "";
      this.uploadedFile = "";
      this.fileUploadClass = false;
      this.fileVisiblity = false;
    },
    removeImage: function(event, afileId) {
        event.stopPropagation();
        this.deleteStatus.message = this.$t("LBL_DELETE_IMAGE_TEXT");
        this.deleteStatus.subMessage = "";
        if (afileId != "") {
            this.deleteStatus.type = 1;
            this.recordId = this.attachedFile = afileId;
        } else {
            this.deleteStatus.type = 0;
        }
        this.$bvModal.show(this.modelId);
    },
  },
  mounted: function() {
    this.searchData = {
      search: ""
    };
    this.refreshedSearchData();
    
  },

};
</script>
