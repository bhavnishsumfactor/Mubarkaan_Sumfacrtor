@php
$configurations = getConfigValues([
    'BUSINESS_NAME',
    'ADMIN_FONT_FAMILY',
    'ADMIN_PRIMARY_COLOR',
    'ADMIN_PRIMARY_COLOR_INVERSE'
]);
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <meta charset="utf-8" />
        <link rel="preload" href="media/logos/logo.png" as="image">
        <!--  -->
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="application-name" content="">
        <meta name="apple-mobile-web-app-title" content="">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="msapplication-starturl" content="/">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">


        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .code {
                border-right: 2px solid;
                font-size: 26px;
                padding: 0 15px 0 15px;
                text-align: center;
            }

            .message {
                font-size: 18px;
                text-align: center;
            }
            .not-found {
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
            }
        </style>
    </head>

    <body>
        <div class="not-found">
            <img src="{{url('images/errorImages/internal-server-issue.png')}}" data-yk="" alt="">
            <div class="flex-center position-ref">
                <div class="code"> @yield('code')</div>
                <div class="message" style="padding: 10px;">@yield('message')</div>
            </div>
        </div>
    </body>     
</html>
