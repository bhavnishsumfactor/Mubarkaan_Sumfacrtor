<template>
  <div
    class="app__collection"
    v-if="appRecord && appRecord.brand_collection1_records && appRecord.brand_collection1_records.length > 0"
  >
    <div class="collection__head">
      <h3>{{appRecord.actr_title}}</h3>
      <a href="javascript:;">See all</a>
    </div>
    <div class="app__collection tiles__wrapper">
      <div class="tile__body brands__wrapper">
        <div
          class="card"
          v-for="(record, index) in appRecord.brand_collection1_records"
          :key="index"
        >
          <div class="card__head">
            <div class="image__wrapper">
              <img
                :src="$getFileUrl('brandLogo', record.brand_id, 0, '160-40', $timestamp(appRecord.actr_updated_on))"
              />
            </div>
          </div>
          <div class="card__body">
            <div class="image__wrapper">
              <img
                :src="$getFileUrl('appBrandCollection', record.acrd_id, 0, '114-114', $timestamp(appRecord.actr_updated_on))"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="app__collection-actions">
        <div class="actions__wrapper">
          <button type="button" @click="$emit('deleteCollection',id, 'appBrandCollection')">
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
  <div class="app__collection tiles__wrapper" v-else>
    <img :src="baseUrl + '/admin/images/app-home/brand-image-1.png'" />    
      <div class="app__collection-actions">
        <div class="actions__wrapper">
          <button type="button" @click="$emit('deleteCollection',id, 'appBrandCollection')">
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
      baseUrl: baseUrl
    };
  }
};
</script>
