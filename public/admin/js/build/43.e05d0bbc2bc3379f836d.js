(window.webpackJsonp=window.webpackJsonp||[]).push([[43],{Lk6d:function(t,a,e){"use strict";(function(t){var e={search:"",dateRange:[]};a.a={props:["dateRange"],data:function(){return{baseUrl:baseUrl,searchData:e,records:[],exportFields:{"Landing page type":"page_type","Landing page path":"landing_page",Visitors:"visitors",Sessions:"sessions"},exportData:[],summary:{},loading:!1,type:"sessionByLandingPage",sortingType:"",sortingBy:""}},methods:{searchRecords:function(t){this.searchData.dateRange=t,this.getBehaviourReportData()},emitExportFieldsToParent:function(){this.$emit("exportExcelFields",this.exportFields)},emitExportDataToParent:function(){this.$emit("exportExcelData",this.exportData)},emitDateRange:function(){this.$emit("setDate",this.searchData.dateRange)},getBehaviourReportData:function(){var a=this;this.loading=!0;var e=this.$serializeData(this.searchData);e.append("sorting-by",this.sortingBy),e.append("sorting-type",this.sortingType),e.append("type",this.type),this.$http.post(adminBaseUrl+"/reports/get-behavior-report",e).then((function(e){if(0==e.data.status)return a.loading=!1,void t.error(e.data.message,"",t.options);a.records=e.data.data.data,a.exportData=e.data.data.data,a.searchData.dateRange=e.data.data.dateRange,a.loading=!1,a.emitExportFieldsToParent(),a.emitExportDataToParent(),a.emitDateRange()}))},printSessionByLandingPage:function(){this.$htmlToPaper("sessionByLandingPage")},sortBy:function(t){""==this.sortingType||"desc"==this.sortingType||this.sortingBy!=t?this.sortingType="asc":this.sortingType="desc",this.sortingBy=t,this.getBehaviourReportData()}},mounted:function(){""!=installationDate&&this.$emit("installationDate",installationDate),this.searchData.dateRange=this.dateRange,this.getBehaviourReportData()},computed:{totalSession:function(){return this.records.reduce((function(t,a){return t+a.sessions}),0)},totalVisitor:function(){return this.records.reduce((function(t,a){return t+parseInt(a.visitors)}),0)}}}}).call(this,e("hUol"))},OgwA:function(t,a,e){"use strict";(function(t){var e={search:"",dateRange:[]};a.a={props:["dateRange"],data:function(){return{baseUrl:baseUrl,searchData:e,records:[],exportFields:{"Device type":"device_type",Visitors:"visitors",Sessions:"sessions"},exportData:[],summary:{},loading:!1,type:"sessionByDevice",sortingType:"",sortingBy:""}},methods:{searchRecords:function(t){this.searchData.dateRange=t,this.getBehaviourReportData()},emitExportFieldsToParent:function(){this.$emit("exportExcelFields",this.exportFields)},emitExportDataToParent:function(){this.$emit("exportExcelData",this.exportData)},emitDateRange:function(){this.$emit("setDate",this.searchData.dateRange)},getBehaviourReportData:function(){var a=this;this.loading=!0;var e=this.$serializeData(this.searchData);e.append("sorting-by",this.sortingBy),e.append("sorting-type",this.sortingType),e.append("type",this.type),this.$http.post(adminBaseUrl+"/reports/get-behavior-report",e).then((function(e){if(0==e.data.status)return a.loading=!1,void t.error(e.data.message,"",t.options);a.records=e.data.data.data,a.exportData=e.data.data.data,a.searchData.dateRange=e.data.data.dateRange,a.loading=!1,a.emitExportFieldsToParent(),a.emitExportDataToParent(),a.emitDateRange()}))},printSessionByDevice:function(){this.$htmlToPaper("sessionByDevice")},sortBy:function(t){""==this.sortingType||"desc"==this.sortingType||this.sortingBy!=t?this.sortingType="asc":this.sortingType="desc",this.sortingBy=t,this.getBehaviourReportData()}},mounted:function(){""!=installationDate&&this.$emit("installationDate",installationDate),this.searchData.dateRange=this.dateRange,this.getBehaviourReportData()},computed:{totalSession:function(){return this.records.reduce((function(t,a){return t+a.sessions}),0)},totalVisitor:function(){return this.records.reduce((function(t,a){return t+parseInt(a.visitors)}),0)}}}}).call(this,e("hUol"))},PrxM:function(t,a,e){"use strict";(function(t){var e={search:"",dateRange:[]};a.a={props:["dateRange","type"],data:function(){return{baseUrl:baseUrl,searchData:e,records:[],exportFields:{"Original Query":"search_term","Total Searches":"total_count"},exportData:[],summary:{},loading:!1,reqType:"",sortingType:"",sortingBy:""}},methods:{searchRecords:function(t){this.searchData.dateRange=t,this.getBehaviourReportData()},emitExportFieldsToParent:function(){this.$emit("exportExcelFields",this.exportFields)},emitExportDataToParent:function(){this.$emit("exportExcelData",this.exportData)},emitDateRange:function(){this.$emit("setDate",this.searchData.dateRange)},getBehaviourReportData:function(){var a=this;this.loading=!0;var e=this.$serializeData(this.searchData);e.append("sorting-by",this.sortingBy),e.append("sorting-type",this.sortingType),e.append("type",this.reqType),this.$http.post(adminBaseUrl+"/reports/get-behavior-report",e).then((function(e){if(0==e.data.status)return a.loading=!1,void t.error(e.data.message,"",t.options);a.records=e.data.data.data,a.exportData=e.data.data.data,a.searchData.dateRange=e.data.data.dateRange,a.loading=!1,a.emitExportFieldsToParent(),a.emitExportDataToParent(),a.emitDateRange()}))},printSearchRecords:function(){this.$htmlToPaper("onlineSearchRecords")},sortBy:function(t){""==this.sortingType||"desc"==this.sortingType||this.sortingBy!=t?this.sortingType="asc":this.sortingType="desc",this.sortingBy=t,this.getBehaviourReportData()}},mounted:function(){""!=installationDate&&this.$emit("installationDate",installationDate),this.reqType=this.type,null!=this.dateRange&&(this.searchData.dateRange=this.dateRange),this.getBehaviourReportData()},computed:{totalResult:function(){return this.records.reduce((function(t,a){return t+a.total_count}),0)}}}}).call(this,e("hUol"))},QYGc:function(t,a,e){"use strict";e.r(a);var s=e("7EX9"),i=(e("QRy/"),e("PrxM").a),r=e("KHd+"),n=Object(r.a)(i,(function(){var t=this,a=t._self._c;return a("div",[t.records.length>0?a("div",{staticClass:"datatable datatable--default datatable--brand datatable--loaded",attrs:{id:"onlineSearchRecords"}},[a("table",{staticClass:"datatable__table"},[a("thead",{staticClass:"datatable__head datatable__head-fixed"},[a("tr",{staticClass:"datatable__row"},[a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["search_term"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("search_term")}}},[a("span",[t._v(t._s(t.$t("LBL_ORIGINAL_QUERY"))+"\n                            "),"search_term"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["total_count"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("total_count")}}},[a("span",[t._v(t._s(t.$t("LBL_TOTAL_SEARCHES"))+"\n                            "),"total_count"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])])])]),t._v(" "),a("tbody",{staticClass:"datatable__body"},[a("tr",{staticClass:"datatable__row datatable__row-highlighted"},[a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.$t("LBL_SUMMARY")))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.totalResult))])])]),t._v(" "),t._l(t.records,(function(e,s){return a("tr",{key:s,staticClass:"datatable__row"},[a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(e.search_term?e.search_term:""))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(e.total_count?e.total_count:0))])])])}))],2)])]):t._e(),t._v(" "),t.loading?a("loader"):t._e(),t._v(" "),0==t.loading&&0==t.records.length?a("noRecordsFound"):t._e()],1)}),[],!1,null,null,null).exports,o=e("Lk6d").a,l=Object(r.a)(o,(function(){var t=this,a=t._self._c;return a("div",[t.records.length>0?a("div",{staticClass:"datatable datatable--default datatable--brand datatable--loaded",attrs:{id:"sessionByLandingPage"}},[a("table",{staticClass:"datatable__table"},[a("thead",{staticClass:"datatable__head datatable__head-fixed"},[a("tr",{staticClass:"datatable__row"},[a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["users"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("users")}}},[a("span",[t._v(t._s(t.$t("LBL_LANDING_PAGE_TYPE"))+"\n                            "),"users"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort"},[a("span",[t._v(t._s(t.$t("Landing page path")))])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["users"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("users")}}},[a("span",[t._v(t._s(t.$t("LBL_VISITORS"))+"\n                            "),"users"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["sessions"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("sessions")}}},[a("span",[t._v(t._s(t.$t("LBL_SESSIONS"))+"\n                            "),"sessions"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])])])]),t._v(" "),a("tbody",{staticClass:"datatable__body"},[a("tr",{staticClass:"datatable__row datatable__row-highlighted"},[a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.$t("LBL_SUMMARY")))])]),t._v(" "),t._m(0),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.totalSession))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.totalVisitor))])])]),t._v(" "),t._l(t.records,(function(e,s){return a("tr",{key:s,staticClass:"datatable__row"},[a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(e.page_type.length>100?e.page_type.substring(0,50)+"....":e.page_type))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(e.landing_page.length>100?e.landing_page.substring(0,50)+"....":e.landing_page))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(e.visitors?e.visitors:0))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(e.sessions?e.sessions:0))])])])}))],2)])]):t._e(),t._v(" "),t.loading?a("loader"):t._e(),t._v(" "),0==t.loading&&0==t.records.length?a("noRecordsFound"):t._e()],1)}),[function(){var t=this._self._c;return t("td",{staticClass:"datatable__cell"},[t("span")])}],!1,null,null,null).exports,c=e("OgwA").a,d=Object(r.a)(c,(function(){var t=this,a=t._self._c;return a("div",[t.records.length>0?a("div",{staticClass:"datatable datatable--default datatable--brand datatable--loaded",attrs:{id:"sessionByDevice"}},[a("table",{staticClass:"datatable__table"},[a("thead",{staticClass:"datatable__head datatable__head-fixed"},[a("tr",{staticClass:"datatable__row"},[a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["deviceCategory"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("deviceCategory")}}},[a("span",[t._v(t._s(t.$t("LBL_DEVICE_TYPE"))+"\n                            "),"deviceCategory"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["users"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("users")}}},[a("span",[t._v(t._s(t.$t("LBL_VISITORS"))+"\n                            "),"users"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["sessions"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("sessions")}}},[a("span",[t._v(t._s(t.$t("LBL_SESSIONS"))+"\n                            "),"sessions"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])])])]),t._v(" "),a("tbody",{staticClass:"datatable__body"},[a("tr",{staticClass:"datatable__row datatable__row-highlighted"},[a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.$t("LBL_SUMMARY")))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.totalVisitor))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.totalSession))])])]),t._v(" "),t._l(t.records,(function(e,s){return a("tr",{key:s,staticClass:"datatable__row"},[a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(e.device_type?e.device_type:""))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(e.visitors?e.visitors:0))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(e.sessions?e.sessions:0))])])])}))],2)])]):t._e(),t._v(" "),t.loading?a("loader"):t._e(),t._v(" "),0==t.loading&&0==t.records.length?a("noRecordsFound"):t._e()],1)}),[],!1,null,null,null).exports,_=new Date,h={dateRange:[]},p={components:{DatePicker:s.a,onlineSearchRecords:n,sessionByLandingPage:l,sessionByDevice:d},data:function(){return{exportFields:{},exportData:[],searchData:h,isDisabled:!0,currentActionTab:"searchWithNoRecords",installationDate:""}},methods:{notAfterToday:function(t){return t>_},setExportFields:function(t){this.exportFields=t},setExportData:function(t){this.exportData=t,this.isDisabled=!(t.length>0)},setDate:function(t){this.searchData.dateRange=t},searchRecords:function(){switch(this.currentActionTab){case"searchWithNoRecords":this.$refs.searchWithNoRecords.searchRecords(this.searchData.dateRange);break;case"searchWithRecords":this.$refs.searchWithRecords.searchRecords(this.searchData.dateRange);break;case"sessionByDevice":this.$refs.sessionByDevice.searchRecords(this.searchData.dateRange);break;case"sessionByLandingPage":this.$refs.sessionByLandingPage.searchRecords(this.searchData.dateRange)}},printReport:function(){switch(this.currentActionTab){case"searchWithNoRecords":this.$refs.searchWithNoRecords.printSearchRecords();break;case"searchWithRecords":this.$refs.searchWithRecords.printSearchRecords();break;case"sessionByDevice":this.$refs.sessionByDevice.printSessionByDevice();break;case"sessionByLandingPage":this.$refs.sessionByLandingPage.printSessionByLandingPage()}},switchTab:function(t){this.currentActionTab=t}}},u=Object(r.a)(p,(function(){var t=this,a=t._self._c;return a("div",[a("div",{staticClass:"subheader grid__item",attrs:{id:"subheader"}},[a("div",{staticClass:"container"},[a("div",{staticClass:"subheader__main"},[a("h3",{staticClass:"subheader__title"},[t._v(t._s(t.$t("LBL_BEHAVIOUR_REPORT"))+" ")]),t._v(" "),a("div",{staticClass:"subheader__breadcrumbs"},[a("router-link",{staticClass:"subheader__breadcrumbs-home",attrs:{to:{name:"dashboard"}}},[a("i",{staticClass:"flaticon2-shelter"})]),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-separator"}),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-link"},[t._v("\n                        "+t._s(t.$t("LBL_REPORTS"))+"\n                    ")]),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-separator"}),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-link"},[t._v(t._s(t.$t("LBL_BEHAVIOUR_REPORT")))])],1)]),t._v(" "),a("div",{staticClass:"subheader__toolbar"},[a("div",{staticClass:"subheader__wrapper"},[a("date-picker",{staticClass:"custom-date-range",attrs:{"disabled-date":t.notAfterToday,type:"date",range:"","value-type":"format",format:"YYYY-MM-DD",placeholder:"Select date"},on:{change:t.searchRecords},model:{value:t.searchData.dateRange,callback:function(a){t.$set(t.searchData,"dateRange",a)},expression:"searchData.dateRange"}}),t._v(" "),a("export-excel",{attrs:{data:t.exportData,fields:t.exportFields,name:t.currentActionTab}},[a("button",{staticClass:"btn btn-subheader YK-exportBtn",attrs:{type:"button",disabled:t.isDisabled||!t.$canWrite("BEHAVIOUR_REPORT")}},[t._v(t._s(t.$t("BTN_EXPORT")))])]),t._v(" "),a("button",{staticClass:"btn btn-subheader YK-printBtn",attrs:{type:"button",disabled:t.isDisabled||!t.$canWrite("BEHAVIOUR_REPORT")},on:{click:t.printReport}},[t._v(t._s(t.$t("BTN_PRINT")))])],1)])])]),t._v(" "),a("div",{staticClass:"container"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-lg-12"},[a("div",{staticClass:"portlet portlet--tabs"},[a("div",{staticClass:"portlet__head"},[a("div",{staticClass:"portlet__head-toolbar"},[a("ul",{staticClass:"nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold",attrs:{role:"tablist"}},[a("li",{staticClass:"nav-item"},[a("a",{staticClass:"nav-link active",attrs:{"data-toggle":"tab",href:"#portlet_tab_content_1",role:"tab","aria-selected":"false"},on:{click:function(a){return t.switchTab("searchWithNoRecords")}}},[t._v(t._s(t.$t("LBL_SEARCH_WITH_NO_RECORDS")))])]),t._v(" "),a("li",{staticClass:"nav-item"},[a("a",{staticClass:"nav-link",attrs:{"data-toggle":"tab",href:"#portlet_tab_content_2",role:"tab","aria-selected":"true"},on:{click:function(a){return t.switchTab("searchWithRecords")}}},[t._v(t._s(t.$t("LBL_SEARCH_WITH_RECORDS"))+" ")])]),t._v(" "),a("li",{staticClass:"nav-item"},[a("a",{staticClass:"nav-link",attrs:{"data-toggle":"tab",href:"#portlet_tab_content_3",role:"tab","aria-selected":"false"},on:{click:function(a){return t.switchTab("sessionByDevice")}}},[t._v(" "+t._s(t.$t("LBL_SESSION_BY_DEVICE")))])]),t._v(" "),a("li",{staticClass:"nav-item"},[a("a",{staticClass:"nav-link",attrs:{"data-toggle":"tab",href:"#portlet_tab_content_4",role:"tab","aria-selected":"false"},on:{click:function(a){return t.switchTab("sessionByLandingPage")}}},[t._v(t._s(t.$t("LBL_SESSION_BY_LANDING_PAGE")))])])])])]),t._v(" "),a("div",{staticClass:"portlet__body portlet__body--fit portlet__body--fit"},[a("div",{staticClass:"tab-content"},[a("div",{staticClass:"tab-pane active",attrs:{id:"portlet_tab_content_1",role:"tabpanel"}},["searchWithNoRecords"==t.currentActionTab?a("onlineSearchRecords",{ref:"searchWithNoRecords",attrs:{dateRange:t.searchData.dateRange,type:"topOnlineStoreWithNoResult"},on:{exportExcelFields:t.setExportFields,exportExcelData:t.setExportData,setDate:t.setDate,installationDate:t.setInstallationDate}}):t._e()],1),t._v(" "),a("div",{staticClass:"tab-pane",attrs:{id:"portlet_tab_content_2",role:"tabpanel"}},["searchWithRecords"==t.currentActionTab?a("onlineSearchRecords",{ref:"searchWithRecords",attrs:{dateRange:t.searchData.dateRange,type:"topOnlineStoreWithResult"},on:{exportExcelFields:t.setExportFields,exportExcelData:t.setExportData,setDate:t.setDate,installationDate:t.setInstallationDate}}):t._e()],1),t._v(" "),a("div",{staticClass:"tab-pane",attrs:{id:"portlet_tab_content_3",role:"tabpanel"}},["sessionByDevice"==t.currentActionTab?a("sessionByDevice",{ref:"sessionByDevice",attrs:{dateRange:t.searchData.dateRange},on:{installationDate:t.setInstallationDate,exportExcelFields:t.setExportFields,exportExcelData:t.setExportData,setDate:t.setDate}}):t._e()],1),t._v(" "),a("div",{staticClass:"tab-pane",attrs:{id:"portlet_tab_content_4",role:"tabpanel"}},["sessionByLandingPage"==t.currentActionTab?a("sessionByLandingPage",{ref:"sessionByLandingPage",attrs:{dateRange:t.searchData.dateRange},on:{installationDate:t.setInstallationDate,exportExcelFields:t.setExportFields,exportExcelData:t.setExportData,setDate:t.setDate}}):t._e()],1)])])])])])])])}),[],!1,null,null,null);a.default=u.exports}}]);