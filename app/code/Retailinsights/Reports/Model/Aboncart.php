<?php
namespace Retailinsights\Reports\Model;

class Aboncart extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Retailinsights\Reports\Model\ResourceModel\Aboncart');
    }
}
?>