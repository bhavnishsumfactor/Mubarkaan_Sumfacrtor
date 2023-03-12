<template>
  <div class="app__collection recent-bought">
    <img :src="baseUrl +  '/admin/images/app-home/recent-bought-image-1.png'" />
    <div class="app__collection-actions">
        <div class="actions__wrapper">
          <button type="button" @click="$emit('deleteCollection',id)">
            <svg>
              <use :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#delete-icon'" />
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
 