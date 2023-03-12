<template>
    <router-link :to="{name: 'editProfile'}" class="notification__item"><div class="notification__item-icon">
        <i class="flaticon2-calendar-3 font-success"></i>
    </div>
    <div class="notification__item-details">
        <div class="notification__item-title font-bold">
            {{$t('My Profile')}}
        </div>
        <div class="notification__item-time">
            {{$t('Account settings and more')}}
        </div>
    </div></router-link>
</template>
<script>
    export default {
        
    }
</script>
