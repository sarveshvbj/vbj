<?php

namespace Vaibhav\Tryathome\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Tryathome extends AbstractDb {

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context, 
        \Magento\Framework\Stdlib\DateTime\DateTime $date, 
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
        
    }
    
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('vaibhav_tryathome', 'id');
    }
    /**
     * Process post data before saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object) {

        if ($object->isObjectNew() && !$object->hasCreationTime()) {
            $object->setCreatedAt($this->_date->gmtDate());
        }
        return parent::_beforeSave($object);
    }

}
