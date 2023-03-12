/* product collection layout 1 functions */
$('iframe').contents().find("body").on('click', '.yk-productCollectionLayout1 .yk-products > *', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'productCollectionLayout1');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
/*embed data into view*/
$(document).on('click', '.yk-addProductCollectionLayout1Widget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-productCollectionLayout1-settings').attr('data-comp');
    let selectedProductsCount = thisObj.closest('.yk-productCollectionLayout1-settings').find('.yk-selectedProductCollectionLayout1 li').length;
    if (selectedProductsCount == 0 || selectedProductsCount >= 3) {
        $.ajax({
            url: adminBaseUrl + '/widgets/load',
            type: 'POST',
            data: { 'layout': 'productCollectionLayout1', cid: cid },
            success: function(response) {
                let model = '';
                if (typeof editor.getSelected() != 'undefined') {
                    model = editor.getSelected();
                } else {
                    model = editor.DomComponents.getWrapper().find('productcollectionlayout1')[0];
                }
                var responseEl = $(response);
                responseEl.find('.yk-productCollectionLayout1 .yk-container').attr('compid', cid);
                let innerHtml = model.getView().$el;
                $(innerHtml).find('productcollectionlayout1').html(responseEl.html());
                model.components($(innerHtml).html());
                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
                resetComponentSettings(model, 'yk-productCollectionLayout1');
                toastr.success(embedSuccessMessage);
            }
        });
        $('#settingsModal').modal('hide');
    } else {
        thisObj.removeClass('gb-is-loading').removeAttr('disabled');
        toastr.error(atleastXProductsMustBeSelectedMessage);
    }
});

function embedProductCollection1(){
    let thisObj = $('.yk-addProductCollectionLayout1Widget');
    let cid = thisObj.closest('.yk-productCollectionLayout1-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'productCollectionLayout1', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('productcollectionlayout1')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-productCollectionLayout1 .yk-container').attr('compid', cid);
            let innerHtml = model.getView().$el;
            $(innerHtml).find('productcollectionlayout1').html(responseEl.html());
            model.components($(innerHtml).html());
            resetComponentSettings(model, 'yk-productCollectionLayout1');
        }
    });   
}

/*autocomplete*/
$(document).on('keydown.autocomplete', '.yk-autocompleteProductCollectionLayout1', function() {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-productCollectionLayout1-settings').attr('data-comp');
    $(this).not('.ui-autocomplete-input').autocomplete({
        source: function(request, response) {
            $.ajax({
                method: "POST",
                url: adminBaseUrl + '/collection/search',
                dataType: "json",
                data: { term: request.term, layout: 'productCollectionLayout1', cid: cid },
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
            var label = ui.item.label;
            var value = ui.item.value;
            let displayOrder = parseInt(thisObj.closest('.yk-productCollectionLayout1-settings').find('.yk-selectedProductCollectionLayout1').attr('data-highest-order')) + 1;
            thisObj.closest('.yk-productCollectionLayout1-settings').find('.yk-selectedProductCollectionLayout1').append(`<li class="list-group-item d-flex justify-content-between align-items-center" data-id="` + value + `" data-display-order="` + displayOrder + `">
<div class="d-flex  align-items-center">
<i class="icon fa fa-arrows-alt handle mr-3"></i>  
<span>` + label + `</span>
</div>
<div class="actions">
    <button type="button" class="btn btn-icon yk-removeProductCollectionLayout1">
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
                data: { cid: cid, layout: 'productCollectionLayout1', records: value, display_order: displayOrder },
                success: function(data) {
                    thisObj.closest('.yk-productCollectionLayout1-settings').find('.yk-selectedProductCollectionLayout1').attr('data-highest-order', displayOrder);
                }
            });
        },
        focus: function(event, ui) {
            thisObj.val(ui.item.label);
            return false;
        }
    });
});
/*remove collection option*/
$(document).on('click', '.yk-removeProductCollectionLayout1', function(e) {
    let thisObj = $(this);
    let productId = thisObj.closest('li').attr('data-id');
    let cid = thisObj.closest('.yk-productCollectionLayout1-settings').attr('data-comp');
    $.ajax({
        method: "POST",
        url: adminBaseUrl + '/collection/delete',
        dataType: "json",
        data: { cid: cid, layout: 'productCollectionLayout1', record: productId },
        success: function(response) {
            thisObj.closest('li').remove();
            $('#settingsModal').find('.yk-sortable').html(response.data.html);
            $('#settingsModal').find('.yk-sortable').attr('data-highest-order', response.data.highestOrder);
            toastr.success(response.message);
            embedProductCollection1();
        }
    });
});