@php
    $html = "";
    if($layout != "custom-code"){
        $html = view('themes.' . config('theme') . '.widgets.' . $layout, $params)->render();
    }
    $titleHtml = '';
    if(!in_array($layout, ["h1Tag", "h2Tag", "h3Tag", "h4Tag", "h5Tag", "h6Tag", "pTag", "ulTag", "divTag", "spanTag"])){
        $titleHtml = '<span class="mt-2 d-block">' . $title . '</span>';
    }
@endphp
bm.add("{{$layout}}", {
    label: `<svg class="svg" width="24px" height="24px">
                <use xlink:href="{{asset('admin/images/retina/sprite.svg#yk-' . $layout)}}" href="{{asset('admin/images/retina/sprite.svg#yk-' . $layout)}}"></use>
            </svg>
            {!! $titleHtml !!}`,
    attributes: {class: "d-flex align-items-center justify-content-center"},
    @if($layout != "custom-code")
    content: `{!! $html !!}`
    @endif
});