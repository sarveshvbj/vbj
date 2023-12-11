<?php
namespace Magegadgets\Sendinquiry\Model;

class Sendinquiry extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magegadgets\Sendinquiry\Model\ResourceModel\Sendinquiry');
    }
}
?>