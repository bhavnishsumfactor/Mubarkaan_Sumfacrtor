(window.webpackJsonp=window.webpackJsonp||[]).push([[116],{"2V0B":function(t,a,e){"use strict";(function(t){var e={module_type:"",record_id:"",record_title:"",page_type:"",meta_title:"",meta_keywords:"",meta_description:"",meta_other_meta_tags:""},s={search:""};a.a={data:function(){return{baseUrl:baseUrl,records:[],pagination:[],clicked:0,loading:!1,adminsData:e,searchData:s,search:"",selectedPage:"",recordId:"",module_type:"pages",createdByUser:"",updatedByUser:"",updatedAt:"",createdAt:""}},methods:{emptyFormValues:function(){this.adminsData={module_type:"",record_id:"",record_title:"",page_type:"",meta_title:"",meta_keywords:"",meta_description:"",meta_other_meta_tags:""},this.errors.clear()},pageRecords:function(t){var a=this,e=arguments.length>1&&void 0!==arguments[1]&&arguments[1];this.loading=e;var s=this.$serializeData(this.searchData);s.append("module_type",this.module_type),void 0!==this.pagination.per_page&&s.append("per_page",this.pagination.per_page),this.$http.post(adminBaseUrl+"/metatags/modulelist?page="+t,s).then((function(t){a.records=t.data.data.records.data,a.pagination=t.data.data.records,a.loading=!1}))},selectModule:function(t){this.module_type=t,this.pageRecords(1),this.cancel(),this.emptyFormValues()},searchRecords:function(){this.pageRecords(1)},currentPage:function(t){this.pageRecords(t)},editRecord:function(a){var e=this;void 0===a&&(a="pages"==this.module_type?{record_id:"",record_title:this.$t("LBL_DEFAULT_META_SETTINGS")}:{record_id:"",record_title:this.$t("LBL_LISTING_PAGE")});var s=this.module_type;"products"==this.module_type&&""==a.record_id?s="productslisting":"blogs"==this.module_type&&""==a.record_id?s="blogslisting":void 0!==a.page_type&&"faq"==a.page_type&&(s="faqs",a.record_id="");var i=this.$serializeData({module_type:s,record_id:a.record_id});this.$http.post(adminBaseUrl+"/metatags/get",i).then((function(s){0!=s.data.status?(e.emptyFormValues(),e.emptyUpdatedFieldValue(),e.adminsData.module_type=e.module_type,e.adminsData.record_id=a.record_id,e.adminsData.record_title=a.record_title,void 0!==a.page_type&&(e.adminsData.page_type=a.page_type),null!=s.data.data&&(e.adminsData.meta_title=s.data.data.meta_title,e.adminsData.meta_keywords=s.data.data.meta_keywords,e.adminsData.meta_description=s.data.data.meta_description,e.adminsData.meta_other_meta_tags=s.data.data.meta_other_meta_tags,null!=s.data.data.created_at&&"admin_username"in s.data.data.created_at&&(e.createdByUser=s.data.data.created_at.admin_username),null!=s.data.data.updated_at&&"admin_username"in s.data.data.updated_at&&(e.updatedByUser=s.data.data.updated_at.admin_username),e.updatedAt=s.data.data.meta_updated_at?s.data.data.meta_updated_at:"",e.createdAt=s.data.data.meta_created_at?s.data.data.meta_created_at:"")):t.error(s.data.message,"",t.options)}),(function(t){})),this.selectedPage="editform"},updateRecord:function(){var a=this;this.$validator.validateAll().then((function(e){if(e){a.clicked=1,"products"==a.adminsData.module_type&&""==a.adminsData.record_id?a.adminsData.module_type="productslisting":"blogs"==a.adminsData.module_type&&""==a.adminsData.record_id?a.adminsData.module_type="blogslisting":void 0!==a.adminsData.page_type&&"faq"==a.adminsData.page_type&&(a.adminsData.module_type="faqs",a.adminsData.record_id="");var s=a.$serializeData(a.adminsData);a.$http.post(adminBaseUrl+"/metatags/update",s).then((function(e){a.clicked=0,0!=e.data.status?(t.success(e.data.message,"",t.options),a.emptyFormValues(),a.pageRecords(a.pagination.current_page),a.selectedPage=""):t.error(e.data.message,"",t.options)}),(function(t){a.clicked=0,a.validateErrors(t)}))}else a.clicked=0}))},cancel:function(){this.selectedPage=!1},validateErrors:function(t){var a=this,e=t.data;Object.keys(e.errors).forEach((function(t){a.errors.add({field:t,msg:e.errors[t][0]})}))},emptyUpdatedFieldValue:function(){this.createdByUser="",this.updatedByUser="",this.updatedAt="",this.createdAt=""}},mounted:function(){this.searchData={search:""},this.pageRecords(1,!0)}}}).call(this,e("hUol"))},yVlT:function(t,a,e){"use strict";e.r(a);var s=e("2V0B").a,i=e("KHd+"),r=Object(i.a)(s,(function(){var t=this,a=t._self._c;return a("div",[a("div",{staticClass:"subheader",attrs:{id:"subheader"}},[a("div",{staticClass:"container"},[a("div",{staticClass:"subheader__main"},[a("h3",{staticClass:"subheader__title"},[t._v(t._s(t.$t("LBL_META_TAGS")))]),t._v(" "),a("div",{staticClass:"subheader__breadcrumbs"},[a("router-link",{staticClass:"subheader__breadcrumbs-home",attrs:{to:{name:"dashboard"}}},[a("i",{staticClass:"flaticon2-shelter"})]),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-separator"}),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-link"},[t._v(t._s(t.$t("LBL_SEO")))]),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-separator"}),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-link"},[t._v(t._s(t.$t("LBL_META_TAGS")))])],1)])])]),t._v(" "),a("div",{staticClass:"container"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"portlet portlet--height-fluid"},[a("div",{staticClass:"portlet__body pb-0 bg-gray flex-grow-0"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group"},[a("div",{staticClass:"input-group input-icon input-icon--left"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.searchData.search,expression:"searchData.search"}],staticClass:"form-control",attrs:{type:"text",placeholder:t.$t("PLH_SEARCH"),id:"generalSearch"},domProps:{value:t.searchData.search},on:{keyup:t.searchRecords,input:function(a){a.target.composing||t.$set(t.searchData,"search",a.target.value)}}}),t._v(" "),t._m(0),t._v(" "),a("div",{staticClass:"input-group-append"},[a("button",{staticClass:"btn btn-default dropdown-toggle",attrs:{type:"button","data-toggle":"dropdown","aria-expanded":"true"}},[t._v("\n                                                "+t._s(t._f("camelCase")(t.module_type))+"\n                                            ")]),t._v(" "),a("div",{staticClass:"dropdown-menu",attrs:{"x-placement":"top-start"}},[a("a",{staticClass:"dropdown-item",attrs:{href:"javascript:;"},on:{click:function(a){return t.selectModule("pages")}}},[t._v(t._s(t.$t("LBL_PAGES")))]),t._v(" "),a("a",{staticClass:"dropdown-item",attrs:{href:"javascript:;"},on:{click:function(a){return t.selectModule("products")}}},[t._v(t._s(t.$t("LBL_PRODUCTS")))]),t._v(" "),a("a",{staticClass:"dropdown-item",attrs:{href:"javascript:;"},on:{click:function(a){return t.selectModule("brands")}}},[t._v(t._s(t.$t("LBL_BRANDS")))]),t._v(" "),a("a",{staticClass:"dropdown-item",attrs:{href:"javascript:;"},on:{click:function(a){return t.selectModule("categories")}}},[t._v(t._s(t.$t("LBL_CATEGORIES")))]),t._v(" "),a("a",{staticClass:"dropdown-item",attrs:{href:"javascript:;"},on:{click:function(a){return t.selectModule("blogs")}}},[t._v(t._s(t.$t("LBL_BLOGS")))])])])])])])])]),t._v(" "),a("hr",{staticClass:"m-0"}),t._v(" "),t.records.length>0?a("div",{staticClass:"portlet__body portlet__body--fit"},[a("div",{staticClass:"section"},[a("div",{staticClass:"section__content"},[a("table",{staticClass:"table table-data table-justified"},[a("thead",[a("tr",[a("th",[t._v(t._s("#"))]),t._v(" "),a("th",[t._v(t._s(t.$t("LBL_MODULE_NAME")))]),t._v(" "),a("th")])]),t._v(" "),a("tbody",["products"!=t.module_type&&"blogs"!=t.module_type&&"pages"!=t.module_type||1!=this.pagination.current_page||""!=t.searchData.search?t._e():a("tr",[a("td",{attrs:{scope:"row"}},[t._v("1")]),t._v(" "),"pages"!=t.module_type?a("td",[t._v("\n                                                "+t._s(t.$t("LBL_LISTING_PAGE"))+"\n                                            ")]):t._e(),t._v(" "),"pages"==t.module_type?a("td",[t._v("\n                                                "+t._s(t.$t("LBL_DEFAULT_META_SETTINGS"))+"\n                                            ")]):t._e(),t._v(" "),a("td",[a("div",{staticClass:"actions"},[a("a",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],staticClass:"btn btn-light btn-sm btn-icon",class:[t.$canWrite("META_TAGS")?"":"disabled no-drop"],attrs:{href:"javascript:;",title:t.$t("TTL_EDIT")},on:{click:function(a){return t.editRecord()}}},[a("svg",[a("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#edit-icon"}})])])])])]),t._v(" "),t._l(t.records,(function(e,s){return a("tr",{key:e.record_id,attrs:{"data-id":e.record_id}},[1!=t.pagination.current_page||"products"!=t.module_type&&"blogs"!=t.module_type&&"pages"!=t.module_type||""!=t.searchData.search?a("td",{attrs:{scope:"row"}},[t._v("\n                                                "+t._s(t.pagination.from+s)+"\n                                            ")]):a("td",{attrs:{scope:"row"}},[t._v("\n                                                "+t._s(t.pagination.from+s+1)+"\n                                            ")]),t._v(" "),a("td",[t.$canWrite("META_TAGS")?a("div",[t._v(t._s(e.record_title))]):a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return a.preventDefault(),t.editRecord(e)}}},[t._v(t._s(e.record_title))])]),t._v(" "),a("td",[a("div",{staticClass:"actions"},[a("a",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],staticClass:"btn btn-light btn-sm btn-icon",class:[t.$canWrite("META_TAGS")?"":"disabled no-drop"],attrs:{href:"javascript:;",title:t.$t("TTL_EDIT")},on:{click:function(a){return t.editRecord(e)}}},[a("svg",[a("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#edit-icon"}})])])])])])}))],2)])])])]):t._e(),t._v(" "),t.loading?a("loader"):t._e(),t._v(" "),0==t.loading&&0==t.records.length?a("noRecordsFound"):t._e(),t._v(" "),t.records.length>0?a("div",{staticClass:"portlet__foot"},[a("pagination",{attrs:{pagination:t.pagination},on:{currentPage:t.currentPage}})],1):t._e()],1)]),t._v(" "),a("div",{staticClass:"col-md-6"},[a("div",{staticClass:"portlet portlet--height-fluid"},[t.selectedPage?a("div",{staticClass:"portlet__head"},[a("div",{staticClass:"portlet__head-label"},["editform"==t.selectedPage?a("h3",{staticClass:"portlet__head-title"},[t._v("\n                                "+t._s(t.$canWrite("META_TAGS")?t.$t("LBL_EDITING")+" -":"")+" "+t._s(t.adminsData.record_title)+"\n                            ")]):t._e()]),t._v(" "),"editform"==t.selectedPage?a("div",{staticClass:"portlet__head-toolbar"},[t._m(1),t._v(" "),a("b-popover",{staticClass:"popover-cover",attrs:{target:"tooltip-target-1",triggers:"hover",placement:"top"}},[a("div",{staticClass:"list-stats"},[a("div",{staticClass:"lable"},[a("span",{staticClass:"stats_title"},[t._v(t._s(t.$t("LBL_CREATED"))+":")]),t._v(" "),a("span",{staticClass:"time"},[t._v(t._s(t.createdByUser?t.createdByUser+" |":"")+" "+t._s(t._f("formatDateTime")(t.createdAt)))])]),t._v(" "),a("div",{staticClass:"lable"},[a("span",{staticClass:"stats_title"},[t._v(t._s(t.$t("LBL_LAST_UPDATED"))+":")]),t._v(" "),a("span",{staticClass:"time"},[t._v(t._s(t.updatedByUser?t.updatedByUser+" |":"")+" "+t._s(t._f("formatDateTime")(t.updatedAt)))])])])])],1):t._e()]):t._e(),t._v(" "),t.selectedPage?a("div",{staticClass:"portlet__body",class:[t.$canWrite("META_TAGS")?"":"disabledbutton"]},[""!=t.adminsData.meta_id?a("input",{directives:[{name:"model",rawName:"v-model",value:t.adminsData.meta_id,expression:"adminsData.meta_id"}],attrs:{type:"hidden",name:"id"},domProps:{value:t.adminsData.meta_id},on:{input:function(a){a.target.composing||t.$set(t.adminsData,"meta_id",a.target.value)}}}):t._e(),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group"},[a("label",[t._v(t._s(t.$t("LBL_META_TITLE")))]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.adminsData.meta_title,expression:"adminsData.meta_title"}],staticClass:"form-control",attrs:{type:"text",name:"meta_title"},domProps:{value:t.adminsData.meta_title},on:{input:function(a){a.target.composing||t.$set(t.adminsData,"meta_title",a.target.value)}}})])])]),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group"},[a("label",[t._v(t._s(t.$t("LBL_META_KEYWORDS"))+" ")]),t._v(" "),a("textarea",{directives:[{name:"model",rawName:"v-model",value:t.adminsData.meta_keywords,expression:"adminsData.meta_keywords"}],staticClass:"form-control",attrs:{rows:"5",cols:"15",name:"meta_keywords"},domProps:{value:t.adminsData.meta_keywords},on:{input:function(a){a.target.composing||t.$set(t.adminsData,"meta_keywords",a.target.value)}}})])])]),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group"},[a("label",[t._v(t._s(t.$t("LBL_META_DESCRIPTION"))+" ")]),t._v(" "),a("textarea",{directives:[{name:"model",rawName:"v-model",value:t.adminsData.meta_description,expression:"adminsData.meta_description"}],staticClass:"form-control",attrs:{rows:"5",cols:"15",name:"meta_description"},domProps:{value:t.adminsData.meta_description},on:{input:function(a){a.target.composing||t.$set(t.adminsData,"meta_description",a.target.value)}}})])])]),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-group"},[a("label",[t._v(t._s(t.$t("LBL_OTHER_META_TAGS"))+" ")]),t._v(" "),a("textarea",{directives:[{name:"model",rawName:"v-model",value:t.adminsData.meta_other_meta_tags,expression:"adminsData.meta_other_meta_tags"}],staticClass:"form-control",attrs:{rows:"5",cols:"15",name:"meta_other_meta_tags"},domProps:{value:t.adminsData.meta_other_meta_tags},on:{input:function(a){a.target.composing||t.$set(t.adminsData,"meta_other_meta_tags",a.target.value)}}}),t._v(" "),a("span",{staticClass:"form-text text-muted"},[t._v(t._s(t.$t("LBL_FOR_EXAMPLE"))+": "+t._s('<meta name="copyright" content="text">'))]),t._v(" "),t.errors.first("meta_other_meta_tags")?a("span",{staticClass:"error text-danger"},[t._v(t._s(t.errors.first("meta_other_meta_tags")))]):t._e()])])])]):t._e(),t._v(" "),""==t.selectedPage?a("div",{staticClass:"portlet__body"},[a("div",{staticClass:"no-data-found"},[a("div",{staticClass:"img"},[a("img",{attrs:{src:this.$noDataImage(),alt:""}})]),t._v(" "),a("div",{staticClass:"data"},[a("p",[t._v("\n                                    "+t._s(t.$t("LBL_USE_ICONS_FOR_ADVANCED_ACTIONS"))+"\n                                ")])])])]):t._e(),t._v(" "),""!=t.selectedPage?a("div",{staticClass:"portlet__foot"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col"},[a("button",{staticClass:"btn btn-secondary",attrs:{type:"reset"},on:{click:function(a){return t.cancel()}}},[t._v("\n                                    "+t._s(t.$t("BTN_DISCARD"))+"\n                                ")])]),t._v(" "),a("div",{staticClass:"col-auto"},["editform"==t.selectedPage?a("button",{staticClass:"btn btn-brand gb-btn gb-btn-primary",class:1==t.clicked&&"gb-is-loading",attrs:{type:"submit",disabled:1==t.clicked||!t.$canWrite("META_TAGS")},on:{click:function(a){return t.updateRecord()}}},[t._v("\n                                    "+t._s(t.$t("BTN_META_TAGS_UPDATE"))+"\n                                ")]):t._e()])])]):t._e()])])])])])}),[function(){var t=this._self._c;return t("span",{staticClass:"input-icon__icon input-icon__icon--left"},[t("span",[t("i",{staticClass:"la la-search"})])])},function(){var t=this._self._c;return t("div",{staticClass:"portlet__head-actions",attrs:{id:"tooltip-target-1"}},[t("i",{staticClass:"fas fa-info-circle"})])}],!1,null,null,null);a.default=r.exports}}]);