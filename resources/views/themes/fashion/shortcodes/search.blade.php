<div id="main-search-bar" class="main-search-bar yk-searchBarSection @if(isset($_GET['s'])) d-none @endif" data-close-on-click-outside="main-search-bar">
    <div class="container">
        <form id="searchform" class="header-search-form" action="{{url('/products')}}" method="get">
            <button type="submit" value="go" class="header-search-form__submit">
            <svg class="svg">
                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#magnifying" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#magnifying"></use>
            </svg>
            </button>
            <input id="s" name="s" value="@if(isset($_GET['s'])){{ $_GET['s'] }}@endif" class="header-search-form__input YK-headerSearchInput" type="text" placeholder="{{__('LBL_I_AM_LOOKING_FOR')}} ...">
            <button type="button" class="header-search-form__close YK-closeSearchBar">
            <svg class="svg">
                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#remove" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#remove"></use>
            </svg>                
            </button>
        </form>
    </div>
</div>