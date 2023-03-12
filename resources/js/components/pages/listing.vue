<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
            <h3 class="subheader__title">{{ $t('LBL_PRODUCT_LISTING') }}</h3>
            <div class="subheader__breadcrumbs">
                <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                    <i class="flaticon2-shelter"></i>
                </router-link>
                <span class="subheader__breadcrumbs-separator"></span>
                <span class="subheader__breadcrumbs-link">{{$t('LBL_CMS')}}</span>
                <span class="subheader__breadcrumbs-separator"></span>
                <router-link :to="{name: 'pages'}" class="subheader__breadcrumbs-link">{{$t('LBL_PAGES')}}</router-link>
                <span class="subheader__breadcrumbs-separator"></span>
                <span class="subheader__breadcrumbs-link">{{$t('LBL_PRODUCT_LISTING')}}</span>
            </div>
        </div>
      </div>
    </div> 

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <!--begin::Portlet-->
          <div class="portlet portlet--height-fluid">
            
            <div class="portlet__body pb-0">
              <div class="form-group row">
                  <label class="col-md-6 col-form-label">{{ $t('LBL_DEFAULT_GRID_ON_LISTING') }}</label>
                  <div class="col-md-6">
                    <div class="radio-inline">
                      <label class="radio">
                        <input type="radio" :value="4" v-model="adminsData.LISTING_GRID_DEFAULT" name="LISTING_GRID_DEFAULT" @click="adminsData.LISTING_RECORDS_PER_PAGE = 12"> {{$t('LBL_4_COLUMN')}}<span></span>
                      </label>
                      <label class="radio">
                        <input type="radio" name="LISTING_GRID_DEFAULT" :value="5" v-model="adminsData.LISTING_GRID_DEFAULT" @click="adminsData.LISTING_RECORDS_PER_PAGE = 15"> {{$t('LBL_5_COLUMN')}}<span></span>
                      </label>
                    </div>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-md-6 col-form-label">{{ $t('LBL_DEFAULT_RECORDS_PER_PAGE') }}</label>
                  <div class="col-md-6">
                    <div class="radio-inline">
                      <div v-if="adminsData.LISTING_GRID_DEFAULT == 4"> 
                    
                      <label class="radio" v-for="(fourColumnData, fourIndex) in adminsData.four_column" :key="fourIndex">
                        <input type="radio" :value="fourColumnData" v-model="adminsData.LISTING_RECORDS_PER_PAGE" name="LISTING_RECORDS_PER_PAGE"> {{fourColumnData}}<span></span>
                      </label>
                     </div>
                     <div v-else>
                      <label class="radio" v-for="(fiveColumnData, fiveIndex) in adminsData.five_column" :key="fiveIndex">
                        <input type="radio" :value="fiveColumnData" v-model="adminsData.LISTING_RECORDS_PER_PAGE" name="LISTING_RECORDS_PER_PAGE"> {{fiveColumnData}}<span></span>
                      </label>
                      </div>
                     </div>
                  </div>
              </div>  
               <div class="form-group row">
                  <label class="col-md-6 col-form-label">{{ $t('LBL_ADD_BACKGROUND_COLOR_TO_IMAGES') }}
                     <a
                                class="toolTip font-danger"
                                href="javascript:;"
                              @click="showPopup()"
                                >
                                <i class="fas fa-info-circle"></i>

                                </a></label>
                  <div class="col-md-6">
                    <div class="radio-inline">
                      <label class="radio">
                        <input type="radio" :value="1" v-model="adminsData.BACKGROUND_COLOR_ENABLED" name="BACKGROUND_COLOR_ENABLED"> {{$t('LBL_YES')}}<span></span>
                      </label>
                      <label class="radio">
                        <input type="radio" name="BACKGROUND_COLOR_ENABLED" :value="0" v-model="adminsData.BACKGROUND_COLOR_ENABLED"> {{$t('LBL_NO')}}<span></span>
                      </label>
                    </div>
                  </div>
              </div>
              
            </div>
            <div class="portlet__foot">
              <div class="row">
                <div class="col">
                    <div class="portlet__head-toolbar">
                        <a href="javascript:;" @click="$router.go(-1)" class="btn btn-secondary btn-icon">{{$t('BTN_BACK')}} </a>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-brand gb-btn gb-btn-primary" @click="updateSettings" :disabled='clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'">{{$t('BTN_LISTING_PAGE_UPDATE')}}</button>
                </div>
              </div>
            </div>
          </div>
          <!--end::Portlet-->
        </div>
      </div>
    </div>
    <template>
  <b-modal
    id="viewDummyImagePopup"
    centered
    title="BootstrapVue"
    :hide-footer="true"
  >
    <template v-slot:modal-header="{ close }">
      <h5 class="modal-title">
        <span></span>
        {{ $t('LBL_FILLED_IMAGE_PREVIEW_POPUP_TITLE') }}
      </h5>
      <button type="button" class="close" @click="close()"></button>
    </template>

    <div class="row">
   
      <div class="col-md-6">
        <img :src="baseUrl+'/admin/images/fill-default.png'" alt="">
        <p  class="p-2 text-center" >{{ $t('LBL_DEFAULT_IMAGE_VIEW') }}</p>
      </div>
  <div class="col-md-6">
        <img :src="baseUrl+'/admin/images/fill-background.png'" alt="">
        <p class="p-2 text-center">{{ $t('LBL_IMAGE_VIEW_WITH_BACKGROUND_COLOR') }}</p>
      </div>

    </div>
  </b-modal>
</template>
  </div>
</template>
<script>

import VueColor from 'vue-color';
const tableFields = {
  'LISTING_GRID_DEFAULT': '',
  'LISTING_RECORDS_PER_PAGE': '',
  'BACKGROUND_COLOR_ENABLED': '',
  'four_column': {},
  'five_column': {}
};
export default {
  data: function() {
    return {
      baseUrl: baseUrl,
      clicked:0,
      adminsData: tableFields,
    };
  },
  methods: {
    showPopup() {
         this.$bvModal.show('viewDummyImagePopup');
    },
    
    getSettings: function() {
        let formData = this.$serializeData({'keys':[
          'LISTING_GRID_DEFAULT', 'LISTING_RECORDS_PER_PAGE', 'BACKGROUND_COLOR_ENABLED']});
        this.$http.post(adminBaseUrl + '/settings/get', formData).then((response) => {
            if (response.data.status == false) {
                toastr.error(response.data.message, '', toastr.options);
                return;
            }
            this.adminsData = response.data.data;
          
        });
    },
     updateSettings: function() {
        this.$validator.validateAll().then(res => {
            if (res) {
                this.clicked = 1;
                this.adminsData.LISTING_CARD_OVERLAY_OPACITY = (this.opacity / 100);
                let formData = this.$serializeData(this.adminsData);
                this.$http.post(adminBaseUrl + '/settings/basedonkeys', formData)
                    .then((response) => { //success
                        this.clicked = 0;
                        if (response.data.status == false) {
                          toastr.error(response.data.message, '', toastr.options);
                          return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                    }, (response) => { //error
                        this.clicked = 0;
                        this.validateErrors(response);
                    });
            } else {
                this.clicked = 0;
            }
        });
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
  },
  mounted: function() {
    this.getSettings();
  }
};
</script>