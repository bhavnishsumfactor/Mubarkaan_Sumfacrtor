/*on click of contact form inside*/
$('iframe').contents().find("body").on('click', '.yk-contactForm > *', function(e) {
    let thisObj = $(this);
    if ($(e.target).hasClass('yk-caption') === false) {
        $(e.target).trigger("blur");
    }
    setTimeout(function() {
        const component = editor.getSelected();
        if (typeof component != 'undefined') {
            let elementid = component.getView().$el.closest('.yk-contactForm').attr('id');
            editor.select(editor.DomComponents.getWrapper().find('#' + elementid)[0]);
            thisObj.find('.gjs-comp-selected').removeClass('gjs-comp-selected');
        }
    }, 200);
});