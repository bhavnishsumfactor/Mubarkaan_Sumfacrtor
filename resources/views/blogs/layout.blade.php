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
@endphp
@include('layouts.headSection', ['blogs' => true])
<script src="{{ asset('vendors/js/nprogress.min.js') }}"></script>
<script>
        NProgress.configure({ trickleSpeed: 150, showSpinner: false });
</script>
<script src="{{ asset('vendors/js/toastr.min.js') }}"></script>
    @toastr_render
<body class="@if(config('app.localSite') == true){{'demo-header--on'}}@endif">

    @if(config('app.localSite') == true)
    @include('restore-system.header', ["sectionType" =>"desktop"])
    @endif
    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1)
    {!!$configurations['GOOGLE_TAG_MANAGER_BODY_SCRIPT']!!}
    @endif
    <div class="wrapper" id="wrapper" data-yk="">

        @include('blogs.header')
        @include('partials.themeSwitch')
        @yield('content')

        @include('blogs.footer')

        <div id="dataModal" class="modal fade"></div>
    </div>
    <a id="back-top" class=""></a>
    <div class="back-overlay"></div> 

    @include('themes.'.config('theme').'.partials.scripts')
    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: Object.keys(languages).join(','),
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }

        function setLangCookie(langcode) {
            $.removeCookie('googtrans');
            $.cookie('googtrans', null, {
                domain: domain,
                path: '/'
            });
            let tempLang = '/en/' + langcode;
            if (langcode == 'en') {
                tempLang = '/auto/en';
            }
            $.cookie('googtrans', tempLang, {
                domain: domain,
                path: '/',
                expires: 15
            });
        }
        setLangCookie(pageDefaultLanguage);
    </script>
    <script  src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1 && !empty($configurations['GOOGLE_ANALYTICS_ANALYTICS_ID']))
    <script async src="https://www.googletagmanager.com/gtag/js?id={!!$configurations['GOOGLE_ANALYTICS_ANALYTICS_ID']!!}"></script>
    {!!$configurations['GOOGLE_ANALYTICS_SITE_TRACKING_CODE']!!}
    @endif
    <script>
        $(document).ready(function() {
            $('body').removeClass('loading');
            if (window.location.href.indexOf('?') > -1) {
                history.pushState('', document.title, window.location.pathname);
            }
        });
    </script>

    @yield('scripts')

</body>

</html>