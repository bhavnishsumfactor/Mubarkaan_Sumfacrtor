<ul class="time-slot">
    @foreach($pickupSlots as $key =>  $slot)
        <li class=""> 
            <input type="radio" class="control-input" name="pickupSlot" id="time-{{$key}}" value="{{ $slot['st_from']}} - {{$slot['st_to'] }}">
            <label class="control-label" for="time-{{$key}}">
                <span  class="time">{{ convertTimeIntoSystemTime($slot['st_from']) }} - {{ convertTimeIntoSystemTime($slot['st_to']) }} </span>
            </label>
        </li>
    @endforeach
    @if(count($pickupSlots) == 0)
        <li class="">
            <div class="no-data-found mt-5">
                
                <img class="my-4" src="{{asset('yokart/'.config('theme').'/media/retina/no-time-slot.svg')}} " width="125px"  data-yk="" alt="">         
                
                <p>{{__("LBL_TIMESLOTS_NOT_AVAILABLE")}}</p> 
        
            </div>  
        </li>
    @endif
</ul>