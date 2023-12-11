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

namespace Bss\ProductsWidgetSlider\Model\ResourceModel\BestSellerProduct;

/**
 * Class GetBestSellerCollection
 *
 * @package Bss\ProductsWidgetSlider\Model\ResourceModel\BestSellerProduct
 */
class GetBestSellerCollection
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * GetBestSellerCollection constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManagerBss
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManagerBss
    ) {
        $this->storeManager = $storeManagerBss;
    }

    /**
     * @param string $dateRange
     * @param int $webSiteId
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $bestSellerCollection
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBestSellerCollection($dateRange, $webSiteId, $collection, $bestSellerCollection)
    {
        // @codingStandardsIgnoreStart
        if ($webSiteId == 0) {
            $bestSellerCollection->getSelect()
                ->joinRight(
                    ['aggregation' => $bestSellerCollection->getResource()
                        ->getTable('sales_bestsellers_aggregated_daily')],
                    "e.entity_id = aggregation.product_id AND aggregation.store_id > 0 {$dateRange}",
                    ['aggregation.qty_ordered AS sold_quantity','product_price']
                )->where('e.entity_id != "null"')
                ->group('e.entity_id')
                ->joinLeft(
                    ['link' => $bestSellerCollection->getResource()->getTable('catalog_product_super_link')],
                    "e.entity_id = link.product_id",
                    'link.parent_id'
                )
                ->joinLeft(
                    ['bundle' => $bestSellerCollection->getResource()->getTable('catalog_product_bundle_selection')],
                    "e.entity_id = bundle.product_id",
                    'bundle.parent_product_id'
                )
                ->joinLeft(
                    ['grouped' => $bestSellerCollection->getResource()->getTable('catalog_product_link')],
                    "e.entity_id = grouped.linked_product_id",
                    'grouped.product_id'
                );
        } else {
            $storeIds = $this->storeManager->getWebsite($webSiteId)->getStoreIds();
            $string = implode(",", $storeIds);
            $bestSellerCollection->getSelect()
                ->joinRight(
                    ['aggregation' => $bestSellerCollection->getResource()
                        ->getTable('sales_bestsellers_aggregated_daily')],
                    "e.entity_id = aggregation.product_id AND aggregation.store_id IN ({$string}) {$dateRange}",
                    ['SUM(aggregation.qty_ordered) AS sold_quantity']
                )->where('e.entity_id != "null"')
                ->group('e.entity_id')
                ->joinLeft(
                    ['link' => $bestSellerCollection->getResource()->getTable('catalog_product_super_link')],
                    "e.entity_id = link.product_id",
                    'link.parent_id'
                )
                ->joinLeft(
                    ['bundle' => $bestSellerCollection->getResource()->getTable('catalog_product_bundle_selection')],
                    "e.entity_id = bundle.product_id",
                    'bundle.parent_product_id'
                )
                ->joinLeft(
                    ['grouped' => $bestSellerCollection->getResource()->getTable('catalog_product_link')],
                    "e.entity_id = grouped.linked_product_id",
                    'grouped.product_id'
                );
        }
        $subQuery = $bestSellerCollection->getSelect();
        $collection->getSelect()->joinRight(
            ['BestSeller' => $subQuery],
            'e.entity_id = BestSeller.entity_id
            ',
            ['BestSeller.sold_quantity','BestSeller.parent_product_id','BestSeller.parent_id','BestSeller.product_price']
        )->distinct(true);
        return $collection;
    }
}
