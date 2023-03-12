//twoColumnTextCta functions
//open setting popup
$('iframe').contents().find("body").on('click', '.yk-twoColumnTextCta > *', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            if(typeof cid == 'undefined'){
                return false;
            }
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'twoColumnTextCta');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
//embed data into view
$(document).on('click', '.yk-addTwoColumnTextCtaWidget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-twoColumnTextCta-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'twoColumnTextCta', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('section.yk-twoColumnTextCta')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-twoColumnTextCta .yk-container').attr('compid', cid);
            model.components(responseEl.html());
            resetComponentSettings(model, 'yk-twoColumnTextCta');
            thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            toastr.success(embedSuccessMessage);
        }
    });
    $('#settingsModal').modal('hide');
});
//save settings
$(document).on('blur', '.yk-twoColumnTextCta-settings input[type="text"], .yk-twoColumnTextCta-settings select[name="cta_target"]', function(e) {
    let cid = $(this).closest('.yk-twoColumnTextCta-settings').attr('data-comp');
    let cta_label = $(this).closest('.yk-twoColumnTextCta-settings').find('input[name="cta_label"]').val();
    let cta_link = $(this).closest('.yk-twoColumnTextCta-settings').find('input[name="cta_link"]').val();
    let cta_target = $(this).closest('.yk-twoColumnTextCta-settings').find('select[name="cta_target"] option:selected').val();
    $.ajax({
        url: adminBaseUrl + '/collection/save',
        type: 'POST',
        data: { cid: cid, 'layout': 'twoColumnTextCta', cta_label: cta_label, cta_link: cta_link, cta_target: cta_target },
        success: function(response) {}
    });
});