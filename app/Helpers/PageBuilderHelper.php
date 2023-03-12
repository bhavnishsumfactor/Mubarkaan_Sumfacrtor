<?php
namespace App\Helpers;

use App\Helpers\YokartHelper;
use App\Helpers\ThemeHelper;
use App\Helpers\FashionHelper;

class PageBuilderHelper extends YokartHelper
{
    public static function getTestimonialHtml($testimonials)
    {
        $html = '<testimonial data-gjs-editable="false" draggable="false" readonly="true"><div class="row"  data-gjs-editable="false" draggable="false" readonly="true">';
        if (!empty($testimonials)) {
            foreach ($testimonials as $v) {
                $html .= '<div class="col-sm-4"  data-gjs-editable="false" draggable="false" readonly="true"><img src="'.$v->testimonial_image.'"  data-gjs-editable="false" draggable="false" readonly="true"><p  data-gjs-editable="false" draggable="false" readonly="true">'.$v->testimonial_user_name.'</p></div>';
            }
        }
        $html .= '</div></testimonial>';
        return $html;
    }

    public static function getTopSliderHtml()
    {
        return view('themes.'.config('theme').'.widgets.topSlider')->render();
    }

    public static function getFeaturedProductsHtml($featuredProducts)
    {
        $html = '<featured-products data-gjs-editable="false" draggable="false" readonly="true">';
        $html .= '<section class="site-section" data-gjs-editable="false" draggable="false" readonly="true"><div class="container container--fixed" data-gjs-editable="false" draggable="false" readonly="true">';
        $html .= '<div class="site-section__title -style-uppercase" data-gjs-editable="false" draggable="false" readonly="true"><h2 data-gjs-editable="false" draggable="false" readonly="true">Featured PRODUCTS</h2></div>';
        $html .= '<div class="site-section__body" data-gjs-editable="false" draggable="false" readonly="true"><div class="caraousel caraousel--oneforth caraousel--oneforth-js" data-gjs-editable="false" draggable="false" readonly="true">';

        $html .= `<div class="card-cell">
            <div class="card card--hovered">
                <div class="card__head">
                    <figure class="card__media"><a href="#"><img src="images/300x300_1.png" alt=""></a></figure>
                    <span class="card__label">new</span>
                </div>
                <div class="card__body">
                    <div class="card__actions -positioned">
                        <ul>
                            <li class="action-like">
                                <a href="javascript:void(0)" class="btn--fav-js is-active" title="Remove from favorites">
                                    <span class="svg-icon">
                                       <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 23.217 23.217" style="enable-background:new 0 0 23.217 23.217;" xml:space="preserve" width="18px" height="15px">
                                           <path stroke-width="2" d="M11.608,21.997c-22.647-12.354-6.268-27.713,0-17.369C17.877-5.716,34.257,9.643,11.608,21.997z"></path>
                                       </svg>
                                   </span>
                                </a>
                            </li>
                            <li>
                                <a href="#" title="Add to Cart">
                                    <span class="svg-icon">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="14.75" height="18.44" viewBox="0 0 14.75 18.44">
<path d="M457.86,1560.76l-1.061-11.95a0.455,0.455,0,0,0-.457-0.42h-2.235a3.608,3.608,0,0,0-7.215,0h-2.234a0.461,0.461,0,0,0-.458.42l-1.06,11.95v0.04a2.586,2.586,0,0,0,2.727,2.41h9.266a2.587,2.587,0,0,0,2.727-2.41v-0.04Zm-7.36-15.06a2.7,2.7,0,0,1,2.692,2.69h-5.384A2.7,2.7,0,0,1,450.5,1545.7Zm4.633,16.59h-9.266a1.683,1.683,0,0,1-1.812-1.48l1.022-11.5h1.815v1.6a0.458,0.458,0,1,0,.916,0v-1.6h5.384v1.6a0.458,0.458,0,1,0,.915,0v-1.6h1.815l1.022,11.51A1.682,1.682,0,0,1,455.133,1562.29Z" transform="translate(-443.125 -1544.78)" />
</svg>
                                   </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" title="Quick View">
                                    <span class="svg-icon">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="18.688" height="11.03" viewBox="0 0 18.688 11.03">
<path d="M505.222,1554.92c-2.93-3.55-6.08-5.29-9.364-5.21-5.3.15-8.944,5.03-9.1,5.23-0.007.01-.012,0.03-0.018,0.04a0.03,0.03,0,0,0-.014.02,0.538,0.538,0,0,0-.028.06c0,0.01-.007.02-0.01,0.03s-0.011.04-.015,0.06,0,0.02,0,.03,0,0.04,0,.05a0.316,0.316,0,0,0,0,.05,0.235,0.235,0,0,0,0,.04q0.006,0.03.015,0.06c0,0.01.006,0.02,0.01,0.03a0.538,0.538,0,0,0,.028.06,0.243,0.243,0,0,0,.014.02c0.006,0.01.011,0.02,0.018,0.03,0.153,0.21,3.8,5.09,9.1,5.23a2.016,2.016,0,0,0,.245.01c3.195,0,6.262-1.76,9.119-5.22A0.492,0.492,0,0,0,505.222,1554.92Zm-9.331,4.86c-4.119-.11-7.266-3.53-8.117-4.55,0.851-1.02,4-4.43,8.117-4.55,2.875-.07,5.668,1.46,8.317,4.55C501.559,1558.33,498.769,1559.87,495.891,1559.78Zm0.352-7.75a3.2,3.2,0,1,0,3.2,3.2A3.208,3.208,0,0,0,496.243,1552.03Zm0,5.43a2.225,2.225,0,1,1,2.23-2.23A2.232,2.232,0,0,1,496.243,1557.46Z" transform="translate(-486.656 -1549.72)"/>
</svg>
                                   </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <span class="card__subtitle">Baby Clothes</span>
                    <span class="card__title"><a href="#">Blue Cassop Dress</a></span>
                    <div class="card-ratings">
                        <div class="card-ratings__star -display-inline"><img src="images/ratings.png" alt=""></div>
                        <div class="card-ratings__value -display-inline">4.4</div>
                    </div>
                    <hr>
                    <span class="card__oldprice">$550.99</span>
                    <span class="card__price">$519.99</span>
                    <span class="card__discount">-25%</span>
                </div>
            </div>
        </div>`;

        $html .= '</div></div></div></section></featured-products>';
        return $html;
    }
}
