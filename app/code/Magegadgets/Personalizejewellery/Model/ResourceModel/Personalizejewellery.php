<?php
namespace Magegadgets\Personalizejewellery\Model\ResourceModel;

class Personalizejewellery extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('personalize_jewellery', 'id');
    }
}
?>