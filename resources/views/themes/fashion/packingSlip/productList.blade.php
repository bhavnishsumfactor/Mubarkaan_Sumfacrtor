
@if(isset($products) && !empty($products))

  
<tr>
    <td colspan="3">
        <table style="width: 100%; border-spacing: 0; border-collapse: collapse;">
            <tbody>
            @php $x = 1; @endphp
    @foreach($products as $key => $product)
    @php 
        $qty = $product->op_qty;
        
        if($product->cancelRequest && $product->cancelRequest->orrequest_status == 2){
        $qty =  $product->op_qty - $product->cancelRequest->orrequest_qty;
        }   
        @endphp
        @if($qty > 0 && $product->op_product_type != 2)
        <tr>
            <td style="padding: 10px; width: 100px;vertical-align: top;">{{$x}}</td>
            <td style="padding: 10px;">
                <h3 style="margin: 0;">{{ $product->op_product_name }}</h3>
                @php
                        $variants = [];
                        if(!empty($product->op_product_variants)){
                            $variants = json_decode($product->op_product_variants, true);
                        }
                        @endphp
                        @if(!empty($variants['styles']))
                @php $variantNames = []; @endphp
                    @foreach($variants['styles'] as $key => $variant)
                    @php $variantNames[] = $variant; @endphp
                    @endforeach
                    @php $variantNames = implode(" | ", $variantNames); @endphp
                    <p class="">{{Str::title($variantNames)}}</p>
                @endif
            </td>
            <td style="padding: 10px; text-align: right;">{{$qty}}</td>
        </tr>
        @php $x++; @endphp
        @endif
    @endforeach
    </tbody>
        </table>
    </td>
</tr>
@endif