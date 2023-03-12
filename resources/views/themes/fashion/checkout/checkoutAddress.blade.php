<div class="scroll-y addresses-wrapper"> 
    <ul class="list-group list-addresses  YK-oldAddress"> 
    @foreach($savedaddress as $k => $address)
        <li class="list-group-item @if(!empty($defaultAddressId) && $address->addr_id == $defaultAddressId) {{ 'selected' }} @elseif($k==0 && empty($defaultAddressId))  {{ 'selected' }} @endif">
            <label class="list-addresses__label" for="address{{isset($type) ? '-' . $type : ''}}-{{ $address->addr_id}}">
                <span class="radio">
                    <input type="radio" name="addr_id" attr-val="{{$address->addr_id}}" value="@php echo $address->addr_id; @endphp" id="address{{isset($type) ? '-' . $type : ''}}-{{ $address->addr_id}}" @if(!empty($defaultAddressId) && $address->addr_id == $defaultAddressId) {{ 'checked' }} @elseif($k==0) {{ 'checked' }} @endif class="filled YK-addressSelected" onclick="changeUserAddress({{ $address->addr_id}}, '{{$type}}', '{{$digital}}')">
                </span>
                <address class="delivery-address">
                    <h5>{{ $address->addr_first_name ? $address->addr_first_name : '' }} {{ $address->addr_last_name ? $address->addr_last_name : ''}} <span class="tag">{{$address->addr_title}}</span></h5>
                    {{ $address->addr_apartment ? $address->addr_apartment.',':'' }} {{ $address->addr_address1 ? $address->addr_address1.',' : '' }} <br>{{ $address->addr_address2 ? $address->addr_address2.',' : '' }} {{ $address->addr_city }}, {{ $address->state->state_name }}, {{ $address->addr_postal_code }}
                </address>
                <div class="YK-actions YK-editAddress-{{ $address->addr_id}} 
                ">
                    <ul class="list-actions">
                        <li>
                            <a href="javascript:;" onclick="editCheckoutAddress('{{ $address->addr_id }}')">
                                <svg class="svg">
                                    <use xlink:href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#edit')}}" href="{{asset('yokart/'.config('theme').'/media/retina/sprite.svg#edit')}}">
                                    </use>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </label>
        </li>
        @endforeach
    </ul>
</div>