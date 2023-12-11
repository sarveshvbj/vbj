<?php
namespace Magegadgets\Sendinquiry\Model\ResourceModel;

class Sendinquiry extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('send_inquiry', 'id');
    }
}
?>