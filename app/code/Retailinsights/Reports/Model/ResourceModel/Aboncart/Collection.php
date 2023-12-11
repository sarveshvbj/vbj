<?php

namespace Retailinsights\Reports\Model\ResourceModel\Aboncart;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Retailinsights\Reports\Model\Aboncart', 'Retailinsights\Reports\Model\ResourceModel\Aboncart');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>