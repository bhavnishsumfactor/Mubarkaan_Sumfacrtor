<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_MOBILE_APPS') }}</h3>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="portlet">
        <div class="portlet__body portlet__body--fit">
          <div class="app-page">
            <sidebar :tab="type"></sidebar>

            <div class="app-main">
              <div class="row justify-content-center">
                <div class="col-md-5">
                  <div class="app__collections info__collections">
                    <div class="app__collections-head d-flex justify-content-between">
                    <h3>Explore</h3>
                   
                  </div>
                    <div class="app__collections-wrapper ">
                    
                      <div class="content__wrapper">
                        <div class="content_item">
                         <p>The explore screen will let you display/manage content screen listing in the app. The explore page is divided into 2 sections. <br/><br/>
                           
                  Content screens can be added to both the sections on this page. Click on the ‘+’ icon to add a new screen. Please select from a pre-defined drop down to map the content screen and give it a display name.<br/>
Available content screens are :<br/>
<ul>
  <li>- Blog Listing </li>
  <li>- Contact us </li>
  <li>- Privacy Policy </li>
  <li>- Terms and conditions</li>
  <li>- FAQ’s</li>
  <li>- Dynamically created content pages.</li>
</ul>
</p>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="device__preview-wrapper">
                    <div class="device__preview">
                      <div class="device__screen">
                        <div class="app__collection app__header mb-0">
                          <div class="app_collection-content">
                            
                          </div>
                        </div>
                        <div
                          class="scroll-y"
                          style="max-height:496px;min-height:496px; padding:0;"
                        >                      
                          <div class="page__content">
                            <div class="page_title">
                            <input 
                                v-model="editField.editValue" 
                                v-if="editField && editField.editKey == 'LBL_EXPLORE'" 
                                type="text" 
                                class="field-value form-control" 
                                @blur="updateLabel('LBL_EXPLORE')"
                                >
                              <div class="actions" v-else @dblclick="focusField('LBL_EXPLORE')">
                                <h4>{{labels['LBL_EXPLORE']}}</h4>     
                                <a href="javascript:;" @click="focusField('LBL_EXPLORE')">
                                  <button type="button">
                                    <svg>
                                      <use :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#edit-icon'" />
                                    </svg>
                                  </button>
                                </a>
                              </div>
                            </div>
                            <div class="widgets">
                                <div class="widget_item">
                                    <div class="widget_item-head">
                                         <input 
                                            v-model="editField.editValue" 
                                            v-if="editField && editField.editKey == 'LBL_EXTRA'" 
                                            type="text" 
                                            class="field-value form-control" 
                                            @blur="updateLabel('LBL_EXTRA')"
                                            >
                                        <div class="actions" v-else>  
                                          <h4 @dblclick="focusField('LBL_EXTRA')">{{labels['LBL_EXTRA']}}</h4>                                        
                                          <a href="javascript:;" @click="focusField('LBL_EXTRA')">
                                          <button type="button">
                                            <svg>
                                              <use :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#edit-icon'" />
                                            </svg>
                                          </button>
                                          </a>
                                          </div>
                                          <div class="actions">
                                          <a href="javascript:;" class="app-nav_item" @click="addNewExplore(1)">
                                            <button type="button">
                                              <svg>
                                                <use :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#add-icon'" />
                                              </svg>
                                            </button>
                                          </a>
                                        </div>
                                    </div>
                                    <div class="widget_item-body">
                                      <ul class="list__items"> 
                                          <li class="list__item" v-for="(page, index) in extra_pages" v-bind:key="index">
                                            <p class="">{{page.ae_title}}</p>
                                            <div class="actions">
                                              <a href="javascript:;" @click.capture="confirmDelete(page.ae_id)" class="btn btn-light btn-sm btn-icon">
                                                <svg width="18px" height="18px">   
                                                  <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>                               
                                                </svg>
                                              </a>
                                            </div> 
                                          </li>                                         
                                          <li class="list__item" v-for="(n, index) in extraSkilton">
                                            <p class="skeleton"></p> 
                                          </li>
                                        </ul> 
                                    </div>
                                </div>
                            </div><div class="widgets">
                                <div class="widget_item">
                                    <div class="widget_item-head">
                                        <input 
                                            v-model="editField.editValue" 
                                            v-if="editField && editField.editKey == 'LBL_QUICK_LINKS'" 
                                            type="text" 
                                            class="field-value form-control" 
                                            @blur="updateLabel('LBL_QUICK_LINKS')"
                                            >
                                          <div class="actions" v-else>
                                            <h4 @dblclick="focusField('LBL_QUICK_LINKS')">{{labels['LBL_QUICK_LINKS']}}</h4>
                                            <a href="javascript:;" @click="focusField('LBL_QUICK_LINKS')">
                                              <button type="button">
                                                <svg>
                                                  <use :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#edit-icon'" />
                                                </svg>
                                              </button>
                                            </a>
                                          </div>
                                        
                                        <div class="actions">
                                          <a href="javascript:;" class="app-nav_item" @click="addNewExplore(2)">
                                          <button type="button">
                                            <svg>
                                              <use :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#add-icon'" />
                                            </svg>
                                          </button>
                                          </a>
                                        </div>
                                    </div>
                                    <div class="widget_item-body">
                                        <ul class="list__items">
                                          <li class="list__item" v-for="(page, index) in quick_links_pages" v-bind:key="index">
                                            <p class="">{{page.ae_title}}</p>     
                                            <div class="actions">
                                              <a href="javascript:;"  @click.capture="confirmDelete(page.ae_id)" class="btn btn-light btn-sm btn-icon">
                                                <svg width="18px" height="18px">   
                                                  <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>                               
                                                </svg>
                                              </a>
                                            </div>                                
                                          </li>
                                           <li class="list__item" v-for="(n, index) in quickLinkSkilton">
                                            <p class="skeleton"></p> 
                                          </li>                                         
                                        </ul>
                                    </div>
                                </div>
                            </div>
                          </div>                       
                        </div>                      
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <b-modal id="explorePageForm" size="md" centered title="BootstrapVue">
    <template v-slot:modal-header>
      <h5 class="modal-title" id="exampleModalLabel">
        <span>Add Explore</span>
      </h5>
      <button type="button" class="close" @click="$bvModal.hide('explorePageForm')"></button>
    </template>
    <div class="form-group row">
      <label class="col-md-12 col-form-label" for="validationCustom01">Select Page</label>
      <div class="col-md-12">
         <select v-model="appPageId" v-validate="'required'" :data-vv-as="$t('LBL_PAGE')" data-vv-validate-on="none" class="form-control" @change="updatePageTitle">
            <option value="" disabled>{{ $t('LBL_SELECT_PAGE')}}</option>
            <option v-for="(appPage, index) in appPages" :value="appPage.page_id" :key="index">{{appPage.page_title}}</option>
        </select> 
      </div>
    </div>
    <div class="form-group row">
      <label class="col-md-12 col-form-label" for="validationCustom01">Page Title</label>
      <div class="col-md-12">
        <input type="text" class="form-control" placeholder="Page Title" v-model="pageTitle" />
      </div>
    </div>
    <template v-slot:modal-footer>
      <button type="submit" :disabled="pageTitle == ''" class="btn btn-brand " @click="saveExplore">
        <span>Save</span>
      </button>
    </template>
  </b-modal>
  <DeleteModel
    :recordId="selectedExploreId"
    :deletePopText="$t('LBL_DELETE_PAGE_CONFIRM_TEXT')"
    @deleted="deleteRecord(selectedExploreId)"
  ></DeleteModel>
  </div>
</template>

<script>
import { SlickList, SlickItem } from "vue-slicksort";
import { Container, Draggable } from "vue-smooth-dnd";
import sidebar from "./sidebar";
import TagsCollection from "./partial/TagsCollection";
import TagsCollectionForm from "./popups/TagsCollectionForm";
export default {
  name: "Copy",
  components: {
    sidebar: sidebar,
    TagsCollection,
    TagsCollectionForm,
    Container,
    Draggable,
    SlickItem,
    SlickList
  },
  data() {
    return {
      pageId: 3,
      type: "explore",
      clickEvent: "",
      selectedCollections: [],
      acId: "",
      collectionId: "",
      collectionType: "",
      baseUrl: baseUrl,
      records: [],
      labels:{'LBL_EXTRA': 'Extra', 'LBL_QUICK_LINK': 'Quick Links', 'LBL_EXPLORE': 'Explore'},
      appRecords: [],
      dropPlaceholderOptions: {
        className: "drop-preview",
        animationDuration: "150",
        showOnTop: true
      },
      appPageId: '',
      pageTitle: '',
      pageType: '',
      appPages: [],
      editField: {},
      extra_pages: [],
      quick_links_pages: [],
      extraSkilton: 3,
      quickLinkSkilton: 3,
      modelId: "deleteModel",
      selectedExploreId: ''
    };
  },
  methods: {
    sortEnd({ oldIndex, newIndex }) {
      let thisObj = this;
      setTimeout(function() {
        let maxVal = Math.max(oldIndex, newIndex);
        let minVal = Math.min(oldIndex, newIndex);
        for (let x = minVal; x <= maxVal; x++) {
       //   thisObj.editExtraPage[x].order = thisObj.extra_pages[x];
        }
      }, 100);
    },
    addNewExplore:function(pageType){
      this.pageTitle = '';
      this.pageType = pageType;
      this.$bvModal.show("explorePageForm");
    },
    saveExplore: function(){
      let formData = new FormData();
      formData.append("page_id", this.appPageId);
      formData.append("title", this.pageTitle);
      formData.append("type", "appExplore");
      formData.append("page_type", this.pageType);
      this.$http
        .post(adminBaseUrl + "/app/store/records", formData)
        .then(response => {
          this.$bvModal.hide("explorePageForm");
          this.getAppExplore();
          this.appPageId = '';
          toastr.success("Updated successfully", '', toastr.options);
        });
    },
    focusField(key){
      this.editField = {editKey:key, editValue: this.labels[key]};
    },
    updateLabel: function() {
      let formData = this.$serializeData({'key':  this.editField.editKey});
      formData.append(appDefaultLanguage, this.editField.editValue);
      this.$http.post(adminBaseUrl + '/languageslabels/mobile/save', formData)
          .then((response) => {            
              if (response.data.status == false) {
                  toastr.error(response.data.message, '', toastr.options);
                  return;
              }
              this.labels[this.editField.editKey] = this.editField.editValue;
              toastr.success(response.data.message, '', toastr.options);
              this.editField = {};
          }, (response) => {
              this.validateErrors(response);
          });
    },
    getAppLanguageLabel: function(){
      let filterKeys = ['LBL_EXTRA','LBL_QUICK_LINKS','LBL_EXPLORE'];
      this.$http.post(adminBaseUrl + '/languageslabels/mobile/all', {"labels": filterKeys})
          .then((response) => {
              if (response.data.status == false) {
                  toastr.error(response.data.message, '', toastr.options);
                  return;
              }
              this.labels = response.data.data;
          }, (response) => {

          });
    },
    getAppExplore: function(){
      let filterKeys = ['LBL_EXTRA','LBL_QUICK_LINKS','LBL_EXPLORE'];
      this.$http.post(adminBaseUrl + '/app-explore/get-data', {"labels": filterKeys})
          .then((response) => {
              if (response.data.status == false) {
                  toastr.error(response.data.message, '', toastr.options);
                  return;
              }
              this.labels = response.data.data.language_labels;
              this.extra_pages = response.data.data.page_data.extra;
              if(parseInt(this.extra_pages.length) >= 3){
                this.extraSkilton = 0;
              }else{
                this.extraSkilton = parseInt(3)-parseInt(this.extra_pages.length);
              }              
              this.quick_links_pages = response.data.data.page_data.quick_links;
              if(parseInt(this.quick_links_pages.length) >= 3){
                this.quickLinkSkilton = 0;
              }else{
                this.quickLinkSkilton = parseInt(3)-parseInt(this.quick_links_pages.length);
              }
              this.appPages = response.data.data.app_pages;
          }, (response) => {

          });
    },
    updatePageTitle: function(){
       this.pageTitle  = JSON.parse(appPages).find((page,key)=>page.page_id == this.appPageId).page_title;
    },
    deleteCollRecordById: function(recordId) {
      let formData = this.$serializeData({
        ae_id: recordId,
      });
      this.$http
        .post(adminBaseUrl + "/app-explore/delete", formData)
        .then(response => {        
          if (response.data.status == false) {
                toastr.error(response.data.message, '', toastr.options);
                return;
            }
            this.$bvModal.hide(this.modelId);
            toastr.success(response.data.message, '', toastr.options);
            this.getAppExplore();
        }, (response) => {

        });
    },   
    confirmDelete: function(explorePageId) {
      this.selectedExploreId = explorePageId;
      this.$bvModal.show(this.modelId);
    },
    deleteRecord: function(recordId) {
      this.deleteCollRecordById(recordId);
    },
  },
  mounted: function() {
    this.getAppExplore();
  }
};
</script>
