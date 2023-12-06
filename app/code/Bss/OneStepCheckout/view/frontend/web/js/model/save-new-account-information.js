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
    'uiComponent',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/url'
], function (
    $,
    ko,
    Component,
    fullScreenLoader,
    urlBuilder
) {
    return {
        ajaxSave: function () {
            var createNewAccountCheckBoxId = 'create-new-customer',
                loginFormSelector = 'form[data-role=email-with-possible-login]';
            var data = {};
            if ($(loginFormSelector + ' #' + createNewAccountCheckBoxId).is(":checked")) {
                data['email'] = $(loginFormSelector + ' input[name=username]').val();
                data['pass'] = $(loginFormSelector + ' input[name=newcustomerpassword]').val();
                data['confirmpass'] = $(loginFormSelector + ' input[name=newcustomerpassword_confirmation]').val();
                var saveUrl = 'onestepcheckout/account/save';

                // If condition prevent dispatch more than one time while ajax doing, this make improve performance
                // Reason: Using interval to run validate
                // additionalValidators.validate() file Bss_OneStepCheckout/js/view/place-order-btn.js
                if (undefined === window.isDispatchedRequestCreateCustomer ||
                    !window.isDispatchedRequestCreateCustomer) {
                    fullScreenLoader.startLoader();
                    window.isDispatchedRequestCreateCustomer = true;
                    $.ajax({
                        url: urlBuilder.build(saveUrl),
                        data: data,
                        type: 'post',
                        dataType: 'json',

                        /** @inheritdoc */
                        success: function (response) {
                            // Set true to dispatch an event, that tell we should clear interval
                            window.ajaxDoSavedCustomer = true;
                            window.isDispatchedRequestCreateCustomer = false;
                            fullScreenLoader.stopLoader();
                        },

                        /** @inheritdoc */
                        error: function (res) {
                            // Set true to dispatch an event, that tell we should clear interval
                            window.ajaxDoSavedCustomer = true;
                            window.isDispatchedRequestCreateCustomer = false;
                            fullScreenLoader.stopLoader();
                            alert({
                                content: $.mage.__('Sorry, something went wrong. Please try again later.')
                            });
                        }
                    });
                }
            } else {
                // If guest does not require create an account.
                // We should better clear interval
                // Set true to dispatch an event, that tell we should clear interval
                window.ajaxDoSavedCustomer = true;
                window.isDispatchedRequestCreateCustomer = false;
            }
        }
    };
});
