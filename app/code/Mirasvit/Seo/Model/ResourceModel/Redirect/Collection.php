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



namespace Mirasvit\Seo\Model\ResourceModel\Redirect;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection implements
    \Magento\Framework\Option\ArrayInterface
{
    /**
     * Use in mass action
     * @var string
     */
    protected $_idFieldName = 'redirect_id';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Data\Collection\EntityFactoryInterface
     */
    protected $entityFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Data\Collection\Db\FetchStrategyInterface
     */
    protected $fetchStrategy;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    protected $resource;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface                   $storeManager
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface    $entityFactory
     * @param \Psr\Log\LoggerInterface                                     $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface                    $eventManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface               $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb         $resource
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->storeManager = $storeManager;
        $this->entityFactory = $entityFactory;
        $this->logger = $logger;
        $this->fetchStrategy = $fetchStrategy;
        $this->eventManager = $eventManager;
        $this->connection = $connection;
        $this->resource = $resource;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Seo\Model\Redirect', 'Mirasvit\Seo\Model\ResourceModel\Redirect');
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $this->addFieldToFilter('is_active', 1)
            ->setOrder('sort_order', 'asc');

        return $this->_toOptionArray('redirect_id');
    }

    /**
     * @return $this
     */
    public function addActiveFilter()
    {
        $this->addFieldToFilter('is_active', 1);

        return $this;
    }

    /**
     * @param \Magento\Store\Model\Store $store
     * @return $this
     */
    public function addStoreFilter($store)
    {
        //        if (!$this->context->getStoreManager()->isSingleStoreMode()) {
        if ($store instanceof \Magento\Store\Model\Store) {
            $store = [$store->getId()];
        }

        $this->getSelect()
                ->joinLeft(
                    ['store_table' => $this->getTable('mst_seo_redirect_store')],
                    'main_table.redirect_id = store_table.redirect_id',
                    []
                )
                ->where('store_table.store_id in (?)', [0, $store]);

        return $this;
    }

    /**
     * @return $this
     */
    public function addStoreColumn()
    {
        $this->getSelect()
            ->columns(
                ['store_id' => new \Zend_Db_Expr(
                    "(SELECT GROUP_CONCAT(store_id) FROM `{$this->getTable('mst_seo_redirect_store')}`
                    AS `seo_redirect_store_table`
                    WHERE main_table.redirect_id = seo_redirect_store_table.redirect_id)"
                )]
            );

        return $this;
    }
}
