<div class="yk-instagramLayout2-settings" data-comp="{{$cid}}">
<div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" data-yk=""  role="yk-document">
      <div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_INSTAGRAM_LAYOUT2')}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <div class="alert alert-secondary" data-yk=""  role="yk-alert">
      <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
      <div class="alert-text">{{__('LBL_INSTAGRAM_LAYOUT_INSTRUCTIONS')}} (https://www.instagram.com/sprouts/)</div>
    </div>
    <div class="form-group row">   
      <label class="col-md-4 col-form-label" for="validationCustom01">{{__("LBL_INSTAGRAM_HANDLE")}}</label>      
      <div class="col-md-8">
      <input type="text" class="form-control" name="handle" value="{{$data['handle'] ?? ''}}" required>
    </div>
  </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addInstagramLayout2Widget">{{__('BTN_EMBED')}}</button>
  </div>
  </div>
  </div>
  </div>
</div>