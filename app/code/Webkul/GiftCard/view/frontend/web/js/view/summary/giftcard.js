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
                template: 'Iksula_Payback/checkout/summary/payback'
            },
            totals: quote.getTotals(),
            isTaxDisplayedInGrandTotal: window.checkoutConfig.includeTaxInGrandTotal || false,
            isDisplayed: function() {
                return !!this.getValue();
            },
            getLabel: function() {
                var paybacklabel = '';
                if (this.totals() && totals.getSegment('payback')) {
                    paybacklabel = 'Payback';
                }
                return paybacklabel;
            },
            getValue: function () {
            for (var i in this.totals().total_segments) {
                var total = this.totals().total_segments[i];

                if (total.code == 'payback') {
                    return this.getFormattedPrice(total.value);
                }
            }
        },
        });
    }
);

