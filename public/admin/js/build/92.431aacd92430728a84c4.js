(window.webpackJsonp=window.webpackJsonp||[]).push([[92],{"4Nrv":function(t,a,e){"use strict";(function(t){var s=e("1F/t"),i={faq_id:"",faq_faqcat_id:"",faq_category:"",faq_title:"",faq_content:"",faq_publish:"",faq_display_order:""},r={search:""};a.a={components:{"vue-bootstrap-typeahead":s.a},data:function(){return{records:[],baseUrl:baseUrl,pagination:[],clicked:0,loading:!1,adminsData:i,searchData:r,search:"",selectedPage:"",categories:[],recordId:"",faqArray:[],editText:"",maxCharacterLimit:"30",createdByUser:"",updatedByUser:"",updatedAt:"",createdAt:"",pageLoaded:0,showEmpty:1}},computed:{isComplete:function(){return this.adminsData.faq_title&&this.adminsData.faq_content&&this.adminsData.faq_category}},watch:{$route:"refreshedSearchData"},methods:{refreshedSearchData:function(){this.$route.query.s&&(this.searchData.search=this.$route.query.s),this.pageRecords(1,!0)},onSelectCategory:function(t){this.adminsData.faq_category=t},emptyFormValues:function(){this.adminsData={faq_id:"",faq_faqcat_id:"",faq_category:"",faq_title:"",faq_content:"",faq_publish:"",faq_display_order:""},this.editText="",this.errors.clear()},pageRecords:function(t){var a=this,e=arguments.length>1&&void 0!==arguments[1]&&arguments[1];this.loading=e;var s=this.$serializeData(this.searchData);void 0!==this.pagination.per_page&&s.append("per_page",this.pagination.per_page),this.$http.post(adminBaseUrl+"/faqs/list?page="+t,s).then((function(t){a.records=t.data.data.faqs.data,a.pagination=t.data.data.faqs,a.categories=t.data.data.categories,a.faqArray=Object.values(a.categories),a.loading=!1,a.showEmpty=t.data.data.showEmpty,a.pageLoaded=1}))},searchRecords:function(){!1!==this.selectedPage&&(this.selectedPage=!1),this.pageRecords(this.pagination.current_page)},currentPage:function(t){this.pageRecords(t)},clearSearch:function(){this.searchData={search:""},this.pageRecords(this.pagination.current_page)},validateRecord:function(){var t=this;this.$validator.validateAll().then((function(a){if(a){var e=t.adminsData;t.clicked=1,""!=e.faq_id?t.updateRecord(e.faq_id,e):t.saveRecord(e)}else t.clicked=0}))},saveRecord:function(a){var e=this;a.faq_display_order=0;var s=this.$serializeData(a);this.$http.post(adminBaseUrl+"/faqs",s).then((function(a){e.clicked=0,0!=a.data.status?(t.success(a.data.message,"",t.options),e.pageRecords(e.pagination.current_page),e.selectedPage="",e.emptyFormValues()):t.error(a.data.message,"",t.options)}),(function(t){e.clicked=0,e.validateErrors(t)}))},editRecord:function(t){this.emptyUpdatedFieldValue(),this.adminsData.faq_id=t.faq_id,this.adminsData.faq_faqcat_id=t.faq_faqcat_id,this.adminsData.faq_category=t.category.faqcat_name,this.adminsData.faq_title=t.faq_title,this.adminsData.faq_content=t.faq_content,this.adminsData.faq_publish=t.faq_publish,this.adminsData.faq_display_order=t.faq_display_order,this.editText=t.faq_title,this.selectedPage="editform",null!=t.created_at&&"admin_username"in t.created_at&&(this.createdByUser=t.created_at.admin_username),null!=t.updated_at&&"admin_username"in t.updated_at&&(this.updatedByUser=t.updated_at.admin_username),this.updatedAt=t.faq_updated_at?t.faq_updated_at:"",this.createdAt=t.faq_created_at?t.faq_created_at:"";var a=this;setTimeout((function(){a.$refs.categoryTypeahead.inputValue=t.category.faqcat_name}),5)},updateRecord:function(a,e){var s=this,i=this.$serializeData({faq_category:e.faq_category,faq_title:e.faq_title,faq_content:e.faq_content,faq_display_order:e.faq_display_order});i.append("_method","put"),this.$http.post(adminBaseUrl+"/faqs/"+a,i).then((function(a){s.clicked=0,0!=a.data.status?(t.success(a.data.message,"",t.options),s.emptyFormValues(),s.pageRecords(s.pagination.current_page),s.selectedPage=""):t.error(a.data.message,"",t.options)}),(function(t){s.clicked=0,s.validateErrors(t)}))},openAddPage:function(){this.emptyFormValues(),this.selectedPage="addform",""!=this.searchData.search&&(this.searchData.search="",this.pageRecords()),void 0!==this.$refs.categoryTypeahead&&(this.$refs.categoryTypeahead.inputValue=""),this.showEmpty=0},cancel:function(){this.selectedPage=!1,0==this.records.length&&(this.showEmpty=1)},validateErrors:function(t){var a=this,e=t.data;Object.keys(e.errors).forEach((function(t){a.errors.add({field:t,msg:e.errors[t][0]})}))},onSwitchStatus:function(a,e){var s=this.$serializeData({status:a.target.checked});this.$http.post(adminBaseUrl+"/faqs/status/"+e,s).then((function(a){0!=a.data.status?t.success(a.data.message,"",t.options):t.error(a.data.message,"",t.options)}))},confirmDelete:function(t,a){t.stopPropagation(),this.recordId=a,this.$bvModal.show("deleteModel")},deleteRecord:function(a){var e=this;this.$http.delete(adminBaseUrl+"/faqs/"+a).then((function(a){0!=a.data.status?(t.success(a.data.message,"",t.options),e.pageRecords(e.pagination.current_page),e.$bvModal.hide("deleteModel"),e.selectedPage=""):t.error(a.data.message,"",t.options)}))},emptyUpdatedFieldValue:function(){this.createdByUser="",this.updatedByUser="",this.updatedAt="",this.createdAt=""}},mounted:function(){this.searchData.search="",this.refreshedSearchData()}}}).call(this,e("hUol"))},fBkf:function(t,a,e){"use strict";e.r(a);var s=e("4Nrv").a,i=e("KHd+"),r=Object(i.a)(s,(function(){var t=this,a=t._self._c;return a("div",[a("div",{staticClass:"subheader",attrs:{id:"subheader"}},[a("div",{staticClass:"container"},[a("div",{staticClass:"subheader__main"},[a("h3",{staticClass:"subheader__title"},[t._v(t._s(t.$t("LBL_FAQS")))]),t._v(" "),a("div",{staticClass:"subheader__breadcrumbs"},[a("router-link",{staticClass:"subheader__breadcrumbs-home",attrs:{to:{name:"dashboard"}}},[a("i",{staticClass:"flaticon2-shelter"})]),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-separator"}),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-link"},[t._v(t._s(t.$t("LBL_CMS")))]),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-separator"}),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-link"},[t._v(t._s(t.$t("LBL_FAQS")))])],1)]),t._v(" "),a("div",{staticClass:"subheader__toolbar"},[a("a",{staticClass:"btn btn-white",class:[t.$canWrite("FAQ")?"":"disabledbutton no-drop"],attrs:{href:"javascript:void(0);"},on:{click:t.openAddPage}},[a("i",{staticClass:"fas fa-plus"}),t._v("\n          "+t._s(t.$t("BTN_ADD"))+"\n        ")])])])]),t._v(" "),a("div",{staticClass:"container"},[a("div",{staticClass:"row"},[a("div",{class:[1==t.showEmpty?"col-md-12":"col-md-8"]},[a("div",{staticClass:"portlet portlet--height-fluid"},[0==t.showEmpty?a("div",{staticClass:"portlet__body pb-0 bg-gray flex-grow-0"},[a("div",{staticClass:"row mb-4"},[a("div",{staticClass:"col-lg-12"},[a("div",{staticClass:"input-icon input-icon--left"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.searchData.search,expression:"searchData.search"}],staticClass:"form-control",attrs:{type:"text",placeholder:t.$t("PLH_SEARCH"),id:"generalSearch",readonly:0==t.records.length&&""===t.searchData.search,maxlength:t.maxCharacterLimit},domProps:{value:t.searchData.search},on:{keyup:t.searchRecords,input:function(a){a.target.composing||t.$set(t.searchData,"search",a.target.value)}}}),t._v(" "),t._m(0),t._v(" "),""!=t.searchData.search?a("span",{staticClass:"input-icon__icon input-icon__icon--right",on:{click:function(a){t.searchData.search="",t.selectedPage=!1,t.pageRecords(1)}}},[t._m(1)]):t._e()])])])]):t._e(),t._v(" "),a("hr",{staticClass:"m-0"}),t._v(" "),0==t.showEmpty&&1==t.pageLoaded?a("div",{staticClass:"portlet__body portlet__body--fit"},[a("div",{staticClass:"section"},[a("div",{staticClass:"section__content"},[a("table",{staticClass:"table table-data table-justified"},[a("thead",[a("tr",[a("th",[t._v(t._s("#"))]),t._v(" "),a("th",[t._v(t._s(t.$t("LBL_FAQS_TITLE")))]),t._v(" "),a("th",[t._v(t._s(t.$t("LBL_FAQS_CATEGORY")))]),t._v(" "),a("th",[t._v(t._s(t.$t("LBL_PUBLISH")))]),t._v(" "),a("th")])]),t._v(" "),0==t.loading&&0==t.records.length&&""!=t.searchData.search&&0==t.showEmpty&&1==t.pageLoaded?a("tbody",[a("tr",[a("td",{attrs:{colspan:"4"}},[a("noRecordsFound")],1)])]):a("tbody",t._l(t.records,(function(e,s){return a("tr",{key:e.faq_id,attrs:{"data-id":e.faq_id}},[a("td",{attrs:{scope:"row"}},[t._v(t._s(t.pagination.from+s))]),t._v(" "),a("td",{attrs:{width:"50%"}},[t.$canWrite("FAQ")?a("div",[t._v(t._s(e.faq_title))]):a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return a.preventDefault(),t.editRecord(e)}}},[t._v(t._s(e.faq_title))])]),t._v(" "),a("td",{attrs:{width:"15%"}},[t._v(t._s(e.category.faqcat_name))]),t._v(" "),a("td",[a("label",{staticClass:"switch switch--sm"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.faq_publish,expression:"record.faq_publish"}],attrs:{type:"checkbox",name:"faq_publish",disabled:!t.$canWrite("FAQ")},domProps:{checked:Array.isArray(e.faq_publish)?t._i(e.faq_publish,null)>-1:e.faq_publish},on:{change:[function(a){var s=e.faq_publish,i=a.target,r=!!i.checked;if(Array.isArray(s)){var d=t._i(s,null);i.checked?d<0&&t.$set(e,"faq_publish",s.concat([null])):d>-1&&t.$set(e,"faq_publish",s.slice(0,d).concat(s.slice(d+1)))}else t.$set(e,"faq_publish",r)},function(a){return t.onSwitchStatus(a,e.faq_id)}]}}),t._v(" "),a("span",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],attrs:{title:1==e.faq_publish?t.$t("TTL_UNPUBLISH"):t.$t("TTL_PUBLISH")}})])]),t._v(" "),a("td",[a("div",{staticClass:"actions"},[a("a",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],staticClass:"btn btn-light btn-sm btn-icon",class:[t.$canWrite("FAQ")?"":"disabled no-drop"],attrs:{href:"javascript:;",title:t.$t("TTL_EDIT")},on:{click:function(a){return t.editRecord(e)}}},[a("svg",[a("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#edit-icon"}})])]),t._v(" "),a("a",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],staticClass:"btn btn-light btn-sm btn-icon",class:[t.$canWrite("FAQ")?"":"disabled no-drop"],attrs:{href:"javascript:;",title:t.$t("TTL_DELETE")},on:{"!click":function(a){return t.confirmDelete(a,e.faq_id)}}},[a("svg",[a("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#delete-icon"}})])])])])])})),0)])])])]):t._e(),t._v(" "),1==t.showEmpty&&1==t.pageLoaded?a("div",{staticClass:"portlet__body"},[a("div",{staticClass:"get-started"},[a("div",{staticClass:"get-started-arrow d-flex justify-content-end mb-5"},[a("img",{attrs:{src:t.baseUrl+"/admin/images/get-started-graphic/get-started-arrow-head.svg"}})]),t._v(" "),a("div",{staticClass:"no-content d-flex text-center flex-column mb-7"},[a("p",[t._v("\n                  "+t._s(t.$t("LBL_CLICK_THE"))+"\n                  "),a("a",{staticClass:"btn btn-brand btn-small",attrs:{href:"javascript:;"},on:{click:t.openAddPage}},[a("i",{staticClass:"fas fa-plus"}),t._v("\n                    "+t._s(t.$t("BTN_ADD"))+"\n                  ")]),t._v("\n                  "+t._s(t.$t("LBL_BUTTON_TO_CREATE_FAQ"))+"\n                ")])]),t._v(" "),a("div",{staticClass:"get-started-media"},[a("img",{attrs:{src:t.baseUrl+"/admin/images/get-started-graphic/get-started-faq.svg"}})])])]):t._e(),t._v(" "),t.loading?a("loader"):t._e(),t._v(" "),t.records.length>0?a("div",{staticClass:"portlet__foot"},[a("pagination",{attrs:{pagination:t.pagination},on:{currentPage:t.currentPage}})],1):t._e()],1)]),t._v(" "),0==t.showEmpty?a("div",{staticClass:"col-md-4"},[a("div",{staticClass:"portlet portlet--height-fluid"},[t.selectedPage?a("div",{staticClass:"portlet__head"},[a("div",{staticClass:"portlet__head-label"},["editform"==t.selectedPage?a("h3",{staticClass:"portlet__head-title"},[t._v("\n                "+t._s(t.$canWrite("FAQ")?t.$t("LBL_EDITING")+" -":"")+"\n                "),a("span",{staticClass:"editing-txt"},[t._v(t._s(t.editText))])]):t._e(),t._v(" "),"addform"==t.selectedPage?a("h3",{staticClass:"portlet__head-title"},[t._v(t._s(t.$t("LBL_NEW_FAQ_SETUP")))]):t._e()]),t._v(" "),"editform"==t.selectedPage?a("div",{staticClass:"portlet__head-toolbar"},[t._m(2),t._v(" "),a("b-popover",{staticClass:"popover-cover",attrs:{target:"tooltip-target-1",triggers:"hover",placement:"top"}},[a("div",{staticClass:"list-stats"},[a("div",{staticClass:"lable"},[a("span",{staticClass:"stats_title"},[t._v(t._s(t.$t("LBL_CREATED"))+":")]),t._v(" "),a("span",{staticClass:"time"},[t._v(t._s(t.createdByUser?t.createdByUser+" |":"")+" "+t._s(t._f("formatDateTime")(t.createdAt)))])]),t._v(" "),a("div",{staticClass:"lable"},[a("span",{staticClass:"stats_title"},[t._v(t._s(t.$t("LBL_LAST_UPDATED"))+":")]),t._v(" "),a("span",{staticClass:"time"},[t._v(t._s(t.updatedByUser?t.updatedByUser+" |":"")+" "+t._s(t._f("formatDateTime")(t.updatedAt)))])])])])],1):t._e()]):t._e(),t._v(" "),t.selectedPage?a("div",{staticClass:"portlet__body",class:[t.$canWrite("FAQ")?"":"disabledbutton"]},[""!=t.adminsData.faq_id?a("input",{directives:[{name:"model",rawName:"v-model",value:t.adminsData.faq_id,expression:"adminsData.faq_id"}],attrs:{type:"hidden",name:"id"},domProps:{value:t.adminsData.faq_id},on:{input:function(a){a.target.composing||t.$set(t.adminsData,"faq_id",a.target.value)}}}):t._e(),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group"},[a("label",[t._v("\n                    "+t._s(t.$t("LBL_CATEGORY"))+"\n                    "),a("span",{staticClass:"required"},[t._v("*")])]),t._v(" "),a("vue-bootstrap-typeahead",{ref:"categoryTypeahead",attrs:{data:t.faqArray,placeholder:t.$t("LBL_START_TYPING_CATEGORY_NAME"),"max-matches":5,"min-matching-chars":1},on:{hit:t.onSelectCategory},model:{value:t.adminsData.faq_category,callback:function(a){t.$set(t.adminsData,"faq_category",a)},expression:"adminsData.faq_category"}}),t._v(" "),t.errors.first("faq_category")?a("span",{staticClass:"error text-danger"},[t._v(t._s(t.errors.first("faq_category")))]):t._e()],1)])]),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group"},[a("label",[t._v("\n                    "+t._s(t.$t("LBL_FAQS_TITLE"))+"\n                    "),a("span",{staticClass:"required"},[t._v("*")])]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.adminsData.faq_title,expression:"adminsData.faq_title"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"form-control",attrs:{type:"text",name:"faq_title","data-vv-as":t.$t("LBL_FAQS_TITLE"),"data-vv-validate-on":"none"},domProps:{value:t.adminsData.faq_title},on:{input:function(a){a.target.composing||t.$set(t.adminsData,"faq_title",a.target.value)}}}),t._v(" "),t.errors.first("faq_title")?a("span",{staticClass:"error text-danger"},[t._v(t._s(t.errors.first("faq_title")))]):t._e()])])]),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group"},[a("label",[t._v("\n                    "+t._s(t.$t("LBL_FAQS_CONTENT"))+"\n                    "),a("span",{staticClass:"required"},[t._v("*")])]),t._v(" "),a("textarea",{directives:[{name:"model",rawName:"v-model",value:t.adminsData.faq_content,expression:"adminsData.faq_content"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"form-control",attrs:{rows:"5",cols:"15",name:"faq_content","data-vv-as":t.$t("LBL_FAQS_CONTENT"),"data-vv-validate-on":"none"},domProps:{value:t.adminsData.faq_content},on:{input:function(a){a.target.composing||t.$set(t.adminsData,"faq_content",a.target.value)}}}),t._v(" "),t.errors.first("faq_content")?a("span",{staticClass:"error text-danger"},[t._v(t._s(t.errors.first("faq_content")))]):t._e()])])])]):t._e(),t._v(" "),""==t.selectedPage?a("div",{staticClass:"portlet__body"},[a("div",{staticClass:"no-data-found"},[a("div",{staticClass:"img"},[a("img",{attrs:{src:this.$noDataImage(),alt:""}})]),t._v(" "),a("div",{staticClass:"data"},[a("p",[t._v(t._s(t.$t("LBL_USE_ICONS_FOR_ADVANCED_ACTIONS")))])])])]):t._e(),t._v(" "),""!=t.selectedPage?a("div",{staticClass:"portlet__foot"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col"},[a("button",{staticClass:"btn btn-secondary",attrs:{type:"reset"},on:{click:function(a){return t.cancel()}}},[t._v(t._s(t.$t("BTN_DISCARD")))])]),t._v(" "),a("div",{staticClass:"col-auto"},["addform"==t.selectedPage?a("button",{staticClass:"btn btn-brand gb-btn gb-btn-primary",class:1==t.clicked&&"gb-is-loading",attrs:{type:"submit",disabled:!t.isComplete||1==t.clicked||!t.$canWrite("FAQ")},on:{click:function(a){return t.validateRecord()}}},[t._v(t._s(t.$t("BTN_FAQS_CREATE")))]):t._e(),t._v(" "),"editform"==t.selectedPage?a("button",{staticClass:"btn btn-brand gb-btn gb-btn-primary",class:1==t.clicked&&"gb-is-loading",attrs:{type:"submit",disabled:!t.isComplete||1==t.clicked||!t.$canWrite("FAQ")},on:{click:function(a){return t.validateRecord()}}},[t._v(t._s(t.$t("BTN_FAQS_UPDATE")))]):t._e()])])]):t._e()])]):t._e(),t._v(" "),a("DeleteModel",{attrs:{deletePopText:t.$t("LBL_FAQS_DELETE_CONFIRMATION"),recordId:t.recordId},on:{deleted:function(a){return t.deleteRecord(t.recordId)}}})],1)])])}),[function(){var t=this._self._c;return t("span",{staticClass:"input-icon__icon input-icon__icon--left"},[t("span",[t("i",{staticClass:"la la-search"})])])},function(){var t=this._self._c;return t("span",[t("i",{staticClass:"fas fa-times"})])},function(){var t=this._self._c;return t("div",{staticClass:"portlet__head-actions",attrs:{id:"tooltip-target-1"}},[t("i",{staticClass:"fas fa-info-circle"})])}],!1,null,null,null);a.default=r.exports}}]);