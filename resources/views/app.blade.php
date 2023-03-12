@php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

$configurations = \Cache::get('configuration')->toArray();
@endphp
@include('layouts.headSection', ['dashboard' => 1])
<script>
        window.config = @php  echo json_encode($configurations); @endphp;
        window.auth = @php  echo json_encode(auth::user()); @endphp;
        window.activeCardTab = @php echo isSavedCardsActive(); @endphp;
        window.currentCurrency = @php echo json_encode(session()->get('currency')); @endphp;
        window.termsCondition =  @php echo "'".getPageByType('terms'). "'"; @endphp;
        window.languages = @php echo json_encode(getLanguages()); @endphp;
        window.currencies = @php echo json_encode(getCurrencies()); @endphp;
        window.selectedCurrency =  @php echo "'".currencyCode(). "'"; @endphp;
        window.selectedLanguage = @php echo json_encode(request()->session()->get('language')); @endphp;
        window.defaultFlag = @php echo "'".defaultFlag(). "'"; @endphp;    

 </script>
@include('partials.googleTranslate')
<script src="{{ route('userLang', auth::user()->user_id) . '?v=' . time() }}"></script>
<script src="{{ asset(mix('js/build/app.js', 'dashboard'))}}" defer></script>


<body class="@if(config('app.localSite') == true){{'demo-header--on'}}@endif">

   @if(config('app.localSite') == true)
   @include('restore-system.header', ["sectionType" =>"desktop"])
   @endif
   @if(!empty($acceptCookie) && $acceptCookie['analytics'] == 1)
   {!!$configurations['GOOGLE_TAG_MANAGER_BODY_SCRIPT']!!}
   @endif
   <div class="wrapper" id="wrapper" data-yk="">
      @inertia
   </div>
   @if(config('app.localSite') == true)
        @include('restore-system.index')
    @endif
  
</body>
<script>
if (typeof $.cookie(systemPrefix + 'ThemeStyle') != 'undefined') {
   $('html').attr('data-theme', $.cookie(systemPrefix + 'ThemeStyle'));
}
</script>
</html>
