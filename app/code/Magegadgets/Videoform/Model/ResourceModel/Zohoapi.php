<?php
namespace Magegadgets\Videoform\Model\ResourceModel;

class Zohoapi extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('zoho_api', 'id');
    }
}
?>