<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_SPECIAL_PRICES') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link> 
                          <span class="subheader__breadcrumbs-separator"></span> <span class="subheader__breadcrumbs-link">{{$t('LBL_PROMOTIONS')}}</span> <span class="subheader__breadcrumbs-separator"></span> <span class="subheader__breadcrumbs-link">{{$t('LBL_SPECIAL_PRICES')}}</span> </div>
                </div>
                <div class="subheader__toolbar">
                    <a @click="openAddPage" class="btn btn-white" href="javascript:void(0);" v-bind:class="[(!$canWrite('SPECIAL_PRICES')) ? 'disabledbutton no-drop': '']"> <i class="fas fa-plus"></i> {{ $t('BTN_ADD') }} </a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="" v-bind:class="[(showEmpty == 1) ? 'col-md-12': 'col-md-7']">
                    <div class="portlet portlet--height-fluid portlet--tabs">
                        <div class="portlet__head" v-if="showEmpty == 0">
                            <div class="portlet__head-toolbar">
                                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold" role="tablist">
                                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="javascript:;" role="tab" @click="tabFilter('all')">{{$t('LNK_SPECIALPRICE_FILTER_ALL')}}</a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="javascript:;" role="tab" @click="tabFilter('active')">{{$t('LNK_SPECIALPRICE_FILTER_ACTIVE')}}</a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="javascript:;" role="tab" @click="tabFilter('scheduled')">{{$t('LNK_SPECIALPRICE_FILTER_SCHEDULED')}}</a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="javascript:;" role="tab" @click="tabFilter('expired')">{{$t('LNK_SPECIALPRICE_FILTER_EXPIRED')}}</a> </li>
                                </ul>
                            </div>
                        </div>
                        <div class="portlet__body pb-0 bg-gray flex-grow-0" v-if="showEmpty == 0">
                            <div class="form-group">
                                <div class="input-icon input-icon--left">
                                    <input type="text" class="form-control" placeholder="Search" id="generalSearch" :readonly="records.length == 0 && searchData.search === ''" v-model="searchData.search" @keyup="searchRecords" /> <span class="input-icon__icon input-icon__icon--left">
                        <span>
                        <i class="la la-search"></i>
                        </span> </span> <span class="input-icon__icon input-icon__icon--right" v-if="searchData.search!=''" @click="searchData.search='';pageRecords(1);">
                        <span>
                        <i class="fas fa-times"></i>
                        </span> </span>
                                </div>
                            </div>
                        </div>
                        <hr class="m-0" />
                        <div class="portlet__body portlet__body--fit" v-if="showEmpty == 0 && pageLoaded==1">
                            <div class="section">
                                <div class="section__content">
                                    <table class="table table-data table-justified">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ '#' }}</th>
                                                <th width="45%">{{ $t('LBL_SPECIALPRICE_NAME') }}</th>
                                                <th width="10%">{{ $t('LBL_PUBLISH') }}</th>
                                                <th width="25%">{{ $t('LBL_SPECIALPRICE_DATES') }}</th>
                                                <th width="15%"></th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="loading==false && records.length == 0 && (searchData.search != '' || selectedPage != 'all') && showEmpty == 0 && pageLoaded == 1">
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
                                                    <a href="javascript:;" @click.prevent="editRecord(record)" v-if="!$canWrite('SPECIAL_PRICES')"> {{ record.splprice_name }}</a>
                                                    <div v-else> {{ record.splprice_name }}</div>
                                                    <span class="form-text text-muted">
                                                    <span v-if="record.splprice_type == 1">{{$t('LBL_FLAT')}}</span> {{ record.splprice_amount }} <span v-if="record.splprice_type == 2">%</span> {{$t('LBL_OF_ON')}} <span v-if="record.splprice_include == 1">{{$t('LBL_PRODUCTS')}}</span> <span v-if="record.splprice_include == 2">{{$t('LBL_CATEGORIES')}}</span> <span v-if="record.splprice_include == 3">{{$t('LBL_BRANDS')}}</span> </span>
                                                </td>
                                                <td>
                                                    <label class="switch switch--sm" v-b-tooltip.hover :title="record.splprice_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') ">
                                                        <input type="checkbox" name="splprice_publish" v-model="record.splprice_publish" @change="onSwitchStatus($event, record.splprice_id)" :disabled ="!$canWrite('SPECIAL_PRICES')"/> <span></span> </label>
                                                </td>
                                                <td> 
                                                    <span class="date">
                                                        {{ record.splprice_startdate | formatDate }} - {{record.splprice_enddate | formatDate }}
                                                        <span class="form-text text-muted">
                                                            ({{record.splprice_startdate | formatTime }} - {{record.splprice_enddate | formatTime }})
                                                        </span>
                                                    </span>
                                                </td>
                                                <td>
                                                  <div class="actions">
                                                      <a class="btn btn-light btn-sm btn-icon" href="javascript:;" v-b-tooltip.hover :title="$t('TTL_EDIT')" @click.prevent="editRecord(record)" v-bind:class="[!$canWrite('SPECIAL_PRICES') ? 'disabled no-drop': '']"> 
                                                       <svg>   
                                                          <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'"></use>                               
                                                        </svg>
                                                        </a>
                                                      <a class="btn btn-light btn-sm btn-icon" href="javascript:;" v-b-tooltip.hover :title="$t('TTL_DELETE')" @click.capture="confirmDelete($event, record.splprice_id)" v-bind:class="[!$canWrite('SPECIAL_PRICES') ? 'disabled no-drop': '']">
                                                        <svg>   
                                                          <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>                               
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
                            <div class="get-started ">
                                <div class="get-started-arrow d-flex justify-content-end mb-5">
                                    <img :src="baseUrl+'/admin/images/get-started-graphic/get-started-arrow-head.svg'"/>
                                </div>
                                <div class="no-content d-flex text-center flex-column mb-7">
                                    <p>{{ $t('LBL_CLICK_THE') }} <a href="javascript:;" @click="openAddPage" class="btn btn-brand btn-small"><i class="fas fa-plus"></i> {{ $t('BTN_ADD') }} </a> {{ $t('LBL_BUTTON_TO_CREATE_SPECIAL_PRICES') }}</p>
                                </div>
                                <div class="get-started-media">
                                    <img :src="baseUrl+'/admin/images/get-started-graphic/get-started-special-price.svg'"/>
                                </div>
                            </div>
                        </div>
                        <loader v-if="loading"></loader>     
                        <div class="portlet__foot" v-if="records.length > 0">
                            <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
                        </div>
                    </div>
                </div>
                <div class="col-md-5" v-if="showEmpty == 0">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__head" v-if="selectedPage">
                            <div class="portlet__head-label">
                                <h3 class="portlet__head-title" v-if="selectedPage == 'editform'">
                                    {{ $canWrite('SPECIAL_PRICES') ? $t('LBL_EDITING') + ' -' : ''}} {{adminsData.splprice_name}}
                                </h3>
                                <h3 class="portlet__head-title" v-if="selectedPage == 'addform'">{{ $t('LBL_SPECIAL_PRICE_SETUP')}}</h3> 
                            </div>
                            <div class="portlet__head-toolbar" v-if="selectedPage == 'editform'">
                                <div class="portlet__head-actions" id="tooltip-target-1"><i class="fas fa-info-circle"></i></div>
                                <b-popover target="tooltip-target-1" triggers="hover" placement="top" class="popover-cover">
                                    <div class="list-stats">
                                        <div class="lable">
                                            <span class="stats_title">{{ $t('LBL_CREATED') }}:</span> 
                                            <span class="time">{{ createdByUser ? createdByUser+ ' |' : '' }} {{ createdAt | formatDateTime}}</span>
                                        </div>
                                        <div class="lable">
                                            <span class="stats_title">{{ $t('LBL_LAST_UPDATED') }}:</span>
                                            <span class="time">{{ updatedByUser ? updatedByUser+ ' |' : ''}} {{ updatedAt | formatDateTime}}</span>
                                        </div>
                                    </div>
                                </b-popover>
                            </div>
                        </div>
                        <div class="portlet__body" v-if="selectedPage" v-bind:class="[( !$canWrite('SPECIAL_PRICES')) ? 'disabledbutton': '']">
                            <input v-if="adminsData.splprice_id != ''" type="hidden" name="id" v-model="adminsData.splprice_id" />
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> {{ $t('LBL_SPECIALPRICE_NAME') }} <span class="required">*</span> </label>
                                        <input type="text" v-model="adminsData.splprice_name" name="splprice_name" v-validate="'required'" :data-vv-as="$t('LBL_SPECIALPRICE_NAME')" data-vv-validate-on="none" class="form-control" /> <span v-if="errors.first('splprice_name')" class="error text-danger">{{ errors.first('splprice_name') }}</span> </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> {{ $t('LBL_SPECIALPRICE_TYPE') }} <span class="required">*</span> </label>
                                        <select v-model="adminsData.splprice_type" name="splprice_type" v-validate="'required'" :data-vv-as="$t('LBL_SPECIALPRICE_TYPE')" data-vv-validate-on="none" class="form-control">
                                            <option value disabled>{{ $t('LBL_SELECT')}}</option>
                                            <option v-for="(type, key) in types" :key="'type'+key" :value="key">{{type}}</option>
                                        </select> <span v-if="errors.first('splprice_type')" class="error text-danger">{{ errors.first('splprice_type') }}</span> </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> {{ $t('LBL_SPECIALPRICE_AMOUNT') }} <span class="required">*</span> </label>
                                        <input type="text" v-model="adminsData.splprice_amount" @keypress="$onlyNumber" name="splprice_amount"  v-validate="'required|decimal:'+decimalValues+'|min_value:1'"  :data-vv-as="$t('LBL_SPECIALPRICE_AMOUNT')" data-vv-validate-on="none" class="form-control" /> <span v-if="errors.first('splprice_amount')" class="error text-danger">{{ errors.first('splprice_amount') }}</span> </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> {{ $t('LBL_SPECIALPRICE_START_DATE') }} <span class="required">*</span> </label>
                                        <date-pick v-model="adminsData.splprice_startdate" :pickTime="true"
                                        :parseDate="parseStartDate"
                                        :formatDate="formatStartDate"
                                         :format="$dateTimeFormat()" :isDateDisabled="isPastDate" :inputAttributes="{class: 'form-control', readonly: true}" class="d-block"></date-pick> <span v-if="errors.first('splprice_startdate')" class="error text-danger">{{ errors.first('splprice_startdate') }}</span> </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> {{ $t('LBL_SPECIALPRICE_END_DATE') }} <span class="required">*</span> </label>
                                        <date-pick v-model="adminsData.splprice_enddate" :pickTime="true"
                                        :parseDate="parseEndDate"
                                        :formatDate="formatEndDate"
                                        :format="$dateTimeFormat()" :isDateDisabled="isPastDate" :inputAttributes="{class: 'form-control', readonly: true}" class="d-block"></date-pick> <span v-if="errors.first('splprice_enddate')" class="error text-danger">{{ errors.first('splprice_enddate') }}</span> </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> {{ $t('LBL_SPECIALPRICE_INCLUDE') }} <span class="required">*</span> </label>
                                        <select v-model="adminsData.splprice_include" @change="selectInclude" name="splprice_include" v-validate="'required'" :data-vv-as="$t('LBL_SPECIALPRICE_INCLUDE')" data-vv-validate-on="none" class="form-control">
                                            <option value disabled>{{ $t('LBL_SELECT')}}</option>
                                            <option v-for="(include, key) in includes" :key="'includes'+key" :value="key">{{include}}</option>
                                        </select> <span v-if="errors.first('splprice_include')" class="error text-danger">{{ errors.first('splprice_include') }}</span> </div>
                                </div>
                            </div>
                            <div class="row" v-if="adminsData.splprice_include != ''">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label v-if="adminsData.splprice_include=='1'"> {{ $t('LBL_LINK_PRODUCTS') }} <span class="required">*</span> </label>
                                        <label v-if="adminsData.splprice_include=='2'"> {{ $t('LBL_LINK_CATEGORIES') }} <span class="required">*</span> </label>
                                        <label v-if="adminsData.splprice_include=='3'"> {{ $t('LBL_LINK_BRANDS') }} <span class="required">*</span> </label>
                                        <vue-tags-input class="vue-tags-style" v-model="includesTag" :tags="includesTags" :autocomplete-items="autocompleteRecords" @tags-changed="updateIncludesTags" :add-only-from-autocomplete="true" :placeholder="$t('PLH_START_TYPING_HERE')"  v-if="adminsData.splprice_include != '' && adminsData.splprice_include == '3'"></vue-tags-input>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row" v-if="adminsData.splprice_include != '' && adminsData.splprice_include == '2'">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <treeselect name="ptc_bpcat_id" v-model="includesTags" :multiple="true" :defaultExpandLevel="Infinity" :clearable="false" :isDefaultExpanded="true" :options="categories" :open-on-click="true" :close-on-select="true" :clear-on-select="true" :placeholder="$t('PLH_SELECT_CATEGORY')" v-validate="'required'" :data-vv-as="$t('LBL_CATEGORY')" data-vv-validate-on="none" /> </div>
                                </div>
                            </div>
                            <div class="row" v-if="adminsData.splprice_include != ''">
                                <div class="col-md-12">
                                    <label v-if=" adminsData.splprice_include != '1'">{{ $t('LBL_EXCLUDE_PRODUCTS') }}</label>
                                    <div class="form-group">
                                        <treeselect :multiple="true" :noOptionsText="$t('PLH_NO_PRODUCTS_AVAILABLE')" :options="options" :placeholder="$t('PLH_START_TYPING_HERE')" v-model="productValues" @search-change="productSearch" :clear-on-select="true"/> </div>
                                </div>
                            </div>

                            
                        </div>
                        <div class="portlet__body" v-if="selectedPage == ''">
                            <div class="no-data-found">
                                <div class="img"> <img :src="this.$noDataImage()" alt /> </div>
                                <div class="data">
                                    <p>{{ $t('LBL_USE_ICONS_FOR_ADVANCED_ACTIONS') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="portlet__foot" v-if="selectedPage != ''">
                            <div class="row">
                                <div class="col">
                                    <button type="reset" class="btn btn-secondary" @click="cancel()">{{ $t('BTN_DISCARD')}}</button>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="validateRecord()" :disabled="!isComplete || clicked==1 || (!$canWrite('SPECIAL_PRICES'))" v-if="selectedPage == 'addform'" v-bind:class="clicked==1 && 'sgb-is-loading'">{{ $t('BTN_SPECIALPRICE_SAVE') }}</button>
                                    <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="validateRecord()" :disabled="!isComplete || clicked==1 || (!$canWrite('SPECIAL_PRICES'))" v-if="selectedPage == 'editform'" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_SPECIALPRICE_UPDATE') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <DeleteModel :deletePopText="$t('LBL_SPECIALPRICE_DELETE_TEXT')" :recordId="recordId" @deleted="deleteRecord(recordId)"></DeleteModel>
            </div>
        </div>
    </div>
</template>
<script>

import DatePick from "vue-date-pick";
import "vue-date-pick/dist/vueDatePick.css";
import fecha from 'fecha';

import VueTagsInput from "@johmun/vue-tags-input";
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";

const tableFields = {
  splprice_id: "",
  splprice_name: "",
  splprice_type: "",
  splprice_amount: "",
  splprice_startdate: "",
  splprice_enddate: "",
  splprice_include: ""
};
const searchFields = {
  search: ""
};
export default {
  components: {
    DatePick,
    VueTagsInput,
    Treeselect
  },
  data: function() {
    return {
      baseUrl: baseUrl,
      adminsData: tableFields,
      selectedPage: false,
      records: [],
      recordId: "",
      modelId: "deleteModel",
      search: "",
      searchData: searchFields,
      decimalValues: decimalValues,
      pagination: [],
      clicked: 0,
      loading: false,
      types: [],
      includes: [],
      includesTag: "",
      includesTags: [],
      autocompleteRecords: [],
      excludesTag: "",
      excludesTags: [],
      productValues: [],
      options: [],
      autocompleteProducts: [],
      includedCategories: [],
      categories: [],         
      pageLoaded: 0,          
      showEmpty: 1
    };
  },
  watch: {
    includesTag: "initItemsIncludes",
    excludesTag: "initItemsExcludes",
     $route: "refreshedSearchData"
  },
  computed: {
    isComplete() {
        if( this.adminsData.splprice_include == 1){
            return (
                this.adminsData.splprice_name &&
                this.adminsData.splprice_type &&
                this.adminsData.splprice_amount &&
                this.adminsData.splprice_startdate &&
                this.adminsData.splprice_enddate &&
                this.productValues.length > 0
            );
        }else if(this.adminsData.splprice_include == 2){
            return (
                this.adminsData.splprice_name &&
                this.adminsData.splprice_type &&
                this.adminsData.splprice_amount &&
                this.adminsData.splprice_startdate &&
                this.adminsData.splprice_enddate &&
                this.includesTags.length > 0
            );
        }else if(this.adminsData.splprice_include == 3){
            return (
                this.adminsData.splprice_name &&
                this.adminsData.splprice_type &&
                this.adminsData.splprice_amount &&
                this.adminsData.splprice_startdate &&
                this.adminsData.splprice_enddate &&
                this.includesTags.length > 0
            );
        }else{
           return (
                this.adminsData.splprice_name &&
                this.adminsData.splprice_type &&
                this.adminsData.splprice_amount &&
                this.adminsData.splprice_startdate &&
                this.adminsData.splprice_enddate
            ); 
        }
        
    }
  },
  methods: {
     refreshedSearchData() {
      if (this.$route.query.s) {
        this.searchData.search = this.$route.query.s;
        }
         this.pageRecords(1, '', true);

    },
    parseStartDate(dateString, format) {
        return fecha.parse(dateString, format);
    },
    formatStartDate(dateObj, format) {
        return fecha.format(dateObj, format);
    },
    parseEndDate(dateString, format) {
        return fecha.parse(dateString, format);
    },
    formatEndDate(dateObj, format) {
        return fecha.format(dateObj, format);
    },
    isPastDate: function(date) {
      const currentDate = new Date().setDate(new Date().getDate() - 1);
      return date < currentDate;
    },
    updateIncludesTags(newTags) {
      this.autocompleteRecords = [];
      this.includesTags = newTags;
    },
    async initItemsIncludes() {
      if (this.includesTag.length < 2) return;
      this.$http
        .post(
          adminBaseUrl +
            "/special-prices/search?query=" +
            this.includesTag +
            "&include=" +
            this.adminsData.splprice_include
        )
        .then(response => {
          this.autocompleteRecords = this.setTags(response.data.data);
        });
    },
    updateExcludesTags(newTags) {
      this.autocompleteProducts = [];
      this.excludesTags = newTags;
    },
    async initItemsExcludes() {
      if (this.excludesTag.length < 2) return;

      let formData = this.$serializeData({
        type: this.adminsData.splprice_include,
        selected: JSON.stringify(this.includesTags)
      });
      this.$http
        .post(
          adminBaseUrl +
            "/special-prices/search?query=" +
            this.excludesTag +
            "&include=1",
          formData
        )
        .then(response => {
          this.autocompleteProducts = this.setTags(response.data.data);
        });
    },    
    setTags: function(response) {
      return response.map(a => {
        return {
          text: a.record_title,
          id: a.record_id
        };
      });
    },
    setProductTags: function(response) {
      let thisObj = this;
      return response.map(a => {
        if (a.varients.length > 0) {
          return {
            label: thisObj.$truncateTxt(a.record_title, 70),
            id: a.record_id,
            children: this.bindChildrens(a.record_title, a.varients)
          };
        } else {
          return {
            label: a.record_title,
            id: a.record_id
          };
        }
      });
    },
    setEditProductTags: function(response) {
      let id = "";
      let title = "";
      return response.map(a => {
        title = a.record_title;
        id = a.record_id;
        if (a.pov_display_title != null) {
          title =
            a.record_title + " - " + a.pov_display_title.replace("_", " | ");
          id = a.pov_code;
        }
        return {
          label: title,
          id: id
        };
      });
    },
    setEditSelectedTags: function(response) {
      let id = "";
      response.map(a => {
        id = a.record_id;
        if (a.pov_display_title != null) {
          id = a.pov_code;
        }
        this.productValues.push(id);
      }); 
    },
    bindChildrens: function(title, varients) {
      let thisObj = this;
      return varients.map(a => {
        return {
          label: thisObj.$truncateTxt(title, 50) + " " + a.pov_display_title.replace("_", " | "),
          id: a.pov_code
        };
      });
    },
    selectInclude: function(event) {
      this.adminsData.splprice_include = event.target.value;
      this.includesTags = [];
      this.excludesTags = [];
      this.productValues = [];
      this.options = [];
    },
    currentPage: function(page) {
      this.pageRecords(page);
    },
    searchRecords: function() {
      this.pageRecords(this.pagination.current_page);
    },
    tabFilter: function(type) {
      this.selectedPage = '';
      this.pageRecords(1, type);
    },
    pageRecords: function(pageNo, type, pageLoad = false) {
      this.loading = pageLoad;
      if (typeof type !== "undefined") {
        Object.assign(this.searchData, {
          type: type
        });
      }
      let formData = this.$serializeData(this.searchData);
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(adminBaseUrl + "/special-prices/list?page=" + pageNo, formData)
        .then(response => {
            if (response.data.status == false) {
                toastr.error(response.data.message, '', toastr.options);
                this.$router.push({ name: 'unAuthorised' });
                return;
            }
          this.records = response.data.data.specialprices.data;
          this.pagination = response.data.data.specialprices;
          this.types = response.data.data.types;
          this.includes = response.data.data.includes;
          this.categories = response.data.data.categories;
          this.loading = false;
          this.showEmpty = response.data.data.showEmpty;   
          this.pageLoaded = 1;   
        });
    },
    openAddPage: function() {
      this.emptyFormValues();
      this.selectedPage = "addform";
      if(this.searchData.search != ""){
          this.searchData.search = "";
          this.pageRecords();
      }    
      this.showEmpty = 0;
    },

    editRecord: function(record) {
      this.emptyFormValues();
      this.emptyUpdatedFieldValue();
      this.adminsData.splprice_id = record.splprice_id;
      this.adminsData.splprice_name = record.splprice_name;
      this.adminsData.splprice_type = record.splprice_type;
      this.adminsData.splprice_amount = record.splprice_amount;
      this.adminsData.splprice_startdate = moment.utc(String(record.splprice_startdate)).tz(window.localStorage.getItem('timezone')).format(this.$dateTimeFormat());
      this.adminsData.splprice_enddate = moment.utc(String(record.splprice_enddate)).tz(window.localStorage.getItem('timezone')).format(this.$dateTimeFormat());
      this.adminsData.splprice_include = record.splprice_include;
      this.options = this.setProductTags(record.excluded);
      this.setEditSelectedTags(record.excluded);
      this.selectedPage = "editform";
        if (record.created_at != null && "admin_username" in record.created_at) {
            this.createdByUser = record.created_at.admin_username;
        }
        if (record.updated_at != null && "admin_username" in record.updated_at) {
            this.updatedByUser = record.updated_at.admin_username;
        }
        this.createdAt = record.splprice_created_at ? record.splprice_created_at : '';
        this.updatedAt = record.splprice_updated_at ? record.splprice_updated_at : '';
      this.$http
        .get(
          adminBaseUrl +
            "/special-prices/included?type=" +
            record.splprice_include +
            "&id=" +
            record.splprice_id
        )
        .then(response => {
          if (record.splprice_include == 1) {
            this.options = this.setEditProductTags(response.data.data);
            this.setEditSelectedTags(response.data.data);
          } else if (record.splprice_include == 2) {
            // this.includesTags.push(response.data.data);
            this.includesTags = response.data.data.map(a => {
              return a;
            });
          } else {
            this.includesTags = this.setTags(response.data.data);
          }
        });
    },
    emptyFormValues: function() {
      this.adminsData = {
        splprice_id: "",
        splprice_name: "",
        splprice_type: "",
        splprice_amount: "",
        splprice_startdate: "",
        splprice_enddate: "",
        splprice_include: ""
      };
      this.productValues = [];
      this.options = [];
      this.errors.clear();
    },
    validateRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          let input = this.adminsData;
          this.clicked = 1;
          if (input.splprice_id != "") {
            this.updateRecord(this.adminsData);
          } else {
            this.saveRecord(input);
          }
        } else {
          this.clicked = 0;
        }
      });
    },
    productSearch: function(includesTag) {
      if (includesTag.length < 2) return;
      let ids = this.includesTags;
    if(this.adminsData.splprice_include == 3){
         let brandIds = [];
         Object.keys(this.includesTags).forEach(key => {
           brandIds.push(this.includesTags[key].id);
      });
      ids = brandIds;
    }
      let formData = this.$serializeData({
        type: this.adminsData.splprice_include,
        selected: JSON.stringify(ids)
      });
      this.$http
        .post(
          adminBaseUrl +
            "/special-prices/search?query=" +
            includesTag +
            "&include=1",
          formData
        )
        .then(response => {
          this.options = this.setProductTags(response.data.data);
        });
    },
    updateRecord: function(input) {
      input.splprice_includes = JSON.stringify(this.includesTags);
      input.splprice_excludes = JSON.stringify(this.excludesTags);
      input.productValues = JSON.stringify(this.productValues);
      let formData = this.$serializeData(input);
      formData.append("_method", "PUT");
      this.$http
        .post(adminBaseUrl + "/special-prices/" + input.splprice_id, formData)
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
            this.emptyFormValues();
            this.cancel();
          },
          response => {
            //error
            this.clicked = 0;
            this.validateErrors(response);
          }
        );
    },
    confirmDelete: function(event, dataid) {
      event.stopPropagation();
      this.recordId = dataid;
      this.$bvModal.show(this.modelId);
    },
    deleteRecord: function(recordId) {
      this.$http
        .delete(adminBaseUrl + "/special-prices/" + recordId)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords(this.pagination.current_page);
          this.$bvModal.hide(this.modelId);
          this.selectedPage = false;
        });
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
      input.splprice_includes = JSON.stringify(this.includesTags);
      input.splprice_excludes = JSON.stringify(this.excludesTags);
      input.productValues = JSON.stringify(this.productValues);
      let formData = this.$serializeData(input);
      this.$http.post(adminBaseUrl + "/special-prices", formData).then(
        response => {
          //success
          this.clicked = 0;
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords(this.pagination.current_page);
          this.selectedPage = false;
        },
        response => {
          //error
          this.clicked = 0;
          this.validateErrors(response);
        }
      );
    },
    onSwitchStatus: function(event, id) {
      let formData = this.$serializeData({
        status: event.target.checked
      });
      this.$http
        .post(adminBaseUrl + "/special-prices/status/" + id, formData)
        .then(response => {
            if (response.data.status == false) {
                toastr.error(response.data.message, "", toastr.options);
                return;
            }
            toastr.success(response.data.message, "", toastr.options);
        });
    },
    cancel: function() {
      this.selectedPage = false;
    },
    emptyUpdatedFieldValue : function() {
        this.createdByUser = '';
        this.updatedByUser = '';
        this.updatedAt = '';
        this.createdAt = '';
    }
  },
  mounted: function() {
    this.searchData.search = "";
    this.refreshedSearchData();
    }
};
</script>