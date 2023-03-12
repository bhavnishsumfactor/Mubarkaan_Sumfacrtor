@php
   $checkLoginCls = 'YK-reviewErrorPopup';
   $writeAReviewBtn = '';
   if(!$can_post['purchase']){
      $checkLoginCls = '';
   }
@endphp
@guest
   @php $checkLoginCls = 'YK-checkLogin'; @endphp
@endguest

@php 
$pencilIcon = url('yokart/'.config('theme'))."/media/retina/sprite.svg#icon-pencil";
   $reviewConfiguration = getConfigValues([
      'DISPLAY_REVIEW_SECTION_IF_NO_REVIEW'
   ]);
   if ($can_post['status'] == "true") {
      $writeAReviewBtn = "<a href='javascript:;' class='btn btn-brand " . $checkLoginCls . "'  onclick='openAddOrEditReview(0, " . $prod_id . ", `productDetail`)'> <span>
            <i class='icon'>
               <svg class='svg' width='18px' height='18px' >
                  <use xlink:href='".$pencilIcon."' href='".$pencilIcon."'>
                  </use>
               </svg>
            </i>
            </span>".__('BTN_WRITE_A_REVIEW')."</a>"; 
   } else {
    
         $writeAReviewBtn = "<a href='javascript:;' class='btn btn-brand " . $checkLoginCls ."'> <span>
            <i class='icon'>
               <svg class='svg' width='18px' height='18px' >
                  <use xlink:href='".$pencilIcon."' href='".$pencilIcon."'>
                  </use>
               </svg>
            </i>
            </span>".__('BTN_WRITE_A_REVIEW')."</a>"; 
   
   }

   
@endphp
@if((count($productReviews)>0 || $reviewConfiguration['DISPLAY_REVIEW_SECTION_IF_NO_REVIEW'] == 1))
<div class="product-block-list__item product-block-list__item--reviews">
   <span id="product-reviews" class="anchor"></span>
   <div class="product-review">
      @if(!empty($productReviews) && count($productReviews)>0)
         <div class="product-review_head">
               <div class="section-content">
                  <h2>{{__("LBL_CUSTOMER_REVIEWS")}}</h2>
                  <div class="btns-group">
                    @if(count($productReviews) > 0)
                        {!!$writeAReviewBtn!!}  
                     @endif 
                     
                  @if(count($productReviews) > 1)  
                     <div class="dropdown">
                        <a class="dropdown-toggle btn btn-outline-secondary" data-toggle="dropdown" href="javascript:;">
                              <span><span class="YK-sortType">{{__("LNK_FILTER_NEWEST")}}</span></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim ">
                           <ul class="nav nav--block">
                           <li class="nav__item"><a class="nav__link YK-sortReviews" href="#!">
                              <span class="nav__link-text">{{__("LNK_FILTER_NEWEST")}}</span></a></li>
                           <li class="nav__item"><a class="nav__link YK-sortReviews" href="#!"> <span class="nav__link-text">{{__("LNK_FILTER_OLDEST")}}</span></a></li>
                           <li class="nav__item"><a class="nav__link YK-sortReviews" href="#!"> <span class="nav__link-text">{{__("LNK_FILTER_MOST_HELPFUL")}}</span></a></li>
                            
                           </ul>
                          
                        </div>
                     </div><!--dropdown-->
                     @endif
                      
               </div>
               </div>
               
         </div>         
         
      @endif
      <div class="product-review_body"> 
      @if(count($productReviews) > 0)  
      @php
      $avgRating = $rating->average('preview_rating') ?? 0;
      $oneRating = $rating->where('preview_rating', 1)->count();
      $twoRating = $rating->where('preview_rating', 2)->count();
      $threeRating = $rating->where('preview_rating', 3)->count();
      $fourRating = $rating->where('preview_rating', 4)->count();
      $fiveRating = $rating->where('preview_rating', 5)->count();
      $totalRating = $oneRating + $twoRating + $threeRating + $fourRating + $fiveRating;

      
      @endphp
              <div class="overall-rating">           
                  <div class="overall-rating__left">
                       
                        <div class="overall-rating__count">{{round($avgRating)}}.0<span>/5</span></div>
                           <div class="rating-holder">
                              
                              @include('themes.'.config('theme').'.product.partials.ratingStars', ['rating' => round($avgRating)])                    
                           </div>
                           <p><strong>{{__("LBL_BASED_ON") . ' ' . $review_count . ' ' . __("LBL_REVIEWS")}} </strong></p>
                       
                  </div>

                  <div class="index-rating-bar">
                     <ul>
                        <li>
                           <div class="index-rating">
                              <span class="index-ratinglevel">5</span>
                              <svg class="svg"  width="12" height="12">
                                    <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star">
                                    </use>
                              </svg> 
                           </div>
                           <div class="progress"><span style="width:{{round($fiveRating/$totalRating * 100)}}%;"></span></div>
                            
                            <div class="index-count">{{$fiveRating}}</div>
                        </li>
                        <li>
                           <div class="index-rating">
                              <span class="index-ratinglevel">4</span>
                              <svg class="svg"  width="12" height="12">
                                    <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star">
                                    </use>
                              </svg> 
                           </div>
                           <div class="progress"><span style="width: {{round($fourRating/$totalRating * 100)}}%;"></span></div>
                           <div class="index-count">{{$fourRating}}</div>
                        </li>
                        <li>
                           <div class="index-rating">
                              <span class="index-ratinglevel">3</span>
                              <svg class="svg"  width="12" height="12">
                                    <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star">
                                    </use>
                              </svg> 
                           </div>
                           <div class="progress"><span style="width: {{round($threeRating/$totalRating * 100)}}%;"></span></div>
                           <div class="index-count">{{$threeRating}}</div>
                        </li>
                        <li>
                           <div class="index-rating">
                              <span class="index-ratinglevel">2</span>
                              <svg class="svg"  width="12" height="12">
                                    <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star">
                                    </use>
                              </svg> 
                           </div>
                           <div class="progress"><span style="width: {{round($twoRating/$totalRating * 100)}}%;"></span></div>
                           <div class="index-count">{{$twoRating}}</div>
                        </li>
                        <li>
                           <div class="index-rating">
                              <span class="index-ratinglevel">1</span>
                              <svg class="svg"  width="12" height="12">
                                    <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star">
                                    </use>
                              </svg> 
                           </div>
                           <div class="progress"><span style="width: {{round($oneRating/$totalRating * 100)}}%;"></span></div>
                           <div class="index-count">{{$oneRating}}</div>
                        </li>
                     </ul>
                  </div>
                   
            </div> 
            @endif      

         @if(!empty($productReviews) && count($productReviews)>0)
            <div class="review-list">
               <ul>
                  @foreach($productReviews as $review)
                     <li class="review">                      
                        <div class="review__data">
                           <div class="review__body">
                              <div class="rating-holder">
                                 @include('themes.'.config('theme').'.product.partials.ratingStars', ['rating' => $review->preview_rating])
                              </div>
                              <h6 class="review-title"> {{$review->preview_title}}</h6>
                              <p>{{$review->preview_description}}</p>
                              @if(fileExists('productReviewImage', $review->preview_id) && !empty($review->attachedFiles))
                                 <ul class="list-media">
                                    @for($x=0; $x < count($review->attachedFiles); $x++)
                                       <li @if($x >= 3) style="display:none;" @endif>
                                          <a href="{{url('')}}/yokart/image/{{$review->attachedFiles[$x]->afile_id}}/original?t={{strtotime($review->attachedFiles[$x]->afile_updated_at)}}" class="fresco"
                                          data-fresco-options="thumbnail: '{{url('')}}/yokart/image/{{$review->attachedFiles[$x]->afile_id}}/small?t={{strtotime($review->attachedFiles[$x]->afile_updated_at)}}'"
                                             data-fresco-group="thumbnail-example{{$review->preview_id}}" ><img data-yk="" alt="" src="{{url('')}}/yokart/image/{{$review->attachedFiles[$x]->afile_id}}/small?t={{strtotime($review->attachedFiles[$x]->afile_updated_at)}}"></a>
                                       </li> 
                                    @endfor
                                    @if(count($review->attachedFiles) > 3)
                                       <li class="more YK-reviewMoreImages"><span>+{{count($review->attachedFiles) - 3}}</span></li> 
                                    @endif
                                 </ul>
                              @endif
                           </div>
                           <div class="d-flex justify-content-between">
                           <div class="review__avatar">
                              <div class="avatar avatar-lg">
                                 @if(fileExists('userProfileImage', $review->preview_user_id))
                                 <img data-yk="" alt="" class="avatar-title rounded-circle" src="{{getFileUrl('userProfileImage', $review->preview_user_id, 0, '48-48')}}">
                                 @else
                                 <span class="avatar-title rounded-circle"><i class="fa fa-user"></i></span>
                                 @endif                           
                              </div>
                              <div class="review__avatar-detail">
                                 <span class="from">{{$review->username}} </span>
                                 <time datetime="">{{ getConvertedDateTime($review->preview_created_at) }}</time>
                              </div>
                           </div>
                           <div class=" row align-items-center">
                              <div class="col-auto d-none d-lg-block"><span class="review-helpful-lbl">{{__('LBL_WAS_REVIEW_HELPFUL')}}</span></div>
                              <div class="col-auto mr-auto">
                                 @php
                                    $helpedCls = $nothelpedCls = 'far';
                                    if ($review->helped == 1 || $review->nothelped == 1) {
                                       if ($review->helped == 1){
                                          $helpedCls = 'fas';
                                       }else{
                                          $nothelpedCls = 'fas';
                                       }
                                    }
                                 @endphp
                                 <div class="rate YK-reviewFeedback">
                                    <a class="rate-item YK-helpful like " data-toggle="vote" data-id="{{$review->preview_id}}" data-count="{{$review->helped_count}}" href="javascript:;" data-yk=""  role="yk-button">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                          <g transform="translate(-1230 -3226)">
                                             <path d="M2591.6,157.285l-1.067,4.9a2.5,2.5,0,0,1-2.411,1.838h-10.166a.9.9,0,0,1-.9-.9v-7.172a.9.9,0,0,1,.9-.9h3.021l4.3-4.868a.491.491,0,0,1,.493-.152l.735.179a1.764,1.764,0,0,1,.359.143,1.5,1.5,0,0,1,.583,2.044l-1.04,1.874a.464.464,0,0,0-.062.242.5.5,0,0,0,.5.5h2.949a1.57,1.57,0,0,1,.412.045A1.842,1.842,0,0,1,2591.6,157.285Z" transform="translate(-1345.061 3077.973)" />
                                          </g>
                                       </svg>
                                    </a>
                                    <a class="rate-item YK-nothelpful dislike" data-toggle="vote" data-id="{{$review->preview_id}}" data-toggle="vote" data-count="{{$review->nothelped_count}}" href="javascript:;" data-yk=""  role="yk-button"> 
                                       <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                          <g transform="translate(-1317 -2707)">
                                             <path d="M2577.109,156.768l1.067-4.9a2.5,2.5,0,0,1,2.411-1.838h10.165a.9.9,0,0,1,.9.9v7.172a.9.9,0,0,1-.9.9h-3.021l-4.3,4.868a.491.491,0,0,1-.494.152l-.735-.179a1.752,1.752,0,0,1-.358-.143,1.5,1.5,0,0,1-.583-2.044l1.04-1.874a.467.467,0,0,0,.063-.242.5.5,0,0,0-.5-.5h-2.949a1.572,1.572,0,0,1-.412-.045A1.842,1.842,0,0,1,2577.109,156.768Z" transform="translate(-1258.06 2558.973)"/>
                                          </g>
                                       </svg>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                           @if(!empty($review->reply))
                           <div class="review__reply">
                              <div class="review__reply_avatar">
                                 <div class="avatar avatar-sm">
                                    @if(fileExists('frontendLogo', 0))
                                       <img data-yk="" alt="" class="avatar-title rounded-circle" src="{{getFileUrl('frontendLogo', 0, 0, '48-48')}}">
                                    @else
                                       <span class="avatar-title rounded-circle"><i class="fa fa-user"></i></span>
                                    @endif
                                 </div>
                              </div>
                              <div><p> {!!$review->reply->prc_comments!!}</p></div>
                           </div>
                           @endif
                        </div>
                        
                     </li>
                  @endforeach
               </ul>
            </div>
            @if($review_count>5)
            <nav class="d-flex justify-content-center mt-9">
                <ul class="pagination pagination-sm text-gray-400 YK-reviewsPaginate">
                    {{ $productReviews->links() }}
                </ul>
            </nav>
            @endif
         @else
            <div class="no-data-found">
               <div class="img">
                     <img data-yk="" alt="" src="{{ asset('yokart/'.config('theme').'/media/retina/empty/empty-state-no-reviews.svg') }}" >
               </div>
               <div class="data mt-0">
                  <h6>{{ __('LBL_BE_FIRST_TO_WRITE_REVIEW') }}</h6>                       
                  <div class="action">
                     {!! $writeAReviewBtn !!}
                  </div>
               </div>
            </div>
         @endif
      </div>
   </div>
</div>
@endif
<div class="modal fade" id="modalReviewError" data-yk="" role="yk-dialog">
    <div class="modal-dialog modal-md modal-dialog-centered" data-yk="" role="yk-document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__("LBL_ALERT")}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @include('themes.'.config('theme').'.partials.noRecordFound', ['heading' => 'Not purchased',
                'text1' => $can_post['message'],
                'headImage' => asset('yokart/'.config('theme').'/media/retina/empty/no-found.svg')
                ])
            </div>
            @if($can_post['purchase'] && $can_post['data'])
            <div class="modal-footer">
            
                <button class="btn btn-brand btn-wide gb-btn gb-btn-primary" type="button"
                
                onclick="addTocart({{$can_post['data']->op_product_id}},'',false,0,'{{$can_post['data']->op_pov_code}}')" name="YK-addToCartBtn" >{{__("BTN_BUY_NOW")}}</button>
            </div>
            @endif
        </div>
    </div>
</div>
