<?php
namespace Magegadgets\Stonemanager\Model\ResourceModel;

class Stonemanager extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('stone_manager', 'id');
    }
}
?>