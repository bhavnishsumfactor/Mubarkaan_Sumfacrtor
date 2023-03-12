<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_CATEGORIES') }}</h3>
          <div class="subheader__breadcrumbs">
            <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_PRODUCTS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_CATEGORIES')}}</span>
          </div>
        </div>
        <div class="subheader__toolbar" v-if="$canWrite('CATEGORIES')">
          <a @click="openAddPage" class="btn btn-white" href="javascript:void(0);">
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
                    v-model="searchData.search"
                    @keyup="search($event)"
                  />
                  <span class="input-icon__icon input-icon__icon--left">
                    <span>
                      <i class="la la-search"></i>
                    </span>
                  </span>
                  <span
                    class="input-icon__icon input-icon__icon--right"
                    v-if="searchData.search!=''"
                    @click="searchData.search='';pageRecords();"
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
              class="portlet__body YK-catListing"
              v-if="records.length > 0 && showEmpty == 0 && pageLoaded==1"
            >
              <div class="list-categories">
                <template>
                  <Tree :value="records" :ondragstart="onDragStart" @drop="drop" ref="tree">
                    <div
                      slot-scope="{node, index, path, tree}"
                      :data-id="node.cat_id"
                      class="outerDiv"
                    >
                      <a>
                        <div class="categories__label">
                          <div class="categories__label_content">
                            <div class="categories__label_icons">
                              <i class="icon fa fa-arrows-alt handle"></i>
                              <i
                                class="icon fa arrow"
                                v-if="node.children.length"
                                v-bind:class="node.$folded ? 'fa-angle-right' : 'fa-angle-down'"
                                v-on:click.stop="tree.toggleFold(node, path);"
                              ></i>
                            </div> 
                            <div class="categories__label_text"> 
                              <span
                                class="category__txt link"
                                v-on:click.stop="openEditPage(node);"
                                v-if="!$canWrite('CATEGORIES')"
                                v-html="$options.filters.highlight(node.cat_name, searchData.search)"
                              ></span>
                              <span class="category__txt"  v-else v-html="$options.filters.highlight(node.cat_name, searchData.search)"></span>
                              <span
                                class="badge badge--success product-count"
                                v-b-tooltip.hover
                                :title="$t('TTL_PRODUCT_COUNT')"
                              >{{ node.product_counts_count }}</span>
                            </div>
                          </div>
                          <div class="category__action">
                            <label
                              class="switch switch--sm"
                              v-b-tooltip.hover
                              :title="node.cat_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') "
                            >
                              <input
                                type="checkbox"
                                v-model="node.cat_publish"
                                @click.stop
                                @change="onSwitchStatus($event, node.cat_id);selectedPage=''"
                                :disabled="!$canWrite('CATEGORIES')"
                              />
                              <span></span>
                            </label>
                            <div class="actions">
                                  <a
                                    class="btn btn-light btn-sm btn-icon"
                                    v-b-tooltip.hover
                                    :title="$t('LNK_VIEW_CATEGORY_PAGE')"
                                    @click.stop="previewCategory(node.url_rewrite)"
                                    href="javascript:;"
                                  >
                                  <svg>   
                                          <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#links'" :href="baseUrl+'/admin/images/retina/sprite.svg#links'"></use>                               
                                      </svg>
                                  </a>

                                  <a
                                    class="btn btn-light btn-sm btn-icon"
                                    v-b-tooltip.hover
                                    :title="$t('LNK_VIEW_PRODUCTS_FOR_CATEGORY')"
                                    @click.stop="seeCategoryProducts(node.cat_id)"
                                    href="javascript:;"
                                  >
                                    <svg>   
                                          <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#view'" :href="baseUrl+'/admin/images/retina/sprite.svg#view'"></use>                               
                                      </svg>
                                  </a>
                                  <button
                                    type="button"
                                    class="btn btn-light btn-sm btn-icon"
                                    v-b-tooltip.hover
                                    :title="$t('TTL_EDIT')"
                                    v-on:click.stop="openEditPage(node);"
                                    :disabled="!$canWrite('CATEGORIES')"
                                  >
                                    <svg style="width: 16px;height: 16px;">   
                                          <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" :href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'"></use>                               
                                      </svg>
                                  </button>
                                  <button
                                    type="button"
                                    class="btn btn-light btn-sm btn-icon"
                                    v-b-tooltip.hover
                                    :title="$t('TTL_DELETE')"
                                    v-on:click.stop="confirmDelete($event, node.cat_id);"
                                    :disabled="!$canWrite('CATEGORIES')"
                                  >
                                    <svg style="width: 16px;height: 16px;">    
                                          <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" :href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"></use>                               
                                      </svg>
                                  </button>
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                  </Tree>
                </template>
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
                    <a href="javascript:;" @click="openAddPage" class="btn btn-brand btn-small">
                      <i class="fas fa-plus"></i>
                      {{ $t('BTN_ADD') }}
                    </a>
                    {{ $t('LBL_BUTTON_TO_CREATE_CATEGORIES') }}
                  </p>
                </div>
                <div class="get-started-media">
                  <img
                    :src="baseUrl+'/admin/images/get-started-graphic/get-started-blog-categories.svg'"
                  />
                </div>
              </div>
            </div>
            <div v-else>
              <loader v-if="loading"></loader>
              <noRecordsFound v-if="loading==false && noDataFound && records.length > 0"></noRecordsFound>
            </div>
          </div>
        </div>
        <div class="col-md-4" v-if="showEmpty == 0">
          <div class="portlet portlet-fluid">
            <div class="portlet__head" v-if="selectedPage">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title" v-if="selectedPage == 'editform'">
                  {{ $canWrite('CATEGORIES') ? $t('LBL_EDITING') + ' -' : ''}}
                  <span class="editing-txt" v-b-tooltip.hover :title="editText">{{ editText | truncate(30)}}</span>
                </h3>
                <h3
                  class="portlet__head-title"
                  v-if="selectedPage == 'addform'"
                >{{ $t('LBL_NEW_CATEGORY_SETUP')}}</h3>
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
              v-bind:class="[(!$canWrite('CATEGORIES')) ? 'disabledbutton': '']"
            >
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>
                      {{ $t('LBL_CATEGORY_NAME') }}
                      <span class="required">*</span>
                    </label>
                    <input
                      v-if="adminsData.cat_id != ''"
                      type="hidden"
                      name="id"
                      v-model="adminsData.cat_id"
                    />
                    <input
                      type="text"
                      v-model="adminsData.cat_name"
                      name="cat_name"
                      v-validate="'required'"
                      :data-vv-as="$t('LBL_CATEGORY_NAME')"
                      data-vv-validate-on="none"
                      class="form-control"
                    />
                    <span
                      v-if="errors.first('cat_name')"
                      class="error text-danger"
                    >{{ errors.first('cat_name') }}</span>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>{{ $t('LBL_PARENT_CATEGORY')}}</label>
                    <treeselect
                      name="cat_parent"
                      v-if="categories.length>0"
                      v-model="adminsData.cat_parent"
                      :defaultExpandLevel="Infinity"
                      :options="categories"
                      :searchable="true"
                      :open-on-click="true"
                      :close-on-select="true"
                      v-validate="'required'"
                      :data-vv-as="$t('LBL_PARENT_CATEGORY')"
                      data-vv-validate-on="none"
                      :clearable="false"
                    />
                    <span
                      v-if="errors.first('cat_parent')"
                      class="error text-danger"
                    >{{ errors.first('cat_parent') }}</span>
                  </div>
                </div>
              </div>
              <div class="row" v-if="taxCode == 1">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>{{ $t('LBL_CATEGORY_TAX_CODE')}}</label>
                    <input
                      type="text"
                      v-model="adminsData.cat_tax_code"
                      name="cat_tax_code"
                      class="form-control"
                    />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class>
                    <label>
                      {{ $t('LBL_CATEGORY_BANNER_IMAGE')}}
                    </label>
                    <div class>
                      <div
                        class="dropzone dropzone-default dropzone-brand dz-clickable ratio-4by1"
                        data-ratio="4:1"
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
                          <div class="needsclick" v-if="croppedImageView == ''">
                            <h3 class="dropzone-msg-title">{{ $t('LBL_CLICK_HERE_TO_UPLOAD')}}</h3>
                          </div>
                        </div>
                      </div>
                      <cropper
                        ref="cropperRef"
                        :title="adminsData.cat_name"
                        :icon="false"
                        :aspectRatio="5"
                        :width="2000"
                        :height="400"
                        v-on:childToParent="setImage"
                        v-on:actualImageToParent="setActualImage"
                      ></cropper>
                      <img :src="originalImage" id="originalImage" style="display: none;" />
                    </div>
                  </div>
                  <p
                    class="img-disclaimer py-2"
                  >{{ $t('LBL_IMAGE_RESTRICTIONS') + ' 2000 x 400 ' + $t('LBL_PIXELS') + $t('LBL_IN') + ' 5:1 ' + $t('LBL_ASPECT_RATIO') }}</p>
                </div>
              </div>
            </div>
            <div class="portlet__body" v-if="selectedPage == ''">
              <div class="no-data-found">
                <div class="img">
                  <img :src="this.$noDataImage()" alt />
                </div>
                <div class="data">
                  <p>{{ $t ('LBL_USE_ICONS_FOR_ADVANCED_ACTIONS') }}</p>
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
                  >{{ $t('BTN_DISCARD')}}</button>
                </div>
                <div class="col-auto">
                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    :disabled="!isComplete || clicked==1 || !$canWrite('CATEGORIES')"
                    v-if="selectedPage == 'addform'"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{ $t('BTN_CREATE') }}</button>
                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    :disabled="!isComplete || clicked==1 || !$canWrite('CATEGORIES')"
                    v-if="selectedPage == 'editform'"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{ $t('BTN_ADMIN_UPDATE') }}</button>
                </div>
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
</template>
<script>
import "he-tree-vue/dist/he-tree-vue.css";
import { Tree, Fold, foldAll, unfoldAll, walkTreeData, Draggable } from "he-tree-vue";
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";

const tableFields = {
  cat_id: "",
  cat_name: "",
  cat_tax_code: "",
  cat_parent: ""
};
const searchFields = {
  cat_name: ""
};
export default {
  components: {
    Tree: Tree.mixPlugins([Fold, Draggable]),
    Treeselect
  },
  data: function() {
    return {
      baseUrl: baseUrl,
      records: [],
      adminsData: tableFields,
      selectedPage: "",
      categories: [],
      formTitle: "",
      modelId: "deleteModel",
      recordId: "",
      source_id: "",
      destination_id: 0,
      taxCode: 0,
      position: "",
      croppedImage: "",
      croppedImageView: "",
      originalImage: "",
      uploadedFile: "",
      searchData: searchFields,
      clicked: 0,
      loading: false,
      noDataFound: false,
      createdByUser: "",
      createdAt: "",
      updatedByUser: "",
      updatedAt: "",
      pageLoaded: 0,
      showEmpty: 1,
      editText: "",
      fileUploadClass: false,
      fileVisiblity: false,
      attachedFile: "",
      deleteStatus: {},
      total:0
    };
  },
  computed: {
    isComplete() {
      let tempParent =
        this.adminsData.cat_parent === 0
          ? "not empty"
          : this.adminsData.cat_parent;
      return this.adminsData.cat_name && tempParent;
    }
  },
  watch: {
    $route: "refreshedSearchData"
  },
  methods: {
    refreshedSearchData() {
      if (this.$route.query.s) {
        this.searchData.search = this.$route.query.s;
    
      }
      this.pageRecords(true);
    },
    onDragStart: function(tree, store) {
      app.source_id = store.dragNode.cat_id;
      this.cancel();
    },
    drop: function(store) {
      let parent = store.targetTree.getNodeParentByPath(store.targetPath);
      if (store.targetPath.length == 1) {
        app.destination_id = 0;
      } else {
        app.destination_id = parent.cat_id;
      }
      setTimeout(() => {
        if (typeof parent == "undefined") {
          app.position = store.targetPath[0];
        } else {
          parent.children.forEach(function(child, key) {
            if (child.cat_id == app.source_id) {
              app.position = key;
            }
          });
        }
        this.updateDragListing();
      }, 200);
    },
    updateDragListing: function() {
      this.selectedPage = "";
      let input = {};
      input.source_id = app.source_id;
      input.destination_id = app.destination_id;
      input.position = app.position;
      let formData = this.$serializeData(input);
      this.$http.post(adminBaseUrl + "/product/categories/drag", formData).then(
        response => {
          //success
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          walkTreeData(this.records, function(node) {
            if (node.cat_id == input.source_id) {
              node.cat_parent = input.destination_id;
            }
          });
        },
        response => {
          //error
          this.validateErrors(response);
        }
      );
    },
    search(e = '', keyword = '') {
        
        let value = '';
        if(keyword){
            value = keyword.trim().toLowerCase();
        }else{
            value = e.target.value.trim().toLowerCase() || "";
        }
        if (this.searchData.search != '') {
          unfoldAll(this.records);
        } else {
          foldAll(this.records);
        }
      let thisObj = this;
      walkTreeData(this.records, function(node) {
        thisObj.$set(
          node,
          "$hidden",
          !node.cat_full_name.toLowerCase().includes(value)
        );
      });
      setTimeout(() => {
        if ( $("div.tree-branch.he-tree--hidden").length == thisObj.total ) {
            thisObj.noDataFound = true;
            $(".YK-catListing").hide();
        } else {
            thisObj.noDataFound = false;
            $(".YK-catListing").show();
        }

        
      }, 50);
    },
    recursiveMap: function(data){
      var thisObj = this;
      return data.map((el) => {
        if (el.children.length > 0) {
          thisObj.recursiveMap(el.children);
          el.cat_full_name = el.cat_name;
          for(var j = 0; j < el.children.length; j++)
          {
            el.cat_full_name += (el.children[j].cat_full_name) ? el.children[j].cat_full_name : el.children[j].cat_name;
          }
        } else {
          el.cat_full_name = el.cat_name;
        }
        el.cat_full_name = (typeof el.cat_full_name == "object") ? el.cat_full_name.cat_name : el.cat_full_name;    
        return el;
      });
    },
    pageRecords: function(pageLoad = false) {
      this.loading = pageLoad;
      this.noDataFound = false;
      this.$http
        .post(adminBaseUrl + "/product/categories/list")
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);            
            return;
          }
          var listingData = response.data.data.listing;
          listingData = this.recursiveMap(listingData);
          this.records = listingData;
          if (this.records.length == 0) {
            this.noDataFound = true;
            $(".YK-catListing").hide();
          }

          this.showEmpty = response.data.data.showEmpty;
          this.taxCode = response.data.data.taxCode;
          this.total = response.data.data.total;
          this.loading = false;
          $(".YK-catListing").show();
          
          foldAll(this.records);
          this.pageLoaded = 1;
        });
      this.getParentCategories("");
    },
    setImage: function(cropUrl) {
      this.croppedImage = cropUrl;
      this.croppedImageView = URL.createObjectURL(cropUrl);
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
    getParentCategories: function(catId) {
      this.categories = [];
      this.$http
        .get(adminBaseUrl + "/categories/parent/" + catId)
        .then(response => {
          this.categories = response.data.data;
        });
    },
    openEditPage: function(record) {
      this.emptyFormValues();
      this.emptyImageData();
      this.getParentCategories(record.cat_id);
      Object.assign(this.adminsData, record);
      this.croppedImage = this.croppedImageView = this.originalImage = this.uploadedFile =
        "";
      if (record.afile_id != null) {
        this.fileVisiblity = true;
        this.fileUploadClass = false;
        this.croppedImage = this.croppedImageView = this.$getFileUrl(
          "productCategoryBanner",
          record.cat_id,
          0,
          "thumb",
          this.$timestamp(record.cat_updated_at)
        );
        this.originalImage = this.$getFileUrl(
          "productCategoryBanner",
          record.cat_id,
          0,
          "original",
          this.$timestamp(record.cat_updated_at)
        );
        this.attachedFile = record.afile_id;
      } else {
        this.attachedFile = "";
      }
      this.editText = record.cat_name;
      this.selectedPage = "editform";
      if (record.created_at != null && "admin_username" in record.created_at) {
        this.createdByUser = record.created_at.admin_username;
      }
      if (record.updated_at != null && "admin_username" in record.updated_at) {
        this.updatedByUser = record.updated_at.admin_username;
      }
      this.updatedAt = record.cat_updated_at ? record.cat_updated_at : "";
      this.createdAt = record.cat_created_at ? record.cat_created_at : "";
    },
    seeCategoryProducts: function(categoryId) {
      this.$router.push({
        name: "products",
        query: { category_id: categoryId }
      });
    },
    openAddPage: function() {
      this.emptyFormValues();
      this.adminsData.cat_parent = 0;
      if (this.searchData.search != "") {
        this.searchData.search = "";
        this.pageRecords();
      }
      this.selectedPage = "addform";
      this.showEmpty = 0;
      this.emptyImageData();
    },
    validateRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          let input = this.adminsData;
          this.clicked = 1;
          if (input.cat_id != "") {
            this.updateRecord(input.cat_id, input);
          } else {
            this.saveRecord(input);
          }
        } else {
          this.clicked = 0;
        }
      });
    },
    saveRecord: function(input) {
      let formData = this.$serializeData(input);
      formData.append("actualImage", this.uploadedFile);
      formData.append("cropImage", this.croppedImage);
      this.$http.post(adminBaseUrl + "/product/categories", formData).then(
        response => {
          if (response.data.status == false) {
            this.clicked = 0;
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords();
          this.noDataFound = false;
          $(".YK-catListing").show();
          this.searchData.search = "";
          this.selectedPage = "";
          this.emptyFormValues();
          this.clicked = 0;
        },
        response => {
          this.validateErrors(response);
          this.clicked = 0;
        }
      );
    },
    updateRecord: function(id, input) {
      let formData = this.$serializeData(input);
      formData.append("actualImage", this.uploadedFile);
      formData.append("cropImage", this.croppedImage);
      formData.append("_method", "PUT");
      this.$http
        .post(adminBaseUrl + "/product/categories/" + id, formData)
        .then(
          response => {
            if (response.data.status == false) {
              this.clicked = 0;
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
            this.pageRecords();
            this.selectedPage = "";
            this.noDataFound = false;
            $(".YK-catListing").show();
            this.searchData.search = "";
            this.cancel();
            this.emptyFormValues();
            this.clicked = 0;
          },
          response => {
            this.validateErrors(response);
            this.clicked = 0;
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
    emptyFormValues: function() {
      this.adminsData = {
        cat_id: "",
        cat_name: ""
      };
      this.editText = this.uploadedFile = this.croppedImage = this.croppedImageView = this.originalImage =
        "";
      this.errors.clear();
    },
    confirmDelete: function(event, dataid) {
      event.stopPropagation();
      this.recordId = dataid;
      this.deleteStatus.id = dataid;
      this.deleteStatus = {
        message: this.$t("LBL_DELETE_CATEGORIES_TEXT"),
        subMessage:
          this.$t("LBL_DELETE_CATEGORIES_SUB_TEXT"),
        id: dataid
      };
      this.$bvModal.show(this.modelId);
    },
    deleteRecord: function(recordId) {
      if (this.attachedFile != "" && this.deleteStatus.type == 1) {
        this.$http
          .delete(adminBaseUrl + "/remove-attached-files/" + recordId)
          .then(response => {
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            this.emptyImageData();
            this.deleteStatus.type = 0;
            this.attachedFile = "";
            toastr.success(response.data.message, "", toastr.options);
            this.pageRecords(0);
          });
      } else if (this.deleteStatus.type == 0) {
        this.emptyImageData();
      } else {
        this.$http
          .delete(adminBaseUrl + "/product/categories/" + recordId)
          .then(response => {
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
            this.pageRecords();
            this.$bvModal.hide(this.modelId);
            this.selectedPage = "";
          });
      }
      this.$bvModal.hide(this.modelId);
    },
    cancel: function() {
      this.selectedPage = false;
      if (this.records.length == 0) {
        this.showEmpty = 1;
      }
    },
    openCropper: function() {
      this.$refs.cropperRef.openCropper();
    },
    previewCategory: function(url) {
     if(url && url.urlrewrite_custom) {
        url = baseUrl + '/' + url.urlrewrite_custom;
      }else if (url && url.urlrewrite_original){
        url = baseUrl + '/' + url.urlrewrite_original;
     }
      window.open(url, "_blank");
    },
    onSwitchStatus: function(event, id) {
      let formData = this.$serializeData({
        status: event.target.checked
      });
      this.$http
        .post(adminBaseUrl + "/categories/status/" + id, formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    emptyUpdatedFieldValue: function() {
      this.createdByUser = this.updatedByUser = this.updatedAt = "";
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
    emptyImageData: function() {
      this.croppedImage = "";
      this.croppedImageView = "";
      this.originalImage = "";
      this.uploadedFile = "";
      this.fileUploadClass = false;
      this.fileVisiblity = false;
    }
  },
  mounted: function() {
    this.searchData = { search: "" };
    this.refreshedSearchData();
  }
};
</script>
<style>
.he-tree .tree-node {
  border: none !important;
  padding: 0 !important;
}
</style>