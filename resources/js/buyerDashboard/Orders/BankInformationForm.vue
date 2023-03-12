 <template>
  <b-modal id="bankInfo" centered title="BootstrapVue" header-class="border-0">
    <template v-slot:modal-header>
      <h4 class="modal-title">{{ $t("LBL_BANK_INFORMATION") }}</h4>
      <button type="button" class="close" @click="$bvModal.hide('bankInfo')"></button>
    </template>

    <form class="form form-floating">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group form-floating__group">
            <input
              type="text"
              class="form-control form-floating__field"
              id="bank_name"
              name="bank_name"
              v-model="userData.orbinfo_name"
              v-bind:class="[userData.orbinfo_name != '' ? 'filled' : '']"
            />
            <label class="form-floating__label">{{ $t("LBL_NAME") }}</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group form-floating__group">
            <input
              type="text"
              class="form-control form-floating__field"
              id="bank_branch"
              name="bank_branch"
              v-model="userData.orbinfo_branch"
              v-bind:class="[userData.orbinfo_branch != '' ? 'filled' : '']"
            />
            <label class="form-floating__label">{{ $t("LBL_BRANCH") }}</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group form-floating__group">
            <input
              type="text"
              class="form-control form-floating__field"
              id="orbinfo_account_name"
              name="orbinfo_account_name"
              v-model="userData.orbinfo_account_name"
              v-bind:class="[
                userData.orbinfo_account_name != '' ? 'filled' : '',
              ]"
            />
            <label class="form-floating__label">
              {{
              $t("LBL_ACCOUNT_NAME")
              }}
            </label>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group form-floating__group">
            <input
              type="text"
              class="form-control form-floating__field"
              id="orbinfo_branch_code"
              name="orbinfo_branch_code"
              v-model="userData.orbinfo_branch_code"
              v-bind:class="[
                userData.orbinfo_branch_code != '' ? 'filled' : '',
              ]"
            />
            <label class="form-floating__label">
              {{
              $t("LBL_BRANCH_CODE")
              }}
            </label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group form-floating__group">
            <input
              type="text"
              class="form-control form-floating__field"
              id="orbinfo_account_number"
              name="orbinfo_account_number"
              v-model="userData.orbinfo_account_number"
              v-bind:class="[
                userData.orbinfo_account_number != '' ? 'filled' : '',
              ]"
            />
            <label class="form-floating__label">
              {{
              $t("LBL_ACCOUNT_NUMBER")
              }}
            </label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group form-floating__group">
            <textarea
              class="form-control form-floating__field"
              style="resize: none"
              id="orbinfo_notes"
              name="orbinfo_notes"
              v-model="userData.orbinfo_notes"
              v-bind:class="[userData.orbinfo_notes != '' ? 'filled' : '']"
              cols="30"
              rows="10"
            ></textarea>
            <label class="form-floating__label">{{ $t("LBL_NOTES") }}</label>
          </div>
        </div>
      </div>
    </form>
    <template v-slot:modal-footer>
      <button
        class="btn btn-outline-brand"
        type="button"
        @click="$bvModal.hide('bankInfo')"
      >{{ $t("BTN_BANK_CANCEL") }}</button>
      <button
        class="btn btn-brand"
        name="submitReviewBtn"
        type="button"
        :disabled="
          userData.orbinfo_account_number.trim() == '' || 
          userData.orbinfo_branch_code.trim() == '' ||
          userData.orbinfo_account_name.trim() == '' ||
          userData.orbinfo_branch.trim() == '' ||
          userData.orbinfo_name.trim() == ''
        "
        @click="$emit('addBankDetails', userData)"
      >{{ $t("BTN_BANK_SUBMIT") }}</button>
    </template>
  </b-modal>
</template>
<script>
const tableFields = {
  orbinfo_notes: "",
  orbinfo_account_number: "",
  orbinfo_branch_code: "",
  orbinfo_account_name: "",
  orbinfo_branch: "",
  orbinfo_name: ""
};
export default {
  props: ["bankDetailInfo"],
  data: function() {
    return {
      clicked: 0,
      userData: tableFields
    };
  },
  watch: {
    bankDetailInfo() {
      if (this.bankDetailInfo) {
        this.userData = this.bankDetailInfo;
      }
    }
  }
};
</script>