<li class="list-group-item">
    <label class="payment-card__label">   
            <span class="radio">
                <input name="card-id" type="radio" value="{{$cardId}}" @if(!empty($isDefaultCard)) checked @endif>
                <i class="input-helper"></i>
            </span>
            <div class="payment-card__photo">                        
            @include('themes.'.config('theme').'.partials.cardIcons', ['cardType'=>$cardType])
            </div>
            <div class="payment-card__number">{{__("LBL_ENDING_IN")}}
                <strong>{{ Str::substr($cardNumber, -4) }}</strong>
            </div>
            <div class="payment-card__name">{{$cardName}}</div>        
    </label>
</li>