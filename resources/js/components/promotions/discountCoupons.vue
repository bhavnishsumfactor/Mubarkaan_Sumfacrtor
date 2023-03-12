<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_DISCOUNT_COUPONS') }}</h3>
          <div class="subheader__breadcrumbs">
            <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_PROMOTIONS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_DISCOUNT_COUPONS')}}</span>
          </div>
        </div>
        <div class="subheader__toolbar">
          <a
            @click="openAddPage"
            class="btn btn-white"
            href="javascript:void(0);"
            v-bind:class="[(!$canWrite('DISCOUNT_COUPONS')) ? 'disabledbutton no-drop': '']"
          >
            <i class="fas fa-plus"></i>
            {{ $t('BTN_ADD') }}
          </a>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class v-bind:class="[(showEmpty == 1) ? 'col-md-12': 'col-md-6']">
          <div class="portlet portlet--height-fluid portlet--tabs">
            <div class="portlet__head" v-if="showEmpty == 0">
              <div class="portlet__head-toolbar">
                <ul
                  class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold"
                  role="tablist"
                >
                  <li class="nav-item">
                    <a
                      class="nav-link active"
                      data-toggle="tab"
                      href="javascript:;"
                      role="tab"
                      @click="tabFilter('all')"
                    >{{$t('LNK_COUPON_FILTER_ALL')}}</a>
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      data-toggle="tab"
                      href="javascript:;"
                      role="tab"
                      @click="tabFilter('active')"
                    >{{$t('LNK_COUPON_FILTER_ACTIVE')}}</a>
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      data-toggle="tab"
                      href="javascript:;"
                      role="tab"
                      @click="tabFilter('scheduled')"
                    >{{$t('LNK_COUPON_FILTER_SCHEDULED')}}</a>
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      data-toggle="tab"
                      href="javascript:;"
                      role="tab"
                      @click="tabFilter('expired')"
                    >{{$t('LNK_COUPON_FILTER_EXPIRED')}}</a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="portlet__body pb-0 bg-gray flex-grow-0" v-if="showEmpty == 0">
              <div class="form-group">
                <div class="input-icon input-icon--left">
                  <input
                    type="text"
                    class="form-control"
                    :placeholder="$t('LBL_SEARCH')"
                    id="generalSearch"
                    :readonly="records.length == 0 && searchData.search === ''"
                    v-model="searchData.search"
                    @keyup="searchRecords"
                  />
                  <span class="input-icon__icon input-icon__icon--left">
                    <span>
                      <i class="la la-search"></i>
                    </span>
                  </span>
                  <span
                    class="input-icon__icon input-icon__icon--right"
                    v-if="searchData.search!=''"
                    @click="searchData.search='';pageRecords(1);"
                  >
                    <span>
                      <i class="fas fa-times"></i>
                    </span>
                  </span>
                </div>
              </div>
            </div>
            <hr class="m-0" />
            <div class="portlet__body portlet__body--fit" v-if="showEmpty == 0 && pageLoaded==1">
              <div class="section">
                <div class="section__content">
                  <table class="table table-data table-justified">
                    <thead>
                      <tr>
                        <th>{{ '#' }}</th>
                        <th>{{ $t('LBL_COUPON_NAME') }}</th>
                        <th>{{ $t('LBL_PUBLISH') }}</th>
                        <th>{{ $t('LBL_COUPON_DATES') }}</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody
                      v-if="loading == false && records.length == 0 && searchData.search != '' && showEmpty == 0 && pageLoaded==1"
                    >
                      <tr>
                        <td colspan="5">
                          <noRecordsFound></noRecordsFound>
                        </td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <tr v-for="(record, index) in records" :key="index">
                        <td scope="row">{{ pagination.from+index }}</td>
                        <td>
                          <a
                            href="javascript:;"
                            @click.prevent="editRecord(record)"
                            v-if="!$canWrite('DISCOUNT_COUPONS')"
                          >{{ record.discountcpn_code }}</a>
                          <div v-else>{{ record.discountcpn_code }}</div>
                          <span class="form-text text-muted">
                            <span v-if="record.discountcpn_in_percent == 0">{{ $t('LBL_FLAT') }}</span>
                            {{ record.discountcpn_amount }}
                            <span
                              v-if="record.discountcpn_in_percent == 1"
                            >%</span>
                            {{ $t('LBL_OFF') }}
                          </span>
                        </td>
                        <td>
                          <label
                            class="switch switch--sm"
                            v-bind:class="[ (checkCouponExpire(record.discountcpn_enddate) == true || hidePublish == true)  ? 'disabledbutton' : '']"
                            v-b-tooltip.hover
                            :title="record.discountcpn_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') "
                          >
                            <input
                              type="checkbox"
                              name="discountcpn_publish"
                              v-model="record.discountcpn_publish"
                              @change="onSwitchStatus($event, record.discountcpn_id)"
                            />
                            <span></span>
                          </label>
                        </td>
                        <td>
                          <span class="date">
                            {{ record.discountcpn_startdate | formatDate }} - {{record.discountcpn_enddate | formatDate }}
                            <span
                              class="form-text text-muted"
                            >({{record.discountcpn_startdate | formatTime }} - {{record.discountcpn_enddate | formatTime }})</span>
                          </span>
                        </td>
                        <td>
                          <div class="actions">
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              href="javascript:;"
                              v-b-tooltip.hover
                              :title="$t('TTL_EDIT')"
                              @click.prevent="editRecord(record)"
                              v-bind:class="[!$canWrite('DISCOUNT_COUPONS') ? 'disabled no-drop': '']"
                            >
                              <svg>
                                <use
                                  :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'"
                                />
                              </svg>
                            </a>
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              href="javascript:;"
                              v-b-tooltip.hover
                              :title="$t('TTL_DELETE')"
                              @click.capture="confirmDelete($event, record.discountcpn_id)"
                              v-bind:class="[!$canWrite('DISCOUNT_COUPONS') ? 'disabled no-drop': '']"
                            >
                              <svg>
                                <use
                                  :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"
                                />
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
              <div class="get-started">
                <div class="get-started-arrow d-flex justify-content-end mb-5">
                  <img
                    :src="baseUrl+'/admin/images/get-started-graphic/get-started-arrow-head.svg'"
                  />
                </div>
                <div class="no-content d-flex text-center flex-column mb-7">
                  <p>
                    {{ $t('LBL_CLICK_THE') }}
                    <a
                      href="javascript:;"
                      @click="openAddPage"
                      class="btn btn-brand btn-small"
                    >
                      <i class="fas fa-plus"></i>
                      {{ $t('BTN_ADD') }}
                    </a>
                    {{ $t('LBL_BUTTON_TO_CREATE_COUPONS') }}
                  </p>
                </div>
                <div class="get-started-media">
                  <img
                    :src="baseUrl+'/admin/images/get-started-graphic/get-started-discount-coupons.svg'"
                  />
                </div>
              </div>
            </div>
            <loader v-if="loading"></loader>
            <div class="portlet__foot" v-if="records.length > 0">
              <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
            </div>
          </div>
        </div>
        <div class="col-md-6" v-if="showEmpty == 0">
          <div class="portlet portlet--height-fluid portlet portlet--tabs">
            <div class="portlet__head" v-if="selectedPage">
              <div class="portlet__head-label">
                <h3
                  class="portlet__head-title"
                  v-if="selectedPage == 'editform'"
                >{{ $canWrite('DISCOUNT_COUPONS') ? $t('LBL_EDITING') + ' -' : ''}} {{adminsData.discountcpn_code}}</h3>
                <h3
                  class="portlet__head-title"
                  v-if="selectedPage == 'addform'"
                >{{ $t('LBL_NEW_COUPON_SETUP')}}</h3>
              </div>
              <div class="portlet__head-toolbar">
                <ul
                  class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold"
                  v-if="selectedPage"
                >
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      v-bind:class="[ tabForm == 'general' ? 'active' : '']"
                      href="javascript:;"
                      @click="tabFormChange('general', $event)"
                    >{{$t('LNK_GENERAL_DETAILS')}}</a>
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      v-bind:class="[ tabForm == 'link' ? 'active' : '']"
                      href="javascript:;"
                      @click="tabFormChange('link', $event)"
                    >{{$t('LNK_LINK_COUPON')}}</a>
                  </li>
                </ul>
                <div
                  class="portlet__head-actions d-flex align-items-center ml-5"
                  id="tooltip-target-1"
                >
                  <i class="fas fa-info-circle"></i>
                </div>
                <b-popover
                  target="tooltip-target-1"
                  triggers="hover"
                  placement="top"
                  class="popover-cover"
                >
                  <div class="list-stats">
                    <div class="lable">
                      <span class="stats_title">{{ $t('LBL_CREATED') }}:</span>
                      <span
                        class="time"
                      >{{ createdByUser ? createdByUser+ ' |' : '' }} {{ createdAt | formatDateTime}}</span>
                    </div>
                    <div class="lable">
                      <span class="stats_title">{{ $t('LBL_LAST_UPDATED') }}:</span>
                      <span
                        class="time"
                      >{{ updatedByUser ? updatedByUser+ ' |' : ''}} {{ updatedAt | formatDateTime}}</span>
                    </div>
                  </div>
                </b-popover>
              </div>
            </div>
            <div
              class="portlet__body"
              v-if="selectedPage"
              v-bind:class="[( !$canWrite('DISCOUNT_COUPONS')) ? 'disabledbutton': '']"
            >
              <input
                v-if="adminsData.discountcpn_id != ''"
                type="hidden"
                name="id"
                v-model="adminsData.discountcpn_id"
              />
              <div v-if="tabForm == 'general'">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>
                        {{ $t('LBL_COUPON_CODE') }}
                        <span class="required">*</span>
                      </label>
                      <div class="input-group">
                        <input
                          type="text"
                          v-model="adminsData.discountcpn_code"
                          name="discountcpn_code"
                          v-validate="'required'"
                          :data-vv-as="$t('LBL_COUPON_CODE')"
                          data-vv-validate-on="none"
                          class="form-control"
                          @focusout="checkCouponAlreadyCreated()"
                        />
                        <div class="input-group-append">
                          <button
                            class="btn btn-outline-brand"
                            type="button"
                            @click="generateCode()"
                          >{{ $t('BTN_COUPON_GENERATE') }}</button>
                        </div>
                      </div>
                      <span
                        v-if="errors.first('discountcpn_code')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_code') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                        {{ $t('LBL_COUPON_TYPE') }}
                        <span class="required">*</span>
                      </label>
                      <select
                        v-model="adminsData.discountcpn_in_percent"
                        name="discountcpn_in_percent"
                        v-validate="'required'"
                        :data-vv-as="$t('LBL_COUPON_TYPE')"
                        data-vv-validate-on="none"
                        class="form-control"
                      >
                        <option value disabled>{{ $t('LBL_SELECT')}}</option>
                        <option
                          :value="0"
                          :selected="adminsData.discountcpn_in_percent"
                        >{{$t('LBL_FLAT')}}</option>
                        <option
                          :value="1"
                          :selected="adminsData.discountcpn_in_percent"
                        >{{$t('LBL_PERCENTAGE')}}</option>
                      </select>
                      <span
                        v-if="errors.first('discountcpn_in_percent')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_in_percent') }}</span>
                    </div>
                  </div>
                  <!-- <div class="col-md-6">
                    <div class="form-group">
                      <label>
                        {{ $t('LBL_APPLIES_TO') }}
                        <span class="required">*</span>
                      </label>
                      <select
                        v-model="adminsData.discountcpn_type"
                        name="discountcpn_type"
                        v-validate="'required'"
                        :data-vv-as="$t('LBL_APPLIES_TO')"
                        data-vv-validate-on="none"
                        class="form-control"
                      >
                        <option value disabled>{{ $t('LBL_SELECT')}}</option>
                        <option
                          :value="0"
                          :selected="adminsData.discountcpn_type"
                        >{{$t('LBL_ORDER')}}</option>
                        <option
                          :value="1"
                          :selected="adminsData.discountcpn_type"
                        >{{$t('LBL_SHIPPING')}}</option>
                      </select>
                      <span
                        v-if="errors.first('discountcpn_type')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_type') }}</span>
                    </div>
                  </div>-->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                        {{ $t('LBL_TOTAL_USES') }}
                        <span class="required">*</span>
                      </label>
                      <input
                        type="text"
                        v-model="adminsData.discountcpn_total_uses"
                        name="discountcpn_total_uses"
                        v-validate="{ rules: { required: true, regex:  /^\d+(\.\d{1,2})?$/} }"
                        :data-vv-as="$t('LBL_TOTAL_USES')"
                        data-vv-validate-on="none"
                        class="form-control"
                      />
                      <span
                        v-if="errors.first('discountcpn_total_uses')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_total_uses') }}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                        {{ $t('LBL_USES_PER_USER') }}
                        <span class="required">*</span>
                      </label>
                      <input
                        type="text"
                        v-model="adminsData.discountcpn_uses_per_user"
                        name="discountcpn_uses_per_user"
                        v-validate="{ rules: { required: true, regex:  /^\d+(\.\d{1,2})?$/} }"
                        :data-vv-as="$t('LBL_USES_PER_USER')"
                        data-vv-validate-on="none"
                        class="form-control"
                      />
                      <span
                        v-if="errors.first('discountcpn_uses_per_user')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_uses_per_user') }}</span>
                    </div>
                  </div>

                  <div
                    class="col-md-6"
                    v-bind:class="[adminsData.discountcpn_in_percent != 1 ? 'disabledbutton' : '']"
                  >
                    <div class="form-group">
                      <label>
                        {{ $t('LBL_MAX_AMOUNT_LIMIT') }}
                        <span class="required">*</span>
                      </label>
                      <input
                        type="text"
                        v-model="adminsData.discountcpn_maxamt_limit"
                        name="discountcpn_maxamt_limit"
                        v-validate="{ rules: { required: adminsData.discountcpn_in_percent == 1 ? true : false, regex:  /^\d+(\.\d{1,2})?$/} }"
                        :data-vv-as="$t('LBL_MAX_AMOUNT_LIMIT')"
                        data-vv-validate-on="none"
                        class="form-control"
                        :readonly="adminsData.discountcpn_in_percent != 1"
                      />
                      <span
                        v-if="errors.first('discountcpn_maxamt_limit')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_maxamt_limit') }}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                        {{adminsData.discountcpn_in_percent != 1 ? $t('LBL_AMOUNT') : $t('LBL_PERCENTAGE')}}
                        <span
                          class="required"
                        >*</span>
                      </label>
                      <input
                        type="text"
                        v-model="adminsData.discountcpn_amount"
                        name="discountcpn_amount"
                        @keypress="$onlyNumber"
                        v-validate="{ rules: { required: true, decimal:decimalValues, min_value:1, max_value: adminsData.discountcpn_in_percent == 1 ? 99 : 1000000} }"
                        :data-vv-as="$t('LBL_AMOUNT')"
                        data-vv-validate-on="none"
                        class="form-control"
                      />
                      <span
                        v-if="errors.first('discountcpn_amount')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_amount') }}</span>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                        {{ $t('LBL_COUPON_START_DATE') }}
                        <span class="required">*</span>
                      </label>
                      <date-pick
                        v-model="adminsData.discountcpn_startdate"
                        name="discountcpn_startdate"
                        v-validate="'required'"
                        :data-vv-as="$t('LBL_COUPON_START_DATE')"
                        data-vv-validate-on="none"
                        :pickTime="true"
                        :parseDate="parseStartDate"
                        :formatDate="formatStartDate"
                        :format="$dateTimeFormat()"
                        :isDateDisabled="isPastDate"
                        :inputAttributes="{class: 'form-control', readonly: true}"
                        class="d-block"
                      ></date-pick>
                      <span
                        v-if="errors.first('discountcpn_startdate')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_startdate') }}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                        {{ $t('LBL_COUPON_END_DATE') }}
                        <span class="required">*</span>
                      </label>
                      <date-pick
                        v-model="adminsData.discountcpn_enddate"
                        v-validate="'required'"
                        name="discountcpn_enddate"
                        :data-vv-as="$t('LBL_COUPON_END_DATE')"
                        data-vv-validate-on="none"
                        :pickTime="true"
                        :parseDate="parseEndDate"
                        :formatDate="formatEndDate"
                        :format="$dateTimeFormat()"
                        :isDateDisabled="isPastDate"
                        :inputAttributes="{class: 'form-control', readonly: true}"
                        class="d-block"
                      ></date-pick>
                      <span
                        v-if="errors.first('discountcpn_enddate')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_enddate') }}</span>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                        {{ $t('LBL_MIN_ORDER_AMOUNT') }}
                        <span class="required">*</span>
                      </label>
                      <input
                        type="text"
                        v-model="adminsData.discountcpn_minorderamt"
                        name="discountcpn_minorderamt"
                        v-validate="{ rules: { required: true, regex:  /^\d+(\.\d{1,2})?$/} }"
                        :data-vv-as="$t('LBL_MIN_ORDER_AMOUNT')"
                        data-vv-validate-on="none"
                        class="form-control"
                      />
                      <span
                        v-if="errors.first('discountcpn_minorderamt')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_minorderamt') }}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                        {{ $t('LBL_AVAILABLE_FOR_GUEST_USERS') }}
                        <span class="required">*</span>
                      </label>
                      <select
                        class="form-control"
                        v-model="adminsData.discountcpn_for_guest"
                        name="prod_type"
                        v-validate="'required'"
                        :data-vv-as="$t('LBL_AVAILABLE_FOR_GUEST_USERS')"
                        data-vv-validate-on="none"
                      >
                        <option
                          v-for="(available, availableKey) in availableCoupons"
                          :key="availableKey"
                          :value="availableKey"
                        >{{available}}</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label>{{ $t('LBL_IMAGE') }}</label>
                    <div
                      data-ratio="1:1"
                      class="dropzone dropzone-default dropzone-brand dz-clickable ratio-1by1"
                      @click="(croppedImageView == '') ? [$refs.cropperRef.openCropper(), fileUploadClass = true]  : ''"
                      @mouseover="fileUploadClass = true"
                      @mouseleave="fileUploadClass = false"
                    >
                      <div class="upload_cover">
                        <div class="img--container uploded__img" v-if="croppedImageView != ''">
                          <img :src="croppedImageView" />
                          <div class="upload__action">
                            <button
                              type="button"
                              @click="removeImage($event, attachedFile)"
                              v-if="croppedImageView != ''"
                            >
                              <svg>
                                <use
                                  :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"
                                />
                              </svg>
                            </button>
                            <button
                              type="button"
                              @click="$refs.cropperRef.openCropper()"
                              v-if="croppedImageView != ''"
                            >
                              <svg>
                                <use
                                  :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'"
                                />
                              </svg>
                            </button>
                          </div>
                        </div>
                        <div clas="img--container  ">
                          <div
                            class="file-upload"
                            v-bind:class="{ isactive: fileUploadClass, fileVisiblity: fileVisiblity}"
                          >
                            <img :src="baseUrl+'/admin/images/upload/upload_img.png'" />
                          </div>
                        </div>
                        <div
                          class="dropzone-msg dz-message needsclick"
                          v-if="croppedImageView == ''"
                        >
                          <h3 class="dropzone-msg-title">{{ $t('LBL_CLICK_HERE_TO_UPLOAD')}}</h3>
                        </div>
                      </div>
                    </div>
                    <cropper
                      ref="cropperRef"
                      :title="adminsData.discountcpn_code"
                      :icon="false"
                      :aspectRatio="1"
                      :width="250"
                      :height="250"
                      v-on:childToParent="setImage"
                      v-on:actualImageToParent="setActualImage"
                    ></cropper>
                    <img :src="originalImage" id="originalImage" style="display: none;" />
                  </div>

                  <div class="col-md-12">
                    <p class="img-disclaimer py-2">
                      <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
                      {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 250x250 ' + $t('LBL_IN') + ' 1:1 ' + $t('LBL_ASPECT_RATIO') }}
                    </p>
                  </div>
                </div>
              </div>
              <div v-if="tabForm == 'link'">
                <div class="row">
                  <div class="col-md-12">
                    <label>{{ $t('LBL_SELECT_OPERATOR') }}</label>
                    <div class="form-group">
                      <select
                        class="form-control"
                        v-model="adminsData.discountcpn_operator"
                        name="prod_type"
                        v-validate="'required'"
                        :data-vv-as="$t('LBL_CONDITION_OPERATOR')"
                        data-vv-validate-on="none"
                      >
                        <option
                          v-for="(operator, operatorKey) in operators"
                          :key="operatorKey"
                          :value="operatorKey"
                        >{{operator}}</option>
                      </select>
                      <span
                        v-if="errors.first('discountcpn_operator')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_operator') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label>{{ $t('LBL_LINK_PRODUCTS') }}</label>
                    <div class="form-group">
                      <treeselect
                        :multiple="true"
                        :options="options"
                        :placeholder="$t('PLH_START_TYPING_HERE')"
                        v-model="productValues"
                        @search-change="productSearch"
                      />
                      <span
                        v-if="errors.first('discountcpn_products')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_products') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label>{{ $t('LBL_LINK_CATEGORIES') }}</label>
                    <div class="form-group">
                      <treeselect
                        name="ptc_bpcat_id"
                        v-model="linkCategories"
                        :multiple="true"
                        :defaultExpandLevel="Infinity"
                        :clearable="false"
                        :isDefaultExpanded="true"
                        :options="categories"
                        :open-on-click="true"
                        :close-on-select="true"
                        :placeholder="$t('PLH_SELECT_CATEGORY')"
                      />
                      <span
                        v-if="errors.first('discountcpn_categories')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_categories') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label>{{ $t('LBL_LINK_BRANDS') }}</label>
                    <div class="form-group">
                      <vue-tags-input
                        class="vue-tags-style"
                        v-model="linkBrand"
                        products
                        :tags="linkBrands"
                        :autocomplete-items="autocompleteBrands"
                        @tags-changed="updateBrandTags"
                        :add-only-from-autocomplete="true"
                        :placeholder="$t('PLH_START_TYPING_HERE')"
                      ></vue-tags-input>
                      <span
                        v-if="errors.first('discountcpn_brands')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_brands') }}</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label>{{ $t('LBL_LINK_USERS') }}</label>
                    <div class="form-group mb-0">
                      <vue-tags-input
                        class="vue-tags-style"
                        v-model="linkUser"
                        :tags="linkUsers"
                        :autocomplete-items="autocompleteUsers"
                        @tags-changed="updateUserTags"
                        :add-only-from-autocomplete="true"
                        :placeholder="$t('PLH_START_TYPING_HERE')"
                      ></vue-tags-input>
                      <span
                        v-if="errors.first('discountcpn_users')"
                        class="error text-danger"
                      >{{ errors.first('discountcpn_users') }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="portlet__body" v-if="selectedPage == ''">
              <div class="no-data-found">
                <div class="img">
                  <img :src="this.$noDataImage()" alt />
                </div>
                <div class="data">
                  <p>{{ $t ('LBL_USE_ICONS_FOR_ADVANCED_ACTIONS') }}</p>
                </div>
              </div>
            </div>
            <div class="portlet__foot" v-if="selectedPage != ''">
              <div class="row">
                <div class="col">
                  <button
                    type="reset"
                    class="btn btn-secondary"
                    @click="cancel()"
                  >{{ $t('BTN_DISCARD')}}</button>
                </div>
                <div class="col-auto">
                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    v-if="tabForm == 'link' && selectedPage == 'editform'"
                    :disabled="!isComplete || clicked==1 || (!$canWrite('DISCOUNT_COUPONS'))"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{ $t('BTN_UPDATE_COUPON') }}</button>
                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    :disabled="!isComplete || clicked==1 || (!$canWrite('DISCOUNT_COUPONS'))"
                    v-if="selectedPage == 'addform' && tabForm != 'general'"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{ $t('BTN_SAVE_COUPON') }}</button>
                  <button
                    type="button"
                    class="btn btn-brand"
                    @click="tabFormChange('link', $event)"
                    :disabled="couponAlreadyExists"
                    v-if="tabForm == 'general'"
                  >{{ $t('BTN_NEXT') }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <DeleteModel
          :deletePopText="deleteStatus.message"
          :subText="deleteStatus.subMessage"
          :recordId="deleteStatus.id"
          @deleted="deleteRecord(recordId)"
        ></DeleteModel>
      </div>
    </div>
  </div>
</template>
<script>
import DatePick from "vue-date-pick";
import "vue-date-pick/dist/vueDatePick.css";
import fecha from "fecha";

import VueTagsInput from "@johmun/vue-tags-input";
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";

const tableFields = {
  discountcpn_id: "",
  discountcpn_code: "",
  discountcpn_in_percent: "0",
  discountcpn_type: "0",
  discountcpn_total_uses: "",
  discountcpn_uses_per_user: "",
  discountcpn_maxamt_limit: "",
  discountcpn_minorderamt: "",
  discountcpn_for_guest: 0,
  discountcpn_amount: "",
  discountcpn_operator: 0,
  discountcpn_startdate: new Date(),
  discountcpn_enddate: new Date()
};
const searchFields = {
  search: ""
};
export default {
  components: {
    DatePick,
    VueTagsInput,
    Treeselect
  },
  data: function() {
    return {
      adminsData: tableFields,
      baseUrl: baseUrl,
      decimalValues: decimalValues,
      selectedPage: false,
      records: [],
      operators: [],
      availableCoupons: [],
      recordId: "",
      modelId: "deleteModel",
      search: "",
      searchData: searchFields,
      pagination: [],
      clicked: 0,
      loading: false,
      autocompleteProducts: [],
      linkCategory: "",
      linkCategories: [],
      autocompleteCategories: [],
      categories: [],
      linkBrand: "",
      linkBrands: [],
      autocompleteBrands: [],
      linkUser: "",
      linkUsers: [],
      productValues: [],
      options: [],
      autocompleteUsers: [],
      croppedImage: "",
      croppedImageView: "",
      originalImage: "",
      uploadedFile: "",
      tabForm: "general",
      hidePublish: false,
      currentDate: "",
      createdByUser: "",
      updatedByUser: "",
      updatedAt: "",
      createdAt: "",
      pageLoaded: 0,
      showEmpty: 1,
      fileUploadClass: false,
      fileVisiblity: false,
      attachedFile: "",
      deleteStatus: {},
      couponAlreadyExists: false
    };
  },
  watch: {
    linkProduct: "initProducts",
    linkBrand: "initBrands",
    linkUser: "initUsers",
    $route: "refreshedSearchData"
  },
  computed: {
    isComplete() {
      return (
        this.adminsData.discountcpn_code &&
        this.adminsData.discountcpn_total_uses &&
        this.adminsData.discountcpn_uses_per_user &&
        this.adminsData.discountcpn_startdate &&
        this.adminsData.discountcpn_enddate &&
        (this.adminsData.discountcpn_in_percent == 1
          ? this.adminsData.discountcpn_maxamt_limit
          : true) &&
        this.adminsData.discountcpn_amount
      );
    }
  },
  methods: {
    refreshedSearchData() {
      if (this.$route.query.s) {
        this.searchData.search = this.$route.query.s;
      }
      this.pageRecords(1, "", true);
    },
    parseStartDate(dateString, format) {
      return fecha.parse(dateString, format);
    },
    formatStartDate(dateObj, format) {
      return fecha.format(dateObj, format);
    },
    parseEndDate(dateString, format) {
      return fecha.parse(dateString, format);
    },
    formatEndDate(dateObj, format) {
      return fecha.format(dateObj, format);
    },
    setImage: function(cropUrl) {
      this.croppedImage = cropUrl;
      this.croppedImageView = URL.createObjectURL(cropUrl);
    },
    setActualImage: function(actualImage) {
      this.fileVisiblity = true;
      this.fileUploadClass = false;
      if (typeof actualImage != "string") {
        this.originalImage = URL.createObjectURL(actualImage);
        this.uploadedFile = actualImage;
      } else {
        this.uploadedFile = this.originalImage = actualImage;
      }
    },
    isPastDate: function(date) {
      const currentDate = new Date().setDate(new Date().getDate() - 1);
      return date < currentDate;
    },
    updateCategoryTags(newTags) {
      this.autocompleteCategories = [];
      this.linkCategories = newTags;
    },
    updateBrandTags(newTags) {
      this.autocompleteBrands = [];
      this.linkBrands = newTags;
    },
    updateUserTags(newTags) {
      this.autocompleteUsers = [];
      this.linkUsers = newTags;
    },
    productSearch: function(includesTag) {
      if (includesTag < 2) return;

      this.$http
        .get(
          adminBaseUrl +
            "/discount-coupons/search?query=" +
            includesTag +
            "&type=products"
        )
        .then(response => {
          this.options = this.setProductTags(response.data.data);
        });
    },
    setProductTags: function(response) {
      return response.map(a => {
        if (a.varients.length > 0) {
          return {
            label: a.record_title,
            id: a.record_id,
            children: this.bindChildrens(a.record_title, a.varients)
          };
        } else {
          return {
            label: a.record_title,
            id: a.record_id
          };
        }
      });
    },
    bindChildrens: function(title, varients) {
      return varients.map(a => {
        return {
          label: title + " " + a.pov_display_title.replace("_", " | "),
          id: a.pov_code
        };
      });
    },
    async initBrands() {
      if (this.linkBrand.length < 2) return;
      const res = await fetch(
        adminBaseUrl +
          "/discount-coupons/search?query=" +
          this.linkBrand +
          "&type=brands"
      );
      const response = await res.json();
      this.autocompleteBrands = response.data.map(a => {
        return {
          text: a.record_title,
          id: a.record_id
        };
      });
    },
    async initUsers() {
      if (this.linkUser.length < 2) return;

      const res = await fetch(
        adminBaseUrl +
          "/discount-coupons/search?query=" +
          this.linkUser +
          "&type=users"
      );
      const response = await res.json();
      this.autocompleteUsers = response.data.map(a => {
        return {
          text: a.record_title,
          id: a.record_id
        };
      });
    },
    currentPage: function(page) {
      this.pageRecords(page);
    },
    searchRecords: function() {
      this.pageRecords(this.pagination.current_page);
    },
    tabFilter: function(type) {
      if (type == "expired") {
        this.hidePublish = true;
      } else {
        this.hidePublish = false;
      }
      this.pageRecords(1, type);
    },
    pageRecords: function(pageNo, type, pageLoad = false) {
      this.loading = pageLoad;
      if (typeof type !== "undefined") {
        Object.assign(this.searchData, {
          type: type
        });
      }
      let formData = this.$serializeData(this.searchData);
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(adminBaseUrl + "/discount-coupons/list?page=" + pageNo, formData)
        .then(response => {
          this.records = response.data.data.coupons.data;
          this.pagination = response.data.data.coupons;
          this.categories = response.data.data.categories;
          this.operators = response.data.data.operators;
          this.availableCoupons = response.data.data.available_coupons;
          this.loading = false;
          this.showEmpty = response.data.data.showEmpty;
          this.pageLoaded = 1;
        });
    },
    generateCode: function() {
      this.adminsData.discountcpn_code = this.$rndStr();
    },
    openAddPage: function() {
      this.emptyFormValues();
      this.cancel();
      var self = this;
      setTimeout(function() {
        self.selectedPage = "addform";
        self.tabForm = "general";
        self.croppedImageView = self.croppedImage = self.originalImage = self.uploadedFile =
          "";
      }, 10);
      if (this.searchData.search != "") {
        this.searchData.search = "";
        this.pageRecords();
      }
      this.showEmpty = 0;
      this.emptyImageData();
    },
    editRecord: function(record) {
      this.emptyFormValues();
      this.emptyUpdatedFieldValue();
      this.adminsData.discountcpn_id = record.discountcpn_id;
      this.adminsData.discountcpn_code = record.discountcpn_code;
      this.adminsData.discountcpn_in_percent = record.discountcpn_in_percent;
      this.adminsData.discountcpn_type = record.discountcpn_type;
      this.adminsData.discountcpn_total_uses = record.discountcpn_total_uses;
      this.adminsData.discountcpn_uses_per_user =
        record.discountcpn_uses_per_user;
      this.adminsData.discountcpn_maxamt_limit =
        record.discountcpn_maxamt_limit;
      this.adminsData.discountcpn_minorderamt = record.discountcpn_minorderamt;
      this.adminsData.discountcpn_amount = record.discountcpn_amount;
      this.adminsData.discountcpn_for_guest = record.discountcpn_for_guest;
      this.adminsData.discountcpn_operator = record.discountcpn_operator;
      this.adminsData.discountcpn_startdate = moment
        .utc(String(record.discountcpn_startdate))
        .tz(window.localStorage.getItem("timezone"))
        .format(this.$dateTimeFormat());
      this.adminsData.discountcpn_enddate = moment
        .utc(String(record.discountcpn_enddate))
        .tz(window.localStorage.getItem("timezone"))
        .format(this.$dateTimeFormat());
      this.tabForm = "general";
      this.selectedPage = "editform";
      this.croppedImageView = this.croppedImage = this.originalImage = this.uploadedFile =
        "";
      if (record.created_at != null && "admin_username" in record.created_at) {
        this.createdByUser = record.created_at.admin_username;
      }
      if (record.updated_at != null && "admin_username" in record.updated_at) {
        this.updatedByUser = record.updated_at.admin_username;
      }
      this.updatedAt = record.discountcpn_updated_at
        ? record.discountcpn_updated_at
        : "";
      this.createdAt = record.discountcpn_created_at
        ? record.discountcpn_created_at
        : "";
      if (record.afile_id != null) {
        this.fileVisiblity = true;
        this.fileUploadClass = false;
        this.croppedImage = this.croppedImageView = this.$getFileUrl(
          "discountCouponImage",
          record.discountcpn_id,
          0,
          "thumb",
          this.$timestamp(record.discountcpn_updated_at)
        );
        this.originalImage = this.$getFileUrl(
          "discountCouponImage",
          record.discountcpn_id,
          0,
          "original",
          this.$timestamp(record.discountcpn_updated_at)
        );
        this.attachedFile = record.afile_id;
      } else {
        this.attachedFile = "";
        this.emptyImageData();
      }
      this.$http
        .get(
          adminBaseUrl +
            "/discount-coupons/included?id=" +
            record.discountcpn_id
        )
        .then(response => {
          this.options = this.setEditProductTags(response.data.data.products);
          this.setEditSelectedTags(response.data.data.products);
          this.linkCategories = response.data.data.categories.map(a => {
            return a.record_id;
          });
          this.linkBrands = response.data.data.brands.map(a => {
            return {
              text: a.record_title,
              id: a.record_id
            };
          });
          this.linkUsers = response.data.data.users.map(a => {
            return {
              text: a.record_title,
              id: a.record_id
            };
          });
        });
    },
    checkCouponAlreadyCreated: function() {
        let formData = this.$serializeData({discountcpn_code : this.adminsData.discountcpn_code});
        this.$http.post(adminBaseUrl + "/discount-coupons/check/" + this.adminsData.discountcpn_id, formData).then(
            response => {
                if(response.data.status){
                    this.couponAlreadyExists=false;
                    this.errors.clear();
                }
        },
        response => {
          //error
          this.couponAlreadyExists = true;
          this.validateErrors(response);
        }
      );
    },
    emptyFormValues: function() {
      this.adminsData = {
        discountcpn_id: "",
        discountcpn_code: "",
        discountcpn_in_percent: "0",
        discountcpn_type: "0",
        discountcpn_total_uses: "",
        discountcpn_uses_per_user: "",
        discountcpn_maxamt_limit: "",
        discountcpn_minorderamt: "",
        discountcpn_amount: "",
        discountcpn_startdate: "",
        discountcpn_enddate: "",
        discountcpn_operator: 0,
        discountcpn_for_guest: 0
      };
      this.productValues = [];
      this.linkCategories = [];
      this.linkBrands = [];
      this.linkUsers = [];
      this.errors.clear();
    },
    validateRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          this.clicked = 1;
          let input = this.adminsData;
          if (input.discountcpn_id != "") {
            this.updateRecord(this.adminsData);
          } else {
            this.saveRecord(input);
          }
        } else {
          this.clicked = 0;
        }
      });
    },
    tabFormChange: function(type, event) {
      event.preventDefault();
      this.$validator.validateAll().then(res => {
        if (res) {
          this.tabForm = type;
          if (type == "link") {
            this.productSearch("");
          }
        }
      });
    },
    setEditProductTags: function(response) {
      let id = "";
      let title = "";
      return response.map(a => {
        title = a.record_title;
        id = a.record_id;
        if (a.pov_display_title != null) {
          title =
            a.record_title + " - " + a.pov_display_title.replace("_", " | ");
          id = a.pov_code;
        }
        return {
          label: title,
          id: id
        };
      });
    },
    setEditSelectedTags: function(response) {
      let id = "";
      response.map(a => {
        id = a.record_id;
        if (a.pov_display_title != null) {
          id = a.pov_code;
        }
        this.productValues.push(id);
      });
    },
    updateRecord: function(input) {
      input.products = JSON.stringify(this.productValues);
      input.categories = JSON.stringify(this.linkCategories);
      input.brands = JSON.stringify(this.linkBrands);
      input.users = JSON.stringify(this.linkUsers);
      let formData = this.$serializeData(input);
      formData.append("actualImage", this.uploadedFile);
      formData.append("cropImage", this.croppedImage);

      if (
        this.adminsData.discountcpn_in_percent != 1 &&
        this.adminsData.discountcpn_maxamt_limit == ""
      ) {
        formData.append("discountcpn_maxamt_limit", 0);
      }

      formData.append("_method", "PUT");
      this.$http
        .post(
          adminBaseUrl + "/discount-coupons/" + input.discountcpn_id,
          formData
        )
        .then(
          response => {
            this.clicked = 0;
            //success
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
            this.pageRecords(this.pagination.current_page);
            this.emptyFormValues();
            this.cancel();
          },
          response => {
            //error
            this.clicked = 0;
            this.validateErrors(response);
          }
        );
    },
    confirmDelete: function(event, dataid) {
      event.stopPropagation();
      this.recordId = dataid;
      this.deleteStatus.id = dataid;
      this.deleteStatus = {
        message: this.$t("LBL_DELETE_COUPON_TEXT"),
        subMessage: "",
        id: dataid
      };
      this.$bvModal.show(this.modelId);
    },
    deleteRecord: function(recordId) {
      if (this.attachedFile != "") {
        this.$http
          .delete(adminBaseUrl + "/remove-attached-files/" + recordId)
          .then(response => {
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            this.emptyImageData();
            this.deleteStatus.type = 0;
            this.attachedFile = "";
            toastr.success(response.data.message, "", toastr.options);
            this.pageRecords(this.pagination.current_page);
          });
      } else if (this.deleteStatus.type == 0) {
        this.emptyImageData();
      } else {
        this.$http
          .delete(adminBaseUrl + "/discount-coupons/" + recordId)
          .then(response => {
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
            this.pageRecords(this.pagination.current_page);
            this.$bvModal.hide(this.modelId);
          });
      }
      this.$bvModal.hide(this.modelId);
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
    saveRecord: function(input) {
      input.categories = JSON.stringify(this.linkCategories);
      input.brands = JSON.stringify(this.linkBrands);
      input.users = JSON.stringify(this.linkUsers);
      input.products = JSON.stringify(this.productValues);
      let formData = this.$serializeData(input);
      formData.append("actualImage", this.uploadedFile);
      formData.append("cropImage", this.croppedImage);
      if (
        this.adminsData.discountcpn_in_percent != 1 &&
        this.adminsData.discountcpn_maxamt_limit == ""
      ) {
        formData.append("discountcpn_maxamt_limit", 0);
      }
      this.$http.post(adminBaseUrl + "/discount-coupons", formData).then(
        response => {
          //success
          this.clicked = 0;
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords(this.pagination.current_page);
          this.emptyFormValues();
          this.selectedPage = false;
        },
        response => {
          //error
          this.clicked = 0;
          this.validateErrors(response);
        }
      );
    },
    onSwitchStatus: function(event, id) {
      let formData = this.$serializeData({
        status: event.target.checked
      });
      this.$http
        .post(adminBaseUrl + "/discount-coupons/status/" + id, formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    cancel: function() {
      this.selectedPage = false;
    },
    checkCouponExpire: function(date) {
      let couponDate = new Date(date);
      if (moment(couponDate).valueOf() < this.currentDate) {
        return true;
      } else {
        return false;
      }
    },
    emptyUpdatedFieldValue: function() {
      this.createdByUser = "";
      this.updatedByUser = "";
      this.updatedAt = "";
      this.createdAt = "";
    },
    emptyImageData: function() {
      this.croppedImage = "";
      this.croppedImageView = "";
      this.originalImage = "";
      this.uploadedFile = "";
      this.fileUploadClass = false;
      this.fileVisiblity = false;
    },
    removeImage: function(event, afileId) {
      event.stopPropagation();
      this.deleteStatus.message = this.$t("LBL_DELETE_IMAGE_TEXT");
      this.deleteStatus.subMessage = "";
      if (afileId != "") {
        this.deleteStatus.type = 1;
        this.recordId = this.attachedFile = afileId;
      } else {
        this.deleteStatus.type = 0;
      }
      this.$bvModal.show(this.modelId);
    }
  },
  mounted: function() {
    this.searchData.search = "";
    this.currentDate = Date.now();
    this.refreshedSearchData();
  }
};
</script>