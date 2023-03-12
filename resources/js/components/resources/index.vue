<template>
    <div>
        <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
            <h3 class="subheader__title">{{ $t("LBL_RESOURCES") }}</h3>
            </div>
        </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                <div class="portlet portlet-no-min-height">
                    <div class="portlet__body pb-0 bg-gray flex-grow-0">
                    <div class="form-group">
                        <div class="input-icon input-icon--left">
                          <input
                                autocomplete="nope"
                                type="text"
                                class="form-control"
                                :placeholder="$t('PLH_SEARCH')"
                                id="generalSearch"
                                :readonly="
                                resources.length == 0 && searchData.search === ''
                                "
                                v-model="searchData.search"
                                @keyup="searchRecords"
                            />
                            <span class="input-icon__icon input-icon__icon--left"
                                ><span><i class="la la-search"></i></span
                            ></span>
                            <span
                                    class="input-icon__icon input-icon__icon--right"
                                    v-if="searchData.search != ''"
                                    @click="
                                    searchData.search = '';
                                    selectedPage = false;
                                    searchRecords();
                                    "
                                >
                                    <span>
                                    <i class="fas fa-times"></i>
                                    </span>
                                </span>
                        <!---->
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                <div class="col-md-4">
                  <div class="portlet portlet--height-fluid">
                      <div class="portlet__body">
                        <!--begin::Widget -->
                        <div class="resource-nav">
                          <div class="resource-nav_inner scroll-y">  
                            <div class="" v-if="resources.length == 0 && searchedRecords.length == 0">
                              <div class="no-data-found">
                                  No results found
                              </div>
                            </div>                              
                            <sl-vue-tree v-model="resources" :isExpanded="false">
                                <template slot="title" slot-scope="{ node }">
                                <span class="item-icon">
                                    <i class="fa fa-file" v-if="node.isLeaf"></i>
                                    <i class="fa fa-folder" v-if="!node.isLeaf"></i>
                                </span>
                                <span v-on:click.stop="openPage(node)">{{node.title}}</span>    
                                </template>
                            </sl-vue-tree>
                          </div>
                        </div>
                      </div>
                      <!--end::Widget -->
                    </div>
                </div>
         
                <div class="col-md-8">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__body">
                              <div class="no-data-found" v-if="selectedPage == false && searchData.search == ''">
                                  <div class="img">
                                   <img :src="this.$noDataImage('information-booklet.svg')" alt />
                                  </div>
                              </div>
                              <div class="no-data-found" v-if="searchData.search != '' && searchedRecords.length == 0  && selectedPage == false">
                                  <noRecordsFound></noRecordsFound>
                              </div>
                            <div class="row justify-content-center" >
                              <div class="col-md-11">
                           
                                <ul class="list__wrapper" v-if="selectedPage == false && searchedRecords.length != 0">
                            
                                      <li class="list__item" v-for="(searchedRecord, sIndex) in searchedRecords" :key="sIndex">
                                          <a class="list__item-action" href="javascript:;" @click="openPage('', searchedRecord)">
                                              <h3>{{searchedRecord.resource_title}}</h3>
                                          </a>
                                          <p class="list__item-content">
                                            {{searchedRecord.resource_description.replace(/(<([^>]+)>)/gi, "").substring(0, 350)+'...'}}
                                          </p>
                                      </li>
                                    
                                </ul>
                                <div class="cms resources" v-if="selectedPage == true">
                                  <h1>{{ this.title }}</h1>
                                  <div v-html="description"></div> 
                               </div>
                              </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import 'sl-vue-tree/dist/sl-vue-tree-dark.css';
import SlVueTree from "sl-vue-tree";
const searchFields = {
  search: "",
};
export default {
  components: {
    SlVueTree,
  }, 
  data: function () {
    return {
      resources: [],
      title: "",
      description: "",
      search: "",
      searchData: searchFields,
      searchedRecords: [],
      pageLoaded: 0,
      loading: false,
      showEmpty: 0,
      selectedPage: false,
    };
  },
  watch: {
    $route: "refreshedSearchData"
  },
  methods: {
     refreshedSearchData() {
      if (this.$route.query.s) {
        this.searchData.search = this.$route.query.s;
        this.openPage('', '', true);
      }
      this.getResources();
    },
    getResources: function () {
      this.$http.get(adminBaseUrl + "/resources").then((response) => {
        //
        if (response.data.status == false) {
          toastr.error(response.data.message, "", toastr.options);
          return;
        }
        this.resources = response.data.data.listing;
      });
    },
    openPage: function (node = '', record = '', globalSearch = false) {
      let id = '';
      let title = '';
      if(record){
          id = record.resource_id;
          title = record.resource_title;
      }else if(globalSearch == false){
        id = node.data.id;
        title = node.title;
        this.searchData.search = '';
      }
       if(globalSearch == true){
        title = this.searchData.search;
      }
      let formData = this.$serializeData({'id': id, 'title': title});
      this.title = "";
      this.description = "";
      this.$http
        .post(adminBaseUrl + "/resource/details", formData)
        .then((response) => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          this.selectedPage = true;
          this.title = title;
          this.description = response.data.data.description;
        });
    },
    recursiveMap: function (data) {
      var thisObj = this;
      return data.map((el) => {
        if (el.children.length > 0) {
          thisObj.recursiveMap(el.children);
          el.title_full_name = el.title;
          for (var j = 0; j < el.children.length; j++) {
            el.title_full_name += el.children[j].title_full_name
              ? el.children[j].title_full_name
              : el.children[j].title;
          }
        } else {
          el.title_full_name = el.title;
        }
        el.title_full_name =
          typeof el.title_full_name == "object"
            ? el.title_full_name.title
            : el.title_full_name;
        return el;
      });
    },
    searchRecords: function () {
      this.title = "";
      this.description = "";
       this.selectedPage = false;
     let formData = this.$serializeData(this.searchData);
      this.$http
        .post(adminBaseUrl + "/resources/search", formData)
        .then((response) => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            this.$router.push({ name: "unAuthorised" });
            return;
          }
          this.searchedRecords = response.data.data;
        });
    },
    getNode(
      path,
      nodeModel = null,
      siblings = null,
      isVisible = null
    ) {
      const ind = path.slice(-1)[0];

      // calculate nodeModel, siblings, isVisible fields if it is not passed as arguments
      siblings = siblings || this.getNodeSiblings(this.currentValue, path);
      nodeModel = nodeModel || (siblings && siblings[ind]) || null;

      if (isVisible == null) {
        isVisible = this.isVisible(path);
      }

      if (!nodeModel) return null;

      const isExpanded = nodeModel.isExpanded == void 0 ? true : !!nodeModel.isExpanded;
      const isDraggable = nodeModel.isDraggable == void 0 ? true : !!nodeModel.isDraggable;
      const isSelectable = nodeModel.isSelectable == void 0 ? true : !!nodeModel.isSelectable;

      const node = {

        // define the all ISlTreeNodeModel props
        title: nodeModel.title,
        isLeaf: !!nodeModel.isLeaf,
        children: nodeModel.children ? this.getNodes(nodeModel.children, path, isExpanded) : [],
        isSelected: !!nodeModel.isSelected,
        isExpanded,
        isVisible,
        isDraggable,
        isSelectable,
        data: nodeModel.data !== void 0 ? nodeModel.data : {},

        // define the all ISlTreeNode computed props
        path: path,
        pathStr: JSON.stringify(path),
        level: path.length,
        ind,
        isFirstChild: ind == 0,
        isLastChild: ind === siblings.length - 1
      };
      return node;
    },
    getNodeSiblings(nodes, path) {
      if (path.length === 1) return nodes;
         
      return this.getNodeSiblings(nodes[path[0]].children, path.slice(1));
    },
     select(path, addToSelection = false, event = null) {
     
      const multiselectKeys = Array.isArray(this.multiselectKey) ?
        this.multiselectKey:
        [this.multiselectKey];
      const multiselectKeyIsPressed = event && !!multiselectKeys.find(key => event[key]);
      addToSelection = (multiselectKeyIsPressed || addToSelection) && this.allowMultiselect ;

      const selectedNode = this.getNode(path);
      if (!selectedNode) return null;
      const newNodes = this.copy(this.currentValue);
      const shiftSelectionMode = this.allowMultiselect && event && event.shiftKey && this.lastSelectedNode;
      const selectedNodes = [];
      let shiftSelectionStarted = false;

      this.traverse((node, nodeModel) => {


        if (shiftSelectionMode) {
          if (node.pathStr === selectedNode.pathStr || node.pathStr === this.lastSelectedNode.pathStr) {
            nodeModel.isSelected = node.isSelectable;
            shiftSelectionStarted = !shiftSelectionStarted;
          }
          if (shiftSelectionStarted) nodeModel.isSelected = node.isSelectable;
        } else if (node.pathStr === selectedNode.pathStr) {
          nodeModel.isSelected = node.isSelectable;
        } else if (!addToSelection) {
          if (nodeModel.isSelected) nodeModel.isSelected = false;
        }

        if (nodeModel.isSelected) selectedNodes.push(node);

      }, newNodes);


      this.lastSelectedNode = selectedNode;
      this.emitInput(newNodes);
      this.emitSelect(selectedNodes, event);
      return selectedNode;
    },
  },
  mounted() {
    let thisVal = this;
      this.searchData = { search: "" };
     this.refreshedSearchData();
 
    $('body').on('click', 'a', function(e) {
      e.preventDefault();
    });
     $('body').on('click', '.openChildNode', function() {
       let path = $(this).attr('data-path');
       thisVal.select(path);
     
    });
  },
  computed: {},
};
</script>