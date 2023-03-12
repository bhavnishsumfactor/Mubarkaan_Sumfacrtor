<div class="yk-video-settings" data-comp="{{$cid}}">
    <div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" data-yk=""  role="yk-document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_VIDEO')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-secondary" data-yk=""  role="yk-alert">
                        <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                        <div class="alert-text">{{__("LBL_FOR_EXAMPLE")}}, https://www.youtube.com/watch?v=EngW7tLk6R8 {{__("LBL_OR")}}
                            https://vimeo.com/253989945</div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-4 col-form-label" for="validationCustom01">{{__("LBL_VIDEO_PROVIDER")}}</label>
                        <div class="col-8">
                            <label class="radio mb-0">
                                <input type="radio" name="provider_type" value="1" @if((empty($data['provider_type']))
                                    || !empty($data['provider_type']) && $data['provider_type']=='1' ) checked="checked"
                                    @endif /> {{__("LBL_YOUTUBE")}}
                                <span></span>
                            </label>

                            <label class="radio mb-0">
                                <input type="radio" name="provider_type" value="2" @if(!empty($data['provider_type']) &&
                                    $data['provider_type']=='2' ) checked="checked" @endif /> {{__("LBL_VIMEO")}}
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-4 col-form-label" for="validationCustom01">{{__("LBL_VIDEO_URL")}}</label>
                        <div class="col-8"><input type="text" class="form-control" name="video_url"
                                value="{{$data['video_url'] ?? ''}}" required>
                        </div>
                    </div>
                    <div class="form-group row ">
                        <label class="col-4 col-form-label" for="validationCustom01">{{__("LBL_AUTOPLAY_VIDEO")}}</label>
                        <div class="col-8">
                            <div class="checkbox-inline">
                                <label class="checkbox  mb-0">
                                    <input type="checkbox" name="autoplay" value="1" @if(!empty($data['autoplay']) &&
                                        $data['autoplay']=='1' ) checked="checked" @endif /> {{__("LBL_YES")}}
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addVideoWidget">{{__('BTN_EMBED')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>