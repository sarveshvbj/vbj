<?php
namespace Magegadgets\Loosediamonds\Model\ResourceModel;

class Loosediamonds extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('loose_diamonds', 'id');
    }
}
?>