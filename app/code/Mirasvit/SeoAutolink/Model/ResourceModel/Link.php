<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-seo
 * @version   2.6.1
 * @copyright Copyright (C) 2023 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\SeoAutolink\Model\ResourceModel;

class Link extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\Context
     */
    protected $context;

    /**
     * @var string
     */
    protected $resourcePrefix;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string                                            $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $resourcePrefix = null
    ) {
        $this->context = $context;
        $this->resourcePrefix = $resourcePrefix;
        parent::__construct($context, $resourcePrefix);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mst_seoautolink_link', 'link_id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\Model\AbstractModel
     */
    public function loadStore(\Magento\Framework\Model\AbstractModel $object)
    {
        $select = $this->getConnection()->select()
            ->from($this->getTable('mst_seoautolink_link_store'))
            ->where('link_id = ?', $object->getId());

        if ($data = $this->getConnection()->fetchAll($select)) {
            $array = [];
            foreach ($data as $row) {
                $array[] = $row['store_id'];
            }
            $object->setData('store_ids', $array);
        }

        return $object;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     */
    protected function saveStore($object)
    {
        $condition = $this->getConnection()->quoteInto('link_id = ?', $object->getId());
        $this->getConnection()->delete($this->getTable('mst_seoautolink_link_store'), $condition);
        foreach ((array) $object->getData('store_ids') as $store) {
            $storeArray = [
                'link_id' => $object->getId(),
                'store_id' => $store,
            ];
            $this->getConnection()->insert(
                $this->getTable('mst_seoautolink_link_store'),
                $storeArray
            );
        }
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$object->getIsMassDelete()) {
            $this->loadStore($object);
        }

        return parent::_afterLoad($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->isObjectNew() && !$object->hasCreatedAt()) {
            $object->setCreatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
        }
        $object->setUpdatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
        if ($object->getActiveTo()) {
            $object->setActiveTo(str_replace('00:00:00', '23:59:59', $object->getActiveTo())); //include full day
        }
        if ($object->getActiveTo() == '') {
            $object->setActiveTo(null);
        }

        if ($object->getActiveFrom() == '') {
            $object->setActiveFrom(null);
        }

        return parent::_beforeSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$object->getIsMassStatus()) {
            $this->saveStore($object);
        }

        return parent::_afterSave($object);
    }
}
