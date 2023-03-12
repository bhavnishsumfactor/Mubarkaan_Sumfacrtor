@if($faqs)
<ul class="list-group list-group-lg list-group-flush-x list-faqs" id="faqCollapseParentOne">
@foreach($faqs as $faq)
@php
$faq->faq_title = str_ireplace($search, '<span class="highlighted">'.$search.'</span>', $faq->faq_title);
@endphp
<li class="list-group-item">
    <a class="faq_trigger collapsed" data-toggle="collapse" href="#faqCollapse{{$faq->faq_id}}" aria-expanded="false">
          {!!$faq->faq_title!!}
    </a>
    <div class="collapse" id="faqCollapse{{$faq->faq_id}}" data-parent="#faqCollapseParentOne" style="">
        <div class="faq_data">
            <p>
                {!!$faq->faq_content!!}
            </p>
        </div>
    </div>
</li>
@endforeach
</ul>
<div class="pagination-wrapper mt-4">
    @if(count($faqs) > 0)
        {{ $faqs->links('partials.pagination') }}  
        <div class="show-all">
            <span class="showing-all">{{__("LBL_PAGINATION_SHOWING")}}: <strong>{{$faqs->firstItem()}} - {{$faqs->lastItem()}}</strong> {{__("LBL_PAGINATION_OF")}} <strong>{{$faqs->total()}}</strong>
            </span>
        </div>    
    @endif
</div>
@endif
