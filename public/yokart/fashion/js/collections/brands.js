/* brand functions */
/*open setting popup*/
$('iframe').contents().find("body").on('click', '.yk-brands .yk-brandimages > *', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'brands');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
/*embed data into view*/
$(document).on('click', '.yk-addBrandWidget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-brands-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'brands', 'cid': cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('section.yk-brands')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-brands .yk-container').attr('compid', cid);
            responseEl.find('brands').attr('compid', cid);
            model.components(responseEl.html());
            resetComponentSettings(model, 'yk-brands');
            thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            toastr.success(embedSuccessMessage);
        }
    });
    $('#settingsModal').modal('hide');
});
function embedBrandCollection1(){
    let thisObj = $('.yk-addBrandWidget');
    let cid = thisObj.closest('.yk-brands-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'brands', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('section.yk-brands')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-brands .yk-container').attr('compid', cid);
            responseEl.find('brands').attr('compid', cid);
            model.components(responseEl.html());
            resetComponentSettings(model, 'yk-brands');
        }
    });   
}
/*autocomplete*/
$(document).on('keydown.autocomplete', '.yk-autocompleteBrands', function() {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-brands-settings').attr('data-comp');
    thisObj.not('.ui-autocomplete-input').autocomplete({
        source: function(request, response) {
            $.ajax({
                method: "POST",
                url: adminBaseUrl + '/collection/search',
                dataType: "json",
                data: { term: request.term, layout: 'brands', cid: cid },
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
            var label = ui.item.label;
            var value = ui.item.value;
            let displayOrder = parseInt(thisObj.closest('.yk-brands-settings').find('.yk-selectedBrands').attr('data-highest-order')) + 1;
            thisObj.closest('.yk-brands-settings').find('.yk-selectedBrands').append(`<li class="list-group-item d-flex justify-content-between align-items-center" data-id="` + value + `" data-display-order="` + displayOrder + `">
<div class="d-flex  align-items-center">
    <i class="icon fa fa-arrows-alt handle mr-3"></i>  
    <span>` + label + `</span>
</div>
<div class="actions">
    <button type="button" class="btn btn-icon yk-removeBrand">
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
                data: { cid: cid, layout: 'brands', records: value, display_order: displayOrder },
                success: function(data) {
                    thisObj.closest('.yk-brands-settings').find('.yk-selectedBrands').attr('data-highest-order', displayOrder);
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
$(document).on('click', '.yk-removeBrand', function(e) {
    let thisObj = $(this);
    let brandId = thisObj.closest('li').attr('data-id');
    let cid = thisObj.closest('.yk-brands-settings').attr('data-comp');
    $.ajax({
        method: "POST",
        url: adminBaseUrl + '/collection/delete',
        dataType: "json",
        data: { cid: cid, layout: 'brands', record: brandId },
        success: function(response) {
            thisObj.closest('li').remove();
            $('#settingsModal').find('.yk-sortable').html(response.data.html);
            $('#settingsModal').find('.yk-sortable').attr('data-highest-order', response.data.highestOrder);
            toastr.success(response.message);
            embedBrandCollection1();
        }
    });
});