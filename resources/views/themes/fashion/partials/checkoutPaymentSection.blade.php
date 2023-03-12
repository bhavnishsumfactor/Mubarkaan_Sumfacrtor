<ul class="list-group review-block YK-selectedAddress" data-addrid="{{$address->addr_id}}">
    <li class="list-group-item">
        
        <div class="review-block__label">@if(!empty($shippingSummary['pick_ups']) || $isDigitalProduct == 1){{__('LBL_BILLING_ADDRESS')}}@else{{__('LBL_DELIVERY_ADDRESS')}}@endif</div>
        <div class="review-block__content" data-yk="" role="yk-cell">{{$address->addr_address1.' '.$address->addr_address2.', '.$address->addr_city.', '.$address->state->state_name.', '. $address->country->country_name}}</div>
        <div class="review-block__link" data-yk="" role="yk-cell">
            <a class="link" href="javascript:;" onclick="previousStep('shippingInfo', 'deliveryStep', 'paymentInfo')"><span>{{__("LNK_CHANGE")}}</span></a>
        </div>
    </li>
    @if(!empty($shippingSummary['pick_ups']))
    <li class="list-group-item">
        <div class="review-block__label">{{__("LBL_PICKUP_ADDRESS")}}</div>
        <div class="review-block__content" data-yk="" role="yk-cell">{{$shippingSummary['pick_ups']['address']}}
            <span class="selected-slot">{{$shippingSummary['pick_ups']['pickup_date']}} <br>
                {{$shippingSummary['pick_ups']['pickup_time']}}</span>
        </div>
        <div class="review-block__link" data-yk="" role="yk-cell">
            <a class="link" href="javascript:;" onclick="previousStep('paymentInfo', 'shippingInfo')"><span>{{__("LNK_CHANGE")}}</span></a>
        </div>
    </li>
    @else
    @if(!empty($selectedShippings))
    @php $productIds = explode(',', $selectedShippings); @endphp
    <li class="list-group-item">
        <div class="review-block__label">{{__("LBL_SHIPPING")}}</div>
        <div class="review-block__content" data-yk="" role="yk-cell">
            <ul class="media-more show">
                @foreach(array_splice($productIds, 0, 4) as $productId)
                @php $attributes = $cartCollection[$productId]['attributes']->getItems();
                $subRecordId = 0;
                if($cartCollection[$productId]->product_id !== $productId){
                $subRecordId = getImageByVariantCode($cartCollection[$productId]->product_id, $productId);
                }
                $images = getProductImages($productId, $subRecordId);
                @endphp
                <li>
                    <span class="circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="product name">
                        <img data-yk="" data-ratio="{{productAspectRatio()}}" src="{{ !empty($images->first()) ? url('yokart/image/'.$images->first()->afile_id.'/'.getProdSize('small').'?t=' . strtotime($images->first()->afile_updated_at)) : noImage('../no_image.jpg') }}" alt="">
                    </span>
                </li>
                @endforeach
                @if(count($productIds) > 0)
                <li> <span class="circle plus-more">+{{count($productIds)}}</span></li>
                @endif
            </ul>
        </div>
        <div class="review-block__link" data-yk="" role="yk-cell">
            <a class="link" href="javascript:;" onclick="previousStep('paymentInfo', 'shippingInfo')"><span>{{ __('LNK_CHANGE') }}</span></a>
        </div> 
    </li>
    @endif
    @endif
</ul>
@php
$pickup = 0;
@endphp
<form id="YK-placeOrder" class="form form-floating">
    <div class="step__section">
        @if($pickup == 0 && $isDigitalProduct == 0)
        <div class="step__section__head">
            <h3 class="step__section__head__title">{{ __('LBL_PAYMENT_AND_BILLING') }}</h3>
        </div>
        <label class="checkbox">
            <input title="" type="checkbox" onclick="billingAddress()" name="billing-address" value="1" checked>
            {{ __('LBL_BILLING_SAME_AS_DELIVERY') }} <i class="input-helper"></i>
        </label>
        <div id="YK-billingAddress" style="display:none"></div>
        @endif
        <input type="hidden" id="YK-paymentGateway" name="payment-gateway" value="@if(!empty($creditCard)){{$creditCard->pkg_slug}}@endif">
        <input type="hidden" value="{{$addressId}}" name="address-id">
        <input type="hidden" value="0" name="cod" id="YK-cod">
        @if(!empty($rewardPoints) && $rewardPoints['usablePoints'] != 0 && $orderSubtotalExcludingDiscount >= $minimumOrderTotal)

        <div class="rewards" id="YK-rewardPoints" @if($rewardApplied !=0 ) {{ 'style=display:none' }} @endif>
            <div class="rewards__points">
                <ul>
                    <li>
                        <p>{{ __('LBL_AVAILABLE_REWARD_POINTS') }}</p>
                        <span class="count">{{$rewardPoints['totalPoints']}}</span>
                    </li>
                    <li>
                        <p>{{ __('LBL_POINTS_WORTH') }}</p>
                        <span class="count">{{displayPrice($rewardPoints['totalPointsAmount'])}}</span>
                    </li>
                </ul>
            </div>
            <div class="form form-inline">
                <input class="form-control" id="" @if($subTotal < $rewardPoints['minOrderTotal']){{'readonly'}}@endif name="points" type="text" placeholder="Enter points to redeem">
                <button class="btn btn-submit" type="button" @if($subTotal < $rewardPoints['minOrderTotal']){{'disabled'}} @else onclick="applyRewardPoints()" @endif>{{ __('BTN_REDEEM') }}</button>
            </div>
            <div class="info">
                
                    <svg class="svg">
                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#info" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#info">
                        </use>
                    </svg>
                    {{ __('LBL_MINIMUM') . ' ' . $rewardPoints['minUsePoints'] . ' ' . __('LBL_POINTS_CAN_BE_REDEEMDED') }} on minimum phachase of order subtotal {{displayPrice($rewardPoints['minOrderTotal'])}}
                
            </div>
        </div>
       <div class="yay-block text-center yk-yayBlock" style="display:none;">
         <div class="img">
              <img src="{{url('yokart/' . config('theme'))}}/media/retina/yay.svg" data-yk="" alt="" />
              <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>
         </div>
       </div>
        @endif
        <div class="payment-area">
            <ul class="nav nav-payments" id="" data-yk="" role="yk-tablist">
                @if(!empty($creditCard))
                <li class="nav-item YK-PaymentGatewaySection">
                    <a class="nav-link" id="credit-tab" data-slug="{{$creditCard->pkg_slug}}" data-toggle="tab" href="#credit" data-yk="" role="yk-tab" aria-controls="credit" aria-selected="true">{{ __('LNK_CREDIT_CARD') }} {{$creditCard->pkg_slug}}</a>
                </li>
                @endif
                @if(!empty($paypalExpress))
                <li class="nav-item YK-PaymentGatewaySection">
                    <a class="nav-link" id="paypal-tab" data-toggle="tab" data-slug="Paypal" href="#paypal" data-yk="" role="yk-tab" aria-controls="paypal" aria-selected="false">{{ __('LNK_PAYPAL') }}</a>
                </li>
                @endif
                @if(!empty($cashFree))
                <li class="nav-item YK-PaymentGatewaySection">
                    <a class="nav-link" id="paycash-tab" data-slug="{{ $cashFree->pkg_slug }}" data-toggle="tab" href="#cashFree" data-yk="" role="yk-tab" aria-controls="cashFree" aria-selected="false">{{ __('LNK_CASH_FREE') }}</a>
                </li>
                @endif
                @if($codAvailable)
                <li class="nav-item YK-PaymentGatewaySection">
                    <a class="nav-link" id="paycash-tab" data-slug="cod" data-toggle="tab" href="#paycash" data-yk="" role="yk-tab" aria-controls="paycash" aria-selected="false">{{ __('LNK_PAYCASH') }}</a>
                </li>
                @endif
            </ul>

            <div class="tab-content YK-PaymentMethod" id="">
                @if(!empty($creditCard))
                <div class="tab-pane fade show" id="credit" data-yk="" role="yk-tabpanel" aria-labelledby="credit-tab">

                    @if(Auth::check() && count($userCards['cards']) > 0)
                    <div class="m-3 text-right">
                        <a class="link-text YK-addNewCardButton" href="javascript:;" onclick="addNewCard(this)">
                            <i class="icn">
                                <svg class="svg">
                                    <use xlink:href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#add')}}" href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#add')}}">
                                    </use>
                                </svg> </i>{{ __('LNK_ADD_NEW_CARD') }}</a>
                        <a class="link-text YK-discardNewCardButton" style="display:none;" href="javascript:;" onclick="displayCardListing(this)">
                            <i class="icn"> <svg class="svg">
                                    <use xlink:href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#minus')}}" href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#minus')}}">
                                    </use>
                                </svg> </i>{{ __('BTN_DISCARD') }}</a>
                    </div>
                    <span class="YK-oldCards active">
                        <ul class="list-group list-group-flush-x payment-card payment-card-view">
                            @foreach ($userCards['cards'] as $key => $card)
                            @switch($userCards['type'])
                            @case("Stripe")
                            @include('themes.'.config('theme').'.partials.userSavedCards',[
                            'cardName'=>$card->name, 'cardType'=>$card->brand, 'cardNumber'=>$card->last4,'cardId'=> $card->id,'cardExpire'=>$card->exp_month.'/'.$card->exp_year, 'isDefaultCard' => (!empty($userCards['defaultCardId']) && $userCards['defaultCardId'] == $card->id) ? true : null ])
                            @break
                            @case("AuthorizeDotNet")
                            @include('themes.'.config('theme').'.partials.userSavedCards',[
                            'cardName'=>$card->getBillTo()->getFirstName(),'cardType'=>$card->getPayment()->getCreditCard()->getCardType(), 'cardNumber'=>$card->getPayment()->getCreditCard()->getCardNumber(),'cardId'=> $card->getCustomerPaymentProfileId(),'cardExpire'=>'', 'isDefaultCard' => $card->getDefaultPaymentProfile()])
                            @break
                            @case("Bluesnap")
                            @include('themes.'.config('theme').'.partials.userSavedCards',[
                            'cardName'=>$card->name,'cardType'=>$card->cardType,'cardNumber'=>$card->last4,'cardId'=> $card->cardId,'cardExpire'=>$card->exp_month.'/'.$card->exp_year, 'isDefaultCard' => ($card->isDefaultCard == 1) ? true : null ])
                            @break
                            @endswitch
                            @endforeach
                        </ul>
                    </span>
                    <div class="p-4 YK-addNewCardForm"></div>
                    @else
                    <div class="p-4 YK-addNewCardForm"></div>
                    @endif
                </div>
                @endif
                @if(!empty($paypalExpress))

                <div class="tab-pane fade" id="paypal" data-yk="" role="yk-tabpanel" aria-labelledby="paypal-tab">
            
            
                    <div class="paypal-data">
                        <a href="javascript:;" onclick="placeOrder(1)"> <img data-yk="" src="{{url('yokart/'.config('theme'))}}/media/paypal.png" alt=""></a>
                        <p>{{ __("LBL_YOULL_RETURN_TO") . ' ' . Request::getHost() . ' ' . __("LBL_TO_REVIEW_PLACE_ORDER") }}</p>
                    </div>
                    
                </div>
                @endif
                @if(!empty($cashFree))
                <div class="tab-pane fade" id="cashFree" data-yk="" role="yk-tabpanel" aria-labelledby="cashfree-tab">
                    <div class="paypal-data">
                        <a href="javascript:;" class="btn btn-outline-brand" onclick="placeOrder(1)"> <img data-yk="" alt="">{{ __('CashFree') }}</a>
                        <p>{{ __("LBL_YOULL_RETURN_TO") . ' ' . Request::getHost() . ' ' . __("LBL_TO_REVIEW_PLACE_ORDER") }}</p>
                    </div>
                </div>
                @endif
                @if($codAvailable)
                <div class="tab-pane fade" id="paycash" data-yk="" role="yk-tabpanel" aria-labelledby="paycash-tab">
                    <div class="otp-block YK-Verify-Otp">
                        <div class="otp-block__head">
                            <h5>{{ __('LBL_OTP_VERIFICATION') }}</h5>
                            <p class="YK-optSentMessage"></p>
                        </div>
                        <div class="otp-block__body">
                            <div class="otp-enter">
                                <div class="otp-inputs">
                                    <div class="digit-group" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                                        <input class="field-otp" type="text" maxlength="1" placeholder="*" id="digit-1" name="digit-1" data-next="digit-2">
                                        <input class="field-otp" type="text" maxlength="1" placeholder="*" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1">
                                        <input class="field-otp" type="text" maxlength="1" placeholder="*" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2">
                                        <input class="field-otp" type="text" maxlength="1" placeholder="*" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3">
                                        <input class="field-otp" type="text" maxlength="1" placeholder="*" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4">
                                        <input class="field-otp" type="text" maxlength="1" placeholder="*" id="digit-6" name="digit-6" data-previous="digit-5">
                                    </div>
                                </div>
                                <button class="btn btn-brand btn-wide" onclick="verifyOtp()" type="button">{{ __('BTN_VERIFY') }} </button>
                            </div>
                        </div>
                        <div class="otp-block__footer">
                            <p class="YK-Verify-Otp" id="timer">{{ __('LBL_CODE_EXPIRES_IN') }}:<span class="txt-success font-weight-bold" id="time"></span>
                            </p>
                            <p class="YK-resendOtp" onclick="resendOtpRequest()">{{ __("LBL_DIDNT_GET_CODE") }} <a class="txt-success font-weight-bold" href="javascript:;" id="resendOtpBtn">
                                    {{ __("BTN_RESEND") }}!</a>
                            </p>

                        </div>

                    </div>
                    <div class="otp-block YK-otpVerifiedSuccess d-none">
                        <div class="otp-success">
                            <img class="img" src="{{asset('yokart/' . config('theme') . '/media/retina/otp-complete.svg')}}"  data-yk="" alt="">
                            <h5>{{ __('LBL_SUCCESS') }}</h5>
                            <p class="YK-OTPSuccessMessage"></p>
                        </div>
                    </div>
                    <div class="otp-block YK-Request-Otp">
                        <div class="otp-block__head">
                            <div class="otp-enter">
                                <button class="btn btn-brand btn-wide" id="requestOtpBtn" onclick="requestOtp()" type="button">{{ __('BTN_REQUEST_OTP') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="form order-notes">
                    <textarea class="form-control form-floating__field" name="order_notes" placeholder="{{ __('PLH_ORDER_NOTES') }}"></textarea>
                </div>
            </div>
        </div>
</form>
<script >
    var cardCount = "{{ isset($userCards['cards']) ? count($userCards['cards']) : 0 }}";
    $(function() {
        if ((cardCount == 0 && $("#credit-tab").hasClass('active')) || $("#paypal-tab").hasClass('active')) {
            addNewCard();
        }
    });
    resetSelectedFields = function() {
        $("input[name='card-id']").val('');
        if($('.YK-oldCards').hasClass('active')) {
            $('.YK-oldCards').removeClass('active');
        }
    }
    $("#verifyOtpBtn").prop("disabled", true);
    $('.YK-PaymentGatewaySection').click(function() {

        let slug = $(this).find('a').data('slug');
        let href = $(this).find('a').attr('href');
        slug = slug.trim();
        if(slug == "TwoCheckout"){

            twoCheckoutForm();            

        }else{
            if (slug == "cod" || slug == "Stripe" || slug == "Paypal") {
                $('#checkoutProceed').addClass('disabled');
            } else {
                $('#checkoutProceed').prop('disabled', false);
            }
            if (slug == "Stripe") {
                let paypalCardObj = $('.YK-paymentInfo .YK-PaymentMethod #' + 'paypal');
                paypalCardObj.find('.YK-addNewCardForm').html('');
                if (cardCount == 0) {
                    addNewCard();
                } else {
                    displayCardListing();
                }
            }
            if (slug == "Paypal") {
                let stripeCardObj = $('.YK-paymentInfo .YK-PaymentMethod #' + 'credit');
                stripeCardObj.find('.YK-addNewCardForm').html('');
                resetSelectedFields();
                addNewCard();
            }
            $('#YK-paymentGateway').val(slug);
            floatingFormFix();
        }
    });

    function enterOtp() {
        if ($("#otpValue").val().length != 6) {
            $("#verifyOtpBtn").prop("disabled", true);
        } else {
            $("#verifyOtpBtn").prop("disabled", false);
        }
    }
    $('.digit-group').find('input').each(function() {
        $(this).attr('maxlength', 1);
        $(this).on('keyup', function(e) {
            var parent = $($(this).parent());
            if (e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));

                if (prev.length) {
                    $(prev).select();
                }
            } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));

                if (next.length) {
                    $(next).select();
                } else {
                    if (parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
    });
    $(".payment-area li:first a").click();
    setCheckoutCardFormValidation = function() {
        setInputFilter(document.getElementById("name"), function(value) {
            return /^[a-zA-Z\s]*$/.test(value);
        });
        setInputFilter(document.getElementById("number"), function(value) {
            return /^(\s*|\d+)$/.test(value) && (value.length <= 19)
        });
        setInputFilter(document.getElementById("exp_month"), function(value) {
            return /^\d*$/.test(value) && (value.length <= 2)
        });
        setInputFilter(document.getElementById("exp_year"), function(value) {
            return /^\d*$/.test(value) && (value.length <= 4)
        });
        setInputFilter(document.getElementById("cvv"), function(value) {
            return /^\d*$/.test(value)
        });

        if ($("#name").val() == '' && $("#number").val() == '' && $("#exp_month").val() == '' || $("#exp_year").val() == '' && $("#cvv").val() == '') {
            $("#checkoutProceed").attr("disabled", true);
        } else {
            $("#checkoutProceed").attr("disabled", false);
        }
    }
    setCheckoutCardFormValidationTrigger = function() {
        $("input[type='text'], textarea, select").on("keyup", function() {
            if ($("#name").val() == '' || $("#number").val() == '' || $("#exp_month").val() == '' || $("#exp_year").val() == '' || $("#cvv").val() == '') {
                $("#checkoutProceed").attr("disabled", true);
            } else {
                $("#checkoutProceed").attr("disabled", false);
            }
        });
    }
   
</script>
<style>
    .YK-Verify-Otp {
        display: none;
    }
</style>