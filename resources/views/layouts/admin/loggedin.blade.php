@php
$configurations = getConfigValues([
    'ADMIN_TIMEZONE',
    'ADMIN_DATE_FORMAT',
    'ADMIN_TIME_FORMAT',
    'ADMIN_DARK_MODE_ENABLE',
    'ADMIN_LOGO_RATIO',
    'BUSINESS_NAME',
    'ADMIN_PRIMARY_COLOR',
    'DECIMAL_VALUES',
    'ADMIN_PRIMARY_COLOR_INVERSE',
    'ADMIN_FONT_FAMILY',
    'SYSTEM_MESSAGE_POSITION',
    'GOOGLE_MAPS_API_STATUS',
    'SYSTEM_MESSAGE_STATUS',
    'GOOGLE_MAP_API_KEY',
    'SYSTEM_MESSAGE_CLOSE_TIME',
    'PRODUCT_TYPES',
    'APP_CONTENT_PAGES_ENABLED',
    'INSTALLATION_DATE',
    'MOBILE_DEFAULT_LANGUAGE'
]);
config(['toastr.options.positionClass' => $configurations['SYSTEM_MESSAGE_POSITION']]);
if ($configurations['SYSTEM_MESSAGE_STATUS']=='1') {
    config(['toastr.options.timeOut' => $configurations['SYSTEM_MESSAGE_CLOSE_TIME'] * 1000]);
}
@endphp
<!DOCTYPE html>
<html lang="{{app()->getLocale()}}" data-theme="light" dir="ltr" id="YK-html" data-version="{{config('app.version')}}" data-header-sticky="no">

<head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta id="token" name="csrf-token" value="{{ csrf_token() }}">
    <title>{{ $configurations['BUSINESS_NAME'] ? $configurations['BUSINESS_NAME'] : 'YoKart'}}</title>
    <link rel="shortcut icon" href="{{ getFavicon() }}" />
    <link href="{{ getJsCssPath('admin/css/main-ltr.css') }}" rel="stylesheet" id="YK-mainCss">
    <link href="{{ getJsCssPath('admin/vendors/custom.css') }}" rel="stylesheet">
    <link href="{{ getJsCssPath('admin/vendors/toastr.min.css') }}" rel="stylesheet">
    @yield('css')
    @if(strtolower($configurations['ADMIN_FONT_FAMILY']) == 'mulish')
        <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        @elseif(strtolower($configurations['ADMIN_FONT_FAMILY']) == 'roboto')
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
        @elseif(strtolower($configurations['ADMIN_FONT_FAMILY']) == 'poppins')
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @elseif(cleanSpaces(strtolower($configurations['ADMIN_FONT_FAMILY'])) == 'opensans')
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
        @endif
    <style>
        body{font-family: "{{$configurations['ADMIN_FONT_FAMILY'] ?? 'Poppins'}}", sans-serif !important;}
        :root { 
            --brand-color: {{ !empty($configurations['ADMIN_PRIMARY_COLOR']) ? $configurations['ADMIN_PRIMARY_COLOR'] : '#ff0055'}} !important;
            --brand-color-inverse: {{ !empty($configurations['ADMIN_PRIMARY_COLOR_INVERSE']) ? $configurations['ADMIN_PRIMARY_COLOR_INVERSE'] : '#FFFFFF'}} !important;
        }         
    </style>

    <!-- Scripts -->
    @php
    $lang = 'en'; 
    if(Session::get('language')){
        $lang = Session::get('language')['code'];
    }
    @endphp
    <script src="{{ getJsCssPath('admin/vendors/jquery.min.js') }}"></script>
    <script src="{{ getJsCssPath('/admin/js/lang.js',base_path('resources/lang/' . $lang . '.json')) }}"></script>
        
     
       
    @php $currency = getSystemCurrency();
        $logos = getAdminLogos();
     @endphp 
    
    <script>
    window.localStorage.setItem('timezone', "{{ !empty(Auth::guard('admin')->user()->admin_default_timezone) ? Auth::guard('admin')->user()->admin_default_timezone : $configurations['ADMIN_TIMEZONE'] }}");
    window.localStorage.setItem('dateformat', "{{$configurations['ADMIN_DATE_FORMAT']}}");
    window.localStorage.setItem('timeformat', "{{$configurations['ADMIN_TIME_FORMAT']}}");
        let darkMode = "{{$configurations['ADMIN_DARK_MODE_ENABLE']}}";
        let decimalValues = "{{($configurations['DECIMAL_VALUES'] == 0) ? 0 : 2}}";
        let siteLogo = "{{$logos['lightLogo']}}";
        let darkLogo = "{{$logos['darkLogo']}}";
        let lightLogo = "{{$logos['lightLogo']}}";
        let contentPagesEnabled = "{{ $configurations['APP_CONTENT_PAGES_ENABLED'] }}";
        let appContentPages = '{!!json_encode(appContentPages())!!}';
        let siteLogoRatio = "{{$configurations['ADMIN_LOGO_RATIO']}}";
        let baseUrl = "{{url('/')}}";
        let activeThemeUrl = "{{url(''). '/yokart/' . getActiveThemeSlug()}}";
        let currencySymbol = "{{$currency->currency_symbol}}";
        let googleMapApiKey = "{{ $configurations['GOOGLE_MAP_API_KEY'] }}";
        let currencyCode = "{{$currency->currency_code}}";
        let adminBaseUrl = "{{url('/admin')}}";
        let productType = "{{ $configurations['PRODUCT_TYPES'] }}";
        let installationDate = "{{ $configurations['INSTALLATION_DATE'] ?? '' }}";
        let appDefaultLanguage = "{{ $configurations['MOBILE_DEFAULT_LANGUAGE'] }}";
        let googleMapApiStatus = "{{ $configurations['GOOGLE_MAPS_API_STATUS'] }}";
        window.adminRole = "{{ Auth::guard('admin')->user()->admin_role_id }}";
        window.Laravel = @php echo getUserPermissions(); @endphp;
        window.Laravel.settings = @php echo getGlobalSettings(); @endphp;
        Object.freeze(window.Laravel.permissions);
        
        let pageDefaultLanguage = "{{app()->getLocale()}}";
        let languagesDir = '{!!json_encode(getLanguagesDirection())!!}';
        if (languagesDir['pageDefaultLanguage'] == 'rtl') { 
            $('link#YK-mainCss').attr('href', "{{ getJsCssPath('admin/css/main-rtl.css') }}");
            $('html').attr('dir', 'rtl');
        } 

        $(document).ready(function() {
            /*toggle theme switch*/
            $(document).on('click', '.YK-themeSwitch', function(e){
                if ($(this).hasClass("light")) {
                    $(this).removeClass('light').addClass('dark');
                    $('html').attr('data-theme', 'dark');
                } else {
                    $(this).removeClass('dark').addClass('light');
                    $('html').attr('data-theme', 'light');
                }            
            });
            $(document).on('click', '.yk-closemessageTab', function(e){
                $(".quick-panel__close").click();
            });
        }); 
    </script>
</head>

 <body
    class="page--loading-enabled demo-panel--right offcanvas-panel--right header--fixed header--minimize-menu header-mobile--fixed subheader--enabled subheader--transparent"> 
    
    <div class="loading-1 loading YK-loader">
        
    </div>
    @if(config('app.localSite') == true)
    @include('restore-system.header', ["sectionType" =>"admin"])
    @endif
        <div class="wrapper" id="app">
            <App> </App>
            @include('admin.partials.topNav')
            <!-- end:: Header -->
            <div class="body">
                <div class="content">

                    @yield('content')

                </div>
            </div>
    
            <!-- begin:: Footer -->
            <footer class="footer" id="footer">
                <div class="container">
                    <div class="row footer__wrapper justify-content-center align-items-center">
                        <div class="col-auto">
                            <div class="footer__copyright">Copyright @ {{date('Y')}} {{config('app.version')}}</div>
                        </div>
                    </div>
                </div>
            </footer>       
        </div>
    <!-- end:: Footer -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset('admin/vendors/stellarnav.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/scroll-hint.min.js') }}"></script>
    <script src="{{ asset('vendors/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ url(mix('js/build/app.js', 'admin')).'?v='.config('app.version')}}"></script>
    <script src="{{ asset('admin/vendors/ui-functions.js') }}"></script>
    <script src="{{ asset('admin/vendors/toastr.min.js') }}"></script> 
    @toastr_render
    <script>
        $(document).on("scroll", function() {
            var height = $(window).scrollTop();
            if(height  > 35) {
                $("#YK-html").attr('data-header-sticky','yes');
            } else{
                $("#YK-html").attr('data-header-sticky','no');
            }
        });
        $(document).on("click", ".modal-backdrop", function() { 
            $(".modal").removeClass('show');
        });        
        $('.YK-loader').css('display', 'none');
        jQuery(document).ready(function($) {
            jQuery('.stellarnav').stellarNav({
                position: 'left',
                breakpoint: 1024
                
            });
        });
    </script>
    <style>
       
        .tox-notifications-container{display: none;}
        div.vue-treeselect__tip.vue-treeselect__no-children-tip{display:none;}
        button.toast-close-button:focus{outline:none !important;}
    </style>
    @if(config('app.localSite') == true)
    @include('restore-system.index')
@endif
</body>

</html>
