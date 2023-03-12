<div @if(isset($shipping) && $shipping == true) id="YK-shippingAddress" @endif class="YK-checkoutForm form form-floating">
    <input type="hidden" name="edited_addr_id" value="">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-floating__group">
                <input type="text" class="form-control form-floating__field" name="addr_first_name" value="@if(auth()->check()){{auth()->user()->user_fname}}@endif" id="addr_first_name">
                <label class="form-floating__label">{{ __('LBL_FIRST_NAME') }}</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-floating__group">
                <input type="text" class="form-control form-floating__field" name="addr_last_name" value="@if(auth()->check()){{auth()->user()->user_lname}}@endif" id="addr_last_name">
                <label class="form-floating__label">{{ __('LBL_LAST_NAME') }}</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-floating__group">
                <input type="email" class="form-control form-floating__field" value="@if(auth()->check()){{auth()->user()->user_email}}@endif"  name="addr_email" id="addr_email">
                <label class="form-floating__label">{{ __('LBL_EMAIL') }}</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-floating__group">
            <input type="hidden" name="addr_phone_country_code" value="{{ !empty($countryCode) ? $countryCode : 'us' }}"> 
            <input id="addr_phone" type="hidden" name="addr_phone" value="@if(!empty($oldAddress)){{ $oldAddress['addr_phone'] }}@endif">
            <input id="addr_phone_mask" type="tel" data-inputmask-clearmaskonlostfocus="false" class="form-control  @error('addr_phone') is-invalid @enderror" name="addr_phone_mask" value="@if(!empty($oldAddress)){{ $oldAddress['addr_phone_mask'] }}@endif">
            </div>
        </div>       
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-floating__group">
                <input type="text" class="form-control form-floating__field" name="addr_title" id="addr_title" value="@if(!empty($oldAddress)){{ $oldAddress['addr_title'] }}@endif">
                <label class="form-floating__label">{{ __('LBL_TITLE') }}</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-floating__group">
                <input type="text" class="form-control form-floating__field" name="addr_address1" id="addr_address1" value="@if(!empty($oldAddress)){{ $oldAddress['addr_address1'] }}@endif">
                <label class="form-floating__label">{{ __('LBL_APARTMENT_NO') }}</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group form-floating__group">
                <input type="text" class="form-control form-floating__field" name="addr_address2" value="@if(!empty($oldAddress)){{ $oldAddress['addr_address2'] }}@endif">
                <label class="form-floating__label">{{ __('LBL_ADDRESS') }}</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-floating__group">
                <select class="form-control form-floating__field YK-country_id js-selectCountry" autocomplete="shipping country" name="addr_country_id" id="addr_country_id" onchange="getStates('{{$shipping}}', '{{$digital}}', this)">
                    <option disabled="" value="" selected>{{ __('LBL_SELECT_COUNTRY') }}</option>
                    @foreach ($countries as $country)
                        <option value="{{$country->country_id}}" @if($country->country_code == $countryCode) {{'selected'}}@else {{''}} @endif >{{$country->country_name}}</option>
                    @endforeach                   
                </select>
                <label class="form-floating__label">{{ __('LBL_COUNTRY') }}</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-floating__group">
                <select class="form-control form-floating__field YK-state_id" onchange="calculateDigitalTax('', '{{$shipping}}', '{{$digital}}')" name="addr_state_id" id="addr_state_id">
                    <option disabled="" value="" selected>{{ __('LBL_SELECT_STATE') }}</option>
                    @foreach ($states as $stateId => $stateName)
                        <option value="{{$stateId}}"  @if(isset($defaultState) && $defaultState == $stateName) {{'selected'}}@endif>{{$stateName}}</option>
                    @endforeach                   
                </select>
                <label class="form-floating__label">{{ __('LBL_STATE') }}</label>
            </div>
        </div>    
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-floating__group">
                <input type="text" class="form-control form-floating__field" value="@if(!empty($oldAddress)){{ $oldAddress['addr_city'] }}@endif" name="addr_city" id="addr_city">
                <label class="form-floating__label">{{ __('LBL_CITY') }}</label>
            </div>
        </div>        
        <div class="col-md-6">
            <div class="form-group form-floating__group">
                <input type="text" class="form-control form-floating__field" value="" name="addr_postal_code" value="@if(!empty($oldAddress)){{ $oldAddress['addr_postal_code'] }}@endif" id="addr_postal_code" maxlength="10">
                <label class="form-floating__label">{{ __('LBL_POSTAL_CODE') }}</label>
            </div>
        </div>
    </div>
</div>
<link href="{{ asset('vendors/css/intlTelInput.min.css')}}" rel="stylesheet" type="text/css">
<style>
.iti__flag { background-image: url( {{asset('yokart/'. config('theme'). '/images/flags.png' )}}); }
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
  .iti__flag {background-image: url({{asset('yokart/'.config('theme').'/images/flags@2x.png')}});}
}
input[name="addr_phone_mask"]{padding-left: 100px !important;}
</style>
<script src="{{ asset('vendors/js/jquery.inputmask.js') }}"></script>
<script src="{{ asset('vendors/js/intlTelInput.min.js') }}"></script>
<script >
    var parentField = "@php if(isset($shipping) && $shipping == true){ echo '#YK-shippingAddress'; }else{ echo '#YK-billingAddress'; } @endphp";

    var input = document.querySelector(parentField + " [name='addr_phone_mask']");
    if(typeof iti != 'undefined') {
        iti.destroy();
    }
        var iti = window.intlTelInput(input, {
            separateDialCode: true,
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                var placeholderVal = selectedCountryPlaceholder.replace(/[0-9]/g, "9").replace(/_/g, "9");
                $(parentField + ' [name="addr_phone_mask"]').inputmask(placeholderVal);
                return placeholderVal;
            },
            utilsScript: "{{asset('yokart/'.config('theme').'/js/utils.js')}}"
        });  
    @php
        $defaultCountry = getDefaultCountryCode();
    @endphp
    @if(empty($oldAddress))
    iti.setCountry("{{ $defaultCountry }}"); 
    invokeChange();
    @endif
    input.addEventListener("countrychange", function() {
        invokeChange();
    });
    function invokeChange () {
        setTimeout(() => {
            var selected = iti.getSelectedCountryData();
            $(parentField + ' [name=addr_phone_country_code]').val(selected.iso2);
            var placeholder = $(parentField + ' [name="addr_phone_mask"]').attr('placeholder');
            $(parentField + ' [name="addr_phone_mask"]').inputmask(placeholder).focus();
        }, 300);
    }
    $(parentField + ' [name="addr_phone_mask"]').blur(function() {
        $(parentField + ' #addr_phone').val($(parentField + ' [name="addr_phone_mask"]').inputmask('unmaskedvalue'));
    });        
    
          function setInputFilter(textbox, inputFilter) {
            ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
                textbox.addEventListener(event, function() {
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
        
         function setGuestCheckoutValidation() {
             if($('.yk-address-form').hasClass('d-none')) {
                return false;
             }
            setInputFilter(document.getElementById("addr_city"), function(value) {
                return /^[a-zA-Z\s]*$/.test(value); });

            setInputFilter(document.getElementById("addr_postal_code"), function(value) {
                return /^([a-zA-Z0-9])*$/.test(value); });
                
            if( ($("#addr_first_name").val() == '' || ($("#agreeTermsCondition").prop("checked") == false) || ($(".YK-itemsOutOfStock").length == 1) || $("#addr_last_name").val() == '' || $("#addr_title").val() == '' || $("#addr_address1").val() == '' ||  $("#addr_city").val() == '' || $("#addr_postal_code").val() == '' || $("#addr_country_id").val() == '' || $("#addr_state_id").val() == '' || $("#addr_phone_mask").val()== '')) {
                $(".yk-continueBtn").attr("disabled", true);
            } else {
                $(".yk-continueBtn").attr("disabled", false);
            }
        }
        function setGuestCheckoutTrigger() {
             if($('.yk-address-form').hasClass('d-none')) {
                return false;
             }
            $("input[type='text'], input[type='select'], input[type='checkbox'], input[type='tel']").on("keyup", function() {  
                var parentObj = $(this).closest(".YK-checkoutForm");      
                if( (parentObj.find("#addr_first_name").val()=='' || parentObj.find("#addr_address1").val() == '' || parentObj.find("#addr_last_name").val()=='' || parentObj.find("#addr_title").val()=='' || parentObj.find("#addr_city").val() == "" || parentObj.find("#addr_postal_code").val() == '' || parentObj.find("#addr_country_id").val() == null || parentObj.find("#addr_state_id").val() == '' || parentObj.find("#addr_phone_mask").val()== '' || ($("#agreeTermsCondition").prop("checked") == false ) || ($(".YK-itemsOutOfStock").length == 1) )  ) {
                    $(".yk-continueBtn").attr("disabled", true);
                } else {
                    $(".yk-continueBtn").attr("disabled", false);
                }
            });        
        }
        setGuestCheckoutValidation();
        setGuestCheckoutTrigger();
    </script>