<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t("LBL_MOBILE_APPS") }}</h3>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="portlet">
        <div class="portlet__body portlet__body--fit">
          <div class="app-page">
            <sidebar :tab="type"></sidebar>
            <div class="app-main">
              <div class="row justify-content-center">
                <div class="col-md-7">
                  <div class="app__collections d-flex">
                    <div class="app__collections-wrapper">
                      <div class="row">
                        <div class="col-lg-6">
                          <h3 class="section__title section__title-sm">{{ $t('LBL_APP_LOGO') }}</h3>
                          <div class="form-group d-flex align-items-center mb-2">
                            <label class="label pr-3">{{ $t('LBL_ASPECT_RATIO') | camelCase}}</label>
                            <div class="radio-inline">
                              <label class="radio">
                                <input type="radio" v-model="ratio" name="aspect_ratio" value="1.0" /> 1:1
                                <span></span>
                              </label>
                              <label class="radio">
                                <input
                                  type="radio"
                                  v-model="ratio"
                                  checked="checked"
                                  name="aspect_ratio"
                                  value="1.77777"
                                /> 16:9
                                <span></span>
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-md-12">
                                <div
                                  class="dropzone dropzone-default dz-clickable dz-dropzone--center ratio-16by9"
                                  data-ratio="16:9"
                                  @click="(croppedImageView == '') ? [$refs.cropperRef.openCropper(), fileUploadClass = true]  : ''"
                                  @mouseover="fileUploadClass = true"
                                  @mouseleave="fileUploadClass = false"
                                >
                                  <div class="upload_cover">
                                    <div
                                      class="img--container uploded__img"
                                      v-if="croppedImageView != ''"
                                    >
                                      <img :src="croppedImageView" />
                                      <div class="upload__action">
                                        <button
                                          type="button"
                                          v-if="croppedImageView != ''"
                                          @click.capture="$bvModal.show('deleteModel')"
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
                                      <h3
                                        class="dropzone-msg-title"
                                      >{{ $t('LBL_CLICK_HERE_TO_UPLOAD')}}</h3>
                                    </div>
                                  </div>
                                </div>
                                <cropper
                                  ref="cropperRef"
                                  :originalImage="originalImage"
                                  :originalElementId="'originalImageApp'"
                                  :modalId="'appLogoModal'"
                                  :cropperImageId="'appImage'"
                                  :title="$t('LBL_ADMIN_LOGO')"
                                  :aspectRatio="ratio"
                                  :width="ratio=='1.77777' ? 160 : 90"
                                  :height="90"
                                  v-on:childToParent="setImage"
                                  v-on:actualImageToParent="setActualImage"
                                  :icon="false"
                                ></cropper>
                                <img
                                  :src="originalImage"
                                  id="originalImageApp"
                                  style="display: none;"
                                />
                              </div>
                            </div>
                            <p v-if="ratio=='1.0'" class="img-disclaimer py-2">
                              <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
                              {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 90x90 ' + $t('LBL_IN') + ' 1:1 ' + $t('LBL_ASPECT_RATIO') }}
                            </p>
                            <p v-if="ratio=='1.77777'" class="img-disclaimer py-2">
                              <strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}:</strong>
                              {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 160x90 ' + $t('LBL_IN') + ' 16:9 ' + $t('LBL_ASPECT_RATIO') }}
                            </p>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row ">
                        <label class="col-lg-10 col-form-label">{{ $t('LBL_FONT_FAMILY') }}:</label>
                        <div class="col-lg-10">
                          <ul class="list-tags w-100 justify-content-around">
                            <li>
                              <label class="option">
                                <input
                                  type="radio"
                                  :value="'Mulish'"
                                  v-model="adminsData.APP_FONT_FAMILY"
                                  name="APP_FONT_FAMILY"
                                />
                                <span
                                  class="option__title"
                                  style="font-family: 'Mulish';"
                                >{{ $t('LBL_MULISH') }}</span>
                              </label>
                            </li>
                            <li>
                              <label class="option">
                                <input
                                  type="radio"
                                  :value="'Roboto'"
                                  v-model="adminsData.APP_FONT_FAMILY"
                                  name="APP_FONT_FAMILY"
                                />
                                <span
                                  class="option__title"
                                  style="font-family: 'Roboto';"
                                >{{ $t('LBL_ROBOTO') }}</span>
                              </label>
                            </li>
                            <li>
                              <label class="option">
                                <input
                                  type="radio"
                                  :value="'Poppins'"
                                  v-model="adminsData.APP_FONT_FAMILY"
                                  name="APP_FONT_FAMILY"
                                />
                                <span
                                  class="option__title"
                                  style=" font-family: 'Poppins';"
                                >{{ $t('LBL_POPPINS') }}</span>
                              </label>
                            </li>
                            <li>
                              <label class="option">
                                <input
                                  type="radio"
                                  :value="'Open Sans'"
                                  v-model="adminsData.APP_FONT_FAMILY"
                                  name="APP_FONT_FAMILY"
                                />
                                <span
                                  class="option__title"
                                  style=" font-family: 'Open Sans';"
                                >{{ $t('LBL_OPEN_SANS') }}</span>
                              </label>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="form-group row  align-items-center">
                        <div class="col-md-5">
                          <div class="row">
                            <label
                              class="col-md-12 col-form-label"
                            >{{ $t('LBL_FRONTEND_THEME_COLOR') }}:</label>
                            <div class="col-md-12">
                              <colorpicker
                                :colorValue="colorValuePrimary"
                                :colors="colorsPrimary"
                                @updateColors="updateColorsPrimary"
                                @updateFromPicker="updateFromPickerPrimary"
                                name="bgcolorprimary"
                              />
                              <span class="error text-danger">{{ errors.first('PRIMARY_COLOR') }}</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-5">
                          <div class="row">
                            <label
                              class="col-md-12 col-form-label"
                            >{{ $t('LBL_FRONTEND_THEME_COLOR_INVERSE') }}:</label>
                            <div class="col-md-12">
                              <colorpicker
                                :colorValue="colorValuePrimaryInverse"
                                :colors="colorsPrimaryInverse"
                                @updateColors="updateColorsPrimaryInverse"
                                @updateFromPicker="updateFromPickerPrimaryInverse"
                                name="bgcolorprimaryinverse"
                              />
                              <span
                                class="error text-danger"
                              >{{ errors.first('PRIMARY_COLOR_INVERSE') }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-10">
                          <p class="img-disclaimer disclaimer-alert">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              xmlns:xlink="http://www.w3.org/1999/xlink"
                              version="1.1"
                              id="Capa_1"
                              x="0px"
                              y="0px"
                              viewBox="0 0 512 512"
                              style="enable-background:new 0 0 512 512;"
                              xml:space="preserve"
                            >
                              <g transform="translate(1 1)">
                                <g>
                                  <g>
                                    <path
                                      d="M436.016,73.984c-99.979-99.979-262.075-99.979-362.033,0.002c-99.978,99.978-99.978,262.073,0.004,362.031     c99.954,99.978,262.05,99.978,362.029-0.002C535.995,336.059,535.995,173.964,436.016,73.984z M405.848,405.844     c-83.318,83.318-218.396,83.318-301.691,0.004c-83.318-83.299-83.318-218.377-0.002-301.693     c83.297-83.317,218.375-83.317,301.691,0S489.162,322.549,405.848,405.844z"
                                    />
                                    <path
                                      d="M254.996,84.338c-11.782,0-21.333,9.551-21.333,21.333v213.333c0,11.782,9.551,21.333,21.333,21.333     c11.782,0,21.333-9.551,21.333-21.333V105.671C276.329,93.889,266.778,84.338,254.996,84.338z"
                                    />
                                    <path
                                      d="M254.996,383.004c-11.776,0-21.333,9.557-21.333,21.333s9.557,21.333,21.333,21.333c11.776,0,21.333-9.557,21.333-21.333     S266.772,383.004,254.996,383.004z"
                                    />
                                  </g>
                                </g>
                              </g>
                            </svg>
                            {{$t('LBL_DISCLAIMER')}}: {{ $t('LBL_INVERSE_SHOULD_BE_IN_CONTRAST') }}
                          </p>
                        </div>
                      </div>
                      <div class="form-group row justify-content-center text-center">
                        <div class="col-md-10">
                          <button
                            class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                            @click="updateSettings"
                            :disabled="clicked==1"
                            v-bind:class="clicked==1 && 'gb-is-loading'"
                          >Update</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="device__preview-wrapper theme__settings-wrapper">
                    <div class="device__preview">
                      <div class="device__screen">
                        <div
                          class="scroll-y"
                          style="max-height:698px;min-height:698px;padding: 0 ;"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="320"
                            height="692"
                            viewBox="0 0 414 896"
                          >
                            <defs>
                              <pattern
                                id="pattern"
                                width="1"
                                height="1"
                                viewBox="-67.75 18.582 549.501 549.501"
                              >
                                <image
                                  preserveAspectRatio="xMidYMid slice"
                                  width="414"
                                  height="552"
                                  xlink:href="data:image/png;base64,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IDA7CiAgICAgICAgICAgIH0KCiAgICAgICAgICAgIC5mdWxsLWhlaWdodCB7CiAgICAgICAgICAgICAgICBoZWlnaHQ6IDEwMHZoOwogICAgICAgICAgICB9CgogICAgICAgICAgICAuZmxleC1jZW50ZXIgewogICAgICAgICAgICAgICAgYWxpZ24taXRlbXM6IGNlbnRlcjsKICAgICAgICAgICAgICAgIGRpc3BsYXk6IGZsZXg7CiAgICAgICAgICAgICAgICBqdXN0aWZ5LWNvbnRlbnQ6IGNlbnRlcjsKICAgICAgICAgICAgfQoKICAgICAgICAgICAgLnBvc2l0aW9uLXJlZiB7CiAgICAgICAgICAgICAgICBwb3NpdGlvbjogcmVsYXRpdmU7CiAgICAgICAgICAgIH0KCiAgICAgICAgICAgIC5jb2RlIHsKICAgICAgICAgICAgICAgIGJvcmRlci1yaWdodDogMnB4IHNvbGlkOwogICAgICAgICAgICAgICAgZm9udC1zaXplOiAyNnB4OwogICAgICAgICAgICAgICAgcGFkZGluZzogMCAxNXB4IDAgMTVweDsKICAgICAgICAgICAgICAgIHRleHQtYWxpZ246IGNlbnRlcjsKICAgICAgICAgICAgfQoKICAgICAgICAgICAgLm1lc3NhZ2UgewogICAgICAgICAgICAgICAgZm9udC1zaXplOiAxOHB4OwogICAgICAgICAgICAgICAgdGV4dC1hbGlnbjogY2VudGVyOwogICAgICAgICAgICB9CiAgICAgICAgPC9zdHlsZT4KICAgIDwvaGVhZD4KICAgIDxib2R5PgogICAgICAgIDxkaXYgY2xhc3M9ImZsZXgtY2VudGVyIHBvc2l0aW9uLXJlZiBmdWxsLWhlaWdodCI+CiAgICAgICAgICAgIDxkaXYgY2xhc3M9ImNvZGUiPgogICAgICAgICAgICAgICAgNTAwICAgICAgICAgICAgPC9kaXY+CgogICAgICAgICAgICA8ZGl2IGNsYXNzPSJtZXNzYWdlIiBzdHlsZT0icGFkZGluZzogMTBweDsiPgogICAgICAgICAgICAgICAgU2VydmVyIEVycm9yICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgPC9kaXY+CiAgICA8L2JvZHk+CjwvaHRtbD4K"
                                />
                              </pattern>
                              <clipPath id="clip-path">
                                <rect width="120" height="22" fill="none" />
                              </clipPath>
                              <filter
                                id="Rectangle_1804"
                                x="280"
                                y="385"
                                width="122"
                                height="50"
                                filterUnits="userSpaceOnUse"
                              >
                                <feOffset dy="4" input="SourceAlpha" />
                                <feGaussianBlur stdDeviation="3" result="blur" />
                                <feFlood flood-opacity="0.078" />
                                <feComposite operator="in" in2="blur" />
                                <feComposite in="SourceGraphic" />
                              </filter>
                              <clipPath id="clip-Detail">
                                <rect width="414" height="896" />
                              </clipPath>
                            </defs>
                            <g id="Detail" clip-path="url(#clip-Detail)">
                              <rect width="414" height="896" fill="#fff" />
                              <g id="image" transform="translate(0 16)">
                                <rect
                                  id="NoPath"
                                  width="414"
                                  height="414"
                                  transform="translate(0 28)"
                                  fill="#efefef"
                                />
                                <rect
                                  id="NoPath-2"
                                  data-name="NoPath"
                                  width="414"
                                  height="414"
                                  transform="translate(0 28)"
                                  fill="url(#pattern)"
                                  style="mix-blend-mode: multiply;isolation: isolate"
                                />
                              </g>
                              <g id="Group_1583" data-name="Group 1583">
                                <rect
                                  id="Rectangle_14"
                                  data-name="Rectangle 14"
                                  width="414"
                                  height="44"
                                  fill="#fff"
                                  opacity="0"
                                />
                                <g
                                  id="Status_Bar"
                                  data-name="Status Bar"
                                  transform="translate(16 11)"
                                >
                                  <g id="Battery" transform="translate(355 5)">
                                    <g
                                      id="Border"
                                      fill="none"
                                      stroke="#000"
                                      stroke-miterlimit="10"
                                      stroke-width="1"
                                      opacity="0.35"
                                    >
                                      <rect width="22" height="11.333" rx="2.667" stroke="none" />
                                      <rect
                                        x="0.5"
                                        y="0.5"
                                        width="21"
                                        height="10.333"
                                        rx="2.167"
                                        fill="none"
                                      />
                                    </g>
                                    <path
                                      id="Cap"
                                      d="M0,0V4A2.17,2.17,0,0,0,1.328,2,2.17,2.17,0,0,0,0,0"
                                      transform="translate(23 3.667)"
                                      opacity="0.4"
                                    />
                                    <rect
                                      id="Capacity"
                                      width="18"
                                      height="7.333"
                                      rx="1.333"
                                      transform="translate(2 2)"
                                    />
                                  </g>
                                  <path
                                    id="Wifi"
                                    d="M7.637,10.966a.313.313,0,0,1-.221-.093l-2-2.016a.313.313,0,0,1,.01-.456,3.421,3.421,0,0,1,4.419,0,.311.311,0,0,1,.1.226.315.315,0,0,1-.094.23l-2,2.016A.308.308,0,0,1,7.637,10.966Zm3.508-3.538a.312.312,0,0,1-.214-.085,4.9,4.9,0,0,0-6.585,0,.312.312,0,0,1-.214.085.308.308,0,0,1-.22-.091L2.758,6.17a.322.322,0,0,1,0-.459,7.158,7.158,0,0,1,9.752,0,.322.322,0,0,1,.1.229.326.326,0,0,1-.094.23L11.364,7.337A.309.309,0,0,1,11.145,7.428ZM13.8,4.743a.3.3,0,0,1-.214-.087,8.64,8.64,0,0,0-11.907,0,.3.3,0,0,1-.214.087.308.308,0,0,1-.22-.091L.094,3.486a.322.322,0,0,1,0-.456,10.9,10.9,0,0,1,15.08,0,.325.325,0,0,1,.1.228.319.319,0,0,1-.094.229L14.023,4.652A.307.307,0,0,1,13.8,4.743Z"
                                    transform="translate(333 5)"
                                  />
                                  <path
                                    id="Cellular_Connection"
                                    data-name="Cellular Connection"
                                    d="M16,10.667H15a1,1,0,0,1-1-1V1a1,1,0,0,1,1-1h1a1,1,0,0,1,1,1V9.667A1,1,0,0,1,16,10.667Zm-4.667,0h-1a1,1,0,0,1-1-1V3.334a1,1,0,0,1,1-1h1a1,1,0,0,1,1,1V9.667A1,1,0,0,1,11.334,10.667Zm-4.667,0h-1a1,1,0,0,1-1-1v-4a1,1,0,0,1,1-1h1a1,1,0,0,1,1,1v4A1,1,0,0,1,6.666,10.667ZM2,10.667H1a1,1,0,0,1-1-1v-2a1,1,0,0,1,1-1H2a1,1,0,0,1,1,1v2A1,1,0,0,1,2,10.667Z"
                                    transform="translate(310 5)"
                                  />
                                  <g
                                    id="Time_Style"
                                    data-name="Time Style"
                                    transform="translate(0 0)"
                                  >
                                    <text
                                      id="_Time"
                                      data-name="↳ Time"
                                      transform="translate(0 1)"
                                      font-size="15"
                                      font-weight="500"
                                      :font-family="adminsData.APP_FONT_FAMILY"
                                      letter-spacing="-0.02em"
                                    >
                                      <tspan x="13.943" y="15">9:41</tspan>
                                    </text>
                                  </g>
                                </g>
                              </g>
                              <rect
                                id="Rectangle_57"
                                data-name="Rectangle 57"
                                width="414"
                                height="22"
                                transform="translate(0 458)"
                                fill="#f5f5f5"
                              />
                              <rect
                                id="Rectangle_1800"
                                data-name="Rectangle 1800"
                                width="414"
                                height="1"
                                transform="translate(0 687)"
                                fill="#ebebeb"
                              />
                              <g
                                id="Group_1312"
                                data-name="Group 1312"
                                transform="translate(189 66)"
                              >
                                <rect
                                  id="Rectangle_1762"
                                  data-name="Rectangle 1762"
                                  width="5"
                                  height="5"
                                  rx="2.5"
                                  transform="translate(32 401)"
                                  fill="#666"
                                />
                                <rect
                                  id="Rectangle_1761"
                                  data-name="Rectangle 1761"
                                  width="5"
                                  height="5"
                                  rx="2.5"
                                  transform="translate(22 401)"
                                  fill="#666"
                                />
                                <rect
                                  id="Rectangle_1760"
                                  data-name="Rectangle 1760"
                                  width="5"
                                  height="5"
                                  rx="2.5"
                                  transform="translate(12 401)"
                                  fill="#666"
                                />
                                <rect
                                  id="Rectangle_1759"
                                  data-name="Rectangle 1759"
                                  width="8"
                                  height="8"
                                  rx="4"
                                  transform="translate(-1 399)"
                                  :fill="adminsData.APP_PRIMARY_COLOR"
                                />
                              </g>
                              <g
                                id="Group_1393"
                                data-name="Group 1393"
                                transform="translate(0 -10)"
                              >
                                <g
                                  id="Group_1392"
                                  data-name="Group 1392"
                                  transform="translate(0 -15)"
                                >
                                  <text
                                    id="Inclusive_All_Taxes"
                                    data-name="Inclusive All Taxes"
                                    transform="translate(21 688)"
                                    fill="#48b34e"
                                    font-size="12"
                                    :font-family="adminsData.APP_FONT_FAMILY"
                                    font-weight="500"
                                  >
                                    <tspan x="0" y="0">Inclusive All Taxes</tspan>
                                  </text>
                                  <g
                                    id="Group_1391"
                                    data-name="Group 1391"
                                    transform="translate(5)"
                                  >
                                    <text
                                      id="_-30_"
                                      data-name="( -30% )"
                                      transform="translate(167 661)"
                                      fill="#f13524"
                                      font-size="18"
                                      :font-family="adminsData.APP_FONT_FAMILY"
                                    >
                                      <tspan x="0" y="0">( -30% )</tspan>
                                    </text>
                                    <text
                                      id="USD_65"
                                      data-name="USD 65"
                                      transform="translate(91 661)"
                                      fill="#999"
                                      font-size="18"
                                      :font-family="adminsData.APP_FONT_FAMILY"
                                      text-decoration="line-through"
                                    >
                                      <tspan x="0" y="0">USD 65</tspan>
                                    </text>
                                    <text
                                      id="USD_45"
                                      data-name="USD 45"
                                      transform="translate(16 661)"
                                      fill="#333"
                                      font-size="18"
                                      :font-family="adminsData.APP_FONT_FAMILY"
                                      font-weight="600"
                                    >
                                      <tspan x="0" y="0">USD 45</tspan>
                                    </text>
                                  </g>
                                </g>
                                <g
                                  id="Group_1388"
                                  data-name="Group 1388"
                                  transform="translate(4 9)"
                                >
                                  <text
                                    id="_4.5_5"
                                    data-name="4.5/5"
                                    transform="translate(185 595)"
                                    fill="#15161a"
                                    font-size="16"
                                    :font-family="adminsData.APP_FONT_FAMILY"
                                    font-weight="500"
                                  >
                                    <tspan x="-38.432" y="0">4.5/5</tspan>
                                  </text>
                                  <text
                                    id="_9.2k_Ratings"
                                    data-name="9.2k Ratings"
                                    transform="translate(222 595)"
                                    fill="#666"
                                    font-size="16"
                                    :font-family="adminsData.APP_FONT_FAMILY"
                                  >
                                    <tspan x="0" y="0">9.2k Ratings</tspan>
                                  </text>
                                  <rect
                                    id="Rectangle_1768"
                                    data-name="Rectangle 1768"
                                    width="1"
                                    height="20"
                                    transform="translate(206 579)"
                                    fill="#d6d6d6"
                                  />
                                  <g
                                    id="Repeat_Grid_6"
                                    data-name="Repeat Grid 6"
                                    transform="translate(17 578)"
                                    clip-path="url(#clip-path)"
                                  >
                                    <g transform="translate(-32 -219)">
                                      <g id="Star" transform="translate(31 218)">
                                        <rect
                                          id="Rectangle_25"
                                          data-name="Rectangle 25"
                                          width="24"
                                          height="24"
                                          :fill="adminsData.APP_PRIMARY_COLOR"
                                          opacity="0"
                                        />
                                        <path
                                          id="Polygon_2"
                                          data-name="Polygon 2"
                                          d="M8.571,0l2.143,6.375,6.429.173-5.1,4.113,1.83,6.482-5.3-3.833-5.3,3.833L5.1,10.661,0,6.548l6.429-.173Z"
                                          transform="translate(3.429 3.429)"
                                          :fill="adminsData.APP_PRIMARY_COLOR"
                                        />
                                      </g>
                                    </g>
                                    <g transform="translate(-8 -219)">
                                      <g
                                        id="Star-2"
                                        data-name="Star"
                                        transform="translate(31 218)"
                                      >
                                        <rect
                                          id="Rectangle_25-2"
                                          data-name="Rectangle 25"
                                          width="24"
                                          height="24"
                                          :fill="adminsData.APP_PRIMARY_COLOR"
                                          opacity="0"
                                        />
                                        <path
                                          id="Polygon_2-2"
                                          data-name="Polygon 2"
                                          d="M8.571,0l2.143,6.375,6.429.173-5.1,4.113,1.83,6.482-5.3-3.833-5.3,3.833L5.1,10.661,0,6.548l6.429-.173Z"
                                          transform="translate(3.429 3.429)"
                                          :fill="adminsData.APP_PRIMARY_COLOR"
                                        />
                                      </g>
                                    </g>
                                    <g transform="translate(16 -219)">
                                      <g
                                        id="Star-3"
                                        data-name="Star"
                                        transform="translate(31 218)"
                                      >
                                        <rect
                                          id="Rectangle_25-3"
                                          data-name="Rectangle 25"
                                          width="24"
                                          height="24"
                                          :fill="adminsData.APP_PRIMARY_COLOR"
                                          opacity="0"
                                        />
                                        <path
                                          id="Polygon_2-3"
                                          data-name="Polygon 2"
                                          d="M8.571,0l2.143,6.375,6.429.173-5.1,4.113,1.83,6.482-5.3-3.833-5.3,3.833L5.1,10.661,0,6.548l6.429-.173Z"
                                          transform="translate(3.429 3.429)"
                                          :fill="adminsData.APP_PRIMARY_COLOR"
                                        />
                                      </g>
                                    </g>
                                    <g transform="translate(40 -219)">
                                      <g
                                        id="Star-4"
                                        data-name="Star"
                                        transform="translate(31 218)"
                                      >
                                        <rect
                                          id="Rectangle_25-4"
                                          data-name="Rectangle 25"
                                          width="24"
                                          height="24"
                                          :fill="adminsData.APP_PRIMARY_COLOR"
                                          opacity="0"
                                        />
                                        <path
                                          id="Polygon_2-4"
                                          data-name="Polygon 2"
                                          d="M8.571,0l2.143,6.375,6.429.173-5.1,4.113,1.83,6.482-5.3-3.833-5.3,3.833L5.1,10.661,0,6.548l6.429-.173Z"
                                          transform="translate(3.429 3.429)"
                                          :fill="adminsData.APP_PRIMARY_COLOR"
                                        />
                                      </g>
                                    </g>
                                    <g transform="translate(64 -219)">
                                      <g
                                        id="Star-5"
                                        data-name="Star"
                                        transform="translate(31 218)"
                                      >
                                        <rect
                                          id="Rectangle_25-5"
                                          data-name="Rectangle 25"
                                          width="24"
                                          height="24"
                                          :fill="adminsData.APP_PRIMARY_COLOR"
                                          opacity="0"
                                        />
                                        <path
                                          id="Polygon_2-5"
                                          data-name="Polygon 2"
                                          d="M8.571,0l2.143,6.375,6.429.173-5.1,4.113,1.83,6.482-5.3-3.833-5.3,3.833L5.1,10.661,0,6.548l6.429-.173Z"
                                          transform="translate(3.429 3.429)"
                                          :fill="adminsData.APP_PRIMARY_COLOR"
                                        />
                                      </g>
                                    </g>
                                  </g>
                                </g>
                                <text
                                  id="Women_s_Gills_Slip-On_Sneakers"
                                  data-name="Women&apos;s Gills Slip-On Sneakers"
                                  transform="translate(21 562)"
                                  fill="#333"
                                  font-size="16"
                                  :font-family="adminsData.APP_FONT_FAMILY"
                                  font-weight="500"
                                >
                                  <tspan x="0" y="0">Women&apos;s Gills Slip-On Sneakers</tspan>
                                </text>
                                <g id="Group_1394" data-name="Group 1394">
                                  <text
                                    id="Women"
                                    transform="translate(191 532)"
                                    fill="#666"
                                    font-size="12"
                                    :font-family="adminsData.APP_FONT_FAMILY"
                                  >
                                    <tspan x="0" y="0">WOMEN</tspan>
                                  </text>
                                  <rect
                                    id="Rectangle_1802"
                                    data-name="Rectangle 1802"
                                    width="1"
                                    height="15"
                                    transform="translate(180 520)"
                                    fill="#d6d6d6"
                                  />
                                  <text
                                    id="Toursers"
                                    transform="translate(105 532)"
                                    fill="#666"
                                    font-size="12"
                                    :font-family="adminsData.APP_FONT_FAMILY"
                                  >
                                    <tspan x="0" y="0">TOURSERS</tspan>
                                  </text>
                                  <rect
                                    id="Rectangle_1803"
                                    data-name="Rectangle 1803"
                                    width="1"
                                    height="15"
                                    transform="translate(94 520)"
                                    fill="#d6d6d6"
                                  />
                                  <text
                                    id="Clothing"
                                    transform="translate(21 532)"
                                    fill="#666"
                                    font-size="12"
                                    :font-family="adminsData.APP_FONT_FAMILY"
                                  >
                                    <tspan x="0" y="0">CLOTHING</tspan>
                                  </text>
                                </g>
                              </g>
                              <rect
                                id="Rectangle_1767"
                                data-name="Rectangle 1767"
                                width="414"
                                height="16"
                                transform="translate(0 752)"
                                fill="#f5f5f5"
                              />
                              <g
                                id="Group_1390"
                                data-name="Group 1390"
                                transform="translate(0 -28)"
                              >
                                <rect
                                  id="Rectangle_1765"
                                  data-name="Rectangle 1765"
                                  width="414"
                                  height="48"
                                  transform="translate(0 732)"
                                  fill="#fff"
                                />
                                <text
                                  id="COD_Available"
                                  data-name="COD Available"
                                  transform="translate(84 760.68)"
                                  fill="#333"
                                  font-size="11"
                                  :font-family="adminsData.APP_FONT_FAMILY"
                                >
                                  <tspan x="0" y="0">COD Available</tspan>
                                </text>
                                <text
                                  id="Pick_Up_Available"
                                  data-name="Pick Up Available"
                                  transform="translate(280 760.68)"
                                  fill="#333"
                                  font-size="11"
                                  :font-family="adminsData.APP_FONT_FAMILY"
                                >
                                  <tspan x="0" y="0">Pick Up Available</tspan>
                                </text>
                                <rect
                                  id="Rectangle_1766"
                                  data-name="Rectangle 1766"
                                  width="1"
                                  height="48"
                                  transform="translate(207 732)"
                                  fill="#ebebeb"
                                />
                                <g id="Cash" transform="translate(44 736)" opacity="0.3">
                                  <rect
                                    id="Rectangle_25-6"
                                    data-name="Rectangle 25"
                                    width="40"
                                    height="40"
                                    fill="#ebebeb"
                                    opacity="0"
                                  />
                                  <path
                                    id="Icon_awesome-money-bill"
                                    data-name="Icon awesome-money-bill"
                                    d="M20.37.048H.37V12.978h20V.048Zm-18,10.939v-3c.946,0,3,2.054,3,3h-3Zm0-6v-3h3C5.37,2.933,3.316,4.987,2.37,4.987Zm8,4c-1.183,0-3-.581-3-2a3.6,3.6,0,0,1,3-3c1.182,0,2,1.581,2,3A1.793,1.793,0,0,1,10.37,8.987Zm8,2H15.446c0-.946,1.978-3,2.924-3Zm0-6c-.946,0-2.924-2.054-2.924-3H18.37Z"
                                    transform="translate(10.13 13.513)"
                                    fill="none"
                                    stroke="#333"
                                    stroke-width="1"
                                  />
                                </g>
                                <g id="Deliver" transform="translate(240 736)" opacity="0.3">
                                  <rect
                                    id="Rectangle_25-7"
                                    data-name="Rectangle 25"
                                    width="40"
                                    height="40"
                                    fill="#333"
                                    opacity="0"
                                  />
                                  <g id="pickup-truck" transform="translate(8 11)">
                                    <path
                                      id="Path_172"
                                      data-name="Path 172"
                                      d="M155.8,84.6h-6a.5.5,0,0,1-.5-.5v-9a.5.5,0,0,1,.5-.5h6a.5.5,0,0,1,.5.5v9A.5.5,0,0,1,155.8,84.6Zm-5.5-1h5v-8h-5Z"
                                      transform="translate(-142.302 -74.6)"
                                      fill="#333"
                                      stroke="rgba(0,0,0,0)"
                                      stroke-width="1"
                                    />
                                    <path
                                      id="Path_173"
                                      data-name="Path 173"
                                      d="M48.1,145.6h-5a.5.5,0,0,1-.5-.5v-6a.5.5,0,0,1,.5-.5h5a.5.5,0,0,1,0,1H43.6v5h4.5a.5.5,0,1,1,0,1Z"
                                      transform="translate(-40.603 -135.6)"
                                      fill="#333"
                                      stroke="rgba(0,0,0,0)"
                                      stroke-width="1"
                                    />
                                    <path
                                      id="Path_174"
                                      data-name="Path 174"
                                      d="M107.1,141.845a.5.5,0,0,1-.5-.5h0V139.2a.5.5,0,0,1,1,0v2.138a.506.506,0,0,1-.5.506Z"
                                      transform="translate(-101.603 -135.695)"
                                      fill="#333"
                                      stroke="rgba(0,0,0,0)"
                                      stroke-width="1"
                                    />
                                    <path
                                      id="Path_175"
                                      data-name="Path 175"
                                      d="M213.8,77.637a.5.5,0,0,1-.5-.5V75.1a.5.5,0,1,1,1,0v2.034a.5.5,0,0,1-.5.5Z"
                                      transform="translate(-203.302 -74.6)"
                                      fill="#333"
                                      stroke="rgba(0,0,0,0)"
                                      stroke-width="1"
                                    />
                                    <path
                                      id="Path_176"
                                      data-name="Path 176"
                                      d="M15.5,150.6h-9a.5.5,0,0,1,0-1H15v-4H1v4H2.5a.5.5,0,0,1,0,1H.5a.5.5,0,0,1-.5-.5v-5a.5.5,0,0,1,.5-.5H15v-5.5a.5.5,0,1,1,1,0h0v11A.505.505,0,0,1,15.5,150.6Z"
                                      transform="translate(0 -135.6)"
                                      fill="#333"
                                      stroke="rgba(0,0,0,0)"
                                      stroke-width="1"
                                    />
                                    <path
                                      id="Path_177"
                                      data-name="Path 177"
                                      d="M328.4,150.6h-2.5a.5.5,0,1,1,0-1h2v-6.5a3.5,3.5,0,0,0-3.5-3.5h-3.5v10h1a.5.5,0,0,1,0,1h-1.5a.5.5,0,0,1-.5-.5v-11a.5.5,0,0,1,.5-.5h4a4.507,4.507,0,0,1,4.5,4.5v7A.5.5,0,0,1,328.4,150.6Z"
                                      transform="translate(-304.905 -135.6)"
                                      fill="#333"
                                      stroke="rgba(0,0,0,0)"
                                      stroke-width="1"
                                    />
                                    <path
                                      id="Path_178"
                                      data-name="Path 178"
                                      d="M354.5,335.7a2.5,2.5,0,1,1,2.5-2.5A2.5,2.5,0,0,1,354.5,335.7Zm0-4a1.5,1.5,0,1,0,1.5,1.5h0A1.5,1.5,0,0,0,354.5,331.7Z"
                                      transform="translate(-335.5 -318.695)"
                                      fill="#333"
                                      stroke="rgba(0,0,0,0)"
                                      stroke-width="1"
                                    />
                                    <path
                                      id="Path_179"
                                      data-name="Path 179"
                                      d="M45.2,335.7a2.5,2.5,0,1,1,2.5-2.5A2.5,2.5,0,0,1,45.2,335.7Zm0-4a1.5,1.5,0,1,0,1.5,1.5h0A1.5,1.5,0,0,0,45.2,331.7Z"
                                      transform="translate(-40.698 -318.695)"
                                      fill="#333"
                                      stroke="rgba(0,0,0,0)"
                                      stroke-width="1"
                                    />
                                  </g>
                                </g>
                              </g>
                              <g
                                id="Group_33"
                                data-name="Group 33"
                                transform="translate(5 801.96)"
                              >
                                <rect
                                  id="Rectangle_48"
                                  data-name="Rectangle 48"
                                  width="414"
                                  height="98"
                                  transform="translate(-5 -3.96)"
                                  fill="#fff"
                                />
                                <g
                                  id="Group_23"
                                  data-name="Group 23"
                                  transform="translate(211 16.04)"
                                >
                                  <rect
                                    id="Rectangle_49"
                                    data-name="Rectangle 49"
                                    width="177"
                                    height="48"
                                    rx="8"
                                    :fill="adminsData.APP_PRIMARY_COLOR"
                                  />
                                  <text
                                    id="Add_to_Cart"
                                    data-name="Add to Cart"
                                    transform="translate(89 30.08)"
                                    :fill="adminsData.APP_PRIMARY_COLOR_INVERSE"
                                    font-size="16"
                                    :font-family="adminsData.APP_FONT_FAMILY"
                                    font-weight="500"
                                    letter-spacing="-0.01em"
                                  >
                                    <tspan x="-44.952" y="0">Add to Cart</tspan>
                                  </text>
                                </g>
                                <g
                                  id="Group_1276"
                                  data-name="Group 1276"
                                  transform="translate(16 16.04)"
                                >
                                  <rect
                                    id="Rectangle_49-2"
                                    data-name="Rectangle 49"
                                    width="176"
                                    height="48"
                                    rx="8"
                                    fill="#f5f5f5"
                                  />
                                  <rect
                                    id="Rectangle_1736"
                                    data-name="Rectangle 1736"
                                    width="44"
                                    height="48"
                                    rx="8"
                                    transform="translate(132)"
                                    fill="#ebebeb"
                                    opacity="0"
                                  />
                                  <text
                                    id="Olive_L"
                                    data-name="Olive, L"
                                    transform="translate(21.609 29)"
                                    fill="#15161a"
                                    font-size="14"
                                    :font-family="adminsData.APP_FONT_FAMILY"
                                    letter-spacing="-0.01em"
                                  >
                                    <tspan x="0" y="0">Olive, L</tspan>
                                  </text>
                                  <g id="Arrow" transform="translate(134 4)">
                                    <rect
                                      id="Rectangle_25-8"
                                      data-name="Rectangle 25"
                                      width="40"
                                      height="40"
                                      fill="#333"
                                      opacity="0"
                                    />
                                    <path
                                      id="Icon_material-keyboard-arrow-up"
                                      data-name="Icon material-keyboard-arrow-up"
                                      d="M10.88,12,17,18.107,23.12,12,25,13.88l-8,8-8-8Z"
                                      transform="translate(3 3)"
                                      fill="#333"
                                    />
                                  </g>
                                </g>
                              </g>
                              <rect
                                id="Rectangle_1801"
                                data-name="Rectangle 1801"
                                width="414"
                                height="1"
                                transform="translate(0 798)"
                                fill="#ebebeb"
                              />
                              <g
                                id="Group_1253"
                                data-name="Group 1253"
                                transform="translate(0 768)"
                              >
                                <rect
                                  id="Rectangle_62"
                                  data-name="Rectangle 62"
                                  width="414"
                                  height="30"
                                  :fill="adminsData.APP_PRIMARY_COLOR"
                                  opacity="0.1"
                                />
                                <text
                                  id="_604_Shopper_Liked_this"
                                  data-name="604 Shopper Liked this"
                                  transform="translate(207 19.8)"
                                  :fill="adminsData.APP_PRIMARY_COLOR"
                                  font-size="12"
                                  :font-family="adminsData.APP_FONT_FAMILY"
                                >
                                  <tspan x="-68.238" y="0">604 Shopper Liked this</tspan>
                                </text>
                              </g>
                              <g id="Group_1395" data-name="Group 1395" transform="translate(268)">
                                <g
                                  transform="matrix(1, 0, 0, 1, -268, 0)"
                                  filter="url(#Rectangle_1804)"
                                >
                                  <g
                                    id="Rectangle_1804-2"
                                    data-name="Rectangle 1804"
                                    transform="translate(289 390)"
                                    fill="#fff"
                                    stroke="#ebebeb"
                                    stroke-width="1"
                                  >
                                    <rect width="104" height="32" rx="16" stroke="none" />
                                    <rect
                                      x="0.5"
                                      y="0.5"
                                      width="103"
                                      height="31"
                                      rx="15.5"
                                      fill="none"
                                    />
                                  </g>
                                </g>
                                <g
                                  id="Group_1375"
                                  data-name="Group 1375"
                                  transform="translate(-97.546 71.709)"
                                >
                                  <g
                                    id="Group_1376"
                                    data-name="Group 1376"
                                    transform="translate(133 327)"
                                  >
                                    <circle
                                      id="Ellipse_8"
                                      data-name="Ellipse 8"
                                      cx="3"
                                      cy="3"
                                      r="3"
                                      transform="translate(4.546 0.291)"
                                      fill="#f13524"
                                    />
                                    <circle
                                      id="Ellipse_9"
                                      data-name="Ellipse 9"
                                      cx="3"
                                      cy="3"
                                      r="3"
                                      transform="translate(-0.454 7.291)"
                                      fill="#48b34e"
                                    />
                                    <circle
                                      id="Ellipse_10"
                                      data-name="Ellipse 10"
                                      cx="3"
                                      cy="3"
                                      r="3"
                                      transform="translate(9.546 7.291)"
                                      fill="#267beb"
                                    />
                                  </g>
                                </g>
                                <text
                                  id="_3_Colors"
                                  data-name="3 Colors"
                                  transform="translate(61 409)"
                                  fill="#666"
                                  font-size="10"
                                  :font-family="adminsData.APP_FONT_FAMILY"
                                >
                                  <tspan x="0" y="0">3 COLORS</tspan>
                                </text>
                              </g>
                              <g
                                id="Group_1386"
                                data-name="Group 1386"
                                transform="translate(0 10)"
                              >
                                <rect
                                  id="Rectangle_58"
                                  data-name="Rectangle 58"
                                  width="40"
                                  height="40"
                                  rx="20"
                                  transform="translate(307 46)"
                                  fill="#fff"
                                  opacity="0.8"
                                />
                                <rect
                                  id="Rectangle_1757"
                                  data-name="Rectangle 1757"
                                  width="40"
                                  height="40"
                                  rx="20"
                                  transform="translate(257 46)"
                                  fill="#fff"
                                  opacity="0.8"
                                />
                                <rect
                                  id="Rectangle_1758"
                                  data-name="Rectangle 1758"
                                  width="40"
                                  height="40"
                                  rx="20"
                                  transform="translate(207 46)"
                                  fill="#fff"
                                  opacity="0.8"
                                />
                                <rect
                                  id="Rectangle_1756"
                                  data-name="Rectangle 1756"
                                  width="40"
                                  height="40"
                                  rx="20"
                                  transform="translate(357 46)"
                                  fill="#fff"
                                  opacity="0.8"
                                />
                                <g id="Cart" transform="translate(357 46)">
                                  <rect
                                    id="Rectangle_25-9"
                                    data-name="Rectangle 25"
                                    width="40"
                                    height="40"
                                    fill="#ebebeb"
                                    opacity="0"
                                  />
                                  <path
                                    id="Path_82"
                                    data-name="Path 82"
                                    d="M-1293.823-1556.381h-8.284l3.222,10.4h10.14l4.3-14.161h3.78"
                                    transform="translate(1311.607 1572.638)"
                                    fill="none"
                                    stroke="#333"
                                    stroke-linecap="round"
                                    stroke-width="2"
                                  />
                                </g>
                                <g id="NUm" transform="translate(212.106 44.138)">
                                  <g
                                    id="Rectangle_1755"
                                    data-name="Rectangle 1755"
                                    transform="translate(162 21)"
                                    :fill="adminsData.APP_PRIMARY_COLOR"
                                    stroke="#fff"
                                    stroke-width="1"
                                  >
                                    <rect width="13" height="13" rx="6.5" stroke="none" />
                                    <rect
                                      x="0.5"
                                      y="0.5"
                                      width="12"
                                      height="12"
                                      rx="6"
                                      fill="none"
                                    />
                                  </g>
                                  <path
                                    id="Path_83"
                                    data-name="Path 83"
                                    d="M3.808-.609V0H.266V-.518l1.9-1.967a3.2,3.2,0,0,0,.49-.612,1.074,1.074,0,0,0,.182-.528.609.609,0,0,0-.256-.518,1.161,1.161,0,0,0-.7-.189,1.824,1.824,0,0,0-.714.157,2.3,2.3,0,0,0-.672.438L.2-4.249a3.258,3.258,0,0,1,.851-.5,2.449,2.449,0,0,1,.906-.179A1.837,1.837,0,0,1,3.1-4.6a1.053,1.053,0,0,1,.43.882,2.607,2.607,0,0,1-.91,1.6L1.148-.609Z"
                                    transform="translate(166.495 29.964)"
                                    :fill="adminsData.APP_PRIMARY_COLOR_INVERSE"
                                  />
                                </g>
                                <g id="Message" transform="translate(207 46)">
                                  <rect
                                    id="Rectangle_25-10"
                                    data-name="Rectangle 25"
                                    width="40"
                                    height="40"
                                    fill="#333"
                                    opacity="0"
                                  />
                                  <path
                                    id="Icon_material-chat-bubble"
                                    data-name="Icon material-chat-bubble"
                                    d="M15.6,3H4.4A1.4,1.4,0,0,0,3,4.4V17l2.8-2.8h9.8A1.4,1.4,0,0,0,17,12.8V4.4A1.4,1.4,0,0,0,15.6,3Z"
                                    transform="translate(10 11)"
                                    fill="#333"
                                  />
                                </g>
                                <g id="Share" transform="translate(257 46)">
                                  <rect
                                    id="Rectangle_25-11"
                                    data-name="Rectangle 25"
                                    width="40"
                                    height="40"
                                    fill="#333"
                                    opacity="0"
                                  />
                                  <g
                                    id="Group_1628"
                                    data-name="Group 1628"
                                    transform="translate(1)"
                                  >
                                    <circle
                                      id="Ellipse_12"
                                      data-name="Ellipse 12"
                                      cx="3.5"
                                      cy="3.5"
                                      r="3.5"
                                      transform="translate(20 11)"
                                      fill="#333"
                                    />
                                    <circle
                                      id="Ellipse_13"
                                      data-name="Ellipse 13"
                                      cx="3.5"
                                      cy="3.5"
                                      r="3.5"
                                      transform="translate(12 17)"
                                      fill="#333"
                                    />
                                    <circle
                                      id="Ellipse_14"
                                      data-name="Ellipse 14"
                                      cx="3.5"
                                      cy="3.5"
                                      r="3.5"
                                      transform="translate(20 23)"
                                      fill="#333"
                                    />
                                    <g
                                      id="Group_1627"
                                      data-name="Group 1627"
                                      transform="translate(1 0.054)"
                                    >
                                      <path
                                        id="Path_257"
                                        data-name="Path 257"
                                        d="M-2426,561.225l-9.184,6.641,9.526,6.467"
                                        transform="translate(2449.184 -547.279)"
                                        fill="none"
                                        stroke="#333"
                                        stroke-width="2"
                                      />
                                    </g>
                                  </g>
                                </g>
                                <g id="Heart" transform="translate(307 46)">
                                  <rect
                                    id="Rectangle_25-12"
                                    data-name="Rectangle 25"
                                    width="40"
                                    height="40"
                                    fill="#333"
                                    opacity="0"
                                  />
                                  <path
                                    id="Icon_awesome-heart"
                                    data-name="Icon awesome-heart"
                                    d="M16.51,3.342a4.884,4.884,0,0,0-6.664.486l-.7.725-.7-.725a4.884,4.884,0,0,0-6.664-.486,5.128,5.128,0,0,0-.354,7.425L8.332,17.9a1.12,1.12,0,0,0,1.618,0l6.911-7.136A5.125,5.125,0,0,0,16.51,3.342Z"
                                    transform="translate(11.001 9.752)"
                                    fill="#333"
                                  />
                                </g>
                              </g>
                            </g>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <DeleteModel
      :deletePopText="deleteStatus.message"
      :subText="deleteStatus.subMessage"
      :recordId="deleteStatus.id"
      @deleted="deleteImageById()"
    ></DeleteModel>
  </div>
  <!-- <div class="wrapper">
     <ul class="container" v-dragula="colOne" bag="first-bag">
      <li v-for="(text, index) in colOne" :key="index" @click="onClick">{{text}} [click me]</li>
    </ul>
  </div>-->
</template>

<script>
import sidebar from "./sidebar";

const tableFields = {
  APP_FONT_FAMILY: "",
  APP_LOGO_RATIO: "",
  APP_PRIMARY_COLOR: "",
  APP_PRIMARY_COLOR_INVERSE: ""
};
export default {
  components: {
    sidebar: sidebar
  },
  data() {
    return {
      colorValuePrimaryInverse: "",
      colorValuePrimary: "",
      colorsPrimaryInverse: {
        hex: ""
      },
      colorsPrimary: {
        hex: ""
      },
      clicked: 0,
      baseUrl: baseUrl,
      type: "theme",
      ratio: "",
      croppedImage: "",
      croppedImageView: "",
      originalImage: "",
      uploadedFile: "",
      fileUploadClass: false,
      fileVisiblity: false,
      deleteStatus: {
        message: this.$t("LBL_DELETE_APP_LOGO_TEXT"),
        subMessage: this.$t("LBL_DELETE_APP_LOGO_SUB_TEXT"),
        id: 0
      },
      adminsData: tableFields

      // colOne: [
      //   "You can move these elements between these two containers",
      //   'Moving them anywhere else isn"t quite possible',
      //   'There"s also the possibility of moving elements around in the same container, changing their position'
      // ],
      // colTwo: [
      //   "This is the default use case. You only need to specify the containers you want to use",
      //   "More interactive use cases lie ahead",
      //   "Another message"
      // ],
      // categories: [
      //   [1, 2, 3],
      //   [4, 5, 6]
      // ],
      // copyOne: [
      //   "Lorem ipsum dolor sit amet, consectetuer adipiscing elit.",
      //   "Aenean commodo ligula eget dolor. Aenean massa."
      // ],
      // copyTwo: [
      //   "Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.",
      //   "Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem."
      // ]
    };
  },
  // created: function() {
  //   Vue.vueDragula.options("third-bag", {
  //     copy: true
  //   });
  // },
  // ready: function() {
  //   var _this = this;
  //   Vue.vueDragula.eventBus.$on("drop", function(args) {
  //     console.log("drop: " + args[0]);
  //     console.log(_this.categories);
  //   });
  //   Vue.vueDragula.eventBus.$on("dropModel", function(args) {
  //     console.log("dropModel: " + args);
  //     console.log(_this.categories);
  //   });
  // },
  methods: {
    setImage: function(cropUrl) {
      this.croppedImage = cropUrl;
      this.croppedImageView = URL.createObjectURL(cropUrl);
      this.uploadImages();
    },
    deleteImageById: function(id) {
      let formData = this.$serializeData({
        "img-id": this.deleteStatus.id
      });
      this.$http
        .post(adminBaseUrl + "/app/delete/image", formData)
        .then(response => {
          this.croppedImageView = this.croppedImage = this.originalImage = this.uploadedFile =
            "";
          this.fileVisiblity = false;
          this.fileUploadClass = true;
          this.$bvModal.hide("deleteModel");
        });
    },

    setActualImage: function(actualImage) {
      if (typeof actualImage != "string") {
        this.originalImage = URL.createObjectURL(actualImage);
        this.uploadedFile = actualImage;
      } else {
        this.uploadedFile = this.originalImage = actualImage;
      }
      this.uploadImages();
    },
    uploadImages: function() {
      if (this.uploadedFile == "") {
        return;
      }
      this.fileVisiblity = true;
      this.fileUploadClass = false;
      let formData = new FormData();
      formData.append("actualImage", this.uploadedFile);
      formData.append("cropImage", this.croppedImage);
      formData.append("id", 0);
      formData.append("subid", 0);
      formData.append("type", "appLogo");
      this.$http
        .post(adminBaseUrl + "/app/upload-images", formData)
        .then(response => {
          this.deleteStatus.id = response.data.data.afile_id;
        });
    },
    updateColorsPrimaryInverse(colors, bgcolor) {
      this.colorsPrimaryInverse = colors;
      this.adminsData.APP_PRIMARY_COLOR_INVERSE = bgcolor;
    },
    updateColorsPrimary(colors, bgcolor) {
      this.colorsPrimary = colors;
      this.adminsData.APP_PRIMARY_COLOR = bgcolor;
    },
    updateFromPickerPrimaryInverse(color, colorValue) {
      this.colorsPrimaryInverse = color;
      this.colorValuePrimaryInverse = colorValue;
    },
    updateFromPickerPrimary(color, colorValue) {
      this.colorsPrimary = color;
      this.colorValuePrimary = colorValue;
    },
    getSettings: function() {
      this.croppedImageView = this.croppedImage = this.originalImage = this.uploadedFile =
        "";

      let formData = this.$serializeData({
        keys: [
          "APP_FONT_FAMILY",
          "APP_LOGO_RATIO",
          "APP_PRIMARY_COLOR",
          "APP_PRIMARY_COLOR_INVERSE",
          "APP_LOGO"
        ]
      });
      this.$http
        .post(adminBaseUrl + "/settings/get", formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          this.adminsData = response.data.data;
          this.colorValuePrimary = this.adminsData.APP_PRIMARY_COLOR;
          this.colorValuePrimaryInverse = this.adminsData.APP_PRIMARY_COLOR_INVERSE;
          let size = "140-140";
          if (this.adminsData.APP_LOGO_RATIO === "1:1") {
            this.ratio = "1.0";
          } else {
            this.ratio = "1.77777";
            size = "159-90";
          }

          if (this.adminsData.applogo) {
            this.deleteStatus.id = this.adminsData.applogo.afile_id;
            this.fileVisiblity = true;
            this.fileUploadClass = false;
            this.croppedImage = this.croppedImageView = this.$getFileUrl(
              "appLogo",
              0,
              0,
              size,
              new Date()
            );
            this.originalImage = this.$getFileUrl(
              "appLogo",
              0,
              0,
              "original",
              new Date()
            );
          }
        });
    },
    updateSettings: function() {
      this.clicked = 1;
      let ratio = 0;
      if (this.ratio == "1.0") {
        ratio = "1:1";
      } else {
        ratio = "16:9";
      }
      this.adminsData.APP_LOGO_RATIO = ratio;
      let formData = this.$serializeData(this.adminsData);
      this.$http
        .post(adminBaseUrl + "/settings/basedonkeys", formData)
        .then(response => {
          //success
          this.clicked = 0;
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
        });
    }
    // onClick: function() {
    //   console.log(Vue.vueDragula.find("first-bag"));
    //   window.alert("click event");
    // },
    // testModify: function() {
    //   this.categories = [
    //     ["a", "b", "c"],
    //     ["d", "e", "f"]
    //   ];
    // }
  },

  mounted: function() {
    this.getSettings();
  }
};
</script>
<style>
@import url("https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap");
</style>