<div class="modal-dialog modal-dialog-centered" data-yk="" role="yk-document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{__("LBL_GIFT_MESSAGE")}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>


        <div class="modal-body">
            <form id="Yk-GiftWrap-From" class="form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" value="@if(count($message) > 0){{$message['from']}}@endif" name="from" placeholder="{{__('PLH_FROM')}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" value="@if(count($message) > 0){{$message['to']}}@endif" name="to" placeholder="{{__('PLH_TO')}}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea class="form-control" name="message" placeholder="Use this section to add multiple messages in case of multiple products that require gift wrapping">@if(count($message) > 0){{$message['message']}}@endif</textarea>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="{{$id}}" id="YK-cartId">
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-brand" type="button" onclick="updateGiftItem()">{{__("BTN_UPDATE")}}</button>
        </div>
    </div>
</div>