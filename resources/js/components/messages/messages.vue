<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_MESSAGES') }}</h3> </div>
        </div>
    </div>

    <!-- begin:: Content -->
    <div class="container">
        <!--Begin::App-->
        <div class="row chat">
            <!--Begin:: App Aside-->
            <div class="col-lg-4 chat__aside" id="chat_aside" data-close-on-click-outside="chat_aside">
                <!--begin::Portlet-->
                <div class="portlet portlet--last portlet--height-fluid">
                    <div class="portlet__body">
                        <!--Begin:: App Aside Mobile Toggle-->
                        <button class="btn btn-outline-brand btn-elevate btn-icon btn-sm app__aside-close d-flex d-lg-none mb-3" id="chat_aside_close" data-target-close="chat_aside"> <i class="fas fa-times"></i> </button>
                        <!--End:: App Aside Mobile Toggle-->
                        <div class="searchbar">
                            <div class="form-group">
                                <div class="input-icon input-icon--left">
                                    <input type="text" class="form-control" :placeholder="$t('PLH_SEARCH_CHAT')" id="generalSearch" v-model="search" @keyup="searchRecords"> <span class="input-icon__icon input-icon__icon--left">
                                            <span><i class="la la-search"></i></span> </span>
                                </div>
                            </div>
                        </div>
                        <perfect-scrollbar :options="{suppressScrollX: true}" :style="'max-height: 500px'" @scroll="onScrollChat">
                            <div class="widget widget--users">
                                <div class="widget__items" v-if="chatThreads.length > 0">
                                    <div class="widget__item " v-for="(record, index) in chatThreads" :key="index" v-bind:class="[adminsData.threadId == record.thread_id ? 'is-active' : '']" @click="threadMessages($event, record)"> 
                                        <span class="media media--circle">
                                            <img :src="$getFileUrl('userProfileImage', record.thread_started_by, 0, '50-50')" width="50">
                                            <i class="badge badge--success" :id="'threadMessage-'+record.thread_id" v-if="record.get_admin_unread_message_count > 0">
                                                {{ record.get_admin_unread_message_count }}
                                            </i>
                                        </span>
                                        <div class="widget__info">
                                            <div class="widget__section"> <a href="#" class="widget__username">{{ record.user_fname + ' ' + record.user_lname }}</a> </div>
                                            <div class="widget__subject"></div> 
                                                <span class="widget__desc" v-if="record.message_text != null">
                                                    {{ record.message_text.length > 100 ? record.message_text.substring(0,50)+"...." : record.message_text }}
                                                </span> 
                                              
                                                </div>
                                        <div class="widget__action">
                                            <div class="widget__date"> <span>
                                                {{ record.message_created_at | formatDate }}</span> <span class="widget__time">{{ record.message_created_at | formatTime }}
                                            </span> </div>
                                        </div>
                                    </div>
                                </div>
                                <loader v-if="loading"></loader>
                                <noRecordsFound v-if="loading==false && chatThreads.length == 0"></noRecordsFound>
                            </div>
                         </perfect-scrollbar>
                    </div>
                </div>
                <!--end::Portlet-->
            </div>
            <!--End:: App Aside-->
            <!--Begin:: App Content-->
            <div class="col-lg-12 col-xl-8 chat__content" id="chat_content">
                <div class="portlet portlet--head-lg">
                    <div class="portlet__head">
                        <div class="chat__head ">
                            <div class="chat__center">
                                <ul class="list-group list-cart list-cart-order list-order list-order-return my-5">
                                    <li class="list-group-item">
                                        <div class="list-inner">
                                            <div class="product-profile" style="max-width: unset;">
                                                <div class="product-profile__thumbnail">
                                                    <a :href="baseUrl+'/product/'+thread_product_id" target="_blank" v-if="thread_product_id != ''"> <img v-if="thread_product_variant != ''" class="pro-img img-fluid" :src="$productImage(thread_product_id, thread_product_variant, '', '39-52')" /> <img v-else class="pro-img img-fluid" :src="$productImage(thread_product_id, 0, '', '39-52')" /> </a>
                                                </div>
                                                <div class="product-profile__data text-left">
                                                    <div class="title" v-if="productName != ''"> <a :href="baseUrl+'/product/'+thread_product_id" target="_blank" class="text-body">{{productName}}</a> </div>
                                                    <div class="sub__title" v-if="thread_subject != ''"> <strong>{{$t('LBL_MESSAGE_SUBJECT')}}: </strong>{{thread_subject}} </div>
                                                    <!-- <div class="title"><strong>{{$t('LBL_ASKED_BY')}}: </strong>{{user_name}}</div> --></div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="portlet__body pb-0">
                        <div class="chat__messages" id="admin-chat-messages">
                            <perfect-scrollbar :options="{suppressScrollX: true}" ref="scroll" id="YK-message-container" :style="'max-height: 370px; min-height: 370px;'">
                                <div class="chat__message" v-for="(record, index) in messages" :key="record.message_id">
                                    <div class="msg" v-if="record.message_from_admin == 0 ">
                                        <div class="chat__user"> <span class="media media--circle media--sm">
                                                        <img :src="$getFileUrl('userProfileImage', record.message_from_id, 0, '60-60')" width="60">
                                                    </span> </div>
                                        <div class="chat_text_wrapper">
                                            <div class="chat__text"> 
                                                <img :src="$getFileUrl('messageFile', record.message_id, 0, 'original', '')" width="200" v-if="record.upload_images" @click="openImage(record.message_id)">
                                                <p v-else>{{ record.message_text  }}</p>
                                                 <p class="chat__datetime time__chat">{{ record.message_created_at | formatTime}} </p>
                                            </div>
                                            <p class="chat__datetime">{{ record.message_created_at | formatDateTime }} </p>
                                        </div>
                                    </div>
                                    <div class="msg" v-if="record.message_from_admin == 1">
                                        <div class="chat__message chat__message--right">
                                            <div class="chat_text_wrapper">
                                                <div class="chat__text"> 
                                                    <img :src="$getFileUrl('messageFile', record.message_id, 0, 'original', '')" @click="openImage(record.message_id)" width="200" v-if="record.upload_images">
                                                    <p v-else>{{ record.message_text  }}</p>
                                                   
                                                </div>
                                                <p class="chat__datetime">{{ record.message_created_at | formatDateTime }} </p>
                                            </div>
                                            <div class="chat__user"> <span class="media media--circle media--sm">
                                                            <img :src="baseUrl + '/' + $getFileUrl('favicon', 0, 0, '38-38')">
                                                        </span> </div>
                                        </div>
                                    </div>
                                </div>
                            </perfect-scrollbar>
                        </div>
                    </div>
                    <div class="portlet__foot p-0">
                        <!--<form v-on:submit.prevent="sendMessage">-->
                        <input type="hidden" v-model="adminsData.threadId">
                        <div class="chat__input">
                            <div class="chat__editor">
                                <div class="chat__emoji">
                                    <twemoji-picker :emojiData="emojiDataAll" :emojiGroups="emojiGroups" :pickerWidth="'300px'" :pickerCloseOnClickaway="true" @emojiUnicodeAdded="selectEmoji">
                                        <template v-slot:twemoji-picker-button>
                                            <button class="btn btn-lg emoji-icn ">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="16" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                    <g>
                                                        <g>
                                                            <path d="M437.02,74.98C388.667,26.629,324.38,0,256,0S123.333,26.629,74.98,74.98C26.629,123.333,0,187.62,0,256    s26.629,132.668,74.98,181.02C123.333,485.371,187.62,512,256,512s132.667-26.629,181.02-74.98    C485.371,388.668,512,324.38,512,256S485.371,123.333,437.02,74.98z M256,472c-119.103,0-216-96.897-216-216S136.897,40,256,40    s216,96.897,216,216S375.103,472,256,472z" /> </g>
                                                    </g>
                                                    <g>
                                                        <g>
                                                            <path d="M368.993,285.776c-0.072,0.214-7.298,21.626-25.02,42.393C321.419,354.599,292.628,368,258.4,368    c-34.475,0-64.195-13.561-88.333-40.303c-18.92-20.962-27.272-42.54-27.33-42.691l-37.475,13.99    c0.42,1.122,10.533,27.792,34.013,54.273C171.022,389.074,212.215,408,258.4,408c46.412,0,86.904-19.076,117.099-55.166    c22.318-26.675,31.165-53.55,31.531-54.681L368.993,285.776z" /> </g>
                                                    </g>
                                                    <g>
                                                        <g>
                                                            <circle cx="168" cy="180.12" r="32" /> </g>
                                                    </g>
                                                    <g>
                                                        <g>
                                                            <circle cx="344" cy="180.12" r="32" /> </g>
                                                    </g>
                                                </svg>
                                            </button>
                                        </template>
                                    </twemoji-picker>
                                </div>
                                <textarea class="form-control" v-model="adminsData.txtMessage" id="txtmessage" 
                                @keydown.enter.exact.prevent
                                @keyup.enter.exact="sendMessage"
                                @keydown.enter.shift.exact="newline"
                                style="height: 50px" :placeholder="$t('PLH_TYPE_HERE') + '...'"></textarea>
                                <div class="chat__send">
                                    <div class="chat__image" v-bind:class="[!$canWrite('MESSAGES') ? 'disabledbutton' : '']">
                                        <label id="images" class="btn btn-lg mb-0" @click="$refs.fileInput.click()">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 550.801 550.8" style="enable-background:new 0 0 550.801 550.8;" xml:space="preserve">
                                                <g>
                                                    <path d="M515.828,61.201H34.972C15.659,61.201,0,76.859,0,96.172v358.458C0,473.942,15.659,489.6,34.972,489.6h480.856   c19.314,0,34.973-15.658,34.973-34.971V96.172C550.801,76.859,535.143,61.201,515.828,61.201z M515.828,96.172V350.51l-68.92-62.66   c-10.359-9.416-26.289-9.04-36.186,0.866l-69.752,69.741L203.438,194.179c-10.396-12.415-29.438-12.537-39.99-0.271L34.972,343.219   V96.172H515.828z M367.201,187.972c0-26.561,21.523-48.086,48.084-48.086c26.562,0,48.086,21.525,48.086,48.086   c0,26.561-21.523,48.085-48.086,48.085C388.725,236.058,367.201,214.533,367.201,187.972z" /> </g>
                                            </svg>
                                        </label>
                                        <input style="display: none" ref="fileInput" type="file" @change="fileSelected" accept="image/x-png,image/gif,image/jpeg"> 
                                    </div> 
                                    <div class="chat__action">
                                        <button class="btn btn-brand btn-lg chat-send" @click="sendMessage" :disabled='!isComplete || clicked==1' v-bind:class="[!$canWrite('MESSAGES') ? 'disabledbutton' : '']">
                                            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="16" viewBox="0 0 24 24" width="16">
                                                <path d="m8.75 17.612v4.638c0 .324.208.611.516.713.077.025.156.037.234.037.234 0 .46-.11.604-.306l2.713-3.692z" />
                                                <path d="m23.685.139c-.23-.163-.532-.185-.782-.054l-22.5 11.75c-.266.139-.423.423-.401.722.023.3.222.556.505.653l6.255 2.138 13.321-11.39-10.308 12.419 10.483 3.583c.078.026.16.04.242.04.136 0 .271-.037.39-.109.19-.116.319-.311.352-.53l2.75-18.5c.041-.28-.077-.558-.307-.722z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--</form>-->
            </div>
        </div>
    </div>
    <!--End:: App Content-->
<!--End::App-->
</div>
</template>
<script>
    import { TwemojiPicker } from '@kevinfaguiar/vue-twemoji-picker';
    import EmojiAllData from '@kevinfaguiar/vue-twemoji-picker/emoji-data/en/emoji-all-groups.json';
    import EmojiDataAnimalsNature from '@kevinfaguiar/vue-twemoji-picker/emoji-data/en/emoji-group-animals-nature.json';
    import EmojiDataFoodDrink from '@kevinfaguiar/vue-twemoji-picker/emoji-data/en/emoji-group-food-drink.json';
    import EmojiGroups from '@kevinfaguiar/vue-twemoji-picker/emoji-data/emoji-groups.json';

    const tableFields = {
        'txtMessage': '',
        'threadId': ''
    };
    export default {
         components: {
            'twemoji-picker': TwemojiPicker
        },
        data: function() {
            return {
                baseUrl: baseUrl,
                messages: [],
                adminsData: tableFields,
                chatThreads: [],
                search: '',
                user_name: '',
                thread_subject: '',
                productName: '',
                thread_product_id: '',
                thread_product_variant: '',
                clicked: 0,
                logo: siteLogo,
                loading: false,
                threadsRow:0,
                threadPending:0,
                threadPaginateCount:0,
                selectedFile:'',
                loadingData:false
            }
        },
        methods: {
            newline() {
                this.value = `${this.value}\n`;
            },
            sendMessage: function() {
                if (this.adminsData.txtMessage == '') {
                    return false;
                }
                this.clicked = 1;
                let input = this.adminsData;
                let formData = this.$serializeData(input);
                this.$http.post(adminBaseUrl + '/messages/send-message', formData)
                    .then((response) => { //success
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            this.clicked = 0;
                            return;
                        }
                        if (response.data.status == true) {
                            this.adminsData.txtMessage = '';
                            this.messages.push(response.data.data);
                            this.clicked = 0;
                            setTimeout(() => {
                                $('#YK-message-container').scrollTop($('#YK-message-container')[0].scrollHeight);
                            }, 500);
                        }
                    }, (response) => { //error
                        //this.validateErrors(response);
                        this.clicked = 0;
                    });
            },            
            validateErrors: function(response) {
                let jsondata = response.data;
                Object.keys(jsondata.errors).forEach(key => {
                    this.errors.add({
                        field: key,
                        msg: jsondata.errors[key][0]
                    });
                });
            },
            searchRecords: function() {
                this.getChatThreads();
            },
            getChatThreads: function() {
                let formData = this.$serializeData({
                    total: 0,
                    search: this.search
                });
                this.$http.post(adminBaseUrl + '/messages/chat-threads', formData).then((response) => {
                    this.loading = false;
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.chatThreads= response.data.data.message;
                   /* if (typeof this.chatThreads[0] != 'undefined' && this.search == '') {
                        if(this.thread_subject == ''){
                            this.thread_subject = this.chatThreads[0].thread_subject;
                            this.thread_product_id = this.chatThreads[0].thread_product_id;
                            this.productName = this.chatThreads[0].thread_product_name;
                        }
                        this.thread_product_variant = this.chatThreads[0].thread_product_variant;
                    }*/
                });
            },
            threadMessages: function(event, thread) { 
                event.preventDefault();
                this.thread_subject = thread.thread_subject;
                this.thread_product_id = thread.thread_product_id;
                this.productName = thread.prod_name;
                this.thread_product_variant = thread.thread_product_variant;
                this.$router.push({ name: 'threadMessages', params: { id: thread.thread_id } });
                setTimeout(() => {
                    $('#YK-message-container').scrollTop($('#YK-message-container')[0].scrollHeight);
                    $('#threadMessage-'+thread.thread_id).text('');
                }, 500);
            },
            getMessageThreadData: function() {
                this.$http.get(adminBaseUrl + '/messages/get-thread-messages/' + this.$route.params.id).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.messages = response.data.data;     
                    this.thread_subject = this.messages[0].thread.thread_subject;
                    this.thread_product_id = this.messages[0].thread.product.prod_id;
                    this.productName = this.messages[0].thread.product.prod_name;                                                     
                    this.user_name = this.messages[0].message_from.user_fname + ' ' + this.messages[0].message_from.user_lname;
                    if (response.data.data.length > 0) {
                        this.adminsData.threadId = response.data.data[0].message_thread_id;
                    }
                    setTimeout(() => {
                        $('#YK-message-container').scrollTop($('#YK-message-container')[0].scrollHeight);
                    }, 500);
                    
                });
            },
            onScrollChat : function({ target: { scrollTop, clientHeight, scrollHeight }}) {
                if (((scrollTop + clientHeight + 100) >= scrollHeight)) {
                    if(this.loading === false && this.search == '') {
                        this.infiniteHandler();
                    }
                }
            },
            selectEmoji: function(emoji) {
                this.adminsData.txtMessage += emoji;
            },
            fileSelected: function(evt) {
                evt.preventDefault();
                let fileName = evt.target.files[0].name;
                let allowedExtensions =  /(\.jpg|\.jpeg|\.png|\.gif)$/i;
                if (!allowedExtensions.exec(fileName)) { 
                    alert(this.$t("NOTI_INVALID_FILE_TYPE"));
                    return false; 
                }
                this.selectedFile = evt.target.files[0];
                this.uploadImage();
            },
            uploadImage: function() {
                let input = this.adminsData;
                let formData = this.$serializeData(input);
                formData.append('file', this.selectedFile);
                formData.append("txtMessage", this.selectedFile.name);
                this.$http.post(adminBaseUrl + '/messages/send-message', formData)
                    .then((response) => { //success
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            this.clicked = 0;
                            return;
                        }
                        this.$refs.fileInput.value = '';
                        this.adminsData.txtMessage = '';
                        this.messages.push(response.data.data);
                        setTimeout(() => {
                            $('#YK-message-container').scrollTop($('#YK-message-container')[0].scrollHeight);
                        }, 500);
                        this.clicked = 0;
                    }, (response) => { //error
                        this.clicked = 0;
                });
            },
            infiniteHandler: function() {
                this.loading =true;
                let formData = this.$serializeData({
                    total: this.chatThreads.length,
                    page: this.page,
                    search: this.search
                });
                this.$http.post(adminBaseUrl + '/messages/chat-threads', formData).then((response) => {
                    this.loading = false;
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }    
                    if (response.data.data.message.length) { 
                        this.chatThreads.push(...response.data.data.message);
                        if(typeof this.chatThreads[0] != 'undefined' && this.search == '') {
                            this.thread_subject = this.chatThreads[0].thread_subject;
                            this.thread_product_id = this.chatThreads[0].thread_product_id;
                            this.productName = this.chatThreads[0].prod_name;
                            this.thread_product_variant = this.chatThreads[0].thread_product_variant;
                        }
                    }
                });
            },
            openImage : function(messageId) {
                let url = baseUrl + '/' + this.$getFileUrl('messageFile', messageId, 0, 'thumb');
                window.open(url);
            }
        },
        computed: {
            isComplete () {
                return this.adminsData.txtMessage.trim();
            },
            emojiDataAll() {
                return EmojiAllData;
            },
            emojiGroups() {
                return EmojiGroups;
            }
        },
        mounted() {
            let id = this.$route.params.id;
            this.adminsData.threadId = id;
            this.getMessageThreadData();
            this.getChatThreads();
        },
        watch: {
            // call again the method if the route changes
            '$route': 'getMessageThreadData'
        }        
    }
</script>
<style scoped>
.emoji-list{
   background: red !important;
}

</style>
