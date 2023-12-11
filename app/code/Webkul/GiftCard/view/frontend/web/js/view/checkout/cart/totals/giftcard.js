define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/model/totals'
    ],
    function (Component, quote, priceUtils, totals) {
        "use strict";
        return Component.extend({
            defaults: {
                isFullTaxSummaryDisplayed: window.checkoutConfig.isFullTaxSummaryDisplayed || false,
                template: 'Webkul_GiftCard/checkout/summary/giftcard'
            },
            totals: quote.getTotals(),
            isTaxDisplayedInGrandTotal: window.checkoutConfig.includeTaxInGrandTotal || false,
            isDisplayed: function() {
                return !!this.getValue();
            },
            getLabel: function() {
                var giftcard = '';
                if (this.totals() && totals.getSegment('giftcard')) {
                    giftcard = 'Gift Card';
                }
                return giftcard;
            },
            getValue: function () {
            for (var i in this.totals().total_segments) {
                var total = this.totals().total_segments[i];

                if (total.code == 'giftcard') {
                    return this.getFormattedPrice(total.value);
                }
            }
        },
        });
    }
);

