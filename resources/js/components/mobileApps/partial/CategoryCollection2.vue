<template>
  <div
    class="app__collection"
    v-if="appRecord && appRecord.cat_collection2_records && appRecord.cat_collection2_records.length > 0"
  >
    <div class="collection__head">
      <h3>{{appRecord.actr_title}}</h3>
      <a href="javascript:;">See all</a>
    </div>
    <div class="app__collection categories__wraper categories__wraper-v2">
      <div
        class="categories-item"
        v-for="(record, index) in appRecord.cat_collection2_records"
        :key="index"
      >
        <div class="category-image">
          <img
            :src="$getFileUrl('appCategoryCollection', record.acrd_id, 0, '94-94', $timestamp(appRecord.actr_updated_on))"
          />
        </div>
        <div class="category-detail">
          <h3>{{record.cat_name}}</h3>
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
  </div>
  <div class="app__collection" v-else>
     <img :src="baseUrl + '/admin/images/app-home/category-image-2.png'" />
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