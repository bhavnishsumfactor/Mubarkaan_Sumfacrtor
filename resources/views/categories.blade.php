@extends('layouts.full')

@section('content')
<div class="body" id="body" data-yk=""  >
    <section class="bg-gray">
        <div class="container">
            <nav class="breadcrumb" data-yk=""   aria-label="breadcrumbs">
                <li class="breadcrumb-item ">
                    <a href="{{url('/')}}" title="Back to the frontpage">{{__('LNK_HOME')}}</a>
                </li>
                <li class="breadcrumb-item ">
                    <a href="javascipt:;">{{__("LNK_CATEGORIES")}}</a>
                </li>
            </nav>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="section-content section-content-center">
                        <h2>{{__("LBL_ALL_CATEGORIES")}}</h2>
                    </div>
                    <ul class="list-collections">
                        @foreach($categories as $category)
                            @php                            
                                $categoryUrl = !empty($category->urlRewrite->urlrewrite_custom) ? $category->urlRewrite->urlrewrite_custom : $category->urlRewrite->urlrewrite_original;
                                $categoryImage = noImage('4_1/480x120.png');
                                if(!empty($category->uploadImages)){
                                    $categoryImage = url('yokart/image/'.$category->uploadImages->afile_id.'/480-120?t=' . strtotime($category->uploadImages->afile_updated_at));
                                }
                            @endphp 
                            <li class="list-collections__item">
                                <a href="{{url($categoryUrl)}}">
                                    <div class="aspect-ratio" style="padding-bottom: 45%">
                                        <div class="list-collections__image" style="background-image: url('{{$categoryImage}}');">
                                        </div>
                                    </div>
                                    <h6 class="list-collections__heading">{{$category->cat_name}}</h6>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection