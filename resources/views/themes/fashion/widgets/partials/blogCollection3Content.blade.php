<figcaption>
    <h5 class="from">{{__("LBL_BY")}} {{$collection->post_author_name}} {{__("LBL_ON")}} <span>{{$collection->post_published_on}}</span></h5>
    <h4><a href="{{getRewriteUrl('blogs', $collection->post_id)}}">{{$collection->post_title}}</a></h4>
    <p>{{Str::limit($collection->content->bpc_short_description, 150, $end='...')}}</p>
</figcaption>