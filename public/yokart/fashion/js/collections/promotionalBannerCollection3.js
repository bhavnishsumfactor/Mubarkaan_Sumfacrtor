//Promotional Banner Collection 3 functions
//open setting popup
$('iframe').contents().find("body").on('click', '.yk-promotionalBannerCollection3 > *', function(e) {
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
            editSettings(cid, 'promotionalBannerCollection3');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
//embed data into view
$(document).on('click', '.yk-addPromotionalBannerCollection3Widget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-promotionalBannerCollection3-settings').attr('data-comp');
    let link = $(this).closest('.yk-promotionalBannerCollection3-settings').find('input[name="link"]').val();
    let target = $(this).closest('.yk-promotionalBannerCollection3-settings').find('select[name="target"] option:selected').val();
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'promotionalBannerCollection3', cid: cid, save: true, link: link, target: target },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('section.yk-promotionalBannerCollection3')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-promotionalBannerCollection3 .yk-container').attr('compid', cid);
            model.components(responseEl.html());
            resetComponentSettings(model, 'yk-promotionalBannerCollection3');
            thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            toastr.success(embedSuccessMessage);
        }
    });
    $('#settingsModal').modal('hide');
});
//open cropper modal
$(document).on('click', '.yk-promotionalBannerCollection3-settings .js-openCropper', function(e) {
    window.tmppath = '';
    window.actualImage = '';
    window.actualImage = $(this).closest('.yk-slide').find('img.actualImage').attr('src');
    $(this).closest('.yk-promotionalBannerCollection3-settings').find('#modal_cropper input[type="file"]').val('');
    window.aspectRatio = 4;
    if ($('.cropperSelectedImage').hasClass('cropper-hidden')) {
        window.$image.cropper('destroy');
    }
    if (window.actualImage == '') {
        $('.yk-promotionalBannerCollection3-settings .js-cropperSelectImage').trigger('click');
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
$(document).on('change', '.yk-promotionalBannerCollection3-settings input.js-cropperSelectImage', function(e) {
    if (e.target.files.length > 0) {
        selectImage(e.target.files[0]);
    }
});
//crop image
$(document).on('click', '.yk-promotionalBannerCollection3-settings .js-cropImage', async function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let formData = new FormData();
    formData.append('cid', thisObj.closest('.yk-promotionalBannerCollection3-settings').attr('data-comp'));
    formData.append('layout', 'promotionalBannerCollection3');
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
                thisObj.closest('.yk-promotionalBannerCollection3-settings').find('.actualImage').attr('src', response.data.originalUrl);
                thisObj.closest('.yk-promotionalBannerCollection3-settings').find('.croppedImage').attr('src', response.data.url).closest('.YK-preview').show();
                thisObj.closest('#modal_cropper').modal('hide');
                $('#settingsModal').modal('show');
                window.$image.cropper('destroy');
                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            }
        });
    }, mimeType, 1);
});
/*remove background image*/
$(document).on('click', '.yk-promotionalBannerCollection3-settings .js-removeBackgroundImage', function(e) {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-promotionalBannerCollection3-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/collection/deleteimage',
        type: 'POST',
        data: { cid: cid, 'layout': 'promotionalBannerCollection3' },
        success: function(response) {
            toastr.success(response.message);
            thisObj.closest('.yk-slide').find('img.actualImage').attr('src', '');
            thisObj.closest('.YK-preview').find('img').attr('src', '');
            thisObj.closest('.YK-preview').css('display', 'none');
        }
    });
});