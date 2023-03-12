"use strict";

var _inertiaVue = require("@inertiajs/inertia-vue");

var _vue = _interopRequireDefault(require("vue"));

var _vueMq = _interopRequireDefault(require("vue-mq"));

var _axios = _interopRequireDefault(require("axios"));

require("vue-toastr-2/dist/vue-toastr-2.min.css");

var _bootstrapVue = _interopRequireDefault(require("bootstrap-vue"));

var _vueTelInput = _interopRequireDefault(require("vue-tel-input"));

var _vue2PerfectScrollbar = _interopRequireDefault(require("vue2-perfect-scrollbar"));

var _moment = _interopRequireWildcard(require("moment"));

var _momentTimezone = _interopRequireDefault(require("moment-timezone"));

var _delete = _interopRequireDefault(require("./components/common/popups/delete.vue"));

require("bootstrap-vue/dist/bootstrap-vue.css");

var _vueSocialSharing = _interopRequireDefault(require("vue-social-sharing"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _getRequireWildcardCache() { if (typeof WeakMap !== "function") return null; var cache = new WeakMap(); _getRequireWildcardCache = function _getRequireWildcardCache() { return cache; }; return cache; }

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } if (obj === null || _typeof(obj) !== "object" && typeof obj !== "function") { return { "default": obj }; } var cache = _getRequireWildcardCache(); if (cache && cache.has(obj)) { return cache.get(obj); } var newObj = {}; var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor; for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) { var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null; if (desc && (desc.get || desc.set)) { Object.defineProperty(newObj, key, desc); } else { newObj[key] = obj[key]; } } } newObj["default"] = obj; if (cache) { cache.set(obj, newObj); } return newObj; }

__webpack_require__.p = baseUrl + '/dashboard/';

require('bootstrap');

_vue["default"].use(_inertiaVue.plugin);

_vue["default"].use(_moment["default"]);

_vue["default"].use(_bootstrapVue["default"]);

_vue["default"].use(_vueTelInput["default"]);

_vue["default"].use(VueToastr2);

_vue["default"].use(_vue2PerfectScrollbar["default"]);

_vue["default"].prototype.$axios = _axios["default"];
var el = document.getElementById('app');

_vue["default"].component('cropper', function () {
  return Promise.resolve().then(function () {
    return _interopRequireWildcard(require("./components/common/cropper/cropper.vue"));
  });
});

_vue["default"].component('DeleteModel', _delete["default"]);

toastr.options.closeOnHover = false;
toastr.options.closeButton = true;
toastr.options.closeDuration = 10;
toastr.options.preventDuplicates = true;

_vue["default"].use(_vueSocialSharing["default"], {
  networks: {
    digg: 'https://digg.com/share?url=@url&title=@title',
    wechat: 'https://wechat.com/share?url=@url&title=@title'
  }
});

_vue["default"].use(_vueMq["default"], {
  breakpoints: {
    mobile: 576,
    tablet: 768,
    laptop: 992,
    desktop: Infinity
  }
});

_vue["default"].prototype.$getTimeFormat = function () {
  var format = '';

  switch (this.$configVal('ADMIN_TIME_FORMAT')) {
    case "1":
      format = 'hh:mm A';
      break;

    case "2":
      format = 'HH:mm';
      break;
  }

  return format;
};

if (typeof window.config != 'undefined') {
  toastr.options.timeOut = window.config['SYSTEM_MESSAGE_STATUS'] == 1 && window.config['SYSTEM_MESSAGE_CLOSE_TIME'] != 0 ? window.config['SYSTEM_MESSAGE_CLOSE_TIME'] * 1000 : 0;
  toastr.options.positionClass = window.config['SYSTEM_MESSAGE_POSITION'];
}

_vue["default"].prototype.$productImage = function (productId, variantCode) {
  var timestamp = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
  var size = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 'small';

  if (typeof variantCode === 'undefined' || variantCode == "") {
    variantCode = 0;
  }

  var url = 'yokart/product/image/' + productId + '/' + variantCode + '/' + size;

  if (timestamp != '') {
    url = url + '?t=' + timestamp;
  }

  return baseUrl + '/' + url;
};

_vue["default"].prototype.$cleanString = function (strings) {
  return strings.replace(/[^A-Z0-9]/ig, "").toLowerCase();
};

_vue["default"].prototype.$t = function (strings) {
  if (window.i19n[pageDefaultLanguage] && window.i19n[pageDefaultLanguage][strings]) {
    return window.i19n[pageDefaultLanguage][strings];
  }

  return strings;
};

_vue["default"].prototype.$getFileUrl = function (section) {
  var recordId = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
  var subRecordId = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 0;
  var size = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 'original';
  var timestamp = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : '';
  var url = 'yokart/image/' + section + '/' + recordId + '/' + subRecordId + '/' + size;

  if (timestamp != '') {
    url = url + '?t=' + timestamp;
  }

  return url;
};

_vue["default"].prototype.$inArray = function (needle, haystack) {
  var length = haystack.length;

  for (var i = 0; i < length; i++) {
    if (haystack[i] == needle) return true;
  }

  return false;
};

_vue["default"].prototype.$serializeData = function (data) {
  var formData = new FormData();
  Object.keys(data).forEach(function (key) {
    formData.append(key, data[key]);
  });
  return formData;
};

_vue["default"].prototype.$numberFormat = function (num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
};

_vue["default"].prototype.$priceFormat = function (price) {
  var displaySymbol = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
  var format = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;
  currentCurrency = window.currentCurrency;
  price = price * currentCurrency.currency_value;

  if (format) {
    if (this.$configVal('DECIMAL_VALUES') == 0) {
      price = this.$numberFormat(parseFloat(price).toFixed(0));
    }

    price = this.$numberFormat(parseFloat(price).toFixed(2));
  }

  if (displaySymbol == true) {
    if (currentCurrency.currency_position) {
      price = price + ' ' + currentCurrency.currency_symbol;
    } else {
      price = currentCurrency.currency_symbol + '' + price;
    }
  }

  return price;
};

_vue["default"].prototype.$currencySymbol = function () {
  currentCurrency = window.currentCurrency;
  return currentCurrency.currency_symbol;
};

_vue["default"].prototype.$returnVerify = function (orderStatus, orderDate, product, returnTypes) {
  var addDays = (0, _moment["default"])(orderDate, "DD-MM-YYYY").add(product.op_return_age, 'days');

  if (orderStatus == 3) {
    return {
      'status': false,
      'message': 'This product is under shipped, return Not available'
    };
  } else if (orderStatus == 4 && (0, _moment["default"])(new Date()).isAfter(addDays) == false) {
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
    return {
      'status': false,
      'message': 'Your return status is ' + returnTypes[product.cancel_request.orrequest_status]
    };
  }

  return {
    'status': true
  };
};

_vue["default"].prototype.$dateTimeFormat = function (value) {
  var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'both';
  var timeFormat = this.$getTimeFormat();
  var format = '';

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

  var timeZone = window.auth.user_timezone ? window.auth.user_timezone : this.$configVal('ADMIN_TIMEZONE');
  return _moment["default"].utc(String(value)).tz(timeZone).format(format);
};

_vue["default"].prototype.$configVal = function (strings) {
  if (window.config && window.config[strings]) {
    return window.config[strings];
  }

  return strings;
};

_vue["default"].prototype.$formatSizeUnits = function (bytes) {
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
};

_vue["default"].prototype.$generateUrl = function (url) {
  if (url && url.urlrewrite_custom) {
    url = baseUrl + "/" + url.urlrewrite_custom;
  } else if (url && url.urlrewrite_original) {
    url = baseUrl + "/" + url.urlrewrite_original;
  }

  return url;
};

_vue["default"].prototype.$addToCart = function (productId, variantCode) {
  var redirect = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;
  var formData = this.$serializeData({
    prodId: productId,
    optionCode: variantCode
  });
  this.$axios.post(baseUrl + "/product/add-to-cart", formData).then(function (response) {
    if (response.data.status == false) {
      toastr.error(response.data.message, "", toastr.options);
      return false;
    }

    toastr.success(response.data.message, "", toastr.options);

    if (redirect == true) {
      window.open(baseUrl + '/cart', '_self');
    }
  });
};

_vue["default"].prototype.$setStringLength = function (string) {
  var stringLength = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 10;

  if (string.length > stringLength) {
    return string.substring(0, stringLength) + '...';
  }

  return string;
};

_vue["default"].prototype.$generateRandomString = function (len) {
  if (typeof len == 'undefined') {
    len = 10;
  }

  var text = "";
  var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz";

  for (var i = 0; i < len; i++) {
    text += chars.charAt(Math.floor(Math.random() * chars.length));
  }

  return text;
};

_vue["default"].filter('camelCase', function (str) {
  if (str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }
});

_vue["default"].prototype.$noImage = function (name) {
  return baseUrl + '/noimages/' + name;
};

new _vue["default"]({
  render: function render(h) {
    return h(_inertiaVue.App, {
      props: {
        initialPage: JSON.parse(el.dataset.page),
        resolveComponent: function resolveComponent(name) {
          return Promise.resolve().then(function () {
            return _interopRequireWildcard(require("./buyerDashboard/".concat(name)));
          }).then(function (module) {
            return module["default"];
          });
        }
      }
    });
  }
}).$mount(el);