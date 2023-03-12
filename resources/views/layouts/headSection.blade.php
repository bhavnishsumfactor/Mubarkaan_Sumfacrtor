@php
$currentUrl = request()->path();

$newUrl = newUrl($currentUrl);

if (!empty($newUrl) && $newUrl != $currentUrl){
if ($configurations['ENABLE_301_REDIRECTION'] == '1') {
header('location: ' . url('') . '/' . $newUrl);
die;
}
$currentUrl = $newUrl;
}
config(['toastr.options.positionClass' => $configurations['SYSTEM_MESSAGE_POSITION']]);
if ($configurations['SYSTEM_MESSAGE_STATUS']=='1') {
config(['toastr.options.timeOut' => $configurations['SYSTEM_MESSAGE_CLOSE_TIME'] * 1000]);
}

$language = request()->session()->get('language');
@endphp
<!DOCTYPE html>
<html lang="{{$language['code']}}" data-theme="light" dir="{{$language['direction']}}" data-version="{{config('app.version')}}" prefix="og: http://ogp.me/ns#" data-header-sticky="yes">

<head>
    <meta charset="utf-8" />
    @php
    $record_id = ($record_id ?? null);
    $metatags = getMetaTags($record_id);
    @endphp

    @if(@$page_id == 1)
        <title>{{ @$metatags->meta_title ? @$metatags->meta_title : $configurations['BUSINESS_NAME']}}</title>
    @else
        <title>{{ @$metatags->meta_title ? @$metatags->meta_title . ' - '  : ''}} {{ $configurations['BUSINESS_NAME'] ?? '' }}</title>
    @endif
    
    <meta name="description" content="{{$metatags->meta_description ?? ''}}" />
    <meta name="keywords" content="{{$metatags->meta_keywords ?? ''}}" />
    <meta name="apple-mobile-web-app-title" content="{{$metatags->meta_title ?? ''}}">

    {!!$metatags->meta_other_meta_tags ?? ''!!}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{$configurations['PRIMARY_COLOR']}}">
    @if(Request::isSecure())
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif
    <!-- Tribe -->
    <link rel="canonical" href="{{ url('') . '/' . $currentUrl }}" />
    <link rel="shortcut icon" href="{{ getFavicon() }}" />
    @yield('metaTags')

    <link rel="apple-touch-icon" href="{{url('image/apple-touch-icon/frontendLogo/0/0')}}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{url('image/apple-touch-icon/frontendLogo/0/0/57-57')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{url('image/apple-touch-icon/frontendLogo/0/0/60-60')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{url('image/apple-touch-icon/frontendLogo/0/0/72-72')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{url('image/apple-touch-icon/frontendLogo/0/0/76-76')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{url('image/apple-touch-icon/frontendLogo/0/0/114-114')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{url('image/apple-touch-icon/frontendLogo/0/0/120-120')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{url('image/apple-touch-icon/frontendLogo/0/0/144-144')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{url('image/apple-touch-icon/frontendLogo/0/0/152-152')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{url('image/apple-touch-icon/frontendLogo/0/0/180-180')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{url('yokart/image/favicon/0/0/192-192')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{url('yokart/image/favicon/0/0/32-32')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{url('yokart/image/favicon/0/0/96-96')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('yokart/image/favicon/0/0/16-16')}}">
    <link rel="manifest" href="{{route('pwaManifest')}}">


    @if(strtolower($configurations['FRONTEND_FONT_FAMILY']) == 'mulish')
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @elseif(strtolower($configurations['FRONTEND_FONT_FAMILY']) == 'roboto')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @elseif(strtolower($configurations['FRONTEND_FONT_FAMILY']) == 'poppins')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @elseif(cleanSpaces(strtolower($configurations['FRONTEND_FONT_FAMILY'])) == 'opensans')
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    @endif
    <style>
    body{font-family: "{{$configurations['FRONTEND_FONT_FAMILY']}}", sans-serif !important;}
    :root { 
        --brand-color: {{ !empty($configurations['PRIMARY_COLOR']) ? $configurations['PRIMARY_COLOR'] : '#ff0055'}} !important;
        --brand-color-inverse: {{ !empty($configurations['PRIMARY_COLOR_INVERSE']) ? $configurations['PRIMARY_COLOR_INVERSE'] : '#FFFFFF'}} !important;
    }
    .iti__flag {background-image: url({{asset('yokart/'.config('theme').'/images/flags.png')}});}
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .iti__flag {       background-image: url({{asset('yokart/'.config('theme').'/images/flags@2x.png')   }});}
        }
    </style>

    @if(isset($dashboard))
    <link href="{{ getJsCssPath('dashboard/css/main-' . $language['direction'] . '.css')  }}" type="text/css" rel="stylesheet">
    @elseif(isset($blogs))
    <link href="{{ getJsCssPath('blogs/css/main-' . $language['direction'] . '.css') }}" type="text/css" rel="stylesheet">
    @else
    <link href="{{ getJsCssPath('yokart/'.config('theme').'/css/main-' . $language['direction'] . '.css') }}" type="text/css" rel="stylesheet">
    @endif

    <link href="{{ getJsCssPath('vendors/css/combined.css') }}" rel="stylesheet" type="text/css">
    {{--<link href="{{ getJsCssPath('vendors/css/dev.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ getJsCssPath('vendors/css/toastr.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ getJsCssPath('vendors/css/perfect-scrollbar.css') }}" rel="stylesheet" type="text/css">--}}
    @yield('css')

    <script src="{{ getJsCssPath('vendors/js/jquery.min.js') }}"></script>
    <script src="{{ getJsCssPath('vendors/js/jquery.cookie.min.js') }}"></script>
    <script>
        let currentUrl = "{{url()->current()}}";
        let baseUrl = "{{url('/')}}";
        @php
        preg_match('#[^\.]+[\.]{1}[^\.]+$#', $_SERVER['SERVER_NAME'], $matches);
        @endphp
        let domain = "{{ $matches[0] ?? 'localhost' }}";
        let searchTerm = "{{request()->s}}";
        let languages = '{!!json_encode(getLanguages())!!}';
        let userDefaultFlag = Object.keys(languages)[0];
        let productBackground = '{{$configurations["BACKGROUND_COLOR_ENABLED"]}}';
        let pageDefaultLanguage = "{{$language['code']}}";
        let systemPrefix = "{{config('systemPrefix')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var twoCheckoutSellerId = "{{  getTwoCheckoutConfig()}}";
        
        if (typeof $.cookie(systemPrefix + 'ThemeStyle') != 'undefined') {
            $('html').attr('data-theme', $.cookie(systemPrefix + 'ThemeStyle'));
        }
    </script>
    <script src="{{ getJsCssPath('admin/vendors/jquery.fillcolor.js') }}"></script>

    <script>
        @php
        if (!empty(Request::ip())) {
            $locationData = \Location::get(Request::ip());
            $countryCode = !empty($locationData -> countryCode) ? $locationData -> countryCode : '';
            $country = App\Models\Country::getCountries(['country_id'], ['country_code' => $countryCode]) -> pluck('country_id') -> toArray();
            $countryId = !empty($country[0]) ? $country[0] : '';
            if (!empty($countryCode) && !empty($countryId)) {
                if (!empty(Auth::user() -> user_id)) {
                    if (empty(Auth::user() -> user_country_id)) {
                        App\Models\User::where('user_id', Auth::user() -> user_id) -> update([
                            'user_country_id' => $countryId
                        ]);
                        $userDefaultFlag = strtolower($countryCode);
                    } else {
                        $country = App\Models\Country::getCountries(['country_code'], ['country_id' => Auth::user() -> user_country_id]) -> pluck('country_code');
                        $userDefaultFlag = !empty($country[0]) ? strtolower($country[0]) : '';
                    }
                } else {
                    $userDefaultFlag = strtolower($countryCode);
                }
            }
        }
        @endphp
        userDefaultFlag = '{{ !empty($userDefaultFlag) ? $userDefaultFlag : "en"}}';
    </script>

    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1)
    {!!$configurations['GOOGLE_TAG_MANAGER_HEAD_SCRIPT']!!}
    @endif
    @if(!empty($configurations['SCHEMA_CODES_DEFAULT_SCRIPT']))
    <!-- Default Schema Code -->
    {!!$configurations['SCHEMA_CODES_DEFAULT_SCRIPT']!!}
    <!-- End Default Schema Code -->
    @endif
    @yield('schemaCodes')
    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1 && !empty($configurations['FACEBOOK_PIXEL_ID']))
    <!-- Facebook Pixel Code -->
    <script>
        let fbPixel = true;
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{$configurations["FACEBOOK_PIXEL_ID"]}}');
        fbq('track', 'PageView');
    </script>
    <noscript><img data-yk="" height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{$configurations['FACEBOOK_PIXEL_ID']}}&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code  -->
    @endif
    <script src="{{ getJsCssPath('vendors/js/events.js') }}"></script>

    <script>
        $(document).ready(function(){
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register("/sw.js?t=@php echo filemtime(public_path('sw.js')) @endphp&f')}}").then(function(registration) {
                    });
                });
            }    
        });
    </script>
</head>