__webpack_require__.p = baseUrl + '/dashboard/';
import {
    App,
    plugin
} from '@inertiajs/inertia-vue'
import Vue from 'vue'
import VueMq from 'vue-mq'
require('bootstrap');
Vue.use(plugin)
import axios from 'axios'
import 'vue-toastr-2/dist/vue-toastr-2.min.css';
import BootstrapVue from 'bootstrap-vue';
import VueTelInput from "vue-tel-input";
import PerfectScrollbar from 'vue2-perfect-scrollbar';
import InfiniteLoading from 'vue-infinite-loading';

import moment, {
    now
} from 'moment'
import moment_timezone from 'moment-timezone'
import DeleteModel from './common/popups/delete.vue';
Vue.use(moment);

Vue.use(BootstrapVue);
Vue.use(VueTelInput);
Vue.use(VueToastr2);
Vue.use(PerfectScrollbar);
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
import 'bootstrap-vue/dist/bootstrap-vue.css';
Vue.prototype.$axios = axios;
const el = document.getElementById('app')
Vue.component('cropper', () =>
    import("./common/cropper/cropper.vue"));
Vue.component('pagination', () =>
    import("./common/pagination.vue"));

Vue.component('DeleteModel', DeleteModel);
toastr.options.closeOnHover = false;
toastr.options.closeButton = true;
toastr.options.closeDuration = 10;
toastr.options.preventDuplicates = true;

import VueSocialSharing from 'vue-social-sharing'

Vue.use(VueSocialSharing, {
    networks: {
        digg: 'https://digg.com/share?url=@url&title=@title',
        wechat: 'https://wechat.com/share?url=@url&title=@title'
    }
});
Vue.use(VueMq, {
    breakpoints: {
        mobile: 576,
        tablet: 768,
        laptop: 992,
        desktop: Infinity,
    }
})


Vue.prototype.$getTimeFormat = function () {
    let format = '';
    switch (this.$configVal('ADMIN_TIME_FORMAT')) {
        case "1":
            format = 'hh:mm A';
            break;
        case "2":
            format = 'HH:mm';
            break;
    }
    return format;
}

if (typeof window.config != 'undefined') {
    toastr.options.timeOut = (window.config['SYSTEM_MESSAGE_STATUS'] == 1 && window.config['SYSTEM_MESSAGE_CLOSE_TIME'] != 0) ?
        window.config['SYSTEM_MESSAGE_CLOSE_TIME'] * 1000 : 0;
    toastr.options.positionClass = window.config['SYSTEM_MESSAGE_POSITION'];
}

Vue.prototype.$productImage = function (productId, variantCode, timestamp = '', size = 'small') {
    if (typeof variantCode === 'undefined' || variantCode == "") {
        variantCode = 0;
    }
    let url = 'yokart/product/image/' + productId + '/' + variantCode + '/' + size;
    if (timestamp != '') {
        timestamp = moment(timestamp).unix();
        url = url + '?t=' + timestamp;
    }
    return baseUrl + '/' + url;
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
Vue.prototype.$t = function (strings) {
    if (window.i19n[pageDefaultLanguage] && window.i19n[pageDefaultLanguage][strings]) {
        return window.i19n[pageDefaultLanguage][strings];
    }
    return strings;
}
Vue.prototype.$getFileUrl = function (section, recordId = 0, subRecordId = 0, size = 'original', timestamp = '') {
    let url = 'yokart/image/' + section + '/' + recordId + '/' + subRecordId + '/' + size;
    if (timestamp != '') {
        url = url + '?t=' + timestamp;
    }
    return url;
}
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
Vue.prototype.$inArray = function (needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
}
Vue.prototype.$serializeData = function (data) {
    let formData = new FormData();
    Object.keys(data).forEach(function (key) {
        formData.append(key, data[key]);
    });
    return formData;
}
Vue.prototype.$numberFormat = function (num) {

    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
};
Vue.prototype.$priceFormat = function (price, displaySymbol = true, format = true) {

    currentCurrency = window.currentCurrency;

    price = (price * currentCurrency.currency_value);

    if (format) {
        if (this.$configVal('DECIMAL_VALUES') == 0) {
            price = this.$numberFormat(parseFloat(price).toFixed(0));
        } else {
            price = this.$numberFormat(parseFloat(price).toFixed(2));

        }

    }

    if (displaySymbol == true) {
        if (currentCurrency.currency_position) {
            price = price + ' ' + currentCurrency.currency_symbol;

        } else {
            price = currentCurrency.currency_symbol + '' + price;

        }
    }
    return price;
}
Vue.prototype.$currencySymbol = function () {
    currentCurrency = window.currentCurrency;
    return currentCurrency.currency_symbol;
}
Vue.prototype.$returnVerify = function (orderStatus, orderDate, product, returnTypes) {

    let addDays = moment(new Date(orderDate)).add(product.op_return_age, 'days').format('YYYY-MM-DD');
    let expiredDate = moment.utc(addDays, 'YYYY-MM-DD[T]HH:mm[Z]');
    let currentDate = moment.utc(new Date(), 'YYYY-MM-DD[T]HH:mm[Z]');
    if (orderStatus == 3) {
        return {
            'status': false,
            'message': 'This product is under shipped, return Not available'
        };
    } else if (orderStatus == 4 && expiredDate.isAfter(currentDate) == false) {
        return {
            'status': false,
            'message': 'Return Not available'
        };
    } else if (this.$inArray(orderStatus, [1, 2]) == false && product.op_product_type == 2) {
        return {
            'status': false,
            'message': 'Return Not available, Due to digital order'
        };
    } else if (product.cancel_request) {
        let type = 'Cancelled';
        if(product.cancel_request.orrequest_type == 1){
            type = 'Returned';
        }
        return {
            'status': false,
            'message': type
        };
    }
    return {
        'status': true
    };
}

Vue.prototype.$dateTimeFormat = function (value, type = 'both') {

    let timeFormat = this.$getTimeFormat();
    let format = '';
    switch (type) {
        case "both":
            format = this.$configVal('ADMIN_DATE_FORMAT') + " " + timeFormat;
            if (typeof format === 'undefined') {
                format = 'DD MM, YYYY HH:mm';
            }
            break;
        case "date":
            format = this.$configVal('ADMIN_DATE_FORMAT');
            if (typeof format === 'undefined') {
                format = 'DD MM, YYYY';
            }
            break;
        case "time":
            format = timeFormat;
            if (typeof format === 'undefined') {
                format = 'HH:mm';
            }
            break;
    }

    let timeZone = window.auth.user_timezone ? window.auth.user_timezone : this.$configVal('ADMIN_TIMEZONE');
    return moment.utc(String(value)).tz(timeZone).format(format);

}
Vue.prototype.$configVal = function (strings) {
    if (window.config && window.config[strings]) {
        return window.config[strings];
    }
    return strings;
}
Vue.prototype.$formatSizeUnits = function (bytes) {
    if (bytes >= 1073741824) {
        bytes = this.$numberFormat((bytes / 1073741824).toFixed(2)) + ' GB';
    } else if (bytes >= 1048576) {
        bytes = this.$numberFormat((bytes / 1048576).toFixed(2)) + ' MB';
    } else if (bytes >= 1024) {
        bytes = this.$numberFormat((bytes / 1024).toFixed(2)) + ' KB';
    } else if (bytes > 1) {
        bytes = bytes + ' bytes';
    } else if (bytes == 1) {
        bytes = bytes + ' byte';
    } else {
        bytes = '0 bytes';
    }

    return bytes;
}

Vue.prototype.$generateUrl = function (url, subUrl = '') {
    let subid = ''
    if(subUrl){
        subid = '/'+ subUrl;
    }
    if (url && url.urlrewrite_custom) {
        url = baseUrl + "/" + url.urlrewrite_custom +''+subid;
    } else if (url && url.urlrewrite_original) {
        url = baseUrl + "/" + url.urlrewrite_original +''+subid;
    }
    return url;
}
Vue.prototype.$addToCart = function (productId, variantCode, redirect = true) {
    let formData = this.$serializeData({
        prodId: productId,
        optionCode: variantCode,
    });
    this.$axios
        .post(baseUrl + "/product/add-to-cart", formData)
        .then((response) => {
            if (response.data.status == false) {
                toastr.error(response.data.message, "", toastr.options);
                return false;
            }
            toastr.success(response.data.message, "", toastr.options);
            if (redirect == true) {
                window.open(baseUrl + '/cart', '_self');
            }

        });
}
Vue.filter('removeHyphen', function (fromString) {
    if (fromString) {
        return fromString.replace(/-/g, ' ');
    }
});
Vue.prototype.$setStringLength = function (string, stringLength = 10) {
    if (string.length > stringLength) {
        return string.substring(0, stringLength) + '...';
    }
    return string;
};
Vue.prototype.$generateRandomString = function (len) {
    if (typeof len == 'undefined') {
        len = 10;
    }
    let text = ""
    let chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz"
    for (let i = 0; i < len; i++) {
        text += chars.charAt(Math.floor(Math.random() * chars.length))
    }
    return text

};
Vue.filter('camelCase', function (str) {
    if (str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
});

Vue.prototype.$camelCase = function (str) {
    if (str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
};
Vue.prototype.$noImage = function (name) {
    return baseUrl + '/noimages/' + name;
}
Vue.prototype.$cleanShipStatus = function (fromString) {
    if (fromString) {
        let str = fromString.replace(/-/g, ' ');
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
};


new Vue({
    render: h => h(App, {
        props: {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: name =>
                import(`./buyerDashboard/${name}`).then(module => module.default),
        },
    }),
}).$mount(el)