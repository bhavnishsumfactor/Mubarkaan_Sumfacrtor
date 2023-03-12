<div class="yk-twoColumnTextCta-settings" data-comp="{{$cid}}">
  <div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" data-yk=""  role="yk-document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_TWO_COLUMN_TEXT_WITH_BUTTON')}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="form-group row">
              <label class="col-md-4 col-form-label" for="validationCustom01">{{__('LBL_BUTTON_LABEL')}}</label>
              <div class="col-md-8"> <input type="text" class="form-control" name="cta_label" value="{{$data['cta_label'] ?? __('BTN_SHOP_NOW')}}" required>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-md-4 col-form-label" for="validationCustom01">{{__('LBL_BUTTON_LINK_URL')}}</label>
              <div class="col-md-8"> <input type="text" class="form-control" name="cta_link" value="{{$data['cta_link'] ?? ''}}" required>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-md-4 col-form-label">{{__('LBL_BUTTON_TARGET')}}</label>
              <div class="col-md-8"> <select class="custom-select my-1 mr-sm-2" name="cta_target">
                      <option value='' readonly disabled selected>{{__('LBL_CHOOSE')}}...</option>
                      <option value="_self" @if(!empty($data['cta_target']) && $data['cta_target']=="_self" ) selected @endif>{{__('LBL_SAME_TAB')}}</option>
                      <option value="_blank" @if(!empty($data['cta_target']) && $data['cta_target']=="_blank" ) selected @endif>{{__('LBL_NEW_TAB')}}</option>
                  </select>
              </div>
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addTwoColumnTextCtaWidget">{{__('BTN_EMBED')}}</button>
      </div>
      </div>
    </div>
  </div>
</div>