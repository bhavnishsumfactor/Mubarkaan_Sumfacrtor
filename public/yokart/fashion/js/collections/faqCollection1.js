/* Faq Collection 1 functions */
/*open setting popup*/
$('iframe').contents().find("body").on('click', '.yk-faqCollection1 > *', function(e) { 
    var $link = $(e.target);
    if ($link.hasClass('yk-title') || $link.hasClass('yk-link')) {
        return false;
    }
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'faqCollection1');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
/*embed data into view*/
$(document).on('click', '.yk-addFaqCollection1Widget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-faqCollection1-settings').attr('data-comp');
    let selectedRecordsCount = thisObj.closest('.yk-faqCollection1-settings').find('.yk-selectedRecords li').length;
    if (selectedRecordsCount <= 6) {
        $.ajax({
            url: adminBaseUrl + '/widgets/load',
            type: 'POST',
            data: { 'layout': 'faqCollection1', 'cid': cid },
            success: function(response) {
                let model = '';
                if (typeof editor.getSelected() != 'undefined') {
                    model = editor.getSelected();
                } else {
                    model = editor.DomComponents.getWrapper().find('faqcollection1')[0];
                }
                var responseEl = $(response);
                responseEl.find('.yk-faqCollection1 .yk-container').attr('compid', cid);
                let innerHtml = model.getView().$el;
                $(innerHtml).find('faqcollection1').html(responseEl.html());
                model.components($(innerHtml).html());
                resetComponentSettings(model, 'yk-faqCollection1');
                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
                toastr.success(embedSuccessMessage);
            }
        });
        $('#settingsModal').modal('hide');
    } else {
        thisObj.removeClass('gb-is-loading').removeAttr('disabled');
        toastr.error(atmost6FaqsRequiredMessage);
    }
});
function embedFaqCollection1(){
    let thisObj = $('.yk-addFaqCollection1Widget');
    let cid = thisObj.closest('.yk-faqCollection1-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'faqCollection1', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('faqcollection1')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-faqCollection1 .yk-container').attr('compid', cid);
            let innerHtml = model.getView().$el;
            $(innerHtml).find('faqcollection1').html(responseEl.html());
            model.components($(innerHtml).html());
            resetComponentSettings(model, 'yk-faqCollection1');
        }
    });   
}
/*autocomplete*/
$(document).on('keydown.autocomplete', '.yk-autocompleteFaqCollection1', function() {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-faqCollection1-settings').attr('data-comp');
    thisObj.not('.ui-autocomplete-input').autocomplete({
        source: function(request, response) {
            $.ajax({
                method: "POST",
                url: adminBaseUrl + '/collection/search',
                dataType: "json",
                data: { term: request.term, layout: 'faqCollection1', cid: cid },
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
            let selectedRecordsCount = $(this).closest('.yk-faqCollection1-settings').find('.yk-selectedRecords li').length;
            if (selectedRecordsCount == 6) {
                toastr.error(atmost6FaqsRequiredMessage);
                ui.item.value = '';
                thisObj.val("");
                return;
            }
            var label = ui.item.label;
            var value = ui.item.value;
            let displayOrder = parseInt(thisObj.closest('.yk-faqCollection1-settings').find('.yk-selectedRecords').attr('data-highest-order')) + 1;
            thisObj.closest('.yk-faqCollection1-settings').find('.yk-selectedRecords').append(`<li class="list-group-item d-flex justify-content-between align-items-center" data-id="` + value + `" data-display-order="` + displayOrder + `">
<div class="d-flex  align-items-center">
    <i class="icon fa fa-arrows-alt handle mr-3"></i>  
    <span>` + label + `</span>
</div>
<div class="actions">
    <button type="button" class="btn btn-icon yk-removeFaqCollection1">
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
                data: { cid: cid, layout: 'faqCollection1', records: value, display_order: displayOrder },
                success: function(data) {

                    thisObj.closest('.yk-faqCollection1-settings').find('.yk-selectedRecords').attr('data-highest-order', displayOrder);
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
$(document).on('click', '.yk-removeFaqCollection1', function(e) {
    let thisObj = $(this);
    let recordId = thisObj.closest('li').attr('data-id');
    let cid = thisObj.closest('.yk-faqCollection1-settings').attr('data-comp');
    $.ajax({
        method: "POST",
        url: adminBaseUrl + '/collection/delete',
        dataType: "json",
        data: { cid: cid, layout: 'faqCollection1', record: recordId },
        success: function(response) {
            thisObj.closest('li').remove();
            $('#settingsModal').find('.yk-sortable').html(response.data.html);
            $('#settingsModal').find('.yk-sortable').attr('data-highest-order', response.data.highestOrder);
            toastr.success(response.message);
            embedFaqCollection1();
        }
    });
});