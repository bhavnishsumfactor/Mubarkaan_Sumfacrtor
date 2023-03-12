<table width="100%" cellspacing="0" cellpadding="0" style="background: #F6F6F6;padding:10px 20px;border-radius: 4px;">
    <tbody>
        @php
            $calculatedRefund = [];
        @endphp
        @foreach($products as $product)
            @php
                $qty = $product->op_qty;
                if($product->cancelRequest && $product->cancelRequest->orrequest_status == 2){
                    $qty = $product->op_qty - $product->cancelRequest->orrequest_qty;
                }
                $subRecordId = 0;
                if(!empty($product->op_pov_code)){
                    $subRecordId = getImageByVariantCode($product->op_product_id, $product->op_pov_code);
                }
                $images = getProductImages($product->op_product_id, $subRecordId);
                $productUrl = getRewriteUrl('products', $product->op_product_id);
                $variantNames = [];
                if(!empty($product->op_product_variants)){
                    $variants = json_decode($product->op_product_variants, true);
                    if(!empty($variants['styles'])){
                        foreach($variants['styles'] as $key => $variant){
                            $variantNames[] = $variant;
                        }
                    }
                }
            @endphp
            @if($qty > 0)
                @if(!$loop->last)
                    <tr style="border-bottom: 1px solid #e2e2e2;">
                @else
                    <tr>  
                @endif
                    <td style="vertical-align: top;width: 70px;padding: 15px 0;"><a
                            href="{{$productUrl}}"
                            style="width: 50px;height: 50px;background: #ffffff;display: block;border: 1px solid rgba(112, 112, 112, 0.2); max-width:100%;"><img
                                data-yk="" alt="" style="max-width:100%; width:100%; height:100%;object-fit: cover;"
                                src="{{ !empty($images->first()) ? url('yokart/image/'.$images->first()->afile_id.'/'.getProdSize('medium').'?t=' . strtotime($images->first()->afile_updated_at)) : productDummyImage()}}" /></a>
                    </td>
                    <td style="vertical-align: top;padding: 15px 0;"><a href="{{$productUrl}}"
                            style="color: #212529;font-size: 14px;line-height: 24px;letter-spacing: -0.2px;text-decoration: none;text-align:left;">{{$product->op_product_name}}
                            @if(!empty($variants['styles']))({{Str::title(implode(" | ", $variantNames))}})@endif</a>

                    </td>
                    <td  style="vertical-align: top; padding:15px 0; font-size:12px; font-weight:bold; color:#212529"><span
                            style="line-height: 24px;font-weight:bold;">
                            X {{$qty}}</span></td>
                    <td
                        style="vertical-align: top;font-size: 14px;line-height: 24px;letter-spacing: -0.2px;color: #212529;text-align: right;padding: 15px 0;">
                        {{displayPrice($product->op_product_price)}}</td>
                </tr>
            @endif
        @endforeach

    </tbody>
</table>