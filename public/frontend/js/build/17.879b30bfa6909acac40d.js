(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[17],{

/***/ "./node_modules/css-loader/index.js?!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-range-component/dist/vue-range-slider.css":
/*!****************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--5-1!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-range-component/dist/vue-range-slider.css ***!
  \****************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".vue-range-slider.slider-component {\n  position: relative;\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n  -webkit-user-select: none;\n     -moz-user-select: none;\n      -ms-user-select: none;\n          user-select: none;\n}\n.vue-range-slider.slider-component .slider {\n  position: relative;\n  display: block;\n  border-radius: 15px;\n  background-color: #ccc;\n}\n.vue-range-slider.slider-component .slider::after {\n  content: '';\n  position: absolute;\n  left: 0;\n  top: 0;\n  width: 100%;\n  height: 100%;\n  z-index: 2;\n}\n.vue-range-slider.slider-component .slider .slider-dot {\n  position: absolute;\n  border-radius: 50%;\n  background-color: #fff;\n  -webkit-box-shadow: 0.5px 0.5px 2px 1px rgba(0,0,0,0.32);\n          box-shadow: 0.5px 0.5px 2px 1px rgba(0,0,0,0.32);\n  -webkit-transition: all 0s;\n  transition: all 0s;\n  will-change: transform;\n  cursor: pointer;\n  z-index: 5;\n}\n.vue-range-slider.slider-component .slider .slider-dot.slider-dot-focus {\n  -webkit-box-shadow: 0 0 2px 1px #3498db;\n          box-shadow: 0 0 2px 1px #3498db;\n}\n.vue-range-slider.slider-component .slider .slider-dot.slider-dot-dragging {\n  z-index: 5;\n}\n.vue-range-slider.slider-component .slider .slider-dot.slider-dot-disabled {\n  z-index: 4;\n}\n.vue-range-slider.slider-component .slider .slider-dot.slider-hover:hover .slider-tooltip-wrap {\n  display: block;\n}\n.vue-range-slider.slider-component .slider .slider-dot.slider-always .slider-tooltip-wrap {\n  display: block !important;\n}\n.vue-range-slider.slider-component .slider .slider-process {\n  position: absolute;\n  border-radius: 15px;\n  background-color: #3498db;\n  z-index: 1;\n}\n.vue-range-slider.slider-component .slider .slider-process.slider-process-draggable {\n  cursor: pointer;\n  z-index: 3;\n}\n.vue-range-slider.slider-component .slider .slider-input {\n  position: absolute;\n  overflow: hidden;\n  height: 1px;\n  width: 1px;\n  clip: rect(1px, 1px, 1px, 1px);\n}\n.vue-range-slider.slider-component .slider .slider-piecewise {\n  position: absolute;\n  width: 100%;\n  padding: 0;\n  margin: 0;\n  left: 0;\n  top: 0;\n  height: 100%;\n  list-style: none;\n}\n.vue-range-slider.slider-component .slider .slider-piecewise .piecewise-item {\n  position: absolute;\n  width: 8px;\n  height: 8px;\n}\n.vue-range-slider.slider-component .slider .slider-piecewise .piecewise-item:first-child .piecewise-dot,\n.vue-range-slider.slider-component .slider .slider-piecewise .piecewise-item:last-child .piecewise-dot {\n  visibility: hidden;\n}\n.vue-range-slider.slider-component .slider .slider-piecewise .piecewise-item .piecewise-dot {\n  position: absolute;\n  left: 50%;\n  top: 50%;\n  width: 100%;\n  height: 100%;\n  display: inline-block;\n  background-color: rgba(0,0,0,0.16);\n  border-radius: 50%;\n  -webkit-transform: translate(-50%, -50%);\n          transform: translate(-50%, -50%);\n  z-index: 2;\n  -webkit-transition: all 0.3s;\n  transition: all 0.3s;\n}\n.vue-range-slider.slider-component.slider-horizontal .slider-dot {\n  left: 0;\n}\n.vue-range-slider.slider-component.slider-horizontal .slider-process {\n  width: 0;\n  height: 100%;\n  top: 0;\n  left: 0;\n  will-change: width;\n}\n.vue-range-slider.slider-component.slider-vertical .slider-dot {\n  bottom: 0;\n}\n.vue-range-slider.slider-component.slider-vertical .slider-process {\n  width: 100%;\n  height: 0;\n  bottom: 0;\n  left: 0;\n  will-change: height;\n}\n.vue-range-slider.slider-component.slider-horizontal-reverse .slider-dot {\n  right: 0;\n}\n.vue-range-slider.slider-component.slider-horizontal-reverse .slider-process {\n  width: 0;\n  height: 100%;\n  top: 0;\n  right: 0;\n}\n.vue-range-slider.slider-component.slider-vertical-reverse .slider-dot {\n  top: 0;\n}\n.vue-range-slider.slider-component.slider-vertical-reverse .slider-process {\n  width: 100%;\n  height: 0;\n  top: 0;\n  left: 0;\n}\n.vue-range-slider.slider-component.slider-horizontal .slider-piecewise .piecewise-item .piecewise-label,\n.vue-range-slider.slider-component.slider-horizontal-reverse .slider-piecewise .piecewise-item .piecewise-label {\n  position: absolute;\n  display: inline-block;\n  top: 100%;\n  left: 50%;\n  white-space: nowrap;\n  font-size: 12px;\n  color: #333;\n  -webkit-transform: translate(-50%, 8px);\n          transform: translate(-50%, 8px);\n  visibility: visible;\n}\n.vue-range-slider.slider-component.slider-vertical .slider-piecewise .piecewise-item .piecewise-label,\n.vue-range-slider.slider-component.slider-vertical-reverse .slider-piecewise .piecewise-item .piecewise-label {\n  position: absolute;\n  display: inline-block;\n  top: 50%;\n  left: 100%;\n  white-space: nowrap;\n  font-size: 12px;\n  color: #333;\n  -webkit-transform: translate(8px, -50%);\n          transform: translate(8px, -50%);\n  visibility: visible;\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap {\n  display: none;\n  position: absolute;\n  z-index: 9;\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.merged-tooltip {\n  display: block;\n  visibility: hidden;\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-top {\n  top: -9px;\n  left: 50%;\n  -webkit-transform: translate(-50%, -100%);\n          transform: translate(-50%, -100%);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-top .slider-tooltip::before {\n  content: '';\n  position: absolute;\n  bottom: -10px;\n  left: 50%;\n  width: 0;\n  height: 0;\n  border: 5px solid transparent;\n  border-top-color: inherit;\n  -webkit-transform: translate(-50%, 0);\n          transform: translate(-50%, 0);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-bottom {\n  bottom: -9px;\n  left: 50%;\n  -webkit-transform: translate(-50%, 100%);\n          transform: translate(-50%, 100%);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-bottom .slider-tooltip::before {\n  content: '';\n  position: absolute;\n  top: -10px;\n  left: 50%;\n  width: 0;\n  height: 0;\n  border: 5px solid transparent;\n  border-bottom-color: inherit;\n  -webkit-transform: translate(-50%, 0);\n          transform: translate(-50%, 0);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-left {\n  top: 50%;\n  left: -9px;\n  -webkit-transform: translate(-100%, -50%);\n          transform: translate(-100%, -50%);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-left .slider-tooltip::before {\n  content: '';\n  position: absolute;\n  top: 50%;\n  right: -10px;\n  width: 0;\n  height: 0;\n  border: 5px solid transparent;\n  border-left-color: inherit;\n  -webkit-transform: translate(0, -50%);\n          transform: translate(0, -50%);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-right {\n  top: 50%;\n  right: -9px;\n  -webkit-transform: translate(100%, -50%);\n          transform: translate(100%, -50%);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-right .slider-tooltip::before {\n  content: '';\n  position: absolute;\n  top: 50%;\n  left: -10px;\n  width: 0;\n  height: 0;\n  border: 5px solid transparent;\n  border-right-color: inherit;\n  -webkit-transform: translate(0, -50%);\n          transform: translate(0, -50%);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.merged-tooltip {\n  display: block;\n  visibility: hidden;\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap .slider-tooltip {\n  display: block;\n  font-size: 14px;\n  white-space: nowrap;\n  padding: 2px 5px;\n  min-width: 20px;\n  text-align: center;\n  color: #fff;\n  border-radius: 5px;\n  border: 1px solid #3498db;\n  background-color: #3498db;\n}\n.vue-range-slider.slider-component.slider-disabled {\n  opacity: 0.5;\n  cursor: not-allowed;\n}\n.vue-range-slider.slider-component.slider-disabled .slider-dot {\n  cursor: not-allowed;\n}\n.vue-range-slider.slider-component.slider-has-label {\n  margin-bottom: 15px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/vue-range-component/dist/vue-range-slider.css":
/*!********************************************************************!*\
  !*** ./node_modules/vue-range-component/dist/vue-range-slider.css ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../css-loader??ref--5-1!../../postcss-loader/src??ref--5-2!./vue-range-slider.css */ "./node_modules/css-loader/index.js?!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-range-component/dist/vue-range-slider.css");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-range-component/dist/vue-range-slider.esm.js":
/*!***********************************************************************!*\
  !*** ./node_modules/vue-range-component/dist/vue-range-slider.esm.js ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/*!
 * vue-range-slider v1.0.3
 * (c) 2016-2018 xwpongithub
 * Released under the MIT License.
 */
// Unsharp text [#166](https://github.com/NightCatSama/vue-slider-component/issues/166)
var roundToDPR = function () {
  var r = typeof window !== 'undefined' ? window.devicePixelRatio || 1 : 1;
  return function (value) {
    return Math.round(value * r) / r;
  };
}();
var isMobile = function () {
  var userAgentInfo = navigator.userAgent.toLowerCase();
  var agents = ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"];
  var flag = false;

  for (var v = 0; v < agents.length; v++) {
    if (userAgentInfo.indexOf(agents[v].toLowerCase()) > 0) {
      flag = true;
      break;
    }
  }

  return flag;
}();
function isArray(input) {
  if (Array.prototype.isArray) {
    return Array.isArray(input);
  }

  return input instanceof Array || Object.prototype.toString.call(input) === '[object Array]';
}
function isDiff(a, b) {
  if (Object.prototype.toString.call(a) !== Object.prototype.toString.call(b)) {
    return true;
  } else if (isArray(a) && a.length === b.length) {
    return a.some(function (v, i) {
      return v !== b[i];
    });
  }

  return a !== b;
}
var elementStyle = document.createElement('div').style;

var vendor = function () {
  var transformNames = {
    webkit: 'webkitTransform',
    Moz: 'MozTransform',
    O: 'OTransform',
    ms: 'msTransform',
    standard: 'transform'
  };

  for (var key in transformNames) {
    if (elementStyle[transformNames[key]] !== undefined) {
      return key;
    }
  }

  return false;
}();

function prefixStyle(style) {
  if (vendor === false) {
    return false;
  }

  if (vendor === 'standard') {
    if (style === 'transitionEnd') {
      return 'transitionend';
    }

    return style;
  }

  return vendor + style.charAt(0).toUpperCase() + style.substr(1);
}
function addEvent(el, type, fn, capture) {
  el.addEventListener(type, fn, {
    passive: false,
    capture: !!capture
  });
}
function removeEvent(el, type, fn, capture) {
  el.removeEventListener(type, fn, {
    passive: false,
    capture: !!capture
  });
}

var transform = prefixStyle('transform');
var transitionDuration = prefixStyle('transitionDuration');
var transitionEnd = prefixStyle('transitionEnd');
var EVENT_TOUCH_START = 'touchstart';
var EVENT_TOUCH_MOVE = 'touchmove';
var EVENT_TOUCH_END = 'touchend';
var EVENT_TOUCH_CANCEL = 'touchcancel';
var EVENT_MOUSE_DOWN = 'mousedown';
var EVENT_MOUSE_MOVE = 'mousemove';
var EVENT_MOUSE_UP = 'mouseup';
var EVENT_MOUSE_LEAVE = 'mouseleave';
var EVENT_KEY_DOWN = 'keydown';
var EVENT_KEY_UP = 'keyup';
var EVENT_RESIZE = 'resize';
var Slider = {
  name: 'vue-range-slider',
  props: {
    // ??????????????????
    show: {
      type: Boolean,
      default: true
    },
    // ???
    value: {
      type: [String, Number, Array, Object],
      default: 0
    },
    // ?????????
    min: {
      type: Number,
      default: 0
    },
    // ?????????
    max: {
      type: Number,
      default: 100
    },
    // ????????????
    step: {
      type: Number,
      default: 1
    },
    // ????????????
    width: {
      type: [Number, String],
      default: 'auto'
    },
    // ????????????
    height: {
      type: [Number, String],
      default: 6
    },
    // ????????????
    dotSize: {
      type: Number,
      default: 16
    },
    dotWidth: {
      type: Number,
      required: false
    },
    dotHeight: {
      type: Number,
      required: false
    },
    stopPropagation: {
      type: Boolean,
      default: false
    },
    // ????????????
    eventType: {
      type: String,
      default: 'auto'
    },
    // ??????????????????????????????
    realTime: {
      type: Boolean,
      default: false
    },
    // ????????????????????????
    tooltip: {
      type: [String, Boolean],
      default: 'always',
      validator: function validator(val) {
        return ['hover', 'always'].indexOf(val) > -1;
      }
    },
    // ????????????
    direction: {
      type: String,
      default: 'horizontal',
      validator: function validator(val) {
        return ['horizontal', 'vertical'].indexOf(val) > -1;
      }
    },
    // ??????????????????
    reverse: {
      type: Boolean,
      default: false
    },
    // ???????????????
    disabled: {
      type: [Boolean, Array],
      default: false
    },
    piecewiseLabel: {
      type: Boolean,
      default: false
    },
    piecewise: {
      type: Boolean,
      default: false
    },
    // ????????????????????????????????????????????????
    processDraggable: {
      type: Boolean,
      default: false
    },
    // ??????????????????
    clickable: {
      type: Boolean,
      default: true
    },
    // ??????????????????
    fixed: {
      type: Boolean,
      default: false
    },
    // ???????????????????????????????????????
    debug: {
      type: Boolean,
      default: true
    },
    // ????????????
    minRange: {
      type: Number
    },
    // ????????????
    maxRange: {
      type: Number
    },
    tooltipMerge: {
      type: Boolean,
      default: true
    },
    // ????????????????????????
    startAnimation: {
      type: Boolean,
      default: false
    },
    // ?????????????????????????????????
    lazy: {
      type: Boolean,
      default: false
    },
    // ???????????????????????????????????????
    enableCross: {
      type: Boolean,
      default: true
    },
    // ???????????????
    speed: {
      type: Number,
      default: 0.5
    },
    useKeyboard: {
      type: Boolean,
      default: false
    },
    actionsKeyboard: {
      type: Array,
      default: function _default() {
        return [function (i) {
          return i - 1;
        }, function (i) {
          return i + 1;
        }];
      }
    },
    data: Array,
    formatter: [String, Function],
    mergeFormatter: [String, Function],
    // ??????????????????
    tooltipDir: [Array, String],
    // ??????????????????
    tooltipStyle: [Array, Object, Function],
    // ????????????
    sliderStyle: [Array, Object, Function],
    // ????????????????????????????????????????????????
    focusStyle: [Array, Object, Function],
    // ???????????????????????????
    disabledStyle: Object,
    // ???????????????
    processStyle: Object,
    // ??????????????????
    bgStyle: Object,
    piecewiseStyle: Object,
    piecewiseActiveStyle: Object,
    disabledDotStyle: [Array, Object, Function],
    labelStyle: Object,
    labelActiveStyle: Object
  },
  data: function data() {
    return {
      currentValue: 0,
      size: 0,
      fixedValue: 0,
      focusSlider: 0,
      currentSlider: 0,
      flag: false,
      processFlag: false,
      processSign: false,
      keydownFlag: false,
      focusFlag: false,
      dragFlag: false,
      crossFlag: false,
      isComponentExists: true,
      isMounted: false
    };
  },
  render: function render(h) {
    var _this = this;

    var sliderConBlocks = []; // dot

    if (this.isRange) {
      var dot0 = h('div', {
        ref: 'dot0',
        staticClass: 'slider-dot',
        class: [this.tooltipStatus, {
          'slider-dot-focus': this.focusFlag && this.focusSlider === 0,
          'slider-dot-dragging': this.flag && this.currentSlider === 0,
          'slider-dot-disabled': !this.boolDisabled && this.disabledArray[0]
        }],
        style: [this.dotStyles, !this.boolDisabled && this.disabledArray[0] ? this.disabledDotStyles[0] : null, this.sliderStyles[0], this.focusFlag && this.focusSlider === 0 ? this.focusStyles[0] : null]
      }, [h('div', {
        ref: 'tooltip0',
        staticClass: 'slider-tooltip-wrap',
        class: "slider-tooltip-".concat(this.tooltipDirection[0])
      }, [this._t('tooltip', [h('span', {
        staticClass: 'slider-tooltip',
        style: this.tooltipStyles[0]
      }, this.formatter ? this.formatting(this.val[0]) : this.val[0])], {
        value: this.val[0],
        index: 0,
        disabled: !this.boolDisabled && this.disabledArray[0]
      })])]);
      sliderConBlocks.push(dot0);
      var dot1 = h('div', {
        ref: 'dot1',
        staticClass: 'slider-dot',
        class: [this.tooltipStatus, {
          'slider-dot-focus': this.focusFlag && this.focusSlider === 1,
          'slider-dot-dragging': this.flag && this.currentSlider === 1,
          'slider-dot-disabled': !this.boolDisabled && this.disabledArray[1]
        }],
        style: [this.dotStyles, !this.boolDisabled && this.disabledArray[1] ? this.disabledDotStyles[1] : null, this.sliderStyles[1], this.focusFlag && this.focusSlider === 1 ? this.focusStyles[1] : null]
      }, [h('div', {
        ref: 'tooltip1',
        staticClass: 'slider-tooltip-wrap',
        class: "slider-tooltip-".concat(this.tooltipDirection[1])
      }, [this._t('tooltip', [h('span', {
        staticClass: 'slider-tooltip',
        style: this.tooltipStyles[1]
      }, this.formatter ? this.formatting(this.val[1]) : this.val[1])], {
        value: this.val[1],
        index: 1,
        disabled: !this.boolDisabled && this.disabledArray[1]
      })])]);
      sliderConBlocks.push(dot1);
    } else {
      var dot = h('div', {
        ref: 'dot',
        staticClass: 'slider-dot',
        class: [this.tooltipStatus, {
          'slider-dot-focus': this.focusFlag && this.focusSlider === 0,
          'slider-dot-dragging': this.flag && this.currentSlider === 0
        }],
        style: [this.dotStyles, this.sliderStyles, this.focusFlag && this.focusSlider === 0 ? this.focusStyles : null]
      }, [h('div', {
        staticClass: 'slider-tooltip-wrap',
        class: "slider-tooltip-".concat(this.tooltipDirection)
      }, [this._t('tooltip', [h('span', {
        staticClass: 'slider-tooltip',
        style: this.tooltipStyles
      }, this.formatter ? this.formatting(this.val) : this.val)], {
        value: this.val
      })])]);
      sliderConBlocks.push(dot);
    } // piecewise


    var dotWrapLen = this.piecewiseDotWrap.length;
    var ulBlock = h('ul', {
      staticClass: 'slider-piecewise'
    }, this._l(this.piecewiseDotWrap, function (item, i) {
      var piecewiseDot = [];

      if (_this.piecewise) {
        piecewiseDot.push(h('span', {
          staticClass: 'piecewise-dot',
          style: [_this.piecewiseStyle, item.inRange ? _this.piecewiseActiveStyle : null]
        }));
      }

      var piecewiseLabel = [];

      if (_this.piecewiseLabel) {
        piecewiseLabel.push(h('span', {
          staticClass: 'piecewise-label',
          style: [_this.labelStyle, item.inRange ? _this.labelActiveStyle : null]
        }, item.label));
      }

      return h('li', {
        key: i,
        staticClass: 'piecewise-item',
        style: [_this.piecewiseDotStyle, item.style]
      }, [_this._t('piecewise', piecewiseDot, {
        label: item.label,
        index: i,
        first: i === 0,
        last: i === dotWrapLen - 1,
        active: item.inRange
      }), _this._t('label', piecewiseLabel, {
        label: item.label,
        index: i,
        first: i === 0,
        last: i === dotWrapLen - 1,
        active: item.inRange
      })]);
    }));
    sliderConBlocks.push(ulBlock); // process

    var processBlock = h('div', {
      ref: 'process',
      staticClass: 'slider-process',
      class: {
        'slider-process-draggable': this.isRange && this.processDraggable
      },
      style: this.processStyle,
      on: {
        click: function click(e) {
          return _this.processClick(e);
        }
      }
    }, [h('div', {
      ref: 'mergedTooltip',
      staticClass: 'merged-tooltip slider-tooltip-wrap',
      class: "slider-tooltip-".concat(this.isRange ? this.tooltipDirection[0] : this.tooltipDirection),
      style: this.tooltipMergedPosition
    }, [this._t('tooltip', [h('span', {
      staticClass: 'slider-tooltip',
      style: this.tooltipStyles
    }, this.mergeFormatter ? this.mergeFormatting(this.val[0], this.val[1]) : this.formatter ? this.val[0] === this.val[1] ? this.formatting(this.val[0]) : "".concat(this.formatting(this.val[0]), " - ").concat(this.formatting(this.val[1])) : this.val[0] === this.val[1] ? this.val[0] : "".concat(this.val[0], " - ").concat(this.val[1]))], {
      value: this.val,
      merge: true
    })])]);
    sliderConBlocks.push(processBlock); // <input type="range">

    if (!this.isRange && !this.data) {
      sliderConBlocks.push(h('input', {
        staticClass: 'slider-input',
        attrs: {
          type: 'range',
          min: this.min,
          max: this.max
        },
        domProps: {
          value: this.val
        },
        on: {
          input: function input(e) {
            return _this.val = e.target.value;
          }
        }
      }));
    }

    return h('div', {
      ref: 'wrap',
      staticClass: 'vue-range-slider slider-component',
      class: [this.flowDirection, this.disabledClass, this.stateClass, {
        'slider-has-label': this.piecewiseLabel
      }],
      style: [this.wrapStyles, this.boolDisabled ? this.disabledStyle : null],
      directives: [{
        name: 'show',
        value: this.show
      }],
      on: {
        click: function click(e) {
          return _this.wrapClick(e);
        }
      }
    }, [h('div', {
      ref: 'elem',
      staticClass: 'slider',
      style: [this.elemStyles, this.bgStyle],
      attrs: {
        'aria-hidden': true
      }
    }, sliderConBlocks)]);
  },
  computed: {
    val: {
      get: function get() {
        return this.data ? this.isRange ? [this.data[this.currentValue[0]], this.data[this.currentValue[1]]] : this.data[this.currentValue] : this.currentValue;
      },
      set: function set(val) {
        if (this.data) {
          if (this.isRange) {
            var index0 = this.data.indexOf(val[0]);
            var index1 = this.data.indexOf(val[1]);

            if (index0 > -1 && index1 > -1) {
              this.currentValue = [index0, index1];
            }
          } else {
            var index = this.data.indexOf(val);

            if (index > -1) {
              this.currentValue = index;
            }
          }
        } else {
          this.currentValue = val;
        }
      }
    },
    currentIndex: function currentIndex() {
      if (this.isRange) {
        return this.data ? this.currentValue : [this.getIndexByValue(this.currentValue[0]), this.getIndexByValue(this.currentValue[1])];
      } else {
        return this.getIndexByValue(this.currentValue);
      }
    },
    tooltipMergedPosition: function tooltipMergedPosition() {
      if (!this.isMounted) return {};
      var tooltipDirection = this.tooltipDirection[0];
      var dot0 = this.$refs.dot0;

      if (dot0) {
        var style = {};

        if (this.direction === 'vertical') {
          style[tooltipDirection] = "-".concat(this.dotHeightVal / 2 - this.width / 2 + 9, "px");
        } else {
          style[tooltipDirection] = "-".concat(this.dotWidthVal / 2 - this.height / 2 + 9, "px");
          style['left'] = "50%";
        }

        return style;
      }
    },
    tooltipDirection: function tooltipDirection() {
      var dir = this.tooltipDir || (this.direction === 'vertical' ? 'left' : 'top');

      if (isArray(dir)) {
        return this.isRange ? dir : dir[1];
      } else {
        return this.isRange ? [dir, dir] : dir;
      }
    },
    piecewiseDotWrap: function piecewiseDotWrap() {
      if (!this.piecewise && !this.piecewiseLabel) {
        return false;
      }

      var arr = [];

      for (var i = 0; i <= this.total; i++) {
        var style = this.direction === 'vertical' ? {
          bottom: "".concat(this.gap * i - this.width / 2, "px"),
          left: 0
        } : {
          left: "".concat(this.gap * i - this.height / 2, "px"),
          top: 0
        };
        var index = this.reverse ? this.total - i : i;
        var label = this.data ? this.data[index] : this.spacing * index + this.min;
        arr.push({
          style: style,
          label: this.formatter ? this.formatting(label) : label,
          inRange: index >= this.indexRange[0] && index <= this.indexRange[1]
        });
      }

      return arr;
    },
    total: function total() {
      if (this.data) {
        return this.data.length - 1;
      } else if (Math.floor((this.maximum - this.minimum) * this.multiple) % (this.step * this.multiple) !== 0) {
        this.printError('Prop[step] is illegal, Please make sure that the step can be divisible');
      }

      return (this.maximum - this.minimum) / this.step;
    },
    piecewiseDotStyle: function piecewiseDotStyle() {
      return this.direction === 'vertical' ? {
        width: "".concat(this.width, "px"),
        height: "".concat(this.width, "px")
      } : {
        width: "".concat(this.height, "px"),
        height: "".concat(this.height, "px")
      };
    },
    dotStyles: function dotStyles() {
      return this.direction === 'vertical' ? {
        width: "".concat(this.dotWidthVal, "px"),
        height: "".concat(this.dotHeightVal, "px"),
        left: "".concat(-(this.dotWidthVal - this.width) / 2, "px")
      } : {
        width: "".concat(this.dotWidthVal, "px"),
        height: "".concat(this.dotHeightVal, "px"),
        top: "".concat(-(this.dotHeightVal - this.height) / 2, "px")
      };
    },
    sliderStyles: function sliderStyles() {
      if (isArray(this.sliderStyle)) {
        return this.isRange ? this.sliderStyle : this.sliderStyle[1];
      } else if (typeof this.sliderStyle === 'function') {
        return this.sliderStyle(this.val, this.currentIndex);
      } else {
        return this.isRange ? [this.sliderStyle, this.sliderStyle] : this.sliderStyle;
      }
    },
    tooltipStyles: function tooltipStyles() {
      if (isArray(this.tooltipStyle)) {
        return this.isRange ? this.tooltipStyle : this.tooltipStyle[1];
      } else if (typeof this.tooltipStyle === 'function') {
        return this.tooltipStyle(this.val, this.currentIndex);
      } else {
        return this.isRange ? [this.tooltipStyle, this.tooltipStyle] : this.tooltipStyle;
      }
    },
    focusStyles: function focusStyles() {
      if (isArray(this.focusStyle)) {
        return this.isRange ? this.focusStyle : this.focusStyle[1];
      } else if (typeof this.focusStyle === 'function') {
        return this.focusStyle(this.val, this.currentIndex);
      } else {
        return this.isRange ? [this.focusStyle, this.focusStyle] : this.focusStyle;
      }
    },
    elemStyles: function elemStyles() {
      return this.direction === 'vertical' ? {
        width: "".concat(this.width, "px"),
        height: '100%'
      } : {
        height: "".concat(this.height, "px")
      };
    },
    wrapStyles: function wrapStyles() {
      return this.direction === 'vertical' ? {
        height: typeof this.height === 'number' ? "".concat(this.height, "px") : this.height,
        padding: "".concat(this.dotHeightVal / 2, "px ").concat(this.dotWidthVal / 2, "px")
      } : {
        width: typeof this.width === 'number' ? "".concat(this.width, "px") : this.width,
        padding: "".concat(this.dotHeightVal / 2, "px ").concat(this.dotWidthVal / 2, "px")
      };
    },
    stateClass: function stateClass() {
      return {
        'slider-state-process-drag': this.processFlag,
        'slider-state-drag': this.flag && !this.processFlag && !this.keydownFlag,
        'slider-state-focus': this.focusFlag
      };
    },
    tooltipStatus: function tooltipStatus() {
      return this.tooltip === 'hover' && this.flag ? 'slider-always' : this.tooltip ? "slider-".concat(this.tooltip) : '';
    },
    tooltipClass: function tooltipClass() {
      return ["slider-tooltip-".concat(this.tooltipDirection), 'slider-tooltip'];
    },
    minimum: function minimum() {
      return this.data ? 0 : this.min;
    },
    maximum: function maximum() {
      return this.data ? this.data.length - 1 : this.max;
    },
    multiple: function multiple() {
      var decimals = "".concat(this.step).split('.')[1];
      return decimals ? Math.pow(10, decimals.length) : 1;
    },
    indexRange: function indexRange() {
      return this.isRange ? this.currentIndex : [0, this.currentIndex];
    },
    spacing: function spacing() {
      return this.data ? 1 : this.step;
    },
    gap: function gap() {
      return this.size / this.total;
    },
    dotWidthVal: function dotWidthVal() {
      return typeof this.dotWidth === 'number' ? this.dotWidth : this.dotSize;
    },
    dotHeightVal: function dotHeightVal() {
      return typeof this.dotHeight === 'number' ? this.dotHeight : this.dotSize;
    },
    disabledArray: function disabledArray() {
      return isArray(this.disabled) ? this.disabled : [this.disabled, this.disabled];
    },
    boolDisabled: function boolDisabled() {
      return this.disabledArray.every(function (b) {
        return b === true;
      });
    },
    disabledClass: function disabledClass() {
      return this.boolDisabled ? 'slider-disabled' : '';
    },
    flowDirection: function flowDirection() {
      return "slider-".concat(this.direction + (this.reverse ? '-reverse' : ''));
    },
    isRange: function isRange() {
      return isArray(this.value);
    },
    isDisabled: function isDisabled() {
      return this.eventType === 'none' ? true : this.boolDisabled;
    },
    isFixed: function isFixed() {
      return this.fixed || this.minRange;
    },
    position: function position() {
      return this.isRange ? [(this.currentValue[0] - this.minimum) / this.spacing * this.gap, (this.currentValue[1] - this.minimum) / this.spacing * this.gap] : (this.currentValue - this.minimum) / this.spacing * this.gap;
    },
    limit: function limit() {
      return this.isRange ? this.isFixed ? [[0, (this.total - this.fixedValue) * this.gap], [this.fixedValue * this.gap, this.size]] : [[0, this.position[1]], [this.position[0], this.size]] : [0, this.size];
    },
    valueLimit: function valueLimit() {
      return this.isRange ? this.isFixed ? [[this.minimum, this.maximum - this.fixedValue * (this.spacing * this.multiple) / this.multiple], [this.minimum + this.fixedValue * (this.spacing * this.multiple) / this.multiple, this.maximum]] : [[this.minimum, this.currentValue[1]], [this.currentValue[0], this.maximum]] : [this.minimum, this.maximum];
    },
    idleSlider: function idleSlider() {
      return this.currentSlider === 0 ? 1 : 0;
    },
    slider: function slider() {
      return this.isRange ? [this.$refs.dot0, this.$refs.dot1] : this.$refs.dot;
    }
  },
  methods: {
    setValue: function setValue(val, noCb, speed) {
      var _this2 = this;

      if (isDiff(this.val, val)) {
        var resetVal = this.limitValue(val);
        this.val = this.isRange ? resetVal.concat() : resetVal;
        this.computedFixedValue();
        this.syncValue(noCb);
      }

      this.$nextTick(function () {
        return _this2.setPosition(speed);
      });
    },
    setIndex: function setIndex(val) {
      if (isArray(val) && this.isRange) {
        var value;

        if (this.data) {
          value = [this.data[val[0]], this.data[val[1]]];
        } else {
          value = [this.getValueByIndex(val[0]), this.getValueByIndex(val[1])];
        }

        this.setValue(value);
      } else {
        val = this.getValueByIndex(val);

        if (this.isRange) {
          this.currentSlider = val > (this.currentValue[1] - this.currentValue[0]) / 2 + this.currentValue[0] ? 1 : 0;
        }

        this.setCurrentValue(val);
      }
    },
    wrapClick: function wrapClick(e) {
      if (this.isDisabled || !this.clickable || this.processFlag || this.dragFlag) return false;
      var pos = this.getPos(e);

      if (this.isRange) {
        if (this.disabledArray.every(function (b) {
          return b === false;
        })) {
          this.currentSlider = pos > (this.position[1] - this.position[0]) / 2 + this.position[0] ? 1 : 0;
        } else if (this.disabledArray[0]) {
          if (pos < this.position[0]) return false;
          this.currentSlider = 1;
        } else if (this.disabledArray[1]) {
          if (pos > this.position[1]) return false;
          this.currentSlider = 0;
        }
      }

      if (this.disabledArray[this.currentSlider]) {
        return false;
      }

      this.setValueOnPos(pos);

      if (this.isRange && this.tooltipMerge) {
        var timer = setInterval(this.handleOverlapTooltip, 16.7);
        setTimeout(function () {
          return window.clearInterval(timer);
        }, this.speed * 1000);
      }
    },
    processClick: function processClick(e) {
      if (this.fixed) e.stopPropagation();
    },
    syncValue: function syncValue(noCb) {
      var val = this.isRange ? this.val.concat() : this.val;
      this.$emit('input', val);
      this.keydownFlag && this.$emit('on-keypress', val);
      noCb || this.$emit('slide-end', val);
    },
    getPos: function getPos(e) {
      this.realTime && this.getStaticData();
      return this.direction === 'vertical' ? this.reverse ? e.pageY - this.offset : this.size - (e.pageY - this.offset) : this.reverse ? this.size - (e.pageX - this.offset) : e.pageX - this.offset;
    },
    setValueOnPos: function setValueOnPos(pos, isDrag) {
      var range = this.isRange ? this.limit[this.currentSlider] : this.limit;
      var valueRange = this.isRange ? this.valueLimit[this.currentSlider] : this.valueLimit;
      var index = Math.round(pos / this.gap);

      if (pos >= range[0] && pos <= range[1]) {
        var v = this.getValueByIndex(index);
        this.setTransform(pos);
        this.setCurrentValue(v, isDrag);

        if (this.isRange && (this.fixed || this.isLessRange(pos, index))) {
          this.setTransform(pos + this.fixedValue * this.gap * (this.currentSlider === 0 ? 1 : -1), true);
          this.setCurrentValue((v * this.multiple + this.fixedValue * this.spacing * this.multiple * (this.currentSlider === 0 ? 1 : -1)) / this.multiple, isDrag, true);
        }
      } else {
        var anotherSlider = pos < range[0] ? 0 : 1;
        var currentSlider = anotherSlider === 0 ? 1 : 0;
        this.setTransform(range[anotherSlider]);
        this.setCurrentValue(valueRange[anotherSlider]);

        if (this.isRange && (this.fixed || this.isLessRange(pos, index))) {
          this.setTransform(this.limit[this.idleSlider][anotherSlider], true);
          this.setCurrentValue(this.valueLimit[this.idleSlider][anotherSlider], isDrag, true);
        } else if (this.isRange && (this.enableCross || this.crossFlag) && !this.isFixed && !this.disabledArray[anotherSlider] && this.currentSlider === currentSlider) {
          this.focusSlider = anotherSlider;
          this.currentSlider = anotherSlider;
        }
      }

      this.crossFlag = false;
    },
    setCurrentValue: function setCurrentValue(val, isDrag, isIdleSlider) {
      var slider = isIdleSlider ? this.idleSlider : this.currentSlider;
      if (val < this.minimum || val > this.maximum) return false;

      if (this.isRange) {
        if (isDiff(this.currentValue[slider], val)) {
          this.currentValue.splice(slider, 1, val);

          if (!this.lazy || !this.flag) {
            this.syncValue();
          }
        }
      } else if (isDiff(this.currentValue, val)) {
        this.currentValue = val;

        if (!this.lazy || !this.flag) {
          this.syncValue();
        }
      }

      isDrag || this.setPosition();
    },
    setPosition: function setPosition(speed) {
      this.flag || this.setTransitionTime(speed === undefined ? this.speed : speed);

      if (this.isRange) {
        this.setTransform(this.position[0], this.currentSlider === 1);
        this.setTransform(this.position[1], this.currentSlider === 0);
      } else {
        this.setTransform(this.position);
      }

      this.flag || this.setTransitionTime(0);
    },
    setTransform: function setTransform(val, isIdleSlider) {
      var slider = isIdleSlider ? this.idleSlider : this.currentSlider;
      var value = roundToDPR((this.direction === 'vertical' ? this.dotHeightVal / 2 - val : val - this.dotWidthVal / 2) * (this.reverse ? -1 : 1));
      var translateValue = this.direction === 'vertical' ? "translateY(".concat(value, "px)") : "translateX(".concat(value, "px)");
      var processSize = this.fixed ? "".concat(this.fixedValue * this.gap, "px") : "".concat(slider === 0 ? this.position[1] - val : val - this.position[0], "px");
      var processPos = this.fixed ? "".concat(slider === 0 ? val : val - this.fixedValue * this.gap, "px") : "".concat(slider === 0 ? val : this.position[0], "px");

      if (this.isRange) {
        this.slider[slider].style[transform] = translateValue;

        if (this.direction === 'vertical') {
          this.$refs.process.style.height = processSize;
          this.$refs.process.style[this.reverse ? 'top' : 'bottom'] = processPos;
        } else {
          this.$refs.process.style.width = processSize;
          this.$refs.process.style[this.reverse ? 'right' : 'left'] = processPos;
        }
      } else {
        this.slider.style[transform] = translateValue;

        if (this.direction === 'vertical') {
          this.$refs.process.style.height = "".concat(val, "px");
          this.$refs.process.style[this.reverse ? 'top' : 'bottom'] = 0;
        } else {
          this.$refs.process.style.width = "".concat(val, "px");
          this.$refs.process.style[this.reverse ? 'right' : 'left'] = 0;
        }
      }
    },
    setTransitionTime: function setTransitionTime(time) {
      // In order to avoid browser merge style and modify together
      time || this.$refs.process.offsetWidth;

      if (this.isRange) {
        var sliderLen = this.slider.length;

        for (var i = 0; i < sliderLen; i++) {
          this.slider[i].style[transitionDuration] = "".concat(time, "s");
        }

        this.$refs.process.style[transitionDuration] = "".concat(time, "s");
      } else {
        this.slider.style[transitionDuration] = "".concat(time, "s");
        this.$refs.process.style[transitionDuration] = "".concat(time, "s");
      }
    },
    computedFixedValue: function computedFixedValue() {
      if (!this.isFixed) {
        this.fixedValue = 0;
        return false;
      }

      this.fixedValue = this.currentIndex[1] - this.currentIndex[0];
      this.fixedValue = Math.max(this.fixed ? this.currentIndex[1] - this.currentIndex[0] : 0, this.minRange || 0);
    },
    limitValue: function limitValue(val) {
      var _this3 = this;

      if (this.data) {
        return val;
      }

      var inRange = function inRange(v) {
        if (v < _this3.min) {
          _this3.printError("The value of the slider is ".concat(val, ", the minimum value is ").concat(_this3.min, ", the value of this slider can not be less than the minimum value"));

          return _this3.min;
        } else if (v > _this3.max) {
          _this3.printError("The value of the slider is ".concat(val, ", the maximum value is ").concat(_this3.max, ", the value of this slider can not be greater than the maximum value"));

          return _this3.max;
        }

        return v;
      };

      if (this.isRange) {
        return val.map(function (v) {
          return inRange(v);
        });
      } else {
        return inRange(val);
      }
    },
    getStaticData: function getStaticData() {
      if (this.$refs.elem) {
        this.size = this.direction === 'vertical' ? this.$refs.elem.offsetHeight : this.$refs.elem.offsetWidth;
        this.offset = this.direction === 'vertical' ? this.$refs.elem.getBoundingClientRect().top + window.pageYOffset || document.documentElement.scrollTop : this.$refs.elem.getBoundingClientRect().left;
      }
    },
    handleOverlapTooltip: function handleOverlapTooltip() {
      var isDirectionSame = this.tooltipDirection[0] === this.tooltipDirection[1];

      if (this.isRange && isDirectionSame) {
        var tooltip0 = this.reverse ? this.$refs.tooltip1 : this.$refs.tooltip0;
        var tooltip1 = this.reverse ? this.$refs.tooltip0 : this.$refs.tooltip1;
        var tooltip0Rect = tooltip0.getBoundingClientRect();
        var tooltip1Rect = tooltip1.getBoundingClientRect();
        var tooltip0Right = tooltip0Rect.right;
        var tooltip1Left = tooltip1Rect.left;
        var tooltip0Y = tooltip0Rect.top;
        var tooltip1Y = tooltip1Rect.top + tooltip1Rect.height;
        var horizontalOverlap = this.direction === 'horizontal' && tooltip0Right > tooltip1Left;
        var verticalOverlap = this.direction === 'vertical' && tooltip1Y > tooltip0Y;

        if (horizontalOverlap || verticalOverlap) {
          this.handleDisplayMergedTooltip(true);
        } else {
          this.handleDisplayMergedTooltip(false);
        }
      }
    },
    handleDisplayMergedTooltip: function handleDisplayMergedTooltip(show) {
      var tooltip0 = this.$refs.tooltip0;
      var tooltip1 = this.$refs.tooltip1;
      var mergedTooltip = this.$refs.process.getElementsByClassName('merged-tooltip')[0];

      if (show) {
        tooltip0.style.visibility = 'hidden';
        tooltip1.style.visibility = 'hidden';
        mergedTooltip.style.visibility = 'visible';
      } else {
        tooltip0.style.visibility = 'visible';
        tooltip1.style.visibility = 'visible';
        mergedTooltip.style.visibility = 'hidden';
      }
    },
    isLessRange: function isLessRange(pos, index) {
      if (!this.isRange || !this.minRange && !this.maxRange) {
        return false;
      }

      var diff = this.currentSlider === 0 ? this.currentIndex[1] - index : index - this.currentIndex[0];

      if (this.minRange && diff <= this.minRange) {
        this.fixedValue = this.minRange;
        return true;
      }

      if (this.maxRange && diff >= this.maxRange) {
        this.fixedValue = this.maxRange;
        return true;
      }

      this.computedFixedValue();
      return false;
    },
    getValueByIndex: function getValueByIndex(index) {
      return (this.spacing * this.multiple * index + this.minimum * this.multiple) / this.multiple;
    },
    getIndexByValue: function getIndexByValue(value) {
      return Math.round((value - this.minimum) * this.multiple) / (this.spacing * this.multiple);
    },
    formatting: function formatting(value) {
      return typeof this.formatter === 'string' ? this.formatter.replace(/{value}/, value) : this.formatter(value);
    },
    mergeFormatting: function mergeFormatting(value1, value2) {
      return typeof this.mergeFormatter === 'string' ? this.mergeFormatter.replace(/{(value1|value2)}/g, function (_, key) {
        return key === 'value1' ? value1 : value2;
      }) : this.mergeFormatter(value1, value2);
    },
    _start: function _start(e) {
      var index = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
      var isProcess = arguments.length > 2 ? arguments[2] : undefined;

      if (this.disabledArray[index]) {
        return false;
      }

      if (this.stopPropagation) {
        e.stopPropagation();
      }

      if (this.isRange) {
        this.currentSlider = index;

        if (isProcess) {
          if (!this.processDraggable) {
            return false;
          }

          this.processFlag = true;
          this.processSign = {
            pos: this.position,
            start: this.getPos(e.targetTouches && e.targetTouches[0] ? e.targetTouches[0] : e)
          };
        }

        if (!this.enableCross && this.val[0] === this.val[1]) {
          this.crossFlag = true;
        }
      }

      if (!isProcess && this.useKeyboard) {
        this.focusFlag = true;
        this.focusSlider = index;
      }

      this.flag = true;
      this.$emit('drag-start', this);
    },
    _move: function _move(e) {
      e.preventDefault();

      if (this.stopPropagation) {
        e.stopPropagation();
      }

      if (!this.flag) return false;
      if (e.targetTouches && e.targetTouches[0]) e = e.targetTouches[0];

      if (this.processFlag) {
        this.currentSlider = 0;
        this.setValueOnPos(this.processSign.pos[0] + this.getPos(e) - this.processSign.start, true);
        this.currentSlider = 1;
        this.setValueOnPos(this.processSign.pos[1] + this.getPos(e) - this.processSign.start, true);
      } else {
        this.dragFlag = true;
        this.setValueOnPos(this.getPos(e), true);
      }

      if (this.isRange && this.tooltipMerge) {
        this.handleOverlapTooltip();
      }
    },
    _end: function _end(e) {
      var _this4 = this;

      if (this.stopPropagation) {
        e.stopPropagation();
      }

      if (this.flag) {
        this.$emit('drag-end', this);

        if (this.lazy && isDiff(this.val, this.value)) {
          this.syncValue();
        }
      } else {
        return false;
      }

      this.flag = false;
      this.$nextTick(function () {
        _this4.crossFlag = false;
        _this4.dragFlag = false;
        _this4.processFlag = false;
      });
      this.setPosition();
    },
    blurSlider: function blurSlider(e) {
      var dot = this.isRange ? this.$refs["dot".concat(this.focusSlider)] : this.$refs.dot;

      if (!dot || dot === e.target) {
        return false;
      }

      this.focusFlag = false;
    },
    handleKeydown: function handleKeydown(e) {
      e.preventDefault();
      e.stopPropagation();

      if (!this.useKeyboard) {
        return false;
      }

      var keyCode = e.which || e.keyCode;

      switch (keyCode) {
        case 37:
        case 40:
          this.keydownFlag = true;
          this.flag = true;
          this.changeFocusSlider(this.actionsKeyboard[0]);
          break;

        case 38:
        case 39:
          this.keydownFlag = true;
          this.flag = true;
          this.changeFocusSlider(this.actionsKeyboard[1]);
          break;

        default:
          break;
      }
    },
    handleKeyup: function handleKeyup() {
      if (this.keydownFlag) {
        this.keydownFlag = false;
        this.flag = false;
      }
    },
    changeFocusSlider: function changeFocusSlider(fn) {
      var _this5 = this;

      if (this.isRange) {
        var arr = this.currentIndex.map(function (index, i) {
          if (i === _this5.focusSlider || _this5.fixed) {
            var val = fn(index);
            var range = _this5.fixed ? _this5.valueLimit[i] : [0, _this5.total];

            if (val <= range[1] && val >= range[0]) {
              return val;
            }
          }

          return index;
        });

        if (arr[0] > arr[1]) {
          this.focusSlider = this.focusSlider === 0 ? 1 : 0;
          arr = arr.reverse();
        }

        this.setIndex(arr);
      } else {
        this.setIndex(fn(this.currentIndex));
      }
    },
    bindEvents: function bindEvents() {
      var me = this;

      this.processStartFn = function (e) {
        me._start(e, 0, true);
      };

      this.dot0StartFn = function (e) {
        me._start(e, 0);
      };

      this.dot1StartFn = function (e) {
        me._start(e, 1);
      };

      if (isMobile) {
        addEvent(this.$refs.process, EVENT_TOUCH_START, this.processStartFn);
        addEvent(document, EVENT_TOUCH_MOVE, this._move);
        addEvent(document, EVENT_TOUCH_END, this._end);
        addEvent(document, EVENT_TOUCH_CANCEL, this._end);

        if (this.isRange) {
          addEvent(this.$refs.dot0, EVENT_TOUCH_START, this.dot0StartFn);
          addEvent(this.$refs.dot1, EVENT_TOUCH_START, this.dot1StartFn);
        } else {
          addEvent(this.$refs.dot, EVENT_TOUCH_START, this._start);
        }
      } else {
        addEvent(this.$refs.process, EVENT_MOUSE_DOWN, this.processStartFn);
        addEvent(document, EVENT_MOUSE_DOWN, this.blurSlider);
        addEvent(document, EVENT_MOUSE_MOVE, this._move);
        addEvent(document, EVENT_MOUSE_UP, this._end);
        addEvent(document, EVENT_MOUSE_LEAVE, this._end);

        if (this.isRange) {
          addEvent(this.$refs.dot0, EVENT_MOUSE_DOWN, this.dot0StartFn);
          addEvent(this.$refs.dot1, EVENT_MOUSE_DOWN, this.dot1StartFn);
        } else {
          addEvent(this.$refs.dot, EVENT_MOUSE_DOWN, this._start);
        }
      }

      addEvent(document, EVENT_KEY_DOWN, this.handleKeydown);
      addEvent(document, EVENT_KEY_UP, this.handleKeyup);
      addEvent(window, EVENT_RESIZE, this.refresh);

      if (this.isRange && this.tooltipMerge) {
        addEvent(this.$refs.dot0, transitionEnd, this.handleOverlapTooltip);
        addEvent(this.$refs.dot1, transitionEnd, this.handleOverlapTooltip);
      }
    },
    unbindEvents: function unbindEvents() {
      if (isMobile) {
        removeEvent(this.$refs.process, EVENT_TOUCH_START, this.processStartFn);
        removeEvent(document, EVENT_TOUCH_MOVE, this._move);
        removeEvent(document, EVENT_TOUCH_END, this._end);
        removeEvent(document, EVENT_TOUCH_CANCEL, this._end);

        if (this.isRange) {
          removeEvent(this.$refs.dot0, EVENT_TOUCH_START, this.dot0StartFn);
          removeEvent(this.$refs.dot1, EVENT_TOUCH_START, this.dot1StartFn);
        } else {
          removeEvent(this.$refs.dot, EVENT_TOUCH_START, this._start);
        }
      } else {
        removeEvent(this.$refs.process, EVENT_MOUSE_DOWN, this.processStartFn);
        removeEvent(document, EVENT_MOUSE_DOWN, this.blurSlider);
        removeEvent(document, EVENT_MOUSE_MOVE, this._move);
        removeEvent(document, EVENT_MOUSE_UP, this._end);
        removeEvent(document, EVENT_MOUSE_LEAVE, this._end);

        if (this.isRange) {
          removeEvent(this.$refs.dot0, EVENT_MOUSE_DOWN, this.dot0StartFn);
          removeEvent(this.$refs.dot1, EVENT_MOUSE_DOWN, this.dot1StartFn);
        } else {
          removeEvent(this.$refs.dot, EVENT_MOUSE_DOWN, this._start);
        }
      }

      removeEvent(document, EVENT_KEY_DOWN, this.handleKeydown);
      removeEvent(document, EVENT_KEY_UP, this.handleKeyup);
      removeEvent(window, EVENT_RESIZE, this.refresh);

      if (this.isRange && this.tooltipMerge) {
        removeEvent(this.$refs.dot0, transitionEnd, this.handleOverlapTooltip);
        removeEvent(this.$refs.dot1, transitionEnd, this.handleOverlapTooltip);
      }
    },
    refresh: function refresh() {
      if (this.$refs.elem) {
        this.getStaticData();
        this.computedFixedValue();
        this.setPosition();
        this.unbindEvents();
        this.bindEvents();
      }
    },
    printError: function printError(msg) {
      if (this.debug) {
        console.error("[VueSlider error]: ".concat(msg));
      }
    }
  },
  mounted: function mounted() {
    var _this6 = this;

    this.isComponentExists = true;

    if (typeof window === 'undefined' || typeof document === 'undefined') {
      return this.printError('window or document is undefined, can not be initialization.');
    }

    this.$nextTick(function () {
      _this6.getStaticData();

      _this6.setValue(_this6.limitValue(_this6.value), true, _this6.startAnimation ? _this6.speed : 0);

      _this6.bindEvents();

      if (_this6.isRange && _this6.tooltipMerge && !_this6.startAnimation) {
        _this6.handleOverlapTooltip();
      }
    });
    this.isMounted = true;
  },
  watch: {
    value: function value(val) {
      this.flag || this.setValue(val, true);
    },
    show: function show(bool) {
      if (bool && !this.size) {
        this.$nextTick(this.refresh);
      }
    },
    max: function max(val) {
      if (val < this.min) {
        return this.printError('The maximum value can not be less than the minimum value.');
      }

      var resetVal = this.limitValue(this.val);
      this.setValue(resetVal);
      this.refresh();
    },
    min: function min(val) {
      if (val > this.max) {
        return this.printError('The minimum value can not be greater than the maximum value.');
      }

      var resetVal = this.limitValue(this.val);
      this.setValue(resetVal);
      this.refresh();
    },
    fixed: function fixed() {
      this.computedFixedValue();
    },
    minRange: function minRange() {
      this.computedFixedValue();
    }
  },
  beforeDestroy: function beforeDestroy() {
    this.isComponentExists = false;
    this.unbindEvents();
    this.refresh();
  },
  deactivated: function deactivated() {
    this.isComponentExists = false;
    this.unbindEvents();
    this.refresh();
  }
};

var version = "1.0.3";

Slider.version = version;

Slider.install = function (Vue) {
  Vue.component(Slider.name, Slider);
};

if (typeof window !== 'undefined' && window.Vue) {
  window.Vue.use(Slider);
}

/* harmony default export */ __webpack_exports__["default"] = (Slider);


/***/ })

}]);