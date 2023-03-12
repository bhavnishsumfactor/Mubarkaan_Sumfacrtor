@php
$subRecordId = 0;
if(!empty($op->op_pov_code)){
    $subRecordId = getImageByVariantCode($op->op_product_id, $op->op_pov_code);
}
$images = getProductImages($op->op_product_id, $subRecordId);  
$productUrl = getRewriteUrl('products', $op->op_product_id);                               
@endphp
<div class="modal-dialog modal-dialog-centered modal-md" data-yk=""  role="yk-document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{__("LBL_REVIEW_YOUR_PURCHASE")}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" style="max-height: 450px;">
            <form id="YK-submitReviewForm" method="post" class="form" enctype="multipart/form-data">
                <div class="write-review">                    
                    <div class="write-review__purchase">
                        <ul class="list-group list-reviews">
                            <li class="list-group-item my-review">
                                <div class="product-profile">
                                    <div class="product-profile__thumbnail">
                                        <a href="javascript:;">
                                            <img class="img-fluid" data-ratio="{{productAspectRatio()}}" src="{{ !empty($images->first()) ? url('yokart/image/'.$images->first()->afile_id.'/'.getProdSize('medium').'?t=' . strtotime($images->first()->afile_updated_at)) : productDummyImage() }}" alt="..." data-yk="">
                                        </a>
                                    </div>
                                    <div class="product-profile__data">
                                        <div class="title"><a class="" href="{{$productUrl}}">{{ $op->op_product_name }}</a></div>
                                        @php
                                            $variants = json_decode($op->op_product_variants);
                                            if (!empty($variants->styles)){
                                                $variantsArr = [];
                                                foreach($variants->styles as $style)
                                                {
                                                    $variantsArr[] = $style;
                                                }
                                                $variants = implode(' | ',$variantsArr);
                                        @endphp
                                            <div class="options">
                                                <p class="">{{Str::title($variants)}}</p>
                                            </div>
                                        @php } @endphp
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="write-review__rate">
                        <h6>{{ __('LBL_RATE_THIS_PRODUCT') }}</h6>
                        <div class="rating">
                            <div style="--size:1rem" class="rating-action YK-selectRating" data-rating="{{$review->preview_rating ?? ''}}" @if($viewOnly==1) data-readonly="1" @endif>
                                    <svg class="icon YK-ratingItem"  width="24" height="24">
                                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star">
                                        </use>
                                    </svg>   
                                    <svg class="icon YK-ratingItem"  width="24" height="24">
                                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star">
                                        </use>
                                    </svg>   
                                    <svg class="icon YK-ratingItem"  width="24" height="24">
                                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star">
                                        </use>
                                    </svg>   
                                    <svg class="icon YK-ratingItem"  width="24" height="24">
                                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star">
                                        </use>
                                    </svg>   
                                    <svg class="icon YK-ratingItem"  width="24" height="24">
                                        <use xlink:href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star" href="{{url('yokart/'.config('theme'))}}/media/retina/sprite.svg#star">
                                        </use>
                                    </svg>                            
                            </div>
                            <input type="hidden" name="preview_rating" value="{{$review->preview_rating ?? ''}}">   
                        </div> 
                    </div>
                    <div class="write-review__about">
                        <h6>{{ __('LBL_GIVE_FEEDBACK_ABOUT_PRODUCT') }}</h6>
                        <div class="form-group"> <label class="">{{ __('LBL_REVIEW_TITLE') }}</label>
                            <input type="hidden" name="preview_id" value="{{$review->preview_id ?? ''}}">
                            <input type="hidden" name="preview_prod_id" value="{{$op->op_product_id}}">
                            <input type="hidden" name="preview_order_id" value="{{$op->op_order_id}}">
                            <input type="hidden" name="deleteids" value="">
                            <input type="text" class="form-control" name="preview_title" id="preview_title" value="{{$review->preview_title ?? ''}}" @if($viewOnly==1) readonly @endif>
                           
                        </div>
                        <div class="form-group"><label class="">{{ __('LBL_REVIEW_DESCRIPTION') }}</label>
                            <textarea class="form-control" name="preview_description" id="preview_description" style="resize: none;" cols="30" rows="10" @if($viewOnly==1) readonly @endif>{{$review->preview_description ?? ''}}</textarea>
                            
                        </div>
                    </div>
                    <div class="write-review__media">
                        <ul class="list-media YK-uploadedImagesPreview">
                            @php $imagesIds = ''; @endphp
                            @if(!empty($review->attachedFiles) && count($review->attachedFiles) > 0)
                                @foreach($review->attachedFiles as $attachedFile)
                                    <li class="remove-able"> <img data-yk="" src="{{url('').'/yokart/image/'.$attachedFile->afile_id. '/small?t=' . strtotime($attachedFile->afile_updated_at)}}" alt="">
                                    @if($viewOnly==0) <button type="button" class="remove YK-removeReviewImage" data-previewid="{{$review->preview_id ?? ''}}" data-id="{{$attachedFile->afile_id}}"><i class="fas fa-times"></i></button> @endif
                                    </li>
                                    @php $imagesIds .= $attachedFile->afile_id . ','; @endphp
                                @endforeach
                            @endif
                            @if($viewOnly==0)
                                <li class="upload">
                                    <input type="hidden" name="imageIds" value="{{$imagesIds}}">
                                    <i class="fas fa-camera"></i>
                                    <input type="file" accept="image/*" id="images" multiple="multiple" onchange="uploadImages()" />
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            @if($viewOnly==0)
            <button class="btn btn-brand btn-wide gb-btn gb-btn-primary" name="submitReviewBtn" type="button" onclick="submitReview()">{{ __('BTN_REVIEW_SUBMIT') }}</button>
            @endif
        </div>
    </div>
</div>