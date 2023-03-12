<header class="header">
    <div class="container">
        <div class="header_inner">
            <div class="logo">
                <a href="{{url('/')}}">
                    {!! getFrontendLogoHtml() !!}
                </a>
            </div>
            <ul class="side-account">
                <li class="">
                    <a class="" href="javascript:void(0);" data-trigger="main-search-bar">
                        <i class="icn">
                            <svg class="svg">
                                <use xlink:href="{{asset('blogs/images/retina/sprite.svg')}}#magnifying" href="{{asset('blogs/images/retina/sprite.svg')}}#magnifying"></use>
                            </svg>

                        </i>
                    </a>
                </li>
                <li class="YK-darkMode">
                    <a class="light YK-themeSwitch" href="javascript:void(0)" data-light="{{asset('blogs/images/retina/sprite.svg#dark')}}" data-dark="{{asset('blogs/images/retina/sprite.svg#light')}}">     
                        <i class="icn">
                            <svg class="svg">
                                <use xlink:href="{{asset('blogs/images/retina/sprite.svg#dark')}}" href="{{asset('blogs/images/retina/sprite.svg#dark')}}"></use>
                            </svg>
                        </i>
                        <span class="icn-txt YK-mode">{{__('LBL_DARK_MODE')}}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div id="main-search-bar" class="main-search-bar" data-close-on-click-outside="main-search-bar">
        <div class="container">
            <form id="searchform" class="header-search-form" action="{{route('blogListing')}}" method="get">
                <button type="submit" value="go" class="header-search-form__submit">
                    <svg class="svg">
                        <use xlink:href="{{asset('blogs/images/retina/sprite.svg')}}#magnifying" href="{{asset('blogs/images/retina/sprite.svg')}}#magnifying"></use>
                    </svg>
                </button>
                <input class="header-search-form__input YK-headerSearchInput focus" type="text" placeholder="I am looking for ..." name="query" value="{{ app('request')->input('query') }}">
                <button type="button" class="header-search-form__close YK-closeSearchBar">
                    <svg class="svg">
                        <use xlink:href="{{asset('blogs/images/retina/sprite.svg')}}#remove" href="{{asset('blogs/images/retina/sprite.svg')}}#remove"></use>
                    </svg>
                </button>
            </form>
        </div>
    </div>

</header>