(function () {
    setInputFilter = function (textbox, inputFilter) {
        ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop", "textArea"].forEach(function (event) {
            textbox.addEventListener(event, function () {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
            });
        });
    }
    setCardFormValidation = function () {
        setInputFilter(document.getElementById("name"), function (value) {
            return /^[a-zA-Z\s]*$/.test(value);
        });
        setInputFilter(document.getElementById("number"), function (value) {
            return /^(\s*|\d+)$/.test(value) && (value.length <= 19)
        });
        setInputFilter(document.getElementById("exp_month"), function (value) {
            return /^\d*$/.test(value) && (value.length <= 2)
        });
        setInputFilter(document.getElementById("exp_year"), function (value) {
            return /^\d*$/.test(value) && (value.length <= 4)
        });
        setInputFilter(document.getElementById("cvv"), function (value) {
            return /^\d*$/.test(value)
        });

        if ($("#name").val() == '' && $("#number").val() == '' && $("#exp_month").val() == '' || $("#exp_year").val() == '' && $("#cvv").val() == '') {
            $("#YK-saveCards button[name='saveCardBtn']").attr("disabled", true);
        } else {
            $("#YK-saveCards button[name='saveCardBtn']").attr("disabled", false);
        }
    }
    setCardFormValidationTrigger = function () {
        $("input[type='text'], textarea, select").on("keyup", function () {
            if ($("#name").val() == '' || $("#number").val() == '' || $("#exp_month").val() == '' || $("#exp_year").val() == '' || $("#cvv").val() == '') {
                $("#YK-saveCards button[name='saveCardBtn']").attr("disabled", true);
            } else {
                $("#YK-saveCards button[name='saveCardBtn']").attr("disabled", false);
            }
        });
    }
    setAddressFormValidation = function () {
        setInputFilter(document.getElementById("addr_first_name"), function (value) {
            return /^[a-zA-Z\s]*$/.test(value);
        });
        setInputFilter(document.getElementById("addr_last_name"), function (value) {
            return /^[a-zA-Z\s]*$/.test(value);
        });
        setInputFilter(document.getElementById("addr_title"), function (value) {
            return /^[a-zA-Z\s]*$/.test(value);
        });
        setInputFilter(document.getElementById("addr_city"), function (value) {
            return /^[a-zA-Z\s]*$/.test(value)
        });
        setInputFilter(document.getElementById("addr_postal_code"), function (value) {
            return /^\d*$/.test(value)
        });

        if ($("#addr_first_name").val() == '' || $("#addr_last_name").val() == '' || $("#addr_title").val() == '' || $("#addr_city").val() == '' || $("#addr_postal_code").val() == '') {
            $("#YK-addAddress button[name='YK-saveaddress']").attr("disabled", true);
        } else {
            $("#YK-addAddress button[name='YK-saveaddress']").attr("disabled", false);
        }
    }
    setAddressFormTrigger = function (update = 0) {
        $("input[type='text'], textarea").on("keyup", function () {
            if ($("#addr_first_name").val() == '' || $("#addr_last_name").val() == '' || $("#addr_title").val() == '' || $("#addr_city").val() == '' || $("#addr_postal_code").val() == '') {
                if (update) {
                    $("#YK-addAddress button[name='YK-updateaddress']").attr("disabled", true);
                } else {
                    $("#YK-addAddress button[name='YK-saveaddress']").attr("disabled", true);
                }
            } else {
                if (update) {
                    $("#YK-addAddress button[name='YK-updateaddress']").attr("disabled", false);
                } else {
                    $("#YK-addAddress button[name='YK-saveaddress']").attr("disabled", false);
                }

            }
        });
    }
    setProfileFormValidation = function () {
        setInputFilter(document.getElementById("user_fname"), function (value) {
            return /^[a-zA-Z\s]*$/.test(value);
        });
        setInputFilter(document.getElementById("user_lname"), function (value) {
            return /^[a-zA-Z\s]*$/.test(value);
        });

        if ($("#user_fname").val() == '' || $("#user_lname").val() == '' || $("#user_email").val() == '') {
            $("#YK-personalDetails button[name='saveProfileBtn']").attr("disabled", true);
        } else {
            $("#YK-personalDetails button[name='saveProfileBtn']").attr("disabled", false);
        }
    }
    setProfileFormTrigger = function () {
        $("input[type='text'], textarea").on("keyup", function () {
            if ($("#user_fname").val() == '' || $("#user_lname").val() == '' || $("#user_email").val() == '') {
                $("#YK-personalDetails button[name='saveProfileBtn']").attr("disabled", true);
            } else {
                $("#YK-personalDetails button[name='saveProfileBtn']").attr("disabled", false);
            }
        });
    }
    setReviewFormValidation = function () {
        setInputFilter(document.getElementById("preview_title"), function (value) {
            return /^[a-zA-Z\s]*$/.test(value);
        });
        if ($("#preview_title").val() == '') {
            $("#dataModal").find('[name="submitReviewBtn"]').attr("disabled", true);
        } else {
            $("#dataModal").find('[name="submitReviewBtn"]').attr("disabled", false);
        }
    }
    setReviewFormTrigger = function () {
        $("input[type='text'], textarea").on("keyup", function () {
            if ($("#preview_title").val() == '') {
                $("#dataModal").find('[name="submitReviewBtn"]').attr("disabled", true);
            } else {
                $("#dataModal").find('[name="submitReviewBtn"]').attr("disabled", false);
            }
        });
    }
    setApplyCouponValidation = function () {
        setInputFilter(document.getElementById("cartCouponCode"), function (value) {
            return /^[a-zA-Z0-9\s]*$/.test(value);
        });
        if ($("#cartCouponCode").val() == '') {
            $("#dataModal").find('[name="ykApplyCoupon"]').attr("disabled", true);
        } else {
            $("#dataModal").find('[name="ykApplyCoupon"]').attr("disabled", false);
        }
    }
    setApplyCouponTrigger = function () {
        $("input[type='text'], textarea").on("keyup", function () {
            if ($("#cartCouponCode").val() == '') {
                $("#dataModal").find('[name="ykApplyCoupon"]').attr("disabled", true);
            } else {
                $("#dataModal").find('[name="ykApplyCoupon"]').attr("disabled", false);
                $("input:radio[name='coupon']").each(function (i) {
                    this.checked = false;
                });
            }
        });
    }
    setLoginValidation = function () {
        let conditionId = ($("#via").val() == "email") ? 'username' : 'user_phone_mask';
        if ($("#" + conditionId).val() == '' || $("#password").val() == '') {
            $("#dataModal").find('[name="loginBtn"]').attr("disabled", true);
        } else {
            $("#dataModal").find('[name="loginBtn"]').attr("disabled", false);
        }
    }
    setLoginTrigger = function () {
        let conditionId = ($("#via").val() == "email") ? 'username' : 'user_phone_mask';
        $("input[type='text'], [name='user_phone_mask'], textarea, input[type='password']").on("keyup", function () {
            if ($("#" + conditionId).val() == '' || $("#password").val() == '') {
                $("#dataModal").find('[name="loginBtn"]').attr("disabled", true);
            } else {
                $("#dataModal").find('[name="loginBtn"]').attr("disabled", false);
            }
        });
    }
    setGuestLoginValidation = function () {
        if ($("#username").val() == '' || $("#password").val() == '') {
            $('button[name="guestCheckoutLgn"]').attr("disabled", true);
        } else {
            $('button[name="guestCheckoutLgn"]').attr("disabled", false);
        }
    }
    setGuestLoginTrigger = function () {
        $("input[type='text'], textarea, input[type='password']").on("keyup", function () {
            if ($("#username").val() == '' || $("#password").val() == '') {
                $('button[name="guestCheckoutLgn"]').attr("disabled", true);
            } else {
                $('button[name="guestCheckoutLgn"]').attr("disabled", false);
            }
        });
    }
})();