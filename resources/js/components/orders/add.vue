<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <div class="with-arrow mb-3">
            <button
              class="btn"
              type="button"
              @click="swichOrder(lastOrderId)"
              v-if="lastOrderId != 0"
            >
              <i class="fas fa-arrow-left"></i>
            </button>
            <router-link
              v-if="lastOrderId == 0"
              :to="{ name: 'orders' }"
              class="btn"
            >
              <i class="fas fa-arrow-left"></i>
            </router-link>
            <h3 class="subheader__title" v-if="lastOrderId != 0">
              #{{ lastOrderId + 1 }}
            </h3>
            <h3 class="subheader__title" v-if="lastOrderId == 0">
              {{ $t("LBL_ORDERS") }}
            </h3>
          </div>
          <div class="subheader__breadcrumbs">
            <router-link
              :to="{ name: 'dashboard' }"
              class="subheader__breadcrumbs-home"
            >
              <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">
              {{ $t("LBL_ORDERS") }}
            </span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">
              {{ $t("LBL_CREATE_ORDER") }}
            </span>
          </div>
        </div>
        <div class="subheader__toolbar">
          <div class="subheader__wrapper">
            <div class="btn-orders">
              <button
                class="btn"
                @click="
                  (shippingEnable = 2),
                    (selectedShippingStatus = 1),
                    calculateOrderSummary()
                "
                v-bind:class="
                  shippingEnable == 2 ? 'btn-white' : 'btn-subheader'
                "
              >
                {{ $t("BTN_PICK_UP") }}
              </button>
              <button
                class="btn"
                @click="
                  (shippingEnable = 1),
                    (selectedShippingStatus = 1),
                    calculateOrderSummary()
                "
                v-bind:class="
                  shippingEnable == 1 ? 'btn-white' : 'btn-subheader'
                "
              >
                {{ $t("BTN_SHIP") }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="portlet portlet-no-min-height">
            <div class="portlet__body pb-0 bg-gray flex-grow-0">
              <div class="form-group">
                <div class="input-icon input-icon--left">
                  <vue-bootstrap-typeahead
                    :serializer="(s) => s.label"
                    v-model="productSearch"
                    :data="products"
                    :inputClass="adminsData.addr_email == '' ? 'disabled' : ''"
                    ref="productTypeahead"
                    :placeholder="$t('PLH_SEARCH_PRODUCT')"
                    @hit="selectedProduct($event)"
                    :max-matches="50"
                    :min-matching-chars="1"
                  />
                  <span class="input-icon__icon input-icon__icon--left">
                    <span>
                      <i class="la la-search"></i>
                    </span>
                  </span>
                </div>
              </div>
            </div>
            <hr class="m-0" />
            <div class="portlet__body">
              <ul
                class="
                  list-group
                  list-group-xs
                  list-cart
                  list-cart-order
                  list-add-orders
                  mb-5
                "
                style="min-height: 270px"
              >
                <li
                  class="list-group-item YK-Errors"
                  v-for="(product, pindex) in selectedProducts"
                  :key="pindex"
                >
                  <div class="product-profile">
                    <div class="product-profile__thumbnail">
                      <a href="javascript:;">
                        <img
                          :src="
                            $productImage(
                              product.id,
                              product.pov_code,
                              '',
                              '52-52'
                            )
                          "
                          alt="..."
                          class="img-fluid"
                          width="100px"
                        />
                      </a>
                    </div>
                    <div class="product-profile__data">
                      <div class="title">
                        <a
                          href="javascript:;"
                          class="text-body"
                          :id="'product-' + product.id"
                          >{{ product.name }}</a
                        >
                      </div>

                      <div class="options" v-if="product.option">
                        <p class>{{ product.option }}</p>
                      </div>
                      <p
                        class="save-later"
                        v-if="
                          giftWarpEnable == 1 && product.giftWrapEnable == 1
                        "
                      >
                        <a
                          href="javascript:;"
                          class="YK-GiftItem gift-item"
                          v-bind:class="[product.giftWrap != 0 ? 'active' : '']"
                          @click="addGiftWrap(pindex, product.giftWrap)"
                          >Gift wrap
                          <svg class="svg">
                            <use
                              :xlink:href="
                                baseUrl +
                                '/admin/images/retina/sprite.svg#gift-icon'
                              "
                              :href="
                                baseUrl +
                                '/admin/images/retina/sprite.svg#gift-icon'
                              "
                            />
                          </svg>
                        </a>
                      </p>

                      <span
                        v-if="errors.first(product.name)"
                        class="error text-danger mt-2"
                        >{{ errors.first(product.name) }}</span
                      >
                      <span
                        class="error text-danger mt-2"
                        v-if="product.stock != true"
                        >Product not in Stock</span
                      >
                    </div>
                  </div>
                  <div class="product-quantity">
                    <div
                      class="quantity quantity-2"
                      v-bind:class="[product.stock != true ? 'disable' : '']"
                    >
                      <span
                        class="decrease"
                        v-bind:class="
                          product.selectedqty == product.minQty
                            ? 'disabled'
                            : ''
                        "
                        @click="updateQuantity(product, 'less')"
                      >
                        <i class="icn">
                          <svg class="svg">
                            <use
                              :xlink:href="
                                baseUrl +
                                '/dashboard/media/retina/sprite.svg#minus'
                              "
                              :href="
                                baseUrl +
                                '/dashboard/media/retina/sprite.svg#minus'
                              "
                            ></use>
                          </svg>
                        </i>
                      </span>
                      <input
                        type="text"
                        v-model="product.selectedqty"
                        :min="product.minQty"
                        :max="product.maxQty"
                        readonly="readonly"
                        class="qty-input no-focus"
                      />
                      <span
                        class="increase"
                        v-bind:class="
                          product.selectedqty == product.maxQty
                            ? 'disabled'
                            : ''
                        "
                        @click="updateQuantity(product, 'add')"
                      >
                        <i class="icn">
                          <svg class="svg">
                            <use
                              :xlink:href="
                                baseUrl +
                                '/dashboard/media/retina/sprite.svg#add'
                              "
                              :href="
                                baseUrl +
                                '/dashboard/media/retina/sprite.svg#add'
                              "
                            ></use>
                          </svg>
                        </i>
                      </span>
                    </div>
                  </div>
                  <div class="product-price font-weight-bold">
                    {{ currencySymbol }}
                    <span v-if="product.includeTax == 1">
                      {{
                        ((product.price / (1 + product.tax)) *
                          product.selectedqty)
                          | numberFormat
                      }}
                    </span>
                    <span v-else>{{
                      (product.selectedqty * product.price) | numberFormat
                    }}</span>
                  </div>
                  <div class="product-action">
                    <button
                      class="btn btn-icon btn-sm"
                      @click="removeItem(pindex, product.id, product.pov_code)"
                    >
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </li>
                <!-- else condision -->
                <li class="list-group-item" v-if="selectedProducts.length == 0">
                  <div class="product-profile">
                    <div class="product-profile__thumbnail skeleton">
                      <a href="javascript:;"></a>
                    </div>
                    <div class="product-profile__data">
                      <div class="title skeleton">
                        <a href="javascript:;" class="text-body">&nbsp</a>
                      </div>
                      <div class="options skeleton">
                        <p>&nbsp</p>
                      </div>
                    </div>
                  </div>
                  <div class="product-quantity">
                    <div class="qty-wrap">
                      <span class="skeleton">&nbsp</span>
                      <span class="skeleton">&nbsp</span>
                      <span class="skeleton">
                        &nbsp
                        <span class="skeleton">&nbsp</span>
                      </span>
                    </div>
                  </div>
                  <div class="product-price font-weight-bold skeleton">
                    &nbsp
                    <span>&nbsp</span>
                  </div>
                  <div class="product-action skeleton">&nbsp&nbsp&nbsp</div>
                </li>
              </ul>

              <div class="row">
                <div
                  class="col-md-6"
                  v-bind:class="selectedProducts.length == 0 ? 'disabled' : ''"
                >
                  <div
                    class="digital-order-admin"
                    v-if="
                      selectedProducts.length != 0 &&
                      shippingEnable == 1 &&
                      inArray(1, this.productTypes) == false
                    "
                  >
                    <img
                      :src="
                        baseUrl +
                        '/admin/images/retina/downloadable_purchase.svg'
                      "
                    />
                  </div>
                  <div class="bg-gray p-4 rounded h-100" v-else>
                    <h5 class="mb-4" v-if="shippingEnable == 1">
                      {{ $t("LBL_SHIPPING_METHOD") }}
                    </h5>
                    <h5 class="mb-4" v-if="shippingEnable != 1">Pick Up</h5>

                    <div
                      class="shipping-section"
                      v-if="
                        allShippings.length != 0 &&
                        shippingEnable == 1 &&
                        adminsData.addr_user_id != '' &&
                        selectedProducts.length > 0
                      "
                    >
                      <div
                        class="shipping-option"
                        v-for="(shipping, productIds) in allShippings"
                        :key="productIds"
                      >
                        <ul class="media-more media-more-sm show">
                          <li
                            v-for="(productId, productKey) in productIds
                              .split(',')
                              .slice(0, 5)"
                            :key="productKey"
                          >
                            <span
                              class="circle"
                              data-toggle="tooltip"
                              data-placement="top"
                              data-original-title="product name"
                            >
                              <img :src="getProductImage(productId)" alt />
                            </span>
                          </li>
                          <li v-if="productIds.split(',').length > 5">
                            <span class="plus-more"
                              >+{{ productIds.split(",").length - 5 }}</span
                            >
                          </li>
                        </ul>

                        <select
                          class="form-control custom-select YK-selectedShipping"
                          :name="'shipping' + productIds"
                          @change="shippingCal(productIds)"
                        >
                          <option value>Select Shipping</option>
                          <option
                            v-for="(val, rateKey) in shipping"
                            :value="val.price"
                            class="shippings"
                            :data-key="rateKey"
                            :key="rateKey"
                            v-if="val.error != true"
                          >
                            {{ val.name }} - {{ currencySymbol
                            }}{{ val.price | numberFormat }}
                          </option>
                        </select>
                      </div>
                    </div>
                    <div
                      class="shipping-section"
                      v-if="selectedProducts.length == 0"
                    >
                      <div class="shipping-option">
                        <ul class="media-more media-more-sm show">
                          <li>
                            <span
                              data-toggle="tooltip"
                              data-placement="top"
                              title
                              data-original-title="product name"
                              class="circle skeleton"
                            ></span>
                          </li>
                        </ul>
                        <div
                          name=" "
                          class="form-control custom-select skeleton"
                        ></div>
                      </div>
                    </div>

                    <div
                      class="pick-section"
                      v-if="
                        shippingEnable != 1 &&
                        adminsData.addr_user_id != '' &&
                        selectedProducts.length > 0
                      "
                    >
                      <div class="pickup-option">
                        <ul class="pickup-option__list">
                          <li
                            class
                            v-for="(paddress, pickupKey) in pickupAddress"
                            :key="pickupKey"
                          >
                            <label class="radio">
                              <input
                                type="radio"
                                :value="paddress.saddr_id"
                                name="pickup"
                                @click="selectPickup(paddress)"
                              />
                              {{
                                paddress.saddr_name +
                                " " +
                                paddress.saddr_address +
                                " " +
                                paddress.saddr_city +
                                " " +
                                paddress.state_name +
                                " " +
                                paddress.country_name
                              }}
                              <span></span>
                            </label>
                          </li>
                        </ul>
                        {{ $t("LBL_PICKUP_TIME") }}
                        <div
                          class="pickup_time"
                          v-if="
                            shippingEnable != 1 &&
                            adminsData.addr_user_id != '' &&
                            selectedProducts.length > 0
                          "
                        >
                          <div class="calendar">
                            <date-pick
                              v-model="pickuptime"
                              :parseDate="parseDate"
                              :formatDate="formatDate"
                              :format="$dateTimeFormat(false) + ' HH:mm'"
                              :hasInputElement="true"
                              :pickTime="true"
                              :placeholder="$t('PLH_DATE_RANGE')"
                              :isDateDisabled="isPastDate"
                              :inputAttributes="{
                                class: 'form-control',
                                readonly: true,
                              }"
                              class="d-block"
                            ></date-pick>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  class="col-md-6"
                  v-bind:class="selectedProducts.length == 0 ? 'disabled' : ''"
                >
                  <div class="bg-gray p-4 rounded h-100">
                    <h5 class="mb-4">{{ $t("LBL_SUMMARY") }}</h5>

                    <ul
                      class="
                        list-group
                        list-group-sm
                        list-group-flush-y
                        list-group-flush-x
                      "
                      v-if="selectedProducts.length == 0"
                    >
                      <li
                        class="list-group-item skeleton"
                        v-for="(item, key) in 4"
                        :key="key"
                      ></li>
                    </ul>

                    <ul
                      class="
                        list-group
                        list-group-sm
                        list-group-flush-y
                        list-group-flush-x
                      "
                      v-else
                    >
                      <li class="list-group-item d-flex">
                        <span>{{ $t("LBL_SUBTOTAL") }}</span>
                        <span class="ml-auto"
                          >{{ currencySymbol
                          }}{{ subTotal | numberFormat }}</span
                        >
                      </li>
                      <li class="list-group-item d-flex">
                        <span>{{ $t("LBL_TAX") }}</span>
                        <span class="ml-auto"
                          >{{ currencySymbol
                          }}{{ totalTax | numberFormat }}</span
                        >
                      </li>

                      <li class="list-group-item d-flex">
                        <span>
                          <a href="javascript:" @click="editDiscount = 1">
                            {{ $t("LBL_DISCOUNT") }}
                          </a>
                        </span>
                        <span
                          class="ml-auto"
                          v-if="editDiscount == 0"
                          @click="editDiscount = 1"
                          title="click to edit"
                          >{{ currencySymbol
                          }}{{ totalDiscount | numberFormat }}</span
                        >
                        <span class="ml-auto w-25" v-if="editDiscount == 1">
                          <input
                            class="form-control form-control-sm"
                            name="appliedDiscount"
                            type="text"
                            @keypress="$onlyNumber"
                            v-validate="'required|decimal:0|min_value:0'"
                            :data-vv-as="$t('LBL_DISCOUNT')"
                            v-model="appliedDiscount"
                            @blur="updatedDiscount()"
                          />
                        </span>
                        <div v-if="errors.first('appliedDiscount')">
                          <span class="error text-danger">{{
                            errors.first("appliedDiscount")
                          }}</span>
                        </div>
                      </li>
                      <li
                        class="list-group-item d-flex"
                        v-if="
                          shippingEnable == 1 &&
                          inArray(1, this.productTypes) != false
                        "
                      >
                        <span>{{ $t("LBL_SHIPPING") }}</span>
                        <span class="ml-auto"
                          >{{ currencySymbol
                          }}{{ totalShipping | numberFormat }}</span
                        >
                      </li>
                      <li
                        class="list-group-item d-flex"
                        v-if="selectedGiftCount > 0"
                      >
                        <span>{{ $t("LBL_GIFT_WRAP_AMOUNT") }}</span>
                        <span class="ml-auto">
                          {{ currencySymbol
                          }}{{ totalGWarpPrice | numberFormat }}
                        </span>
                      </li>
                      <li
                        class="
                          list-group-item
                          d-flex
                          font-size-lg font-weight-bold
                        "
                      >
                        <span>{{ $t("LBL_TOTAL") }}</span>
                        <span class="ml-auto">
                          {{ currencySymbol
                          }}{{ (total - totalDiscount) | numberFormat }}
                        </span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div v-bind:class="selectedProducts.length == 0 ? 'disabled' : ''">
              <hr class="m-0" />
              <div class="portlet__body">
                <h6>
                  {{
                    shippingEnable == 1
                      ? $t("LBL_ORDERSTATUS")
                      : "Pickup Status"
                  }}
                </h6>
                <ul class="order-status-click" v-if="shippingEnable == 1">
                  <li
                    v-for="(statusVal, statusId) in shipstatus"
                    :key="statusId"
                    @click="updateOrderStatus(statusId)"
                    v-bind:class="[
                      selectedShippingStatus == statusId ? 'active' : '',
                    ]"
                  >
                    <i class="icn">
                      <svg class="svg">
                        <use
                          :xlink:href="
                            baseUrl +
                            '/admin/images/retina/sprite.svg#' +
                            statusVal
                          "
                          :href="
                            baseUrl +
                            '/admin/images/retina/sprite.svg#' +
                            statusVal
                          "
                        />
                      </svg>
                    </i>
                    {{ statusVal | removeHyphen | camelCase }}
                  </li>
                </ul>
                <ul class="order-status-click" v-else>
                  <li
                    v-for="(pickStatus, pickId) in pickupStatus"
                    :key="pickId"
                    @click="updateOrderStatus(pickId)"
                    v-bind:class="[
                      selectedShippingStatus == pickId ? 'active' : '',
                    ]"
                  >
                    <i class="icn">
                      <svg class="svg">
                        <use
                          :xlink:href="
                            baseUrl +
                            '/admin/images/retina/sprite.svg#' +
                            pickStatus
                          "
                          :href="
                            baseUrl +
                            '/admin/images/retina/sprite.svg#' +
                            pickStatus
                          "
                        />
                      </svg>
                    </i>
                    {{ pickStatus | removeHyphen | camelCase }}
                  </li>
                </ul>
              </div>
              <div class="portlet__body bg-gray">
                <div class="row align-items-center">
                  <div class="col">
                    <h6 class="m-0">
                      <i class="far fa-credit-card"></i>
                      {{ $t("LBL_PAYMENT") | upperCase }}
                    </h6>
                  </div>
                  <div class="col-auto">
                    <button
                      class="btn btn-light"
                      type="button"
                      v-bind:class="[paymentType == 1 ? 'active' : '']"
                      @click="paymentType = 1"
                    >
                      {{ $t("BTN_CASH") }}
                    </button>
                    <button
                      class="btn btn-light"
                      type="button"
                      v-bind:class="[paymentType == 2 ? 'active' : '']"
                      @click="
                        $bvModal.show('cardModel');
                        paymentType = 2;
                      "
                      :disabled="total - this.totalDiscount === 0"
                    >
                      {{ $t("BTN_CARD") }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="portlet__foot">
              <div class="row">
                <div class="col">
                  <router-link
                    :to="{ name: 'orders' }"
                    class="btn btn-secondary btn-wide"
                    >{{ $t("BTN_DISCARD") }}</router-link
                  >
                </div>
                <div
                  class="col-auto"
                  v-bind:class="[
                    selectedProducts.length == 0 ? 'disabled' : '',
                  ]"
                >
                  <button
                    type="button"
                    :disabled="selectedProducts.length == 0"
                    class="btn btn-brand btn-wide"
                    @click="saveOrder"
                  >
                    <span>{{ $t("BTN_SAVE_ORDER") }}</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="portlet portlet-no-min-height">
            <div class="portlet__body pb-0 bg-gray">
              <div class="form-group">
                <label>{{ $t("LBL_SEARCH_CUSTOMER") }}</label>
                <div class="input-icon input-icon--left">
                  <div>
                    <div class="input-group">
                      <input
                        type="text"
                        :placeholder="$t('PLH_SEARCH_CUSTOMER_BY_EMAIL')"
                        :aria-label="$t('PLH_SEARCH_CUSTOMER_BY_EMAIL')"
                        autocomplete="true"
                        class="form-control"
                        data-toggle="dropdown"
                        aria-expanded="true"
                        v-model="userSearch"
                      />

                      <div
                        class="
                          dropdown-menu
                          dropdown-menu-fit
                          dropdown-menu-anim
                          dropdown-suggestions
                        "
                      >
                        <ul class="nav nav--block">
                          <li
                            class="dropdown-item nav__item"
                            v-for="(user, userIndex) in users"
                            :key="userIndex"
                          >
                            <a href="javascript:;" @click="selectedUser(user)"
                              >{{ user.user_name }}({{
                                user.user_email
                                  ? user.user_email
                                  : user.user_phone
                              }})</a
                            >
                          </li>

                          <li class="dropdown-divider"></li>
                          <li class="dropdown-item nav__item">
                            <a
                              class
                              href="javascript:;"
                              data-toggle="tooltip"
                              title
                              data-placement="right"
                              data-skin="dark"
                              data-container="body"
                              data-original-title="Tooltip title"
                              @click="addUser()"
                            >
                              <i class="la la-plus"></i>
                              {{ $t("LNK_ADD_NEW_CUSTOMER") }}
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <span class="input-icon__icon input-icon__icon--left">
                    <span>
                      <i class="la la-search"></i>
                    </span>
                  </span>

                  <span
                    class="input-icon__icon input-icon__icon--right"
                    @click="clearSearch"
                    v-if="userSearch != ''"
                  >
                    <span>
                      <i class="fas fa-times"></i>
                    </span>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="portlet portlet-no-min-height">
            <div
              class="portlet__head"
              v-bind:class="
                Object.keys(userDetail).length === 0 ? 'disabled' : ''
              "
            >
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i class="fas fa-envelope"></i>
                  {{ $t("LBL_CONTACT_INFORMATION") }}
                </h3>
              </div>
            </div>
            <div
              class="portlet__body"
              v-bind:class="
                Object.keys(userDetail).length === 0 ? 'disabled' : ''
              "
            >
              <p class="list-text">
                <span class="lable">{{ $t("LBL_EMAIL") }}:</span>
                {{ userDetail ? userDetail.user_email : "" }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_PHONE") }}:</span>
                {{
                  userDetail
                    ? userDetail.phone_country != null
                      ? "+" + userDetail.phone_country.country_phonecode + " "
                      : ""
                    : ""
                }}
                {{ userDetail.user_phone ? userDetail.user_phone : "" }}
              </p>
            </div>
          </div>
          <div class="portlet portlet-no-min-height" v-if="shippingEnable != 1">
            <div
              class="portlet__head"
              v-bind:class="
                Object.keys(selectedpickupAddress).length == 0 ? 'disabled' : ''
              "
            >
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i class="fas fa-people-carry"></i>
                  {{ $t("LBL_PICKUPADDRESS") }}
                </h3>
              </div>
            </div>
            <div
              class="portlet__body"
              v-bind:class="
                Object.keys(selectedpickupAddress).length == 0 ? 'disabled' : ''
              "
            >
              <p class="list-text">
                <span class="lable">{{ $t("LBL_NAME") }}:</span>
                {{ selectedpickupAddress.saddr_name }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_SHOP_ADDRESS") }}:</span>
                {{ selectedpickupAddress.saddr_address }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_CITY_STATE") }}:</span>
                <span v-if="Object.keys(selectedpickupAddress).length > 0">
                  {{
                    selectedpickupAddress.saddr_city +
                    ", " +
                    shippingAddress.state_name
                  }}
                </span>
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_POSTAL_CODE") }}:</span>
                {{ selectedpickupAddress.saddr_postal_code }}
              </p>
              <p class="list-text">
                <span class="lable">{{ $t("LBL_COUNTRY") }}:</span>
                {{ selectedpickupAddress.country_name }}
              </p>
            </div>
          </div>
          <div class="portlet portlet-no-min-height" v-if="shippingEnable == 1">
            <div
              class="portlet__head"
              v-bind:class="
                Object.keys(shippingAddress).length == 0 &&
                Object.keys(userDetail).length == 0
                  ? 'disabled'
                  : ''
              "
            >
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i class="fas fa-shipping-fast"></i>
                  {{ $t("LBL_SHIPPINGADDRESS") }}
                </h3>
              </div>
              <div
                class="portlet__head-toolbar"
                v-bind:class="
                  Object.keys(shippingAddress).length == 0 &&
                  Object.keys(userDetail).length == 0
                    ? 'disabled'
                    : ''
                "
              >
                <div class="portlet__head-actions">
                  <a
                    href="javascript:;"
                    class="btn btn-secondary btn-sm"
                    @click="editUserAddress('shipping')"
                    v-bind:class="
                      Object.keys(shippingAddress).length == 0 &&
                      Object.keys(userDetail).length == 0
                        ? 'disabled'
                        : ''
                    "
                    >{{ $t("LNK_MANAGE") }}</a
                  >
                </div>
              </div>
            </div>
            <div
              class="portlet__body"
              v-bind:class="
                Object.keys(shippingAddress).length == 0 &&
                Object.keys(userDetail).length == 0
                  ? 'disabled'
                  : ''
              "
            >
              <span>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_NAME") }}:</span>
                  <span v-if="Object.keys(shippingAddress).length > 0">
                    {{
                      shippingAddress.addr_first_name +
                      " " +
                      shippingAddress.addr_last_name
                    }}
                  </span>
                </p>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_APARTMENT_NO") }}:</span>
                  {{ shippingAddress.addr_address1 }}
                </p>
                <p class="list-text" v-if="shippingAddress.addr_address2">
                  <span class="lable">{{ $t("LBL_ADDRESS") }}:</span>
                  {{ shippingAddress.addr_address2 }}
                </p>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_CITY_STATE") }}:</span>
                  <span v-if="Object.keys(shippingAddress).length > 0">
                    {{
                      shippingAddress.addr_city +
                      ", " +
                      shippingAddress.state_name
                    }}
                  </span>
                </p>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_POSTAL_CODE") }}:</span>
                  {{ shippingAddress.addr_postal_code }}
                </p>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_COUNTRY") }}:</span>
                  {{ shippingAddress.country_name }}
                </p>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_PHONE") }}:</span>
                  {{
                    shippingAddress.phonecountry
                      ? "+" + shippingAddress.phonecountry.country_phonecode
                      : "+" + shippingAddress.phonecode
                  }}
                  {{
                    shippingAddress.addr_phone ? shippingAddress.addr_phone : ""
                  }}
                </p>
              </span>
            </div>
          </div>

          <div class="portlet portlet-no-min-height">
            <div
              class="portlet__head"
              v-bind:class="
                Object.keys(billingAddress).length == 0 &&
                Object.keys(userDetail).length == 0
                  ? 'disabled'
                  : ''
              "
            >
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  <i class="fas fa-file-invoice"></i>
                  {{ $t("LBL_BILLING_ADDRESS") }}
                </h3>
              </div>
              <div class="portlet__head-toolbar">
                <div class="portlet__head-actions">
                  <a
                    href="javascript:;"
                    class="btn btn-secondary btn-sm"
                    @click="editUserAddress('billing')"
                    v-bind:class="
                      Object.keys(billingAddress).length == 0 &&
                      Object.keys(userDetail).length == 0
                        ? 'disabled'
                        : ''
                    "
                    >Manage</a
                  >
                </div>
              </div>
            </div>
            <div
              class="portlet__body"
              v-bind:class="
                Object.keys(billingAddress).length == 0 ? 'disabled' : ''
              "
            >
              <span>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_NAME") }}:</span>
                  <span v-if="Object.keys(billingAddress).length > 0">
                    {{
                      billingAddress.addr_first_name +
                      " " +
                      billingAddress.addr_last_name
                    }}
                  </span>
                </p>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_APARTMENT_NO") }}:</span>
                  {{ billingAddress.addr_address1 }}
                </p>
                <p class="list-text" v-if="billingAddress.addr_address2">
                  <span class="lable">{{ $t("LBL_ADDRESS") }}:</span>
                  {{ billingAddress.addr_address2 }}
                </p>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_CITY_STATE") }}:</span>
                  <span v-if="Object.keys(billingAddress).length > 0">
                    {{
                      billingAddress.addr_city +
                      ", " +
                      billingAddress.state_name
                    }}
                  </span>
                </p>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_POSTAL_CODE") }}:</span>
                  {{ billingAddress.addr_postal_code }}
                </p>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_COUNTRY") }}:</span>
                  {{ billingAddress.country_name }}
                </p>
                <p class="list-text">
                  <span class="lable">{{ $t("LBL_PHONE") }}:</span>
                  {{
                    billingAddress.phonecountry
                      ? "+" + billingAddress.phonecountry.country_phonecode
                      : "+" + billingAddress.phonecode
                  }}

                  {{ billingAddress.addr_phone }}
                </p>
              </span>
            </div>
          </div>
          <div
            class="portlet portlet-no-min-height"
            v-if="selectedGiftCount > 0"
          >
            <div class="portlet__head">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">
                  {{ $t("LBL_GIFT_WRAP_MESSAGE") }}
                </h3>
              </div>
            </div>
            <div class="portlet__body">
              <div class="form-group">
                <input
                  name
                  id
                  v-model="gift.to"
                  :placeholder="$t('PLH_TO')"
                  class="form-control"
                />
              </div>
              <div class="form-group">
                <input
                  name
                  id
                  v-model="gift.from"
                  :placeholder="$t('PLH_FROM')"
                  class="form-control"
                />
              </div>
              <div class="form-group">
                <textarea
                  name
                  id
                  v-model="gift.message"
                  :placeholder="$t('PLH_MESSAGE')"
                  spellcheck="false"
                  class="form-control"
                ></textarea>
              </div>
            </div>
          </div>
          <div class="portlet portlet-no-min-height">
            <div
              class="portlet__head"
              v-bind:class="selectedProducts.length == 0 ? 'disabled' : ''"
            >
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">{{ $t("LBL_NOTES") }}</h3>
              </div>
            </div>
            <div
              class="portlet__body pb-0"
              v-bind:class="selectedProducts.length == 0 ? 'disabled' : ''"
            >
              <div class="form-group">
                <textarea
                  name
                  v-model="note"
                  :placeholder="$t('PLH_ADD_A_NOTE')"
                  class="form-control"
                  :disabled="selectedProducts.length == 0"
                  spellcheck="false"
                ></textarea>
              </div>
            </div>
          </div>
          <div class="portlet portlet-no-min-height">
            <div
              class="portlet__head"
              v-bind:class="selectedProducts.length == 0 ? 'disabled' : ''"
            >
              <div class="portlet__head-label">
                <h3 class="portlet__head-title">{{ $t("LBL_TAGS") }}</h3>
              </div>
            </div>
            <div
              class="portlet__body"
              v-bind:class="selectedProducts.length == 0 ? 'disabled' : ''"
            >
              <div>
                <vue-tags-input
                  class="vue-tags-style"
                  v-model="tag"
                  :tags="tags"
                  @tags-changed="updateTags"
                  :placeholder="$t('PLH_ADD_ORDER_TAGS')"
                  name="tag"
                ></vue-tags-input>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal id="userAddress" centered title="BootstrapVue">
      <template v-slot:modal-header="{ close }">
        <h5 class="modal-title">{{ $t("LBL_ADDRESS_FORM") }}</h5>
        <button type="button" class="close" @click="close()"></button>
      </template>
      <ul
        class="my-addresses"
        v-if="allAddress.length > 0 && editExistingAddress == 0"
      >
        <perfect-scrollbar :style="'max-height: 330px'">
          <li
            class="addresses-list"
            v-for="(address, addId) in allAddress"
            :key="addId"
          >
            <label class="my-addresses__label" :for="'address-' + addId">
              <input
                type="radio"
                @click="selectedAddressId = addId"
                name="addr_id"
                :id="'address-' + addId"
              />
              <div class="my-addresses__body">
                <address class="delivery-address">
                  <h5>
                    {{ address.addr_first_name + " " + address.addr_last_name }}
                    <span class="tag">{{ address.addr_title }}</span>
                  </h5>
                  <p>
                    {{ address.addr_address1 }},
                    {{
                      address.addr_address2 ? address.addr_address2 + ", " : ""
                    }}
                    {{ address.addr_city }}, {{ address.state_name }},
                    {{ address.country_name }},
                  </p>
                  <p class="phone-txt">
                    <i class="fas fa-mobile-alt"></i>
                    {{ "+" + address.phonecountry.country_phonecode }}
                    {{ address.addr_phone }}
                  </p>
                </address>
              </div>
              <div class="actions">
                <a href="javascript:;" @click="editAddress(addId)">
                  <svg class="svg">
                    <use
                      :xlink:href="
                        baseUrl + '/admin/images/retina/sprite.svg#edit-icon'
                      "
                      :href="
                        baseUrl + '/admin/images/retina/sprite.svg#edit-icon'
                      "
                    />
                  </svg>
                </a>
              </div>
            </label>
          </li>
        </perfect-scrollbar>
      </ul>
      <div
        class="d-flex justify-content-center my-3"
        v-if="allAddress.length > 0 && editExistingAddress == 0"
      >
        <a
          class="btn btn-icon btn-outline-brand yk-addAddressPopup"
          href="javascript:"
          @click="addNewAddress()"
        >
          <i class="fas fa-plus"></i> Add a new address
        </a>
      </div>
      <div
        class="row"
        v-if="allAddress.length == 0 || editExistingAddress == 1"
      >
        <div class="col-md-12">
          <div class="form-group">
            <input
              type="email"
              class="form-control"
              v-model="adminsData.addr_email"
              name="addr_email"
              :placeholder="$t('LBL_EMAIL')"
              v-validate="'required'"
              data-vv-validate-on="none"
              :data-vv-as="$t('LBL_EMAIL')"
            />
            <span v-if="errors.first('addr_email')" class="error text-danger">
              {{ errors.first("addr_email") }}
            </span>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <input
              type="text"
              class="form-control"
              v-model="adminsData.addr_first_name"
              name="addr_first_name"
              :placeholder="$t('LBL_FIRST_NAME')"
              v-validate="'required'"
              data-vv-validate-on="none"
              :data-vv-as="$t('LBL_FIRST_NAME')"
            />
            <span
              v-if="errors.first('addr_first_name')"
              class="error text-danger"
              >{{ errors.first("addr_first_name") }}</span
            >
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <input
              type="text"
              class="form-control"
              v-model="adminsData.addr_last_name"
              name="addr_last_name"
              :placeholder="$t('LBL_LAST_NAME')"
              v-validate="'required'"
              data-vv-validate-on="none"
              :data-vv-as="$t('LBL_LAST_NAME')"
            />
            <span
              v-if="errors.first('addr_last_name')"
              class="error text-danger"
              >{{ errors.first("addr_last_name") }}</span
            >
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group">
            <input
              type="text"
              class="form-control"
              v-model="adminsData.addr_address1"
              name="addr_address1"
              :placeholder="$t('PLH_APARTMENT_SUITE_ETC')"
              v-validate="'required'"
              data-vv-validate-on="none"
              :data-vv-as="$t('PLH_APARTMENT_SUITE_ETC')"
            />
            <span
              v-if="errors.first('addr_address1')"
              class="error text-danger"
              >{{ errors.first("addr_address1") }}</span
            >
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group">
            <input
              type="text"
              class="form-control"
              v-model="adminsData.addr_address2"
              :placeholder="$t('LBL_ADDRESS')"
            />
          </div>
        </div>

        <div class="col-md-5">
          <div class="form-group">
            <input
              type="text"
              class="form-control"
              :placeholder="$t('LBL_CITY')"
              v-model="adminsData.addr_city"
              name="addr_city"
              v-validate="'required'"
              data-vv-validate-on="none"
              :data-vv-as="$t('LBL_CITY')"
            />
            <span v-if="errors.first('addr_city')" class="error text-danger">
              {{ errors.first("addr_city") }}
            </span>
          </div>
        </div>
        <div class="col-md-7">
          <div class="form-group">
            <vue-tel-input
              v-if="defaultCountryCode != ''"
              v-model="phone"
              :defaultCountry="defaultCountryCode"
              :enabledCountryCode="true"
              @country-changed="changeCountry"
              @input="onPhoneNumberChange"
              inputClasses="form-control"
              :placeholder="$t('PLH_ENTER_PHONE_NUMBER')"
              :validCharactersOnly="true"
            ></vue-tel-input>
            <span v-if="errors.first('addr_phone')" class="error text-danger">
              {{ errors.first("addr_phone") }}
            </span>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <select
              class="form-control YK-country_id"
              @change="getStates()"
              name="addr_country_id"
              v-model="adminsData.addr_country_id"
              v-validate="'required'"
              data-vv-validate-on="none"
              :data-vv-as="$t('LBL_COUNTRY')"
            >
              <option disabled selected value="">{{ $t("LBL_SELECT_COUNTRY") }}</option>
              <option
                :value="country.country_id"
                v-for="(country, cId) in countries"
                :key="cId"
              >
                {{ country.country_name }}
              </option>
            </select>
            <span
              v-if="errors.first('addr_country_id')"
              class="error text-danger"
              >{{ errors.first("addr_country_id") }}</span
            >
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <select
              class="form-control YK-state_id"
              placeholder="State"
              v-model="adminsData.addr_state_id"
              name="addr_state_id"
              v-validate="'required'"
              data-vv-validate-on="none"
              :data-vv-as="$t('LBL_STATE')"
            >
              <option disabled selected value="">{{ $t("LBL_SELECT_STATE") }}</option>
              <option
                :value="stateId"
                v-for="(state, stateId) in states"
                :key="stateId"
              >
                {{ state }}
              </option>
            </select>
            <span
              v-if="errors.first('addr_state_id')"
              class="error text-danger"
              >{{ errors.first("addr_state_id") }}</span
            >
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <input
              type="text"
              class="form-control"
              v-model="adminsData.addr_postal_code"
              name="addr_postal_code"
              :placeholder="$t('LBL_POSTAL_CODE')"
              v-validate="'required'"
              data-vv-validate-on="none"
              :data-vv-as="$t('LBL_POSTAL_CODE')"
            />
            <span
              v-if="errors.first('addr_postal_code')"
              class="error text-danger"
              >{{ errors.first("addr_postal_code") }}</span
            >
          </div>
        </div>
      </div>

      <template v-slot:modal-footer>
        <div class="d-flex justify-content-between w-100">
          <button
            type="button"
            class="btn btn-secondary"
            @click="$bvModal.hide(userModelId)"
          >
            Discard
          </button>
          <button
            type="button"
            class="btn btn-brand"
            @click="validateUserRecord"
          >
            {{ $t("BTN_ORDER_CUSTOMER_SAVE") }}
          </button>
        </div>
      </template>
    </b-modal>
    <b-modal id="cardModel" centered title="BootstrapVue" hide-footer>
      <template v-slot:modal-header="{ close }">
        <button type="button" class="close" @click="close()"></button>
      </template>
      <div class v-if="paymentType == 2">
        <div class="row justify-content-center">
          <div class="col-md-10">
            <div class="text-center my-5">
              <h6>{{ $t("LBL_ENTER_CARD_DETAILS_BELOW") }}</h6>
              <div class="form-group">
                <input
                  class="form-control"
                  type="text"
                  v-model="card.name"
                  :placeholder="$t('LBL_NAME_ON_CARD')"
                />
                <span v-if="errors.first('name')" class="error text-danger">
                  {{ errors.first("name") }}
                </span>
              </div>

              <div class="form-group">
                <input
                  class="form-control"
                  type="text"
                  v-model="card.number"
                  :placeholder="$t('LBL_CARD_NUMBER')"
                  data-vv-validate-on="none"
                  :data-vv-as="$t('LBL_CARD_NUMBER')"
                  v-cardformat:formatCardNumber
                />
                <span v-if="errors.first('number')" class="error text-danger">
                  {{ errors.first("number") }}
                </span>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <input
                      class="form-control"
                      type="text"
                      v-model="card.exp_month"
                      :placeholder="$t('LBL_MONTH')"
                      v-cardformat:restrictNumeric
                    />
                    <span
                      v-if="errors.first('exp_month')"
                      class="error text-danger"
                      >{{ errors.first("exp_month") }}</span
                    >
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <input
                      class="form-control"
                      type="text"
                      v-model="card.exp_year"
                      :placeholder="$t('LBL_YEAR')"
                      v-cardformat:restrictNumeric
                    />
                    <span
                      v-if="errors.first('exp_year')"
                      class="error text-danger"
                      >{{ errors.first("exp_year") }}</span
                    >
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <input
                      class="form-control"
                      type="text"
                      v-model="card.cvv"
                      :placeholder="$t('LBL_CVV')"
                      v-cardformat:formatCardCVC
                    />
                    <span
                      v-if="errors.first('cvv')"
                      class="error text-danger"
                      >{{ errors.first("cvv") }}</span
                    >
                  </div>
                </div>
              </div>

              <div class="text-center">
                <button
                  type="button"
                  class="btn btn-brand btn-block"
                  @click="saveOrder"
                >
                  {{ $t("BTN_SAVE_AND_PAY") }}
                </button>
              </div>

              <div class="or">
                <span>{{ $t("LBL_OR") }}</span>
              </div>

              <div class="text-center py-5 bg-gray rounded">
                <h6>{{ $t("LBL_SHARE_PAYMENT_URL_WITH_CUSTOMER") }}</h6>
                <ul class="social-sharing">
                  <li v-bind:class="[shareLinkTo == 'sms' ? 'active' : '']">
                    <a
                      href="javascript:;"
                      class="icon"
                      @click="
                        shareLinkTo = 'sms';
                        saveOrder(true);
                      "
                    >
                      <i class="fas fa-phone"></i>
                    </a>
                  </li>
                  <li
                    v-bind:class="[shareLinkTo == 'email' ? 'active' : '']"
                    v-if="user.user_email"
                  >
                    <a
                      href="javascript:;"
                      class="icon"
                      @click="
                        shareLinkTo = 'email';
                        saveOrder(true);
                      "
                    >
                      <i class="fas fa-envelope"></i>
                    </a>
                  </li>
                  <li v-bind:class="[shareLinkTo == 'copy' ? 'active' : '']">
                    <a
                      href="javascript:;"
                      class="icon"
                      @click="
                        shareLinkTo = 'copy';
                        saveOrder(true);
                      "
                    >
                      <i class="fas fa-link"></i>
                    </a>
                  </li>
                </ul>
                <p v-if="shippingAddress && shareLinkTo == 'email'">
                  {{ $t("LBL_PAYMENT_LINK_WILL_BE_SEND_ON") }}
                  {{ shippingAddress.addr_email }}
                </p>
                <p v-if="shippingAddress && shareLinkTo == 'sms'">
                  {{ $t("LBL_PAYMENT_LINK_WILL_BE_SEND_ON") }}
                  {{ shippingAddress.addr_phone }}
                </p>
                <p v-if="shareLinkTo == 'copy'">
                  {{ $t("LBL_PAYMENT_LINK_WILL_COPIED_AFTER_SAVE") }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>
<script>
import DatePick from "vue-date-pick";
import "vue-date-pick/dist/vueDatePick.css";
import fecha from "fecha";
import VueBootstrapTypeahead from "vue-bootstrap-typeahead";
import VueTagsInput from "@johmun/vue-tags-input";
const tableFields = {
  addr_id: "",
  addr_user_id: "",
  addr_email: "",
  addr_first_name: "",
  addr_last_name: "",
  addr_address1: "",
  addr_address2: "",
  addr_city: "",
  addr_country_id: "",
  addr_state_id: "",
  state_name: "",
  country_name: "",
  addr_postal_code: "",
  addr_phone: "",
};
const cardFields = {
  number: "",
  name: "",
  exp_month: "",
  exp_year: "",
  cvv: "",
};
export default {
  components: {
    "vue-bootstrap-typeahead": VueBootstrapTypeahead,
    VueTagsInput,
    DatePick,
  },
  data: function () {
    return {
      adminsData: tableFields,
      billingAddress: {},
      gift: {
        to: "",
        from: "",
        message: "",
      },
      selectedpickupAddress: {},
      shippingAddress: {},
      card: cardFields,
      userDetail: {},
      baseUrl: baseUrl,
      productSearch: "",
      declinedModelId: "declinedModel",
      shareModelId: "cardModel",
      paymentType: 1,
      userSearch: "",
      modelId: "coupon",
      userModelId: "userAddress",
      products: [],
      countries: [],
      tag: "",
      shippingEnable: 1,
      selectedPickup: 0,
      editDiscount: 0,
      tags: [],
      selectedShippingStatus: 1,
      shippingValue: [],
      pickupStatus: [],
      pickupAddress: [],
      states: [],
      selectedProducts: [],
      selectedProductIds: [],
      selectedVarients: [],
      allShippings: [],
      shipstatus: [],
      users: [],
      allAddress: [],
      orderProducts: {},
      user: null,
      subTotal: 0,
      totalDiscount: 0,
      appliedDiscount: 0,
      totalShipping: 0,
      totalGWarpPrice: 0,
      editExistingAddress: 0,
      selectedAddressId: "",
      totalTax: 0,
      total: 0,
      currencySymbol: currencySymbol,
      couponCode: "",
      pickuptime: "",
      note: "",
      selectedCoupon: "",
      shareLinkTo: "",
      giftPrice: 0,
      giftWarpEnable: 0,
      selectedGiftCount: 0,
      lastOrderId: 0,
      editAddressType: "",
      defaultAddress: {},
      selectedShipping: {},
      productTypes: [],
      defaultCountryCode: "",
      countryCode: "",
      phone: "",
      userId: "",
    };
  },
  watch: {
    productSearch: _.debounce(function (prod) {
      this.getProducts(prod);
    }, 500),
    userSearch: _.debounce(function (prod) {
      this.getUsers(prod);
    }, 500),
  },
  methods: {
    async getProducts(query) {
      if (query == "") {
        return false;
      }
      let formData = this.$serializeData({
        prod_id: this.selectedProductIds,
        query: query,
      });
      this.$http
        .post(adminBaseUrl + "/products/search", formData)
        .then((response) => {
          this.products = this.productTags(response.data.data);
        });
    },
    parseDate(dateString, format) {
      return fecha.parse(dateString, format);
    },
    formatDate(dateObj, format) {
      return fecha.format(dateObj, format);
    },
    isPastDate: function (date) {
      const currentDate = new Date().setDate(new Date().getDate() - 1);
      return date < currentDate;
    },
    clearSearch: function () {
      this.userSearch = "";
      this.emptyFormValues();
    },
    selectPickup: function (address) {
      this.selectedpickupAddress = address;
    },
    addNewAddress: function () {
      this.adminsData = {
        addr_id: "",
        addr_email: "",
        addr_first_name: "",
        addr_last_name: "",
        addr_address1: "",
        addr_address2: "",
        addr_city: "",
        addr_country_id: "",
        addr_state_id: "",
        addr_postal_code: "",
        addr_phone: "",
      };
      this.phone = "";
      this.states = [];
      this.editExistingAddress = 1;
    },
    editAddress: function (addressId) {
      this.adminsData = this.allAddress[addressId];
      this.getStates(false);
      this.editExistingAddress = 1;
    },
    updatedDiscount: function () {
      this.$validator
        .validate("appliedDiscount", this.appliedDiscount)
        .then((res) => {
          if (res) {
            if (this.total < this.appliedDiscount) {
              toastr.error(
                this.$t("NOTI_AMOUNT_MUST_BE_LESS_THEN") + " " + this.total,
                "",
                toastr.options
              );
              return;
            }
            this.totalDiscount = this.appliedDiscount;
            this.calculateTotal();
            this.editDiscount = 0;
          }
        });
    },
    productTags: function (response) {
      let value = "";
      let title = "";
      let thisObject = this;
      return response.map((a) => {
        title = a.label;
        value = a.value;
        if (a.pov_display_title != null) {
          title = a.label + " - " + a.pov_display_title.replace("_", " | ");
          value = a.pov_code;
          if (this.inArray(a.pov_code, this.selectedVarients) == true) {
            title = "";
            value = "";
          }
        }
        return {
          label: title,
          value: value,
        };
      });
    },
    countryIdByName: function (name, phoneId = "") {
      for (var i = 0; i < this.countries.length; i++) {
        if (this.countries[i].country_code == name) {
          if (phoneId) {
            return this.countries[i].country_phonecode;
          } else {
            return this.countries[i].country_id;
          }
        }
      }
    },
    inArrayKey: function (needle, haystack) {
      var length = haystack.length;
      for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return i;
      }
      return false;
    },
    inArray: function (needle, haystack) {
      var length = haystack.length;
      for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
      }
      return false;
    },
    async getUsers(query) {
      if (query.length < 3) {
        return false;
      }
      const res = await fetch(adminBaseUrl + "/users/search?query=" + query);
      const response = await res.json();
      this.users = response.data;
    },
    updateTags(newTags) {
      this.tags = newTags;
    },
    addGiftWrap(pindex, gift) {
      let wrap = 1;
      if (gift == 1) {
        wrap = 0;
      }
      this.selectedProducts[pindex].giftWrap = wrap;
      this.calculateTotal();
    },
    shippingCal(productId) {
      let shippingkey = $('select[name="shipping' + productId + '"]')
        .find(":selected")
        .attr("data-key");
      let productNames = [];
      let prodIds = productId.split(",");
      Object.keys(prodIds).forEach((key) => {
        productNames.push(this.getProductName(prodIds[key]));
      });

      this.selectedShipping[productNames.join(",")] =
        this.allShippings[productId][shippingkey];
      this.calculateTotal();
    },
    removeItem: function (index, productId, prodCode) {
      let arrayIndex = "";
      this.$delete(this.selectedProducts, index);
      if (prodCode != null) {
        arrayIndex = this.inArrayKey(prodCode, this.selectedVarients);
        this.$delete(this.selectedVarients, arrayIndex);
      } else {
        arrayIndex = this.inArrayKey(productId, this.selectedProductIds);
        this.$delete(this.selectedProductIds, arrayIndex);
      }
      this.calculateOrderSummary();
    },
    validateUserRecord: function () {
      this.$validator.validateAll(this.adminsData).then((res) => {
        if (res) {
          this.saveUserRecords();
        }
      });
    },
    saveUserRecords: function () {
      if (this.editAddressType != "") {
        if (this.editExistingAddress == 0 && this.selectedAddressId !== "") {
          this.adminsData = this.allAddress[this.selectedAddressId];

          this.adminsData.country_name =
            this.allAddress[this.selectedAddressId].country.country_name;
          this.adminsData.state_name =
            this.allAddress[this.selectedAddressId].state.state_name;
        } else {
          this.adminsData.country_name = $(".YK-country_id")
            .find(":selected")
            .html();
          this.adminsData.state_name = $(".YK-state_id")
            .find(":selected")
            .html();
        }
        this.adminsData.addr_phone_country_id = this.countryIdByName(
          this.countryCode
        );
        this.adminsData.country_code = this.countryCode;
        this.adminsData.phonecode = this.countryIdByName(
          this.countryCode,
          "id"
        );

        //this.adminsData.addr_phone = this.phone;

        if (this.editAddressType == "shipping") {
          this.shippingAddress = this.adminsData;
        } else {
          this.billingAddress = this.adminsData;
        }
        this.$bvModal.hide(this.userModelId);

        this.editAddressType = "";

        this.calculateOrderSummary();
        this.$forceUpdate();
        return;
      }
      let formData = this.$serializeData(this.adminsData);
      formData.append("country_code", this.countryCode);
      this.$http.post(adminBaseUrl + "/orders/address/save", formData).then(
        (response) => {
          //success
          if (response.data.status == false) {
            this.isLoading = false;
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.adminsData.addr_id = response.data.data.addr_id;
          this.adminsData.addr_user_id = response.data.data.addr_user_id;
          this.shippingAddress = response.data.data.shippingAddress;
          this.billingAddress = response.data.data.billingAddress;
     //     this.allAddress[response.data.data.addr_id] = this.billingAddress;
          this.allAddress.push(this.billingAddress);
          this.userDetail = response.data.data.userDetail;
          this.userSearch = response.data.data.userDetail.user_email;
          this.userId = response.data.data.userDetail.user_id;
          this.users = [];
          this.$bvModal.hide(this.userModelId);
          
          this.calculateOrderSummary();
          this.editExistingAddress = 0;
        },
        (response) => {
          //error
          this.validateErrors(response);
        }
      );
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

    validateCoupon: function () {
      this.$validator.validate("couponCode", this.couponCode).then((res) => {
        if (res) {
          this.applyCoupon();
        }
      });
    },
    addUser: function () {
      this.selectedUser(false);
      this.editAddressType = "";
      this.userSearch = "";
      this.allAddress = [];
      this.editExistingAddress = 1;
    },
    selectedUser: function (event) {
      let userid = 0;
      if (event != false) {
        this.user = event;
        userid = this.user.user_id;
        this.userSearch = this.user.user_email;
      }
      this.$http
        .get(adminBaseUrl + "/orders/user/address/" + userid)
        .then((response) => {
          let data = response.data.data;
          this.countries = data.countries;
          this.states = data.states;
          this.userDetail = data.userDetail !== null ? data.userDetail : [];
          this.userId = userid;

          if (data.defaultAddress.length != 0 && data.defaultAddress != null) {
            this.adminsData = data.defaultAddress;
            this.defaultAddress = data.defaultAddress;
            this.shippingAddress = data.shippingAddress;
            this.defaultCountryCode =
              data.shippingAddress.country_code != ""
                ? data.shippingAddress.country_code.toLowerCase()
                : "us";
            this.billingAddress = data.billingAddress;
            this.allAddress = data.allAddress;
          } else {
            this.$bvModal.show(this.userModelId);
            this.emptyFormValues();
            this.defaultCountryCode = "us";
            Object.keys(this.countries).forEach((key) => {
              if (this.countries[key].country_code == data.countryCode) {
                this.adminsData.addr_country_id =
                  this.countries[key].country_id;
              }
            });
            Object.keys(this.states).forEach((skey) => {
              if (this.states[skey] == data.defaultState) {
                this.adminsData.addr_state_id = skey;
              }
            });
            event = false;
          }
          this.phone = this.adminsData.addr_phone;
          if (event != false) {
            this.calculateOrderSummary();
          }
        });
    },
    getProductName: function (id) {
      let name = "";
      Object.keys(this.selectedProducts).forEach((skey) => {
        if (
          this.selectedProducts[skey].id == id ||
          this.selectedProducts[skey].pov_code == id
        ) {
          name = id;
        }
      });
      return name;
    },
    getProductImage: function (id) {
      let code = "";
      let prodid = "";
      Object.keys(this.selectedProducts).forEach((skey) => {
        if (
          this.selectedProducts[skey].id == id ||
          this.selectedProducts[skey].pov_code == id
        ) {
          prodid = this.selectedProducts[skey].id;
          code = this.selectedProducts[skey].pov_code;
        }
      });
      return this.$productImage(prodid, code, "", "28-37");
    },
    editUserAddress: function (type) {
      if (type == "shipping") {
        this.adminsData = this.shippingAddress;
      } else {
        this.adminsData = this.billingAddress;
      }
      this.editAddressType = type;
      this.editExistingAddress = 0;
      this.$bvModal.show(this.userModelId);
    },
    selectedProduct: function (event) {
      let formData = this.$serializeData({
        "product-id": event.value,
      });
      this.$http
        .post(adminBaseUrl + "/product/detail", formData)
        .then((response) => {
          this.productSearch = "";
          this.products = [];
          this.$refs.productTypeahead.inputValue = "";
          this.selectedProducts.push(response.data.data);
          if (response.data.data.pov_code != null) {
            this.selectedVarients.push(response.data.data.pov_code);
          } else {
            this.selectedProductIds.push(event.value);
          }
          this.calculateOrderSummary();
        });
    },
    getStates: function (emptyState = true) {
      if (emptyState == true) {
        this.adminsData.addr_state_id = "";
      }
      let formData = this.$serializeData({
        "country-id": this.adminsData.addr_country_id,
      });
      this.$http
        .post(adminBaseUrl + "/orders/get-states", formData)
        .then((response) => {
          this.states = response.data.data;
        });
    },
    calculateTotal: function (discount = true) {
      this.$validator.validateAll().then((res) => {
        if (res) {
          let price = 0,
            productPrice = 0,
            giftWrapPrice = 0,
            giftCount = 0,
            tax = 0,
            calSubTotal = 0;
          this.productTypes = [];
          Object.keys(this.selectedProducts).forEach((skey) => {
            this.productTypes.push(this.selectedProducts[skey].prod_type);

            if (this.selectedProducts[skey].giftWrap == 1) {
              giftWrapPrice +=
                this.selectedProducts[skey].selectedqty * this.giftPrice;
              giftCount += 1;
            }
            price = this.selectedProducts[skey].price;
            if (this.selectedProducts[skey].includeTax == 1) {
              price = (
                this.selectedProducts[skey].price /
                (1 + this.selectedProducts[skey].tax)
              ).toFixed(2);
            }
            calSubTotal += this.selectedProducts[skey].selectedqty * price;
            tax +=
              this.selectedProducts[skey].tax *
              price *
              this.selectedProducts[skey].selectedqty;
          });
          let shipping = 0;
          $(".YK-selectedShipping").each(function () {
            if ($(this).val() != "") {
              shipping += parseFloat($(this).val());
            }
          });
          this.selectedGiftCount = giftCount;
          this.totalShipping = shipping;
          this.totalGWarpPrice = giftWrapPrice;
          this.subTotal = calSubTotal;
          this.totalTax = tax;
          this.total =
            this.subTotal +
            this.totalTax +
            this.totalGWarpPrice +
            this.totalShipping;
        }
        if (this.totalDiscount != 0) {
          if (this.total - this.totalDiscount < 0) {
            this.totalDiscount = 0;
            this.appliedDiscount = 0;
          }
        }
      });
    },
    emptyFormValues: function () {
      this.adminsData = {
        addr_id: "",
        addr_user_id: "",
        addr_email: "",
        addr_first_name: "",
        addr_last_name: "",
        addr_address1: "",
        addr_address2: "",
        addr_city: "",
        addr_country_id: "",
        addr_state_id: "",
        addr_postal_code: "",
        addr_phone: "",
      };
      this.phone = "";
      this.errors.clear();

      this.shippingAddress = {};
      this.billingAddress = {};
    },
    updateQuantity: function (product, type) {
      if (type == "add") {
        if (product.maxQty == product.selectedqty) {
          return;
        }
        product.selectedqty = parseInt(product.selectedqty) + parseInt(1);
      } else {
        if (product.selectedqty == product.minQty) {
          return;
        }
        product.selectedqty = product.selectedqty - 1;
      }
      this.calculateTotal();
    },
    updateOrderStatus: function (id) {
      this.selectedShippingStatus = id;
    },
    sharePopup: function () {
      this.$bvModal.show(this.shareModelId);
    },
    changeVarient: function (index) {
      let productIndex = this.selectedProducts[index];
      let formData = this.$serializeData({
        "product-id": productIndex.id,
        "pov-code": productIndex.pov_code,
      });
      this.$http
        .post(adminBaseUrl + "/product/detail", formData)
        .then((response) => {
          this.selectedProducts[index] = [];
          this.selectedProducts[index] = response.data.data;
          this.calculateOrderSummary();
        });
    },
    calculateOrderSummary: function () {
      if (this.selectedProducts.length == 0) {
        return false;
      }
      this.selectedShipping = {};
      this.allShippings = [];
      let productData = [];
      let id = "";
      Object.keys(this.selectedProducts).forEach((skey) => {
        id = this.selectedProducts[skey].id;
        if (this.selectedProducts[skey].pov_code) {
          id = this.selectedProducts[skey].pov_code;
        }
        productData[skey] = {
          product_id: this.selectedProducts[skey].id,
          id: id,
          price: this.selectedProducts[skey].price,
          name: this.selectedProducts[skey].name,
          prod_type: this.selectedProducts[skey].prod_type,
          quantity: this.selectedProducts[skey].selectedqty,
          shipType: this.selectedProducts[skey].shipType,
          taxId: this.selectedProducts[skey].prod_taxcat_id,
        };
      });

      let countryId = "";
      let stateId = "";
      let shippingCountryId = "";
      let shippingStateId = "";

      if (this.billingAddress) {
        countryId = this.billingAddress.addr_country_id;
        stateId = this.billingAddress.addr_state_id;
      }
      if (this.shippingAddress) {
        shippingCountryId = this.shippingAddress.addr_country_id;
        shippingStateId = this.shippingAddress.addr_state_id;
      }
      let formData = this.$serializeData({
        products: JSON.stringify(productData),
        "country-id": countryId,
        "state-id": stateId,
        "shipping-country-id": shippingCountryId,
        "shipping-state-id": shippingStateId,
        "shipping-enable": this.shippingEnable,
      });
      this.$http
        .post(adminBaseUrl + "/orders/order-summary", formData)
        .then((response) => {
          this.allShippings = response.data.data.shippingResults;
          this.discountType = response.data.data.discountType;
          this.pickupAddress = response.data.data.pickupAddress;
          Object.keys(this.selectedProducts).forEach((skey) => {
            this.selectedProducts[skey].tax =
              response.data.data.taxResults[this.selectedProducts[skey].id];
          });
          this.calculateTotal();
        });
    },
    saveOrder: function (shareLink = false) {
      let message = "";
      if (Object.keys(this.selectedProducts).length == 0) {
        message = this.$t("NOTI_PLEASE_ADD_PRODUCT_BEFORE_SAVE_ORDER");
      } else if (
        Object.keys(this.selectedShipping).length == 0 &&
        this.shippingEnable == 1 &&
        this.allShippings.length != 0
      ) {
        message = this.$t("NOTI_SELECT_SHIPPING_BEFORE_SAVE_ORDER");
      } else if (Object.keys(this.billingAddress).length == 0) {
        message = this.$t("NOTI_PLEASE_ENTER_BILLING_ADDRESS");
      } else if (
        Object.keys(this.shippingAddress).length == 0 &&
        this.shippingEnable == 1
      ) {
        message = this.$t("NOTI_PLEASE_ENTER_SHIPPING_ADDRESS");
      } else if (
        Object.keys(this.selectedpickupAddress).length == 0 &&
        this.shippingEnable != 1
      ) {
        message = this.$t("NOTI_PLEASE_ENTER_PICKUP_ADDRESS");
      } else if (
        Object.keys(this.pickuptime).length == 0 &&
        this.shippingEnable != 1
      ) {
        message = this.$t("NOTI_PLEASE_ENTER_PICKUP_TIME");
      }else if (
        this.selectedGiftCount > 0 &&
        (this.gift.to == "" || this.gift.from == "" || this.gift.message == "")
      ) {
        message = this.$t("NOTI_PLEASE_ENTER_GIFT_WRAP_MESSAGE");
      } else if (
        Object.keys(this.selectedShipping).length !=
          Object.keys(this.allShippings).length &&
        this.shippingEnable == 1
      ) {
        message = this.$t("NOTI_PLEASE_SELECT_ALLAVAILABLE_SHIPPINGS");
      } else if ($(".YK-Errors").find("span").hasClass("error text-danger")) {
        message = this.$t("NOTI_FIX_THE_QUANTITY_ERRORS");
      } else if (shareLink == true && this.shareLinkTo == "") {
        message = this.$t("NOTI_SELECT_PAYMENT_LINK_SHARE_OPTION");
      }
      if (message != "") {
        toastr.error(message, "", toastr.options);
        return false;
      }
      let shippingData = {};
      let giftData = {};
      shippingData["shipping"] = this.selectedShipping;
      giftData["message"] = this.gift;
      let formData = this.$serializeData({
        products: JSON.stringify(this.selectedProducts),
        "user-id": this.userId,
        tags: JSON.stringify(this.tags),
        shipping: JSON.stringify(shippingData),
        "billing-address": JSON.stringify(this.billingAddress),
        "shipping-address": JSON.stringify(this.shippingAddress),
        "selected-pickup-address": JSON.stringify(this.selectedpickupAddress),
        pickup_time: this.pickuptime,
        "total-discount": this.totalDiscount,
        "total-price": this.total,
        "total-tax": this.totalTax,
        "total-shipping": this.totalShipping,
        "total-gift-price": this.totalGWarpPrice,
        "gift-message": JSON.stringify(giftData),
        "order-shipping-status": this.selectedShippingStatus,
        "order-shipping-type": this.shippingEnable,
        "order-subtotal": this.subTotal,
        order_notes: this.note,
        "card-details": JSON.stringify(this.card),
        "share-link": shareLink,
        payment_type: this.paymentType,
      });
      if (shareLink == true) {
        formData.append("type", this.shareLinkTo);
      }
      this.$http.post(adminBaseUrl + "/orders/save", formData).then(
        (response) => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          this.$bvModal.hide(this.shareModelId);
          toastr.success(response.data.message, "", toastr.options);
          if (shareLink == true && this.shareLinkTo == "copy") {
            let copyText = response.data.data.paymentUrl;

            let $temp = $("<input>");
            $("body").append($temp);
            $temp.val(copyText).select();
            document.execCommand("copy");
            $temp.remove();
          }
          this.swichOrder(response.data.data.orderId);
        },
        (response) => {
          this.validateErrors(response);
        }
      );
    },
    swichOrder: function (id) {
      this.$router.push({
        name: "orderDetail",
        params: {
          id: id,
        },
      });
    },
    pageLoadData: function (id) {
      this.$http
        .get(adminBaseUrl + "/orders/page-load-data")
        .then((response) => {
          this.pickupStatus = response.data.data.pickupStatus;
          this.shipstatus = response.data.data.shipstatus;
          this.giftPrice = response.data.data.giftPrice;
          this.giftWarpEnable = response.data.data.gWarpEnable;
          this.lastOrderId = response.data.data.lastOrderId;
        });
    },
    changeCountry: function (data) {
      this.countryCode = data.iso2;
    },
    onPhoneNumberChange: function (data, obj) {
      this.countryCode = obj.country.iso2;
     // this.unmaskedPhoneNumber = obj.number.significant;
      this.adminsData.addr_phone = obj.number.significant;
    },
    handleInput: function (e, item) {
      var letters = /^[a-zA-Z\s]*$/;
      if (!this.card.name.match(letters)) {
        //this.card.name = ''
      }
    },
  },
  mounted: function () {
    this.emptyFormValues();
    this.pageLoadData();
  },
};
</script>
