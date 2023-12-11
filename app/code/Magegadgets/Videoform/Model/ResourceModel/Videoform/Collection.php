<?php

namespace Magegadgets\Videoform\Model\ResourceModel\Videoform;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magegadgets\Videoform\Model\Videoform', 'Magegadgets\Videoform\Model\ResourceModel\Videoform');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>