<template>
  <b-modal
    id="reviewModel"
    centered
    title="BootstrapVue"
    :hide-footer="reviewType.type == 'view'"
  >
    <template v-slot:modal-header>
      <h5 class="modal-title">{{ $t("LBL_REVIEW_YOUR_PURCHASE") }}</h5>
      <button
        type="button"
        class="close"
        @click="$bvModal.hide('reviewModel')"
      ><span>×</span></button>
    </template>

    <form
      id="YK-submitReviewForm"
      method="post"
      class="form"
      enctype="multipart/form-data"
    >
      <div class="write-review">
        <div class="write-review__purchase">
          <ul class="list-group">
            <li class="list-group-item">
              <div class="product-profile">
                <div class="product-profile__thumbnail">
                  <img
                    class="img-fluid"
                    data-ratio="3:4"
                    :src="
                      $productImage(product.op_product_id, product.op_pov_code, product.images ? product.images.afile_updated_at : '', '38-51')
                    "
                    alt="..."
                  />
                </div>
                <div class="product-profile__data">
                  <div class="title">{{ product.op_product_name }}</div>

                  <div class="options" v-if="product.op_product_variants">
                    <p v-if="JSON.parse(product.op_product_variants).styles">
                      {{
                        Object.values(
                          JSON.parse(product.op_product_variants).styles
                        ).join(" | ")
                      }}
                    </p>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="write-review__rate">
          <h6>{{ $t("LBL_RATE_THIS_PRODUCT") }}</h6>
          <div class="rating">
            <div
              v-bind:class="
                reviewType.type == 'view' ? 'rating-view' : 'rating-action'
              "
              :data-rating="selectedRating"
            >
              <svg
                class="icon YK-ratingItem"
                width="24"
                height="24"
                @click="selectedRating = 5"
              >
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#star'
                  "
                  :href="baseUrl + '/dashboard/media/retina/sprite.svg#star'"
                ></use>
              </svg>
              <svg
                class="icon YK-ratingItem"
                width="24"
                height="24"
                @click="selectedRating = 4"
              >
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#star'
                  "
                  :href="baseUrl + '/dashboard/media/retina/sprite.svg#star'"
                ></use>
              </svg>
              <svg
                class="icon YK-ratingItem"
                width="24"
                height="24"
                @click="selectedRating = 3"
              >
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#star'
                  "
                  :href="baseUrl + '/dashboard/media/retina/sprite.svg#star'"
                ></use>
              </svg>
              <svg
                class="icon YK-ratingItem"
                width="24"
                height="24"
                @click="selectedRating = 2"
              >
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#star'
                  "
                  :href="baseUrl + '/dashboard/media/retina/sprite.svg#star'"
                ></use>
              </svg>
              <svg
                class="icon YK-ratingItem"
                width="24"
                height="24"
                @click="selectedRating = 1"
              >
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#star'
                  "
                  :href="baseUrl + '/dashboard/media/retina/sprite.svg#star'"
                ></use>
              </svg>
            </div>
          </div>
        </div>

        <div class="write-review__about">
          <h6>{{ $t("LBL_GIVE_FEEDBACK_ABOUT_PRODUCT") }}</h6>
          <div class="form-group"> 
             <label class="">{{
              $t("LBL_REVIEW_TITLE")
            }}</label>
            <input
              type="text"
              class="form-control"
              name="preview_title"
              v-model="userData.preview_title"
              :readonly="readOnly"
              id="preview_title"
              v-bind:class="[userData.preview_title != '' ? 'filled' : '']"
            />
           
          </div>
          <div class="form-group"> 
            <label class="">{{
              $t("LBL_REVIEW_DESCRIPTION")
            }}</label>
            <textarea
              class="form-control"
              v-model="userData.preview_description"
              v-bind:class="[
                userData.preview_description != '' ? 'filled' : '',
              ]"
              style="resize: none"
              cols="30"
              :readonly="readOnly"
              rows="10"
            ></textarea>
            
          </div>
        </div>
        <div class="write-review__media">
          <ul class="list-media YK-uploadedImagesPreview">
            <li
              class="remove-able"
              v-for="(imageId, imageKey) in imageIds"
              :key="imageKey"
            >
              <img
                data-yk=""
                :src="baseUrl + '/yokart/image/' + imageId + '/small'"
                alt=""
              />
              <button
                type="button"
                class="remove YK-removeReviewImage"
                v-if="reviewType.type != 'view'"
                @click="removeImage(imageKey, imageId)"
              >
                <i class="fas fa-times"></i>
              </button>
            </li>
            <li class="upload" v-if="reviewType.type != 'view'">
              <i class="fas fa-camera"></i>
              <input
                type="file"
                accept="image/*"
                id="images"
                multiple="multiple"
                @change="onSelectImage($event)"
              />
            </li>
          </ul>
        </div>
      </div>
    </form>
    <template v-slot:modal-footer>
      <!-- <button
        type="button"
        class="btn btn-outline-brand btn-wide"
        @click="$bvModal.hide('reviewModel')"
      >
        {{ $t("BTN_REVIEW_CANCEL") }}
      </button> -->
      <button
        type="button"
        class="btn btn-brand btn-wide"
        :disabled="selectedRating == 0 || userData.preview_title == ''"
        @click="reviewInfo(), (sending = true)"
      >
        {{ $t("BTN_REVIEW_SUBMIT") }}
      </button>
    </template>
  </b-modal>
</template>
<script>
const tableFields = {
  preview_title: "",
  preview_description: "",
  preview_id: 0,
};
export default {
  props: ["product", "reviewType"],
  data: function() {
    return {
      sending: false,
      baseUrl: baseUrl,
      userData: tableFields,
      selectedRating: 0,
      imageIds: [],
      deleteIds: [],
      readOnly: false,
    };
  },
  methods: {
    reviewInfo: function() {
      let formData = this.$serializeData(this.userData);
      formData.append("preview_rating", this.selectedRating);
      formData.append("preview_prod_id", this.product.op_product_id);
      formData.append("preview_order_id", this.product.op_order_id);
      formData.append("imageIds", this.imageIds);
      formData.append("deleteids", this.deleteIds);
      this.$axios.post(baseUrl + "/user/reviews", formData).then((response) => {
        if (this.readOnly == false) {
          this.$emit("updateListing", this.product.op_id, this.selectedRating);
        }
        this.sending = false;
        this.$bvModal.hide("reviewModel");
      });
    },
    editDetails: function() {
      if (this.product.product_review) {
        this.userData.preview_title = this.reviewType.logId
          ? this.product.product_review.prl_preview_title
          : this.product.product_review.preview_title;
        this.userData.preview_description = this.reviewType.logId
          ? this.product.product_review.prl_preview_description
          : this.product.product_review.preview_description;
        this.userData.preview_id = this.product.product_review.preview_id;
        let rating = this.product.product_review.preview_rating;

        if (this.reviewType.logId) {
          rating = this.product.product_review.prl_preview_rating;
        }
        this.selectedRating = rating;

        this.getImages();
      }
    },
    getImages: function() {
      let id = this.userData.preview_id;
      let type = "productReviewImage";
      if (this.reviewType.logId) {
        id = this.product.product_review.prl_preview_id;
        type = "productReviewLogImage";
      }
      let formData = this.$serializeData({ id: id, type: type });
      this.$axios
        .post(baseUrl + "/user/reviews/uploaded/images", formData)
        .then((response) => {
          if (response.data.data.length != 0) {
            this.imageIds = response.data.data;
          }
        });
    },
    removeImage: function(index, id) {
      this.$axios
        .delete(baseUrl + "/user/reviews/images/" + id)
        .then((response) => {
          this.deleteIds.push(id);
          this.$delete(this.imageIds, index);
        });
    },
    emptyForm: function() {
      this.userData = {
        preview_title: "",
        preview_description: "",
        preview_id: 0,
      };
      this.readOnly = false;
      this.selectedRating = 0;
      this.imageIds = [];
      this.deleteIds = [];
    },
    onSelectImage: function($event) {
      let formData = this.$serializeData({
        preview_id: this.userData.preview_id,
      });
      let totalImages =
        parseInt(this.imageIds.length) + parseInt($event.target.files.length);
      if (totalImages > 5) {
        toastr.error("Max 5 images can be uploaded", "", toastr.options);
        return false;
      }
      let errorCount = 0;
      for (let i = 0; i < $event.target.files.length; i++) {
        if ($event.target.files[i].size > 2 * 1024 * 1024) {
          errorCount = errorCount + 1;
        } else {
          formData.append("preview_image[]", $event.target.files[i]);
        }
      }
      if (errorCount > 0) {
        toastr.error(
          "Some Images with size more than 2 Mb are skipped. Please upload images with size less than 2 MB"
        );
      }
      if (totalImages < errorCount) {
        return false;
      }
      this.$axios
        .post(baseUrl + "/user/reviews/images", formData)
        .then((response) => {
          if (response.data.data.length != 0) {
            this.imageIds = this.imageIds.concat(response.data.data);
          }
        });
    },
  },
  watch: {
    reviewType() {
      this.emptyForm();
      if (this.reviewType.type == "view") {
        this.readOnly = true;
        this.selectedRating = this.product.product_review.preview_rating;
      }
      this.editDetails();
    },
  },
};
</script>
 