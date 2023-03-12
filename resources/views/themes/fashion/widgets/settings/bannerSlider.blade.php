<div class="yk-bannerSlider-settings" data-comp="{{$cid}}">
  <div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" data-yk=""  role="yk-document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_BANNER_SLIDER')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body scroll-y yk-perfectScrollbar" style="max-height:520px">
          <div class="alert alert-secondary" data-yk=""  role="yk-alert">
            <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
            <div class="alert-text">{{__("LBL_BANNER_SLIDER_INSTRUCTIONS")}}</div>
          </div>          
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="validationCustom01">{{__("LBL_SLIDE_DURATION")}}</label>
            <div class="col-md-8">
                <input type="number" min="1" max="10" class="form-control" name="slide_duration" placeholder="Enter in seconds" value="{{$data['slider_duration'] ?? ''}}" required>
            </div>
          </div>
          <div class="product">
            <ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-2x" data-yk=""  role="yk-tablist">
                @if(!empty($data['slides']))

                @php $totalSlider = count($data['slides']);@endphp
                @for($k =0; $k < $totalSlider; $k++)
                <li class="nav-item" data-id="sliderwidget-tab-{{$k+1}}"><a class="nav-link {{($k==0)?'active':''}}" data-toggle="tab" href="#sliderwidget-tab-{{$k+1}}"><span>{{__("LBL_SLIDE")}} {{$k+1}}</span>
                        <i class="fa fa-remove ml-2 yk-removeSlide"></i></a></li>
                @endfor
                @else
                <li class="nav-item" data-id="sliderwidget-tab-1"><a class="nav-link active" data-toggle="tab" href="#sliderwidget-tab-1"><span>{{__("LBL_SLIDE")}} 1</span><i class="fa fa-remove ml-2 yk-removeSlide"></i></a></li>
                @endif
                <li class="nav-item"><a class="nav-link yk-addNewSlide" href="javascript:;"><i class="fa fa-plus ml-2"></i></a></li>
            </ul>
            <div class="tab-content">
                @if(!empty($data['slides']))
                @foreach($data['slides'] as $k=>$item)
                @php
                $newTabNumber = $k+1;
                @endphp
                @include('themes.'.config('theme').'.widgets.settings.partials.bannerSlider', ['k'=>$k, 'item'=>$item, 'newTabNumber'=>$newTabNumber])
                @endforeach
                @else
                @include('themes.'.config('theme').'.widgets.settings.partials.bannerSlider', ['k'=>'', 'item'=>''])
                @endif
            </div>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addBannerSliderWidget">{{__('BTN_EMBED')}}</button>
        </div>
      </div>
    </div>
  </div>
<script>
  if (!$('.yk-perfectScrollbar').hasClass('ps')) {
    new PerfectScrollbar('.yk-perfectScrollbar');
  }
</script>  
  <div class="modal fade" style="display:none;" id="modal_cropper" data-backdrop="static" data-keyboard="false" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" data-yk=""  role="yk-document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_CROP')}}</h5>
            <button type="button" class="close closeCropper"></button>
        </div>
        <div class="modal-body">
          <div id="cropper-body">
              <div class="image-wrapper">
                  <img data-yk="" alt="" class="cropperSelectedImage" src="">
              </div>
              <canvas id="preview" style="display:none;"></canvas>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="tabId">
          <input type="hidden" name="slideType">
          <div class="file-input overflow-hidden">
              <label class="btn btn-outline-secondary" @click="openCropper()">{{__('BTN_UPLOAD_PICTURE')}}
                  <input class="file-input-field cropper-upload-picture js-cropperSelectImage" type="file" accept="image/*">
              </label>
          </div>
          <button type="button" class="btn btn-brand gb-btn gb-btn-primary js-cropImage">{{__('BTN_CROP_SAVE')}}</button>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>