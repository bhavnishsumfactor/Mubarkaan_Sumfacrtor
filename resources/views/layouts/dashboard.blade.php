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
    'LIVE_CHAT_CODE'
]); 
@endphp
@include('layouts.headSection', ['dashboard' => 1])
<body class="@if(config('app.localSite') == true){{'demo-header--on'}}@endif loading">


@if(config('app.localSite') == true)
@include('restore-system.header', ["sectionType" =>"desktop"])
@endif
@if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1)
{!!$configurations['GOOGLE_TAG_MANAGER_BODY_SCRIPT']!!}
@endif
    <div class="wrapper" id="wrapper" data-yk="">

        @yield('content')

        <div id="dataModal" class="modal fade"></div>
    </div>
    <a id="back-top" class=""></a>
    <div class="back-overlay"></div>     
    @include('themes.'.config('theme').'.partials.scripts')
    <script>

    function getUnreadMessage() {
        $.ajax({
            url: baseUrl + '/user/unread-message-count',
            type: "get",
            success: function(res) {
                if (res.status == true) {
                    if (res.data != 0) {
                        $(".yk-messageCount").text(res.data);
                    } else {
                        $(".yk-messageCount").text(0);
                    }
                }
            },
            error: function(xhr, status, error) {

            }
        });
    }
    getUnreadMessage();
    /*function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: Object.keys(languages).join(','),
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');        
    }*/
    
    function setLangCookie(langcode){
        
        $.removeCookie('googtrans'); 
        $.cookie('googtrans',null, {domain:domain, path: '/'});
        let tempLang = '/en/' + langcode;
        if (langcode == 'en') {
            tempLang = '/auto/en';
        }
        $.cookie('googtrans', tempLang, { domain:domain, path: '/', expires: 15 });
    }    
    setLangCookie(pageDefaultLanguage);
    </script>
    <script  src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

   
    <script>var _tooltip = jQuery.fn.tooltip;</script>
    
    
    {!!$configurations['LIVE_CHAT_CODE']!!} 
    <script>
        NProgress.configure({ trickleSpeed: 150, showSpinner: false });
        $(document).ready(function(){
            $('body').removeClass('loading');  
            if (window.location.href.indexOf('?') > -1) {
                history.pushState('', document.title, window.location.pathname);
            }  
            $('[role="tooltip"]').on('click mousedown mouseup', function() { 
                $(this).trigger("mouseleave");
            });
            $('.yk-perfectScrollbar').each(function(){
                new PerfectScrollbar($(this)[0]);
            });
        });    
    </script>
    {{--<script src="{{ asset('vendors/js/toastr.min.js') }}"></script>
    <script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>--}}

    {{--<link href="{{ asset('vendors/css/intlTelInput.min.css')}}" rel="stylesheet" type="text/css">--}}
    <style>
        .iti__flag {background-image: url({{asset('yokart/'.config('theme').'/images/flags.png')}});}
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .iti__flag {       background-image: url({{asset('yokart/'.config('theme').'/images/flags@2x.png')   }});}
        }
    </style>
    {{--<script src="{{ asset('vendors/js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('vendors/js/jquery.inputmask.js') }}"></script>--}}
    
    @toastr_render
    @yield('scripts')
    <div class="modal fade hide" id="modal_cropper">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title">Crop</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="cropper-body">
                        <div class="image-wrapper">
                            <img data-yk="" class="cropperSelectedImage" src="" alt="">
                        </div>
                        <canvas id="preview" style="display:none;"></canvas>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="tabId">
                    <input type="hidden" name="slideType">
                    
                    <div class="file-input"  @click="openCropper()">
                        <input class="btn btn-outline-brand js-cropperSelectImage" type="file" accept="image/*" style="width: 131px;"><label class="btn btn-brand btn-link" style="padding: 5px;">Upload Picture</label>
                    </div>            
                    <button type="button" class="btn btn-brand" onclick="saveCropper(this)">Save</button>
                </div>
            </div>
        </div>
    </div>
@if(config('app.localSite') == true)
    @include('restore-system.index')
@endif

</script>
</body>

</html>
