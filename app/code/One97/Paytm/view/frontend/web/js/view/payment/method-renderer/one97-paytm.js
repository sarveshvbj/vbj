define(
    [
    'jquery',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/action/place-order',
        'Magento_Checkout/js/action/select-payment-method',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/payment/additional-validators',
        'mage/cookies'
    ],
    function ($, Component, placeOrderAction, selectPaymentMethodAction, customer, checkoutData, additionalValidators) {
        'use strict';
        $('.errP').text('');
        var promo='';
        /*alert(window.checkoutConfig.payment.paytm.hide_promo_field);
        alert($.trim(window.checkoutConfig.payment.paytm.promoCode));
        alert($.trim(window.checkoutConfig.payment.paytm.promoCode).length);
        alert(window.checkoutConfig.payment.paytm.promoCode);*/
        if(window.checkoutConfig.payment.paytm.hide_promo_field=='1'){
            if(window.checkoutConfig.payment.paytm.promo_code_local_validation=='1' && ($.trim(window.checkoutConfig.payment.paytm.promoCode).length=='0' || $.trim(window.checkoutConfig.payment.paytm.promoCode)=='')){
                // $('.customDiv').css('display','block');
            }else{
                setTimeout(function(){
                    $('.customDiv').css('display','block');
                }, 1000);
            }

        }
        $(document).on('click','.promoApplyBtn',function(){
            // alert(window.checkoutConfig.payment.paytm.promoCode);
            if(window.checkoutConfig.payment.paytm.promo_code_local_validation=='1'){
                if($.trim($('.promoCodeField').val())==''){
                    $('.errP').text('Please enter your promo code.');
                }else{
                    var promoCode=window.checkoutConfig.payment.paytm.promoCode;
                    var promoCodeArr=promoCode.split(',');
                    var matchCode=0;
                    $.each(promoCodeArr, function( index, value ) {
                        if($.trim($('.promoCodeField').val())==$.trim(value )){
                            matchCode=1;
                        }
                    });
                    if(matchCode==1){
                        $('.promoApplyBtn').val('Remove Code');
                        $('.promoApplyBtn').addClass('promoRemoveBtn');
                        $('.promoApplyBtn').removeClass('promoApplyBtn');
                        $('.promoRemoveBtn').css('background','red');
                        $('.promoRemoveBtn').css('border','1px solid red');
                        $('.promoCodeField').attr('disabled',true);
                        $('.errP').text('Applied Successfully');
                        promo=$.trim($('.promoCodeField').val());
                    }else{
                        $('.errP').text('Incorrect Promo Code');
                        promo='';
                    }
                }
            }else{
                $('.promoApplyBtn').val('Remove Code');
                $('.promoApplyBtn').addClass('promoRemoveBtn');
                $('.promoApplyBtn').removeClass('promoApplyBtn');
                $('.promoRemoveBtn').css('background','red');
                $('.promoRemoveBtn').css('border','1px solid red');
                $('.promoCodeField').attr('disabled',true);
                $('.errP').text('Applied Successfully');
                promo=$.trim($('.promoCodeField').val());
            }
        });
        $(document).on('click','.promoRemoveBtn',function(){
            $('.promoRemoveBtn').val('Apply');
            $('.promoRemoveBtn').addClass('promoApplyBtn');
            $('.promoRemoveBtn').removeClass('promoRemoveBtn');
            $('.promoApplyBtn').css('background','#006bb4');
            $('.promoApplyBtn').css('border','1px solid #006bb4');
            $('.promoCodeField').attr('disabled',false);
            $('.promoCodeField').val('');
            $('.errP').text('');
            promo='';
        });
        return Component.extend({
            defaults: {
                template: 'One97_Paytm/payment/one97',
                selectedValues: ''
            },
            initialize: function () {
                    this._super()
                            .observe(
                                    [   'selectedValues',
                                    ]
                                    );
                    return this;
                },
            placeOrder: function (data, event) {
                if (event) {
                    event.preventDefault();
                }
                var self = this,
                    placeOrder,
                    emailValidationResult = customer.isLoggedIn(),
                    loginFormSelector = 'form[data-role=email-with-possible-login]';
                if (!customer.isLoggedIn()) {
                    $(loginFormSelector).validation();
                    emailValidationResult = Boolean($(loginFormSelector + ' input[name=username]').valid());
                }
                if (emailValidationResult && this.validate() && additionalValidators.validate()) {
                    this.isPlaceOrderActionAllowed(false);
                    placeOrder = placeOrderAction(this.getData(), false, this.messageContainer);

                    $.when(placeOrder).fail(function () {
                        self.isPlaceOrderActionAllowed(true);
                    }).done(this.afterPlaceOrder.bind(this));
                    return true;
                }
                return false;
            },
            getStoreCard: function() {
                //alert(window.checkoutConfig.storedCards);
                    return  window.checkoutConfig.payment.storedCards;
                },
    getCardList: function() {
        return _.map(this.getStoreCard(), function(value, key) {
            return {
                'value': key,
                'type': value
            }
        });
    }, 
    getSelectedValuecc: function() {
            jQuery(function () {
            jQuery("#paytm_payment_profile_id").change(function () {
            //alert('select chnage');
            var selectedValue = jQuery(this).val();
            //alert("Value: " + selectedValue);
             var date = new Date();
             var minutes = 30;
             date.setTime(date.getTime() + (minutes * 60 * 1000));
            
            //jQuery('input[name="payment[method]"]').attr("disabled",true);
           if (selectedValue !==''){
               jQuery("#pay").show();
               jQuery("#address").show();
            }else{
                jQuery("#pay").hide();
                jQuery("#address").hide();
            }
            if(selectedValue == 'vsquare'){
               $.cookie('cookie_name', 'vsquare',{expires: date});
               var temp = $.cookie('cookie_name');
               //alert("cookie - " +temp);
               jQuery("#address").html("V Square Building 1st Lane, DwarakaNagar,Visakhapatnam-530016,AP.,India").show();
               jQuery("#paytm").click();
               return true;
            }
            if(selectedValue == 'pavanpalace'){
                $.cookie('cookie_name', 'pavanpalace',{expires: date});
               var temp1 = $.cookie('cookie_name');
               //alert("cookie - " +temp);
               jQuery("#address").html("A-1,Pavan Palace,near telugu thalli Flyover,Station Road, Dwaraka Nagar,Visakhapatnam-530016,AP.,India").show();
               jQuery("#paytm").click();
               return true;
            }
            if(selectedValue == 'kakinada'){
                $.cookie('cookie_name', 'kakinada',{expires: date});
               var temp2 = $.cookie('cookie_name');
               //alert("cookie - " +temp);
               jQuery("#address").html("D.No: 34-1-1, Opp. District Co-Operative Bank, Pulavarthivari Street, Kakinada, Andhra Pradesh 533001").show();
               jQuery("#paytm").click();
               return true;
            }
             if(selectedValue == 'gajuwaka'){
                $.cookie('cookie_name', 'gajuwaka',{expires: date});
               var temp3 = $.cookie('cookie_name');
               //alert("cookie - " +temp);
               jQuery("#address").html("Door No 10-7-110/1, Opposite Canara Bank, Cinema Hall Junction, Gajuwaka, Visakhapatnam - 530026,AP.,India").show();
               jQuery("#paytm").click();
               return true;
            }
             if(selectedValue == 'parvathipuram'){
                $.cookie('cookie_name', 'parvathipuram',{expires: date});
               var temp4 = $.cookie('cookie_name');
               //alert("cookie - " +temp);
               jQuery("#address").html("D.No:25-1, Near RTC Complex, Main Road,, Parvathipuram, Andhra Pradesh 535501,India").show();
               jQuery("#paytm").click();
               return true;
            }
             if(selectedValue == 'bobbili'){
                $.cookie('cookie_name', 'bobbili',{expires: date});
               var temp5 = $.cookie('cookie_name');
               //alert("cookie - " +temp);
               jQuery("#address").html("Baljipeta Main Rd, Maharanipeta, Bobbili, Andhra Pradesh 535558 ,India").show();
               jQuery("#paytm").click();
               return true;
            }
             if(selectedValue == 'rajahmundry'){
                $.cookie('cookie_name', 'rajahmundry',{expires: date});
               var temp6 = $.cookie('cookie_name');
               //alert("cookie - " +temp);
               jQuery("#address").html("Door No:6-5-85,Opp. Hero Motor bike showroom, Main Rd, Tyagaraja Nagar, Rajahmundry, Andhra Pradesh 533101. India").show();
               jQuery("#paytm").click();
               return true;
            }
             if(selectedValue == 'anakapalli'){
                $.cookie('cookie_name', 'anakapalli',{expires: date});
               var temp7 = $.cookie('cookie_name');
               //alert("cookie - " +temp);
               jQuery("#address").html("D.No: 11-1-50, Near: Sri Kanyakaparameswari Temple, Main Road, Anakapalle, Visakhapatnam-531001, Andhra Pradesh, India").show();
               jQuery("#paytm").click();
               return true;
            }
             if(selectedValue == 'dilsukhnagar'){
                $.cookie('cookie_name', 'dilsukhnagar',{expires: date});
               var temp8 = $.cookie('cookie_name');
               //alert("cookie - " +temp);
               jQuery("#address").html("D.No: 16-11-477,, Below Agarwal Eye Hospital, Indira Nagar,, Main Road Dilsukhnagar., Hyderabad, Telangana 500060,India").show();
               jQuery("#paytm").click();
               return true;
            }
             if(selectedValue == 'asrao'){
                $.cookie('cookie_name', 'asrao',{expires: date});
               var temp9 = $.cookie('cookie_name');
               //alert("cookie - " +temp);
               jQuery("#address").html("D.No:1-19-71/A-8/2 Aiswarya Chambers, Above Ratnadeep Super Market, Main Road, Rukminipuri Colony, Telangana 500062,India").show();
               jQuery("#paytm").click();
               return true;
            }
             if(selectedValue == 'gopalapatnam'){
                $.cookie('cookie_name', 'gopalapatnam',{expires: date});
               var temp10 = $.cookie('cookie_name');
               //alert("cookie - " +temp);
               jQuery("#address").html("D.No: 4-218/6/1, Gopalapatnam Main Road, Gopalapatnam, Visakhapatnam - 530027,India").show();
               jQuery("#paytm").click();
               return true;
            }
            return selectedValue;
        });
    });
    },
   /* getSelectedvalue:function(){
        return _.map(this.getSelectedValuecc());*/
        /* getData: function () {
            //var selectedValues = this.getSelectedValuecc();
               var data = 'pavan';//this.getSelectedValuecc();//'Payatstore';
        
             return data;

            },*/
           /*           getData: function() {
                return {
                    'method': 'paytm',
                    'additional_data': {
                        'bankowner': 'pavan'
                    }
                };
            },*/
   // },
  /* getSelectedvalue:function(){
           var selectedValues = ("#paytm_payment_profile_id").val();//'Sarvesh';
           return selectedValues;
   },
        getData: function () {
                    return {
                        "additional_data": {
                            'tax_code': this.selectedValues()
                            
                        }
                    };
                },*/
            afterPlaceOrder: function () {
                if(promo==''){
                    $.mage.redirect(window.checkoutConfig.payment.paytm.redirectUrl);
                }else{
                    $.mage.redirect(window.checkoutConfig.payment.paytm.redirectUrl+"?promo="+promo);
                }
            }
        });
    }
);



