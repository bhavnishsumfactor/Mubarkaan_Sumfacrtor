<div class="modal fade" id="modalSetCookiesPreferences" data-yk=""  role="yk-dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered" data-yk="">
            <div class="modal-content">
                <form method="post" id="acceptcookie-form">
                @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"> {{__('LBL_YOUR_COOKIE_PREFERENCE')}}</h5>
                    </div>
                    <div class="modal-body" style="max-height: 450px;">
                        <div class="cms">
                            <p>{!! nl2br($configurations['ADVANCED_PREFERENCES_COOKIE_TEXT']) !!}</p>
                            <div class="YK-fieldCookie">
                                <label class="yk-label col-form-label checkbox" for="yk-field-functional">
                                    <input type="checkbox" class="yk-choice checkbox disabledbutton" id="yk-field-functional" name="yk-ac-functional" rel="functional" disabled="disabled" checked>
                                    <strong>{{__('LBL_FUNCTIONAL_COOKIES')}}</strong>
                                </label>
                                <div class="yk-pseudolabel"><p>{!! nl2br($configurations['FUNCTIONAL_COOKIE_TEXT']) !!}</p></div>
                            </div>
                            <div class="YK-fieldCookie">
                                <label class="yk-label col-form-label checkbox" for="yk-field-analytics">
                                    <input type="checkbox" class="yk-choice checkbox" id="yk-field-analytics" rel="analytics" value="1" name="yk-ac-analytics" checked>
                                    <strong>{{__('LBL_STATISTICAL_ANALYSIS_COOKIE')}}</strong>
                                </label>
                                <div class="yk-pseudolabel"><p>{!! nl2br($configurations['STATISTICAL_ANALYSIS_COOKIE_TEXT']) !!}</p></div>
                            </div>
                            <div class="YK-fieldCookie">
                                <label class="yk-label col-form-label checkbox" for="yk-field-customization">
                                    <input type="checkbox" class="yk-choice checkbox" id="yk-field-customization" rel="customization" value="1" name="yk-ac-personalized" checked>
                                    <strong>{{__('LBL_PERSONALISE_COOKIE')}}</strong>
                                </label>
                                <div class="yk-pseudolabel"><p>{!! nl2br($configurations['PERSONALIZE_COOKIE_TEXT']) !!}</p></div>
                            </div>
                            <div class="YK-fieldCookie">
                                <label class="yk-label col-form-label checkbox" for="yk-field-advertising">
                                    <input type="checkbox" class="yk-choice checkbox" id="yk-field-advertising" rel="advertising" value="1" name="yk-ac-advertising" checked>
                                    <strong>{{__('LBL_ADVERTISING_SOCIAL_MEDIA_COOKIE')}}</strong>
                                </label>
                                <div class="yk-pseudolabel"><p>{!! nl2br($configurations['ADVERTISING_SOCIAL_MEDIA_COOKIE_TEXT']) !!}</p></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-brand YK-backAcceptCookies" type="button">{{__('BTN_BACK')}}</button>
                        <button class="btn btn-brand YK-saveCookiesPreferences" type="button">{{__('BTN_SAVE')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalCookies" data-yk=""  role="yk-dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered" data-yk="">
            <div class="modal-content">
                <form method="post">
                @csrf                   
                    <div class="modal-body">
                        <div class="cookies">
                            <img class="cookies_img" data-yk="" src="{{asset('yokart/'.config('theme').'/media/retina/cookie.svg')}}" alt="">
                            <h5 class="modal-title"> {{__('LBL_YOUR_COOKIE_PREFERENCE')}}</h5>
                            <p>{!! nl2br($configurations['ACCEPT_COOKIE_TEXT']) !!}</p>

                            <div class="cookies_actions"><input type="hidden" value="all" name="yk-acceptcookie">
                            <button class="btn btn-brand btn-wide YK-acceptCookies" type="button">{{__('LBL_ACCEPT')}}</button>

                            @if($configurations['ENABLE_ADVANCED_COOKIE_PREFERENCES'] == 1)
                             <button class="btn btn-link mt-5 YK-setCookiePreferences" type="button">{{__('LBL_SET_COOKIE_PREFERENCE')}}</button>
                             @endif
                           
                            </div>
                        </div>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.YK-setCookiePreferences', function(){
            $('#modalCookies').modal('hide');
            $('#modalSetCookiesPreferences').modal('show');
        });
        $(document).on('click', '.YK-acceptCookies', function(){
            acceptCookies(true);
            $('#modalCookies').modal('hide');
        });
        $(document).on('click', '.YK-backAcceptCookies', function(){
            $('#modalSetCookiesPreferences').modal('hide');
            $('#modalCookies').modal('show');               
        });
        $(document).on('click', '.YK-saveCookiesPreferences', function(){
            acceptCookies();
            $('#modalSetCookiesPreferences').modal('hide');
        });

        function acceptCookies (all = false) {   
            var formData = new FormData();   
            if (all == true) {              
                formData.append('yk-acceptcookie', "all");
            } else {            
                formData.append('yk-acceptcookie', "selective");
                formData.append('functional', $('[name="yk-ac-functional"]:checked').length);
                formData.append('analytics', $('[name="yk-ac-analytics"]:checked').length);
                formData.append('personalized', $('[name="yk-ac-personalized"]:checked').length);
                formData.append('advertising', $('[name="yk-ac-advertising"]:checked').length);
            }
            
            fetch(baseUrl + '/accept-cookies', {method: "POST", body: formData, headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }});
        }

        @if(!empty($configurations['ACCEPT_COOKIE_ENABLE']) && $configurations['ACCEPT_COOKIE_ENABLE'] == '1' && empty(config('acceptcookie')))
            window.addEventListener('load', function() {
                $('#modalCookies').modal('show');
            });
        @endif
        @if(empty($dashboard))
        new PerfectScrollbar($('#modalSetCookiesPreferences .modal-body')[0]);
        @endif
    </script>