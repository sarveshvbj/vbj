<?php

namespace Retailinsights\ConfigProducts\Model\ResourceModel\ConfigProducts;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Retailinsights\ConfigProducts\Model\ConfigProducts', 'Retailinsights\ConfigProducts\Model\ResourceModel\ConfigProducts');
    }

}
?>