<?php

namespace Als\Testimonials\Block\Product\View;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;

/**
 * Class CheckDelivery
 * @package MageArray\CheckDelivery\Block\Product\View
 */
class Notify extends Template
{
    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * CheckDelivery constructor.
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_registry = $registry;
        $this->stockItem = $stockItem;
    }

    /**
     * @return mixed
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }
     public function getCurrentProductQty($productId, $productWebsiteId)
    {
        return $this->stockItem->getStockQty($productId, $productWebsiteId);
    }
}