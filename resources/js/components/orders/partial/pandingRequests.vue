<template>
  <b-modal
    id="pendingRequestModel"
    centered
    title="BootstrapVue"
    header-class="border-0"
    hide-footer
  >
    <template v-slot:modal-header>
      <button type="button" class="close" @click="closePopup()"></button>
    </template>
    <div class="modal__content-wrapper my-1 pb-5 text-center">
      <img  data-yk :src="baseUrl + '/admin/images/retina/oops.svg'" alt />
      <p class="px-3">{{$t('NOTI_YOU_HAVE_PENDING_REQUESTS')}}</p>
    </div>
  </b-modal>
</template>
<script>
export default {
  data: function() {
    return {
      baseUrl:baseUrl,
      clicked: 0
    };
  },
  methods: {
    closePopup: function() {
      this.$bvModal.hide("pendingRequestModel");
      this.$emit("updateListing");
    }
  }
};
</script>
