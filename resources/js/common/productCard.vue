<template>
  <li class="favourite" v-bind:class="[productStockVerify(product) == false ? 'no-stock-wrap' : '']">   
    <div class="favourite_body">
      <div class="no-stock" v-if="productStockVerify(product) == false">{{$t("LBL_OUT_OF_STOCK")}}</div>
      <div class="favourite_actions">
        <button class="btn wishlist active" type="button" @click="$emit('updateFavourite', index)">
          <i class="icn" tooltip-popup-delay="300" tooltip-append-to-body="true">
            <svg class="svg">
                  <use
                    :xlink:href="
                      baseUrl + '/dashboard/media/retina/sprite.svg#favorite'
                    "
                  ></use>
                </svg>
          </i> 
        </button>
        <button
          class="btn"
          @click="$addToCart(product.prod_id, product.pov_code)"
          type="button"
          :disabled="productStockVerify(product) == false"
        >
          <i class="icn" tooltip-popup-delay="300" tooltip-append-to-body="true">
            <svg class="svg" >
              <use
                :xlink:href="baseUrl+'/dashboard/media/retina/sprite.svg#cart'"
                :href="baseUrl+'/dashboard/media/retina/sprite.svg#cart'"
              />
            </svg>
          </i>
        </button>
      </div>
      <a :href="$generateUrl(product.url_rewrite, product.pov_id)">
        <div class="favourite_img" :data-ratio="aspectRatio">
          <picture>
            <source
              loading="lazy"
              type="image/webp"
              :srcset="$productImage(product.prod_id, product.pov_code, imageTime, '269-359-webp')"
              alt
            />
            <img loading="lazy" data-yk :src="$productImage(product.prod_id, product.pov_code, imageTime, '269-359')" alt />
          </picture>
        </div>
      </a>
    </div>

    <div class="favourite_foot" v-bind:class="[product.pov_display_title ? '' : 'no-options']">
      <div class="favourite_name">
      
        <a :href="$generateUrl(product.url_rewrite, product.pov_id)">
          {{
          $setStringLength(product.prod_name, 25)
          }}
        </a>
      </div>
      <span class="favourite_category">
       <!-- <a
          :href="[
            product.cat_id
              ? baseUrl + '/category/' + product.cat_id
              : 'javascript:;',
          ]"
        >{{ product.cat_id ? $setStringLength(product.cat_name, 20) : "" }}</a> discuss with rahil and pawan before delete-->
        {{product.pov_display_title ? product.pov_display_title.replace("_", " | ") : '' }}
      </span>

      <div class="favourite_price">
        <span class="favourite_price_new notranslate">{{ $priceFormat(product.finalprice) }}</span>
        <span
          v-if="product.finalprice != product.finalprice"
          class="favourite_price_old notranslate"
        >{{ $priceFormat(product.price) }}</span>
      </div>
    </div>
  </li>
</template>

<script>
export default {
  props: ["product", 'imageTime', "aspectRatio", "index"],
  data() {
    return {
      baseUrl: baseUrl
    };
  },
  methods: {
    productStockVerify: function(product) {
      let stock = true;
      if (product.prod_stock_out_sellable == 0) {
        if (product.totalstock < product.prod_min_order_qty) {
          stock = false;
        }
        if (
          product.prod_min_order_qty != 0 &&
          product.totalstock < product.prod_min_order_qty
        ) {
          stock = false;
        }
      }
      return stock;
    }
  }
};
</script>
