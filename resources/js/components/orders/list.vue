<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_ORDERS') }}</h3>
        </div>
        <div class="subheader__toolbar">
          <div class="subheader__wrapper">
            <router-link
              :to="{name: 'addOrder'}"
              class="btn btn-white subheader__btn-options"
              v-bind:class="[(!$canWrite('ADD_ORDERS')) ? 'disabledbutton': '']"
            >
              <i class="fas fa-plus"></i>
              {{ $t('BTN_ADD') }}
            </router-link>
            <a
              href="javascript:void(0);"
              v-bind:class="[(!$canWrite('ADD_ORDERS') || (orders.length == 0)) ? 'disabledbutton': '']"
              class="btn btn-subheader"
              @click="openExportPopup"
            >
              <i class="fas fa-cloud-download-alt"></i>
              {{ $t('BTN_EXPORT') }}
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="portlet portlet--height-fluid portlet--tabs">
            <div
              class="portlet__head"
              v-if="returnstatus == 1 || returnstatus == 2 || showEmpty == 0"
            >
              <div class="portlet__head-toolbar">
                <ul
                  class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold"
                  role="tablist"
                >
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      v-bind:class="[tabtype === 'all' ? 'active' : '']"
                      data-toggle="tab"
                      href="javascript:;"
                      @click="switchPage('all')"
                      role="tab"
                      aria-selected="true"
                    >All</a>
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      v-bind:class="[tabtype === 'open' ? 'active' : '']"
                      data-toggle="tab"
                      href="javascript:;"
                      @click="switchPage('')"
                      role="tab"
                      aria-selected="true"
                    >{{$t('LNK_SHIP')}}</a>
                  </li>
                  <li class="nav-item" v-if="pickupEnabled == 1 || tabtype == 'pickup'">
                    <a
                      class="nav-link"
                      v-bind:class="[tabtype === 'pickup' ? 'active' : '']"
                      data-toggle="tab"
                      href="javascript:;"
                      @click="switchPage('pickup')"
                      role="tab"
                      aria-selected="true"
                    >{{$t('LNK_PICKUP')}}</a>
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      v-bind:class="[tabtype === 'unpaid' ? 'active' : '']"
                      data-toggle="tab"
                      href="javascript:;"
                      role="tab"
                      @click="switchPage('pstatus', 'PENDING')"
                      aria-selected="false"
                    >{{$t('LNK_UNPAID')}}</a>
                  </li>
                  <li
                    class="nav-item"
                    v-for="(YK_rstatus, rIndex) in orderReturnStatus"
                    :key="rIndex"
                  >
                    <a
                      class="nav-link"
                      v-bind:class="[tabtype === rIndex ? 'active' : '']"
                      data-toggle="tab"
                      href="javascript:;"
                      role="tab"
                      @click="switchPage('returnstatus', rIndex)"
                      aria-selected="false"
                    >{{YK_rstatus}}</a>
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      v-bind:class="[tabtype === 'completed' ? 'active' : '']"
                      data-toggle="tab"
                      href="javascript:;"
                      role="tab"
                      @click="switchPage('ostatus', 4)"
                      aria-selected="false"
                    >{{$t('LNK_COMPLETED')}}</a>
                  </li>
                </ul>
              </div>

              <div
                class="portlet__head-toolbar"
                v-if="pstatus != 'PENDING'  && status!='4' && tabtype != 'all'"
              >
                <ul
                  class="nav nav-tabs nav-tabs-bold nav-tabs-line nav-tabs-line-brand nav-tabs-line-right nav-tabs-line-danger"
                  role="tablist"
                  v-if="$canWrite('ORDERS')"
                >
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      v-bind:class="[view === 'list' ? 'active' : '']"
                      href="javascript:;"
                      @click="switchView('list')"
                    >
                      <i class="fas fa-th-large"></i>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      v-bind:class="[view != 'list' ? 'active' : '']"
                      href="javascript:;"
                      @click="switchView('kanban')"
                    >
                      <i class="fas fa-table"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="portlet__body pb-0 bg-formx flex-grow-0">
              <div class="tab-content">
                <div class="tab-pane active" id="portlet_tab_content_1" role="tabpanel">
                  <div class="mb-4">
                    <div
                      class="row"
                      v-if="returnstatus == 1 || returnstatus == 2 || showEmpty == 0"
                    >
                      <div class="col">
                        <div class="input-group input-icon input-icon--left">
                          <input
                            type="text"
                            class="form-control"
                            :placeholder="$t('PLH_ORDER_SEARCH')"
                            @keypress.13.prevent="validateSearch"
                            name="searchByKeyword"
                            v-model="searchByKeyword"
                            v-validate="'required'"
                            :data-vv-as="$t('LBL_SEARCH')"
                            data-vv-validate-on="none"
                          />
                          <span class="input-icon__icon input-icon__icon--left">
                            <span>
                              <i class="la la-search"></i>
                            </span>
                          </span>
                          <div class="input-group-append" v-if="pstatus == ''">
                            <button
                              type="button"
                              class="btn btn-default dropdown-toggle"
                              data-toggle="dropdown"
                            >{{$t("BTN_PAYMENT_STATUS")}}</button>
                            <div class="dropdown-menu" x-placement="bottom-start">
                              <div>
                                <ul class="nav nav--block list-checkbox-radio">
                                  <li
                                    class="dropdown-item nav__item"
                                    v-for="(paymentFilter, pFilterIndex) in paymentFilters"
                                    :key="pFilterIndex"
                                  >
                                    <label class="checkbox">
                                      <input
                                        type="checkbox"
                                        :value="pFilterIndex"
                                        v-model="orderSearch.payment_status"
                                        @change="searchRecords()"
                                      />
                                      {{paymentFilter}}
                                      <span></span>
                                    </label>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div
                            class="input-group-append"
                            v-if="view == 'list'  && status != 4 && tabtype != 'unpaid' && tabtype != 'all'"
                          >
                            <button
                              type="button"
                              class="btn btn-default dropdown-toggle"
                              data-toggle="dropdown"
                            >{{$t("BTN_FULFILMENT_STATUS")}}</button>
                            <div class="dropdown-menu" x-placement="bottom-start">
                              <div>
                                <ul
                                  class="nav nav--block list-checkbox-radio"
                                  v-if="tabtype == 'open'"
                                >
                                  <li
                                    class="dropdown-item nav__item"
                                    v-for="(shipping, shippingKey) in shippingStatus"
                                    :key="shippingKey"
                                  >
                                    <label class="checkbox">
                                      <input
                                        type="checkbox"
                                        :value="shippingKey"
                                        v-model="orderSearch.fulfillment"
                                        @change="searchRecords()"
                                      />
                                      {{shipping | removeHyphen | camelCase}}
                                      <span></span>
                                    </label>
                                  </li>
                                </ul>
                                <ul
                                  class="nav nav--block list-checkbox-radio"
                                  v-if="tabtype == 'pickup'"
                                >
                                  <li
                                    class="dropdown-item nav__item"
                                    v-for="(pickup, pickupKey) in pickupStatus"
                                    :key="pickupKey"
                                  >
                                    <label class="checkbox">
                                      <input
                                        type="checkbox"
                                        :value="pickupKey"
                                        v-model="orderSearch.fulfillment"
                                        @change="searchRecords()"
                                      />
                                      {{pickup | removeHyphen | camelCase}}
                                      <span></span>
                                    </label>
                                  </li>
                                </ul>

                                <ul class="nav nav--block list-checkbox-radio" v-if="returnstatus">
                                  <li
                                    class="dropdown-item nav__item"
                                    v-for="(returnRequests, returnRequestskey) in orderReturnRequests"
                                    :key="returnRequestskey"
                                    v-if="tabtype == 1 || returnRequestskey != 1"
                                  >
                                    <label class="checkbox">
                                      <input
                                        type="checkbox"
                                        :value="returnRequestskey"
                                        v-model="orderSearch.fulfillment"
                                        @change="searchRecords()"
                                      />
                                      {{returnRequests | removeHyphen | camelCase}}
                                      <span></span>
                                    </label>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="input-group-append">
                            <date-picker
                              class="custom-date-range"
                              v-model="orderSearch.dateRange"
                              type="date"
                              range
                              value-type="format"
                              :parseDate="parseDate"
                              :formatDate="formatDate"
                              :format="$dateTimeFormat(false)"
                              :placeholder="$t('PLH_DATE_RANGE')"
                              @change="searchRecords()"
                            ></date-picker>
                          </div>
                        </div>
                        <span
                          v-if="errors.first('searchByKeyword')"
                          class="error text-danger"
                        >{{ errors.first('searchByKeyword') }}</span>
                      </div>
                      <div class="col-auto">
                        <button
                          type="button"
                          class="btn btn-default dropdown-toggle"
                          data-toggle="dropdown"
                        >
                          <i class="fas fa-sort"></i>
                          <span v-if="orderSearch.sortby == 'order_id'">{{$t('LBL_LATEST')}}</span>
                          <span
                            v-else-if="orderSearch.sortby == 'order_net_amount'"
                          >{{$t('LBL_AMOUNT')}}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-anim">
                          <div>
                            <ul class="nav nav--block list-checkbox-radio">
                              <li class="dropdown-item nav__item">
                                <label class="radio">
                                  <input
                                    type="radio"
                                    name="sortby"
                                    value="order_id"
                                    v-model="orderSearch.sortby"
                                    @change="searchRecords()"
                                  />
                                  {{$t('LBL_LATEST')}}
                                  <span></span>
                                </label>
                              </li>
                              <li class="dropdown-item nav__item">
                                <label class="radio">
                                  <input
                                    type="radio"
                                    name="sortby"
                                    value="order_net_amount"
                                    v-model="orderSearch.sortby"
                                    @change="searchRecords()"
                                  />
                                  {{$t('LBL_AMOUNT')}}
                                  <span></span>
                                </label>
                              </li>
                            </ul>
                          </div>
                        </div>
                        <button
                          class="btn btn-light btn-icon"
                          v-b-tooltip.hover
                          :title="orderSearch.keywords.length == 0 && orderSearch.payment_status == 0 && orderSearch.fulfillment == 0 && orderSearch.dateRange == '' &&  orderSearch.sortby == 'order_id' ? '' : $t('TTL_CLEAR_FILTERS')"
                          @click="clearSearch()"
                          :disabled="orderSearch.keywords.length == 0 && orderSearch.payment_status == 0 && orderSearch.fulfillment == 0 && orderSearch.dateRange == '' &&  orderSearch.sortby == 'order_id'"
                        >
                          <i class="fas fa-trash-restore-alt"></i>
                        </button>
                      </div>
                    </div>
                    <ul class="tagify-custom my-2">
                      <li
                        v-for="(searchPStatus, pIndex) in orderSearch.payment_status"
                        :key="pIndex"
                      >
                        <span>{{paymentFilters[searchPStatus]}}</span>
                        <a
                          href="javascript:;"
                          @click="removeKeyword(pIndex, 'paymentStatus')"
                          class="remove"
                        >
                          <i class="fas fa-times"></i>
                        </a>
                      </li>
                      <li
                        v-for="(searchKeyword, searchIndex) in orderSearch.keywords"
                        :key="searchIndex"
                      >
                        <span>{{searchKeyword}}</span>
                        <a href="javascript:;" @click="removeKeyword(searchIndex)" class="remove">
                          <i class="fas fa-times"></i>
                        </a>
                      </li>
                    </ul>
                  </div>
                  <kanban-view
                    :returnstatus="returnstatus"
                    :orderSearch="orderSearch"
                    :updateSearch="updateSearch"
                    :tabtype="tabtype"
                    v-if="view != 'list' && pstatus != 'PENDING' &&  tabtype != 'all' && status != 4"
                    @quickViewOrder="quickViewOrder"
                    @kanbanList="kanbanList"
                  ></kanban-view>
                </div>
              </div>
            </div>
            <hr class="m-0" v-if="showEmpty == 0" />

            <div class="portlet__body" v-if="showEmpty == 1 && pageLoaded==1">
              <div class="get-started">
                <div class="get-started-arrow d-flex justify-content-end mb-5">
                  <img
                    :src="baseUrl+'/admin/images/get-started-graphic/get-started-arrow-head.svg'"
                  />
                </div>
                <div class="no-content d-flex text-center flex-column mb-7">
                  <p>
                    {{$t("LBL_CLICK_THE")}}
                    <router-link :to="{name: 'addOrder'}" class="btn btn-brand btn-small">
                      <i class="fas fa-plus"></i>
                      {{$t("BTN_ADD")}}
                    </router-link>
                    {{$t("LBL_BUTTON_TO_CREATE_ORDERS")}}
                  </p>
                </div>
                <div class="get-started-media">
                  <img :src="baseUrl+'/admin/images/get-started-graphic/get-started-orders.svg'" />
                </div>
              </div>
            </div>
            <div
              class="portlet__body portlet__body--fit"
              v-if="showEmpty == 0 && orders.length > 0 && (view == 'list' || pstatus == 'PENDING' || tabtype == 'all' || status == 4)"
            >
              <div class="section">
                <div class="section__content">
                  <table class="table table-data table-justified" v-if="orders.length > 0">
                    <thead>
                      <tr>
                        <th>{{tabtype == 'open' || tabtype == 'all' || tabtype == 'pickup' || tabtype == 'unpaid' || tabtype == 'completed' ? 'Order #' : 'Request #'}}</th>
                        <th>{{$t("LBL_DATE")}}</th>
                        <th>{{$t("LBL_CUSTOMER")}}</th>
                        <th>{{$t("LBL_AMOUNT")}}</th>
                        <th>{{$t("LBL_PAYMENT_STATUS")}}</th>
                        <th>{{tabtype == 'open' || tabtype == 'all' || tabtype == 'pickup' || tabtype == 'unpaid' || tabtype == 'completed' ? $t("LBL_FULFILMENT_STATUS") : $t('LBL_REQUEST_STATUS')}}</th>

                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(order, orderKey) in orders" :key="orderKey">
                        <td>
                          <b>
                            <router-link
                              :to="{name: 'orderReturnDetail', params: {id: order.order_id, requestId: order.orrequest_id}}"
                              v-if="order.orrequest_id"
                            >#{{order.order_id +'-'+ order.orrequest_id}}</router-link>
                            <router-link
                              v-bind:class="[order.order_gift_amount != 0 ? 'd-inline-flex align-items-center' : '']"
                              :to="{name: 'orderDetail', params: {id: order.order_id}}"
                              v-else
                            >
                              #{{order.order_id}}
                              <span
                                class="YK-GiftItem gift-item active m-0"
                                href="javascript:;"
                                v-if="order.order_gift_amount != 0"
                              >
                                <svg class="svg">
                                  <use
                                    :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#gift-icon'"
                                    :href="baseUrl+'/admin/images/retina/sprite.svg#gift-icon'"
                                  />
                                </svg>
                              </span>
                            </router-link>
                          </b>
                        </td>
                        <td>{{order.order_date_added | formatDate}}</td>
                        <td>
                          <span v-if="order.user_id">
                            <router-link
                              :to="{name: 'userDetails', params: {id: order.user_id}}"
                            >{{ order.username }}</router-link>
                          </span>
                        </td>
                        <td v-if="order.orrequest_id">
                          {{currencySymbol}}
                          <span v-html="calulateRefund(order)"></span>
                        </td>
                        <td v-else>{{currencySymbol}}{{ order.order_net_amount | numberFormat}}</td>
                        <td>
                          <span
                            v-bind:class="[order.order_payment_status.toLowerCase() == 'pending' ? 'badge-text--danger' : 'badge-text--success']"
                            class="badge badge--dot badge--inline"
                          >
                            {{order.order_payment_status}}
                            <svg
                              class="svg ml-2"
                              width="16"
                              height="16"
                              v-if="order.order_payment_status.toLowerCase() != 'pending'"
                            >
                              <use
                                :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#method'"
                                :href="baseUrl + '/admin/images/retina/sprite.svg#method'"
                                v-if="order.order_payment_method != 'cod'"
                              />
                              <use
                                :xlink:href="baseUrl + '/admin/images/retina/sprite.svg#p-status'"
                                :href="baseUrl + '/admin/images/retina/sprite.svg#p-status'"
                                v-else-if="order.order_payment_method"
                              />
                            </svg>
                          </span>
                        </td>
                        <td v-if="returnstatus">
                          <div
                            class="dropdown"
                            v-bind:class="[order.orrequest_status == 2 || order.orrequest_status == 3 ? 'disabledbutton' : '']"
                          >
                            <button
                              class="btn btn-outline-secondary btn-sm dropdown-toggle"
                              type="button"
                              id="dropdownMenuButton"
                              data-toggle="dropdown"
                              aria-haspopup="true"
                              aria-expanded="false"
                            >{{orderReturnRequests[order.orrequest_status] | removeHyphen | camelCase}}</button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a
                                class="dropdown-item"
                                href="javascript:;"
                                v-for="(returnRequests, returnRequestskey) in orderReturnRequests"
                                :key="returnRequestskey"
                                v-if="order.orrequest_status != returnRequestskey && (returnRequestskey != 1 || order.orrequest_type == 1)"
                                @click="updateReturnStatus(order, returnRequests, returnRequestskey)"
                              >{{returnRequests | removeHyphen | camelCase}}</a>
                            </div>
                          </div>
                          <span class="badge badge--success badge--inline"></span>
                        </td>
                        <td v-else>
                          <div
                            class="dropdown"
                            v-bind:class="[order.order_shipping_status == 4 || order.order_status == 4 || !$canWrite('ORDERS')  ? 'disabledbutton' : '']"
                          >
                            <button
                              class="btn btn-outline-secondary btn-sm dropdown-toggle"
                              type="button"
                              id="dropdownMenuButton"
                              data-toggle="dropdown"
                              aria-haspopup="true"
                              aria-expanded="false"
                            >
                              <span
                                v-if="order.order_shipping_type == 1"
                              >{{ pickupStatus[order.order_shipping_status] | removeHyphen | camelCase}}</span>
                              <span
                                v-b-tooltip.hover
                                :title="productType == 2 || productType == 3 ? $t('LBL_PHYSICAL_ORDER_STATUS') :''"
                                v-if="order.order_shipping_type != 1 && (productType == 3 || productType == 1)"
                              >{{ shippingStatus[order.order_shipping_status] | removeHyphen | camelCase}}</span>
                              <span
                                v-b-tooltip.hover
                                :title="productType == 1 || productType == 3 ? $t('LBL_DIGITAL_ORDER_STATUS') :''"
                                v-if="orderDigitalStatus[shippingStatus[order.order_shipping_status]] && order.order_shipping_type != 1 && (productType == 3 || productType == 2)"
                              >
                                {{productType == 1 || productType == 3 ? '/' : ''}}
                                {{ orderDigitalStatus[shippingStatus[order.order_shipping_status]] | removeHyphen | camelCase}}
                              </span>
                            </button>
                            <div
                              class="dropdown-menu"
                              aria-labelledby="dropdownMenuButton"
                              v-if="order.order_shipping_type == 1"
                            >
                              <a
                                class="dropdown-item"
                                href="javascript:;"
                                v-for="(pickup, pickupkey) in pickupStatus"
                                :key="pickupkey"
                                @click="updateOrderStatus(pickupkey, pickup, order)"
                                v-if="order.order_shipping_status != pickupkey"
                              >
                                <span>{{ pickup | removeHyphen | camelCase}}</span>
                              </a>
                            </div>
                            <div
                              class="dropdown-menu"
                              aria-labelledby="dropdownMenuButton"
                              v-if="order.order_shipping_type != 1"
                            >
                              <a
                                class="dropdown-item"
                                href="javascript:;"
                                v-for="(shipStatus, shipkey) in shippingStatus"
                                :key="shipkey"
                                @click="updateOrderStatus(shipkey, shipStatus, order)"
                                v-if="order.order_shipping_status != shipkey"
                              >
                                <span
                                  v-if="(productType == 3 || productType == 1)"
                                >{{ shipStatus | removeHyphen | camelCase}}</span>
                                <span
                                  v-if="orderDigitalStatus[shipStatus] && (productType == 3 || productType == 2)"
                                >
                                  <span v-if="productType == 1 || productType == 3">/</span>
                                  {{ orderDigitalStatus[shipStatus] | removeHyphen | camelCase}}
                                </span>
                              </a>
                            </div>
                          </div>
                          <span class="badge badge--success badge--inline"></span>
                        </td>

                        <td>
                          <div class="actions">
                            <a
                              class="btn btn-sm btn-icon"
                              href="javascript:;"
                              @click="quickViewOrder(order.order_id)"
                              v-b-tooltip.hover
                              :title="$t('TTL_QUICK_ORDER')"
                            >
                              <i class="icon__svg">
                                <svg>
                                  <use
                                    :xlink:href="baseUrl +'/admin/images/retina/sprite.svg#view-icon'"
                                    :href="baseUrl +'/admin/images/retina/sprite.svg#view-icon'"
                                  />
                                </svg>
                              </i>
                            </a>
                            <a
                              class="btn btn-sm btn-icon"
                              href="javascript:;"
                              v-b-tooltip.hover
                              :title="$t('TTL_PAYMENT_LINK')"
                              @click="sendPaymentLink(order)"
                              v-bind:class="[order.order_payment_method == 'cod' && order.payment_status != 2 && order.order_shipping_status == 3 ? '':'disabled']"
                              v-if="tabtype ==  'open' || tabtype == 'all' || tabtype ==  'pickup' || tabtype ==  'unpaid'"
                            >
                              <i class="icon__svg">
                                <svg>
                                  <use
                                    :xlink:href="baseUrl +'/admin/images/retina/sprite.svg#share-icon'"
                                    :href="baseUrl +'/admin/images/retina/sprite.svg#share-icon'"
                                  />
                                </svg>
                              </i>
                            </a>
                            <a
                              class="btn btn-sm btn-icon"
                              href="javascript:;"
                              v-b-tooltip.hover
                              :title="$t('TTL_PACKING_SLIP')"
                              @click="downloadPackingSlip(order.order_id)"
                              v-bind:class="[order.total_products == order.digital_products ? 'disabled': '']"
                              v-if="tabtype ==  'open' || tabtype ==  'pickup' || tabtype == 'all' || tabtype ==  'unpaid'"
                            >
                              <i class="icn">
                                <svg class="svg-icon svg-icon--sm">
                                  <use
                                    :xlink:href="baseUrl +'/admin/images/retina/sprite.svg#invoice'"
                                    :href="baseUrl +'/admin/images/retina/sprite.svg#invoice'"
                                  />
                                </svg>
                              </i>
                            </a>

                            <router-link
                              class="btn btn-sm btn-icon"
                              :to="{name: 'returnRequest', params: {id: order.order_id}}"
                              v-b-tooltip.hover
                              :title="$t('TTL_CANCEL_ORDER')"
                              v-bind:class="[order.order_shipping_status == 1 || order.order_shipping_status == 2  ? '' : 'disabled', (!$canWrite('ORDERS')) ? 'disabledbutton': '', order.return_products_count == order.total_products ? 'disabledbutton': '' ]"
                              v-if="tabtype ==  'open' || tabtype ==  'pickup' || tabtype == 'all' || tabtype ==  'unpaid'"
                            >
                              <i class="icn">
                                <svg class="svg-icon svg-icon--sm">
                                  <use
                                    :xlink:href="baseUrl +'/admin/images/retina/sprite.svg#cancel-order'"
                                    :href="baseUrl +'/admin/images/retina/sprite.svg#cancel-order'"
                                  />
                                </svg>
                              </i>
                            </router-link>
                            <a
                              class="btn btn-sm btn-icon"
                              @click="($canWrite('ORDERS')) ? markAsCompleted(order) : ''"
                              v-b-tooltip.hover
                              :title="$t('TTL_COMPLETE_ORDER')"
                              href="javascript:;"
                              v-bind:class="[(!$canWrite('ORDERS')) ? 'disabledbutton': '', order.order_status ==4 ? 'disabledbutton': '']"
                              v-if="tabtype ==  'open' || tabtype ==  'pickup' || tabtype == 'all' || tabtype ==  'unpaid'"
                            >
                              <i class="icn">
                                <svg class="svg-icon svg-icon--sm">
                                  <use
                                    :xlink:href="baseUrl +'/admin/images/retina/sprite.svg#complete-order'"
                                    :href="baseUrl +'/admin/images/retina/sprite.svg#complete-order'"
                                  />
                                </svg>
                              </i>
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

            <noRecordsFound
              v-if="(view == 'list' && loading == false && orders.length == 0 && showEmpty == 0 && (orderSearch.payment_status.length != 0 || orderSearch.keywords.length != 0 ||  orderSearch.fulfillment.length != 0 || orderSearch.sortby != '' || orderSearch.dateRange != '')) || (view == 'kanban' && kanbanBlock == 0)"
            ></noRecordsFound>
            <div
              class="portlet__foot"
              v-if="orders.length > 0 && (view == 'list' || tabtype == 'all' ||tabtype == 'unpaid' || tabtype == 'completed')"
            >
              <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
            </div>
          </div>
        </div>
      </div>
    </div>
    <products :orderId="orderId" :clickEvent="clickEvent"></products>
    <shipping-status
      :statusData="statusData"
      :url="url"
      :shippingCourier="couriers"
      @closePopup="closePopup"
      @updateStatus="updateStatus"
    ></shipping-status>
    <cod-payment
      :statusData="statusData"
      :url="url"
      :adminsData="adminsData"
      :readonly="readonly"
      @closePopup="closePopup"
      @updateStatus="updateStatus"
    ></cod-payment>
    <share-payment-link :statusData="statusData" :shareSuccessMessage="shareSuccessMessage"></share-payment-link>
    <export-details
      :shippingStatus="shippingStatus"
      :pickupStatus="pickupStatus"
      :productType="productType"
      :orderDigitalStatus="orderDigitalStatus"
    ></export-details>
    <pending-request-model @updateListing="updateListing"></pending-request-model>
  </div>
</template>
<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import kanbanView from "./kanbanView";
import products from "./quickViewOrder";
import shippingStatus from "./partial/shippingStatus";
import codPayment from "./partial/codPayment";
import sharePaymentLink from "./partial/sharePaymentLink";
import exportDetails from "./partial/exportDetails";
import pendingRequestModel from "./partial/pandingRequests";

var VueCookie = require("vue-cookie");
import fecha from "fecha";
// Tell Vue to use the plugin
Vue.use(VueCookie);

export default {
  components: {
    kanbanView,
    products,
    DatePicker,
    codPayment,
    sharePaymentLink,
    exportDetails,
    pendingRequestModel,
    shippingStatus
  },
  data: function() {
    return {
      baseUrl: baseUrl,
      currencySymbol: currencySymbol,
      orders: [],
      orderId: "",
      adminsData: {},
      pstatus: "",
      clickEvent: "",
      type: 0,
      kanbanBlock: 1,
      pickupEnabled: 0,
      statusData: {},
      orderBlock: {},
      readonly: false,
      status: "",
      returnstatus: "",
      shippingStatusPopupId: "shippingStatusPopup",
      codApprovedModelId: "codApproved",
      paymentLinkPopupId: "paymentLinkPopup",
      exportDetailPopupId: "exportDetailPopup",
      pendingRequestPopupId: "pendingRequestModel",
      view: "list",
      orderReturnRequests: [],
      shippingStatus: [],
      orderStatus: [],
      orderReturnStatus: [],
      pagination: [],
      orderSearch: {
        payment_status: [],
        keywords: [],
        fulfillment: [],
        sortby: "order_id",
        dateRange: ""
      },
      searchByKeyword: "",
      updateSearch: "",
      shareSuccessMessage: "",
      url: "",
      loading: false,
      pageLoaded: 0,
      showEmpty: 1,
      tabtype: "all",
      orderDigitalStatus: [],
      pickupStatus: [],
      shippingMesagesEnable: 0,
      productType: 0,
      couriers: {},
      paymentFilters: {
        2: this.$t("LBL_PAID"),
        1: this.$t("LBL_PENDING"),
        cod: this.$t("LBL_CASH")
      }
    };
  },
  methods: {
    openExportPopup() {
      this.$bvModal.show(this.exportDetailPopupId);
    },
    parseDate(dateString, format) {
      return fecha.parse(dateString, format);
    },
    formatDate(dateObj, format) {
      return fecha.format(dateObj, format);
    },
    kanbanList: function(blocks) {
      this.kanbanBlock = blocks;
    },
    currentPage: function(pageNo) {
      this.pageRecords(pageNo);
    },
    switchPage: function(type = "", value = "") {
      this.pstatus = "";
      this.status = "";
      this.returnstatus = "";
      this.tabtype = "open";
      if (type == "pstatus") {
        this.pstatus = value;
        this.tabtype = "unpaid";
      } else if (type == "returnstatus") {
        this.returnstatus = value;
        this.tabtype = value;
      } else if (type == "ostatus") {
        this.status = value;
        this.tabtype = "completed";
      } else if (type == "pickup") {
        this.tabtype = "pickup";
      } else if (type == "all") {
        this.tabtype = "all";
      }
      this.clearSearch();
    },
    calulateRefund: function(block, numberFormat = true) {
      let price = block.op_product_price * block.orrequest_qty;
      let tax = block.oramount_tax;
      let discount = block.oramount_discount;
      let giftwrap = block.oramount_giftwrap_price;
      let reward = block.oramount_reward_price;
      let shipping = block.oramount_shipping;

      let total = parseFloat(price) + parseFloat(tax) + parseFloat(shipping);
      if (discount != 0) {
        total = total - discount;
      }
      if (reward != 0) {
        total = total - reward;
      }
      if (giftwrap != 0) {
        if (giftwrap > 0) {
          total = parseFloat(total) + parseFloat(Math.abs(giftwrap));
        } else {
          total = total - giftwrap;
        }
      }
      if (numberFormat == true) {
        return total.toLocaleString(undefined, { maximumFractionDigits: 2 });
      }
      return total;
    },
    updateOrderStatus: function(id, status, order, complete = 0) {
      if (order.order_shipping_status == id && complete == 0) {
        return false;
      }

      if (order.pending_requests != 0) {
        this.$bvModal.show(this.pendingRequestPopupId);
        return false;
      }
      let digitalOrderType = 0;
      if (order.total_products == order.digital_products) {
        digitalOrderType = 1;
      }
      if (
        order.digital_products != 0 &&
        order.return_products_count != 0 &&
        status == "shipped"
      ) {
        digitalOrderType = this.validateDigitalRequests(order.order_id);
      }

      let gatwayType = order.order_payment_method;
      if (order.transaction) {
        if (
          this.inArray(order.transaction.txn_gateway_type, ["cash", "card"]) ==
          false
        ) {
          gatwayType = order.transaction.txn_gateway_type;
        }
      }
      if (
        status == "delivered" &&
        gatwayType == "cod" &&
        order.payment_status != 2
      ) {
        this.adminsData.amount = order.order_net_amount;
        this.adminsData.transaction_id = this.$rndStr();
        this.adminsData.payment_method = "cash";
        this.readonly = "true";
        this.statusData = {
          id: order.order_id,
          status: status,
          dropdownKey: id,
          complete: complete,
          digitalOrderType: digitalOrderType
        };
        this.$bvModal.show(this.codApprovedModelId);
        return false;
      } else if (status == "delivered" && order.digital_products != 0) {
        this.digitalAdditionalUpload(
          id,
          status,
          order.order_id,
          digitalOrderType,
          complete,
          order.digital_products
        );
        return;
      } else {
        this.statusData = {
          id: order.order_id,
          status: status,
          dropdownKey: id,
          complete: complete,
          digitalOrderType: digitalOrderType
        };
        if (
          (this.shippingMesagesEnable == 0 && status != "shipped") ||
          (status == "shipped" && digitalOrderType == 1)
        ) {
          this.updateStatus(this.statusData);
          return false;
        }
        this.$bvModal.show(this.shippingStatusPopupId);
        return false;
      }
    },
    validateDigitalRequests: function(orderId) {
      let physicalProductExist = [];
      this.$http.get(adminBaseUrl + "/orders/" + orderId).then(response => {
        let order = response.data.data.order;

        Object.keys(order.products).forEach(pkey => {
          if (
            order.products[pkey].cancel_request != "" &&
            order.products[pkey].cancel_request != null &&
            order.products[pkey].op_product_type == true
          ) {
            if (
              order.products[pkey].op_qty -
                order.products[pkey].cancel_request.orrequest_qty ==
              0
            ) {
              physicalProductExist.push(false);
            } else {
              physicalProductExist.push(true);
            }
          }
        });
      });
      if (this.inArray(true, physicalProductExist)) {
        return 0;
      } else {
        return 1;
      }
    },
    digitalAdditionalUpload: function(
      id,
      status,
      orderId,
      digitalOrderType,
      complete,
      digitalCount
    ) {
      let formData = this.$serializeData({
        "order-id": orderId
      });
      this.$http
        .post(adminBaseUrl + "/orders/digital-additional-upload", formData)
        .then(response => {
          this.statusData = {
            id: orderId,
            digitalCount: digitalCount,
            status: status,
            dropdownKey: id,
            complete: complete,
            digitalOrderType: digitalOrderType,
            uploadInfo: response.data.data
          };
          this.$bvModal.show(this.shippingStatusPopupId);
        });
    },
    closePopup: function(id) {
      this.$bvModal.hide(id);
      this.$bvModal.hide(this.shippingStatusPopupId);
      this.$bvModal.hide(this.codApprovedModelId);
      this.statusData = {};
      this.adminsData = {};
    },
    markAsCompleted: function(order) {
      let type = "delivered";
      if (order.order_shipping_type == 1) {
        type = "picked-up";
      }
      this.updateOrderStatus(4, type, order, 1);
    },
    downloadPackingSlip: function(orderId) {
      window.open(adminBaseUrl + "/orders/download-packing-slip/" + orderId);
    },
    updateReturnStatus: function(order, status, key) {
      let amount = this.calulateRefund(order, false);

      this.url = "return/";

      let gatwayType = order.order_payment_method;
      if (order.transaction) {
        if (
          this.inArray(order.transaction.txn_gateway_type, ["cash", "card"]) ==
          false
        ) {
          gatwayType = order.transaction.txn_gateway_type;
        }
      }

      if (
        status == "approved" &&
        gatwayType == "cod" &&
        order.payment_status == 2
      ) {
        this.adminsData.payment_method = "Bank Transfer";
        this.adminsData.amount = amount;
        this.readonly = "false";
        this.statusData = {
          id: order.orrequest_id,
          status: status,
          dropdownKey: key,
          amount: amount,
          complete: 0
        };

        this.$bvModal.show(this.codApprovedModelId);
        return false;
      } else {
        this.statusData = {
          id: order.orrequest_id,
          status: status,
          dropdownKey: key,
          amount: amount,
          complete: 0
        };

        this.$bvModal.show(this.shippingStatusPopupId);
        return false;
      }
    },
    updateStatus(shipStatus, shipComment, adminsData = []) {
      let formData = this.$serializeData({
        status: shipStatus.status,
        complete: shipStatus.complete,
        "transfer-detail": JSON.stringify(adminsData)
      });
      if (shipComment) {
        formData.append("message", shipComment.message);
        formData.append("tracking_number", shipComment.tracking_number);
        formData.append("courier_name", shipComment.courier_name);
      }

      this.$http
        .post(
          adminBaseUrl + "/orders/" + this.url + "status/" + shipStatus.id,
          formData
        )
        .then(response => {
          toastr.success(response.data.message, "", toastr.options);
          this.emptyFormValues();
          this.pageRecords();
        });
    },
    inArray: function(needle, haystack) {
      var length = haystack.length;
      for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
      }
      return false;
    },
    emptyFormValues: function() {
      this.commentData = {
        message: "",
        courier_name: "",
        tracking_number: ""
      };
      this.$bvModal.hide(this.shippingStatusPopupId);
      this.$bvModal.hide(this.codApprovedModelId);
      this.url = "";
      this.statusData = {};
      this.message = "";
      this.url = "";
      this.adminsData = {};
      this.errors.clear();
    },
    isRequestPending: function(id, status, orderId, digitalOrderType) {
      let formData = this.$serializeData({
        "order-id": orderId
      });
      this.$http
        .post(adminBaseUrl + "/orders/pending-requests", formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          this.statusData = {
            id: orderId,
            status: status,
            orderId: orderId,
            digitalOrderType: digitalOrderType
          };
          if (
            (this.shippingMesagesEnable == 0 && status != "shipped") ||
            (status == "shipped" && digitalOrderType == 1)
          ) {
            this.updateStatus(this.statusData);
            return false;
          }
          this.$bvModal.show(this.shippingStatusPopupId);
        });
    },
    sendPaymentLink: function(block) {
      this.shareSuccessMessage = "";
      this.orderBlock = block;
      this.statusData = { id: block.order_id };
      this.$bvModal.show(this.paymentLinkPopupId);
    },
    searchRecords: function() {
      this.orderSearch = this.orderSearch;
      this.updateSearch = this.$rndStr();
      if (
        this.view == "list" ||
        this.tabtype == "unpaid" ||
        this.tabtype == "all" ||
        this.tabtype == "completed"
      ) {
        this.pageRecords(1);
      }
    },
    clearSearch: function() {
      this.orderSearch = {
        payment_status: [],
        keywords: [],
        fulfillment: [],
        sortby: "order_id",
        dateRange: ""
      };
      this.updateSearch = this.$rndStr();
      if (
        this.view == "list" ||
        this.tabtype == "all" ||
        this.tabtype == "unpaid" ||
        this.tabtype == "completed"
      ) {
        this.pageRecords(1);
      }
    },
    validateSearch: function() {
      this.$validator
        .validate("searchByKeyword", this.searchByKeyword)
        .then(res => {
          if (res) {
            this.orderSearch.keywords.push(this.searchByKeyword);
            this.searchByKeyword = "";
            this.updateSearch = this.$rndStr();

            if (
              this.view == "list" ||
              this.tabtype == "unpaid" ||
              this.tabtype == "all" ||
              this.tabtype == "completed"
            ) {
              this.pageRecords(1);
            }
          }
        });
    },
    removeKeyword: function(index, type = "") {
      if (type == "paymentStatus") {
        this.$delete(this.orderSearch.payment_status, index);
      } else {
        this.$delete(this.orderSearch.keywords, index);
      }
      this.updateSearch = this.$rndStr();
      if (
        this.view == "list" ||
        this.tabtype == "unpaid" ||
        this.tabtype == "all" ||
        this.tabtype == "completed"
      ) {
        this.pageRecords(1);
      }
    },
    pageRecords: function(pageNo, pageLoad = false) {
      this.loading = pageLoad;
      let formData = this.$serializeData({
        "active-tab": this.tabtype,
        pstatus: this.pstatus,
        order_status: this.status,
        return_status: this.returnstatus,
        search: JSON.stringify(this.orderSearch)
      });
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(adminBaseUrl + "/orders-listing?page=" + pageNo, formData)
        .then(response => {
          this.orders = response.data.data.orders.data;
          this.pagination = response.data.data.orders;
          this.shippingStatus = response.data.data.shippingStatus;
          this.pickupStatus = response.data.data.pickupStatus;
          this.orderStatus = response.data.data.orderStatus;
          this.orderReturnStatus = response.data.data.orderReturnStatus;
          this.orderReturnRequests = response.data.data.orderReturnRequests;
          this.pickupEnabled = response.data.data.pickupEnabled;
          this.showEmpty = response.data.data.showEmpty;
          this.orderDigitalStatus = response.data.data.orderDigitalStatus;
          this.productType = response.data.data.productType;
          this.loading = false;
          this.shippingMesagesEnable = response.data.data.shippingMesages;
          let couriers = response.data.data.shippingCouriers;
          this.couriers = couriers.data;
          this.pageLoaded = 1;
        });
    },
    quickViewOrder: function(orderId) {
      this.clickEvent = this.$rndStr();
      this.orderId = orderId;
    },
    switchView: function(listView) {
      this.$cookie.set("ordersView", listView, {
        expires: "1Y"
      });
      this.view = listView;
      if (listView == "list") {
        this.pageRecords(1);
      }
    },
    updateListing: function() {
      if (this.view != "list") {
        this.updateSearch = "";
      }
    }
  },
  mounted: function() {
    if (this.$cookie.get("ordersView")) {
      this.view = this.$cookie.get("ordersView");
    }
    this.pageRecords(1, true);
  }
};
</script>