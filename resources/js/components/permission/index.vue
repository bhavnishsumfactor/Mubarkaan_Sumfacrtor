<template>
    <div>
        <div id="subheader" class="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">Access Denied</h3>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                <div class="portlet">
                        <div class="portlet__body portlet__body--fit">
                            <div class="access-denied">
                                <img :src="baseUrl+'/admin/images/ratina/403.svg'" alt="" />
                                <h3>OOPS!!!! Sorry...</h3>
                                <p>                                                
                                    Sorry, your access is refused due to security reasons of our server
                                    and also our sensitive data. Please go back to the previous page to
                                    continue browsing.
                                </p>
                                <div class="action">
                                    <router-link :to="{name: 'dashboard'}" class="btn btn-brand btn-wide router-link-active">
                                        Go Back
                                    </router-link>
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
    data: function () {
        return {
            baseUrl: baseUrl
        }
    }
}
</script>