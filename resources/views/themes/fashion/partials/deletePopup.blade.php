<div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header border-0">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <div class="my-1 pb-5 text-center">
                @php 
                    $message = __("LBL_DELETE_CONFIRMATION");
                    if($type == 'addressDelete'){
                        $message = __("LBL_DELETE_ADDRESS_CONFIRMATION");
                    }else if($type == 'cardDelete'){
                        $message = __("LBL_DELETE_CARD_CONFIRMATION");
                    }
                @endphp
                <p>{{$message}}</p>
                <button type="button" class="btn btn-brand mt-5 YK-confirmDelete" id="delete-btn" onclick="deleteData('{{$id}}','{{$type}}')">{{__("BTN_DELETE")}}</button>
            </div>
        </div>
    </div>
</div>