<?php
namespace Magegadgets\Videoform\Model\ResourceModel;

class Aboncart extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('abondoned_customer_cart', 'id');
    }
}
?>