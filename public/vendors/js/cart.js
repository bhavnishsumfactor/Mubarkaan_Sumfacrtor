(function () {
    var currentRequest = null;
    cartUpdate = function (event, id, qty) {
        $.post(baseUrl + '/cart/update', {
            'cartId': id,
            'qty': qty
        }, function (response) {
            if (response.status == false) {
                event.val(response.data);
                toastr.error(response.message);
                return false;
            }
            Object.keys(response.data).forEach(key => {
                $('.YK-' + key).html(response.data[key]);
            });
        });
    };
    cartItemRemove = function (event, id) {
        NProgress.start();
        $.post(baseUrl + '/cart/item-remove', {
            'id': id,
        }, function (response) {
            Object.keys(response.data).forEach(key => {
                if (key == 'shipping' || key == 'pickup') {
                    if (response.data[key] == true) {
                        $('.YK-' + key).prop("disabled", false);
                        $('.YK-' + key).next('label').removeClass('disabled');
                    } else {
                        $('.YK-' + key).next('label').addClass('disabled');
                        $('.YK-' + key).prop("disabled", true);
                    }

                    return;
                }
                $('.YK-' + key).html(response.data[key]);
                if (response.data.cartItems == 0) {
                    window.location.reload();
                }
            });
            NProgress.done();
            $(event).closest('.YK-cartloop').remove();
        });
    };
    moveToCart = function (event, id) {
        NProgress.start();
        let shipType = $('input[name="shippingType"]:checked').val();
        $.post(baseUrl + '/product/move-to-cart', {
            'id': id,
            'ship-type': shipType
        }, function (response) {
            if (response.status == false) {
                toastr.error(response.message);
                NProgress.done();
                return false;
            }
            toastr.success(response.message);
            if ($(".YK-cartProducts ul li").length == 0) {
                NProgress.done();
                $(event).closest('.YK-cartloop').remove();
                window.location.reload();
            }
            Object.keys(response.data).forEach(key => {
                if (key == 'shipping' || key == 'pickup') {
                    if (response.data[key] == true) {
                        $('.YK-' + key).prop("disabled", false);
                        $('.YK-' + key).next('label').removeClass('disabled');
                    } else {
                        $('.YK-' + key).next('label').addClass('disabled');
                        $('.YK-' + key).prop("disabled", true);
                    }

                    return;
                }
                $('.YK-' + key).html(response.data[key]);
            });
            NProgress.done();
            $(event).closest('.YK-cartloop').remove();
        });
    };

    switchShipping = function (shipType = '') {
        if (shipType == "") {
            shipType = $('input[name="shippingType"]:checked').val();
        }
        NProgress.start();
        $.post(baseUrl + '/cart/shippingUpdate/' + shipType, function (response) {
            Object.keys(response.data).forEach(key => {
                $('.YK-' + key).html(response.data[key]);
            });
            $("input[name=shippingType][value='" + shipType + "']").prop("checked", true);
            NProgress.done();

        });
    }
    savedItemRemove = function (event, id) {
        $.post(baseUrl + '/saved/product/remove', {
            'id': id,
        }, function (response) {
            $(event).closest('.YK-cartloop').remove();
            if ($(".YK-savedLater li").length == 0) {
                window.location.reload();
            }
        });
    };
    removeCoupon = function (pageType) {
        let formData = {
            'page-type': pageType
        };
        $.post(baseUrl + '/cart/coupon-remove', formData, function (response) {
            Object.keys(response.data).forEach(key => {
                $('.YK-' + key).html(response.data[key]);
            });
        });
    };
    increaseQty = function (event, cartid = '') {
        let qtyobj = $(event).closest('.YK-quantity').find('.YK-qty');
        let decobj = $(event).closest('.YK-quantity').find('.decrease');
        let qtyVal = qtyobj.val();
        if ($(decobj).hasClass('disabled')) {
            $(decobj).removeClass("disabled");
        }
        if (qtyVal >= parseInt(qtyobj.attr('max'))) {
            return false;
        };
        let newVal = parseInt(qtyVal) + 1;
        if (newVal == parseInt(qtyobj.attr('max'))) {
            $(event).addClass("disabled");
        }
        qtyobj.val(newVal);
        if (cartid != '') {
            cartUpdate(qtyobj, cartid, newVal);
        }
    }

    decreaseQty = function (event, cartid = '') {
        let qtyobj = $(event).closest('.YK-quantity').find('.YK-qty');
        let incobj = $(event).closest('.YK-quantity').find('.increase');
        let qtyVal = qtyobj.val();
        if ($(incobj).hasClass('disabled')) {
            $(incobj).removeClass("disabled");
        }
        if (qtyVal <= parseInt(qtyobj.attr('min'))) {
            return false;
        };
        let newVal = parseInt(qtyVal) - 1;
        if (newVal == parseInt(qtyobj.attr('min'))) {
            $(event).addClass("disabled");
        }
        qtyobj.val(newVal);
        if (cartid != '') {
            cartUpdate(qtyobj, cartid, newVal);
        }
    }
    removeReward = function () {

        $.post(baseUrl + '/checkout/remove-reward-points', function (response) {
            if (response.status == false) {
                toastr.error(response.message);
                return;
            }
            $("#YK-rewardPoints").show();
            $('input[name="points"]').val('');
            $('.YK-PaymentMethod').show();
            $('.nav-payments').show();
            $('.earn-points').show();
            $('#YK-paymentGateway').val("Stripe");
            $('.yk-yayBlock').hide();
            Object.keys(response.data).forEach(key => {
                $('.YK-' + key).html(response.data[key]);
            });
        })


    }

    addTocart = function (id, event = '', listing = true, fav = 0, prodOptionCode = 0) {
        
        let redirect = false;
        let generateVar = false;
        let formData = {
            'prodId': id,
            'optionCode': prodOptionCode
        };

        let submitBtn = $('button[name="YK-addToCartBtn"]');
        loader(submitBtn);
        if (event) {
            let shippingType = $("input[name='deliveryType']:checked").val();
            let qty = $(event).closest('.js-add-to-cartloop').find('.YK-qty').val();
            if (qty == '') {
                qty = 1;
            }
            Object.assign(formData, {
                'qty': qty,
                'shippingType': shippingType,
            });
            generateVar = true;
        }
        if (listing == false) {
            redirect = true;
        }
        Object.assign(formData, {
            'generateVar': generateVar,
        });
        if (fav == 1) {
            updateFavourite(event, id, varientCode, fav);
        }
        $.post(baseUrl + '/product/add-to-cart', formData, function (response) {
            NProgress.done();

            if (response.status == false) {
                toastr.error('Errors', response.message);
                loader(submitBtn, true);
                return false;
            }
            events.addToCart({
                content_name: response.data.currentItem.content_name,
                currency: response.data.currentItem.currency,
                value: response.data.currentItem.value
            });
            if (redirect == true) {
                setTimeout(function () {
                    window.location.replace(baseUrl + '/cart');
                }, 1000);
            }else{
                $('.YK-shoppingCart').click();
       
            }
            loader(submitBtn, true);
            toastr.success(response.message);
            Object.keys(response.data).forEach(key => {
                $('.YK-' + key).html(response.data[key]);
            });
        });
    };

    varifyShipping = function (productId) {
        let obj = $('#YK-zipcode');
        obj.find('input').removeClass('is-invalid');
        obj.find('.invalid-feedback').remove();

        let varientCode = [];
        if ($(".YK-varient input[type='radio']").length != 0) {
            varientCode.push(productId);
            $(".YK-varient input[type='radio']:checked").each(function () {
                varientCode.push($(this).val());
            });
        }
        let formData = obj.serializeArray();
        formData.push({
            name: 'prod-id',
            value: productId
        }, {
            name: 'varient-code',
            value: varientCode
        });
        $.post(baseUrl + '/product/validate-shipping', formData, function (response) {
            if (response.status == false) {
                toastr.error(response.message);
                return;
            }
            $('.YK-addTocartHtml').html(response.data.addToCartHtml);

        }).fail(function (response) {
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });
    };
    getTimeSlots = function (date, address) {

        $.post(baseUrl + '/checkout/pickup-time-slots', {
            'date': date,
            'addrerss': address
        }, function (response) {
            Object.keys(response.data).forEach(key => {
                $('.YK-' + key).html(response.data[key]);
            });
        });
    };
    addNewAddress = function (event) {
        if ($('.YK-oldAddress').hasClass('active')) {
            $('.YK-newAddress').show();
            $('.YK-oldAddress').removeClass('active');
            $('.YK-oldAddress').hide();
            $(event).find('.YK-addTxt').html($(event).data('old'));
        } else {
            $('.YK-newAddress').hide();
            $('.YK-oldAddress').addClass('active');
            $('.YK-oldAddress').show();
            $(event).find('.YK-addTxt').html($(event).data('new'));
        }

    };
    setPickupAddress = function (id) {
        let addressobj = $('input[name="pickupValues"]:checked');
        let addressDay = $('input[name="pickupDays"]:checked');
        let addressTime = $('input[name="pickupTime"]:checked');
        let selectedDate = $('#selectedDate').val();
        let address = addressobj.closest('li').find('.YK-address-detail').html();
        $('.YK-pickupAddress').html(address);
        $.post(baseUrl + '/checkout/set-pickup-address', {
            'address-id': addressobj.val(),
            'date': selectedDate,
            'day-id': addressDay.val(),
            'time': addressTime.val()
        }, function (response) {
            $("#dataModal").empty();
            $('#dataModal').modal("hide");
        });
    };
    $('a.askquestion').on('click mousedown mouseup', function () {
        $(this).trigger("mouseleave");
    });
    askQuestions = function (id, code, event = '') {
        NProgress.start();
        $.get(baseUrl + '/product/' + id + '/'+code+'/ask-questions', function (response) {
            NProgress.done();
            if (response.status == true) {
                $("#dataModal").empty();
                $("#dataModal").html(response.data);
                $('#dataModal').modal("show"); 
                setTimeout(function () {
                    floatingFormFix();
                }, 200); 
            }
        });
    };
    updateFavourite = function (event = '', id, code = '', fav = 0) {
        if (fav == 1) {
            $(event).closest('.YK-productListing').remove();
        }
        NProgress.start();
        $.post(baseUrl + '/product/favourite', {
            'id': id,
            'code': code
        }, function (response) {
            NProgress.done();
            if (response.status == true) {
                if ($(event).closest('button').hasClass('active')) {
                    $(event).closest('button').removeClass('active');
                } else {
                    $(event).closest('button').addClass('active');
                    events.addToWishList({
                        content_name: response.data.product.prod_name,
                        currency: response.data.product.currency,
                        value: response.data.product.prod_price
                    });
                }
                if ($('.YK-favAjaxlist .YK-favouriteList').length) {
                    if ($('.YK-favouriteList .YK-productListing').length == 0) {
                        $('.YK-listingBody').remove();
                        $('.YK-favAjaxlist .noRecordFound').show();
                    }
                }
            }
            if (response.status == false) {
                $("#dataModal").empty();
                $("#dataModal").html(response.data);
                $('#dataModal').modal("show");
                setTimeout(function () {
                    floatingFormFix();
                }, 200);
            }
        })
    };
    savedForLater = function (event, id, cartId = 0, code = '') {
        NProgress.start();
        $.post(baseUrl + '/product/saved-for-later  ', {
            'id': id,
            'code': code
        }, function (response) {
            if (response.status == true) {
                if (cartId != 0) {
                    cartItemRemove(event, cartId);
                }

                toastr.success(response.message);
                Object.keys(response.data).forEach(key => {
                    $('.YK-' + key).html(response.data[key]);
                });
                NProgress.done();
                $(event).closest('.YK-cartloop').remove();
                if ($('.YK-tempProducts').find('.YK-cartloop').length == 0) {
                    $('.YK-tempProducts').html('');
                }
            }
            if (response.status == false) {
                $("#dataModal").empty();
                $("#dataModal").html(response.data);
                $('#dataModal').modal("show");
                setTimeout(function () {
                    floatingFormFix();
                }, 200);
                NProgress.done();
            }
        })
    };
    deleteTempProducts = function () {
        NProgress.start();
        $.post(baseUrl + '/error/products/remove/', function (response) {
            toastr.success(response.message);
            $('.YK-tempProducts').html('');
            NProgress.done();
        });
    };
    giftItemMessage = function (event, id) {

        let obj = $(event).closest('li').find('.YK-GiftItem');
        if (obj.hasClass('active')) {
            obj.removeClass('active');
            $('#YK-cartId').val(id);
            updateGiftItem(false);
        } else {
            $.get(baseUrl + '/cart/gift-messages/' + id, function (response) {
                $("#dataModal").empty();
                $("#dataModal").html(response);
                $('#dataModal').modal("show");
            });
        }


    };
    updateGiftItem = function (validate = true) {
        let obj = $('#Yk-GiftWrap-From');
        obj.find('input').removeClass('is-invalid');
        obj.find('.invalid-feedback').remove();

        let formData = obj.serializeArray();
        $.post(baseUrl + '/cart/gift-items/' + validate, formData, function (response) {
            if (response.status == true) {
                $('#dataModal').modal("hide");
                Object.keys(response.data).forEach(key => {
                    $('.YK-' + key).html(response.data[key]);
                });
                obj.find("input[type=text], textarea").val("");
                if (validate == true) {
                    $('#YK-GiftItem-' + $('#YK-cartId').val().split('|').join('-')).addClass('active');

                }
            }
        }).fail(function (response) {
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });
    };

    getFilters = function (filterType = '', filterValue = '', disableTags = '') {
        let searchKeyword = $('input[name="s"]').val();
        let filters = $('#YK-filtersForm').serializeArray();
        filters.push({
            name: 'search-items',
            value: searchKeyword
        });
        if (filterType && filterValue) {
            filters.push({
                name: filterType,
                value: filterValue
            }, {
                name: "searchedBy",
                value: disableTags
            });
        }
        $.post(baseUrl + '/product/filters', filters, function (response) {
            $('.YK-sidebar').html(response);
        });
    };


    filterProducts = function ($event = '', filter = false, pagination = '', optionType = '') {
        let searchKeyword = $('input[name="s"]').val();

        if ($event && filter == false) {
            $($event).closest('li.YK-varient').toggleClass('is--active');
        }
        var searchedId = 0;
        var lastFilter = '';
        if ($event && filter == true) {
            searchedId = $($event).attr('data-id');
            if ($($event).prop("checked") == false) {
                lastFilter = $($event).attr('data-type');
                if (lastFilter == "brands") {

                    $('input[name="brands[' + $($event).val() + ']"]').prop("checked", false);
                } else {
                    $('input[id="option-' + $($event).attr('data-title') + '"').prop("checked", false);
                }

            }
        }

        if (optionType) {
            lastFilter = optionType;
        }
        let rangeValue = '';
        if (parseInt($('input[name="priceFilterMinValue"]').val()) != $('input[name="priceFilterMinValue"]').attr('data-defaultvalue') || parseInt($('input[name="priceFilterMaxValue"]').val()) != $('input[name="priceFilterMaxValue"]').attr('data-defaultvalue')) {
            rangeValue = [$('input[name="priceFilterMinValue"]').val(), $('input[name="priceFilterMaxValue"]').val()];
        }

        let postUrl = baseUrl + '/products';
        if (pagination != "") {
            postUrl = pagination;
        }

        let filters = $('#YK-filtersForm').serializeArray();


        filters.push({
            name: 'showRecords',
            value: $('.Yk-showRecords').val()
        }, {
            name: 'listview',
            value: $('.YK-listview.active').attr('data-num')
        }, {
            name: 'moreFilters',
            value: filter
        }, {
            name: 'sortBy',
            value: $('select[name="sortBy"]').val()
        }, {
            name: 'price-range',
            value: rangeValue
        }, {
            name: 'search-items',
            value: searchKeyword
        }, {
            name: 'search-id',
            value: searchedId
        }, {
            name: 'last-filters',
            value: lastFilter
        });
        if (filter == true) {
            let moreFilters = $('#YK-morefilters').serializeArray();
            filters = filters.concat(moreFilters);
        }


        currentRequest = $.ajax({
            type: 'POST',
            data: filters,
            url: postUrl,
            beforeSend: function () {
                if (currentRequest != null) {
                    currentRequest.abort();
                }
            },
            success: function (response) {
                Object.keys(response).forEach(key => {
                    $('.YK-' + key).html(response[key]);
                });

                if (productBackground == 1) {
                    $('.js-fillcolor').fillColor();
                }
            }
        });

    };
    updateQueryStringParameter = function (url, key, value) {
        let re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        let separator = url.indexOf('?') !== -1 ? "&" : "?";
        if (url.match(re)) {
            return url.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            return url + separator + key + "=" + value;
        }
    };
    $(document).on('click', '.YK-productSection .YK-pagination', function (e) {
        e.preventDefault();
        filterProducts('', '', $(this).attr('href'));
        $('html, body').animate({
            scrollTop: $(".YK-listingRightContainer").offset().top - 100
        }, 1000);
    });
    getVarient = function (varientCode) {
  
        let productId = $('.YK-productId').val();
       
        $.post(baseUrl + '/product/product-varient-price', {
            'varientCode': varientCode,
            'product-id': productId,
        }, function (response) {
            $('.YK-price').html(response.data.price);
            if (response.data.sliderHtml) {
                destroyCarousel();
                $('.YK-image-slider').html(response.data.sliderHtml);
            }
            $('.YK-addTocartHtml').html(response.data.addToCartHtml);
            if (response.data.oldprice == 0) {
                $('.YK-oldprice').html('');
            } else {
                $('.YK-oldprice').html('<del>' + response.data.oldprice + '</del>');
            }
            $('.YK-oldprice del').html(response.data.oldprice);
            window.__sharethis__.initialize();
        });
    };
    destroyCarousel = function ($event) {
        if ($('.slider-for').hasClass('slick-initialized')) {
            $('.slider-for').slick('destroy');
        }
    };
   /*$(document).on('change', '.js-selectCountry', function () {
        let thisObj = $(this).closest('.YK-checkoutForm');
        let country_id = thisObj.find('.YK-country_id').val();
        getStates("", "", country_id);
        $.post(baseUrl + '/checkout/get-states', {
            'country-id': country_id
        }, function (response) {
            $('.YK-state_id').html('');
            Object.keys(response.data).forEach(key => {
                thisObj.find('.YK-state_id').append(new Option(response.data[key], key));
            });
        });
    });*/
    
    getStates = function (type = "", digital = "", thisObj = '') {
    
        let country_id = $('.YK-country_id').val();
        if(thisObj){
            country_id = $(thisObj).val();
        }
        $.post(baseUrl + '/checkout/get-states', {
            'country-id': country_id
        }, function (response) {
            $('.YK-state_id').html('');
            Object.keys(response.data).forEach(key => {
                $('.YK-state_id').append(new Option(response.data[key], key));
            });
        });
        calculateDigitalTax('', type, digital);      
    };

    calShippingTax = function () {
        $.post(baseUrl + '/checkout/cal-shipping-tax', {
            'country-id': $('.YK-country_id').val(),
            'state-id': $('.YK-state_id').val()
        }, function (response) {
            Object.keys(response).forEach(key => {
                $('.YK-' + key).html(response[key]);
            });
        });
    };
    $(document).on('click', '.YK-timeSlots li', function () {
        $("#checkoutProceed").removeClass("disabled");
        if(!(typeof $('input[name="pickupSlot"]:checked').val() === "undefined")) {
            $("#checkoutProceed").attr("disabled", false);
        }
    });
    saveAddress = function (type) {
        let continueBtn = $('button[id="checkoutProceed"]');
        loader(continueBtn);
        let obj = $('#YK-' + type);
        obj.find('input').removeClass('is-invalid');
        $('.YK-shipping-lists').removeClass('is-invalid');
        obj.find('.invalid-feedback').remove();
        let address = obj.find('input,select').serializeArray();
        let selectedAddress = false;
        let addressForm = false;
        if ($('.YK-oldAddress').hasClass('active')) {
            selectedAddress = true;
        }
        if ($('.yk-address-form').hasClass('d-block')) {
            addressForm = true;
        }
        address.push({
            name: 'selectedAddress',
            value: selectedAddress
        }, {
            name: 'addressForm',
            value: addressForm
        })
        $.post(baseUrl + '/checkout/address/save', address, function (response) {

            loader(continueBtn, true);
            if (response.status == false) {
                toastr.error(response.message);
                return;
            }
            $('input[name="edited_addr_id"]').val(response.data.addressId);
            $('input[name="selected_address_id"]').val(response.data.addressId);
            if(isDigitalProduct == "1"){
                paymentSection(response.data.addressId);
            } else {
                $("#deliveryStep").removeClass('is-active').addClass('is-active');
                $('.YK-deliveryStep').removeClass('active');
                $('.yk-agreeTerms').hide();
                $('.YK-shippingInfo').html(response.data.shippingInfo);
                $('.YK-shippingInfo').addClass('active');
                $('.YK-cartInfo').html(response.data.cartInfo);
                if (response.data.shippingCount > 0) {
                    $("#checkoutProceed").addClass("disabled");
                    $("#checkoutProceed").attr("disabled", true);
                }
            }
        }).fail(function (response) {
            loader(continueBtn, true);
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });
    }
    productImages = function () {
        let varientId = $(".Yk-color-varient:checked").val();
        let productId = $('.YK-productId').val();
        $.post(baseUrl + '/product/get-images', {
            'product-id': productId,
            'varient-id': varientId,
        },
        function (response) {
            $('.YK-image-slider').html(response);
            if (productBackground == 1) {
                $('.js-fillcolor').fillColor();
            }
        });
    }

    cartSummary = function () {
        $.get(baseUrl + '/checkout/cart-summary', function (response) {
            $('.YK-cartInfo').html(response);
        });
    }
    updateShipping = function () {
        $('.YK-shippingErrors').find('.invalid-feedback').remove();
        $('.YK-shipping-lists').removeClass('is-invalid');
        let shippingRecords = [];
        $('.YK-shippings').each(function () {
            let shippingKey = $(this).find('.YK-selectedShipping').find(':selected').data('key');
            let shippingName = $(this).find('.YK-selectedShipping').find(':selected').data('name');
            let shippingVal = $(this).find('.YK-selectedShipping').val();
            if (shippingVal) {
                shippingRecords.push({
                    'name': 'shipping-attributes[' + shippingKey + '][' + shippingName + ']',
                    'value': shippingVal
                }, {
                    'name': 'shipping-val[]',
                    'value': shippingVal
                });
            }
        });

        $.post(baseUrl + '/checkout/shipping-update', shippingRecords, function (response) {
            $('.YK-cartInfo').html(response);
            $("#checkoutProceed").attr("disabled", false);
            $("#checkoutProceed").removeClass("disabled");
        });
    }
    var jsPaymentClient;
    var component;
    $.getScript('https://2pay-js.2checkout.com/v1/2pay.js', function() {  
        jsPaymentClient = new  TwoPayClient(twoCheckoutSellerId);    
        component = jsPaymentClient.components.create('card');
    });

    twoCheckoutForm = function(){
        setTimeout(() => {
            $("#YK-saveCard").html("");
            let nameDiv = '<div class="row"><div class="col-md-12"><div class="form-group form-floating__group">';
            nameDiv += '<input type="text" name="name" class="form-control form-floating__field" placeholder="" id="name">';
            nameDiv += '<label class="form-floating__label">Card Holder Name</label>';
            nameDiv += '</div></div></div>';
            $("#YK-saveCard").prepend(nameDiv);
            $('#checkoutProceed').removeAttr('disabled');
            component.mount('#YK-saveCard');
        }, 1000);        
    }     
    twoCheckoutRequest = function(orderData) {
        const billingDetails = {
            name: document.querySelector('#name').value
        };      
        jsPaymentClient.tokens.generate(component, billingDetails).then((response) => {
            orderData.push({
                name: 'token',
                value: response.token
            });
            placeOrderRequest(orderData);
        }).catch((error) => {
            console.error(error);
        });
    }
    placeOrder = async function (redirect = 0) {
        let continueBtn = $('button[id="checkoutProceed"]');
        if (redirect == 0) {
            loader(continueBtn);
        }
        let paymentType = $('#YK-paymentGateway').val();
        if (paymentType == '') {
            toastr.error('please select payment method');
            return false;
        }
        $('#YK-' + paymentType).find('input').removeClass('is-invalid');
        $('#YK-' + paymentType).find('.invalid-feedback').remove();
        let obj = $('#YK-placeOrder');
        obj.find('input,select').removeClass('is-invalid');
        obj.find('.invalid-feedback').remove();

        let orderData = obj.serializeArray();

        let selectedCards = false;
        if ($('.YK-oldCards').hasClass('active') && redirect == 0) {
            selectedCards = true;
        }
        orderData.push({
            name: 'selectedCards',
            value: selectedCards
        }, {
            name: 'redirect',
            value: redirect
        });
        if(paymentType=='TwoCheckout'){
            twoCheckoutRequest(orderData);
        }else{
            placeOrderRequest(orderData, obj);
        }                  
    }
    placeOrderRequest = function(orderData, obj=''){
        let continueBtn = $('button[id="checkoutProceed"]');
        $.post(baseUrl + '/order', orderData, function (response) {
            events.purchase({
                currency: response.data.currency,
                value: response.data.value
            });
            if (response.status == false) {
                if (response.data.method == "redirect") {
                    window.location.href = response.data.url;
                    return;
                } else {
                    toastr.error(response.message);
                    window.location.href = response.data.url;
                    return;
                }
            }
            $("#yk-step3").removeClass('is-active').addClass('is-active');
            orderSuccessPage(response.data.orderId);
        }).fail(function (response) {
            loader(continueBtn, true);
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
            Object.keys(errors).forEach(key => {
                $('input[name="[' + key + ']"]').addClass('is-invalid');
                $('input[name="[' + key + ']').closest('div').append('<div class="invalid-feedback">' + errors[key][0] + '</div>');

            });
        });
    }
    validateErrors = function (errors, obj) {
        Object.keys(errors).forEach(key => {

            if (!obj.find('input[name="' + key + '"], textarea[name="' + key + '"]').hasClass('is-invalid')) {
                obj.find('input[name="' + key + '"], textarea[name="' + key + '"], select[name="' + key + '"]').addClass('is-invalid');
                obj.find('input[name="' + key + '"], textarea[name="' + key + '"], select[name="' + key + '"]').closest('div').append('<div class="invalid-feedback">' + errors[key][0] + '</div>');
                obj.find('.invalid-feedback').show();
            }
        });
    }
    couponApplied = function (pageType) {


        let obj = $('#YK-coupon-code');
        obj.find('input,select').removeClass('is-invalid');
        obj.find('.invalid-feedback').remove();
        let formData = {
            'page-type': pageType,
            'coupon-code': obj.find('[name="coupon-code"]').val(),
        };
        $.post(baseUrl + '/cart/coupon', formData, function (response) {
            if (response.status == false) {
                toastr.error(response.message);
                return;
            }
            $('#dataModal').modal("hide");
            Object.keys(response.data).forEach(key => {
                $('.YK-' + key).html(response.data[key]);
            });
        }).fail(function (response) {
            let errors = response.responseJSON.errors;

            validateErrors(errors, obj);

        });
    }
    applyRewardPoints = function () {

        let obj = $('#YK-placeOrder');
        obj.find('input,select').removeClass('is-invalid');
        obj.find('.invalid-feedback').remove();
        $.post(baseUrl + '/checkout/apply-reward-points', {
            'points': obj.find('[name="points"]').val()
        }, function (response) {
            if (response.status == false) {
                toastr.error(response.message);
                return;
            }
            if (response.data.total == 0) {
                $('.YK-PaymentMethod').hide();
                $('.nav-payments').hide();
                $('#YK-paymentGateway').val("rewards");
                $('.yk-yayBlock').show();
            }
            if (response.data.canEarn == '0') {
                $(".earn-points").hide();
            }
            delete response.data.total;
            delete response.data.canEarn;
            $("#YK-rewardPoints").hide();
            Object.keys(response.data).forEach(key => {
                $('.YK-' + key).html(response.data[key]);
            });

            $('#checkoutProceed').prop('disabled', false);
        }).fail(function (response) {
            let errors = response.responseJSON.errors;

            validateErrors(errors, obj);

        });
    }
    getCoupons = function (pageType) {
        let formData = {
            'page-type': pageType
        };
        $.post(baseUrl + '/cart/get-coupons', formData, function (response) {
            if (response.status == true) {
                $("#dataModal").empty();
                $("#dataModal").html(response.data);
                $('#dataModal').modal("show");
                setApplyCouponValidation();
                setApplyCouponTrigger();
            }
        });
    };
    selectedCoupon = function () {
        $('[name="coupon-code"]').val($('[name="coupon"]:checked').val());
        $("button[name='ykApplyCoupon']").removeAttr('disabled');
    }
    billingAddress = function () {
        let billingAddress = $('input[name="billing-address"]:checked').val();
        if (billingAddress) {
            $('#YK-billingAddress').html("");
            $('#YK-billingAddress').hide();
            return false;
        }
        NProgress.start();

        $.get(baseUrl + '/checkout/get-billing-address', function (response) {
            if (response.status == true) {

                $('#YK-billingAddress').html(response.data.html);
                $('YK-cartInfo').html(response.data.cartInfo);
                $('#YK-billingAddress').show();
                floatingFormFix();
                $("#YK-billingAddress").find('input[name="addr_id"]').val(response.data.defaultAddressId);
                $("#checkoutProceed").attr("disabled", false);
                NProgress.done();
            }
        });
    }
    calculateDigitalTax = function (addressId = '', type = '', digital = false) {
        if(digital == false){
            return;
        }
        let countryId = ''; 
        let stateId = '';
        if(addressId == "" && type == ""){
            countryId = $('.YK-country_id').val();
            stateId = $('.YK-state_id').val()
        }
       
        let formData = {
            'address-id': addressId,
            'country-id': countryId,
            'state-id': stateId,
        };
        $.post(baseUrl + '/checkout/update-tax', formData,function (response) {
            if (response.status == true) {
                Object.keys(response.data).forEach(key => {
                    $('.YK-' + key).html(response.data[key]);
                });
                    
            }
        });
    }
    previousStep = function (current, previous, previousToPrevious = "") {
        let addrid = $('.YK-selectedAddress').attr('data-addrid');
        $('.YK-' + current).removeClass('active');
        $('.YK-' + current).html('');
        if (previous != "deliveryStep") {
            $('.YK-' + previous).addClass('active');
            $("#" + current).removeClass('is-active');
        }        
        
        if ($('.YK-' + previous).hasClass('active')) {
            $('#' + previous).removeClass('is-active').addClass('is-active');
        }
        if (previousToPrevious != "") {
            $('.YK-' + previousToPrevious).removeClass('active');
        }

        if ($('.yk-address-form').hasClass('d-block')) {
            $('#YK-shippingAddress').find('[name="edited_addr_id"]').val(addrid);
        }
        if (previous == "deliveryStep") {
            checkoutFirstStep();
            // editCheckoutAddress($('input[name="edited_addr_id"]').val());
            // $('.yk-agreeTerms').show();
        }
        $('#checkoutProceed').text('Continue');
        $('#checkoutProceed').removeClass('disabled');
        $('#checkoutProceed').attr('disabled', false);
    }
    addNewCard = function () {
            $.get(baseUrl + '/checkout/add-card-popup', function (response) {
                if (response.status == true) {
                    let attributeValue = $('.nav-payments').find('li a.active').attr('href');
                    let creditCardObj = $('.YK-paymentInfo .YK-PaymentMethod ' + attributeValue);
                    creditCardObj.find('.YK-oldCards').hide();
                    creditCardObj.find('.YK-oldCards').removeClass('active');
                    creditCardObj.find('.YK-addNewCardForm').html(response.data);
                    creditCardObj.find('.YK-addNewCardForm').show();
                    $('.YK-addNewCardButton').hide();
                    $('.YK-discardNewCardButton').show();
                    setCheckoutCardFormValidation();
                    setCheckoutCardFormValidationTrigger();
                } else {
                    toastr.error(response.message);
                }
            });
        },
        displayCardListing = function () {
            let creditCardObj = $('.YK-paymentInfo .YK-PaymentMethod #credit');
            creditCardObj.find('.YK-oldCards').show();
            creditCardObj.find('.YK-oldCards').addClass('active');
            creditCardObj.find('.YK-addNewCardForm').html('');
            creditCardObj.find('.YK-addNewCardForm').hide();
            $('.YK-discardNewCardButton').hide();
            $('.YK-addNewCardButton').show();
        },
        saveNewCard = function () {
            let obj = $("#YK-saveCard");
            obj.find('input').removeClass('is-invalid');
            $('.YK-shipping-lists').removeClass('is-invalid');
            obj.find('.invalid-feedback').remove();
            let card = obj.find('input').serializeArray();
            $.post(baseUrl + '/user/savecard', card, function (response) {
                if (response.status == false) {
                    toastr.error(response.message);
                    return;
                }
                $("#dataModal").empty();
                $('#dataModal').modal("hide");
                $("#step1").removeClass('is-active').addClass('is-active');
                $('.YK-deliveryStep').removeClass('active');
                $('.YK-shippingInfo').html(response.data.shippingInfo);
                $('.YK-shippingInfo').addClass('active');
                $('.YK-cartInfo').html(response.data.cartInfo);
            }).fail(function (response) {
                let errors = response.responseJSON.errors;
                validateErrors(errors, obj);
            });
        },
        paymentSection = function (id) {
            let continueBtn = $('button[id="checkoutProceed"]');
            loader(continueBtn);
            let shippings = $('.YK-shippings').find('select').serializeArray();
            let address = $('input[name="pickupValues"]:checked').val();
            let pickupSlot = $('input[name="pickupSlot"]:checked').val();
            let selectedDate = $('#selectedDate').val();

            shippings.push({
                name: 'shippings',
                value: shippings.length
            }, {
                name: 'address',
                value: id
            }, {
                name: 'shippings',
                value: shippings.length
            }, {
                name: 'shipping-address',
                value: address
            }, {
                name: 'pickupSlot',
                value: pickupSlot
            }, {
                name: 'selectedDate',
                value: selectedDate
            });

            $.post(baseUrl + '/checkout/payment-section', shippings, function (response) {
                loader(continueBtn, true);
                if (response.status == false) {
                    toastr.error(response.message);
                    return;
                }

                if(isDigitalProduct == "1"){
                    $("#deliveryStep").removeClass('is-active').addClass('is-active');
                    $('.YK-deliveryStep').removeClass('active');
                    $('.yk-agreeTerms').hide();
                }

                $("#shippingInfo").removeClass('is-active').addClass('is-active');
                $('.YK-shippingInfo').removeClass('active');

                $('.YK-paymentInfo').html(response.data.html);
                $('.YK-total').html(response.data.total);
                $('.YK-paymentInfo').addClass('active');
                $("#checkoutProceed").text("Place Order");
                $('.YK-addNewCardForm').hide();
                if (typeof $("input[name='card-id']:checked").val() == 'undefined' && $(".YK-oldAddress li").length > 0) {
                    $("input[name='addr_id']").eq(0).prop('checked', true);
                }
                if ($(".payment-card").length > 0) {
                    $("#checkoutProceed").attr('disabled', false);
                } else {
                    $("#checkoutProceed").attr('disabled', true);
                }
                if (response.data.totalPrice == 0) {
                    $('.YK-PaymentMethod').hide();
                    $('.nav-payments').hide();
                    $('#YK-paymentGateway').val("rewards");
                }
            });
        }
    cardFormValidate = function (paymentType) {
        var cardValid = 0;
        let cardNumber = $('input[name="' + paymentType + '[number]"]');

        cardNumber.validateCreditCard(function (result) {
            if (result.valid) {
                cardValid = 1;
            } else {
                cardValid = 0;
            }
        });
        let cardName = $('input[name="' + paymentType + '[name]"]');
        let expMonth = $('input[name="' + paymentType + '[exp_month]"]');
        let expYear = $('input[name="' + paymentType + '[exp_year]"]');
        let cardCvv = $('input[name="' + paymentType + '[cvv]"]');
        var currentYear = new Date().getFullYear();
        let regName = /^[a-z ,.'-]+$/i;
        let regMonth = /^01|02|03|04|05|06|07|08|09|10|11|12$/;
        let regCVV = /^[0-9]{3,3}$/;
        if (cardValid == 0) {
            cardNumber.addClass('is-invalid');
            cardNumber.closest('div').append('<div class="invalid-feedback">Card number not valid</div>');
            return false;
        } else if (!regMonth.test(expMonth.val())) {
            expMonth.addClass('is-invalid');
            expMonth.closest('div').append('<div class="invalid-feedback">Card month not valid</div>');
            return false;
        } else if (expYear.val() == '' || currentYear > expYear.val()) {
            expYear.addClass('is-invalid');
            expYear.closest('div').append('<div class="invalid-feedback">Card year not valid</div>');
            return false;
        } else if (!regCVV.test(cardCvv.val())) {
            cardCvv.addClass('is-invalid');
            cardCvv.closest('div').append('<div class="invalid-feedback">Card cvv not valid</div>');
            return false;
        } else if (!regName.test(cardName.val())) {
            cardName.addClass('is-invalid');
            cardName.closest('div').append('<div class="invalid-feedback">Card name is required</div>');
            return false;
        } else {
            return true;
        }
    }
    validateQty = function (event) {
        let obj = $(event);
        let val = parseInt(obj.val());
        if (val > obj.attr('max')) {
            $(event).val(obj.attr('max'));
            return false;
        } else if (val < obj.attr('min')) {
            $(event).val(obj.attr('min'));
            return false;
        }
    }
    orderSuccessPage = function (id) {
        window.location.replace(baseUrl + '/order/success/' + id);
    }
    $(document).ready(function () {
        $("#checkoutBtn").attr("disabled", "disabled");
    });
    removeFiltersByTags = function (type, value) {
        if (type == "option") {
            $('input[id="' + type + '-' + value + '"').prop("checked", false);
        } else {
            $('input[name="' + type + '[' + value + ']"]').prop("checked", false);
        }
        filterProducts();
    }
    removePriceFilter = function () {
        let minPriceObj = $('input[name="priceFilterMinValue"]');
        let maxPriceObj = $('input[name="priceFilterMaxValue"]');
        minPriceObj.val(minPriceObj.attr('data-defaultvalue'));
        maxPriceObj.val(maxPriceObj.attr('data-defaultvalue'));

        stepsSlider.noUiSlider.set([minPriceObj.attr('data-defaultvalue'), maxPriceObj.attr('data-defaultvalue')]);

        //
        filterProducts();
    }
    clearFilters = function () {
        $('#YK-filtersForm').find('input[type="checkbox"]').prop("checked", false);
        removePriceFilter();
    }
    loadFilters = function (type, id = 0, searchType = '') {
        let searchKeyword = $('input[name="s"]').val();

        let filters = $('#YK-filtersForm').serializeArray();

        filters.push({
            name: 'search-items',
            value: searchKeyword
        }, {
            name: 'search-id',
            value: id
        }, {
            name: 'search-type',
            value: searchType
        });
        $.post(baseUrl + '/products/filters/' + type, filters, function (response) {
            if (response.status == true) {
                $("#dataModal").empty();
                $("#dataModal").html(response.data);
                $('#dataModal').modal("show");
            } else {
                toastr.error(response.message);
            }
        });
    }
    twoCheckoutPaymentRequest = function(orderId, paymentMethod, formData) {
        const billingDetails = {
            name: document.querySelector('#name').value
        };      
        jsPaymentClient.tokens.generate(component, billingDetails).then((response) => {
            formData.push({
                name: 'token',
                value: response.token
            });
            makeOrderPaymentRequest(orderId, paymentMethod, formData);
        }).catch((error) => {
            console.error(error);
        });
    }
    makeOrderPayment = function (orderId, paymentMethod, amount) {
        let obj = $('#YK-paymentForm');
        obj.find('.invalid-feedback').remove();
        obj.find('input, textarea').removeClass('is-invalid');
        let formData = obj.serializeArray();
        formData.push({
            name: 'amount',
            value: amount
        });
        if(paymentMethod=='TwoCheckout'){
            twoCheckoutPaymentRequest(orderId, paymentMethod, formData);
        }else{
            makeOrderPaymentRequest(orderId, paymentMethod, formData, obj);    
        }    
    }
    makeOrderPaymentRequest = function(orderId, paymentMethod, formData, obj=''){
        
        $.post(baseUrl + '/make/' + paymentMethod + '/payment/' + orderId, formData, function (response) {
            if (response.status == false) {
                toastr.error(response.message);
                return false;
            }
            events.purchase({
                currency: response.data.currency,
                value: response.data.value
            });
            orderSuccessPage(response.data.orderId);
        }).fail(function (response) {
            let errors = response.responseJSON.errors;
            validateErrors(errors, obj);
        });
    }

    $(document).on('mouseenter', '.bfilter-js li', function (e) {
        e.preventDefault();
        $('.brandList-js').addClass('filter-directory_disabled');
        $('.filter-directory_list_title').addClass('filter-directory_disabled');
        $('.b-' + $(this).attr('data-item').toLowerCase()).removeClass('filter-directory_disabled');
        lbl = $(this).attr('data-item');
        $('.filter-directory_list_title').each(function () {
            txt = $(this).attr('data-item').trim().toUpperCase();
            if (txt == lbl.toUpperCase()) {
                $(this).removeClass('filter-directory_disabled');
            }
        });

        if (typeof $('.filter-directory_list_title:not(.filter-directory_disabled)').position() != 'undefined') {
            $("#YKbrandfiltersListing").stop().animate({
                scrollLeft: 0
            }, 0);
            var offset = $('.filter-directory_list_title:not(.filter-directory_disabled)').position().left;
            $("#YKbrandfiltersListing").stop().animate({
                scrollLeft: offset - 20
            }, 0);
        }
    });

    $(document).on('mouseleave', '.bfilter-js li', function () {
        $('.brandList-js').removeClass('filter-directory_disabled');
        $('.filter-directory_list_title').removeClass('filter-directory_disabled');
    });

    $(document).on('click', '.bfilter-js li', function (e) {
        e.preventDefault();
        $(".filter-directory_list").animate({
            scrollLeft: $("#" + $(this).attr('data-item').toLowerCase()).position().left
        }, 1000);
    });
    autoKeywordSearch = function (keyword) {
            keyword = keyword.toUpperCase();
            var myarray = [];
            $('.filter-directory_list li').each(function () {
                txt = $(this).text().trim().toUpperCase();
                if (txt.indexOf(keyword) > -1) {
                    caption = $(this).attr('data-caption');
                    if (typeof caption !== 'undefined') {
                        myarray.push(caption.toUpperCase());
                    }
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            myarray = $.unique(myarray);
            $('.filter-directory_list_title').each(function () {
                txt = $(this).attr('data-item').trim().toUpperCase();
                if ($.inArray(txt, myarray) >= 0) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        },
        $(document).on('click', '.YK-PaymentGatewaySection', function (e) {
            if ($(this).find('#paycash-tab').hasClass('active')) {
                $(".yk-continueBtn").addClass("disabled");
                $(".yk-continueBtn").attr("disabled", true);
            } else {
                $(".yk-continueBtn").removeClass("disabled");
                $(".yk-continueBtn").removeAttr("disabled");
            }
        });
    requestOtp = function () {
            let submitBtn = $('#requestOtpBtn');
            loader(submitBtn);
            let addressId = $("input[name='address-id']").val();
            $.post(baseUrl + '/checkout/request-otp', {
                'address-id': addressId
            }, function (response) {
                loader(submitBtn, true);
                if (response.status == true) {
                    $(".YK-Request-Otp").hide();
                    $(".YK-optSentMessage").text(response.data);
                    $(".YK-Verify-Otp").show();
                    $(".YK-resendOtp").hide();
                    timeCounter(20);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            });
        },
        resendOtpRequest = function () {
            let submitBtn = $('#resendOtpBtn');
            loader(submitBtn);
            let addressId = $("input[name='address-id']").val();
            $.post(baseUrl + '/checkout/resent-request-otp', {
                'address-id': addressId
            }, function (response) {
                loader(submitBtn, true);
                if (response.status == true) {
                    $(".YK-Request-Otp").hide();
                    $(".YK-resendOtp").hide();
                    $(".YK-Verify-Otp").show();
                    floatingFormFix();
                    timeCounter(20);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            });
        },
        verifyOtp = function () {
            let submitBtn = $('#verifyOtpBtn');
            loader(submitBtn);
            let addressId = $("input[name='address-id']").val();
            let otp = $('.otp-inputs input').map(function () {
                return $(this).val();
            }).get().join('');
            $.post(baseUrl + '/checkout/verify-otp', {
                'address-id': addressId,
                'otp': otp
            }, function (response) {
                loader(submitBtn, true);
                if (response.status == true) {
                    $("#YK-cod").val(1);
                    $(".YK-orderButton").prop('disabled', false);
                    $("#verifyOtpBtn").hide();
                    $("#resendOtpBtn").hide();

                    $(".YK-Verify-Otp").addClass('d-none');
                    $(".YK-OTPSuccessMessage").text(response.message);
                    $(".YK-otpVerifiedSuccess").removeClass('d-none');

                    $("#otpValue").prop("disabled", true);
                    $(".yk-continueBtn").removeClass("disabled");
                    $(".yk-continueBtn").removeAttr("disabled");

                } else {
                    $('.otp-inputs').find('input').val('');
                    $('.otp-inputs').find('input').removeClass('filled');
                    $('.otp-inputs').find('input:first').focus();
                    toastr.error(response.message);
                }
            });
        },
        timeCounter = function (counter) {
            $('#time').text(counter);
            var interval = setInterval(function () {
                counter--;
                if (counter <= 0) {
                    clearInterval(interval);
                    $("#timer").hide();
                    $(".YK-resendOtp").show();

                    return;
                } else {
                    $('#time').text(counter);
                }
            }, 1000);
        },
        /*loader = function(obj, removeLoader = false) {
            if (removeLoader == false) {
                obj.attr("disabled", "disabled");
                //obj.addClass("spinner spinner--sm spinner--light");
                obj.addClass("gb-is-loading");
            } else {
                obj.removeAttr("disabled");
                obj.removeClass('gb-is-loading');
                //obj.removeClass('spinner spinner--sm spinner--light');
            }
        }*/
        addAddressPopup = function () {
            if ($(".yk-address-form").hasClass('d-none')) {
                $('.YK-oldAddress').removeClass('active');
                $(".yk-address-form").removeClass('d-none');
                $(".list-addresses").hide();
                $(".yk-address-form").addClass('d-block');
            }
            if ($(".yk-closeAddressPopup").hasClass('d-none')) {
                $(".yk-closeAddressPopup").removeClass('d-none');
                $(".yk-addAddressPopup").addClass('d-none');
            }
            $(".yk-address-form").find('input,select').val('');
            $(".yk-address-form").find('input[name="addr_phone_country_code"]').val(defaultCountry);
            iti.setCountry(defaultCountry);
            $('.iti__selected-flag').find('.countryCode').remove();
            setTimeout(() => {
                $('ul.iti__country-list li.iti__country#iti-item-' + defaultCountry).click();
            }, 500);

            if ($(".YK-paymentInfo").hasClass('active')) {
                $("#YK-billingAddress").find('input[name="addr_id"]').val("");
            }

            floatingFormFix();
        },
        continueCheckoutForm = function () {
            /*$('.yk-agreeTerms').hide();*/
            if ($(".YK-deliveryStep").hasClass('active')) {
                saveAddress('shippingAddress')
            } else if ($(".YK-shippingInfo").hasClass('active')) {
                paymentSection($('.YK-selectedAddress').attr('data-addrid'));
            } else if ($(".YK-paymentInfo").hasClass('active')) {
                placeOrder();
            }
        },
        editCheckoutAddress = function (addressId) {
            $.get(baseUrl + '/checkout/get-addressform/' + addressId, function (response) {
                if (response.status == true) {
                    $('.YK-oldAddress').removeClass('active');
                    $(".yk-address-form").removeClass('d-none');
                    $(".list-addresses").hide();
                    $(".yk-address-form").find('input[name="addr_first_name"]').val(response.data.addr_first_name);
                    $(".yk-address-form").find('input[name="addr_last_name"]').val(response.data.addr_last_name);
                    $(".yk-address-form").find('input[name="addr_email"]').val(response.data.addr_email);
                    $(".yk-address-form").find('input[name="edited_addr_id"]').val(response.data.addr_id);

                    if ($(".YK-paymentInfo").hasClass('active')) {
                        $("#YK-billingAddress").find('input[name="addr_id"]').val(response.data.addr_id);
                    }

                    if (response.data.phonecountry != null && response.data.addr_phone != '') {
                        let countrycode = response.data.phonecountry.country_code.toLowerCase();
                        iti.setCountry(countrycode);
                        iti.setNumber(response.data.addr_phone);
                        $("#addr_phone").val(response.data.addr_phone);
                        $('.iti__selected-flag').find('.countryCode').remove();
                        setTimeout(() => {
                            $('ul.iti__country-list li.iti__country#iti-item-' + countrycode).click();
                        }, 500);
                    }
                    $(".yk-address-form").find('input[name="addr_title"]').val(response.data.addr_title);
                    $(".yk-address-form").find('input[name="addr_address1"]').val(response.data.addr_address1);
                    $(".yk-address-form").find('input[name="addr_address2"]').val(response.data.addr_address2);
                    $(".yk-address-form").find('select[name="addr_country_id"]').val(response.data.addr_country_id);
                    $('.YK-state_id').html('');
                    Object.keys(response.data.states).forEach(key => {
                        $('.YK-state_id').append(new Option(response.data.states[key], key));
                    });

                    $(".yk-address-form").find('select[name="addr_state_id"]').val(response.data.addr_state_id);
                    $(".yk-address-form").find('input[name="addr_city"]').val(response.data.addr_city);
                    $(".yk-address-form").find('input[name="addr_postal_code"]').val(response.data.addr_postal_code);

                    $(".yk-address-form").addClass('d-block');
                    floatingFormFix();
                    if ($(".yk-closeAddressPopup").hasClass('d-none')) {
                        $(".yk-closeAddressPopup").removeClass('d-none');
                        $(".yk-addAddressPopup").addClass('d-none');
                    }
                } else {
                    toastr.error(response.message);
                }
            });
        },

        updatePaymentMethod = function (orderId) {

            $.post(baseUrl + '/order/payment-url', {
                'order_id': orderId
            }, function (response) {
                console.log(response);
                if (response.status == true) {
                    window.open(response.data.url, "_self");
                }

            });
        }

        checkoutFirstStep = function () {
            let addr_id = '';
            if ($('#guestaddress-quick').length !== 0) {
                addr_id = $('input[name="selected_address_id"]').val();
                $("#shippingInfo").removeClass('is-active');
                $("#deliveryStep").addClass('is-active');
                $('.YK-deliveryStep').addClass('active');
                $('.YK-shippingInfo').removeClass('active');
                $('.YK-oldAddress').removeClass('active');
                $(".yk-address-form").removeClass('d-none');
                $(".list-addresses").hide();
                $(".yk-address-form").find('input[name="edited_addr_id"]').val(addr_id);
                $('.yk-agreeTerms').show();
            } else {
                $.get(baseUrl + '/checkout/get', function (response) {
                    $("#shippingInfo").removeClass('is-active');
                    $("#deliveryStep").addClass('is-active');

                    $('.YK-deliveryStep').html(response.data.html);
                    $('.YK-deliveryStep').addClass('active');
                    $('.YK-shippingInfo').removeClass('active');
                    $('.YK-oldAddress').removeClass('active').addClass('active');
                    let selectedAddr = $('input[name="selected_address_id"]').val();
                    $("input[name=addr_id]").removeAttr('checked');
                    $("input[name=addr_id][value=" + selectedAddr + "]").prop("checked", true)
                    
                    $('.YK-oldAddress').find('li').removeClass('selected');
                    $('.YK-editAddress-' + selectedAddr).closest('li').addClass('selected');
                    $('.yk-agreeTerms').show();
                });
            }
        }

    $(document).on('click', '.YK-addressSelected', function () {
        $('.YK-addressItem').removeClass('selected');
        $('.YK-addressItem').find('.YK-actions').addClass('d-none');
        $(this).closest('.YK-addressItem').addClass('selected');
        $(this).closest('.YK-addressItem').find('.YK-actions').removeClass('d-none');
        $('input[name="selected_address_id"]').val($(this).val());
    });
    $(document).on('click', '.yk-backBtn', function () {
        let currentActive = $('.yk-stepOuter').find('.yk-step.active');
        if (currentActive.is(":first-child")) {
            window.location.href = baseUrl + '/cart';
            return false;
        } else if (currentActive.is(":nth-child(2)")) {
            let addrid = $('.YK-selectedAddress').attr('data-addrid');
            if ($('.yk-address-form').hasClass('d-block')) {
                $('#YK-shippingAddress').find('[name="edited_addr_id"]').val(addrid);
            }
        }
        if ($("#" + currentActive.prev().attr('data-step')).hasClass('is-active')) {
            $("#" + currentActive.prev().attr('data-step')).removeClass('is-active');
            $(".progress-step").removeClass('is-active');
            $("#" + currentActive.prev().attr('data-step')).addClass('is-active');
        }
        if (currentActive.prev().attr('data-step') == "deliveryStep" || currentActive.prev().attr('data-step') == "shippingInfo") {
            $("#checkoutProceed").text("Continue");
        }
        console.log(currentActive.prev().attr('data-step'));
        if (currentActive.prev().attr('data-step') == "deliveryStep") {
            checkoutFirstStep();
        }
        let prevElement = '';
        if (currentActive.prev().hasClass('yk-step')) {
            prevElement = currentActive.prev();
        } else {
            prevElement = currentActive.prev().prev();
        }
        if (currentActive.prev().attr('data-step') != "deliveryStep") {
            prevElement.addClass('active');
            currentActive.removeClass('active');
        }
        
        $('#checkoutProceed').removeClass('disabled');
        $('#checkoutProceed').attr('disabled', false);
    });
    $(document).on('click', '.yk-closeAddressPopup', function () {
        if ($(".yk-addAddressPopup").hasClass('d-none')) {
            $(".yk-addAddressPopup").removeClass('d-none');
            $(".yk-closeAddressPopup").addClass('d-none');
            $(".list-addresses").show();
            if ($(".yk-address-form").hasClass('d-block')) {
                $(".yk-address-form").removeClass('d-block');
                $(".yk-address-form").addClass('d-none');
            }
            $('.YK-oldAddress').addClass('active');
            $(".yk-address-form").find('input[name="addr_first_name"]').val('');
            $(".yk-address-form").find('input[name="addr_last_name"]').val('');
            $(".yk-address-form").find('input[name="addr_email"]').val('');
            $(".yk-address-form").find('input[name="addr_phone"]').val('');
            $(".yk-address-form").find('input[name="addr_title"]').val('');
            $(".yk-address-form").find('input[name="addr_address1"]').val('');
            $(".yk-address-form").find('input[name="addr_address2"]').val('');
            $(".yk-address-form").find('select[name="addr_country_id"]').val('');
            $(".yk-address-form").find('select[name="addr_state_id"]').val('');
            $(".yk-address-form").find('input[name="addr_city"]').val('');
            $(".yk-address-form").find('input[name="addr_postal_code"]').val('');
            if ($(".YK-paymentInfo").hasClass('active')) {
                $("#YK-billingAddress").find('input[name="addr_id"]').val($("#YK-billingAddress").find('input[name="addr_id"]:checked').attr("attr-val"));
            }
            floatingFormFix();
        }
    });
    
    

})();