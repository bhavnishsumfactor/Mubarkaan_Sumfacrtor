@if(!empty($images))
@foreach ($images as $prodImg)
<div class="slider-item">


    <picture class="product-img" data-ratio="{{productAspectRatio()}}">
        <source type="image/webp" srcset="{{url('yokart/image/'.$prodImg->afile_id.'/'.getProdSize('medium', true).'?t=' . strtotime($prodImg->afile_updated_at))}}" title="{{$prodImg->afile_attribute_title}}">
        <img data-yk="" data-aspect-ratio="{{productAspectRatio()}}" src="{{url('yokart/image/'.$prodImg->afile_id.'/'.getProdSize('medium').'?t=' . strtotime($prodImg->afile_updated_at))}}" alt="{{$prodImg->afile_attribute_alt}}" title="{{$prodImg->afile_attribute_title}}">
    </picture>
</div>
@endforeach
@endif