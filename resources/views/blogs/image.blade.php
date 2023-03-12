<div class="blog-media">
    <a href="{{url('').'/'.$latestBlog->record_slug}}">
        <img data-yk="" src="@if(!empty($latestBlog->afile_id)) {{ url('yokart/image/blogImage/'.$latestBlog->post_id.'/0/1000-750?t=' . strtotime($latestBlog->post_updated_at)) }} @else {{noImage('4_3/1000x750.png')}} @endif" alt="{{displayImageAttr($latestBlog->afile_name, $latestBlog->afile_attribute_alt)}}" title="{{displayImageAttr($latestBlog->afile_name, $latestBlog->afile_attribute_title)}}" data-aspect-ratio="4:3">
    </a>
</div>