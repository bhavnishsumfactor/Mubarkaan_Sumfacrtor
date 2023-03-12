<div class="yk-newsletterCollection2-settings" data-comp="{{$cid}}">
  <div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" data-yk=""  role="yk-document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_NEWSLETTERCOLLECTION2')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="form-group row">            
                    <label class="col-md-4 col-form-label" for="validationCustom01">{{__("LBL_TEXT1")}}</label>
                  <div class="col-md-8"> <input type="text" class="form-control" name="text" value="{{$data['text'] ?? __('LBL_NEWSLETTER_TRY_SOMETHING_NEW')}}" required>
                </div>
            </div>
            <ul class="list-group">
              <li class="list-group-item preview-wrapper yk-slide">
                <div class="row">
                  <div class="col">
                      <img data-yk="" alt="" class="actualImage" src="{{$data['actual'] ?? ''}}" style="display:none;">
                      <div class="file-input overflow-hidden">
                          <button type="button" class="btn btn-secondary btn-wide js-openCropper">{{__("BTN_UPLOAD_FILE")}}</button> 
                      </div>
                      <p class="img-disclaimer editor"><strong>{{ __('LBL_BACKGROUND_IMAGE') }}: </strong> {{ __('LBL_IMAGE_RESTRICTIONS') }} 1800x300 {{__("LBL_IN")}} 6:1 {{ __('LBL_ASPECT_RATIO') }}</p>
                  </div>
                  <div class="col-auto preview-image YK-preview" @if(empty($data['cropped'])) style="display:none;" @endif>
                      <ul class="list-media">
                          <li class="media"><img data-yk="" alt="" class="croppedImage" src="{{$data['cropped'] ?? ''}}">
                          <div class="media-actions">
                            <button type="button" class="btn btn-icon btn-sm js-removeBackgroundImage"><i class="fas fa-times"></i></button>
                          </div>
                        </li>
                      </ul>
                  </div>
                </div>
              </li>
            </ul>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addNewsletterCollection2Widget">{{__('BTN_EMBED')}}</button>
        </div>
      </div>
    </div>
  </div>
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
