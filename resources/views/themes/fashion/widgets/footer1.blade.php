@php
$businessName = getConfigValueByName('BUSINESS_NAME');
@endphp
<footer class="footer main-footer content-min-height v2 yk-footer" id="footer" data-yk="" data-gjs-draggable="div#wrapper">
    <div class="footer-top">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-auto d-md-flex align-items-center justify-content-center justify-content-xl-start">
                    <nav class="nav nav--secondary">
                        <a href="#">{{__("LNK_PRIVACY_POLICY")}} </a>
                        <a href="#">{{__("LNK_TERMS_CONDITIONS")}}</a>
                    </nav>
                </div>
                <div class="col-xl-auto">
                    <div class="socail-follow yk-socialIcons">
                        <a class="icon icon-twitter yk-socialLink" href="#"><i class="fab fa-twitter" data-gjs-editable="false" data-gjs-removable="false" data-gjs-selectable="false"></i></a>
                        <a class="icon icon-facebook yk-socialLink" href="#"><i class="fab fa-facebook-f" data-gjs-editable="false" data-gjs-removable="false" data-gjs-selectable="false"></i></a>
                        <a class="icon icon-instagram yk-socialLink" href="#"><i class="fab fa-instagram" data-gjs-editable="false" data-gjs-removable="false" data-gjs-selectable="false"></i></a>
                        <a class="icon icon-gplus yk-socialLink" href="#"><i class="fab fa-google" data-gjs-editable="false" data-gjs-removable="false" data-gjs-selectable="false"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-copyright-info">
                {{__("LBL_COPYRIGHT")}} © {{date('Y')}} <a href="#">{{$businessName}}</a>
            </div>
        </div>
    </div>

</footer>