<template>
  <div
    class="app__collection"
    v-if="appRecord && appRecord.product_collection1_records && appRecord.product_collection1_records.length > 0"
  >
    <div class="collection__head">
      <h3>{{appRecord.actr_title}}</h3>
      <a href="javascript:;">See all</a>
    </div>
    <div class="app__collection tiles__wrapper">
      <div class="tile__body tiles-slider scroll-x">
        <div
          class="card"
          v-for="(record, index) in appRecord.product_collection1_records"
          :key="index"
        >
          <div class="card__head">
            <div class="image__wrapper">
              <img
                :src="$productImage(record.prod_id, record.pov_code, $timestamp(record.prod_updated_on), '140-140')"
              />
            </div>
          </div>
          <div class="card__body">
            <div class="content">
              <h3 class="content__title">{{record.prod_name}}</h3>
              <div class="price">
                <span>{{currencySymbol +''+ record.price}}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="app__collection-actions">
        <div class="actions__wrapper">
          <button type="button" @click="$emit('deleteCollection',id)">
            <svg>
              <use :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#delete-icon'" />
            </svg>
          </button>
          <button type="button" @click="$emit('editCollection', id)">
            <svg>
              <use :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#edit-icon'" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="app__collection" v-else>
      <img :src="baseUrl +  '/admin/images/app-home/product-image-1.png'" />
      <div class="app__collection-actions">
        <div class="actions__wrapper">
          <button type="button" @click="$emit('deleteCollection',id)">
            <svg>
              <use :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#delete-icon'" />
            </svg>
          </button>
          <button type="button" @click="$emit('editCollection', id)">
            <svg>
              <use :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#edit-icon'" />
            </svg>
          </button>
        </div>
      </div>
  </div>
</template>
<script>
export default {
  props: ["id", "appRecord"],
  data() {
    return {
      currencySymbol: currencySymbol,
      baseUrl: baseUrl
    };
  }
};
</script>
