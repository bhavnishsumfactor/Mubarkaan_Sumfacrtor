(window.webpackJsonp=window.webpackJsonp||[]).push([[108],{253:function(t,e,s){"use strict";s.r(e);var a=s(455).a,i=s(432),r=Object(i.a)(a,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("b-modal",{attrs:{id:"reviewModel",centered:"",title:"BootstrapVue","hide-header":t.success,"hide-footer":t.success},scopedSlots:t._u([{key:"modal-header",fn:function(){return[s("h5",{staticClass:"modal-title"},[t._v(t._s(t.$t("LBL_REVIEW_YOUR_PURCHASE")))]),t._v(" "),s("button",{staticClass:"close",attrs:{type:"button"},on:{click:function(e){return t.$bvModal.hide("reviewModel")}}},[s("span",[t._v("×")])])]},proxy:!0},{key:"modal-footer",fn:function(){return[s("button",{staticClass:"btn btn-brand btn-wide gb-btn gb-btn-primary",class:1==t.clicked&&"gb-is-loading",attrs:{type:"button",disabled:0==t.selectedRating||""==t.userData.preview_title||1==t.clicked},on:{click:t.submitReview}},[t._v("\n      "+t._s(t.$t("BTN_REVIEW_SUBMIT"))+"\n    ")])]},proxy:!0}])},[t._v(" "),!1===t.success?s("form",{staticClass:"form",attrs:{id:"YK-submitReviewForm",method:"post",enctype:"multipart/form-data"}},[s("div",{staticClass:"write-review"},[s("div",{staticClass:"write-review__purchase"},[s("ul",{staticClass:"list-group"},[s("li",{staticClass:"list-group-item"},[s("div",{staticClass:"product-profile"},[s("div",{staticClass:"product-profile__thumbnail"},[s("img",{staticClass:"img-fluid",attrs:{"data-ratio":"3:4",src:t.$productImage(t.product.prod_id,t.canPost.op_pov_code,t.product.images?t.product.images.afile_updated_at:"","38-51"),alt:"..."}})]),t._v(" "),s("div",{staticClass:"product-profile__data"},[s("div",{staticClass:"title"},[t._v(t._s(t.product.prod_name))])])])])])]),t._v(" "),s("div",{staticClass:"write-review__rate"},[s("h6",[t._v(t._s(t.$t("LBL_RATE_THIS_PRODUCT")))]),t._v(" "),s("div",{staticClass:"rating"},[s("div",{staticClass:"rating-action",attrs:{"data-rating":t.selectedRating}},[s("svg",{staticClass:"icon YK-ratingItem",attrs:{width:"24",height:"24"},on:{click:function(e){t.selectedRating=5}}},[s("use",{attrs:{"xlink:href":t.baseUrl+"/dashboard/media/retina/sprite.svg#star",href:t.baseUrl+"/dashboard/media/retina/sprite.svg#star"}})]),t._v(" "),s("svg",{staticClass:"icon YK-ratingItem",attrs:{width:"24",height:"24"},on:{click:function(e){t.selectedRating=4}}},[s("use",{attrs:{"xlink:href":t.baseUrl+"/dashboard/media/retina/sprite.svg#star",href:t.baseUrl+"/dashboard/media/retina/sprite.svg#star"}})]),t._v(" "),s("svg",{staticClass:"icon YK-ratingItem",attrs:{width:"24",height:"24"},on:{click:function(e){t.selectedRating=3}}},[s("use",{attrs:{"xlink:href":t.baseUrl+"/dashboard/media/retina/sprite.svg#star",href:t.baseUrl+"/dashboard/media/retina/sprite.svg#star"}})]),t._v(" "),s("svg",{staticClass:"icon YK-ratingItem",attrs:{width:"24",height:"24"},on:{click:function(e){t.selectedRating=2}}},[s("use",{attrs:{"xlink:href":t.baseUrl+"/dashboard/media/retina/sprite.svg#star",href:t.baseUrl+"/dashboard/media/retina/sprite.svg#star"}})]),t._v(" "),s("svg",{staticClass:"icon YK-ratingItem",attrs:{width:"24",height:"24"},on:{click:function(e){t.selectedRating=1}}},[s("use",{attrs:{"xlink:href":t.baseUrl+"/dashboard/media/retina/sprite.svg#star",href:t.baseUrl+"/dashboard/media/retina/sprite.svg#star"}})])])])]),t._v(" "),s("div",{staticClass:"write-review__about"},[s("h6",[t._v(t._s(t.$t("LBL_GIVE_FEEDBACK_ABOUT_PRODUCT")))]),t._v(" "),s("div",{staticClass:"form-group"},[s("label",{},[t._v(t._s(t.$t("LBL_REVIEW_TITLE")))]),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.userData.preview_title,expression:"userData.preview_title"}],staticClass:"form-control",class:[""!=t.userData.preview_title?"filled":""],attrs:{type:"text",name:"preview_title",id:"preview_title"},domProps:{value:t.userData.preview_title},on:{input:function(e){e.target.composing||t.$set(t.userData,"preview_title",e.target.value)}}})]),t._v(" "),s("div",{staticClass:"form-group"},[s("label",{},[t._v(t._s(t.$t("LBL_REVIEW_DESCRIPTION")))]),t._v(" "),s("textarea",{directives:[{name:"model",rawName:"v-model",value:t.userData.preview_description,expression:"userData.preview_description"}],staticClass:"form-control",class:[""!=t.userData.preview_description?"filled":""],staticStyle:{resize:"none"},attrs:{cols:"30",rows:"10"},domProps:{value:t.userData.preview_description},on:{input:function(e){e.target.composing||t.$set(t.userData,"preview_description",e.target.value)}}})])]),t._v(" "),s("div",{staticClass:"write-review__media"},[s("ul",{staticClass:"list-media YK-uploadedImagesPreview"},[t._l(t.imageIds,(function(e,a){return s("li",{key:a,staticClass:"remove-able"},[s("img",{attrs:{"data-yk":"",src:t.baseUrl+"/yokart/image/"+e+"/small",alt:""}}),t._v(" "),s("button",{staticClass:"remove YK-removeReviewImage",attrs:{type:"button"},on:{click:function(s){return t.removeImage(a,e)}}},[s("i",{staticClass:"fas fa-times"})])])})),t._v(" "),s("li",{staticClass:"upload"},[s("i",{staticClass:"fas fa-camera"}),t._v(" "),s("input",{attrs:{type:"file",accept:"image/*",id:"images",multiple:"multiple"},on:{change:function(e){return t.onSelectImage(e)}}})])],2)])])]):s("div",{staticClass:"my-1 pb-5 text-center"},[s("div",{staticClass:"success-animation"},[s("div",{staticClass:"svg-box"},[s("svg",{staticClass:"circular green-stroke"},[s("circle",{staticClass:"path",attrs:{cx:"75",cy:"75",r:"50",fill:"none","stroke-width":"5","stroke-miterlimit":"10"}})]),s("svg",{staticClass:"checkmark green-stroke"},[s("g",{attrs:{transform:"matrix(0.79961,8.65821e-32,8.39584e-32,0.79961,-489.57,-205.679)"}},[s("path",{staticClass:"checkmark__check",attrs:{fill:"none",d:"M616.306,283.025L634.087,300.805L673.361,261.53"}})])])])]),t._v(" "),s("p",[t._v(t._s(t.successMsg))])])])}),[],!1,null,null,null);e.default=r.exports},455:function(t,e,s){"use strict";(function(t){var s={preview_title:"",preview_description:""};e.a={props:["product","canPost"],data:function(){return{clicked:!1,baseUrl:baseUrl,userData:s,selectedRating:0,imageIds:[],deleteIds:[],success:!1,successMsg:""}},methods:{submitReview:function(){var t=this,e=this;this.clicked=!0;var s=this.$serializeData(this.userData);s.append("preview_rating",this.selectedRating),s.append("preview_prod_id",this.product.prod_id),s.append("preview_order_id",this.canPost.order_id),s.append("imageIds",this.imageIds),s.append("deleteids",this.deleteIds),this.$axios.post(baseUrl+"/user/reviews",s).then((function(s){t.clicked=!1,t.success=!0,t.successMsg=s.data.message,setTimeout((function(){e.$bvModal.hide("reviewModel"),t.$emit("reviewSubmitted")}),2e3)}))},removeImage:function(t,e){var s=this;this.$axios.delete(baseUrl+"/user/reviews/images/"+e).then((function(a){s.deleteIds.push(e),s.$delete(s.imageIds,t)}))},onSelectImage:function(e){var s=this,a=this.$serializeData({preview_id:this.userData.preview_id}),i=parseInt(this.imageIds.length)+parseInt(e.target.files.length);if(i>5)return t.error("Max 5 images can be uploaded","",t.options),!1;for(var r=0,l=0;l<e.target.files.length;l++)e.target.files[l].size>2097152?r+=1:a.append("preview_image[]",e.target.files[l]);if(r>0&&t.error("Some Images with size more than 2 Mb are skipped. Please upload images with size less than 2 MB"),i<r)return!1;this.$axios.post(baseUrl+"/user/reviews/images",a).then((function(t){0!=t.data.data.length&&(s.imageIds=s.imageIds.concat(t.data.data))}))}}}}).call(this,s(231))}}]);