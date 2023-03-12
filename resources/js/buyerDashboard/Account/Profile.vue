<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'profile'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card">
                  <div class="card-head">
                    <h2 v-if="$mq === 'mobile'">{{ $t("LBL_PROFILE") }}</h2>
                  </div>
                  <form class="form form-floating fluid-height">
                    <div class="card-body">
                      <div class="row justify-content-center">
                        <div class="col-lg-7">
                          <div class="row align-items-center mb-5">
                            <div class="col-md-12">
                              <div class="profile-card">
                                <div
                                  class="profile-card__avatar yk-common-cropper"
                                >
                                  <div class="profile-card__avatarimg">
                                    <img
                                      class="YK-userAvatar"
                                      :src="croppedImageView"
                                      alt
                                    />
                                  </div>
                                  <a
                                    class="edit js-openCropper"
                                    href="javascript:;"
                                    @click="$refs.cropperRef.openCropper()"
                                  >
                                    <i class="fas fa-pen"></i>
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <input
                                  class="form-control form-floating__field"
                                  type="text"
                                  v-model="userData.user_fname"
                                  v-bind:class="[
                                    userData.user_fname != '' ? 'filled' : '',
                                  ]"
                                />
                                <label class="form-floating__label"
                                  >{{ $t("LBL_FIRST_NAME") }} *</label
                                >
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <input
                                  class="form-control form-floating__field"
                                  v-bind:class="[
                                    userData.user_lname != '' ? 'filled' : '',
                                  ]"
                                  type="text"
                                  v-model="userData.user_lname"
                                />
                                <label class="form-floating__label"
                                  >{{ $t("LBL_LAST_NAME") }} *</label
                                >
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group form-floating__group">
                                <input
                                  class="form-control form-floating__field"
                                  type="email"
                                  v-model="userData.user_email"
                                  v-bind:class="[
                                    userData.user_email != '' ? 'filled' : '',
                                  ]"
                                  disabled
                                />
                                <label class="form-floating__label"
                                  >{{ $t("LBL_EMAIL") }} *</label
                                >
                                <i
                                  class="field-edit"
                                  @click="changeEmailPhone('email')"
                                >
                                  <svg class="svg">
                                    <use
                                      :xlink:href="
                                        baseUrl +
                                        '/dashboard/media/retina/sprite.svg#edit'
                                      "
                                    />
                                  </svg>
                                </i>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group form-floating__group">
                                <input
                                  class="form-control form-floating__field filled"
                                  type="text"
                                  :value="
                                    '+' + code + ' ' + userData.user_phone
                                  "
                                  disabled
                                />
                                <label class="form-floating__label"
                                  >{{ $t("LBL_PHONE") }} *</label
                                >
                                <i
                                  class="field-edit"
                                  @click="changeEmailPhone('phone')"
                                >
                                  <svg class="svg">
                                    <use
                                      :xlink:href="
                                        baseUrl +
                                        '/dashboard/media/retina/sprite.svg#edit'
                                      "
                                    />
                                  </svg>
                                </i>
                              </div>
                            </div>

                            <div class="col-md-6">
                              <div class="form-group">
                                <div class="gender-toggle">
                                  <label
                                    class="radio-option"
                                    v-for="(gender, genKey) in genders"
                                    :key="genKey"
                                  >
                                    <input
                                      type="radio"
                                      name="user_gender"
                                      :value="genKey"
                                      v-model="userData.user_gender"
                                    />
      
                                    <span class="sex-icon">
                                       <svg
                                        class="svg"
                                        width="20"
                                        height="20"
                                        v-if="gender.toLowerCase() == 'male'"
                                       >
                                        <use
                                          :xlink:href="
                                            baseUrl +
                                            '/dashboard/media/retina/sprite.svg#male-icon'
                                          "
                                        />
                                      </svg>
                                      <svg
                                        class="svg"
                                        width="20"
                                        height="20"
                                        v-else
                                       >
                                        <use
                                          :xlink:href="
                                            baseUrl +
                                            '/dashboard/media/retina/sprite.svg#female-icon'
                                          "
                                        />
                                      </svg>
                                      <!-- {{ gender }} -->
                                    </span>
                                  </label>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <date-pick
                                  :value="userData.user_dob"
                                  v-model="userData.user_dob"
                                  :isDateDisabled="isFutureDate"
                                  v-bind:class="[
                                    userData.user_dob != '' ? 'filled' : '',
                                  ]"
                                  :inputAttributes="{
                                    class: 'form-control form-floating__field',
                                  }"
                                  class="d-block"
                                ></date-pick>
                                <label class="form-floating__label"
                                  >{{ $t("LBL_DATE_OF_BIRTH") }} *</label
                                >
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="or"><span>{{ $t("LBL_MORE_OPTIONS") }}</span></div>
                              
                              <div class="account-delete">
                                <button
                                  type="button"
                                  class="links"
                                  id="YK-openChangePasswordModal"
                                  @click="openPopup('changePasswordModel')"
                                >
                                  {{ $t("BTN_CHANGE_PASSWORD") }}
                                </button>
                                <button
                                  type="button"
                                  class="links"
                                  @click="openPopup('requestDataModel')"
                                >
                                  {{ $t("BTN_REQUEST_MY_DATA") }}
                                </button>
                                <button
                                  type="button"
                                  class="links"
                                  @click="confirmDelete($event, userId)"
                                >
                                  {{ $t("BTN_DELETE_CLOSE_ACCOUNT") }}
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                  <div class="card-footer">
                    <div class="row justify-content-center">
                      <div class="col-lg-10">
                        <div class="text-center">
                          <button
                            class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                            v-bind:class="[
                              sending == true ? 'gb-is-loading' : '',
                            ]"
                            name="saveProfileBtn"
                            type="button"
                            :disabled="
                              userData.user_fname == '' ||
                              userData.user_lname == '' ||
                              userData.user_dob == '' ||
                              userData.user_gender == ''
                            "
                            @click="updateProfileInfo(), (sending = true)"
                          >
                            <span>{{ $t("BTN_PROFILE_SUBMIT") }}</span>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </main>
            </div>
          </div>
          <change-password ref="changePassword"></change-password>
          <request-data ref="requestMyData"></request-data>
          <cropper
            ref="cropperRef"
            :title="$t('LBL_PROFILE_IMAGE')"
            :icon="false"
            :aspectRatio="1"
            :width="250"
            :height="250"
            v-on:childToParent="setImage"
            v-on:actualImageToParent="setActualImage"
          ></cropper>
          <img :src="originalImage" id="originalImage" style="display: none" />
          <delete-model
            :deletePopText="deleteStatus.message"
            :subText="deleteStatus.subMessage"
            :recordId="deleteStatus.id"
            @deleted="deleteProfileData(recordId)"
          ></delete-model>
          <change-email-phone
            :type="popupType"
            :smsPackageActive="smsActivePackage"
            ref="changeEmailPhoneNumber"
          ></change-email-phone>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import leftSidebar from "@/buyerDashboard/Sidebar";
import dasboardHeader from "@/buyerDashboard/Header";
import DatePick from "vue-date-pick";
// import "vue-date-pick/dist/vueDatePick.css";
import changePassword from "@/buyerDashboard/Account/ChangePassword";
import RequestData from "@/buyerDashboard/Account/RequestData";
import changeEmailPhone from "@/buyerDashboard/Account/changeEmailPhone";

const tableFields = {
  user_fname: window.auth.user_fname,
  user_lname: window.auth.user_lname,
  user_dob: window.auth.user_dob,
  user_gender: window.auth.user_gender,
  user_email: window.auth.user_email,
  user_phone: window.auth.user_phone === null ? "" : window.auth.user_phone,
  user_country_id: window.auth.user_country_id,
  user_image: "",
};
export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader,
    DatePick: DatePick,
    changePassword: changePassword,
    RequestData: RequestData,
    changeEmailPhone: changeEmailPhone,
  },
  props: ["genders", "phoneCode", "checkUserProfileImage", "smsActivePackage"],
  data: function () {
    return {
      sending: false,
      baseUrl: baseUrl,
      auth: window.auth,
      userData: tableFields,
      isFutureDate(date) {
        const currentDate = new Date();
        return date > currentDate;
      },
      croppedImage: "",
      croppedImageView: "",
      originalImage: "",
      uploadedFile: "",
      deleteStatus: {},
      recordId: "",
      userId: "",
      popupType: "",
      user_phone_code: "",
    };
  },
  methods: {
    updateProfileInfo: function () {
      let formData = this.$serializeData(this.userData);
      this.$axios.post(baseUrl + "/user/profile", formData).then((response) => {
        this.sending = false;

        toastr.success(response.data.message, "", toastr.options);
      });
    },
    openPopup: function (type) {
      if(type == 'changePasswordModel'){
        this.$refs.changePassword.emptyData();
      }else if(type == 'requestDataModel'){
        this.$refs.requestMyData.emptyData();
      }
      this.$bvModal.show(type);
    },
    confirmDelete: function (event, dataid) {
      this.recordId = dataid;
      this.deleteStatus = {
        message: this.$t("LBL_GDPR_DELETION_TEXT"),
        subMessage: "",
        id: dataid,
      };
      this.$bvModal.show("deleteModel");
    },
    setImage: function (cropUrl) {
      this.croppedImage = cropUrl;
      this.croppedImageView = URL.createObjectURL(cropUrl);
    },
    setActualImage: function (actualImage) {
      if (typeof actualImage != "string") {
        this.originalImage = URL.createObjectURL(actualImage);
        this.uploadedFile = actualImage;
      } else {
        this.uploadedFile = this.originalImage = actualImage;
      }
      this.uploadProfileImage();
    },
    changeEmailPhone: function (type) {
      this.popupType = type;
      this.$refs.changeEmailPhoneNumber.emptyField();
      this.$bvModal.show("changeEmailPhoneModel");
    },
    deleteProfileData: function (recordId) {
      this.clicked = 1;
      this.$axios
        .delete(baseUrl + "/user/remove-gdpr-data/" + recordId)
        .then((response) => {
          this.clicked = 0;
          if (response.status == 200) {
            toastr.success(response.data.message, "", toastr.options);
            this.$bvModal.hide("deleteModel");
            window.location.reload();
          }
        })
        .catch((err) => {
          this.clicked = 0;
          toastr.error(err.data, "", toastr.options);
        });
    },
    uploadProfileImage: function () {
      let formData = new FormData();
      formData.append("actualImage", this.uploadedFile);
      formData.append("cropImage", this.croppedImage);
      this.$axios.post(baseUrl + "/user/profile/updateImage", formData).then(
        (response) => {
          toastr.success(response.data.message, "", toastr.options);
          this.$root.$emit(
            "updateProfileImage",
            this.baseUrl +
              "/" +
              this.$getFileUrl("userProfileImage", this.userId, 0, "100-100", Date.now())
          );
        },
        (response) => {}
      );
    },
  },
  computed: {
    code: function () {
      return this.user_phone_code != "" ? this.user_phone_code : this.phoneCode;
    },
  },
  mounted: function () {
    this.userId = window.auth.user_id;
    if (this.checkUserProfileImage == 1) {
      this.croppedImage = this.croppedImageView =
        this.baseUrl +
        "/" +
        this.$getFileUrl("userProfileImage", this.userId, 0, "100-100");
    } else {
      this.croppedImage = this.croppedImageView =
        this.baseUrl + "/dashboard/media/retina/admin-profile-user-icon.svg";
    }
    this.$root.$emit("updateProfileImage", this.croppedImage);

    this.$root.$on("updateUserEmail", (data) => {
      this.userData.user_email = data;
    });
    this.$root.$on("updateUserPhone", (phoneCode, phone) => {
      this.userData.user_phone = phone;
      this.user_phone_code = phoneCode;
    });
  },
};
</script>
