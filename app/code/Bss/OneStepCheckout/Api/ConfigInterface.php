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
 * @category  BSS
 * @package   Bss_OneStepCheckout
 * @author    Extension Team
 * @copyright Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license   http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\OneStepCheckout\Api;

/**
 * Interface ConfigInterface
 *
 * @api
 * @package Bss\OneStepCheckout\Api
 */
interface ConfigInterface
{
    /**
     * Get all module config
     *
     * @param string $storeId
     * @return \Bss\OneStepCheckout\Api\Data\ConfigDataInterface
     */
    public function getAllConfig($storeId = null);
}
