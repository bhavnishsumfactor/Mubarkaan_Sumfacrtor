<form class="form">
    <ul class="list-group review-block YK-selectedAddress" data-addrid="{{$address->addr_id}}">
        <li class="list-group-item">
            <div class="review-block__label">@if(count($pickups) > 0 || $isDigitalProduct == 1){{__("LBL_BILLING_ADDRESS")}}@else{{__("LBL_DELIVERY_ADDRESS")}}@endif</div>
            <div class="review-block__content" data-yk="" role="yk-cell">{{ $address->addr_address1.' '.$address->addr_address2.', '.$address->addr_city.', '.$address->state->state_name.', '. $address->country->country_name }}</div>
            <div class="review-block__link" data-yk="" role="yk-cell">
                <a class="link" href="javascript:;" onclick="previousStep('shippingInfo', 'deliveryStep')"><span>{{__("LNK_CHANGE")}}</span></a>
            </div>
        </li>
    </ul>
    <div class="step__section">
        @if(count($shippings) > 0)
            <div class="step__section__head">
                <h3 class="step__section__head__title">{{__("LBL_SHIPPING")}}</h3>
            </div>
            @foreach($shippings as $key => $shipping)
                @php
                    $productIds = explode(',', $key);
                    $productNames = [];
                @endphp
                <div class="shipping-section YK-shippings">
                    <div class="shipping-option ">
                        <ul class="media-more show ">
                            @foreach(array_splice($productIds, 0, 4) as $productId)
                            @php
                            $attributes = $cartCollection[$productId]['attributes']->getItems();
                            $subRecordId = 0;
                            if($cartCollection[$productId]->product_id !== $productId){
                            $subRecordId = getImageByVariantCode($cartCollection[$productId]->product_id, $productId);
                            }
                            $images = getProductImages($productId, $subRecordId);
                            $productNames[] = $cartCollection[$productId]['name'];
                            @endphp
                            <li><span class="circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="product name"><img data-yk="" src="{{ !empty($images->first()) ? url('yokart/image/'.$images->first()->afile_id.'/'.getProdSize('small').'?t=' . strtotime($images->first()->afile_updated_at)) : noImage('../no_image.jpg') }}" alt=""></span></li>
                            @endforeach
                            @if(count($productIds) > 0)
                            <li> <span class=" plus-more">+{{count($productIds)}}</span></li>
                            @endif
                        </ul>

                    </div> 
                    @if(array_key_exists('error', end($shipping)))
                    {{'Product is not serviceable in you location '}}
                    @else
                    <select class=" form-control custom-select YK-selectedShipping" name="shipping[{{$key}}]" onchange="updateShipping()">
                        <option value="" disabled selected>{{__("LBL_SELECT_SHIPPING")}}</option>
                        @foreach($shipping as $rateKey => $val)
                        <option data-name="{{$val['name']}}" data-key="{{$key}}" value="{{$val['price']}}">{{$val['name'] . ' - ' . displayPrice($val['price'])}}</option>
                        
                        @endforeach
                    </select>
                    @endif
                </div>
            @endforeach
        @endif
        @if(count($pickups) > 0)
            <div class="step__section__head">
                <h3 class="step__section__head__title">{{__("LBL_PICK_UP")}}</h3>
            </div>
            <div class="pick-section">
                <div class="pickup-option">
                    <div class="pickup-address scroll-y address-wrapper">
                        <ul class="list-group pickup-option__list  yk-perfectScrollbar">                 
                            @foreach($pickupAddress as $key => $pickup)
                                <li class="list-group-item @if(array_key_first($pickupAddress->toArray()) == $key){{'selected'}}@endif" >
                                    <label class="pickup-option__list_label radio YK-pickup-{{$pickup->saddr_id}}"><input @if(array_key_first($pickupAddress->toArray()) == $key)  {{'checked'}} @endif   name="pickupValues" type="radio"  data-pickup="{{$pickup->saddr_id}}"  value="{{$pickup->saddr_id}}" onclick="changeAddress({{$pickup->saddr_id}})"> 
                                        <i class="input-helper" name="pickupAddreess"></i> 
                                        <span class="lb-txt"> {{$pickup->saddr_name.' '.$pickup->saddr_address.' , '.$pickup->saddr_postal_code.' '.$pickup->saddr_city.' '.$pickup->state_name.' '.$pickup->country_name}}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="pickup_time">
                        <div class="calendar">                            
                        </div>                
                        <input type="hidden" name="date" id="selectedDate" value="">    
                        <div class="YK-timeSlots">

                        
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <script>
            if (!$('.yk-perfectScrollbar').hasClass('ps')) {
                new PerfectScrollbar('.yk-perfectScrollbar');
            }
            $('input[name="pickupValues"]').click(function() {
                $('.YK-pickups').hide();
                let currentPickupValue = $("input[name='pickupValues']:checked").val();
                $('.YK-pickupID-' + currentPickupValue).show();
            });
            $('input[name="pickupDays"]').click(function() {
                let currentPickupDayValue = $("input[name='pickupDays']:checked").val();
                let currentPickupValue = $("input[name='pickupDays']:checked").attr('data-pickup');
                $('.YK-pickupTiming').hide();
                $('.YK-pickupDayTiming-' + currentPickupValue + '-' + currentPickupDayValue).show();
            });

            function changeAddress(pickId) {
                $('.pickup-option__list').find('li').removeClass('selected');
                $('.YK-pickup-'+pickId).closest('li').addClass('selected');
                $(".YK-timeSlots").html('');
                $.ajax({
                    type: 'GET',
                    url: baseUrl + '/checkout/pickup-days/' + pickId,
                    success: function(response) {
                        var days = response.data.days;
                        var defaultDate = response.data.defaultDate;
                        if (days.includes(7)) {
                            let index = days.indexOf(7);
                            days[index] = 0;
                        }
                        let date = false;
                        if (defaultDate) {
                            date = new Date(defaultDate);
                            var selectedDefault = new Date(defaultDate);
                            $("#selectedDate").val(defaultDate);
                        }
                        let weekDays = [0,1,2,3,4,5,6];
                        disabledWeekdays = weekDays.filter( ( el ) => !days.includes( el ) );
                        // $('#datetimepicker2').datetimepicker('destroy');
                        // $('#datetimepicker2').datetimepicker({
                        //     inline: true,
                        //     timepicker: false,
                        //     todayHighlight: false,
                        //     format: 'd-m-Y',
                        //     formatDate: 'd-m-Y',
                        //     minDate: selectedDefault,
                        //     defaultDate: date,
                        //     beforeShowDay: function(dt) {
                        //         let day = dt.getDay();
                        //         let daysToDisable = (days.includes(day));
                        //         return [daysToDisable];
                        //     }
                        // });
                        $('.calendar').pignoseCalendar({
                            format: 'DD-MM-YYYY',
                            disabledWeekdays: disabledWeekdays,
                            minDate:moment().format('YYYY-MM-DD'),
                            select: function(date) {
                                $("#selectedDate").val(date[0].format('D-M-Y'));
                                getTimeSlots(date[0].format('D-M-Y'), $("input[name='pickupValues']:checked").val());
                                $("#checkoutProceed").addClass("disabled");
                                $("#checkoutProceed").attr("disabled", true);
                            }
                        });
                        getTimeSlots(defaultDate, $("input[name='pickupValues']:checked").val());
                        $('.ui-datepicker-current-day').removeClass('ui-datepicker-current-day');
                    }
                });
            }
            $(document).on("change", "#selectedDate", function() {
                getTimeSlots($(this).val(), $("input[name='pickupValues']:checked").val());
                $("#selectedDate").val($(this).val());
                $("#checkoutProceed").addClass("disabled");
                $("#checkoutProceed").attr("disabled", true);
            });
            $(function() {
                var currentAddressValue = $("input[name='pickupValues']:checked").val();                
                changeAddress(currentAddressValue);
                $("#checkoutProceed").addClass("disabled");
                $("#checkoutProceed").attr("disabled", true);
                // $('#datetimepicker2').datetimepicker({
                //     inline: true,
                //     timepicker: false,
                //     format: 'd-m-Y',
                //     formatDate: 'd-m-Y',
                //     minDate: 0,
                //     defaultSelect: false
                // });
                //$('.ui-datepicker-current-day').removeClass('ui-datepicker-current-day');
                //changeAddress(currentAddressValue);
            });
        </script>
        @endif
    </div>
</form>