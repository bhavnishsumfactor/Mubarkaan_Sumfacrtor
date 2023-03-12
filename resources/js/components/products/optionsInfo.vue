<template>
  <form
    class="form"
    id="form"
    novalidate="novalidate"
    v-on:submit.prevent="validateRecord"
    v-bind:class="[(!$canWrite('PRODUCTS')) ? 'disabledbutton': '']"
  >
    <div
      class="wizard-v2__content"
      data-f-a-tbitwizard-type="step-content"
      data-f-a-tbitwizard-state="current"
    >
      <div class="form__section form__section--first">
        <div class="wizard-v2__form">
          <div class="form-group row justify-content-between">
            <div class="col">
              <label>{{$t('LBL_PRODUCT_HAS_MULTIPLE_OPTIONS')}}</label>
            </div>

            <div class="col-auto">
              <div class="radio-inline">
                <label class="radio">
                  <input
                    type="radio"
                    checked="checked"
                    name="radio7"
                    :value="1"
                    v-model="optionsFormFields.optionEnable"
                  />
                  {{$t('LBL_YES')}}
                  <span></span>
                </label>
                <label class="radio">
                  <input
                    type="radio"
                    name="radio7"
                    :value="0"
                    v-model="optionsFormFields.optionEnable"
                  />
                  {{$t('LBL_NO')}}
                  <span></span>
                </label>
              </div>
            </div>
          </div>

          <div v-if="optionsFormFields.optionEnable">
            <span
              v-if="errors.first('optionsSelect')"
              class="error text-danger"
            >{{ errors.first('optionsSelect') }}</span>
            <span v-if="optiontag" class="error text-danger">{{$t('MSG_OPTION_TAGS_REQUIRED')}}</span>

            <div
              class="form-group row options"
              v-for="(optionInput, index) in optionInputs"
              :key="'option'+index"
              :data-id="index+1"
            >
              <label class="col-md-2 col-form-label">{{$t('LBL_OPTION')}} {{index+1}}</label>
              <div class="col-md-10">
                <div class="row">
                  <div class="col-md-4">
                    <select
                      class="form-control"
                      v-model="optionsFormFields.optionSelect[index]"
                      @change="addVariant"
                      name="optionsSelect"
                      v-validate="'required'"
                      :data-vv-as="$t('LBL_OPTION')"
                      data-vv-validate-on="none"
                    >
                      <option disabled value>{{$t('LBL_SELECT_OPTION')}}</option>
                      <option
                        v-for="optionValue in optionInput.optionsVal"
                        :key="optionValue.option_id"
                        :value="optionValue.option_id"
                      >{{optionValue.option_name}}</option>
                    </select>
                  </div>

                  <div class="col-md-8">
                    <div class="row no-gutters">
                      <div class="col">
                        <vue-tags-input
                          class="vue-tags-style"
                          v-model="optionsFormFields.optionsValue[index]"
                          :tags="optionTags[index]"
                          :avoid-adding-duplicates="false"
                          @tags-changed="addVariant"
                        ></vue-tags-input>
                      </div>
                      <div class="col-auto">
                        <div class="actions">
                        <a
                          href="javascript:;"
                          data-repeater-delete
                          @click="deleteOptions(index)"
                          class="btn btn-icon btn-light ml-2"
                        >
                          <svg>   
                              <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>   
                          </svg>
                        </a>

                        <button
                          v-if="optionInputs.length < 3 && index == optionInputs.length -1 "
                          data-repeater-create
                          @click="addOptions"
                          class="btn btn-icon btn-light ml-2"
                        >
                          <i class="fas fa-plus"></i>
                        </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="separator separator--border-dashed"></div>

            <ul class="tbl-variant tbl-variant-head">
              <li class="variant">
                <strong>{{$t('LBL_VARIANT')}}</strong>
              </li>
              <li>
                <strong>
                  {{$t('Price')}}
                  <span v-if="priceIncTax == '1'">({{$t('LBL_INCLUDING_TAX')}})</span>
                  <span v-if="priceIncTax == '0'">({{$t('LBL_EXCLUDING_TAX')}})</span>
                </strong>
              </li>
              <li>
                <strong>{{$t('LBL_QUANTITY')}}</strong>
              </li>
              <li>
                <strong>{{$t('LBL_SKU')}}</strong>
              </li>
              <li>
                <button
                  type="button"
                  class="btn btn-brand btn-sm"
                  :disabled="copyVariant === false"
                  v-bind:class="[copyVariant === false ? 'disabled' : '']"
                  @click="undoCopiedVariant"
                >{{$t('BTN_UNDO')}}</button>
              </li>
            </ul>

            <perfect-scrollbar style="max-height: 673px;">
              <ul
                class="tbl-variant "
                v-bind:class="[ optionsFormFields.variantPublish[index] == false ? 'disableVariant' : '', errors.first('variantPrice['+index+']') || errors.first('variantQuantity['+index+']') || errors.first('SKU['+index+']') ? 'is-errors':'']"
                v-for="(variantInput, index) in variantInputs"
                :key="'variant'+index"
                v-if="variantInput != ''"
              >
                <li class="variant" v-html="variantText(variantInput,index)"></li>
                <li>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">{{currencySymbol}}</span>
                    </div>
                    <input
                      type="text"
                      class="form-control"
                      :disabled="optionsFormFields.variantPublish[index] == false"
                      v-model="optionsFormFields.variantPrice[index]"
                      @keypress="$onlyNumber"
                      placeholder="0.00"
                      :name="'variantPrice['+index+']'"
                        v-validate="optionsFormFields.variantPublish[index] == true ? 'required|decimal:'+decimalValues+'|min_value:1': ''"
                      :data-vv-as="$t('LBL_PRICE')"
                      data-vv-validate-on="none"
                    />
                    <a
                      class="toolTip font-danger"
                      href="javascript:;"
                      v-if="errors.first('variantPrice['+index+']')"
                      v-b-tooltip.hover.topleft
                      :title="errors.first('variantPrice['+index+']')"
                      >
                      <i class="fas fa-info-circle"></i>
                      </a>
                    
                  </div>
                </li>
                <li>
                  <input
                    type="text"
                    class="form-control"
                    :disabled="optionsFormFields.variantPublish[index] == false"
                    v-model="optionsFormFields.variantQuantity[index]"
                    placeholder="0"
                    :name="'variantQuantity['+index+']'"
                    v-validate="optionsFormFields.variantPublish[index] == true ? 'required|decimal:0|min_value:0' :''"
                    :data-vv-as="$t('LBL_QUANTITY')"
                    data-vv-validate-on="none"
                  />
                  <a
                      class="toolTip font-danger"
                      href="javascript:;"
                      v-if="errors.first('variantQuantity['+index+']')"
                      v-b-tooltip.hover.topleft
                      :title="errors.first('variantQuantity['+index+']')"
                      >
                      <i class="fas fa-info-circle"></i>
                      </a>
                </li>
                <li>
                  <input
                    type="text"
                    :disabled="optionsFormFields.variantPublish[index] == false"
                    class="form-control"
                    v-model="optionsFormFields.variantSKU[index]"
                    placeholder
                    :name="'SKU['+index+']'"
                    v-validate="optionsFormFields.variantPublish[index] == true ? 'required' :''"
                    :data-vv-as="$t('LBL_SKU')"
                    data-vv-validate-on="none"
                  />
                  <a
                      class="toolTip font-danger"
                      href="javascript:;"
                      v-if="errors.first('SKU['+index+']')"
                      v-b-tooltip.hover.topleft
                      :title="errors.first('SKU['+index+']')"
                      >
                      <i class="fas fa-info-circle"></i>
                      </a>  
                </li>
                <li>
                  <div class="d-flex justify-content-end">
                    <label class="switch switch--sm ml-1">
                      <input type="checkbox" v-model="optionsFormFields.variantPublish[index]" />
                      <span></span>
                    </label>

                    <div class="icon-group">
                      <button
                        type="button"
                        title="Copy to all"
                        @click="copyOptionValues(index)"
                        class="btn btn-light btn-sm btn-icon ml-1"
                        v-bind:class="[optionsFormFields.variantSKU[index] === '' || optionsFormFields.variantSKU[index] === undefined || optionsFormFields.variantQuantity[index] === '' || optionsFormFields.variantQuantity[index] === undefined || optionsFormFields.variantPrice[index] === '' || optionsFormFields.variantPrice[index] === undefined || copyVariant !== false ? 'disabled' : '']"
                        :disabled="optionsFormFields.variantSKU[index] === '' || optionsFormFields.variantSKU[index] === undefined || optionsFormFields.variantQuantity[index] === '' || optionsFormFields.variantQuantity[index] === undefined || optionsFormFields.variantPrice[index] === '' || optionsFormFields.variantPrice[index] === undefined || copyVariant !== false"
                      >
                        <i
                          class="fas fa-copy"
                          v-if="copyVariant === false || copyVariant !== index"
                        ></i>
                        <i class="fas fa-check" v-else></i>
                      </button>
                    </div>
                  </div>
                </li>
              </ul>
            </perfect-scrollbar>
            <div class="separator separator--border-dashed"></div>
            <div class="row">
              <label class="col-md-6 col-form-label">{{$t('LBL_SELECT_DEFAULT_VARIANT')}}</label>
              <div class="col-md-6">
                <select
                  class="form-control"
                  v-model="optionsFormFields.variantDefault"
                  name="default"
                  v-validate="'required'"
                  :data-vv-as="$t('LBL_DEFAULT')"
                  data-vv-validate-on="none"
                >
                  <option disabled value>Select</option>
                  <option
                    v-for="(variantInput,index) in variantInputs"
                    :key="'select'+index"
                    :value="index"
                    v-if="optionsFormFields.variantPublish[index] == true"
                  >
                    <span v-html="variantText(variantInput,index)"></span>
                  </option>
                </select>
                <span
                  v-if="errors.first('default')"
                  class="error text-danger"
                >{{ errors.first('default') }}</span>
              </div>
            </div>
          </div>
          <div class="form-group row" v-else>
            <div class="col-md-4">
              <label>
                {{$t('LBL_SELLING_PRICE')}}
                <span
                  v-if="priceIncTax == '1'"
                >({{$t('LBL_INCLUDING_TAX')}})</span>
                <span v-if="priceIncTax == '0'">({{$t('LBL_EXCLUDING_TAX')}})</span>
                <span class="required">*</span>
              </label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">{{currencySymbol}}</span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  @keypress="$onlyNumber"
                  v-model="adminsData.prod_price"
                  placeholder="200"
                  name="prod_price"
                  v-validate="'required|decimal:'+decimalValues+'|min_value:1'"
                  :data-vv-as="$t('LBL_SELLING_PRICE')"
                  data-vv-validate-on="none"
                />
                <span
                  v-if="errors.first('prod_price')"
                  class="error text-danger"
                >{{ errors.first('prod_price') }}</span>
              </div>
            </div>
            <div class="col-md-4">
              <label>
                {{$t('LBL_AVAILABLE_STOCK')}}
                <span class="required">*</span>
              </label>
              <input
                type="text"
                class="form-control"
                v-model="adminsData.prod_stock"
                placeholder="50"
                name="prod_stock"
                v-validate="'required|decimal:0|min_value:0'"
                :data-vv-as="$t('LBL_AVAILABLE_STOCK')"
                data-vv-validate-on="none"
              />
              <span
                v-if="errors.first('prod_stock')"
                class="error text-danger"
              >{{ errors.first('prod_stock') }}</span>
            </div>
            <div class="col-md-4">
              <label>{{$t('LBL_STOCK_KEEPING_UNIT')}}</label>
              <input
                type="text"
                class="form-control"
                v-model="adminsData.pc_sku"
                placeholder="25DS7"
              />
            </div>
          </div>
        </div>
      </div>

      <b-modal id="colorModel" centered title="BootstrapVue" size="sm">
        <template v-slot:modal-header="{ close }">
          <h5 class="modal-title">
            <span></span>
            {{ $t('LBL_SELECT') }} {{colorOption.text}} {{ $t('LBL_COLOR') }}
          </h5>
          <button type="button" class="close" @click="close()"></button>
        </template>
        <chrome-picker @input="updateFromPicker" :value="colorValue"></chrome-picker>
        <template v-slot:modal-footer>
          <button type="button" class="btn btn-brand" @click="applyColor">{{ $t('BTN_COLOR_APPLY')}}</button>
        </template>
      </b-modal>
    </div>
    <div class="form__actions">
      <button
        class="btn btn-secondary btn-wide"
        @click="previous()"
        type="button"
      >{{$t('BTN_PREVIOUS')}}</button>
      <button
        class="btn btn-brand btn-wide gb-btn gb-btn-primary ml-auto"
        @click="exitClicked=1;validateRecord();"
        :disabled="!isComplete || exitClicked==1"
        v-bind:class="exitClicked==1 && 'gb-is-loading' || !$canWrite('PRODUCTS')"
      >{{$t('BTN_SAVE_AND_EXIT')}}</button> &nbsp;
      <button
        class="btn btn-brand btn-wide gb-btn gb-btn-primary"
        data-f-a-tbitwizard-type="action-next"
        @click="clicked=1;validateRecord();"
        :disabled="!isComplete || clicked==1 || !$canWrite('PRODUCTS')"
        v-bind:class="clicked==1 && 'gb-is-loading'"
      >{{$t('BTN_NEXT')}}</button>
    </div>
  </form>
</template> 
<script>
import { Chrome } from "vue-color";
import VueTagsInput from "@johmun/vue-tags-input";

const optionsFields = {
  optionsValue: [],
  optionSelect: [],
  variantCode: [],
  variantPrice: [],
  variantQuantity: [],
  variantSKU: [],
  variantPublish: [],
  variantDefault: "",
  optionEnable: "1"
};
const tableFields = {
  prod_price: "",
  pc_sku: "",
  prod_stock: "",
  prod_type: "",
  prod_min_order_qty: 0
};
const colours = {
  prod_price: "",
  pc_sku: "",
  prod_stock: "",
  prod_type: "",
  prod_min_order_qty: 0
};
export default {
  props: ["prod_id"],
  components: {
    VueTagsInput,
    "chrome-picker": Chrome
  },
  data: function() {
    return {
      tab: "options",
      adminsData: tableFields,
      optiontag: false,
      colorModelId: "colorModel",
      baseUrl:baseUrl,
      optionsFormFields: optionsFields,
      optionInputs: [
        {
          optionsVal: []
        }
      ],
      colors: {
        hex: ""
      },
      defaultColors: {
        lightsalmon: "#FA8072",
        salmon: "#FFA07A",
        darksalmon: "#E9967A",
        lightcoral: "#F08080",
        indianred: "#CD5C5C",
        crimson: "#DC143C",
        firebrick: "#B22222",
        red: "#FF0000",
        darkred: "#8B0000",
        coral: "#FF7F50",
        tomato: "#FF6347",
        orangered: "#FF4500",
        gold: "#FFD700",
        orange: "#FFA500",
        darkorange: "#FF8C00",
        lightyellow: "#FFFFE0",
        lemonchiffon: "#FFFACD",
        lightgoldenrodyellow: "#FAFAD2",
        papayawhip: "#FFEFD5",
        moccasin: "#FFE4B5",
        peachpuff: "#FFDAB9",
        palegoldenrod: "#EEE8AA",
        khaki: "#F0E68C",
        darkkhaki: "#BDB76B",
        yellow: "#FFFF00",
        lawngreen: "#7CFC00",
        chartreuse: "#7FFF00",
        limegreen: "#32CD32",
        lime: "#00FF00",
        forestgreen: "#228B22",
        green: "#008000",
        darkgreen: "#006400",
        greenyellow: "#ADFF2F",
        yellowgreen: "#9ACD32",
        springgreen: "#00FF7F",
        mediumspringgreen: "#00FA9A",
        lightgreen: "#90EE90",
        palegreen: "#98FB98",
        darkseagreen: "#8FBC8F",
        mediumseagreen: "#3CB371",
        seagreen: "#2E8B57",
        olive: "#808000",
        darkolivegreen: "#556B2F",
        olivedrab: "#6B8E23",
        lightcyan: "#E0FFFF",
        cyan: "#00FFFF",
        aqua: "#00FFFF",
        aquamarine: "#7FFFD4",
        mediumaquamarine: "#66CDAA",
        paleturquoise: "#AFEEEE",
        turquoise: "#40E0D0",
        mediumturquoise: "#48D1CC",
        darkturquoise: "#00CED1",
        lightseagreen: "#20B2AA",
        cadetblue: "#5F9EA0",
        darkcyan: "#008B8B",
        teal: "#008080",
        powderblue: "#B0E0E6",
        lightblue: "#ADD8E6",
        lightskyblue: "#87CEFA",
        skyblue: "#87CEEB",
        deepskyblue: "#00BFFF",
        lightsteelblue: "#B0C4DE",
        dodgerblue: "#1E90FF",
        cornflowerblue: "#6495ED",
        steelblue: "#4682B4",
        royalblue: "#4169E1",
        blue: "#0000FF",
        mediumblue: "#0000CD",
        darkblue: "#00008B",
        navy: "#000080",
        midnightblue: "#191970",
        mediumslateblue: "#7B68EE",
        slateblue: "#6A5ACD",
        darkslateblue: "#483D8B",
        lavender: "#E6E6FA",
        thistle: "#D8BFD8",
        plum: "#DDA0DD",
        violet: "#EE82EE",
        orchid: "#DA70D6",
        fuchsia: "#FF00FF",
        magenta: "#FF00FF",
        mediumorchid: "#BA55D3",
        mediumpurple: "#9370DB",
        blueviolet: "#8A2BE2",
        darkviolet: "#9400D3",
        darkorchid: "#9932CC",
        darkmagenta: "#8B008B",
        purple: "#800080",
        indigo: "#4B0082",
        pink: "#FFC0CB",
        lightpink: "#FFB6C1",
        hotpink: "#FF69B4",
        deeppink: "#FF1493",
        palevioletred: "#DB7093",
        mediumvioletred: "#C71585",
        white: "#FFFFFF",
        snow: "#FFFAFA",
        honeydew: "#F0FFF0",
        mintcream: "#F5FFFA",
        azure: "#F0FFFF",
        aliceblue: "#F0F8FF",
        ghostwhite: "#F8F8FF",
        whitesmoke: "#F5F5F5",
        seashell: "#FFF5EE",
        beige: "#F5F5DC",
        oldlace: "#FDF5E6",
        floralwhite: "#FFFAF0",
        ivory: "#FFFFF0",
        antiquewhite: "#FAEBD7",
        linen: "#FAF0E6",
        lavenderblush: "#FFF0F5",
        mistyrose: "#FFE4E1",
        gainsboro: "#DCDCDC",
        lightgray: "#D3D3D3",
        silver: "#C0C0C0",
        darkgray: "#A9A9A9",
        gray: "#808080",
        dimgray: "#696969",
        lightslategray: "#778899",
        slategray: "#708090",
        darkslategray: "#2F4F4F",
        black: "#000000",
        cornsilk: "#FFF8DC",
        blanchedalmond: "#FFEBCD",
        bisque: "#FFE4C4",
        navajowhite: "#FFDEAD",
        wheat: "#F5DEB3",
        burlywood: "#DEB887",
        tan: "#D2B48C",
        rosybrown: "#BC8F8F",
        sandybrown: "#F4A460",
        goldenrod: "#DAA520",
        peru: "#CD853F",
        chocolate: "#D2691E",
        saddlebrown: "#8B4513",
        sienna: "#A0522D",
        brown: "#A52A2A",
        maroon: "#800000"
      },

      optionTags: [],
      colorValue: "",
      variantInputs: [],
      alloptions: [],
      optionColorArray: [],
      variantArray: [],
      variantTxt: [],
      colorOption: {},
      optionCount: 1,
      clicked: 0,
      decimalValues: decimalValues,
      exitClicked: 0,
      copyVariant: false,
      priceIncTax: "",
      currencySymbol: currencySymbol
    };
  },
  computed: {
    isComplete() {
      return (
        (this.optionsFormFields.optionEnable == 0 &&
          this.adminsData.prod_price &&
          this.adminsData.prod_stock) ||
        (this.optionsFormFields.optionEnable == 1 &&
        this.optionsFormFields.variantDefault === 0
          ? "not empty"
          : this.optionsFormFields.variantDefault)
      );
    }
  },
  methods: {
    onSwitchStatus: function(event) {
      let status = event.target.checked;
    },
    validateRecord: function() {
      let defaultVarient = this.optionsFormFields.variantDefault;
      if (this.optionsFormFields.variantPublish[defaultVarient] == false) {
        this.optionsFormFields.variantDefault = "";
      }
      this.$validator.validateAll().then(res => {
        if (res) {
          this.saveOptionsInfo();
        } else {
          this.exitClicked = this.clicked = 0;
        }
      });
    },
    saveOptionsInfo: function() {
      let formData = this.$serializeData(this.optionsFormFields);
      formData.append(
        "variantCode",
        JSON.stringify(this.optionsFormFields.variantCode)
      );
      formData.append("optionsValue", JSON.stringify(this.optionTags));
      formData.append("optionsColors", JSON.stringify(this.optionColorArray));
      formData.append("prod_id", this.prod_id);
      formData.append("prod_price", this.adminsData.prod_price);
      formData.append("prod_stock", this.adminsData.prod_stock);
      formData.append("pc_sku", this.adminsData.pc_sku);
      this.$http
        .post(adminBaseUrl + "/products/save/" + this.tab, formData)
        .then(
          response => {
            //success
            if (response.data.status == false) {
              this.clicked = 0;
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
            if (this.exitClicked == 1) {
              this.$router.push({
                name: "products"
              });
              return;
            }
            this.exitClicked = this.clicked = 0;
            this.$emit(
              "next",
              response.data.data.prod_id,
              "attributes",
              this.adminsData.prod_type
            );
          },
          response => {
            //error
            this.exitClicked = this.clicked = 0;
            this.validateErrors(response);
          }
        );
    },
    validateErrors: function(response) {
      let jsondata = response.data;
      Object.keys(jsondata.errors).forEach(key => {
        this.errors.add({
          field: key,
          msg: jsondata.errors[key][0]
        });
      });
    },
    previous: function() {
      this.$emit("previous", "price", this.adminsData.prod_type);
    },
    pageLoadData: function() {
      let prod_id = this.prod_id;
      this.$http
        .post(adminBaseUrl + "/products/page-load-data", {
          tab: this.tab,
          prod_id: prod_id
        })
        .then(response => {
          //success
          if (response.data.status == true) {
            let productResponse = response.data.data["editInfo"];
            this.priceIncTax = response.data.data["priceIncTax"];

            this.adminsData = productResponse.product;
            this.adminsData.prod_price = this.$displaynumberFormat(
              productResponse.product.prod_price
            );

            this.optionInputs[0].optionsVal = productResponse.alloptions;
              this.optionsFormFields.optionSelect[0] = "";
            this.alloptions = productResponse.alloptions;
            if (productResponse.options.length != 0) {
              this.optionInputs = [];

              this.optionInputs = productResponse.options;
              Object.keys(productResponse.options).forEach(key => {
                this.optionInputs[key]["optionsVal"] =
                  productResponse.alloptions;
                this.optionsFormFields.optionSelect[key] =
                  productResponse.options[key].poption_option_id;
                this.setOptionTags(
                  productResponse.options[key].poption_name,
                  key,
                  productResponse.options[key].poption_option_id
                );
              });

              this.variantInputs = this.cartesianTags(this.variantArray);
              let publishStatus = false;

              Object.keys(productResponse.optionVarients).forEach(key => {
                this.variantTxt[key] =
                  productResponse.optionVarients[key].pov_display_title;
                this.optionsFormFields.variantPrice[
                  key
                ] = this.$displaynumberFormat(
                  productResponse.optionVarients[key].pov_price
                );
                this.optionsFormFields.variantQuantity[key] =
                  productResponse.optionVarients[key].pov_quantity;
                this.optionsFormFields.variantSKU[key] =
                  productResponse.optionVarients[key].pov_sku;
                this.optionsFormFields.variantPublish[key] = publishStatus;
                if (productResponse.optionVarients[key].pov_publish == 1) {
                  this.optionsFormFields.variantPublish[key] = true;
                }

                if (productResponse.optionVarients[key].pov_default == 1) {
                  this.optionsFormFields.variantDefault = key;
                }
              });

              if (productResponse.optionColor.length > 0) {
                Object.keys(productResponse.optionColor).forEach(key => {
                  this.optionColorArray.push({
                    text: productResponse.optionColor[key].opn_value,
                    color: productResponse.optionColor[key].opn_color_code
                  });
                });
                let thisVal = this;
                setTimeout(function() {
                  thisVal.setBgColor();
                }, 500);
              }
            } else {
              this.optionsFormFields.optionEnable = 0;
            }
            this.progressBarUpdate(this.adminsData.prod_type);
          }
        });
    },
    setOptionTags: function(tags, key, optionId) {
      let tagsArray = tags.split(",");

      let editTagsArray = [];
      let variantEditArray = [];
      for (let x = 0; x < tagsArray.length; x++) {
        editTagsArray.push({
          text: tagsArray[x]
        });
        variantEditArray.push(optionId + "_" + tagsArray[x]);
      }

      this.variantArray.push(variantEditArray);
      this.optionTags.push(editTagsArray);
    },
    addOptions: function() {
     
      let thisVal = this.optionsFormFields;
      let selectOptions = this.alloptions;
      let OptionArray = [];

      Object.keys(selectOptions).forEach(key => {
        if (thisVal.optionSelect.indexOf(selectOptions[key].option_id) !== -1)
          return;
        OptionArray.push(selectOptions[key]);
      });

      this.optionInputs.push({
        optionsVal: OptionArray
      });
      this.optionsFormFields.optionSelect[this.optionInputs.length-1] = "";
     
    },
    deleteOptions: function(index) {
    let colorKey = '';
    if(this.optionTags[index]){
       Object.keys(this.optionTags[index]).forEach(key => {
         colorKey = this.inArrayKey(this.optionTags[index][key].text,this.optionColorArray)
            if (key !== false) {
                this.$delete(this.optionColorArray, colorKey);
              }
       });
    }  
      if (index == 0 && this.optionInputs.length == 1) {
        this.optionsFormFields.optionSelect[index] = "";
        this.optionsFormFields.optionsValue[index] = "";
      } else {
        this.$delete(this.optionsFormFields.optionSelect, index);
        this.$delete(this.optionInputs, index);
      }
     
      this.$delete(this.optionTags, index);
      this.addVariant();
    },
    validateTags: function(tags) {
      let tagsArray = [];
      Object.keys(tags).forEach(key => {
        tagsArray.push(tags[key].text.trim().toLowerCase());
      });
      var duplicateTags = tagsArray.filter(function(elem, index, self) {
        return index !== self.indexOf(elem);
      });

      if (duplicateTags.length != 0) {
        return tagsArray.length;
      }
      return true;
    },

    addVariant: function(tags = "") {
     
      let thisVal = this;
      thisVal.duplicateInput = false;
      let enteredTag = "";
      if (tags.length != 0 && typeof tags.length !== "undefined") {
        let tagResponse = thisVal.validateTags(tags);
        if (tagResponse != true) {
          this.$delete(tags, tagResponse - 1);
          toastr.error("You have entered duplicate input", "", toastr.options);
          return false;
        }
        enteredTag = tags[tags.length - 1].text;
      }

      setTimeout(function() {
        thisVal.variantArray = [];
        let count = 0;
        let key = "";
        $(".options").each(function() {
          let selectVal = $(this)
            .find("select")
            .val();
          let OptionIndex = $(this).data("id");
          if (
            selectVal == null ||
            selectVal == "" ||
            $(this).find("ul li.ti-valid span").length == 0
          ){
            return false;
          }
       
          thisVal.variantArray[OptionIndex] = [];
           thisVal.optionTags[count] = [];
          let tagsArray = [];
          $(this)
            .find("ul li.ti-valid")
            .each(function() {


               
              thisVal.variantArray[OptionIndex].push(
                selectVal +
                  "_" +
                  $(this)
                    .find("span")
                    .text()
              );
              tagsArray.push({
                text: $(this)
                  .find("span")
                  .text()
              });

              
              if (
                thisVal.optionTypeByValue(selectVal) == 1 &&
                $(this)
                  .find("span")
                  .text() == enteredTag
              ) {
              
                if (
                  thisVal.defaultColors[
                    enteredTag.replace(" ", "").toLowerCase()
                  ]
                ) {
                  key = thisVal.inArrayKey(
                    $(this)
                      .find("span")
                      .text(),
                    thisVal.optionColorArray
                  );
                  if (key !== false) {
                    thisVal.$delete(thisVal.optionColorArray, key);
                  }
                  thisVal.optionColorArray.push({
                    text: $(this)
                      .find("span")
                      .text(),
                    color:
                      thisVal.defaultColors[
                        enteredTag.replace(" ", "").toLowerCase()
                      ]
                  });
                  $(this)
                    .closest("li")
                    .css(
                      "background-color",
                      thisVal.defaultColors[
                        enteredTag.replace(" ", "").toLowerCase()
                      ]
                    );
                }
              }else if(thisVal.optionTypeByValue(selectVal) == 1) {


                  key = thisVal.inArrayKey(
                    $(this)
                      .find("span")
                      .text(),
                    thisVal.optionColorArray
                  );
                  let colorCode = '';
                    if(thisVal.optionColorArray[key]){
                      colorCode = thisVal.optionColorArray[key].text;
                    }
                   $(this)
                    .closest("li")
                    .css(
                      "background-color",
                      colorCode
                    );
              }

              
            });
          thisVal.optionTags[count] = tagsArray;
        
          count++;
        });
      
        thisVal.variantInputs = thisVal.cartesianTags(thisVal.variantArray);

        if (
          thisVal.optionsFormFields.variantPublish.length !=
          thisVal.variantInputs.length
        ) {
          for (
            let x = thisVal.optionsFormFields.variantPublish.length;
            x < thisVal.variantInputs.length;
            x++
          ) {
            thisVal.optionsFormFields.variantPublish[x] = true;
          }
        }

        thisVal.copyVariant = false;
         thisVal.$forceUpdate(); 
      }, 500);
    },
    cartesianTags: function(input) {
      let tagsArray = [];
      Object.keys(input).forEach(key => {
        if (input[key].length == 0) return;
        tagsArray.push(input[key]);
      });
      return tagsArray.reduce(
        function(a, b) {
          return a
            .map(function(x) {
              return b.map(function(y) {
                return x.concat(y);
              });
            })
            .reduce(function(a, b) {
              return a.concat(b);
            }, []);
        },
        [[]]
      );
    },

    progressBarUpdate: function(type) {
      let totalStep = 5;
      let bar = (100 / totalStep) * 2;

      if (type == 2) {
        totalStep = 6;
        bar = (100 / totalStep) * 2;
      }
      this.$emit("progressBar", bar, 3, totalStep);
    },
    variantText: function(txt, index) {
      if (typeof txt.length === "undefined") {
        return this.variantTxt[index];
      }

      let variantTxtMap = txt.map(function(x) {
        return x.split("_")[1];
      });
      let variantCode = txt.map(function(x) {
        return x;
      });
      this.optionsFormFields.variantCode[index] = variantCode;
      return variantTxtMap.join(" / ");
    },
    emptyFormValues: function() {
      this.optionsFormFields = {
        optionsValue: [],
        optionSelect: [],
        variantCode: [],
        variantPrice: [],
        variantQuantity: [],
        variantSKU: [],
        variantPublish: [],
        variantDefault: "",
        optionEnable: "1"
      };
      this.adminsData = {
        prod_price: "",
        pc_sku: "",
        prod_stock: "",
        prod_type: "",
        prod_min_order_qty: 0
      };
      this.errors.clear();
    },
    applyColor: function() {
      this.colorOption.color = this.colorValue;
      let key = this.inArrayKey(this.colorOption.text, this.optionColorArray);
      if (key !== false) {
        this.$delete(this.optionColorArray, key);
      }
      this.optionColorArray.push(this.colorOption);
      this.setBgColor();
    },
    setBgColor: function() {
      let thisVal = this;
      $(".vue-tags-input li .ti-content").each(function() {
        if (
          thisVal.optionTypeByValue(
            $(this)
              .closest("div.options")
              .find("select")
              .val()
          ) != 1
        ) {
          return;
        }
        let key = thisVal.inArrayKey(
          $(this)
            .find("span")
            .html(),
          thisVal.optionColorArray
        );
        if (key !== false) {
          $(this)
            .closest("li")
            .css("background-color", thisVal.optionColorArray[key].color);
        }
      });
      this.$bvModal.hide(this.colorModelId);
    },
    updateFromPicker: function(color) {
      this.colorValue = color.hex;
    },
    copyOptionValues: function(index) {
      let copiedPrice = this.optionsFormFields.variantPrice[index];
      let copiedQty = this.optionsFormFields.variantQuantity[index];
      let copiedSKU = this.optionsFormFields.variantSKU[index];
      for (var i = 0; i < this.variantInputs.length; i++) {
        if (
          (this.optionsFormFields.variantPrice[i] === "" ||
            this.optionsFormFields.variantPrice[i] === undefined) &&
          (this.optionsFormFields.variantQuantity[i] === "" ||
            this.optionsFormFields.variantQuantity[i] === undefined) &&
          (this.optionsFormFields.variantSKU[i] === "" ||
            this.optionsFormFields.variantSKU[i] === undefined)
        ) {
          this.optionsFormFields.variantPrice[i] = copiedPrice;
          this.optionsFormFields.variantQuantity[i] = copiedQty;
          this.optionsFormFields.variantSKU[i] = copiedSKU;
        }
      }
      this.copyVariant = index;
    },
    undoCopiedVariant: function() {
      let copiedPrice = this.optionsFormFields.variantPrice[this.copyVariant];
      let copiedQty = this.optionsFormFields.variantQuantity[this.copyVariant];
      let copiedSKU = this.optionsFormFields.variantSKU[this.copyVariant];
      for (var i = 0; i < this.variantInputs.length; i++) {
        if (
          this.optionsFormFields.variantPrice[i] === copiedPrice &&
          this.optionsFormFields.variantQuantity[i] === copiedQty &&
          this.optionsFormFields.variantSKU[i] === copiedSKU &&
          this.copyVariant != i
        ) {
          this.optionsFormFields.variantPrice[i] = "";
          this.optionsFormFields.variantQuantity[i] = "";
          this.optionsFormFields.variantSKU[i] = "";
        }
      }
      this.copyVariant = false;
    },
    openPickerPopup: function(txt) {
      this.colorOption = { text: txt, color: "" };
      this.$bvModal.show(this.colorModelId);
    },
    inArrayKey: function(needle, haystack) {
      var length = haystack.length;
      for (var i = 0; i < length; i++) {
        if (haystack[i].text == needle) return i;
      }
      return false;
    },
    optionTypeByValue: function(value) {
      var length = this.alloptions.length;
      for (var i = 0; i < length; i++) {
        if (this.alloptions[i].option_id == value)
          return this.alloptions[i].option_type;
      }
    },
    
  },

  mounted: function() {
    this.emptyFormValues();
    this.pageLoadData();
    let thisVal = this;
    $(document).on("click", ".vue-tags-input li .ti-content", function() {
      if (
        thisVal.optionTypeByValue(
          $(this)
            .closest("div.options")
            .find("select")
            .val()
        ) != 1
      ) {
        return false;
      }
      let key = thisVal.inArrayKey(
        $(this)
          .find("span")
          .html(),
        thisVal.optionColorArray
      );
      if (key !== false) {
        thisVal.colorValue = thisVal.optionColorArray[key].color;
      }
      thisVal.openPickerPopup(
        $(this)
          .find("span")
          .html()
      );
    });
  }
};
</script>
<style scoped>
.vc-chrome {
  position: static;
  box-shadow: 0 0 2px rgba(0, 0, 0, 0.3);
  margin: 0;
}
</style>