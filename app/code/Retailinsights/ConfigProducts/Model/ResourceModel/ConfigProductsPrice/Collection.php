<?php

namespace Retailinsights\ConfigProducts\Model\ResourceModel\ConfigProductsPrice;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Retailinsights\ConfigProducts\Model\ConfigProductsPrice', 'Retailinsights\ConfigProducts\Model\ResourceModel\ConfigProductsPrice');
    }

}
?>