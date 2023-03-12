<template>
<div class="grid-layout-aside" id="user_profile_aside">
    <div class="portlet portlet--height-fluid">
        <div class="portlet__body">
            <div class="widget widget--user-profile-1">
                <div class="widget__head">
                    <div class="widget__media">
                        <div class="avatar avatar--outline" id="user_avatar">
                            <div class="avatar__holder" v-bind:style="{ backgroundImage: 'url(' + croppedImageView + ')'}"></div>
                            <cropper ref="cropperRef" :title="'Admin Profile Image'" :aspectRatio="1" :width="250" :height="250" v-on:childToParent="setImage" v-on:actualImageToParent="setActualImage"></cropper>
                            <img :src="originalImage" id="originalImage" style="display: none;" />
                        </div>
                    </div>
                    <div class="widget__content">
                        <div class="widget__section">
                            <h3 class="widget__username">
                                {{userFullname}}
                            </h3>
                            <span class="widget__subtitle pb-3">
                                {{roleName}}
                            </span>                         
                            <a href="javascript:void(0);" @click="logout" class="logout-button">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15.4px" height="16.3px" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 198.715 198.715"  xml:space="preserve">
                                    <g>
                                        <path d="M161.463,48.763c-2.929-2.929-7.677-2.929-10.607,0c-2.929,2.929-2.929,7.677,0,10.606   c13.763,13.763,21.342,32.062,21.342,51.526c0,19.463-7.579,37.761-21.342,51.523c-14.203,14.204-32.857,21.305-51.516,21.303   c-18.659-0.001-37.322-7.104-51.527-21.309c-28.405-28.405-28.402-74.625,0.005-103.032c2.929-2.929,2.929-7.678,0-10.606   c-2.929-2.929-7.677-2.929-10.607,0C2.956,83.029,2.953,138.766,37.206,173.019c17.132,17.132,39.632,25.697,62.135,25.696   c22.497-0.001,44.997-8.564,62.123-25.69c16.595-16.594,25.734-38.659,25.734-62.129C187.199,87.425,178.059,65.359,161.463,48.763   z"/>
                                        <path d="M99.332,97.164c4.143,0,7.5-3.358,7.5-7.5V7.5c0-4.142-3.357-7.5-7.5-7.5s-7.5,3.358-7.5,7.5v82.164   C91.832,93.807,95.189,97.164,99.332,97.164z"/>
                                    </g>
                                </svg>

                                {{ $t('LNK_LOGOUT') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="widget__body">
                    <div class="widget__items">
                        <router-link :to="{name: 'editProfile'}" class="widget__item" v-bind:class="[tab == 'editProfile'? 'widget__item--active': '']" >
                            <span class="widget__section">
                                <span class="widget__icon">
                                    <svg class="svg-icon">
                                        <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#personal-info'" :href="baseUrl+'/admin/images/retina/sprite.svg#personal-info'"></use>
                                    </svg>
                                </span>
                                <span class="widget__desc">
                                    {{ $t('LNK_PERSONAL_INFORMATION') }}
                                </span>
                            </span>
                        </router-link>
                        <router-link :to="{name: 'changePassword'}" class="widget__item" v-bind:class="[tab == 'changePassword'? 'widget__item--active': '']" >
                            <span class="widget__section">
                                <span class="widget__icon">
                                   <svg class="svg-icon">
                                        <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#change-password'" :href="baseUrl+'/admin/images/retina/sprite.svg#change-password'"></use>
                                    </svg>
                                </span>
                                <span class="widget__desc">
                                    {{ $t('LNK_CHANGE_PASSWORD') }}
                                </span>
                            </span>
                        </router-link>
                        <router-link :to="{name: 'editEmailAddress'}" class="widget__item" v-bind:class="[tab == 'editEmail'? 'widget__item--active': '']" >
                            <span class="widget__section">
                                <span class="widget__icon">
                                    <svg class="svg-icon">
                                        <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#change-email'" :href="baseUrl+'/admin/images/retina/sprite.svg#change-email'"></use>
                                    </svg>
                                </span>
                                <span class="widget__desc">
                                    {{ $t('LNK_CHANGE_EMAIL') }}
                                </span>
                            </span>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
    props: ['tab'],
    data: function() {
        return {
            adminBaseUrl: adminBaseUrl,
            baseUrl: baseUrl,
            croppedImage: '',
            croppedImageView: '',
            originalImage: '',
            uploadedFile: '',
            noImage: '',
            userFullname: '',
            roleName: '',
            admin_id: ''
        };
    },
    methods: {
        getUser: function() {
            this.$http.get(adminBaseUrl + '/get-user').then((response) => {
                this.roleName = 'Super Admin';
                if (response.data.data.admin_role_id != null) {
                   this.roleName = response.data.data.admin_role.role_name;
                }
                this.noImage = response.data.noImage;
                this.userFullname = response.data.data.admin_name;
                this.admin_id = response.data.data.admin_id;
                if(response.data.data.admin_profile_image.afile_record_id != '') {
                    this.croppedImage = this.croppedImageView = this.$getFileUrl('profileImage', response.data.data.admin_profile_image.afile_record_id, 0, '120-120') + '/' + this.$rndStr();
                } else {
                    this.croppedImage =  this.uploadedFile = '';
                }
                this.originalImage = this.uploadedFile = this.$getFileUrl('profileImage', response.data.data.admin_profile_image.afile_record_id) + '/' + this.$rndStr();

                //this.originalImage = this.baseUrl + "/" + this.$getFileUrl("profileImage", response.data.data.admin_id, 0, "50-50", Date.now()); 
                //this.croppedImage = this.croppedImageView = this.$getFileUrl('profileImage', response.data.data.admin_profile_image.afile_record_id, 0, '50-50',Date.now());
            });
        },
        setImage: function(cropUrl, actualImage) {
            this.croppedImage = cropUrl;
            this.croppedImageView = URL.createObjectURL(cropUrl);
            if (actualImage == '') { //in case cropping already uploaded image
              this.uploadImage();
            }
        },
        setActualImage: function(actualImage) {
            if (typeof actualImage != 'string') {
                this.originalImage = URL.createObjectURL(actualImage);
                this.uploadedFile = actualImage;
            } else {
                this.uploadedFile = this.originalImage = actualImage;
            }
            this.uploadImage(); //in case of new file
        },
        uploadImage() {
            let formData = new FormData();
            formData.append('actualImage', this.uploadedFile);
            formData.append('cropImage', this.croppedImage);
            this.$root.$emit('adminImage', this.croppedImageView);
            this.$http.post(adminBaseUrl + '/update-profile-image/' + this.admin_id, formData).then((response) => {
                    toastr.success(response.data.success, '', {
                        timeOut: 5000
                    });
                },
                (response) => { //error
                    this.validateErrors(response);
                });
        },
        logout: function() {
            this.$http.get(adminBaseUrl + "/logout").then(response => {
                this.$router.go();
            });
        }
    },
    mounted: function() {
        this.getUser();
    }
}
</script>
