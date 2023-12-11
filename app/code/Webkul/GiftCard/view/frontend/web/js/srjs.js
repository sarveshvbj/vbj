define([
    "jquery",
    "Magento_Checkout/js/action/get-totals",
    "mage/url"
], function($, getTotalsAction, url) {
    "use strict";
 
     $.widget('mage.srjs', {
        _create: function() {

            this.element.on('click', function(e){

                if($(this).prop("checked") == true) {   var use_payback = 1;    }
                else {  var use_payback = 0; }
                var redeempoints = $('#redeempoints').val();
                    $.ajax({
                            url     :   url.build('payback/getaccountbalance/redeempoints'),
                            type    :   "POST",
                            dataType:   "json",
                            async   :   true,
                            data    :   {redeempoints:redeempoints,use_payback:use_payback}, 
                            showLoader: true,
                            success: function(response){
                                if (response)
                                   {    
                                        var deferred = $.Deferred();
                                        getTotalsAction([], deferred);
                                   }
                            }
                    });
            });
        }
 
    });
 
    return $.mage.srjs;
});