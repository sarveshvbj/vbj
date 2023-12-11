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

namespace Bss\ProductsWidgetSlider\Model\ResourceModel\MostViewProduct;

/**
 * Class GetMostViewCollection
 * @package Bss\ProductsWidgetSlider\Model\ResourceModel\MostViewProduct
 */
class GetMostViewCollection
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * GetMostViewCollection constructor.
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
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $mostViewedCollection
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMostViewCollection($dateRange, $webSiteId, $collection, $mostViewedCollection)
    {
        // @codingStandardsIgnoreStart
        if ($webSiteId == 0) {
            $mostViewedCollection->getSelect()->joinRight(
                ['aggregation' => $mostViewedCollection->getResource()
                    ->getTable('report_viewed_product_aggregated_daily')],
                "e.entity_id = aggregation.product_id AND aggregation.store_id > 0 {$dateRange}",
                ['SUM(aggregation.views_num) AS views']
            )->where('e.entity_id != "null"')->group('e.entity_id');
        } else {
            $storeIds = $this->storeManager->getWebsite($webSiteId)->getStoreIds();
            $string = implode(",", $storeIds);
            $mostViewedCollection->getSelect()->joinRight(
                ['aggregation' => $mostViewedCollection->getResource()
                    ->getTable('report_viewed_product_aggregated_daily')],
                "e.entity_id = aggregation.product_id AND aggregation.store_id IN ({$string}) {$dateRange}",
                ['SUM(aggregation.views_num) AS views']
            )->where('e.entity_id != "null"')->group('e.entity_id');
        }
        $subQuery = $mostViewedCollection->getSelect();

        $collection->getSelect()->joinRight(
            ['MostViewed' => $subQuery],
            'e.entity_id = MostViewed.entity_id',
            'MostViewed.views'
        );
        // @codingStandardsIgnoreStart

        return $collection;
    }
}
