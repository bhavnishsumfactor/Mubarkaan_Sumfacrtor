<div class="modalContent yk-textWithTwoImagesOnRight-settings" data-comp="{{$cid}}">
  <div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-lg" data-yk=""  role="yk-document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_TEXT_WITH_IMAGES_ON_RIGHT')}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul class="list-group yk-slides">    
            @if(!empty($data))
              @foreach($data as $key=>$item)
              <li class="list-group-item yk-slide" data-id="{{$key}}">
                <div class="row align-items-center">
                  <div class="col">
                      <h5>Image {{$key}}</h5>
                      <img data-yk="" alt="" class="actualImage" src="{{$item['banner_actual'] ?? ''}}" style="display:none;">
                      <div class="file-input overflow-hidden">
                          <button type="button" class="btn btn-secondary btn-wide js-openCropper">{{__("BTN_UPLOAD_FILE")}}</button> 
                      </div>
                      <p class="img-disclaimer editor"><strong>{{ __('LBL_IMAGE_DISCLAIMER') }}: </strong> {{ __('LBL_IMAGE_RESTRICTIONS') }} @if($key==1) 285x380 @else 375x500 @endif {{__("LBL_IN")}} 3:4 {{ __('LBL_ASPECT_RATIO') }}</p>
                  </div>
                  <div class="col-auto YK-preview" @if(empty($item['banner_cropped'])) style="display:none;" @endif>
                      <ul class="list-media">
                          <li class="media"><img data-yk="" alt="" class="croppedImage" src="{{$item['banner_cropped'] ?? ''}}">
                          </li>
                      </ul>
                  </div>
                </div>
              </li>
              @endforeach
            @endif
            </ul>   
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addTextWithTwoImagesOnRightWidget">{{__('BTN_EMBED')}}</button>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" style="display:none;" id="modal_cropper" data-backdrop="static" data-keyboard="false" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" data-yk=""  role="yk-document">
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