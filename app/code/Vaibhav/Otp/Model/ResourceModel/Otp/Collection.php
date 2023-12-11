<?php

namespace Vaibhav\Otp\Model\ResourceModel\Otp;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct() {
        
        $this->_init('Vaibhav\Otp\Model\Otp',
                    'Vaibhav\Otp\Model\ResourceModel\Otp');
    }
}
