<template>
    <div id="app" >
        <vue-particles color="#dedede"></vue-particles>
        <div class="orientation-landscape">            
              <img class="orientation-landscape_img" :src="baseUrl + '/admin/images/retina/orientation-landscape.svg'"  alt="">
            <div class="orientation-landscape_message">Please rotate your device to landscape mode</div>
        </div>
    </div>
</template>
<script>

export default {
    name: 'App',   
    data: function() {
        return {
            baseUrl: baseUrl,
           
        };
    }, 
}
</script>