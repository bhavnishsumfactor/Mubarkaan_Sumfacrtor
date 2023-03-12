<div class="block-sticky">
    <div class="panel-box-blog">
        <!--<div class="panel-box-search">
            <form class="sub-form mb-4" action="{{route('blogListing')}}">
                <input placeholder="Search" class="no--focus" type="text" name="query" value="{{ app('request')->input('query') }}">
            </form>
        </div>-->
    </div>
    <div class="panel-box-blog">
        <h6 class="mb-3">{{__('LBL_BLOGS_CATEGORIES')}}</h6>
        <ul class="list-group list-group-flush-x mb-5 list-categories">
        @foreach($categories as $category)
            <li class="list-group-item">
                <a href="{{route('blogListing',$category->bpcat_id)}}" class="d-flex justify-content-between align-items-center">{{$category->bpcat_name}}({{$category->blogs_count}})<span><i class="fa fa-chevron-right" aria-hidden="true"></i></span></a>
            </li>
        @endforeach
        </ul>   
    </div>
</div>
