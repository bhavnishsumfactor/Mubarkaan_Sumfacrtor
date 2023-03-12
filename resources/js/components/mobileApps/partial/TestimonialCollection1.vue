<template>
  <div
    class="app__collection tiles__wrapper"
    v-if="appRecord && appRecord.testimonial_collection1_records && appRecord.testimonial_collection1_records.length > 0"
  >
    <h3>{{appRecord.actr_title}}</h3>
    <div class="tile__body reviews__wrapper tiles-slider scroll-x">
      <div
        class="card"
        v-for="(record, index) in appRecord.testimonial_collection1_records"
        :key="index"
      >
        <div class="card__head">
          <div class="image__wrapper">
            <img
              :src="$getFileUrl('appTestimonialCollection1', record.acrd_id, 0, '372-372', $timestamp(appRecord.actr_updated_on))"
            />
          </div>
        </div>
        <div class="card__body">
          <div class="content">
            <h3 class="content__title">{{record.testimonial_title}}</h3>
            <div class="content__description">
              <p>{{record.testimonial_description}}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="app__collection-actions">
      <div class="actions__wrapper">
        <button type="button" @click="$emit('deleteCollection',id, 'appTestimonialCollection1')">
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
  <div class="app__collection tiles__wrapper" v-else>    
    <img :src="baseUrl +  '/admin/images/app-home/review-image-1.png'" /> 
    <div class="app__collection-actions">
      <div class="actions__wrapper">
        <button type="button" @click="$emit('deleteCollection',id, 'appTestimonialCollection1')">
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
