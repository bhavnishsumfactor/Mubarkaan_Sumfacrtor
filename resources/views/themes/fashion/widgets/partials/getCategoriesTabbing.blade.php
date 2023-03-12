@if(!empty($collections) && count($collections)>0)
@php $categories = $collections->keyBy('cat_id'); 

          $k = 0;
          @endphp
    @foreach($categories as $key => $collection)
   
    <li class="{{($k==0)?'active':''}}"><a href="#{{$collection->collection_id.'-'.$key.'-'. $k}}">{{$collection->cat_name}}</a></li>
    @php $k++;@endphp
    @endforeach
@else
    <li class="active"><a href="#{{time()}}">{{__('LBL_BEST_SELLERS')}}</a></li>
    <li><a href="#catTwo">{{__('LBL_FEATURED')}}</a></li>
    <li><a href="#catThree">{{__('LBL_TOP_CATEGORIES')}}</a></li>
    <li><a href="#catFour">{{__('LBL_NEW_PRODUCTS')}}</a></li>
@endif