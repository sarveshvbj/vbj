<?php
namespace Magegadgets\Stonemanager\Model;

class Stonemanager extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magegadgets\Stonemanager\Model\ResourceModel\Stonemanager');
    }
}
?>