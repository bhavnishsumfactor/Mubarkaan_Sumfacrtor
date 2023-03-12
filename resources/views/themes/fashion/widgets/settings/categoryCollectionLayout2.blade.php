@if($updateListing == 0)
<div class="modalContent yk-categoryCollectionLayout2-settings" data-comp="{{$cid}}">
  <div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-lg" data-yk=""  role="yk-document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_CATEGORY_LAYOUT2')}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-secondary" data-yk=""  role="yk-alert">
            <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
            <div class="alert-text">{{__('LBL_CATEGORY_LAYOUT2_INSTRUCTIONS')}}</div>
          </div>
          <div class="form-group">
            <div class="input-icon input-icon--left">
              <input type="text" class="form-control yk-autocompleteCategories" placeholder="{{__('LBL_SEARCH_CATEGORY')}}..." name="cat_name" autocomplete="off">        
              <span class="input-icon__icon input-icon__icon--left" ><span><i class="la la-search"></i></span></span>
            </div>
          </div>
          @php
            $highestOrder = 0;
            if (!empty($data) && count($data)>0) {
              $lastElement = last($data)['value'];
              $highestOrder = $recordIds[$lastElement];
            }
          @endphp
          <div class="scroll-y yk-perfectScrollbar">
          <ul class="list-group yk-selectedCategories yk-sortable" data-highest-order="{{$highestOrder}}">    
            @if(!empty($data))
              @foreach($data as $item)
              <li class="list-group-item yk-slide yk-category" data-id="{{$item['value']}}" data-display-order="{{$recordIds[$item['value']]}}">
                <div class="row align-items-center">
                  <div class="col-auto"><i class="icon fa fa-arrows-alt handle"></i> </div>
                  <div class="col">
                      <h5>{{$item['label']}}</h5>
                      <img data-yk="" alt="" class="actualImage" src="{{$item['banner_actual'] ?? ''}}" style="display:none;">
                      <div class="file-input overflow-hidden">
                          <button type="button" class="btn btn-secondary btn-wide js-openCropper">{{__("BTN_UPLOAD_FILE")}}</button> 
                      </div>
                      <p class="img-disclaimer editor"><strong>{{ __('LBL_IMAGE_DISCLAIMER') }}: </strong> {{ __('LBL_IMAGE_RESTRICTIONS') }} 250x250 {{__("LBL_IN")}} 1:1 {{ __('LBL_ASPECT_RATIO') }}</p>
                  </div>
                  <div class="col-auto YK-preview" @if(empty($item['banner_cropped'])) style="display:none;" @endif>
                      <ul class="list-media">
                          <li class="media"><img data-yk="" alt="" class="croppedImage" src="{{$item['banner_cropped'] ?? ''}}">
                          <div class="media-actions"><button type="button" class="btn btn-icon btn-sm js-removeBackgroundImage"><i class="fas fa-times"></i></button></div></li>
                      </ul>
                  </div>
                  <div class="actions">
                    <button type="button" class="btn btn-icon yk-removecategoryCollectionLayout2">
                        <svg>   
                        <use xlink:href="{{asset('admin/images/retina/sprite.svg')}}#delete-icon" ></use>                               
                        </svg>
                    </button>
                </div>
                </div>
              </li>
              @endforeach
            @endif
            </ul>   
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addCategoryCollectionLayout2Widget">{{__('BTN_EMBED')}}</button>
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
<script>
  if (!$('.yk-perfectScrollbar').hasClass('ps')) {
    new PerfectScrollbar('.yk-perfectScrollbar');
  }
</script>   
@else
  @if(!empty($data))
    @foreach($data as $item)
      <li class="list-group-item yk-slide yk-category" data-id="{{$item['value']}}" data-display-order="{{$recordIds[$item['value']]}}">
        <div class="row align-items-center">
          <div class="col-auto"><i class="icon fa fa-arrows-alt handle"></i> </div>
          <div class="col">
              <h5>{{$item['label']}}</h5>
              <img data-yk="" alt="" class="actualImage" src="{{$item['banner_actual'] ?? ''}}" style="display:none;">
              <div class="file-input overflow-hidden">
                  <button type="button" class="btn btn-secondary btn-wide js-openCropper">{{__("BTN_UPLOAD_FILE")}}</button> 
              </div>   
              <p class="img-disclaimer editor"><strong>{{ __('LBL_IMAGE_DISCLAIMER') }}: </strong> {{ __('LBL_IMAGE_RESTRICTIONS') }} 250x250 {{__("LBL_IN")}} 1:1 {{ __('LBL_ASPECT_RATIO') }}</p>
          </div>
          <div class="col-auto YK-preview" @if(empty($item['banner_cropped'])) style="display:none;" @endif>
              <ul class="list-media">
                  <li class="media"><img data-yk="" alt="" class="croppedImage" src="{{$item['banner_cropped'] ?? ''}}">
                  <div class="media-actions">
                  <button type="button" class="btn btn-icon btn-sm js-removeBackgroundImage"><i class="fas fa-times"></i></button>
                  </div>
                </li>
              </ul>
          </div>
          <div class="actions">
                    <button type="button" class="btn btn-icon yk-removecategoryCollectionLayout2">
                        <svg>   
                        <use xlink:href="{{asset('admin/images/retina/sprite.svg')}}#delete-icon" ></use>                               
                        </svg>
                    </button>
                </div>
        </div>
      </li>
    @endforeach
  @endif
@endif