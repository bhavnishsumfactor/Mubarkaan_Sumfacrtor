/* testimonial layout 2 functions */
/*open setting popup*/
$('iframe').contents().find("body").on('click', '.yk-testimonialCollection2 .yk-testimonials > *', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'testimonialCollection2');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
/*embed data into view*/
$(document).on('click', '.yk-addTestimonialCollection2Widget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-testimonialCollection2-settings').attr('data-comp');
    let selectedCount = thisObj.closest('.yk-testimonialCollection2-settings').find('.yk-selectedTestimonials li').length;
    if (selectedCount == 0 || (selectedCount >= 3 && selectedCount <= 6)) {
        $.ajax({
            url: adminBaseUrl + '/widgets/load',
            type: 'POST',
            data: { 'layout': 'testimonialCollection2', 'cid': cid },
            success: function(response) {
                let model = '';
                if (typeof editor.getSelected() != 'undefined') {
                    model = editor.getSelected();
                } else {
                    model = editor.DomComponents.getWrapper().find('section.yk-testimonialCollection2')[0];
                }

                var responseEl = $(response);
                responseEl.find('.yk-testimonialCollection2 .yk-container').attr('compid', cid);
                responseEl.find('testimonialcollection2').attr('compid', cid);
                let innerHtml = model.getView().$el;
                $(innerHtml).find('testimonialcollection2').html(responseEl.html());
                model.components($(innerHtml).html());
                resetComponentSettings(model, 'yk-testimonialCollection2');

                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
                toastr.success(embedSuccessMessage);
            }
        });
        $('#settingsModal').modal('hide');
    } else {
        thisObj.removeClass('gb-is-loading').removeAttr('disabled');
        toastr.error(between3To6TestimonialsRequiredMessage);
    }
});
function embedTestimonialCollection2(){
    let thisObj = $('.yk-addTestimonialCollection2Widget');
    let cid = thisObj.closest('.yk-testimonialCollection2-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'testimonialCollection2', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('section.yk-testimonialCollection2')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-testimonialCollection2 .yk-container').attr('compid', cid);
            responseEl.find('testimonialcollection2').attr('compid', cid);
            let innerHtml = model.getView().$el;
            $(innerHtml).find('testimonialcollection2').html(responseEl.html());
            model.components($(innerHtml).html());
            resetComponentSettings(model, 'yk-testimonialCollection2');
        }
    });   
}
/*autocomplete*/
$(document).on('keydown.autocomplete', '.yk-autocompleteTestimonialCollection2', function() {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-testimonialCollection2-settings').attr('data-comp');
    thisObj.not('.ui-autocomplete-input').autocomplete({
        source: function(request, response) {
            $.ajax({
                method: "POST",
                url: adminBaseUrl + '/collection/search',
                dataType: "json",
                data: { term: request.term, layout: 'testimonialCollection2', cid: cid },
                success: function(data) {
                    response(data);
                    if (!$('ul.ui-autocomplete').hasClass('ps')) {
                        new PerfectScrollbar('ul.ui-autocomplete');
                    }
                }
            });
        },
        minLength: 1,
        appendTo: thisObj.parent(),
        select: function(event, ui) {
            let selectedCount = $(this).closest('.yk-testimonialCollection2-settings').find('.yk-selectedTestimonials li').length;
            if (selectedCount == 6) {
                toastr.error(between3To6TestimonialsRequiredMessage);
                ui.item.value = '';
                thisObj.val("");
                return;
            }
            var label = ui.item.label;
            var value = ui.item.value;
            let displayOrder = parseInt(thisObj.closest('.yk-testimonialCollection2-settings').find('.yk-selectedTestimonials').attr('data-highest-order')) + 1;
            thisObj.closest('.yk-testimonialCollection2-settings').find('.yk-selectedTestimonials').append(`<li class="list-group-item d-flex justify-content-between align-items-center" data-id="` + value + `" data-display-order="` + displayOrder + `">
<div class="d-flex  align-items-center">
    <i class="icon fa fa-arrows-alt handle mr-3"></i>  
    <span>` + label + `</span>
</div>
<div class="actions">
    <button type="button" class="btn btn-icon yk-removeTestimonialCollection2">
        <svg>   
            <use xlink:href="` + adminBaseUrl + `/images/retina/sprite.svg#delete-icon" ></use>                               
        </svg>
    </button>
</div>
</li>`);
            ui.item.value = '';
            thisObj.val("");
            $.ajax({
                method: "POST",
                url: adminBaseUrl + '/collection/save',
                dataType: "json",
                data: { cid: cid, layout: 'testimonialCollection2', records: value, display_order: displayOrder },
                success: function(data) {
                    thisObj.closest('.yk-testimonialCollection2-settings').find('.yk-selectedTestimonials').attr('data-highest-order', displayOrder);
                }
            });
        },
        focus: function(event, ui) {
            thisObj.val(ui.item.label);
            return false;
        }
    });
});
/*remove collection option*/
$(document).on('click', '.yk-removeTestimonialCollection2', function(e) {
    let thisObj = $(this);
    let testimonialId = thisObj.closest('li').attr('data-id');
    let cid = thisObj.closest('.yk-testimonialCollection2-settings').attr('data-comp');
    $.ajax({
        method: "POST",
        url: adminBaseUrl + '/collection/delete',
        dataType: "json",
        data: { cid: cid, layout: 'testimonialCollection2', record: testimonialId },
        success: function(response) {
            thisObj.closest('li').remove();
            $('#settingsModal').find('.yk-sortable').html(response.data.html);
            $('#settingsModal').find('.yk-sortable').attr('data-highest-order', response.data.highestOrder);
            toastr.success(response.message);
            embedTestimonialCollection2();
        }
    });
});