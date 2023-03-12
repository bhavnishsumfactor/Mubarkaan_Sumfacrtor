<ul class="navigations @if(!empty($collections) && count($collections) <= 4) less  @endif">
    @php $navbarText = ''; $remainingNavItems = []; @endphp
    @if(!empty($collections) && count($collections)>0)
        @foreach($collections as $k => $collection)                
        @php 
            $navbarText .= $collection->navmenu_label;
            $navbarTextLength = Str::length($navbarText);
            if ($navbarTextLength > 60) {
                $remainingNavItems = $collections->slice($k); 
                break;
            }
            if ($collection->navmenu_type == '1') { @endphp
                <li class="navchild">
                  @if(count($collection->children) > 0)
                        <a target="_self" href="javascript:void(0);">{{$collection->navmenu_label}}</a> 
                    @else

                    
                        <a target="_self" href="{{createUrl($collection->catUrls)}}">{{$collection->navmenu_label}}</a> 
                    @endif
                    
                @if(count($collection->children) > 0)
                    <span class="link__mobilenav"></span>
                        <div class="subnav">
                            <div class="subnav__wrapper ">
                                <div class="container">
                                    <div class="nav__sub-panels">
                                        @if(!empty($collection->children))
                                       
                                            @foreach($collection->children as $children)
                                                            
                                              
                                                <div class="nav__panel"><a class="nav__panel-title" href="{{createUrl($children->urlRewrite)}}">{{$children->cat_name}}</a>
                                                    <ul class="nav__list">
                                                        @php
                                                            $childCount = 10;
                                                        @endphp
                                                        @foreach($children->childs as $childKey => $childCategory)
                                                            <li class="nav__list-item"><a class="nav__list-item__link " href="{{createUrl($childCategory->urlRewrite)}}"><span>{{$childCategory->cat_name}}</span></a></li>
                                                            @php
                                                                if($childKey == ($childCount-1)){
                                                                    break;
                                                                }
                                                            @endphp
                                                        @endforeach
                                                        @if (count($children->childs)>$childCount)
                                                            <li class="nav__list-item"><a class="nav__list-item__link " href="javascript:;"><span>{{__("LNK_VIEW_MORE")}}</span></a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </li>
            @php } elseif ($collection->navmenu_type == '2') { @endphp
                <li class="navchild"><a target="_self" href="{{createUrl($collection->pageUrls)}}">{{$collection->navmenu_label}} </a></li>
            @php } else { @endphp
                <li class="navchild"><a target="{{$collection->navmenu_target}}" href="{{$collection->navmenu_url}}">{{$collection->navmenu_label}} </a></li>
            @php } @endphp
        @endforeach
    @else
        @for($i = 1; $i <= 6; $i++)
            <li class="navchild"><a target="_self" href="#">{{__("LNK_MENU")}} {{$i}}</a></li>
        @endfor
    @endif
    @if(count($remainingNavItems) > 0)
        <li class="navchild more"><a target="_self" href="#">
            <svg class="svg" width="24" height="24"><use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#hemburger" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#hemburger"></use></svg>
            </a> <span class="link__mobilenav"></span>
            <div class="subnav">
                <div class="subnav__wrapper ">
                    <div class="container">
                        <div class="nav__sub-panels">
                            <div class="nav__sub-panels">
                                <div class="nav__panel ">
                                    <ul class="nav__list">
                                   
                                        @foreach($remainingNavItems as $collection)
                                        
                                            @php
                                            $url = '';
                                            $target = '_self';
                                            if ($collection->navmenu_type == '1') {
                                                $url = getRewriteUrl('categories',$collection->navmenu_record_id);
                                            } elseif ($collection->navmenu_type == '2') {
                                                $url = getRewriteUrl('pages',$collection->navmenu_record_id);
                                            } else {
                                                $url = $collection->navmenu_url;
                                                $target = $collection->navmenu_target;
                                            } 
                                            @endphp
                                            <li class="nav__list-item"><a class="nav__list-item__link " target="{{$target}}" href="{{$url}}"><span>{{$collection->navmenu_label}}</span></a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    @endif
</ul>
@php 
$languages = getLanguages();
$language = request()->session()->get('language');
$currencies = getCurrencies();
$selectedCurrency = currencyCode();
@endphp
<div class="short-links--mobile">
    <div class="short-link--item">
        <div class="YK-languageCurrencyDropdown">
            <ul class="nav nav--block list-checkbox-radio">
                <li class="dropdown-header">{{__("LBL_CHANGE_LANGUAGE")}}</li>
                @if(!empty($languages) && count($languages) > 0)   
                    @foreach($languages as $langCode => $langName)  
                    @php $languageUrl = url()->current() . '?language=' . $langCode . '#googtrans(auto|' . $langCode . ')'; @endphp              
                    <li class="dropdown-item nav__item @if(!empty($language['code']) && $langCode == $language['code']) selected @endif" onclick="window.location.href = '{{$languageUrl}}'" data-language-code="{{$langCode}}">
                        <label class="radio notranslate">
                        <input type="radio" value="1"  @if(!empty($language['code']) && $langCode == $language['code']) checked @endif> <i class="input-helper"></i>{{$langName}}</label>
                    </li>
                    @endforeach
                @endif
            </ul>
            <ul class="nav nav--block list-checkbox-radio">
                <li class="dropdown-header">{{__("LBL_CHANGE_CURRENCY")}}</li>
                @if(!empty($currencies) && count($currencies) > 0)
                    @foreach($currencies as $currency) 
                    <li class="dropdown-item nav__item YK-switchCurrency @if($currency->currency_code == $selectedCurrency) selected @endif"> 
                        <label class="radio"><input type="radio" value="{{$currency->currency_code}}" @if($currency->currency_code == $selectedCurrency) checked @endif> <i class="input-helper"></i>{{$currency->currency_code}}</label>
                    </li>
                    @endforeach
                @endif
            </ul>
        </div>
        
    </div>
    <div class="short-link--item">
    <h4>Theme Mode</h4>
        <a class="light theme-switch YK-themeSwitch" href="javascript:void(0)" data-light="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#dark" data-dark="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#light">     
            <i class="icn">
                <svg class="svg">
                    <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#dark" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#dark"></use>
                </svg>
            </i>
            <span class="icn-txt YK-mode">{{__('LBL_DARK_MODE') }}</span>
        </a>
    </div>
        
</div>