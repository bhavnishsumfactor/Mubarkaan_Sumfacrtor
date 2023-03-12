<template>
<b-modal id="previewModel" size="lg" centered title="BootstrapVue">
    <template v-slot:modal-header>
        <h5 class="modal-title" id="exampleModalLabel">{{ templateName + ' - ' + $t('LBL_PREVIEW') }}</h5>
        <button type="button" class="close" @click="$bvModal.hide('previewModel');"></button>
    </template>
        <perfect-scrollbar :style="'max-height: 600px'">
        <div v-html="pageHtml"></div>
        </perfect-scrollbar> 
    <template v-slot:modal-footer>
        <button type="button" class="btn btn-brand" @click="$bvModal.hide('previewModel');">{{ $t('BTN_CLOSE')}}</button>
    </template>
</b-modal>
</template>
<script>
export default {
    props: ['header', 'footer', 'body', 'templateName'],
    computed: {
        pageHtml: function () {
            return this.header + 
            '<div class="body" role="body" style="margin:0 auto;max-width:628px;" data-width_container="max-width" max-width="628px">' + this.body + '</div>'
            + this.footer;
        }
    },
}
</script>
