<?php

namespace Magegadgets\Loosediamonds\Model\ResourceModel\Loosediamonds;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magegadgets\Loosediamonds\Model\Loosediamonds', 'Magegadgets\Loosediamonds\Model\ResourceModel\Loosediamonds');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>