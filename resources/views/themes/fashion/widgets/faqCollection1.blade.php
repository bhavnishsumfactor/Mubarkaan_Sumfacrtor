@if($innerHtml==false)
<section class="section collection-faq yk-faqCollection1" data-gjs-draggable="div.js-body">
        <div class="container yk-container" compid="{{ $cid ?? '' }}">
            <div class="row justify-content-between">                
                <div class="col-md-4">
                    <div class="section-content">
                        <h2 class="yk-title">{{__("LBL_FREQUENTLY_ASKED_QUESTIONS")}}</h2>
                        <div class="section-action">
                            <a href="{{getPageByType('faq')}}" target="_blank" class="link-arrow yk-link">{{__("BTN_VIEW_ALL")}}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
@endif
                    <faqcollection1>
                        <ul class="list-group list-group-lg list-group-flush-x list-faqs" id="faqCollapseParentOne">
                            @if(!empty($collections) && count($collections) > 0)
                                @foreach($collections as $k => $collection)
                                    @php $time = time() . "_" . $k; @endphp
                                    <li class="list-group-item">
                                        <a class="faq_trigger" data-toggle="collapse" href="#faqCollapse{{$time}}">{{$collection->faq_title}}</a>
                                        <div class="collapse" id="faqCollapse{{$time}}" data-parent="#faqCollapseParentOne" >
                                            <div class="faq_data">
                                                <p>{{$collection->faq_content}}</p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                @for($i = 0; $i < 3; $i++)
                                @php $time = time() . "_" . $i; @endphp
                                    <li class="list-group-item">
                                        <a class="faq_trigger" data-toggle="collapse" href="#faqCollapse{{$time}}">
                                            {{__("LBL_DUMMY_FAQ_TITLE")}}
                                        </a>
                                        <div class="collapse" id="faqCollapse{{$time}}" data-parent="#faqCollapseParentOne" >
                                            <div class="faq_data">
                                                <p>{{__("LBL_DUMMY_FAQ_CONTENT")}}</p>
                                            </div>
                                        </div>
                                    </li>
                                @endfor
                            @endif 
                        </ul>
                    </faqcollection1>
@if($innerHtml==false)
                </div>
            </div>
        </div>
    </section>
@endif