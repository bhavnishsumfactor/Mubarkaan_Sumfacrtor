<template>
  <li class="message__chat" v-bind:class="[message.message_from_admin == 0 ? 'message--right' : '']">
    <div class="msg">
        <div class="message__user">
          <span class="media media--circle media--sm">
            <img data-yk="" data-aspect-ratio="1:1" :src="baseUrl + '/' + this.$getFileUrl('favicon', 0, 0, '38-38')" alt="" v-if="message.message_from_admin" /> 
            <img data-yk="" data-aspect-ratio="1:1" :src="baseUrl +'/' + this.$getFileUrl('userProfileImage', message.message_from_id, 0, '50-50')" alt="" 
             v-else/>
          </span>
        </div>
        <div class="message__wrapper">
          <div class="message__text">
            <p v-if="message.upload_images">
                <img data-yk="" :src="baseUrl + '/' + this.$getFileUrl('messageFile', message.message_id, 0, 'thumb')" alt="" @click="openImage(message.message_id)">
            </p>
            <p v-else>{{ message.message_text }}</p>
            
          </div> 
          <p class="message__datetime">
              {{ $dateTimeFormat(message.message_created_at) }}
            </p>
        </div>
    </div>
  </li>
</template>
<script>
export default {
    props:["message"],
    data: function () {
      return {
        baseUrl: baseUrl
      };
    },
    methods: {
        openImage : function(messageId) {
            let url = baseUrl + '/' + this.$getFileUrl('messageFile', messageId, 0, 'thumb');
            window.open(url);
        }
    }
}
</script>