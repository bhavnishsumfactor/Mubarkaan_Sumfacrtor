
<html lang="en" data-theme="light" dir="ltr" data-version="1">
    <head>
        <meta charset="utf-8" />
        <link rel="preload" href="media/logos/logo.png" as="image">
        <meta name="theme-color" content="#0193ee">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="img/favicon/ms-icon-144x144.png">
        <title>A Unit of Yo!kart </title>
        <meta id="token" name="csrf-token" value="{{ csrf_token() }}">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="application-name" content="A Unit of Yo!kart">
        <meta name="apple-mobile-web-app-title" content="Single Vendor System">
        <meta name="msapplication-navbutton-color" content="#0193ee">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="msapplication-starturl" content="/">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--begin::CSS -->
        <link href="{{ asset('installer/css/main-ltr.css') }}" rel="stylesheet">
        <!--end::CSS -->
        <!--end::Layout Skins -->
        <link rel="shortcut icon" href="img/favicon/favicon.ico" />
        
        <link rel="apple-touch-icon" sizes="57x57" href="{{ url('installer/images/favicon/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ url('installer/images/favicon/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ url('installer/images/favicon/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ url('installer/images/favicon/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ url('installer/images/favicon/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ url('installer/images/favicon/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ url('installer/images/favicon/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ url('installer/images/favicon/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ url('installer/images/favicon/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ url('installer/images/favicon/android-icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ url('installer/images/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ url('installer/images/favicon/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ url('installer/images/favicon/favicon-16x16.png') }}">
        <!--<link rel="manifest" href="img/favicon/manifest.json">-->
        <script src="{{ asset('admin/js/jquery.min.js') }}"></script>
        <script src="{{ url('/js/installer-lang.js') }}"></script>
            
        <script>
            let baseUrl = "{{url('/')}}";
            let adminBaseUrl = "{{url('/admin')}}";
            window.Laravel = {"permissions":{"role_id":null}};
            
            
            let pageDefaultLanguage = "{{app()->getLocale()}}";
           
        </script>
    </head>
    <body>
        
        <div class="wrapper" id="app" data-yk="">
            <installer></installer>
        </div>
        <script src="{{ url('installer/js/build/installer.js').'?v='.config('app.version')}}"></script>
    </body>
</html>