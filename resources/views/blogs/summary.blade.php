<div class="inner-block">
    <a href="{{url('').'/'.$latestBlog->record_slug}}"><h3>{{$latestBlog->post_title}}</h3></a>
    <p>{{Str::limit($latestBlog->bpc_short_description, 200)}}</p>
    <div class="blog-date">{{__("LBL_BY")}} <strong>{{$latestBlog->post_author_name}}</strong> - <span>{{ getConvertedDateTime($latestBlog->post_created_at) }}</span></div>
</div>