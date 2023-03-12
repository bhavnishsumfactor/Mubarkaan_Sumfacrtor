/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 __webpack_require__.p = baseUrl + '/admin/';

 window.Vue = require('vue');
 require('./bootstrap');
 import VueInternalization from 'vue-i18n';
 import router from './routes';
 require('bootstrap');
 
 var VueDragula = require('vue-dragula');
 
 Vue.use(VueDragula);
 
 import 'vue-toastr-2/dist/vue-toastr-2.min.css';
 Vue.use([VueResource, VueToastr2]);
 
 import VuejsDialog from 'vuejs-dialog';
 Vue.use(VuejsDialog);
 
 import 'vuejs-dialog/dist/vuejs-dialog.min.css';
 
 import VueCardFormat from 'vue-credit-card-validation';
 Vue.use(VueCardFormat);
 
 Vue.use(VeeValidate, {
     inject: true
 });
 
 
 import VueMq from 'vue-mq';
 Vue.use(VueMq, {
     breakpoints: {
         mobile: 576,
         tablet: 1025,
         laptop: 1366,
         desktop: Infinity,
     }
 })
 Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
 
 
 import VueParticles from 'vue-particles';
 Vue.use(VueParticles);
 Vue.use(require('vue-chartist'));
 import PerfectScrollbar from 'vue2-perfect-scrollbar'
 import 'vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css'
 import VueExcelEditor from 'vue-excel-editor'
 Vue.use(VueExcelEditor)
 Vue.use(PerfectScrollbar)
 window.Vue.use(VueInternalization);
 import Permissions from './Permissions.vue';
 import VueTelInput from 'vue-tel-input'
 Vue.use(VueTelInput)
 
 import VueHtmlToPaper from 'vue-html-to-paper';
 
 const options = {
     name: '_blank',
     specs: [
         'fullscreen=yes',
         'titlebar=yes',
         'scrollbars=yes'
     ],
     styles: [
         'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'
     ]
 }
 Vue.use(VueHtmlToPaper, options);
 //import { Laue } from 'laue';
 //Vue.use(Laue);
 import excel from 'vue-excel-export'
 Vue.use(excel)
 //Vue.component('downloadExcel', JsonExcel);
 Vue.component('App', require('./components/app.vue').default);
 Vue.mixin(Permissions);
 toastr.options.closeOnHover = false;
 toastr.options.closeButton = true;
 toastr.options.closeDuration = 10;
 toastr.options.preventDuplicates = true;
 
 if (typeof window.Laravel.settings != 'undefined') {
     toastr.options.timeOut = (window.Laravel.settings['SYSTEM_MESSAGE_STATUS'] == 1 && window.Laravel.settings['SYSTEM_MESSAGE_CLOSE_TIME'] != 0) ?
         window.Laravel.settings['SYSTEM_MESSAGE_CLOSE_TIME'] * 1000 : 0;
     toastr.options.positionClass = window.Laravel.settings['SYSTEM_MESSAGE_POSITION'];
 }
 
 
 import BootstrapVue from 'bootstrap-vue';
 import DeleteModel from './components/common/popups/delete.vue';
 import ConfirmModel from './components/common/popups/confirm.vue';
 import loader from './components/common/loader.vue';
 import noRecordsFound from './components/common/noRecordsFound.vue';
 import ratingStars from './common/ratingStars.vue';
 Vue.component('DeleteModel', DeleteModel);
 Vue.component('ConfirmModel', ConfirmModel);
 Vue.component('loader', loader);
 Vue.component('noRecordsFound', noRecordsFound);
 Vue.component('ratingStars', ratingStars);
 Vue.use(BootstrapVue);
 import 'bootstrap-vue/dist/bootstrap-vue.css';
 import InfiniteLoading from 'vue-infinite-loading';
 
 Vue.component('logo', require('./components/logo.vue').default);
 Vue.component('navbar', require('./components/navbar.vue').default);
 Vue.component('menubar', require('./components/menubar.vue').default);
 Vue.component('notificationbar', require('./components/notificationbar.vue').default);
 Vue.component('userprofilebar', require('./components/userProfileBar.vue').default);
 Vue.component('left-sidebar', require('./components/leftSidebar.vue').default);
 Vue.component('quick-panel', require('./components/quickPanel.vue').default);
 Vue.component('ProfileLinks', require('./components/profileLinks.vue').default);
 Vue.component('pagination', require('./components/pagination.vue').default);
 
 Vue.use(InfiniteLoading, {
     props: {
         spinner: 'spiral',
         // spinner: 'circles',
         // spinner: 'bubbles',
         // spinner: 'waveDots',
         /* other props need to configure */
     },
     slots: {
         noMore: '',
         noResults: '',
     },
 });
 
 Vue.component('cropper', () =>
     import("./components/common/cropper/cropper.vue"));
 Vue.component('colorpicker', () =>
     import("./components/common/colorPicker/colorPicker.vue"));
 
 import moment, {
     now
 } from 'moment'
 import moment_timezone from 'moment-timezone'
 Vue.use(moment);
 
 
 Vue.http.interceptors.push(function (request, next) {
     next(function (response) {
 
     });
 });
 Vue.prototype.$tinyMceApiKey = function () {
     return '9ds9hfrc1eely5vt7mmsqoldzp3nr4mqnldk7ui63qgfxmz6';
 }
 Vue.prototype.$searchNotFoundImage = function (searchRecord = 0) {
     if (searchRecord == 0) {
         return baseUrl + '/admin/images/retina/no-found.svg';
     }
     return baseUrl + '/admin/images/retina/no-results-found.svg';
 }
 Vue.prototype.$inArray = function (needle, haystack) {
     var length = haystack.length;
     for (var i = 0; i < length; i++) {
          if (parseInt(haystack[i]) === parseInt(needle)){
            console.log(needle);
            console.log(haystack);
          return true;
          }
          
     }
     return false;
 }
 
 Vue.prototype.$noDataImage = function (image = '') {
     if (image) {
         return baseUrl + '/admin/images/retina/' + image;
     }
     return baseUrl + '/admin/images/retina/info.svg';
 }
 Vue.prototype.$noImage = function (name) {
     return baseUrl + '/noimages/' + name;
 }
 
 Vue.prototype.$t = function (strings) {
     if (window.i18n[pageDefaultLanguage] && window.i18n[pageDefaultLanguage][strings]) {
         return window.i18n[pageDefaultLanguage][strings];
     }
     return strings;
 }
 
 Vue.prototype.$rndStr = function (len) {
     if (typeof len == 'undefined') {
         len = 10;
     }
     let text = ""
     let chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"
     for (let i = 0; i < len; i++) {
         text += chars.charAt(Math.floor(Math.random() * chars.length))
     }
     return text
 }
 Vue.prototype.$rndInt = function (len) {
     if (typeof len == 'undefined') {
         len = 10;
     }
     let text = ""
     let chars = "0123456789"
     for (let i = 0; i < len; i++) {
         text += chars.charAt(Math.floor(Math.random() * chars.length))
     }
     return text
 }
 Vue.prototype.$productImage = function (productId, variantCode, timestamp = '', size = 'small') {
     let url = 'yokart/product/image/' + productId + '/' + variantCode + '/' + size;
     if (timestamp != '') {
         url = url + '?t=' + timestamp;
     }
     return url;
 }
 Vue.prototype.$getFileUrl = function (section, recordId = 0, subRecordId = 0, size = 'original', timestamp = '') {
     let url = 'yokart/image/' + section + '/' + recordId + '/' + subRecordId + '/' + size;
     if (timestamp != '') {
         url = url + '?t=' + timestamp;
     }
     return url;
 }
 Vue.prototype.$getFileUrlById = function (id, size, timestamp = '') {
     let url = 'yokart/image/' + id + '/' + size;
     if (timestamp != '') {
         url = url + '?t=' + this.$timestamp(timestamp);
     }
     return url;
 }
 Vue.prototype.$pagesNumber = function (pagination, offset = 2) {
         if (!pagination.to) {
             return [];
         }
         let from = pagination.current_page - offset;
         if (from < 1) {
             from = 1;
         }
         let to = from + (offset * 2);
         if (to >= pagination.last_page) {
             to = pagination.last_page;
         }
         let pagesArray = [];
         while (from <= to) {
             pagesArray.push(from);
             from++;
         }
         return pagesArray;
     },
     Vue.prototype.$cleanString = function (strings) {
         return strings.replace(/[^A-Z0-9]/ig, "").toLowerCase();
     }
 Vue.prototype.$orderSubTotal = function (order) {
     let tax = 0;
     if (order.order_tax_charged) {
         tax = order.order_tax_charged;
     }
     let shipping = 0;
     if (order.order_shipping_value) {
         shipping = order.order_shipping_value;
     }
     let discount = 0;
     if (order.order_discount_amount) {
         discount = order.order_discount_amount;
     }
     let giftprice = 0;
     if (order.order_gift_amount) {
         giftprice = order.order_gift_amount;
     }
     let rewardAmount = 0;
     if (order.order_reward_amount) {
         rewardAmount = order.order_reward_amount;
     }
     return (
         parseFloat(order.order_net_amount - tax - shipping - giftprice) +
         parseFloat(rewardAmount) +
         parseFloat(discount)
     );
 
 }
 Vue.prototype.$serializeData = function (data) {
     let formData = new FormData();
     Object.keys(data).forEach(function (key) {
         formData.append(key, data[key]);
     });
     return formData;
 }
 Vue.prototype.$timestamp = function (dateTime) {
         return moment(String(dateTime)).unix();
     },
     Vue.prototype.$cleanPhoneNumber = function (phoneNumber) {
         return phoneNumber.replace(/\D/g, "");
     },
 
     Vue.filter('formatDateTime', function (value, format) {
         if (value == '0000-00-00 00:00:00') {
             return null;
         }
         if (typeof format == 'undefined') {
             format = 'DD MM, YYYY HH:mm';
         }
         if (value) {
             let timeFormat = getTimeFormat();
             format = window.localStorage.getItem('dateformat') + " " + timeFormat;
             return moment.utc(String(value)).tz(window.localStorage.getItem('timezone')).format(format);
         }
     });
 Vue.filter('orderDateTime', function (value, format) {
     if (value == '0000-00-00 00:00:00') {
         return null;
     }
     if (typeof format == 'undefined') {
         format = 'DD MM, YYYY HH:mm';
     }
     if (value) {
         let timeFormat = getTimeFormat();
         format = window.localStorage.getItem('dateformat') + " | " + timeFormat;
         return moment.utc(String(value)).tz(window.localStorage.getItem('timezone')).format(format);
     }
 });
 Vue.filter('formatTime', function (value, convert = 1) {
     let timeFormat = getTimeFormat();
     //console.log('>>>>>', value);
     if (convert == 1 && value) {
         return moment.utc(String(value)).tz(window.localStorage.getItem('timezone')).format(timeFormat);
     }
     return moment(value).format(timeFormat)
 });
 Vue.filter('highlight', function (word, query) {
     var check = new RegExp(query, "ig");
     return word.toString().replace(check, function (matchedText, a, b) {
         return (query != '') ? ('<strong class="highlight">' + matchedText + '</strong>') : matchedText;
     });
 });
 
 Vue.filter('formatDate', function (value, format, dob = 0) {
     if (typeof format == 'undefined') {
         format = 'DD MM, YYYY';
     }
     if (dob == 0 && value) {
         return moment.utc(String(value)).tz(window.localStorage.getItem('timezone')).format(window.localStorage.getItem('dateformat'));
     }
     return moment.utc(String(value)).tz(window.localStorage.getItem('timezone')).format(window.localStorage.getItem('dateformat'))
 });
 Vue.filter('removeHyphen', function (fromString) {
     if (fromString) {
         return fromString.replace(/-/g, ' ');
     }
 });
 Vue.filter('upperCase', function (str) {
     return str.toUpperCase();
 });
 
 
 Vue.prototype.$cleanShipStatus = function (fromString) {
     if (fromString) {
         let str = fromString.replace(/-/g, ' ');
         return str.charAt(0).toUpperCase() + str.slice(1);
     }
 };
 Vue.prototype.$displaynumberFormat = function (amount) {
     return amountFormated(amount);
 
 };
 Vue.filter('numberFormat', function (amount) {
     return amountFormated(amount);
 });
 Vue.filter('camelCase', function (str) {
     if (str) {
         return str.charAt(0).toUpperCase() + str.slice(1);
     }
 });
 Vue.prototype.$collectionNameExist = function (needle, haystack) {
     var length = haystack.length;
     for (var i = 0; i < length; i++) {
         if (haystack[i].name == needle) return true;
     }
     return false;
 }
 Vue.filter('truncate', function (text, length) {
     if (text) {
         if (typeof length == 'undefined') {
             length = 20;
         }
         return text.substr(0, length - 1) + (text.length > length ? '...' : '');
     }
 });
 Vue.prototype.$nl2br = function (str) {
     if (str) {
         return str.replace(/(?:\r\n|\r|\n)/g, '<br>');
     }
 };
 Vue.prototype.$truncateTxt = function (text, length) {
     if (text) {
         if (typeof length == 'undefined') {
             length = 20;
         }
         return text.substr(0, length - 1) + (text.length > length ? '...' : '');
     }
 
 };
 Vue.prototype.$dateTimeFormat = function (time = true) {
     let timeFormat = getTimeFormat();
     if (time === false) {
         return window.localStorage.getItem('dateformat');
     }
     return window.localStorage.getItem('dateformat') + " " + timeFormat;
 };
 Vue.prototype.$onlyNumber = function (evt) {
 
     evt = (evt) ? evt : window.event;
     let charCode = (evt.which) ? evt.which : evt.keyCode;
     if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
         if (charCode === 46 && decimalValues != 0) {
             return true;
         }
         evt.preventDefault();
     }
 
 };
 
 Vue.prototype.$onlyNumberAndLimit = function (evt, thisObj) {
 
     evt = (evt) ? evt : window.event;
     let charCode = (evt.which) ? evt.which : evt.keyCode;
     if (($(thisObj).val().length + 1) > 3) {
         evt.preventDefault();
     }
     if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
         if (charCode === 46 && decimalValues != 0) {
             return true;
         }
         evt.preventDefault();
     }
 
 };
 Vue.prototype.$convertTimezone = function (dateTime) {
     return moment.utc(String(dateTime)).tz(window.localStorage.getItem('timezone')).format('YYYY-MM-DD HH:mm:ss');
 };
 
 function amountFormated(amount) {
     if (amount == 0) {
         return amount;
     }
     let amt = Number(amount);
 
     if (decimalValues == 0) {
         return Math.round(amt);
     }
     return amt.toFixed(2);
 }
 
 function getTimeFormat() {
     switch (window.localStorage.getItem('timeformat')) {
         case "1":
             var format = 'hh:mm A';
             break;
         case "2":
             var format = 'HH:mm';
             break;
     }
     return format;
 }
 
 
 
 import NProgress from 'nprogress';
 let adminProgressBar = NProgress.configure({
     showSpinner: false
 });
 
 router.beforeResolve((to, from, next) => {
     // If this isn't an initial page load.
     if (to.name) {
         // Start the route progress bar.
         adminProgressBar.start()
     }
     if (to.meta.hasOwnProperty('permissions')) {
         if ((!window.Laravel.permissions.hasOwnProperty(to.meta.permissions) || window.Laravel.permissions[to.meta.permissions] == 0) && to.name != 'unAuthorised' && (window.Laravel.permissions.role_id != null || window.Laravel.permissions[to.meta.permissions] == 0)) {
             adminProgressBar.done();
             next({
                 name: 'unAuthorised'
             });
         } else {
             next()
         }
     } else {
         next()
     }
 })
 
 router.afterEach((to, from) => {
     // Complete the animation of the route progress bar.
     adminProgressBar.done()
 })
 
 Vue.http.interceptors.push(function (request) {
     // return response callback
     return function (response) {
         if (response.body.status === 401) {
             window.location.reload();
         }
     };
 });
 import * as VueGoogleMaps from 'vue2-google-maps'
 Vue.use(VueGoogleMaps, {
     load: {
         key: googleMapApiKey,
         libraries: 'places',
     },
     installComponents: true
 });
 const app = new Vue({
     router,
 }).$mount('#app')