<template>
  <div
    class="app__collection promotional__wraper promotional__wraper-v2"
    v-if="appRecord && appRecord.banner_slider_collection2_records && appRecord.banner_slider_collection2_records.length > 0"
  >
    <h3>{{appRecord.actr_title}}</h3>
    <div
      class="image-wrapper"
      v-for="(record, index) in appRecord.banner_slider_collection2_records"
      :key="index"
    >
      <img
        :src="$getFileUrl('appBannerSliderCollection', record.acs_id, 0, '298-149', $timestamp(appRecord.actr_updated_on))"
      />
    </div>
    <div class="app__collection-actions">
      <div class="actions__wrapper">
        <button type="button" @click="$emit('deleteCollection',id, 'banner')">
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
  <div class="app__collection" v-else>
    <img :src="baseUrl +  '/admin/images/app-home/collection-image-1.png'" />
    <div class="app__collection-actions">
      <div class="actions__wrapper">
        <button type="button" @click="$emit('deleteCollection',id, 'banner')">
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