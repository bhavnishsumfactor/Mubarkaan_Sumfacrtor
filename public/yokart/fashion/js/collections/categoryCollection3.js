//category collection layout 3 functions
//open setting popup
$('iframe').contents().find("body").on('click', '.yk-categoryCollection3 .yk-categories > *', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'categoryCollection3');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
//embed data into view
$(document).on('click', '.yk-addCategoryCollection3Widget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-categoryCollection3-settings').attr('data-comp');
    let selectedCount = thisObj.closest('.yk-categoryCollection3-settings').find('.yk-selectedCategories li.yk-category').length;
    if (selectedCount == 0 || selectedCount == 6) {
        $.ajax({
            url: adminBaseUrl + '/widgets/load',
            type: 'POST',
            data: { 'layout': 'categoryCollection3', cid: cid },
            success: function(response) {
                let model = '';
                if (typeof editor.getSelected() != 'undefined') {
                    model = editor.getSelected();
                } else {
                    model = editor.DomComponents.getWrapper().find('section.yk-categoryCollection3')[0];
                }
                var responseEl = $(response);
                responseEl.find('.yk-categoryCollection3 .yk-container').attr('compid', cid);
                model.components(responseEl.html());
                resetComponentSettings(model, 'yk-categoryCollection3');
                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
                toastr.success(embedSuccessMessage);
            }
        });
        $('#settingsModal').modal('hide');
    } else {
        thisObj.removeClass('gb-is-loading').removeAttr('disabled');
        toastr.error(exactly6CategoriesRequiredMessage);
    }
});
function embedCategoryCollection3(){
    let thisObj = $('.yk-addCategoryCollection3Widget');
    let cid = thisObj.closest('.yk-categoryCollection3-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'categoryCollection3', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('section.yk-categoryCollection3')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-categoryCollection3 .yk-container').attr('compid', cid);
            model.components(responseEl.html());
            resetComponentSettings(model, 'yk-categoryCollection3');
        }
    });   
}
//autocomplete
$(document).on('click', '.yk-categoryCollection3-settings .yk-autocompleteCategories', function() {
    let thisObj = $(this);
    reloadComboTreeCategory(thisObj);
    let elementId = thisObj.closest('.form-group').find('.comboTreeDropDownContainer').attr('id');
    /*if (!$('#' + elementId).hasClass('ps')) {
        new PerfectScrollbar('#' + elementId);
    } */
    thisObj.click();
});
$(document).on('change', '.yk-categoryCollection3-settings input.yk-autocompleteCategories', function() {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-categoryCollection3-settings').attr('data-comp');
    if (thisObj.val() != '') {
        let selectedCount = thisObj.closest('.yk-categoryCollection3-settings').find('.yk-selectedCategories li.yk-category').length;
        if (selectedCount >= 6) {
            thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            toastr.error(exactly6CategoriesRequiredMessage);
            thisObj.val('');
            return;
        }
        if (Array.isArray(window.comboTreeCategory)) {
            categoriesDropdown = window.comboTreeCategory[0];
        } else {
            categoriesDropdown = window.comboTreeCategory;
        }
        let selectedCategoryId = categoriesDropdown.getSelectedIds()[0];
        let selectedCategoryName = categoriesDropdown.getSelectedNames();
        let alreadyExists = 0;
        $.each(thisObj.closest('.yk-categoryCollection3-settings').find('.yk-selectedCategories li[data-id]'), function() {
            if ($(this).attr('data-id') == selectedCategoryId) {
                alreadyExists = 1;
            }
        });
        if (alreadyExists) {
            toastr.error(categoryAlreadyAddedMessage);
            return;
        }

        let displayOrder = parseInt(thisObj.closest('.yk-categoryCollection3-settings').find('.yk-selectedCategories').attr('data-highest-order')) + 1;
        $.ajax({
            method: "POST",
            url: adminBaseUrl + '/collection/save',
            dataType: "json",
            data: { cid: cid, layout: 'categoryCollection3', records: selectedCategoryId, display_order: displayOrder },
            success: function(response) {
                if (response.status == false) {
                    toastr.error(response.message);
                } else {
                    thisObj.closest('.yk-categoryCollection3-settings').find('.yk-selectedCategories').append(`
    <li class="list-group-item yk-slide yk-category" data-id="` + selectedCategoryId + `" data-display-order="` + displayOrder + `">
        <div class="row align-items-center">
        <div class="col-auto"><i class="icon fa fa-arrows-alt handle"></i> </div>
        <div class="col">
            <h5>` + selectedCategoryName + `</h5>
            <img class="actualImage" src="" style="display:none;">
            <div class="file-input">
                <button type="button" class="btn btn-secondary btn-wide js-openCropper">Upload a file</button> 
            </div>   
            <p class="img-disclaimer editor"><strong>Image Disclaimer: </strong> File type must be a .jpg, .gif or .png smaller than 10MB and at least 250x250 in 1:1 aspect ratio.</p>
        </div>
        <div class="col-auto YK-preview" style="display:none;">
            <ul class="list-media">
                <li class="media"><img class="croppedImage" src="">
                <div class="media-actions"><button type="button" class="btn btn-icon btn-sm js-removeBackgroundImage"><i class="fas fa-times"></i></button></div>
                </li>
            </ul>
        </div>
        <div class="actions">
            <button type="button" class="btn btn-icon yk-removeCategoryCollection3">
                <svg>   
                    <use xlink:href="` + adminBaseUrl + `/images/retina/sprite.svg#delete-icon" ></use>                               
                </svg>
            </button>
        </div>
        </div>
    </li>`);
                    thisObj.closest('.yk-categoryCollection3-settings').find('.yk-selectedCategories').attr('data-highest-order', displayOrder);
                }
            }
        });
        thisObj.val('');
    }
});
//remove collection option
$(document).on('click', '.yk-removeCategoryCollection3', function(e) {
    let thisObj = $(this);
    let categoryId = thisObj.closest('.yk-category').attr('data-id');
    let cid = thisObj.closest('.yk-categoryCollection3-settings').attr('data-comp');
    $.ajax({
        method: "POST",
        url: adminBaseUrl + '/collection/delete',
        dataType: "json",
        data: { cid: cid, layout: 'categoryCollection3', record: categoryId },
        success: function(response) {
            thisObj.closest('li').remove();
            $('#settingsModal').find('.yk-sortable').html(response.data.html);
            $('#settingsModal').find('.yk-sortable').attr('data-highest-order', response.data.highestOrder);
            toastr.success(response.message);
            embedCategoryCollection3();
        }
    });
});
/*remove background image*/
$(document).on('click', '.yk-categoryCollection3-settings .js-removeBackgroundImage', function(e) {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-categoryCollection3-settings').attr('data-comp');
    let recordId = thisObj.closest('.yk-slide').attr('data-id');
    $.ajax({
        url: adminBaseUrl + '/collection/deleteimage',
        type: 'POST',
        data: { cid: cid, 'layout': 'categoryCollection3', record_id: recordId },
        success: function(response) {
            toastr.success(response.message);
            thisObj.closest('.yk-slide').find('img.actualImage').attr('src', '');
            thisObj.closest('.YK-preview').find('img').attr('src', '');
            thisObj.closest('.YK-preview').css('display', 'none');
        }
    });
});

//open cropper modal
$(document).on('click', '.yk-categoryCollection3-settings .js-openCropper', function(e) {
    window.tmppath = '';
    window.actualImage = '';
    window.actualImage = $(this).closest('.yk-slide').find('img.actualImage').attr('src');
    $(this).closest('.yk-categoryCollection3-settings').find('#modal_cropper input[type="file"]').val('');
    $(this).closest('.yk-categoryCollection3-settings').find('#modal_cropper input[name="tabId"]').val($(this).closest('.yk-category').attr('data-id'));
    window.aspectRatio = 1;
    if ($('.cropperSelectedImage').hasClass('cropper-hidden')) {
        window.$image.cropper('destroy');
    }
    if (window.actualImage == '') {
        $('.yk-categoryCollection3-settings .js-cropperSelectImage').trigger('click');
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
$(document).on('change', '.yk-categoryCollection3-settings input.js-cropperSelectImage', function(e) {
    if (e.target.files.length > 0) {
        selectImage(e.target.files[0]);
    }
});
//crop image
$(document).on('click', '.yk-categoryCollection3-settings .js-cropImage', async function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let formData = new FormData();
    formData.append('cid', thisObj.closest('.yk-categoryCollection3-settings').attr('data-comp'));
    let tabId = thisObj.closest('#modal_cropper').find('input[name="tabId"]').val();
    formData.append('layout', 'categoryCollection3');
    formData.append('records', tabId);
    var cropped = window.$image.cropper("getCroppedCanvas", { width: 250, height: 250 });  
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
                thisObj.closest('.yk-categoryCollection3-settings').find('.yk-category[data-id="' + tabId + '"] .actualImage').attr('src', response.data.originalUrl);
                thisObj.closest('.yk-categoryCollection3-settings').find('.yk-category[data-id="' + tabId + '"] .croppedImage').attr('src', response.data.url).closest('.YK-preview').show();
                thisObj.closest('#modal_cropper').modal('hide');
                $('#settingsModal').modal('show');
                window.$image.cropper('destroy');
                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            }
        });
    }, mimeType, 1);
});