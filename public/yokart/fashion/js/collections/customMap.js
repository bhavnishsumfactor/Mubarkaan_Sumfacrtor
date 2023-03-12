//customMap functions
//open setting popup
$('iframe').contents().find("body").on('click', '.yk-mapContainer .yk-customMap', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'customMap');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
//embed data into view
$(document).on('click', '.yk-addCustomMapWidget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-customMap-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'customMap', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('.yk-customMap')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-customMap .yk-container').attr('compid', cid);
            model.components(responseEl.html());
            resetComponentSettings(model, 'yk-customMap');
            thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            toastr.success(embedSuccessMessage);
        }
    });
    $('#settingsModal').modal('hide');
});
//save settings
$(document).on('blur', '.yk-customMap-settings textarea[name="map_script"]', function(e) {
    let cid = $(this).closest('.yk-customMap-settings').attr('data-comp');
    let map_script = $(this).closest('.yk-customMap-settings').find('textarea[name="map_script"]').val();
    $.ajax({
        url: adminBaseUrl + '/collection/save',
        type: 'POST',
        data: { cid: cid, 'layout': 'customMap', map_script: map_script },
        success: function(response) {}
    });
});
$('iframe').contents().find("body").on('click', '.yk-mapContainer', function(e) {
    var $link = $(e.target);
    let thisObj = $(this);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            if (thisObj.find('.yk-customMap').length > 0) {
                thisObj.find('.yk-customMap').trigger('click');
            }
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});