(window.webpackJsonp=window.webpackJsonp||[]).push([[100],{BzZu:function(t,e,a){"use strict";a.r(e);var i=a("Wpc0").a,s=a("KHd+"),l=Object(s.a)(i,(function(){var t=this,e=t._self._c;return e("div",[e("div",{staticClass:"subheader",attrs:{id:"subheader"}},[e("div",{staticClass:"container"},[e("div",{staticClass:"subheader__main"},[e("h3",{staticClass:"subheader__title"},[t._v(t._s(t.$t("LBL_MOBILE_APPS")))])])])]),t._v(" "),e("div",{staticClass:"container"},[e("div",{staticClass:"portlet"},[e("div",{staticClass:"portlet__body portlet__body--fit"},[e("div",{staticClass:"app-page"},[e("sidebar",{attrs:{tab:t.type}}),t._v(" "),e("div",{staticClass:"app-main"},[e("div",{staticClass:"row justify-content-center"},[t._m(0),t._v(" "),e("div",{staticClass:"col-md-7"},[e("div",{staticClass:"device__preview-wrapper"},[e("div",{staticClass:"device__preview"},[e("div",{staticClass:"device__screen"},[t._m(1),t._v(" "),e("div",{staticClass:"scroll-y",staticStyle:{"max-height":"496px","min-height":"496px",padding:"0"}},[e("div",{staticClass:"page__content"},[e("div",{staticClass:"page_title"},[t.editField&&"LBL_EXPLORE"==t.editField.editKey?e("input",{directives:[{name:"model",rawName:"v-model",value:t.editField.editValue,expression:"editField.editValue"}],staticClass:"field-value form-control",attrs:{type:"text"},domProps:{value:t.editField.editValue},on:{blur:function(e){return t.updateLabel("LBL_EXPLORE")},input:function(e){e.target.composing||t.$set(t.editField,"editValue",e.target.value)}}}):e("div",{staticClass:"actions",on:{dblclick:function(e){return t.focusField("LBL_EXPLORE")}}},[e("h4",[t._v(t._s(t.labels.LBL_EXPLORE))]),t._v(" "),e("a",{attrs:{href:"javascript:;"},on:{click:function(e){return t.focusField("LBL_EXPLORE")}}},[e("button",{attrs:{type:"button"}},[e("svg",[e("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#edit-icon"}})])])])])]),t._v(" "),e("div",{staticClass:"widgets"},[e("div",{staticClass:"widget_item"},[e("div",{staticClass:"widget_item-head"},[t.editField&&"LBL_EXTRA"==t.editField.editKey?e("input",{directives:[{name:"model",rawName:"v-model",value:t.editField.editValue,expression:"editField.editValue"}],staticClass:"field-value form-control",attrs:{type:"text"},domProps:{value:t.editField.editValue},on:{blur:function(e){return t.updateLabel("LBL_EXTRA")},input:function(e){e.target.composing||t.$set(t.editField,"editValue",e.target.value)}}}):e("div",{staticClass:"actions"},[e("h4",{on:{dblclick:function(e){return t.focusField("LBL_EXTRA")}}},[t._v(t._s(t.labels.LBL_EXTRA))]),t._v(" "),e("a",{attrs:{href:"javascript:;"},on:{click:function(e){return t.focusField("LBL_EXTRA")}}},[e("button",{attrs:{type:"button"}},[e("svg",[e("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#edit-icon"}})])])])]),t._v(" "),e("div",{staticClass:"actions"},[e("a",{staticClass:"app-nav_item",attrs:{href:"javascript:;"},on:{click:function(e){return t.addNewExplore(1)}}},[e("button",{attrs:{type:"button"}},[e("svg",[e("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#add-icon"}})])])])])]),t._v(" "),e("div",{staticClass:"widget_item-body"},[e("ul",{staticClass:"list__items"},[t._l(t.extra_pages,(function(a,i){return e("li",{key:i,staticClass:"list__item"},[e("p",{},[t._v(t._s(a.ae_title))]),t._v(" "),e("div",{staticClass:"actions"},[e("a",{staticClass:"btn btn-light btn-sm btn-icon",attrs:{href:"javascript:;"},on:{"!click":function(e){return t.confirmDelete(a.ae_id)}}},[e("svg",{attrs:{width:"18px",height:"18px"}},[e("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#delete-icon"}})])])])])})),t._v(" "),t._l(t.extraSkilton,(function(t,a){return e("li",{staticClass:"list__item"},[e("p",{staticClass:"skeleton"})])}))],2)])])]),e("div",{staticClass:"widgets"},[e("div",{staticClass:"widget_item"},[e("div",{staticClass:"widget_item-head"},[t.editField&&"LBL_QUICK_LINKS"==t.editField.editKey?e("input",{directives:[{name:"model",rawName:"v-model",value:t.editField.editValue,expression:"editField.editValue"}],staticClass:"field-value form-control",attrs:{type:"text"},domProps:{value:t.editField.editValue},on:{blur:function(e){return t.updateLabel("LBL_QUICK_LINKS")},input:function(e){e.target.composing||t.$set(t.editField,"editValue",e.target.value)}}}):e("div",{staticClass:"actions"},[e("h4",{on:{dblclick:function(e){return t.focusField("LBL_QUICK_LINKS")}}},[t._v(t._s(t.labels.LBL_QUICK_LINKS))]),t._v(" "),e("a",{attrs:{href:"javascript:;"},on:{click:function(e){return t.focusField("LBL_QUICK_LINKS")}}},[e("button",{attrs:{type:"button"}},[e("svg",[e("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#edit-icon"}})])])])]),t._v(" "),e("div",{staticClass:"actions"},[e("a",{staticClass:"app-nav_item",attrs:{href:"javascript:;"},on:{click:function(e){return t.addNewExplore(2)}}},[e("button",{attrs:{type:"button"}},[e("svg",[e("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#add-icon"}})])])])])]),t._v(" "),e("div",{staticClass:"widget_item-body"},[e("ul",{staticClass:"list__items"},[t._l(t.quick_links_pages,(function(a,i){return e("li",{key:i,staticClass:"list__item"},[e("p",{},[t._v(t._s(a.ae_title))]),t._v(" "),e("div",{staticClass:"actions"},[e("a",{staticClass:"btn btn-light btn-sm btn-icon",attrs:{href:"javascript:;"},on:{"!click":function(e){return t.confirmDelete(a.ae_id)}}},[e("svg",{attrs:{width:"18px",height:"18px"}},[e("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#delete-icon"}})])])])])})),t._v(" "),t._l(t.quickLinkSkilton,(function(t,a){return e("li",{staticClass:"list__item"},[e("p",{staticClass:"skeleton"})])}))],2)])])])])])])])])])])])],1)])])]),t._v(" "),e("b-modal",{attrs:{id:"explorePageForm",size:"md",centered:"",title:"BootstrapVue"},scopedSlots:t._u([{key:"modal-header",fn:function(){return[e("h5",{staticClass:"modal-title",attrs:{id:"exampleModalLabel"}},[e("span",[t._v("Add Explore")])]),t._v(" "),e("button",{staticClass:"close",attrs:{type:"button"},on:{click:function(e){return t.$bvModal.hide("explorePageForm")}}})]},proxy:!0},{key:"modal-footer",fn:function(){return[e("button",{staticClass:"btn btn-brand",attrs:{type:"submit",disabled:""==t.pageTitle},on:{click:t.saveExplore}},[e("span",[t._v("Save")])])]},proxy:!0}])},[t._v(" "),e("div",{staticClass:"form-group row"},[e("label",{staticClass:"col-md-12 col-form-label",attrs:{for:"validationCustom01"}},[t._v("Select Page")]),t._v(" "),e("div",{staticClass:"col-md-12"},[e("select",{directives:[{name:"model",rawName:"v-model",value:t.appPageId,expression:"appPageId"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"form-control",attrs:{"data-vv-as":t.$t("LBL_PAGE"),"data-vv-validate-on":"none"},on:{change:[function(e){var a=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.appPageId=e.target.multiple?a:a[0]},t.updatePageTitle]}},[e("option",{attrs:{value:"",disabled:""}},[t._v(t._s(t.$t("LBL_SELECT_PAGE")))]),t._v(" "),t._l(t.appPages,(function(a,i){return e("option",{key:i,domProps:{value:a.page_id}},[t._v(t._s(a.page_title))])}))],2)])]),t._v(" "),e("div",{staticClass:"form-group row"},[e("label",{staticClass:"col-md-12 col-form-label",attrs:{for:"validationCustom01"}},[t._v("Page Title")]),t._v(" "),e("div",{staticClass:"col-md-12"},[e("input",{directives:[{name:"model",rawName:"v-model",value:t.pageTitle,expression:"pageTitle"}],staticClass:"form-control",attrs:{type:"text",placeholder:"Page Title"},domProps:{value:t.pageTitle},on:{input:function(e){e.target.composing||(t.pageTitle=e.target.value)}}})])])]),t._v(" "),e("DeleteModel",{attrs:{recordId:t.selectedExploreId,deletePopText:t.$t("LBL_DELETE_PAGE_CONFIRM_TEXT")},on:{deleted:function(e){return t.deleteRecord(t.selectedExploreId)}}})],1)}),[function(){var t=this,e=t._self._c;return e("div",{staticClass:"col-md-5"},[e("div",{staticClass:"app__collections info__collections"},[e("div",{staticClass:"app__collections-head d-flex justify-content-between"},[e("h3",[t._v("Explore")])]),t._v(" "),e("div",{staticClass:"app__collections-wrapper"},[e("div",{staticClass:"content__wrapper"},[e("div",{staticClass:"content_item"},[e("p",[t._v("The explore screen will let you display/manage content screen listing in the app. The explore page is divided into 2 sections. "),e("br"),e("br"),t._v("\n                           \n                  Content screens can be added to both the sections on this page. Click on the ‘+’ icon to add a new screen. Please select from a pre-defined drop down to map the content screen and give it a display name."),e("br"),t._v("\nAvailable content screens are :"),e("br"),t._v(" "),e("ul",[e("li",[t._v("- Blog Listing ")]),t._v(" "),e("li",[t._v("- Contact us ")]),t._v(" "),e("li",[t._v("- Privacy Policy ")]),t._v(" "),e("li",[t._v("- Terms and conditions")]),t._v(" "),e("li",[t._v("- FAQ’s")]),t._v(" "),e("li",[t._v("- Dynamically created content pages.")])])])])])])])])},function(){var t=this._self._c;return t("div",{staticClass:"app__collection app__header mb-0"},[t("div",{staticClass:"app_collection-content"})])}],!1,null,null,null);e.default=l.exports},Wpc0:function(t,e,a){"use strict";(function(t){var i=a("EbCt"),s=a("P33W"),l=a("GJPm"),n=a("uQ3t"),o=a("TbjC");e.a={name:"Copy",components:{sidebar:l.a,TagsCollection:n.a,TagsCollectionForm:o.a,Container:s.Container,Draggable:s.Draggable,SlickItem:i.SlickItem,SlickList:i.SlickList},data:function(){return{pageId:3,type:"explore",clickEvent:"",selectedCollections:[],acId:"",collectionId:"",collectionType:"",baseUrl:baseUrl,records:[],labels:{LBL_EXTRA:"Extra",LBL_QUICK_LINK:"Quick Links",LBL_EXPLORE:"Explore"},appRecords:[],dropPlaceholderOptions:{className:"drop-preview",animationDuration:"150",showOnTop:!0},appPageId:"",pageTitle:"",pageType:"",appPages:[],editField:{},extra_pages:[],quick_links_pages:[],extraSkilton:3,quickLinkSkilton:3,modelId:"deleteModel",selectedExploreId:""}},methods:{sortEnd:function(t){var e=t.oldIndex,a=t.newIndex;setTimeout((function(){for(var t=Math.max(e,a),i=Math.min(e,a);i<=t;i++);}),100)},addNewExplore:function(t){this.pageTitle="",this.pageType=t,this.$bvModal.show("explorePageForm")},saveExplore:function(){var e=this,a=new FormData;a.append("page_id",this.appPageId),a.append("title",this.pageTitle),a.append("type","appExplore"),a.append("page_type",this.pageType),this.$http.post(adminBaseUrl+"/app/store/records",a).then((function(a){e.$bvModal.hide("explorePageForm"),e.getAppExplore(),e.appPageId="",t.success("Updated successfully","",t.options)}))},focusField:function(t){this.editField={editKey:t,editValue:this.labels[t]}},updateLabel:function(){var e=this,a=this.$serializeData({key:this.editField.editKey});a.append(appDefaultLanguage,this.editField.editValue),this.$http.post(adminBaseUrl+"/languageslabels/mobile/save",a).then((function(a){0!=a.data.status?(e.labels[e.editField.editKey]=e.editField.editValue,t.success(a.data.message,"",t.options),e.editField={}):t.error(a.data.message,"",t.options)}),(function(t){e.validateErrors(t)}))},getAppLanguageLabel:function(){var e=this;this.$http.post(adminBaseUrl+"/languageslabels/mobile/all",{labels:["LBL_EXTRA","LBL_QUICK_LINKS","LBL_EXPLORE"]}).then((function(a){0!=a.data.status?e.labels=a.data.data:t.error(a.data.message,"",t.options)}),(function(t){}))},getAppExplore:function(){var e=this;this.$http.post(adminBaseUrl+"/app-explore/get-data",{labels:["LBL_EXTRA","LBL_QUICK_LINKS","LBL_EXPLORE"]}).then((function(a){0!=a.data.status?(e.labels=a.data.data.language_labels,e.extra_pages=a.data.data.page_data.extra,parseInt(e.extra_pages.length)>=3?e.extraSkilton=0:e.extraSkilton=parseInt(3)-parseInt(e.extra_pages.length),e.quick_links_pages=a.data.data.page_data.quick_links,parseInt(e.quick_links_pages.length)>=3?e.quickLinkSkilton=0:e.quickLinkSkilton=parseInt(3)-parseInt(e.quick_links_pages.length),e.appPages=a.data.data.app_pages):t.error(a.data.message,"",t.options)}),(function(t){}))},updatePageTitle:function(){var t=this;this.pageTitle=JSON.parse(appPages).find((function(e,a){return e.page_id==t.appPageId})).page_title},deleteCollRecordById:function(e){var a=this,i=this.$serializeData({ae_id:e});this.$http.post(adminBaseUrl+"/app-explore/delete",i).then((function(e){0!=e.data.status?(a.$bvModal.hide(a.modelId),t.success(e.data.message,"",t.options),a.getAppExplore()):t.error(e.data.message,"",t.options)}),(function(t){}))},confirmDelete:function(t){this.selectedExploreId=t,this.$bvModal.show(this.modelId)},deleteRecord:function(t){this.deleteCollRecordById(t)}},mounted:function(){this.getAppExplore()}}}).call(this,a("hUol"))}}]);