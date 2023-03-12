<div class="modal fixed-right fade" id="pickUp" data-yk=""  role="yk-dialog">
    <div class="modal-dialog modal-dialog-centered" data-yk=""  role="yk-document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__("LBL_STORE_TIMINGS")}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="js-pickup-slider pickup-slider">
                    @foreach($pickupAddress as $address)
                    <div class="slide-item " id="YK-address{{$address->saddr_id}}">
                        <div class="mb-3">
                            <h6 class="m-0 p-0">{{$address->saddr_name}}</h6>
                        </div>
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{__("LBL_DAYS")}}</th>
                                    <th>{{__("LBL_FROM")}}</th>
                                    <th>{{__("LBL_TO")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($address->timings as $value)
                                <tr>
                                    <td>{{$value->st_day}}</td>
                                    <td>{{$value->st_from}}</td>
                                    <td>{{$value->st_to}}</td>
                                </tr>
                                @endforeach                                    
                            </tbody>
                        </table>
                    </div> 
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>