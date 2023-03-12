//titleWithBackgroundImage functions
//open setting popup
$('iframe').contents().find("body").on('click', '.yk-titleWithBackgroundImage > *', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'titleWithBackgroundImage');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
//embed data into view
$(document).on('click', '.yk-addTitleWithBackgroundImageWidget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-titleWithBackgroundImage-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'titleWithBackgroundImage', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('section.yk-titleWithBackgroundImage')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-titleWithBackgroundImage .yk-container').attr('compid', cid);
            model.components(responseEl.html());
            resetComponentSettings(model, 'yk-titleWithBackgroundImage');
            thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            toastr.success(embedSuccessMessage);
        }
    });
    $('#settingsModal').modal('hide');
});
//open cropper modal
$(document).on('click', '.yk-titleWithBackgroundImage-settings .js-openCropper', function(e) {
    window.tmppath = '';
    window.actualImage = '';
    window.actualImage = $(this).closest('.yk-slide').find('img.actualImage').attr('src');
    $(this).closest('.yk-titleWithBackgroundImage-settings').find('#modal_cropper input[type="file"]').val('');
    window.aspectRatio = 4;
    if ($('.cropperSelectedImage').hasClass('cropper-hidden')) {
        window.$image.cropper('destroy');
    }
    if (window.actualImage == '') {
        $('.yk-titleWithBackgroundImage-settings .js-cropperSelectImage').trigger('click');
        return;
    }
    $('#settingsModal').modal('hide');
    $('#modal_cropper').modal('show');
    $('.cropperSelectedImage').attr('src', window.actualImage);
    setTimeout(function() {
        loadCropper();
        window.tmppath = window.actualImage;
        window.$image.cropper('replace', window.actualImage)
    }, 200);
});
//select image in cropper
$(document).on('change', '.yk-titleWithBackgroundImage-settings input.js-cropperSelectImage', function(e) {
    if (e.target.files.length > 0) {
        selectImage(e.target.files[0]);
    }
});
//crop image
$(document).on('click', '.yk-titleWithBackgroundImage-settings .js-cropImage', async function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let formData = new FormData();
    formData.append('cid', thisObj.closest('.yk-titleWithBackgroundImage-settings').attr('data-comp'));
    formData.append('layout', 'titleWithBackgroundImage');
    var cropped = window.$image.cropper("getCroppedCanvas", { width: 2000, height: 500 });
    var mimeType = await getFileMimeType($(".cropperSelectedImage").attr('src'));
    cropped.toBlob((croppedBlob) => {
        formData.append('cropImage', croppedBlob);
        formData.append('actualImage', window.actualImage);
        $.ajax({
            url: adminBaseUrl + '/collection/save',
            type: 'POST',
            enctype: 'multipart/form-data',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                toastr.success(response.message);
                thisObj.closest('.yk-titleWithBackgroundImage-settings').find('.actualImage').attr('src', response.data.originalUrl);
                thisObj.closest('.yk-titleWithBackgroundImage-settings').find('.croppedImage').attr('src', response.data.url).closest('.YK-preview').show();
                thisObj.closest('#modal_cropper').modal('hide');
                $('#settingsModal').modal('show');
                window.$image.cropper('destroy');
                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            }
        });
    }, mimeType, 1);
});
/*remove background image*/
$(document).on('click', '.yk-titleWithBackgroundImage-settings .js-removeBackgroundImage', function(e) {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-titleWithBackgroundImage-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/collection/deleteimage',
        type: 'POST',
        data: { cid: cid, 'layout': 'titleWithBackgroundImage' },
        success: function(response) {
            toastr.success(response.message);
            thisObj.closest('.yk-slide').find('img.actualImage').attr('src', '');
            thisObj.closest('.YK-preview').find('img').attr('src', '');
            thisObj.closest('.YK-preview').css('display', 'none');
        }
    });
});
//save settings
$(document).on('blur', '.yk-titleWithBackgroundImage-settings input[type="text"]', function(e) {
    let cid = $(this).closest('.yk-titleWithBackgroundImage-settings').attr('data-comp');
    let text1 = $(this).closest('.yk-titleWithBackgroundImage-settings').find('input[name="text1"]').val();
    $.ajax({
        url: adminBaseUrl + '/collection/save',
        type: 'POST',
        data: { cid: cid, 'layout': 'titleWithBackgroundImage', text1: text1 },
        success: function(response) {}
    });
});