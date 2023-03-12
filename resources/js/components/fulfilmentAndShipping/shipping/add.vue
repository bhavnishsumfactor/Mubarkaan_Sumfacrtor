<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title" v-if="typeof $route.params.id != 'undefined'">{{ $t('LBL_EDITING') }} - {{editText}}</h3>
                    <h3 class="subheader__title" v-if="typeof $route.params.id == 'undefined'">{{ $t('LBL_NEW_SHIPPING_SETUP') }}</h3>                   
                    
                </div>   
                <div class="subheader__toolbar"><router-link :to="{name: 'shipping'}" class="btn btn-white"> {{$t('BTN_BACK')}}</router-link></div>             
            </div>
        </div> 

        <div class="container">
            <div class="row" id="manage-admins">
                <!-- begin:: Content -->
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <!--begin::Portlet-->
                            <div class="portlet portlet-no-min-height" v-if="defaultProfile == 0">
                                <div class="portlet__head">
                                    <div class="portlet__head-label">
                                        <h3 class="portlet__head-title">{{$t('LBL_SHIPPING_PROFILE_NAME')}}</h3>
                                    </div>
                                    <div class="portlet__head-toolbar">
                                        <div class="portlet__head-actions">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet__body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-0">
                                                <input type="text" v-model="profileZoneTableData.sprofile_name" @blur="validateProfileRecord" class="form-control" placeholder="Fragile products" name="sprofile_name" v-validate="'required'" data-vv-validate-on="none" :data-vv-as=" $t('LBL_PROFILE_NAME')">
                                                <span v-if="errors.first('sprofile_name')" class="error text-danger">{{ errors.first('sprofile_name') }}</span>
                                                <span class="form-text text-muted">{{$t('LBL_CUSTOMERS_WONT_SEE_THIS')}}.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--begin::Portlet -->
                            <div class="portlet portlet-no-min-height" v-if="defaultProfile == 0">
                                <div class="portlet__head">
                                    <div class="portlet__head-label">
                                        <h3 class="portlet__head-title">{{$t('LBL_PRODUCTS_IN')}} {{editText}} {{$t('LBL_PROFILE')}}</h3>
                                    </div>
                                    <div class="portlet__head-toolbar">
                                        <div class="portlet__head-actions">
                                            <a class="link" href="javascript:void(0)" @click="searchProducts()"> {{$t('LNK_ADD_PRODUCTS')}}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet__body">
                                    <div class="">
                                        <div class="text-center p-4" v-if="selectedProducts.length <= 0">
                                            <div class="h4">{{$t('LBL_NO_PRODUCTS')}}</div>
                                            <span class="">{{$t('LBL_MOVE_PRODUCTS_TO_SETUP_RATES')}}.</span>
                                        </div>
                                        <div class="shipping-products">
                                       <ul class="list-group list-group-triple">
                                            <li  class="list-group-item" v-for="product in selectedProducts.slice(0, 8)" :key="'selectedproducts'+product.prod_id">
                                            <div class="product-profile">
                                                <div class="product-profile__thumbnail"><img :title="product.prod_name" :src="baseUrl+'/yokart/image/productImages/'+product.prod_id+'/0/50-67'" alt="" width="50"></div>
                                                <div class="product-profile__data">
                                                   <div class="title"><span class="text-body" :title="product.prod_name"> {{product.prod_name}} </span></div></div>
                                            </div>
                                            <button class="btn btn-icon btn-sm remove" @click="removeProduct(profileZoneTableData.sprofile_id, product.prod_id)"><i class="fas fa-times"></i></button>
                                            </li>
                                            <li  class="list-group-item" v-if="selectedProducts.length > 8">
                                               <div class="text-center py-2"><a class="links" @click="viewMoreProducts()" href="javascript:;">{{$t('LNK_VIEW_MORE')}}</a></div>
                                            </li>
                                        </ul> 
                                        </div>                                    
                                    </div>
                                </div>
                            </div>
                            <!--end::Portlet -->
                            <div class="portlet portlet-no-min-height">
                                <div class="portlet__head">
                                    <div class="portlet__head-label">
                                        <h3 class="portlet__head-title">{{$t('LBL_SHIPPING_FROM')}}</h3>
                                    </div>
                                    <div class="portlet__head-toolbar">
                                        <div class="portlet__head-actions">
                                        </div>
                                    </div>
                                </div>
                                 <div class="portlet__body">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto mr-3">
                                                    <button class="btn btn-secondary btn-elevate btn-icon"><i class="fas fa-map-marker-alt"></i></button>
                                                </div>
                                                <div class="col-auto">
                                                    <h6 class="font-bold">{{ businessDetails.BUSINESS_NAME }}</h6>
                                                    <p class="mb-0">{{ businessDetails.BUSINESS_ADDRESS1 ? businessDetails.BUSINESS_ADDRESS1+ ',' : ' '}} {{ businessDetails.BUSINESS_ADDRESS2 ? businessDetails.BUSINESS_ADDRESS2+ ',' : ' '}} {{ businessDetails.BUSINESS_STATE ? businessDetails.BUSINESS_STATE+ ',' : ' '}} {{ businessDetails.BUSINESS_COUNTRY ? businessDetails.BUSINESS_COUNTRY : '' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="portlet portlet-no-min-height">
                                <div class="portlet__head ">
                                    <div class="portlet__head-label">
                                        <h3 class="portlet__head-title">{{$t('LBL_SHIPPING_TO')}}</h3>
                                    </div>
                                    <div class="portlet__head-toolbar">
                                        <div class="portlet__head-actions">
                                             <a  href="javascript:void(0)" @click="addShippingZone()" class="link">{{ $t('LBL_CREATE_ZONE') }}</a>
                                        </div>
                                    </div>
                                </div>                                     
                                <div class="portlet__body p-0">
                                    <div class="shipping-to-list" v-for="(zoneRecord, key) in zoneRecords" :key="'zonerecord'+key">
                                        <div class="row justify-content-between mb-4">
                                            <div class="col">
                                                <div class="row no-gutters">
                                                    <div class="col-auto mr-3">
                                                        <button class="btn btn-secondary btn-elevate btn-icon"><i class="fas fa-globe"></i></button>
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="font-bold">{{zoneRecord.spzone_name}}</h6>
                                                        <p class="mb-0" v-if="zoneRecord.spzone_shipping_type == 1">{{$t('LBL_REST_OF_WORLD')}}</p>
                                                        <ul class="list-countries" v-else> 
                                                            <li v-for="location in zoneRecord.locations_grouped.slice(0, 3)">
                                                                <i class="icn-flag">
                                                                    <img :src="baseUrl + '/flags/'+location.country.country_code.toLowerCase()+'.svg'" alt="" width="20px">
                                                                </i> {{location.country.country_name}}</li>
                                                            <li v-if="zoneRecord.locations_grouped.length > 3">
                                                                <a href="javascript:;" class="links" @click="viewMoreLocations(zoneRecord.spzone_id)">{{zoneRecord.locations_grouped.length}}+</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="dropdown">
                                                    <a data-toggle="dropdown" class="btn btn-light btn-sm btn-icon " aria-expanded="">
                                                        <i class="fas fa-ellipsis-h"></i> </a>
                                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                                        <a href="javascript:;"  @click="viewMoreLocations(zoneRecord.spzone_id)" class="dropdown-item">{{$t('LNK_EDIT_ZONE')}}</a>
                                                        <a href="javascript:;" @click="confirmDelete(zoneRecord.spzone_id,'zone')" class="dropdown-item">{{$t('LNK_DELETE')}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{$t('LBL_RATE_NAME')}}</th>
                                                    <th>{{$t('LBL_CONDITIONS')}}</th>
                                                    <th>{{$t('LBL_RATE_COST')}}</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(rate, key) in zoneRecord.rates" :key="'rate'+key">
                                                    <td>{{rate.srate_name}}</td>
                                                    <td v-if="rate.srate_type == 0">—</td>
                                                    <td v-else>{{rate.srate_min_value}}<span v-if="rate.srate_type == 1">{{$t('LBL_KG')}}</span> - {{rate.srate_max_value}} <span v-if="rate.srate_type == 1">{{$t('LBL_KG')}}</span></td>
                                                    <td v-if="rate.srate_cost == 0">{{$t('LBL_FREE')}}</td>
                                                    <td v-else>{{rate.srate_cost}}</td>
                                                    <td>
                                                        <div class="d-flex">
                                                        <div class="dropdown">
                                                            <a data-toggle="dropdown" class="btn btn-light btn-sm btn-icon " aria-expanded="">
                                                                <i class="fas fa-ellipsis-h"></i> </a>
                                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                                                <a href="javascript:;" @click="editRateRecord(rate)" class="dropdown-item">{{$t('LNK_EDIT_RATE')}}</a>
                                                                <a href="javascript:;" @click="confirmDelete(rate.srate_id,'rate')" class="dropdown-item">{{$t('LNK_DELETE')}}</a>
                                                            </div>
                                                        </div>
                                                        <a href="javascript:;" @click="zoneRate(zoneRecord.spzone_id)" class="btn btn-sm btn-light btn-icon ml-2" v-if="key == zoneRecord.rates.length -1 ">
                                                            <i class="fas fa-plus"></i>
                                                            
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr v-if=" zoneRecord.rates.length == 0">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <a href="javascript:;" @click="zoneRate(zoneRecord.spzone_id)" class="btn btn-sm btn-light btn-icon ml-2">
                                                            <i class="fas fa-plus"></i>
                                                            
                                                            </a>
                                                    
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center p-4" v-if="zoneRecords.length <= 0">
                                        <div class="h4"> {{  $t('LBL_NO_SHIPPING_ZONES') }}   </div>
                                        <span class="">{{  $t('LBL_PLEASE_CREATE_SHIPPING_ZONE') }}!</span>
                                    </div>
                                </div>
                            </div>
                            <!--end::Portlet-->
                        </div>
                    </div>
                </div>
                <shipping-products :productsRecords="productsRecords" :selectedProductsIds="selectedProductsIds" :baseUrl="baseUrl" :pagination="pagination" @saveProfileData="saveProfileData" @searchProducts="searchProducts"></shipping-products>

                <b-modal id="shippingZonePopup" centered title="BootstrapVue">
                
                    <template v-slot:modal-header>
                        <h5 class="modal-title">
                            <span>{{$t('LBL_CREATE_ZONE') }}</span>
                        </h5>
                        <button type="button" class="close" @click="$bvModal.hide('shippingZonePopup')"></button>
                    </template>
                    <div class="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{$t('LBL_ZONE_NAME') }} </label>
                                    <input type="text" class="form-control" v-model="profileZoneData.spzone_name" name="spzone_name" v-validate="'required'" data-vv-validate-on="none" :data-vv-as="$t('LBL_ZONE_NAME')">
                                    <span v-if="errors.first('spzone_name')" class="error text-danger">{{ errors.first('spzone_name') }}</span>
                                    <span class="form-text text-muted">{{$t('LBL_CUSTOMERS_WONT_SEE_THIS') }}.</span>
                                    <input type="text" v-model="triggerupdate" style="display:none;">
                                </div>
                            </div>
                        </div>
                        <perfect-scrollbar :options="{suppressScrollX: true}" style="height:400px">
                            <div class="accordion-country">
                                <ul class="no-list" v-bind:class="profileZoneData.spzone_name == '' && 'disabled'">
                                    <li>
                                        <div class="row">
                                            <div class="col"><label class="checkbox  checkbox--brand">
                                                <input type="checkbox" :value="1" :disabled="profileZoneData.spzone_name == '' || restWord == 1" class="js-restofWordCheckbox" v-model="profileZoneData.spzone_shipping_type">{{$t('LBL_REST_OF_WORLD') }} <span></span>
                                            </label></div>
                                        </div>
                                    </li>                        
                                    <li v-for="(zoneRegion, zoneRegionId) in zoneRegions" v-if="zoneRegion != null" :data-regionid="'zoneRegion_'+zoneRegionId" class="js-region YK-regionlist" :key="'zoneRegion_'+zoneRegionId" >
                                        <div class="row" v-if="zoneRegion != null">
                                            <div class="col"><label class="checkbox checkbox--brand">
                                                <input type="checkbox" :value="zoneRegionId" v-model="selectedRegions" :disabled="profileZoneData.spzone_name == ''" class="js-parentCheckbox js-regionCheckbox" :data-regionid="'zoneRegion_'+zoneRegionId"> {{zoneRegion.region_name}}<span></span>
                                            </label></div>
                                            <div class="col-auto">
                                                <a href="javascript:;" class="YK-toggleCountry" @click="getCountriesForZone(zoneRegionId)" v-bind:class="profileZoneData.spzone_name == '' && 'd-none'"><i class="fas fa-chevron-down"></i></a>
                                            </div>
                                        </div>
                                        <ul class="no-list YK-countrieslist" v-bind:class="zoneRegions[zoneRegionId].countries.length == 0 && 'd-none'" v-if="zoneRegions[zoneRegionId].countries.length > 0">
                                            <li v-for="(zoneCountry, countryIndex) in zoneRegions[zoneRegionId].countries" :data-id="zoneRegionId + '-' + zoneCountry.country_id" :key="'zoneCountry_'+countryIndex" v-if="zoneCountry != null" class="js-countryListing YK-countryList" >
                                               
                                                <div class="row justify-content-between align-items-center">
                                                    <div class="col-auto">
                                                        <label class="checkbox checkbox--brand">
                                                            <input type="checkbox" v-model="selectedCountries" :disabled="profileZoneData.spzone_name == ''" :data-id="zoneRegionId + '-' + zoneCountry.country_id" :value="zoneRegionId + '-' + zoneCountry.country_id" 
                                                            :checked="zoneRegionId + '-' + zoneCountry.country_id" 
                                                            :data-show-selected="zoneRegions[zoneRegionId].countries[countryIndex].states.length > 0 ? 1 : 0" :data-states-count="zoneCountry.states_count" class="js-parentCheckbox js-countryCheckbox"
                                                            ><i class="icn-flag">
                                                                <img :src="baseUrl + '/flags/'+zoneCountry.country_code.toLowerCase()+'.svg'" alt="">
                                                            </i>{{zoneCountry.country_name}} <span></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="javascript:;" class="js-stateCount YK-toggleStates" @click="getStatesForZone(zoneRegionId, countryIndex,$event)"><span>{{zoneCountry.selected == true ? zoneCountry.states_count :'0'}}</span> of {{zoneCountry.states_count}} <i class="fas fa-chevron-down"></i></a>
                                                    </div>
                                                </div>
                                                <ul class="no-list YK-statesList" v-bind:class="zoneRegions[zoneRegionId].countries[countryIndex].states.length == 0 && 'd-none'" v-if="zoneRegions[zoneRegionId].countries[countryIndex].states.length > 0">
                                                    <li v-for="(zoneStates, stateIndex) in zoneCountry.states" :key="'zoneState_'+stateIndex" v-if="zoneStates != null">
                                                        <label class="checkbox checkbox--brand">
                                                        <input type="checkbox" :disabled="profileZoneData.spzone_name == ''" v-model="selectedStates" :value="zoneRegionId + '-' + zoneCountry.country_id+'-'+zoneStates.state_id" class="js-statesCheckbox">{{zoneStates.state_name}}<span></span>
                                                    </label>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>

                                </ul>
                            </div>
                        </perfect-scrollbar>
                    </div>
                    <template v-slot:modal-footer>
                        <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" :disabled="!isComplete || clicked==1" v-bind:class="clicked==1 && 'gb-is-loading'" @click="validateZoneRecord()"><span>{{$t('BTN_ADD_ZONE') }}</span></button>
                    </template>
                
                </b-modal>

                <shipping-rate :rateTableData="rateTableData" :clicked="clicked" :currencySymbol="currencySymbol" :weightType="weightType" :rateConditions="rateConditions" @saveProfileData="saveProfileData"></shipping-rate>

                <DeleteModel :deletePopText="$t('Are you sure you want to delete this shipping zone?')" :recordId="recordId" @deleted="deleteRecord(recordId)"></DeleteModel>

                <b-modal id="viewMoreLocations" size="lg" centered title="BootstrapVue" hide-footer>
                    <template v-slot:modal-header>
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{viewMorelocations.zone.spzone_name}}
                        </h5>
                        <button type="button" class="close" @click="$bvModal.hide('viewMoreLocations')"></button>
                    </template>
                    <perfect-scrollbar :options="{suppressScrollX: true}" style="height:500px">
                        <div class="" style="position: sticky;top: 0;background: white;z-index: 1;">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="">{{$t('LBL_REGION')}}</label>
                                        <select v-model="zoneLocationData.region_id" :disabled="regions.length==0" @change="getCountries($event)" class="input-edited form-control" :name="'region_id'" v-validate="'required'" :data-vv-as="$t('LBL_REGION')" data-vv-validate-on="none">
                                            <option disabled value="">{{$t('LBL_SELECT_REGION')}}</option>
                                            <option v-for="(region, key) in regions" :value="key" :key="key">
                                                {{region}}
                                            </option>
                                        </select>
                                        <span v-if="errors.first('region_id')" class="error text-danger">{{ errors.first('region_id') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="">{{$t('LBL_COUNTRY')}}</label>
                                        <select v-model="zoneLocationData.country_id" :disabled="countries.length==0" @change="getStates($event)" class="input-edited form-control" :name="'country_id'" v-validate="'required'" :data-vv-as="$t('LBL_COUNTRY')" data-vv-validate-on="none">
                                            <option disabled value="">{{$t('LBL_SELECT_COUNTRY')}}</option>
                                            <option v-for="(country, key) in countries" :value="key" :key="key">
                                                {{country}}
                                            </option>
                                        </select>
                                        <span v-if="errors.first('country_id')" class="error text-danger">{{ errors.first('country_id') }}</span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="example-text-input" class="">{{$t('LBL_STATE')}}</label>
                                        <treeselect :disabled="states.length==0" v-model="zoneLocationData.state_id" :multiple="true" :value-consists-of="'LEAF_PRIORITY'" :defaultExpandLevel="Infinity" :clearable="false" :isDefaultExpanded="true" :options="states" :searchable="true" :open-on-click="true" :close-on-select="true"  placeholder="Select some" v-validate="'required'" :data-vv-as="$t('LBL_STATE')" data-vv-validate-on="none" />
                                    </div>
                                </div>
                                <div class="col-auto">
                                     <div class="form-group">
                                        <div><label for="example-text-input" class="">&nbsp;</label></div>
                                        <button type="submit" :disabled="!canAddZoneLocation || clicked==1" class="btn btn-wide btn-brand gb-btn gb-btn-primary " @click="saveZoneLocations()" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_ADD') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <ul class="list-group">
                       <li class="list-group-item" v-for="location in viewMorelocations.locations">
                            <h6 class="font-bold mb-3">
                                <i class="icn-flag">
                                    <img :src="baseUrl + '/flags/'+location.country.country_code.toLowerCase()+'.svg'" alt="" width="20px">
                                </i>
                                {{location.country.country_name}}
                                <button class="btn btn-icon btn-sm remove" @click="removeLocation('country', viewMorelocations.zone.spzone_id, location.country.country_id)"><i class="fas fa-times"></i></button>
                            </h6>
                            <div class="tags-wrapper mb-0">
                                <span class="tag" v-for="(state, key) in location.states"> {{state.states.state_name}}
                                    <button class="btn btn-icon btn-sm remove" @click="removeLocation('state', viewMorelocations.zone.spzone_id, state.states.state_id)"><i class="fas fa-times"></i></button>
                                </span>
                            </div>
                        </li>
                    </ul>
                    </perfect-scrollbar>
                </b-modal>

                <b-modal id="viewMoreProducts" size="lg" centered title="BootstrapVue" hide-footer>
                    <template v-slot:modal-header>
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{$t('LBL_PRODUCTS_IN')}} {{editText}} {{$t('LBL_PROFILE')}}
                        </h5>
                        <button type="button" class="close" @click="$bvModal.hide('viewMoreProducts')"></button>
                    </template>
                    <perfect-scrollbar style="height:400px">
                        <div class="shipping-products">
                            <ul class="list-group list-group-triple">
                                <li  class="list-group-item" v-for="product in selectedProducts" :key="'selectedproducts'+product.prod_id">
                                    <div class="product-profile">
                                        <div class="product-profile__thumbnail">
                                            <img :title="product.prod_name" :src="baseUrl+'/yokart/image/productImages/'+product.prod_id+'/0/50-67'" alt="" width="50"></div>
                                        <div class="product-profile__data">
                                            <div class="title"><span class="text-body" :title="product.prod_name"> {{product.prod_name}}  </span></div></div>
                                    </div>  
                                    <button class="btn btn-icon btn-sm remove" @click="removeProduct(profileZoneTableData.sprofile_id, product.prod_id)"><i class="fas fa-times"></i></button>                          
                                </li>
                            </ul>
                        </div>
                    </perfect-scrollbar>
                </b-modal>

            </div>
        </div>
    </div>
</template>
<script>
    import {
        BModal,
        VBModal
    } from 'bootstrap-vue';
    import shippingProducts from './shippingProductsPopup';
    import shippingRate from './shippingRatePopup';
    import Treeselect from '@riophae/vue-treeselect';
    import '@riophae/vue-treeselect/dist/vue-treeselect.css';
    const profileZoneFields = {
        'sprofile_name': '',
        'sprofile_id': '',
        'spzone_name': '',
        'spzone_shipping_type': '',
        'spzone_id': ''
    };
    const rateFields = {
        'srate_id': '',
        'shipRateCondistion': false,
        'srate_spzone_id': '',
        'srate_type': '1',
        'srate_name': '',
        'srate_cost': '',
        'srate_min_value': '',
        'srate_max_value': '',
        'profile_type': ''
    };
    export default {
        components: {
            shippingProducts,
            shippingRate,
            Treeselect
        },
        provide() {
            return {
                $validator: this.$validator,
            }
        },
        computed: {
            canAddZoneLocation() {
                return (this.zoneLocationData.region_id && this.zoneLocationData.country_id);
            },
            isComplete() {
                return (
                    this.profileZoneData.spzone_name
                );
            }
        },
        data: function() {
            return {
                records: [],
                selectedRegions: [],
                selectedCountries: [],
                selectedStates: [],
                baseUrl: baseUrl,
                searchProduct: '',
                triggerupdate: 1,
                packageRecords: [],
                profilesData: [],
                selectedProducts: [],
                selectedProductsIds: [],
                viewMorelocations: {
                    'zone' : {},
                    'locations' : []
                },
                zoneLocationData: {
                    'spzone_id' : '',
                    'region_id' : '',
                    'country_id' : '',
                    'state_id' : null
                },
                clicked: 0,
                regions: [],
                countries: [],
                states: [],
                dimensionsData: [],
                zoneRecords: [],
                zoneRecordsAll: [],
                rateConditions: [],
                counrtyStateData: [],
                productsRecords: [],
                defaultProfile: 0,
                baseUrl: baseUrl,
                profileZoneTableData: profileZoneFields,
                rateTableData: rateFields,
                productsModelId: 'shippingProductsPopup',
                zoneModelId: 'shippingZonePopup',
                rateModelId: 'shippingRatePopup',
                editText: '',
                weightType: '',
                currencySymbol: currencySymbol,
                deleteType: '',
                recordId: '',
                 modelId: 'deleteModel',
                pagination: [],
                businessDetails: [],
                profileZoneData: {
                    'spzone_shipping_type': '',
                    'spzone_id': '',
                    'spzone_name': ''
                },
                zoneRegions: [],
                restWord: ''
            }
        },
        methods: {
            getCountriesForZone: function(regionId) {
                let thisObj = this;

                if(thisObj.zoneRegions[regionId].countries.length > 0){
                    return;
                }
                let formData = this.$serializeData({
                    'region_id': regionId
                });
                let countriesSelected = false;
                if(this.$inArray(regionId, this.selectedRegions) == true){
                    countriesSelected = true;
                }
      
                this.$http.post(adminBaseUrl + '/shipping-zone/get-countries-data', formData)
                    .then((response) => { 
                        if (response.data.status == true) {
                            for (var i = 0; i < response.data.data.length; i++) {
                                thisObj.zoneRegions[regionId].countries[response.data.data[i].country_id] = { country_id: response.data.data[i].country_id, country_name: response.data.data[i].country_name, country_code: response.data.data[i].country_code, states_count: response.data.data[i].states_count, selected:countriesSelected, states: []};

                                if(countriesSelected == true){
                                        thisObj.selectedCountries.push(regionId + '-' + response.data.data[i].country_id);
                                    }
                            }
                            thisObj.triggerupdate += 1;
                            
                        }
                     
                        
                        
                    });
            },
            getStatesForZone: function(regionId, countryId, event) {
                let thisObj = this;
                let thisJquery = $(this);
                let formData = this.$serializeData({
                    'country_id': countryId
                });
               
                if(thisObj.zoneRegions[regionId].countries[countryId].states.length > 0){
                    return;
                }

                let statesSelected = false;
                if(thisObj.$inArray(regionId + '-' + countryId, thisObj.selectedCountries) == true){
                    statesSelected = true;
                }
                this.$http.post(adminBaseUrl + '/shipping-zone/get-states-data', formData)
                    .then((response) => { 
                        if (response.data.status == true) {
                            for (var i = 0; i < response.data.data.length; i++) {
                                thisObj.zoneRegions[regionId].countries[countryId].states[response.data.data[i].state_id] = { state_id: response.data.data[i].state_id, state_name: response.data.data[i].state_name};

                                if(statesSelected == true){
                                    thisObj.selectedStates.push(regionId + '-' + countryId + '-' + response.data.data[i].state_id);
                                }
                            }
                            thisObj.triggerupdate += 1;
                        }
                    });
            },
            viewMoreProducts: function() {
                this.$bvModal.show('viewMoreProducts');
            },
            viewMoreLocations: function(spzone_id) {
                this.viewMorelocations = [];
                this.zoneLocationData.region_id = '';
                this.zoneLocationData.country_id = '';
                this.zoneLocationData.state_id = null;
                this.countries = [];
                this.states = [];
                this.$http.get(adminBaseUrl + '/shipping/view-more-locations/' + spzone_id)
                    .then((response) => { //success
                        this.viewMorelocations = response.data.data.locations;
                        this.zoneLocationData.spzone_id = this.viewMorelocations.zone.spzone_id;
                        this.regions = response.data.data.regions;
                        this.$bvModal.show('viewMoreLocations');
                    });
            },
            removeLocation: function(type, spzone_id, record_id) { 
                let formData = this.$serializeData({
                    'type': type,
                    'spzone_id': spzone_id,
                    'record_id': record_id
                });               
                this.$http.post(adminBaseUrl + '/shipping/zone-location/remove', formData)
                    .then((response) => { //success
                        toastr.success(response.data.message, '', toastr.options);
                        this.viewMorelocations = response.data.data;
                        this.pageLoadData();
                    });
            },
            removeProduct: function(sprofile_id, prod_id) { 
                let formData = this.$serializeData({
                    'sprofile_id': sprofile_id,
                    'prod_id': prod_id
                });               
                this.$http.post(adminBaseUrl + '/shipping/products/remove', formData)
                    .then((response) => { //success
                        toastr.success(response.data.message, '', toastr.options);
                        this.pageLoadData();
                    });
            },
            getCountries: function(event) {
                let formData = this.$serializeData({
                    'region_id': event.target.value,
                    'sprofile_id': this.profileZoneTableData.sprofile_id
                });
                this.countries = [];
                this.$http.post(adminBaseUrl + '/shipping-zone/get-countries', formData)
                    .then((response) => { 
                        if (response.data.status == true) {
                            this.countries = response.data.data;
                            this.states = [];
                            this.zoneLocationData.state_id = '';
                        }
                    });
            },
            getStates: function(event) {
                this.states = [];
                let formData = this.$serializeData({
                    'country_id': event.target.value,
                    'sprofile_id': this.profileZoneTableData.sprofile_id
                });
                this.$http.post(adminBaseUrl + '/shipping-zone/get-states', formData)
                    .then((response) => { 
                        if (response.data.status == true) {
                            Object.keys(response.data.data).forEach(key => {
                                this.states.push({
                                    id: key,
                                    label: response.data.data[key]
                                });
                            });
                        }
                    });
            },
            saveZoneLocations: function() {
                this.clicked = 1;
                let formData = this.$serializeData({
                    'spzone_id': this.zoneLocationData.spzone_id,
                    'region_id': this.zoneLocationData.region_id,
                    'country_id': this.zoneLocationData.country_id,
                    'state_id': (this.zoneLocationData.state_id != null) ? this.zoneLocationData.state_id : ''
                });
                this.$http.post(adminBaseUrl + '/shipping-zone/save-location', formData)
                    .then((response) => { 
                        if (response.data.status == true) {
                            this.clicked = 0;
                            this.viewMorelocations = response.data.data;
                            this.zoneLocationData.region_id = '';
                            this.zoneLocationData.country_id = '';
                            this.zoneLocationData.state_id = null;
                            this.countries = [];
                            this.states = [];
                        }
                    });
            },
            pageLoadData: function() {
                let formData = this.$serializeData(this.profileZoneTableData);
                let thisObj = this;
                this.$http.post(adminBaseUrl + '/shipping/page-load-data', formData)
                    .then((response) => { //success
                        if (response.data.status == true) {
                            let responseData = response.data.data;
                            for (var i = 0; i < response.data.data.regions.length; i++) {
                                thisObj.zoneRegions[response.data.data.regions[i].region_id] = { region_id: response.data.data.regions[i].region_id, region_name: response.data.data.regions[i].region_name, countries: []};
                            }
                            this.zoneRecords = responseData.zoneRecords;
                            this.zoneRecordsAll = responseData.zoneRecordsAll;
                            this.rateConditions = responseData.rateConditions;
                            this.selectedProducts = responseData.selectedProducts;
                            this.selectedProductsIds = responseData.selectedProductsIds;
                            this.businessDetails = responseData.businessDetails;
                            this.weightType = responseData.weightType;
                            if (responseData.profilesRecords) {
                                this.profileZoneTableData.sprofile_name = responseData.profilesRecords.sprofile_name;
                                this.editText = responseData.profilesRecords.sprofile_name;
                                this.defaultProfile = responseData.profilesRecords.sprofile_default;
                            }
                        }
                    });
            },
            validateProfileRecord: function() {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        let formData = this.$serializeData(this.profileZoneTableData);
                        this.saveProfileData(formData, 'profile');
                    }
                });
            },
            validateZoneRecord: function () {
                this.$validator.validateAll().then(res=>{
                    if (res) {
                        this.clicked = 1;
                        let formData = this.$serializeData(this.profileZoneData);
                        formData.append('states', this.selectedStates);
                        formData.append('regions', this.selectedRegions);
                        formData.append('countries', this.selectedCountries);
                     //   console.log('Countries',this.selectedCountries);
                     //   console.log('Regiions',this.selectedRegions)
                        this.saveProfileData(formData, 'zone', true);
                    } else {
                        console.log('error')
                        this.clicked = 0;
                    }
                });
            },
            searchProducts: function(pageNo = 1, search = '') {
                if (this.profileZoneTableData.sprofile_id == '') {
                    this.validateProfileRecord();
                    return;
                }
                this.$bvModal.show(this.productsModelId);
                let formData = this.$serializeData({
                    'search': search,
                    'sprofile_id': this.profileZoneTableData.sprofile_id
                });
                 if (typeof this.pagination.per_page != 'undefined') {
                    formData.append('per_page', this.pagination.per_page);
                }
                let thisval = this;
                this.$http.post(adminBaseUrl + '/shipping/products?page=' + pageNo, formData).then((response) => {
                    this.productsRecords = response.data.data.products.data;
                    this.pagination = response.data.data.products;
                });
            },
            reload: function() {
                this.pageLoadData();
            },
            saveProfileData: function(formData, saveType, continueToAddRate = false) {
                formData.append('sprofile_id', this.profileZoneTableData.sprofile_id);
                this.$http.post(adminBaseUrl + '/shipping/save/' + saveType, formData)
                    .then((response) => { //success
                        if (response.data.status == true) {
                            this.profileZoneTableData.sprofile_id = response.data.data.sprofile_id;
                            toastr.success(response.data.message, '', toastr.options);
                            this.$bvModal.hide(this.productsModelId);
                            this.$bvModal.hide(this.zoneModelId);
                            this.$bvModal.hide(this.rateModelId);
                            this.pageLoadData();
                            if(continueToAddRate === true){
                                this.zoneRate(response.data.data.spzone_id);  
                            }                                                      
                            this.clicked = 0;
                        }
                        if (response.data.status == false) {
                            this.clicked = 0;
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                    }, (response) => { //error
                        this.clicked = 0;
                        this.validateErrors(response);
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
            addShippingZone: function() {
                if (this.profileZoneTableData.sprofile_id == '') {
                    this.validateProfileRecord();
                    return;
                }
                let formData = this.$serializeData({
                    'sprofile_id': this.profileZoneTableData.sprofile_id
                });
                let thisval = this;
                this.profileZoneData.spzone_name = '';
                this.profileZoneData.spzone_id = '';
                $.each(thisval.zoneRegions, function(i) {
                    if(typeof thisval.zoneRegions[i] != 'undefined'){
                        thisval.zoneRegions[i].countries = [];
                    }                    
                });
                this.selectedRegions = [];
                this.$http.post(adminBaseUrl + '/shipping/getSelectedLocations', formData)
                    .then((response) => { //success
                        if (response.data.status == true) {
                            this.restWord = response.data.data.restWord;
                            if (response.data.data.restWord == 1) {
                                $('.js-restofWordCheckbox').closest('li').addClass('disabled');
                            }
                        }
                    });
                this.$bvModal.show(this.zoneModelId);
                this.emptyZoneFormValues();
            },
            zoneRate: function(zoneId) {
                this.emptyRateFormValues();
                this.rateTableData.srate_spzone_id = zoneId;
                this.rateTableData.profile_type = this.defaultProfile;
                this.$bvModal.show(this.rateModelId);
            },
            confirmDelete: function(Id, type) {
                this.$bvModal.show(this.modelId);
                this.recordId = Id;
                this.deleteType = type;
            },
            deleteRecord: function(recordId) {
                let formData = this.$serializeData({
                    id: recordId
                });
                this.$http.post(adminBaseUrl + '/shipping/delete/' + this.deleteType, formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                    this.pageLoadData();
                    this.$bvModal.hide(this.modelId);
                });
            },
            emptyZoneFormValues: function() {
                this.profileZoneTableData = {
                    'sprofile_name': this.profileZoneTableData.sprofile_name,
                    'sprofile_id': this.profileZoneTableData.sprofile_id,
                    'spzone_shipping_type': '',
                    'spzone_id': '',
                    'spzone_name': ''
                };
                this.errors.clear();
            },
            emptyRateFormValues: function() {
                this.rateTableData = {
                    'srate_id': '',
                    'shipRateCondistion': false,
                    'srate_spzone_id': '',
                    'srate_type': '1',
                    'srate_name': '',
                    'srate_cost': '',
                    'srate_min_value': '',
                    'srate_max_value': ''
                };
                this.errors.clear();
            },
            editZoneRecord: function(record) {
                this.$bvModal.show(this.zoneModelId);
                this.profileZoneTableData.spzone_name = record.spzone_name;
                this.profileZoneTableData.spzone_shipping_type = record.spzone_shipping_type;
                this.profileZoneTableData.spzone_id = record.spzone_id;
                let thisVal = this;
                setTimeout(function() {
                    $.each(record.locations, function(i, val) {
                        $("input[value='" + val.stloc_country_id + '-' + val.stloc_state_id + "']").prop('checked', true);
                    });
                    thisVal.updateCount();
                }, 100);
            },
            updateCount: function() {
                let length = 0;
             
                $('.js-parentCheckbox').closest('li.js-countryListing').each(function() {
                    length = $(this).find('.js-statesCheckbox:checked').length;
                    if (length == 0) {
                        return;
                    };
                    $(this).find('.js-stateCount span').html(length);
                });
            },
            editRateRecord: function(record) {
                this.$bvModal.show(this.rateModelId);
                this.rateTableData.profile_type = this.defaultProfile;
                this.rateTableData.srate_spzone_id = record.srate_spzone_id;
                this.rateTableData.srate_id = record.srate_id;
                this.rateTableData.srate_name = record.srate_name;
                this.rateTableData.srate_cost = record.srate_cost;
                if (record.srate_type != 0) {
                    this.rateTableData.shipRateCondistion = true;
                    this.rateTableData.srate_type = record.srate_type;
                    this.rateTableData.srate_min_value = record.srate_min_value;
                    this.rateTableData.srate_max_value = record.srate_max_value;
                }
            },
        },
        mounted() {          
            this.profileZoneTableData = {
                'sprofile_name': '',
                'sprofile_id': '',
                'spzone_name': '',
                'spzone_shipping_type': '',
                'spzone_id': ''
            };
            let id = this.$route.params.id;
            if (id) {
                this.profileZoneTableData.sprofile_id = id;
            }
            this.pageLoadData();
            (function($) {

                $.fn.trigger2 = function(eventName) {
                    return this.each(function() {
                        var el = $(this).get(0);
                        triggerNativeEvent(el, eventName);
                    });
                };

                function triggerNativeEvent(el, eventName){
                if (el.fireEvent) { // < IE9
                    (el.fireEvent('on' + eventName));
                } else {
                    var evt = document.createEvent('Events');
                    evt.initEvent(eventName, true, false);
                    el.dispatchEvent(evt);
                }
            }

            }(jQuery)); 
            $('body').on('click', '.js-parentCheckbox', function() {
                if ($(this).is(':checked')) {
                    $(this).closest('li').find('ul li input[type=checkbox]').prop('checked', true);
                } else {
                    $(this).closest('li').find('ul li input[type=checkbox]').prop('checked', false);
                    $(this).closest('li.js-region').find('.js-regionCheckbox').prop('checked', false);
                }
                let stateCheckedLength = 0;
                if ($(this).closest('li').hasClass('js-region')) {
                    $(this).closest('li').find('ul li.js-countryListing').each(function() {
                        stateCheckedLength = 0;
                         if ($(this).find('input[type=checkbox]').is(':checked')) {
                                stateCheckedLength = $(this).find('.js-countryCheckbox').attr('data-states-count');
                         }
                        $(this).closest('li.js-countryListing').find('a.js-stateCount span').html(stateCheckedLength);
                    });
                } else {
                   stateCheckedLength = 0;
                         if ( $(this).closest('li.js-countryListing').find('input[type=checkbox]').is(':checked')) {
                            stateCheckedLength = $(this).attr('data-states-count');
                      }

                    $(this).closest('li.js-countryListing').find('a.js-stateCount span').html(stateCheckedLength);

                                       
                }
                $('.js-restofWordCheckbox').prop('checked', false);
                $('input[type=checkbox]').trigger2('change')
            });
            $('body').on('click', '.js-restofWordCheckbox', function() {
                $('.js-parentCheckbox').prop('checked', false);
                $('.js-parentCheckbox').closest('li').find('ul li input[type=checkbox]').prop('checked', false);
                $('a.js-stateCount span').html(0);
                $('select').trigger2('change')
            });
            $('body').on('click', '.js-statesCheckbox', function() {
                let thisObj = $(this);
                let countryIdentifier = $(this).closest('li.js-countryListing').data('id');
                let regionIdentifier = $(this).closest('li.js-region').data('regionid');
                if (!thisObj.is(':checked')) {
                    thisObj.closest('li.js-countryListing[data-id=' + countryIdentifier + ']').find('.js-parentCheckbox[data-id=' + countryIdentifier + ']').prop('checked', false);
                    thisObj.closest('li.js-region[data-regionid=' + regionIdentifier + ']').find('.js-regionCheckbox[data-regionid=' + regionIdentifier + ']').prop('checked', false);
                }
                let stateCheckedLength = thisObj.closest('ul').find('li input[type=checkbox]:checked').length;
                thisObj.closest('li.js-countryListing').find('a.js-stateCount span').html(stateCheckedLength);
                $('select').trigger2('change')

            });
            $(document).on('click', '.YK-toggleCountry', function(){
                let countrieslist = $(this).closest('.YK-regionlist').find('.YK-countrieslist');
                let toggleBtn = $(this).find('i');
                if(countrieslist.hasClass('d-none') || countrieslist.length == 0){
                    countrieslist.removeClass('d-none');
                    toggleBtn.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                }else{
                    countrieslist.addClass('d-none');
                    toggleBtn.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                }                
            });
            $(document).on('click', '.YK-toggleStates', function(){            
                let stateslist = $(this).closest('.YK-countryList').find('.YK-statesList');
                let toggleBtn = $(this).find('i');
                if(stateslist.hasClass('d-none') || stateslist.length == 0){
                    stateslist.removeClass('d-none');
                    toggleBtn.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                }else{
                    stateslist.addClass('d-none');
                    toggleBtn.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                }                
            });
        }
    }
</script>