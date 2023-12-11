<?php

namespace Als\Testimonials\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

class Testimonials extends AbstractModel implements  IdentityInterface
{
    const CREATED_AT = 'created_at';
    const ID = 'id';
    
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'testimonials_testimonials';

    public function _construct() {
        
        $this->_init('Als\Testimonials\Model\ResourceModel\Testimonials');
    }
    
    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    
    /**
     * Set creation time
     *
     * @param string $created_at
     * @return 
     */
    public function setCreatedAt($created_at)
    {
        return $this->setData(self::CREATED_AT, $created_at);
    }
    
    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
