<template>
  <div
    class="app__collection reviews__wrapper-v2"
    v-if="appRecord && appRecord.testimonial_collection2_records && appRecord.testimonial_collection2_records.length > 0"
  >
    <div class="collection__head">
      <h3>{{appRecord.actr_title}}</h3>
      <a href>See all</a>
    </div>
    <div class="collection__body slider-wrapper  scroll-x">
      <div
        class="content"
        v-for="(record, index) in appRecord.testimonial_collection2_records"
        :key="index"
      >
        <h3 class="content__title">{{record.testimonial_title}}</h3>
        <p class="content__desc">{{record.testimonial_description}}</p>
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
  <div class="app__collection reviews__wrapper-v2" v-else>
    <img :src="baseUrl +  '/admin/images/app-home/sweet-word-image-1.png'" />
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
      baseUrl: baseUrl
    };
  }
};
</script>
