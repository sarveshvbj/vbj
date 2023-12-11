<?php

namespace Vaibhav\Tryathome\Model\ResourceModel\Tryathome;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct() {
        
        $this->_init('Vaibhav\Tryathome\Model\Tryathome',
                    'Vaibhav\Tryathome\Model\ResourceModel\Tryathome');
    }
}
