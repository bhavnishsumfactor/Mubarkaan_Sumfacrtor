window.Vue = require('vue');
require('./bootstrap');
import BootstrapVue from 'bootstrap-vue';
import VueTelInput from 'vue-tel-input';
import 'vue-toastr-2/dist/vue-toastr-2.min.css';
Vue.use(VueTelInput)
Vue.use([VueResource, VueToastr2]);
toastr.options.closeOnHover = false;
toastr.options.closeButton = true;
toastr.options.closeDuration = 10;
toastr.options.preventDuplicates = true;
Vue.use(VeeValidate, {   
    inject: true
});
Vue.use(BootstrapVue);
import 'bootstrap-vue/dist/bootstrap-vue.css';
import PerfectScrollbar from 'vue2-perfect-scrollbar'
import DeleteModel from './components/common/popups/delete.vue';
import 'vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css'
Vue.component('installer', require('./installer/index.vue').default);
Vue.component('colorpicker', require("./components/common/colorPicker/colorPicker.vue").default);
Vue.component('cropper', require("./components/common/cropper/cropper.vue").default);
import axios from 'axios';
Vue.prototype.$axios = axios;
Vue.component('DeleteModel', DeleteModel);
Vue.use(PerfectScrollbar)
Vue.prototype.$t = function(strings) {
    if (window.i18n[pageDefaultLanguage] && window.i18n[pageDefaultLanguage][strings]) {
        return window.i18n[pageDefaultLanguage][strings];
    }
    return strings;
}
Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

Vue.prototype.$serializeData = function(data) {
    let formData = new FormData();
    Object.keys(data).forEach(function(key) {
        formData.append(key, data[key]);
    });
    return formData;
}


const app = new Vue({
}).$mount('#app')