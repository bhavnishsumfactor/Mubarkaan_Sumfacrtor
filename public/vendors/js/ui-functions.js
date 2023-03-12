/* 
STARTS triggers & toggles[

data-trigger => value = target element id to be opened
data-target-close => value = target element id to be closed
data-close-on-click-outside => value 

*/

$('body').on('click', '*[data-trigger]', function() {
    var targetElmId = $(this).data('trigger');
    var elmToggleClass = targetElmId + '--on';
    if ($('body').hasClass(elmToggleClass)) {
        $('body').removeClass(elmToggleClass);
    } else {
        $('body').addClass(elmToggleClass);
    }
});
$('body').on('click', '*[data-target-close]', function() {
    var targetElmId = $(this).data('target-close');
    $('body').toggleClass(targetElmId + '--on');
});

$('body').mouseup(function(event) {

    if ($(event.target).data('trigger') != '' && typeof $(event.target).data('trigger') !== typeof undefined) {
        event.preventDefault();
        return;
    }

    $('body').find('*[data-close-on-click-outside]').each(function(idx, elm) {
        var slctr = $(elm);
        if (!slctr.is(event.target) && !$.contains(slctr[0], event.target)) {
            $('body').removeClass(slctr.data('close-on-click-outside') + '--on');
        }
    });
});

/*
] ENDS triggers & toggles
*/



/* begin:tabs */
$(document).ready(function() {
    $(window).bind('hashchange', function(event) {
        var url = window.location.href.split('#')[0];
        window.history.pushState({}, document.title, url);
    });

    $('.js-tabs li a').on('click', function() {
        var tabId = $(this).attr("href")
            .replace("#", "");
        $(this).closest('section').find("li").removeClass("active");
        $(this).closest('section').find('.content-data').removeClass("active");
        $(this).closest("li").addClass("active");
        $("#" + tabId).addClass("active");
        moveToTargetDiv(this);
        return false;
    });
});

function moveToTargetDiv(liElm) {

    var out = $(liElm).parent().parent();
    var tar = $(liElm);
    var x = out.width();
    var y = tar.outerWidth(true);
    var z = tar.index();
    var q = 0;
    var m = out.find('li');

    for (var i = 0; i < z; i++) {
        q += $(m[i]).outerWidth(true) + 4;
    }

    out.animate({
        scrollLeft: Math.max(0, q)
    }, 800);
    return false;
}

/* for fixed header */

$(window).scroll(function() {
    body_height = $("#body").position();
    scroll_position = $(window).scrollTop();
    if (typeof body_height != 'undefined') {
        if (body_height.top < scroll_position)
            $("body").addClass("header-fixed");
        else
            $("body").removeClass("header-fixed");
    }
});


/*Back to Top */
var btn = $('#back-top');

$(window).scroll(function() {
    if ($(window).scrollTop() > 300) {
        btn.addClass('show');
    } else {
        btn.removeClass('show');
    }
});

btn.on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: 0 }, '300');
});



$(function() {
    $(document).on('click', '.YK-animatedBtn', function() {
        var thisObj = $(this);
        thisObj.addClass('active');
        setTimeout(() => {
            thisObj.removeClass('active');
            thisObj.addClass('finished');
        }, 2000);
    });
    // $('.select-fancy').niceSelect();
})
loader = function(obj, removeLoader = false) {
    if (removeLoader == false) {
        obj.attr("disabled", "disabled");
        obj.addClass("gb-is-loading");
    } else {
        obj.removeAttr("disabled");
        obj.removeClass('gb-is-loading');
    }
}