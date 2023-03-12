/* banner Slider functions */
/*open setting popup*/
$('iframe').contents().find("body").on('click', '.yk-bannerSlider > *', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'bannerSlider');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
/*embed data into view*/
$(document).on('click', '.yk-addBannerSliderWidget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-bannerSlider-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'bannerSlider', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('.yk-navigationLayout1')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-navigationLayout1 .yk-container').attr('compid', cid);
            model.components(responseEl.html());
            resetComponentSettings(model, 'yk-navigationLayout1');
            thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            toastr.success(embedSuccessMessage);
        }
    });
    $('#settingsModal').modal('hide');
});
/*add new category tab*/
$(document).on('click', '.yk-addNewSlide', function(e) {
    let cid = $(this).closest('.yk-bannerSlider-settings').attr('data-comp');
    let settingsObj = $(this).closest('.yk-bannerSlider-settings');
    let tabCount = settingsObj.find('ul.nav li[data-id]').length;
    if (tabCount == 6) {
        return false;
    }
    let newTabNumber = getRandomNumber();
    while (settingsObj.find('ul.nav li[data-id="sliderwidget-tab-' + newTabNumber + '"]').length != 0) {
        newTabNumber = getRandomNumber();
    }
    settingsObj.find('ul.nav li .nav-link').removeClass('active');
    settingsObj.find('ul.nav li:last').before(`<li class="nav-item" data-id="sliderwidget-tab-` + newTabNumber + `">
<a class="nav-link active" data-toggle="tab" href="#sliderwidget-tab-` + newTabNumber + `"><span>Slide ` + (parseInt(tabCount) + 1) + `</span><i class="fa fa-remove ml-2 yk-removeSlide"></i></a>
</li>`);
    if (tabCount == 5) {
        settingsObj.find('ul.nav li:last').remove();
    }
    $.ajax({
        url: adminBaseUrl + '/collection/settings',
        type: 'POST',
        data: { cid: cid, 'layout': 'bannerSlider', params: newTabNumber, currentTabCount: tabCount },
        success: function(response) {
            settingsObj.find('div.tab-content .tab-pane').removeClass('show active');
            settingsObj.find('div.tab-content').append(response);
            settingsObj.find('div.tab-content .tab-pane:last').addClass('show active');
        }
    });
});

/*save slider duration*/
$(document).on('blur', '.yk-bannerSlider-settings input[name="slide_duration"]', function(e) {
    let cid = $(this).closest('.yk-bannerSlider-settings').attr('data-comp');
    let slide_duration = $(this).val();
    $.ajax({
        url: adminBaseUrl + '/sliders/settings',
        type: 'POST',
        data: { cid: cid, 'layout': 'bannerSlider', slide_duration: slide_duration },
        success: function(response) {}
    });
});

/*save slide url*/
$(document).on('change', '.yk-bannerSlider-settings input[name="url"], .yk-bannerSlider-settings select[name="target"]', function(e) {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-bannerSlider-settings').attr('data-comp');
    let slideId = thisObj.closest('.tab-pane').attr('data-slide-id');
    let slideUrl = thisObj.closest('.tab-pane').find('input[name="url"]').val();
    if (slideUrl != '' && /(http(s)?:\\)?([\w-]+\.)+[\w-]+[.com|.in|.org]+(\[\?%&=]*)?/.test(slideUrl) == false) {
        toastr.error(pleaseEnterValidUrlMessage);
        return;
    }
    let slideTarget = thisObj.closest('.tab-pane').find('select[name="target"] option:selected').val();
    $.ajax({
        url: adminBaseUrl + '/sliders/save',
        type: 'POST',
        data: { cid: cid, 'layout': 'bannerSlider', slide_id: slideId, slide_url: slideUrl, slide_target: slideTarget },
        success: function(response) {
            if (slideId == '') {
                thisObj.closest('.tab-pane').attr('data-slide-id', response.data.slide_id);
            }
            toastr.success(response.message);
        }
    });
});

/*remove category tab*/
$(document).on('click', '.yk-removeSlide', function(e) {
    let thisObj = $(this);
    let settingsObj = thisObj.closest('.yk-bannerSlider-settings');
    let tabId = thisObj.closest('li.nav-item').attr('data-id');
    let cid = thisObj.closest('.yk-bannerSlider-settings').attr('data-comp');
    let slideId = thisObj.closest('.yk-bannerSlider-settings').find('#' + tabId).attr('data-slide-id');
    $.ajax({
        url: adminBaseUrl + '/sliders/delete',
        type: 'POST',
        data: { cid: cid, 'layout': 'bannerSlider', slide_id: slideId },
        success: function(response) {
            toastr.success(response.message);

            if (thisObj.closest('a.nav-link').hasClass('active')) {
                let prevTab = thisObj.closest('li.nav-item').prev('li.nav-item');
                let prevTabId = prevTab.attr('data-id');
                prevTab.find('a.nav-link').addClass('active');
                settingsObj.find('div.tab-content div#' + prevTabId).addClass('active show');
            }
            thisObj.closest('li.nav-item').remove();
            settingsObj.find('div.tab-content div#' + tabId).remove();

            let i = 1;
            $.each(settingsObj.find('ul.nav li[data-id]'), function() {
                $(this).find('a span').text('Slide ' + i);
                i++;
            });
            if (settingsObj.find('ul.nav li[data-id]').length == 5) {
                settingsObj.find('ul.nav').append(`<li class="nav-item"><a  class="nav-link yk-addNewSlide" href="javascript:;"><i class="fa fa-plus"></i></a></li>`);
            }
        }
    });
});
/*remove slide image for specific device type*/
$(document).on('click', '.js-removeSlideDeviceImage', function(e) {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-bannerSlider-settings').attr('data-comp');
    let slideId = thisObj.closest('.tab-pane').attr('data-slide-id');
    let slideType = thisObj.closest('.yk-slide').attr('data-type');
    $.ajax({
        url: adminBaseUrl + '/sliders/deleteslideimage',
        type: 'POST',
        data: { cid: cid, 'layout': 'bannerSlider', slide_id: slideId, slide_type: slideType },
        success: function(response) {
            toastr.success(response.message);
            thisObj.closest('.yk-slide').find('img.actualImage').attr('src', '');
            thisObj.closest('.YK-preview').find('img').attr('src', '');
            thisObj.closest('.YK-preview').css('display', 'none');
        }
    });
});


$(document).on('click', '.yk-bannerSlider-settings .js-openCropper', function(e) {
    window.tmppath = '';
    window.actualImage = '';
    window.actualImage = $(this).closest('.yk-slide').find('img.actualImage').attr('src');
    $(this).closest('.yk-bannerSlider-settings').find('#modal_cropper input[type="file"]').val('');
    $('#modal_cropper').find('input[name="tabId"]').val($(this).closest('.tab-pane').attr('id'));
    $('#modal_cropper').find('input[name="slideType"]').val($(this).closest('.yk-slide').attr('data-type'));
    window.aspectRatio = $(this).closest('.yk-slide').attr('data-aspect-ratio');
    if ($('.cropperSelectedImage').hasClass('cropper-hidden')) {
        window.$image.cropper('destroy');
    }
    if (window.actualImage == '') {
        $('.yk-bannerSlider-settings .js-cropperSelectImage').trigger('click');
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

$(document).on('change', '.yk-bannerSlider-settings input.js-cropperSelectImage', function(e) {
    if (e.target.files.length > 0) {
        selectImage(e.target.files[0]);
    }
});

$(document).on('click', '.yk-bannerSlider-settings .js-cropImage', async function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let tabId = thisObj.closest('#modal_cropper').find('input[name="tabId"]').val();
    let slideType = thisObj.closest('#modal_cropper').find('input[name="slideType"]').val();
    let slideId = thisObj.closest('.yk-bannerSlider-settings').find('.tab-pane[id=' + tabId + ']').attr('data-slide-id');
    let formData = new FormData();
    formData.append('cid', thisObj.closest('.yk-bannerSlider-settings').attr('data-comp'));
    formData.append('slide_id', slideId);
    formData.append('slide_type', slideType);
    formData.append('layout', 'bannerSlider');
    var cropped = '';
    if (slideType == 'mobile') {
        cropped = window.$image.cropper("getCroppedCanvas", { width: 800, height: 600, imageSmoothingEnabled: true, imageSmoothingQuality: 'high' });
    } else if (slideType == 'tablet') {
        cropped = window.$image.cropper("getCroppedCanvas", { width: 1200, height: 800, imageSmoothingEnabled: true, imageSmoothingQuality: 'high' });
    } else {
        cropped = window.$image.cropper("getCroppedCanvas", { width: 2000, height: 500, imageSmoothingEnabled: true, imageSmoothingQuality: 'high' });
    }  
    var mimeType = await getFileMimeType($(".cropperSelectedImage").attr('src'));
    cropped.toBlob((croppedBlob) => {
        formData.append('cropImage', croppedBlob);
        formData.append('actualImage', window.actualImage);
        $.ajax({
            url: adminBaseUrl + '/sliders/saveslideimage',
            type: 'POST',
            enctype: 'multipart/form-data',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (slideId == '') {
                    thisObj.closest('.yk-bannerSlider-settings').find('.tab-pane[id=' + tabId + ']').attr('data-slide-id', response.data.slide_id);
                }
                toastr.success(response.message);
                thisObj.closest('.yk-bannerSlider-settings').find('.tab-pane[id=' + tabId + '] .yk-slide[data-type=' + slideType + '] .actualImage').attr('src', response.data.originalUrl);
                thisObj.closest('.yk-bannerSlider-settings').find('.tab-pane[id=' + tabId + '] .yk-slide[data-type=' + slideType + '] .croppedImage').attr('src', response.data.url).closest('.YK-preview').show();
                thisObj.closest('#modal_cropper').modal('hide');
                $('#settingsModal').modal('show');
                window.$image.cropper('destroy');
                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            }
        });
    }, mimeType, 1);
});