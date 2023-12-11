<?php

namespace Als\Testimonials\Model\ResourceModel\Testimonials;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct() {
        
        $this->_init('Als\Testimonials\Model\Testimonials',
                    'Als\Testimonials\Model\ResourceModel\Testimonials');
    }
}
