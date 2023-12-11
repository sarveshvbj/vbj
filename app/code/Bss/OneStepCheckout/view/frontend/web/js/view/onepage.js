/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category  BSS
 * @package   Bss_OneStepCheckout
 * @author    Extension Team
 * @copyright Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license   http://bsscommerce.com/Bss-Commerce-License.txt
 */

define([
    'uiComponent',
    'uiRegistry',
    'underscore',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/select-billing-address',
    'Bss_OneStepCheckout/js/model/payment-service',
    'Magento_Checkout/js/model/totals',
    'Bss_OneStepCheckout/js/action/set-shipping-information',
    'Magento_Checkout/js/model/shipping-service',
    'Magento_Customer/js/model/customer',
    'Bss_OneStepCheckout/js/model/update-item-service'
], function (
    Component,
    registry,
    _,
    quote,
    selectBillingAddress,
    paymentService,
    totalsService,
    setShippingInformationAction,
    shippingService,
    customer,
    updateItemService
) {
    'use strict';

    return Component.extend({

        /** @inheritdoc */
        initialize: function () {
            this._super();
            var self = this;
            quote.shippingMethod.subscribe(function (method) {
                if (method && !updateItemService.hasUpdateResult()) {
                    var shippingRates = shippingService.getShippingRates();
                    var availableRate = _.find(shippingRates(), function (rate) {
                        if (rate['method_code'] === null && method['method_code'] === null) {
                            return false;
                        }
                        return rate['carrier_code'] + '_' + rate['method_code'] === method['carrier_code'] + '_' + method['method_code'];
                    });
                    if (availableRate) {
                        selectBillingAddress(quote.billingAddress());
                        paymentService.isLoading(true);
                        totalsService.isLoading(true);
                        setShippingInformationAction().done(
                            function () {
                                paymentService.isLoading(false);
                                totalsService.isLoading(false);
                            }
                        );
                    }
                }
            }, this);
            quote.shippingAddress.subscribe(function (address) {
                if (_.isUndefined(address.street) || address.street.length === 0) {
                    address.street = ["", ""];
                }
            }, this);
        },

        /**
         * @returns {*}
         */
        _isAddressSameAsShipping: function () {
            return registry.get('checkout.steps.billing-step.payment.payments-list.billing-address-form-shared').isAddressSameAsShipping();
        }
    });
});
