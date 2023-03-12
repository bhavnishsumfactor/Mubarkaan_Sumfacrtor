<style>
.ti-tag.ti-invalid{background-color: #5C6BC0 !important;}
</style>
<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_BUY_TOGETHER_PRODUCTS') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_PROMOTIONS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_BUY_TOGETHER_PRODUCTS')}}</span>
                    </div>
                </div>                
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="alert alert-light alert-elevate fade show" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning font-info"></i></div>
                        <div class="alert-text">{{ $t('LBL_BUY_TOGETHER_INFO1')}}.<br>{{ $t('LBL_BUY_TOGETHER_INFO2')}}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__body pb-0 bg-gray flex-grow-0">
                            <div class="row justify-content-between">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <vue-bootstrap-typeahead :serializer="s => s.prod_name" @input="inputChange($event)" v-model="productSearch" :data="products" @hit="selectProduct($event)" ref="productTypeahead" :placeholder="$t('LBL_SEARCH_PRODUCT')" :max-matches=5 :min-matching-chars=1 :disabled="( !$canWrite('BUY_TOGETHER_PRODUCTS'))" v-bind:class="[( !$canWrite('BUY_TOGETHER_PRODUCTS')) ? 'disabledbutton': '']"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group custom-input">                                        
                                        <vue-tags-input :disabled="selectedProduct==null" class="vue-tags-style" v-model="tag" :tags="tags" ref="productsTagify" :autocomplete-items="autocompleteProducts" @tags-changed="update" :add-only-from-autocomplete="true" :placeholder="$t('PLH_ADD_BUY_TOGETHER_PRODUCTS')">
                                            <div slot="tag-center" slot-scope="props">
                                                <span class="tagifyTooltip" :title="props.tag.text">{{ props.tag.text | truncate }}</span>
                                            </div>
                                        </vue-tags-input>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-brand btn-block gb-btn gb-btn-primary" :disabled='!isComplete || clicked==1' @click="updateRecord()" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_BUY_TOGETHER_SAVE') }}</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-secondary btn-block" :disabled='!isComplete' @click="cancel()">{{ $t('BTN_DISCARD')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="portlet__body portlet__body--fit" v-if="showEmpty == 0 && pageLoaded==1" v-bind:class="[( !$canWrite('BUY_TOGETHER_PRODUCTS')) ? 'disabledbutton': '']">
                            <div class="section">
                                <div class="section__content">
                                    <table class="table table-data">
                                        <thead>
                                            <tr>
                                                <th width="25%">{{ $t('LBL_PRODUCTS_NAME') }}</th>
                                                <th width="65%">{{ $t('LBL_BUY_TOGETHER_PRODUCTS') }}</th>
                                  
                                            </tr>
                                        </thead>
                                        <tbody v-if="loading==false && records.length == 0 && showEmpty == 0 && pageLoaded==1">
                                            <tr>
                                                <td colspan="3">  
                                                    <noRecordsFound></noRecordsFound> 
                                                </td>
                                            </tr>
                                        </tbody>  
                                        <tbody v-else>
                                            <tr v-for="(record, index) in records" :key="record.prod_id" :data-id="record.prod_id">
                                                <td><a href="javascript:;" @click="selectRecord(record)">{{ record.prod_name }}</a></td>
                                                <td>
                                                    <ul class="tagify-custom">
                                                        <li v-for="(product,key) in record.recommended" :key="key">
                                                            <span> {{ product.prod_name }} </span>
                                                            <a class="remove" v-b-tooltip.hover :title="$t('TTL_DELETE')" href="javascript:;" @click="deleteRecord(record.prod_id, product.prod_id,index)" v-bind:class="[( !$canWrite('BUY_TOGETHER_PRODUCTS')) ? 'disabledbutton': '']"><i class="fas fa-times"></i></a>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        <a class="btn btn-light btn-sm btn-icon" href="javascript:;" @click.capture="confirmDelete($event, record.prod_id)" v-b-tooltip.hover :title="$t('TTL_DELETE')" v-bind:class="[( !$canWrite('BUY_TOGETHER_PRODUCTS')) ? 'disabledbutton': '']">
                                                            <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>                               
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="portlet__body" v-if="showEmpty == 1 && pageLoaded==1">
                            <div class="get-started ">
                                <div class="get-started-arrow d-flex justify-content-end mb-5">
                                </div>
                                <div class="no-content d-flex text-center flex-column mb-7">
                                    <p>{{$t('LBL_REFER_TO_INSTRUCTIONS')}}</p>
                                </div>
                                <div class="get-started-media">
                                    <img :src="baseUrl+'/admin/images/get-started-graphic/get-started-buy-togther.svg'"/>
                                </div>
                            </div>
                        </div>
                        <loader v-if="loading"></loader>    
                        <div class="portlet__foot" v-if="records.length > 0">
                            <pagination :pagination="pagination" @currentPage="currentPage"> </pagination>
                        </div>
                    </div>
                </div>
                <DeleteModel :deletePopText="$t('LBL_BUY_TOGETHER_DELETE_TEXT')" :recordId="recordId" @deleted="deleteRecommendedProduct(recordId)"></DeleteModel>
            </div>
        </div>
    </div>
</template>
<script>
    import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';
    import VueTagsInput from '@johmun/vue-tags-input';
    export default {
        components: {
            'vue-bootstrap-typeahead': VueBootstrapTypeahead,
            VueTagsInput
        },
        data: function() {
            return {
                records: [],
                baseUrl: baseUrl,
                pagination: [],
                clicked: 0,
                loading: false,
                searchData: {},
                products: [],
                productSearch: '',
                selectedProduct: null,
                autocompleteProducts: [],
                tag: '',
                tags: [],
                modelId: 'deleteModel',
                recordId: '',  
                pageLoaded: 0,          
                showEmpty: 1
            }
        },
        computed: {
            isActived: function() {
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                return this.$pagesNumber(this.pagination);
            },
            isComplete () {
                return (this.selectedProduct!=null) && (this.tags.length>0);
            }
        },
        watch: {
            productSearch: _.debounce(function(prod) {
                this.getProducts(prod)
            }, 500),
            'tag': 'initItems'
        },
        methods: {
            async getProducts(query) {
                const res = await fetch(adminBaseUrl + '/buytogether/search?query=' + query);
                const response = await res.json();
                this.products = response.data;
            },
            selectProduct: function(event) {
                this.selectedProduct = event;
                this.$http.get(adminBaseUrl + '/buytogether/get?prod_id=' + this.selectedProduct.prod_id).then((response) => {
                    this.tags = response.data.data.map(a => {
                        return {
                            text: a.prod_name,
                            id: a.prod_id
                        };
                    });
                });
            },
            inputChange: function(event) {
                if(event==''){
                    this.selectedProduct = null;    
                    this.tags = [];
                }
            },
            confirmDelete: function(event, dataid) {
                event.stopPropagation();
                this.recordId = dataid;
                this.$bvModal.show(this.modelId);
            },
            update(newTags) {
                this.autocompleteProducts = [];
                this.tags = newTags;
                Vue.nextTick(function() {
                    $('.tagifyTooltip').tooltip();
                })
            },
            async initItems() {
                if (this.tag.length < 2 || this.selectedProduct == null) return;
                const res = await fetch(adminBaseUrl + '/buytogether/search?query=' + this.tag + '&prod_id=' + this.selectedProduct.prod_id);
                const response = await res.json();
                this.autocompleteProducts = response.data.map(a => {
                    return {
                        text: a.prod_name,
                        id: a.prod_id
                    };
                });
            },
            emptyFormValues: function() {
                this.products = [];
                this.productSearch = '';
                this.selectedProduct = null;
                this.autocompleteProducts = [];
                this.tag = '';
                this.tags = [];
                this.$refs.productTypeahead.inputValue = '';
            },
            pageRecords: function(pageNo, pageLoad = false) {
                this.loading = pageLoad;
                let formData = this.$serializeData(this.searchData);
                if (typeof this.pagination.per_page != 'undefined') {
                    formData.append('per_page', this.pagination.per_page);
                }
                this.$http.post(adminBaseUrl + '/buytogether?page=' + pageNo, formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.records = response.data.data.buytogether.data;
                    this.loading = false;
                    this.pagination = response.data.data.buytogether;
                    this.showEmpty = response.data.data.showEmpty;   
                    this.pageLoaded = 1;   
                });
            },
            searchRecords: function() {
                this.pageRecords(this.pagination.current_page);
            },
            currentPage: function(page) {
                this.pageRecords(page);
            },
            cancel: function() {
                this.emptyFormValues();
            },
            selectRecord: function(record) {
                this.$refs.productTypeahead.inputValue = record.prod_name;
                this.$emit('productSearch', record.prod_name);
                this.selectedProduct = record;
                this.tags = record.recommended.map(a => {
                    return {
                        text: a.prod_name,
                        id: a.prod_id
                    };
                });
                Vue.nextTick(function() {
                    $('.tagifyTooltip').tooltip();
                })
            },
            updateRecord: function() {
                this.clicked = 1;
                if (this.$refs.productTypeahead.inputValue == '' || this.tags.length == 0) {
                    toastr.error(this.$t('NOTI_PLEASE_SELECT_REQUIRED_FIELDS'), '', toastr.options);
                    return;
                }
                let formData = this.$serializeData({
                    'prod_id': this.selectedProduct.prod_id,
                    'recommended': JSON.stringify(this.tags)
                });
                this.$http.post(adminBaseUrl + '/buytogether/update', formData)
                    .then((response) => {
                        this.clicked = 0;
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        this.emptyFormValues();
                        this.pageRecords(this.pagination.current_page);
                    });
            },
            deleteRecord: function(productId, recommendedId,prodKey) {
                let formData = this.$serializeData({
                    'prod_id': productId,
                    'recommended_id': recommendedId
                })
                var recommend = [...this.records[prodKey].recommended];
                $.each(this.records[prodKey].recommended, function(key, value) {
                    if(value.prod_id==recommendedId){
                        recommend.splice(key, 1);
                    }
                });
                this.records[prodKey].recommended = recommend;
                
                this.$http.post(adminBaseUrl + '/buytogether/delete', formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                    if(this.records[prodKey].recommended.length == 0){
                        this.pageRecords(this.pagination.current_page);
                    }
                });
            },
            deleteRecommendedProduct: function(productId) {
                let formData = this.$serializeData({
                    'prod_id': productId
                })
                this.$http.post(adminBaseUrl + '/buytogether/delete', formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                    this.pageRecords(this.pagination.current_page);
                    this.$bvModal.hide(this.modelId);
                });
            },
        },
        mounted: function() {
            this.pageRecords(1, true);
        }
    }
</script>