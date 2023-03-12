(function() {
    if (productBackground == 1) {
        $('.js-fillcolor').fillColor();
    }
    var rtlValue = false;
    if ($('html').attr('dir') == 'rtl') {
        rtlValue = true;
    }

    $(document).on('click', '.YK-openSearchBar', function() {
        $(".main-search-bar").removeClass("d-none");
        var input = $('.YK-headerSearchInput');
        setTimeout(function() {
            input.trigger('focus');
            var tmpStr = input.val();
            input.val('').val(tmpStr);
        }, 200);
    });

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    $(document).find('.yk-productCollectionLayout1 .yk-container, .yk-productCollectionLayout2 .yk-container, .yk-categoryCollectionLayout1 .yk-container').each(function() {
        $(this).find('.yk-link').attr('href', baseUrl + '/collection/' + $(this).attr('pageid') + '/' + $(this).attr('compid'));
    });

    $(document).on('click', '.view_gallery', function() {
        $(this).hide();
        let closestobj = $(this).closest('.product');
        closestobj.find('.product3D').addClass('flip180');
        closestobj.find('.product-front').css('display', 'none');
        closestobj.find('.product-back').css('display', 'block');
        if ($.trim(closestobj.find(".slider-quick").html()) == "") {
            closestobj.find('.js-flipLoader').show();
            $.ajax({
                url: baseUrl + '/product/get-gallery-images',
                type: "post",
                data: { 'product-id': $(this).data('productid'), 'variant-id': $(this).data('subrecordid'), 'prod-name': $(this).data('prodname') },
                success: function(res) {
                    closestobj.find('.js-flipLoader').hide();
                    closestobj.find(".slider-quick").html(res);
                    if (closestobj.find(".slider-quick picture").length > 1) {
                        closestobj.find(".slider-quick").slick({
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            autoplay: true,
                            autoplaySpeed: 1500,
                            arrows: false,
                            rtl: rtlValue,
                            dots: true,
                        });
                    }
                }
            });
        }
    });
    $(document).on('click', '.flip-back', function() {
        $("#view-gallery-" + $(this).data('id')).show();
        let closestobj = $(this).closest('.product');
        closestobj.find('.product3D').removeClass('flip180');
        closestobj.find('.product-front').css('display', 'block');
        closestobj.find('.product-back').css('display', 'none');
    });

    /** Add to cart */
    $(document).on('click', '.YK-add-to-cart', function() {
        NProgress.start();
        let productId = $(this).data('productid');
        let redirect = $(this).data('redirect');
        let productOptionId = $(this).data('optioncode') ? $(this).data('optioncode') : 0;

        addTocart(productId, '', redirect, 0, productOptionId);
    });
    if ($('.YK-darkMode').html() == "") {
        $('.YK-darkMode').remove();
    }

    /**/
    $(document).on('click', '.YK-closeSearchBar', function() {
        $('body').removeClass('main-search-bar--on');
    });
    $(document).on('click', '.YK-closeSlogan', function() {
        $('.YK-topHeader').remove();
    });
    if (typeof $.cookie(systemPrefix + 'ThemeStyle') != 'undefined') {
        if ($.cookie(systemPrefix + 'ThemeStyle') == 'light') {
            $('#yk-header-logo').attr('src', $("#yk-header-logo").attr('data-lite'));
        } else {
            if ($("#yk-header-logo").attr('data-dark') != "") {
                $('#yk-header-logo').attr('src', $("#yk-header-logo").attr('data-dark'));
            }
        }
    }
    /*toggle theme switch*/
    $('.YK-themeSwitch').on('click', function(e) {
        // $.removeCookie(systemPrefix + 'ThemeStyle');
        $.removeCookie(systemPrefix + 'ThemeStyle', { path: '/', domain: domain });
        $.removeCookie(systemPrefix + 'ThemeStyle', { path: '/', domain: '.' + domain });
        if ($(this).hasClass("dark")) {
            $('#yk-header-logo').attr('src', $("#yk-header-logo").attr('data-lite'));
            $('.YK-themeSwitch').removeClass('dark').addClass('light');
            $('html').attr('data-theme', 'light');
            $('.YK-themeSwitch svg use').attr('xlink:href', $('.YK-themeSwitch').data('light'));
            $('.YK-themeSwitch svg use').attr('href', $('.YK-themeSwitch').data('light'));
            $('.YK-mode').html('Dark Mode');
            $.cookie(systemPrefix + 'ThemeStyle', 'light', { path: '/', expires: 365 });
        } else {
            if ($("#yk-header-logo").attr('data-dark') != "") {
                $('#yk-header-logo').attr('src', $("#yk-header-logo").attr('data-dark'));
            }
            $('.YK-themeSwitch').removeClass('light').addClass('dark');
            $('html').attr('data-theme', 'dark');
            $('.YK-themeSwitch svg use').attr('xlink:href', $('.YK-themeSwitch').data('dark'));
            $('.YK-themeSwitch svg use').attr('href', $('.YK-themeSwitch').data('dark'));
            $('.YK-mode').html('Light Mode');
            $.cookie(systemPrefix + 'ThemeStyle', 'dark', { path: '/', expires: 365 });
        }
    });
    /*change currency*/
    $('.YK-switchCurrency [type="radio"]').on('click', function(e) {
        window.location.href = currentUrl + '?currency=' + $(this).val();
    });

  //  $('.YK-selectedLanguageFlag').attr('src', baseUrl + '/flags/' + userDefaultFlag + '.svg');

    /*on click of card image link to product detail*/
    $('.YK-productLink').on('click', function() {
        window.location.href = $(this).attr('data-href');
    });
    /*handle the newsletter submission*/
    $('.YK-agreeTerms').on('click', function(e) {
        if ($(this).is(":checked")) {
            $(this).closest('.form').find('button[name="contactform"]').removeAttr('disabled');
        } else {
            $(this).closest('.form').find('button[name="contactform"]').attr('disabled', '');
        }
    });
    $('.YK-subscribeNewsletter').attr('disabled', 'disabled');
    $(document).on('keyup', '.yk-newsletter input[type="email"], .yk-newsletterFullwidth input[type="email"], .yk-newsletterCollection2 input[type="email"], .yk-footer input[name="email"]', function(e) {
        var emailValue = $(this).val();
        if (emailValue == "") {
            $(this).closest('form').find('.YK-subscribeNewsletter').attr('disabled', 'disabled');
        } else {
            $(this).closest('form').find('.YK-subscribeNewsletter').removeAttr('disabled');
        }
    });
    $('.YK-subscribeNewsletter').on('click', function(e) {
        e.preventDefault();
        NProgress.start();
        let thisObj = $(this);
        var values = thisObj.closest('form').serialize();
        thisObj.closest('form').find('div.message').html('');
        $.ajax({
            url: baseUrl + '/newsletter',
            type: "post",
            data: values,
            success: function(res) {
                NProgress.done();
                if (res.status) {
                    // toastr.success(res.message);
                    $(".YK-subscribedModal").find(".YK-content").html('').html(res.message);
                    $(".YK-subscribedModal").modal("show");
                    thisObj.closest('form').find('input[name=email]').val('');
                } else {
                    toastr.error(res.message);
                }
            }
        });
    });

    blogSidebarContent = function(query = '') {
        $.get(baseUrl + '/blogs/load-sidebar-content?query=' + query, function(response) {
            $('.YK-blogSidebarContent').html(response);
        });
    };
    saveComments = function() {
            let obj = $('#YK-comment_form');
            obj.find('.invalid-feedback').remove();
            obj.find('input, textarea').removeClass('is-invalid');
            let commentData = obj.serializeArray();
            $.post(baseUrl + '/blog/comment/save', commentData, function(response) {
                if (response.status == true) {
                    obj.trigger("reset");
                    toastr.success(response.message);
                }
            }).fail(function(response) {
                let errors = response.responseJSON.errors;
                validateErrors(errors, obj);
            });

        }
        /*handle the contactform */
    $('.yk-contactForm input, .yk-contactForm textarea').removeAttr('readonly');
    $('form.yk-contactForm button[name="contactform"]').on('click', function(e) {
        e.preventDefault();
        let obj = $(this).closest('.yk-contactForm');
        obj.find('input,textarea,select').removeClass('is-invalid');
        obj.find('.invalid-feedback').remove();
        obj.find('button[name="contactform"]').addClass("gb-is-loading");
        var values = obj.find('input,textarea,select').serialize();
        NProgress.start();
        $.ajax({
            url: baseUrl + '/contact',
            type: "post",
            data: values,
            success: function(res) {
                NProgress.done();
                events.contactUs();
                if (!res.status) {
                    toastr.error(res.message);
                }
                toastr.success(res.message);
                obj.find('button[name="contactform"]').removeClass("gb-is-loading");
                obj[0].reset();
            },
            error: function(xhr, status, error) {
                NProgress.done();
                obj.find('button[name="contactform"]').removeClass("gb-is-loading");
                let errors = xhr.responseJSON.errors;
                validateErrors(errors, obj);
            }
        });
    });

    saveProfile = function() {
        let obj = $('#YK-personalDetails');
        obj.find('.invalid-feedback').remove();
        obj.find('input').removeClass('is-invalid');
        let submitBtn = obj.find('button[name="saveProfileBtn"]');
        loader(submitBtn);
        $.post(baseUrl + '/user/profile', obj.serializeArray(), function(response) {
            loader(submitBtn, true);
            if (response.status == true) {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        }).fail(function(response) {
            loader(submitBtn, true);
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });
    }
    savePassword = function() {
        let obj = $('#YK-passwordDetails');
        $('.YK-comment_success').html('');
        obj.find('.invalid-feedback').remove();
        obj.find('input').removeClass('is-invalid');
        let passwordData = obj.serializeArray();
        $.post(baseUrl + '/user/password', passwordData, function(response) {
            if (response.status == true) {
                toastr.success(response.message);
            }
        }).fail(function(response) {
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });
    }
    switchStatus = function(event, id) {
        let status = 0;
        if ($(event).is(":checked")) {
            status = 1;
        }
        let formData = {
            'addr_default': status,
            'addr_id': id
        }
        $.post(baseUrl + '/user/address/updateStatus', formData, function(response) {

        })
    }

    deleteAddress = function(id) {
        let formData = {
            'addr_id': id
        }
        $.post(baseUrl + '/user/address/delete', formData, function(response) {
            if (response.status == true) {
                toastr.success(response.message);
                $('#dataModal').modal("hide");
                $("#YK-Address-list").empty();
                $("#YK-Address-list").html(response.data);
                $('[data-toggle="tooltip"]').tooltip();
            } else {
                toastr.error(res.message);
            }
        })
    }
    updateAddress = function(id) {
        let obj = $('#YK-addAddress');
        let submitBtn = obj.find('button[name="YK-updateaddress"]');
        let formdata = obj.serializeArray();
        loader(submitBtn);
        $.post(baseUrl + '/user/update-address', formdata, function(response) {
            loader(submitBtn, true);
            if (response.status == false) {
                toastr.error(response.message);
                return;
            }
            toastr.success(response.message);
            setTimeout(() => {
                window.location.href = baseUrl + "/user/address";
            }, 2000);
        }).fail(function(response) {
            loader(submitBtn, true);
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });
    }
    validateErrors = function(errors, obj) {
        Object.keys(errors).forEach(key => {
            if (!obj.find('input[name="' + key + '"], textarea[name="' + key + '"], select[name="' + key + '"]').hasClass('is-invalid')) {
                if (key.indexOf('.') !== -1) {
                    let arr = key.split(".");
                    let index = arr[0] + '[' + arr[1] + ']';
                    obj.find('input[name="' + index + '"], textarea[name="' + index + '"], select[name="' + index + '"]').addClass('is-invalid');
                    obj.find('input[name="' + index + '"], textarea[name="' + index + '"], select[name="' + index + '"]').closest('div').append('<div class="invalid-feedback">' + errors[key][0] + '</div>');
                } else {
                    obj.find('input[name="' + key + '"], textarea[name="' + key + '"], select[name="' + key + '"]').addClass('is-invalid');
                    obj.find('input[name="' + key + '"], textarea[name="' + key + '"], select[name="' + key + '"]').closest('div').append('<div class="invalid-feedback">' + errors[key][0] + '</div>');
                }
            }
        });
    }
    getDownloadItems = function(id) {
        $.post(baseUrl + '/user/orders/get-dowload-items', { 'order-id': id }, function(response) {
            $("#dataModal").empty();
            $("#dataModal").html(response);
            $('#dataModal').modal("show");

        })
    }
    saveMessage = function(id, type = '') {
        let obj = $('#YK-message-' + id);
        obj.find('.invalid-feedback').remove();
        obj.find('input').removeClass('is-invalid');
        let comment = obj.find('input[name="comment"]');
        let submitBtn = obj.find('button[name="YK-postOrderMessage-' + id + '"]');
        loader(submitBtn);
        $.post(baseUrl + '/user/orders/comments', { 'record-id': id, 'comment': comment.val(), 'type': type }, function(response) {
            loader(submitBtn, true);
            loadComments(id, '', '', type);
            comment.val('');
            toastr.success('Success', response.message);

        }).fail(function(response) {
            loader(submitBtn, true);
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });
    }
    getOrders = function(type, event = '', loadData = false, id = '') {
        let thisObj = $('.YK-loadMoreOrders');
        let loadId = thisObj.attr('data-last-id');
        if (event != "") {
            $(".YK-orderTabs").find('li').removeClass('active');
            $(event).closest('li').addClass('active');
            loadId = '';
        }

        thisObj.closest('.YK-loadMoreFooter').hide();
        let formData = {
            'lastid': loadId,
            'id': id,
        };
        NProgress.start();
        let postUrl1 = baseUrl + '/user/get-orders/' + type;
        $.post(postUrl1, formData, function(response) {
            NProgress.done();
            if (loadData == false) {
                $('.YK-orderListing').html(response.data.data);
            } else {
                $('.YK-orderListing').append(response.data.data);
            }
            thisObj.attr('data-last-id', response.data.lastId);
            floatingFormFix();
            if ($('.YK-orderListing li.YK-listing').length != response.data.total) {
                thisObj.closest('.YK-loadMoreFooter').show();
            }
            $('.noRecordFound').remove();
            $('.YK-listingBody').show();
            if ($('.YK-orderListing li.YK-listing').length == 0 && response.data.total == 0) {
                $('.YK-listingBody').hide();
                $('.YK-listing').append(response.data.data);
            }


        });

    }

    $(document).on('click', '.YK-loadMoreOrders', function(e) {
        let type = $('.YK-orderTabs').find('li.active a').attr('alt')
        getOrders(type, '', true);
    });

    getDigitalOrders = function(loadData = false, orderId = '', productId = '') {
        let thisObj = $('.YK-loadMoreDigitalOrders');
        let loadId = thisObj.attr('data-last-id');

        let sortBy = $("#YK-Sort-Name").attr('data-value');
        thisObj.closest('.YK-loadMoreFooter').hide();
        let formData = {
            'lastid': loadId,
            'order-id': orderId,
            'product-id': productId,
            'sortBy': sortBy,
        };
        NProgress.start();
        $.post(baseUrl + '/user/get-digtal-orders', formData, function(response) {
            NProgress.done();
            if (loadData == false) {
                $('.YK-digitalProductListing').html(response.data.data);
            } else {
                $('.YK-digitalProductListing').append(response.data.data);
            }
            thisObj.attr('data-last-id', $('li.YK-digitalListing').length);
            if ($('li.YK-digitalListing').length != response.data.total) {
                thisObj.closest('.YK-loadMoreFooter').show();
            }

            if ($('li.YK-digitalListing').length == 0 && response.data.total == 0) {
                $('.YK-filters').remove();
                $('.YK-listingBody').remove();
                $('.YK-listing').append(response.data.data);
            }

        });

    }

    $(document).on('click', '.YK-loadMoreDigitalOrders', function(e) {
        getDigitalOrders(true);
    });
    downloadFiles = function(orderId, productId) {
        let formData = {
            'order-id': orderId,
            'product-id': productId,
        };
        $.post(baseUrl + '/product/download/url', formData, function(response) {
            if (response.status == false) {
                toastr.error(response.message);
                return false;
            }
            $('#digitalListing-' + orderId + '-' + productId).html(response.data.data);
            window.location.href = response.data.url;
        });


    }
    updateNotes = function(event, id) {
        $.post(baseUrl + '/order/notes/' + id, { 'notes': $(event).val() }, function(response) {
            toastr.success('Success', response.message);
        })

    }

    orderConfirmation = function(event, id) {
        $.post(baseUrl + '/order/received', { 'order-id': id }, function(response) {
            $(event).closest('.YK-orderReceived').remove();
            toastr.success('Success', response.message);
        });

    }
    getBankForm = function() {
        let orderId = $('#YK-orderId').val();
        $.get(baseUrl + '/order/bank-form/' + orderId, function(response) {
            $("#dataModal").empty();
            $("#dataModal").html(response.data);
            $('#dataModal').modal("show");
            floatingFormFix();
        });

    }
    submitBankInformation = function() {

        let obj = $('#YK-bankInformation');
        obj.find('.invalid-feedback').remove();
        obj.find('input, textarea').removeClass('is-invalid');
        let formData = obj.serializeArray();
        formData.push({ name: 'order_id', value: $('#YK-orderId').val() });
        $.post(baseUrl + '/order/bank-form', formData, function(response) {
            if (response.status == true) {
                $('#dataModal').modal("hide");
                $('#YK-bankDetails').html(response.data);
            }
        }).fail(function(response) {
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });


    }
    $(document).on('click', '.YK-ordersTimeline .pagination a', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var url = $(this).attr('href');
        var orderId = $(this).closest('.YK-ordersTimeline').attr('data-id');
        loadComments(orderId, url);
    });
    loadComments = function(id, url = '', event = '', type = '') {
        if (url == '') {
            url = baseUrl + '/user/orders/load-comments';
        }
        if ($(event).attr('aria-expanded') == "true") {
            return;
        }
        $.post(url, { 'order-id': id, 'type': type }, function(response) {
            $('#YK-comments-' + id).html(response.data);
        })
    }
    updateReturn = function() {

        let subTotal = 0;
        let tax = 0;
        let price = 0;
        let maxQty = 0;
        let selectedQty = 0;
        let discount = 0;
        let reward = 0;
        let gift = 0;
        let shipping = $('input[name="YK-shippingValue"]').val();
        let shippingStatus = $('input[name="YK-shippingStatus"]').val();
        let totalRewardAmount = $('input[name="YK-rewardAmount"]').val();
        let totalDiscountAmount = $('input[name="YK-discountAmount"]').val();

        $('.YK-productListing').find('input').each(function() {
            maxQty += parseFloat($(this).attr('max'));
            selectedQty += parseFloat($(this).val());
            if ($(this).hasClass('YK-CaneledProduct')) {
                return;
            }
            price = $(this).data('price') * $(this).val();
            discount += $(this).data('discount') * $(this).val();
            reward += $(this).data('reward') * $(this).val();
            gift += $(this).data('gift') * $(this).val();
            subTotal += parseFloat(price);
            tax += $(this).data('tax') * $(this).val();
            $(this).closest('li').find('.YK-price').html(price);
        });
        if (maxQty != selectedQty) {
            shipping = 0;
        }
        if (discount != 0 && totalDiscountAmount < discount) {
            discount = parseFloat(totalDiscountAmount);
        }
        if (reward != 0 && totalRewardAmount < reward) {
            reward = parseInt(totalRewardAmount);
        }
        $('.YK-subTotal').html(subTotal.toFixed(2));
        $('.YK-tax').html(tax.toFixed(2));
        $('.YK-discount').html(discount.toFixed(2));
        $('.YK-reward').html(reward.toFixed(2));
        $('.YK-shipping').html(shipping);
        $('.YK-gift').html(gift.toFixed(2));
        let total = (parseFloat(subTotal) + parseFloat(tax) + parseFloat(shipping) - discount - reward);
        if (shippingStatus == 1) {
            total = parseFloat(total) + parseFloat(gift);
        } else {
            total = total - gift;

        }
        if (total < 0) {
            total = 0;
        }
        $('.YK-total').html(total.toFixed(2));

    }
    increaseReturnQty = function(event) {

        let qtyobj = $(event).closest('.YK-quantity').find('.YK-qty');
        let qtyVal = qtyobj.val();
        if (qtyVal >= parseInt(qtyobj.attr('max'))) {
            return false;
        };
        let newVal = parseInt(qtyVal) + 1;
        qtyobj.val(newVal);
        $(event).closest('.YK-productListing').find('.YK-reasonForm').show();
        updateReturn();

    }

    decreaseReturnQty = function(event) {

        let qtyobj = $(event).closest('.YK-quantity').find('.YK-qty');
        let qtyVal = qtyobj.val();
        if (qtyVal <= parseInt(qtyobj.attr('min'))) {
            return false;
        };
        let newVal = parseInt(qtyVal) - 1;
        if (newVal == 0) {
            $(event).closest('.YK-productListing').find('.YK-reasonForm').hide();
        }

        qtyobj.val(newVal);
        updateReturn();

    }

    returnProcess = function(orderId, reasonType) {
        let obj = $('#YK-cancelRequest');
        let submitBtn = obj.find('button[name="YK-returnProcessBtn"]');
        loader(submitBtn);
        obj.find('.invalid-feedback').remove();
        obj.find('input, textarea').removeClass('is-invalid');
        $('.YK-commonErrors').html('');
        let returnItems = [];
        let returnQty = 0;
        let selectReason = '';
        let comment = $('.YK-comment').val();
        let shipping = 1;
        let maxQty = 0;
        let selectedQty = 0;
        $('.YK-productListing').each(function() {
            if ($(this).hasClass('YK-CaneledProduct')) {
                return;
            }
            maxQty += parseFloat($(this).find('.YK-qty').attr('max'));
            selectReason = $(this).find('.YK-selectReason').val();
            if ($(this).find('.YK-qty').val() != 0) {
                returnItems.push({ 'id': $(this).find('input').data('id'), 'qty': $(this).find('.YK-qty').val(), 'reason': selectReason });
                selectedQty += parseFloat($(this).find('.YK-qty').val());
            }
            returnQty += parseInt($(this).find('.YK-qty').val());
        });
        if (returnQty == 0) {
            toastr.error('Please enter product qty');
            return false;
        }

        if (maxQty != selectedQty) {
            shipping = 0;
        }
        let commentdata = obj.serializeArray();
        commentdata.push({ name: 'items', value: JSON.stringify(returnItems) });
        commentdata.push({ name: 'qty', value: returnQty });
        commentdata.push({ name: 'comment', value: comment });
        commentdata.push({ name: 'order-id', value: orderId });
        commentdata.push({ name: 'type', value: reasonType });
        commentdata.push({ name: 'shipping', value: shipping });
        $.post(baseUrl + '/user/order/return-items', commentdata, function(response) {
            if (response.status == true) {
                loader(submitBtn, true);
                toastr.success('Success', response.message);
                window.location.replace(baseUrl + '/user/order/cancel/summary/' + orderId);
            }
        }).fail(function(response) {
            loader(submitBtn, true);
            let errors = response.responseJSON.errors;
            if (errors['qty']) {
                $('.YK-commonErrors').html(errors['qty']);
            }
            validateErrors(errors, obj);
        });
    }

    quickViewProduct = function(id) {
        $.get(baseUrl + '/product/' + id, function(response) {
            if (response.status == true) {
                $("#dataModal").empty();
                $("#dataModal").html(response.data);
                $('#dataModal').modal("show");
            }
        })
    }
    openDeletePopup = function(id, type) {
        $.get(baseUrl + '/delete-popup/' + id + '/' + type, function(response) {
            if (response.status == true) {
                $("#dataModal").empty();
                $("#dataModal").html(response.data);
                $('#dataModal').modal("show");
            }
        });
    }
    deleteData = function(id, type) {
        switch (type) {
            case "cardDelete":
                deleteCard(id);
                break;
            case "addressDelete":
                deleteAddress(id);
                break;
            case "gdprdataDelete":
                deleteAddress(id);
                break;
        }
    }
    deleteCard = function(id) {
        NProgress.start();
        $('#dataModal').modal("hide");
        $.ajax({
            url: baseUrl + '/user/card/delete/' + id,
            type: 'DELETE',
            success: function(response) {
                NProgress.done();
                if (response.status == true) {
                    toastr.success(response.message);
                    $('.YK-savedCardsHtml').html(response.data);
                    $('[data-toggle="tooltip"]').tooltip();
                } else {
                    toastr.error(res.message);
                }
            }
        });
    }
    $(document).on('click', '.YK-showAddCardForm', function() {
        NProgress.start();
        $.get(baseUrl + '/user/savecard', function(response) {
            $(".YK-savedCardsHtml").html(response.data);
            $('[data-toggle="tooltip"]').tooltip();
            setCardFormValidation();
            setCardFormValidationTrigger();
            NProgress.done();
            floatingFormFix();
            creditCardValidate();
        });
    });
    $(document).on('click', '.YK-savedCardsCancel', function() {
        NProgress.start();
        $.get(baseUrl + '/user/cards-ajax', function(response) {
            $(".YK-savedCardsHtml").html(response.data);
            NProgress.done();
            $('[data-toggle="tooltip"]').tooltip();
        });
    });
    creditCardValidate = function() {
        $('#YK-saveCards #number').validateCreditCard(function(result) {
            if (result.length_valid && result.luhn_valid) {
                var cardType = result.card_type.name.toLowerCase();
                var cardUrl = $('#YK-validateCardType').data('url');
                console.log(cardType);
                if (cardType == 'visa' || cardType == 'mastercard' || cardType == 'amex' || cardType == 'discover') {
                    $('#YK-validateCardType svg use').attr('xlink:href', cardUrl + cardType);
                    $('#YK-validateCardType svg use').attr('href', cardUrl + cardType);
                } else {
                    $('#YK-validateCardType svg use').attr('xlink:href', cardUrl + 'cc-card');
                    $('#YK-validateCardType svg use').attr('href', cardUrl + 'cc-card');
                }
                // repeat for rest of card types
            } else {
                // just print an error
            }
        });
    }
    saveCard = function() {
        let obj = $('#YK-saveCards');
        obj.find('.invalid-feedback').remove();
        obj.find('input').removeClass('is-invalid');
        let submitBtn = obj.find('button[name="saveCardBtn"]');
        loader(submitBtn);
        let formdata = obj.serializeArray();
        $.post(baseUrl + '/user/savecard', formdata, function(response) {
            if (response.status == true) {
                toastr.success(response.message);
                $(".YK-savedCardsHtml").html(response.data);
                $('[data-toggle="tooltip"]').tooltip();
            } else {
                loader(submitBtn, true);
                toastr.error(response.message);
            }
        }).fail(function(response) {
            loader(submitBtn, true);
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });
    }
    defaultCard = function(cardId, event) {
        event.disabled = true;
        NProgress.start();
        if (event.checked == false) {
            return;
        }
        let formdata = { cardId: cardId };
        $.post(baseUrl + '/user/defaultcard', formdata, function(response) {
            NProgress.done();
            if (response.status == true) {
                toastr.success(response.message);
                $('[data-toggle="tooltip"]').tooltip('dispose');
                $('.YK-savedCardsHtml').html(response.data);
                $('[data-toggle="tooltip"]').tooltip();
            } else if (response.status == false) {
                event.disabled = false;
                toastr.error('Something went wrong. Please try again.');
            }
        });
    }
    $(document).on('click', '.YK-checkLogin', function(e) {
        e.preventDefault();
        $.get(baseUrl + '/user/checklogin', function(response) {
            if (response.status == false) {
                $("#dataModal").empty();
                $("#dataModal").html(response.data);
                $('#dataModal').modal("show");
                setTimeout(function() { floatingFormFix(); }, 200);
            }
        });
    });
    sendReferralCode = function() {
        let obj = $('#YK-shareAndEarnForm');
        let submitBtn = obj.find('button[name="referralBtn"]');
        let formdata = obj.serializeArray();
        loader(submitBtn);
        $.post(baseUrl + '/user/send-referral', formdata, function(response) {
            $("#dataModal").empty();
            $("#dataModal").html(response.data);
            $('#dataModal').modal("show");
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        });
    }
    saveReferralCode = function(element) {
        let obj = $(element);
        let code = obj.attr('data-code');
        let formData = {
            'ser_code': code
        }
        $.post(baseUrl + '/user/save-referral-code', formData, function(response) {
            if (response.status == false) {
                toastr.error(response.message);
                return;
            }
        });
    }
    generateRandomString = function(len) {
        if (typeof len == 'undefined') {
            len = 10;
        }
        let text = ""
        let chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz"
        for (let i = 0; i < len; i++) {
            text += chars.charAt(Math.floor(Math.random() * chars.length))
        }
        return text
    }
    $(".js-fourColumn-slider").slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: false,
        arrows: true,
        dots: false,
        rtl: rtlValue,
        autoplaySpeed: 2000,
        responsive: [{
                breakpoint: 1030,
                settings: {
                    slidesToShow: 3,
                    dots: false,
                    arrows: false,
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 3,
                    arrows: false,
                    dots: false,

                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                    dots: false,

                }
            }

        ]
    });
    /*loader = function(obj, removeLoader = false) {
        if (removeLoader == false) {
            obj.attr("disabled", "disabled");
            obj.addClass("gb-is-loading");
        } else {
            obj.removeAttr("disabled");
            obj.removeClass('gb-is-loading');
        }
    }*/
    saveLocalization = function() {

            let obj = $('#YK-localizationForm');
            obj.find('.invalid-feedback').remove();
            obj.find('select').removeClass('is-invalid');
            let submitBtn = obj.find('button[name="saveLocalizationBtn"]');
            loader(submitBtn);
            $.post(baseUrl + '/user/localization', obj.find('select, input').serializeArray(), function(response) {
                loader(submitBtn, true);
                if (response.status == true) {
                    toastr.success(response.message);
                    window.location.reload();
                } else {
                    toastr.error(response.message);
                }
            }).fail(function(response) {
                loader(submitBtn, true);
                let errors = response.responseJSON.errors;
                validateErrors(errors, obj);
            });
        },
        setDefaultBillingAddress = function(el, id) {
            NProgress.start();
            let formData = {
                'addr_id': id,
                'type': 'defaultDeliveryAddress',
                'addr_billing_default': $(el).is(":checked")
            }
            $.post(baseUrl + '/user/update-address-status', formData, function(response) {
                NProgress.done();
                if (response.status == false) {
                    toastr.error(response.message);
                    return;
                }
                $('[data-toggle="tooltip"]').tooltip('dispose');
                $("#YK-Address-list").empty();
                $("#YK-Address-list").html(response.data);
                $('[data-toggle="tooltip"]').tooltip();
                toastr.success(response.message);
            });
        }
    setDefaultShippingAddress = function(el, id) {
        NProgress.start();
        let formData = {
            'addr_id': id,
            'type': 'defaultShippingAddress',
            'addr_shipping_default': $(el).is(":checked")
        }
        $.post(baseUrl + '/user/update-address-status', formData, function(response) {
            NProgress.done();
            if (response.status == false) {
                toastr.error(response.message);
                return;
            }
            $('[data-toggle="tooltip"]').tooltip('dispose');
            $("#YK-Address-list").empty();
            $("#YK-Address-list").html(response.data);
            $('[data-toggle="tooltip"]').tooltip();
            toastr.success(response.message);
        });
    }
    AddressForm = function() {
        NProgress.start();
        $.get(baseUrl + '/user/add-address', function(response) {
            NProgress.done();
            $("#YK-Address-list").html(response.html);
            $('[data-toggle="tooltip"]').tooltip();
            setAddressFormValidation();
            setAddressFormTrigger();
            floatingFormFix();
        });
    }
    editAddress = function(id) {
        NProgress.start();
        $.get(baseUrl + '/user/address/' + id + '/edit', function(response) {
            NProgress.done();
            $("#YK-Address-list").empty();
            $("#YK-Address-list").html(response.html);
            $('[data-toggle="tooltip"]').tooltip();
            setAddressFormValidation();
            setAddressFormTrigger(true);
            floatingFormFix();
        });
    }
    cancelAddress = function() {
        NProgress.start();
        $.get(baseUrl + '/user/address-list', function(response) {
            NProgress.done();
            $("#YK-Address-list").html('');
            $("#YK-Address-list").html(response.data);
            $('[data-toggle="tooltip"]').tooltip();
        });
    }
    $(document).on("click", ".copyToClipboard", function() {
        let $temp = $("<input>");
        $("body").append($temp);
        let copyText = $(this).attr("data-val");
        $temp.val(copyText).select();
        document.execCommand("copy");
        $temp.remove();
        toastr.success("Copied to clipboard");
    });

    openAddOrEditReview = function(opId = 0, prodId = 0, page = '', prlId = 0) {
        window.deleteids = [];
        $.get(baseUrl + '/user/reviews/' + opId + '/' + prodId + '/' + prlId, function(response) {
            $(".YK-addOrEditReview").empty();
            if (response.status == true) {
                $("#dataModal").empty();
                $("#dataModal").html(response.data);
                var existingRating = $('#dataModal').find('.YK-selectRating').data('rating');
                if (existingRating != '') {
                    $('#dataModal').find('.YK-selectRating .YK-ratingItem:nth-child(' + Math.abs(existingRating - 6) + ')').addClass('active');
                }
                $('#dataModal').modal("show");
                new PerfectScrollbar($('#dataModal .modal-body')[0]);
                $('#dataModal').find('[name="submitReviewBtn"]').attr('data-page', page);
                setReviewFormValidation();
                setReviewFormTrigger();
                floatingFormFix();
            } else if (response.status == false) {
                toastr.error(response.message);
                setTimeout(() => {
                    window.location.href = baseUrl + '/user/reviews';
                }, 1000);
            }
        });
    }
    cancelReview = function() {
        $('#dataModal').modal("hide");
    }
    $(document).on("click", ".YK-selectRating .icon", function(event) {
        event.preventDefault();
        if ($(this).closest('.YK-selectRating').attr('data-readonly') == 1) {
            return false;
        }
        let selectRating = Math.abs($(this).index() - 5);
        $(this).closest('form').find('[name="preview_rating"]').val(selectRating);
        $(this).closest('.YK-selectRating').attr("data-rating", selectRating);
    });
    $(document).on('click', '.YK-loadMoreReviews', function(e) {
        getReviews(true);
    });
    $(document).on('click', '.YK-filterReviews a', function(e) {
        $('.YK-filterBy').text($(this).find('span').text());
        $('.YK-filterBy').attr('data-value', $(this).attr('data-value'));
        $('.YK-loadMoreReviews').attr('data-last-id', '');
        getReviews(false);
    });
    getReviews = function(loadMore = false) {
        NProgress.start();
        let thisObj = $('.YK-loadMoreReviews');
        let lastId = thisObj.attr('data-last-id');

        let formData = {
            'lastid': lastId,
            'filterby': $('.YK-filterBy').attr('data-value')
        };

        loader(thisObj);
        $.post(baseUrl + '/user/reviews/listing', formData, function(response) {
            loader(thisObj, true);
            NProgress.done();
            if (loadMore) {
                $('.YK-listReviews').append(response.data.data);
            } else {
                $('.YK-listReviews').html(response.data.data);
            }

            thisObj.attr('data-last-id', response.data.last_id);
            if (response.data.loadmore) {
                thisObj.closest('.YK-loadMoreFooter').show();
            } else {
                thisObj.closest('.YK-loadMoreFooter').hide();
            }
            $('.noRecordFound').remove();
            $('.YK-listingBody').show();
            if (response.data.norecords == true) {
                $('.YK-listingBody').remove();
                $('.YK-listing').append(response.data.data);
            }

        });
    }
    submitReview = function() {
        let obj = $('#YK-submitReviewForm');
        obj.find('.invalid-feedback').remove();
        obj.find('input,textarea').removeClass('is-invalid');
        let submitBtn = $('button[name="submitReviewBtn"]');
        loader(submitBtn);
        let page = submitBtn.attr("data-page");
        obj.find('[name="deleteids"]').val(window.deleteids.join(','));
        $.post(baseUrl + '/user/reviews', obj.find('textarea, input').serializeArray(), function(response) {
            loader(submitBtn, true);
            $('#dataModal').modal("hide");
            if (typeof page != 'undefined' && page != '') {
                if (page == 'productDetail') {
                    getReviews('', '1');
                } else if (page == 'orders') {
                    window.location.reload();
                }
            } else {
                $('.YK-listReviews').html(response.data);
            }

            if (response.status == true) {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        }).fail(function(response) {
            loader(submitBtn, true);
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });
    }
    uploadImages = function() {
        let formData = new FormData();
        var totalfiles = document.getElementById('images').files.length;
        let imageIds = $('[name="imageIds"]').val();
        let imageIdsArr = imageIds.split(",");
        imageIdsArr = imageIdsArr.filter(item => item);
        let diff = 5 - (parseInt(imageIdsArr.length) + parseInt(totalfiles));
        if (diff < 0) {
            toastr.error('Max 5 images can be uploaded');
            return false;
        }
        formData.append("preview_id", $('[name="preview_id"]').val());
        let errorCount = 0;
        for (var index = 0; index < totalfiles; index++) {
            if (document.getElementById('images').files[index].size > 5 * 1024 * 1024) {
                errorCount = errorCount + 1;
            } else {
                formData.append("preview_image[]", document.getElementById('images').files[index]);
            }
        }
        if (errorCount > 0) {
            toastr.error('Some Images with size more than 5 Mb are skipped. Please upload images with size less than 5 MB');
        }
        if (totalfiles > errorCount) {
            let addPhotosBtn = $('.yk-addPhotoBtn');
            loader(addPhotosBtn);
            $.ajax({
                url: baseUrl + '/user/reviews/images',
                type: 'POST',
                enctype: 'multipart/form-data',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    loader(addPhotosBtn, true);
                    let uploadedIds = response.data;//$.parseJSON(response.data);
                    $('[name="imageIds"]').val(imageIds + ',' + uploadedIds.join(','));
                    for (var index = 0; index < uploadedIds.length; index++) {
                        var src = baseUrl + '/yokart/image/' + uploadedIds[index] + '/small';
                        $('ul.YK-uploadedImagesPreview').find('li:last').before(`<li class="remove-able"> <img src="` + src + `" alt="">
                        <button type="button" class="remove YK-removeReviewImage" data-id="` + uploadedIds[index] + `"><i class="fas fa-times"></i></button>
                    </li>`);
                    }
                }
            });
        }
    }
    window.deleteids = [];
    $(document).on('click', '.YK-removeReviewImage', function() {
        let thisObj = $(this);
        let imageId = thisObj.attr('data-id');
        let previewid = thisObj.attr('data-previewid');
        if (previewid != '') {
            window.deleteids.push(imageId);
            thisObj.closest('li').remove();
        } else {
            $.ajax({
                url: baseUrl + '/user/reviews/images/' + imageId,
                type: 'DELETE',
                success: function() {
                    let imageIds = $('[name="imageIds"]').val();
                    let imageIdsArr = imageIds.split(",");
                    var index = imageIdsArr.indexOf(imageId.toString());
                    if (index !== -1) imageIdsArr.splice(index, 1);
                    imageIdsArr = imageIdsArr.filter(function(v) { return v !== '' });
                    $('[name="imageIds"]').val(imageIdsArr.join(','));
                    thisObj.closest('li').remove();
                }
            });
        }
    });

    $(document).on('click', '.YK-helpful', function(e) {
        /* review helpful */
        let previewId = $(this).attr('data-id');
        var thisObj = $(this);
        $.ajax({
            url: baseUrl + '/product-reviews/helpful',
            type: "post",
            data: {
                id: previewId
            },
            success: function(res) {
                if (res.status == false && res.message != '') {
                    toastr.error(res.message);
                    return;
                }
                if (res.status == false && res.message == '') {
                    $("#dataModal").empty();
                    $("#dataModal").html(res.data);
                    $('#dataModal').modal("show");
                    setTimeout(function() { floatingFormFix(); }, 200);
                }
                if (res.status == true) {
                    thisObj.parent().html(res.data);
                }
            }
        });
    });
    $(document).on('click', '.YK-nothelpful', function(e) {
        /* review not helpful */
        let previewId = $(this).attr('data-id');
        var thisObj = $(this);
        $.ajax({
            url: baseUrl + '/product-reviews/nothelpful',
            type: "post",
            data: {
                id: previewId
            },
            success: function(res) {
                if (res.status == false && res.message != '') {
                    toastr.error(res.message);
                    return;
                }
                if (res.status == false && res.message == '') {
                    $("#dataModal").empty();
                    $("#dataModal").html(res.data);
                    $('#dataModal').modal("show");
                    setTimeout(function() { floatingFormFix(); }, 200);
                }
                if (res.status == true) {
                    thisObj.parent().html(res.data);
                }
            }
        });
    });

    $(document).on('click', '.YK-reviewErrorPopup', function() {
        $('#modalReviewError').modal('show');
    });
    $(document).on('keyup', '.form-floating input, .form-floating textarea', function() {
        if ($(this).val().length > 0) {
            $(this).addClass('filled')
        } else {
            $(this).removeClass('filled')
        }
    });
    $(document).on('click', '.form-floating select', function() {
        if ($(this).val() != '') {
            $(this).addClass('filled')
        } else {
            $(this).removeClass('filled')
        }
    });

    floatingFormFix = function() {
        $('.form-floating').find('input, textarea, select').each(function() {
            if (($(this).val() != null && $(this).val().length > 0) || $(this).val() != '') {
                $(this).addClass('filled')
            } else {
                $(this).removeClass('filled')
            }
        });
    }
    floatingFormFix();

    // $(document).on('click', '.YK-productReviewList .YK-pagination', function (e) {
    //     e.preventDefault();
    //     paginateReviewProduct($(this).attr('href'));
    //     $('html, body').animate({
    //         scrollTop: $(".YK-productReviewList").offset().top
    //     }, 1000);
    // });
    ykMiniCart = function(close = false) {
        if($(".YK-MiniCartHeader").hasClass('show')) {
            return false
        }
        NProgress.start();
        $.post(baseUrl + '/cart/quick-view', function(response) {
            NProgress.done();
            $('.YK-miniCart').html(response);
        });
    }
    $('.YK-MiniCartHeader .dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });

    // paginateReviewProduct = function (pagination) {
    //     NProgress.start();
    //     $.get(pagination, function (response) {
    //         NProgress.done();
    //         $(".YK-addOrEditReview").empty();
    //         $('.YK-productReviewList').html(response.data);
    //     });
    // }
    seeReviewProduct = function() {
        NProgress.start();
        let formData = {
            'showRecords': $(".Yk-showRecords").val()
        };
        $.post(baseUrl + '/user/show-review-products', formData, function(response) {
            NProgress.done();
            $(".YK-addOrEditReview").empty();
            $('.YK-productReviewList').html(response.data);
        });
    }
    $('[data-toggle="popover"]').popover();


    getFavouriteProduct = function(loadMore = false, fiters = false) {
        NProgress.start();
        let thisObj = $('.YK-loadMoreFavourite');
        let loadId = thisObj.attr('data-last-id');
        if (fiters == true) {
            loadId = '';
        }
        let formData = {
            'sortBy': $("#YK-Sort-Name").attr('data-value'),
            'last-id': loadId
        };
        loader(thisObj);
        $.post(baseUrl + '/user/favorite', formData, function(response) {
            loader(thisObj, true);
            NProgress.done();
            if (loadMore != false) {
                $('.YK-favouriteList').append(response.data.html);
            } else {
                $('.YK-favouriteList').html(response.data.html);
            }

            thisObj.attr('data-last-id', $('.product').length);
            if ($('.product').length < response.data.total) {
                thisObj.closest('.YK-loadMoreFooter').show();
            } else {
                thisObj.closest('.YK-loadMoreFooter').hide();
            }
            $('.noRecordFound').hide();
            $('.YK-listingBody').show();
            if (response.data.total == 0) {
                $('.YK-filters').remove();
                $('.YK-listingBody').hide();
                $('.YK-listing').append(response.data.html);
            }
        });
    }
    $(document).on('click', '.YK-loadMoreFavourite', function(e) {
        getFavouriteProduct(true);
    });

    $(document).on('click', ".YK-fav-option li a", function(e) {
        $("#YK-Sort-Name").text($(this).text());
        $("#YK-Sort-Name").attr('data-value', $(this).attr('data-value'));
        getFavouriteProduct(false, true);
    });
    requestDeleteMyData = function() {
        NProgress.start();
        let submitBtn = $('button[name="YK-deleteAccountBtn"]');
        loader(submitBtn);
        $.ajax({
            url: baseUrl + '/user/requestremovegdprdata',
            type: "GET",
            success: function(res) {
                NProgress.done();
                loader(submitBtn, true);
                if (res.status == true) {
                    $("#removeUserData").empty();
                    $('#removeUserData').modal("hide");
                    toastr.success(res.message);
                    window.location.href = baseUrl;
                } else {
                    toastr.error(res.message);
                }
            }
        });
    }
    requestMyData = function() {
            NProgress.start();
            $.ajax({
                url: baseUrl + '/user/requestgdprdata',
                type: "GET",
                success: function(res) {
                    NProgress.done();
                    if (res.status == true) {
                        $("#dataModal").empty();
                        $("#dataModal").html(res.data);
                        $('#dataModal').modal("show");
                    }
                }
            });
        }
        /** Address Save */
    saveUserAddress = function() {
        let obj = $('#YK-addAddress');
        let formdata = obj.serializeArray();
        let submitBtn = obj.find('button[name="YK-saveaddress"]');
        loader(submitBtn);
        $.post(baseUrl + '/user/add-address', formdata, function(response) {
            if (response.status == false) {
                toastr.error(response.message);
                return;
            }
            toastr.success(response.message);
            $("#YK-Address-list").empty();
            $("#YK-Address-list").html(response.data);
            $('[data-toggle="tooltip"]').tooltip();
        }).fail(function(response) {
            loader(submitBtn, true);
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });
    }

    updateUserAddress = function() {
        let obj = $('#YK-addAddress');
        let submitBtn = obj.find('button[name="YK-updateaddress"]');
        loader(submitBtn);
        let formdata = obj.serializeArray();
        $.post(baseUrl + '/user/update-address', formdata, function(response) {
            if (response.status == false) {
                toastr.error(response.message);
                return;
            }
            toastr.success(response.message);
            $("#YK-Address-list").empty();
            $("#YK-Address-list").html(response.data);
            $('[data-toggle="tooltip"]').tooltip();
        }).fail(function(response) {
            loader(submitBtn, true);
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });
    }
    getStates = function() {
        $('.YK-state_id').html('');
        let country_id = $('.YK-country_id').val();
        $.post(baseUrl + '/checkout/get-states', {
            'country-id': country_id
        }, function(response) {
            Object.keys(response.data).forEach(key => {
                $('.YK-state_id').append(new Option(response.data[key], key));
            });
        });
    }



    getRewardPointsListing = function(loadData = false) {
        let thisObj = $('.YK-loadMoreRewardPoints');
        let loadId = thisObj.attr('data-last-id');
        let formData = {
            'lastid': loadId,
            'filterby': $('.YK-filterBy').attr('data-value')
        };
        NProgress.start();
        loader(thisObj);
        let rewardPointUrl = baseUrl + '/user/reward-points';
        $.post(rewardPointUrl, formData, function(response) {
            loader(thisObj, true);
            NProgress.done();
            if (parseInt(response.data.total) == 0) {
                $('.YK-notFoundTemplate').removeClass('d-none').addClass('d-flex');
                $('.YK-dataFoundTemplate').addClass('d-none');
            } else {
                $('.YK-notFoundTemplate').addClass('d-none').removeClass('d-flex');
                $('.YK-dataFoundTemplate').removeClass('d-none');
            }
            if (loadData == false) {
                $('.YK-rewardPointsTimeline').html(response.data.html);
            } else {
                $('.YK-rewardPointsTimeline').append(response.data.html);
            }
            thisObj.attr('data-last-id', response.data.id);
            if ($('.YK-rewardPointsTimeline li.YK-uniqueRecord').length != response.data.total) {
                thisObj.closest('.YK-rewardPointsFooter').show();
            } else {
                thisObj.closest('.YK-rewardPointsFooter').hide();
            }
        });
    }

    $(document).on('click', '.YK-loadMoreRewardPoints', function(e) {
        getRewardPointsListing(true);
    });

    $(document).on('click', '.YK-filterRewardPoints a', function(e) {
        $('.YK-filterBy').text($(this).find('span').text());
        $('.YK-filterBy').attr('data-value', $(this).attr('data-value'));
        $('.YK-loadMoreRewardPoints').attr('data-last-id', '');
        getRewardPointsListing(false);
    });

    $(document).on("keyup", "#askQuestion input[type='text'], #askQuestion textarea", function() {
        if ($("#subject").val() == '' || $("#description").val() == '') {
            $("button[name='submitQuestionBtn']").attr("disabled", true);
        } else {
            $("button[name='submitQuestionBtn']").attr("disabled", false);
        }
    });
    $(document).on("click", ".YK-loadMoreCoupons", function() {
        getDiscountCoupons(true);
    });

    getDiscountCoupons = function(loadMore = false) {
        NProgress.start();
        let thisObj = $('.YK-loadMoreCoupons');
        let lastId = thisObj.attr('data-last-id');
        let formData = {
            'lastid': lastId
        };
        loader(thisObj);
        $.post(baseUrl + '/user/coupons', formData, function(response) {
            loader(thisObj, true);
            NProgress.done();
            if (loadMore) {
                $('.YK-list-coupons').append(response.data.data);
            } else {
                $('.YK-list-coupons').html(response.data.data);
            }

            thisObj.attr('data-last-id', response.data.last_id);
            if (response.data.loadmore) {
                thisObj.closest('.YK-loadMoreFooter').show();
            } else {
                thisObj.closest('.YK-loadMoreFooter').hide();
            }

            $('.YK-listingBody').show();
            if (response.data.norecords == true) {
                $('.YK-loadMoreFooter').remove();
                $('.YK-listingBody').remove();
                $('.YK-listing').append(response.data.data);
            }
        });
    }

    $(document).on("click", ".YK-toggleOrderRewardPoints", function() {
        $(this).closest('.YK-uniqueRecord').find('ul.YK-orderRewardPoints').slideToggle('slow');
        if ($(this).hasClass('fas fa-angle-down')) {
            $(this).removeClass('fas fa-angle-down').addClass('fas fa-angle-up');
        } else {
            $(this).removeClass('fas fa-angle-up').addClass('fas fa-angle-down');
        }
    });

    $(document).on("click", ".YK-loadMoreInvites", function() {
        getReferralInvites(true);
    });

    getReferralInvites = function(loadMore = false) {
        NProgress.start();
        let thisObj = $('.YK-loadMoreInvites');
        let lastId = thisObj.attr('data-last-id');
        let formData = {
            'lastid': lastId
        };
        loader(thisObj);
        $.post(baseUrl + '/user/share-earn/ajax', formData, function(response) {
            loader(thisObj, true);
            NProgress.done();
            if (loadMore) {
                $('.YK-referralInvites').append(response.data.data);
            } else {
                $('.YK-referralInvites').html(response.data.data);
            }

            thisObj.attr('data-last-id', response.data.last_id);
            if (response.data.loadmore) {
                thisObj.closest('.YK-loadMoreFooter').show();
            } else {
                thisObj.closest('.YK-loadMoreFooter').hide();
            }
        });
    }
})();