(window.webpackJsonp=window.webpackJsonp||[]).push([[5],{446:function(t,i,e){var s=e(466);"string"==typeof s&&(s=[[t.i,s,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};e(233)(s,n);s.locals&&(t.exports=s.locals)},447:function(t,i,e){"use strict";var s,n=(s="undefined"!=typeof window&&window.devicePixelRatio||1,function(t){return Math.round(t*s)/s}),r=function(){for(var t=navigator.userAgent.toLowerCase(),i=["Android","iPhone","SymbianOS","Windows Phone","iPad","iPod"],e=!1,s=0;s<i.length;s++)if(t.indexOf(i[s].toLowerCase())>0){e=!0;break}return e}();function o(t){return Array.prototype.isArray?Array.isArray(t):t instanceof Array||"[object Array]"===Object.prototype.toString.call(t)}function l(t,i){return Object.prototype.toString.call(t)!==Object.prototype.toString.call(i)||(o(t)&&t.length===i.length?t.some((function(t,e){return t!==i[e]})):t!==i)}var a=document.createElement("div").style,h=function(){var t={webkit:"webkitTransform",Moz:"MozTransform",O:"OTransform",ms:"msTransform",standard:"transform"};for(var i in t)if(void 0!==a[t[i]])return i;return!1}();function d(t){return!1!==h&&("standard"===h?"transitionEnd"===t?"transitionend":t:h+t.charAt(0).toUpperCase()+t.substr(1))}function c(t,i,e,s){t.addEventListener(i,e,{passive:!1,capture:!!s})}function u(t,i,e,s){t.removeEventListener(i,e,{passive:!1,capture:!!s})}var p=d("transform"),f=d("transitionDuration"),g=d("transitionEnd"),m={name:"vue-range-slider",props:{show:{type:Boolean,default:!0},value:{type:[String,Number,Array,Object],default:0},min:{type:Number,default:0},max:{type:Number,default:100},step:{type:Number,default:1},width:{type:[Number,String],default:"auto"},height:{type:[Number,String],default:6},dotSize:{type:Number,default:16},dotWidth:{type:Number,required:!1},dotHeight:{type:Number,required:!1},stopPropagation:{type:Boolean,default:!1},eventType:{type:String,default:"auto"},realTime:{type:Boolean,default:!1},tooltip:{type:[String,Boolean],default:"always",validator:function(t){return["hover","always"].indexOf(t)>-1}},direction:{type:String,default:"horizontal",validator:function(t){return["horizontal","vertical"].indexOf(t)>-1}},reverse:{type:Boolean,default:!1},disabled:{type:[Boolean,Array],default:!1},piecewiseLabel:{type:Boolean,default:!1},piecewise:{type:Boolean,default:!1},processDraggable:{type:Boolean,default:!1},clickable:{type:Boolean,default:!0},fixed:{type:Boolean,default:!1},debug:{type:Boolean,default:!0},minRange:{type:Number},maxRange:{type:Number},tooltipMerge:{type:Boolean,default:!0},startAnimation:{type:Boolean,default:!1},lazy:{type:Boolean,default:!1},enableCross:{type:Boolean,default:!0},speed:{type:Number,default:.5},useKeyboard:{type:Boolean,default:!1},actionsKeyboard:{type:Array,default:function(){return[function(t){return t-1},function(t){return t+1}]}},data:Array,formatter:[String,Function],mergeFormatter:[String,Function],tooltipDir:[Array,String],tooltipStyle:[Array,Object,Function],sliderStyle:[Array,Object,Function],focusStyle:[Array,Object,Function],disabledStyle:Object,processStyle:Object,bgStyle:Object,piecewiseStyle:Object,piecewiseActiveStyle:Object,disabledDotStyle:[Array,Object,Function],labelStyle:Object,labelActiveStyle:Object},data:function(){return{currentValue:0,size:0,fixedValue:0,focusSlider:0,currentSlider:0,flag:!1,processFlag:!1,processSign:!1,keydownFlag:!1,focusFlag:!1,dragFlag:!1,crossFlag:!1,isComponentExists:!0,isMounted:!1}},render:function(t){var i=this,e=[];if(this.isRange){var s=t("div",{ref:"dot0",staticClass:"slider-dot",class:[this.tooltipStatus,{"slider-dot-focus":this.focusFlag&&0===this.focusSlider,"slider-dot-dragging":this.flag&&0===this.currentSlider,"slider-dot-disabled":!this.boolDisabled&&this.disabledArray[0]}],style:[this.dotStyles,!this.boolDisabled&&this.disabledArray[0]?this.disabledDotStyles[0]:null,this.sliderStyles[0],this.focusFlag&&0===this.focusSlider?this.focusStyles[0]:null]},[t("div",{ref:"tooltip0",staticClass:"slider-tooltip-wrap",class:"slider-tooltip-".concat(this.tooltipDirection[0])},[this._t("tooltip",[t("span",{staticClass:"slider-tooltip",style:this.tooltipStyles[0]},this.formatter?this.formatting(this.val[0]):this.val[0])],{value:this.val[0],index:0,disabled:!this.boolDisabled&&this.disabledArray[0]})])]);e.push(s);var n=t("div",{ref:"dot1",staticClass:"slider-dot",class:[this.tooltipStatus,{"slider-dot-focus":this.focusFlag&&1===this.focusSlider,"slider-dot-dragging":this.flag&&1===this.currentSlider,"slider-dot-disabled":!this.boolDisabled&&this.disabledArray[1]}],style:[this.dotStyles,!this.boolDisabled&&this.disabledArray[1]?this.disabledDotStyles[1]:null,this.sliderStyles[1],this.focusFlag&&1===this.focusSlider?this.focusStyles[1]:null]},[t("div",{ref:"tooltip1",staticClass:"slider-tooltip-wrap",class:"slider-tooltip-".concat(this.tooltipDirection[1])},[this._t("tooltip",[t("span",{staticClass:"slider-tooltip",style:this.tooltipStyles[1]},this.formatter?this.formatting(this.val[1]):this.val[1])],{value:this.val[1],index:1,disabled:!this.boolDisabled&&this.disabledArray[1]})])]);e.push(n)}else{var r=t("div",{ref:"dot",staticClass:"slider-dot",class:[this.tooltipStatus,{"slider-dot-focus":this.focusFlag&&0===this.focusSlider,"slider-dot-dragging":this.flag&&0===this.currentSlider}],style:[this.dotStyles,this.sliderStyles,this.focusFlag&&0===this.focusSlider?this.focusStyles:null]},[t("div",{staticClass:"slider-tooltip-wrap",class:"slider-tooltip-".concat(this.tooltipDirection)},[this._t("tooltip",[t("span",{staticClass:"slider-tooltip",style:this.tooltipStyles},this.formatter?this.formatting(this.val):this.val)],{value:this.val})])]);e.push(r)}var o=this.piecewiseDotWrap.length,l=t("ul",{staticClass:"slider-piecewise"},this._l(this.piecewiseDotWrap,(function(e,s){var n=[];i.piecewise&&n.push(t("span",{staticClass:"piecewise-dot",style:[i.piecewiseStyle,e.inRange?i.piecewiseActiveStyle:null]}));var r=[];return i.piecewiseLabel&&r.push(t("span",{staticClass:"piecewise-label",style:[i.labelStyle,e.inRange?i.labelActiveStyle:null]},e.label)),t("li",{key:s,staticClass:"piecewise-item",style:[i.piecewiseDotStyle,e.style]},[i._t("piecewise",n,{label:e.label,index:s,first:0===s,last:s===o-1,active:e.inRange}),i._t("label",r,{label:e.label,index:s,first:0===s,last:s===o-1,active:e.inRange})])})));e.push(l);var a=t("div",{ref:"process",staticClass:"slider-process",class:{"slider-process-draggable":this.isRange&&this.processDraggable},style:this.processStyle,on:{click:function(t){return i.processClick(t)}}},[t("div",{ref:"mergedTooltip",staticClass:"merged-tooltip slider-tooltip-wrap",class:"slider-tooltip-".concat(this.isRange?this.tooltipDirection[0]:this.tooltipDirection),style:this.tooltipMergedPosition},[this._t("tooltip",[t("span",{staticClass:"slider-tooltip",style:this.tooltipStyles},this.mergeFormatter?this.mergeFormatting(this.val[0],this.val[1]):this.formatter?this.val[0]===this.val[1]?this.formatting(this.val[0]):"".concat(this.formatting(this.val[0])," - ").concat(this.formatting(this.val[1])):this.val[0]===this.val[1]?this.val[0]:"".concat(this.val[0]," - ").concat(this.val[1]))],{value:this.val,merge:!0})])]);return e.push(a),this.isRange||this.data||e.push(t("input",{staticClass:"slider-input",attrs:{type:"range",min:this.min,max:this.max},domProps:{value:this.val},on:{input:function(t){return i.val=t.target.value}}})),t("div",{ref:"wrap",staticClass:"vue-range-slider slider-component",class:[this.flowDirection,this.disabledClass,this.stateClass,{"slider-has-label":this.piecewiseLabel}],style:[this.wrapStyles,this.boolDisabled?this.disabledStyle:null],directives:[{name:"show",value:this.show}],on:{click:function(t){return i.wrapClick(t)}}},[t("div",{ref:"elem",staticClass:"slider",style:[this.elemStyles,this.bgStyle],attrs:{"aria-hidden":!0}},e)])},computed:{val:{get:function(){return this.data?this.isRange?[this.data[this.currentValue[0]],this.data[this.currentValue[1]]]:this.data[this.currentValue]:this.currentValue},set:function(t){if(this.data)if(this.isRange){var i=this.data.indexOf(t[0]),e=this.data.indexOf(t[1]);i>-1&&e>-1&&(this.currentValue=[i,e])}else{var s=this.data.indexOf(t);s>-1&&(this.currentValue=s)}else this.currentValue=t}},currentIndex:function(){return this.isRange?this.data?this.currentValue:[this.getIndexByValue(this.currentValue[0]),this.getIndexByValue(this.currentValue[1])]:this.getIndexByValue(this.currentValue)},tooltipMergedPosition:function(){if(!this.isMounted)return{};var t=this.tooltipDirection[0];if(this.$refs.dot0){var i={};return"vertical"===this.direction?i[t]="-".concat(this.dotHeightVal/2-this.width/2+9,"px"):(i[t]="-".concat(this.dotWidthVal/2-this.height/2+9,"px"),i.left="50%"),i}},tooltipDirection:function(){var t=this.tooltipDir||("vertical"===this.direction?"left":"top");return o(t)?this.isRange?t:t[1]:this.isRange?[t,t]:t},piecewiseDotWrap:function(){if(!this.piecewise&&!this.piecewiseLabel)return!1;for(var t=[],i=0;i<=this.total;i++){var e="vertical"===this.direction?{bottom:"".concat(this.gap*i-this.width/2,"px"),left:0}:{left:"".concat(this.gap*i-this.height/2,"px"),top:0},s=this.reverse?this.total-i:i,n=this.data?this.data[s]:this.spacing*s+this.min;t.push({style:e,label:this.formatter?this.formatting(n):n,inRange:s>=this.indexRange[0]&&s<=this.indexRange[1]})}return t},total:function(){return this.data?this.data.length-1:(Math.floor((this.maximum-this.minimum)*this.multiple)%(this.step*this.multiple)!=0&&this.printError("Prop[step] is illegal, Please make sure that the step can be divisible"),(this.maximum-this.minimum)/this.step)},piecewiseDotStyle:function(){return"vertical"===this.direction?{width:"".concat(this.width,"px"),height:"".concat(this.width,"px")}:{width:"".concat(this.height,"px"),height:"".concat(this.height,"px")}},dotStyles:function(){return"vertical"===this.direction?{width:"".concat(this.dotWidthVal,"px"),height:"".concat(this.dotHeightVal,"px"),left:"".concat(-(this.dotWidthVal-this.width)/2,"px")}:{width:"".concat(this.dotWidthVal,"px"),height:"".concat(this.dotHeightVal,"px"),top:"".concat(-(this.dotHeightVal-this.height)/2,"px")}},sliderStyles:function(){return o(this.sliderStyle)?this.isRange?this.sliderStyle:this.sliderStyle[1]:"function"==typeof this.sliderStyle?this.sliderStyle(this.val,this.currentIndex):this.isRange?[this.sliderStyle,this.sliderStyle]:this.sliderStyle},tooltipStyles:function(){return o(this.tooltipStyle)?this.isRange?this.tooltipStyle:this.tooltipStyle[1]:"function"==typeof this.tooltipStyle?this.tooltipStyle(this.val,this.currentIndex):this.isRange?[this.tooltipStyle,this.tooltipStyle]:this.tooltipStyle},focusStyles:function(){return o(this.focusStyle)?this.isRange?this.focusStyle:this.focusStyle[1]:"function"==typeof this.focusStyle?this.focusStyle(this.val,this.currentIndex):this.isRange?[this.focusStyle,this.focusStyle]:this.focusStyle},elemStyles:function(){return"vertical"===this.direction?{width:"".concat(this.width,"px"),height:"100%"}:{height:"".concat(this.height,"px")}},wrapStyles:function(){return"vertical"===this.direction?{height:"number"==typeof this.height?"".concat(this.height,"px"):this.height,padding:"".concat(this.dotHeightVal/2,"px ").concat(this.dotWidthVal/2,"px")}:{width:"number"==typeof this.width?"".concat(this.width,"px"):this.width,padding:"".concat(this.dotHeightVal/2,"px ").concat(this.dotWidthVal/2,"px")}},stateClass:function(){return{"slider-state-process-drag":this.processFlag,"slider-state-drag":this.flag&&!this.processFlag&&!this.keydownFlag,"slider-state-focus":this.focusFlag}},tooltipStatus:function(){return"hover"===this.tooltip&&this.flag?"slider-always":this.tooltip?"slider-".concat(this.tooltip):""},tooltipClass:function(){return["slider-tooltip-".concat(this.tooltipDirection),"slider-tooltip"]},minimum:function(){return this.data?0:this.min},maximum:function(){return this.data?this.data.length-1:this.max},multiple:function(){var t="".concat(this.step).split(".")[1];return t?Math.pow(10,t.length):1},indexRange:function(){return this.isRange?this.currentIndex:[0,this.currentIndex]},spacing:function(){return this.data?1:this.step},gap:function(){return this.size/this.total},dotWidthVal:function(){return"number"==typeof this.dotWidth?this.dotWidth:this.dotSize},dotHeightVal:function(){return"number"==typeof this.dotHeight?this.dotHeight:this.dotSize},disabledArray:function(){return o(this.disabled)?this.disabled:[this.disabled,this.disabled]},boolDisabled:function(){return this.disabledArray.every((function(t){return!0===t}))},disabledClass:function(){return this.boolDisabled?"slider-disabled":""},flowDirection:function(){return"slider-".concat(this.direction+(this.reverse?"-reverse":""))},isRange:function(){return o(this.value)},isDisabled:function(){return"none"===this.eventType||this.boolDisabled},isFixed:function(){return this.fixed||this.minRange},position:function(){return this.isRange?[(this.currentValue[0]-this.minimum)/this.spacing*this.gap,(this.currentValue[1]-this.minimum)/this.spacing*this.gap]:(this.currentValue-this.minimum)/this.spacing*this.gap},limit:function(){return this.isRange?this.isFixed?[[0,(this.total-this.fixedValue)*this.gap],[this.fixedValue*this.gap,this.size]]:[[0,this.position[1]],[this.position[0],this.size]]:[0,this.size]},valueLimit:function(){return this.isRange?this.isFixed?[[this.minimum,this.maximum-this.fixedValue*(this.spacing*this.multiple)/this.multiple],[this.minimum+this.fixedValue*(this.spacing*this.multiple)/this.multiple,this.maximum]]:[[this.minimum,this.currentValue[1]],[this.currentValue[0],this.maximum]]:[this.minimum,this.maximum]},idleSlider:function(){return 0===this.currentSlider?1:0},slider:function(){return this.isRange?[this.$refs.dot0,this.$refs.dot1]:this.$refs.dot}},methods:{setValue:function(t,i,e){var s=this;if(l(this.val,t)){var n=this.limitValue(t);this.val=this.isRange?n.concat():n,this.computedFixedValue(),this.syncValue(i)}this.$nextTick((function(){return s.setPosition(e)}))},setIndex:function(t){var i;o(t)&&this.isRange?(i=this.data?[this.data[t[0]],this.data[t[1]]]:[this.getValueByIndex(t[0]),this.getValueByIndex(t[1])],this.setValue(i)):(t=this.getValueByIndex(t),this.isRange&&(this.currentSlider=t>(this.currentValue[1]-this.currentValue[0])/2+this.currentValue[0]?1:0),this.setCurrentValue(t))},wrapClick:function(t){if(this.isDisabled||!this.clickable||this.processFlag||this.dragFlag)return!1;var i=this.getPos(t);if(this.isRange)if(this.disabledArray.every((function(t){return!1===t})))this.currentSlider=i>(this.position[1]-this.position[0])/2+this.position[0]?1:0;else if(this.disabledArray[0]){if(i<this.position[0])return!1;this.currentSlider=1}else if(this.disabledArray[1]){if(i>this.position[1])return!1;this.currentSlider=0}if(this.disabledArray[this.currentSlider])return!1;if(this.setValueOnPos(i),this.isRange&&this.tooltipMerge){var e=setInterval(this.handleOverlapTooltip,16.7);setTimeout((function(){return window.clearInterval(e)}),1e3*this.speed)}},processClick:function(t){this.fixed&&t.stopPropagation()},syncValue:function(t){var i=this.isRange?this.val.concat():this.val;this.$emit("input",i),this.keydownFlag&&this.$emit("on-keypress",i),t||this.$emit("slide-end",i)},getPos:function(t){return this.realTime&&this.getStaticData(),"vertical"===this.direction?this.reverse?t.pageY-this.offset:this.size-(t.pageY-this.offset):this.reverse?this.size-(t.pageX-this.offset):t.pageX-this.offset},setValueOnPos:function(t,i){var e=this.isRange?this.limit[this.currentSlider]:this.limit,s=this.isRange?this.valueLimit[this.currentSlider]:this.valueLimit,n=Math.round(t/this.gap);if(t>=e[0]&&t<=e[1]){var r=this.getValueByIndex(n);this.setTransform(t),this.setCurrentValue(r,i),this.isRange&&(this.fixed||this.isLessRange(t,n))&&(this.setTransform(t+this.fixedValue*this.gap*(0===this.currentSlider?1:-1),!0),this.setCurrentValue((r*this.multiple+this.fixedValue*this.spacing*this.multiple*(0===this.currentSlider?1:-1))/this.multiple,i,!0))}else{var o=t<e[0]?0:1,l=0===o?1:0;this.setTransform(e[o]),this.setCurrentValue(s[o]),this.isRange&&(this.fixed||this.isLessRange(t,n))?(this.setTransform(this.limit[this.idleSlider][o],!0),this.setCurrentValue(this.valueLimit[this.idleSlider][o],i,!0)):!this.isRange||!this.enableCross&&!this.crossFlag||this.isFixed||this.disabledArray[o]||this.currentSlider!==l||(this.focusSlider=o,this.currentSlider=o)}this.crossFlag=!1},setCurrentValue:function(t,i,e){var s=e?this.idleSlider:this.currentSlider;if(t<this.minimum||t>this.maximum)return!1;this.isRange?l(this.currentValue[s],t)&&(this.currentValue.splice(s,1,t),this.lazy&&this.flag||this.syncValue()):l(this.currentValue,t)&&(this.currentValue=t,this.lazy&&this.flag||this.syncValue()),i||this.setPosition()},setPosition:function(t){this.flag||this.setTransitionTime(void 0===t?this.speed:t),this.isRange?(this.setTransform(this.position[0],1===this.currentSlider),this.setTransform(this.position[1],0===this.currentSlider)):this.setTransform(this.position),this.flag||this.setTransitionTime(0)},setTransform:function(t,i){var e=i?this.idleSlider:this.currentSlider,s=n(("vertical"===this.direction?this.dotHeightVal/2-t:t-this.dotWidthVal/2)*(this.reverse?-1:1)),r="vertical"===this.direction?"translateY(".concat(s,"px)"):"translateX(".concat(s,"px)"),o=this.fixed?"".concat(this.fixedValue*this.gap,"px"):"".concat(0===e?this.position[1]-t:t-this.position[0],"px"),l=this.fixed?"".concat(0===e?t:t-this.fixedValue*this.gap,"px"):"".concat(0===e?t:this.position[0],"px");this.isRange?(this.slider[e].style[p]=r,"vertical"===this.direction?(this.$refs.process.style.height=o,this.$refs.process.style[this.reverse?"top":"bottom"]=l):(this.$refs.process.style.width=o,this.$refs.process.style[this.reverse?"right":"left"]=l)):(this.slider.style[p]=r,"vertical"===this.direction?(this.$refs.process.style.height="".concat(t,"px"),this.$refs.process.style[this.reverse?"top":"bottom"]=0):(this.$refs.process.style.width="".concat(t,"px"),this.$refs.process.style[this.reverse?"right":"left"]=0))},setTransitionTime:function(t){if(t||this.$refs.process.offsetWidth,this.isRange){for(var i=this.slider.length,e=0;e<i;e++)this.slider[e].style[f]="".concat(t,"s");this.$refs.process.style[f]="".concat(t,"s")}else this.slider.style[f]="".concat(t,"s"),this.$refs.process.style[f]="".concat(t,"s")},computedFixedValue:function(){if(!this.isFixed)return this.fixedValue=0,!1;this.fixedValue=this.currentIndex[1]-this.currentIndex[0],this.fixedValue=Math.max(this.fixed?this.currentIndex[1]-this.currentIndex[0]:0,this.minRange||0)},limitValue:function(t){var i=this;if(this.data)return t;var e=function(e){return e<i.min?(i.printError("The value of the slider is ".concat(t,", the minimum value is ").concat(i.min,", the value of this slider can not be less than the minimum value")),i.min):e>i.max?(i.printError("The value of the slider is ".concat(t,", the maximum value is ").concat(i.max,", the value of this slider can not be greater than the maximum value")),i.max):e};return this.isRange?t.map((function(t){return e(t)})):e(t)},getStaticData:function(){this.$refs.elem&&(this.size="vertical"===this.direction?this.$refs.elem.offsetHeight:this.$refs.elem.offsetWidth,this.offset="vertical"===this.direction?this.$refs.elem.getBoundingClientRect().top+window.pageYOffset||document.documentElement.scrollTop:this.$refs.elem.getBoundingClientRect().left)},handleOverlapTooltip:function(){var t=this.tooltipDirection[0]===this.tooltipDirection[1];if(this.isRange&&t){var i=this.reverse?this.$refs.tooltip1:this.$refs.tooltip0,e=this.reverse?this.$refs.tooltip0:this.$refs.tooltip1,s=i.getBoundingClientRect(),n=e.getBoundingClientRect(),r=s.right,o=n.left,l=s.top,a=n.top+n.height,h="horizontal"===this.direction&&r>o,d="vertical"===this.direction&&a>l;h||d?this.handleDisplayMergedTooltip(!0):this.handleDisplayMergedTooltip(!1)}},handleDisplayMergedTooltip:function(t){var i=this.$refs.tooltip0,e=this.$refs.tooltip1,s=this.$refs.process.getElementsByClassName("merged-tooltip")[0];t?(i.style.visibility="hidden",e.style.visibility="hidden",s.style.visibility="visible"):(i.style.visibility="visible",e.style.visibility="visible",s.style.visibility="hidden")},isLessRange:function(t,i){if(!this.isRange||!this.minRange&&!this.maxRange)return!1;var e=0===this.currentSlider?this.currentIndex[1]-i:i-this.currentIndex[0];return this.minRange&&e<=this.minRange?(this.fixedValue=this.minRange,!0):this.maxRange&&e>=this.maxRange?(this.fixedValue=this.maxRange,!0):(this.computedFixedValue(),!1)},getValueByIndex:function(t){return(this.spacing*this.multiple*t+this.minimum*this.multiple)/this.multiple},getIndexByValue:function(t){return Math.round((t-this.minimum)*this.multiple)/(this.spacing*this.multiple)},formatting:function(t){return"string"==typeof this.formatter?this.formatter.replace(/{value}/,t):this.formatter(t)},mergeFormatting:function(t,i){return"string"==typeof this.mergeFormatter?this.mergeFormatter.replace(/{(value1|value2)}/g,(function(e,s){return"value1"===s?t:i})):this.mergeFormatter(t,i)},_start:function(t){var i=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0,e=arguments.length>2?arguments[2]:void 0;if(this.disabledArray[i])return!1;if(this.stopPropagation&&t.stopPropagation(),this.isRange){if(this.currentSlider=i,e){if(!this.processDraggable)return!1;this.processFlag=!0,this.processSign={pos:this.position,start:this.getPos(t.targetTouches&&t.targetTouches[0]?t.targetTouches[0]:t)}}this.enableCross||this.val[0]!==this.val[1]||(this.crossFlag=!0)}!e&&this.useKeyboard&&(this.focusFlag=!0,this.focusSlider=i),this.flag=!0,this.$emit("drag-start",this)},_move:function(t){if(t.preventDefault(),this.stopPropagation&&t.stopPropagation(),!this.flag)return!1;t.targetTouches&&t.targetTouches[0]&&(t=t.targetTouches[0]),this.processFlag?(this.currentSlider=0,this.setValueOnPos(this.processSign.pos[0]+this.getPos(t)-this.processSign.start,!0),this.currentSlider=1,this.setValueOnPos(this.processSign.pos[1]+this.getPos(t)-this.processSign.start,!0)):(this.dragFlag=!0,this.setValueOnPos(this.getPos(t),!0)),this.isRange&&this.tooltipMerge&&this.handleOverlapTooltip()},_end:function(t){var i=this;if(this.stopPropagation&&t.stopPropagation(),!this.flag)return!1;this.$emit("drag-end",this),this.lazy&&l(this.val,this.value)&&this.syncValue(),this.flag=!1,this.$nextTick((function(){i.crossFlag=!1,i.dragFlag=!1,i.processFlag=!1})),this.setPosition()},blurSlider:function(t){var i=this.isRange?this.$refs["dot".concat(this.focusSlider)]:this.$refs.dot;if(!i||i===t.target)return!1;this.focusFlag=!1},handleKeydown:function(t){if(t.preventDefault(),t.stopPropagation(),!this.useKeyboard)return!1;switch(t.which||t.keyCode){case 37:case 40:this.keydownFlag=!0,this.flag=!0,this.changeFocusSlider(this.actionsKeyboard[0]);break;case 38:case 39:this.keydownFlag=!0,this.flag=!0,this.changeFocusSlider(this.actionsKeyboard[1])}},handleKeyup:function(){this.keydownFlag&&(this.keydownFlag=!1,this.flag=!1)},changeFocusSlider:function(t){var i=this;if(this.isRange){var e=this.currentIndex.map((function(e,s){if(s===i.focusSlider||i.fixed){var n=t(e),r=i.fixed?i.valueLimit[s]:[0,i.total];if(n<=r[1]&&n>=r[0])return n}return e}));e[0]>e[1]&&(this.focusSlider=0===this.focusSlider?1:0,e=e.reverse()),this.setIndex(e)}else this.setIndex(t(this.currentIndex))},bindEvents:function(){var t=this;this.processStartFn=function(i){t._start(i,0,!0)},this.dot0StartFn=function(i){t._start(i,0)},this.dot1StartFn=function(i){t._start(i,1)},r?(c(this.$refs.process,"touchstart",this.processStartFn),c(document,"touchmove",this._move),c(document,"touchend",this._end),c(document,"touchcancel",this._end),this.isRange?(c(this.$refs.dot0,"touchstart",this.dot0StartFn),c(this.$refs.dot1,"touchstart",this.dot1StartFn)):c(this.$refs.dot,"touchstart",this._start)):(c(this.$refs.process,"mousedown",this.processStartFn),c(document,"mousedown",this.blurSlider),c(document,"mousemove",this._move),c(document,"mouseup",this._end),c(document,"mouseleave",this._end),this.isRange?(c(this.$refs.dot0,"mousedown",this.dot0StartFn),c(this.$refs.dot1,"mousedown",this.dot1StartFn)):c(this.$refs.dot,"mousedown",this._start)),c(document,"keydown",this.handleKeydown),c(document,"keyup",this.handleKeyup),c(window,"resize",this.refresh),this.isRange&&this.tooltipMerge&&(c(this.$refs.dot0,g,this.handleOverlapTooltip),c(this.$refs.dot1,g,this.handleOverlapTooltip))},unbindEvents:function(){r?(u(this.$refs.process,"touchstart",this.processStartFn),u(document,"touchmove",this._move),u(document,"touchend",this._end),u(document,"touchcancel",this._end),this.isRange?(u(this.$refs.dot0,"touchstart",this.dot0StartFn),u(this.$refs.dot1,"touchstart",this.dot1StartFn)):u(this.$refs.dot,"touchstart",this._start)):(u(this.$refs.process,"mousedown",this.processStartFn),u(document,"mousedown",this.blurSlider),u(document,"mousemove",this._move),u(document,"mouseup",this._end),u(document,"mouseleave",this._end),this.isRange?(u(this.$refs.dot0,"mousedown",this.dot0StartFn),u(this.$refs.dot1,"mousedown",this.dot1StartFn)):u(this.$refs.dot,"mousedown",this._start)),u(document,"keydown",this.handleKeydown),u(document,"keyup",this.handleKeyup),u(window,"resize",this.refresh),this.isRange&&this.tooltipMerge&&(u(this.$refs.dot0,g,this.handleOverlapTooltip),u(this.$refs.dot1,g,this.handleOverlapTooltip))},refresh:function(){this.$refs.elem&&(this.getStaticData(),this.computedFixedValue(),this.setPosition(),this.unbindEvents(),this.bindEvents())},printError:function(t){this.debug&&console.error("[VueSlider error]: ".concat(t))}},mounted:function(){var t=this;if(this.isComponentExists=!0,"undefined"==typeof window||"undefined"==typeof document)return this.printError("window or document is undefined, can not be initialization.");this.$nextTick((function(){t.getStaticData(),t.setValue(t.limitValue(t.value),!0,t.startAnimation?t.speed:0),t.bindEvents(),t.isRange&&t.tooltipMerge&&!t.startAnimation&&t.handleOverlapTooltip()})),this.isMounted=!0},watch:{value:function(t){this.flag||this.setValue(t,!0)},show:function(t){t&&!this.size&&this.$nextTick(this.refresh)},max:function(t){if(t<this.min)return this.printError("The maximum value can not be less than the minimum value.");var i=this.limitValue(this.val);this.setValue(i),this.refresh()},min:function(t){if(t>this.max)return this.printError("The minimum value can not be greater than the maximum value.");var i=this.limitValue(this.val);this.setValue(i),this.refresh()},fixed:function(){this.computedFixedValue()},minRange:function(){this.computedFixedValue()}},beforeDestroy:function(){this.isComponentExists=!1,this.unbindEvents(),this.refresh()},deactivated:function(){this.isComponentExists=!1,this.unbindEvents(),this.refresh()}};m.version="1.0.3",m.install=function(t){t.component(m.name,m)},"undefined"!=typeof window&&window.Vue&&window.Vue.use(m),i.a=m},466:function(t,i,e){(t.exports=e(232)(!1)).push([t.i,".vue-range-slider.slider-component {\n  position: relative;\n  -webkit-box-sizing: border-box;\n          box-sizing: border-box;\n  -webkit-user-select: none;\n     -moz-user-select: none;\n      -ms-user-select: none;\n          user-select: none;\n}\n.vue-range-slider.slider-component .slider {\n  position: relative;\n  display: block;\n  border-radius: 15px;\n  background-color: #ccc;\n}\n.vue-range-slider.slider-component .slider::after {\n  content: '';\n  position: absolute;\n  left: 0;\n  top: 0;\n  width: 100%;\n  height: 100%;\n  z-index: 2;\n}\n.vue-range-slider.slider-component .slider .slider-dot {\n  position: absolute;\n  border-radius: 50%;\n  background-color: #fff;\n  -webkit-box-shadow: 0.5px 0.5px 2px 1px rgba(0,0,0,0.32);\n          box-shadow: 0.5px 0.5px 2px 1px rgba(0,0,0,0.32);\n  -webkit-transition: all 0s;\n  transition: all 0s;\n  will-change: transform;\n  cursor: pointer;\n  z-index: 5;\n}\n.vue-range-slider.slider-component .slider .slider-dot.slider-dot-focus {\n  -webkit-box-shadow: 0 0 2px 1px #3498db;\n          box-shadow: 0 0 2px 1px #3498db;\n}\n.vue-range-slider.slider-component .slider .slider-dot.slider-dot-dragging {\n  z-index: 5;\n}\n.vue-range-slider.slider-component .slider .slider-dot.slider-dot-disabled {\n  z-index: 4;\n}\n.vue-range-slider.slider-component .slider .slider-dot.slider-hover:hover .slider-tooltip-wrap {\n  display: block;\n}\n.vue-range-slider.slider-component .slider .slider-dot.slider-always .slider-tooltip-wrap {\n  display: block !important;\n}\n.vue-range-slider.slider-component .slider .slider-process {\n  position: absolute;\n  border-radius: 15px;\n  background-color: #3498db;\n  z-index: 1;\n}\n.vue-range-slider.slider-component .slider .slider-process.slider-process-draggable {\n  cursor: pointer;\n  z-index: 3;\n}\n.vue-range-slider.slider-component .slider .slider-input {\n  position: absolute;\n  overflow: hidden;\n  height: 1px;\n  width: 1px;\n  clip: rect(1px, 1px, 1px, 1px);\n}\n.vue-range-slider.slider-component .slider .slider-piecewise {\n  position: absolute;\n  width: 100%;\n  padding: 0;\n  margin: 0;\n  left: 0;\n  top: 0;\n  height: 100%;\n  list-style: none;\n}\n.vue-range-slider.slider-component .slider .slider-piecewise .piecewise-item {\n  position: absolute;\n  width: 8px;\n  height: 8px;\n}\n.vue-range-slider.slider-component .slider .slider-piecewise .piecewise-item:first-child .piecewise-dot,\n.vue-range-slider.slider-component .slider .slider-piecewise .piecewise-item:last-child .piecewise-dot {\n  visibility: hidden;\n}\n.vue-range-slider.slider-component .slider .slider-piecewise .piecewise-item .piecewise-dot {\n  position: absolute;\n  left: 50%;\n  top: 50%;\n  width: 100%;\n  height: 100%;\n  display: inline-block;\n  background-color: rgba(0,0,0,0.16);\n  border-radius: 50%;\n  -webkit-transform: translate(-50%, -50%);\n          transform: translate(-50%, -50%);\n  z-index: 2;\n  -webkit-transition: all 0.3s;\n  transition: all 0.3s;\n}\n.vue-range-slider.slider-component.slider-horizontal .slider-dot {\n  left: 0;\n}\n.vue-range-slider.slider-component.slider-horizontal .slider-process {\n  width: 0;\n  height: 100%;\n  top: 0;\n  left: 0;\n  will-change: width;\n}\n.vue-range-slider.slider-component.slider-vertical .slider-dot {\n  bottom: 0;\n}\n.vue-range-slider.slider-component.slider-vertical .slider-process {\n  width: 100%;\n  height: 0;\n  bottom: 0;\n  left: 0;\n  will-change: height;\n}\n.vue-range-slider.slider-component.slider-horizontal-reverse .slider-dot {\n  right: 0;\n}\n.vue-range-slider.slider-component.slider-horizontal-reverse .slider-process {\n  width: 0;\n  height: 100%;\n  top: 0;\n  right: 0;\n}\n.vue-range-slider.slider-component.slider-vertical-reverse .slider-dot {\n  top: 0;\n}\n.vue-range-slider.slider-component.slider-vertical-reverse .slider-process {\n  width: 100%;\n  height: 0;\n  top: 0;\n  left: 0;\n}\n.vue-range-slider.slider-component.slider-horizontal .slider-piecewise .piecewise-item .piecewise-label,\n.vue-range-slider.slider-component.slider-horizontal-reverse .slider-piecewise .piecewise-item .piecewise-label {\n  position: absolute;\n  display: inline-block;\n  top: 100%;\n  left: 50%;\n  white-space: nowrap;\n  font-size: 12px;\n  color: #333;\n  -webkit-transform: translate(-50%, 8px);\n          transform: translate(-50%, 8px);\n  visibility: visible;\n}\n.vue-range-slider.slider-component.slider-vertical .slider-piecewise .piecewise-item .piecewise-label,\n.vue-range-slider.slider-component.slider-vertical-reverse .slider-piecewise .piecewise-item .piecewise-label {\n  position: absolute;\n  display: inline-block;\n  top: 50%;\n  left: 100%;\n  white-space: nowrap;\n  font-size: 12px;\n  color: #333;\n  -webkit-transform: translate(8px, -50%);\n          transform: translate(8px, -50%);\n  visibility: visible;\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap {\n  display: none;\n  position: absolute;\n  z-index: 9;\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.merged-tooltip {\n  display: block;\n  visibility: hidden;\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-top {\n  top: -9px;\n  left: 50%;\n  -webkit-transform: translate(-50%, -100%);\n          transform: translate(-50%, -100%);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-top .slider-tooltip::before {\n  content: '';\n  position: absolute;\n  bottom: -10px;\n  left: 50%;\n  width: 0;\n  height: 0;\n  border: 5px solid transparent;\n  border-top-color: inherit;\n  -webkit-transform: translate(-50%, 0);\n          transform: translate(-50%, 0);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-bottom {\n  bottom: -9px;\n  left: 50%;\n  -webkit-transform: translate(-50%, 100%);\n          transform: translate(-50%, 100%);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-bottom .slider-tooltip::before {\n  content: '';\n  position: absolute;\n  top: -10px;\n  left: 50%;\n  width: 0;\n  height: 0;\n  border: 5px solid transparent;\n  border-bottom-color: inherit;\n  -webkit-transform: translate(-50%, 0);\n          transform: translate(-50%, 0);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-left {\n  top: 50%;\n  left: -9px;\n  -webkit-transform: translate(-100%, -50%);\n          transform: translate(-100%, -50%);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-left .slider-tooltip::before {\n  content: '';\n  position: absolute;\n  top: 50%;\n  right: -10px;\n  width: 0;\n  height: 0;\n  border: 5px solid transparent;\n  border-left-color: inherit;\n  -webkit-transform: translate(0, -50%);\n          transform: translate(0, -50%);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-right {\n  top: 50%;\n  right: -9px;\n  -webkit-transform: translate(100%, -50%);\n          transform: translate(100%, -50%);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.slider-tooltip-right .slider-tooltip::before {\n  content: '';\n  position: absolute;\n  top: 50%;\n  left: -10px;\n  width: 0;\n  height: 0;\n  border: 5px solid transparent;\n  border-right-color: inherit;\n  -webkit-transform: translate(0, -50%);\n          transform: translate(0, -50%);\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap.merged-tooltip {\n  display: block;\n  visibility: hidden;\n}\n.vue-range-slider.slider-component .slider-tooltip-wrap .slider-tooltip {\n  display: block;\n  font-size: 14px;\n  white-space: nowrap;\n  padding: 2px 5px;\n  min-width: 20px;\n  text-align: center;\n  color: #fff;\n  border-radius: 5px;\n  border: 1px solid #3498db;\n  background-color: #3498db;\n}\n.vue-range-slider.slider-component.slider-disabled {\n  opacity: 0.5;\n  cursor: not-allowed;\n}\n.vue-range-slider.slider-component.slider-disabled .slider-dot {\n  cursor: not-allowed;\n}\n.vue-range-slider.slider-component.slider-has-label {\n  margin-bottom: 15px;\n}\n",""])}}]);