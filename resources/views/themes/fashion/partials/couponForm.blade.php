<div class="modal-dialog modal-dialog-centered" data-yk="" role="yk-document">
    <div class="modal-content">
        <div class="modal-body">
            <form class="form mb-4" id="YK-coupon-code">
                <div class="row form-row">
                    <div class="col">
                        <input class="form-control" id="cartCouponCode" name="coupon-code" type="text" placeholder="{{__('PLH_ENTER_COUPON_CODE')}}*">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-brand btn-wide" type="button" name="ykApplyCoupon" onclick="couponApplied('{{$pageType}}')">
                            {{ __('BTN_APPLY') }}
                        </button>
                    </div>
                </div>
                <div class="YK-commonErrors error"></div>
            </form>
            <div class="yk-perfectScrollbar1" style="position: relative;max-height: 343px;">
                <ul class="list-group  list-group-flush-x list-group-flush-y list-promo">
                    @foreach($coupons as $coupon)

                    @php

                    $validCoupon = true;
                    if($coupon->couponConditions->count() > 0 ){
                    $validCoupon = isValidCoupon($coupon, $cartCollection);
                    }
                    @endphp
                    @if($validCoupon == true)
                    <li class="list-group-item @if($subtotal < $coupon->discountcpn_minorderamt){{'disabled'}}@elseif(isExpiredCoupon($coupon) == false){{'disabled'}}@endif">
                        <label class="radio">

                            <div class="row-coupon">
                                <div class="row-coupon__left"><input value="{{$coupon->discountcpn_code}}" name="coupon" @if($subtotal < $coupon->discountcpn_minorderamt){{'disabled'}}@else onclick="selectedCoupon() @endif " type="radio"> <i class="input-helper"></i>
                                </div>
                                <div class="row-coupon__right">
                                    <div class="list-promo__detial">
                                        <h6 class="list-promo__name">{{$coupon->discountcpn_code}}</h6>
                                        <p class="list-promo__text">

                                            @if($coupon->discountcpn_type == 1)

                                            @if($coupon->discountcpn_in_percent == 0)
                                            Flat {{displayPrice($coupon->discountcpn_amount)}}
                                            @else {{$coupon->discountcpn_amount. '%'}} @endif off upto {{displayPrice($coupon->discountcpn_maxamt_limit)}} on Shipping Charges. Minimum shipping charges of {{displayPrice($coupon->discountcpn_minorderamt)}} .
                                            Expires on {{getConvertedDateTime($coupon->discountcpn_enddate)}}


                                            <span class="coupon-label">Shipping</span>

                                            @else
                                            @if($coupon->discountcpn_in_percent == 0)
                                            Flat {{displayPrice($coupon->discountcpn_amount)}}
                                            @else {{$coupon->discountcpn_amount. '%'}} @endif
                                            off upto {{displayPrice($coupon->discountcpn_maxamt_limit)}} on minimum purchase of
                                            {{displayPrice($coupon->discountcpn_minorderamt)}} .
                                            <br> Expires on {{getConvertedDateTime($coupon->discountcpn_enddate)}}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        new PerfectScrollbar('.yk-perfectScrollbar1');
    });
</script>