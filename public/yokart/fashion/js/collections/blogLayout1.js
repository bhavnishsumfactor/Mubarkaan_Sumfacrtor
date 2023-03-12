/* blog layout 1 functions */
/*open setting popup*/
$('iframe').contents().find("body").on('click', '.yk-blogLayout1 .yk-blogposts > *', function(e) {
    var $link = $(e.target);
    e.preventDefault();
    if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
        setTimeout(function() {
            const component = editor.getSelected();
            let cid = component.getView().$el.find('.yk-container').attr('compid');
            component.getEl().setAttribute('compid', cid);
            editSettings(cid, 'blogLayout1');
        }, 100);
    }
    $link.data('lockedAt', +new Date());
});
/*embed data into view*/
$(document).on('click', '.yk-addBlogLayout1Widget', function(e) {
    let thisObj = $(this);
    thisObj.addClass('gb-is-loading').attr('disabled', 'disabled');
    let cid = thisObj.closest('.yk-blogLayout1-settings').attr('data-comp');
    let selectedCount = thisObj.closest('.yk-blogLayout1-settings').find('.yk-selectedBlogs li').length;
    if (selectedCount == 0 || (selectedCount >= 1 && selectedCount <= 3)) {
        $.ajax({
            url: adminBaseUrl + '/widgets/load',
            type: 'POST',
            data: { 'layout': 'blogLayout1', 'cid': cid },
            success: function(response) {
                let model = '';
                if (typeof editor.getSelected() != 'undefined') {
                    model = editor.getSelected();
                } else {
                    model = editor.DomComponents.getWrapper().find('section.yk-blogLayout1')[0];
                }
                var responseEl = $(response);
                responseEl.find('.yk-blogLayout1 .yk-container').attr('compid', cid);
                model.components(responseEl.html());
                resetComponentSettings(model, 'yk-blogLayout1');
                thisObj.removeClass('gb-is-loading').removeAttr('disabled');
                toastr.success(embedSuccessMessage);
            }
        });
        $('#settingsModal').modal('hide');
    } else {
        thisObj.removeClass('gb-is-loading').removeAttr('disabled');
        toastr.error(xBlogPostsCanBeAddedMessage);
    }
});
function embedBlogCollection1(){
    let thisObj = $('.yk-addBlogLayout1Widget');
    let cid = thisObj.closest('.yk-blogLayout1-settings').attr('data-comp');
    $.ajax({
        url: adminBaseUrl + '/widgets/load',
        type: 'POST',
        data: { 'layout': 'blogLayout1', cid: cid },
        success: function(response) {
            let model = '';
            if (typeof editor.getSelected() != 'undefined') {
                model = editor.getSelected();
            } else {
                model = editor.DomComponents.getWrapper().find('section.yk-blogLayout1')[0];
            }
            var responseEl = $(response);
            responseEl.find('.yk-blogLayout1 .yk-container').attr('compid', cid);
            model.components(responseEl.html());
            resetComponentSettings(model, 'yk-blogLayout1');
        }
    });   
}
/*autocomplete*/
$(document).on('keydown.autocomplete', '.yk-autocompleteblogLayout1', function() {
    let thisObj = $(this);
    let cid = thisObj.closest('.yk-blogLayout1-settings').attr('data-comp');
    thisObj.not('.ui-autocomplete-input').autocomplete({
        source: function(request, response) {
            $.ajax({
                method: "POST",
                url: adminBaseUrl + '/collection/search',
                dataType: "json",
                data: { term: request.term, layout: 'blogLayout1', cid: cid },
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
            let selectedBlogsCount = $(this).closest('.yk-blogLayout1-settings').find('.yk-selectedBlogs li').length;
            if (selectedBlogsCount == 3) {
                toastr.error(atmostXBlogPostsCanBeAddedMessage);
                ui.item.value = '';
                thisObj.val("");
                return;
            }
            var label = ui.item.label;
            var value = ui.item.value;
            let displayOrder = parseInt(thisObj.closest('.yk-blogLayout1-settings').find('.yk-selectedBlogs').attr('data-highest-order')) + 1;
            thisObj.closest('.yk-blogLayout1-settings').find('.yk-selectedBlogs').append(`<li class="list-group-item d-flex justify-content-between align-items-center" data-id="` + value + `" data-display-order="` + displayOrder + `">
<div class="d-flex  align-items-center">
    <i class="icon fa fa-arrows-alt handle mr-3"></i>  
    <span>` + label + `</span>
</div>
<div class="actions">
    <button type="button" class="btn btn-icon yk-removeBlogLayout1">
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
                data: { cid: cid, layout: 'blogLayout1', records: value, display_order: displayOrder },
                success: function(data) {

                    thisObj.closest('.yk-blogLayout1-settings').find('.yk-selectedBlogs').attr('data-highest-order', displayOrder);
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
$(document).on('click', '.yk-removeBlogLayout1', function(e) {
    let thisObj = $(this);
    let blogId = thisObj.closest('li').attr('data-id');
    let cid = thisObj.closest('.yk-blogLayout1-settings').attr('data-comp');
    $.ajax({
        method: "POST",
        url: adminBaseUrl + '/collection/delete',
        dataType: "json",
        data: { cid: cid, layout: 'blogLayout1', record: blogId },
        success: function(response) {
            thisObj.closest('li').remove();
            $('#settingsModal').find('.yk-sortable').html(response.data.html);
            $('#settingsModal').find('.yk-sortable').attr('data-highest-order', response.data.highestOrder);
            toastr.success(response.message);
            embedBlogCollection1();
        }
    });
});