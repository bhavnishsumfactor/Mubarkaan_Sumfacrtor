<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_BLOG_CATEGORIES') }}</h3>
          <div class="subheader__breadcrumbs">
            <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_CMS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_BLOGS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_BLOG_CATEGORIES')}}</span>
          </div>
        </div>
        <div class="subheader__toolbar">
          <a
            @click="openAddPage"
            class="btn btn-white"
            href="javascript:void(0);"
            v-bind:class="[(!$canWrite('BLOGS')) ? 'disabledbutton no-drop': '']"
          >
            <i class="fas fa-plus"></i>
            {{ $t('BTN_ADD') }}
          </a>
        </div>
      </div>
    </div>

    <div class="container">
      <div class id="manage-blogCategory">
        <div class="row">
          <div class v-bind:class="[(showEmpty == 1) ? 'col-md-12': 'col-md-6']">
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
                        :data-id="node.bpcat_id"
                        class="outerDiv"
                      >
                        <div class="categories__label">
                          <div class>
                            <i class="fa fa-arrows-alt handle m-2"></i>
                            <i
                              class="fa m-2"
                              v-if="node.children.length"
                              v-bind:class="node.$folded ? 'fa-angle-right' : 'fa-angle-down'"
                              v-on:click.stop="tree.toggleFold(node, path);"
                            ></i>
                            <span
                              class="category__txt link"
                              v-on:click.stop="openEditPage(node);"
                              v-if="!$canWrite('BLOGS')"
                              v-html="$options.filters.highlight(node.bpcat_name, searchData.search)"
                            ></span>
                            <span class="category__txt"  v-else v-html="$options.filters.highlight(node.bpcat_name, searchData.search)"></span>                           
                            <span class="badge badge--success">{{node.children.length}}</span>
                          </div>
                          <div class="actions">
                            <label
                              class="switch switch--sm"
                              v-b-tooltip.hover
                              :title="node.bpcat_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') "
                            >
                              <input
                                type="checkbox"
                                v-model="node.bpcat_publish"
                                @click.stop
                                @change="onSwitchStatus($event, node.bpcat_id);selectedPage=''"
                                :disabled="!$canWrite('BLOGS')"
                              />
                              <span></span>
                            </label>
                            <button
                              type="button"
                              class="btn btn-light btn-sm btn-icon"
                              v-b-tooltip.hover
                              :title="$t('TTL_EDIT')"
                              v-on:click.stop="openEditPage(node);"
                              :disabled="!$canWrite('BLOGS')"
                            >
                              <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg>
                            </button> &nbsp;
                            <button
                              v-b-tooltip.hover
                              :title="$t('TTL_DELETE')"
                              type="button"
                              class="btn btn-light btn-sm btn-icon"
                              v-on:click.stop="confirmDelete($event, node.bpcat_id);"
                              :disabled="!$canWrite('BLOGS')"
                            >
                              <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>                               
                                                            </svg>
                            </button>
                          </div>
                        </div>
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
                      {{$t("LBL_CLICK_THE")}}
                      <a
                        href="javascript:;"
                        @click="openAddPage"
                        class="btn btn-brand btn-small"
                      >
                        <i class="fas fa-plus"></i> {{$t("BTN_ADD")}}
                      </a> {{$t("LBL_BUTTON_TO_CREATE_CATEGORIES")}}
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

          <div class="col-md-6" v-if="showEmpty == 0">
            <div class="portlet portlet--height-fluid">
              <div class="portlet__head" v-if="selectedPage">
                <div class="portlet__head-label">
                  <h3 class="portlet__head-title" v-if="selectedPage == 'editform'">
                    {{ $canWrite('BLOGS') ? $t('LBL_EDITING') + ' -' : ''}}
                    <span
                      class="editing-txt"
                    >{{editText}}</span>
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
                v-bind:class="[(!$canWrite('BLOGS')) ? 'disabledbutton': '']"
              >
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                        {{ $t('LBL_BLOG_CATEGORY_NAME') }}
                        <span class="required">*</span>
                      </label>
                      <input
                        v-if="adminsData.bpcat_id != ''"
                        type="hidden"
                        name="id"
                        v-model="adminsData.bpcat_id"
                      />
                      <input
                        type="text"
                        v-model="adminsData.bpcat_name"
                        name="bpcat_name"
                        v-validate="'required'"
                        :data-vv-as="$t('LBL_BLOG_CATEGORY_NAME')"
                        data-vv-validate-on="none"
                        class="form-control"
                      />
                      <span
                        v-if="errors.first('bpcat_name')"
                        class="error text-danger"
                      >{{ errors.first('bpcat_name') }}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ $t('LBL_PARENT_CATEGORY')}}</label>
                      <treeselect
                        name="bpcat_parent"
                        v-if="categories.length>0"
                        v-model="adminsData.bpcat_parent"
                        :defaultExpandLevel="Infinity"
                        :options="categories"
                        :searchable="true"
                        :open-on-click="true"
                        :close-on-select="true"
                        v-validate="'required'"
                        :data-vv-as="$t('LBL_PARENT_CATEGORY')"
                        data-vv-validate-on="none"
                      />
                      <span
                        v-if="errors.first('bpcat_parent')"
                        class="error text-danger"
                      >{{ errors.first('bpcat_parent') }}</span>
                    </div>
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
                      :disabled="!isComplete || clicked==1 || !$canWrite('BLOGS')"
                      v-if="selectedPage == 'addform'"
                      v-bind:class="clicked==1 && 'gb-is-loading'"
                    >{{ $t('BTN_BLOG_CATEGORY_SAVE') }}</button>
                    <button
                      type="submit"
                      class="btn btn-brand gb-btn gb-btn-primary"
                      @click="validateRecord()"
                      :disabled="!isComplete || clicked==1 || !$canWrite('BLOGS')"
                      v-if="selectedPage == 'editform'"
                      v-bind:class="clicked==1 && 'gb-is-loading'"
                    >{{ $t('BTN_BLOG_CATEGORY_UPDATE') }}</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <DeleteModel
            :deletePopText="$t('LBL_BLOG_CATEGORY_DELETE_TEXT')"
            :subText="$t('LBL_BLOG_CATEGORY_DELETE_CAPTION')"
            :recordId="recordId"
            @deleted="deleteRecord(recordId)"
          ></DeleteModel>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import "he-tree-vue/dist/he-tree-vue.css";
import { Tree, Fold, foldAll, unfoldAll, walkTreeData, Draggable } from "he-tree-vue";
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
const tableFields = {
  bpcat_id: "",
  bpcat_name: ""
};
const searchFields = {
  search: ""
};
export default {
  components: {
    Tree: Tree.mixPlugins([Fold, Draggable]),
    Treeselect
  },
  data: function() {
    return {
      records: [],
      baseUrl: baseUrl,
      adminsData: tableFields,
      selectedPage: "",
      categories: [],
      formTitle: "",
      modelId: "deleteModel",
      recordId: "",
      source_id: "",
      destination_id: 0,
      position: "",
      clicked: 0,
      loading: false,
      noDataFound: false,
      searchData: searchFields,
      createdByUser: "",
      updatedByUser: "",
      updatedAt: "",
      createdAt: "",
      pageLoaded: 0,
      showEmpty: 1,
      editText: ""
    };
  },
  watch: {
    $route: "refreshedSearchData"
  },
  computed: {
    isComplete() {
      let tempParent =
        this.adminsData.bpcat_parent === 0
          ? "not empty"
          : this.adminsData.bpcat_parent;
      return this.adminsData.bpcat_name && tempParent;
    }
  },
  methods: {
    refreshedSearchData() {
      if (this.$route.query.s) {
        this.searchData.search = this.$route.query.s;
      }
      this.pageRecords(true);
    },
    pageRecords: function(pageLoad = false) {
      this.loading = pageLoad;
      this.noDataFound = false;
      this.$http.post(adminBaseUrl + "/blog/categories/list").then(response => {        
        var listingData = response.data.data.listing;
        listingData = this.recursiveMap(listingData);
        this.records = listingData;
        if (this.records.length == 0) {
          this.noDataFound = true;
          $(".YK-catListing").hide();
        }
        this.loading = false;
        foldAll(this.records);
        this.showEmpty = response.data.data.showEmpty;
        $(".YK-catListing").show();
        this.pageLoaded = 1;
      });
      this.getParentCategories("");
    },
    onDragStart: function(tree, store) {
      app.source_id = store.dragNode.bpcat_id;
    },
    drop: function(store) {
      let parent = store.targetTree.getNodeParentByPath(store.targetPath);

      if (store.targetPath.length == 1) {
        app.destination_id = 0;
      } else {
        app.destination_id = parent.bpcat_id;
      }

      setTimeout(() => {
        if (typeof parent == "undefined") {
          app.position = store.targetPath[0];
        } else {
          parent.children.forEach(function(child, key) {
            if (child.bpcat_id == app.source_id) {
              app.position = key;
            }
          });
        }
        this.updateDragListing();
      }, 200);
    },
    updateDragListing: function() {
      let input = {};
      input.source_id = app.source_id;
      input.destination_id = app.destination_id;
      input.position = app.position;
      let formData = this.$serializeData(input);
      this.$http.post(adminBaseUrl + "/blog/categories/drag", formData).then(
        response => {
          //success
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
          !node.bpcat_full_name.toLowerCase().includes(value)
        );
      });
      setTimeout(() => {
        if (
          $("div.tree-branch.he-tree--hidden").length == this.records.length
        ) {
          this.noDataFound = true;
          $(".YK-catListing").hide();
        } else {
          this.noDataFound = false;
          $(".YK-catListing").show();
        }
      }, 50);
    },
    recursiveMap: function(data){
      var thisObj = this;
      return data.map((el) => {
        if (el.children.length > 0) {
          thisObj.recursiveMap(el.children);
          el.bpcat_full_name = el.bpcat_name;
          for(var j = 0; j < el.children.length; j++)
          {
            el.bpcat_full_name += (el.children[j].bpcat_full_name) ? el.children[j].bpcat_full_name : el.children[j].bpcat_name;
          }
        } else {
          el.bpcat_full_name = el.bpcat_name;
        }
        el.bpcat_full_name = (typeof el.bpcat_full_name == "object") ? el.bpcat_full_name.bpcat_name : el.bpcat_full_name;    
        return el;
      });
    },
    getParentCategories: function(catId) {
      this.categories = [];
      this.$http
        .get(adminBaseUrl + "/blog/categories/parent/" + catId)
        .then(response => {
          this.categories = response.data.data;
        });
    },
    validateRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          let input = this.adminsData;
          this.clicked = 1;
          if (input.bpcat_id != "") {
            this.updateRecord(input.bpcat_id, input);
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
      this.$http.post(adminBaseUrl + "/blog/categories", formData).then(
        response => {
          this.clicked = 0;
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords();
          this.selectedPage = "";
          this.noDataFound = false;
          this.searchData.search = "";
          this.emptyFormValues();
        },
        response => {
          this.clicked = 0;
          this.validateErrors(response);
        }
      );
    },
    updateRecord: function(id, input) {
      let formData = this.$serializeData(input);
      formData.append("_method", "put");
      this.$http.post(adminBaseUrl + "/blog/categories/" + id, formData).then(
        response => {
          this.clicked = 0;
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords();
          this.selectedPage = "";
          this.noDataFound = false;
          this.searchData.search = "";
          this.emptyFormValues();
        },
        response => {
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
    emptyFormValues: function() {
      this.adminsData = {
        bpcat_id: "",
        bpcat_name: ""
      };
      this.errors.clear();
    },
    deleteRecord: function(recordId) {
      this.$http
        .delete(adminBaseUrl + "/blog/categories/" + recordId)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords();
          this.$bvModal.hide(this.modelId);
        });
    },
    openEditPage: function(record) {
      this.emptyUpdatedFieldValue();
      this.emptyFormValues();
      this.getParentCategories(record.bpcat_id);
      this.adminsData = record;
      this.selectedPage = "editform";
      this.editText = record.bpcat_name;
      if (record.created_at != null && "admin_username" in record.created_at) {
        this.createdByUser = record.created_at.admin_username;
      }
      if (record.updated_at != null && "admin_username" in record.updated_at) {
        this.updatedByUser = record.updated_at.admin_username;
      }
      this.updatedAt = record.bpcat_updated_at ? record.bpcat_updated_at : "";
      this.createdAt = record.bpcat_created_at ? record.bpcat_created_at : "";
    },
    openAddPage: function() {
      this.emptyFormValues();
      this.adminsData.bpcat_parent = 0;
      if (this.searchData.search != "") {
        this.searchData.search = "";
        this.pageRecords();
      }
      this.selectedPage = "addform";
      this.showEmpty = 0;
    },
    confirmDelete: function(event, dataid) {
      event.stopPropagation();
      this.recordId = dataid;
      this.$bvModal.show(this.modelId);
    },
    cancel: function() {
      this.selectedPage = false;
      if (this.records.length == 0) {
        this.showEmpty = 1;
      }
    },
    onSwitchStatus: function(event, id) {
      let formData = this.$serializeData({
        status: event.target.checked
      });
      this.$http
        .post(adminBaseUrl + "/blog/categories/status/" + id, formData)
        .then(response => {
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    emptyUpdatedFieldValue: function() {
      this.createdByUser = "";
      this.updatedByUser = "";
      this.updatedAt = "";
      this.createdAt = "";
    }
  },
  mounted: function() {
      this.searchData.search = '';
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