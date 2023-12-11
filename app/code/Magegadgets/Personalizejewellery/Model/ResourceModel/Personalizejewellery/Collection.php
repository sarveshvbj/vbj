<?php

namespace Magegadgets\Personalizejewellery\Model\ResourceModel\Personalizejewellery;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magegadgets\Personalizejewellery\Model\Personalizejewellery', 'Magegadgets\Personalizejewellery\Model\ResourceModel\Personalizejewellery');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>