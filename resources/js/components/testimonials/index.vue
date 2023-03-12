<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t("LBL_TESTIMONIALS") }}</h3>
          <div class="subheader__breadcrumbs">
              <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                  <i class="flaticon2-shelter"></i>
              </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{ $t("LBL_CMS") }}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{
              $t("LBL_TESTIMONIALS")
            }}</span>
          </div>
        </div>
        <div class="subheader__toolbar">
          <a
            @click="openAddPage"
            class="btn btn-white"
            href="javascript:void(0);"
            v-bind:class="[
              !$canWrite('TESTIMONIALS') ? 'disabledbutton no-drop' : '',
            ]"
          >
            <i class="fas fa-plus"></i>
            {{ $t("BTN_ADD") }}
          </a>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class v-bind:class="[showEmpty == 1 ? 'col-md-12' : 'col-md-8']">
          <div class="portlet portlet--height-fluid">
            <div
              class="portlet__body pb-0 bg-gray flex-grow-0"
              v-if="showEmpty == 0"
            >
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
                    v-if="searchData.search != ''"
                    @click="
                      searchData.search = '';
                      selectedPage = false;
                      pageRecords(1);
                    "
                  >
                    <span>
                      <i class="fas fa-times"></i>
                    </span>
                  </span>
                </div>
              </div>
            </div>
            <hr class="m-0" />
            <div
              class="portlet__body portlet__body--fit"
              v-if="showEmpty == 0 && pageLoaded == 1"
            >
              <div class="section">
                <div class="section__content">
                  <table class="table table-data table-justified">
                    <thead>
                      <tr>
                        <th width="5%">{{ "#" }}</th>
                        <th width="50%">{{ $t("LBL_TESTIMONIAL_TITLE") }}</th>
                        <th width="20%">
                          {{ $t("LBL_TESTIMONIAL_ENDORSER") }}
                        </th>
                        <th width="10%">{{ $t("LBL_PUBLISH") }}</th>
                        <th width="15%"></th>
                      </tr>
                    </thead>
                    <tbody
                      v-if="
                        loading == false &&
                        records.length == 0 &&
                        searchData.search != '' &&
                        showEmpty == 0 &&
                        pageLoaded == 1
                      "
                    >
                      <tr>
                        <td colspan="5">
                          <noRecordsFound></noRecordsFound>
                        </td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <tr v-for="(record, index) in records" :key="index">
                        <td scope="row">{{ pagination.from + index }}</td>
                        <td>
                          <a
                            href="javascript:;"
                            @click.prevent="editRecord(record)"
                            v-if="!$canWrite('TESTIMONIALS')"
                            >{{ record.testimonial_title }}</a
                          >
                          <div v-else>{{ record.testimonial_title }}</div>
                        </td>
                        <td>{{ record.testimonial_user_name }}</td>
                        <td>
                          <label class="switch switch--sm">
                            <input
                              type="checkbox"
                              name="testimonial_publish"
                              v-model="record.testimonial_publish"
                              @change="
                                onSwitchStatus($event, record.testimonial_id)
                              "
                              :disabled="!$canWrite('TESTIMONIALS')"
                            />
                            <span
                              v-b-tooltip.hover
                              :title="
                                record.testimonial_publish == 1
                                  ? $t('TTL_UNPUBLISH')
                                  : $t('TTL_PUBLISH')
                              "
                            ></span>
                          </label>
                        </td>
                        <td>
                          <div class="actions">
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              href="javascript:;"
                              v-b-tooltip.hover
                              :title="$t('TTL_EDIT')"
                              @click.prevent="editRecord(record)"
                              v-bind:class="[
                                !$canWrite('TESTIMONIALS')
                                  ? 'disabled no-drop'
                                  : '',
                              ]"
                            >
                              <svg>
                                <use
                                  :xlink:href="
                                    baseUrl +
                                    '/admin/images/retina/sprite.svg#edit-icon'
                                  "
                                ></use>
                              </svg>
                            </a>
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              href="javascript:;"
                              @click.capture="
                                confirmDelete($event, record.testimonial_id)
                              "
                              v-b-tooltip.hover
                              :title="$t('TTL_DELETE')"
                              v-bind:class="[
                                !$canWrite('TESTIMONIALS')
                                  ? 'disabled no-drop'
                                  : '',
                              ]"
                            >
                              <svg>
                                <use
                                  :xlink:href="
                                    baseUrl +
                                    '/admin/images/retina/sprite.svg#delete-icon'
                                  "
                                ></use>
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
            <div class="portlet__body" v-if="showEmpty == 1 && pageLoaded == 1">
              <div class="get-started">
                <div class="get-started-arrow d-flex justify-content-end mb-5">
                  <img
                    :src="
                      baseUrl +
                      '/admin/images/get-started-graphic/get-started-arrow-head.svg'
                    "
                  />
                </div>
                <div class="no-content d-flex text-center flex-column mb-7">
                  <p>
                    {{ $t("LBL_CLICK_THE") }}
                    <a
                      href="javascript:;"
                      @click="openAddPage"
                      class="btn btn-brand btn-small"
                    >
                      <i class="fas fa-plus"></i> {{ $t("BTN_ADD") }}
                    </a>
                    {{ $t("LBL_BUTTON_TO_CREATE_TESTIMONIALS") }}
                  </p>
                </div>
                <div class="get-started-media">
                  <img
                    :src="
                      baseUrl +
                      '/admin/images/get-started-graphic/get-started-testimonials.svg'
                    "
                  />
                </div>
              </div>
            </div>
            <loader v-if="loading"></loader>
            <div class="portlet__foot" v-if="records.length > 0">
              <pagination
                :pagination="pagination"
                @currentPage="currentPage"
              ></pagination>
            </div>
          </div>
        </div>

        <div class="col-md-4" v-if="showEmpty == 0">
          <div class="portlet portlet--height-fluid">
            <div class="portlet__head" v-if="selectedPage">
              <div class="portlet__head-label">
                <h3
                  class="portlet__head-title"
                  v-if="selectedPage == 'editform'"
                >
                  {{
                    $canWrite("TESTIMONIALS") ? $t("LBL_EDITING") + " -" : ""
                  }}
                  <span class="editing-txt">{{ editText }}</span>
                </h3>
                <h3
                  class="portlet__head-title"
                  v-if="selectedPage == 'addform'"
                >
                  {{ $t("LBL_NEW_TESTIMONIAL_SETUP") }}
                </h3>
              </div>
              <div
                class="portlet__head-toolbar"
                v-if="selectedPage == 'editform'"
              >
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
                      <span class="stats_title">{{ $t("LBL_CREATED") }}:</span>
                      <span class="time"
                        >{{ createdByUser ? createdByUser + " |" : "" }}
                        {{ createdAt | formatDateTime }}</span
                      >
                    </div>
                    <div class="lable">
                      <span class="stats_title"
                        >{{ $t("LBL_LAST_UPDATED") }}:</span
                      >
                      <span class="time"
                        >{{ updatedByUser ? updatedByUser + " |" : "" }}
                        {{ updatedAt | formatDateTime }}</span
                      >
                    </div>
                  </div>
                </b-popover>
              </div>
            </div>

            <div
              class="portlet__body"
              v-if="selectedPage"
              v-bind:class="[
                !$canWrite('TESTIMONIALS') ? 'disabledbutton' : '',
              ]"
            >
              <input
                v-if="adminsData.testimonial_id != ''"
                type="hidden"
                name="id"
                v-model="adminsData.testimonial_id"
              />
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>
                      {{ $t("LBL_TESTIMONIAL_TITLE") }}
                      <span class="required">*</span>
                    </label>
                    <input
                      type="text"
                      v-model="adminsData.testimonial_title"
                      name="testimonial_title"
                      v-validate="'required'"
                      :data-vv-as="$t('LBL_TESTIMONIAL_TITLE')"
                      data-vv-validate-on="none"
                      class="form-control"
                    />
                    <span
                      v-if="errors.first('testimonial_title')"
                      class="error text-danger"
                      >{{ errors.first("testimonial_title") }}</span
                    >
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>
                      {{ $t("LBL_TESTIMONIAL_CONTENT") }}
                      <span class="required">*</span>
                    </label>
                    <textarea
                      class="form-control"
                      rows="5"
                      cols="15"
                      v-model="adminsData.testimonial_description"
                      name="testimonial_description"
                      v-validate="'required|max:500'"
                      :data-vv-as="$t('LBL_TESTIMONIAL_CONTENT')"
                      data-vv-validate-on="none"
                    ></textarea>
                    <span
                      v-if="errors.first('testimonial_description')"
                      class="error text-danger"
                      >{{ errors.first("testimonial_description") }}</span
                    >
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>
                      {{ $t("LBL_TESTIMONIAL_ENDORSER_NAME") }}
                      <span class="required">*</span>
                    </label>
                    <input
                      type="text"
                      v-model="adminsData.testimonial_user_name"
                      name="testimonial_user_name"
                      v-validate="'required'"
                      :data-vv-as="$t('LBL_TESTIMONIAL_ENDORSER_NAME')"
                      data-vv-validate-on="none"
                      class="form-control"
                    />
                    <span
                      v-if="errors.first('testimonial_user_name')"
                      class="error text-danger"
                      >{{ errors.first("testimonial_user_name") }}</span
                    >
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>{{
                      $t("LBL_TESTIMONIAL_ENDORSER_DESIGNATION")
                    }}</label>
                    <input
                      type="text"
                      v-model="adminsData.testimonial_designation"
                      name="testimonial_designation"
                      class="form-control"
                    />
                    <span
                      v-if="errors.first('testimonial_designation')"
                      class="error text-danger"
                      >{{ errors.first("testimonial_designation") }}</span
                    >
                  </div>
                </div>
              </div>
              <div class="row align-items-end">
                <div class="col-md-8">
                  <label>{{ $t("LBL_TESTIMONIAL_ENDORSER_IMAGE") }}</label>
                  <div
                    data-ratio="1:1"
                    class="dropzone dropzone-default dropzone-brand dz-clickable ratio-1by1"
                    @click="
                      croppedImageView == ''
                        ? [
                            $refs.cropperRef.openCropper(),
                            (fileUploadClass = true),
                          ]
                        : ''
                    "
                    @mouseover="fileUploadClass = true"
                    @mouseleave="fileUploadClass = false"
                  >
                    <div class="upload_cover">
                      <div
                        class="img--container uploded__img"
                        v-if="croppedImageView != ''"
                      >
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
                          v-bind:class="{
                            isactive: fileUploadClass,
                            fileVisiblity: fileVisiblity,
                          }"
                        >
                          <img
                            :src="
                              baseUrl + '/admin/images/upload/upload_img.png'
                            "
                          />
                        </div>
                      </div>
                      <div
                        class="dropzone-msg dz-message needsclick"
                        v-if="croppedImageView == ''"
                      >
                        <h3 class="dropzone-msg-title">
                          {{ $t("LBL_CLICK_HERE_TO_UPLOAD") }}
                        </h3>
                      </div>
                    </div>
                  </div>
                  <cropper
                    ref="cropperRef"
                    :title="adminsData.testimonial_title"
                    :icon="false"
                    :aspectRatio="1"
                    :width="100"
                    :height="100"
                    v-on:childToParent="setImage"
                    v-on:actualImageToParent="setActualImage"
                  ></cropper>
                  <img
                    :src="originalImage"
                    id="originalImage"
                    style="display: none"
                  />
                </div>
                <div class="col-md-12">
                  <p class="img-disclaimer py-2">
                    <strong>{{ $t("LBL_IMAGE_DISCLAIMER") }}: </strong>
                    {{ $t("LBL_IMAGE_RESTRICTIONS") }} 100x100
                    {{ $t("LBL_IN") }} 1:1 {{ $t("LBL_ASPECT_RATIO") }}
                  </p>
                </div>
              </div>
            </div>

            <div class="portlet__body" v-if="selectedPage == ''">
              <div class="no-data-found">
                <div class="img">
                  <img :src="this.$noDataImage()" alt />
                </div>
                <div class="data">
                  <p>{{ $t("LBL_USE_ICONS_FOR_ADVANCED_ACTIONS") }}</p>
                </div>
              </div>
            </div>

            <div class="portlet__foot" v-if="selectedPage != ''">
              <div class="row">
                <div class="col">
                  <button
                    type="reset"
                    class="btn btn-secondary"
                    @click="cancel()"
                  >
                    {{ $t("BTN_DISCARD") }}
                  </button>
                </div>
                <div class="col-auto">
                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    :disabled="
                      !isComplete || clicked == 1 || !$canWrite('TESTIMONIALS')
                    "
                    v-if="selectedPage == 'addform'"
                    v-bind:class="clicked == 1 && 'gb-is-loading'"
                  >
                    {{ $t("BTN_TESTIMONIAL_CREATE") }}
                  </button>
                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    :disabled="
                      !isComplete || clicked == 1 || !$canWrite('TESTIMONIALS')
                    "
                    v-if="selectedPage == 'editform'"
                    v-bind:class="clicked == 1 && 'gb-is-loading'"
                  >
                    {{ $t("BTN_TESTIMONIAL_UPDATE") }}
                  </button>
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
  afile_id: "",
  testimonial_id: "",
  testimonial_title: "",
  testimonial_user_name: "",
  testimonial_designation: "",
  testimonial_description: "",
  testimonial_publish: "",
};
const searchFields = {
  search: "",
};
export default {
  data: function () {
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
      editText: "",
      loading: false,
      createdByUser: "",
      updatedByUser: "",
      updatedAt: "",
      createdAt: "",
      pageLoaded: 0,
      showEmpty: 1,
      fileUploadClass: false,
      fileVisiblity: false,
      deleteStatus: {},
      attachedFile: "",
    };
  },
  computed: {
    isComplete() {
      return (
        this.adminsData.testimonial_title &&
        this.adminsData.testimonial_user_name &&
        this.adminsData.testimonial_description
      );
    },
  },
  watch: {
    $route: "refreshedSearchData",
  },
  methods: {
    refreshedSearchData() {
      if (this.$route.query.s) {
        this.searchData.search = this.$route.query.s;
      }
      this.pageRecords(1, true);
    },

    setImage: function (cropUrl) {
      this.croppedImage = cropUrl;
      this.croppedImageView = URL.createObjectURL(cropUrl);
    },
    currentPage: function (page) {
      this.pageRecords(page);
    },
    setActualImage: function (actualImage) {
      this.fileVisiblity = true;
      this.fileUploadClass = false;
      if (typeof actualImage != "string") {
        this.originalImage = URL.createObjectURL(actualImage);
        this.uploadedFile = actualImage;
      } else {
        this.uploadedFile = this.originalImage = actualImage;
      }
    },
    searchRecords: function () {
      if (this.selectedPage !== false) {
        this.selectedPage = false;
      }
      this.pageRecords(this.pagination.current_page);
    },
    pageRecords: function (pageNo, pageLoad = false) {
      this.loading = pageLoad;
      let formData = this.$serializeData(this.searchData);
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(adminBaseUrl + "/testimonials/list?page=" + pageNo, formData)
        .then((response) => {
          this.records = response.data.data.testimonials.data;
          this.pagination = response.data.data.testimonials;
          this.loading = false;
          this.showEmpty = response.data.data.showEmpty;
          this.pageLoaded = 1;
        });
    },
    openAddPage: function () {
      this.emptyFormValues();
      this.selectedPage = "addform";
      if (this.searchData.search != "") {
        this.searchData.search = "";
        this.pageRecords();
      }
      this.showEmpty = 0;
      this.emptyImageData();
    },
    editRecord: function (record) {
      this.emptyFormValues();
      this.emptyImageData();
      this.emptyUpdatedFieldValue();
      this.adminsData.afile_id = record.afile_id;
      this.adminsData.testimonial_id = record.testimonial_id;
      this.adminsData.testimonial_user_name = record.testimonial_user_name;
      this.adminsData.testimonial_designation = record.testimonial_designation;
      this.adminsData.testimonial_title = record.testimonial_title;
      this.adminsData.testimonial_description = record.testimonial_description;
      this.adminsData.testimonial_publish = record.testimonial_publish;
      if (record.created_at != null && "admin_username" in record.created_at) {
        this.createdByUser = record.created_at.admin_username;
      }
      if (record.updated_at != null && "admin_username" in record.updated_at) {
        this.updatedByUser = record.updated_at.admin_username;
      }
      this.updatedAt = record.testimonial_updated_at
        ? record.testimonial_updated_at
        : "";
      this.createdAt = record.testimonial_created_at
        ? record.testimonial_created_at
        : "";
      this.editText = record.testimonial_title;
      this.croppedImage = this.croppedImageView = this.originalImage = this.uploadedFile =
        "";
      if (record.afile_id != null) {
        this.fileVisiblity = true;
        this.fileUploadClass = false;
        this.croppedImage = this.croppedImageView = this.$getFileUrl(
          "testimonialMedia",
          record.testimonial_id,
          0,
          "thumb",
          this.$timestamp(record.testimonial_updated_at)
        );
        this.originalImage = this.$getFileUrl(
          "testimonialMedia",
          record.testimonial_id,
          0,
          "original",
          this.$timestamp(record.testimonial_updated_at)
        );
        this.attachedFile = record.afile_id ? record.afile_id : "";
      } else {
        this.attachedFile = "";
      }
      this.selectedPage = "editform";
    },
    emptyFormValues: function () {
      this.adminsData = {
        afile_id: "",
        testimonial_id: "",
        testimonial_title: "",
        testimonial_user_name: "",
        testimonial_designation: "",
        testimonial_description: "",
        testimonial_publish: "",
      };
      this.editText = this.uploadedFile = this.croppedImage = this.croppedImageView = this.originalImage =
        "";
      this.errors.clear();
    },
    validateRecord: function () {
      this.$validator.validateAll().then((res) => {
        if (res) {
          let input = this.adminsData;
          this.clicked = 1;
          if (input.testimonial_id != "") {
            this.updateRecord(this.adminsData);
          } else {
            this.saveRecord(input);
          }
        } else {
          this.clicked = 0;
        }
      });
    },
    updateRecord: function (input) {
      let formData = this.$serializeData(input);
      formData.append("actualImage", this.uploadedFile);
      formData.append("cropImage", this.croppedImage);
      formData.append("_method", "PUT");
      this.$http
        .post(adminBaseUrl + "/testimonials/" + input.testimonial_id, formData)
        .then(
          (response) => {
            //success
            this.clicked = 0;
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
            this.pageRecords(this.pagination.current_page);
            this.emptyFormValues();
            this.cancel();
          },
          (response) => {
            //error
            this.clicked = 0;
            this.validateErrors(response);
          }
        );
    },
    confirmDelete: function (event, dataid) {
      event.stopPropagation();
      this.recordId = dataid;
      this.deleteStatus = {
        message: this.$t("LBL_TESTIMONIAL_DELETE_TEXT"),
        subMessage: "",
        id: dataid,
      };
      this.$bvModal.show(this.modelId);
    },
    deleteRecord: function (recordId) {
      if (this.attachedFile != "") {
        this.$http
          .delete(adminBaseUrl + "/remove-attached-files/" + recordId)
          .then((response) => {
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            this.emptyImageData();
            this.deleteStatus.type = 0;
            this.attachedFile = "";
            toastr.success(response.data.message, "", toastr.options);
            this.pageRecords(this.pagination.current_page);
          });
      } else if (this.deleteStatus.type == 0) {
        this.emptyImageData();
      } else {
        this.$http
          .delete(adminBaseUrl + "/testimonials/" + recordId)
          .then((response) => {
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
            this.pageRecords(this.pagination.current_page);

            this.selectedPage = "";
            this.emptyImageData();
          });
      }
      this.$bvModal.hide(this.modelId);
    },
    validateErrors: function (response) {
      let jsondata = response.data;
      Object.keys(jsondata.errors).forEach((key) => {
        this.errors.add({
          field: key,
          msg: jsondata.errors[key][0],
        });
      });
    },
    saveRecord: function (input) {
      let formData = this.$serializeData(input);
      formData.append("actualImage", this.uploadedFile);
      formData.append("cropImage", this.croppedImage);
      this.$http.post(adminBaseUrl + "/testimonials", formData).then(
        (response) => {
          //success
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords(this.pagination.current_page);
          this.emptyFormValues();
          this.selectedPage = "";
          this.clicked = 0;
        },
        (response) => {
          //error
          this.clicked = 0;
          this.validateErrors(response);
        }
      );
    },
    onSwitchStatus: function (event, id) {
      let formData = this.$serializeData({
        status: event.target.checked,
      });
      this.$http
        .post(adminBaseUrl + "/testimonials/status/" + id, formData)
        .then((response) => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    cancel: function () {
      this.selectedPage = false;
      if (this.records.length == 0) {
        this.showEmpty = 1;
      }
    },
    emptyUpdatedFieldValue: function () {
      this.createdByUser = "";
      this.updatedByUser = "";
      this.updatedAt = "";
      this.createdAt = "";
    },
    emptyImageData: function () {
      this.croppedImage = "";
      this.croppedImageView = "";
      this.originalImage = "";
      this.uploadedFile = "";
      this.fileUploadClass = false;
      this.fileVisiblity = false;
    },
    removeImage: function (event, afileId) {
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
  mounted: function () {
    this.searchData.search = "";
    this.refreshedSearchData();
  },
};
</script>