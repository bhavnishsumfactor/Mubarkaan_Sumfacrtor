<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t("LBL_META_TAGS") }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{ $t("LBL_SEO") }}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{ $t("LBL_META_TAGS") }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__body pb-0 bg-gray flex-grow-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-group input-icon input-icon--left">
                                            <input type="text" class="form-control" :placeholder="$t('PLH_SEARCH')" id="generalSearch" v-model="searchData.search" @keyup="searchRecords" />
                                            <span class="input-icon__icon input-icon__icon--left">
                                                <span><i class="la la-search"></i></span>
                                            </span>
                                            <div class="input-group-append">
                                                <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="true">
                                                    {{module_type | camelCase}}
                                                </button>
                                                <div class="dropdown-menu" x-placement="top-start">
                                                    <a class="dropdown-item" href="javascript:;" @click="selectModule('pages')">{{$t("LBL_PAGES")}}</a>
                                                    <a class="dropdown-item" href="javascript:;" @click="selectModule('products')">{{$t("LBL_PRODUCTS")}}</a>
                                                    <a class="dropdown-item" href="javascript:;" @click="selectModule('brands')">{{$t("LBL_BRANDS")}}</a>
                                                    <a class="dropdown-item" href="javascript:;" @click="selectModule('categories')">{{$t("LBL_CATEGORIES")}}</a>
                                                    <a class="dropdown-item" href="javascript:;" @click="selectModule('blogs')">{{$t("LBL_BLOGS")}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="m-0" />
                        <div class="portlet__body portlet__body--fit" v-if="records.length > 0">
                            <div class="section">
                                <div class="section__content">
                                    <table class="table table-data table-justified">
                                        <thead>
                                            <tr>
                                                <th>{{ "#" }}</th>
                                                <th>{{ $t("LBL_MODULE_NAME") }}</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="(module_type == 'products' || module_type == 'blogs' || module_type =='pages') && this.pagination.current_page == 1 && searchData.search == ''">
                                                <td scope="row">1</td>
                                                <td
                                                    v-if="
                                                        module_type != 'pages'
                                                    "
                                                >
                                                    {{ $t("LBL_LISTING_PAGE") }}
                                                </td>
                                                <td
                                                    v-if="
                                                        module_type == 'pages'
                                                    "
                                                >
                                                    {{ $t( "LBL_DEFAULT_META_SETTINGS" ) }}
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                    <a href="javascript:;" v-b-tooltip.hover :title="$t('TTL_EDIT')" class="btn btn-light btn-sm btn-icon" @click="editRecord()" v-bind:class="[(!$canWrite('META_TAGS')) ? 'disabled no-drop': '']"><svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr
                                                v-for="(record,
                                                index) in records"
                                                :key="record.record_id"
                                                :data-id="record.record_id"
                                            >
                                                <td
                                                    scope="row"
                                                    v-if="
                                                        pagination.current_page ==
                                                            1 &&
                                                            (module_type ==
                                                                'products' ||
                                                                module_type ==
                                                                    'blogs' ||
                                                                module_type ==
                                                                    'pages') &&
                                                            searchData.search ==
                                                                ''
                                                    "
                                                >
                                                    {{ pagination.from + index + 1 }}
                                                </td>
                                                <td scope="row" v-else>
                                                    {{ pagination.from + index }}
                                                </td>
                                                <td>
                                                    <a href="javascript:;" @click.prevent="editRecord(record)" v-if="!$canWrite('META_TAGS')">{{ record.record_title }}</a>
                                                    <div v-else>{{ record.record_title }}</div>
                                                    
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        <a
                                                            href="javascript:;"
                                                            v-b-tooltip.hover
                                                             :title="$t('TTL_EDIT')"
                                                            class="btn btn-light btn-sm btn-icon"
                                                            @click="editRecord(record)"
                                                            v-bind:class="[(!$canWrite('META_TAGS')) ? 'disabled no-drop': '']"
                                                        >
                                                            <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
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
                        <loader v-if="loading"></loader>
                        <noRecordsFound v-if="loading == false && records.length == 0"></noRecordsFound>
                        <div class="portlet__foot" v-if="records.length > 0">
                            <pagination :pagination="pagination" @currentPage="currentPage"> </pagination>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__head" v-if="selectedPage">
                            <div class="portlet__head-label">
                                <h3 class="portlet__head-title" v-if="selectedPage == 'editform'">
                                    {{ $canWrite('META_TAGS') ? $t('LBL_EDITING') + ' -' : ''}} {{ adminsData.record_title }}
                                </h3>
                            </div>
                            <div class="portlet__head-toolbar" v-if="selectedPage == 'editform'">
                                <div class="portlet__head-actions" id="tooltip-target-1">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <b-popover target="tooltip-target-1" triggers="hover" placement="top" class="popover-cover">
                                    <div class="list-stats">
                                        <div class="lable">
                                            <span class="stats_title">{{ $t("LBL_CREATED") }}:</span>
                                            <span class="time">{{ createdByUser ? createdByUser + " |" : "" }} {{ createdAt | formatDateTime }}</span>
                                        </div>
                                        <div class="lable">
                                            <span class="stats_title">{{ $t("LBL_LAST_UPDATED") }}:</span>
                                            <span class="time">{{ updatedByUser ? updatedByUser + " |" : "" }} {{ updatedAt | formatDateTime }}</span>
                                        </div>
                                    </div>
                                </b-popover>
                            </div>
                        </div>
                        <div class="portlet__body" v-if="selectedPage" v-bind:class="[(!$canWrite('META_TAGS')) ? 'disabledbutton': '']">
                            <input v-if="adminsData.meta_id != ''" type="hidden" name="id" v-model="adminsData.meta_id" />
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t("LBL_META_TITLE") }}</label>
                                        <input type="text" v-model="adminsData.meta_title" name="meta_title" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t("LBL_META_KEYWORDS") }} </label>
                                        <textarea class="form-control" rows="5" cols="15" v-model="adminsData.meta_keywords" name="meta_keywords"> </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t("LBL_META_DESCRIPTION") }} </label>
                                        <textarea
                                            class="form-control"
                                            rows="5"
                                            cols="15"
                                            v-model="adminsData.meta_description"
                                            name="meta_description"
                                        >
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('LBL_OTHER_META_TAGS')}} </label>
                                        <textarea class="form-control" rows="5" cols="15" v-model="adminsData.meta_other_meta_tags" name="meta_other_meta_tags"> </textarea>
                                        <span class="form-text text-muted">{{ $t ('LBL_FOR_EXAMPLE') }}: {{'&#60;meta name="copyright" content="text"&#62;'}}</span>
                                        <span v-if="errors.first('meta_other_meta_tags')" class="error text-danger">{{ errors.first('meta_other_meta_tags') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet__body" v-if="selectedPage == ''">
                            <div class="no-data-found">
                                <div class="img">
                                    <img :src="this.$noDataImage()" alt="" />
                                </div>
                                <div class="data">
                                    <p>
                                        {{ $t("LBL_USE_ICONS_FOR_ADVANCED_ACTIONS") }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="portlet__foot" v-if="selectedPage != ''">
                            <div class="row">
                                <div class="col">
                                    <button type="reset" class="btn btn-secondary" @click="cancel()">
                                        {{ $t("BTN_DISCARD") }}
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <button
                                        type="submit"
                                        class="btn btn-brand gb-btn gb-btn-primary"
                                        @click="updateRecord()"
                                        :disabled="clicked == 1 || (!$canWrite('META_TAGS'))"
                                        v-if="selectedPage == 'editform'"
                                        v-bind:class="clicked == 1 && 'gb-is-loading'"
                                    >
                                        {{ $t("BTN_META_TAGS_UPDATE") }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    const tableFields = {
        module_type: "",
        record_id: "",
        record_title: "",
        page_type: "",
        meta_title: "",
        meta_keywords: "",
        meta_description: "",
        meta_other_meta_tags: "",
    };
    const searchFields = {
        search: "",
    };
    export default {
        data: function () {
            return {
                baseUrl: baseUrl,
                records: [],
                pagination: [],
                clicked: 0,
                loading: false,
                adminsData: tableFields,
                searchData: searchFields,
                search: "",
                selectedPage: "",
                recordId: "",
                module_type: "pages",
                createdByUser: "",
                updatedByUser: "",
                updatedAt: "",
                createdAt: "",
            };
        },
        methods: {
            emptyFormValues: function () {
                this.adminsData = {
                    module_type: "",
                    record_id: "",
                    record_title: "",
                    page_type: "",
                    meta_title: "",
                    meta_keywords: "",
                    meta_description: "",
                    meta_other_meta_tags: "",
                };
                this.errors.clear();
            },
            pageRecords: function (pageNo, pageLoad = false) {
                this.loading = pageLoad;
                let formData = this.$serializeData(this.searchData);
                formData.append("module_type", this.module_type);
                if (typeof this.pagination.per_page != "undefined") {
                    formData.append("per_page", this.pagination.per_page);
                }
                this.$http.post(adminBaseUrl + "/metatags/modulelist?page=" + pageNo, formData).then((response) => {
                    this.records = response.data.data.records.data;
                    this.pagination = response.data.data.records;
                    this.loading = false;
                });
            },
            selectModule: function (type) {
                this.module_type = type;
                this.pageRecords(1);
                this.cancel();
                this.emptyFormValues();
            },
            searchRecords: function () {
                this.pageRecords(1);
            },
            currentPage: function (page) {
                this.pageRecords(page);
            },
            editRecord: function (record) {
                if (typeof record == "undefined") {
                    if (this.module_type == "pages") {
                        record = {
                            record_id: "",
                            record_title: this.$t("LBL_DEFAULT_META_SETTINGS"),
                        };
                    } else {
                        record = {
                            record_id: "",
                            record_title: this.$t("LBL_LISTING_PAGE"),
                        };
                    }
                }

                let moduleTypeTemp = this.module_type;
                if (this.module_type == "products" && record.record_id == "") {
                    moduleTypeTemp = "productslisting";
                } else if (this.module_type == "blogs" && record.record_id == "") {
                    moduleTypeTemp = "blogslisting";
                } else if (typeof record.page_type != "undefined" && record.page_type == "faq") {
                    //in case of faqs
                    moduleTypeTemp = "faqs";
                    record.record_id = "";
                }
                let formData = this.$serializeData({
                    module_type: moduleTypeTemp,
                    record_id: record.record_id,
                });
                this.$http.post(adminBaseUrl + "/metatags/get", formData).then(
                    (response) => {
                        if (response.data.status == false) {
                            toastr.error(response.data.message, "", toastr.options);
                            return;
                        }
                        this.emptyFormValues();
                        this.emptyUpdatedFieldValue();
                        this.adminsData.module_type = this.module_type;
                        this.adminsData.record_id = record.record_id;
                        this.adminsData.record_title = record.record_title;
                        if (typeof record.page_type != "undefined") {
                            this.adminsData.page_type = record.page_type;
                        }
                        if (response.data.data != null) {
                            this.adminsData.meta_title = response.data.data.meta_title;
                            this.adminsData.meta_keywords = response.data.data.meta_keywords;
                            this.adminsData.meta_description = response.data.data.meta_description;
                            this.adminsData.meta_other_meta_tags = response.data.data.meta_other_meta_tags;
                            if (response.data.data.created_at != null && "admin_username" in response.data.data.created_at) {
                                this.createdByUser = response.data.data.created_at.admin_username;
                            }
                            if (response.data.data.updated_at != null && "admin_username" in response.data.data.updated_at) {
                                this.updatedByUser = response.data.data.updated_at.admin_username;
                            }
                            this.updatedAt = response.data.data.meta_updated_at ? response.data.data.meta_updated_at : "";
                            this.createdAt = response.data.data.meta_created_at ? response.data.data.meta_created_at : "";
                        }
                    },
                    (response) => {}
                );
                this.selectedPage = "editform";
            },
            updateRecord: function () {
                this.$validator.validateAll().then((res) => {
                    if (res) {
                        this.clicked = 1;
                        if (this.adminsData.module_type == "products" && this.adminsData.record_id == "") {
                            this.adminsData.module_type = "productslisting";
                        } else if (this.adminsData.module_type == "blogs" && this.adminsData.record_id == "") {
                            this.adminsData.module_type = "blogslisting";
                        } else if (typeof this.adminsData.page_type != "undefined" && this.adminsData.page_type == "faq") {
                            //in case of faqs
                            this.adminsData.module_type = "faqs";
                            this.adminsData.record_id = "";
                        }
                        let formData = this.$serializeData(this.adminsData);
                        this.$http.post(adminBaseUrl + "/metatags/update", formData).then(
                            (response) => {
                                this.clicked = 0;
                                if (response.data.status == false) {
                                    toastr.error(response.data.message, "", toastr.options);
                                    return;
                                }
                                toastr.success(response.data.message, "", toastr.options);
                                this.emptyFormValues();
                                this.pageRecords(this.pagination.current_page);
                                this.selectedPage = "";
                            },
                            (response) => {
                                this.clicked = 0;
                                this.validateErrors(response);
                            }
                        );
                    } else {
                        this.clicked = 0;
                    }
                });
            },
            cancel: function () {
                this.selectedPage = false;
            },
            validateErrors: function (response) {
                let jsondata = response.data;
                Object.keys(jsondata.errors).forEach((key) => {
                    this.errors.add({
                        field: key,
                        msg: jsondata.errors[key][0],
                    });
                });
            },
            emptyUpdatedFieldValue: function () {
                this.createdByUser = "";
                this.updatedByUser = "";
                this.updatedAt = "";
                this.createdAt = "";
            },
        },
        mounted: function () {
            this.searchData = { search: "" };
            this.pageRecords(1, true);
        },
    };
</script>
