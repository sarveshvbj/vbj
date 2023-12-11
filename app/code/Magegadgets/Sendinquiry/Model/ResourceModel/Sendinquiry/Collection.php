<?php

namespace Magegadgets\Sendinquiry\Model\ResourceModel\Sendinquiry;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magegadgets\Sendinquiry\Model\Sendinquiry', 'Magegadgets\Sendinquiry\Model\ResourceModel\Sendinquiry');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>