<template>
<div>
    <div class="input-group color-picker" ref="colorpicker">
        <input type="text" class="form-control" id="colorInput" :value="colorValue" @input="updateFromInput" @focus="showPicker()" />
        <div class="input-group-append"><span class="input-group-text input-group-addon color-picker-container">
                <span class="current-color" :style="'background-color: ' + colorValue"  @click="togglePicker()"></span>
                <chrome-picker :value="colors" @input="updateFromPicker" v-if="displayPicker" />
            </span></div>
    </div>
</div>
</template>
<script>
import VueColor from 'vue-color';
let Chrome = VueColor.Chrome;
export default {
    props: ['colorValue', 'colors'],
    components: {
        'chrome-picker': Chrome,
    },
    data() {
		return {
			displayPicker: false,
		}
	},
    methods: {
        updateColors(color) {
            let colorsTemp = '';
            if (color.slice(0, 1) == '#') {
                colorsTemp = {
                    hex: color
                };
            } else if (color.slice(0, 4) == 'rgba') {
                let rgba = color.replace(/^rgba?\(|\s+|\)$/g, '').split(','),
                    color = '#' + ((1 << 24) + (parseInt(rgba[0]) << 16) + (parseInt(rgba[1]) << 8) + parseInt(rgba[2])).toString(16).slice(1);
                colorsTemp = {
                    hex: color,
                    a: rgba[3],
                }
            }
            this.$emit('updateColors', colorsTemp, color);
        },
        updateFromInput() {
            this.updateColors(this.colorValue);
        },
        updateFromPicker(color) {
            let colorValueTemp = '';
            if (color.rgba.a == 1) {
                colorValueTemp = color.hex;
            } else {
                colorValueTemp = 'rgba(' + color.rgba.r + ', ' + color.rgba.g + ', ' + color.rgba.b + ', ' + color.rgba.a + ')';
            }
            this.$emit('updateFromPicker', color, colorValueTemp);
        },
        showPicker() {
			document.addEventListener('click', this.documentClick);
			this.displayPicker = true;
		},
		hidePicker() {
			document.removeEventListener('click', this.documentClick);
			this.displayPicker = false;
		},
		togglePicker() {
			this.displayPicker ? this.hidePicker() : this.showPicker();
		},
		documentClick(e) {
			var el = this.$refs.colorpicker,
				target = e.target;
			if(el !== target && !el.contains(target)) {
				this.hidePicker()
			}
		}
    },
    watch: {
        colorValue(val) {
            if (val) {
                this.updateColors(val);
                this.$emit('input', val);
            }
        }
    },
}
</script>
