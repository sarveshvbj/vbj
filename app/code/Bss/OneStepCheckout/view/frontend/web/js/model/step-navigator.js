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
    'jquery',
    'ko',
    'Magento_Checkout/js/model/step-navigator'
], function ($, ko, stepNavigator) {
    'use strict';

    var steps = ko.observableArray();

    return {

        /**
         * @param {String} code
         * @param {*} alias
         * @param {*} title
         * @param {Function} isVisible
         * @param {*} navigate
         * @param {*} sortOrder
         */
        registerStep: function (code, alias, title, isVisible, navigate, sortOrder) {
            var hash, active;

            if ($.inArray(code, stepNavigator.validCodes) !== -1) {
                throw new DOMException('Step code [' + code + '] already registered in step navigator');
            }

            if (alias != null) {
                if ($.inArray(alias, stepNavigator.validCodes) !== -1) {
                    throw new DOMException('Step code [' + alias + '] already registered in step navigator');
                }
                stepNavigator.validCodes.push(alias);
            }
            stepNavigator.validCodes.push(code);
            steps.push({
                code: code,
                alias: alias != null ? alias : code,
                title: title,
                isVisible: isVisible,
                navigate: navigate,
                sortOrder: sortOrder
            });
            active = stepNavigator.getActiveItemIndex();
            steps.each(function (elem, index) {
                if (active !== index && elem.code !== 'payment') {
                    elem.isVisible(false);
                }
            });
            stepNavigator.stepCodes.push(code);
            hash = window.location.hash.replace('#', '');

            if (hash != '' && hash != code) {
                isVisible(false);
            }
        }
    };
});
