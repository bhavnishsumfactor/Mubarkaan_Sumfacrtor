(window.webpackJsonp=window.webpackJsonp||[]).push([[69],{"7NNP":function(t,a,s){"use strict";s.r(a);var e=s("7EX9"),r=(s("QRy/"),s("NEaW").a),o=s("KHd+"),n=Object(o.a)(r,(function(){var t=this,a=t._self._c;return a("div",[t.records.length>0?a("div",{staticClass:"datatable datatable--default datatable--brand datatable--loaded",attrs:{id:"profitByProduct"}},[a("table",{staticClass:"datatable__table"},[a("thead",{staticClass:"datatable__head datatable__head-fixed"},[a("tr",{staticClass:"datatable__row"},[a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["op_product_name"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("op_product_name")}}},[a("span",[t._v(t._s(t.$t("LBL_PRODUCTS_NAME"))+" \n                            "),"op_product_name"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["op_product_type"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("op_product_type")}}},[a("span",[t._v(t._s(t.$t("LBL_PRODUCTS_TYPE"))+"\n                            "),"op_product_type"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["order_net_quantity"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("order_net_quantity")}}},[a("span",[t._v(t._s(t.$t("LBL_NET_QUANTITY"))+" \n                            "),"order_net_quantity"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["net_sale"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("net_sale")}}},[a("span",[t._v(t._s(t.$t("LBL_NET_SALES"))+"\n                            "),"net_sale"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["cost"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("cost")}}},[a("span",[t._v(t._s(t.$t("LBL_COST"))+" \n                            "),"cost"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["gross_percent"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("gross_percent")}}},[a("span",[t._v(t._s(t.$t("LBL_GROSS_MARGIN")+" %")+" \n                            "),"gross_percent"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["gross_profit"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("gross_profit")}}},[a("span",[t._v(t._s(t.$t("LBL_GROSS_PROFIT"))+" \n                            "),"gross_profit"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])])])]),t._v(" "),a("tbody",{staticClass:"datatable__body"},[a("tr",{staticClass:"datatable__row datatable__row-highlighted"},[a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.$t("LBL_SUMMARY")))])]),t._v(" "),t._m(0),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.totalQuantity))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.symbol)+t._s(Math.round(t.totalNetSale)))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.symbol)+t._s(Math.round(t.totalCost)))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s())])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.symbol)+t._s(Math.round(t.totalGrossProfit)))])])]),t._v(" "),t._l(t.sortlisting,(function(s,e){return a("tr",{key:e,staticClass:"datatable__row"},[a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(s.op_product_name?s.op_product_name:""))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[a("span",{staticClass:"badge badge--dot",class:[1==s.op_product_type?"badge--danger":"badge--success"]}),t._v(" \n                            "),a("span",{staticClass:"font-bold",class:[1==s.op_product_type?"font-danger":"font-success"]},[t._v(t._s(t.productTypes[s.op_product_type]))])])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(s.order_net_quantity?s.order_net_quantity:0))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.symbol)+t._s(s.net_sale?s.net_sale:0))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.symbol)+t._s(s.cost?s.cost:0))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(s.gross_percent?s.gross_percent:0))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.symbol)+t._s(s.gross_profit?s.gross_profit:0))])])])}))],2)])]):t._e(),t._v(" "),t.loading?a("loader"):t._e(),t._v(" "),0==t.loading&&0==t.records.length?a("noRecordsFound"):t._e()],1)}),[function(){var t=this._self._c;return t("td",{staticClass:"datatable__cell"},[t("span")])}],!1,null,null,null).exports,i=s("G60K").a,l=Object(o.a)(i,(function(){var t=this,a=t._self._c;return a("div",[t.records.length>0?a("div",{staticClass:"datatable datatable--default datatable--brand datatable--loaded",attrs:{id:"profitByProductVariant"}},[a("table",{staticClass:"datatable__table"},[a("thead",{staticClass:"datatable__head datatable__head-fixed"},[a("tr",{staticClass:"datatable__row"},[a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["op_product_name"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("op_product_name")}}},[a("span",[t._v("\n                            "+t._s(t.$t("LBL_PRODUCTS_NAME"))+"\n                            "),"op_product_name"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["op_product_type"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("op_product_type")}}},[a("span",[t._v(t._s(t.$t("LBL_PRODUCTS_TYPE"))+"\n                            "),"op_product_type"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort"},[a("span",[t._v("\n                            "+t._s(t.$t("LBL_VARIANT_TITLE"))+"\n                        ")])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["order_net_quantity"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("order_net_quantity")}}},[a("span",[t._v("\n                            "+t._s(t.$t("LBL_NET_QUANTITY"))+"\n                            "),"order_net_quantity"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["net_sale"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("net_sale")}}},[a("span",[t._v("\n                            "+t._s(t.$t("LBL_NET_SALES"))+"\n                            "),"net_sale"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["cost"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("cost")}}},[a("span",[t._v("\n                            "+t._s(t.$t("LBL_COST"))+"\n                            "),"cost"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["gross_percent"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("gross_percent")}}},[a("span",[t._v(t._s(t.$t("LBL_GROSS_MARGIN")+" %")+"\n                            "),"gross_percent"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])]),t._v(" "),a("th",{staticClass:"datatable__cell datatable__cell--sort",class:["gross_profit"==t.sortingBy?"datatable__cell--sorted":""],on:{click:function(a){return t.sortBy("gross_profit")}}},[a("span",[t._v(t._s(t.$t("LBL_GROSS_PROFIT"))+"\n                            "),"gross_profit"==t.sortingBy?a("i",{staticClass:"fas",class:["asc"==t.sortingType?"fa-arrow-up":"fa-arrow-down"]}):t._e()])])])]),t._v(" "),a("tbody",{staticClass:"datatable__body"},[a("tr",{staticClass:"datatable__row datatable__row-highlighted"},[a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.$t("LBL_SUMMARY")))])]),t._v(" "),t._m(0),t._v(" "),t._m(1),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.totalQuantity))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.symbol)+t._s(Math.round(t.totalNetSale))+" ")])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.symbol)+t._s(Math.round(t.totalCost))+" ")])]),t._v(" "),a("td",{staticClass:"datatable__cell"}),t._v(" "),a("td",{staticClass:"datatable__cell"},[t._v(t._s(t.symbol)+"  "+t._s(Math.round(t.totalGrossProfit)))])]),t._v(" "),t._l(t.sortlisting,(function(s,e){return a("tr",{key:e,staticClass:"datatable__row"},[a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(s.op_product_name?s.op_product_name:""))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[a("span",{staticClass:"badge badge--dot",class:[1==s.op_product_type?"badge--danger":"badge--success"]}),t._v(" \n                            "),a("span",{staticClass:"font-bold",class:[1==s.op_product_type?"font-danger":"font-success"]},[t._v(t._s(t.productTypes[s.op_product_type]))])])]),t._v(" "),null!=s.op_product_variants&&s.op_product_variants.includes("styles")?a("td",{staticClass:"datatable__cell"},t._l(JSON.parse(s.op_product_variants).styles,(function(s,e){return a("span",{key:e},[t._v(" "+t._s(s)+" "),a("br")])})),0):a("td",{staticClass:"datatable__cell"},[a("span",[t._v(t._s(t.$t("LBL_N_A")))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(" "+t._s(s.order_net_quantity?s.order_net_quantity:0))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(" "+t._s(t.symbol)+t._s(s.net_sale?s.net_sale:0))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(" "+t._s(t.symbol)+t._s(s.cost?s.cost:0))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(" "+t._s(s.gross_percent?s.gross_percent:0))])]),t._v(" "),a("td",{staticClass:"datatable__cell"},[a("span",[t._v(" "+t._s(t.symbol)+t._s(s.gross_profit?s.gross_profit:0))])])])}))],2)])]):t._e(),t._v(" "),t.loading?a("loader"):t._e(),t._v(" "),0==t.loading&&0==t.records.length?a("noRecordsFound"):t._e()],1)}),[function(){var t=this._self._c;return t("td",{staticClass:"datatable__cell"},[t("span")])},function(){var t=this._self._c;return t("td",{staticClass:"datatable__cell"},[t("span")])}],!1,null,null,null).exports,c={dateRange:[]},_={components:{DatePicker:e.a,profitByProduct:n,profitByProductVariant:l},data:function(){return{exportFields:{},exportData:[],searchData:c,currentActionTab:"profitByProduct",isDisabled:!0,installationDate:""}},methods:{setExportFields:function(t){this.exportFields=t},setExportData:function(t){this.exportData=t,this.isDisabled=!(t.length>0)},setDate:function(t){this.searchData.dateRange=t},setInstallationDate:function(t){this.installationDate=t},disabledDates:function(t){if(""!=this.installationDate)return t<new Date(this.installationDate)||t>new Date},searchRecords:function(){switch(this.currentActionTab){case"profitByProduct":this.$refs.profitByProduct.searchRecords(this.searchData.dateRange);break;case"profitByProductVariant":this.$refs.profitByProductVariant.searchRecords(this.searchData.dateRange)}},printReport:function(){switch(this.currentActionTab){case"profitByProduct":this.$refs.profitByProduct.printProfitByProduct();break;case"profitByProductVariant":this.$refs.profitByProductVariant.printSaleByProductVariant()}},switchTab:function(t){this.currentActionTab=t}}},d=Object(o.a)(_,(function(){var t=this,a=t._self._c;return a("div",[a("div",{staticClass:"subheader grid__item",attrs:{id:"subheader"}},[a("div",{staticClass:"container"},[a("div",{staticClass:"subheader__main"},[a("h3",{staticClass:"subheader__title"},[t._v(t._s(t.$t("LBL_PROFIT_REPORT"))+" ")]),t._v(" "),a("div",{staticClass:"subheader__breadcrumbs"},[a("router-link",{staticClass:"subheader__breadcrumbs-home",attrs:{to:{name:"dashboard"}}},[a("i",{staticClass:"flaticon2-shelter"})]),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-separator"}),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-link"},[t._v("\n                        "+t._s(t.$t("LBL_REPORTS"))+"\n                    ")]),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-separator"}),t._v(" "),a("span",{staticClass:"subheader__breadcrumbs-link"},[t._v(t._s(t.$t("LBL_PROFIT_REPORT")))])],1)]),t._v(" "),a("div",{staticClass:"subheader__toolbar"},[a("div",{staticClass:"subheader__wrapper"},[a("date-picker",{staticClass:"custom-date-range",attrs:{type:"date",range:"","value-type":"format",format:"YYYY-MM-DD",placeholder:"Select date","disabled-date":t.disabledDates},on:{change:t.searchRecords},model:{value:t.searchData.dateRange,callback:function(a){t.$set(t.searchData,"dateRange",a)},expression:"searchData.dateRange"}}),t._v(" "),a("export-excel",{attrs:{data:t.exportData,fields:t.exportFields,name:t.currentActionTab}},[a("button",{staticClass:"btn btn-subheader YK-exportBtn",attrs:{type:"button",disabled:t.isDisabled||!t.$canWrite("PROFIT_MARGIN_REPORT")}},[t._v(t._s(t.$t("BTN_EXPORT")))])]),t._v(" "),a("button",{staticClass:"btn btn-subheader YK-printBtn",attrs:{type:"button",disabled:t.isDisabled||!t.$canWrite("PROFIT_MARGIN_REPORT")},on:{click:t.printReport}},[t._v(t._s(t.$t("BTN_PRINT")))])],1)])])]),t._v(" "),a("div",{staticClass:"container"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-lg-12"},[a("div",{staticClass:"portlet portlet--tabs"},[a("div",{staticClass:"portlet__head"},[a("div",{staticClass:"portlet__head-toolbar"},[a("ul",{staticClass:"nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold",attrs:{role:"tablist"}},[a("li",{staticClass:"nav-item"},[a("a",{staticClass:"nav-link active",attrs:{"data-toggle":"tab",href:"#portlet_tab_content_1",role:"tab","aria-selected":"false"},on:{click:function(a){return t.switchTab("profitByProduct")}}},[t._v(t._s(t.$t("LNK_PROFIT_BY_PRODUCT")))])]),t._v(" "),a("li",{staticClass:"nav-item"},[a("a",{staticClass:"nav-link",attrs:{"data-toggle":"tab",href:"#portlet_tab_content_2",role:"tab","aria-selected":"true"},on:{click:function(a){return t.switchTab("profitByProductVariant")}}},[t._v(t._s(t.$t("LNK_PROFIT_BY_PRODUCT_VARIANT"))+" ")])])])])]),t._v(" "),a("div",{staticClass:"portlet__body portlet__body--fit"},[a("div",{staticClass:"tab-content"},[a("div",{staticClass:"tab-pane active",attrs:{id:"portlet_tab_content_1",role:"tabpanel"}},["profitByProduct"==t.currentActionTab?a("profitByProduct",{ref:"profitByProduct",attrs:{dateRange:t.searchData.dateRange},on:{exportExcelFields:t.setExportFields,exportExcelData:t.setExportData,setDate:t.setDate,installationDate:t.setInstallationDate}}):t._e()],1),t._v(" "),a("div",{staticClass:"tab-pane",attrs:{id:"portlet_tab_content_2",role:"tabpanel"}},["profitByProductVariant"==t.currentActionTab?a("profitByProductVariant",{ref:"profitByProductVariant",attrs:{dateRange:t.searchData.dateRange},on:{exportExcelFields:t.setExportFields,exportExcelData:t.setExportData,installationDate:t.setInstallationDate,setDate:t.setDate}}):t._e()],1)])])])])])])])}),[],!1,null,null,null);a.default=d.exports},G60K:function(t,a,s){"use strict";(function(t){function s(t){return(s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}var e;function r(t,a,e){return(a=function(t){var a=function(t,a){if("object"!==s(t)||null===t)return t;var e=t[Symbol.toPrimitive];if(void 0!==e){var r=e.call(t,a||"default");if("object"!==s(r))return r;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===a?String:Number)(t)}(t,"string");return"symbol"===s(a)?a:String(a)}(a))in t?Object.defineProperty(t,a,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[a]=e,t}function o(t){return function(t){if(Array.isArray(t))return n(t)}(t)||function(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}(t)||function(t,a){if(!t)return;if("string"==typeof t)return n(t,a);var s=Object.prototype.toString.call(t).slice(8,-1);"Object"===s&&t.constructor&&(s=t.constructor.name);if("Map"===s||"Set"===s)return Array.from(t);if("Arguments"===s||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(s))return n(t,a)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function n(t,a){(null==a||a>t.length)&&(a=t.length);for(var s=0,e=new Array(a);s<a;s++)e[s]=t[s];return e}var i={search:"",dateRange:[]};a.a={props:["dateRange"],data:function(){return{baseUrl:baseUrl,searchData:i,records:[],productTypes:[],exportFields:{"Product Name":"op_product_name","Variant Title":{field:"op_product_variants",callback:function(t){if(JSON.parse(t).styles){var a="",s=JSON.parse(t).styles;return Object.keys(s).forEach((function(t){a+="".concat(s[t]," \n")})),"".concat(a)}}},"Net Quantity":"order_net_quantity","Net Sales":"net_sale",Cost:"cost","Gross Margin":"gross_percent","Gross Profit":"gross_profit"},exportData:[],summary:{},loading:!1,type:"profitByProductVariant",symbol:"",sortingType:"",sortingBy:""}},methods:{searchRecords:function(t){this.searchData.dateRange=t,this.getProfitData()},emitExportFieldsToParent:function(){this.$emit("exportExcelFields",this.exportFields)},emitExportDataToParent:function(){this.$emit("exportExcelData",this.exportData)},emitDateRange:function(){this.$emit("setDate",this.searchData.dateRange)},getProfitData:function(){var a=this;this.loading=!0;var s=this.$serializeData(this.searchData);s.append("sorting-by",this.sortingBy),s.append("sorting-type",this.sortingType),s.append("type",this.type),this.$http.post(adminBaseUrl+"/reports/get-profit-by-product-report",s).then((function(s){if(0==s.data.status)return a.loading=!1,void t.error(s.data.message,"",t.options);a.records=o(Object.values(s.data.data.totalProducts)),a.exportData=a.records,a.productTypes=s.data.data.types,a.searchData.dateRange=s.data.data.dateRange,a.loading=!1,a.emitExportFieldsToParent(),a.emitExportDataToParent(),a.emitDateRange()}))},printSaleByProductVariant:function(){this.$htmlToPaper("profitByProductVariant")},sortBy:function(t){""==this.sortingType||"desc"==this.sortingType||this.sortingBy!=t?this.sortingType="asc":this.sortingType="desc",this.sortingBy=t,this.getProfitData()}},mounted:function(){""!=installationDate&&this.$emit("installationDate",installationDate),this.symbol=currencySymbol,this.searchData.dateRange=this.dateRange,this.getProfitData()},computed:(e={sortlisting:function(){var t=this;return"op_product_name"==this.sortingBy?this.records.sort((function(a,s){var e=1;return"desc"===t.sortingType&&(e=-1),a[t.sortingBy].toUpperCase()<s[t.sortingBy].toUpperCase()?-1*e:a[t.sortingBy].toUpperCase()>s[t.sortingBy].toUpperCase()?1*e:0})):this.records.sort((function(a,s){var e=1;return"desc"===t.sortingType&&(e=-1),a[t.sortingBy]<s[t.sortingBy]?-1*e:a[t.sortingBy]>s[t.sortingBy]?1*e:0}))},totalQuantity:function(){return this.records.reduce((function(t,a){return t+(a.order_net_quantity?Number(a.order_net_quantity):0)}),0)},totalNetSale:function(){return this.records.reduce((function(t,a){return t+(a.net_sale?Number(a.net_sale):0)}),0)},totalCost:function(){return this.records.reduce((function(t,a){return t+(a.cost?Number(a.cost):0)}),0)}},r(e,"totalCost",(function(){return this.records.reduce((function(t,a){return t+(a.cost?Number(a.cost):0)}),0)})),r(e,"totalGrossProfit",(function(){return this.records.reduce((function(t,a){return t+(a.gross_profit?Number(a.gross_profit):0)}),0)})),e)}}).call(this,s("hUol"))},NEaW:function(t,a,s){"use strict";(function(t){function s(t){return(s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}var e;function r(t,a,e){return(a=function(t){var a=function(t,a){if("object"!==s(t)||null===t)return t;var e=t[Symbol.toPrimitive];if(void 0!==e){var r=e.call(t,a||"default");if("object"!==s(r))return r;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===a?String:Number)(t)}(t,"string");return"symbol"===s(a)?a:String(a)}(a))in t?Object.defineProperty(t,a,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[a]=e,t}function o(t){return function(t){if(Array.isArray(t))return n(t)}(t)||function(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}(t)||function(t,a){if(!t)return;if("string"==typeof t)return n(t,a);var s=Object.prototype.toString.call(t).slice(8,-1);"Object"===s&&t.constructor&&(s=t.constructor.name);if("Map"===s||"Set"===s)return Array.from(t);if("Arguments"===s||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(s))return n(t,a)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function n(t,a){(null==a||a>t.length)&&(a=t.length);for(var s=0,e=new Array(a);s<a;s++)e[s]=t[s];return e}var i={search:"",dateRange:[]};a.a={props:["dateRange"],data:function(){return{baseUrl:baseUrl,searchData:i,records:[],productTypes:[],exportFields:{"Product Title":"op_product_name","Product Type":{field:"op_product_type",callback:function(t){return 1==t?"Physical":"Digital"}},"Net Quantity":"order_net_quantity","Net Sale":"net_sale",Cost:"cost","Gross margin":"gross_percent","Gross profit":"gross_profit"},exportData:[],summary:{},loading:!1,type:"profitByProduct",symbol:"",sortingBy:"op_product_name",sortingType:"asc"}},methods:{searchRecords:function(t){this.searchData.dateRange=t,this.getProfitData()},emitExportFieldsToParent:function(){this.$emit("exportExcelFields",this.exportFields)},emitExportDataToParent:function(){this.$emit("exportExcelData",this.exportData)},emitDateRange:function(){this.$emit("setDate",this.searchData.dateRange)},getProfitData:function(){var a=this;this.loading=!0;var s=this.$serializeData(this.searchData);s.append("type",this.type),this.$http.post(adminBaseUrl+"/reports/get-profit-by-product-report",s).then((function(s){if(0==s.data.status)return a.loading=!1,void t.error(s.data.message,"",t.options);a.records=o(Object.values(s.data.data.totalProducts)),a.exportData=a.records,a.productTypes=s.data.data.types,a.searchData.dateRange=s.data.data.dateRange,a.loading=!1,a.emitExportFieldsToParent(),a.emitExportDataToParent(),a.emitDateRange()}))},printProfitByProduct:function(){this.$htmlToPaper("profitByProduct")},sortBy:function(t){""==this.sortingType||"desc"==this.sortingType||this.sortingBy!=t?this.sortingType="asc":this.sortingType="desc",this.sortingBy=t,this.getProfitData()}},mounted:function(){""!=installationDate&&this.$emit("installationDate",installationDate),this.symbol=currencySymbol,this.searchData.dateRange=this.dateRange,this.getProfitData()},computed:(e={sortlisting:function(){var t=this;return"op_product_name"==this.sortingBy?this.records.sort((function(a,s){var e=1;return"desc"===t.sortingType&&(e=-1),a[t.sortingBy].toUpperCase()<s[t.sortingBy].toUpperCase()?-1*e:a[t.sortingBy].toUpperCase()>s[t.sortingBy].toUpperCase()?1*e:0})):this.records.sort((function(a,s){var e=1;return"desc"===t.sortingType&&(e=-1),a[t.sortingBy]<s[t.sortingBy]?-1*e:a[t.sortingBy]>s[t.sortingBy]?1*e:0}))},totalQuantity:function(){return this.records.reduce((function(t,a){return t+(a.order_net_quantity?Number(a.order_net_quantity):0)}),0)},totalNetSale:function(){return this.records.reduce((function(t,a){return t+(a.net_sale?Number(a.net_sale):0)}),0)},totalCost:function(){return this.records.reduce((function(t,a){return t+(a.cost?Number(a.cost):0)}),0)}},r(e,"totalCost",(function(){return this.records.reduce((function(t,a){return t+(a.cost?Number(a.cost):0)}),0)})),r(e,"totalGrossProfit",(function(){return this.records.reduce((function(t,a){return t+(a.gross_profit?Number(a.gross_profit):0)}),0)})),e)}}).call(this,s("hUol"))}}]);