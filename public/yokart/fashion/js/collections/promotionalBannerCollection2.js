//promotional Banner Collection 2 functions
//open setting popup
$('iframe').contents().find("body").on('click', '.yk-promotionalBannerCollection2 .yk-images > *', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'promotionalBannerCollection2');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
//embed data into view
$(document).on('click', '.yk-addPromotionalBannerCollection2Widget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let slidesLength = thisObj.closest('.yk-promotionalBannerCollection2-settings').find(".yk-slides img.actualImage[src='']").length;
    if(slidesLength > 0){
        thisObj.removeClass('gb-is-loading').removeAttr('disabled');
        toastr.error(allBannersRequiredMessage);
        return false;
    }
    let cid = thisObj.closest('.yk-promotionalBannerCollection2-settings').attr('data-comp');
    let link_1 = thisObj.closest('.yk-promotionalBannerCollection2-settings').find('input[name="link_1"]').val();
    let target_1 = thisObj.closest('.yk-promotionalBannerCollection2-settings').find('select[name="target_1"] option:selected').val();
    let link_2 = thisObj.closest('.yk-promotionalBannerCollection2-settings').find('input[name="link_2"]').val();
    let target_2 = thisObj.closest('.yk-promotionalBannerCollection2-settings').find('select[name="target_2"] option:selected').val();
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'promotionalBannerCollection2', cid: cid, save: true, link_1: link_1, target_1: target_1, link_2: link_2, target_2: target_2 },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('section.yk-promotionalBannerCollection2')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-promotionalBannerCollection2 .yk-container').attr('compid', cid);
            model.components(responseEl.html());
            resetComponentSettings(model, 'yk-promotionalBannerCollection2');
            thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            toastr.success(embedSuccessMessage);
        }
    });
    $('#settingsModal').modal('hide');
});
//open cropper modal
$(document).on('click', '.yk-promotionalBannerCollection2-settings .js-openCropper', function(e) {
    window.tmppath = '';
    window.actualImage = '';
    window.actualImage = $(this).closest('.yk-slide').find('img.actualImage').attr('src');
    $(this).closest('.yk-promotionalBannerCollection2-settings').find('#modal_cropper input[type="file"]').val('');
    $(this).closest('.yk-promotionalBannerCollection2-settings').find('#modal_cropper input[name="tabId"]').val($(this).closest('.yk-slide').attr('data-id'));
    window.aspectRatio = 1.33;
    if ($('.cropperSelectedImage').hasClass('cropper-hidden')) {
        window.$image.cropper('destroy');
    }
    if (window.actualImage == '') {
        $('.yk-promotionalBannerCollection2-settings .js-cropperSelectImage').trigger('click');
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
$(document).on('change', '.yk-promotionalBannerCollection2-settings input.js-cropperSelectImage', function(e) {
    if (e.target.files.length > 0) {
        selectImage(e.target.files[0]);
    }
});
//crop image
$(document).on('click', '.yk-promotionalBannerCollection2-settings .js-cropImage', async function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let formData = new FormData();
    formData.append('cid', thisObj.closest('.yk-promotionalBannerCollection2-settings').attr('data-comp'));
    let tabId = thisObj.closest('#modal_cropper').find('input[name="tabId"]').val();
    formData.append('layout', 'promotionalBannerCollection2');
    formData.append('records', tabId);
    var cropped = window.$image.cropper("getCroppedCanvas", { width: 800, height: 600, imageSmoothingEnabled: true, imageSmoothingQuality: 'high'});
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
                thisObj.closest('.yk-promotionalBannerCollection2-settings').find('.yk-slide[data-id="' + tabId + '"] .actualImage').attr('src', response.data.originalUrl);
                thisObj.closest('.yk-promotionalBannerCollection2-settings').find('.yk-slide[data-id="' + tabId + '"] .croppedImage').attr('src', response.data.url).closest('.YK-preview').show();
                thisObj.closest('#modal_cropper').modal('hide');
                $('#settingsModal').modal('show');
                window.$image.cropper('destroy');
                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            }
        });
    }, mimeType, 1);
});
/*remove background image*/
$(document).on('click', '.yk-promotionalBannerCollection2-settings .js-removeBackgroundImage', function(e) {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-promotionalBannerCollection2-settings').attr('data-comp');
    let recordId = thisObj.closest('.yk-slide').attr('data-id');
    $.ajax({
        url: adminBaseUrl + '/collection/deleteimage',
        type: 'POST',
        data: { cid: cid, 'layout': 'promotionalBannerCollection2', record_id: recordId },
        success: function(response) {
            toastr.success(response.message);
            thisObj.closest('.yk-slide').find('img.actualImage').attr('src', '');
            thisObj.closest('.YK-preview').find('img').attr('src', '');
            thisObj.closest('.YK-preview').css('display', 'none');
        }
    });
});