@if(!empty($recentViewProducts) && count($recentViewProducts)>0)
   <section class="section bg-grayx">
      <div class="container">
         <div class="section-content section-content-center">
            <h2>{{__("LBL_RECENTLY_VIEWED")}}</h2>
         </div>
         <ul class="js-fourColumn-slider collection-slider">
            @foreach($recentViewProducts as $recentViewProduct)
               <li class="item">
                  @include('themes.'.config('theme').'.partials.productCard', ['product'=>$recentViewProduct, 'aspectRatio' => productAspectRatio()])
               </li>
            @endforeach
         </ul>
      </div>
   </section>
@endif