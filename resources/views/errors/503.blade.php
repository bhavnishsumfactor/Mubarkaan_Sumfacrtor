@php
$configurations = getConfigValues([
    'BUSINESS_NAME',
    'ADMIN_FONT_FAMILY',
    'ADMIN_PRIMARY_COLOR',
    'ADMIN_PRIMARY_COLOR_INVERSE'
]);
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr" data-version="1">
<head>
<meta charset="utf-8" />
    <link rel="preload" href="media/logos/logo.png" as="image">
    <title>{{ $configurations['BUSINESS_NAME'] ?? '' }} - {{$metatags->meta_title ?? ''}}</title>
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{{ $configurations['BUSINESS_NAME'] }}">
    <meta name="apple-mobile-web-app-title" content="{{ $configurations['BUSINESS_NAME'] }}">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="/">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="{{ asset('yokart/'.config('theme').'/css/main-ltr.css') }}" id="YK-mainCss" rel="stylesheet" type="text/css">
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
    </head>
<!-- end::Head -->

<body>
    <div class="maintenance">
        <img src="{{url('yokart/'.config('theme'))}}/media/retina/under-maintenance.svg" data-yk="" alt="">
        <h3> {{ __('LBL_MAINTENANCE_MODE_HEADING') }} </h3>
        <p>{{ __('LBL_MAINTENANCE_MODE_DESCRIPTION') }}  </p>
    </div>
           
</body>
</html>
