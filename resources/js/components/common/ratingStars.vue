<template>
    <div class="rating">
        <div class="rating-bg">
            <svg class="icon"  width="24" height="24">
                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#star'" :href="baseUrl+'/admin/images/retina/sprite.svg#star'"></use>
            </svg>   
            <svg class="icon"  width="24" height="24">
                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#star'" :href="baseUrl+'/admin/images/retina/sprite.svg#star'"></use>
            </svg> 
            <svg class="icon"  width="24" height="24">
                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#star'" :href="baseUrl+'/admin/images/retina/sprite.svg#star'"></use>
            </svg> 
            <svg class="icon"  width="24" height="24">
                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#star'" :href="baseUrl+'/admin/images/retina/sprite.svg#star'"></use>
            </svg> 
            <svg class="icon"  width="24" height="24">
                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#star'" :href="baseUrl+'/admin/images/retina/sprite.svg#star'"></use>
            </svg> 
        </div>
        <div class="rating-fg" :style="'width: ' + (rating * 20) + '%;'">
            <svg class="icon"  width="24" height="24">
                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#star'" :href="baseUrl+'/admin/images/retina/sprite.svg#star'"></use>
            </svg>   
            <svg class="icon"  width="24" height="24">
                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#star'" :href="baseUrl+'/admin/images/retina/sprite.svg#star'"></use>
            </svg> 
            <svg class="icon"  width="24" height="24">
                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#star'" :href="baseUrl+'/admin/images/retina/sprite.svg#star'"></use>
            </svg> 
            <svg class="icon"  width="24" height="24">
                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#star'" :href="baseUrl+'/admin/images/retina/sprite.svg#star'"></use>
            </svg> 
            <svg class="icon"  width="24" height="24">
                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#star'" :href="baseUrl+'/admin/images/retina/sprite.svg#star'"></use>
            </svg> 
        </div>
    </div>
</template>
<script>
    export default {
        props: ['rating'],
        data() {
            return {
                baseUrl: baseUrl,
            }
        },
    }
</script>