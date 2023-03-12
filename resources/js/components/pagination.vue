<template>
    <div class="pagination">
        <ul class="pagination__links">
          
            <li v-if="pagesNumber.length > 1" class="pagination__link--first" > 
                <a href="javascript:;" @click.prevent="changePage(1)" v-bind:class="[ pagination.current_page == 1 ? 'disabledbutton' : '']"><i class="fa fa-angle-double-left"></i></a>
            </li>
            <li v-if="pagesNumber.length > 1" class="pagination__link--next" >
                <a href="javascript:;" @click.prevent="changePage(pagination.current_page - 1)" v-bind:class="[ pagination.current_page == 1 ? 'disabledbutton' : '']"><i class="fa fa-angle-left"></i></a>
            </li>
            
            <li v-show="pagesNumber.length > 1 && page >= (pagination.current_page - 1) && page <= (pagination.current_page + 1)" v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'pagination__link--active' : '']" v-bind:key="page">
                <a href="javascript:;" @click.prevent="changePage(page)">{{ page }}</a>
            </li>
            <li v-if="pagesNumber.length > 1" class="pagination__link--prev"  >
                <a href="javascript:;" @click.prevent="changePage(pagination.current_page + 1)" v-bind:class="[ pagination.current_page == pagination.last_page ? 'disabledbutton' : '']"><i class="fa fa-angle-right"></i></a>
            </li>
            <li v-if="pagesNumber.length > 1" class="pagination__link--last"  >
                <a href="javascript:;" @click.prevent="changePage(pagination.last_page)" v-bind:class="[ pagination.current_page == pagination.last_page ? 'disabledbutton' : '']"><i class="fa fa-angle-double-right"></i></a>
            </li>
        </ul>
            <div class="pagination__toolbar">
                <select class="form-control" v-model="pagination.per_page" style="width: 60px;" @change="displayRecords()" v-if="pagesNumber.length > 1 && hideRecordsSelection != true">
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>
                <span class="pagination__desc" v-if="pagination.from < pagination.to">
                    {{$t('LBL_PAGINATION_SHOWING')}} {{ pagination.from }} {{$t('LBL_PAGINATION_TO')}} {{ pagination.to }} {{$t('LBL_PAGINATION_OF')}} {{pagination.total}} {{$t('LBL_PAGINATION_RECORDS')}}
                </span>
                <span class="pagination__desc" v-else>
                    {{$t('LBL_PAGINATION_SHOWING')}} {{ pagination.from }} {{$t('LBL_PAGINATION_OF')}} {{pagination.total}} {{$t('LBL_PAGINATION_RECORDS')}}
                </span>
            </div>
        
    </div>


</template>
<script>


export default{
props:['pagination', 'hideRecordsSelection'],
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        pagesNumber: function () {
            return this.$pagesNumber(this.pagination);
        }
    },
     methods: {
         changePage: function (page) {
           this.pagination.current_page = page;
           this.$emit('currentPage',page);
        },
         displayRecords: function () {
           this.$emit('currentPage', 1);
        },
     },
}
</script>