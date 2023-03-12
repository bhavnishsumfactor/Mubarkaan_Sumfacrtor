/* navigation layout 1 functions */
/*open setting popup*/
$('iframe').contents().find("body").on('click', '.yk-header > *', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'navigationLayout1');

            setTimeout(() => {
                reloadComboTreeSelect();
                $('.yk-navigationLayout1-settings').find('.yk-menuItems .card:last .yk-cardHeading').trigger('click');
            }, 500);
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});

/*embed data into view*/
$(document).on('click', '.yk-addNavigationLayout1Widget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-navigationLayout1-settings').attr('data-comp');

    thisObj.closest('.yk-navigationLayout1-settings .collapse.show .yk-cardBody').trigger("click");

    setTimeout(() => {
        var fields = $("#yk-formNavbar").find('input.required, select.required');
        for (var i = 0; i < fields.length; i++) {
            if ($(fields[i]).is(":visible") && ($(fields[i]).val() == '' || $(fields[i]).val() == null)) {
                toastr.error(pleaseFillAllFieldsMessage);
                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
                return;
            }
        }
        $.ajax({
            url: adminBaseUrl + '/widgets/load',
            type: 'POST',
            data: { 'layout': 'navigationLayout1', cid: cid },
            success: function(response) {
                let model = '';
                if (typeof editor.getSelected() != 'undefined') {
                    model = editor.getSelected();
                } else {
                    model = editor.DomComponents.getWrapper().find('.yk-header')[0];
                }
                var responseEl = $(response);
                responseEl.find('.yk-header .yk-container').attr('compid', cid);
                model.components(responseEl.html());
                resetComponentSettings(model, 'yk-header');
                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
                toastr.success(embedSuccessMessage);
            }
        });
        $('#settingsModal').modal('hide');
    }, 500);
});

/*save welcome text*/
$(document).on('blur', '.yk-navigationLayout1-settings input[name="welcome_text"]', function(e) {
    let cid = $(this).closest('.yk-navigationLayout1-settings').attr('data-comp');
    let welcome_text = $(this).val();
    $.ajax({
        url: adminBaseUrl + '/collection/save',
        type: 'POST',
        data: { cid: cid, 'layout': 'navigationLayout1', welcome_text: welcome_text },
        success: function(response) {}
    });
});

/*save menu*/
$(document).on('blur', '.yk-navigationLayout1-settings .yk-cardBody select, .yk-navigationLayout1-settings .yk-cardBody input', function(e) {
    let thisObj = $(this);
    setTimeout(() => {
        let cid = thisObj.closest('.yk-navigationLayout1-settings').attr('data-comp');
        let navmenu_id = thisObj.closest('.card').attr('data-id');
        let type = thisObj.closest('.card').find('select[name="type"] option:selected').val();
        let category_id = page_id = '';
        let index = $(this).closest('.card').prevAll().length;
        let categoriesDropdown = pagesDropdown = '';
        if (Array.isArray(window.comboTreePages)) {
            pagesDropdown = window.comboTreePages[index];
        } else {
            pagesDropdown = window.comboTreePages;
        }
        if (Array.isArray(window.comboTreeCategories)) {
            categoriesDropdown = window.comboTreeCategories[index];
        } else {
            categoriesDropdown = window.comboTreeCategories;
        }
        if (type == '1' && typeof categoriesDropdown != 'undefined' && categoriesDropdown.getSelectedIds() != null) {
            category_id = categoriesDropdown.getSelectedIds()[0];
        } else if (type == '2' && typeof pagesDropdown != 'undefined' && pagesDropdown.getSelectedIds() != null) {
            page_id = pagesDropdown.getSelectedIds()[0];
        }
        let label = thisObj.closest('.card').find('input[name="label"]').val();
        let url = thisObj.closest('.card').find('input[name="url"]').val();
        let target = thisObj.closest('.card').find('select[name="target"] option:selected').val();
        if (type == '' || label == '') {
            // toastr.error('Please fill all fields.');
            return;
        }
        $.ajax({
            url: adminBaseUrl + '/navigations/save',
            type: 'POST',
            data: { cid: cid, layout: 'navigationLayout1', navmenu_id: navmenu_id, type: type, category_id: category_id, page_id: page_id, label: label, url: url, target: target },
            success: function(response) {
                if (navmenu_id == '') {
                    thisObj.closest('.card').attr('data-id', response.data.navmenu_id);
                }
            }
        });
    }, 200);
});
/*update card heading with label field changes*/
$(document).on('keyup', '.yk-navigationLayout1-settings input[name="label"]', function(e) {
    let thisObj = $(this);
    thisObj.closest('.card').find('.yk-menuHeading').text(thisObj.val());
});
/*add new menu */
$(document).on('click', '.yk-addMenu', function(e) {
    e.preventDefault();
    e.stopPropagation();
    let thisObj = $(this);
    let newMenuNumber = getRandomNumber();
    while (thisObj.closest('.yk-navigationLayout1-settings').find('.card-header[id="heading-' + newMenuNumber + '"]').length != 0) {
        newMenuNumber = getRandomNumber();
    }
    let displayOrder = parseInt(thisObj.closest('.yk-navigationLayout1-settings').find('.yk-menuItems').attr('data-highest-order')) + 1;

    thisObj.closest('.yk-navigationLayout1-settings').find('.yk-menuItems .card .collapse.show').collapse('hide');
    $.ajax({
        url: adminBaseUrl + '/collection/settings',
        type: 'POST',
        data: { 'layout': 'navigationLayout1', 'params': newMenuNumber, displayOrder: displayOrder },
        success: function(response) {
            $(response).find('select[name="type"]').prop('selectedIndex', 0);
            $(response).find('.yk-categoriesList').hide();
            $(response).find('.yk-pagesList').hide();
            $(response).find('.yk-menuUrl').hide();
            thisObj.closest('.yk-navigationLayout1-settings').find('.yk-menuItems').append($(response)[0]);
            thisObj.closest('.yk-navigationLayout1-settings').find('.yk-menuItems').attr('data-highest-order', displayOrder);
            setTimeout(() => {
                thisObj.closest('.yk-navigationLayout1-settings').find('.yk-menuItems .card:last .collapse').collapse('show');
            }, 500);
            reloadComboTreeSelect();
        }
    });
});
/*on selecting type*/
$(document).on('change', '.yk-navigationLayout1-settings select[name="type"]', function() {
    let type = $(this).val();
    let cardBody = $(this).closest('.yk-cardBody');
    cardBody.find('select[name="category_id"], select[name="page_id"], select[name="target"]').prop('selectedIndex', 0);
    cardBody.find('input[name="label"], input[name="url"]').val('');
    switch (type) {
        case '1':
            cardBody.find('.yk-categoriesList').show();
            cardBody.find('.yk-pagesList').hide();
            cardBody.find('.yk-menuUrl').hide();
            break;
        case '2':
            cardBody.find('.yk-categoriesList').hide();
            cardBody.find('.yk-pagesList').show();
            cardBody.find('.yk-menuUrl').hide();
            break;
        case '3':
            cardBody.find('.yk-categoriesList').hide();
            cardBody.find('.yk-pagesList').hide();
            cardBody.find('.yk-menuUrl').show();
            break;
        default:
    }
});
/*on selecting category*/
$(document).on('click', '.yk-navigationLayout1-settings .yk-cardHeading.collapsed', function(e) {
    e.preventDefault();
    e.stopPropagation();
    let thisObj = $(this);
    let type = thisObj.parent().find('select[name="type"]').val();
    let selected_category_id = thisObj.parent().find('input[name="selected_category_id"]').attr('val');
    let selected_page_id = thisObj.parent().find('input[name="selected_page_id"]').attr('val');
    let index = $(this).closest('.card').prevAll().length;
    let categoriesDropdown = pagesDropdown = '';
    if (Array.isArray(window.comboTreePages)) {
        pagesDropdown = window.comboTreePages[index];
    } else {
        pagesDropdown = window.comboTreePages;
    }
    if (Array.isArray(window.comboTreeCategories)) {
        categoriesDropdown = window.comboTreeCategories[index];
    } else {
        categoriesDropdown = window.comboTreeCategories;
    }
    if (type == '1' && selected_category_id != '') {
        categoriesDropdown.setSelection([selected_category_id]);
    } else if (type == '2' && selected_page_id != '') {
        pagesDropdown.setSelection([selected_page_id]);
    }
});
/*remove category tab*/
$(document).on('click', '.yk-removeMenu', function(e) {
    e.preventDefault();
    e.stopPropagation();
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-navigationLayout1-settings').attr('data-comp');
    let navmenu_id = thisObj.closest('.card').attr('data-id');
    if (navmenu_id == '') {
        thisObj.closest('.card').remove();
        return;
    }

    $.ajax({
        url: adminBaseUrl + '/navigations/delete',
        type: 'POST',
        data: { navmenu_id: navmenu_id, cid: cid, layout: 'navigationLayout1' },
        success: function(response) {
            thisObj.closest('.card').remove();
            $('#settingsModal').find('.yk-sortable').html(response.data.html);
            $('#settingsModal').find('.yk-sortable').attr('data-highest-order', response.data.highestOrder);
            toastr.success(response.message);
        }
    });
});