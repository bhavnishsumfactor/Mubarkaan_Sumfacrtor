<template>
  <b-modal id="codApproved" centered title="BootstrapVue" no-close-on-backdrop>
    <template v-slot:modal-header>
      <h5 class="modal-title">
        <span
          v-if="statusData && statusData.complete == 1"
        >{{$t('LBL_CHANGING_STATUS_FOR')}} {{url != "" ? $t('LBL_REQUEST') : $t('LBL_ORDER') }} #{{statusData.id}} {{$t('LBL_TO_COMPLETE')}}</span>
        <span
          v-else-if="(statusData && statusData.type == 'payment')"
        >{{$t('LBL_ADDING_PAYMENT_FOR')}} {{url != "" ? $t('LBL_REQUEST') : $t('LBL_ORDER') }} #{{statusData.id}}</span>
        <span
          v-else-if="(statusData && statusData.type == 'returnApproved')"
        >{{$t('LBL_RETURNING_AMOUNT_FOR')}} {{url != "" ? $t('LBL_REQUEST') : $t('LBL_ORDER') }} #{{statusData.id}}</span>
        <span
          v-else
        >{{$t('LBL_CHANGING_STATUS_FOR')}} {{url != "" ? $t('LBL_REQUEST') : $t('LBL_ORDER') }} #{{statusData.id}} {{$t('LBL_TO')}} {{statusData.status | removeHyphen}}</span>
      </h5>

      <button type="button" class="close" @click="closePopup('codApproved')"></button>
    </template>
    <div class="row">
      <div class="col-lg-4">
        <div
          class="form-group"
          v-bind:class="[statusData.status == 'approved' ? 'disabledbutton' : '']"
        >
          <label>{{ $t('LBL_PAYMENT_METHOD') }}</label>
          <select
            class="form-control"
            v-model="adminsData.payment_method"
            name="prod_type"
            v-validate="'required'"
            :data-vv-as="$t('LBL_PAYMENT_METHOD')"
            data-vv-validate-on="none"
            @change="methodSelection()"
            v-if="statusData.status == 'delivered'"
          >
            <option value="cash">{{$t('LBL_CASH')}}</option>
            <option value="card">{{$t('LBL_CARD')}}</option>
          </select>
          <input
            type="text"
            class="form-control"
            v-model="adminsData.payment_method"
            name="payment_method"
            v-validate="'required'"
            readonly
            data-vv-validate-on="none"
            :data-vv-as="$t('LBL_PAYMENT_METHOD')"
            v-else
          />

          <span
            v-if="errors.first('payment_method')"
            class="error text-danger"
          >{{errors.first('payment_method')}}</span>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="form-group">
          <label>{{ $t('LBL_TRANSACTION') + ' #' }}</label>
          <input
            type="text"
            class="form-control"
            v-model="adminsData.transaction_id"
            name="transaction_id"
            v-validate="'required'"
            data-vv-validate-on="none"
            :data-vv-as="$t('LBL_TRANSACTION') + ' #'"
          />
          <span
            v-if="errors.first('transaction_id')"
            class="error text-danger"
          >{{errors.first('transaction_id')}}</span>
        </div>
      </div>

      <div class="col-lg-4" v-bind:class="[readonly == 'true' ? 'disabledbutton' : '']">
        <div class="form-group">
          <label>{{ $t('LBL_AMOUNT') }}</label>
          <input
            type="number"
            class="form-control"
            v-model="adminsData.amount"
            name="amount"
            :min="0"
            v-validate="'required'"
            data-vv-validate-on="none"
            v-bind:readonly="readonly"
          />
          <span v-if="errors.first('amount')" class="error text-danger">{{errors.first('amount')}}</span>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="form-group">
          <label>{{ $t('LBL_COMMENTS') }}</label>
          <textarea
            class="form-control"
            v-model="adminsData.payment_comment"
            name="payment_comment"
            v-validate="'required'"
            data-vv-validate-on="none"
            :data-vv-as="$t('LBL_COMMENTS')"
          ></textarea>
          <span
            v-if="errors.first('payment_comment')"
            class="error text-danger"
          >{{errors.first('payment_comment')}}</span>
        </div>
      </div>
    </div>

    <template v-slot:modal-footer>
      <button
        type="button"
        class="btn btn-brand"
        @click="validatePaymentRecord"
      >{{ $t('BTN_COD_APPROVED_SAVE')}}</button>
    </template>
  </b-modal>
</template>
<script>
export default {
  data: function() {
    return {
      clicked: 0
    };
  },
  props: ["statusData", "url", "adminsData", "readonly"],
  methods: {
    validatePaymentRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          this.$emit(
            "updateStatus",
            this.statusData,
            this.commentData,
            this.adminsData
          );
        }
      });
    },
    closePopup: function() {
      this.$emit("closePopup", "codApproved");
    },
    methodSelection: function() {
      this.adminsData.transaction_id = this.$rndStr();
      if (this.adminsData.payment_method == "card") {
        this.adminsData.transaction_id = "";
      }
      this.$forceUpdate();
    }
  }
};
</script>
