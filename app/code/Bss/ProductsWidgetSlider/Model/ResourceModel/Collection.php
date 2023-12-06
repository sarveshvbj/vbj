<?php
/**
 * Bss Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   Bss
 * @package    Bss_ProductsWidgetSlider
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 Bss Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\ProductsWidgetSlider\Model\ResourceModel;

/**
 * Class Collection
 * @package Bss\ProductsWidgetSlider\Model\ResourceModel
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class Collection extends \Magento\Catalog\Model\ResourceModel\Product\Collection
{
    /**
     * @return \Magento\Framework\DB\Select
     */
    public function getSelectCountSql()
    {
        return $this->_getSelectCountSql()->reset(\Magento\Framework\DB\Select::GROUP);
    }
}
