(window.webpackJsonp=window.webpackJsonp||[]).push([[91],{"/Kt7":function(t,a,s){"use strict";s.r(a);var i=s("YgKi").a,e=s("KHd+"),l=Object(e.a)(i,(function(){var t=this,a=t._self._c;return a("div",[a("div",{staticClass:"subheader",attrs:{id:"subheader"}},[a("div",{staticClass:"container"},[a("div",{staticClass:"subheader__main"},[a("h3",{staticClass:"subheader__title"},[t._v(t._s(t.$t("Sent Emails")))])]),t._v(" "),t.$route.params.id?a("div",{staticClass:"subheader__toolbar"},[a("router-link",{staticClass:"btn btn-white",attrs:{to:{name:"emailList",query:{page:t.$route.query.page}}}},[a("i",{staticClass:"la la-arrow-left"}),a("span",{staticClass:"hidden-mobile"},[t._v(t._s(t.$t("Back")))])])],1):t._e()])]),t._v(" "),a("div",{staticClass:"container"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"portlet portlet--height-fluid"},[a("div",{staticClass:"portlet__head"},[a("div",{staticClass:"portlet__head-label"},[a("h3",{staticClass:"portlet__head-title"},[t._v(t._s(t.record.emailarchive_subject))])])]),t._v(" "),a("div",{staticClass:"portlet__body portlet__body--fit"},[a("div",{staticClass:"card-body"},[a("div",{staticClass:"form-group row"},[a("label",{staticClass:"col-md-2 col-form-label"},[t._v("Date")]),t._v(" "),a("div",{staticClass:"col-md-10"},[t._v(t._s(t._f("formatDateTime")(t.record.emailarchive_created_at)))])]),t._v(" "),a("div",{staticClass:"form-group row"},[a("label",{staticClass:"col-md-2 col-form-label"},[t._v("Headers")]),t._v(" "),a("div",{staticClass:"col-md-10"},[t._v(t._s(t.record.emailarchive_headers))])]),t._v(" "),a("div",{staticClass:"form-group row"},[a("label",{staticClass:"col-md-2 col-form-label"},[t._v("Body")]),t._v(" "),a("div",{staticClass:"col-md-10",domProps:{innerHTML:t._s(t.record.emailarchive_body)}})])])])])])])])])}),[],!1,null,null,null);a.default=l.exports},YgKi:function(t,a,s){"use strict";(function(t){a.a={data:function(){return{id:"",record:""}},methods:{pageLoadData:function(){var a=this;this.$http.get(adminBaseUrl+"/email-archieves/details/"+this.id).then((function(s){0!=s.data.status?(a.record=s.data.data,console.log(a.record)):t.error(s.data.message,"",t.options)})).catch((function(t){}))}},mounted:function(){this.id=this.$route.params.id,this.pageLoadData()}}}).call(this,s("hUol"))}}]);