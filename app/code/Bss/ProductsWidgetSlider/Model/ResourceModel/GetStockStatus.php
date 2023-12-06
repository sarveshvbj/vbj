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

class GetStockStatus
{
    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param bool $showOutOfStock
     * @return mixed
     */
    public function getStockStatus($collection, $showOutOfStock)
    {
        if ($showOutOfStock) {
            $collection->getSelect()->joinRight(
                ['stock' => $collection->getResource()->getTable('cataloginventory_stock_status')],
                'stock.product_id = e.entity_id',
                ['stock_status']
            )
                ->where("`stock`.`stock_status` = 1")
            ->orWhere("`stock`.`stock_status` = 0");
        } else {
            $collection->getSelect()->joinRight(
                ['stock' => $collection->getResource()->getTable('cataloginventory_stock_status')],
                'stock.product_id = e.entity_id',
                ['stock_status']
            )->where("`stock`.`stock_status` = 1");
        }
        return $collection;
    }
}
