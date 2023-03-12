<template>
   <div class="app__collection promotional__wraper">

     
      <div class="app__collection-content" v-if="appRecord && appRecord.collections_data && appRecord.collections_data.length">
       <ul v-if="type == 23">
         <li v-for="(collection, index) in appRecord.collections_data" :key="index">{{collection.acrd_description}}</li>
        </ul>
        
        <span v-html="appendTags(type, appRecord.collections_data)" v-else></span>
       </div>
          <div class="app__collection-content" v-html="appendTags(type)" v-else>
       </div>
      
       <div class="app__collection-actions">
        <div class="actions__wrapper">
          <button type="button" @click="$emit('deleteCollection',id, 'appImageCollection')">
            <svg>
              <use :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#delete-icon'" />
            </svg>
          </button>
          <button type="button" @click="$emit('editCollection', id)">
            <svg>
              <use :xlink:href=" baseUrl + '/admin/images/retina/sprite.svg#edit-icon'" />
            </svg>
          </button>
        </div>
      </div>
    </div>
</template>
<script>
export default {
  props: ["type", "id", "appRecord"],
  data() {
    return {
      baseUrl: baseUrl
    };
  }, 
  methods: {
    appendTags: function(type, data = '') {
      let defaultText = '';
      let imgUrl = baseUrl + '/admin/images/app-home/blog-img-1.png';
      if(data.length != 0){
              defaultText = data[0].acrd_description;
              imgUrl = this.$getFileUrl('appImageCollection',  data[0].acrd_id, 0, '412-206');
            }   
      switch (type) {
        case 16:
          if(defaultText == ''){
             defaultText = 'H1 <span class="dummy-text">lorem Ipsum</span>';
          }    
        return '<h1>'+defaultText+'</h1>';
        break;
        case 17:
           if(defaultText == ''){
             defaultText = 'H2 <span class="dummy-text">lorem Ipsum</span>';
          }    
        return '<h2>'+defaultText+'</h2>';
        break;
        case 18:
            if(defaultText == ''){
             defaultText = 'H3 <span class="dummy-text">lorem Ipsum</span>';
          }    
        return '<h3>'+defaultText+'</h3>';
        break;
        case 19:
          if(defaultText == ''){
             defaultText = 'H4 <span class="dummy-text">lorem Ipsum</span>';
          }    
        return '<h4>'+defaultText+'</h4>';
        break;
        case 20:
          if(defaultText == ''){
             defaultText = 'H5 <span class="dummy-text">lorem Ipsum</span>';
          }    
        return '<h5>'+defaultText+'</h5>';
        break;  
        case 21:
          if(defaultText == ''){
             defaultText = 'H6 <span class="dummy-text">lorem Ipsum</span>';
          }    
        return '<h6>'+defaultText+'</h6>';
        break;
        case 22:
          if(defaultText == ''){
             defaultText = `Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo animi commodi esse eius incidunt? Iure eum iste aspernatur mollitia ab fuga, placeat tempora ....`;
          }    
        return '<p>'+defaultText+'</p>';
          break;
       case 23:
        return `<ul>
         <li>List Item</li>
          <li>List Item</li>
          <li>List Item</li>
          <li>List Item</li>
          <li>List Item</li>
        </ul>`;
        break;
       case 24:
        return '<div class="image-wrapper"> <img src="'+imgUrl+'" /></div>';
        break;
      }
    },
  },
};
</script>
