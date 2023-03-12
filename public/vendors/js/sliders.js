var rtlValue = false;
if ($('html').attr('dir') == 'rtl') {
    rtlValue = true;
}

$('.js-hero-banner').each(function() {
    if ($(this).children().length == 1) {
        $(this).removeClass('js-hero-banner');
    }
});
$('.js-hero-banner').each(function() {
    let thisObj = $(this);
    thisObj.slick({
        lazyLoad: 'progressive',
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        arrows: true,
        dots: true,
        rtl: rtlValue,
        autoplaySpeed: thisObj.attr('data-duration') + '000',
        prevArrow: '<div class="slick-nav prev-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
        nextArrow: '<div class="slick-nav next-arrow"><i></i><svg><use xlink:href="#circle"></svg></div>',
    });
});

$('.slick-nav').on('click touch', function(e) {
    e.preventDefault();
    var arrow = $(this);
    if (!arrow.hasClass('animate')) {
        arrow.addClass('animate');
        setTimeout(() => {
            arrow.removeClass('animate');
        }, 1600);
    }
});


$('.js-collection-slider').each(function() {
    if ($(this).children('li').length <= 4) {
        $(this).removeClass('js-collection-slider');
    }
});
$(".js-collection-slider").slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    autoplay: false,
    arrows: true,
    dots: false,
    rtl: rtlValue,
    autoplaySpeed: 2000,
    responsive: [{
            breakpoint: 1199,
            settings: {
                slidesToShow: 4,
                dots: false,
                arrows: false,
            }
        },
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 3,
                arrows: false,
                dots: false,

            }
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 2,
                arrows: false,
                dots: false,

            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 2,
                arrows: false,
                dots: false,

            }
        }

    ]
});
$(".js-collection-slider-sm").slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: false,
    arrows: true,
    dots: false,
    rtl: rtlValue,
    autoplaySpeed: 2000,
    responsive: [{
            breakpoint: 1030,
            settings: {
                slidesToShow: 3,
                dots: false,
                arrows: false,
            }
        },
        {
            breakpoint: 800,
            settings: {
                slidesToShow: 2,
                arrows: false,
                dots: false,

            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                arrows: false,
                dots: false,

            }
        }
    ]
});
$('.yk-brandimages').each(function() {
    if ($(this).children('div').length <= 6) {
        $(this).removeClass('yk-brandimages');
    }
});
$(".yk-brandimages").slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    autoplay: false,
    arrows: false,
    dots: false,
    rtl: rtlValue,
    autoplaySpeed: 2000,
    responsive: [{
            breakpoint: 1030,
            settings: {
                slidesToShow: 3,
                dots: false,


            }
        },
        {
            breakpoint: 800,
            settings: {
                slidesToShow: 2,
                arrows: false,
                dots: false,

            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 2,
                arrows: false,
                dots: false,

            }
        }

    ]
});

$(".js-slider-reviews").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: false,
    arrows: true,
    dots: true,
    rtl: rtlValue,
    autoplaySpeed: 2000,
    responsive: [{
        breakpoint: 600,
        settings: {
            arrows: false,
        }
    }]
});
$('.js-slider-testimonials').each(function() {
    if ($(this).children('.testimonials__item').length <= 3) {
        $(this).removeClass('js-slider-testimonials');
        $(this).addClass('testimonial-1-no-slider');
    }
});
$(".js-slider-testimonials").slick({
    slidesToShow: 3,
    slidesToScroll: 3,
    autoplay: false,
    arrows: true,
    dots: false,
    rtl: rtlValue,
    infinite: true,
    centerMode: true,
    centerPadding: 0,
    responsive: [{
            breakpoint: 1199,
            settings: {
                slidesToShow: 2,
                dots: true,
            }
        },
        {
            breakpoint: 800,
            settings: {
                slidesToShow: 2,
                dots: true,
            }
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 1,
                arrows: false,
                dots: true,
            }
        }

    ]
});
$('.yk-testimonialCollection2').each(function() {
    if ($(this).find('.yk-testimonials').children('.testimonials__item').length <= 2) {
        $(this).find('.slider-controls').remove();
    }
});

//Start of Common Carousel
var _carousel = $('.js-carousel');
_carousel.each(function() {

    var _this = $(this),
        _slidesToShow = (_this.data("slides")).toString().split(',');

    _this.slick({
        slidesToShow: parseInt(_slidesToShow.length > 0 ? _slidesToShow[0] : "3"),
        slidesToScroll: 1,
        //appendDots: '.slider-controls[data-href="#' + _this.attr("id") + '"]',
        centerMode: _this.data("mode"),
        arrows: _this.data("arrows"),
        vertical: _this.data("vertical"),
        infinite: _this.data("infinite"),
        prevArrow: (() => {
            return $('.prev-arrow[data-href="#' + _this.attr("id") + '"]')
        })(),
        nextArrow: (() => {
            return $('.next-arrow[data-href="#' + _this.attr("id") + '"]')
        })(),
        autoplay: false,
        dots: true,
        pauseOnHover: false,
        centerPadding: 0,
        adaptiveHeight: true,
        infinite: true,
        responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: parseInt(parseInt(_slidesToShow.length > 1 ? _slidesToShow[1] : "2")),
                    vertical: false
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: parseInt(parseInt(_slidesToShow.length > 2 ? _slidesToShow[2] : "1")),
                    vertical: false
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: parseInt(parseInt(_slidesToShow.length > 3 ? _slidesToShow[3] : "1")),
                    vertical: false
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: parseInt(parseInt(_slidesToShow.length > 4 ? _slidesToShow[4] : "1")),
                    vertical: false
                }
            }
        ]
    });
});
//End of Common Carousel

// blog slider

$('.blog-slider-for').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.blog-slider-nav'
});
$('.blog-slider-nav').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    asNavFor: '.blog-slider-for',
    dots: false,
    focusOnSelect: true,
    responsive: [{
        breakpoint: 992,
        settings: {
            arrows: false,
            dots: true,
        }
    }]
});