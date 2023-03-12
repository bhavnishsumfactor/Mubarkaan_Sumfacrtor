@php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

$configurations = getConfigValues([
'ENABLE_301_REDIRECTION',
'FACEBOOK_PIXEL_ID',
'GOOGLE_TAG_MANAGER_HEAD_SCRIPT',
'GOOGLE_TAG_MANAGER_BODY_SCRIPT',
'SCHEMA_CODES_DEFAULT_SCRIPT',
'SYSTEM_MESSAGE_STATUS', 'SYSTEM_MESSAGE_CLOSE_TIME',
'BUSINESS_NAME',
'GOOGLE_ANALYTICS_SITE_TRACKING_CODE',
'GOOGLE_ANALYTICS_ANALYTICS_ID',
'SYSTEM_MESSAGE_POSITION',
'FRONTEND_FONT_FAMILY',
'PRIMARY_COLOR',
'PRIMARY_COLOR_INVERSE',
'ACCEPT_COOKIE_ENABLE',
'ACCEPT_COOKIE_TEXT',
'ENABLE_ADVANCED_COOKIE_PREFERENCES',
'FUNCTIONAL_COOKIE_TEXT',
'ADVANCED_PREFERENCES_COOKIE_TEXT',
'STATISTICAL_ANALYSIS_COOKIE_TEXT',
'PERSONALIZE_COOKIE_TEXT',
'ADVERTISING_SOCIAL_MEDIA_COOKIE_TEXT',
'BACKGROUND_COLOR_ENABLED',
'LIVE_CHAT_CODE', 'LIVE_CHAT_STATUS'
]);
$acceptCookie = config('acceptcookie');
@endphp
@include('layouts.headSection')


<body class="@if(config('app.localSite') == true){{'demo-header--on'}}@endif">

    @if(config('app.localSite') == true)
    @include('restore-system.header', ["sectionType" =>"desktop"])
    @endif
    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1)
    {!!$configurations['GOOGLE_TAG_MANAGER_BODY_SCRIPT']!!}
    @endif

    <div class="orientation-landscape"> 
    
        <img class="orientation-landscape_img" src="{{ asset('yokart/' . config('theme') . '/media/retina/mobile-portrait-mode.svg')}}" alt="">
        <div class="orientation-landscape_message">Please rotate your device to portrait mode</div>
    </div>
    <div class="wrapper" id="wrapper" data-yk="">
        @if(url()->current() != route('mobileSite'))
        {!!getHeader()!!}
    @endif
    @include('partials.themeSwitch')
        @yield('content')
        @if(url()->current() != route('mobileSite'))
        {!!getFooter()!!}
        @endif
        
        
        <div id="dataModal" class="modal fade"></div>
    </div>
    <a id="back-top" class=""></a>
    <div class="back-overlay"></div>
    @include('themes.'.config('theme').'.partials.scripts')
    @include('partials.googleTranslate')
    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1 &&
    !empty($configurations['GOOGLE_ANALYTICS_ANALYTICS_ID']))
    <script async
        src="https://www.googletagmanager.com/gtag/js?id={!!$configurations['GOOGLE_ANALYTICS_ANALYTICS_ID']!!}">
    </script>
    {!!$configurations['GOOGLE_ANALYTICS_SITE_TRACKING_CODE']!!}
    @endif

    <script>
    var _tooltip = jQuery.fn.tooltip;

    jQuery.fn.tooltip = _tooltip;

    NProgress.configure({
        trickleSpeed: 150,
        showSpinner: false
    });
    $(document).ready(function() {
        $('body').removeClass('loading');

        $('[role="tooltip"]').on('click mousedown mouseup', function() {
            $(this).trigger("mouseleave");
        });
        $('.yk-perfectScrollbar').each(function() {
            new PerfectScrollbar($(this)[0]);
        });
    });
    </script>
    {{--<script src="{{ asset('vendors/js/toastr.min.js') }}"></script>
    <script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>--}}

    {{--<link href="{{ asset('vendors/css/intlTelInput.min.css')}}" rel="stylesheet" type="text/css">--}}

    {{--<script src="{{ asset('vendors/js/intlTelInput.min.js') }}"></script>
    <script src="{{asset('vendors/js/jquery.inputmask.js')}}"></script>--}}

    @toastr_render
    @yield('scripts')
    @if(config('app.localSite') == true)
    @include('restore-system.index')
    @endif
    @if($configurations['LIVE_CHAT_STATUS'])
    {!!$configurations['LIVE_CHAT_CODE']!!}
    @endif
    @include('partials.acceptCookies')
</body>

</html>
