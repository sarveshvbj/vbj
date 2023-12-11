<?php
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

namespace Bss\OneStepCheckout\Api\Data;

/**
 * Interface UpdateItemDetailsInterface
 * @api
 */
interface UpdateItemDetailsInterface
{
    /**
     * Constants defined for keys of array, makes typos less likely
     */
    const PAYMENT_METHODS = 'payment_methods';

    const TOTALS = 'totals';

    const SHIPPING_METHODS = 'shipping_methods';

    const MESSAGE = 'message';

    const STATUS = 'status';

    const HAS_ERROR = 'has_error';

    const GIFT_WRAP_DISPLAY = 'gift_wrap_display';

    const GIFT_WRAP_LABEL = 'gift_wrap_label';

    const QTY_BEFORE = 'qty_before';

    /**
     * @return \Magento\Quote\Api\Data\PaymentMethodInterface[]
     */
    public function getPaymentMethods();

    /**
     * @param \Magento\Quote\Api\Data\PaymentMethodInterface[] $paymentMethods
     * @return $this
     */
    public function setPaymentMethods($paymentMethods);

    /**
     * @return \Magento\Quote\Api\Data\TotalsInterface
     */
    public function getTotals();

    /**
     * @param \Magento\Quote\Api\Data\TotalsInterface $totals
     * @return $this
     */
    public function setTotals($totals);

    /**
     * @return \Magento\Quote\Api\Data\ShippingMethodInterface[]
     */
    public function getShippingMethods();

    /**
     * @param \Magento\Quote\Api\Data\ShippingMethodInterface[] $shippingMethods
     * @return $this
     */
    public function setShippingMethods($shippingMethods);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * @return bool
     */
    public function getStatus();

    /**
     * @param bool $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return bool
     */
    public function getHasError();

    /**
     * @param bool $error
     * @return $this
     */
    public function setHasError($error);

    /**
     * @return bool
     */
    public function getGiftWrapDisplay();

    /**
     * @param bool $display
     * @return $this
     */
    public function setGiftWrapDisplay($display);

    /**
     * @return string
     */
    public function getGiftWrapLabel();

    /**
     * @param string $label
     * @return $this
     */
    public function setGiftWrapLabel($label);

    /**
     * @return int|float
     */
    public function getQtyBefore();

    /**
     * @param int|float $qtyBefore
     * @return $this
     */
    public function setQtyBefore($qtyBefore);
}
