<section class="section yk-imageGallery" data-gjs-draggable="div.js-body">
    <div class="container yk-container yk-images" compid="{{ $cid ?? '' }}">
        <div class="about-images">
            <img data-aspect-ratio="1:1" src="{{(empty($collections[1]['banner_cropped'])) ? noImage('1_1/600x600.png') : $collections[1]['banner_cropped']}}" data-yk="" alt="">
            <img data-aspect-ratio="1:1" src="{{(empty($collections[2]['banner_cropped'])) ? noImage('1_1/600x600.png') : $collections[2]['banner_cropped']}}" data-yk="" alt="">
            <img data-aspect-ratio="1:1" src="{{(empty($collections[3]['banner_cropped'])) ? noImage('1_1/600x600.png') : $collections[3]['banner_cropped']}}" data-yk="" alt="">
            <img data-aspect-ratio="1:1" src="{{(empty($collections[4]['banner_cropped'])) ? noImage('1_1/600x600.png') : $collections[4]['banner_cropped']}}" data-yk="" alt="">
        </div>
    </div>
</section>