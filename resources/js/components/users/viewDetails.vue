<template>
  <div>
    <div id="subheader" class="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_USER_DETAILS') }}</h3>
        </div>
        <div class="subheader__toolbar">
          <router-link :to="{name: 'users'}" class="btn btn-white">{{$t('BTN_BACK')}}</router-link>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="grid-layout">
        <div id="user_profile_aside" class="grid-layout-aside">
          <div class="portlet portlet--height-fluid">
            <div class="portlet__body">
              <div class="widget widget--user-profile-1">
                <div class="widget__body">
                  <div class="widget__items">
                    <a
                      href="javascript:;"
                      class="widget__item"
                      v-bind:class="[ tabForm == 'view' ? 'router-link-exact-active router-link-active widget__item--active' : '']"
                      @click="tabFilter('view')"
                    >
                      <span class="widget__section">
                        <span class="widget__icon">
                          <svg>
                            <use
                              :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#user-details'"
                              :href="adminBaseUrl+'/images/retina/sprite.svg#user-details'"
                            />
                          </svg>
                        </span>
                        <span class="widget__desc">{{ $t('LNK_DETAILS') }}</span>
                      </span>
                    </a>

                    <a
                      href="javascript:;"
                      class="widget__item"
                      v-if="rewardPointsEnabled == '1'"
                      v-bind:class="[ tabForm == 'rewards' ? 'router-link-exact-active router-link-active widget__item--active' : '']"
                      @click="tabFilter('rewards')"
                    >
                      <span class="widget__section">
                        <span class="widget__icon">
                          <svg>
                            <use
                              :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#user-reward-point-history'"
                              :href="adminBaseUrl+'/images/retina/sprite.svg#user-reward-point-history'"
                            />
                          </svg>
                        </span>
                        <span class="widget__desc">{{ $t('LNK_REWARD_POINT_HISTORY') }}</span>
                      </span>
                    </a>
                    <a
                      href="javascript:;"
                      class="widget__item"
                      v-bind:class="[ tabForm == 'coupons' ? 'router-link-exact-active router-link-active widget__item--active' : '']"
                      @click="tabFilter('coupons')"
                    >
                      <span class="widget__section">
                        <span class="widget__icon">
                          <svg>
                            <use
                              :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#user-redeem-coupon'"
                              :href="adminBaseUrl+'/images/retina/sprite.svg#user-redeem-coupon'"
                            />
                          </svg>
                        </span>
                        <span class="widget__desc">{{ $t('LNK_REDEEMED_COUPONS') }}</span>
                      </span>
                    </a>
                    <a
                      href="javascript:;"
                      class="widget__item"
                      v-bind:class="[ tabForm == 'orders' ? 'router-link-exact-active router-link-active widget__item--active' : '']"
                      @click="tabFilter('orders')"
                    >
                      <span class="widget__section">
                        <span class="widget__icon">
                          <svg>
                            <use
                              :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#user-orders'"
                              :href="adminBaseUrl+'/images/retina/sprite.svg#user-orders'"
                            />
                          </svg>
                        </span>
                        <span class="widget__desc">{{ $t('LNK_USER_ORDERS') }}</span>
                      </span>
                    </a>
                    <a
                      href="javascript:;"
                      class="widget__item"
                      v-bind:class="[ tabForm == 'cancel' ? 'router-link-exact-active router-link-active widget__item--active' : '']"
                      @click="tabFilter('cancel')"
                    >
                      <span class="widget__section">
                        <span class="widget__icon">
                          <svg>
                            <use
                              :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#user-cancellation-requests'"
                              :href="adminBaseUrl+'/images/retina/sprite.svg#user-cancellation-requests'"
                            />
                          </svg>
                        </span>
                        <span class="widget__desc">{{ $t('LNK_CANCELLATION_REQUESTS') }}</span>
                      </span>
                    </a>
                    <a
                      href="javascript:;"
                      class="widget__item"
                      v-bind:class="[ tabForm == 'return' ? 'router-link-exact-active router-link-active widget__item--active' : '']"
                      @click="tabFilter('return')"
                    >
                      <span class="widget__section">
                        <span class="widget__icon">
                          <svg>
                            <use
                              :xlink:href="adminBaseUrl + '/images/retina/sprite.svg#user-return-requests'"
                              :href="adminBaseUrl+'/images/retina/sprite.svg#user-return-requests'"
                            />
                          </svg>
                        </span>
                        <span class="widget__desc">{{ $t('LNK_RETURN_REQUESTS') }}</span>
                      </span>
                    </a>
                    <a
                      href="javascript:;"
                      class="widget__item"
                      v-bind:class="[ tabForm == 'reviews' ? 'router-link-exact-active router-link-active widget__item--active' : '']"
                      @click="tabFilter('reviews')"
                    >
                      <span class="widget__section">
                        <span class="widget__icon">
                          <svg>
                            <use
                              :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#user-product-reviews'"
                              :href="adminBaseUrl+'/images/retina/sprite.svg#user-product-reviews'"
                            />
                          </svg>
                        </span>
                        <span class="widget__desc">{{ $t('LNK_USER_PRODUCT_REVIEWS') }}</span>
                      </span>
                    </a>
                    <a
                      href="javascript:;"
                      class="widget__item"
                      v-bind:class="[ tabForm == 'addresses' ? 'router-link-exact-active router-link-active widget__item--active' : '']"
                      @click="tabFilter('addresses')"
                    >
                      <span class="widget__section">
                        <span class="widget__icon">
                          <svg>
                            <use
                              :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#user-addresses'"
                              :href="adminBaseUrl+'/images/retina/sprite.svg#user-addresses'"
                            />
                          </svg>
                        </span>
                        <span class="widget__desc">{{ $t('LNK_USER_ADDRESSES') }}</span>
                      </span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="grid-layout-content">
          <div class="portlet portlet-no-min-height">
            <div class="portlet__body pb-0 bg-gray flex-grow-0">
              <div class="form-group">
                <div class="input-icon input-icon--left">
                  <vue-bootstrap-typeahead
                    :serializer="u => u.user_email"
                    v-model="userSearch"
                    :data="users"
                    ref="userTypeahead"
                    :placeholder="$t('PLH_SEARCH_EMAIL')"
                    @hit="selectedUser($event)"
                    :max-matches="5"
                    :min-matching-chars="1"
                  />
                  <span class="input-icon__icon input-icon__icon--left">
                    <span>
                      <i class="la la-search"></i>
                    </span>
                  </span>
                </div>
                <ul class="tagify-custom my-2">
                  <li
                    v-for="(oldSearch, searchIndex) in oldSearches"
                    :key="searchIndex"
                    v-bind:class="[adminsData.user_id == oldSearch.id ? 'active': '']"
                  >
                    <span v-if="oldSearch.email != ''">
                      <a
                        href="javascript:;"
                        @click="getRecordByKeyword(searchIndex)"
                      >{{oldSearch.email}}</a>
                    </span>
                    <a
                      v-if="oldSearch.email != ''"
                      href="javascript:;"
                      class="remove"
                      @click="removeKeyword(searchIndex, oldSearch.id)"
                    >
                      <i class="fas fa-times"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="portlet">
            <div
              class="portlet__body p-0 flex-grow-1"
              v-bind:class="[!$canWrite('USERS') ? 'disabledbutton no-drop': '']"
            >
              <!---View_body-->
              <div class="wrapper user--details" v-if="tabForm == 'view'">
                <div class="row justify-content-center mt-4">
                  <div class="col-md-8">
                    <div class="form-group row align-items-center">
                      <div class="col-lg-3 col-form-label">{{ $t('LBL_FIRST_NAME') }}:</div>
                      <div class="col-lg-9">
                        <input
                          type="text"
                          class="form-control show-outline"
                          v-model="adminsData.user_fname"
                        />
                      </div>
                    </div>
                    <div class="form-group row align-items-center">
                      <div class="col-lg-3 col-form-label">{{ $t('LBL_LAST_NAME') }}:</div>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" v-model="adminsData.user_lname" />
                      </div>
                    </div>
                    <div class="form-group row align-items-center">
                      <div class="col-lg-3 col-form-label">{{ $t('LBL_EMAIL_ADDRESS') }}:</div>
                      <div class="col-lg-9">
                        <input
                          type="text"
                          class="form-control"
                          v-model="adminsData.user_email"
                          v-validate="'required'"
                          :data-vv-as="$t('LBL_EMAIL_ADDRESS')"
                          data-vv-validate-on="none"
                        />
                        <span
                          v-if="errors.first('user_email')"
                          class="error text-danger"
                        >{{ errors.first('user_email') }}</span>
                      </div>
                    </div>

                    <div class="form-group row align-items-center">
                      <div class="col-lg-3 col-form-label">{{ $t('LBL_PASSWORD') }}</div>
                      <div class="col-lg-9">
                        <a
                          href="javascript:;"
                          @click="openChangePasswordPopup"
                        >{{$t('LNK_CHANGE_PASSWORD')}}</a>
                      </div>
                    </div>

                    <div class="form-group row align-items-center">
                      <div class="col-lg-3 col-form-label">{{ $t('LBL_PHONE') }}:</div>
                      <div class="col-lg-9">
                        <vue-tel-input
                          v-model="adminsData.user_phone"
                          :defaultCountry="defaultCountryCode"
                          :enabledCountryCode="true"
                          @country-changed="changeCountry"
                          @input="onPhoneNumberChange"
                          placeholder="Enter a phone number"
                          :validCharactersOnly="true"
                          :maxLen="15"
                          v-if="defaultCountryCode != ''"
                        ></vue-tel-input>
                        <span v-if="errors.first('user_phone')" class="error text-danger">{{ errors.first('user_phone') }}</span>
                      </div>
                    </div>

                    <div class="form-group row align-items-center">
                      <div class="col-lg-3 col-form-label">{{ $t('LBL_GENDER') }}:</div>
                      <div class="col-lg-9">
                        <select class="form-control" v-model="adminsData.user_gender">
                          <option disabled value>{{$t('LBL_SELECT_GENDER')}}</option>
                          <option
                            v-for="(genderVal,index) in gender"
                            :key="index"
                            :value="index"
                          >{{genderVal}}</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row align-items-center">
                      <div class="col-lg-3 col-form-label">{{ $t('LBL_DOB') }}:</div>
                      <div class="col-lg-9">
                        <date-pick
                          v-model="adminsData.user_dob"
                          :isDateDisabled="isFutureDate"
                          :format="'YYYY-MM-DD'"
                          :inputAttributes="{class: 'form-control'}"
                          class="d-block"
                        ></date-pick>
                      </div>
                    </div>
                    <div class="form-group row align-items-center">
                      <div class="col-lg-3 col-form-label">{{ $t('LBL_GROUPS') }}:</div>
                      <div class="col-lg-9">
                        <ul class="chip-wrapper">
                          <li class="mb-3" v-for="group in groups" :key="group.ugroup_id">
                            <label
                              class="chip-sm group--bg chip--active"
                              :for="'group-'+group.ugroup_id"
                            >
                              <input
                                type="checkbox"
                                class="control-input"
                                name="user_group_ids"
                                :checked="group.checked"
                                :value="group.ugroup_id"
                                :id="'group-'+group.ugroup_id"
                              />
                              <span>{{group.ugroup_name}}</span>
                            </label>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="form-group row align-items-center">
                      <div class="col-lg-3 col-form-label">{{ $t('LBL_CREATED') }}:</div>
                      <div class="col-lg-9">
                        <span>{{ createdByUser ? createdByUser+ ' |' : '' }} {{ createdAt | formatDateTime}}</span>
                      </div>
                    </div>
                    <div class="form-group row align-items-center">
                      <div class="col-lg-3 col-form-label">{{ $t('LBL_LAST_UPDATED') }}:</div>
                      <div class="col-lg-9">
                        <span>{{ updatedByUser ? updatedByUser+ ' |' : '' }} {{ updatedAt | formatDateTime}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!---End_View_body-->

              <!---Reward-Points_body-->
              <table class="table table-data" v-if="tabForm == 'rewards'" v-bind:class="[ adminsData.user_publish == 0 ? 'disabledbutton' : '']">
                <thead>
                  <tr>
                    <th
                      colspan="2"
                    >{{ $t('LBL_AVAILABLE_BALANCE') }}: {{rewardsBalance}} {{ $t('LBL_POINTS') }}</th>
                    <th colspan="1" class="text-right">
                      <a
                        href="javascript:;"
                        class="links"
                        v-if="rewardPointsEnabled == '1'"
                        @click="addRemoveRewardsPopop"
                      >{{ $t('LNK_MANAGE_REWARD') }}</a>
                    </th>
                  </tr>
                  <tr>
                    <th>{{ $t('LBL_REWARD_DATE') }}</th>
                    <th>{{ $t('LBL_POINTS') }}</th>
                    <th>{{ $t('LBL_REWARD_COMMENTS') }}</th>
                  </tr>
                </thead>
                <tbody v-if="rewards.length > 0">
                  <tr v-for="(reward, index) in rewards" :key="'rewards'+index">
                    <td width="25%">{{ reward.date | formatDate }}</td>
                    <td
                      v-bind:class="[(reward.points > 0 && reward.urp_type != 6) ? 'text-success' : 'text-danger']"
                    >
                      <span v-if="reward.urp_type == 6">-{{reward.points}}</span>
                      <span v-else>{{reward.points}}</span>
                    </td>
                    <td>
                      <ul v-if="reward.urp_type == 1">
                        <li v-for="(op, key1) in reward.order_products" :key="'op'+key1">
                          <span
                            v-if="op.reward_points"
                          >{{op.reward_points.opc_value}} {{ $t('LBL_ORDER_EARNED_POINTS_MESSAGE') }} {{op.op_product_name}}</span>
                        </li>
                      </ul>
                      <span
                        v-if="reward.urp_type == 2"
                      >#{{$t('LBL_HURRAY')}}! {{reward.referral_email}} {{$t('LBL_JOINED')}} {{ businessName }}. {{$t('LBL_HERES')}} {{reward.points}} {{$t('LBL_POINTS_FOR_SIGNUP')}}</span>
                      <span
                        v-if="reward.urp_type == 3"
                      >#{{$t('LBL_HAPPY_BIRTHDAY_HERE_ARE')}} {{reward.points}} {{$t('LBL_POINTS_FOR_BIRTHDAY')}}</span>
                      <span
                        v-if="reward.urp_type == 4"
                      >{{reward.points}} {{$t('LBL_ORDER_REDEEMED_POINTS_MESSAGE')}} #{{reward.urp_order_id}}</span>
                      <span
                        v-if="reward.urp_type == 6"
                      >{{reward.points}} {{$t('LBL_POINTS_EXPIRED')}}</span>
                      <span v-if="reward.urp_type == 7 || reward.urp_type == 8">{{reward.comments}}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div v-if="tabForm == 'rewards' && rewards.length == 0 && loadingRightPanel === false" class="wrapper position-relative">
                <noRecordsFound :heading="$t('LBL_NO_USER_REWARD_FOUND')" :subHeading="''"></noRecordsFound>
              </div>
              <!---End_Reward-Points_body-->

              <!---Coupons_body-->
              <table class="table table-data" v-if="tabForm == 'coupons'  && coupons.length > 0">
                <thead>
                  <tr>
                    <th colspan="4">{{ $t('LBL_TOTAL_SAVED') }}: {{currencySymbol}}{{savedTotal | numberFormat}}</th>
                  </tr>
                  <tr>
                    <th></th>
                    <th>{{ $t('LBL_COUPON_CODE') }}</th>
                    <th>{{ $t('LBL_AMOUNT_SAVED') }}</th>
                    <th>{{ $t('LBL_USE_COUNT') }}</th>
                  </tr>
                </thead>
                <tbody v-if="coupons.length > 0">
                  <tr v-for="(coupon, index) in coupons" :key="'coupons'+index">
                    <td>
                      <span>
                        <img
                          class="pro-img"
                          :src="baseUrl + '/yokart/image/' + coupon.afile_id + '/small?t='+$timestamp(coupon.afile_updated_at)"
                          data-ratio="1:1"
                        />
                      </span>
                    </td>
                    <td>{{coupon.discountcpn_code}}</td>
                    <td>{{currencySymbol}}{{coupon.savedTotal | numberFormat}}</td>
                    <td>{{coupon.orders_count}}</td>
                  </tr>
                </tbody>
              </table>
              <div v-if="tabForm == 'coupons' && coupons.length == 0 && loadingRightPanel === false"  class="wrapper position-relative">
                <noRecordsFound :heading="$t('LBL_NO_USER_COUPONS_FOUND')" :subHeading="''"></noRecordsFound>
              </div>
              <!---End_Coupons_body-->

              <!---Orders_body-->
              <table
                class="table table-data"
                v-if="(tabForm == 'orders' || tabForm == 'cancel' || tabForm == 'return') && orders.length > 0"
              >
                <thead>
                  <tr>
                    <th colspan="3">
                      <span v-if="tabForm == 'cancel'">{{ $t('LBL_TOTAL_CANCELLATION_AMOUNT') }}</span>
                      <span v-else-if="tabForm == 'return'">{{ $t('LBL_TOTAL_RETURN_AMOUNT') }}</span>
                      <span v-else>{{ $t('LBL_TOTAL_ORDER_AMOUNT') }}</span>
                      : {{currencySymbol}}{{orderTotal | numberFormat}}
                    </th>
                  </tr>
                  <tr>
                    <th>{{ $t('LBL_ORDER_DATE') }}</th>
                    <th>{{ $t('LBL_ORDER') }}#</th>
                    <th>{{ $t('LBL_AMOUNT') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(order, index) in orders" :key="'orders'+index">
                    <td>{{ order.order_date_added | formatDate }}</td>
                    <td>
                      <router-link
                        class="btn btn-link"
                        :to="{name: 'orderDetail', params: {id: order.order_id}}"
                        target="_blank"
                        v-if="tabForm == 'orders'"
                      >#{{order.order_id}}</router-link>

                      <router-link
                        :to="{name: 'orderReturnDetail', params: {id: order.order_id, requestId: order.orrequest_id}}"
                        target="_blank"
                        v-else
                      >#{{order.order_id +'-'+ order.orrequest_id}}</router-link>
                    </td>
                    <td>{{currencySymbol}}{{ order.order_net_amount | numberFormat }}</td>
                  </tr>
                </tbody>
              </table>
              <div
                v-if="(tabForm == 'orders' || tabForm == 'cancel' || tabForm == 'return') && orders.length == 0 && loadingRightPanel === false"
                class="wrapper position-relative"
              >
                <noRecordsFound :heading="$t('LBL_NO_USER_ORDERS_FOUND')" :subHeading="''"></noRecordsFound>
              </div>
              <!---End_Orders_body-->

              <!---Reviews_body-->
              <ul class="product__reviews" v-if="tabForm == 'reviews' && reviews.length > 0">
                <li
                  class="product__reviews_list"
                  v-for="(review, index) in reviews"
                  :key="'reviews'+index"
                >
                  <div class="card">
                    <div class="card-body">
                      <div class="product__reviews_head">
                        <div class="product__reviews_image">
                          <a target="_blank" :href="baseUrl+'/product/'+review.preview_prod_id">
                            <div class="avatar">
                              <img
                                :src="$productImage(review.preview_prod_id, (review.op_pov_code != null ? review.op_pov_code : '0'), '', '80-107')"
                                v-b-tooltip.hover
                                :title="review.prod_name"
                              />
                            </div>
                          </a>
                        </div>
                        <div class="product__reviews_info">
                          <h5 class="card-title">{{ review.preview_title }}</h5>
                          <p class="card-text">
                            <span
                              v-if="review.collapsed"
                            >{{ review.preview_description | truncate(150) }}</span>
                            <span v-else>{{ review.preview_description }}</span>
                          </p>
                          <a
                            href="javascript:;"
                            class="view__more"
                            @click="review.collapsed = !review.collapsed"
                            v-if="review.preview_description && review.preview_description.length > 150"
                            v-bind:class="[review.collapsed ? 'active' : '']"
                          >
                            <span v-if="review.collapsed">{{$t("LBL_DISPLAY_MORE")}}</span>
                            <span v-else>{{$t("LBL_DISPLAY_LESS")}}</span>
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer text-muted">
                      <div class="product__reviews_footer">
                        <div style="width: 10%;">
                          <span class="rating-holder" v-b-tooltip.hover :title="$t('LBL_RATING')">
                            <ratingStars :rating="review.preview_rating"></ratingStars>
                          </span>
                        </div>
                        <div style="width: 30%;">
                          <span v-b-tooltip.hover :title="$t('LBL_ORDER_ID')">
                            <router-link
                              :to="{name: 'orderDetail', params: {id: review.preview_order_id}}"
                            >#{{review.preview_order_id }}</router-link>
                          </span>
                        </div>
                        <div style="width: 20%;">
                          <span
                            v-b-tooltip.hover
                            :title="$t('LBL_REVIEW_POSTED_ON')"
                          >{{ review.preview_created_at | formatDate }}</span>
                        </div>
                        <div style="width: 20%;">
                          <span v-b-tooltip.hover :title="$t('LBL_REVIEW_STATUS')">
                            <span
                              class="badge badge--dot badge--inline badge-text--warning"
                              v-if="review.preview_status == 1"
                            >{{$t('LBL_REVIEW_PENDING')}}</span>
                            <span
                              class="badge badge--dot badge--inline badge-text--success"
                              v-if="review.preview_status == 2"
                            >{{$t('LBL_REVIEW_APPROVED')}}</span>
                            <span
                              class="badge badge--dot badge--inline badge-text--danger"
                              v-if="review.preview_status == 3"
                            >{{$t('LBL_REVIEW_REJECTED')}}</span>
                          </span>
                        </div>
                        <div style="width: 20%;">
                          <span
                            v-if="review.helped_count == review.nothelped_count"
                          >{{review.helped_count}} {{$t('LBL_REVIEW_USERS_LIKE')}}</span>
                          <span
                            v-if="review.helped_count > review.nothelped_count"
                          >{{review.helped_count}} {{$t('LBL_REVIEW_USERS_LIKE')}}</span>
                          <span
                            v-if="review.helped_count < review.nothelped_count"
                          >{{review.nothelped_count}} {{$t('LBL_REVIEW_USERS_DISLIKE')}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
              <div
                v-if="tabForm == 'reviews' && reviews.length==0 && loadingRightPanel === false"
                class="wrapper position-relative"
              >
                <noRecordsFound :heading="$t('LBL_NO_USER_REVIEWS_FOUND')" :subHeading="''"></noRecordsFound>
              </div>
              <!---End_Reviews_body-->
              <ul
                class="my-addresses list-addresses p-5"
                v-if="tabForm == 'addresses' && addresses.length > 0"
              >
                <li v-for="(address, index) in addresses" :key="index">
                  <div class="my-addresses__body">
                    <address class="delivery-address">
                      <h5>
                        {{address.addr_first_name +' '+address.addr_last_name}}
                        <span class="tag">{{address.addr_title}}</span>
                      </h5>
                       <p>{{ (address.addr_address1 != "") ? address.addr_address1+', ' : '' }} {{ (address.addr_address2 != "") ? address.addr_address2+', ' : '' }} {{ (address.addr_city != "") ? address.addr_city+', ' : '' }} {{ (address.state.state_name != "") ? address.state.state_name+', ' : '' }} {{ address.country.country_name+ ', ' }} {{ (address.addr_postal_code != "") ? address.addr_postal_code : '' }}</p>
                      <p class="phone-txt">
                        <i class="fas fa-mobile-alt"></i>
                        +{{ address.phonecountry.country_phonecode }} {{ (address.addr_phone != '') ? address.addr_phone: '' }}
                      </p>
                    </address>
                  </div>
                </li>
              </ul>
              <div
                v-if="tabForm == 'addresses' && addresses.length==0 && loadingRightPanel === false"
                class="wrapper position-relative"
              >
                <noRecordsFound :heading="$t('LBL_NO_USER_ADDRESS_FOUND')" :subHeading="''"></noRecordsFound>
              </div>
            </div>
            <div class="portlet__foot">
              <pagination
                class="px-4"
                :pagination="pagination"
                @currentPage="tabsCurrentPage"
                v-if="(tabForm == 'addresses' && addresses.length > 0)  
                                && tabForm != 'view' 
                                || (tabForm == 'rewards' && rewards.length > 0) 
                                || (tabForm == 'coupons' && coupons.length > 0) 
                                || ((tabForm == 'orders' || tabForm == 'cancel' || tabForm == 'return') && orders.length > 0) 
                                || (tabForm == 'reviews' && reviews.length > 0)"
              ></pagination>
              <div class="row" v-if="tabForm=='view'">
                <div class="col-md-4"></div>
                <div class="col-md-6">
                  <button
                    type="submit"
                    class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                    @click="updateRecord()"
                    :disabled="(!isUserFormComplete || !$canWrite('USERS'))"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{ $t("BTN_UPDATE") }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <b-modal id="addRemoveRewardsModal" centered title="BootstrapVue">
      <template v-slot:modal-header>
        <h5 class="modal-title" id="exampleModalLabel">{{ $t('LBL_ADD_REMOVE_REWARD_POINTS') }}</h5>
        <button type="button" class="close" @click="$bvModal.hide('addRemoveRewardsModal')"></button>
      </template>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">{{$t('LBL_REWARD_TYPE') }}</label>
            <div class="col-lg-9">
              <div class="row">
                <div class="col-lg-6">
                  <div class="radio-inline">
                    <label class="radio">
                      <input
                        type="radio"
                        :value="'credit'"
                        v-model="rewardPoints.urp_type"
                        name="urp_type"
                      />
                      {{ $t('LBL_CREDIT') }}
                      <span></span>
                    </label>
                    <label class="radio">
                      <input
                        type="radio"
                        :value="'debit'"
                        v-model="rewardPoints.urp_type"
                        name="urp_type"
                      />
                      {{ $t('LBL_DEBIT') }}
                      <span></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">{{$t('LBL_POINTS') }}</label>
            <div class="col-lg-9">
              <input
                type="text"
                class="form-control"
                v-model="rewardPoints.urp_points"
                name="urp_points"
                v-validate="'required|numeric|greaterThanZero|lessThanEqualToBalance'"
                data-vv-validate-on="none"
                :data-vv-as="$t('LBL_POINTS')"
              />
              <span
                v-if="errors.first('urp_points')"
                class="error text-danger"
              >{{ errors.first('urp_points') }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">{{$t('LBL_REWARD_COMMENTS') }}</label>
            <div class="col-lg-9">
              <textarea
                class="form-control"
                type="text"
                v-model="rewardPoints.urp_comments"
                name="urp_comments"
                v-validate="'required'"
                :data-vv-as="$t('LBL_REWARD_COMMENTS')"
                data-vv-validate-on="none"
              ></textarea>
              <span
                v-if="errors.first('urp_comments')"
                class="error text-danger"
              >{{ errors.first('urp_comments') }}</span>
            </div>
          </div>
        </div>

        <div class="col-md-12" v-if="rewardPoints.urp_type == 'credit'">
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">{{$t('LBL_POINT_VALIDAITY') }}</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" v-model="rewardPoints.point_validity" name="point_validity" v-validate="'required|decimal:0|min_value:0'" data-vv-validate-on="none" :data-vv-as="$t('LBL_POINTS')"/>
                    <span v-if="errors.first('point_validity')" class="error text-danger">{{ errors.first('point_validity') }}</span>
                </div>
            </div>
        </div>
      </div>
      <template v-slot:modal-footer>
        <button
          type="button"
          class="btn btn-secondary"
          @click="$bvModal.hide('addRemoveRewardsModal')"
        >{{ $t('BTN_DISCARD')}}</button>
        <button
          type="button"
          class="btn btn-brand gb-btn gb-btn-primary"
          @click="addRemoveRewards"
          :disabled="!isRewardFormComplete || updateRewardClicked==1"
          v-bind:class="updateRewardClicked==1 && 'gb-is-loading'"
        >{{ $t('BTN_UPDATE_REWARD_POINTS')}}</button>
      </template>
    </b-modal>
    <b-modal id="passwordUpdateModal" centered title="BootstrapVue">
      <template v-slot:modal-header>
        <h5 class="modal-title" id="exampleModalLabel">{{ $t('LBL_CHANGE_PASSWORD') }}</h5>
        <button type="button" class="close" @click="$bvModal.hide('passwordUpdateModal')"></button>
      </template>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">{{$t('LBL_PASSWORD') }}</label>
            <div class="col-lg-9">
              <div class="input-icon input-icon--right">
                <input
                  type="password"
                  v-model="adminsData.user_password"
                  name="user_password"
                  class="form-control"
                  ref="new-password"
                  autocomplete="new-password"
                  @focus="pswd_info = 1"
                  @blur="pswd_info = 0"
                  @keyup="onPasswordInput($event)"
                />
                <div
                  class="input-icon__icon input-icon__icon--right"
                  v-if="!Object.values(passwordValidate).includes(false)"
                >
                  <span>
                    <i class="password-toggle">
                      <img
                        :src="baseUrl + '/admin/images/retina/check.svg'"
                        width="16px"
                        height="16px"
                      />
                    </i>
                  </span>
                </div>
                <div id="pswd_info" v-show="pswd_info">
                  <h4>{{$t('LBL_PASSWORD_MUST_CONTAIN')}}:</h4>
                  <ul>
                    <li
                      id="length"
                      v-bind:class="[ passwordValidate.length === true ? 'valid' : 'invalid']"
                    >{{$t('MSG_VALIDATE_LENGTH')}}</li>
                    <li
                      id="letter"
                      v-bind:class="[ passwordValidate.letter === true ? 'valid' : 'invalid']"
                    >{{$t('MSG_VALIDATE_LETTER')}}</li>
                    <li
                      id="capital"
                      v-bind:class="[ passwordValidate.capital === true ? 'valid' : 'invalid']"
                    >{{$t('MSG_VALIDATE_UPPERCASE')}}</li>
                    <li
                      id="number"
                      v-bind:class="[ passwordValidate.number === true ? 'valid' : 'invalid']"
                    >{{$t('MSG_VALIDATE_NUMBER')}}</li>
                    <li
                      id="special"
                      v-bind:class="[ passwordValidate.special === true ? 'valid' : 'invalid']"
                    >{{$t('MSG_VALIDATE_SPECIAL_CHAR')}}</li>
                  </ul>
                </div>
                <span
                  v-if="errors.first('user_password')"
                  class="error text-danger"
                >{{ errors.first('user_password') }}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">{{$t('LBL_CONFIRM_PASSWORD') }}</label>
            <div class="col-lg-9">
              <div class="input-icon input-icon--right">
                <input
                  type="password"
                  v-model="adminsData.user_confirm_password"
                  name="user_confirm_password"
                  ref="confirm-password"
                  class="form-control"
                  autocomplete="new-password"
                  @keyup="onConfirmPasswordInput($event)"
                />
                <div
                  class="input-icon__icon input-icon__icon--right"
                  v-if="displayConfirmPasswordCheck === true"
                >
                  <span>
                    <i class="password-toggle">
                      <img
                        :src="baseUrl + '/admin/images/retina/check.svg'"
                        width="16px"
                        height="16px"
                      />
                    </i>
                  </span>
                </div>
                <span
                  v-if="errors.first('user_confirm_password')"
                  class="error text-danger"
                >{{ errors.first('user_confirm_password') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <template v-slot:modal-footer>
        <button
          type="button"
          class="btn btn-secondary"
          @click="$bvModal.hide('passwordUpdateModal')"
        >{{ $t('BTN_DISCARD')}}</button>
        <button
          type="button"
          :disabled="!changePasswordFormValidated || passClicked==1"
          @click="updateRecord('passwords')"
          class="btn btn-brand"
        >{{ $t('BTN_CHANGE_PASSWORD_SUBMIT')}}</button>
      </template>
    </b-modal>
  </div>
</template>
<script>
const format = /[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
import DatePick from "vue-date-pick";
import VueBootstrapTypeahead from "vue-bootstrap-typeahead";
import "vue-date-pick/dist/vueDatePick.css";
const tableFields = {
  user_id: "",
  user_fname: "",
  user_lname: "",
  user_email: "",
  user_password: "",
  user_confirm_password: "",
  user_phone: "",
  user_dob: "",
  country_code: "",
  user_gender: "",
  user_group_labels: ""
};
export default {
  components: {
    "vue-bootstrap-typeahead": VueBootstrapTypeahead,
    DatePick
  },
  data: function() {
    return {
      oldSearches: [],
      pagination: [],
      groups: [],
      businessName: "",
      gender: [],
      currencySymbol: currencySymbol,
      adminsData: tableFields,
      passwordFormat: format,
      orders: [],
      users: [],
      addresses: [],
      baseUrl: baseUrl,
      userSearch: "",
      adminBaseUrl: adminBaseUrl,
      loadingRightPanel: false,
      tabForm: "view",
      updateRewardClicked: 0,
      rewards: [],
      reviews: [],
      rewardPoints: {
        urp_type: "credit",
        urp_points: "",
        urp_comments: "",
        point_validity: ""
      },
      isFutureDate(date) {
        const currentDate = new Date();
        return date > currentDate;
      },
      coupons: [],
      rewardsBalance: 0,
      orderTotal: 0,
      savedTotal: 0,
      editField: { type: "" },
      currentEmail: "",
      defaultCountryCode: "",
      createdByUser: "",
      updatedByUser: "",
      updatedAt: "",
      createdAt: "",
      clicked: 0,
      passClicked: 0,
      pswd_info: 0,
      rewardPointsEnabled: "0",
      displayConfirmPasswordCheck: false,
      changePasswordFormValidated: false,
      passwordValidate: {
        length: false,
        letter: false,
        capital: false,
        number: false,
        special: false
      },
      userGroup: []
    };
  },
  computed: {
    isRewardFormComplete() {
      return this.rewardPoints.urp_points && this.rewardPoints.urp_comments && (this.rewardPoints.urp_type =='credit' ? this.rewardPoints.point_validity : true);
    },
    isUserFormComplete() {
      var emailFormat = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
      return this.adminsData.user_email != ""
        ? emailFormat.test(this.adminsData.user_email)
        : true &&
          this.adminsData.user_email == "" && this.adminsData.user_phone == ""
        ? false
        : true;
    }
  },
  watch: {
    userSearch: _.debounce(function(search) {
      this.getUsers(search);
    }, 500)
  },
  methods: {
    async getUsers(query) {
      let formData = this.$serializeData({
        searchedUsers: JSON.stringify(this.oldSearches),
        query: query
      });
      this.$http
        .post(adminBaseUrl + "/users/search", formData)
        .then(response => {
          this.users = response.data.data;
        });
    },
    selectedUser: function(event) {
      if (event != false) {
        this.adminsData.user_id = event.user_id;
        this.tabFilter("view");
      }
    },
    getRecordByKeyword: function(index) {
      if (this.adminsData.user_id == this.oldSearches[index].id) {
        return;
      }
      this.adminsData.user_id = this.oldSearches[index].id;
      this.tabFilter(this.tabForm);
    },
    addRemoveRewardsPopop: function() {
      this.rewardPoints = {
        urp_type: "credit",
        urp_points: "",
        urp_comments: ""
      };
      this.errors.clear();
      this.$bvModal.show("addRemoveRewardsModal");
    },
    removeKeyword: function(index, userId) {
      this.$delete(this.oldSearches, index);
      if (this.adminsData.user_id == userId && this.oldSearches.length > 0) {
        this.getRecordByKeyword(this.oldSearches.length - 1);
      } else if (this.oldSearches.length == 0) {
        this.$router.push({ name: "users" });
      }
    },
    addRemoveRewards: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          this.updateRewardClicked = 1;
          let formData = this.$serializeData(this.rewardPoints);
          if (typeof this.pagination.per_page != "undefined") {
            formData.append("per_page", this.pagination.per_page);
          }
          this.$http
            .post(
              adminBaseUrl + "/users/rewards/update/" + this.adminsData.user_id,
              formData
            )
            .then(response => {
              this.updateRewardClicked = 0;
              this.$bvModal.hide("addRemoveRewardsModal");
              toastr.success(response.data.message, "", toastr.options);
              this.rewardRecords(1);
            });
        } else {
          this.updateRewardClicked = 0;
        }
      });
    },
    tabsCurrentPage: function(page) {
      if (this.tabForm == "orders") {
        this.orderRecords(page);
      } else if (this.tabForm == "cancel" || this.tabForm == "return") {
        this.orderRequestRecords(page, this.tabForm);
      } else if (this.tabForm == "reviews") {
        this.reviewRecords(page);
      } else if (this.tabForm == "rewards") {
        this.rewardRecords(page);
      } else if (this.tabForm == "coupons") {
        this.couponRecords(page);
      } else if (this.tabForm == "addresses") {
        this.addressRecords(page);
      }
    },
    tabFilter: function(type) {
      this.loadingRightPanel = true;
      this.tabForm = type;
      this.pagination = [];
      this.orders = [];
      this.reviews = [];
      if (type == "view") {
        this.viewRecord(1);
      } else if (type == "orders") {
        this.orderRecords(1);
      } else if (type == "cancel" || type == "return") {
        this.orderRequestRecords(1, type);
      } else if (type == "reviews") {
        this.reviewRecords(1);
      } else if (type == "rewards") {
        this.rewardRecords(1);
      } else if (type == "coupons") {
        this.couponRecords(1);
      } else if (type == "addresses") {
        this.addressRecords(1);
      } else {
        this.loadingRightPanel = false;
      }
    },
    isEmailExist: function(needle, haystack) {
      var length = haystack.length;
      for (var i = 0; i < length; i++) {
        if (haystack[i].id == needle) return true;
      }
      return false;
    },
    updateRecord: function(type = "") {
      var inputData = _.pick(this.adminsData, ['user_fname', 'user_lname', 'user_gender', 'user_email', 'user_dob']);
      if (type == "") {
        this.clicked = 1;
        var formData = this.$serializeData(inputData);
        formData.append("_method", "PUT");
        
        let group_ids = [];
        $.each($("input[name='user_group_ids']:checked"), function() {
          group_ids.push($(this).val());
        });
        formData.append("groups", group_ids.join(","));

        if (typeof this.unmaskedPhoneNumber !== "undefined") {
          formData.append("user_phone", this.unmaskedPhoneNumber);
          formData.append("country_code", this.countryCode);
        }
      } else if (type == "passwords") {
        var formData = this.$serializeData({ _method: "PUT" });
        formData.append("user_password", this.adminsData.user_password);
        formData.append(
          "user_confirm_password",
          this.adminsData.user_confirm_password
        );
      }
      this.$http
        .post(adminBaseUrl + "/users/" + this.adminsData.user_id, formData)
        .then(
          response => {
            this.clicked = 0;
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            this.editField.type = "";
            toastr.success(response.data.message, "", toastr.options);
            if (type != "") {
              this.$bvModal.hide("passwordUpdateModal");
              (this.displayConfirmPasswordCheck = false),
                (this.changePasswordFormValidated = false),
                (this.passwordValidate = {
                  length: false,
                  letter: false,
                  capital: false,
                  number: false,
                  special: false
                });
              this.adminsData.user_password = this.adminsData.user_confirm_password =
                "";
            }
            this.errors.clear();
          },
          response => {
            this.clicked = 0;
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
    viewRecord: function() {
      this.$http
        .get(adminBaseUrl + "/users/" + this.adminsData.user_id)
        .then(response => {
          this.groups = response.data.data.user_groups;
          this.adminsData = response.data.data.user;
          this.rewardPointsEnabled = response.data.data.rewardPointsEnabled;
          this.adminsData.user_group_labels =
            response.data.data.user_group_labels;
          this.adminsData.user_lname =
            this.adminsData.user_lname != null
              ? this.adminsData.user_lname
              : "";
          this.adminsData.user_fname =
            this.adminsData.user_fname != null
              ? this.adminsData.user_fname
              : "";
          this.adminsData.user_email =
            this.adminsData.user_email != null
              ? this.adminsData.user_email
              : "";
          this.adminsData.user_phone =
            this.adminsData.user_phone != null
              ? this.adminsData.user_phone
              : "";
          this.adminsData.user_dob =
            this.adminsData.user_dob != null ? this.adminsData.user_dob : "";
          this.defaultCountryCode =
            this.adminsData.country_code != null
              ? this.adminsData.country_code.toLowerCase()
              : response.data.data.defaultCountry;
          this.gender = response.data.data.gender;
          this.createdByUser = response.data.data.user.created_at
            ? response.data.data.user.created_at.admin_username
            : "";
          this.updatedByUser = response.data.data.user.updated_at
            ? response.data.data.user.updated_at.admin_username
            : "";
          this.updatedAt = response.data.data.user.user_updated_at
            ? response.data.data.user.user_updated_at
            : "";
          this.createdAt = response.data.data.user.user_created_at
            ? response.data.data.user.user_created_at
            : "";
          if (
            this.isEmailExist(this.adminsData.user_id, this.oldSearches) ==
            false
          ) {
            this.oldSearches.push({
              id: this.adminsData.user_id,
              email: this.adminsData.user_email
            });
          }
          this.$refs.userTypeahead.inputValue = "";
          this.userSearch = "";
        });
    },
    addressRecords: function(pageNo) {
      let formData = this.$serializeData({});
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(
          adminBaseUrl +
            "/users/addresses/" +
            this.adminsData.user_id +
            "?page=" +
            pageNo,
          formData
        )
        .then(response => {
          this.addresses = response.data.data.data;
          this.pagination = response.data.data;
          this.loadingRightPanel = false;
        });
    },
    orderRequestRecords: function(pageNo, type) {
      let formData = this.$serializeData({ type: type });
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(
          adminBaseUrl +
            "/users/orders/" +
            type +
            "/" +
            this.adminsData.user_id +
            "?page=" +
            pageNo,
          formData
        )
        .then(response => {
          this.orders = response.data.data.orders.data;
          this.pagination = response.data.data.orders;
          this.orderTotal = response.data.data.order_total;
          this.loadingRightPanel = false;
        });
    },
    changeCountry: function(data) {
      this.countryCode = data.iso2;
      this.unmaskedPhoneNumber = this.$cleanPhoneNumber(this.adminsData.user_phone);
    },
    onPhoneNumberChange: function(data, obj) {
      this.countryCode = obj.country.iso2;
      this.unmaskedPhoneNumber = obj.number.input;
    },
    orderRecords: function(pageNo) {
      let formData = this.$serializeData({});
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(
          adminBaseUrl +
            "/users/orders/" +
            this.adminsData.user_id +
            "?page=" +
            pageNo,
          formData
        )
        .then(response => {
          this.orders = response.data.data.orders.data;
          this.pagination = response.data.data.orders;
          this.orderTotal = response.data.data.order_total;
          this.loadingRightPanel = false;
        });
    },
    reviewRecords: function(pageNo) {
      let formData = this.$serializeData({});
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(
          adminBaseUrl +
            "/users/reviews/" +
            this.adminsData.user_id +
            "?page=" +
            pageNo,
          formData
        )
        .then(response => {
          this.reviews = response.data.data.data;
          this.reviews = this.reviews.map(obj => ({ ...obj, collapsed: true }));
          this.pagination = response.data.data;
          this.loadingRightPanel = false;
        });
    },
    rewardRecords: function(pageNo) {
      let formData = this.$serializeData({});
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(
          adminBaseUrl +
            "/users/rewards/" +
            this.adminsData.user_id +
            "?page=" +
            pageNo,
          formData
        )
        .then(response => {
          this.rewards = response.data.data.rewards.data;
          this.rewardsBalance = response.data.data.usablePoints;
          this.pagination = response.data.data.rewards;
          this.loadingRightPanel = false;
          this.businessName = response.data.data.bussinessName;
        });
    },
    couponRecords: function(pageNo) {
      let formData = this.$serializeData({});
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(
          adminBaseUrl +
            "/users/coupons/" +
            this.adminsData.user_id +
            "?page=" +
            pageNo,
          formData
        )
        .then(response => {
          this.coupons = response.data.data.coupons.data;
          this.savedTotal = response.data.data.savedTotal;
          this.pagination = response.data.data.coupons;
          this.loadingRightPanel = false;
        });
    },
    joinGroup: function(groupId) {
      if (this.userGroup.indexOf(groupId) === -1) {
        this.userGroup.push(groupId);
      } else {
        this.userGroup.splice(this.userGroup.indexOf(groupId), 1);
      }
    },
    openChangePasswordPopup: function() {
      this.pswd_info = 0;
      this.$bvModal.show("passwordUpdateModal");
    },
    onConfirmPasswordInput: function(event) {
      this.changePasswordFormValidated = this.displayConfirmPasswordCheck = this.validatePasswordForm()
        ? true
        : false;
    },
    validatePasswordForm: function() {
      return (
        !Object.values(this.passwordValidate).includes(false) &&
        this.adminsData.user_password != "" &&
        this.adminsData.user_confirm_password != "" &&
        this.adminsData.user_password == this.adminsData.user_confirm_password
      );
    },
    onPasswordInput: function(event) {
      var pswd = event.target.value;
      if (pswd.length < 8) {
        this.passwordValidate.length = false;
      } else {
        this.passwordValidate.length = true;
      }
      if (pswd.match(/[A-z]/)) {
        this.passwordValidate.letter = true;
      } else {
        this.passwordValidate.letter = false;
      }
      if (pswd.match(/[A-Z]/)) {
        this.passwordValidate.capital = true;
      } else {
        this.passwordValidate.capital = false;
      }
      if (pswd.match(/[^\w]/)) {
        this.passwordValidate.special = true;
      } else {
        this.passwordValidate.special = false;
      }
      if (pswd.match(/\d/)) {
        this.passwordValidate.number = true;
      } else {
        this.passwordValidate.number = false;
      }
      this.changePasswordFormValidated = this.displayConfirmPasswordCheck = this.validatePasswordForm()
        ? true
        : false;
    }
  },
  mounted: function() {
    this.$validator.extend("greaterThanZero", {
      getMessage: field => field + " " + this.$t("MSG_NEED_TO_BE_GREATER_THAN_ZERO"),
      validate: value => {
        if (parseInt(value) > 0) return true;
        return false;
      }
    });
    this.$validator.extend("lessThanEqualToBalance", {
      getMessage: field => field + " " + this.$t("MSG_NEED_TO_BE_EQUAL_TO_BALANCE"),
      validate: value => {
        if (
          this.rewardPoints.urp_type == "debit" &&
          parseInt(value) > parseInt(this.rewardsBalance)
        )
          return false;
        return true;
      }
    });
    this.tabForm = "view";
    this.adminsData.user_id = this.$route.params.id;
    this.viewRecord();
  }
};
</script>
<style scoped>
.no-border {
  border: none;
  cursor: pointer;
}
.wrapper {
  position: relative;
}
</style>