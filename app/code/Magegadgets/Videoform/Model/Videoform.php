<?php
namespace Magegadgets\Videoform\Model;

class Videoform extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magegadgets\Videoform\Model\ResourceModel\Videoform');
    }
}
?>