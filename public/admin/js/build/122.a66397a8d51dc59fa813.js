(window.webpackJsonp=window.webpackJsonp||[]).push([[122],{VqGN:function(t,a,s){"use strict";s.r(a);var e=s("sP1F").a,i=s("KHd+"),r=Object(i.a)(e,(function(){var t=this,a=t._self._c;return a("div",[a("div",{staticClass:"subheader",attrs:{id:"subheader"}},[a("div",{staticClass:"container"},[a("div",{staticClass:"subheader__main"},[a("h3",{staticClass:"subheader__title"},[t._v(t._s(t.$t("LBL_TAX_MANAGEMENT")))])]),t._v(" "),a("div",{staticClass:"subheader__toolbar"},[a("router-link",{staticClass:"btn btn-white",class:[t.$canWrite("TAX_SETTINGS")?"":"disabledbutton no-drop"],attrs:{to:{name:"taxCreate"}}},[a("i",{staticClass:"fas fa-plus"}),t._v("\n          "+t._s(t.$t("BTN_ADD"))+"\n        ")])],1)])]),t._v(" "),a("div",{staticClass:"container"},[a("div",{staticClass:"row justify-content-center"},[a("div",{staticClass:"col-xl-9"},[a("div",{staticClass:"alert alert-light alert-elevate fade show",attrs:{role:"alert"}},[t._m(0),t._v(" "),a("div",{staticClass:"alert-text"},[t._v(t._s(t.$t("LBL_TAX_INFO"))+".")])])])]),t._v(" "),a("div",{staticClass:"row justify-content-center"},[a("div",{staticClass:"col-xl-9"},[a("div",{staticClass:"portlet portlet--height-fluid"},[0==t.showEmpty?a("div",{staticClass:"portlet__body pb-0 bg-gray flex-grow-0"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-lg-12"},[a("div",{staticClass:"form-group"},[a("div",{staticClass:"input-icon input-icon--left"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.search,expression:"search"}],staticClass:"form-control",attrs:{type:"text",placeholder:t.$t("PLH_SEARCH")+"...",id:"generalSearch"},domProps:{value:t.search},on:{keyup:t.searchRecords,input:function(a){a.target.composing||(t.search=a.target.value)}}}),t._v(" "),t._m(1),t._v(" "),""!=t.search?a("span",{staticClass:"input-icon__icon input-icon__icon--right",on:{click:function(a){t.search="",t.pageRecords(1)}}},[t._m(2)]):t._e()])])])])]):t._e(),t._v(" "),a("hr",{staticClass:"m-0"}),t._v(" "),0==t.showEmpty&&1==t.pageLoaded?a("div",{staticClass:"portlet__body portlet__body--fit"},[a("div",{staticClass:"section"},[a("div",{staticClass:"section__content"},[a("table",{staticClass:"table table-data table-justified"},[a("thead",[a("tr",[a("th",[t._v("#")]),t._v(" "),a("th",[t._v(t._s(t.$t("LBL_TAX_NAME")))]),t._v(" "),a("th",[t._v(t._s(t.$t("LBL_TAX_SUMMARY")))]),t._v(" "),a("th")])]),t._v(" "),0==t.loading&&0==t.records.length&&0==t.showEmpty&&1==t.pageLoaded?a("tbody",[a("tr",[a("td",{attrs:{colspan:"4"}},[a("noRecordsFound")],1)])]):a("tbody",t._l(t.records,(function(s,e){return a("tr",{key:"records"+e},[a("td",{attrs:{scope:"row"}},[t._v(t._s(t.pagination.from+e))]),t._v(" "),a("td",[t.$canWrite("TAX_SETTINGS")?a("div",[t._v(t._s(s.taxgrp_name))]):a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return a.preventDefault(),t.openViewMode(s)}}},[t._v(t._s(s.taxgrp_name))])]),t._v(" "),a("td",[a("ul",{staticClass:"list-group tax-list-wrap"},t._l(s.group_types,(function(s,e){return a("li",{key:"group"+e,staticClass:"list-group-item"},[t._v("\n                               "+t._s(s.tgtype_name)+" ("+t._s(s.tgtype_rate)+"%) "+t._s(t.stateTypes[s.tgtype_state_type])+"\n                          ")])})),0)]),t._v(" "),a("td",[a("div",{staticClass:"actions"},[a("router-link",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],staticClass:"btn btn-light btn-sm btn-icon",class:[t.$canWrite("TAX_SETTINGS")?"":"disabled no-drop"],attrs:{to:{name:"taxEdit",params:{id:s.taxgrp_id}},title:t.$t("TTL_EDIT")}},[a("svg",[a("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#edit-icon"}})])]),t._v(" "),a("a",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],staticClass:"btn btn-light btn-sm btn-icon",class:[t.$canWrite("TAX_SETTINGS")?"":"disabled no-drop"],attrs:{href:"javascript:;",title:t.$t("TTL_DELETE")},on:{"!click":function(a){return t.confirmDelete(a,s.taxgrp_id)}}},[a("svg",[a("use",{attrs:{"xlink:href":t.baseUrl+"/admin/images/retina/sprite.svg#delete-icon"}})])])],1)])])})),0)])])])]):t._e(),t._v(" "),1==t.showEmpty&&1==t.pageLoaded?a("div",{staticClass:"portlet__body"},[a("div",{staticClass:"get-started"},[a("div",{staticClass:"get-started-arrow d-flex justify-content-end mb-5"},[a("img",{attrs:{src:t.baseUrl+"/admin/images/get-started-graphic/get-started-arrow-head.svg"}})]),t._v(" "),a("div",{staticClass:"no-content d-flex text-center flex-column mb-7"},[a("p",[t._v("\n                  "+t._s(t.$t("LBL_CLICK_THE"))+"\n                  "),a("router-link",{staticClass:"btn btn-brand btn-small",attrs:{to:{name:"taxCreate"}}},[a("i",{staticClass:"fas fa-plus"}),t._v("\n                    "+t._s(t.$t("BTN_ADD"))+"\n                  ")]),t._v("\n                  "+t._s(t.$t("LBL_BUTTON_TO_CREATE_TAX"))+"\n                ")],1)]),t._v(" "),a("div",{staticClass:"get-started-media"},[a("img",{attrs:{src:t.baseUrl+"/admin/images/get-started-graphic/get-started-pickup-address.svg"}})])])]):t._e(),t._v(" "),t.loading?a("loader"):t._e(),t._v(" "),t.records.length>0?a("div",{staticClass:"portlet__foot"},[a("pagination",{attrs:{pagination:t.pagination},on:{currentPage:t.currentPage}})],1):t._e()],1)])])]),t._v(" "),a("DeleteModel",{attrs:{deletePopText:t.$t("LBL_TAX_DELETE_CONFIRMATION"),recordId:t.recordId},on:{deleted:function(a){return t.deleteRecord(t.recordId)}}})],1)}),[function(){var t=this._self._c;return t("div",{staticClass:"alert-icon"},[t("i",{staticClass:"flaticon-signs-2 font-brand"})])},function(){var t=this._self._c;return t("span",{staticClass:"input-icon__icon input-icon__icon--left"},[t("span",[t("i",{staticClass:"la la-search"})])])},function(){var t=this._self._c;return t("span",[t("i",{staticClass:"fas fa-times"})])}],!1,null,null,null);a.default=r.exports},sP1F:function(t,a,s){"use strict";(function(t){var s={post_title:""};a.a={data:function(){return{records:[],recordId:"",search:"",pagination:[],loading:!1,modelId:"deleteModel",searchData:s,stateTypes:[],baseUrl:baseUrl,pageLoaded:0,showEmpty:1}},watch:{$route:"refreshedSearchData"},methods:{refreshedSearchData:function(){this.$route.query.s&&(this.search=this.$route.query.s),this.pageRecords(1,!0)},currentPage:function(t){this.pageRecords(t)},searchRecords:function(){this.pageRecords(this.pagination.current_page)},pageRecords:function(t){var a=this,s=arguments.length>1&&void 0!==arguments[1]&&arguments[1];this.loading=s;var e=this.$serializeData({search:this.search});void 0!==this.pagination.per_page&&e.append("per_page",this.pagination.per_page),this.$http.post(adminBaseUrl+"/tax-group/list?page="+t,e).then((function(t){a.records=t.data.data.taxGroup.data,a.pagination=t.data.data.taxGroup,a.stateTypes=t.data.data.stateTypes,a.loading=!1,a.showEmpty=t.data.data.showEmpty,a.pageLoaded=1}))},confirmDelete:function(t,a){t.stopPropagation(),this.recordId=a,this.$bvModal.show(this.modelId)},deleteRecord:function(a){var s=this;this.$http.delete(adminBaseUrl+"/tax-group/"+a).then((function(a){0!=a.data.status?(t.success(a.data.message,"",t.options),s.pageRecords(s.pagination.current_page),s.$bvModal.hide(s.modelId)):t.error(a.data.message,"",t.options)}))},openViewMode:function(t){this.$router.push({name:"taxEdit",params:{id:t.taxgrp_id}})}},mounted:function(){this.search="",this.refreshedSearchData()}}}).call(this,s("hUol"))}}]);