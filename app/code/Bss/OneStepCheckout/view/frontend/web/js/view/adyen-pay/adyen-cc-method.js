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
    'Bss_OneStepCheckout/js/model/adyen-pay-btn-active'
], function (adyenPayBtnActive) {
    'use strict';

    return function (adyenPay) {
        return adyenPay.extend({
            isButtonActive: function () {
                var check = this.isActive() && this.getCode() == this.isChecked() &&
                    this.isPlaceOrderActionAllowed() &&
                    this.placeOrderAllowed();
                adyenPayBtnActive(check);
                return this._super();
            }
        });
    }
});
