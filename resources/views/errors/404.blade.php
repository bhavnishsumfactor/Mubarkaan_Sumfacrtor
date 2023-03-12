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

        <title>{{__('LBL_NOT_FOUND')}}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
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

        @if(strtolower($configurations['ADMIN_FONT_FAMILY']) == 'mulish')
            <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
            @elseif(strtolower($configurations['ADMIN_FONT_FAMILY']) == 'roboto')
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
            @elseif(strtolower($configurations['ADMIN_FONT_FAMILY']) == 'poppins')
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            @elseif(cleanSpaces(strtolower($configurations['ADMIN_FONT_FAMILY'])) == 'opensans')
            <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
        @endif
        <link href="{{ asset('yokart/'.config('theme').'/css/main-ltr.css') }}" id="YK-mainCss" rel="stylesheet" type="text/css">
        <!-- Styles -->
        <style>            
            body{font-family: "{{$configurations['ADMIN_FONT_FAMILY'] ?? 'Poppins'}}", sans-serif !important;}
            :root { 
                --brand-color: {{ !empty($configurations['ADMIN_PRIMARY_COLOR']) ? $configurations['ADMIN_PRIMARY_COLOR'] : '#ff0055'}} !important;
                --brand-color-inverse: {{ !empty($configurations['ADMIN_PRIMARY_COLOR_INVERSE']) ? $configurations['ADMIN_PRIMARY_COLOR_INVERSE'] : '#FFFFFF'}} !important;
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
        <img src="{{url('yokart/'.config('theme'))}}/media/retina/404-img.svg" data-yk="" alt="">
        <h3>{{__('LBL_404')}}. {{__('LBL_PAGE_NOT_FOUND')}}.</h3>
        <p> {{__('LBL_PAGE_404_CAPTION')}}.</p>

        <div class="action">
            <a class="btn btn-outline-brand btn-wide" href="{{route('home')}}"> {{__('LNK_GO_TO_HOME_PAGE')}} </a>
        </div>
    </div>      
    </body>     
</html>
