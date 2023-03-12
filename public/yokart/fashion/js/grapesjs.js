(function() {
    var modal = editor.Modal;
    var cmdm = editor.Commands;
    const rte = editor.RichTextEditor;

    const btnState = {
        DISABLED: -1,
        ACTIVE: 1,
        INACTIVE: 0
    };

    const isValidTag = (rte, tagName) => {
        const anchor = rte.selection().anchorNode;
        const nextSibling = anchor && anchor.nextSibling;
        return (nextSibling && nextSibling.nodeName == tagName)
    }

    rte.remove('link');
    rte.remove('bold');
    rte.remove('italic');
    rte.remove('underline');
    rte.remove('strikethrough');
    rte.add('bold', {
        icon: '<strong>B</strong>',
        attributes: { title: 'Bold' },
        state: (rte, doc) => {
            if (rte && rte.selection()) {
              return isValidTag(rte, 'STRONG') ? btnState.ACTIVE : btnState.INACTIVE;
            } else {
              return btnState.INACTIVE;
            }
        },
        result: rte => rte.insertHTML(`<strong>${rte.selection()}</strong>`)
    });
    rte.add('italic', {
        icon: '<em>I</em>',
        attributes: { title: 'Italic' },
        state: (rte, doc) => {
            if (rte && rte.selection()) {
              return isValidTag(rte, 'EM') ? btnState.ACTIVE : btnState.INACTIVE;
            } else {
              return btnState.INACTIVE;
            }
        },
        result: rte => rte.insertHTML(`<em>${rte.selection()}</em>`)
    });
    rte.add('underline', {
        icon: '<u>U</u>',
        attributes: { title: 'Underline' },
        state: (rte, doc) => {
            if (rte && rte.selection()) {
              return isValidTag(rte, 'U') ? btnState.ACTIVE : btnState.INACTIVE;
            } else {
              return btnState.INACTIVE;
            }
        },
        result: rte => rte.insertHTML(`<u>${rte.selection()}</u>`)
    });
    rte.add('strikethrough', {
        icon: '<del>S</del>',
        attributes: { title: 'Strikethrough' },
        state: (rte, doc) => {
            if (rte && rte.selection()) {
              return isValidTag(rte, 'DEL') ? btnState.ACTIVE : btnState.INACTIVE;
            } else {
              return btnState.INACTIVE;
            }
        },
        result: rte => rte.insertHTML(`<del>${rte.selection()}</del>`)
    });    
    var anchorCls = "";
    rte.add('link', {
        icon: '<i class="fa fa-link"/>',
        attributes: { title: 'Link' },
        event: 'click',
        result: function(rte, action) {
            anchorCls = '';
            if(rte.el.nodeName != "A"){
                anchorCls = "yk-" + new Date().valueOf();
                rte.insertHTML(`<a href="#" data-gjs-type="link" class="yk-anchor ${anchorCls}">${rte.selection()}</a>`);
                editor.select(null);
            }else{
                openAnchorSettings();
            }
        }
    });
    
    openAnchorSettings = function (thisEvent = null){
        var sameTabSelected = newTabSelected = target = href = '';
        if(thisEvent == null){
            target = editor.getSelected().attributes.attributes.target;
            href = editor.getSelected().attributes.attributes.href;
        } else {
            target = $(thisEvent.target).attr('target');
            href = $(thisEvent.target).attr('href');
        }
        
        if (target == '_blank') {
            newTabSelected = 'selected';
        } else if (target == '_self') {
            sameTabSelected = 'selected';
        }
        anchorEvent = thisEvent;

        var linkContainer = `<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Insert/Update Link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="form-row align-items-center">
                    <div class="col-md-6 mb-3">
                    <label for="validationCustom01">URL</label>
                    <input type="text" class="form-control" name="url" placeholder="enter link" value="${href}" id="linkInputField" required>
                    </div>
                    <div class="col-md-6 mb-3">
                    <label class="my-1 mr-2">Target</label>
                    <select class="custom-select my-1 mr-sm-2" name="target">
                        <option value='' readonly disabled selected>Choose...</option>
                        <option value="_self" ${sameTabSelected}>Same Tab</option>
                        <option value="_blank" ${newTabSelected}>New Tab</option>
                    </select>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary gb-btn gb-btn-primary" onclick="saveAnchorSettings()">Save</button>
                </div>
                </div>
                </div>
                </div>
            </div>`;
        $('.modalsContent').html('').html(linkContainer);
        $('#settingsModal').modal('show');
    }

    saveAnchorSettings = function (){
            $(this).addClass('gb-is-loading').attr('disabled', 'disabled');
            var thisObj = '';
            if(anchorCls != ''){
                thisObj = $('iframe').contents().find(`a.${anchorCls}`);
                editor.select(editor.DomComponents.getWrapper().find(`a.${anchorCls}`)[0]);
            } else {
                thisObj = $('iframe').contents().find("a.gjs-selected");
            }
            var linkVal = $('#linkInputField').val();
            var targetVal = $('select[name="target"] option:selected').val();
            thisObj.attr('target', targetVal);
            thisObj.attr('href', linkVal);
            editor.getSelected().attributes.attributes.href = linkVal;
            editor.getSelected().attributes.attributes.target = targetVal;
            $('#settingsModal').modal('hide');
            setTimeout(() => {
                $(this).removeClass('gb-is-loading').removeAttr('disabled');
            }, 1000);
        }

    const blocks = bm.getAll();
    blocks.map(block => {
        if (themeWidgets.indexOf(block.attributes.id) != -1) {
            block.attributes.attributes.style = 'order: ' + (parseInt(themeWidgets.indexOf(block.id)) + 1);
            block.attributes.category = {
                label: "Theme elements ",
                order: 1,
                open: true
            }
        } else if (generalWidgets.indexOf(block.attributes.id) != -1) {
            block.attributes.attributes.style = 'order: ' + (parseInt(generalWidgets.indexOf(block.id)) + 1);
            block.attributes.category = {
                label: "General ",
                order: 2,
                open: false
            }
        }
    });

    /* update top panel "options" buttons */
    pn.addButton //add save button to save the current page template to db
        ('options', [{
            id: 'save-db',
            label: getSvgIcon('save-db', '20'),
            command: function(editor, sender) {
                loader();
                sender && sender.set('active'); // turn off the button
                saveTemplate(editor);
            },
            attributes: { title: 'Save Template', 'data-tooltip-pos': 'bottom', style: 'order: 5' }
        }]);

    saveTemplate = async function(editor, callback) {
        var sections = { header: '', body: '', footer: '' };
        var store = {
            components: {...sections},
            html: {...sections},
            css: editor.getCss()
            // styles: editor.getStyle()
        }
        
        editor.getComponents().forEach(component => {
            if (component.ccid == 'header') {
                store.components.header = JSON.stringify(component);
                store.html.header = component.view.el.outerHTML;
            } else if (component.ccid == 'body') {
                // component.find('img').map(comp => { 
                //     if (comp.getAttributes()['src'].includes("https://demo.tribe.yo-kart.com/")) {
                //         comp.attributes.src = comp.getAttributes()['src'].replace("https://demo.tribe.yo-kart.com/", "BASE_URL");
                //     }
                // });

                store.components.body = JSON.stringify(component);
                store.html.body = component.view.el.outerHTML;
            } else if (component.ccid == 'footer') {
                store.components.footer = JSON.stringify(component);
                store.html.footer = component.view.el.outerHTML;
            }
        })
        await fetch(adminBaseUrl + `/savetemplate/${pageId}`, {method: "POST", body: JSON.stringify(store), headers: headers})
        .then(response => response.json())
        .then((data) => {
            if(data.status){
                toastr.success(templateSavedMessage);
                loader(false);
                window.onbeforeunload = null;
                typeof callback === 'function' && callback(data.status);
            }
        })
        .catch(() => {
            toastr.error(somethingWentWrongMessage);
        })
    }

    loadTemplate = function() {
        fetch(adminBaseUrl + `/loadtemplate/${pageId}`, {method: "GET", headers: headers})
        .then(response => response.json())
        .then((data) => {
            editor.CssComposer.getAll().reset();
            
            var header = (data.header != '') ? JSON.parse(data.header) : '';
            var body = (data.body != '') ? JSON.parse(data.body) : '';
            var footer = (data.footer != '') ? JSON.parse(data.footer) : '';

            editor.setComponents([header, body, footer]);
            editor.setStyle(data.css);
            loader(false);
            window.onbeforeunload = null;    
        })
        .catch(() => {
            toastr.error(somethingWentWrongMessage);
        })
    }   

    pn.addButton //add custom preview button to open preview page in new tab
        ('options', [{
            id: 'preview-page',
            label: getSvgIcon('preview-page', '20'),
            command: function(editor, sender) {
                loader();
                sender && sender.set('active'); 
                saveTemplate(editor, function(response) {
                    window.open(previewLink, "_blank");     
                });
            },
            attributes: { title: 'Preview Page', 'data-tooltip-pos': 'bottom', style: 'order: 6' }
        }]);

    pn.addButton //add home button to go back to pages management
        ('commands', [{
            id: 'tlb-home',
            command: function() {
                window.open(pagesUrl, "_self");
            },
            attributes: { title: 'Back to Pages' },
            label: getSvgIcon('tlb-home', '20')
        }]);

    pn.removeButton('options', 'preview'); //remove default preview button
    pn.removeButton('options', 'gjs-open-import-webpage'); //remove download button
    pn.removeButton('options', 'export-template'); //remove export code button
    pn.removeButton('options', 'fullscreen'); //remove fullscreen button
    pn.removeButton('devices-c', 'set-device-desktop');
    pn.removeButton('devices-c', 'set-device-tablet');
    pn.removeButton('devices-c', 'set-device-mobile');

    pn.addButton('commands', [{
        id: 'tlb-level',
        label: `<form method="post" action="` + adminBaseUrl + `/pages/skill-level/` + pageId + `">
        <input type="hidden" name="skill_level" value="` + cmsSkillLevel + `">
        <button class="btn--skill skill-toggle" onclick="switchSkillLevel(event)">
        <i class="icn">` + getSvgIcon('tlb-level-' + cmsSkillLevel, "20") + `</i>
        <span class="txt">` + cmsSkillLevelTitle + `</span></button></form>`,
        attributes: { title: 'Change skill level', style: 'order: 1;' }
    }]);

    pn.getButton('options', 'sw-visibility').set('active', 1); // Show borders by default
    pn.getButton('options', 'sw-visibility').set('attributes', { title: 'Highlights', 'data-tooltip-pos': 'bottom', style: 'order: 2' })
        .set('label', getSvgIcon('sw-visibility', '20'));

    pn.removeButton('options', 'canvas-clear'); //to remove option buttons
    pn.removeButton('options', 'btn'); //to remove option buttons
    pn.getButton('options', 'undo').set('attributes', {
        title: 'Undo',
        'data-tooltip-pos': 'bottom', style: 'order: 3'
    }).set('label', getSvgIcon('undo', '20'));
    pn.getButton('options', 'redo').set('attributes', {
        title: 'Redo',
        'data-tooltip-pos': 'bottom', style: 'order: 4'
    }).set('label', getSvgIcon('redo', '20'));

    pn.addButton('options', [{
        id: 'reset-page',
        label: getSvgIcon('reset-page', '20'),
        command: function(editor, sender) {
            $('#resetConfirmationModal').modal('show');
        },
        attributes: { title: 'Reset Page', 'data-tooltip-pos': 'bottom', style: 'order: 4' }
    }]);

    pn.addButton('devices-c', [{
            id: 'set-device-desktop',
            label: `<span class="yk-deviceicons" data-key="1">` + getSvgIcon('set-device-desktop', '20') + `</span>`,
            command: function(ed) {
                ed.setDevice('Desktop')
            },
            attributes: { title: 'Desktop', 'data-tooltip-pos': 'bottom' }
        },
        {
            id: 'set-device-tablet',
            label: `<span class="yk-deviceicons" data-key="2">` + getSvgIcon('set-device-tablet', '20') + `</span>`,
            command: function(ed) {
                ed.setDevice('Tablet')
            },
            attributes: { title: 'Tablet', 'data-tooltip-pos': 'bottom' }
        },
        {
            id: 'set-device-mobile',
            label: `<span class="yk-deviceicons" data-key="3">` + getSvgIcon('set-device-mobile', '20') + `</span>`,
            command: function(ed) {
                ed.setDevice('Mobile portrait')
            },
            attributes: { title: 'Mobile', 'data-tooltip-pos': 'bottom' }
        }
    ]);
    
    initToolbar = function(model) {
        let toolbar = model.get('toolbar');
        const ppfx = (editor && editor.getConfig('stylePrefix')) || '';
        $(toolbar).each(function(index) {
            if (toolbar[index].command == "tlb-move") {
                toolbar[index].attributes = {
                    class: `fa fa-arrows-alt ${ppfx}no-touch-actions`
                };
            }
            if (toolbar[index].command == "tlb-delete") {
                toolbar[index].attributes = { class: 'fa fa-trash', cid: model.cid };
                toolbar[index].command = 'delete-comp';
            }
            if (toolbar[index].command == "tlb-clone") {
                delete toolbar[index];
                // toolbar[index].attributes = { class: 'far fa-clone' };
            }
        });
        if(toolbar == ''){
            return false;
        }
        model.set('toolbar', toolbar.filter( (el)  => el != null));
    };
    
    cmdm.add('delete-comp', editor => {
        const selected = editor.getSelected();
        $('.YK-deleteComponent').attr('data-cid', "");
        if( selected.find('.yk-container').length > 0 ){
            var compid = selected.find('.yk-container')[0].attributes.attributes.compid;
            if(compid != ''){
                $('.YK-deleteComponent').attr('data-cid', compid);
            }
        }
        $('#deleteConfirmationModal').modal("show");
    });

    deleteComponent = function(event) {   
        window.onbeforeunload = null;     
        event.preventDefault();
        event.target.classList.add('gb-is-loading');
        const selected = editor.getSelected();
        var compid = $('.YK-deleteComponent').data('cid');
        if (compid != "") {
            var formData = new FormData();
            formData.append('cid', compid);
            fetch(adminBaseUrl + '/collection/delete_all', {method: "POST", body: formData, headers: headers})
            .then(response => response.json())
            .then((data) => {
                if(data.status){
                    selected && selected.destroy();
                    saveTemplate(editor);
                }
            })
            .catch(() => {
                toastr.error(somethingWentWrongMessage);
            })
        } else {
            selected && selected.destroy();
            saveTemplate(editor);
        }       
        event.target.classList.remove('gb-is-loading');
        $('#deleteConfirmationModal').modal("hide"); 
    };

    editor.on("component:selected", model => {
        initToolbar(model);
    });

    editor.on(`component:mount`, function(el) { //this is how to update the grapesjs components manually with new html
        if (el.attributes.tagName == 'productcollectionlayout1' || el.attributes.tagName == 'productcollectionlayout2' || el.attributes.tagName == 'categorycollectionlayout1') {
            let cid = el.parent().getView().$el.attr('compid');
            if (typeof productsHtml[cid] != 'undefined') {
                // el.components().forEach(comp => {// your modifications on the children components
                //   console.log(comp);
                // }) 
                el.components($(productsHtml[cid]).html());
                delete productsHtml[cid];
                resetComponentSettings(el, 'yk-container');
            }
        } else if (el.attributes.tagName == 'logo-shortcode') {
            el.components($(logoHtml).html());
            resetComponentSettings(el, 'yk-container');
        }
    });

    editor.on('component:update:components', component => {
        // console.log('update:components=>');
        let selected = editor.getSelected();
        let outerClass = '';
        if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-brands') != -1) {
            outerClass = 'brands';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-header') != -1) {
            outerClass = 'navigationLayout1';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-productCollectionLayout1') != -1) {
            outerClass = 'productCollectionLayout1';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-productCollectionLayout2') != -1) {
            outerClass = 'productCollectionLayout2';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-categoryCollectionLayout1') != -1) {
            outerClass = 'categoryCollectionLayout1';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-categoryCollectionLayout2') != -1) {
            outerClass = 'categoryCollectionLayout2';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-bannerSlider') != -1) {
            outerClass = 'bannerSlider';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-promotionalBannerLayout1') != -1) {
            outerClass = 'promotionalBannerLayout1';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-newsletterFullwidth') != -1) {
            outerClass = 'newsletterFullwidth';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-newsletterCollection2') != -1) {
            outerClass = 'newsletterCollection2';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-logo') != -1) {
            outerClass = 'logo';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-instagramLayout1') != -1) {
            outerClass = 'instagramLayout1';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-instagramLayout2') != -1) {
            outerClass = 'instagramLayout2';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-instagramCollection3') != -1) {
            outerClass = 'instagramCollection3';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-imageGallery') != -1) {
            outerClass = 'imageGallery';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-promotionalBannerCollection2') != -1) {
            outerClass = 'promotionalBannerCollection2';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-imageTag') != -1) {
            outerClass = 'imageTag';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-videoTag') != -1) {
            outerClass = 'videoTag';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-blogLayout1') != -1) {
            outerClass = 'blogLayout1';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-blogLayout2') != -1) {
            outerClass = 'blogLayout2';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-blogCollection3') != -1) {
            outerClass = 'blogCollection3';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-testimonialLayout1') != -1) {
            outerClass = 'testimonialLayout1';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-twoColumnTextCta') != -1) {
            outerClass = 'twoColumnTextCta';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-titleWithBackgroundImage') != -1) {
            outerClass = 'titleWithBackgroundImage';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-textWithTwoImagesOnLeft') != -1) {
            outerClass = 'textWithTwoImagesOnLeft';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-textWithTwoImagesOnRight') != -1) {
            outerClass = 'textWithTwoImagesOnRight';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-customMap') != -1) {
            outerClass = 'customMap';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-categoryCollection3') != -1) {
            outerClass = 'categoryCollection3';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-categoryCollection4') != -1) {
            outerClass = 'categoryCollection4';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-testimonialCollection2') != -1) {
            outerClass = 'testimonialCollection2';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-faqCollection1') != -1) {
            outerClass = 'faqCollection1';
        } else if (typeof selected != 'undefined' && selected.view.el.classList.value.indexOf('yk-promotionalBannerCollection3') != -1) {
            outerClass = 'promotionalBannerCollection3';
        } else {
            return false;
        }
        resetComponentSettings(selected, 'yk-' + outerClass);
    });

    editor.on('block:drag:stop', function(droppedComponent) {
        //console.log('drop component=>');
        //console.log(droppedComponent);
        // console.log(editor.getComponents().models);
        // editor.getComponents().forEach(component => {
        //     if (component.ccid == 'header') {
        //         store.components.header = JSON.stringify(component);
        //         store.html.header = component.view.el.outerHTML;
        //     }
        // })
        // var headerComponent = editor.getComponents().models.filter( (component) => component.attributes.getNamedItem("data-id").value .view.el.classList.value.indexOf('yk-header') == -1 ? true : false );
        // console.log(headerComponent);
        // console.log(droppedComponent.view.el.classList);
        if (typeof droppedComponent == 'undefined') {
            return false;
        }
        // console.log("droppedComponent.ccid=>", droppedComponent.ccid);
        editor.select(editor.DomComponents.getWrapper().find('#' + droppedComponent.ccid)[0]);
        // console.log("editor.DomComponents.getWrapper().find('#' + droppedComponent.ccid)=>", editor.DomComponents.getWrapper().find('#' + droppedComponent.ccid));
        const component = editor.getSelected();
        let outerClass = '';
        let displayEditor = 1;
        // console.log("droppedComponent.view.el.classList=>", droppedComponent.view.el.classList);
        // console.log("droppedComponent.view.el.classList.value=>", droppedComponent.view.el.classList.value);
        if (droppedComponent.view.el.classList.value.indexOf('yk-brands') != -1) {
            outerClass = 'brands';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-header') != -1) {
            outerClass = 'navigationLayout1';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-productCollectionLayout1') != -1) {
            outerClass = 'productCollectionLayout1';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-productCollectionLayout2') != -1) {
            outerClass = 'productCollectionLayout2';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-categoryCollectionLayout1') != -1) {
            outerClass = 'categoryCollectionLayout1';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-categoryCollectionLayout2') != -1) {
            outerClass = 'categoryCollectionLayout2';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-bannerSlider') != -1) {
            outerClass = 'bannerSlider';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-promotionalBannerLayout1') != -1) {
            outerClass = 'promotionalBannerLayout1';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-newsletterFullwidth') != -1) {
            outerClass = 'newsletterFullwidth';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-newsletterCollection2') != -1) {
            outerClass = 'newsletterCollection2';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-instagramLayout1') != -1) {
            outerClass = 'instagramLayout1';
            displayEditor = 0;
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-instagramLayout2') != -1) {
            outerClass = 'instagramLayout2';
            displayEditor = 0;
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-instagramCollection3') != -1) {
            outerClass = 'instagramCollection3';
            displayEditor = 0;
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-footer') != -1) {
            outerClass = 'logo';
            component.getView().$el.find('.yk-logo').attr('compid', droppedComponent.cid);
            return false;
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-imageGallery') != -1) {
            outerClass = 'imageGallery';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-promotionalBannerCollection2') != -1) {
            outerClass = 'promotionalBannerCollection2';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-imageTag') != -1) {
            outerClass = 'imageTag';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-videoTag') != -1) {
            outerClass = 'videoTag';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-blogLayout1') != -1) {
            outerClass = 'blogLayout1';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-blogLayout2') != -1) {
            outerClass = 'blogLayout2';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-blogCollection3') != -1) {
            outerClass = 'blogCollection3';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-testimonialLayout1') != -1) {
            outerClass = 'testimonialLayout1';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-twoColumnTextCta') != -1) {
            outerClass = 'twoColumnTextCta';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-titleWithBackgroundImage') != -1) {
            outerClass = 'titleWithBackgroundImage';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-textWithTwoImagesOnLeft') != -1) {
            outerClass = 'textWithTwoImagesOnLeft';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-textWithTwoImagesOnRight') != -1) {
            outerClass = 'textWithTwoImagesOnRight';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-customMap') != -1) {
            outerClass = 'customMap';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-highlightedText') != -1) {
            outerClass = 'highlightedText';
            displayEditor = 0;
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-categoryCollection3') != -1) {
            outerClass = 'categoryCollection3';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-categoryCollection4') != -1) {
            outerClass = 'categoryCollection4';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-testimonialCollection2') != -1) {
            outerClass = 'testimonialCollection2';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-faqCollection1') != -1) {
            outerClass = 'faqCollection1';
        } else if (droppedComponent.view.el.classList.value.indexOf('yk-promotionalBannerCollection3') != -1) {
            outerClass = 'promotionalBannerCollection3';
        } else {
            return false;
        }        
        component.getEl().setAttribute('compid', droppedComponent.cid);
        component.getView().$el.attr('compid', droppedComponent.cid);
        let responseEl = component.getView().$el;
        responseEl.find('.yk-' + outerClass + ' .yk-container').attr('compid', droppedComponent.cid).attr('pageid', pageId);
        responseEl.find('.yk-' + outerClass).attr('compid', droppedComponent.cid);
        if (outerClass == 'productCollectionLayout1' || outerClass == 'productCollectionLayout2' || outerClass == 'categoryCollectionLayout1' || outerClass == 'testimonialLayout1' || outerClass == 'testimonialCollection2') {
            responseEl.find(outerClass.toLowerCase()).attr('compid', droppedComponent.cid).attr('pageid', pageId);
        }
        component.components(responseEl.html());
        if (droppedComponent.view.el.classList.value.indexOf('yk-footer') == -1) { //skip this for footer
            if (displayEditor == 1) {
                editSettings(droppedComponent.cid, outerClass);
            }
            resetComponentSettings(droppedComponent, 'yk-' + outerClass);
        }
    });

    // editor.on('canvas:drop', (dataTransfer, model) => { toastr.warning('Loading... Please wait', '', {timeOut: 1000}); });

    editSettings = function(cid, layout) {
        if (typeof layout == 'undefined' || layout == '') {
            return false;
        }
        $.ajax({
            method: "POST",
            url: adminBaseUrl + '/collection/get',
            data: { cid: cid, layout: layout },
            success: function(response) {
                $('.modal-backdrop').remove();
                $('.modalsContent').html('').html(response);
                refreshSortable(cid, layout);
                $('#settingsModal').modal('show');
            }
        });
    };

    refreshSortable = function(cid, layout) {
        $(".yk-sortable").sortable({
            placeholder: "ui-state-highlight",
            start: function(event, ui) {
                ui.item.data('start_pos', ui.item.index());
            },
            stop: function(event, ui) {
                var start_pos = ui.item.data('start_pos');
                if (start_pos != ui.item.index()) {
                    let recordId = ui.item[0].attributes.getNamedItem("data-id").value;
                    let newPosition = parseInt(ui.item.index()) + 1;
                    var movement = ui.position.top - ui.originalPosition.top > 0 ? "down" : "up";
                    getUpdatedListing(cid, layout, recordId, newPosition, movement);
                }
            }
        });
    };
    getUpdatedListing = function(cid, layout, recordId, newPosition, movement) {
        $.ajax({
            method: "POST",
            url: adminBaseUrl + '/collection/get',
            data: { cid: cid, layout: layout, updateListing: 1, recordId: recordId, newPosition: newPosition, movement: movement },
            success: function(response) {
                toastr.success(displayOrderUpdatedMessage);
                if (layout == 'categoryCollectionLayout1') {
                    $('#settingsModal .tab-content').find('.tab-pane.active.show .yk-sortable').html(response);
                } else {
                    $('#settingsModal').find('.yk-sortable').html(response);
                    if (layout == 'navigationLayout1') {
                        reloadComboTreeSelect();
                    }
                }
            }
        });
    };
    editor.on('styleable:change', (model, property) => {
        const value = model.getStyle()[property];
        if (typeof value != 'undefined' && value.toString().indexOf('!important') === -1 && property != 'display') {
            model.addStyle({
                [property]: value + ' !important'
            });
        }
    });
    resetComponentSettings = function(resourceModel, outerClass) {
        if (typeof resourceModel == 'undefined' || outerClass == '') {
            return false;
        }
        const updateAll = model => {
            if (model.cid != resourceModel.cid && typeof model.view != 'undefined' && typeof model.view.el.classList != 'undefined' &&
                model.view.el.classList.value.indexOf(outerClass) == -1 &&
                model.view.el.classList.value.indexOf('yk-title') == -1 &&
                model.view.el.classList.value.indexOf('yk-caption') == -1 &&
                model.view.el.classList.value.indexOf('yk-link') == -1
            ) {
                if(model.view.el.classList.value.indexOf('yk-styleable') != -1){
                    model.set({ editable: false, removable: false, draggable: false, highlightable: true, selectable: true, hoverable: true });
                }else{
                    model.set({ editable: false, removable: false, draggable: false, highlightable: false, selectable: false, hoverable: false });
                }
            }
            model.get('components').each(model => updateAll(model));
        }
        updateAll(resourceModel);
    };

    getRandomNumber = function() {
        return Math.floor((Math.random() * 100) + 1);
    };

    truncateText = function(text, limit) {
        return jQuery.trim(text).substring(0, limit)
            .split(" ").slice(0, -1).join(" ") + "...";
    };
    reloadComboTreeSelect = function() {
        window.comboTreeCategories = $('.yk-getCategories').not('.comboTreeInputBox').comboTree({
            source: categoriesData
        });
        window.comboTreePages = $('.yk-getPages').not('.comboTreeInputBox').comboTree({
            source: pagesData
        });
    }

    resetPage = function(event) {   
        window.onbeforeunload = null;     
        event.preventDefault();
        event.target.classList.add('gb-is-loading');
        let formData = new FormData();
        formData.append('page_id', pageId);
        fetch(adminBaseUrl + '/pages/reset', {method: "POST", body: formData, headers: headers})
        .then(response => response.json())
        .then((data) => {
            if(data.status){
                toastr.success(data.message);
                window.location.reload();
            }
        })
        .catch(() => {
            event.target.classList.remove('gb-is-loading');
            toastr.error(somethingWentWrongMessage);
        })
    };

    switchSkillLevel = function(event) {
        window.onbeforeunload = null;
        event.preventDefault();
        $(event.target).closest('form').trigger("submit");
    };

    // var window.$image = "";
    $(function() {
        loadTemplate();

        /*setting variables for image cropper*/
        window.aspectRatio = 1.77;
        window.tmppath = '';
        window.actualImage = '';

        window.isParentFooter = false;
        $('iframe').contents().find("body").on('click', '.yk-anchor', function(e) {
            var $link = $(e.target);
            e.preventDefault();
            if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
                setTimeout(function() {
                    openAnchorSettings(e);
                }, 200);
            }
            $link.data('lockedAt', +new Date());
        });
        
        $(document).on('click', '.yk-deviceicons', function(e){
            var key = $(this).data('key');
            $('.gjs-pn-devices-c .gjs-pn-btn').removeClass("gjs-pn-active gjs-four-color");
            $('.gjs-pn-devices-c .gjs-pn-btn:nth-child(' + key + ')').addClass("gjs-pn-active gjs-four-color");
        });
        $(document).on('click', '.gjs-pn-devices-c .gjs-pn-btn', function(e){
            var key = $(this).find('.yk-deviceicons').data('key');
            $('.gjs-pn-devices-c .gjs-pn-btn').removeClass("gjs-pn-active gjs-four-color");
            $('.gjs-pn-devices-c .gjs-pn-btn:nth-child(' + key + ')').addClass("gjs-pn-active gjs-four-color");
        });
                    
        $(document).on('click', '.modal-backdrop.fade.show', function() {
            $('.modal-backdrop.fade.show').remove();
        });

        /*open settings popup for social link elements*/        
        $('iframe').contents().find("body").on('dblclick', '.yk-socialLink', function(e) {
            var $link = $(e.target);
            e.preventDefault();
            if (!$link.data('lockedAt') || +new Date() - $link.data('lockedAt') > 300) {
                setTimeout(function() {
                    let sameTabSelected = '';
                    let newTabSelected = '';                    
                    var component = editor.getSelected();
                    if(component.attributes.tagName != 'a') {
                        editor.select(editor.DomComponents.getWrapper().find('#' + component.ccid)[0].collection.parent);
                        component = editor.getSelected();
                    }                    
                    let target = component.attributes.attributes.target;
                    let href = component.attributes.attributes.href;
                    if (target == '_blank') {
                        newTabSelected = 'selected';
                    } else if (target == '_self') {
                        sameTabSelected = 'selected';
                    }
                    if (typeof href == 'undefined' || href == '#') { href = ''; }
                    var linkContainer = `<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content"><div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Social Link</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-row align-items-center">
            <div class="col-md-6 mb-3">
              <label for="validationCustom01">URL</label>
              <input type="text" class="form-control" name="url" placeholder="enter link" value="` + href + `" id="linkInputField" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="my-1 mr-2">Target</label>
              <select class="custom-select my-1 mr-sm-2" name="target">
                <option value='' readonly disabled selected>Choose...</option>
                <option value="_self" ` + sameTabSelected + `>Same Tab</option>
                <option value="_blank" ` + newTabSelected + `>New Tab</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary gb-btn gb-btn-primary yk-saveSocialLink">Save</button>
        </div>
        </div>
        </div>
        </div>
      </div>`;
                    $('.modalsContent').html('').html(linkContainer);
                    $('#settingsModal').modal('show');
                }, 200);
            }
            $link.data('lockedAt', +new Date());
        });
        /*insert social link on social elements*/
        $(document).on('click', '.yk-saveSocialLink', function(e) {
            $(this).addClass('gb-is-loading').attr('disabled', 'disabled');
            var thisObj = $('iframe').contents().find(".gjs-selected");
            var linkVal = $('#linkInputField').val();
            var targetVal = $('select[name="target"] option:selected').val();
            thisObj.attr('href', linkVal);
            thisObj.attr('target', targetVal);
            const component = editor.getSelected();
            component.attributes.attributes.href = linkVal;
            component.attributes.attributes.target = targetVal;
            let responseEl = component.getView().$el;
            responseEl.find('.gjs-selected').attr('href', linkVal).attr('target', targetVal);
            responseEl.find('.gjs-selected i').attr('data-gjs-editable', "false").attr('data-gjs-removable', "false").attr('data-gjs-selectable', "false");
            component.components(responseEl.html());
            $('#settingsModal').modal('hide');
            setTimeout(() => {
                $(this).removeClass('gb-is-loading').removeAttr('disabled');
            }, 1000);
        });
        
        require([
            widgetsJs + "/brands.js",
            widgetsJs + "/bannerSlider.js",
            widgetsJs + "/contactForm.js",
            widgetsJs + "/customMap.js",
            widgetsJs + "/categoryCollectionLayout1.js",
            widgetsJs + "/categoryCollectionLayout2.js",
            widgetsJs + "/categoryCollection3.js",
            widgetsJs + "/categoryCollection4.js",
            widgetsJs + "/testimonialCollection2.js",
            widgetsJs + "/promotionalBannerLayout1.js",
            widgetsJs + "/promotionalBannerCollection2.js",
            widgetsJs + "/newsletterFullwidth.js",
            widgetsJs + "/newsletterCollection2.js",
            widgetsJs + "/blogLayout1.js",
            widgetsJs + "/blogLayout2.js",
            widgetsJs + "/blogCollection3.js",
            widgetsJs + "/faqCollection1.js",
            widgetsJs + "/productCollectionLayout1.js",
            widgetsJs + "/productCollectionLayout2.js",
            widgetsJs + "/image.js",
            widgetsJs + "/imageGallery.js",
            widgetsJs + "/promotionalBannerCollection3.js",
            widgetsJs + "/testimonialLayout1.js",
            widgetsJs + "/twoColumnTextCta.js",
            widgetsJs + "/titleWithBackgroundImage.js",
            widgetsJs + "/navigationLayout1.js",
            widgetsJs + "/textWithTwoImagesOnLeft.js",
            widgetsJs + "/textWithTwoImagesOnRight.js",
            widgetsJs + "/video.js"
        ]);

    });
    
    /*cropper functions*/
    loadCropper = function() {
        window.$image = $('.cropperSelectedImage');
        window.$image.cropper({ aspectRatio: window.aspectRatio });
    }
    selectImage = function(img) {
            if (img.size > 2 * 1024 * 1024) {
                toastr.error(imageSizeValidationMessage);
                document.querySelector('.js-cropperSelectImage').value = '';
                return;
            }
            window.actualImage = img;
            window.tmppath = URL.createObjectURL(img);
            if (!$('#modal_cropper').hasClass('show')) {
                $('.cropperSelectedImage').attr('src', window.tmppath);
                $('#settingsModal').modal('hide');
                $('#modal_cropper').modal('show');
                setTimeout(function() {
                    loadCropper();
                }, 200);
            } else {
                window.$image.data('cropper').replace(window.tmppath);
            }
        }
        // Do stuff on load
    editor.on('load', function() {
        var $ = grapesjs.$;
        $('.gjs-pn-devices-c [title="Desktop"]').trigger('click'); // display desktop device selected by default
        
        const keys = 'up, down, left, right, shift+up, shift+down, shift+left, shift+right, backspace, delete';
        keys.split(/, ?/).forEach((key) => {
            editor.Keymaps.keymaster.unbind(key);
        });        
    });
})();