(window.webpackJsonp=window.webpackJsonp||[]).push([[133],{238:function(t,a,r){"use strict";r.r(a);var e=r(436),o=r.n(e),s=(r(437),{components:{VueSlickCarousel:o.a},props:["product","imageTime","aspectRatio","index"],data:function(){return{baseUrl:baseUrl,currentTheme:currentTheme,flip180:!1,displayProductFront:!0,displayProductBack:!1,displayFlipLoader:!1,productAspectRatio:"",media:[],isFav:!1,gallerySliderSettings:{slidesToShow:1,slidesToScroll:1,autoplay:!0,autoplaySpeed:1500,arrows:!1,rtl:"rtl"==$("html").attr("dir"),dots:!0}}},methods:{updateFavorite:function(){var t=this;if(null!==this.$auth()){var a=this.$serializeData({id:this.product.prod_id,code:this.product.pov_code});this.$axios.post(baseUrl+"/product/favourite",a).then((function(a){if(0==a.data.status)return!1;var r=a.data.data.product,e=null,o=null;r&&(e=r.favId,o=r.ufp_pov_code),t.product.favId=e,t.product.ufp_pov_code=o,t.isFav=t.checkFavorite(t.product)}))}else this.$bvModal.show("loginModal")},viewGallery:function(){var t=this;this.flip180=!0,this.displayProductFront=!1,this.displayProductBack=!0,0===this.media.length&&(this.displayFlipLoader=!0,this.$axios.post(baseUrl+"/product/get-gallery-images",{product_id:this.product.prod_id,pov_code:this.product.pov_code}).then((function(a){t.displayFlipLoader=!1,t.productAspectRatio=a.data.data.productAspectRatio,t.media=a.data.data.images})))},closeGallery:function(){this.flip180=!0,this.displayProductFront=!0,this.displayProductBack=!1},checkFavorite:function(t){return!(!t.favId||""!=t.ufp_pov_code&&t.ufp_pov_code!=t.pov_code)}},mounted:function(){this.isFav=this.checkFavorite(this.product)}}),i=r(432),c=Object(i.a)(s,(function(){var t=this,a=t.$createElement,r=t._self._c||a;return r("div",{staticClass:"product",class:[0==t.$productStockVerify(t.product)?"no-stock-wrap":""]},[r("div",{staticClass:"product__body"},[r("picture",{staticClass:"product__img",attrs:{"data-ratio":t.aspectRatio}},[r("source",{attrs:{"data-aspect-ratio":t.aspectRatio,type:"image/png",srcset:t.$productImage(t.product.prod_id,t.product.pov_code,t.imageTime,t.$prodImgSize(t.aspectRatio,"medium"))}}),t._v(" "),r("img",{attrs:{"data-aspect-ratio":t.aspectRatio,src:t.$productImage(t.product.prod_id,t.product.pov_code,t.imageTime,t.$prodImgSize(t.aspectRatio,"medium",!0)),alt:""}})]),t._v(" "),r("div",{staticClass:"product__actions"},[r("button",{staticClass:"btn wishlist",class:[1==t.isFav?"active":""],attrs:{type:"button"},on:{click:function(a){return t.updateFavorite(t.product)}}},[r("svg",{staticClass:"svg",attrs:{width:"17.5",height:"15.455"}},[r("use",{attrs:{"xlink:href":t.baseUrl+"/yokart/"+t.currentTheme+"/media/retina/sprite-new.svg#icon__heart",href:t.baseUrl+"/yokart/"+t.currentTheme+"/media/retina/sprite-new.svg#icon__heart"}})])]),t._v(" "),r("button",{staticClass:"btn view_gallery",attrs:{type:"button"}},[r("svg",{staticClass:"svg",attrs:{width:"16",height:"15.994"}},[r("use",{attrs:{"xlink:href":t.baseUrl+"/yokart/"+t.currentTheme+"/media/retina/sprite-new.svg#icon__gallery",href:t.baseUrl+"/yokart/"+t.currentTheme+"/media/retina/sprite-new.svg#icon__gallery"}})])])]),t._v(" "),0!=t.product.discount?r("div",{staticClass:"product__offbadge"},[t._v(t._s(Math.round(t.product.discount))+"% OFF")]):t._e()]),t._v(" "),r("div",{staticClass:"product__footer"},[r("div",{staticClass:"d-md-flex justify-content-between"},[r("div",{staticClass:"product__leftcol"},[r("h6",{staticClass:"product__category"},[r("a",{attrs:{href:[t.product.cat_id?t.baseUrl+"/category/"+t.product.cat_id:"javascript:;"]}},[t._v(t._s(t.product.cat_name?t.product.cat_name:""))])]),t._v(" "),r("h2",{staticClass:"product__title"},[r("a",{attrs:{href:t.$generateUrl(t.product.url_rewrite)}},[t._v(t._s(t.product.prod_name))])]),t._v(" "),r("div",{staticClass:"product__price"},[r("span",{staticClass:"product__price-new"},[t._v(t._s(t.$priceFormat(t.product.finalprice)))]),t._v(" "),parseFloat(t.product.price)!==parseFloat(t.product.finalprice)?r("span",{staticClass:"product__price-old"},[t._v(t._s(t.$priceFormat(t.product.price)))]):t._e()])]),t._v(" "),r("div",{staticClass:"product__rightcol mt-md-0 mt-2"},[t.product.varients?r("div",{staticClass:"product__options"},[r("ul",{staticClass:"colors"},[t._l(t.product.varients.filter((function(t,a){return a<4})),(function(a,e){return r("li",{key:e},[r("span",{style:{backgroundColor:a.opn_color_code?a.opn_color_code:t.color.opn_value}})])})),t._v(" "),t.product.varients.length>4?r("li",[t._v("+"+t._s(t.product.varients.length-4))]):t._e()],2)]):t._e()])])])])}),[],!1,null,null,null);a.default=c.exports}}]);