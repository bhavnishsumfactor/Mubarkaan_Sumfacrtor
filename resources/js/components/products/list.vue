<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t("LBL_PRODUCTS") }}</h3>
          <div class="subheader__breadcrumbs">
            <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">
              {{ $t("LBL_PRODUCTS") }}
            </span>
          </div>
        </div>
        <div class="subheader__toolbar">
          <router-link
            :to="{ name: 'productCreate' }"
            class="btn btn-white"
            v-bind:class="[(!$canWrite('PRODUCTS')) ? 'disabledbutton': '']"
          >
            <i class="fas fa-plus"></i>
            {{ $t("BTN_ADD") }}
          </router-link>
        </div>
      </div>
    </div>
    <div class="container">
      <div class id="manage-admins">
        <div class="row">
          <div class="col-xl-12">
            <!--begin::Portlet-->
            <div class="portlet portlet--height-fluid portlet--tabs">
              <div
                class="portlet__head"
                v-if="
                  (records.length != 0 ||
                    searchKeywords.length != 0 ||
                      listingType != '' ||
                    productSearch != '') &&
                  pageLoad == 1
                "
              >
                <div class="portlet__head-toolbar">
                  <ul
                    class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold"
                    role="tablist"
                  >
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        v-bind:class="[
                          customSearch != 1 && tabType == 0 ? 'active' : '',
                        ]"
                        @click="switchTab(0)"
                        data-toggle="tab"
                        href="javascript:;"
                        role="tab"
                        aria-selected="false"
                      >{{$t('LNK_PRODUCT_ALL')}}</a>
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        v-bind:class="[
                          customSearch == 1 && tabType == 0 ? 'active' : '',
                        ]"
                        @click="switchTab(1)"
                        data-toggle="tab"
                        href="javascript:;"
                        role="tab"
                        aria-selected="true"
                        v-if="searchKeywords.length > 0 || Object.keys(listingType).length != 0"
                      >{{$t('LNK_PRODUCT_CUSTOM_SEARCH')}}</a>
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        v-bind:class="[tabType == 1 ? 'active' : '']"
                        @click="switchTab(3)"
                        data-toggle="tab"
                        href="javascript:;"
                        role="tab"
                        aria-selected="true"
                        v-if="alertRecords != 0"
                      >{{$t('LNK_STOCK_QUANTITY_ALERT')}}</a>
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        v-bind:class="[tabType == 2 ? 'active' : '']"
                        @click="switchTab(4)"
                        data-toggle="tab"
                        href="javascript:;"
                        role="tab"
                        aria-selected="true"
                        v-if="errorRecords != 0"
                      >{{$t('LNK_PRODUCT_WITH_ERRORS')}}</a>
                    </li>
                  </ul>
                </div>
              </div>
             
              <div
                class="portlet__body pb-0 bg-gray flex-grow-0"
                v-if="
                  (records.length != 0 ||
                    searchKeywords.length != 0 ||
                    listingType != '' ||
                    productSearch != '') &&
                  pageLoad == 1
                "
              >
                <div class="form">
                  <div class="row">
                    <div class="col" v-if="tabType == 0">
                      <div class="form-group product-filter-tag">
                        <div class="input-group input-icon input-icon--right">
                          <input
                            type="text"
                            class="form-control"
                            @keypress.13.prevent="validateSearch"
                            name="productSearch"
                            v-model="productSearch"
                            v-validate="'required'"
                            :data-vv-as="$t('LBL_PRODUCT_SEARCH')"
                            data-vv-validate-on="none"
                            :placeholder="$t('PLH_PRODUCT_SEARCH')"
                          />
                          <span
                            class="input-icon__icon input-icon__icon--right"
                            v-if="productSearch != ''"
                            @click="productSearch = ''"
                          >
                            <span>
                              <i class="fas fa-times"></i>
                            </span>
                          </span>
                        </div>
                        <span
                          v-if="errors.first('productSearch')"
                          class="error text-danger"
                        >{{ errors.first("productSearch") }}</span>

                      
                        <ul class="tagify-custom my-2" v-if="customSearch == 1 && listingType !=''">
                          <li
                            v-for="(searchKeyword,
                            searchIndex) in searchKeywords"
                            :key="searchIndex"
                          >
                            <span>{{ searchKeyword }}</span>
                            <a
                              href="javascript:;"
                              @click="removeKeyword(searchIndex)"
                              class="remove"
                            >
                              <i class="fas fa-times"></i>
                            </a>
                          </li>
                        
                           <li
                            v-if="listingType && listingType.type"

                          >
                            <span>{{ listingType.name }}</span>
                            <a
                              href="javascript:;"
                             class="remove"
                             @click="removeKeyword(0, true)"
                            >
                              <i class="fas fa-times"></i>
                            </a>
                          </li>
                    
                          <li class="clear"> <button
                          class="btn btn-link"
                          @click="clearSearch()"
                          v-if="
                            (searchKeywords.length > 0 || Object.keys(listingType).length != 0) &&
                            customSearch == 1 &&
                            tabType == 0
                          "
                        >
                          
                        {{$t('LBL_CLEAR_ALL')}}</button></li>
                        </ul>
                        
                      </div>
                    </div>
                    <div class="col-auto">
                      <div class="form-group">
                        <div class="actions">
                        <span v-if="selected.length > 0">
                          <button
                            class="btn btn-outline-secondary btn-icon"
                            v-bind:class="[ !$canWrite('PRODUCTS') ? 'disabledbutton' : '']"
                            @click="updateAllRecord('publish')"
                          >{{$t('BTN_PUBLISH')}}</button>
                          <button
                            class="btn btn-outline-secondary btn-icon"
                            v-bind:class="[ !$canWrite('PRODUCTS') ? 'disabledbutton' : '']"
                            @click="updateAllRecord('unpublish')"
                          >{{$t('BTN_UNPUBLISH')}}</button>
                          <button
                            class="btn btn-outline-secondary btn-icon"
                            v-bind:class="[ !$canWrite('PRODUCTS') ? 'disabledbutton' : '']"
                            @click="updateAllRecord('delete')"
                          >
                            <svg>   
                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"></use>                               
                                </svg>
                          </button>
                        </span>
                        </div>
                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr class="m-0" />
              <div class="portlet__body portlet__body--fit" v-if="records.length > 0">
                <!--begin::Section-->
                <div class="section">
                  <div class="section__content" id="local_data">
                    <table class="table table-data table-justified">
                      <thead>
                        <tr>
                          <th class="th-sort" width="5%">
                            <span>
                              <label class="checkbox" v-if="tabType != 2">
                                <input type="checkbox" v-model="allSelected" @click="selectAll" />
                                <span></span>
                              </label>
                            </span>
                          </th>
                          <th class="th-sort" width="2%">
                            <span>#</span>
                          </th>
                          <th class="th-sort" width="5%">
                            <span></span>
                          </th>
                          <th
                            class="th-sort"
                            width="35%"
                            @click="sortBy('prod_name')"
                            v-bind:class="[
                              sortingBy == 'prod_name' ? 'th-sorted' : '',
                            ]"
                          >
                            <span> {{$t('LBL_PRODUCT_NAME')}}
                              <i
                                class="fas"
                                v-bind:class="[
                                  sortingType == 'asc'
                                    ? 'fa-arrow-up'
                                    : 'fa-arrow-down',
                                ]"
                                v-if="sortingBy == 'prod_name'"
                              ></i>
                            </span>
                          </th>
                          <th
                            class="th-sort"
                            width="8%"
                            @click="sortBy('prod_type')"
                            v-bind:class="[
                              sortingBy == 'prod_type' ? 'th-sorted' : '',
                            ]"
                          >
                            <span>
                              {{$t('LBL_PRODUCT_TYPE')}}
                              <i
                                class="fas"
                                v-bind:class="[
                                  sortingType == 'asc'
                                    ? 'fa-arrow-up'
                                    : 'fa-arrow-down',
                                ]"
                                v-if="sortingBy == 'prod_type'"
                              ></i>
                            </span>
                          </th>
                          <th class="th-sort" width="11%">
                            <span>{{$t('LBL_PRODUCT_VARIANTS')}}</span>
                          </th>
                          <th
                            class="th-sort"
                            width="8%"
                            @click="sortBy('qty')"
                            v-bind:class="[sortingBy == 'qty' ? 'th-sorted' : '']"
                          >
                            <span>
                              {{$t('LBL_PRODUCT_INVENTORY')}}
                              <i
                                class="fas"
                                v-bind:class="[
                                  sortingType == 'asc'
                                    ? 'fa-arrow-up'
                                    : 'fa-arrow-down',
                                ]"
                                v-if="sortingBy == 'qty'"
                              ></i>
                            </span>
                          </th>
                          <th
                            class="th-sort"
                            width="8%"
                            @click="sortBy('price')"
                            v-bind:class="[
                              sortingBy == 'price' ? 'th-sorted' : '',
                            ]"
                          >
                            <span>
                              {{$t('LBL_PRODUCT_PRICE')}}
                              <i
                                class="fas"
                                v-bind:class="[
                                  sortingType == 'asc'
                                    ? 'fa-arrow-up'
                                    : 'fa-arrow-down',
                                ]"
                                v-if="sortingBy == 'price'"
                              ></i>
                            </span>
                          </th>
                          <th
                            class="th-sort"
                            width="8%"
                            @click="sortBy('prod_published')"
                            v-bind:class="[
                              sortingBy == 'prod_published' ? 'th-sorted' : '',
                            ]"
                          >
                            <span>
                              {{ $t('LBL_PUBLISH') }}
                              <i
                                class="fas"
                                v-bind:class="[
                                  sortingType == 'asc'
                                    ? 'fa-arrow-up'
                                    : 'fa-arrow-down',
                                ]"
                                v-if="sortingBy == 'prod_published'"
                              ></i>
                            </span>
                          </th>
                          <th class="th-sort" width="10%">
                            <span></span>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                          <tr v-for="(record, index) in records" :key="index" v-bind:class="checkingChecked(record.prod_id) ? 'is-selected': ''">
                              <td class="th-sort">
                              <span v-if="productError(record) != true">
                                  <label class="checkbox" v-if="tabType != 2">
                                  <input
                                      type="checkbox"
                                      v-model="selected"
                                      :value="record.prod_id"
                                      @click="unSelectAll"
                                  />
                                  <span></span>
                                  </label>
                              </span>
                              <span class="disabledbutton" v-else>
                                  <label class="checkbox" v-if="tabType != 2">
                                  <input type="checkbox" />
                                  <span></span>
                                  </label>
                              </span>
                              </td>
                              <td>{{ pagination.from + index }}</td>
                              <td>
                              <span>
                                  <img
                                  class="pro-img"
                                  :src="
                                      $productImage(
                                      record.prod_id,
                                      record.pov_code,
                                      $timestamp(record.prod_updated_on),
                                      '47-62'
                                      )
                                  "
                                  />
                              </span>
                              </td>
                              <td>
                              <a
                                  href="javascript:;"
                                  @click.prevent="openViewMode(record)"
                                  v-if="!$canWrite('PRODUCTS')"
                              >{{ record.prod_name }}</a>
                              <div v-else>{{ record.prod_name }}</div>
                              </td>
                              <td>{{ types[record.prod_type] }}</td>
                              <td>
                              <span v-if="record.varients.length != 0">
                                  <p v-if="(!$canWrite('PRODUCTS'))">{{ record.varients.length }}</p>
                                  <a
                                  href="javascript:;"
                                  @click="($canWrite('PRODUCTS')) ? displayProduct(record) : ''"
                                  v-if="($canWrite('PRODUCTS'))"
                                  >{{ record.varients.length }}</a>
                              </span>
                              <span v-else>{{ record.varients.length }}</span>
                              </td>
                              <td>
                              <span v-if="digitalEnable == 1">
                                  {{
                                  record.qty
                                  }}
                              </span>
                              <span v-else>
                                  <p v-if="(!$canWrite('PRODUCTS'))">{{ (record.qty) ? record.qty: 0 }}</p>
                                  <a
                                  href="javascript:;"
                                  @click="($canWrite('PRODUCTS')) ? updateProduct(record,'inventory'): ''"
                                  v-if="($canWrite('PRODUCTS'))"
                                  >
                                  <span v-if="record.qty">{{record.qty}}</span>
                                  <span v-else>0</span>
                                  </a>
                              </span>
                              </td>
                              <td>
                              <span v-if="digitalEnable == 1">
                                  {{ currencySymbol
                                  }}{{ record.price | numberFormat }}
                              </span>
                              <span v-else>
                                  <p
                                  v-if="!($canWrite('PRODUCTS'))"
                                  >{{currencySymbol}}{{ record.price | numberFormat }}</p>
                                  <a
                                  href="javascript:;"
                                  @click="($canWrite('PRODUCTS')) ? updateProduct(record,'price'): ''"
                                  v-if="($canWrite('PRODUCTS'))"
                                  >{{currencySymbol}}{{ record.price | numberFormat }}</a>
                              </span>
                              </td>
                              <td>
                              <span class="d-flex align-items-center">
                                  <label
                                  class="switch switch--sm m-0"
                                  v-bind:class="[ productError(record) == true ? 'disabledbutton': '']"
                                  >
                                  <input
                                      type="checkbox"
                                      v-model="record.prod_published"
                                      @change="onSwitchStatus($event, record.prod_id)"
                                      :disabled="!$canWrite('PRODUCTS')"
                                  />
                                  <span
                                      v-b-tooltip.hover
                                      :title=" record.prod_published == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH')"
                                  ></span>
                                  </label>
                                  <a
                                  class="toolTip font-danger"
                                  href="javascript:;"
                                  v-if="productError(record) == true"
                                  v-b-tooltip.hover
                                  :title="tolltipMsg(record)"
                                  >
                                  <i class="fas fa-info-circle"></i>
                                  </a>
                              </span>
                              </td>
                              <td data-field="Actions" data-autohide-disabled="false">
                              <div class="actions">
                                  <router-link
                                  :to="{
                                      name: 'editProduct',
                                      params: { id: record.prod_id },
                                  }"
                                  class="btn btn-light btn-sm btn-icon"
                                  v-b-tooltip.hover
                                  :title="$t('TTL_EDIT')"
                                  v-bind:class="[!$canWrite('PRODUCTS') ? 'disabled no-drop': '']"
                                  >
                                  <svg>   
                                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                  </svg>
                                  </router-link>
                                  <a
                                  href="javascript:;"
                                  @click="confirmDelete(record.prod_id)"
                                  v-b-tooltip.hover
                                  :title="$t('TTL_DELETE')"
                                  class="btn btn-light btn-sm btn-icon"
                                  v-bind:class="[!$canWrite('PRODUCTS') ? 'disabled no-drop': '']"
                                  >
                                  <svg>   
                                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"></use>                               
                                  </svg>
                                  </a>
                                  <div class="dropdown d-inline">
                                  <a
                                      data-toggle="dropdown"
                                      class="btn btn-light btn-sm btn-icon"
                                      aria-expanded="false"
                                  >
                                    <svg>   
                                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#more-option'" ></use>                               
                                  </svg>
                                  </a>
                                  <div
                                      class="dropdown-menu dropdown-menu-right"
                                      x-placement="bottom-end"
                                  >
                                      <a
                                      :href="baseUrl + '/product/' + record.prod_id"
                                      target="_blank"
                                      class="dropdown-item"
                                      v-if="productError(record) == false"
                                      >{{ $t("LNK_VIEW_PRODUCT") }}</a>
                                      <router-link
                                      :to="{
                                          name: 'productReviews',
                                          params: { id: record.prod_id },
                                          query: { page: pagination.current_page },
                                      }"
                                      class="dropdown-item"
                                      >{{ $t("LNK_VIEW_REVIEWS") }}</router-link>
                                  </div>
                                  </div>
                              </div>
                              </td>
                          </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div
                class="portlet__body"
                v-if="
                  records.length == 0 &&
                  searchKeywords.length == 0 &&
                  pageLoad == 1
                "
              >
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
                      {{ $t('LBL_CLICK_THE') }}
                      <router-link
                        :to="{ name: 'productCreate' }"
                        class="btn btn-brand btn-small"
                      >
                        <i class="fas fa-plus"></i>
                        {{ $t("BTN_ADD") }}
                      </router-link>{{ $t('LBL_BUTTON_TO_CREATE_PRODUCTS') }}
                    </p>
                  </div>
                  <div class="get-started-media">
                    <img
                      :src="
                        baseUrl +
                        '/admin/images/get-started-graphic/get-started-blog-categories.svg'
                      "
                    />
                  </div>
                </div>
              </div>
              <div v-else>
                <loader v-if="loading"></loader>
                <noRecordsFound v-if="loading == false && this.records.length == 0"></noRecordsFound>
              </div>
              <div class="portlet__foot" v-if="this.records.length > 0">
                <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
              </div>
              <!--end::Section-->
            </div>
            <!--end::Form-->
          </div>
          <!--end::Portlet-->
        </div>
      </div>
      <DeleteModel
        :deletePopText="$t('LBL_DELETE_PRODUCT_TEXT')"
        :recordId="recordId"
        @deleted="deleteRecord(recordId)"
      ></DeleteModel>
      <b-modal id="productUpdateModel" centered title="BootstrapVue" :hide-footer="editType != 'inventory' && editType != 'price'">
        
        <template v-slot:modal-header="{ close } " >
          <h5
            class="modal-title"
            v-if="editType == 'inventory' || editType == 'price'"
          >{{$t("LBL_UPDATE") + " " + editType + " for " +   prodName }}</h5>
          <h5 class="modal-title" v-if="editType == 'optionPrice'">{{ prodName + " - " + $t("LBL_VARIANTS") }}</h5>
          <button type="button" class="close" @click="close()"></button>
        </template>
        <perfect-scrollbar :style="'height:200px;'">
          <div class="p-4" v-if="editType == 'inventory' || editType == 'price'">
            <div class="form-group row" v-if="editType != 'optionPrice'">
              <label class="col-md-4 col-form-label">
                <strong>{{ $t("LBL_VARIANT_NAME") }}</strong>
              </label>
              <label class="col-md-4 col-form-label" v-if="editType == 'inventory'">
                <strong>{{ $t("LBL_PRODUCT_INVENTORY") }}</strong>
              </label>
              <label class="col-md-4 col-form-label" v-if="editType == 'price'">
                <strong>{{ $t("LBL_PRODUCT_PRICE") }}</strong>
              </label>
            </div>
            <div class="form-group row" v-for="(product, key) in editProduct" :key="key">
              <label class="col-md-4 col-form-label">
                {{ product.name.replace(/_/g, " / ") }}
                <span
                  v-if="product.default && product.default == 1"
                >({{$t('LBL_DEFAULT')}})</span>
              </label>
              <div
                class="col-md-8"
                v-bind:class="[
                  product.condistion.substractStock < product.value &&
                  tabType == 1
                    ? 'disabledbutton'
                    : '',
                ]"
              >
                
                            
                <input
                  type="text"
                  v-model="product.value"
                  :name="product.name"
                  @keypress="$onlyNumber"
                  v-validate="
                    'required|decimal:' +
                    product.condistion.decimal +
                    '|min_value:' +
                    product.condistion.min
                  "
                  :maxlength="editType == 'price' ? 9 : 4"
                  :data-vv-as="$t(product.name.replace(/_/g, ' '))"
                  data-vv-validate-on="none"
                  class="form-control"
                />
                <span
                  v-if="errors.first(product.name)"
                  class="error text-danger"
                >{{ errors.first(product.name) }}</span>
              </div>
            </div>
          </div>
          <ul class="list-group">
            <li
              class="list-group-item list-group-item-sticky d-flex justify-content-between align-items-center"
              v-if="editType == 'optionPrice'"
            >
              <strong>{{ $t("LBL_VARIANT_NAME") }}</strong>
              <span>
                <strong>{{ $t("LBL_PRODUCT_PRICE") }}</strong>
              </span>
            </li>
            <li
              class="list-group-item d-flex justify-content-between align-items-center"
              v-for="(products, index) in displayProducts"
              :key="index"
              v-bind:class="[products.pov_publish == 0 ? 'disabledbutton' : '']"
            >
              {{ products.pov_display_title.replace(/_/g, " / ") }}
              {{ products.pov_default == 1 ? "(" + $t('LBL_DEFAULT') + ")" : "" }}
              <span
                class="yk-price-badge"
              > 
                {{ currencySymbol
                }}{{ products.pov_price | numberFormat }}
              </span>
            </li>
          </ul>
        </perfect-scrollbar>
        <template v-slot:modal-footer>
          <button
            type="button"
            v-if="editType == 'inventory' || editType == 'price'"
            class="btn btn-brand"
            @click="validateRecord"
          >{{ $t("BTN_SAVE") }}</button>
        </template>
      </b-modal>
    </div>
  </div>
</template>
<script>
const searchFields = {
  post_title: ""
};
export default {
  data: function() {
    return {
      baseUrl: baseUrl,
      records: [],
      adminData: [],
      recordId: "",
      currencySymbol: currencySymbol,
      prod_published: "",
      pagination: [],
      loading: false,
      modelId: "deleteModel",
      productModelId: "productUpdateModel",
      searchData: searchFields,
      productSearch: "",
      sortingType: "",
      sortingBy: "",
      customSearch: 0,
      searchKeywords: [],
      allSelected: false,
      brandRequired: 0,
      tabType: 0,
      digitalEnable: "",
      selected: [],
      types: [],
      editProduct: [],
      listingType: {},
      displayProducts: [],
      editType: "",
      prodName: "",
      pageId: "",
      pageLoad: 0,
      alertRecords: 0,
      errorRecords: 0,
    };
  },
  watch: {
    $route: "refreshedSearchData"
  },
  methods: {
    refreshedSearchData() {
      this.customSearch = 0;
        if (this.$route.query.s) {
            this.productSearch = this.$route.query.s;
            this.errors.clear();
            if (this.searchKeywords.includes(this.productSearch) === false) {
              this.searchKeywords.push(this.productSearch);
              this.customSearch = 1;
              this.sortBy();
              this.pageRecords(1);
            }
            this.productSearch = "";
            return false;
        }
        if (typeof this.$route.query.brand_id != "undefined") {
            this.customSearch = 1;
            this.listingType = {'type': 'brand_id', 'id': this.$route.query.brand_id, 'name' : ''};
            this.pageRecords(1);
            return false;
        }
        if (typeof this.$route.query.category_id != "undefined") {
            this.customSearch = 1;
            this.listingType = {'type': 'category_id', 'id': this.$route.query.category_id, 'name' : ''};
            this.pageRecords(1);
            return false;
        }
        let searchType = localStorage.getItem('productSearchType');
        let productSearch = JSON.parse(localStorage.getItem('productSearch'));
        if((productSearch && productSearch.length > 0) || (searchType && searchType != "{}")){
        
            if(productSearch.length > 0){
                this.customSearch = 1;
                this.searchKeywords = productSearch;
            }
            if(searchType && searchType && searchType != "{}"){
            this.customSearch = 1;
            this.listingType  =  JSON.parse(searchType);
            }
            
            this.pageRecords(1);
            return false;
        }
        if (this.pageId != "") {
            this.pageRecords(this.pageId);
        } else {
            this.pageRecords(1);
        }
    },
    selectAll: function() {
        this.selected = [];
        this.allSelected = !this.allSelected;
        if (this.allSelected) {
            Object.keys(this.records).forEach(key => {
            if (this.productError(this.records[key]) == true) {
                return;
            }
            this.selected[key] = this.records[key].prod_id;
            });
        }        
    },
    pageRecords: function(pageNo) {
      
      this.records = [];
      this.loading = true;
      let formData = this.$serializeData({
        "sorting-by": this.sortingBy,
        "sorting-type": this.sortingType,
        "records-type": this.tabType,
        
      });
      if (this.customSearch == 1) {
        formData.append("search", this.searchKeywords);
      
     }
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
        
      }
      
        if(this.listingType && this.listingType.type && this.customSearch == 1){
            formData.append(this.listingType.type, this.listingType.id);
        }
       if (typeof this.pagination.per_page != "undefined" &&  this.pagination.per_page != 0) {
          formData.append("loaded-page", this.pageLoad);
          formData.append("alert-records", this.alertRecords);
          formData.append("error-records", this.errorRecords);
      }
      this.pageLoad = 0;
      this.$http
        .post(adminBaseUrl + "/products/list?page=" + pageNo, formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          this.alertRecords = response.data.data.alertRecords;
          this.errorRecords = response.data.data.errorRecords;
          if (
            this.alertRecords == 0 &&
            this.tabType == 1 &&
            typeof this.$route.params.tab == "undefined"
          ) {
         
            this.customSearch = 0;
            this.tabType = 0;
          }
          if (
            this.errorRecords == 0 &&
            this.tabType == 2 &&
            typeof this.$route.params.tab == "undefined"
          ) {
            this.customSearch = 0;
            this.tabType = 0;
          }
          this.records = response.data.data.records.data;
         
          this.loading = false;
          this.types = response.data.data.types;
          this.brandRequired = response.data.data.brandRequired;
          this.digitalEnable = response.data.data.digitalEnable;
          this.pagination = response.data.data.records;
          this.pageLoad = 1;

           if(this.listingType && this.listingType.type && this.records.length != 0 && this.customSearch == 1){
            this.listingType.name = this.listingType.type == "category_id" ? this.records[0].cat_name : this.records[0].brand_name;
          }
          if(response.data.data.searchName){
             this.listingType.name =  response.data.data.searchName;
          }
           
          this.saveSearchData();
        });
      
    },
    tolltipMsg: function(record) {
      if (this.digitalEnable == 1) {
        return this.$t("LBL_DIGITAL_PRODUCTS_ARE_DISABLED");
      } else if (this.digitalEnable == 2) {
        return this.$t("LBL_PHYSICAL_PRODUCTS_ARE_DISABLED");
      } else if (this.productError(record, false) == true) {
        return this.$t("LBL_PRODUCT_HAS_ERRORS");
      }
    },
    productError: function(product, digital = true) {
      if (
        product.prod_type == 2 &&
        this.digitalEnable == 1 &&
        digital == true
      ) {
        return true;
      } else if (
        product.prod_type == 1 &&
        this.digitalEnable == 2 &&
        digital == true
      ) {
        digital == false;
        return true;
      }
      if (
        product.cat_id != null &&
        product.price != null &&
        product.qty != null
      ) {
        if (this.brandRequired != 1 && product.brand_count == 0) {
          return true;
        }
        if (
          product.prod_type == 2 &&
          product.digitalEnable == 1 &&
          product.prod_from_origin_country_id != null &&
          product.pc_weight_unit != null &&
          product.pc_weight != null &&
          product.prod_sbpkg_id != null
        ) {
          return true;
        }
        return false;
      }
      return true;
    },
    updateProduct: function(product, type) {
    
      this.editProduct = [];
      this.displayProducts = [];
      this.editType = type;
      this.prodName = product.prod_name;
      let value = 0;
      let decimal = 0;
      let min = 0;
      Object.keys(product.varients).forEach(key => {
        if (product.varients[key].pov_publish == 1) {
          if (type == "price") {
            value = this.$displaynumberFormat(product.varients[key].pov_price);
            decimal = decimalValues;
          } else {
            value = product.varients[key].pov_quantity;
          }
          this.editProduct.push({
            name: product.varients[key].pov_display_title,
            value: value,
            code: product.varients[key].pov_code,
            type: "varients",
            default: product.varients[key].pov_default,
            condistion: {
              min: 0,
              decimal: decimal,
              substractStock: product.pc_substract_stock
            }
          });
        }
      });
      if (product.varients.length == 0) {
        if (type == "price") {
          value = this.$displaynumberFormat(product.price);
          decimal = decimalValues;
        } else {
          value = product.qty;
         }
        this.editProduct.push({
          name: type,
          value: value,
          code: product.prod_id,
          type: "product",
          condistion: {
            min: 0,
            decimal: decimal,
            substractStock: product.pc_substract_stock
          }
        });
      }
      this.$bvModal.show(this.productModelId);
    },
    displayProduct: function(product) {
      this.editProduct = [];
      this.editType = "optionPrice";
      this.prodName = product.prod_name;
      this.displayProducts = product.varients;
      this.$bvModal.show(this.productModelId);
    },
    validateRecord: function() {
      this.$validator.validateAll(this.editProduct).then(res => {
        if (res) {
          this.alertRecords = 0;
          this.errorRecords = 0;
          this.pageLoad = 0;
          let formData = this.$serializeData({
            items: JSON.stringify(this.editProduct),
            type: this.editType
          });
          this.$http
            .post(adminBaseUrl + "/products/update_items", formData)
            .then(response => {
              if (response.data.status == false) {
                toastr.error(response.data.message, "", toastr.options);
                return;
              }
              toastr.success(response.data.message, "", toastr.options);
              this.editType = "";
              this.editProduct = [];
              this.$bvModal.hide(this.productModelId);
              this.pageRecords(this.pagination.current_page);
            });
        }
      });
    },
    validateSearch: function() {
      this.$validator
        .validate("productSearch", this.productSearch)
        .then(res => {
          if (res) {
            this.errors.clear();
            if (this.searchKeywords.includes(this.productSearch) === false) {
              this.searchKeywords.push(this.productSearch);
              this.customSearch = 1;
              this.sortBy();
              this.pageRecords(1);
            }
            this.productSearch = "";
          }
        });
    },
    sortBy: function(type) {

      if (this.customSearch == 1) {
        this.sortingBy = "";
        this.sortingType = "";
        return false;
      }
   
      if (
        this.sortingType == "" ||
        this.sortingType == "desc" ||
        this.sortingBy != type
      ) {
        this.sortingType = "asc";
      } else {
        this.sortingType = "desc";
      }
      this.sortingBy = type;
      this.pageRecords(1);
    },
    removeKeyword: function(index, type = false) {
      if(type == true){
          this.listingType = {};
      }else{
          this.$delete(this.searchKeywords, index);
      }

      this.saveSearchData();

          this.pageRecords(1);
      if (this.searchKeywords.length == 0 && Object.keys(this.listingType).length == 0) {
        this.customSearch = 0;
        this.errors.clear();
      }
    },
    saveSearchData: function(){
    //  localStorage.setItem('productSearch', JSON.stringify(this.searchKeywords));
    //  localStorage.setItem('productSearchType', JSON.stringify(this.listingType));

    },
    switchTab: function(type) {
      this.tabType = 0;
      this.selected = [];
      this.allSelected = false;
      if (type == 3) {
        this.tabType = 1;
        this.pageRecords(1);
        this.$router.replace({
          path: "/products/alerts",
          query: this.$route.query
        });
        return false;
      }
      if (type == 4) {
        this.tabType = 2;
        this.pageRecords(1);
        this.$router.replace({
          path: "/products/errors",
          query: this.$route.query
        });
        return false;
      }
      this.$router.replace({ path: "/products", query: this.$route.query });
      this.customSearch = type;
      this.pageRecords(1);
    },
    clearSearch: function() {
      this.listingType = {};
      this.searchKeywords = [];
      this.pageRecords(1);
      this.customSearch = 0;
      this.errors.clear();
 
      this.saveSearchData();
    },
    currentPage: function(page) {
      this.pageRecords(page);
    },
    onSwitchStatus: function(event, id) {
      let formData = this.$serializeData({
        status: event.target.checked
      });
      this.$http
        .post(adminBaseUrl + "/products/status/" + id, formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    confirmDelete: function(dataid) {
      this.recordId = dataid;
      this.$bvModal.show(this.modelId);
    },
    unSelectAll: function() {
      this.allSelected = false;
    },
    updateAllRecord: function(type, displayMsg = 0) {
      if (type == "delete" && displayMsg == 0) {
        this.confirmDelete(type);
        return false;
      }
      let formData = this.$serializeData({
        "record-ids": this.selected,
        type: type
      });
      this.$http
        .post(adminBaseUrl + "/products/update_records", formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords(this.pagination.current_page);
          this.selected = [];
          this.allSelected = false;
          this.$bvModal.hide(this.modelId);
        });
    },
    deleteRecord: function(recordId) {
      if (recordId == "delete") {
        this.updateAllRecord(recordId, 1);
      }
      this.$http
        .delete(adminBaseUrl + "/products/" + recordId)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords(this.pagination.current_page);
          this.$bvModal.hide(this.modelId);
        });
    },
    openViewMode: function(record) {
      this.$router.push({
        name: "editProduct",
        params: { id: record.prod_id }
      });
    },
    checkingChecked: function(productId) {
        return (this.selected.includes(productId));
    }
  },
  created() {
    this.pageId = this.$route.query.page;
  },

  beforeMount() {
  //     this.clearSearch()
  },

  beforeUpdate(){
  },

  updated(){
    
  },


  mounted() {
    let thisObj = this;
    if (typeof thisObj.$route.params.tab != "undefined") {
      if (thisObj.$route.params.tab == "errors") {
        thisObj.tabType = 2;
      } else if (thisObj.$route.params.tab == "alerts") {
        thisObj.tabType = 1;
      }
    }
    this.refreshedSearchData();
  }
};
</script>