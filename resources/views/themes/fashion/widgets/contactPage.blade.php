@php
$contactConfig = getConfigValues(['BUSINESS_NAME', 'BUSINESS_PHONE_NUMBER', 'BUSINESS_EMAIL', 'BUSINESS_ADDRESS1',
'BUSINESS_ADDRESS2', 'BUSINESS_CITY', 'BUSINESS_PINCODE', 'BUSINESS_COUNTRY', 'BUSINESS_STATE']);
if (!empty($contactConfig["BUSINESS_COUNTRY"])) {
$country = App\Models\Country::getRecordById($contactConfig["BUSINESS_COUNTRY"]);
$contactConfig["BUSINESS_COUNTRY"] = $country->country_name;
}
if (!empty($contactConfig["BUSINESS_STATE"])) {
$state = App\Models\State::getRecordById($contactConfig["BUSINESS_STATE"]);
$contactConfig["BUSINESS_STATE"] = $state->state_name;
}
@endphp
<div data-gjs-draggable="div.body">
    <section class="bg-contact bg-gray yk-contactPage">
        <div class="container">
            <div class="section-content">
                <h1> {{__("LBL_GET_IN_TOUCH")}}</h1>
                <p>{{__("LBL_HOW_CAN_WE_HELP_YOU")}}</p>
            </div>
        </div>
    </section>
    <section class="section contact-us">
        <div class="container">
            <div class="contact-head">
                <div class="row">
                    <div class="col-lg-5 col-12">
                        <div class="single-head">
                            <div class="contant-inner-title">
                                <h4>{{__('LBL_CONTACT_INFORMATION')}}</h4>
                                <p>{{__('LBL_CONTACT_GET_IN_TOUCH')}}!</p>
                            </div>
                            <div class="single-info">
                                <i class="fas fa-mobile-alt"></i>
                                <ul>
                                    <li class="yk-caption">
                                        {{$contactConfig['BUSINESS_PHONE_NUMBER'] ?? '+919555596666'}}</li>
                                </ul>
                            </div>
                            <div class="single-info">
                                <i class="fas fa-envelope"></i>
                                <ul>
                                    <li><a class="yk-caption"
                                            href="mailto:{{'sales@fatbit.com'}}">{{'sales@fatbit.com'}}</a>
                                        {{--$contactConfig['BUSINESS_EMAIL']--}}
                                    </li>
                                </ul>
                            </div>
                            <div class="single-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <ul>
                                    <li>
                                        {{$contactConfig['BUSINESS_ADDRESS1']}}@if($contactConfig['BUSINESS_ADDRESS2']),
                                        {{$contactConfig['BUSINESS_ADDRESS2']}}@endif
                                        {{$contactConfig['BUSINESS_CITY']}}, {{$contactConfig['BUSINESS_STATE']}},
                                        {{$contactConfig['BUSINESS_COUNTRY']}} {{$contactConfig['BUSINESS_PINCODE']}}
                                    </li>
                                </ul>
                            </div>
                            <div class="contact-social">
                                <h5>{{__('LBL_FOLLOW_US_ON')}}</h5>
                                <ul>
                                    <li>
                                        <a href="https://www.facebook.com/Ablysoft/">
                                            <span class="icon-1"><i class="fab fa-facebook-f"></i></span>
                                            <span class="icon-2"><i class="fab fa-facebook-f"></i></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://twitter.com/ablysoft_?lang=en">
                                            <span class="icon-1"><i class="fab fa-twitter"></i></span>
                                            <span class="icon-2"><i class="fab fa-twitter"></i></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.linkedin.com/company/ablysoft-pvt-ltd/">
                                            <span class="icon-1"><i class="fab fa-linkedin-in"></i></span>
                                            <span class="icon-2"><i class="fab fa-linkedin-in"></i></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/ablysoft/?hl=en">
                                            <span class="icon-1"><i class="fab fa-instagram"></i></span>
                                            <span class="icon-2"><i class="fab fa-instagram"></i></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.behance.net/AblySoft">
                                            <span class="icon-1"><i class="fab fa-behance"></i></span>
                                            <span class="icon-2"><i class="fab fa-behance"></i></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-12">
                        <div class="form-main yk-contactFormContainer content-min-height" data-gjs-type="default"
                            data-gjs-removable="false">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section py-0">
        <div class="map-container">
            <div class="mapouter yk-mapContainer content-min-height" data-gjs-type="default" data-gjs-removable="false">

            </div>

        </div>
    </section>
</div>