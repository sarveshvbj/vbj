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
 * @category   BSS
 * @package    Bss_OneStepCheckout
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */

define([
    'jquery',
    'underscore',
    'ko',
    'uiComponent',
    'Amazon_Payment/js/model/storage',
    'mage/storage',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Checkout/js/model/url-builder',
    'Magento_Checkout/js/model/full-screen-loader',
], function (
    $,
    _,
    ko,
    Component,
    amazonStorage,
    storage,
    errorProcessor,
    urlBuilder,
    fullScreenLoader,
) {
    'use strict';

    var mixin = {
        /**
         * Revert checkout
         */
        revertCheckout: function () {
            var serviceUrl = urlBuilder.createUrl('/amazon/order-ref', {});

            fullScreenLoader.startLoader();
            storage.delete(
                serviceUrl
            ).done(
                function () {
                    amazonStorage.amazonlogOut();
                    fullScreenLoader.stopLoader();

                    // Amazon pay hot-fix: reload page after Customer click "Return to standard checkout"
                    // to reload other payment method
                    location.reload();
                }
            ).fail(
                function (response) {
                    fullScreenLoader.stopLoader();
                    errorProcessor.process(response);
                }
            );
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});