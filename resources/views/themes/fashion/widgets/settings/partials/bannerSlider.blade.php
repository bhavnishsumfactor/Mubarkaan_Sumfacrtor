<div class="tab-pane fade @if($k==0) show active @endif" data-slide-id="{{$item->slide_id ?? ''}}" id="sliderwidget-tab-{{$newTabNumber}}">
    <ul class="list-group list-group-lg list-uploads mb-4">
        <li class="list-group-item yk-slide" data-type="desktop" data-aspect-ratio="4">
            <div class="row">
                <div class="col-12">
                    <img data-yk="" class="actualImage" src="{{$item->desktop_actual ?? ''}}" style="display:none;">
                    <input type="hidden" name="actualImage" >
                    <div class="file-input overflow-hidden">
                        <button type="button" class="btn btn-secondary btn-wide js-openCropper">{{__("BTN_UPLOAD_FILE")}}</button> 
                    </div>             
                    <p class="img-disclaimer editor"><strong>{{ __('LBL_DESKTOP') }}: </strong> {{ __('LBL_IMAGE_RESTRICTIONS') }} 2000x500 {{__("LBL_IN")}} 4:1 {{ __('LBL_ASPECT_RATIO') }}</p>
                </div>
                <div class="col-12 YK-preview" @if(empty($item->desktop_cropped)) style="display:none;" @endif>
                    <ul class="list-media">
                        <li class="media"><img data-yk="" class="croppedImage" src="{{$item->desktop_cropped ?? ''}}">
                            <div class="media-actions">   
                                <button type="button" class="btn btn-icon btn-sm js-removeSlideDeviceImage"><i class="fas fa-times"></i></button>
                            </div>
                        </li>
                    </ul>
                </div>                   
            </div>
        </li>
        <li class="list-group-item yk-slide" data-type="tablet" data-aspect-ratio="1.5">
            <div class="row">
                <div class="col-12">
                    <img data-yk="" class="actualImage" src="{{$item->tablet_actual ?? ''}}" style="display:none;">
                    <div class="file-input overflow-hidden">
                        <button type="button" class="btn btn-secondary btn-wide js-openCropper">{{__("BTN_UPLOAD_FILE")}}</button> 
                    </div>               
                    <p class="img-disclaimer editor"><strong>{{ __('LBL_TABLET') }}: </strong> {{ __('LBL_IMAGE_RESTRICTIONS') }} 1200x800 {{__("LBL_IN")}} 3:2 {{ __('LBL_ASPECT_RATIO') }}</p>
                </div>
                <div class="col-12 YK-preview" @if(empty($item->tablet_cropped)) style="display:none;" @endif>
                    <ul class="list-media">
                        <li class="media"><img data-yk="" class="croppedImage" src="{{$item->tablet_cropped ?? ''}}">
                        <div class="media-actions">
                            <button type="button" class="btn btn-icon btn-sm js-removeSlideDeviceImage"><i class="fas fa-times"></i></button>
                        </div>
                        </li>
                    </ul>
                </div>    
            </div>
        </li>
        <li class="list-group-item yk-slide" data-type="mobile" data-aspect-ratio="1.33">
            <div class="row">
                <div class="col-12">
                    <img data-yk="" class="actualImage" src="{{$item->mobile_actual ?? ''}}" style="display:none;">
                    <div class="file-input overflow-hidden">
                        <button type="button" class="btn btn-secondary btn-wide js-openCropper">{{__("BTN_UPLOAD_FILE")}}</button> 
                    </div>             
                    <p class="img-disclaimer editor"><strong>{{ __('LBL_MOBILE') }}: </strong> {{ __('LBL_IMAGE_RESTRICTIONS') }} 800x600 {{__("LBL_IN")}} 4:3 {{ __('LBL_ASPECT_RATIO') }}</p>
                </div>
                <div class="col-12 YK-preview" @if(empty($item->mobile_cropped)) style="display:none;" @endif>
                    <ul class="list-media">
                        <li class="media"><img data-yk="" class="croppedImage" src="{{$item->mobile_cropped ?? ''}}">
                            <div class="media-actions">
                                <button type="button" class="btn btn-icon btn-sm js-removeSlideDeviceImage"><i class="fas fa-times"></i></button>
                            </div>
                        </li>
                    </ul>
                </div>      
            </div>
        </li>
    </ul>
	
    <div class="form-group row">
        <label class="col-md-4 col-form-label" for="validationCustom01">{{__("LBL_URL")}}</label>
        <div class="col-md-8"><input type="text" class="form-control" name="url" value="{{$item->slide_url ?? ''}}" required></div>
    </div>
    <div class="form-group row mb-0">
        <label class="col-md-4 col-form-label">{{__("LBL_TARGET")}}</label>
        <div class="col-md-8"> <select class="form-control" name="target">
                <option value='' readonly disabled selected>{{__("LBL_CHOOSE")}}...</option>
                <option value="_self" @if(!empty($item->slide_target) && $item->slide_target == "_self") selected @endif >{{__("LBL_SAME_TAB")}}</option>
                <option value="_blank" @if(!empty($item->slide_target) && $item->slide_target == "_blank") selected @endif >{{__("LBL_NEW_TAB")}}</option>
            </select> </div>
    </div>


</div>