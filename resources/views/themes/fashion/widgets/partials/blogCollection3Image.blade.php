<a href="{{getRewriteUrl('blogs', $collection->post_id)}}">
    <picture class="blog_img">
        <source  data-aspect-ratio="4:3" type="image/webp" srcset="{{$collection->post_image}}">
        <img  data-aspect-ratio="4:3" src="{{$collection->post_image}}" data-yk="" alt="{{displayImageAttr($collection->afile_name, $collection->afile_attribute_alt)}}" title="{{displayImageAttr($collection->afile_name, $collection->afile_attribute_title)}}">
    </picture>
</a>