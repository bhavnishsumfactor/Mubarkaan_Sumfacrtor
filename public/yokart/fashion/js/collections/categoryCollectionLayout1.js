/* category collection layout 1 functions */
/*open setting popup*/
$('iframe').contents().find("body").on('click', '.yk-categoryCollectionLayout1 .yk-categories > *', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'categoryCollectionLayout1');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
/*add new category tab*/
$(document).on('click', '.yk-addNewCategory', function(e) {
    let settingsObj = $(this).closest('.yk-categoryCollectionLayout1-settings');
    let cid = settingsObj.attr('data-comp');
    let tabCount = settingsObj.find('ul.nav li[data-id]').length;
    if (tabCount == 5) {
        return false;
    }
    let newTabNumber = getRandomNumber();
    while (settingsObj.find('ul.nav li[data-id="categorywidget-tab-' + newTabNumber + '"]').length != 0) {
        newTabNumber = getRandomNumber();
    }
    settingsObj.find('ul.nav li .nav-link').removeClass('active');
    settingsObj.find('div.tab-content div.tab-pane.fade').removeClass('active show');
    settingsObj.find('ul.nav li:last').before(`<li class="nav-item" data-id="categorywidget-tab-` + newTabNumber + `">
<a class="nav-link active" data-toggle="tab" href="#categorywidget-tab-` + newTabNumber + `"><span>Category ` + (parseInt(tabCount) + 1) + `</span><i class="fa fa-remove ml-2 yk-removeCategory"></i></a>
</li>`);
    settingsObj.find('div.tab-content').append(`<div class="tab-pane fade active show" id="categorywidget-tab-` + newTabNumber + `">
<div>
<div class="form-group yk-categoryInputGroup">
<div class="input-icon input-icon--left">
    <input type="text" class="form-control yk-autocompleteCategories" placeholder="Search category..." name="cat_name" autocomplete="off">        
    <span class="input-icon__icon input-icon__icon--left" ><span><i class="la la-search"></i></span></span>
</div>
</div>

<span class="yk-linkedCategory" style="display:none;">Category<i class="fa fa-remove yk-unlinkCategory"></i></span>
</div>
<div class="yk-linkedProducts" style="display:none;">
<div class="form-group">
<div class="input-icon input-icon--left">
    <input type="text" class="form-control yk-autocompleteProducts" placeholder="Search products..." name="prod_name" autocomplete="off">        
    <span class="input-icon__icon input-icon__icon--left" ><span><i class="la la-search"></i></span></span>
</div>
</div>

<ul class="list-group mt-4 yk-selectedProducts yk-sortable" data-highest-order="0">
</ul>
</div>
</div>`);
    refreshSortable(cid, 'categoryCollectionLayout1');
    if (tabCount == 4) {
        settingsObj.find('ul.nav li:last').remove();
    }
});
/*remove category tab*/
$(document).on('click', '.yk-removeCategory', function(e) {
    let settingsObj = $(this).closest('.yk-categoryCollectionLayout1-settings');
    let tabId = $(this).closest('li.nav-item').attr('data-id');
    let unlinkThis = $(this).closest('.yk-categoryCollectionLayout1-settings').find('#' + $(this).closest('li').attr('data-id') + ' .yk-unlinkCategory');
    unlinkCategoryInCategoryLayout1(unlinkThis);

    if ($(this).closest('a.nav-link').hasClass('active')) {
        let prevTab = $(this).closest('li.nav-item').prev('li.nav-item');
        let prevTabId = prevTab.attr('data-id');
        prevTab.find('a.nav-link').addClass('active');
        settingsObj.find('div.tab-content div#' + prevTabId).addClass('active show');
    }
    $(this).closest('li.nav-item').remove();
    settingsObj.find('div.tab-content div#' + tabId).remove();

    let i = 1;
    $.each(settingsObj.find('ul.nav li[data-id]'), function() {
        $(this).find('a span').text('Category ' + i);
        i++;
    });
    if (settingsObj.find('ul.nav li[data-id]').length == 4) {
        settingsObj.find('ul.nav').append(`<li class="nav-item" ><a  class="nav-link yk-addNewCategory" href="javascript:;"><i class="fa fa-plus ml-2"></i></a></li>`);
    }
});
/*autocomplete category*/
$(document).on('click', '.yk-categoryCollectionLayout1-settings input.yk-autocompleteCategories', function() {
    let thisObj = $(this);
    reloadComboTreeCategory($(this));
    let elementId = $(this).closest('.yk-categoryInputGroup').find('.comboTreeDropDownContainer').attr('id');
    /*if (!$('#' + elementId).hasClass('ps')) {
        new PerfectScrollbar('#' + elementId);
    } */
    thisObj.click();
});
window.comboTreeCategory = '';

reloadComboTreeCategory = function(thisObj) {
    window.comboTreeCategory = thisObj.not('.comboTreeInputBox').comboTree({
        source: categoriesData
    });
}
$(document).on('change', '.yk-categoryCollectionLayout1-settings input.yk-autocompleteCategories', function() {
    let thisObj = $(this);
    if (thisObj.val() != '') {
        if (Array.isArray(window.comboTreeCategory)) {
            let index = $(".tab-pane").index(thisObj.closest('.tab-pane'));
            categoriesDropdown = window.comboTreeCategory[index];
        } else {
            categoriesDropdown = window.comboTreeCategory;
        }
        if (categoriesDropdown.getSelectedIds() == null) {
            return;
        }
        let selectedCategoryId = categoriesDropdown.getSelectedIds()[0];
        let selectedCategoryName = categoriesDropdown.getSelectedNames();
        if (selectedCategoryId != '') {
            let cid = thisObj.closest('.yk-categoryCollectionLayout1-settings').attr('data-comp');
            $.ajax({
                method: "POST",
                url: adminBaseUrl + '/collection/checkifexists',
                dataType: "json",
                data: { cid: cid, layout: 'categoryCollectionLayout1', record: selectedCategoryId },
                success: function(data) {
                    thisObj.val(' ');
                    if (data.data.exists != true) {
                        thisObj.closest('.tab-pane').attr('data-cat-id', selectedCategoryId);
                        thisObj.closest('.form-group').hide(); //hide category autosearch input field
                        thisObj.closest('.form-group').next().html(`Category linked: ` + selectedCategoryName + ` <i class="fa fa-remove yk-unlinkCategory"></i>`);
                        thisObj.closest('.form-group').next().show(); //show selected category name
                        thisObj.closest('.tab-pane').find('.yk-linkedProducts').find('.yk-selectedProducts').attr('data-highest-order', 0);
                        thisObj.closest('.tab-pane').find('.yk-linkedProducts').show();
                    } else {
                        toastr.error(sameCategoryCannotBeAddedAgainMessage);
                    }
                }
            });     
        } else {
            thisObj.val('');
        }
    }
});
/*autocomplete products*/
$(document).on('keydown.autocomplete', 'input.yk-autocompleteProducts', function() {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-categoryCollectionLayout1-settings').attr('data-comp');
    $(this).not('.ui-autocomplete-input').autocomplete({
        source: function(request, response) {
            $.ajax({
                method: "POST",
                url: adminBaseUrl + '/collection/search',
                dataType: "json",
                data: { term: request.term, layout: 'categoryCollectionLayout1', cid: cid, categoryid: thisObj.closest('.tab-pane').attr('data-cat-id') },
                success: function(data) {
                    response(data);
                    if (!$('ul.ui-autocomplete').hasClass('ps')) {
                        new PerfectScrollbar('ul.ui-autocomplete');
                    }
                }
            });
        },
        minLength: 1,
        appendTo: thisObj.parent(),
        select: function(event, ui) {
            let selectedCount = thisObj.closest('.yk-linkedProducts').find('.yk-selectedProducts li').length;
            if (selectedCount == 4) {
                toastr.error(atmostXProductsCanBeAddedPerCategoryMessage);
                ui.item.value = '';
                thisObj.val("");
                return;
            }
            var label = ui.item.label;
            var value = ui.item.value;
            let displayOrder = parseInt(thisObj.closest('.yk-linkedProducts').find('.yk-selectedProducts').attr('data-highest-order')) + 1;
            thisObj.closest('.yk-linkedProducts').find('.yk-selectedProducts').append(`
<li class="list-group-item d-flex justify-content-between align-items-center" data-id="` + value + `" data-display-order="` + displayOrder + `">
<div class="d-flex  align-items-center">
<i class="icon fa fa-arrows-alt handle mr-3"></i>  
<span>` + label + `</span>
</div>
<div class="actions">
    <button type="button" class="btn btn-icon yk-removeCategoryProduct">
        <svg>   
            <use xlink:href="` + adminBaseUrl + `/images/retina/sprite.svg#delete-icon" ></use>                               
        </svg>
    </button>
</div>
</li>`);
            ui.item.value = '';
            thisObj.val("");
            $.ajax({
                method: "POST",
                url: adminBaseUrl + '/collection/save',
                dataType: "json",
                data: { cid: cid, layout: 'categoryCollectionLayout1', records: value, categoryid: thisObj.closest('.tab-pane').attr('data-cat-id'), display_order: displayOrder },
                success: function(data) {
                    thisObj.closest('.yk-linkedProducts').find('.yk-selectedProducts').attr('data-highest-order', displayOrder);
                }
            });
        },
        focus: function(event, ui) {
            thisObj.val(ui.item.label);
            return false;
        }
    });
});
/*remove category product linking*/
$(document).on('click', '.yk-removeCategoryProduct', function(e) {
    let parentObj = $(this).closest('li');
    let listingObj = $(this).closest('.yk-sortable');
    let productId = parentObj.attr('data-id');
    let cid = $(this).closest('.yk-categoryCollectionLayout1-settings').attr('data-comp');
    let categoryid = $(this).closest('.tab-pane').attr('data-cat-id');
    $.ajax({
        method: "POST",
        url: adminBaseUrl + '/collection/delete',
        dataType: "json",
        data: { cid: cid, layout: 'categoryCollectionLayout1', record: productId, categoryid: categoryid },
        success: function(response) {
            parentObj.remove();
            listingObj.html(response.data.html);
            listingObj.attr('data-highest-order', response.data.highestOrder);
            toastr.success(response.message);
            embedCategoryCollection1();
        }
    });
});
/*remove category linking*/
$(document).on('click', '.yk-unlinkCategory', function(e) {
    unlinkCategoryInCategoryLayout1($(this));
});
/*embed data into view*/
$(document).on('click', '.yk-addcategoryCollectionLayout1Widget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-categoryCollectionLayout1-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'categoryCollectionLayout1', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('section.yk-categoryCollectionLayout1')[0];
            }
            var responseEl = $(response.data.html);
            responseEl.find('.yk-categoryCollectionLayout1 .yk-container').attr('compid', cid);
            let innerHtml = model.getView().$el;
            $(innerHtml).find('ul.yk-categories').html(response.data.tabbingHtml);
            $(innerHtml).find('categoryCollectionLayout1').html(responseEl.html());
            model.components($(innerHtml).html());
            resetComponentSettings(model, 'yk-categoryCollectionLayout1');
            thisObj.removeClass('gb-is-loading').removeAttr('disabled');
            toastr.success(embedSuccessMessage);
        }
    });
    $('#settingsModal').modal('hide');
});

function unlinkCategoryInCategoryLayout1(thisObj) {
    let cid = thisObj.closest('.yk-categoryCollectionLayout1-settings').attr('data-comp');
    let categoryid = thisObj.closest('.tab-pane').attr('data-cat-id');
    $.ajax({
        method: "POST",
        url: adminBaseUrl + '/collection/delete',
        dataType: "json",
        data: { cid: cid, layout: 'categoryCollectionLayout1', categoryid: categoryid },
        success: function(data) {
            thisObj.closest('.yk-linkedCategory').hide();
            thisObj.closest('.tab-pane').find('.yk-categoryInputGroup').show();
            thisObj.closest('.tab-pane').find('.yk-linkedProducts').hide();
            thisObj.closest('.tab-pane').find('ul.yk-selectedProducts').html('');
            thisObj.closest('.tab-pane').removeAttr('data-cat-id');
        }
    });
};

function embedCategoryCollection1(){
    let thisObj = $('.yk-addcategoryCollectionLayout1Widget');
    let cid = thisObj.closest('.yk-categoryCollectionLayout1-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'categoryCollectionLayout1', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('section.yk-categoryCollectionLayout1')[0];
            }
            var responseEl = $(response.data.html);
            responseEl.find('.yk-categoryCollectionLayout1 .yk-container').attr('compid', cid);
            let innerHtml = model.getView().$el;
            $(innerHtml).find('ul.yk-categories').html(response.data.tabbingHtml);
            $(innerHtml).find('categoryCollectionLayout1').html(responseEl.html());
            model.components($(innerHtml).html());
            resetComponentSettings(model, 'yk-categoryCollectionLayout1');
        }
    });   
}