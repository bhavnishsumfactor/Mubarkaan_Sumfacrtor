<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{$t('Sent Emails')}}</h3>
                </div>
                <div v-if="$route.params.id" class="subheader__toolbar">
                    <router-link :to="{name: 'emailList', query:{page: $route.query.page}}" class="btn btn-white"><i class="la la-arrow-left"></i><span class="hidden-mobile">{{$t('Back')}}</span></router-link>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__head" >
                            <div class="portlet__head-label">
                                <h3 class="portlet__head-title">{{ record.emailarchive_subject }}</h3>
                            </div>
                        </div>
                        <div class="portlet__body portlet__body--fit" >
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Date</label> 
                                    <div class="col-md-10">{{ record.emailarchive_created_at | formatDateTime }}</div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Headers</label> 
                                    <div class="col-md-10">{{ record.emailarchive_headers }}</div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Body</label> 
                                    <div class="col-md-10" v-html="record.emailarchive_body"></div>
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
export default {
    data: function() {
        return {
            id:'',
            record:''
        }
    },
    methods: {
        pageLoadData: function () {
            this.$http.get(adminBaseUrl + '/email-archieves/details/'+ this.id).then((response) => {
                 if (response.data.status == false) {
                    toastr.error(response.data.message, "", toastr.options);
                    return;
                }
                this.record = response.data.data;
                console.log(this.record );
            }).catch(function (error) {
                
            });
        }
    },
    mounted: function() {
        this.id = this.$route.params.id;
        this.pageLoadData();
    }
}
</script>