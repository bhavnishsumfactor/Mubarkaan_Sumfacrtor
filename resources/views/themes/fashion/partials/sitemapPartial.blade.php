<li><a href="{{ getRewriteUrl('categories',$child['cat_id'])}}">{{ $child['cat_name'] }}</a></li>
@if (count($category['children']) > 0)
    @foreach($child['children'] as $childCat)
        @include('themes.'.config('theme').'.partials.sitemapPartial', ['child' => $childCat ])
    @endforeach
@endif
