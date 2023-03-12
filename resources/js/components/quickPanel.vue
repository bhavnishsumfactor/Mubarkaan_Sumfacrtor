<template>
    <div id="quick-panel" class="quick-panel" data-close-on-click-outside="quick-panel">
        <button class="btn btn-sm btn-light btn-icon quick-panel__close" id="quick-panel__close" data-target-close="quick-panel"><i class="fas fa-times"></i></button>
        <div class="quick-panel__nav">
            <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold notification-item-padding-x" role="tablist">
                <li class="nav-item active">
                    <a @click="getTodos(0)" class="nav-link active" data-toggle="tab" href="#quick_panel_tab_todo-section" role="tab">{{$t('LNK_TODO_LIST')}}  <span class="badge badge--success" v-if="todoCount>0">{{todoCount}}</span></a>
                </li>
                <li class="nav-item">
                    <a @click="getNotifications(0)" class="nav-link" data-toggle="tab" href="#quick_panel_tab_notifications" role="tab">{{$t('LNK_NOTIFICATIONS')}} <span class="badge badge--success" v-if="notificationsUnread>0">{{ (notificationsUnread > 99) ? '99+' : notificationsUnread }}</span></a>
                </li>
                <li @click="getChatMessages(0)" class="nav-item" v-if="$canRead('MESSAGES')" v-bind:class="[(loadingChat) ? 'disabledbutton': '']">
                    <a class="nav-link" data-toggle="tab" href="#quick_panel_tab_chat" role="tab">{{$t('LNK_CHAT')}} <span class="badge badge--success" v-if="messageCount>0">{{messageCount}}</span></a>
                </li>
            </ul>
        </div>
        <div class="quick-panel__content">
            <div class="tab-content">
                <div class="tab-pane fade active show" id="quick_panel_tab_todo-section" aria-labelledby="todo-section">
                    <div class="add-items d-flex px-4 mb-0">
                        <form class="form w-100" v-on:submit.prevent="createTodo">
                            <div class="row no-gutters">
                                <div class="col">
                                    <div class="input-icon">
                                        <vue-tribute :options="todoOptions" >
                                            <input class="form-control" type="text" v-model="todo_description" placeholder="@..."/>
                                        </vue-tribute>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-brand btn-icon todo-list-add-btn ml-2 YK-addTodoBtn" :disabled="todo_description == '' "><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="list-wrapper px-4" v-if="todosTotal > 0">
                        <perfect-scrollbar :style="'max-height: '+todoDataHeight+'px'" @scroll="onScrollTodo">
                            <ul class="todo-list YK-todos" style="height: 100%;"></ul>
                        </perfect-scrollbar>
                    </div>
                    <div class="no-data-found" v-if="loadingTodo == false && todosTotal == 0">
                        <div class="img">
                            <img :src="activeThemeUrl+'/media/retina/empty/no-messages.svg'" width="125px" height="125px" />
                        </div>
                        <div class="data">
                            <h5>{{ $t('LBL_NO_TODOS') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="quick_panel_tab_notifications">
                    <div class="list-wrapper px-4">
                        <perfect-scrollbar :style="'max-height: '+notificationDataHeight+'px'" @scroll="onScrollNotification">
                            <div class="notification YK-notifications" style="height: 100%;"></div>
                        </perfect-scrollbar>
                    </div>
                    <div class="p-3 text-center"><a href="javascript:;" class="btn btn-outline-secondary btn-sm readAllNotifications" v-if="notificationsTotal>0" @click="readAllNotifications()">{{$t('Read All')}}</a></div>
                    <div class="no-data-found" v-if="loadingNotification == false && notificationsTotal == 0">
                        <div class="img">
                            <img :src="activeThemeUrl+'/media/retina/empty/no-messages.svg'" width="125px" height="125px" />
                        </div>
                        <div class="data">
                            <h5>{{ $t('There are no notifications yet') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade pl-3 pr-3" id="quick_panel_tab_chat" v-if="$canRead('MESSAGES')">
                    <div class="form-group">
                        <div class="input-icon input-icon--left">
                            <input type="text" class="form-control" :readonly="chatsearch == '' && messagesData.length == 0" v-model="chatsearch" @keyup="searchChatRecords" :placeholder="$t('PLH_SEARCH_CHAT')" aria-describedby="basic-addon1"> 
                        <span class="input-icon__icon input-icon__icon--left"><span><i class="la la-search"></i></span></span>                        
                        <span class="input-icon__icon input-icon__icon--right" v-if="chatsearch!=''" @click="clearChatSearch()">
                            <span><i class="fas fa-times"></i></span>
                        </span>
                        </div>
                    </div>
                    <perfect-scrollbar v-if="messagesData.length > 0" :style="'max-height: '+chatMessageHeight+'px'" @scroll="onScrollChat">
                        <div class="widget widget--users">
                            <div class="widget__items">
                                <div v-for="(record, index) in messagesData" :key="record.thread_id + 'message'">
                                    <router-link  class="widget__item yk-closemessageTab"  :to="{name: 'threadMessages', params: { id: record.thread_id }}" data-target-close="quick-panel">
                                        <span class="media media--circle">
                                            <img :src="$getFileUrl('userProfileImage', record.thread_started_by, 0, '60-60') " width="60">
                                            <i class="badge badge--success" v-if="record.get_admin_unread_message_count > 0">{{ record.get_admin_unread_message_count }}</i>
                                        </span>
                                        <div class="widget__info">
                                            <div class="widget__section">
                                                <a href="#" class="widget__username">{{ record.user_name }} </a>
                                                <span class="badge badge--success badge--dot"></span>
                                            </div>
                                            <span class="widget__desc">
                                                {{ record.message_text.length > 50 ? record.message_text.substring(0,50)+"...." : record.message_text }}
                                            </span>
                                             
                                        </div>
                                        <div class="widget__action">
                                            <div class="widget__date"><span>{{ record.thread_created_at | formatDate }}</span><span class="widget__time">{{ record.thread_created_at | formatTime }}</span></div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                            <loader v-if="loadingChat" :removePortlet="true"></loader>
                        </div>
                    </perfect-scrollbar>
                    <div class="no-data-found" v-if="loadingChat == false && messagesData.length == 0">
                        <div class="img">
                            <img :src="activeThemeUrl+'/media/retina/empty/no-messages.svg'" width="125px" height="125px" />
                        </div>
                        <div class="data">
                            <h5>{{ $t('LBL_NO_CHAT') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <b-modal id="todoReminderModal" centered title="BootstrapVue">
                <template v-slot:modal-header>
                    <h5 class="modal-title" id="exampleModalLabel">{{todo_Desc}}</h5>
                    <button type="button" class="close" @click="$bvModal.hide('todoReminderModal')"></button>
                </template>
                <div class="form-group row">
                    <div class="col-md-12">
                        <date-pick v-model="todo_reminder_at" :format="'YYYY-MM-DD HH:mm'" :hasInputElement="false" :pickTime="true" :isDateDisabled="isPastDate" :inputAttributes="{class: 'form-control', readonly: true}" class="d-block"></date-pick>
                        <span v-if="errors.first('todo_reminder_at')" class="error text-danger">{{ errors.first('todo_reminder_at') }}</span>
                    </div>
                </div>
                <template v-slot:modal-footer>
                    <button type="button" class="btn btn-brand YK-setReminderBtn" @click="setReminderForTodo()">{{ $t('BTN_SET_REMINDER')}}</button>
                </template>
            </b-modal>
        </div>
    </div>
</template>
<script>
    import DatePick from 'vue-date-pick';
    import 'vue-date-pick/dist/vueDatePick.css';
    import VueTribute from 'vue-tribute'
    export default {
        components: {
            DatePick,
            VueTribute
        },
        data: function() {
            return {
                notifications: [],
                messagesData: [],
                chatsearch: '',
                notificationsTotal: 0,
                notificationsUnread: '',
                notificationsPending: 0,
                baseUrl: baseUrl,
                activeThemeUrl: activeThemeUrl,
                notificationsRow: 0,
                todos: [],
                todosRow: 0,
                todosTotal: 0,
                todosPending: 0,
                chatMessageHeight: 0,
                paginateCount: 0,
                todo_description: '',
                todo_reminder_at: '',
                todo_id: '',
                todo_Desc: '',
                todo_reminder_email: '',
                todo_reminder_sms: '',
                todoDataHeight: 0,
                notificationDataHeight: 0,
                loadingTodo: false,
                loadingChat: false,
                loadingNotification: false,
                todoCount:0,
                messageCount:0,
                totalThreads:0,
                threadRow: 0,
                threadPending:0,
                messagePaginateCount:0,
                threadsRow:0,
                loadMore: "Load More",
                todoOptions: {
                    values: []
                },
            }
        },
        methods: {
            getTodos: function(rowVal) {
                let thisObj = this;
                this.loadingTodo = true;
                if (typeof rowVal != 'undefined') {
                    this.todosRow = rowVal;
                }
                this.$http.get(adminBaseUrl + '/todos/' + this.todosRow).then((response) => {
                    this.todosTotal = response.data.data.total;
                    this.loadingTodo = false;
                    setTimeout(() => {
                        if (typeof rowVal != 'undefined') {
                            $('.YK-todos').html('');
                        }
                        $('.YK-todos').append(response.data.data.todos);
                        thisObj.todo_reminder_email = response.data.data.configurations.TODO_REMINDER_EMAIL;
                        thisObj.todo_reminder_sms = response.data.data.configurations.TODO_REMINDER_SMS;
                        thisObj.todoCount = response.data.data.todoCount;
                        thisObj.paginateCount = response.data.data.paginate_count;
                        thisObj.todosRow = Number(thisObj.todosRow) + thisObj.paginateCount;
                        thisObj.todosPending = thisObj.todosTotal - thisObj.todosRow;
                        thisObj.todoOptions.values = response.data.data.adminUsers;
                    }, 100);
                });
            },            
            onScrollTodo ({ target: { scrollTop, clientHeight, scrollHeight }}) {
                if (this.loadingTodo === false && ((scrollTop + clientHeight + 100) >= scrollHeight) && this.todosPending>0) {
                    this.getTodos();
                }
            },
            completeTodo: function(todoId, status) {
                let formData = this.$serializeData({
                    'todo_status': status
                });
                this.$http.post(adminBaseUrl + '/todos/complete/' + todoId, formData).then((response) => {
                    this.todoCount = response.data.data.todoCount;
                });
            },
            removeTodo: function(todoId) {
                this.$http.delete(adminBaseUrl + '/todos/' + todoId).then((response) => {
                    this.getTodos(0);
                });
            },
            createTodo: function() {
                if (this.todo_description.trim() == '') {
                    toastr.error(this.$t('NOTI_EMPTY_TODO'), '', toastr.options);
                    return;
                }
                let formData = this.$serializeData({
                    'todo_description': this.todo_description
                });
                this.$http.post(adminBaseUrl + '/todos', formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.getTodos(0);
                    this.todo_description = '';
                });
            },
            isPastDate: function(date) {
                const currentDate = new Date().setDate(new Date().getDate() - 1);
                return date < currentDate;
            },
            openTodoReminderPopup: function(todoId, todoDesc) {
                this.todo_reminder_at = moment(new Date()).format('YYYY-MM-DD HH:mm');
                this.todo_id = todoId;
                this.todo_Desc = todoDesc;
                this.$bvModal.show('todoReminderModal');
            },
            setReminderForTodo: function() {
                $('.YK-setReminderBtn').addClass('spinner spinner--sm spinner--right spinner--light');
                let formData = this.$serializeData({
                    'todo_reminder_at': this.todo_reminder_at
                });
                this.$http.post(adminBaseUrl + '/todos/reminder/' + this.todo_id, formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                    this.getTodos(0);
                    $('.YK-setReminderBtn').removeClass('spinner spinner--sm spinner--right spinner--light');
                    this.$bvModal.hide('todoReminderModal');
                    this.todo_reminder_at = '';
                    this.todo_id = this.todo_Desc = '';
                });
            },
            getNotifications: function(rowVal) {                
                this.loadingNotification = true;
                if (typeof rowVal != 'undefined') {
                    this.notificationsRow = rowVal;
                }
                this.$http.get(adminBaseUrl + '/get-notifications/' + this.notificationsRow).then((response) => {
                    this.loadingNotification = false;
                    if (typeof rowVal != 'undefined') {
                        $('.YK-notifications').html('');
                    }
                    $('.YK-notifications').append(response.data.data.notifications);
                    this.notificationsUnread = response.data.data.unread;
                    this.notificationsTotal = response.data.data.total;                    
                    this.notificationsRow = Number(this.notificationsRow) + this.paginateCount;
                    this.notificationsPending = this.notificationsTotal - this.notificationsRow;
                });
            },
            onScrollNotification ({ target: { scrollTop, clientHeight, scrollHeight }}) {
                if (this.loadingNotification === false && ((scrollTop + clientHeight + 100) >= scrollHeight) && this.notificationsPending>0) {
                    this.getNotifications();
                }
            },
            redirectNotification: function(notify_id) {
                $(this).closest('.YK-notification-actions').find('span[attr-data-val="1"]').addClass('d-none');
                $(this).closest('.YK-notification-actions').find('span[attr-data-val="0"]').removeClass('d-none');
                $(this).closest('a').addClass('notification__item--read');
                this.$http.get(adminBaseUrl + '/notifications/redirect/' + notify_id).then((response) => {
                    let path = '';
                    if(response.data.data.notify_type == 1 || response.data.data.notify_type == 2){
                        path = '/product-reviews/'+response.data.data.notify_record_id+'/detail';
                    }else if(response.data.data.notify_type == 14){
                        path = '/users';
                    }else if(response.data.data.notify_type == 16 || response.data.data.notify_type == 17){
                        path = '/user/requests';
                    }else if(response.data.data.notify_type == 13){
                        path = '/blog/comments/'+response.data.data.notify_record_id+'/view';
                    }else if(response.data.data.notify_type == 11 || response.data.data.notify_type == 12){
                        path = '/order/return/'+response.data.data.notify_record_id+'/'+ response.data.data.notify_record_subid;
                    }else if(response.data.data.notify_type == 3 || response.data.data.notify_type == 4 || response.data.data.notify_type == 5 || response.data.data.notify_type == 6 || response.data.data.notify_type == 7 || response.data.data.notify_type == 8 || response.data.data.notify_type == 9 || response.data.data.notify_type == 10){
                        path = '/order/'+response.data.data.notify_record_id;
                    } else {
                        this.getNotifications(0);
                    }
                    if (this.$route.path !== path) {
                        this.$router.push(path);
                    }
                    $('#quick-panel__close').trigger('click');
                });
            },
            readAllNotifications: function() {
                this.$http.get(adminBaseUrl + '/notifications/status').then((response) => {
                    this.getNotifications(0);
                });
            },            
            onScrollChat ({ target: { scrollTop, clientHeight, scrollHeight }}) {
                if (this.loadingChat === false && ((scrollTop + clientHeight + 80) >= scrollHeight) && this.chatsearch == '') {
                    this.infiniteChatScrollHandler();
                }
            },
            infiniteChatScrollHandler: function() {
                this.loadingChat =true;
                let formData = this.$serializeData({
                    total: this.messagesData.length,
                    search: this.chatsearch,
                    page: this.page
                });
                this.$http.post(adminBaseUrl + '/messages/chat-threads', formData).then((response) => {
                    this.loadingChat = false;
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    if (response.data.data.message.length) { 
                        this.messagesData.push(...response.data.data.message);
                    }
                });
            },
            searchChatRecords: function() {
                this.getChatMessages();
            },
            getChatMessages: function(rowVal) {
                this.loadingChat = true;
                let formData = this.$serializeData({
                    total: 0,
                    search: this.chatsearch
                });
                this.$http.post(adminBaseUrl + '/messages/chat-threads', formData).then((response) => {
                    this.loadingChat = false;
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.messagesData = response.data.data.message;
                    this.messageCount = response.data.data.threadUnreadCount;
                });
            },
            clearChatSearch : function() {
                this.chatsearch = '';
                this.getChatMessages();
            },
            getChatThreadMessage(threadId){
                this.$router.push('/thread/'+threadId+'/messages');
            },
            destroy() {
                this.$destroy();
            }
        },
        mounted() {
            this.$root.$on('reloadQuickPanel', (data) => {
                thisObj.getTodos(0);
                thisObj.getNotifications(0);
                thisObj.getChatMessages(0);
            });
            let screenHeight = $( window ).height();
            this.todoDataHeight = screenHeight - 150;
            this.notificationDataHeight = screenHeight - 150;
            this.chatMessageHeight = screenHeight - 200;
            let thisObj = this;
            $(document).on('click', '.redirectNotification', function() {
                thisObj.redirectNotification($(this).attr('attr-data-id'));
            });
            $(document).on('click', '.YK-readUnreadNoti', function(event) {
                event.stopPropagation();
                let nta_read = $(this).attr('attr-data-val');
                let $this = $(this);
                let notify_id = $(this).attr('attr-data-id');
                thisObj.$http.get(adminBaseUrl + '/notifications/read-unread/'+notify_id+'/'+nta_read).then((response) => {      
                    if(nta_read == 1){
                        $this.closest('.YK-notification-actions').find('span[attr-data-val="1"]').addClass('d-none');
                        $this.closest('.YK-notification-actions').find('span[attr-data-val="0"]').removeClass('d-none');
                        $this.closest('a').addClass('notification__item--read');
                    }else{
                        $this.closest('.YK-notification-actions').find('span[attr-data-val="1"]').removeClass('d-none');
                        $this.closest('.YK-notification-actions').find('span[attr-data-val="0"]').addClass('d-none');
                        $this.closest('a').removeClass('notification__item--read');
                    }            
                    
                });
            });
            $(document).on('change', '.YK-completeTodo input[type="checkbox"]', function() {
                if ($(this).is(":checked")) {
                    thisObj.completeTodo($(this).closest('li').attr('attr-data-id'), 1);
                    $(this).closest('li').addClass('completed');
                    $(this).attr('checked');
                } else {
                    thisObj.completeTodo($(this).closest('li').attr('attr-data-id'), 0);
                    $(this).closest('li').removeClass('completed');
                    $(this).removeAttr('checked');
                }
            });
            $(document).on('click', '.YK-removeTodo', function() {
                thisObj.removeTodo($(this).closest('li').attr('attr-data-id'));
                $(this).closest('li').remove();
            });
            $(document).on('click', '.YK-reminderTodo', function() {
                thisObj.openTodoReminderPopup($(this).closest('li').attr('attr-data-id'), $(this).closest('li').attr('attr-data-desc'));
            });
            $(document).on('mouseenter', '.YK-todoItem', function() {
                $(this).find('.YK-reminderTodo').show();
            });
            $(document).on('mouseleave', '.YK-todoItem', function() {
                $(this).find('.YK-reminderTodo').hide();
            });
            $(document).on('click', '#todoReminderModal .modal-content', function() {
                $('body').addClass('quick-panel--on');
            });
        }
    }
</script>