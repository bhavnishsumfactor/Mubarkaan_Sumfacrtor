@php
$configurations = getConfigValues([
    'FACEBOOK_PIXEL_ID',
    'GOOGLE_TAG_MANAGER_HEAD_SCRIPT',
    'GOOGLE_TAG_MANAGER_BODY_SCRIPT',
    'SCHEMA_CODES_DEFAULT_SCRIPT',
    'SCHEMA_CODES_DEFAULT_SCRIPT',
    'SYSTEM_MESSAGE_STATUS', 'SYSTEM_MESSAGE_CLOSE_TIME','SYSTEM_MESSAGE_POSITION',
    'GOOGLE_ANALYTICS_SITE_TRACKING_CODE',
    'GOOGLE_ANALYTICS_ANALYTICS_ID',
    'FRONTEND_FONT_FAMILY',
    'FRONTEND_LOGO_RATIO',
    'PRIMARY_COLOR',
    'PRIMARY_COLOR_INVERSE', 
    'BACKGROUND_COLOR_ENABLED',
    'BUSINESS_NAME'
]);
config(['toastr.options.positionClass' => $configurations['SYSTEM_MESSAGE_POSITION']]);
if ($configurations['SYSTEM_MESSAGE_STATUS']=='1') {
    config(['toastr.options.timeOut' => $configurations['SYSTEM_MESSAGE_CLOSE_TIME'] * 1000 ]);
}
$acceptCookie = config('acceptcookie');
$logo = getLogo();
$language = request()->session()->get('language');
@endphp
<!DOCTYPE html>
<html lang="{{$language['code']}}" data-theme="light" dir="{{$language['direction']}}" data-version="{{config('app.version')}}" prefix="og: http://ogp.me/ns#" class="sign-page">
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    @php
    $record_id = ($record_id ?? null);
    $metatags = getMetaTags($record_id);
    
               
    @endphp
    <title>{{ $configurations['BUSINESS_NAME'] ?? '' }} - {{$metatags->meta_title ?? ''}}</title>
    <meta name="description" content="{{$metatags->meta_description ?? ''}}" />
    <meta name="keywords" content="{{$metatags->meta_keywords ?? ''}}" />
    {!!$metatags->meta_other_meta_tags ?? ''!!}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta id="token" name="csrf-token" value="{{ csrf_token() }}">
    @if(Request::isSecure())
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif 
    <link rel="shortcut icon" href="{{ getFavicon() }}" />
    {{--@yield('metaTags')--}}
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $configurations['BUSINESS_NAME'] }}" />
    <meta property="og:site_name" content="{{ $configurations['BUSINESS_NAME'] }}" />
    <meta property="og:image" content="{{$logo}}" />
    <meta property="og:url" content="{{ route('home') }}" />
    
    <meta name="twitter:card" content="home">
    <meta name="twitter:title" content="{{ $configurations['BUSINESS_NAME'] }}">
    <meta name="twitter:description" content="{{ $configurations['BUSINESS_NAME'] }}">
    <meta name="twitter:image:src" content="{{$logo}}">

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
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{$configurations["FACEBOOK_PIXEL_ID"]}}');
        fbq('track', 'PageView');
        </script>
        <noscript><img data-yk="" height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{$configurations['FACEBOOK_PIXEL_ID']}}&ev=PageView&noscript=1"
        /></noscript>   
        <!-- End Facebook Pixel Code  -->
    @endif    
    <script src="{{ asset('vendors/js/events.js') }}"></script>
    <script>
        let systemPrefix = "{{config('systemPrefix')}}";
    </script>
    @if($configurations['FRONTEND_FONT_FAMILY'] == 'Mulish')
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @elseif($configurations['FRONTEND_FONT_FAMILY'] == 'Roboto')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @elseif($configurations['FRONTEND_FONT_FAMILY'] == 'Poppins')
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
    </style>
    <link href="{{ asset('yokart/'.config('theme').'/css/main-' . $language['direction'] . '.css') }}" rel="stylesheet" type="text/css" rel="preload">
    <script src="{{ asset('vendors/js/jquery.min.js') }}"></script>   
    <script src="{{ asset('vendors/js/jquery.min.js') }}"></script>    
    <script src="{{ asset('vendors/js/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/js/jquery.cookie.min.js') }}"></script>
    <script>
        let currentUrl = "{{url()->current()}}";
        let baseUrl = "{{url('/')}}";
        @php
            preg_match('#[^\.]+[\.]{1}[^\.]+$#', $_SERVER['SERVER_NAME'], $matches);
        @endphp
        let domain = "{{ $matches[0] ?? 'localhost' }}";  
        let languages = {!!json_encode(getLanguages())!!}
        let pageDefaultLanguage = "{{$language['code']}}";
    </script>
    <link href="{{ asset('vendors/css/toastr.min.css') }}" rel="stylesheet">
</head>
<body class="@if(config('app.localSite') == true){{'demo-header--on flex-grow-1'}}@endif">
@if(config('app.localSite') == true)
    @include('restore-system.header', ["sectionType" =>"desktop"])
@endif

@if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1)
{!!$configurations['GOOGLE_TAG_MANAGER_BODY_SCRIPT']!!}
@endif
<div class="" id="body" data-yk=""  >
   <div class="login-bg"></div>
   @yield('content')
  
</div>
@include('partials.themeSwitch')
<script>
    
    if (typeof $.cookie(systemPrefix + 'ThemeStyle') != 'undefined') {
        $('html').attr('data-theme', $.cookie(systemPrefix + 'ThemeStyle'));
    }
    $(document).on('keyup', '.form-floating input', function(){    
        if ($(this).val().length > 0) {
            $(this).addClass('filled')
        } else {
            $(this).removeClass('filled')
        }
    });
    (function () {
        $('body').removeClass('loading');  
        loader = function(obj, removeLoader = false) {
            if (removeLoader == false) {
                obj.attr("disabled", "disabled");
                obj.addClass("gb-is-loading");
            } else {
                obj.removeAttr("disabled");
                obj.removeClass('gb-is-loading');
            }
        }
        floatingFormFix = function() {
            $('.form-floating').find('input').each(function () {
                if ($(this).val().length > 0) {
                    $(this).addClass('filled')
                } else {
                    $(this).removeClass('filled')
                }
            });
        }
        floatingFormFix();
    })();
</script>
    
    <script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });
    </script>
    </script>
    @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1 && !empty($configurations['GOOGLE_ANALYTICS_ANALYTICS_ID']))
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={!!$configurations['GOOGLE_ANALYTICS_ANALYTICS_ID']!!}"></script>
        {!!$configurations['GOOGLE_ANALYTICS_SITE_TRACKING_CODE']!!}
    @endif
    <script src="{{ asset('vendors/js/toastr.min.js') }}"></script>
    <link href="{{ asset('vendors/css/intlTelInput.min.css')}}" rel="stylesheet" type="text/css">
    <style>
        .iti__flag {background-image: url({{asset('yokart/'.config('theme').'/images/flags.png')}});}
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .iti__flag {       background-image: url({{asset('yokart/'.config('theme').'/images/flags@2x.png')   }});}
        }
    </style>
    <script src="{{ asset('vendors/js/intlTelInput.min.js') }}"></script>
    <script src="{{asset('vendors/js/jquery.inputmask.js')}}"></script>
    <script src="{{ asset('vendors/js/ui-functions.js') }}"></script>
    @toastr_render
    @if(config('app.localSite') == true)
        @include('restore-system.index')
    @endif
    @yield('scripts')
    @yield('css')
</body>
</html>
