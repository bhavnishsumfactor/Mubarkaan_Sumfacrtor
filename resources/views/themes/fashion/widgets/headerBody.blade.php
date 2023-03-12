<div class="yk-body yk-header yk-navigationLayout1 header-main" data-gjs-draggable="header">
    <div class="header-outer " compid="{{ $cid ?? '' }}">
        <div class="header-top YK-topHeader">
            <div class="container">
                <div class="header-top_inner">
                    <p class="slogan">{{!empty($welcome_text) ? $welcome_text : __("LBL_WELCOME_TO_THE_STORE")}} 
                        <a class="slogan_close YK-closeSlogan" href="javascript:void(0)">
                            <svg class="svg">
                                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#remove" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#remove"></use>
                            </svg>
                        </a>
                    </p>                   
                </div>
            </div>
        </div>
        <div class="header-middle">
            <searchinput-shortcode>
                <div id="main-search-bar" class="main-search-bar yk-searchBarSection" data-close-on-click-outside="main-search-bar">
                    <div class="container">
                        <form id="searchform" class="header-search-form" action="{{url('/products')}}" method="get">
                            <button type="submit" value="go" class="header-search-form__submit">
                                <svg class="svg">
                                    <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#magnifying" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#magnifying"></use>
                                </svg>
                            </button>
                            <input id="s" name="s" class="header-search-form__input" type="text" placeholder="{{__('LBL_I_AM_LOOKING_FOR')}} ...">
                            <button type="button" class="header-search-form__close YK-closeSearchBar">
                                <svg class="svg">
                                    <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#remove" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#remove"></use>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </searchinput-shortcode>
            <div class="container">
                <div class="logo-bar">
                    @include('themes.'.config('theme').'.widgets.languageCurrency')
                    <a class="navs_toggle" href="javascript:void(0)"><span></span> <span></span> <span></span></a>
                    @include('themes.'.config('theme').'.widgets.logo')

                    @include('themes.'.config('theme').'.widgets.partials.accountCart')
                </div>
            </div>
        </div>
        <div class="header-bottom"> 
            @include('themes.'.config('theme').'.widgets.navigationLayout1')

        </div>
    </div>
    <div class="search-overlay"></div>
</div>