<template>
  <form
    class="form"
    id="form"
    novalidate="novalidate"
    v-on:submit.prevent="validateRecord"
  >
    <div
      class="wizard-v2__content"
      data-f-a-tbitwizard-type="step-content"
      data-f-a-tbitwizard-state="current"
    >
      <div class="form__section form__section--first">
        <div class="wizard-v2__form">
          <div class="row">
            <div class="col-xl-6">
              <div class="form-group">
                <label>
                  {{ $t('LBL_MAX_DOWNLOAD_TIMES') }}
                  <span class="required">*</span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  v-model="adminsData.pc_max_download_times"
                  placeholder="1"
                  name="pc_max_download_times"
                  v-validate="'required|decimal:0|min_value:1'"
                  :data-vv-as="$t('LBL_MAX_DOWNLOAD')"
                  data-vv-validate-on="none"
                />
                <span
                  v-if="errors.first('pc_max_download_times')"
                  class="error text-danger"
                >
                  {{ errors.first('pc_max_download_times') }}
                </span>
              </div>
            </div>
            <div class="col-xl-6">
              <div class="form-group">
                <label>
                  {{ $t('LBL_VALIDATION_DAYS') }}
                  <span class="required">*</span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  v-model="adminsData.pc_download_validity_in_days"
                  placeholder="1"
                  name="pc_download_validity_in_days"
                  v-validate="'required|decimal:0|min_value:1'"
                  :data-vv-as="$t('LBL_DOWNLOAD_VALIDITY')"
                  data-vv-validate-on="none"
                />
                <span
                  v-if="errors.first('pc_download_validity_in_days')"
                  class="error text-danger"
                >
                  {{ errors.first('pc_download_validity_in_days') }}
                </span>
              </div>
            </div>
          </div>
          <div
            class="separator separator--border-dashed separator--space-lg"
          ></div>
          <div class="row justify-content-between">
            <div class="col">
              <div class="form-group">
                <label>{{ $t('LBL_ADDITIONAL_FILES_AT_DELIVERY') }}?</label>
              </div>
            </div>
            <div class="col-auto">
              <div class="form-group">
                <div class="radio-inline">
                  <label class="radio">
                    <input
                      type="radio"
                      checked="checked"
                      :value="1"
                      v-model="adminsData.pc_upload_additional_files"
                      name="radio4"
                    />
                    {{ $t('LBL_YES') }}
                    <span></span>
                  </label>
                  <label class="radio">
                    <input
                      type="radio"
                      name="radio4"
                      :value="0"
                      v-model="adminsData.pc_upload_additional_files"
                    />
                    {{ $t('LBL_NO') }}
                    <span></span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          <span v-if="errors.first('type')" class="error text-danger">
            {{ errors.first('type') }}
          </span>
          <br />
          <span v-if="errors.first('dfr_file_type')" class="error text-danger">
            {{ errors.first('dfr_file_type') }}
          </span>
          <br />
          <span v-if="errors.first('dfr_name')" class="error text-danger">
            {{ errors.first('dfr_name') }}
          </span>
          <br />
          <span v-if="errors.first('dfr_url')" class="error text-danger">
            {{ errors.first('dfr_url') }}
          </span>
          <span v-if="errors.first('varient')" class="error text-danger">
            {{ errors.first('varient') }}
          </span>
          <br />
          <span v-if="errors.first('file')" class="error text-danger">
            {{ errors.first('file') }}
          </span>

          <table class="table table-variant">
            <thead>
              <tr>
                <th v-if="varients.length > 0">
                  Select varient
                </th>
                <th>
                  {{ $t('LBL_SELECT_TYPE') }}
                </th>
                <th>
                  {{ $t('LBL_FILE_TYPE') }}
                </th>
                <th>
                  {{ $t('LBL_DIGITAL_FILE_NAME') }}
                </th>
                <th>
                  {{ $t('LBL_DIGITAL_FILE_LINK') }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                class=""
                v-for="(downloadType, index) in downloadTypes"
                :key="index"
              >
                <td class="" v-if="varients.length > 0">
                  <select
                    class="form-control"
                    v-model="adminsData.varient[index]"
                    placeholder
                    name="varient"
                    v-validate="'required'"
                    :data-vv-as="$t('varient')"
                    data-vv-validate-on="none"
                  >
                    <option value>{{ $t('LBL_SELECT') }}</option>
                    <option
                      :value="varient.pov_id"
                      v-for="(varient, vIndex) in varients"
                      :key="vIndex"
                    >
                      {{ varient.pov_display_title.replace('_', '|') }}
                    </option>
                  </select>
                </td>
                <td>
                  <select
                    class="form-control"
                    v-model="type[index]"
                    placeholder
                    name="type"
                    v-validate="'required'"
                    :data-vv-as="$t('Type')"
                    data-vv-validate-on="none"
                  >
                    <option value>{{ $t('LBL_SELECT') }}</option>
                    <option :value="1">{{ $t('LBL_TYPE_FILE') }}</option>
                    <option :value="2">{{ $t('LBL_TYPE_URL') }}</option>
                  </select>
                </td>
                <td>
                  <select
                    class="form-control"
                    v-model="adminsData.dfr_file_type[index]"
                    name="dfr_file_type"
                    v-validate="'required'"
                    :data-vv-as="$t('LBL_FILE_TYPE')"
                    data-vv-validate-on="none"
                  >
                    <option value>{{ $t('select') }}</option>
                    <option
                      v-for="(fileType, index) in fileTypes"
                      :key="index"
                      :value="index"
                    >
                      {{ fileType }}
                    </option>
                  </select>
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-model="adminsData.dfr_name[index]"
                    name="dfr_name"
                    v-validate="'required'"
                    :data-vv-as="$t('LBL_NO_DIGITAL_DOWNLOADS')"
                    data-vv-validate-on="none"
                  />
                </td>
                <td>
                  <div class="d-flex">
                    <input
                      type="text"
                      class="form-control"
                      v-if="type[index] != 1"
                      placeholder="https://www.example.com"
                      v-model="adminsData.dfr_url[index]"
                      name="dfr_url"
                      v-validate="{
                        required: true,
                        url: { require_protocol: true },
                      }"
                      :data-vv-as="$t('LBL_TYPE_URL')"
                      data-vv-validate-on="none"
                    />
                    <input
                      type="file"
                      class="form-control"
                      v-else
                      @change="uploadFile($event, index)"
                      name="file"
                      v-validate="uploadedFile[index] ? '' : 'required'"
                      :data-vv-as="$t('LBL_FILE')"
                      data-vv-validate-on="none"
                    />
                    <div class="actions">
                      <a
                        :href="baseUrl + '/storage/' + uploadedFile[index]"
                        download
                        class="btn btn-icon btn-light ml-2"
                        v-if="uploadedFile[index]"
                      >
                        <i class="flaticon-download"></i>
                      </a>
                      <button
                        type="button"
                        @click="deleteDownloadType(index)"
                        class="btn btn-icon btn-light ml-2"
                        :disabled="index == 0"
                      >
                   
                        <svg>
                          <use
                            :xlink:href="
                              baseUrl +
                              '/admin/images/retina/sprite.svg#delete-icon'
                            "
                          ></use>
                        </svg>
                      </button>
                      <button
                        type="button"
                        data-repeater-create
                        @click="addDownloadType"
                         v-if="index == Object.keys(downloadTypes).length - 1"
                        class="btn btn-icon btn-brand ml-2"
                        
                      >
                        <i class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="form__actions">
      <button
        class="btn btn-secondary btn-wide"
        @click="previous()"
        type="button"
      >
        {{ $t('BTN_PREVIOUS') }}
      </button>
      <button
        class="btn btn-wide btn-brand gb-btn gb-btn-primary"
        data-f-a-tbitwizard-type="action-next"
        @click="
          clicked = 1
          validateRecord()
        "
        :disabled="!isComplete || clicked == 1 || uploaded == 1"
        v-bind:class="clicked == 1 || (uploaded == 1 && 'gb-is-loading')"
      >
        {{ $t('BTN_FINISH') }}
      </button>
    </div>
  </form>
</template>
<script>
const tableFields = {
  pc_max_download_times: '',
  pc_download_validity_in_days: '',
  prod_id: '',
  pc_upload_additional_files: 0,
  prod_type: '',
  dfr_file_type: [],
  dfr_name: [],
  dfr_url: [],
  varient: [],
  dfr_afile_id: [],
}
export default {
  props: ['prod_id'],
  data: function () {
    return {
      tab: 'digital',
      adminsData: tableFields,
      downloadTypes: [],
      fileTypes: [],
      uploadedFile: [],
      varients: [],
      baseUrl: baseUrl,
      type: [],
      clicked: 0,
      uploaded: 0,
    }
  },
  computed: {
    isComplete() {
      return (
        this.adminsData.pc_max_download_times &&
        this.adminsData.pc_download_validity_in_days
      )
    },
  },
  methods: {
    validateRecord: function () {
      this.$validator.validateAll().then((res) => {
        if (res) {
          this.clicked = 1
          this.saveDigitalInfo()
        } else {
          this.clicked = 0
        }
      })
    },
    saveDigitalInfo: function () {
      this.$emit('progressBar', 100, 6, 6)
      let formData = this.$serializeData(this.adminsData)
      formData.append('prod_id', this.prod_id)
      formData.append('type', this.type)
      this.$http
        .post(adminBaseUrl + '/products/save/' + this.tab, formData)
        .then(
          (response) => {
            //success
            if (response.data.status == false) {
              toastr.error(response.data.message, '', toastr.options)
              return
            }
            toastr.success(response.data.message, '', toastr.options)
            this.clicked = 0
            this.$router.push({ name: 'products' })
          },
          (response) => {
            //error
            this.clicked = 0
            this.validateErrors(response)
          },
        )
    },

    deleteDownloadType: function (index) {
      this.deleteUploadedFile(index)

      this.$delete(this.uploadedFile, index)
      this.$delete(this.adminsData.dfr_file_type, index)
      this.$delete(this.adminsData.dfr_name, index)
      this.$delete(this.adminsData.dfr_afile_id, index)
      this.$delete(this.adminsData.varient, index)
      this.$delete(this.downloadTypes, index)
    },
    deleteUploadedFile: function (index) {
      if (this.adminsData.dfr_afile_id[index] != '') {
        let formData = this.$serializeData({
          id: this.adminsData.dfr_afile_id[index],
        })
        this.$http.post(adminBaseUrl + '/products/delete-file', formData).then(
          (response) => {},
          (response) => {
            //error
            this.validateErrors(response)
          },
        )
      }
    },
    addDownloadType: function () {
      this.downloadTypes.push([])
      this.type[this.downloadTypes.length - 1] = ''
      this.adminsData.dfr_file_type[this.downloadTypes.length - 1] = ''
      this.adminsData.dfr_name[this.downloadTypes.length - 1] = ''
      this.adminsData.dfr_afile_id[this.downloadTypes.length - 1] = ''
      this.adminsData.varient[this.downloadTypes.length - 1] = ''
    },
    uploadFile: function (event, index) {
      this.uploaded = 1
      let formData = new FormData()
      formData.append('file', event.target.files[0])
      formData.append('prod_id', this.adminsData.prod_id)
      this.$http
        .post(adminBaseUrl + '/products/upload-digital-file', formData)
        .then(
          (response) => {
            this.adminsData.dfr_afile_id[index] = response.data.data
            this.uploaded = 0
          },
          (response) => {
            //error
            this.validateErrors(response)
          },
        )
    },
    validateErrors: function (response) {
      let jsondata = response.data
      Object.keys(jsondata.errors).forEach((key) => {
        this.errors.add({
          field: key,
          msg: jsondata.errors[key][0],
        })
      })
    },
    pageLoadData: function () {
      let prod_id = this.prod_id
      this.$http
        .post(adminBaseUrl + '/products/page-load-data', {
          tab: this.tab,
          prod_id: prod_id,
        })
        .then((response) => {
          //success
          if (response.data.status == true) {
            let productResponse = response.data.data
            this.fileTypes = productResponse.fileTypes
            this.varients = productResponse.varients
            this.adminsData.pc_max_download_times =
              productResponse.editInfo.product.pc_max_download_times
            this.adminsData.pc_download_validity_in_days =
              productResponse.editInfo.product.pc_download_validity_in_days
            this.adminsData.prod_id = productResponse.editInfo.product.prod_id
            this.adminsData.pc_upload_additional_files =
              productResponse.editInfo.product.pc_upload_additional_files
            this.adminsData.prod_type =
              productResponse.editInfo.product.prod_type

            this.progressBarUpdate(this.adminsData.prod_type)
            if (productResponse.editInfo.uploads.length != 0) {
              Object.keys(productResponse.editInfo.uploads).forEach((key) => {
                this.addDownloadType()
                this.adminsData.dfr_file_type[key] =
                  productResponse.editInfo.uploads[key].dfr_file_type
                this.adminsData.dfr_name[key] =
                  productResponse.editInfo.uploads[key].dfr_name
                this.adminsData.dfr_url[key] =
                  productResponse.editInfo.uploads[key].dfr_url
                this.adminsData.dfr_afile_id[key] =
                  productResponse.editInfo.uploads[key].dfr_afile_id
                this.adminsData.varient[key] =
                  productResponse.editInfo.uploads[key].dfr_pov_id
                if (productResponse.editInfo.uploads[key].dfr_afile_id == 0) {
                  this.type[key] = 2
                } else {
                  this.type[key] = 1
                  this.uploadedFile[key] =
                    productResponse.editInfo.uploads[key].afile_physical_path
                }
              })
            } else {
              this.addDownloadType()
            }
          }
        })
    },

    progressBarUpdate: function (type) {
      let totalStep = 6
      let bar = (100 / totalStep) * 5

      if (type == 2) {
        totalStep = 6
        bar = (100 / totalStep) * 5
      }
      this.$emit('progressBar', bar, 6, totalStep)
    },
    previous: function () {
      this.$emit('previous', 'media', this.adminsData.prod_type)
    },
  },
  mounted: function () {
    this.pageLoadData()
  },
}
</script>
