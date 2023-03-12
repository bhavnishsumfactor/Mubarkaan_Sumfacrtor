@php
$configurations = getConfigValues([
    'BUSINESS_NAME',
    'ADMIN_LOGO_RATIO',
    'FRONTEND_FONT_FAMILY',
    'ADMIN_PRIMARY_COLOR',
    'ADMIN_PRIMARY_COLOR_INVERSE',
    'ADMIN_FONT_FAMILY',
    'SYSTEM_MESSAGE_POSITION',
    'SYSTEM_MESSAGE_STATUS',
    'SYSTEM_MESSAGE_CLOSE_TIME'
]);
config(['toastr.options.positionClass' => $configurations['SYSTEM_MESSAGE_POSITION']]);
if ($configurations['SYSTEM_MESSAGE_STATUS']=='1') {
    config(['toastr.options.timeOut' => $configurations['SYSTEM_MESSAGE_CLOSE_TIME'] * 1000]);
}
@endphp
<!DOCTYPE html>
   <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @yield('css')
        <!-- CSRF Token -->
        <meta id="token" name="csrf-token" value="{{ csrf_token() }}">
        <title>{{$configurations['BUSINESS_NAME']}}</title>
        <link rel="shortcut icon" href="{{ getFavicon() }}" />
        <!-- Scripts -->
        <!--begin::Fonts -->
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        <script>
            let adminBaseUrl = "{{url('/admin')}}";
            function validateErrors(errors, obj) {
                Object.keys(errors).forEach(key => {
                    obj.find('input[name="' + key + '"]').addClass('is-invalid');
                    obj.find('input[name="' + key + '"]').closest('div').append('<div class="invalid-feedback">' + errors[key] + '</div>');
                });
            }
        </script> 
        <!--end::Fonts -->
        <link href="{{ asset('admin/css/main-ltr.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/css/custom.css') }}" rel="stylesheet">
        @if($configurations['FRONTEND_FONT_FAMILY'] == 'Mulish')
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @elseif($configurations['FRONTEND_FONT_FAMILY'] == 'Roboto')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @elseif($configurations['FRONTEND_FONT_FAMILY'] == 'Poppins')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @elseif(cleanSpaces(strtolower($configurations['FRONTEND_FONT_FAMILY'])) == 'opensans')
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    @endif
    <link href="{{ asset('admin/vendors/toastr.min.css') }}" rel="stylesheet">
    <style>
        body{font-family: "{{$configurations['ADMIN_FONT_FAMILY'] ?? 'Poppins'}}", sans-serif !important;}
        :root { 
            --brand-color: {{ !empty($configurations['ADMIN_PRIMARY_COLOR']) ? $configurations['ADMIN_PRIMARY_COLOR'] : '#ff0055'}} !important;
            --brand-color-inverse: {{ !empty($configurations['ADMIN_PRIMARY_COLOR_INVERSE']) ? $configurations['ADMIN_PRIMARY_COLOR_INVERSE'] : '#FFFFFF'}} !important;
        }  
       
    </style>
    </head>
   <body class="@if(config('app.localSite') == true){{'demo-header--on'}}@endif is-login login-3">
   @if(config('app.localSite') == true)
   @include('restore-system.header', ["sectionType" =>"admin"])
   @endif
   <div class="login login-v3">
		<div class="login-v3__wrapper">
			<div class="login-v3-aside" style="background-image: url({{ asset('admin/images/bg/bg-page-section.png') }})">
				<div class="login-v3-aside__head">
					<a href="{{ url('/admin') }}" class="login-v3-aside__logo">
                        <img data-yk="" src="{{ getAdminLogo() ?? asset('admin/images/logos/logo-icon-sm.png') }}" data-ratio="{{ $configurations['ADMIN_LOGO_RATIO'] }}" alt="" /></a>
				</div>
				<div class="login-v3-aside___body">
					<div class="login-v3-aside__content ">
                       <div class="text-center">
                       <h2>{{__('LBL_WELCOME')}}</h2>
                       <h3>{{$configurations['BUSINESS_NAME']}}</h3></div>
					</div>
				</div>
				<div class="login-v3-aside__footer">
                   
				</div>
			</div>
     		<div class="login-v3-main">
				<div class="login-v3-main__body">
                @include('notificationMessages')
                 @yield('content')
				</div>
                <img style="display:none" src="https://fatlib.4livedemo.com/img/nav.png">
			</div>
		</div>
	</div>
    
    <script src="{{ asset('admin/vendors/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/toastr.min.js') }}"></script>
    @toastr_render
    @if(config('app.localSite') == true)
    @include('restore-system.index')
    @endif    
    @yield('scripts')
    </body>
   </html>
