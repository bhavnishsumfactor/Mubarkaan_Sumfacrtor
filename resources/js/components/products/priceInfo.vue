<template>
   <form class="form" id="form" novalidate="novalidate" v-on:submit.prevent="validateRecord" v-bind:class="[(!$canWrite('PRODUCTS')) ? 'disabledbutton': '']">
      <div
         class="wizard-v2__content"
         data-f-a-tbitwizard-type="step-content"
         data-f-a-tbitwizard-state="current"
         >
         <div class="form__section form__section--first">
            <div class="wizard-v2__form">
               <div class="row justify-content-between">
                  <div class="col">
                     <div class="form-group">
                        <label>{{$t('LBL_TRACK_INVENTORY_FOR_PRODUCT')}}?</label>
                     </div>
                  </div>
                  <div class="col-auto">
                     <div class="form-group">
                        <div class="radio-inline">
                           <label class="radio">
                           <input
                              type="radio"
                              checked="checked"
                              :value="1"
                              v-model="adminsData.pc_threshold_stock_level"
                              name="radio4"
                              />
                           {{$t('LBL_YES')}}
                           <span></span>
                           </label>
                           <label class="radio">
                           <input
                              type="radio"
                              name="radio4"
                              :value="0"
                              v-model="adminsData.pc_threshold_stock_level"
                              />
                           {{$t('LBL_NO')}}
                           <span></span>
                           </label>
                        </div>
                     </div>
                  </div>
               </div>
               <div
                  class="row justify-content-between"
                  v-if="pickupEnable != 0 && adminsData.prod_type != 2"
                  >
                  <div class="col">
                     <div class="form-group">
                        <label>{{$t('LBL_SELL_PRODUCT_AS')}}?</label>
                     </div>
                  </div>
                  <div class="col-auto">
                     <div class="form-group">
                        <div class="radio-inline">
                           <label class="radio">
                           <input
                              type="radio"
                              :value="1"
                              v-model="adminsData.prod_self_pickup"
                              name="radiopickup"
                              />
                           {{$t('LBL_SHIPPING_ONLY')}}
                           <span></span>
                           </label>
                           <label class="radio">
                           <input
                              type="radio"
                              name="radiopickup"
                              :value="2"
                              v-model="adminsData.prod_self_pickup"
                              />
                           {{$t('LBL_PICKUP_ONLY')}}
                           <span></span>
                           </label>
                           <label class="radio">
                           <input
                              type="radio"
                              name="radiopickup"
                              :value="3"
                              v-model="adminsData.prod_self_pickup"
                              />
                           {{$t('LBL_BOTH')}}
                           <span></span>
                           </label>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row justify-content-between" v-if="adminsData.prod_type == 1">
                  <div class="col">
                     <div class="form-group">
                        <label>{{$t('LBL_CONTINUE_SELLING_WHEN_OUTOFSTOCK')}}</label>
                     </div>
                  </div>
                  <div class="col-auto">
                     <div class="form-group">
                        <div class="radio-inline">
                           <label class="radio">
                           <input
                              type="radio"
                              checked="checked"
                              :value="1"
                              v-model="adminsData.prod_stock_out_sellable"
                              name="radio5"
                              />
                           {{$t('LBL_YES')}}
                           <span></span>
                           </label>
                           <label class="radio">
                           <input
                              type="radio"
                              name="radio5"
                              :value="0"
                              v-model="adminsData.prod_stock_out_sellable"
                              />
                           {{$t('LBL_NO')}}
                           <span></span>
                           </label>
                        </div>
                     </div>
                  </div>
               </div>
               <div
                  class="row justify-content-between"
                  v-if="adminsData.prod_type != 2 && codEnable == 1"
                  >
                  <div class="col">
                     <div class="form-group">
                        <label>{{$t('LBL_AVAILABLE_FOR_COD')}}</label>
                     </div>
                  </div>
                  <div class="col-auto">
                     <div class="form-group">
                        <div class="radio-inline">
                           <label class="radio">
                           <input
                              type="radio"
                              checked="checked"
                              :value="1"
                              v-model="adminsData.prod_cod_available"
                              name="radio6"
                              />
                           {{$t('LBL_YES')}}
                           <span></span>
                           </label>
                           <label class="radio">
                           <input
                              type="radio"
                              name="radio6"
                              :value="0"
                              v-model="adminsData.prod_cod_available"
                              />
                           {{$t('LBL_NO')}}
                           <span></span>
                           </label>
                        </div>
                     </div>
                  </div>
               </div>
               <div
                  class="row justify-content-between"
                  v-if="adminsData.prod_type != 2 && giftWrapStatus == 1"
                  >
                  <div class="col">
                     <div class="form-group">
                        <label>{{$t('LBL_AVAILABLE_FOR_GIFT_WRAP')}}</label>
                     </div>
                  </div>
                  <div class="col-auto">
                     <div class="form-group">
                        <div class="radio-inline">
                           <label class="radio">
                           <input
                              type="radio"
                              checked="checked"
                              :value="1"
                              v-model="adminsData.pc_gift_wrap_available"
                              name="gift_wrap_radio"
                              />
                           {{$t('LBL_YES')}}
                           <span></span>
                           </label>
                           <label class="radio">
                           <input
                              type="radio"
                              name="gift_wrap_radio"
                              :value="0"
                              v-model="adminsData.pc_gift_wrap_available"
                              />
                           {{$t('LBL_NO')}}
                           <span></span>
                           </label>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-xl-6">
                     <div class="form-group">
                        <label>
                        {{$t('LBL_MINIMUM_PURCHASE_QUANTITY')}}
                        <span class="required">*</span>
                        </label>
                        <input
                           type="text"
                           class="form-control"
                           v-model="adminsData.prod_min_order_qty"
                           placeholder="1"
                           name="prod_min_order_qty"
                           v-validate="'required|decimal:0|min_value:1'"
                           :data-vv-as="$t('LBL_MINIMUM_PURCHASE_QUANTITY')"
                           data-vv-validate-on="none"
                           />
                        <span
                           v-if="errors.first('prod_min_order_qty')"
                           class="error text-danger"
                           >{{ errors.first('prod_min_order_qty') }}</span>
                     </div>
                  </div>
                  <div class="col-xl-6">
                     <div class="form-group">
                        <label>{{$t('LBL_MAXIMUM_PURCHASE_QUANTITY')}}</label>
                        <span class="required">*</span>
                        <input
                           type="text"
                           class="form-control"
                           v-model="adminsData.prod_max_order_qty"
                           name="prod_max_order_qty"
                           placeholder="1"
                           v-validate="'required|decimal:0|min_value:1'"
                           :data-vv-as="$t('LBL_MAXIMUM_PURCHASE_QUANTITY')"
                           data-vv-validate-on="none"
                           />
                        <span
                           v-if="errors.first('prod_max_order_qty')"
                           class="error text-danger"
                           >{{ errors.first('prod_max_order_qty') }}</span>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-xl-6">
                     <div class="form-group">
                        <label>{{$t('LBL_COST_PRICE')}}</label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text">{{currencySymbol}}</span>
                           </div>
                           <input
                              type="text"
                              class="form-control"
                              v-model="adminsData.prod_cost"
                              name="prod_cost"
                              @keypress="$onlyNumber"
                              v-validate="'decimal:'+decimalValues+'|min_value:1'"
                              placeholder="100"
                              :data-vv-as="$t('LBL_COST_PRICE')"
                              data-vv-validate-on="none"
                              />
                           <span
                              v-if="errors.first('prod_cost')"
                              class="error text-danger"
                              >{{ errors.first('prod_cost') }}</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-6" v-if="adminsData.pc_threshold_stock_level">
                     <div class="form-group">
                        <label>
                        {{$t('LBL_STOCK_ALERT_QUANTITY')}}
                        <span class="required">*</span>
                        </label>
                        <input
                           type="text"
                           class="form-control"
                           v-model="adminsData.pc_substract_stock"
                           name="pc_substract_stock"
                           v-validate="'required|decimal:0|min_value:1'"
                           :data-vv-as="$t('LBL_STOCK_ALERT_QUANTITY')"
                           data-vv-validate-on="none"
                           />
                        <span
                           v-if="errors.first('pc_substract_stock')"
                           class="error text-danger"
                           >{{ errors.first('pc_substract_stock') }}</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
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
            :disabled="!isComplete || exitClicked==1 || !$canWrite('PRODUCTS')"
            v-bind:class="exitClicked==1 && 'gb-is-loading'"
            >{{$t('BTN_SAVE_AND_EXIT')}}</button> &nbsp;
         <button
            class="btn btn-brand btn-wide gb-btn gb-btn-primary"
            data-f-a-tbitwizard-type="action-next"
            @click="clicked=1;validateRecord();"
            :disabled="!isComplete || clicked==1 || !$canWrite('PRODUCTS')"
            v-bind:class="clicked == 1 && 'gb-is-loading'"
            > {{ $t('BTN_NEXT') }} </button>
      </div>
   </form>
</template>
<script>
   
   const tableFields = {
     pc_threshold_stock_level: "1",
     prod_stock_out_sellable: "",
     prod_cod_available: "",
     pc_substract_stock: "",
     prod_cost: "",
     prod_min_order_qty: "",
     prod_max_order_qty: "",
     prod_self_pickup: "1",
     prod_type: "",
     pc_gift_wrap_available: 0
   };
   export default {
     props: ["prod_id"],
     data: function() {
       return {
         tab: "price",
         adminsData: tableFields,
         pickupEnable: 0,
         giftWrapStatus: 0,
         codEnable: 0,
         decimalValues: decimalValues,
         clicked: 0,
         exitClicked: 0,
         currencySymbol: currencySymbol,
         logs: []
       };
     },
     computed: {
       isComplete() {
         return (
           (this.adminsData.pc_threshold_stock_level == 0 ||
             (this.adminsData.pc_threshold_stock_level == 1 &&
               this.adminsData.pc_substract_stock)) &&
           this.adminsData.prod_min_order_qty && this.adminsData.prod_max_order_qty
         );
       }
     },
     methods: {
       validateRecord: function() {
         this.$validator.validateAll().then(res => {
           if (res) {
             this.savePriceInfo();
           } else {
             this.exitClicked = this.clicked = 0;
           }
         });
       },
       savePriceInfo: function() {
         let formData = this.$serializeData(this.adminsData);
         formData.append("prod_id", this.prod_id);
         this.$http
           .post(adminBaseUrl + "/products/save/" + this.tab, formData)
           .then(
             response => {
               //success
               if (response.data.status == false) {
                  this.exitClicked = this.clicked = 0;
                  toastr.error(response.data.message, "", toastr.options);
                  return;
               }
               toastr.success(response.data.message, "", toastr.options);
               if (this.exitClicked == 1) {
                 this.$router.push({ name: "products" });
                 return;
               }
               this.exitClicked = this.clicked = 0;
               this.$emit(
                 "next",
                 response.data.data.prod_id,
                 "options",
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
               let productResponse = response.data.data;
               this.adminsData = productResponse.editInfo;
               this.adminsData.prod_self_pickup = this.adminsData.prod_self_pickup != 0 ? this.adminsData.prod_self_pickup : 1 ;
               this.adminsData.prod_cost =
                 this.adminsData.prod_cost != null
                   ? this.adminsData.prod_cost
                   : "";
               this.pickupEnable = productResponse.pickupStatus;
               this.codEnable = productResponse.codStatus;
               this.giftWrapStatus = productResponse.giftWrapStatus;
               this.progressBarUpdate(this.adminsData.prod_type);
             }
           });
       },
       previous: function() {
         this.$emit("previous", "general", this.adminsData.prod_type);
       },
       progressBarUpdate: function(type) {
         let totalStep = 5;
         let bar = (100 / totalStep) * 1;
   
         if (type == 2) {
           totalStep = 6;
           bar = (100 / totalStep) * 1;
         }
         this.$emit("progressBar", bar, 2, totalStep);
       }
     },
     mounted: function() {
       this.pageLoadData();
     }
   };
</script>