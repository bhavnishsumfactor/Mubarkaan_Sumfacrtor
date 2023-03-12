<template>
    <form class="form" id="form" novalidate="novalidate" v-on:submit.prevent="validateRecord" v-bind:class="[(!$canWrite('PRODUCTS')) ? 'disabledbutton': '']">
        <div class="wizard-v2__content" data-f-a-tbitwizard-type="step-content" data-f-a-tbitwizard-state="current">
            <div class="form__section form__section--first">
                <div class="wizard-v2__form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label> {{$t('LBL_PRODUCTS_TYPE')}} <span class="required">*</span> </label>
                                <select class="form-control" v-model="adminsData.prod_type" name="prod_type" v-validate="'required'" :data-vv-as="$t('LBL_PRODUCTS_TYPE')" :disabled="Object.keys(productTypes).length == 1" data-vv-validate-on="none">
                                    <option disabled value>{{$t('LBL_SELECT_PRODUCT_TYPE')}}</option>
                                    <option v-for="(productType,index) in productTypes" :key="index" :value="index">{{productType}}</option>
                                </select> <span v-if="errors.first('prod_type')" class="error text-danger">{{ errors.first('prod_type') }}</span> </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label> {{$t('LBL_PRODUCT_TITLE')}} <span class="required">*</span> </label>
                                <input type="text" class="form-control" :placeholder="$t('PLH_PRODUCT_TITLE')" v-model="adminsData.prod_name" name="prod_name" v-validate="'required'" :data-vv-as="$t('LBL_PRODUCT_TITLE')" data-vv-validate-on="none" /> <span v-if="errors.first('prod_name')" class="error text-danger">{{ errors.first('prod_name') }}</span> </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <label> {{$t('LBL_BRAND')}} <span v-if="isBrandRequired != 1">*</span> </label>
                                </div>    
                                <input type="text" name="prod_brand_id" v-if="render == 1" :placeholder="$t('PLH_BRAND_PLEASE_SEARCH')" aria-label="Search Brand" autocomplete="off" class="form-control" data-toggle="dropdown" aria-expanded="true" v-model="productBrand" @keyup="searchBrandData" v-validate="isBrandRequired != 1 ? 'required' : '' " :data-vv-as="$t('LBL_BRAND')" data-vv-validate-on="none"/>
                                <span v-if="errors.first('prod_brand_id')" class="error text-danger">{{ errors.first('prod_brand_id') }}</span>
                                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-anim dropdown-suggestions">
                                    <ul class="nav nav--block">
                                  
                                        <li class="dropdown-item nav__item" v-for="(brand, index) in productBrandData" :key="index">
                                            <a href="javascript:;" @click="selectedBrand(brand)">{{ brand.label }}</a>
                                        </li>
                                        <li class="dropdown-divider"></li>
                                            <li class="dropdown-item nav__item">
                                            <a class="" href="javascript:;" @click="addBrandPopup()" > <i class="la la-plus"></i> {{ $t('LNK_ADD_BRAND') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <label> {{$t('LBL_CATEGORY')}} <span class="required">*</span> </label> <a class="links" href="javascript:;" @click="addCategoryPopup()"> {{ $t('LNK_ADD_CATEGORY') }}</a> </div>
                                <treeselect name="prod_cat_id" v-if="prodCategories.length > 0" v-model="adminsData.prodCatId" :defaultExpandLevel="Infinity" :isDefaultExpanded="true" :options="prodCategories" :searchable="true" :open-on-click="true" :close-on-select="true" :placeholder="$t('PLH_SELECT_ONE')" v-validate="'required'" :data-vv-as="$t('LBL_CATEGORY')" data-vv-validate-on="none" /> <span v-if="errors.first('prod_cat_id')" class="error text-danger">{{ errors.first('prod_cat_id') }}</span> </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{$t('LBL_DESCRIPTION')}}</label>
                                <div v-bind:class="[loadingEditor == true ? 'loadEditor' : '']">
                                    <textarea class="form-control" id="editor" v-model="adminsData.prod_description" name="prod_description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{$t('LBL_MODEL_NUMBER')}}.</label>
                                <input type="text" class="form-control" v-model="adminsData.prod_model" placeholder="5623"/> </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> {{$t('LBL_TAX_CATEGORY')}} <span class="required">*</span> </label>
                                <select class="form-control" v-model="adminsData.prod_taxcat_id" name="prod_taxcat_id" v-validate="'required'" :data-vv-as="$t('LBL_TAX_CATEGORY')" data-vv-validate-on="none">
                                    <option disabled value>{{$t('LBL_SELECT_TAX_CATEGORY')}}</option>
                                    <option v-for="taxGroup in taxGroups" :key="taxGroup.taxgrp_id" :value="taxGroup.taxgrp_id">{{taxGroup.taxgrp_name}}</option>
                                </select> <span v-if="errors.first('prod_taxcat_id')" class="error text-danger">{{ errors.first('prod_taxcat_id') }}</span> </div>
                        </div>
                        <div class="col-md-4" v-if="adminsData.prod_type != 2">
                            <div class="form-group">
                                <label> {{$t('LBL_PRODUCT_CONDITION')}} <span class="required">*</span> </label>
                                <select class="form-control" v-model="adminsData.prod_condition">
                                    <option disabled value>{{$t('LBL_SELECT')}}</option>
                                    <option v-for="(productCondition,index) in productConditions" :key="index" :value="index">{{productCondition}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="adminsData.prod_type != 2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> {{$t('LBL_WARRANTY_DAYS')}} <span class="required">*</span> </label>
                                <input type="text" class="form-control" v-model="adminsData.pc_warranty_age" name="pc_warranty_age" v-validate="'required|decimal:0|min_value:0'" :data-vv-as="$t('LBL_WARRANTY')" data-vv-validate-on="none" placeholder="7" /> <span v-if="errors.first('pc_warranty_age')" class="error text-danger">{{ errors.first('pc_warranty_age') }}</span> </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> {{$t('LBL_RETURN_DAYS')}} <span class="required">*</span> </label>
                                <input type="text" class="form-control" v-model="adminsData.pc_return_age" name="pc_return_age" v-validate="'required|decimal:0|min_value:0'" :data-vv-as="$t('LBL_RETURN')" data-vv-validate-on="none" placeholder="7" /> <span v-if="errors.first('pc_return_age')" class="error text-danger">{{ errors.first('pc_return_age') }}</span> </div>
                        </div>
                    </div>
                </div>
                <BrandPopup @setBrand="setNewBrand"></BrandPopup>
                <CategoryPopup @setCategory="setNewCategory" :prodCat="allProdCategories"></CategoryPopup>
            </div>
        </div>
        <div class="form__actions">
            <button class="btn btn-secondary btn-wide" @click="$router.push({ name: 'products'} )" type="button">{{$t('BTN_DISCARD')}}</button>
            <button class="btn btn-wide btn-brand gb-btn gb-btn-primary" data-f-a-tbitwizard-type="action-next" :disabled="!isComplete || clicked == 1 || !$canWrite('PRODUCTS')" v-bind:class="clicked==1 && 'gb-is-loading'" >{{$t('BTN_NEXT')}}</button>
        </div>
    </form>
</template>
<script>


import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import BrandPopup from '../brands/addbrandPopup';
import CategoryPopup from './categories/addCategoryPopup';

const tableFields = {
  prod_id: "",
  prod_name: "",
  prod_type: "",
  prodCatId: "",
  prodBrandId: "",
  prod_taxcat_id: "",
  prod_description: "",
  prod_model: "",
  prod_condition: 0,
  pc_warranty_age: "",
  pc_return_age: "",
};
export default {
  props: ["prod_id"],
  components: {
    Treeselect,
    BrandPopup,
    CategoryPopup
  },
  data: function() {
    return {
      activeThemeUrl: activeThemeUrl,
      adminsData: tableFields,
      taxGroups: [],
      productConditions: [],
      productTypes: [],
      prodCategories: [],
      allProdCategories:[],
      allCategories: [],
      allBrands: [],
      brands: [],
      brandPopup:'brandPopup',
      categoryPopup:'categoryPopup',
      tab: "general",
      isBrandRequired: 1,
      clicked: 0,
      loadingEditor: true,
      defaultBrand: [],
      render:0,
      myPlugins: [
          'advlist autolink lists link image charmap',
          'searchreplace visualblocks code fullscreen',
          'print preview anchor insertdatetime media',
          'paste code help wordcount table'
      ],
      toolBar: 'undo redo | formatselect | bold italic | \
      alignleft aligncenter alignright | \
      bullist numlist outdent indent | image | preview | fullscreen',
      logs: [],
      productBrand : "",
      productBrandData:[]
    };
  },
  computed: {
    isComplete() {
      return (
        this.adminsData.prod_name &&
        this.adminsData.prod_type &&
        this.adminsData.prodCatId &&
        this.adminsData.prod_taxcat_id &&
        (this.isBrandRequired != 1 ? this.adminsData.prodBrandId : true) &&
        (this.adminsData.prod_type == 1
          ? (this.adminsData.prod_condition !== '') &&
            this.adminsData.pc_warranty_age &&
            this.adminsData.pc_return_age
          : true)
      );
    }
  },
  methods: {
    validateRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          this.clicked = 1;
          this.saveGenInfo();
        } else {
          this.clicked = 0;
        }
      });
    },
    saveGenInfo: function() {
      let formData = this.$serializeData(this.adminsData);
      formData.append("prod_cat_id", this.adminsData.prodCatId);
      formData.append("prod_brand_id", this.adminsData.prodBrandId);
      this.$http
        .post(adminBaseUrl + "/products/save/" + this.tab, formData)
        .then(
          response => {
            //success
            if (response.data.status == false) {
                this.clicked = 0;
                toastr.error(response.data.message, "", toastr.options);
                return;
            }
            toastr.success(response.data.message, "", toastr.options);
            this.clicked = 0;
            this.$emit(
              "next",
              response.data.data.prod_id,
              "price",
              this.adminsData.prod_type
            );
          },
          response => {
            //error
            this.clicked = 0;
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
      let prod_id = this.adminsData.prod_id;
      this.prodCategories = this.brands = [];
      let formData = this.$serializeData({ tab: this.tab, prod_id: prod_id });
      this.$http
        .post(adminBaseUrl + "/products/page-load-data", formData)
        .then(response => {
          //success
          if (response.data.status == true) {
            let productResponse = response.data.data;
            this.taxGroups = productResponse.taxGroups;
            this.productTypes = productResponse.productTypes;
            if (Object.keys(this.productTypes).length == 1) {
              this.adminsData.prod_type = Object.keys(this.productTypes)[0];
            }
            this.productConditions = productResponse.productConditions;
            this.prodCategories = productResponse.prodCategories;
            this.allProdCategories = productResponse.allCategories;
            this.defaultBrand = productResponse.brand;
            this.isBrandRequired = productResponse.brandOption;
            if (Object.keys(this.defaultBrand).length == 1) {
              this.productBrand = this.defaultBrand[0].label;
            }
            if (this.defaultBrand.length == 0) {
                this.adminsData.prodBrandId = null;
            }
            if(productResponse.top_brands.length > 0) {
                this.productBrandData = productResponse.top_brands;
            }
            this.render = 1;
            if (productResponse.editInfo) {
              this.assignValues(productResponse.editInfo);
              this.logs['created_at'] = productResponse.editInfo.created_at;
              this.logs['updated_at'] = productResponse.editInfo.updated_at;
              this.logs['prod_created_on'] = productResponse.editInfo.prod_created_on;
              this.logs['prod_updated_on'] = productResponse.editInfo.prod_updated_on;
              this.$emit("lastUpdatedLogs", this.logs);
            }else{
              this.initTinyMce('');
            }
          }
        });
    },

    assignValues: function(response) {
        this.adminsData.prod_id = response.prod_id;
        this.adminsData.prod_name = response.prod_name;
        this.adminsData.prod_type = response.prod_type;
        this.adminsData.prodCatId = response.prod_cat_id;
        if (response.prod_brand_id != 0 && this.defaultBrand.length != 0) {
            this.adminsData.prodBrandId = response.prod_brand_id;
        }
      this.$emit("previous", "general", response.prod_type);
      this.adminsData.prod_taxcat_id = response.prod_taxcat_id;
      this.adminsData.prod_description = response.prod_description;
      this.initTinyMce(this.adminsData.prod_description);
      this.adminsData.prod_model = response.prod_model;
      this.adminsData.prod_condition = response.prod_condition;
      this.adminsData.pc_warranty_age = response.pc_warranty_age;
      this.adminsData.pc_return_age = response.pc_return_age;
      this.progressBarUpdate(this.adminsData.prod_type);
    },

    progressBarUpdate: function(type) {
      let totalStep = 5;
      let bar = 0; /**(100 / totalStep) * 1 */
      if (type == 2) {
        totalStep = 6;
        bar = 0; /*(100 / totalStep) * 1 */
      }
      this.$emit("progressBar", bar, 1, totalStep);
    },
    emptyFormValues: function() {
      this.adminsData = {
        prod_name: "",
        prod_type: "",
        prodCatId: "",
        prodBrandId: "",
        prod_taxcat_id: "",
        prod_description: "",
        prod_model: "",
        prod_condition: "",
        pc_warranty_age: "",
        pc_return_age: "",
      };
      this.errors.clear();
    },
    addBrandPopup: function(){
        this.$root.$emit('emptyBrandPopup');
        this.$bvModal.show(this.brandPopup);
    },
    addCategoryPopup : function() {      
        this.$root.$emit('emptyCategoryPopup');
        this.$bvModal.show(this.categoryPopup);
    },
    initTinyMce: function(descriptionInitValue) {
      
        let thisObj = this;
        thisObj.loadingEditor = true;
        tinymce.remove();
        tinymce.init({
            selector:'#editor',
            branding: false,
            height: 400,
            menubar: true,
            plugins: this.myPlugins,
            toolbar: this.toolBar,
            images_upload_url: adminBaseUrl + '/editor/images',
            images_upload_credentials: true,
            //content_css : this.activeThemeUrl + '/css/main-ltr.css',
            setup: function(editor) {
                editor.on('init', function(e) {                        
                   editor.setContent(descriptionInitValue);
                });
                editor.on('change', function(e) {
                    thisObj.adminsData.prod_description = editor.getContent();
                });
                thisObj.loadingEditor = false;
            }
        });
    },
    setNewBrand : function(brand){
        this.defaultBrand = brand;
        this.adminsData.prodBrandId = (this.defaultBrand.length != 0) ? this.defaultBrand[0].id : '';
        this.productBrand =  (this.defaultBrand.length != 0) ? this.defaultBrand[0].label : '';
        this.$bvModal.hide(this.brandPopup);
    },
    setNewCategory : function(categoryId){
        this.$http.get(adminBaseUrl + "/products/get-categories")
        .then(response => {
            this.prodCategories = response.data.data.prodCategories;
            this.adminsData.prodCatId = categoryId;
            this.$bvModal.hide(this.categoryPopup);
        });
    },
    loadBrands : function({ action, searchQuery, callback }) {
       if (action === "ASYNC_SEARCH") {
            this.$http.post(adminBaseUrl + "/products/search-brands", {'brand': searchQuery })
            .then(response => {
                callback(null, response.data.data);
            });
       }   
    },
    searchBrandData : function() {
        //if (this.productBrand.length >= 2) {
            this.$http.post(adminBaseUrl + "/products/search-brands", {'brand': this.productBrand})
            .then(response => {
                this.productBrandData = response.data.data;
            });
        //}
    },
    selectedBrand : function(brand) {
       this.productBrand =  brand.label;
       this.adminsData.prodBrandId = brand.id;
    }
  },
  mounted: function() {
    this.emptyFormValues();
    let id = this.$route.params.id;
    if (id) {
      this.adminsData.prod_id = id;
    } else {
      this.adminsData.prod_id = this.prod_id;
    }
    this.adminsData.prodCatId = 0;
    this.adminsData.prodBrandId = null;
    this.pageLoadData();
  }  
};
</script>