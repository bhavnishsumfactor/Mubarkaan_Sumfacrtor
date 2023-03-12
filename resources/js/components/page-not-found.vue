<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t("LBL_PAGE_NOT_FOUND") }}</h3>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="portlet justify-content-center">
            <div class="portlet__body">
              <div class="not-found h-100">
                <img :src="baseUrl +' /admin/images/404-error.svg'" alt="" />
                <h3>{{ $t("LBL_404") }} {{ $t("LBL_PAGE_NOT_FOUND_TITLE") }}</h3>
                <p>{{ $t("LBL_PAGE_NOT_FOUND_CAPTION") }}</p>
                <div class="action">
                  <router-link
                    :to="{ name: 'dashboard' }"
                    class="btn btn-brand btn-wide"
                    >{{ $t("BTN_BACK_TO_HOME") }}</router-link
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data: function () {
    return {
      baseUrl: baseUrl,
    };
  },
};
</script>