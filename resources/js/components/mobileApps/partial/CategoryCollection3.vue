<template>
  <div
    class="app__collection tiles__wrapper"
    v-if="appRecord && appRecord.cat_collection3_records && appRecord.cat_collection3_records.length > 0"
  >
    <div class="tile__header">
      <h3>{{appRecord.actr_title}}</h3>
    </div>
    <div class="tile__body">
      <div class="card" v-for="(record, index) in appRecord.cat_collection3_records" :key="index">
        <div class="card__head">
          <div class="image__wrapper">
            <img
              :src="$getFileUrl('appCategoryCollection', record.acrd_id, 0, '140-140', $timestamp(appRecord.actr_updated_on))"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="app__collection-actions">
      <div class="actions__wrapper">
        <button type="button" @click="$emit('deleteCollection',id, 'appCategoryCollection')">
          <svg>
            <use :xlink:href="baseUrl +'/admin/images/retina/sprite.svg#delete-icon'" />
          </svg>
        </button>
        <button type="button" @click="$emit('editCollection', id)">
          <svg>
            <use :xlink:href="baseUrl +'/admin/images/retina/sprite.svg#edit-icon'" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <div class="app__collection tiles__wrapper" v-else>
    <img :src="baseUrl + '/admin/images/app-home/best-buy-image-2.png'" />
    
    <div class="app__collection-actions">
      <div class="actions__wrapper">
        <button type="button" @click="$emit('deleteElement', id)">
          <svg>
            <use :xlink:href="baseUrl +'/admin/images/retina/sprite.svg#delete-icon'" />
          </svg>
        </button>
        <button type="button">
          <svg>
            <use :xlink:href="baseUrl +'/admin/images/retina/sprite.svg#edit-icon'" />
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