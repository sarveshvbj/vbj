<?php
namespace Magegadgets\Videoform\Model\ResourceModel;

class Videoform extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('video_form', 'id');
    }
}
?>