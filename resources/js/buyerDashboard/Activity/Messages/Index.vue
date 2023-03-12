<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar
                :pageType="'messages'"
                :messageNav="'sidebarNav'"
                @switchLayout="switchLayout"
                v-if="sidebarNav"
              ></left-sidebar>

              <listing-sidebar
                :messages="messages"
                :messageId="messageId"
                :search="search"
                :productSearch="productSearch"
                @getMessagesDetails="getMessagesDetails"
                @switchLayout="switchLayout"
                @searchThread="searchThread"
                v-if="sidebarNav == false"
              ></listing-sidebar>
        
              <main class="main-content" data-yk role="yk-main-content">
                <div
                  class="card"
                  v-bind:class="[messages.length == 0 && productSearch != true ? 'h-100' : '']"
                  id="YK-messages"
                >
                  <div
                    class="card-body p-0"
                    v-bind:class="[
                      messages.length == 0 && productSearch != true
                        ? 'd-flex align-items-center justify-content-center'
                        : '',
                    ]"
                  >
                    <div class="messages-page">
                      <div
                        class="messages-detail"
                        id="YK-Message-chat"
                        v-if="messages.length != 0 || productSearch == true"
                        v-bind:class="[detailPage ? 'active' : '']"
                      >
                        <thread
                          :messagesDetails="messagesDetails"
                          @closeDetailPage="closeDetailPage"
                          v-if="Object.keys(messagesDetails).length > 0"
                        ></thread>
                        
                         <div class="message-dummy" v-else>
                          <img
                            data-yk
                            :src="
                              baseUrl +
                                '/dashboard/media/retina/start-chat.svg'
                            "
                            alt
                          />
                          <p>{{$t('LBL_CHAT_THREAD_DETAILS')}}</p>
                        </div>
                      </div>
                      <div class="no-data-found no-data-found--sm" v-if="messages.length == 0  && productSearch != true">
                        <div class="img">
                          <img
                            data-yk
                            :src="
                              baseUrl +
                                '/dashboard/media/retina/no-messages.svg'
                            "
                            alt
                          />
                        </div>
                        <div class="data">
                          <h2>{{ $t("LBL_NO_MESSAGES") }}</h2>
                          <p>{{ $t("LBL_NO_MESSAGES_TO_SHOW") }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </main>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import leftSidebar from "@/buyerDashboard/Sidebar";
import dasboardHeader from "@/buyerDashboard/Header";
import listingSidebar from "@/buyerDashboard/Activity/Messages/Sidebar";
import Thread from "@/buyerDashboard/Activity/Messages/Thread";

export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader,
    listingSidebar: listingSidebar,
    Thread: Thread
  },
  props: ["allMessages"],
  data: function() {
    return {
      sending: false,
      baseUrl: baseUrl,
      messages: [],
      messagesDetails: {},
      search: "",
      productSearch: false,
      messageId: "",
      sidebarNav: false,
       detailPage: false
    };
  },
  methods: {
    getMessagesDetails: function(id) {
      this.$axios.get(baseUrl + "/user/message/detail/" + id).then(response => {
        this.messageId = id;
       this.messagesDetails = response.data.data;
        this.detailPage = true;
        setTimeout(() => {
          $('#YK-message-container').scrollTop($('#YK-message-container')[0].scrollHeight);
        }, 500);
      });
    },
    switchLayout: function(type) {
      if(this.messages.length == 0){
        return false;
      }
      this.sidebarNav = type;
    },
    searchThread: function(searchData) {
      this.productSearch = true;
      this.search = searchData;
      let formData = this.$serializeData({ search: searchData });
      this.$axios
        .post(baseUrl + "/user/search-thread", formData)
        .then(response => {
          this.messages = response.data.data;
        });
    },
    closeDetailPage: function() {
      this.detailPage = false;
      // if (this.$mq !== 'mobile') {
      //   this.messageId = "";
      // }
    }
  },

  mounted: function() {
   this.messages = this.allMessages;
    if(this.messages.length == 0){
       this.sidebarNav = true;
    }
    
  }
};
</script>
