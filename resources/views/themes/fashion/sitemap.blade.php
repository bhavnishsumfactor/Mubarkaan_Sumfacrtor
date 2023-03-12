@extends('layouts.full')

@section('content')
<div class="body" id="body" data-yk=""  >
    <section class="bg-gray">
        <div class="container">
            @include('themes.'.config('theme').'.partials.breadcrumb')
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="row  justify-content-center">
                <div class="col-md-8">
                    <div class="site-map">
                        <h3>{{__("LBL_BRANDS")}}</h3>
                        <ul class="site-map__brand">
                            <li>
                                <div class="site-map-section">
                                    <ul>
                                        @foreach($brands as $brand)
                                            <li><a href="javascript:;">{{ $brand->brand_name}} </a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>                    
                    <div class="site-map">
                        <h3>{{__("LBL_PAGES")}}</h3>
                        <ul class="site-map__pages">
                            <li>
                                <div class="site-map-section">
                                    <ul>
                                        @foreach($pages as $page)
                                            <li><a href="{{ getRewriteUrl('pages', $page['page_id']) }}">{{ $page['page_name'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>                    
                    <div class="site-map">
                        <h3>{{__("LBL_CATEGORIES")}}</h3>
                        <ul class="site-map__categories">
                            @foreach($productCategories as $category)
                                <li>
                                    <div class="site-map-section">
                                        <h6>{{ $category['cat_name'] }}</h6>
                                        <ul>
                                            @if (count($category['children']) > 0)
                                                @foreach($category['children'] as $child)
                                                    @include('themes.'.config('theme').'.partials.sitemapPartial', ['child' => $child ])
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection