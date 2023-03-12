<div class="modalContent yk-promotionalBannerCollection2-settings" data-comp="{{$cid}}">
  <div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" data-yk=""  role="yk-document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_PROMOTIONALBANNERCOLLECTION2')}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul class="list-group yk-slides">    
            @if(!empty($data))
              @foreach($data as $key=>$item)
              <li class="list-group-item yk-slide" data-id="{{$key}}">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="validationCustom01">{{__("LBL_LINK_URL")}}</label>
                    <div class="col-md-8"> <input type="text" class="form-control" name="link_{{$key}}" value="{{$data[$key]['link'] ?? ''}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{__("LBL_TARGET")}}</label>
                    <div class="col-md-8"> <select class="custom-select my-1 mr-sm-2" name="target_{{$key}}">
                            <option value='' readonly disabled selected>{{__("LBL_CHOOSE")}}...</option>
                            <option value="_self" @if(!empty($data[$key]['target']) && $data[$key]['target']=="_self" ) selected @endif>{{__("LBL_SAME_TAB")}}</option>
                            <option value="_blank" @if(!empty($data[$key]['target']) && $data[$key]['target']=="_blank" ) selected @endif>{{__("LBL_NEW_TAB")}}</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center">
                  <div class="col">
                      <h5>Image {{$key}}</h5>
                      <img data-yk="" alt="" class="actualImage" src="{{$item['actual'] ?? ''}}" style="display:none;">
                      <div class="file-input overflow-hidden">
                          <button type="button" class="btn btn-secondary btn-wide js-openCropper">{{__("BTN_UPLOAD_FILE")}}</button> 
                      </div>  
                      <p class="img-disclaimer editor"><strong>{{ __('LBL_IMAGE_DISCLAIMER') }}: </strong> {{ __('LBL_IMAGE_RESTRICTIONS') }} 800x600 {{__("LBL_IN")}} 4:3 {{ __('LBL_ASPECT_RATIO') }}</p>
                  </div>
                  <div class="col-auto YK-preview" @if(empty($item['cropped'])) style="display:none;" @endif>
                      <ul class="list-media">
                          <li class="media"><img data-yk="" alt="" class="croppedImage" src="{{$item['cropped'] ?? ''}}">
                          <div class="media-actions">
                            <button type="button" class="btn btn-icon btn-sm js-removeBackgroundImage"><i class="fas fa-times"></i></button>
                          </div>
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
          <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addPromotionalBannerCollection2Widget">{{__('BTN_EMBED')}}</button>
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