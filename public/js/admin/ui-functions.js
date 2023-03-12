/* 
STARTS triggers & toggles[

data-trigger => value = target element id to be opened
data-target-close => value = target element id to be closed
data-close-on-click-outside => value 
*/

$('body').find('*[data-trigger]').click(function () {

	var targetElmId = $(this).data('trigger');
	var elmToggleClass = targetElmId + '--on';
	if ($('body').hasClass(elmToggleClass)) {
		$('body').removeClass(elmToggleClass);
	} else {
		$('body').addClass(elmToggleClass);
	}
});

$('body').find('*[data-target-close]').click(function () {
	var targetElmId = $(this).data('target-close');
	$('body').toggleClass(targetElmId + '--on');
});

$('body').mouseup(function (event) {

	if ($(event.target).data('trigger') != '' && typeof $(event.target).data('trigger') !== typeof undefined) {
		event.preventDefault();
		return;
	}

	$('body').find('*[data-close-on-click-outside]').each(function (idx, elm) {
		var slctr = $(elm);
		if (!slctr.is(event.target) && !$.contains(slctr[0], event.target)) {
			$('body').removeClass(slctr.data('close-on-click-outside') + '--on');
		}
	});
});

/*
] ENDS triggers & toggles
*/

new ScrollHint('.js--table-scrollable');


jQuery(document).ready(function ($) {
	jQuery('.stellarnav').stellarNav({
		theme: 'custom',
		breakpoint: 1024,
		position: 'left',


	});
});

$(function () {
  //$('[data-toggle="tooltip"]').tooltip()
})

$(function () {
  //$('[data-toggle="popover"]').popover()
})