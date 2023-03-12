<ul class="side-account">
    <li class="">
        <a class="YK-openSearchBar" href="javascript:void(0);" data-trigger="main-search-bar">
            <i class="icn">
                <svg class="svg">
                    <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#magnifying" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#magnifying"></use>
                </svg>
            </i>
            <span class="icn-txt">{{__('LBL_SEARCH')}}</span>
        </a>
    </li>
    <li>
        <cart-shortcode>
            <div class="header-cart">
                <a href="{{route('cartindex')}}" class="shopping-cart YK-shoppingCart">
                    <i class="icn">
                        <svg class="svg">
                            <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#shopping-bag" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#shopping-bag"></use>
                        </svg>
                    </i>
                    <span class="cart-items YK-cartItems" data-item-counts="0">0</span>
                </a>
            </div>
        </cart-shortcode>
    </li>     
    <li>
        <login-shortcode>
            <a class="d-flex" data-toggle="modal" href="#modallogin">
                <i class="icn">
                    <svg class="svg">
                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#user-icon" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#user-icon"></use>
                    </svg>
                </i>
                <span class="icn-txt">{{__("LBL_LOGIN")}}</span>
            </a>
        </login-shortcode>
    </li>
    


</ul>