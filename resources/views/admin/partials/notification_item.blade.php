@foreach($notifications as $notification)
<a href="javascript:;" attr-data-id="{{$notification->notify_id}}" class="notification__item redirectNotification @if($notification->nta_read == 1) notification__item--read @endif">
    <div class="notification__item-icon">
        @if($notification->notify_type == 1)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#order-review')}}"></use></svg></i>
        @elseif($notification->notify_type == 2)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#order-review')}}"></use></svg></i>
        @elseif($notification->notify_type == 3)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#order-by-user')}}"></use></svg></i>
        @elseif($notification->notify_type == 4)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#order-by-admin')}}"></use></svg></i>
        @elseif($notification->notify_type == 5)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#order-cancellation')}}"></use></svg></i>
        @elseif($notification->notify_type == 6)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#order-partial-cancellation')}}"></use></svg></i>
        @elseif($notification->notify_type == 7)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#order-return')}}"></use></svg></i>
        @elseif($notification->notify_type == 8)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#order-partial-return')}}"></use></svg></i>
        @elseif($notification->notify_type == 9)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#order-payment')}}"></use></svg></i>
        @elseif($notification->notify_type == 10)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#order-comment')}}"></use></svg></i>
        @elseif($notification->notify_type == 11)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#order-cancel-comment')}}"></use></svg></i>
        @elseif($notification->notify_type == 12)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#order-return-comment')}}"></use></svg></i>
        @elseif($notification->notify_type == 13)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#blog-comment')}}"></use></svg></i>
        @elseif($notification->notify_type == 14)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#user-registration')}}"></use></svg></i>
        @elseif($notification->notify_type == 15)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#newsletter-subscription')}}"></use></svg></i>
        @elseif($notification->notify_type == 16)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#gdpr-data-request')}}"></use></svg></i>
        @elseif($notification->notify_type == 17)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#gdpr-delete-request')}}"></use></svg></i>
        @elseif($notification->notify_type == 18)
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#to-do-reminder')}}"></use></svg></i>
        @else
            <i class="svg--icon"><svg class="svg"><use xlink:href="{{asset('admin/images/notification-icons/sprite.svg#newsletter-subscription')}}"></use></svg></i>
        @endif
    </div>
    <div class="notification__item-details">
        <div class="notification__item-title">
            {{$notification->notify_msg}}
        </div>
        <div class="notification__item-time">
            {{\Carbon\Carbon::createFromTimeStamp(strtotime($notification->notify_created_at))->diffForHumans()}}
        </div>
    </div>
    <div class="YK-notification-actions">
        <span href="javascript:;" title="" attr-data-id="{{$notification->notify_id}}" attr-data-val="1" class="btn btn-light btn-sm btn-icon YK-readUnreadNoti @if($notification->nta_read == 1) d-none @endif"><i class="fas fa-envelope-open"></i> </span>
        <span href="javascript:;" title="" attr-data-id="{{$notification->notify_id}}" attr-data-val="0" class="btn btn-light btn-sm btn-icon YK-readUnreadNoti @if($notification->nta_read == 0) d-none @endif"><i class="fas fa-envelope"></i></span>
	</div>
</a>
@endforeach