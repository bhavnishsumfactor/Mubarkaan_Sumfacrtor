@php
    $subRecordId = productColorId($product);
    $images	= getProductImages($product->prod_id, $subRecordId);
    if(empty($favorite)){
        $favorite = 0;
    }	 
    $colorOption = productColorVarients($product);  
    $detailPageUrl = $product->urlRewrite->urlrewrite_original; 
    if(!empty($product->urlRewrite->urlrewrite_custom)){
        $detailPageUrl = $product->urlRewrite->urlrewrite_custom;
    }     
    $currentImages = $webpCurrentImages = productDummyImage();
    $code = $product->pov_code ?? 0;
    if(!empty($productImage = $images->first())){


        
        $currentImages = url('yokart/product/image/'.$product->prod_id.'/'.$code.'/'.getProdSize('medium').'?t=' .strtotime($productImage->afile_updated_at));
$webpCurrentImages = url('yokart/product/image/'.$product->prod_id.'/'.$code.'/'.getProdSize('medium',true).'?t=' .strtotime($productImage->afile_updated_at));


    }                
    
     
@endphp
<div class="product"  data-ratio="{{$aspectRatio}}">
    <div class="product-body">
        @if($product->discount != 0)
            <span class="badge-sale tag tag-primary"><span>{{__("BDG_SAVE")}} {{round($product->discount)}}%</span></span>
        @endif        
        
        <a href="{{url($detailPageUrl)}}">
            <picture class="product-img" data-ratio="{{$aspectRatio}}">
                <source  type="image/webp" srcset="{{$webpCurrentImages}}" alt="{{$product->prod_name}}">
                <img data-yk="" data-aspect-ratio="{{$aspectRatio}}" src="{{$currentImages}}" alt="">
            </picture>
        </a>
    </div>
    <div class="product-foot">
        <a class="product_title" href="{{url($detailPageUrl)}}" tabindex="0">{{Str::limit($product->prod_name, 40)}}</a>
        <div class="product_price">
            <div class="price">{{displayPrice($product->finalprice)}}</div>
            @if($product->price != $product->finalprice)
                <div class="sale-price text-linethrough"><del>{{displayPrice($product->price)}}</del></div>
            @endif
        </div>
    </div>    
</div>