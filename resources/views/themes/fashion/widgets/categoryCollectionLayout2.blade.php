<section class="section collection-categories yk-categoryCollectionLayout2" data-gjs-draggable="div.js-body">
    <div class="container yk-container" compid="{{ $cid ?? '' }}">
        <div class="section-content">
            <h2 class="yk-title">{{__("LBL_CATEGORY_COLLECTION2_TITLE")}}</h2>
            <div class="section-action"><a href="BASE_URL/categories" class="link-arrow yk-link">{{__("BTN_VIEW_ALL")}}</a></div>
        </div>
        <div class="categories-top yk-categories">
            @if(!empty($collections))
            @foreach($collections as $k=>$collection)

            @php 
                $image = url('yokart/image/categoryCollectionLayout2/'.$collection->collection_id.'/0/250-250?t=' .strtotime($collection->afile_updated_at));
                $webp = url('yokart/image/categoryCollectionLayout2/'.$collection->collection_id.'/0/250-250-webp?t=' .strtotime($collection->afile_updated_at));
                if (empty($collection->afile_id)) {
                    $image = $webp = noImage("1_1/250x250.png");
                }
                
                @endphp

                <div class="cat-block"> 
                    <div class="categories__item">
                        <div class="categories__item_inner">
                            <div class="img-box">
                                <a href="{{createUrl($collection->urlRewrite)}}" class="animate-scale">
                                    <picture class="img">
                                    <source  data-aspect-ratio="1:1" type="image/webp" srcset="{{$webp}}">
                                        <img data-yk="" data-aspect-ratio="1:1" src="{{$image}}" alt="">
                                    </picture>
                                </a>
                            </div>
                            <div class="categories__text">
                                <h3>
                                    <a class="btn btn-link " href="{{createUrl($collection->urlRewrite)}}"><span>{{$collection->cat_name}}</span>
                                        <i class="arrow"> <svg class="svg">
                                            <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#arrow-right"
                                                href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#arrow-right"></use>
                                        </svg></i>
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @else
                @for ($i = 0; $i < 6; $i++)
                    <div class="cat-block">
                        <div class="categories__item">
                            <div class="categories__item_inner">
                                <div class="img-box">
                                    <a href="javascript:;" class="animate-scale">
                                        <picture class="img">
                                            <img data-yk="" data-aspect-ratio="1:1" src="{{ noImage('1_1/250x250.png') }}" alt="">
                                        </picture>
                                    </a>
                                </div>
                                <div class="categories__text">
                                    <h3>
                                        <a class="btn btn-link " href="javascript:;"><span>{{__("LBL_CATEGORY")}}</span>
                                            <i class="arrow"> <svg class="svg">
                                                <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#arrow-right"
                                                    href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#arrow-right"></use>
                                            </svg></i>
                                        </a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            @endif
        </div>
    </div>
</section>