<template>
<div class="">
  <div class="account-menu" id="account-menu" data-close-on-click-outside="account-menu">
    <!--<button class="" data-target-close="account-menu"  v-if="$mq !== 'desktop'">Close</button>-->
    <div class="sidebar_body">
      <div class="messages-listing" v-if="messages.length != 0 || productSearch == true">
        <div class="messages-listing__head">
          <a class="back" href="javascript:void(0);" @click="$emit('switchLayout', true)">{{ $t('LNK_BACK') }}</a>
          <h5>{{$t('LBL_MESSAGE') }}</h5>
        </div>
        <div class="input-group input-group-solid messages-listing__search">
          <input
            type="text"
            class="form-control"
            id="YK-search-product"
            :placeholder="$t('PLH_SEARCH')"
            v-model="searchRecord"
            @keyup="$emit('searchThread', searchRecord)"
          />
          <div class="input-group-prepend">
            <span class="input-group-text">
              <svg class="svg"> 
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#magni'
                  "
                  :href="baseUrl + '/dashboard/media/retina/sprite.svg#magni'"
                />
              </svg>
            </span>
          </div>
        </div>
        <div class="messages-listing__users scroll-y">
          <ul id="YK-message-sidebar" class v-if="messages.length != 0">
            <li
              v-for="(message, mKey) in messages"
              :key="mKey"
              :data-id="mKey"
              v-bind:class="[message.thread_id == messageId ? 'active' : '']"
            >
              <a class="user-side" href="javascript:;" @click="renderMessages(message.thread_id)">
                <div class="user-pic">
                  <img
                    data-yk 
                    :src="
                      $productImage(
                        message.thread_product_id,
                        message.thread_product_variant,
                        message.images ? message.images.afile_updated_at : '',
                        '38-38'
                      )
                    "
                  />

                  <span
                    class="badge badge--success"
                    v-if="message.get_unread_message_count > 0"
                  >{{ message.get_message_count_count }}</span>
                </div>
                <div class="user-detail">
                  <span class="user-name">{{$setStringLength(message.thread_product_name, 25) }}</span>
                  <span class="user-last-message">
                    {{
                    $setStringLength(
                    message.get_last_message.message_text,
                    25
                    )
                    }}
                  </span>
                </div>
              </a>
              <div class="user-time">
                <span
                  class="time"
                  v-if="
                    $dateTimeFormat(new Date(), 'date') ===
                      $dateTimeFormat(
                        message.get_last_message.message_created_at,
                        'date'
                      )
                  "
                >
                  {{
                  $dateTimeFormat(
                  message.get_last_message.message_created_at,
                  "time"
                  )
                  }}
                </span>
                <span class="date" v-else>
                  {{
                  $dateTimeFormat(
                  message.get_last_message.message_created_at,
                  "date"
                  )
                  }}
                </span>
              </div>
            </li>
          </ul>

          <div class="no-data-found no-data-found--sm" v-else>
            <div class="img">
              <img data-yk :src="baseUrl + '/dashboard/media/retina/no-messages.svg'" alt />
            </div>
            <div class="data">
              <h2>{{ $t("LBL_NO_MESSAGES") }}</h2>
              <p>{{ $t("LBL_NO_MESSAGES_TO_SHOW") }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="back-overlay"></div> 
  </div> 
</template>
<script>
export default {
  props: ["messages", "messageId", "productSearch", "search"],
  data: function() {
    return {
      baseUrl: baseUrl,
      searchRecord: ''
    };
  },
   mounted: function() {
   this.searchRecord = this.search;
  },
  
  methods: {
    renderMessages: function(id) {
      this.$emit("getMessagesDetails", id);
    }
  }
};
</script>
