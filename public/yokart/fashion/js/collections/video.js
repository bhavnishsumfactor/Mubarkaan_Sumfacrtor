//Video functions
//open setting popup
$('iframe').contents().find("body").on('click', '.yk-videoTag', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'videoTag');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
//embed data into view
$(document).on('click', '.yk-addVideoWidget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-video-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'videoTag', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('.yk-video')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-videoTag .yk-container').attr('compid', cid);
            model.components(responseEl.html());
            resetComponentSettings(model, 'yk-videoTag');
            thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            toastr.success(embedSuccessMessage);
        }
    });
    $('#settingsModal').modal('hide');
});
//save settings
$(document).on('blur', '.yk-video-settings input[name="provider_type"], .yk-video-settings input[name="video_url"], .yk-video-settings input[name="autoplay"]', function(e) {
    let cid = $(this).closest('.yk-video-settings').attr('data-comp');
    let provider_type = $(this).closest('.yk-video-settings').find('input[name="provider_type"]:checked').val();
    let autoplay = $(this).closest('.yk-video-settings').find('input[name="autoplay"]').prop("checked");
    let video_url = $(this).closest('.yk-video-settings').find('input[name="video_url"]').val();
    $.ajax({
        url: adminBaseUrl + '/collection/save',
        type: 'POST',
        data: { cid: cid, 'layout': 'videoTag', provider_type: provider_type, video_url: video_url, autoplay: autoplay },
        success: function(response) {}
    });
});