<?php

namespace Magegadgets\Stonemanager\Model\ResourceModel\Stonemanager;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magegadgets\Stonemanager\Model\Stonemanager', 'Magegadgets\Stonemanager\Model\ResourceModel\Stonemanager');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>