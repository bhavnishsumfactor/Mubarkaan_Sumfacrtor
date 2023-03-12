@extends('layouts.full')

@section('content')
<div class="body" id="body" data-yk="" >
    <section class="bg-gray">
        <div class="container">
            <nav class="breadcrumb" data-yk=""  aria-label="breadcrumbs">
                <li class="breadcrumb-item ">
                    <a href="{{url('/')}}" title="Back to the frontpage">{{__('LNK_HOME')}}</a>
                </li>
                <li class="breadcrumb-item ">
                    <a href="javascipt:;">{{__("LNK_FAQS")}}</a>
                </li>
            </nav>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-10 col-xl-8">
                            <h3 class="mb-1 mb-md-5 text-center">{{__('LBL_FREQUENTLY_ASKED_QUESTIONS')}}</h3>
                            <div class="faqsearch mb-3">
                                <div class="input-icon input-icon--left">
                                    <div class="yk-searchInputWrapper">
                                        <input type="text" class="form-control" name="searchFaqs" placeholder="{{__('LBL_SEARCH')}}..." id="generalSearch" autocomplete="off">
                                    </div>
                                    <span class="input-icon__icon input-icon__icon--left">
                                        <span><i class="fas fa-search"></i></span>
                                    </span>
                                </div>
                            </div>
                            <div class="faq-filters mb-5 yk-faqsCategories" id="categoryPanel">
                            </div>
                            <div class="yk-faqsHtml">
                            </div>
                        </div>
                        <div class="col-xl-10 yk-notFound" style="display:none;">
                            @php $url = route('products.index'); @endphp
                            @include('themes.'.config('theme').'.partials.noRecordFound',['heading'=>__('LBL_NO_FAQS'), 'text1'=> __('LBL_TRY_MODIFYING_SEARCH'),'size'=>'large', 'headImage'=> asset('yokart/'.config('theme').'/media/retina/empty/faq-empty.svg')])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@section('scripts')
@parent
<script>
    faqSearch();
    $(document).on('keyup', 'input[name="searchFaqs"]', function(e) {
        e.preventDefault();
        let search = $('input[name="searchFaqs"]').val();
        faqSearch(search, '');
    });
    $(document).on('click', '.yk-faqsCategories a', function(e) {
        e.preventDefault();
        let search = $('input[name="searchFaqs"]').val();
        let categories_id = $(this).attr('id');
        if ($(this).hasClass('is--active')) {
            $(this).removeClass('is--active');
            faqSearch(search, '');
            return false;
        }
        $('.yk-faqsCategories a').removeClass('is--active');
        $(this).addClass('is--active');
        faqSearch(search, categories_id);

    });
    $(document).on('click', '.pagination li.page-item a.page-link', function(e) {
        e.preventDefault();
        let search = $('input[name="searchFaqs"]').val();
        let categories_id = $('.yk-faqsCategories a.is--active').attr('id');
        let page = getUrlVars($(this).attr('href'))["page"];
        page = '?page=' + page;
        faqSearch(search, categories_id, page);
    });

    function faqSearch(search, categories_id, page = '') {
        NProgress.start();
        $('.yk-searchInputWrapper').addClass('spinner spinner--sm spinner--success spinner--right spinner--input');
        $.ajax({
            url: baseUrl + '/faqs' + page,
            type: "post",
            data: {
                search: search,
                category_id: categories_id
            },
            success: function(response) {
                NProgress.done();
                if (typeof response.data.categories != 'undefined') {
                    $('.yk-faqsCategories').html('');
                    $.each(response.data.categories, function(index, val) {
                        $('.yk-faqsCategories').append(`<a href="javascript:void(0);" id="` + index + `" class="">` + val + `</a>`);
                    });
                }
                if (response.data.faqsHtml != '') {
                    $('.yk-notFound').hide();
                    $('.yk-faqsHtml').html(response.data.faqsHtml);
                } else {
                    $('.yk-notFound').show();
                }
                if (response.data.faqs.data.length > 0) {
                    $('.yk-notFound').hide();
                } else {
                    $('.yk-notFound').show();
                }
                $('.yk-searchInputWrapper').removeClass('spinner spinner--sm spinner--success spinner--right spinner--input');
            }
        });
    }

    // Read a page's GET URL variables and return them as an associative array.
    function getUrlVars(href) {
        var vars = [],
            hash;
        if (typeof href == 'undefined') {
            let href = window.location.href;
        }
        var hashes = href.slice(href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }
</script>

@endsection
@endsection