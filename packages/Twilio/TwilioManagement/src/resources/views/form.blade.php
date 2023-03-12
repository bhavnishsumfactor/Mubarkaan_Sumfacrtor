
<div class="col-md-8">
    <div class="card">
        <div class="card-header">
            <div class="info__wrapper">
                <div class="info__media">
                    <div class="img__wrapper">
                        <img src="{{asset('admin/images/third-party/twilio.png')}}" alt="" />
                    </div>
                </div>
                <div class="info__content">
                    <div class="row">
                        <div class="col-md-10">
                            <h3>{{ __('LBL_TWILIO') }}</h3>
                            <a href="https://www.twilio.com/" target="_blank">https://www.twilio.com/</a>
                        </div>
                        <div class="col-md-2">
                            <span class="switch switch--sm">
                                <label>
                                    <input type="checkbox" name="twilio_status" value=1  id="twilioStatus" class="YK-twilioStatus"  @if($status == 1) {{'checked'}} @endif>
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">{{__('LBL_TWILIO_SID')}}</label>
                <div class="col-lg-9">
                    <input type="text" name="sid" value="{{$sid}}" class="form-control" />
                    <span class="error text-danger" hidden></span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label">{{__('LBL_TWILIO_AUTH_TOKEN')}}</label>
                <div class="col-lg-9">
                    <input type="text" name="auth_token" value="{{$auth_token}}" class="form-control" />
                    <span class="error text-danger" hidden></span>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-lg-3 col-form-label">{{__('LBL_TWILIO_NUMBER')}}</label>
                <div class="col-lg-9">
                    <input type="text" name="number" value="{{$number}}" class="form-control" />
                    <span class="error text-danger" hidden></span>
                </div>
            </div>
        </div>
    </div>
</div>
