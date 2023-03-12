<div class="yk-customMap-settings" data-comp="{{$cid}}">
    <div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" data-yk=""  role="yk-document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_MAP')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-secondary" data-yk=""  role="yk-alert">
                        <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                        <div class="alert-text">{{__('LBL_FOR_EXAMPLE')}}, 
                        {{{'<iframe src="https://www.google.com/maps/embed?pb=address" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>'}}}                        
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label" for="validationCustom01">{{__('LBL_MAP_SCRIPT')}}</label>
                        <div class="col-10">
                            <textarea class="form-control" name="map_script" rows="6" required>{!! $data['map_script'] ?? '' !!}</textarea>    
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addCustomMapWidget">{{__('BTN_EMBED')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>