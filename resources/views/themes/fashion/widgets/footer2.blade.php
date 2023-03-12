<footer class="main-footer content-min-height yk-footer"  id="footer" data-yk="" data-gjs-draggable="div#wrapper">
  <div class="footer-top">
    <div class="container">
      <div class="footer-row row">
          <div class="col-lg-3 footer-contact-information">
              <div class="block-content mb-4">
                  @include('themes.'.config('theme').'.widgets.logo')
              </div>
              <div class="block-content">
                  <div class="block-contact">
                      <div class="text-widget">
                          <p><i class="fa fa-map-marker-alt"></i><span>{{__('LBL_CMS_FOOTER2_ADDRESS')}}</span>
                          </p>
                          <p>
                              <i class="fa fa-envelope"></i>
                              <a class="contact_email" href="mailto:claue@domain.com">{{__('LBL_CMS_FOOTER2_EMAIL')}}</a>
                          </p>
                          <p>
                              <i class="fa fa-phone-alt"></i>
                              <a href="tel:1800-123-222"> {{__('LBL_CMS_FOOTER2_CONTACT')}}
                              </a> </p>
                          <p>
                              <i class="fa fa-clock"></i>
                              {{__('LBL_CMS_FOOTER2_TIMINGS')}}
                          </p>
                      </div>
                  </div>
              </div>
              <div class="block-content">
                  <div class="block-social">
                      @include('themes.'.config('theme').'.widgets.socialIcons')
                  </div>

              </div>
          </div>
          <div class="col-lg-6">
              <div class="row">
                  <div class="col-md-4">
                      <div class="block-content toggle-group">
                          <h5 class="toggle__trigger toggle__trigger-js">{{__("LBL_CATEGORIES")}}</h5>
                          <div class="toggle__target toggle__target-js">
                              <ul class="nav-vertical">
                                    <li><a href="#">{{__("LBL_WOMEN")}}</a></li>
                                    <li><a href="#">{{__("LBL_ACCESSORIES")}}</a></li>
                                    <li><a href="#">{{__("LBL_TSHIRTS")}}</a></li>
                                    <li><a href="#">{{__("LBL_JACKETS")}}</a></li>
                                    <li><a href="#">{{__("LBL_MENS")}}</a></li>
                                    <li><a href="#">{{__("LBL_JEWELLERY")}}</a></li>
                              </ul>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="block-content toggle-group">
                          <h5 class="toggle__trigger toggle__trigger-js">{{__("LBL_CATEGORIES")}}</h5>
                          <div class="toggle__target toggle__target-js">
                              <ul class="nav-vertical">
                                    <li><a href="#">{{__("LBL_WOMEN")}}</a></li>
                                    <li><a href="#">{{__("LBL_ACCESSORIES")}}</a></li>
                                    <li><a href="#">{{__("LBL_TSHIRTS")}}</a></li>
                                    <li><a href="#">{{__("LBL_JACKETS")}}</a></li>
                                    <li><a href="#">{{__("LBL_MENS")}}</a></li>
                                    <li><a href="#">{{__("LBL_JEWELLERY")}}</a></li>
                              </ul>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="block-content toggle-group">
                          <h5 class="toggle__trigger toggle__trigger-js">{{__("LBL_CATEGORIES")}}</h5>
                          <div class="toggle__target toggle__target-js">
                              <ul class="nav-vertical">
                                    <li><a href="#">{{__("LBL_WOMEN")}}</a></li>
                                    <li><a href="#">{{__("LBL_ACCESSORIES")}}</a></li>
                                    <li><a href="#">{{__("LBL_TSHIRTS")}}</a></li>
                                    <li><a href="#">{{__("LBL_JACKETS")}}</a></li>
                                    <li><a href="#">{{__("LBL_MENS")}}</a></li>
                                    <li><a href="#">{{__("LBL_JEWELLERY")}}</a></li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-lg-3">
              @include('themes.'.config('theme').'.widgets.newsletter')
              <div class="block-content toggle-group">
                  <div class="payment-cards">
                      <img  data-yk="" alt="" src="{{url('yokart/'.config('theme'))}}/media/payment.png">
                  </div>
              </div>
          </div>
        </div>
    </div>

  </div>
@php
    $businessName = getConfigValueByName('BUSINESS_NAME');
@endphp
  <div class="footer-copyright">
    <div class="container">
      <div class="footer-row row">
        <div class="col-lg-6 col-md-6">
          <div class="block-content">
            <div class="footer-copyright-info " style="">
              <p>{{__("LBL_COPYRIGHT")}} © {{date('Y')}} <a href="#">{{$businessName}}</a></p>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="block-content">
            <div class="footer-menu">
              <ul>
                <li><a href="#">{{__("LBL_SHOP")}}</a></li>
                <li><a href="#">{{__("LBL_ABOUT_US")}}</a></li>
                <li><a href="#">{{__("LBL_CONTACT")}}</a></li>
                <li><a href="#">{{__("LBL_BLOG")}}</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
