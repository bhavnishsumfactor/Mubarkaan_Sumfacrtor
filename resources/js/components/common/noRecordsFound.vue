<template>
    <div class="no-data-found" >
        <div class="img" ><img :style="(typeof maxWidth != 'undefined') ? 'max-width: ' + maxWidth + 'px;' : ''" :src="(typeof image != 'undefined') ? baseUrl + '/admin/images/blank/' + image : this.$searchNotFoundImage(searchRecord)" alt=""></div>
        <div class="data">
            <h4 v-if="typeof heading != 'undefined'">{{ heading }}</h4>
            <h4 v-else>{{ $t('LBL_NO_RECORDS_FOUND_TITLE') }}</h4>

            <p v-if="typeof subHeading != 'undefined'">{{subHeading}}</p>
            <p v-else>{{$t('LBL_NO_RECORDS_FOUND_CAPTION')}}</p>
        </div>  
    </div>    
</template>
<script>
	export default {
        props: ['heading', 'image', 'maxWidth', 'subHeading', 'searchRecord'],
        data: function() {
            return {
                baseUrl: baseUrl
            };
        }, 
	}
</script>