@if (auth()->guest())
    <a href="javascript:;" class="YK-checkLogin">
        <i class="icn">
            <svg class="svg">
                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#user-icon" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#user-icon"></use>
            </svg>
        </i>
        <span class="icn-txt">{{__('LBL_LOGIN')}}</span>
    </a>
@else
<a href="#" class="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <div class="header-account">
            @php  $checkFileExists = fileExists('userProfileImage', Auth::user()->user_id, 0); @endphp
                @if($checkFileExists)
                    <figure class="user-avatar"><img data-yk="" src="{{ getFileUrl('userProfileImage', Auth::user()->user_id, 0, '32-32') }}" class="YK-userAvatar" alt="" data-ratio="1:1"></figure>
                @else
                    <figure class="user-avatar"><img data-yk="" src="{{  url('yokart/'.config('theme')).'/media/retina/admin-profile-user-icon.svg' }}" class="YK-userAvatar" alt="" data-ratio="1:1"></figure>
                @endif
    </div>
</a>
<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim header-account-dropdown">
    <ul class="nav nav--block">
        <li class="login-user">
            <span class="user-title">
                <span class="hello">{{__("LBL_HELLO")}}</span>
                <span class="username">{{ Auth::user()->user_fname }}</span>
            </span>
            <a class="" href="{{ route('logout') }}"> <span class="">
                <i class="icn">
                 <svg class="svg"><use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#logout" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#logout"></use></svg>            </i></span></a>
        </li>
        <li class="nav__item">
            <a class="nav__link" href="{{route('userOrders')}}"> <span class="nav__link-text"> {{ __('LBL_ORDERS_AND_RETURNS') }} </span></a>
        </li>
        <li class="nav__item">
            <a class="nav__link" href="{{route('Userfavorite')}}"> <span class="nav__link-text">{{ __('LBL_FAVORITES') }}</span></a>
        </li>
        <li class="nav__item">
            <a class="nav__link" href="{{route('reviews')}}"> <span class="nav__link-text">{{ __('LBL_REVIEWS') }}</span></a>
        </li>
        <li class="nav__item">
            <a class="nav__link" href="{{route('messages')}}"> <span class="nav__link-text">{{ __('LBL_INBOX') }}</span></a>
        </li>
        <li class="nav__item">
            <a class="nav__link" href="{{route('userProfile')}}"> <span class="nav__link-text">{{ __('LBL_PROFILE') }}</span></a>
        </li>        
    </ul>
</div>
@endif
