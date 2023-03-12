<template>
    <div class="portlet__body" v-if="typeof removePortlet == 'undefined'">
        <div class="loader-wrapper">
            <div class="btn btn-outline-dark spinner spinner--v2 spinner--right spinner--dark">{{ $t('LBL_LOADING') }}</div>
        </div> 
    </div>
    <div class="loader-wrapper" v-else>
        <div class="btn btn-outline-dark spinner spinner--v2 spinner--right spinner--dark">{{ $t('LBL_LOADING') }}</div>
    </div> 
</template>
<script>
    export default {
        props: ['removePortlet']
	}
</script>