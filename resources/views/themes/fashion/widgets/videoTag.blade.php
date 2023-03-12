<div class="video__container ratio-16by9 position-relative yk-videoTag" compid="{{ $cid ?? '' }}">
  <div class="yk-container element--center video_frame_wrap" compid="{{ $cid ?? '' }}">@if(!empty($collections['video_url']))<iframe src="{{$collections['video_url'] ?? ''}}" width="100%" height="100%" allow="@if($collections['autoplay']==1) autoplay; @endif fullscreen"></iframe>@endif</div>
</div>
