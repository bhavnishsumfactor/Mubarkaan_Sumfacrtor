<div class="yk-promotionalBannerLayout1-settings" data-comp="{{$cid}}">
  <div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" data-yk=""  role="yk-document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_PROMOTIONAL_BANNER')}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="form-group row">

              <label class="col-md-4 col-form-label" for="validationCustom01">{{__("LBL_TEXT1")}}</label>
              <div class="col-md-8"><input type="text" class="form-control" name="text1" value="{{$data['text1'] ?? '100+ new products and combinations'}}" required>
              </div>
          </div>
          <div class="form-group row">

              <label class="col-md-4 col-form-label" for="validationCustom01">{{__("LBL_TEXT2")}}</label>
              <div class="col-md-8"> <input type="text" class="form-control" name="text2" value="{{$data['text2'] ?? 'UTOPIA'}}" required>
              </div>
          </div>
          <div class="form-group row">

              <label class="col-md-4 col-form-label" for="validationCustom01">{{__("LBL_BUTTON_LABEL")}}</label>
              <div class="col-md-8"> <input type="text" class="form-control" name="cta_label" value="{{$data['cta_label'] ?? 'BROWSE THE CATALOG'}}" required>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-md-4 col-form-label" for="validationCustom01">{{__("LBL_BUTTON_LINK_URL")}}</label>
              <div class="col-md-8"> <input type="text" class="form-control" name="cta_link" value="{{$data['cta_link'] ?? ''}}" required>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-md-4 col-form-label">{{__("LBL_BUTTON_TARGET")}}</label>
              <div class="col-md-8"> <select class="custom-select my-1 mr-sm-2" name="cta_target">
                      <option value='' readonly disabled selected>{{__("LBL_CHOOSE")}}...</option>
                      <option value="_self" @if(!empty($data['cta_target']) && $data['cta_target']=="_self" ) selected @endif>{{__("LBL_SAME_TAB")}}</option>
                      <option value="_blank" @if(!empty($data['cta_target']) && $data['cta_target']=="_blank" ) selected @endif>{{__("LBL_NEW_TAB")}}</option>
                  </select>
              </div>
          </div>
          <ul class="list-group">
              <li class="list-group-item preview-wrapper yk-slide">
                <div class="row">
                  <div class="col">
                      <img data-yk="" alt="" class="actualImage" src="{{$data['banner_actual'] ?? ''}}" style="display:none;">
                      <div class="file-input overflow-hidden">
                          <button type="button" class="btn btn-secondary btn-wide js-openCropper">{{__("BTN_UPLOAD_FILE")}}</button> 
                      </div>
                      <p class="img-disclaimer editor"><strong>{{ __('LBL_BACKGROUND_IMAGE') }}: </strong> {{ __('LBL_IMAGE_RESTRICTIONS') }} 2000x1000 {{__("LBL_IN")}} 2:1 {{ __('LBL_ASPECT_RATIO') }}</p>
                  </div>
                  <div class="col-auto preview-image YK-preview" @if(empty($data['banner_cropped'])) style="display:none;" @endif>
                      <ul class="list-media">
                          <li class="media"><img data-yk="" alt="" class="croppedImage" src="{{$data['banner_cropped'] ?? ''}}">
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
          <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addPromotionalBannerLayout1Widget">{{__('BTN_EMBED')}}</button>
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