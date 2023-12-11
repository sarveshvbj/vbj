<?php

namespace Magegadgets\Videoform\Model\ResourceModel\Zohoapi;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magegadgets\Videoform\Model\Zohoapi', 'Magegadgets\Videoform\Model\ResourceModel\Zohoapi');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>