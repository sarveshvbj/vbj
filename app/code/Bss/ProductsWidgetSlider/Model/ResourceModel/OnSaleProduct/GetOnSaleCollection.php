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

namespace Bss\ProductsWidgetSlider\Model\ResourceModel\OnSaleProduct;

class GetOnSaleCollection
{
    /**
     * @var \Magento\CatalogWidget\Block\Product\ProductsList
     */
    protected $productsList;

    /**
     * GetOnSaleCollection constructor.
     * @param \Magento\CatalogWidget\Block\Product\ProductsList $productsList
     */
    public function __construct(
        \Magento\CatalogWidget\Block\Product\ProductsList $productsList,
        \Magento\Catalog\Model\ResourceModel\Product $productResource
    ) {
        $this->productsList = $productsList;
        $this->productResource = $productResource;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $onSaleCollection
     * @return mixed
     */
    public function getOnSaleCollection($collection, $onSaleCollection)
    {
        // @codingStandardsIgnoreStart
        $linkedFieldIdCategory = $this->productResource->getLinkField();
        $onSaleCollection->getSelect()
            ->joinRight(
                ['deci' => $onSaleCollection->getResource()->getTable('catalog_product_entity_decimal')],
                "e.row_id = deci." . $linkedFieldIdCategory,
                'deci.value_id'
            )
            ->where("e.row_id != 'NULL' AND deci.attribute_id
            IN (SELECT `attribute_id` FROM {$onSaleCollection->getResource()->getTable('eav_attribute')}
             WHERE `attribute_code` = 'special_price')
                                        AND deci.value_id != 'NULL'
                                        AND e.entity_id
                                        IN (SELECT `entity_id` FROM {$onSaleCollection->getResource()
                                        ->getTable('catalog_product_index_price')}
                                        WHERE `final_price` < `price` )")
            ->group('e.entity_id')
            ->joinLeft(
                ['link' => $onSaleCollection->getResource()->getTable('catalog_product_super_link')],
                "e.entity_id = link.product_id",
                'link.parent_id'
            )
            ->joinLeft(
                ['bundle' => $onSaleCollection->getResource()->getTable('catalog_product_bundle_selection')],
                "e.entity_id = bundle.product_id",
                'bundle.parent_product_id'
            )
            ->joinLeft(
                ['grouped' => $onSaleCollection->getResource()->getTable('catalog_product_link')],
                "e.entity_id = grouped.linked_product_id",
                'grouped.product_id'
            );
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite()
            ->addStoreFilter();
        $subQuery = $onSaleCollection->getSelect();
        $collection->getSelect()->joinRight(
            ['OnSale' => $subQuery],
            'e.entity_id = OnSale.entity_id
            OR e.row_id = OnSale.parent_id
            OR e.row_id = OnSale.parent_product_id
            OR e.row_id = OnSale.product_id',
            ['(price-final_price) AS fix', '(100-ROUND((final_price/price)*100)) AS percent']
        )->distinct(true);
        // @codingStandardsIgnoreEnd
        return $collection;
    }
}
