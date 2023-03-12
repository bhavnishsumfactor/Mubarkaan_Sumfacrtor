
@php 
$childCategories = $category->childs;
@endphp
@if($category->cat_parent == 0 && count($childCategories) == 0)

    <li class="menu__item  @if(!empty($searchedCategory) && $searchedCategory == $category->cat_id){{'active'}}@endif">
        <span class="menu__link-text">
            <a class="menu__link" href="{{url($detailPageUrl)}}">{{$category->cat_name}}</a>
        </span>
    </li>
@else
    <li class="menu__item  @if(!empty($searchedCategory) && $searchedCategory == $category->cat_id){{'active'}}@endif">
        <span class="menu__link-text">
            <a class="menu__link" href="{{url($detailPageUrl)}}">
            {{$category->cat_name}}</a>
            @if(count($childCategories) != 0)
            <i class="menu__ver-arrow fa fa-chevron-down collapsed" data-toggle="collapse" data-parent="#accordionMenu" href="#collapse{{$category->cat_id}}" aria-expanded="true"></i>
        @endif
        </span>
        <div id="collapse{{$category->cat_id}}" class="menu__submenu panel-collapse collapse">
            <ul class="menu__subnav">
                @foreach($childCategories as $childCategory)
               
                @php 
                $detailPageUrl = '';
                        if($childCategory->urlRewrite){
                        $detailPageUrl = $childCategory->urlRewrite->urlrewrite_original; 
                        if(!empty($childCategory->urlRewrite->urlrewrite_custom)){
                            $detailPageUrl = $childCategory->urlRewrite->urlrewrite_custom;
                        }
                    }
                        @endphp
                    @include('themes.'.config('theme').'.product.partials.sidebarChildCategories',['category'=>$childCategory, 'searchedCategory' => $searchedCategory, 'detailPageUrl' => $detailPageUrl])
                @endforeach
            </ul>
        </div>
    </li>
@endif 