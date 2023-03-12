<template>
  <div
    class="app__collection tiles__wrapper blog__wrapper blog__wrapper-v2"
    v-if="appRecord && appRecord.blog_collection2_records && appRecord.blog_collection2_records.length > 0"
  >
    <div class="collection__head">
      <h3>{{appRecord.actr_title}}</h3>
      <a href>See all</a>
    </div>

    <div class="app__collection tiles__wrapper blog__wrapper">
      <div class="tile__body scroll-x">
        <div
          class="card"
          v-for="(record, index) in appRecord.blog_collection2_records"
          :key="index"
        >
          <div class="card__head">
            <div class="image__wrapper">
              <img
                :src="$getFileUrl('blogImage', record.post_id, 0, '372-186', $timestamp(appRecord.actr_updated_on))"
              />
            </div>
          </div>
          <div class="card__body">
            <div class="content">
              <p class="content__date">{{record.post_published_on | formatDate}}</p>
              <h3 class="content__title">{{record.post_title}}</h3>
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
    <img :src="baseUrl +'/admin/images/app-home/blog-image-2.png'" />
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
