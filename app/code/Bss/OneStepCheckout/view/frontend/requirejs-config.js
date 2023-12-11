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
 * @copyright Copyright (c) 2017-2022 BSS Commerce Co. ( http://bsscommerce.com )
 * @license   http://bsscommerce.com/Bss-Commerce-License.txt
 */
var config = {
    map: {
        '*': {
            'Magento_Checkout/template/billing-address/form.html':
                'Bss_OneStepCheckout/template/billing-address/form.html',
            'Magento_Checkout/js/model/shipping-rate-service':
                'Bss_OneStepCheckout/js/model/shipping-rate-service',
            'Magento_Checkout/js/action/get-payment-information':
                'Bss_OneStepCheckout/js/action/get-payment-information',
            'Magento_SalesRule/js/action/cancel-coupon':
                'Bss_OneStepCheckout/js/action/cancel-coupon',
            'Magento_Checkout/js/action/set-payment-information-extended':
                'Bss_OneStepCheckout/js/action/set-payment-information-extended'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'Bss_OneStepCheckout/js/model/place-order-mixin': true,
                'Magento_CheckoutAgreements/js/model/place-order-mixin': false
            },
            'Magento_Checkout/js/model/step-navigator': {
                'Bss_OneStepCheckout/js/model/step-navigator-mixin': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'Bss_OneStepCheckout/js/model/set-payment-information-mixin': true,
                'Magento_CheckoutAgreements/js/model/set-payment-information-mixin': false
            },
            'Magento_Checkout/js/model/shipping-rates-validation-rules': {
                'Bss_OneStepCheckout/js/model/shipping-rates-validation-rules-mixin': true
            },
            'Magento_Paypal/js/in-context/express-checkout-wrapper': {
                'Bss_OneStepCheckout/js/in-context/express-checkout-wrapper-mixin': true
            },
            'Magento_Paypal/js/view/payment/method-renderer/in-context/checkout-express': {
                'Bss_OneStepCheckout/js/view/payment/method-renderer/in-context/checkout-express-mixin': true
            },
            'Magento_Braintree/js/view/payment/method-renderer/paypal': {
                'Bss_OneStepCheckout/js/view/payment/method-renderer/paypal-mixin': true
            },
            'PayPal_Braintree/js/view/payment/method-renderer/paypal': {
                'Bss_OneStepCheckout/js/view/payment/method-renderer/paypal-braintree-mixin': true
            },
            'Magento_Checkout/js/model/error-processor': {
                'Bss_OneStepCheckout/js/model/error-processor-mixin': true
            },
            'Magento_Checkout/js/action/select-shipping-address': {
                'Bss_OneStepCheckout/js/action/select-shipping-address-mixin': true
            },
            'Amazon_Payment/js/view/payment/list': {
                'Bss_OneStepCheckout/js/view/amazon-pay/payment-list-mixin': true
            },
            'Amazon_Payment/js/view/checkout-revert': {
                'Bss_OneStepCheckout/js/view/amazon-pay/checkout-revert-rewrite': true
            },
            'Adyen_Payment/js/view/payment/method-renderer/adyen-cc-method': {
                'Bss_OneStepCheckout/js/view/adyen-pay/adyen-cc-method': true
            },
            'Magento_Checkout/js/action/select-billing-address': {
                'Bss_OneStepCheckout/js/action/select-billing-address-mixin': true
            },
            'Magento_Checkout/js/view/shipping-address/address-renderer/default': {
                'Bss_OneStepCheckout/js/view/shipping-address/address-renderer/default-mixin': true
            }
        }
    }
};
