<template>
  <div>
    <div
      class="backlayer"
      v-bind:style="{ backgroundImage: 'url(' + baseUrl + '/installer/images/tribe-bg.png)' }"
    >
      <div class="wizard">
        <div class="logo">
          <a href="#" v-if="currentStep != 1">
            <img :src="baseUrl+'/installer/images/logo.png'" />
          </a>
        </div>
        <div class="wizard__sidebar">
          <ul>
            <!-- <li class="completed"> -->
            <li
              v-bind:class="[ currentStep==1 ? 'inprogress' : (currentStep > 1) ? 'completed' : 'inactive']"
              @click="goToStep(1)"
            >
              <h5>{{ $t('Welcome to Tribe ') }}</h5>
              <p>{{ $t('Accelerate Your Shift To eCommerce') }}</p>
            </li>
            <li
              v-bind:class="[ currentStep==2 ? 'inprogress' : (currentStep > 2) ? 'completed' : 'inactive']"
              @click="goToStep(2)"
            >
              <h5>{{ $t('License & Use') }}</h5>
              <p>{{ $t('Agree to the license & terms of use') }}</p>
            </li>
            <li
              v-bind:class="[ currentStep==3 ? 'inprogress' : (currentStep > 3) ? 'completed' : 'inactive']"
              @click="goToStep(3)"
            >
              <h5>{{ $t('Language') }}</h5>
              <p>{{ $t('Select the installer language') }}</p>
            </li>
            <li
              v-bind:class="[ currentStep==4 ? 'inprogress' : (currentStep > 4) ? 'completed' : 'inactive']"
              @click="goToStep(4)"
            >
              <h5>{{ $t('Server') }}</h5>
              <p>{{ $t('Validate the server requirements') }}</p>
            </li>
            <li
              v-bind:class="[ currentStep==5 ? 'inprogress' : (currentStep > 5) ? 'completed' : 'inactive']"
              @click="goToStep(5)"
            >
              <h5>{{ $t('App & Database') }}</h5>
              <p>{{ $t('Configure App permissions & Database') }}</p>
            </li>
            <li
              v-bind:class="[ currentStep==6 ? 'inprogress' : (currentStep > 6) ? 'completed' : 'inactive']"
              @click="goToStep(6)"
            >
              <h5>{{ $t('Business Details') }}</h5>
              <p>{{ $t('Add Business Information & Admin details') }}</p>
            </li>
            <li
              v-bind:class="[ currentStep==7 ? 'inprogress' : (currentStep > 7) ? 'completed' : 'inactive']"
              @click="goToStep(7)"
            >
              <h5>{{ $t('Brand Information') }}</h5>
              <p>{{ $t('Personalize your app') }}</p>
            </li>
            <li
              v-bind:class="[ currentStep==8 ? 'inprogress' : (currentStep > 8) ? 'completed' : 'inactive']"
              @click="goToStep(8)"
            >
              <h5>{{ $t('Review') }}</h5>
              <p>{{ $t('Review & Confirm details') }}</p>
            </li>
            <li
              v-bind:class="[ currentStep==9 ? 'inprogress' : (currentStep > 9) ? 'completed' : 'inactive']"
            >
              <h5>{{ $t('Installation') }}</h5>
              <p>{{ $t("We're all set now!") }}</p>
            </li>
          </ul>
        </div>
        <div class="wizard__main">
          <div class="wizard__steps">
            <welcome :installerData="installerData" @next="next" v-if="currentStep==1"></welcome>
            <viewLicenses
              :installerData="installerData"
              @next="next"
              @previous="previous"
              v-if="currentStep==2"
            ></viewLicenses>
            <installerLanguage
              :installerData="installerData"
              @next="next"
              @previous="previous"
              v-if="currentStep==3"
            ></installerLanguage>
            <serverRequirement
              :installerData="installerData"
              @next="next"
              @previous="previous"
              v-if="currentStep==4"
            ></serverRequirement>
            <appAndDatabase
              :installerData="installerData"
              @next="next"
              @previous="previous"
              v-if="currentStep==5"
            ></appAndDatabase>
            <businessAndAdminInfo
              :installerData="installerData"
              @next="next"
              @previous="previous"
              v-if="currentStep==6"
            ></businessAndAdminInfo>
            <brandingInformation
              :installerData="installerData"
              @next="next"
              @previous="previous"
              v-if="currentStep==7"
            ></brandingInformation>
            <viewDetails
              :installerData="installerData"
              @next="next"
              @previous="previous"
              v-if="currentStep==8"
            ></viewDetails>
            <migrationAndSeeding
              :installerData="installerData"
              @next="next"
              @previous="previous"
              v-if="currentStep==9"
            ></migrationAndSeeding>
             <div class="powered-by text-center pt-4">
               <img style="display:none" src="https://fatlib.4livedemo.com/img/nav.png" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
const tableFields = {
  language: "",
  app_name: "",
  app_url: "",
  install_dummy: 0,
  db_host: "",
  db_port: "",
  db_name: "",
  db_user: "",
  db_password: "",
  config: {
    BUSINESS_NAME: "",
    BUSINESS_EMAIL: "",
    BUSINESS_ADDRESS1: "",
    BUSINESS_ADDRESS2: "",
    BUSINESS_COUNTRY: "",
    BUSINESS_STATE: "",
    BUSINESS_CITY: "",
    BUSINESS_PINCODE: "",
    BUSINESS_PHONE_COUNTRY_CODE: "",
    BUSINESS_PHONE_NUMBER: "",
    PRIMARY_COLOR: "",
    PRIMARY_COLOR_INVERSE: "",
    ADMIN_PRIMARY_COLOR: "",
    ADMIN_PRIMARY_COLOR_INVERSE: "",
    ADMIN_LOGO_RATIO: "1.77777",
    MEDIA_TYPES: "1"
  },
  country_name: "",
  state_name: "",
  actualImage: "",
  cropImage: "",
  admin_name: "",
  admin_email: "",
  admin_password: "",
  admin_confirm_password: "",
  admin_currency: "USD",
  admin_language: "en"
};
/*
const tableFields = {
  app_name: "mayank",
  app_url: "maynk.com",
  install_dummy: 0,
  db_host: "localhost",
  db_port: "3306",
  db_name: "restore_db2",
  db_user: "root",
  db_password: "",
  admin_name: "sfgdgd",
  admin_email: "admin@mailinator.com",
  admin_password: "Admin@123",
  admin_confirm_password: "",
  admin_currency: "INR",
  admin_language: "hi",
  business_email: "fdg@mailiantor.com",
  confirm_password: "Admin@123",
  country_name: "",
  state_name: "",
  actualImage: "",
  cropImage: "",
  config: {
    BUSINESS_NAME: "business",
    BUSINESS_EMAIL: "admin@mailinator.com",
    BUSINESS_ADDRESS1: "busin",
    BUSINESS_ADDRESS2: "bsu",
    BUSINESS_COUNTRY: "4",
    BUSINESS_STATE: "166",
    BUSINESS_CITY: "test",
    BUSINESS_PINCODE: "32332",
    BUSINESS_PHONE_COUNTRY_CODE: "IN",
    BUSINESS_PHONE_NUMBER: "72066232",
    PRIMARY_COLOR: "#CE5151",
    PRIMARY_COLOR_INVERSE: "#A22121",
    ADMIN_PRIMARY_COLOR: "#B21212",
    ADMIN_PRIMARY_COLOR_INVERSE: "#BA2626"
  }
};
*/
import welcome from "./welcome";
import viewLicenses from "./viewLicenses";
import installerLanguage from "./installerLanguage";
import serverRequirement from "./serverRequirement";
import appAndDatabase from "./appAndDatabase";
import businessAndAdminInfo from "./businessAndAdminInfo";
import brandingInformation from "./brandingInformation";
import viewDetails from "./viewDetails";
import migrationAndSeeding from "./migrationAndSeeding";

export default {
  components: {
    welcome,
    viewLicenses,
    installerLanguage,
    serverRequirement,
    appAndDatabase,
    businessAndAdminInfo,
    brandingInformation,
    viewDetails,
    migrationAndSeeding
  },
  data: function() {
    return {
      baseUrl: baseUrl,
      currentStep: 1,
      installerData: tableFields
    };
  },
  methods: {
    next: function(step, data) {
      this.currentStep = step;
      this.installerData = data;
      if (this.currentStep != 9) {
        this.currentStep++;
      }
    },
    previous: function(step, data) {
      this.currentStep = step;
      this.installerData = data;
      if (this.currentStep != 1) {
        this.currentStep--;
      }
    },
    goToStep: function(step) {
      if (step < this.currentStep) {
        this.currentStep = step;
      }
    }
  },
  mounted: function() {
    if (localStorage.getItem("lang")) {
      this.currentStep = 4;
    }
  }
};
</script>
