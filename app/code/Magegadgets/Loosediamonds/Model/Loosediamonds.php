<?php
namespace Magegadgets\Loosediamonds\Model;

class Loosediamonds extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magegadgets\Loosediamonds\Model\ResourceModel\Loosediamonds');
    }
}
?>