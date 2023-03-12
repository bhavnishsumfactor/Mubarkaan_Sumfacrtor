<table width="100%" cellspacing="0" cellpadding="0">
    <tbody>
        <tr style="vertical-align: top;">
        {{--text-align: center;--}}
            @if($orderType == 'pickup')
                <td style="padding-right:10px;width: 50%;">
                    <h4 style="font-size: 14px;font-weight: 600;text-transform: uppercase;color: #212529;letter-spacing: -0.2px;margin:0 0 10px 0;">Pickup Address</h4>
                    <p style="font-size: 14px; color:#7A7C7F;line-height: 24px;">{!!$pickupAddress!!}
                    </p>
                    <p style="font-size: 14px; color:#7A7C7F;line-height: 24px;"><strong>Date & Time </strong><br> {{$pickupDateTime}}</p>
                </td>
            @elseif($orderType == 'shipping')
                <td style="padding-right:10px;width: 50%;">
                    <h4 style="font-size: 14px;font-weight: 600;text-transform: uppercase;color: #212529;letter-spacing: -0.2px;margin:0 0 10px 0;">Shipping Address</h4>
                    <p style="font-size: 14px; color:#7A7C7F;line-height: 24px;">{!!$shippingAddress!!}
                    </p>
                </td>
            @endif
            <td style="padding-left:10px;">
                <h4 style="font-size: 14px;font-weight: 600;text-transform: uppercase;color: #212529;letter-spacing: -0.2px;margin:0 0 10px 0;">Billing Address</h4>
                <p style="font-size: 14px; color:#7A7C7F;line-height: 24px;">{!!$billingAddress!!}
                </p>
            </td>
        </tr>
    </tbody>
</table>