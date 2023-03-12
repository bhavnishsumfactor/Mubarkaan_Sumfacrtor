<a class="rate-item @if($review->helped == 0) YK-helpful @endif" data-toggle="vote" data-id="{{$review->preview_id}}" data-count="{{$review->helped_count}}" href="javascript:;" data-yk=""  role="yk-button">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
        <g transform="translate(-1230 -3226)">
            <path d="M2591.6,157.285l-1.067,4.9a2.5,2.5,0,0,1-2.411,1.838h-10.166a.9.9,0,0,1-.9-.9v-7.172a.9.9,0,0,1,.9-.9h3.021l4.3-4.868a.491.491,0,0,1,.493-.152l.735.179a1.764,1.764,0,0,1,.359.143,1.5,1.5,0,0,1,.583,2.044l-1.04,1.874a.464.464,0,0,0-.062.242.5.5,0,0,0,.5.5h2.949a1.57,1.57,0,0,1,.412.045A1.842,1.842,0,0,1,2591.6,157.285Z" transform="translate(-1345.061 3077.973)" />
        </g>
    </svg>
</a>
<a class="rate-item @if($review->nothelped == 0) YK-nothelpful @endif" data-toggle="vote" data-id="{{$review->preview_id}}" data-toggle="vote" data-count="{{$review->nothelped_count}}" href="javascript:;" data-yk=""  role="yk-button"> 
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
        <g transform="translate(-1317 -2707)">
            <path d="M2577.109,156.768l1.067-4.9a2.5,2.5,0,0,1,2.411-1.838h10.165a.9.9,0,0,1,.9.9v7.172a.9.9,0,0,1-.9.9h-3.021l-4.3,4.868a.491.491,0,0,1-.494.152l-.735-.179a1.752,1.752,0,0,1-.358-.143,1.5,1.5,0,0,1-.583-2.044l1.04-1.874a.467.467,0,0,0,.063-.242.5.5,0,0,0-.5-.5h-2.949a1.572,1.572,0,0,1-.412-.045A1.842,1.842,0,0,1,2577.109,156.768Z" transform="translate(-1258.06 2558.973)"/>
        </g>
    </svg>
</a>