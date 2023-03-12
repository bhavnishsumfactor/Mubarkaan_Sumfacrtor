//textWithTwoImagesOnRight functions
//open setting popup
$('iframe').contents().find("body").on('click', '.yk-textWithTwoImagesOnRight .yk-internalContent > *', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'textWithTwoImagesOnRight');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
//embed data into view
$(document).on('click', '.yk-addTextWithTwoImagesOnRightWidget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-textWithTwoImagesOnRight-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'textWithTwoImagesOnRight', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('section.yk-textWithTwoImagesOnRight')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-textWithTwoImagesOnRight .yk-container').attr('compid', cid);
            model.components(responseEl.html());
            resetComponentSettings(model, 'yk-textWithTwoImagesOnRight');
            thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            toastr.success(embedSuccessMessage);
        }
    });
    $('#settingsModal').modal('hide');
});
//open cropper modal
$(document).on('click', '.yk-textWithTwoImagesOnRight-settings .js-openCropper', function(e) {
    window.tmppath = '';
    window.actualImage = '';
    window.actualImage = $(this).closest('.yk-slide').find('img.actualImage').attr('src');
    $(this).closest('.yk-textWithTwoImagesOnRight-settings').find('#modal_cropper input[type="file"]').val('');
    $(this).closest('.yk-textWithTwoImagesOnRight-settings').find('#modal_cropper input[name="tabId"]').val($(this).closest('.yk-slide').attr('data-id'));
    window.aspectRatio = 0.75;
    if ($('.cropperSelectedImage').hasClass('cropper-hidden')) {
        window.$image.cropper('destroy');
    }
    if (window.actualImage == '') {
        $('.yk-textWithTwoImagesOnRight-settings .js-cropperSelectImage').trigger('click');
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
$(document).on('change', '.yk-textWithTwoImagesOnRight-settings input.js-cropperSelectImage', function(e) {
    if (e.target.files.length > 0) {
        selectImage(e.target.files[0]);
    }
});
//crop image
$(document).on('click', '.yk-textWithTwoImagesOnRight-settings .js-cropImage', async function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let formData = new FormData();
    formData.append('cid', thisObj.closest('.yk-textWithTwoImagesOnRight-settings').attr('data-comp'));
    let tabId = thisObj.closest('#modal_cropper').find('input[name="tabId"]').val();
    formData.append('layout', 'textWithTwoImagesOnRight');
    formData.append('records', tabId);
    var cropped = window.$image.cropper("getCroppedCanvas", { width: 400, height: 533 });
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
                thisObj.closest('.yk-textWithTwoImagesOnRight-settings').find('.yk-slide[data-id="' + tabId + '"] .actualImage').attr('src', response.data.originalUrl);
                thisObj.closest('.yk-textWithTwoImagesOnRight-settings').find('.yk-slide[data-id="' + tabId + '"] .croppedImage').attr('src', response.data.url).closest('.YK-preview').show();
                thisObj.closest('#modal_cropper').modal('hide');
                $('#settingsModal').modal('show');
                window.$image.cropper('destroy');
                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            }
        });
    }, mimeType, 1);
});