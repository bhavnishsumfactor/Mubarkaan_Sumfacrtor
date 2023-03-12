<template>
  <b-modal id="blogCollection" size="lg" centered title="BootstrapVue">
    <template v-slot:modal-header>
      <h5 class="modal-title" id="exampleModalLabel">
        <span>Blog Collection</span>
      </h5>
      <button type="button" class="close" @click="closePopup()"></button>
    </template>
    <div class="alert alert-secondary" data-yk role="yk-alert">
      <div class="alert-icon">
        <i class="flaticon-questions-circular-button"></i>
      </div>
      <div
        class="alert-text"
      >{{collectionType!='blog-collection1' ? ' 1 item need to be added' : 'Atleast 1 items need be added'}}</div>
    </div>
    <div class="form-group row">
      <div class="col-md-12">
        <input type="text" class="form-control" placeholder="Title" v-model="pageTitle" />
      </div>
    </div>
    <div class="form-group">
      <input
        type="text"
        :placeholder="$t('PLH_BLOG_PLEASE_SEARCH')"
        aria-label="Search Blog"
        autocomplete="off"
        class="form-control"
        data-toggle="dropdown"
        aria-expanded="true"
        v-model="blogName"
        @keyup="searchblogs"
      />
      <div class="dropdown-menu dropdown-menu-fit dropdown-menu-anim dropdown-suggestions">
        <ul class="nav nav--block">
          <perfect-scrollbar :style="'max-height: 200px'">
            <li class="dropdown-item nav__item" v-for="(blog, index) in blogs" :key="index">
              <a href="javascript:;" @click="selectedBlog(blog)">{{ blog.label }}</a>
            </li>
          </perfect-scrollbar>
        </ul>
      </div>
    </div>
    <perfect-scrollbar :style="'max-height: 400px'" v-if="selectedCollections.length > 0">
      <SlickList
        lockAxis="y"
        class="list-group list-group__collections mt-4"
        @sort-end="sortEnd"
        v-model="selectedCollections"
        tag="ul"
      >
        <SlickItem
          class="list-group-item d-flex justify-content-between align-items-center"
          v-for="(selectedCollection, sindex) in selectedCollections"
          :index="sindex"
          :key="sindex"
          tag="li"
        >
          <div class="d-flex align-items-center">
            <i class="icon fa fa-arrows-alt handle mr-3"></i>
            <span>{{selectedCollection.name}}</span>
          </div>
          <div class="actions">
            <button type="button" class="btn btn-icon" @click="deleteBlogCollection(sindex)">
              <svg>
                <use :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#delete-icon'" />
              </svg>
            </button>
          </div>
        </SlickItem>
      </SlickList>
    </perfect-scrollbar>
    <div class="no-data-found" v-else>
      <div class="img">
        <img :src="this.$noDataImage()" alt />
      </div>
      <div class="data">
        <p>{{ $t('LBL_USE_ICONS_FOR_ADVANCED_ACTIONS') }}</p>
      </div>
    </div>

    <template v-slot:modal-footer>
      <button type="submit" :disabled="!isComplete" class="btn btn-brand" @click="saveCollection">
        <span>Embed</span>
      </button>
    </template>
  </b-modal>
</template>
<script>
import { SlickList, SlickItem } from "vue-slicksort";

export default {
  components: {
    SlickItem,
    SlickList
  },
  props: ["clickEvent", "pageId", "acId", "collectionId", "collectionType"],
  data() {
    return {
      baseUrl: baseUrl,
      adminBaseUrl: adminBaseUrl,
      blogs: [],
      pageTitle: "",
      blogName: [],
      sortCollection: [],
      listingIndex: 0,
      selectedCollections: []
    };
  },
  watch: {
    clickEvent: function() {
      this.sortCollection = [];
      this.selectedCollections = [];
      this.pageTitle = "";
      if (this.collectionId) {
        this.getRecords();
      }
    }
  },
  computed: {
    isComplete() {
      if (this.collectionType != "blog-collection1") {
        return this.selectedCollections.length == 1 && this.pageTitle != "";
      } else {
        return this.selectedCollections.length > 1 && this.pageTitle != "";
      }
    }
  },
  methods: {
    sortEnd({ oldIndex, newIndex }) {
      let thisObj = this;
      setTimeout(function() {
        let maxVal = Math.max(oldIndex, newIndex);
        let minVal = Math.min(oldIndex, newIndex);
        for (let x = minVal; x <= maxVal; x++) {
          thisObj.selectedCollections[x].order = thisObj.sortCollection[x];
        }
      }, 100);
    },
    closePopup: function() {
      this.$emit("pageLoadData", true);
      this.$bvModal.hide("blogCollection");
    },
    saveCollection: function() {
      let formData = new FormData();
      formData.append("collection", JSON.stringify(this.selectedCollections));
      formData.append("page-id", this.pageId);
      formData.append("ac-id", this.acId);
      formData.append("actr-id", this.collectionId);
      formData.append("type", this.collectionType);
      formData.append("title", this.pageTitle);
      this.$http
        .post(adminBaseUrl + "/app/store/records", formData)
        .then(response => {
          this.closePopup();
        });
    },

    deleteBlogCollection: function(index) {
      if (this.selectedCollections[index].acrd_id != 0) {
        this.$emit(
          "deleteCollRecordById",
          this.selectedCollections[index].acrd_id
        );
      }
      this.$delete(this.sortCollection, index);
      this.$delete(this.selectedCollections, index);
    },

    selectedBlog: function(record) {
      if (
        this.$collectionNameExist(record.label, this.selectedCollections) ==
        true
      ) {
        toastr.error("You have selected duplicate record", "", toastr.options);
        return;
      }
      let maxSortVal = 1;
      if (this.sortCollection.length > 0) {
        maxSortVal = Math.max.apply(null, this.sortCollection) + 1;
      }
      this.selectedCollections.push({
        record_id: record.value,
        name: record.label,
        order: maxSortVal,
        acrd_id: 0
      });
      this.sortCollection.push(maxSortVal);
      this.blogName = "";
    },

    searchblogs: function() {
      let formData = this.$serializeData({
        type: "blog",
        search: this.blogName
      });
      this.$http
        .post(adminBaseUrl + "/app/collection/search-records", formData)
        .then(response => {
          this.blogs = response.data.data;
        });
    },
    getRecords: function() {
      let formData = this.$serializeData({
        type: this.collectionType,
        "collection-id": this.collectionId
      });
      this.$http
        .post(adminBaseUrl + "/app/collection", formData)
        .then(response => {
          this.selectedCollections = [];
          let editData = response.data.data.editData;
          Object.keys(editData).forEach(key => {
            this.pageTitle = editData[key].actr_title;
            this.selectedCollections.push({
              record_id: editData[key].post_id,
              name: editData[key].post_title,
              order: editData[key].acrd_display_order,
              acrd_id: editData[key].acrd_id
            });
            this.sortCollection.push(editData[key].acrd_display_order);
          });
        });
    }
  }
};
</script>
