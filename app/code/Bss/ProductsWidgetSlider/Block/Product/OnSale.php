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

namespace Bss\ProductsWidgetSlider\Block\Product;

use Bss\ProductsWidgetSlider\Block\GetData;
use Bss\ProductsWidgetSlider\Model\ResourceModel\OnSaleProduct\GetOnSaleCollection;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

/**
 * Class OnSale
 *
 * @package Bss\ProductsWidgetSlider\Block\Product
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OnSale extends GetData
{
    /**
     * @var GetOnSaleCollection
     */
    protected $getOnSaleCollection;

    /**
     * Create Collection
     *
     * @return Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createCollection()
    {
        $this->setCache();
        /** @var $collection Collection */
        $showOutOfStock = $this->isShowOutOfStock();
        $order = $this->getSortOrder();
        $sortBy = $this->getSortBy();
        $collection = $this->productCollectionFactory->create();
        $onSaleCollection = $this->productCollectionFactory->create();
        $collection->setFlag('has_stock_status_filter', true);
        $collection = $this->getStockStatus($collection, $showOutOfStock);
        $collection = $this->getData('getOnSaleCollection')->getOnSaleCollection($collection, $onSaleCollection);
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());
        $collection->getSelectCountSql($collection, false);
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addAttributeToSelect('*')
            ->setPageSize($this->getPageSize())
            ->setCurPage($this->getRequest()->getParam($this->getData('page_var_name'), 1));
        $conditions = $this->getConditions();
        $conditions->collectValidatedAttributes($collection);
        $this->sqlBuilder->attachConditionToCollection($collection, $conditions);
        if ($sortBy == "name") {
            $collection = $this->_addProductAttributesAndPrices($collection)->addAttributeToSort('name', $order);
        } else {
            $collection->getSelect()->order($sortBy . ' ' . $order);
        }
        return $collection;
    }
}
