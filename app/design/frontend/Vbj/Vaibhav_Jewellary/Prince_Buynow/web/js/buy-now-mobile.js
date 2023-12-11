define([
    'jquery'
], function ($) {
    "use strict";
    return function (config, element) {
        $(element).click(function () {
            var form = $(config.form);
            var url = 'checkout/cart/add';
            var baseUrl = form.attr('action'),
            
                buyNowUrl = baseUrl.url;
            form.attr('action', buyNowUrl);
            form.trigger('submit');
            form.attr('action', baseUrl);
            return false;
        });
    }
});
