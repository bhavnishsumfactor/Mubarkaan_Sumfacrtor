@if(empty($cartConditions['coupon']))
<div class="coupons">
    <button class="links" onclick="getCoupons('{{$cartPage}}')">{{__('BTN_HAVE_DISCOUNT_COUPON')}} ?</button>
</div>
@endif
@if(!empty($cartConditions['coupon']))

<div class="coupons-applied @if($cartConditions['coupon']->getError() == true) {{'error'}} @endif">
    <div class="">
        <h6><i class="fas fa-check-circle"></i> {{$cartConditions['coupon']->getCode()}} </h6>
        <p>@if($cartConditions['coupon']->getError() == true) Invalid discount coupon @else {{__("LBL_SAVED_ADDITIONAL") . ' -' . displayPrice(abs($cartConditions['coupon']->getValue()))}} @endif</p>
    </div>
    <ul class="list-actions">
        <li>
            <a data-toggle="modal" onclick="removeCoupon('{{$cartPage}}')" href="javascript:;">
                <svg class="svg">
                    <use xlink:href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#remove')}}" href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#remove')}}">
                    </use>
                </svg>
            </a>
        </li>
    </ul>
</div>
@endif
<div class="">
    <ul class="list-group">
        <li class="list-group-item border-0">
            <span class="label">{{ __('LBL_SUBTOTAL') }}</span>
            <span class="ml-auto">{{displayPrice(Cart::getSubTotal())}}</span>
        </li>
        {{--
            @if(isset($displayTop) && $displayTop == false)
                @if(empty($cartConditions['coupon']))
                    <li class="list-group-item ">
                        <span class="label">{{__("LBL_COUPON")}}
        </span>
        <span class="ml-auto">
            <button class="btn btn-outline-secondary btn-sm" onclick="getCoupons('{{$cartPage}}')">{{__("BTN_APPLY")}}</button>
        </span>
        </li>
        @else
        <li class="list-group-item ">
            <span class="label">{{ucfirst($cartConditions['coupon']->getName())}}

                <span class="applied">
                    <!-- <i class="fas fa-check-circle"></i>   -->
                    {{$cartConditions['coupon']->getCode()}}
                    <a data-toggle="modal" onclick="removeCoupon('{{$cartPage}}')" href="javascript:;">
                        <svg class="svg" width="10px" height="10px">
                            <use xlink:href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#remove')}}">
                            </use>
                        </svg>
                    </a>
                </span>
            </span>
            <span class="ml-auto YK-{{$cartConditions['coupon']->getName()}} @if(!empty($cartConditions['coupon'])) {{'txt-success'}}  @endif">
                -{{displayPrice(abs($cartConditions['coupon']->getValue()))}}
            </span>
        </li>
        @endif
        @endif
        --}}
        @foreach($cartConditions as $condition)
        @php
        if($condition->getType() == 'shipping' && ($digitalProducts == 1 || $pickup != 0)){
          
        continue;
        }
        @endphp
        <li class="list-group-item ">

   
             <span class="label">
                @if(isset($cartPage) && $cartPage == true && ($condition->getType() == 'shipping' || $condition->getType() == 'tax')){{__("LBL_ESTIMATE")}}
                @endif{{__('LBL_'.strtoupper($condition->getName()))}}
                @if(!empty($condition->getType() == 'coupon')) <i class="fas fa-check-circle"></i>@endif
            </span>
            @if(!empty($condition->getType() == 'coupon'))
            <span class="ml-auto YK-{{$condition->getName()}} @if(!empty($condition->getType() == 'coupon')) {{'txt-success'}}  @endif">
                -{{displayPrice(abs($condition->getValue()))}}
            </span>
            @elseif(!empty($condition->getType() == 'rewardpoints'))
            <span class="ml-auto txt-success YK-{{$condition->getName()}}">
                -{{displayPrice(abs($condition->getValue()))}} <a href="javascript:;" onclick="removeReward()"><i class="fas fa-trash"></i></a>
            </span>
            @else
            <span class="ml-auto">
                @if($condition->getValue() == 0)
 
                <span class="txt-success">{{__("LBL_FREE")}}</span>
                <del>{{displayPrice('00.00')}}</del>
                @else
                {{displayPrice($condition->getValue())}}
                @endif
            </span>
            @endif
        </li>
        @endforeach
        <li class="list-group-item hightlighted">
            <span class="label">{{__("LBL_TOTAL")}}</span>
            <span class="ml-auto">{{displayPrice(Cart::getTotal())}}</span>
        </li>
    </ul>
    @if(isset($cartPage) && $cartPage == true)
    <p class="included">{{__("LBL_TAX_INCLUDED_SHIPPING_AT_CHECKOUT")}}</p>

    @endif
    @if(empty($cartPage) && $earnRewardPoints != 0 && $displayRewardPointOnEarn)
    <p class="earn-points"><svg class="svg" width="20px" height="20px">
            <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#rewards" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#rewards">
            </use>
        </svg> {{__("LBL_YOU_WILL_EARN") . ' ' . $earnRewardPoints . ' ' . __("LBL_POINTS")}} </p>
    @endif
</div>
@if($cartPage)
<div class="buttons-group">
    <a class="btn btn-outline-gray" href="{{url('/products')}}">{{ __('BTN_SHOP_MORE') }}</a>
    <a class="btn btn-brand YK-checkout" @if(isset($outOfStock) && $outOfStock) href="javascript:;" disabled @else href="{{route('checkout')}}" @endif>{{ __('BTN_CHECKOUT') }}</a>

</div>
@endif