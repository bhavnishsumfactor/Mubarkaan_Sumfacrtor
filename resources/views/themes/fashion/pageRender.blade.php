@extends('layouts.full')
@section('metaTags')
  <meta property="og:type" content="home" />
  <meta property="og:title" content="{{ $businessInformation['BUSINESS_NAME'] }}" />
  <meta property="og:site_name" content="{{ $businessInformation['BUSINESS_NAME'] }}" />
  <meta property="og:image" content="{{ $logo }}" />
  <meta property="og:url" content="{{ route('home') }}" />
  
  <meta name="twitter:card" content="home">
  <meta name="twitter:title" content="{{ $businessInformation['BUSINESS_NAME'] }}">
  <meta name="twitter:description" content="{{ $businessInformation['BUSINESS_NAME'] }}">
  <meta name="twitter:image:src" content="{{ $logo }}">
@endsection
@section('content')
  {!!$pageHtml!!}
@endsection

@section('css')
  @parent
  <style>
  {!!$pageCss!!}
  </style>
@endsection

@section('scripts')
  @parent
  @if($page_id == 4 && !empty($businessInformation['GOOGLE_RECAPCHA_SITE_KEY']) && !Request::exists('sess') &&
  $businessInformation['GOOGLE_RECAPCHA_STATUS'] == 1)
  <script>
      var siteKey = "{!! $businessInformation['GOOGLE_RECAPCHA_SITE_KEY'] !!}";
  </script>
  <script src=" https://www.google.com/recaptcha/api.js?render={!! $businessInformation['GOOGLE_RECAPCHA_SITE_KEY'] !!}">
  </script>
  <script>
  grecaptcha.ready(function() {
      grecaptcha.execute(siteKey, {
          action: 'register'
      }).then(function(token) {
          if (token) {
              document.getElementById('recaptcha').value = token;
          }
      });
  });
  </script>
  @endif
  @if($page_id == 1)
  <div class="modal fade YK-subscribedModal">
    <div class="modal-dialog modal-dialog-centered" data-yk=""  role="">
      <div class="modal-content">
        <div class="modal-body"> 
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <div class="newsletter-wrapper subscribe">
          <img src="{{ asset('yokart/'.config('theme')) }}/media/retina/subscribed.svg" alt="">
          <h5 class="YK-content"></h5>

          <button type="button" class="btn btn-outline-brand btn-wide" data-dismiss="modal" aria-label="Close"> {{ __('BTN_CLOSE') }}
          </button>
        </div>
        </div>

      </div>
    </div>
  </div>
  
  <div class="modal fade YK-unsubscribedModal">
    <div class="modal-dialog modal-dialog-centered" data-yk=""  role="">
      <div class="modal-content">
        <div class="modal-body"> 
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <div class="newsletter-wrapper unsubscribe">
          <img src="{{ asset('yokart/'.config('theme')) }}/media/retina/unsubscibed.svg" alt="">
          <h5 class="YK-content"></h5>
          <button type="button" class="btn btn-outline-brand btn-wide" data-dismiss="modal" aria-label="Close"> {{ __('BTN_CLOSE') }}
          </button>
        </div>
        </div>

      </div>
    </div>
  </div>
  @if (Session::get('subscribed'))
    <script>
      $(".YK-subscribedModal").find(".YK-content").html('').html("{{Session::get('subscribed')}}");
      $(".YK-subscribedModal").modal("show");
    </script>
  @endif    
  @if (Session::get('unsubscribed'))
    <script>
      $(".YK-unsubscribedModal").find(".YK-content").html('').html("{{Session::get('unsubscribed')}}");
      $(".YK-unsubscribedModal").modal("show");
    </script>
  @endif    

  
  @endif
@endsection
