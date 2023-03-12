<div id="google_translate_element"></div>
@php 
$languages = getLanguages();
$languagesCount = count($languages);
$language = request()->session()->get('language');
$langCode = 'en';
if($language && $language['code']){
    $langCode = $language['code'];
}

unset($languages[$language['code']]);

$currencies = getCurrencies();
$selectedCurrency = currencyCode();
@endphp
@if(!empty($languages) && $languagesCount==1 && !empty($currencies) && count($currencies)==1)
<span>
    <i class="icn"><img data-yk="" class="YK-selectedLanguageFlag" src="{{asset('flags/'.$langCode.'.svg')}}" alt=""></i>
</span>
@else
<a href="javascript:;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="quick-link_text lang-dropdown">
    <i class="icn flag"><img data-yk="" class="YK-selectedLanguageFlag" src="{{asset('flags/'.$langCode.'.svg')}}" alt=""></i>
    <span class="icn-txt">{{ucwords($language["name"]) ?? __('LBL_ENGLISH')}}/{{$selectedCurrency ?? __('LBL_USD') }}</span>
</a>

    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-left dropdown-menu-anim YK-languageCurrencyDropdown">
        <ul class="nav nav--block list-checkbox-radio">
            <li class="dropdown-header">{{__("LBL_CHANGE_LANGUAGE")}}</li>
            <li class="dropdown-item nav__item">
                <label class="radio">
                    <input type="radio" checked> <i class="input-helper"></i>
                    <span>{{$language['name']}}</span>
                </label>
            </li>
            @if(!empty($languages) && $languagesCount > 1)
                <li class="dropdown-divider"></li>                
                @foreach($languages as $langCode => $langName)  
                @php $languageUrl = url()->current() . '?language=' . $langCode . '#googtrans(auto|' . $langCode . ')'; @endphp              
                <li class="dropdown-item nav__item" onclick="window.location.href = '{{$languageUrl}}'" data-language-code="{{$langCode}}">
                    <label class="radio notranslate">
                        <input type="radio" value="1"> <i class="input-helper"></i>{{$langName}}</label>
                </li>
                @endforeach
            @endif
        </ul>
        <ul class="nav nav--block list-checkbox-radio">
            <li class="dropdown-header">{{__("LBL_CHANGE_CURRENCY")}}</li>
            <li class="dropdown-item nav__item"> 
                <label class="radio">
                    <input type="radio" checked> <i class="input-helper"></i>
                    {{$selectedCurrency}}
                </label>
            </li>
            @if(!empty($currencies) && count($currencies)>1)
                <li class="dropdown-divider"></li>
                @foreach($currencies as $currency)            
                @php 
                if ($currency->currency_code == $selectedCurrency) {
                    continue;
                }
                @endphp
                <li class="dropdown-item nav__item YK-switchCurrency"> <label class="radio">
                        <input type="radio" value="{{$currency->currency_code}}"> <i class="input-helper"></i>{{$currency->currency_code}}</label>
                </li>
                @endforeach
            @endif
        </ul>
    </div>
@endif