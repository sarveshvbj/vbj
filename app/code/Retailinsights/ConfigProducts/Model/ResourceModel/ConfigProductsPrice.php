<?php
namespace Retailinsights\ConfigProducts\Model\ResourceModel;

class ConfigProductsPrice extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('custom_config_products_price', 'id');
    }
}
?>