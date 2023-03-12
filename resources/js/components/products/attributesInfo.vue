<template>
  <form
    class="form"
    id="form"
    novalidate="novalidate"
    v-on:submit.prevent="validateRecord"
    v-bind:class="[(!$canWrite('PRODUCTS')) ? 'disabledbutton': '']"
  >
    <div
      class="wizard-v2__content"
      data-f-a-tbitwizard-type="step-content"
      data-f-a-tbitwizard-state="current"
    >
      <div class="form__section form__section--first">
        <div class="heading heading--md mt-0">{{$t('LBL_ATTRIBUTES')}}</div>
        <div class="wizard-v2__form">
          <div class="row" v-if="attributesFormFields.prod_type != 2">
            <label class="col-xl-3 col-form-label">
              {{$t('LBL_COUNTRY_OF_ORIGIN')}}
              <span class="required">*</span>
            </label>
            <div class="col-xl-9">
              <div class="form-group">
                <select
                  class="form-control"
                  v-model="attributesFormFields.prod_from_origin_country_id"
                  name="prod_from_origin_country_id"
                  placeholder="0"
                  v-validate="'required'"
                  :data-vv-as="$t('origin country')"
                  data-vv-validate-on="none"
                >
                  <option disabled value>{{$t('LBL_SELECT_COUNTRY')}}</option>
                  <option
                    v-for="(country,index) in countries"
                    :key="index"
                    :value="index"
                  >{{country}}</option>
                </select>
                <span
                  v-if="errors.first('prod_from_origin_country_id')"
                  class="error text-danger"
                >{{ errors.first('prod_from_origin_country_id') }}</span>
              </div>
            </div>
          </div>
          <div class="row" v-if="attributesFormFields.prod_type != 2">
            <label class="col-xl-3 col-form-label">
              {{$t('LBL_WEIGHT')}}
              <span class="required">*</span>
            </label>
            <div class="col-xl-9">
              <div class="row">
                <div class="col-xl-6">
                  <div class="form-group">
                    <select
                      class="form-control"
                      v-model="attributesFormFields.pc_weight_unit"
                      name="pc_weight_unit"
                      v-validate="'required'"
                      :data-vv-as="$t('LBL_WEIGHT_TYPE')"
                      data-vv-validate-on="none"
                    >
                      <option disabled value>{{$t('LBL_SELECT_WEIGHT')}}</option>
                      <option
                        v-for="(productWeightType,index) in productWeightTypes"
                        :key="index"
                        :value="index"
                      >{{productWeightType}}</option>
                    </select>
                    <span
                      v-if="errors.first('pc_weight_unit')"
                      class="error text-danger"
                    >{{ errors.first('pc_weight_unit') }}</span>
                  </div>
                </div>
                <div class="col-xl-6">
                  <div class="form-group">
                    <input
                      type="text"
                      class="form-control"
                      v-model="attributesFormFields.pc_weight"
                      name="pc_weight"
                      placeholder="0"
                      v-validate="'required|decimal:0|min_value:1'"
                      :data-vv-as="$t('LBL_WEIGHT')"
                      data-vv-validate-on="none"
                    />
                    <span
                      v-if="errors.first('pc_weight')"
                      class="error text-danger"
                    >{{ errors.first('pc_weight') }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray p-4 rounded">
            <div class="row">
              <div class="col-xl-6">
                <div class="form-group">
                  <label>{{$t('LBL_ISBN')}}</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="attributesFormFields.pc_isbn"
                    placeholder
                  />
                </div>
              </div>
              <div class="col-xl-6">
                <div class="form-group">
                  <label>{{$t('LBL_HSN')}}</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="attributesFormFields.pc_hsn"
                    placeholder
                  />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xl-6">
                <div class="form-group">
                  <label>{{$t('LBL_SAC')}}</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="attributesFormFields.pc_sac"
                    placeholder
                  />
                </div>
              </div>
              <div class="col-xl-6">
                <div class="form-group">
                  <label>{{$t('LBL_UPC')}}</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="attributesFormFields.pc_upc"
                    placeholder
                  />
                </div>
              </div>
            </div>

            <div class="row" v-if="optionExists">
                <div class="col-xl-6">
                    <label class="">{{$t('LBL_FILE')}}:</label>
                    <input type="text" class="form-control" v-model="attributesFormFields.pc_file_title" placeholder="Title"/>
                </div>
                <div class="col-xl-6">
                    <label class="">&nbsp</label>
                    <div class="dropzone dropzone-multi dropzone-height-auto" id="dropzone_5">
                        <div class="dropzone-panel">
                            <input class="form-control" type="file" accept="image/*" @change="onSelectImage($event)" />
                            <a class="dropzone-panel-icon" :href="sizeChart.size_chart" target="_blank" v-if="sizeChart !== null && Object.keys(sizeChart).length > 0"><i class="fa fa-eye"></i></a>
                        </div>
                        <div class="dropzone-progress mt-2" v-if="uploadBar != 0">
                            <div class="progress">
                                <div class="progress-bar kt-bg-brand" role="progressbar" aria-valuemin="0" aria-valuemax="100" v-bind:style="{ width: uploadBar+'%' }"></div>
                            </div>
                        </div>
                    </div>
                    <span class="form-text text-muted">{{$t('LBL_FILE_VALIDATION')}}.</span></div>
                </div>
            </div>

          <div class="heading heading--md">{{$t('LBL_ADDITIONAL_INFORMATION')}}</div>
          <span v-if="errors.first('sname')" class="error text-danger">{{ errors.first('sname') }}</span>
          <span v-if="errors.first('svalue')" class="error text-danger">{{ errors.first('svalue') }}</span>
          <span v-if="errors.first('sgroup')" class="error text-danger">{{ errors.first('sgroup') }}</span>

          <div class="row" v-for="(specificationInput, index) in specificationInputs" :key="index">
            <div class="col-xl-4">
              <div class="form-group">
                <input
                  type="text"
                  class="form-control"
                  v-model="attributesFormFields.ps_product_key[index]"
                  placeholder="Name"
                  name="sname"
                  v-validate="'required'"
                  :data-vv-as="$t('LBL_SPECIFICATION_NAME')"
                  data-vv-validate-on="none"
                />
              </div>
            </div>
            <div class="col-xl-4">
              <div class="form-group">
                <input
                  type="text"
                  class="form-control"
                  v-model="attributesFormFields.ps_product_value[index]"
                  placeholder="Value"
                  name="svalue"
                  v-validate="'required'"
                  :data-vv-as="$t('LBL_SPECIFICATION_VALUE')"
                  data-vv-validate-on="none"
                />
              </div>
            </div>
            <div class="col-xl-4">
              <div class="form-group">
                <div class="icon-group actions">
                  <vue-bootstrap-typeahead
                    name="svalue"
                    ref="groupRef"
                    v-model="attributesFormFields.ps_product_group[index]"
                    :data="suggestions"
                    :max-matches="5"
                    :min-matching-chars="1"
                    :placeholder=" $t('LBL_START_TYPING_CATEGORY_NAME')"
                  />
                  <a
                    href="javascript:;"
                    data-repeater-delete
                    @click="deleteSpecification(index)"
                    class="btn btn-icon btn-light ml-2"
                  >
                    <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>                               
                                                            </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xl-12">
              <a
                href="javascript:;"
                data-repeater-create
                @click="addSpecifications"
                class="btn btn-outline-brand"
              >
                <i class="fas fa-plus"></i>
                {{$t('BTN_ADD')}}
              </a>
            </div>
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
        class="btn btn-wide btn-brand gb-btn gb-btn-primary ml-auto"
        @click="exitClicked=1;validateRecord();"
        :disabled="!isComplete || exitClicked==1 || !$canWrite('PRODUCTS')"
        v-bind:class="exitClicked==1 && 'gb-is-loading'"
      >{{$t('BTN_SAVE_AND_EXIT')}}</button> &nbsp;
      <button
        class="btn btn-wide btn-brand gb-btn gb-btn-primary"
        data-f-a-tbitwizard-type="action-next"
        @click="clicked=1;validateRecord();"
        :disabled="!isComplete || clicked==1 || !$canWrite('PRODUCTS')"
        v-bind:class="clicked==1 && 'gb-is-loading'"
      >{{$t('BTN_NEXT')}}</button>
    </div>
  </form>
</template>
<script>
import VueBootstrapTypeahead from "vue-bootstrap-typeahead";

const attributesFields = {
  ps_product_key: [],
  ps_product_value: [],
  ps_product_group: [],
  pc_isbn: "",
  pc_hsn: "",
  pc_sac: "",
  pc_upc: "",
  pc_file_title: "",
  prod_from_origin_country_id: "",
  prod_sbpkg_id: "1",
  pc_weight: "",
  prod_type: "",
  pc_weight_unit: "",
  tag: ""
};
export default {
  props: ["prod_id"],
  components: {
    VueBootstrapTypeahead
  },
  data: function() {
    return {
      tab: "attributes",
      attributesFormFields: attributesFields,
      uploadBar: 0,
      baseUrl:baseUrl,
      shippingPackages: [],
      countries: [],
      productWeightTypes: [],
      suggestions: [],
      specificationInputs: [
        {
          specificationId: 1
        }
      ],
      sizeChart:[],
      clicked: 0,
      exitClicked: 0,
      optionExists: false
    };
  },
  watch: {
    productSearch: _.debounce(function(prod) {
      this.getProducts(prod);
    }, 500)
  },
  computed: {
    isComplete() {
      return this.attributesFormFields.prod_type == 1
        ? this.attributesFormFields.prod_from_origin_country_id === 0
          ? "not empty"
          : this.attributesFormFields.prod_from_origin_country_id &&
            this.attributesFormFields.pc_weight &&
            this.attributesFormFields.pc_weight_unit === 0
          ? "not empty"
          : this.attributesFormFields.pc_weight_unit &&
            this.attributesFormFields.prod_sbpkg_id === 0
          ? "not empty"
          : this.attributesFormFields.prod_sbpkg_id
        : true;
    }
  },
  methods: {
    validateRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          this.saveAttributesInfo();
        } else {
          this.exitClicked = this.clicked = 0;
        }
      });
    },
    saveAttributesInfo: function() {
      let formData = this.$serializeData(this.attributesFormFields);
      formData.append("prod_id", this.prod_id);
      formData.set(
        "ps_product_key",
        JSON.stringify(this.attributesFormFields.ps_product_key)
      );
      formData.set(
        "ps_product_value",
        JSON.stringify(this.attributesFormFields.ps_product_value)
      );
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
            if (this.exitClicked == 1) {
              this.$router.push({ name: "products" });
              return;
            }
            this.exitClicked = this.clicked = 0;
            this.$emit(
              "next",
              response.data.data.prod_id,
              "media",
              this.attributesFormFields.prod_type
            );
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
    addSpecifications: function() {
      this.specificationInputs.push({
        specificationId: 1 + 1
      });
      this.attributesFormFields.ps_product_group[
        this.specificationInputs.length - 1
      ] = "";
      this.suggestions = this.removeDuplicates(
        this.attributesFormFields.ps_product_group
      );
    },
    removeDuplicates: function(array) {
      return array.filter((a, b) => array.indexOf(a) === b);
    },
    previous: function() {
      this.$emit("previous", "options", this.attributesFormFields.prod_type);
    },
    deleteSpecification: function(index) {
      this.$delete(this.attributesFormFields.ps_product_key, index);
      this.$delete(this.attributesFormFields.ps_product_value, index);
      this.$delete(this.attributesFormFields.ps_product_group, index);
      this.$delete(this.specificationInputs, index);
    },
    pageLoadData: function() {
      this.attributesFormFields.ps_product_key = [];
      this.attributesFormFields.ps_product_value = [];
      this.attributesFormFields.ps_product_group = [];

      let prod_id = this.prod_id;
      this.$http
        .post(adminBaseUrl + "/products/page-load-data", {
          tab: this.tab,
          prod_id: prod_id
        })
        .then(response => {
          //success
          if (response.data.status == true) {
            this.specificationInputs = [];
            let productResponse = response.data.data;
            this.countries = productResponse.countries;
            this.productWeightTypes = productResponse.productWeightTypes;
            this.shippingPackages = productResponse.shipping_packages;
            this.shippingDimensions = productResponse.shipping_dimensions;
            this.optionExists = productResponse.enableSizeChart;
            this.sizeChart = productResponse.sizeChartUrl;
        //    console.log('sizsch',this.sizeChart);
            this.assignValues(productResponse.editInfo);
            this.progressBarUpdate(this.attributesFormFields.prod_type);
          }
        });
    },
    assignValues: function(response) {
      let fields = this.attributesFormFields;
      fields.pc_isbn =
        response.product.pc_isbn != "null" ? response.product.pc_isbn : "";
      fields.pc_hsn =
        response.product.pc_hsn != "null" ? response.product.pc_hsn : "";
      fields.pc_sac =
        response.product.pc_sac != "null" ? response.product.pc_sac : "";
      fields.pc_upc =
        response.product.pc_upc != "null" ? response.product.pc_upc : "";
      fields.prod_from_origin_country_id =
        response.product.prod_from_origin_country_id != null
          ? response.product.prod_from_origin_country_id
          : "";
      fields.prod_sbpkg_id =
        response.product.prod_sbpkg_id != null
          ? response.product.prod_sbpkg_id
          : "1";
      fields.pc_weight = response.product.pc_weight;
      fields.pc_weight_unit =
        response.product.pc_weight_unit != null
          ? response.product.pc_weight_unit
          : "";
      fields.pc_file_title =
        response.product.pc_file_title != "null"
          ? response.product.pc_file_title
          : "";
      fields.prod_type = response.product.prod_type;
      this.specificationInputs = response.specification;

      let thisObj = this;
      Object.keys(response.specification).forEach(key => {
        fields.ps_product_key[key] = response.specification[key].ps_product_key;
        fields.ps_product_value[key] =
          response.specification[key].ps_product_value;
        fields.ps_product_group[key] =
          response.specification[key].ps_product_group;

        setTimeout(function() {
          thisObj.$refs.groupRef[key].inputValue =
            response.specification[key].ps_product_group;
        }, 5);
      });
    },
    progressBarUpdate: function(type) {
      let totalStep = 5;
      let bar = (100 / totalStep) * 3;

      if (type == 2) {
        totalStep = 6;
        bar = (100 / totalStep) * 3;
      }
      this.$emit("progressBar", bar, 4, totalStep);
    },
    onSelectImage: function($event) {
      let thisVal = this;
      thisVal.uploadBar = 0;
      let formData = this.$serializeData({
        file: $event.target.files[0]
      });
      formData.append("prod_id", this.prod_id);
      formData.append("attachedType", "productChartUpload");
      this.$http
        .post(adminBaseUrl + "/products/upload-files", formData, {
          progress(e) {
            if (e.lengthComputable) {
              thisVal.uploadBar = (e.loaded / e.total) * 100;
            }
          }
        })
        .then(
          response => {
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
          },
          response => {
            //error
            this.validateErrors(response);
          }
        );
    }
  },
  mounted: function() {
    this.pageLoadData();
  }
};
</script>